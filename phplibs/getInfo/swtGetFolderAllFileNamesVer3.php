<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../userManage/swtUserManager.php";

//$logFolderName = "\\\\oglserver\\Incoming\\Davy\\deletable\\benchMax\\code20160607\\benchMax\\logStore\\2016-03-29-man";
$batchID = intval($_POST["batchID"]);
$logFolderName = $_POST["logFolderName"];
$allFileListString = $_POST["allFileListString"];
$fileID = intval($_POST["fileID"]);
$parentFolder = $_POST["parentFolder"];
$parentFolderOnly = $_POST["parentFolderOnly"];
$username = cleaninput($_POST["username"], 32);
$password = cleaninput($_POST["password"], 32);

$allFileList = array();
if (strlen($allFileListString) > 0)
{
    $allFileList = explode(",", $allFileListString);
}

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "get filenames success";
$returnMsg["allFileList"] = array();
$returnMsg["copyFileFinished"] = 0;

//$targetFolder = $tmpLogStoreDir;
$targetFolder = $logStoreDir;

if ((strlen($username) > 0) && (strlen($password) > 0))
{
    system ( "net use \"" . $logFolderName . "\" " . $password . " /user:" . $username . " /persistent:no>nul 2>&1" );
    //setcookie("benchMaxUsername", $username, time()+3600);
    //setcookie("benchMaxPassword", $password, time()+3600);
}
else
{
    $userChecker = new CUserManger();
    if (($userChecker->isUser()    == true) &&
        ($userChecker->isManager() == false))
    {
        $password = $userChecker->getPassWord();
        $username = $userChecker->getUserName();
        system ( "net use \"" . $logFolderName . "\" " . $password . " /user:" . $username . " /persistent:no>nul 2>&1" );
    }
}

if (count($allFileList) == 0)
{
    $n1 = strlen($logFolderName);
    $t1 = substr($logFolderName, $n1 - 1);
    $tmpName = $logFolderName;

    $tmpName = str_replace("/", "\\", $tmpName);

    if (strcmp($t1, "\\") == 0)
    {
        $tmpName = substr($tmpName, 0, $n1 - 1);
    }
    $tmpUpperName = $tmpName;



    $allFolderList = array();

    function checkFiles($_parentFolder)
    {
        global $allFileList;
        global $allFolderList;
        $fileList = glob($_parentFolder . "\\*");
        $folderList = array();
        foreach ($fileList as $tmpName)
        {
            if (is_dir($tmpName) == true)
            {
                array_push($folderList, $tmpName);
                array_push($allFolderList, $tmpName);
            }
            else
            {
                array_push($allFileList, $tmpName);
            }
        }
        foreach ($folderList as $tmpName)
        {
            checkFiles($tmpName);
        }
        //print_r($folderList);
    }

    checkFiles($tmpUpperName);

    //print_r($allFileList);
    
    if (count($allFileList) == 0)
    {
        // if read results file folder error
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "folder empty or invalid, " .
                                 "or our server needs be authorized to access that location ";
        echo json_encode($returnMsg);
        return;
    }
    

    if (strlen($parentFolderOnly) == 0)
    {
        // clear outdated result folders
        //$tmpList = glob($targetFolder . "/*", GLOB_ONLYDIR);
        //$curTime = time();
        //// 7 days
        //$noTouchTimeLen = 7 * 24 * 3600;
        //
        //foreach ($tmpList as $tmpName)
        //{
        //    $n1 = swtGetFileTreeLastAccessTime($tmpName);
        //    if (($curTime - $n1) > $noTouchTimeLen)
        //    {
        //        swtDelFileTree($tmpName);
        //    }
        //}
        
        
        // no last log file path
        $date = new DateTime(null, new DateTimeZone('PRC'));
        $folder01 = $date->format('Y-m-d') . "";
        $fullFolder01 = "";
        $i = 1;
        while(1)
        {
            $t1 = sprintf("-man%05d", $i);
            $parentFolderOnly = $folder01 . $t1;
            $fullFolder01 = $targetFolder . "/" . $parentFolderOnly;
            
            if (file_exists($fullFolder01) == false)
            {
                // make first sub folder under /driverStore
                // like /logStore/2016-03-30-man00001/
                mkdir($fullFolder01);
                break;
            }
            $i++;
        }
        
        $db = new CPdoMySQL();
        if ($db->getError() != null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "can't reach mysql server";
            return -1;
        }
        $batchGroup = 0;
        $batchState = 1;
        
        $params1 = array($parentFolderOnly);
        $sql1 = "SELECT * FROM mis_table_path_info " .
                "WHERE path_name=?";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__;
            return -1;
        }
        $row1 = $db->fetchRow();
        $pathID = -1;
        if ($row1 == false)
        {
            $params1 = array($parentFolderOnly);
            $sql1 = "INSERT INTO mis_table_path_info " .
                    "(path_name) " .
                    "VALUES (?)";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__;
                return -1;
            }
            $pathID = $db->getInsertID();
        }
        else
        {
            $pathID = $row1[0];
        }
        
        $params1 = array($batchState, $batchGroup, $pathID);
        $sql1 = "INSERT INTO mis_table_batch_list " .
                "(insert_time, batch_state, batch_group, path_id) " .
                "VALUES (NOW(), ?, ?, ?)";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3a";
            return -1;
        }
        $batchID = $db->getInsertID();
        
        // for blank batch
        $userChecker = new CUserManger();
        $userID = $userChecker->getUserID();
        if ($userID != -1)
        {
            // valid user
            
            $params1 = array($userID, $batchID);
            $sql1 = "SELECT COUNT(*) FROM mis_table_user_batch_info " .
                    "WHERE user_id = ? AND batch_id = ?";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__;
                return -1;
            }
            $row1 = $db->fetchRow();
            if ($row1 == false)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__;
                return -1;
            }
            $userNum = intval($row1[0]);
            if ($userNum == 0)
            {
                $params1 = array($userID, $batchID);
                $sql1 = "INSERT INTO mis_table_user_batch_info " .
                        "(user_id, batch_id, insert_time) " .
                        "VALUES (?, ?, NOW())";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__;
                    return -1;
                }
            }
        }

    }
    else
    {
        $fullFolder01 = $targetFolder . "/" . $parentFolderOnly;
    }
    
    $parentFolder = $fullFolder01;

    $n1 = strlen($tmpUpperName);

    foreach ($allFolderList as $tmpName)
    {
        $t1 = substr($tmpName, $n1);
        $tmpFolerName = $fullFolder01 . $t1;
        if ((file_exists($tmpFolerName) == false) ||
            (is_dir($tmpFolerName)      == false))
        {
            // do not create folder if exists
            mkdir($tmpFolerName);
        }
    }

}

if (count($allFileList) > 0)
{
    // copy one single file each loop
    if ($fileID < count($allFileList))
    {
        $tmpName = $allFileList[$fileID];
        $n1 = strlen($logFolderName);
        //$t2 = file_get_contents($tmpName);
        $t1 = substr($tmpName, $n1);
        //file_put_contents($parentFolder . $t1, $t2);
        
        copy($tmpName, $parentFolder . $t1);
        $fileID++;
    }
    else
    {
        $returnMsg["copyFileFinished"] = 1;
        $returnMsg["parentFolderOnly"] = $parentFolderOnly;
        $returnMsg["allFileListString"] = implode(",", $allFileList);
        $returnMsg["parentFolder"] = $parentFolder;
        echo json_encode($returnMsg);
        return;
    }
}

$returnMsg["allFileListString"] = implode(",", $allFileList);
$returnMsg["parentFolder"] = $parentFolder;
$returnMsg["parentFolderOnly"] = $parentFolderOnly;
$returnMsg["fileID"] = $fileID;
$returnMsg["fileNum"] = count($allFileList);

echo json_encode($returnMsg);

?>