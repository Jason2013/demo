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

$targetFolder = $logStoreDir;

// Normalize log folder name
$logFolderName = str_replace("/", "\\", $logFolderName); // Replace '/' with '\'
$lastChar = substr($logFolderName, -1);
if (strcmp($lastChar, "\\") == 0)
{
    $logFolderName = substr($logFolderName, 0, -1); // Remove trailing '\' character
}

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

    checkFiles($logFolderName);

    //print_r($allFileList);
    
    if (count($allFileList) == 0)
    {
        // if read results file folder error
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "folder empty or invalid, " .
                                 "or our server needs be authorized to access that location ";
    }
    
    if ($batchID != -1)
    {
        // add result to existing batch
        // get last time log file path
        $db = new CPdoMySQL();
        if ($db->getError() != null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "can't reach mysql server";
            echo json_encode($returnMsg);
            return;
        }
        $params1 = array($batchID);
        $sql1 = "SELECT t0.*, t1.path_name FROM mis_table_batch_list t0 " .
                "LEFT JOIN mis_table_path_info t1 " .
                "USING (path_id) " .
                "WHERE t0.batch_id=?";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__;
            echo json_encode($returnMsg);
            return;
        }
        $row1 = $db->fetchRow();
        if ($row1 == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__ . ", batchID: " . $batchID;
            echo json_encode($returnMsg);
            return;
        }
        $parentFolderOnly = $row1[5];
    }

    if (strlen($parentFolderOnly) == 0)
    {
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
    }
    else
    {
        $fullFolder01 = $targetFolder . "/" . $parentFolderOnly;
    }
    
    $parentFolder = $fullFolder01;

    $n1 = strlen($logFolderName);

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
else /* count($allFileList) > 0 */
{
    // copy one single file each loop
    if ($fileID < count($allFileList))
    {
        $source = $allFileList[$fileID];
        $len = strlen($logFolderName);
        $dest = $parentFolder . substr($source, $len);
        copy($source, $dest);
        $fileID++;
    }
    else
    {
        $returnMsg["copyFileFinished"] = 1;
        $returnMsg["parentFolderOnly"] = $parentFolderOnly;
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