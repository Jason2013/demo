<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../userManage/swtUserManager.php";

//$batchID = intval($_POST["batchID"]);
//$logFolderName = $_POST["logFolderName"];
$userTemplatePathName = $_POST["userTemplatePathName"];
//$allFileListString = $_POST["allFileListString"];
//$fileID = intval($_POST["fileID"]);
//$parentFolder = $_POST["parentFolder"];
//$parentFolderOnly = $_POST["parentFolderOnly"];
$username = cleaninput($_POST["username"], 32);
$password = cleaninput($_POST["password"], 32);

$tmpPathParts = pathinfo($userTemplatePathName);
$userTemplateFolderName = $tmpPathParts["dirname"];
$userTemplateFileName = $tmpPathParts["basename"];

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "get filenames success";
$returnMsg["copyFileFinished"] = 0;

//$targetFolder = $logStoreDir;
$targetFolder = $userDefinedReportTemplateDir;

if ((strlen($username) > 0) && (strlen($password) > 0))
{
    system ( "net use \"" . $userTemplateFolderName . "\" " . $password . " /user:" . $username . " /persistent:no>nul 2>&1" );
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
        system ( "net use \"" . $userTemplateFolderName . "\" " . $password . " /user:" . $username . " /persistent:no>nul 2>&1" );
    }
}

$fullFolder01 = "";

$parentFolderOnly = "";
// no last log file path
$date = new DateTime(null, new DateTimeZone('PRC'));
$folder01 = $date->format('Y-m-d') . "";
$i = 1;
while(1)
{
    $t1 = sprintf("-tmplt%05d", $i);
    $parentFolderOnly = $folder01 . $t1;
    $fullFolder01 = $targetFolder . "/" . $parentFolderOnly;
    
    if (file_exists($fullFolder01) == false)
    {
        // make first sub folder
        // like /reportTemplate/2016-03-30-tmplt00001/
        mkdir($fullFolder01);
        break;
    }
    $i++;
}

$parentFolder = $fullFolder01;

//$n1 = strlen($userTemplateFolderName);
$t2 = file_get_contents($userTemplatePathName);
//$t1 = substr($userTemplatePathName, $n1);
//file_put_contents($parentFolder . $t1, $t2);
file_put_contents($parentFolder . "/" . $userTemplateFileName, $t2);

$tmpTemplate = json_decode($t2, true);

if ($tmpTemplate == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "template format invalid, line: " . __LINE__;
    echo json_encode($returnMsg);
    return null;
}

if (isset($tmpTemplate["ReportType"]) == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "template key-value missing, line: " . __LINE__;
    echo json_encode($returnMsg);
    return null;
}

$ReportSort = $tmpTemplate["ReportType"];


//$returnMsg["allFileListString"] = implode(",", $allFileList);
$returnMsg["parentFolder"] = $parentFolder;
$returnMsg["parentFolderOnly"] = $parentFolderOnly;
$returnMsg["userTemplateFileName"] = $userTemplateFileName;
$returnMsg["ReportSort"] = $ReportSort;
//$returnMsg["fileID"] = $fileID;
//$returnMsg["fileNum"] = count($allFileList);

echo json_encode($returnMsg);

?>