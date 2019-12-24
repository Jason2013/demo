<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "swtClassGenReportShaderBench.php";
include_once "../configuration/swtConfig.php";


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
//$subTestUmdDataMaskList = cleanText($_POST["subTestUmdDataMaskList"], 1024);
$tempFileLineNumPos = intval($_POST["tempFileLineNumPos"]);
$forceGenReport = intval($_POST["forceGenReport"]);
$reportToken = intval($_POST["reportToken"]);
$curReportFolder = intval($_POST["curReportFolder"]);
$reportType = intval($_POST["reportType"]);
$crossType = intval($_POST["crossType"]);

// default values
$startStyleID = $swtStartStyleID;
$startSheetLineNum = 11;
$tempFileStartSheetLineNum = 4;
$compileFinished = 0;
$historyBatchMaxNum = 4;
//$startGraphDataLinePos = 2;
$startGraphDataLinePos = 1;
$graphDataStartLineID = 5;
$graphDataStartLineIDCompare = 5;
$maxSubTestNumOnce = 3000;

$subTestUmdDataMaskList = array();

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "compile report success";

$umdNameList = $swtUmdNameList_sb;
$umdStandardOrder = $swtUmdStandardOrder_sb;
$cardStandardOrder = $swtCardStandardOrder_sb;

// check input card, system selection
//$machineIDPair = "";
$returnSet = $xmlWriter->checkInputMachineID($machineIDPair, $checkedMachineIDList);
if ($returnSet == null)
{
    $returnMsg["returnLine"] = "line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}
$machineIDPairList = $returnSet["machineIDPairList"];
$machineIDBatchPairList = $returnSet["machineIDBatchPairList"];
$machineIDBatchPairMap = $returnSet["machineIDBatchPairMap"];
$checkedMachineIDList = $returnSet["checkedMachineIDList"];
$checkedMachineIDListString = $returnSet["checkedMachineIDListString"];

$returnMsg["machineIDBatchPairList"] = $machineIDBatchPairList;

$db = new CPdoMySQL();
if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
    $returnMsg["returnLine"] = "line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$returnMsg["postBatchID"] = $batchID;

$returnSet = $xmlWriter->getBatchID($db, $batchID);
$returnMsg["returnLine"] = "line: " . __LINE__;
if ($returnSet === null)
{
    return;
}
$batchID = $returnSet["batchID"];
$batchIDList = $returnSet["batchIDList"];
$batchDateTextList = $returnSet["batchDateTextList"];
$returnMsg["batchID"] = $batchID;
$returnMsg["batchIDList"] = $batchIDList;
$returnMsg["batchDateTextList"] = $batchDateTextList;

$returnSet = $xmlWriter->getBatchEnvironmentInfo($db, $batchID);
$envDefaultInfo = $returnSet["envDefaultInfo"];
$logFileFolder = $returnSet["logFileFolder"];

if ((isset($envDefaultInfo["shaderBenchCompilerList"]) == true) &&
    (count($envDefaultInfo["shaderBenchCompilerList"]) > 0))
{
    $swtUmdNameList_sb = $envDefaultInfo["shaderBenchCompilerList"];
    $swtUmdStandardOrder_sb = $envDefaultInfo["shaderBenchCompilerList"];
    $swtUmdNameList_sb []= "OPT1";
    $swtUmdStandardOrder_sb []= "OPT1";
    
    $umdNameList = $swtUmdNameList_sb;
    $umdStandardOrder = $swtUmdStandardOrder_sb;
}

if ((isset($envDefaultInfo["shaderBenchCardList"]) == true) &&
    (count($envDefaultInfo["shaderBenchCardList"]) > 0))
{
    $swtCardStandardOrder_sb = $envDefaultInfo["shaderBenchCardList"];
    $cardStandardOrder = $swtCardStandardOrder_sb;
}

$returnSet = $xmlWriter->getCardCompilerInfo();
$sortedCardCompilerList = $returnSet["sortedCardCompilerList"];
if (count($sortedCardCompilerList) > 0)
{
    $umdNameList = $sortedCardCompilerList;
    $umdStandardOrder = $sortedCardCompilerList;
}

// set up folder for report xml
$returnSet = $xmlWriter->prepareReportFolder($reportType, $batchID, $curReportFolder);
$reportFolder = $returnSet["reportFolder"];
$curReportFolder = $returnSet["curReportFolder"];

$returnMsg["returnLine"] = "line: " . __LINE__;
if ($xmlWriter->checkSkipReportGen($reportType, $reportFolder) == true)
{
    // skip generate
    return;
}

$returnMsg["reportType"] = $reportType;

// check if batch id valid
$returnMsg["returnLine"] = "line: " . __LINE__;
$returnSet = $xmlWriter->checkBatchNum($db, $batchID);
if ($returnSet == false)
{
    return;
}

$returnMsg["returnLine"] = "line: " . __LINE__;
$returnSet = $xmlWriter->getTestTitleInfo($db, $batchID);
if ($returnSet === null)
{
    return;
}
$testNameList = $returnSet["testNameList"];
$subjectNameList = $returnSet["subjectNameList"];
$unitNameList = $returnSet["unitNameList"];
$subjectNameFilterNumMax = $returnSet["subjectNameFilterNumMax"];
$subjectNameFilterNumList = $returnSet["subjectNameFilterNumList"];
$subjectFilterNameList = $returnSet["subjectFilterNameList"];

$returnMsg["returnLine"] = "line: " . __LINE__;
if ($curTestPos >= count($testNameList))
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input test pos invalid";
    echo json_encode($returnMsg);
    return;
}

$returnMsg["returnLine"] = "line: " . __LINE__;
$returnSet = $xmlWriter->getSelectedMachineInfo($db, $batchID, $checkedMachineIDListString);
if ($returnSet === null)
{
    return;
}
$selectedCardIDList = $returnSet["selectedCardIDList"];
$selectedSysIDList = $returnSet["selectedSysIDList"];

$testName = $testNameList[$curTestPos];
$tmpTestName = str_replace(" ", "", $testName);
$tmpTestName = cleaninput($tmpTestName, 256);
//$tableName01 = $db_mis_table_name_string002 . $testName;
$tableName01 = $db_mis_table_name_string002 . $tmpTestName;

$returnMsg["testNameList"] = $testNameList;
$returnMsg["curTestPos"] = $curTestPos;
$returnMsg["testName"] = $testName;
$returnMsg["tableName01"] = $tableName01;

$returnMsg["returnLine"] = "line: " . __LINE__;
$returnSet = $xmlWriter->getBatchInfo($db, $batchIDList);
if ($returnSet === null)
{
    return;
}
$resultIDList = $returnSet["resultIDList"];
$machineIDList = $returnSet["machineIDList"];
$cardNameList = $returnSet["cardNameList"];
$card2NameList = $returnSet["card2NameList"];
$driverNameList = $returnSet["driverNameList"];
$driver2NameList = $returnSet["driver2NameList"];
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

$cardNameRealList = $returnSet["cardNameRealList"];
$sysNameRealList = $returnSet["sysNameRealList"];

$tmpList = array_unique($driver2NameList[0]);
$uniqueDriver2NameList = array();
foreach ($tmpList as $v)
{
    $uniqueDriver2NameList []= $v;
}
$returnMsg["uniqueDriver2NameList"] = $uniqueDriver2NameList;

$returnMsg["changeListNumList"] = $changeListNumList;

//$crossBuildResultIDList =      $returnSet["crossBuildResultIDList"];
//$crossBuildMachineIDList =     $returnSet["crossBuildMachineIDList"];
//$crossBuildCardNameList =      $returnSet["crossBuildCardNameList"];
//$crossBuildDriverNameList =    $returnSet["crossBuildDriverNameList"];
//$crossBuildChangeListNumList = $returnSet["crossBuildChangeListNumList"];
//$crossBuildCpuNameList =       $returnSet["crossBuildCpuNameList"];
//$crossBuildSysNameList =       $returnSet["crossBuildSysNameList"];
//$crossBuildMainLineNameList =  $returnSet["crossBuildMainLineNameList"];
//$crossBuildSClockNameList =    $returnSet["crossBuildSClockNameList"];
//$crossBuildMClockNameList =    $returnSet["crossBuildMClockNameList"];
//$crossBuildGpuMemNameList =    $returnSet["crossBuildGpuMemNameList"];
//$crossBuildResultTimeList =    $returnSet["crossBuildResultTimeList"];
//$crossBuildMachineNameList =   $returnSet["crossBuildMachineNameList"];
//
//$returnMsg["crossBuildResultIDList"] = $crossBuildResultIDList;
//$returnMsg["crossBuildMachineIDList"] = $crossBuildMachineIDList;
//$returnMsg["crossBuildCardNameList"] = $crossBuildCardNameList;
//$returnMsg["crossBuildDriverNameList"] = $crossBuildDriverNameList;
//$returnMsg["crossBuildSysNameList"] = $crossBuildSysNameList;

$returnMsg["checkedMachineIDList"] = $checkedMachineIDList;
$returnMsg["cardNameList"] = $cardNameList[0];
$returnMsg["card2NameList"] = $card2NameList[0];
$returnMsg["cardNameListAll"] = $cardNameList;
$returnMsg["sysNameList"] = $sysNameList[0];
$returnMsg["sysNameListAll"] = $sysNameList;
$returnMsg["mainLineNameList"] = $mainLineNameList;
$returnMsg["gpuMemNameList"] = $gpuMemNameList;
$returnMsg["driverNameList"] = $driverNameList;
$returnMsg["driver2NameList"] = $driver2NameList;

//$returnSet = $xmlWriter->getBatchEnvironmentInfo($db, $batchID);
//$envDefaultInfo = $returnSet["envDefaultInfo"];
//$logFileFolder = $returnSet["logFileFolder"];

for ($i = 0; $i < count($machineIDList); $i++)
{
    $returnMsg["machineIDList_" . $i] = $machineIDList[$i];
}

$returnMsg["resultPos"] = $resultPos;
$returnMsg["resultIDList"] = $resultIDList;

$returnMsg["returnLine"] = "line: " . __LINE__;
if ($resultPos >= count($resultIDList[0]))
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input result pos invalid";
    echo json_encode($returnMsg);
    return;
}

//$resultNoiseNum = $xmlWriter->getNoiseNum($db, $resultPos);
//$returnMsg["resultNoiseNum"] = $resultNoiseNum;

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

$returnMsg["curMachineID"] = $curMachineID;
$returnMsg["cmpMachineID"] = $cmpMachineID;
$returnMsg["curMachineName"] = $curMachineName;
$returnMsg["cmpMachineName"] = $cmpMachineName;

// get subtest num of current test
$returnMsg["returnLine"] = "line: " . __LINE__;

//umdNameList

$reportUmdNum = count($umdNameList);
$startResultID = intval($resultPos / $umdNum) * $umdNum;

$cardStandardResultPos = $xmlWriter->getStandardResultID($startResultID, $resultPos);
$cardStandardResultID = $resultIDList[0][$cardStandardResultPos];
$returnMsg["cardStandardResultPos"] = $cardStandardResultPos;
$returnMsg["cardStandardResultID"] = $cardStandardResultID;

//$returnSet = $xmlWriter->getSubTestNum($db, $resultPos, $tableName01, $subTestNum);
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

$umdOrder = array_fill(0, $reportUmdNum * 2, -1);
$resultUmdOrder = array_fill(0, $reportUmdNum * 2, -1);

$validUmdNum = 0;

for ($i = 0; $i < $reportUmdNum; $i++)
{
    //$n3 = array_search($driverNameList[0][$i], $umdNameList);
    $n3 = false;
    for ($j = 0; $j < count($umdNameList); $j++)
    {
        if (strtolower($driverNameList[0][$i]) == strtolower($umdNameList[$j]))
        {
            $n3 = $j;
            break;
        }
    }
    
    if ($n3 !== false)
    {
        $umdOrder[$i] = $n3;
        $umdOrder[$reportUmdNum + $i] = $umdNum + $n3;
        
        if ($resultIDList[0][$startResultID + $i] != PHP_INT_MAX)
        {
            $resultUmdOrder[$i] = $n3;
            $validUmdNum++;
        }
        if ($cmpStartResultID != -1)
        {
            if ($resultIDList[0][$cmpStartResultID + $i] != PHP_INT_MAX)
            {
                $resultUmdOrder[$reportUmdNum + $i] = $n3;
            }
        }
    }
}

//if ($crossType == 2)
//{
//    $umdOrder = array_fill(0, $reportUmdNum * 2, -1);
//    $resultUmdOrder = array_fill(0, $reportUmdNum * 2, -1);
//    
//    for ($i = 0; $i < $reportUmdNum; $i++)
//    {
//        $n3 = array_search($driverNameList[0][$i], $umdNameList);
//        if ($n3 !== false)
//        {
//            $umdOrder[$i] = $n3;
//            $umdOrder[$reportUmdNum + $i] = $umdNum + $n3;
//            
//            if ($resultIDList[0][$startResultID + $i] != PHP_INT_MAX)
//            {
//                $resultUmdOrder[$i] = $n3;
//                $validUmdNum++;
//            }
//            $tmpIndex = -1;
//            for ($j = 0; $j < (count($machineIDBatchPairList) / 2); $j++)
//            {
//                if ($curMachineID == intval($machineIDBatchPairList[$j * 2]))
//                {
//                    $tmpIndex = $j;
//                    break;
//                }
//            }
//            if ($tmpIndex == -1)
//            {
//                continue;
//            }
//            if ($crossBuildResultIDList[$tmpIndex][$i] != PHP_INT_MAX)
//            {
//                $resultUmdOrder[$reportUmdNum + $i] = $n3;
//            }
//        }
//    }
//    
//    if ($curMachineID == PHP_INT_MAX)
//    {
//        for ($i = 0; $i < $reportUmdNum; $i++)
//        {
//            $resultUmdOrder[$reportUmdNum + $i] = $resultUmdOrder[$i];
//        }
//    }
//}

$returnMsg["resultUmdOrder"] = $resultUmdOrder;
$returnMsg["resultIDList[0]"] = $resultIDList[0];
$returnMsg["startResultID"] = $startResultID;
$returnMsg["resultIDListAll"] = $resultIDList;
$returnMsg["validUmdNum"] = $validUmdNum;

$returnMsg["returnLine"] = "line: " . __LINE__;

$tmpUmdName = "";
$tmpUmd2Name = "";
$tmpCardName = "";
$tmpSysName = "";
if ($resultPos < count($resultIDList[0]))
{
    $tmpUmdName = $driverNameList[0][$resultPos];
    $tmpUmd2Name = $driver2NameList[0][$resultPos];
    $tmpCardName = $cardNameList[0][$resultPos];
    $tmpSysName = $sysNameList[0][$resultPos];
}

$returnMsg["uniqueUmdNameList"] = $uniqueUmdNameList;
$returnMsg["umdStandardOrder"] = $umdStandardOrder;
$returnMsg["tmpUmdName"] = $tmpUmdName;
$returnMsg["tmpUmd2Name"] = $tmpUmd2Name;
$returnMsg["tmpCardName"] = $tmpCardName;
$returnMsg["tmpSysName"] = $tmpSysName;
$returnMsg["driverNameList[0]"] = $driverNameList[0];
$returnMsg["driverNameList"] = $driverNameList;

$isUbuntuSys = false;
$t1 = strtolower($tmpSysName);
$tmpPos = strpos($t1, $ubuntuCheckWord);
$isUbuntuSys = $tmpPos !== false;

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
$returnMsg["reportUmdNum"] = $reportUmdNum;
$dataColumnNum = $returnSet["dataColumnNum"];
$graphDataColumnNum = $returnSet["graphDataColumnNum"];
$returnMsg["dataColumnNum"] = $dataColumnNum;
$returnMsg["graphDataColumnNum"] = $graphDataColumnNum;


$isCompStandard = false;
for ($i = 0; $i < count($umdStandardOrder); $i++)
{
    // find a standard umd name
    $isSkip = false;
    for ($j = 0; $j < $reportUmdNum; $j++)
    {
        if ($umdStandardOrder[$i] == $driverNameList[0][$j + $startResultID])
        {
            if ($resultIDList[0][$j + $startResultID] == PHP_INT_MAX)
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
        if (($tmpPos === false) ||
            ($resultUmdOrder[$tmpPos] == -1) ||
            ($resultUmdOrder[$reportUmdNum + $tmpPos] == -1))
        {
            // win cmp ubuntu, skip none vulkan
            //continue;
        }
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
$swtReportInfoPre = array_fill(0, $reportUmdNum, "");
$swtReportUmdInfo = array_fill(0, $reportUmdNum, "");
$swtReportUmdInfoASICOnly = array_fill(0, $reportUmdNum, "");

for ($i = 0; $i < $reportUmdNum; $i++)
{
    // loop all comparison batches of one umd
    //$swtReportInfo[$i] = "CL#" . $changeListNumList[0][$startResultID + $i];
    $swtReportInfo[$i] = "" . $changeListNumList[0][$startResultID + $i];
    $tmpBatchIndex = (count($changeListNumList) <= 1) ? 0 : 1;
    if (count($changeListNumList[$tmpBatchIndex]) > ($startResultID + $i))
    {
        $swtReportInfoPre[$i] = "" . $changeListNumList[$tmpBatchIndex][$startResultID + $i];
    }
    else
    {
        $swtReportInfoPre[$i] = "" . $changeListNumList[0][$startResultID + $i];
    }
    //$swtReportInfoPre[$i] = "" . $changeListNumList[$tmpBatchIndex][$tmpResultIndex];
    $swtReportUmdInfo[$i] = $driverNameList[0][$startResultID + $i];
}

$swtReportUmdInfoASICOnly = array();
for ($i = 0; $i < count($swtReportUmdInfo); $i++)
{
    //$tmpList = explode("_", $swtReportUmdInfo[$i]);
    //
    //if (count($tmpList) > 0)
    //{
    //    $swtReportUmdInfoASICOnly []= $tmpList[0];
    //}
    
    $tmpPos = strrpos($swtReportUmdInfo[$i], "_");
    if ($tmpPos === false)
    {
        $swtReportUmdInfoASICOnly []= $swtReportUmdInfo[$i];
    }
    else
    {
        $swtReportUmdInfoASICOnly []= str_replace(" ", "", substr($swtReportUmdInfo[$i], 0, $tmpPos));
    }
}
$hasRepeat = false;
for ($i = 0; $i < count($swtReportUmdInfoASICOnly); $i++)
{
    $tmpKeys = array_keys($swtReportUmdInfoASICOnly, $swtReportUmdInfoASICOnly[$i]);
    if (count($tmpKeys) > 1)
    {
        $hasRepeat = true;
        break;
    }
}
if ($hasRepeat == true)
{
    $swtReportUmdInfoASICOnly = $swtReportUmdInfo;
}

$returnMsg["swtReportInfo"] = $swtReportInfo;
$returnMsg["swtReportUmdInfo"] = $swtReportUmdInfo;
$returnMsg["startResultID"] = $startResultID;

// get subtest num of all tests
$returnMsg["returnLine"] = "line: " . __LINE__;
$returnSet = $xmlWriter->getSubTestNumList($db, $isCompStandard, $resultPos, $cmpStartResultID, $umdNum);
if ($returnSet === null)
{
    return;
}
$subTestNumList = $returnSet["subTestNumList"];
$startSubTestID = $returnSet["startSubTestID"];
$subTestNumMap = $returnSet["subTestNumMap"];
$cmpSubTestNumList = $returnSet["cmpSubTestNumList"];
// skip these test in report compare sheet and graph
$skipTestNameList = $returnSet["skipTestNameList"];
$subTestUmdDataMaskList = $returnSet["subTestUmdDataMaskList"];

$returnMsg["subTestNumList"] = $subTestNumList;
$returnMsg["startSubTestID"] = $startSubTestID;
$returnMsg["skipTestNameList"] = $skipTestNameList;

//$allUmdTestCaseNumList = $xmlWriter->getAllUmdTestCaseNumList($db);
//$returnMsg["allUmdTestCaseNumList"] = $allUmdTestCaseNumList;

$returnSet = $xmlWriter->getStandardUmdTestCaseNumList($db);
$standardUmdTestCaseNumList = $returnSet["standardUmdTestCaseNumList"];
$allAPITestCaseNumList = $returnSet["allAPITestCaseNumList"];
$allAPITestCaseNumSumList = $returnSet["allAPITestCaseNumSumList"];
$returnMsg["standardUmdTestCaseNumList"] = $standardUmdTestCaseNumList;
$returnMsg["allAPITestCaseNumList"] = $allAPITestCaseNumList;
$returnMsg["allAPITestCaseNumSumList"] = $allAPITestCaseNumSumList;

// generate seperate cards report
if (($subTestNum == 0) ||
    ($resultIDList[0][$resultPos] == PHP_INT_MAX) ||
    ($nextSubTestPos >= $subTestNum))
{
    // if this test has no sub test
    // or if this test is finished
    $returnMsg["nextSubTestPos_old"] = $nextSubTestPos;
    $returnMsg["subTestNum_old"] = $subTestNum;
    
    $curTestPos++;
    $nextSubTestPos = 0;
    $subTestNum = 0;
    
    $returnMsg["returnLine2"] = "line: " . __LINE__;
    
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
        $xmlFileName2 = $returnSet["xmlFileName2"];
        // comparison sheet
        $tmpFileName = $returnSet["tmpFileName"];
        // flatData
        $tmpFileName1 = $returnSet["tmpFileName1"];
        // summary
        $jsonFileName = $returnSet["jsonFileName"];
        $jsonFileName2 = $returnSet["jsonFileName2"];
        $jsonFileName3 = $returnSet["jsonFileName3"];
        $jsonFileName4 = $returnSet["jsonFileName4"];

        $returnMsg["checkShiftCard"] = "0";
        $returnMsg["resultPos"] = $resultPos;
        if ($resultIDList[0][$resultPos] != PHP_INT_MAX)
        {
            $fileHandle = fopen($xmlFileName, "r+");
            $fileHandle2 = fopen($xmlFileName2, "r+");
            $tempFileHandle = fopen($tmpFileName, "r+");
        
            $returnMsg["checkNeedCreateReportFile"] = "1";
            $returnMsg["returnLine"] = "line: " . __LINE__;
            $returnSet = $xmlWriter->checkNeedCreateReportFile($xmlFileName, $xmlFileName2,
                                                               $tmpFileName, $jsonFileName, $jsonFileName2,
                                                               $jsonFileName3, $jsonFileName4,
                                                               $umdNum, $startResultID, $cmpMachineID, $resultPos,
                                                               $tempFileLineNumPos,
                                                               $curCardName, $tmpSysName,
                                                               $cmpCardName, $cmpSysName);
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
            fseek($fileHandle2, 0, SEEK_END);
            fseek($tempFileHandle, 0, SEEK_END);
            
            $returnMsg["returnLine"] = "line: " . __LINE__;
            $returnSet = $xmlWriter->checkStartSheet($fileHandle, $fileHandle2, $tempFileHandle,
                                                     $curTestPos, $nextSubTestPos, $firstTestPos, $firstSubTestPos,
                                                     $lineNumPos, $resultPos, $umdNum,
                                                     $tmpUmdName, $tmpCardName, $tmpSysName);
            if ($returnSet === null)
            {
                return;
            }
            $lineNumPos = $returnSet["lineNumPos"];
            
            fseek($fileHandle, 0, SEEK_END);
            fseek($fileHandle2, 0, SEEK_END);
            $returnMsg["returnLine"] = "line: " . __LINE__;
            $returnSet = $xmlWriter->checkShiftAPI($fileHandle, $fileHandle2, $resultPos, $tmpUmdName,
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
            fclose($fileHandle2);
        }
        
        
        
        $returnMsg["checkShiftCard"] = "1";
        $returnMsg["returnLine"] = "line: " . __LINE__;
        $returnSet = $xmlWriter->checkShiftCard($xmlFileName, $xmlFileName2,
                                                $tmpFileName, $tmpFileName1, 
                                                $jsonFileName, $jsonFileName2,
                                                $jsonFileName3, $jsonFileName4,
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
        
        $returnMsg["returnLine"] = "line: " . __LINE__;
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
    $xmlFileName2 = $returnSet["xmlFileName2"];
    $tmpFileName = $returnSet["tmpFileName"];
    // flatData
    $tmpFileName1 = $returnSet["tmpFileName1"];
    // summary
    $jsonFileName = $returnSet["jsonFileName"];
    $jsonFileName2 = $returnSet["jsonFileName2"];
    $jsonFileName3 = $returnSet["jsonFileName3"];
    $jsonFileName4 = $returnSet["jsonFileName4"];
    
    //$returnMsg["returnLine2"] = "line: " . __LINE__;
    
    $returnMsg["returnLine"] = "line: " . __LINE__;
    $returnSet = $xmlWriter->checkNeedCreateReportFile($xmlFileName, $xmlFileName2,
                                                       $tmpFileName, $jsonFileName, $jsonFileName2,
                                                       $jsonFileName3, $jsonFileName4,
                                                       $umdNum, $startResultID, $cmpMachineID, $resultPos,
                                                       $tempFileLineNumPos,
                                                       $curCardName, $tmpSysName,
                                                       $cmpCardName, $cmpSysName);
    if ($returnSet === null)
    {
        return;
    }
    $tempFileLineNumPos = $returnSet["tempFileLineNumPos"];
    
    
    $fileHandle = fopen($xmlFileName, "r+");
    $fileHandle2 = fopen($xmlFileName2, "r+");
    $tempFileHandle = fopen($tmpFileName, "r+");

    fseek($fileHandle, 0, SEEK_END);
    fseek($fileHandle2, 0, SEEK_END);
    fseek($tempFileHandle, 0, SEEK_END);
    
    if ($firstTestPos == -1)
    {
        $firstTestPos = $curTestPos;
        $firstSubTestPos = $nextSubTestPos;
    }
    
    $returnMsg["returnLine"] = "line: " . __LINE__;
    $returnSet = $xmlWriter->checkStartSheet($fileHandle, $fileHandle2, $tempFileHandle,
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
    
    $averageColumnHasVal = $returnSet["averageColumnHasVal"];
    $returnMsg["averageColumnHasVal"] = $averageColumnHasVal;
    
    $returnSet = $xmlWriter->checkStartTest($db, $fileHandle, $fileHandle2, $tempFileHandle,
                                            $nextSubTestPos, $firstSubTestPos, $curTestPos, 
                                            $isCompStandard, $cmpMachineID,
                                            $lineNum, $sheetLinePos, $tempLineNum);
    $lineNum = $returnSet["lineNum"];
    $sheetLinePos = $returnSet["sheetLinePos"];
    $tempLineNum = $returnSet["tempLineNum"];
    
    $returnSet = $xmlWriter->getHistoryResultIDList($resultPos);
    $historyResultIDList = $returnSet["historyResultIDList"];
    
    $returnMsg["returnLine"] = "line: " . __LINE__;
    $returnSet = $xmlWriter->writeReportData($db, $fileHandle, $fileHandle2, $tempFileHandle,
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
    
    fclose($fileHandle);
    fclose($fileHandle2);
    
    $returnMsg["returnLine"] = "line: " . __LINE__;
    $returnSet = $xmlWriter->writeReportCompareData($db, $tempFileHandle, $reportFolder,
                                                    $isCompStandard, $umdNum, $tempFileLineNumPos,
                                                    $startResultID, $cmpStartResultID, $historyStartResultID, $tempLineNum,
                                                    $resultPos, $sheetLinePos, $startGraphDataLinePos,
                                                    $averageColumnHasVal);
    if ($returnSet === null)
    {
        return;
    }
    $sheetLinePos = $returnSet["sheetLinePos"];

    fclose($tempFileHandle);
}


$returnMsg["isCompStandard"] = $isCompStandard;
$returnMsg["sheetLinePos"] = $sheetLinePos;
$returnMsg["tempFileLineNumPos"] = $tempFileLineNumPos;
$returnMsg["lineNumPos"] = $lineNumPos;
$returnMsg["resultPos"] = $resultPos;
$returnMsg["resultNum"] = count($resultIDList[0]);
$returnMsg["resultIDList"] = $resultIDList;
$returnMsg["cardNameList"] = $cardNameList;
$returnMsg["sysNameList"] = $sysNameList;
$returnMsg["machineIDList"] = $machineIDList;
$returnMsg["driverNameList"] = $driverNameList;
$returnMsg["curTestPos"] = $curTestPos;
$returnMsg["firstTestPos"] = $firstTestPos;
$returnMsg["firstSubTestPos"] = $firstSubTestPos;
$returnMsg["tmpUmdName"] = $tmpUmdName;
$returnMsg["testNum"] = count($testNameList);
$returnMsg["nextSubTestPos"] = $nextSubTestPos;
$returnMsg["subTestNum"] = $subTestNum;
$returnMsg["compileFinished"] = $compileFinished;
$returnMsg["reportToken"] = $reportToken;
$returnMsg["curReportFolder"] = $curReportFolder;
$returnMsg["batchID"] = $batchID;
$returnMsg["uniqueUmdNameList"] = $uniqueUmdNameList;
$returnMsg["subTestUmdDataMaskList"] = $subTestUmdDataMaskList;
$returnMsg["crossType"] = $crossType;

$returnMsg["returnLine"] = "line: " . __LINE__;

$t1 = json_encode($returnMsg);
echo $t1;

?>