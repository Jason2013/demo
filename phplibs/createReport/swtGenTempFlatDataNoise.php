<?php

//session_start();

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../configuration/swtConfig.php";
include_once "swtClassGenReportNoise.php";
include_once "swtClassGenFlatDataNoise.php";

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "compile report success";
$returnMsg["parseFinished"] = 0;

$batchID = intval($_POST["batchID"]);
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

$xmlWriter = new CGenReport();
// get xml code template pieces
$returnSet = $xmlWriter->getXMLCodePiece();
$appendStyleList = $returnSet["appendStyleList"];
$allStylesEndTag = $returnSet["allStylesEndTag"];
$allSheetsEndTag = $returnSet["allSheetsEndTag"];


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
$returnMsg["batchID"] = $batchID;
$returnMsg["pathName"] = $pathName;
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
    return $returnSet;
}

$reportFolder = $returnSet["reportFolder"];
$curReportFolder = $returnSet["curReportFolder"];

$returnMsg["reportFolder"] = $reportFolder;
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

$outFileNameLater = "_batch%05d.tmp1";

$returnMsg["machineIDPair"] = $machineIDPair;


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
$returnMsg["machineIDCardNameDict"] = $machineIDCardNameDict;
$returnMsg["machineIDSysNameDict"] = $machineIDSysNameDict;
$returnMsg["machineIDCardNameSysNameDict"] = $machineIDCardNameSysNameDict;

$returnMsg["pathName"] = $pathName;

$batchPathName = $logStoreDir . "/batch" . $batchID;

if (file_exists($batchPathName) == false)
{
    $batchPathName = $logStoreDir . "/" . $pathName;
}

$returnMsg["batchPathName"] = $batchPathName;

if (file_exists($batchPathName) == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "invalid result path, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

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
    $returnMsg["tmp---003:"] = "";
    $returnMsg["tmp---004:"] = "";
    $returnMsg["tmp---005:"] = "";
    $tmpResult = $flatDataGen->getAllFileList($batchPathName);
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

while (($crossType >= 10) && 
       ($fileID    != 0)  &&
       ($fileID    <  count($uniqueCardNameList)))
{
    $curMachineID = $uniqueMachineIDList[$fileID];
    
    $returnMsg["tmpEnter1"] = "2";
    
    $tmpPos = array_search($curMachineID, $machineIDListLimit);
    
    $returnMsg["tmpEnter_curMachineID"] = $curMachineID;
    $returnMsg["tmpEnter_machineIDListLimit"] = $machineIDListLimit;
    $returnMsg["tmpEnter_machineIDPair"] = $machineIDPair;
    
    if (($tmpPos === false) &&
        ($fileID <=  count($uniqueCardNameList)))
    {
        $returnMsg["tmpEnter3"] = "1";
        $fileID++;
    }
    else
    {
        break;
    }
}

if ($fileID <= count($uniqueCardNameList))
{
    // test if need to add sheet head to tmp file
    $flatDataGen->testWriteSheetHead($batchID,
                                     $fileID,
                                     $uniqueCardNameList,
                                     $reportFolder,
                                     $outFileNameLater);
    
    // test if need to add sheet end to tmp file
    $returnSet = $flatDataGen->testWriteSheetEnd($batchID,
                                                  $fileID,
                                                  $uniqueCardNameList,
                                                  $reportFolder,
                                                  $outFileNameLater,
                                                  $curReportFolder);
    if ($returnSet == false)
    {
        // not end, continue next
        return;
    }
    
    $tmpCardName = $uniqueCardNameList[$fileID];
    
    $curCardNameList = array_keys($cardNameList, $tmpCardName);
    $curMachineID = -1;
    $curPairMachineID = -1;
    $pairCardNameList = array();
    
    $returnSet = $flatDataGen->getDoubleMachineID($tmpCardName,
                                                  $cardSysNameMachineIDDict,
                                                  $machineIDPair);
    if ($returnSet == false)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "error line: " . __LINE__;
        echo json_encode($returnMsg);
        return;
    }

    $curMachineID = $returnSet["curMachineID"];
    $curPairMachineID = $returnSet["curPairMachineID"];
    
    $returnMsg["curMachineID"] = $curMachineID;
    $returnMsg["curPairMachineID"] = $curPairMachineID;
    $returnMsg["cardSysNameMachineIDDict"] = $cardSysNameMachineIDDict;

    $returnMsg["curPairMachineID"] = $curPairMachineID;

    if ($curPairMachineID != -1)
    {
        // if has compare card
        
        $tmpCardNameList = array();
        foreach ($cardNameList as $tmpName)
        {
            array_push($tmpCardNameList, strtolower($tmpName));
        }
        $tmpCardNameSysName = strtolower($machineIDCardNameSysNameDict["" . $curPairMachineID]);
        
        $pairCardNameList = array_keys($tmpCardNameList, $tmpCardNameSysName);
        
    }
    
    $visitedTestNameList = array();
    $testStartPosList = array();
    $pairTestStartPosList = array();
    
    $returnMsg["cardNameList"] = $cardNameList;
    $returnMsg["machineIDListNew"] = $machineIDList;
    $returnMsg["machineIDCardNameSysNameDict"] = $machineIDCardNameSysNameDict;
    $returnMsg["curPairMachineID"] = $curPairMachineID;
    $returnMsg["curCardNameList"] = $curCardNameList;
    $returnMsg["pairCardNameList"] = $pairCardNameList;
    $returnMsg["tmpCardName"] = $tmpCardName;
    

    // get cur machineID start pos of each test
    $testStartPosList = $flatDataGen->getTestStartPos($curCardNameList);
    if ($curPairMachineID != -1)
    {
        // get compare machineID to cur machineID start pos of each test
        $pairTestStartPosList = $flatDataGen->getTestStartPos($pairCardNameList);
    }
    
    
    $returnMsg["testStartPosList"] = $testStartPosList;
    $returnMsg["pairTestStartPosList"] = $pairTestStartPosList;
    
    $tmpFileName = sprintf($reportFolder . "/" . $tmpCardName . $outFileNameLater, $batchID);
    // open dest file
    $fileHandle = fopen($tmpFileName, "r+");
    fseek($fileHandle, 0, SEEK_END);
    
    $returnMsg["tmpStr1"] = "";
    

    // open all src files
    $resultFileHandleList = array();
    $pairResultFileHandleList = array();
    for ($i = 0; $i < count($curCardNameList); $i++)
    {
        $curTmpFileName = $allFileList[$curCardNameList[$i]];
        $resultFileHandle = fopen($curTmpFileName, "r"); 
        array_push($resultFileHandleList, $resultFileHandle);
    }
    
    if ($curPairMachineID != -1)
    {
        // if has compare card
        for ($i = 0; $i < count($pairCardNameList); $i++)
        {
            $curTmpFileName = $allFileList[$pairCardNameList[$i]];
            $resultFileHandle = fopen($curTmpFileName, "r"); 
            array_push($pairResultFileHandleList, $resultFileHandle);
        }
    }
    
    // write result file lines to tmp file
    $flatDataGen->dumpLines($visitedTestNameList,
                            $curCardNameList,
                            $pairCardNameList,
                            $testStartPosList,
                            $pairTestStartPosList,
                            $fileHandle,
                            $resultFileHandleList,
                            $pairResultFileHandleList,
                            $tmpCardName,
                            $curPairMachineID,
                            $machineIDCardNameSysNameDict);


    // close all src files
    foreach ($resultFileHandleList as $tmpHandle)
    {
        fclose($tmpHandle);
    }
    
    foreach ($pairResultFileHandleList as $tmpHandle)
    {
        fclose($tmpHandle);
    }

    // write max column & linenum
    //$t1 = file_get_contents($templateFileName1);
    //$t1 = sprintf($t1, 1, 2);
    //$n1 = strpos($t1, "00001");
    //$n2 = strpos($t1, "0000000002");
    //$t1 = sprintf("%05d", $columnNum);
    //$t2 = sprintf("%010d", $rowNum);
    //fseek($fileHandle, $n1, SEEK_SET);
    //fwrite($fileHandle, $t1);
    //fseek($fileHandle, $n2, SEEK_SET);
    //fwrite($fileHandle, $t2);
    fclose($fileHandle);
    
    $columnNum = 0;
    $rowNum = 0;

    $fileID++;
}
else
{
    $returnMsg["parseFinished"] = 1;
    $returnMsg["curReportFolder"] = $curReportFolder;
    
    $flatDataGen->delConnectJson($reportFolder);
    
    echo json_encode($returnMsg);
    return;
}


$valueSet = array();
$valueSet["allFileList"] = $valueSet;
$valueSet["cardNameList"] = $cardNameList;
$valueSet["machineIDList"] = $machineIDList;
$valueSet["cardSysNameMachineIDDict"] = $cardSysNameMachineIDDict;
$valueSet["fileID"] =    $fileID;
$valueSet["columnNum"] = $columnNum;
$valueSet["rowNum"] =    $rowNum;

$flatDataGen->setConnectValues($valueSet, $reportFolder);

$returnMsg["cardSysNameMachineIDDictNew"] = $cardSysNameMachineIDDictNew;
$returnMsg["valueSet"] = $valueSet;
$returnMsg["fileID"] = $fileID;
$returnMsg["fileNum"] = count($allFileList);
$returnMsg["curReportFolder"] = $curReportFolder;
$returnMsg["uniqueCardNameList"] = $uniqueCardNameList;
$returnMsg["uniqueMachineIDList"] = $uniqueMachineIDList;
$returnMsg["crossType"] = $crossType;

echo json_encode($returnMsg);
return;


?>