<?php

//session_start();

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
//include_once "../configuration/swtConfig.php";
include_once "swtClassGenReportNoise.php";
include_once "swtClassGenFlatDataNoise.php";

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "compile report success";
$returnMsg["parseFinished"] = 0;

$batchID = intval($_POST["batchID"]);
//$batchID = 491;
// $reportType == 0 for routine report, 1 for all reports
$reportType = intval($_POST["reportType"]);
$crossType = intval($_POST["crossType"]);
$curReportFolder = intval($_POST["curReportFolder"]);
$machineIDPairStr = cleanText($_POST["machineIDPair"], 256);
$machineIDListStr = cleanText($_POST["machineIDList"], 256);

$startStyleID = $swtStartStyleID;

$testCaseIDColumnName = "TestCaseId#";

$tmpMachineIDPair = explode(",", $machineIDPairStr);
$tmpMachineIDList = explode(",", $machineIDListStr);

$machineIDListLimit = array();
foreach ($tmpMachineIDList as $tmpVal)
{
    array_push($machineIDListLimit, intval($tmpVal));
}

$machineIDPair = array();
foreach ($tmpMachineIDPair as $tmpVal)
{
    array_push($machineIDPair, intval($tmpVal));
    
    if (array_search(intval($tmpVal), $machineIDListLimit) === false)
    {
        array_push($machineIDListLimit, intval($tmpVal));
    }
}

$flatDataGen = new CGenReportFlatData();

$db = new CPdoMySQL();
if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$returnSet = $flatDataGen->getSrcResultPathName($db, $batchID);
if ($returnSet == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "error line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}
$batchID = $returnSet["batchID"];
$pathName = $returnSet["pathName"];
//$returnMsg["batchID"] = $batchID;
//$returnMsg["pathName"] = $pathName;
$returnSet = $flatDataGen->getReportFolder($batchID,
                                           $reportType,
                                           $curReportFolder);
if ($returnSet == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "error line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

if ($returnSet["parseFinished"] == 1)
{
    // can skip
    foreach ($returnSet as $tmpKey => $tmpVal)
    {
        $returnMsg["" . $tmpKey] = $tmpVal;
    }
    echo json_encode($returnMsg);
    //return $returnSet;
    return;
}

$reportFolder = $returnSet["reportFolder"];
$curReportFolder = $returnSet["curReportFolder"];

//$returnMsg["reportFolder"] = $reportFolder;
$returnMsg["curReportFolder"] = $curReportFolder;

$returnSet = $flatDataGen->getConnectValues($reportFolder);
if ($returnSet == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "error line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$allFileList = $returnSet["allFileList"];
$cardNameList = $returnSet["cardNameList"];
$machineIDList = $returnSet["machineIDList"];
$fileID =    intval($returnSet["fileID"]);
$columnNum = intval($returnSet["columnNum"]);
$rowNum =    intval($returnSet["rowNum"]);
$cardSysNameMachineIDDict = $returnSet["cardSysNameMachineIDDict"];
$cardSysNameMachineIDDictNew = array();


$machineInfoFileName = "machine_info.json";
$resultFileName1 = "test_results_for_analysis.txt";
$resultFileName2 = "test_results_for_analysis.csv";
$resultFileName3 = "runlog.txt";

$templateFileName0 = $reportTemplateDir . "/sectionHead001.txt";
$templateFileName1 = $reportTemplateDir . "/sectionSheet003A.txt";
$templateFileName2 = $reportTemplateDir . "/sectionSheet003B.txt";

$templateFileName3 = $reportTemplateDir . "/sectionSheet005A.txt";
$templateFileName4 = $reportTemplateDir . "/sectionSheet005B.txt";

//$allStylesEndTag = "</Styles>\n";
//$allSheetsEndTag = "</Workbook>";

$outFileNameLater = ".tmp1";

//$returnMsg["machineIDPair"] = $machineIDPair;


$returnSet = $flatDataGen->getMachineIDInfo($db, $machineIDPair);
if ($returnSet == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "error line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$machineIDCardNameDict = $returnSet["machineIDCardNameDict"];
$machineIDSysNameDict = $returnSet["machineIDSysNameDict"];
$machineIDCardNameSysNameDict = $returnSet["machineIDCardNameSysNameDict"];
//$returnMsg["machineIDCardNameDict"] = $machineIDCardNameDict;
//$returnMsg["machineIDSysNameDict"] = $machineIDSysNameDict;
//$returnMsg["machineIDCardNameSysNameDict"] = $machineIDCardNameSysNameDict;

//$returnMsg["pathName"] = $pathName;

$batchPathName = $logStoreDir . "/batch" . $batchID;

if (file_exists($batchPathName) == false)
{
    $batchPathName = $logStoreDir . "/" . $pathName;
}

//$returnMsg["batchPathName"] = $batchPathName;

if (file_exists($batchPathName) == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "invalid result path, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$allRunLogFileList = array();

if (count($allFileList) == 0)
{
    $allFileList = array();
    $allFolderList = array();
    $cardNameList = array();
    $machineIDList = array();
    
    // will write to
    // $allFileList
    // $allFolderList
    // $cardNameList
//    $returnMsg["tmp---003:"] = "";
//    $returnMsg["tmp---004:"] = "";
//    $returnMsg["tmp---005:"] = "";
    $beforeGetAll = microtime_float();
    $tmpResult = $flatDataGen->getAllFileList($batchPathName);
    $afterGetAll = microtime_float();
    $returnMsg["GetAllSecs"] = $afterGetAll - $beforeGetAll;
    if ($tmpResult === false)
    {
        // "run.log" file missing
        return;
    }
}

if (count($allFileList) == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no result files found, line: " . __LINE__ . ", ";
    echo json_encode($returnMsg);
    return;
}

$tmpUniqueCardNameList = array_unique($cardNameList);
$uniqueCardNameList = array();
$uniqueMachineIDList = array();

foreach ($tmpUniqueCardNameList as $tmpName)
{
    array_push($uniqueCardNameList, $tmpName);
}

foreach ($uniqueCardNameList as $tmpName)
{
    $tmpPos = array_search($tmpName, $cardNameList);
    array_push($uniqueMachineIDList, intval($machineIDList[$tmpPos]));
}

//$returnMsg["uniqueCardNameList"] = $uniqueCardNameList;

$flatDataGen->delConnectJson($reportFolder);
$returnMsg["parseFinished"] = 1;
$returnMsg["curReportFolder"] = $curReportFolder;
echo json_encode($returnMsg);
return;

?>
