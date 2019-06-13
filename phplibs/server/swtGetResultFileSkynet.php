<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

$zipFileName = "resultFiles";
$tmpResultPath = $swtTempResultPath;

$returnMsg = array();
$returnMsg["errorCode"] = 0;



if (empty($_FILES[$zipFileName]))
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "zipped result file missing, " . $zipFileName;
    echo json_encode($returnMsg);
    return;
}

$batchID = -1;
$pathID = -1;
$pathName = "";

$targetFolder = $logStoreDir;
$parentFolderOnly = "";
$fullFolder01 = "";

$db = new CPdoMySQL();
if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server";
    echo json_encode($returnMsg);
    return;
}
$params1 = array();
$sql1 = "SELECT t0.batch_id, t0.path_id, t1.path_name FROM mis_table_batch_list t0 " .
        "LEFT JOIN mis_table_path_info t1 USING(path_id) " .
        "WHERE t0.batch_group=\"4\" AND YEARWEEK(t0.insert_time)=YEARWEEK(NOW()) ORDER BY t0.insert_time DESC ";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__ . ", error: " . $db->getError()[2];
    echo json_encode($returnMsg);
    return;
}
$row1 = $db->fetchRow();
if ($row1 == false)
{
    // first upload, create a batchID & result path folder
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
    
    $pathName = $parentFolderOnly;
    
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
    
    $params1 = array($pathID);
    $sql1 = "INSERT INTO mis_table_batch_list " .
            "(insert_time, batch_state, batch_group, path_id) " .
            "VALUES (NOW(), \"1\", \"4\", ?)";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3a";
        return -1;
    }
    $batchID = $db->getInsertID();
}
else
{
    $batchID = intval($row1[0]);
    $pathID = intval($row1[1]);
    // like 2017-10-18-man00015
    $pathName = $row1[2];
}

$fullFolder01 = $targetFolder . "/" . $pathName;


$curDateName = date("Y-m-d");
$fullFolder02 = $tmpResultPath . "/" . $curDateName;

if (file_exists($fullFolder02) == false)
{
    // make date level folder
    mkdir($fullFolder02);
}

$tempFile = $_FILES[$zipFileName]['tmp_name'];
$targetFile = $fullFolder02 . "/" . $_FILES[$zipFileName]['name'];

// Validate the file type
$fileTypes = array('zip'); // File extensions
$fileParts = pathinfo($_FILES[$zipFileName]['name']);

if (in_array($fileParts['extension'], $fileTypes))
{
    move_uploaded_file($tempFile, $targetFile);
}

// upzip result files
$zip = new ZipArchive;
if ($zip->open($targetFile) === TRUE)
{
    //$zip->extractTo($fullFolder01);
    //$zip->close();
    //$returnMsg["fullFolder01"] = $fullFolder01;
    //
    //system ("net use * /delete /y >nul 2>&1");
    //system ( "net use \"" . $swtLogStoreDirServerDBA . "\" " . $passwordServerDBA . " /user:" . $usernameServerDBA . " /persistent:yes >nul 2>&1" );
    //
    //// copy result files to \\srdcvmysqldp1\PHPresultfiles\benchMax\logStore
    //$tmpDestPath = $swtLogStoreDirServerDBA . "\\" . $pathName;
    //if (file_exists($tmpDestPath) == false)
    //{
    //    @mkdir($tmpDestPath, 0777, true);
    //}
    //recurse_copy_fast($fullFolder01, $tmpDestPath);
    //
    //system ("net use * /delete /y >nul 2>&1");
    
    
    $zip->extractTo($fullFolder01);
    $returnMsg["fullFolder01"] = $fullFolder01;
    
    system ("net use * /delete /y >nul 2>&1");
    system ( "net use \"" . $swtLogStoreDirServerDBA . "\" " . $passwordServerDBA . " /user:" . $usernameServerDBA . " /persistent:yes >nul 2>&1" );
    
    // copy result files to \\srdcvmysqldp1\PHPresultfiles\benchMax\logStore
    $tmpDestPath = $swtLogStoreDirServerDBA . "\\" . $pathName;
    if (file_exists($tmpDestPath) == false)
    {
        @mkdir($tmpDestPath, 0777, true);
    }
    
    @$zip->extractTo($tmpDestPath);
    $returnMsg["tmpDestPath"] = $tmpDestPath;
    $zip->close();
    
    system ("net use * /delete /y >nul 2>&1");
}
else
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "open zip file failed: " . $targetFile;
    echo json_encode($returnMsg);
    return;
}

if (@is_writable($targetFile))
{
    @unlink($targetFile);
}


// delete old reports
$tmpPath = $tmpResultPath . "/*";
$tmpList = glob($tmpPath, GLOB_ONLYDIR);
$curTime = time();
// 3 days
$noTouchTimeLen = 5 * 24 * 3600;

foreach ($tmpList as $tmpName)
{
    $n1 = swtGetFileTreeLastAccessTime($tmpName);
    if (($curTime - $n1) > $noTouchTimeLen)
    {
        swtDelFileTree($tmpName);
    }
}


$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "log files saving success";
$returnMsg["batchID"] = $batchID;
$returnMsg["pathID"] = $pathID;
$returnMsg["pathName"] = $pathName;
echo json_encode($returnMsg);



?>