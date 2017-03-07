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

//$fullFolder02 = $tmpResultPath;

$curDateName = date("Y-m-d");
$fullFolder02 = $tmpResultPath . "/" . $curDateName;

if (file_exists($fullFolder02) == false)
{
    // make date level folder
    mkdir($fullFolder02);
}

$folderID = 1;
while (1)
{
    $folder02 = sprintf("%05d", $folderID);
    $fullFolder02 = $tmpResultPath . "/" . $curDateName . "/" . $folder02;
    if (file_exists($fullFolder02) == false)
    {
        mkdir($fullFolder02);
        break;
    }
    $folderID++;
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
    $zip->extractTo($fullFolder02);
    $zip->close();
}
else
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "open zip file failed: " . $targetFile;
    echo json_encode($returnMsg);
    return;
}

unlink($targetFile);


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
echo json_encode($returnMsg);



?>