<?php

include_once __dir__ . "/../../config/siteInfo.php";
include_once __dir__ . "/../generalLibs/code01.php";

class CUserManger
{
	public $dbResult = null;
    
	public function __construct()
	{
        session_start();
	}
	public function __destruct()
	{

    }
    
	public function checkUserInfo($_userName, $_passWord)
	{
        global $swtSiteAuthInfo;
        
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
                    return true;
                }
            }
        }
        
        return false;
    }
    
	public function logIn($_userName, $_passWord)
	{
        if ($this->checkUserInfo($_userName, $_passWord) == false)
        {
            return false;
        }
        $this->doLogIn($_userName, $_passWord);
        return true;
    }
    
	public function tryLogIn()
	{
        if (isset($_SESSION["managerLoged"]) && ($_SESSION["managerLoged"] == true))
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
    
	public function doLogIn($_userName, $_passWord)
	{
        $_SESSION["userName"] = $_userName;
        $_SESSION["passWord"] = $_passWord;
        $_SESSION["managerLoged"] = true;
        setcookie("userName", $_userName, time() + 3600 * 24 * 365, "/");
        setcookie("passWord", $_passWord, time() + 3600 * 24 * 365, "/");
    }
    
	public function logOut()
	{
        //$_SESSION["userName"] = "";
        $_SESSION["passWord"] = "";
        $_SESSION["managerLoged"] = false;
        //setcookie("userName", "", time() - 3600, "./");
        setcookie("passWord", "", time() - 3600, "/");
    }
}

?>