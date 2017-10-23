<?php

include_once "../generalLibs/dopdo.php";
include_once "../generalLibs/dodb.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../userManage/swtUserManager.php";

//$logFolderName = cleaninput($_POST["logFolderName"], 128);
$srcFolderName = cleanPath($_POST["srcFolderName"], 512);
//$srcFolderName = $_POST["srcFolderName"];
$runLogFileName = "runlog.txt";
$batchID = intval($_POST["batchID"]);

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "get source path info success";

function swtGetMachineID($_pathName, $_machineName)
{
    global $returnMsg;

    $clientCmdParser = new CClientHeartBeat;
    $tmpMsg = $clientCmdParser->updateMachineInfo2($_pathName, $_machineName);

    $returnMsg["errorMsg"] = isset($tmpMsg["errorMsg"]) ? $tmpMsg["errorMsg"] : "";
    return $tmpMsg;
}

$userChecker = new CUserManger();
if (($userChecker->isUser()    == true) &&
    ($userChecker->isManager() == false))
{
    $password = $userChecker->getPassWord();
    $username = $userChecker->getUserName();
    if (strpos($username, "\\") === false)
    {
        $username = "amd\\" . $username;
    }
    // net use * /del
    //system ("net use \"" . $srcFolderName . "\" /del");
    system ("net use * /del /y >nul 2>&1");
    system ( "net use \"" . $srcFolderName . "\" \"" . $password . "\" /user:\"" . $username . "\" /persistent:no>nul 2>&1" );
    $returnMsg["check"] = "check";
    $returnMsg["username"] = $username;
    $returnMsg["password"] = $password;
    $returnMsg["srcFolderName"] = $srcFolderName;
}

//$cardFolderList = glob($logStoreDir . "/" . $logFolderName . "/*", GLOB_ONLYDIR);
$cardFolderList = glob($srcFolderName . "\\*", GLOB_ONLYDIR);

$returnMsg["srcFolderName"] = $srcFolderName;

if ((strlen($srcFolderName) == 0) || 
    (count($cardFolderList) == 0))
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["isUser"] = $userChecker->isUser();
    $returnMsg["errorMsg"] = "input folder is empty";
    echo json_encode($returnMsg);
    return;
}

$machineID = -1;
$folderMachineNameList = array();
$machineIDList = array();
$cardNameList = array();
$sysNameList = array();
$reportNameList = array();

foreach ($cardFolderList as $tmpPath)
{   
    $tmpFolderName = basename($tmpPath);
    
    $pieceFolderList = glob($tmpPath . "/*", GLOB_ONLYDIR);
    
    $machineID = -1;
    $tmpCardName = "";
    $tmpSysName = "";
    
    if (count($pieceFolderList) == 0)
    {
        // no piece folder
        $t2 = $tmpPath . "/" . $runLogFileName;
        if (file_exists($t2))
        {
            $tmpSet = swtGetMachineID($t2, $tmpFolderName);
            $machineID = $tmpSet["machineID"];
            $tmpCardName = $tmpSet["videoCardName"];
            $tmpSysName = $tmpSet["systemName"];
        }
    }
    else
    {
        // log file in piece folders
        foreach ($pieceFolderList as $piecePath)
        {
            $t2 = $piecePath . "/" . $runLogFileName;
            if (file_exists($t2))
            {
                $tmpSet = swtGetMachineID($t2, $tmpFolderName);
                $machineID = $tmpSet["machineID"];
                $tmpCardName = $tmpSet["videoCardName"];
                $tmpSysName = $tmpSet["systemName"];
                if ($machineID != -1)
                {
                    break;
                }
            }
        }
    }
    
    array_push($machineIDList, $machineID);
    array_push($cardNameList, $tmpCardName);
    array_push($sysNameList, $tmpSysName);
    $t1 = sprintf($tmpCardName . "_" . $tmpSysName . "_batch%05d.xlsm", $batchID);
    array_push($reportNameList, $t1);
    array_push($folderMachineNameList, $tmpFolderName);
}

$returnMsg["folderMachineNameList"] = $folderMachineNameList;
$returnMsg["machineIDList"] = $machineIDList;
$returnMsg["reportNameList"] = $reportNameList;

echo json_encode($returnMsg);

?>