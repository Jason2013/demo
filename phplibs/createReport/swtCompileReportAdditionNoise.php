<?php

use Utilities\ReportCache;

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../generalLibs/code01.php";
include_once "swtClassGenReportNoise.php";
include_once "../configuration/swtConfig.php";
include_once __DIR__ . "/../userManage/swtUserManager.php";
include_once __DIR__ . "/../generalLibs/reportcache.php";


$xmlWriter = new CGenReport();
// get xml code template pieces
$returnSet = $xmlWriter->getXMLCodePiece();
$appendStyleList = $returnSet["appendStyleList"];
$allStylesEndTag = $returnSet["allStylesEndTag"];
$allSheetsEndTag = $returnSet["allSheetsEndTag"];
                         
$batchID = intval($_POST["batchID"]);
$resultPos = intval($_POST["resultPos"]);
$curTestPos = intval($_POST["curTestPos"]);
$firstTestPos = intval($_POST["firstTestPos"]);
$firstSubTestPos = intval($_POST["firstSubTestPos"]);
$nextSubTestPos = intval($_POST["nextSubTestPos"]);
$subTestNum = intval($_POST["subTestNum"]);
$lineNumPos = intval($_POST["lineNumPos"]);
$sheetLinePos = intval($_POST["sheetLinePos"]);
$machineIDPair = cleanText($_POST["machineIDPair"], 256);
$checkedMachineIDList = cleanText($_POST["machineIDList"], 256);
$colMachineIDOrderList = cleanText($_POST["colMachineIDOrderList"], 256);
$colMachineIDOrderIndexList = cleanText($_POST["colMachineIDOrderIndexList"], 256);
//$subTestUmdDataMaskList = cleanText($_POST["subTestUmdDataMaskList"], 1024);
$tempFileLineNumPos = intval($_POST["tempFileLineNumPos"]);
$forceGenReport = intval($_POST["forceGenReport"]);
$reportToken = intval($_POST["reportToken"]);
$curReportFolder = intval($_POST["curReportFolder"]);
$reportType = intval($_POST["reportType"]);
$crossType = intval($_POST["crossType"]);

// set up folder for report xml
$returnSet = $xmlWriter->prepareReportFolder($reportType, $batchID, $curReportFolder);
$reportFolder = $returnSet["reportFolder"];
$curReportFolder = $returnSet["curReportFolder"];

$cacheFileName = __DIR__ . '/' . $reportFolder . '/CompileReportAdditionNoise.txt';
$cache = new ReportCache($cacheFileName);

// default values
$startStyleID = $swtStartStyleID;
$startSheetLineNum = 11;
$tempFileStartSheetLineNum = 4;
$compileFinished = 0;
$historyBatchMaxNum = 4;
$startGraphDataLinePos = 2;
$maxSubTestNumOnce = 3000;

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "compile report success";

$umdNameList = $swtUmdNameList;
$umdStandardOrder = $swtUmdStandardOrder;

$colMachineIDOrderList = explode(",", $colMachineIDOrderList);
$colMachineIDOrderIndexList = explode(",", $colMachineIDOrderIndexList);
// check input card, system selection
$returnSet = $xmlWriter->checkInputMachineID($machineIDPair, $checkedMachineIDList);
if ($returnSet == null)
{
    echo json_encode($returnMsg);
    return;
}
$machineIDPairList = $returnSet["machineIDPairList"];
$machineIDBatchPairList = $returnSet["machineIDBatchPairList"];
$machineIDBatchPairMap = $returnSet["machineIDBatchPairMap"];
$checkedMachineIDList = $returnSet["checkedMachineIDList"];
$checkedMachineIDListString = $returnSet["checkedMachineIDListString"];


$db = new CPdoMySQL();
if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$userChecker = new CUserManger();
if ($userChecker->isManager()) {
    $userInfo["isManager"] = true;
    $userInfo["userID"] = -1;
} else {
    $userInfo["isManager"] = false;
    $userInfo["userID"] = $userChecker->getUserID();
}

$returnSet = $cache->getCachedValue("getBatchID", [$historyBatchMaxNum, $batchID, $userInfo], function () {
    global $xmlWriter, $db, $batchID, $userInfo;
    return $xmlWriter->getBatchID($db, $batchID, $userInfo);
});
if ($returnSet === null)
{
    return;
}
$batchID = $returnSet["batchID"];
$batchIDList = $returnSet["batchIDList"];
$batchDateTextList = $returnSet["batchDateTextList"];

if ($xmlWriter->checkSkipReportGen($reportType, $reportFolder) == true)
{
    // skip generate
    return;
}

// check if batch id valid
$returnSet = $cache->getCachedValue("checkBatchNum", $batchID, function () {
    global $xmlWriter, $db, $batchID;
    return $xmlWriter->checkBatchNum($db, $batchID);
});
if ($returnSet == false)
{
    return;
}

$returnSet = $cache->getCachedValue("getTestTitleInfo", $batchID, function () {
    global $xmlWriter, $db, $batchID;
    return $xmlWriter->getTestTitleInfo($db, $batchID);
});
if ($returnSet === null)
{
    return;
}
$testNameList = $returnSet["testNameList"];
$testIDList = $returnSet["testIDList"];
$subjectNameList = $returnSet["subjectNameList"];
$unitNameList = $returnSet["unitNameList"];
$subjectNameFilterNumMax = $returnSet["subjectNameFilterNumMax"];
$subjectNameFilterNumList = $returnSet["subjectNameFilterNumList"];
$subjectFilterNameList = $returnSet["subjectFilterNameList"];

if ($curTestPos >= count($testNameList))
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input test pos invalid";
    echo json_encode($returnMsg);
    return;
}

$returnSet = $cache->getCachedValue("getSelectedMachineInfo",
    [$swtOldCardNameMatchList, $batchID, $checkedMachineIDListString],
    function () {
        global $xmlWriter, $db, $batchID, $checkedMachineIDListString;
        return $xmlWriter->getSelectedMachineInfo($db, $batchID, $checkedMachineIDListString);
    });
if ($returnSet === null)
{
    return;
}
$selectedCardIDList = $returnSet["selectedCardIDList"];
$selectedSysIDList = $returnSet["selectedSysIDList"];


$testName = $testNameList[$curTestPos];
$tableName01 = $db_mis_table_name_string001 . $testName;

$returnSet = $cache->getCachedValue("getBatchInfo",
    [$selectedCardIDList, $selectedSysIDList, $umdNameList, $batchIDList],
    function () {
        global $xmlWriter, $db, $batchIDList;
        return $xmlWriter->getBatchInfo($db, $batchIDList);
    });
if ($returnSet === null)
{
    return;
}
$resultIDList = $returnSet["resultIDList"];
$machineIDList = $returnSet["machineIDList"];
$cardNameList = $returnSet["cardNameList"];
$driverNameList = $returnSet["driverNameList"];
$changeListNumList = $returnSet["changeListNumList"];
$cpuNameList = $returnSet["cpuNameList"];
$sysNameList = $returnSet["sysNameList"];
$mainLineNameList = $returnSet["mainLineNameList"];
$sClockNameList = $returnSet["sClockNameList"];
$mClockNameList = $returnSet["mClockNameList"];
$gpuMemNameList = $returnSet["gpuMemNameList"];
$resultTimeList = $returnSet["resultTimeList"];
$machineNameList = $returnSet["machineNameList"];
$sysMemNameList = $returnSet["sysMemNameList"];

$returnSet = $xmlWriter->getBatchEnvironmentInfo($db, $batchID);
$envDefaultInfo = $returnSet["envDefaultInfo"];
$logFileFolder = $returnSet["logFileFolder"];

if ($resultPos >= count($resultIDList[0]))
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input result pos invalid";
    echo json_encode($returnMsg);
    return;
}

$uniqueCardNameList = array_unique($cardNameList[0]);
$cardNum = count($uniqueCardNameList);
$uniqueUmdNameList = array_unique($driverNameList[0]);
$umdNum = count($uniqueUmdNameList);
$uniqueSysNameList = array_unique($sysNameList[0]);
$sysNum = count($uniqueSysNameList);

$xmlWriter->checkDefaultMachinePair($resultPos);

$returnSet = $xmlWriter->getCompareMachineInfo($resultPos);
$cmpMachineID = $returnSet["cmpMachineID"];
$curMachineID = $returnSet["curMachineID"];
$cmpMachineName = $returnSet["cmpMachineName"];
$curMachineName = $returnSet["curMachineName"];
$curResultTime = $returnSet["curResultTime"];
$cmpBatchTime = $returnSet["cmpBatchTime"];
$cmpStartResultID = $returnSet["cmpStartResultID"];
$cmpCardName = $returnSet["cmpCardName"];
$cmpSysName = $returnSet["cmpSysName"];
$curCardName = $returnSet["curCardName"];
$colMachineIDList = $returnSet["colMachineIDList"];
$colStartResultIDPosList = $returnSet["colStartResultIDPosList"];
$colCardNameList = $returnSet["colCardNameList"];
$colSysNameList = $returnSet["colSysNameList"];
$colMachineNum = count($colMachineIDList);


// get subtest num of current test

//umdNameList

$reportUmdNum = count($umdNameList);
$startResultID = intval($resultPos / $umdNum) * $umdNum;

$cardStandardResultPos = $cache->getCachedValue("getStandardResultID",
    [$resultIDList, $driverNameList, $umdStandardOrder, $reportUmdNum, $uniqueUmdNameList, $startResultID],
    function () {
        global $xmlWriter, $startResultID;
        $xmlWriter->getStandardResultID($startResultID);
    });
//$cardStandardResultID = $resultIDList[0][$cardStandardResultPos];

$returnSet = $xmlWriter->getSubTestNum($db, $resultPos, $tableName01, $subTestNum);
if ($returnSet === null)
{
    return;
}
$subTestNum = $returnSet["subTestNum"];
$standardTestCaseNum = $returnSet["standardTestCaseNum"];
$curResultTestCaseNum = $returnSet["curResultTestCaseNum"];

$returnSet = $xmlWriter->getHistoryNachineInfo($startResultID, $resultPos);
$historyStartResultID = $returnSet["historyStartResultID"];

$tmpNum = $colMachineNum > 1 ? $colMachineNum : 2;

$umdOrder = array_fill(0, $reportUmdNum * $tmpNum, -1);
$resultUmdOrder = array_fill(0, $reportUmdNum * $tmpNum, -1);

for ($i = 0; $i < $reportUmdNum; $i++)
{
    $n3 = array_search($driverNameList[0][$i], $umdNameList);
    if ($n3 !== false)
    {
        for ($j = 0; $j < $colMachineNum; $j++)
        {
            $umdOrder[$reportUmdNum * $j + $i] = $umdNum * $j + $n3;
            if ($resultIDList[0][$colStartResultIDPosList[$j] + $i] != PHP_INT_MAX)
            {
                $resultUmdOrder[$reportUmdNum * $j + $i] = $n3;
            }
        }
    }
}

$returnSet = $xmlWriter->checkReportDataColumnNum();
if ($returnSet === null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "data column invalid";
    $returnMsg["resultUmdOrder"] = $resultUmdOrder;
    $returnMsg["reportUmdNum"] = $reportUmdNum;
    $returnMsg["cmpMachineID"] = $cmpMachineID;
    echo json_encode($returnMsg);
}
$dataColumnNum = $returnSet["dataColumnNum"];
$graphDataColumnNum = $returnSet["graphDataColumnNum"];

$tmpUmdName = "";
$tmpCardName = "";
$tmpSysName = "";
if ($resultPos < count($resultIDList[0]))
{
    $tmpUmdName = $driverNameList[0][$resultPos];
    $tmpCardName = $cardNameList[0][$resultPos];
    $tmpSysName = $sysNameList[0][$resultPos];
}

$isCompStandard = false;
for ($i = 0; $i < count($umdStandardOrder); $i++)
{
    // find a standard umd name
    $isSkip = false;
    for ($j = 0; $j < $reportUmdNum; $j++)
    {
        if ($umdStandardOrder[$i] == $driverNameList[0][$j])
        {
            if ($resultIDList[0][$j] == PHP_INT_MAX)
            {
                // if API missing
                $isSkip = true;
                break;
            }
        }
    }
    if ($isSkip)
    {
        continue;
    }
    if ($cmpMachineID != -1)
    {
        // asic comparison
        $tmpPos = array_search($umdStandardOrder[$i], $umdNameList);
    }
    $tmpPos = array_search($umdStandardOrder[$i], $uniqueUmdNameList);
    if ($tmpPos !== false)
    {
        if (strcmp($tmpUmdName, $umdStandardOrder[$i]) == 0)
        {
            $isCompStandard = true;
        }
        break;
    }
}

$swtReportInfo = array_fill(0, $reportUmdNum, "");
$swtReportUmdInfo = array_fill(0, $reportUmdNum, "");

for ($i = 0; $i < $reportUmdNum; $i++)
{
    // loop all comparison batches of one umd
    $swtReportInfo[$i] = "CL#" . $changeListNumList[0][$startResultID + $i];
    $swtReportUmdInfo[$i] = $driverNameList[0][$startResultID + $i];
}


// get subtest num of all tests
$returnSet = $xmlWriter->getSubTestNumList($db, $isCompStandard, $resultPos, $cmpStartResultID, $umdNum);
if ($returnSet === null)
{
    return;
}
$subTestNumList = $returnSet["subTestNumList"];
$subTestNumMap = $returnSet["subTestNumMap"];
$cmpSubTestNumList = $returnSet["cmpSubTestNumList"];
// skip these test in report compare sheet and graph
$skipTestNameList = $returnSet["skipTestNameList"];

$cacheName = "getStandardUmdTestCaseNumList";
$cacheKey = [$testNameList, $db_mis_table_name_string001, $resultIDList, $cardStandardResultPos];
if ($cache->hasValue($cacheName, $cacheKey)) {
    $standardUmdTestCaseNumList = $cache->getValue($cacheName, $cacheKey);
} else {
    $standardUmdTestCaseNumList = $xmlWriter->getStandardUmdTestCaseNumList($db);
    $cache->setValue($cacheName, $standardUmdTestCaseNumList, $cacheKey);
}

// generate seperate cards report
if (($subTestNum == 0) ||
    ($resultIDList[0][$resultPos] == PHP_INT_MAX) ||
    ($nextSubTestPos >= $subTestNum))
{
    // if this test has no sub test
    // or if this test is finished
    $curTestPos++;
    $nextSubTestPos = 0;
    $subTestNum = 0;
    
    
    if ($curTestPos < count($testNameList))
    {
        $tmpPos = array_search($testNameList[$curTestPos], $skipTestNameList);
        if ($tmpPos !== false)
        {
            // skip blank test
            $curTestPos++;
        }
    }
    
    // if cur test finished
    if ($curTestPos >= count($testNameList))
    {
        $returnSet = $xmlWriter->getReportFileNames($reportFolder, $tmpCardName, $tmpSysName, $batchID);
        // main xml file
        $xmlFileName = $returnSet["xmlFileName"];
        // comparison sheet
        $tmpFileName = $returnSet["tmpFileName"];
        // flatData
        $tmpFileName1 = $returnSet["tmpFileName1"];
        // summary
        $jsonFileName = $returnSet["jsonFileName"];
        $jsonFileName2 = $returnSet["jsonFileName2"];
        // alarm ini
        $alarmFileName = $returnSet["alarmFileName"];

        $returnMsg["checkShiftCard"] = "0";
        $returnMsg["resultPos"] = $resultPos;
        if ($resultIDList[0][$resultPos] != PHP_INT_MAX)
        {
            $fileHandle = fopen($xmlFileName, "r+");
            $tempFileHandle = fopen($tmpFileName, "r+");
        
            $returnMsg["checkNeedCreateReportFile"] = "1";
            $returnSet = $xmlWriter->checkNeedCreateReportFile($xmlFileName, $tmpFileName, $jsonFileName, $jsonFileName2,
                $cmpMachineID,
                $tempFileLineNumPos,
                $curCardName, $tmpSysName);

            if ($returnSet === null)
            {
                return;
            }
            $tempFileLineNumPos = $returnSet["tempFileLineNumPos"];
            
            if ($firstTestPos == -1)
            {
                $firstTestPos = $curTestPos;
                $firstSubTestPos = $nextSubTestPos;
            }

            fseek($fileHandle, 0, SEEK_END);
            fseek($tempFileHandle, 0, SEEK_END);
            
            $returnSet = $xmlWriter->checkStartSheet($fileHandle, $tempFileHandle,
                                                     $curTestPos, $nextSubTestPos, $firstTestPos, $firstSubTestPos,
                                                     $lineNumPos, $resultPos, $umdNum,
                                                     $tmpUmdName, $tmpCardName, $tmpSysName);
            if ($returnSet === null)
            {
                return;
            }
            $lineNumPos = $returnSet["lineNumPos"];
            
            
            //$fileHandle = fopen($xmlFileName, "r+");
            fseek($fileHandle, 0, SEEK_END);
            $returnSet = $xmlWriter->checkShiftAPI($fileHandle, $resultPos, $tmpUmdName,
                                                   $firstTestPos, $firstSubTestPos, $sheetLinePos);
            if ($returnSet === null)
            {
                return;
            }
            $firstTestPos = $returnSet["firstTestPos"];
            $firstSubTestPos = $returnSet["firstSubTestPos"];
            $sheetLinePos = $returnSet["sheetLinePos"];
        
            fclose($tempFileHandle);
            fclose($fileHandle);
        }
        
        
        
        $returnMsg["checkShiftCard"] = "1";
        $returnSet = $xmlWriter->checkShiftCard($xmlFileName, $tmpFileName, $tmpFileName1, 
                                                $jsonFileName, $jsonFileName2,
                                                $allSheetsEndTag,
                                                $resultPos, $sheetLinePos,
                                                $tmpUmdName, $tmpCardName, $tmpSysName, $cmpMachineID,
                                                $cmpCardName);
        if ($returnSet === null)
        {
            return;
        }
        $sheetLinePos = $returnSet["sheetLinePos"];
        
        
            
        
        
        $resultPos++;
        $curTestPos = 0;
        
        $returnSet = $xmlWriter->checkAllReportsFinished($db, $resultPos, $reportFolder, $batchID);
        if ($returnSet === null)
        {
            // all task finished
            return;
        }
    }
}
else
{
    // if cur test in process
    $returnSet = $xmlWriter->getReportFileNames($reportFolder, $tmpCardName, $tmpSysName, $batchID);
    $xmlFileName = $returnSet["xmlFileName"];
    $tmpFileName = $returnSet["tmpFileName"];
    // flatData
    $tmpFileName1 = $returnSet["tmpFileName1"];
    // summary
    $jsonFileName = $returnSet["jsonFileName"];
    $jsonFileName2 = $returnSet["jsonFileName2"];
    // alarm ini
    $alarmFileName = $returnSet["alarmFileName"];
    
    //$returnMsg["returnLine2"] = "line: " . __LINE__;

    $returnSet = $xmlWriter->checkNeedCreateReportFile($xmlFileName, $tmpFileName, $jsonFileName, $jsonFileName2,
        $cmpMachineID,
        $tempFileLineNumPos,
        $curCardName, $tmpSysName);

    if ($returnSet === null)
    {
        return;
    }
    $tempFileLineNumPos = $returnSet["tempFileLineNumPos"];
    
    
    $fileHandle = fopen($xmlFileName, "r+");
    $tempFileHandle = fopen($tmpFileName, "r+");

    fseek($fileHandle, 0, SEEK_END);
    fseek($tempFileHandle, 0, SEEK_END);
    
    if ($firstTestPos == -1)
    {
        $firstTestPos = $curTestPos;
        $firstSubTestPos = $nextSubTestPos;
    }
    
    $returnSet = $xmlWriter->checkStartSheet($fileHandle, $tempFileHandle,
                                             $curTestPos, $nextSubTestPos, $firstTestPos, $firstSubTestPos,
                                             $lineNumPos, $resultPos, $umdNum,
                                             $tmpUmdName, $tmpCardName, $tmpSysName);
    if ($returnSet === null)
    {
        return;
    }
    $lineNumPos = $returnSet["lineNumPos"];
    
    $lineNum = 0;
    $tempLineNum = 0;
    
    $graphCells = array();
    
    $returnSet = $xmlWriter->genAverageDataForGraph($isCompStandard, $cmpStartResultID,
                                                    $curCardName, $cmpCardName, $graphCells,
                                                    $tmpSysName, $cmpSysName);
    $graphCells = $returnSet["graphCells"];
    
    $returnSet = $xmlWriter->checkStartTest($fileHandle, $tempFileHandle,
                                            $nextSubTestPos, $firstSubTestPos, $curTestPos, 
                                            $isCompStandard, $cmpMachineID,
                                            $lineNum, $sheetLinePos, $tempLineNum);
    $lineNum = $returnSet["lineNum"];
    $sheetLinePos = $returnSet["sheetLinePos"];
    $tempLineNum = $returnSet["tempLineNum"];
    
    $returnSet = $xmlWriter->getHistoryResultIDList($resultPos);
    $historyResultIDList = $returnSet["historyResultIDList"];
    
    $returnSet = $xmlWriter->writeReportData($db, $fileHandle, $tempFileHandle,
                                             $resultPos, $nextSubTestPos,
                                             $isCompStandard,
                                             $lineNum);
    if ($returnSet === null)
    {
        return;
    }
    $nextSubTestPos = $returnSet["nextSubTestPos"];
    $lineNum = $returnSet["lineNum"];
    $standardSubTestIDList = $returnSet["standardSubTestIDList"];
    $standardSubTestNameList = $returnSet["standardSubTestNameList"];
    $standardSubTestFilterNameList = $returnSet["standardSubTestFilterNameList"];
    $standardTestCaseIDList = $returnSet["standardTestCaseIDList"];

    
    fseek($fileHandle, $lineNumPos, SEEK_SET);
    // line num is 10 digis number, like: 0000000011
    $t1 = fread($fileHandle, 10);
    $n1 = intval($t1);
    $n1 += $lineNum;
    
    fseek($fileHandle, $lineNumPos, SEEK_SET);
    $t1 = sprintf("%010d", $n1);
    fwrite($fileHandle, $t1);
    fclose($fileHandle);
    
    $returnSet = $xmlWriter->writeReportCompareData($db, $tempFileHandle, $reportFolder,
                                                    $isCompStandard, $umdNum, $tempFileLineNumPos,
                                                    $startResultID, $cmpStartResultID, $historyStartResultID, $tempLineNum,
                                                    $resultPos, $sheetLinePos, $startGraphDataLinePos);
    if ($returnSet === null)
    {
        return;
    }
    $sheetLinePos = $returnSet["sheetLinePos"];

    fclose($tempFileHandle);
}

$returnMsg["sheetLinePos"] = $sheetLinePos;
$returnMsg["tempFileLineNumPos"] = $tempFileLineNumPos;
$returnMsg["lineNumPos"] = $lineNumPos;
$returnMsg["resultPos"] = $resultPos;
$returnMsg["resultNum"] = count($resultIDList[0]);
$returnMsg["curTestPos"] = $curTestPos;
$returnMsg["firstTestPos"] = $firstTestPos;
$returnMsg["firstSubTestPos"] = $firstSubTestPos;
$returnMsg["testNum"] = count($testNameList);
$returnMsg["nextSubTestPos"] = $nextSubTestPos;
$returnMsg["subTestNum"] = $subTestNum;
$returnMsg["compileFinished"] = $compileFinished;
$returnMsg["reportToken"] = $reportToken;
$returnMsg["curReportFolder"] = $curReportFolder;

$t1 = json_encode($returnMsg);
echo $t1;
?>
