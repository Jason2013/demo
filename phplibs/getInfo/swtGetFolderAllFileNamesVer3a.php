<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../userManage/swtUserManager.php";

$srcFilePath = $_POST["srcFilePath"];
$batchID = intval($_POST["batchID"]);
$fileID = intval($_POST["fileID"]);
$fileNum = intval($_POST["fileNum"]);
$parentFolder = $_POST["parentFolder"];
$parentFolderOnly = $_POST["parentFolderOnly"];

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "get filenames success";
$returnMsg["copyFileFinished"] = 0;

$targetFolder = $logStoreDir;

if ($fileID == 0)
{
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
        
        $db = new CPdoMySQL();
        if ($db->getError() != null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "can't reach mysql server";
            echo json_encode($returnMsg);
            return;
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
            echo json_encode($returnMsg);
            return;
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
                echo json_encode($returnMsg);
                return;
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
            echo json_encode($returnMsg);
            return;
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
                echo json_encode($returnMsg);
                return;
            }
            $row1 = $db->fetchRow();
            if ($row1 == false)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__;
                echo json_encode($returnMsg);
                return;
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
                    echo json_encode($returnMsg);
                    return;
                }
            }
        }

    }
    else
    {
        $fullFolder01 = $targetFolder . "/" . $parentFolderOnly;
    }
    
    $parentFolder = $fullFolder01;
}

if ($fileID < $fileNum)
{
    $srcFilePath = str_replace("\\", "/", $srcFilePath);
    $tmpPos = strpos($srcFilePath, "/");
    if ($tmpPos !== false)
    {
        $srcFilePath = substr($srcFilePath, $tmpPos + 1);
    }
    
    $tmpFileName = basename($srcFilePath);
    $n1 = strlen($srcFilePath);
    $n2 = strlen($tmpFileName);
    $t1 = "";
    if ($n1 > $n2)
    {
        $t1 = substr($srcFilePath, 0, $n1 - $n2);
    }
    $tmpFolderName = $parentFolder . "/" . $t1;

    if ((file_exists($tmpFolderName) == false) ||
        (is_dir($tmpFolderName)      == false))
    {
        // do not create folder if exists
        mkdir($tmpFolderName, 0777, true);
    }

    $tmpFileName = "file" . $fileID;
    if (isset($_FILES[$tmpFileName]) == false)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "uploaded file not found, line: " . __LINE__;
        echo json_encode($returnMsg);
        return;
    }
    
    $tmpError = $_FILES[$tmpFileName]["error"];

    if ($tmpError == UPLOAD_ERR_OK)
    {
        $tmp_name = $_FILES[$tmpFileName]["tmp_name"];
        $name = basename($_FILES[$tmpFileName]["name"]);
        move_uploaded_file($tmp_name, $tmpFolderName . $name);
    }
    else
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "file uploading failed, error: " . $tmpError . ", line: " . __LINE__;
        echo json_encode($returnMsg);
        return;
    }
    
    $fileID++;
    
    if ($fileID >= $fileNum)
    {
        $returnMsg["copyFileFinished"] = 1;
        $returnMsg["parentFolderOnly"] = $parentFolderOnly;
        $returnMsg["parentFolder"] = $parentFolder;
        echo json_encode($returnMsg);
        return;
    }
}


/*

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

//*/

$returnMsg["srcFilePath"] = $srcFilePath;
$returnMsg["parentFolder"] = $parentFolder;
$returnMsg["parentFolderOnly"] = $parentFolderOnly;
$returnMsg["fileID"] = $fileID;
$returnMsg["fileNum"] = $fileNum;

echo json_encode($returnMsg);

?>