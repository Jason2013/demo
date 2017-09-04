<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "swtClassGenReportVer3.php";
include_once "../configuration/swtConfig.php";


$xmlWriter = new CGenReport();
// get xml code template pieces
$returnSet = $xmlWriter->getXMLCodePiece();
$appendStyleList = $returnSet["appendStyleList"];
$allStylesEndTag = $returnSet["allStylesEndTag"];
$allSheetsEndTag = $returnSet["allSheetsEndTag"];
                         
$batchID = intval($_POST["batchID"]);
$folderID = intval($_POST["folderID"]);
$curTestPos = intval($_POST["curTestPos"]);
$sheetLinePos = intval($_POST["sheetLinePos"]);
$machineIDPair = cleanText($_POST["machineIDPair"], 256);
$checkedMachineIDList = cleanText($_POST["machineIDList"], 256);
$curReportFolder = intval($_POST["curReportFolder"]);
$crossType = intval($_POST["crossType"]);

// default values
$startStyleID = $swtStartStyleID;
$startSheetLineNum = 11;
$tempFileStartSheetLineNum = 4;
$compileFinished = 0;
$historyBatchMaxNum = 4;
$startGraphDataLinePos = 2;
$maxSubTestNumOnce = 5000;

$templateFileName0 = $reportTemplateDir . "/sectionHead001.txt";
$templateFileName1 = $reportTemplateDir . "/sectionSheet003A.txt";
$templateFileName2 = $reportTemplateDir . "/sectionSheet003B.txt";

$templateFileName3 = $reportTemplateDir . "/sectionSheet005A.txt";
$templateFileName4 = $reportTemplateDir . "/sectionSheet005B.txt";

$outFileNameLater = "_batch%05d.tmp1";

$testCaseIDColumnName = "TestCaseId#";

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "compile report success";

$umdNameList = $swtUmdNameList;
$umdStandardOrder = $swtUmdStandardOrder;

// check input card, system selection
$returnSet = $xmlWriter->checkInputMachineID($machineIDPair, $checkedMachineIDList);
if ($returnSet === null)
{
    echo "error line: " . __LINE__;
    return;
}
$machineIDPairList = $returnSet["machineIDPairList"];
$checkedMachineIDList = $returnSet["checkedMachineIDList"];
$checkedMachineIDListString = $returnSet["checkedMachineIDListString"];
$folderNum = $returnSet["folderNum"];

$db = new CPdoMySQL();
if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$returnSet = $xmlWriter->getBatchID($db, $batchID);
if ($returnSet === null)
{
    echo "error line: " . __LINE__;
    return;
}
$batchID = $returnSet["batchID"];
$batchIDList = $returnSet["batchIDList"];
$returnMsg["batchID"] = $batchID;
$returnMsg["batchIDList"] = $batchIDList;

// set up folder for report xml
$returnSet = $xmlWriter->prepareReportFolder($batchID, $curReportFolder);
$reportFolder = $returnSet["reportFolder"];
$curReportFolder = $returnSet["curReportFolder"];

$connectDataSet = $xmlWriter->getConnectValues($reportFolder);
if ($returnSet === null)
{
    echo "error line: " . __LINE__;
    return;
}

// check if batch id valid
$returnSet = $xmlWriter->checkBatchNum($db, $batchID);
if ($returnSet === false)
{
    echo "error line: " . __LINE__;
    return;
}

$returnSet = $xmlWriter->getCompareMachineInfo($folderID, $folderNum);
if ($returnSet === false)
{
    echo "error line: " . __LINE__;
    return;
}
$cmpMachineID = $returnSet["cmpMachineID"];
$curMachineID = $returnSet["curMachineID"];
$cmpMachineName = $returnSet["cmpMachineName"];
$curMachineName = $returnSet["curMachineName"];
$cmpCardName = $returnSet["cmpCardName"];
$cmpSysName = $returnSet["cmpSysName"];
$curCardName = $returnSet["curCardName"];
$curSysName = $returnSet["curSysName"];

$returnMsg["curMachineID"] = $curMachineID;
$returnMsg["cmpMachineID"] = $cmpMachineID;
$returnMsg["curMachineName"] = $curMachineName;
$returnMsg["cmpMachineName"] = $cmpMachineName;
$returnMsg["folderID"] = $folderID;
$returnMsg["folderNum"] = $folderNum;


// get subtest num of current test
//$returnSet = $xmlWriter->getSubTestNum($curTestPos, $curMachineID);
//if ($returnSet === null)
//{
//    return;
//}
//$subTestNum = $returnSet["subTestNum"];


$returnSet = $xmlWriter->getTestTitleInfo($curMachineID);
if ($returnSet === false)
{
    echo "error line: " . __LINE__;
    return;
}
$testNameList = $returnSet["testNameList"];
$testCaseNumList = $returnSet["testCaseNumList"];
$testCaseUmdDataMaskList = $returnSet["testCaseUmdDataMaskList"];
$reportUmdNameList = $returnSet["reportUmdNameList"];
$testCaseNumMap = $returnSet["testCaseNumMap"];
$subjectNameFilterNumMax = $returnSet["subjectNameFilterNumMax"];

$returnMsg["reportUmdNameList"] = $reportUmdNameList;

if ($curTestPos >= count($testNameList))
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input test pos invalid";
    echo json_encode($returnMsg);
    return;
}

$subTestNum = $testCaseNumList[$curTestPos];
$testName = $testNameList[$curTestPos];
$tableName01 = $db_mis_table_name_string001 . $testName;

$returnMsg["testName"] = $testName;
$returnMsg["subTestNum"] = $subTestNum;

$reportUmdNum = count($umdNameList);

$umdOrder = array_fill(0, $reportUmdNum * 2, -1);
$resultUmdOrder = array_fill(0, $reportUmdNum * 2, -1);

for ($i = 0; $i < $reportUmdNum; $i++)
{
    $n3 = false;
    if ($i < count($reportUmdNameList))
    {
        $n3 = array_search($reportUmdNameList[$i], $umdNameList);
    }
    if ($n3 !== false)
    {
        $umdOrder[$i] = $n3;
        $umdOrder[$reportUmdNum + $i] = $reportUmdNum + $n3;
        
        $resultUmdOrder[$i] = $n3;
        
        if ($cmpMachineID != -1)
        {
            $resultUmdOrder[$reportUmdNum + $i] = $n3;
        }
    }
}

$returnMsg["resultUmdOrder"] = $resultUmdOrder;

$returnSet = $xmlWriter->checkReportDataColumnNum();
if ($returnSet === null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "data column invalid";
    $returnMsg["resultUmdOrder"] = $resultUmdOrder;
    $returnMsg["reportUmdNum"] = $reportUmdNum;
    $returnMsg["cmpMachineID"] = $cmpMachineID;
    echo json_encode($returnMsg);
    return;
}
$returnMsg["reportUmdNum"] = $reportUmdNum;
$dataColumnNum = $returnSet["dataColumnNum"];
$returnMsg["dataColumnNum"] = $dataColumnNum;


$isCompStandard = true;

$swtReportInfo = array_fill(0, $reportUmdNum, "");
$swtReportUmdInfo = array_fill(0, $reportUmdNum, "");

for ($i = 0; $i < count($reportUmdNameList); $i++)
{
    // loop all comparison batches of one umd
    $swtReportInfo[$i] = "CL#";
    $swtReportUmdInfo[$i] = $reportUmdNameList[$i];
}

$returnMsg["swtReportInfo"] = $swtReportInfo;
$returnMsg["swtReportUmdInfo"] = $swtReportUmdInfo;

$subTestNumList = $testCaseNumList;
$subTestNumMap = $testCaseNumMap;
$subTestUmdDataMaskList = $testCaseUmdDataMaskList;


$returnSet = $xmlWriter->getReportFileNames($reportFolder, $curCardName, $curSysName, $batchID);
// API sheets
$xmlFileName = $returnSet["xmlFileName"];
// comparison sheet
$tmpFileName = $returnSet["tmpFileName"];
// flatData
$tmpFileName1 = $returnSet["tmpFileName1"];
// summary
$jsonFileName = $returnSet["jsonFileName"];


$returnSet = $xmlWriter->checkNeedCreateReportFile($xmlFileName, $tmpFileName, $jsonFileName,
                                                   $cmpMachineID,
                                                   $curCardName, $curSysName,
                                                   $cmpCardName, $cmpSysName);
if ($returnSet === null)
{
    echo "error line: " . __LINE__;
    return;
}

$graphCells = array();

$returnSet = $xmlWriter->genAverageDataForGraph($isCompStandard, $cmpMachineID,
                                                $curCardName, $cmpCardName, $graphCells,
                                                $curSysName, $cmpSysName);
$graphCells = $returnSet["graphCells"];
$averageColumnHasVal = $returnSet["averageColumnHasVal"];
$returnMsg["averageColumnHasVal"] = $averageColumnHasVal;

$flatDataBuffer = "";

$isProduceFlatData = true;

if ($curTestPos < count($testNameList))
{
    $curTestName = $testNameList[$curTestPos];

    // test if create flatdata file "*.tmp1"
    if ($isProduceFlatData)
    {
        $xmlWriter->testWriteFlatDataReportHead($curTestPos, $curTestName, $tmpFileName1);
    }
    
    $fileHandle = fopen($xmlFileName, "r+");
    $tempFileHandle = fopen($tmpFileName, "r+");

    fseek($fileHandle, 0, SEEK_END);
    fseek($tempFileHandle, 0, SEEK_END);
    
    $testCaseDataList = array();
    $testCaseIDList = array();
    $testCaseIDMap = array();
    $testCaseFilterNameList = array();
    
    $fileHandleFlatData = null;
    
    if ($isProduceFlatData)
    {
        $fileHandleFlatData = fopen($tmpFileName1, "r+");
        fseek($fileHandleFlatData, 0, SEEK_END);
    }
    
    // get data of a test of cur machine
    $returnSet = $xmlWriter->getTestResultData($curMachineID, $curTestName, true, $fileHandleFlatData);
    if ($returnSet !== null)
    {
        $testSubjectNameList = $returnSet["testSubjectNameList"];
        $unitSubject = $returnSet["unitSubject"];
        // get data of same test of cmp machine
        $returnSet = $xmlWriter->getTestResultData($cmpMachineID, $curTestName, false, $fileHandleFlatData);
        if ($returnSet !== null)
        {
            if ($fileHandleFlatData !== null)
            {
                fclose($fileHandleFlatData);
                $fileHandleFlatData = null;
            }
            
            $returnSet = $xmlWriter->checkStartTest($fileHandle, $tempFileHandle,
                                                    $curTestPos, $curTestName, $testSubjectNameList, $unitSubject,
                                                    $isCompStandard, $cmpMachineID, $sheetLinePos);
                                                    
            $sheetLinePos = $returnSet["sheetLinePos"];
            
            $returnSet = $xmlWriter->writeReportCompareData($tempFileHandle, $reportFolder,
                                                            $isCompStandard, $reportUmdNum,
                                                            $sheetLinePos, $startGraphDataLinePos,
                                                            $averageColumnHasVal);
            if ($returnSet === null)
            {
                fclose($fileHandle);
                fclose($tempFileHandle);

                echo "error line: " . __LINE__;
                return;
            }
            $sheetLinePos = $returnSet["sheetLinePos"];
        
        }
    }
    
    if ($fileHandleFlatData !== null)
    {
        fclose($fileHandleFlatData);
    }
    
    fclose($fileHandle);
    fclose($tempFileHandle);

    
    $curTestPos++;
    
    // test if finish flatdata file "*.tmp1"
    if ($isProduceFlatData)
    {
        $xmlWriter->testWriteFlatDataReportEnd($curTestPos, count($testNameList), $tmpFileName1);
    }
    
    if ($curTestPos >= count($testNameList))
    {
        $returnSet = $xmlWriter->checkShiftCard($xmlFileName, $tmpFileName, $tmpFileName1, $jsonFileName,
                                                $allSheetsEndTag, $sheetLinePos,
                                                $curCardName, $curSysName, $cmpMachineID,
                                                $cmpCardName);
        if ($returnSet === null)
        {
            echo "error line: " . __LINE__;
            return;
        }
        $sheetLinePos = $returnSet["sheetLinePos"];
        
        
        $curTestPos = 0;
        $folderID++;
        
        $returnSet = $xmlWriter->checkAllReportsFinished($reportFolder, $folderID, $folderNum);
        if ($returnSet === null)
        {
            // all task finished
            return;
        }
    }
}


$returnMsg["isCompStandard"] = $isCompStandard;
$returnMsg["sheetLinePos"] = $sheetLinePos;
$returnMsg["curTestPos"] = $curTestPos;
$returnMsg["testNum"] = count($testNameList);
$returnMsg["subTestNum"] = $subTestNum;
$returnMsg["compileFinished"] = $compileFinished;
$returnMsg["curReportFolder"] = $curReportFolder;
$returnMsg["batchID"] = $batchID;
$returnMsg["subTestUmdDataMaskList"] = $subTestUmdDataMaskList;
$returnMsg["crossType"] = $crossType;
$returnMsg["folderID"] = $folderID;
$returnMsg["folderNum"] = $folderNum;

$t1 = json_encode($returnMsg);
echo $t1;

?>