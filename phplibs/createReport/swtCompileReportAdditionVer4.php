<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "swtClassGenReportVer4.php";
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
$subjectNameFilterNumMax = intval($_POST["subjectNameFilterNumMax"]);

$curFolderFileID = intval($_POST["curFolderFileID"]);
$curFileOffset = intval($_POST["curFileOffset"]);
$cmpFolderFileID = intval($_POST["cmpFolderFileID"]);
$cmpFileOffset = intval($_POST["cmpFileOffset"]);

//$visitedTestNameList = cleanText($_POST["visitedTestNameList"], 1000000);

// default values
$startStyleID = $swtStartStyleID;
$startSheetLineNum = 11;
$tempFileStartSheetLineNum = 4;
$compileFinished = 0;
$historyBatchMaxNum = 4;
$startGraphDataLinePos = 2;
$maxSubTestNumOnce = 5000;

// pre saved graph data into report file
// will be replaced by real data after traversed all test
$defaultGraphDataLineLen = 5000;
$defaultGraphDataLineNum = 50;
$defaultGraphDataLineBuff = str_pad("", $defaultGraphDataLineLen);

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
if ($connectDataSet === null)
{
    echo "error line: " . __LINE__;
    return;
}
$returnMsg["connectDataSet"] = $connectDataSet;
//$allFileReportUmdNameList = $connectDataSet["allFileReportUmdNameList"];
//$machineIDList = $connectDataSet["machineIDList"];
//$returnMsg["reportUmdNameList"] = $reportUmdNameList;
//$reportUmdNameList = $connectDataSet["reportUmdNameList"];
//$returnMsg["reportUmdNameList"] = $reportUmdNameList;

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
$curReportUmdNameList = $returnSet["curReportUmdNameList"];
$cmpReportUmdNameList = $returnSet["cmpReportUmdNameList"];

$returnMsg["curMachineID"] = $curMachineID;
$returnMsg["cmpMachineID"] = $cmpMachineID;
$returnMsg["curMachineName"] = $curMachineName;
$returnMsg["cmpMachineName"] = $cmpMachineName;
$returnMsg["folderID"] = $folderID;
$returnMsg["folderNum"] = $folderNum;
$returnMsg["curReportUmdNameList"] = $curReportUmdNameList;
$returnMsg["cmpReportUmdNameList"] = $cmpReportUmdNameList;

$reportUmdNum = count($umdNameList);
$umdOrder = array_fill(0, $reportUmdNum * 2, -1);
$resultUmdOrder = array_fill(0, $reportUmdNum * 2, -1);

$reportUmdListRef = $curReportUmdNameList;
if (($cmpMachineID                != -1) &&
    (count($cmpReportUmdNameList) >  count($curReportUmdNameList)))
{
    $reportUmdListRef = $cmpReportUmdNameList;
}

$returnMsg["reportUmdListRef"] = $reportUmdListRef;
$returnMsg["umdNameList"] = $umdNameList;
$returnMsg["curReportUmdNameList"] = $curReportUmdNameList;
$returnMsg["cmpReportUmdNameList"] = $cmpReportUmdNameList;

for ($i = 0; $i < $reportUmdNum; $i++)
{
    $n3 = false;
    $n4 = false;
    $n5 = false;
    if ($i < count($reportUmdListRef))
    {
        $n3 = array_search($reportUmdListRef[$i], $umdNameList);
        $n4 = array_search($reportUmdListRef[$i], $curReportUmdNameList);
        $n5 = array_search($reportUmdListRef[$i], $cmpReportUmdNameList);
    }
    if ($n3 !== false)
    {
        $umdOrder[$i] = $n3;
        $umdOrder[$reportUmdNum + $i] = $reportUmdNum + $n3;
        
        if ($n4 !== false)
        {
            $resultUmdOrder[$i] = $n3;
        }
        if ($n5 !== false)
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

for ($i = 0; $i < count($curReportUmdNameList); $i++)
{
    // loop all comparison batches of one umd
    $swtReportInfo[$i] = "CL#";
    $swtReportUmdInfo[$i] = $curReportUmdNameList[$i];
}

$returnMsg["swtReportInfo"] = $swtReportInfo;
$returnMsg["swtReportUmdInfo"] = $swtReportUmdInfo;

$returnSet = $xmlWriter->getReportFileNames($reportFolder, $curCardName, $curSysName, $batchID);
// API sheets
$xmlFileName = $returnSet["xmlFileName"];
// comparison sheet
$tmpFileName = $returnSet["tmpFileName"];
// flatData
$tmpFileName1 = $returnSet["tmpFileName1"];
// summary
$jsonFileName = $returnSet["jsonFileName"];
// final report name
$xlsmFileName = $returnSet["xlsmFileName"];

$returnSet = $xmlWriter->checkNeedCreateReportFile($xmlFileName, $tmpFileName, $jsonFileName,
                                                   $cmpMachineID,
                                                   $curCardName, $curSysName,
                                                   $cmpCardName, $cmpSysName);
if ($returnSet === null)
{
    echo "error line: " . __LINE__;
    return;
}

$isProduceFlatData = true;

if ($isProduceFlatData)
{
    $xmlWriter->testWriteFlatDataReportHead($curTestPos, $tmpFileName1);
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

$isFolderFinished = false;
$curVisitedFileSizeSum = 0;
$curToVisitFileSizeSum = 0;
$cmpVisitedFileSizeSum = 0;
$cmpToVisitFileSizeSum = 0;

$returnMsg["curFolderFileID"] = $curFolderFileID;
$returnMsg["curFileOffset"] = $curFileOffset;

$isOutputFlatData = false;

$returnSet = $xmlWriter->getTestResultData2($curMachineID, 
                                            $curFolderFileID, 
                                            $curFileOffset,
                                            true, 
                                            "",
                                            $fileHandleFlatData);
if ($returnSet !== null)
{
    $testSubjectNameList = $returnSet["testSubjectNameList"];
    $unitSubject = $returnSet["unitSubject"];
    $isFolderFinished = $returnSet["isFolderFinished"];
    $curFolderFileID = $returnSet["tmpFolderFileID"];
    $curFileOffset = $returnSet["tmpFileOffset"];
    $folderFileNum = $returnSet["folderFileNum"];
    $curTestName = $returnSet["curTestName"];
    $testName = $curTestName;
    $tmpTestCaseNum = $returnSet["tmpTestCaseNum"];
    $subTestNum = $tmpTestCaseNum;
    $tableName01 = $db_mis_table_name_string001 . $testName;
    $curVisitedFileSizeSum = $returnSet["tmpVisitedFileSizeSum"];
    $curToVisitFileSizeSum = $returnSet["tmpToVisitFileSizeSum"];
    
$returnMsg["curFolderFileID1"] = $curFolderFileID;
$returnMsg["curFileOffset1"] = $curFileOffset;
    
    // get data of same test of cmp machine
    $returnSet = $xmlWriter->getTestResultData2($cmpMachineID, 
                                                $cmpFolderFileID, 
                                                $cmpFileOffset,
                                                false, 
                                                $curTestName,
                                                $fileHandleFlatData);
    if ($returnSet !== null)
    {
        $cmpFolderFileID = $returnSet["tmpFolderFileID"];
        $cmpFileOffset = $returnSet["tmpFileOffset"];
        $cmpVisitedFileSizeSum = $returnSet["tmpVisitedFileSizeSum"];
        $cmpToVisitFileSizeSum = $returnSet["tmpToVisitFileSizeSum"];
        
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
                                                        $sheetLinePos, $startGraphDataLinePos);
        if ($returnSet === null)
        {
            fclose($fileHandle);
            fclose($tempFileHandle);

            echo "error line: " . __LINE__;
            return;
        }
        $sheetLinePos = $returnSet["sheetLinePos"];
    
        $xmlWriter->setConnectValues($reportFolder);
    }
}

if ($fileHandleFlatData !== null)
{
    fclose($fileHandleFlatData);
}

if ($isFolderFinished == true)
{
    // a folder's (machine's) all test finished
    $testNameList = $connectDataSet["testNameList"];
    $testCaseNumList = $connectDataSet["testCaseNumList"];
    $testCaseUmdDataMaskList = $connectDataSet["testCaseUmdDataMaskList"];
    //$reportUmdNameList = $connectDataSet["reportUmdNameList"];
    $testCaseNumMap = $connectDataSet["testCaseNumMap"];
    // $subjectNameFilterNumMax = $subjectNameFilterNumMax;
    $subTestNumList = $testCaseNumList;
    $subTestNumMap = $testCaseNumMap;
    $subTestUmdDataMaskList = $testCaseUmdDataMaskList;
    
    $returnMsg["testCaseUmdDataMaskList"] = $testCaseUmdDataMaskList;

    $graphCells = array();

    $returnSet = $xmlWriter->genAverageDataForGraph($isCompStandard, $cmpMachineID,
                                                    $curCardName, $cmpCardName, $graphCells,
                                                    $curSysName, $cmpSysName);
    $graphCells = $returnSet["graphCells"];
    //$returnMsg["graphCells"] = $graphCells;
    //$connectDataSet["graphCells"] = $graphCells;
    $averageColumnHasVal = $returnSet["averageColumnHasVal"];
    
    // write graph data into json for call from vba script
    $xmlWriter->writeVBAConfigJsonAndGraphData($tempFileHandle,
                                               $reportFolder,
                                               $isCompStandard);
    
    $connectDataSet["testNameList"] = array();
    $connectDataSet["testCaseNumList"] = array();
    $connectDataSet["testCaseUmdDataMaskList"] = array();
    $connectDataSet["testCaseNumMap"] = array();
    $connectDataSet["graphDataBlankBuffOffset"] = array();
    $xmlWriter->setConnectValues($reportFolder);
    
    $curFolderFileID = 0;
    $curFileOffset = 0;
    $cmpFolderFileID = 0;
    $cmpFileOffset = 0;
}

fclose($fileHandle);
fclose($tempFileHandle);


$curTestPos++;



// test if finish flatdata file "*.tmp1"
if ($isProduceFlatData && ($isOutputFlatData || $isFolderFinished))
{
    $xmlWriter->testWriteFlatDataReportEnd($isFolderFinished, $tmpFileName1);
}


if ($isFolderFinished)
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

$returnMsg["isCompStandard"] = $isCompStandard;
$returnMsg["sheetLinePos"] = $sheetLinePos;
$returnMsg["curTestPos"] = $curTestPos;
//$returnMsg["testNum"] = count($testNameList);
$returnMsg["subTestNum"] = $subTestNum;
$returnMsg["compileFinished"] = $compileFinished;
$returnMsg["curReportFolder"] = $curReportFolder;
$returnMsg["batchID"] = $batchID;
$returnMsg["curMachineID"] = $curMachineID;
$returnMsg["crossType"] = $crossType;
$returnMsg["folderID"] = $folderID;
$returnMsg["folderNum"] = $folderNum;
$returnMsg["subjectNameFilterNumMax"] = $subjectNameFilterNumMax;
$returnMsg["curFolderFileID"] = $curFolderFileID;
$returnMsg["curFileOffset"] = $curFileOffset;
$returnMsg["cmpFolderFileID"] = $cmpFolderFileID;
$returnMsg["cmpFileOffset"] = $cmpFileOffset;
$returnMsg["curVisitedFileSizeSum"] = $curVisitedFileSizeSum;
$returnMsg["curToVisitFileSizeSum"] = $curToVisitFileSizeSum;
$returnMsg["cmpVisitedFileSizeSum"] = $cmpVisitedFileSizeSum;
$returnMsg["cmpToVisitFileSizeSum"] = $cmpToVisitFileSizeSum;
$returnMsg["finalReportFileName"] = $xlsmFileName;

$t1 = json_encode($returnMsg);
echo $t1;

?>