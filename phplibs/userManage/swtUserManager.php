<?php

session_start();

include_once __dir__ . "/../../config/siteInfo.php";
include_once __dir__ . "/../generalLibs/code01.php";
include_once __dir__ . "/../generalLibs/dopdo.php";
include_once __dir__ . "/../generalLibs/genfuncs.php";

class CUserManger
{
	public $dbResult = null;
    
	public function __construct()
	{
        //session_start();
	}
	public function __destruct()
	{

    }
    
	public function ldapCheckUserInfo($_userName, $_passWord)
	{
        global $returnMsg;
        
        $returnMsg["errorCode"] = 1;
        $returnMsg["errorMsg"] = "LDAP login success";
        
        $conn = @ldap_connect('amd.com');
        if ($conn == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "can't connect LDAP server.";
            return false;
        }
		
		@ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

        $tmpUserName = $_userName;
        if (strpos($_userName, "\\") === false)
        {
            $tmpUserName = "amd\\" . $_userName;
        }
        
        $tmpResult = @ldap_bind($conn, $tmpUserName, $_passWord);
        if ($tmpResult == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "can't bind with LDAP server.";
			$returnMsg["errorMsg"] = "LDAP can't log in. " . ldap_error($conn) . ", username: " . $tmpUserName;
            return false;
        }

        if(@ldap_errno($conn) != 0)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "LDAP can't log in. " . ldap_error($conn);
            return false;
        }

        @ldap_unbind($conn);
        return true;
    }
    
	public function checkUserInfo($_userName, $_passWord)
	{
        global $swtSiteAuthInfo;
        global $returnMsg;
        
        if ((strlen($_userName) == 0) ||
            (strlen($_passWord) == 0))
        {
            return false;
        }
        
        $tmpKeys = array_keys($swtSiteAuthInfo, $_userName);
        foreach ($tmpKeys as $key)
        {
            // if username
            if ((intval($key) % 2) == 0)
            {
                if (strcmp($swtSiteAuthInfo[intval($key) + 1], $_passWord) == 0)
                {
                    return 1;
                }
            }
        }
        if (count($tmpKeys) > 0)
        {
            // invalid manager password
            return false;
        }
        
        //$returnMsg = array();
        $returnMsg["errorCode"] = 1;
        $returnMsg["errorMsg"] = "query MySQL success";
        
        
        if ($this->ldapCheckUserInfo($_userName, $_passWord) == false)
        {
                $returnMsg["errorCode"] = 0;
                //$returnMsg["errorMsg"] = "can't verify LDAP credential, line: " . __LINE__;
                //echo json_encode($returnMsg);
                return false;
        }
        
        // not manager
        $db = new CPdoMySQL();
        if ($db->getError() != null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
            //echo json_encode($returnMsg);
            return false;
        }
        
        $userName = cleaninput($_userName, 128);
        //$passWord = md5($_passWord);
        $passWord = $_passWord;
        //$passWord = md5("123");
        
        $params1 = array($userName);
        $sql1 = "SELECT COUNT(*) FROM mis_table_user_info " .
                "WHERE user_name = ?";

        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            return false;
        }
        
        $row1 = $db->fetchRow();
        if ($row1 == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            return false;
        }
        
        if (intval($row1[0]) == 0)
        {
            // new user
            $params1 = array($userName, $passWord, "2");
            $sql1 = "INSERT INTO mis_table_user_info " .
                    "(user_name, pass_word, user_type, create_time, login_time) " .
                    "VALUES (?, ?, ?, NOW(), NOW())";
                    
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                return false;
            }
        }
        
        $params1 = array($userName, $passWord);
        $sql1 = "SELECT COUNT(*) FROM mis_table_user_info " .
                "WHERE user_name = ? AND pass_word = ?";

        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            return false;
        }
        
        $row1 = $db->fetchRow();
        if ($row1 == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            return false;
        }
        
        if (intval($row1[0]) > 0)
        {
            // login success
            // update login time
            $params1 = array($userName);
            $sql1 = "UPDATE mis_table_user_info " .
                    "SET login_time = NOW() " .
                    "WHERE user_name = ?";
            
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                return false;
            }
            
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "user login success";
            
            return true;
        }
        else
        {
            // login success
            // update login time
            $params1 = array($passWord, $userName);
            $sql1 = "UPDATE mis_table_user_info " .
                    "SET pass_word = ?, login_time = NOW() " .
                    "WHERE user_name = ?";
            
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                return false;
            }
            
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "user login success";
            
            return true;
        }
        
        return false;
    }
    
	public function logIn($_userName, $_passWord)
	{
        $tmpResult = $this->checkUserInfo($_userName, $_passWord);
        if ($tmpResult == false)
        {
            return false;
        }
        else if ($tmpResult === 1)
        {
            // is site manager
            $this->doManagerLogIn($_userName, $_passWord);
            return true;
        }
        // is common user
        $this->doLogIn($_userName, $_passWord);
        //$this->doLogIn($_userName, "123");
        return true;
    }
    
	public function tryLogIn()
	{
        //if (isset($_SESSION["managerLoged"]) && ($_SESSION["managerLoged"] == true))
        //{
        //    return true;
        //}
        
        if ($this->isUser())
        {
            return true;
        }
        
        $userName = isset($_COOKIE["userName"]) ? $_COOKIE["userName"] : "";
        $passWord = isset($_COOKIE["passWord"]) ? $_COOKIE["passWord"] : "";
        
        if ((strlen($userName) == 0) ||
            (strlen($passWord) == 0))
        {
            return false;
        }
        
        return $this->logIn($userName, $passWord);
    }
    
	public function isManager()
	{
        if (isset($_SESSION["managerLoged"]) && ($_SESSION["managerLoged"] == true))
        {
            return true;
        }
        return false;
    }
    
	public function isUser()
	{
        if (isset($_SESSION["userLoged"]) && ($_SESSION["userLoged"] == true))
        {
            return true;
        }
        return false;
    }
    
	public function getUserName()
	{
        if ($this->isUser() == false)
        {
            return "";
        }
        if (isset($_SESSION["userName"]))
        {
            if (strlen($_SESSION["userName"]) > 0)
            {
                return $_SESSION["userName"];
            }
        }
        if (isset($_COOKIE["userName"]))
        {
            if (strlen($_COOKIE["userName"]) > 0)
            {
                return $_COOKIE["userName"];
            }
        }
        return "";
    }
    
	public function getPassWord()
	{
        if ($this->isUser() == false)
        {
            return "";
        }
        if (isset($_SESSION["passWord"]))
        {
            if (strlen($_SESSION["passWord"]) > 0)
            {
                return $_SESSION["passWord"];
            }
        }
        if (isset($_COOKIE["passWord"]))
        {
            if (strlen($_COOKIE["passWord"]) > 0)
            {
                return $_COOKIE["passWord"];
            }
        }
        return "";
    }
    
	public function getUserID()
	{
        global $returnMsg;
        
        $returnMsg["tmpStep00"] = 1;
        if ($this->isUser() == false)
        {
            $returnMsg["tmpStep01"] = 1;
            return -1;
        }
        $userName = $this->getUserName();
        $passWord = $this->getPassWord();
        
        //$returnMsg = array();
        $returnMsg["errorCode"] = 1;
        $returnMsg["errorMsg"] = "query MySQL success";
        
        // not manager
        $db = new CPdoMySQL();
        if ($db->getError() != null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
            //echo json_encode($returnMsg);
            return -1;
        }
        
        $userName = cleaninput($userName, 128);
        //$passWord = md5($passWord);
        $passWord = $passWord;
        
        $params1 = array($userName, $passWord);
        $sql1 = "SELECT user_id FROM mis_table_user_info " .
                "WHERE user_name = ? AND pass_word = ?";

        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["tmpStep02"] = 1;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            return -1;
        }
        
        $row1 = $db->fetchRow();
        if ($row1 == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["tmpStep03"] = 1;
            $returnMsg["errorMsg"] = "invalid username or password";
            return -1;
        }
        return intval($row1[0]);
    }
    
	public function getSessionOrCookieInfo($_valName)
	{
        if (strlen($_valName) == 0)
        {
            return "";
        }
        $valCont = isset($_SESSION[$_valName]) ? $_SESSION[$_valName] : "";
        if (strlen($valCont) > 0)
        {
            return $valCont;
        }
        $valCont = isset($_COOKIE[$_valName]) ? $_COOKIE[$_valName] : "";

        return $valCont;
    }
    
	public function doManagerLogIn($_userName, $_passWord)
	{
        $_SESSION["userName"] = $_userName;
        $_SESSION["passWord"] = $_passWord;
        $_SESSION["managerLoged"] = true;
        $_SESSION["userLoged"] = true;
        setcookie("userName", $_userName, time() + 3600 * 24 * 365, "/");
        setcookie("passWord", $_passWord, time() + 3600 * 24 * 365, "/");
    }
    
	public function doLogIn($_userName, $_passWord)
	{
        $_SESSION["userName"] = $_userName;
        $_SESSION["passWord"] = $_passWord;
        $_SESSION["managerLoged"] = false;
        $_SESSION["userLoged"] = true;
        setcookie("userName", $_userName, time() + 3600 * 24 * 365, "/");
        setcookie("passWord", $_passWord, time() + 3600 * 24 * 365, "/");
    }
    
	public function logOut()
	{
        //$_SESSION["userName"] = "";
        $_SESSION["passWord"] = "";
        $_SESSION["managerLoged"] = false;
        $_SESSION["userLoged"] = false;
        //setcookie("userName", "", time() - 3600, "./");
        setcookie("passWord", "", time() - 3600, "/");
    }
}

?>