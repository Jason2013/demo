<?php

include_once "../generalLibs/dopdo.php";
include_once "../generalLibs/dodb.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../userManage/swtUserManager.php";

$oneTimeDoTestCaseNum = 1000;

$batchID = intval($_POST["batchID"]);
$resultPos = intval($_POST["resultPos"]);
$testPos = intval($_POST["testPos"]);
$testCasePos = intval($_POST["testCasePos"]);
$curTestCaseNum = intval($_POST["curTestCaseNum"]);
$curTestNoiseNum = intval($_POST["curTestNoiseNum"]);

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "import result success.";

if ($batchID == -1)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "invalid batchID, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$db = new CPdoMySQL();
if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

// get testName list
$params1 = array($batchID);
$sql1 = "SELECT DISTINCT t0.test_id, t0.list_id, " .
        "(SELECT t1.test_name FROM mis_table_test_info t1 WHERE t1.test_id=t0.test_id) AS testName " .
        "FROM mis_table_test_subject_list t0 " .
        "WHERE t0.batch_id=? ORDER BY t0.list_id ASC";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
    echo json_encode($returnMsg);
    return null;
}

$testNameList = array();

while ($row1 = $db->fetchRow())
{
    array_push($testNameList, $row1[2]);
}
$returnMsg["testNameList"] = $testNameList;


// get resultID list

$params1 = array($batchID);
$sql1 = "SELECT result_id FROM mis_table_result_list " .
        "WHERE batch_id=?";

if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
    echo json_encode($returnMsg);
    return null;
}

$resultIDList = array();

while ($row1 = $db->fetchRow())
{
    array_push($resultIDList, $row1[0]);
}
$returnMsg["resultIDList"] = $resultIDList;


function swtGetAverageDataList($_dataList)
{
    if (count($_dataList) < 3)
    {
        $returnArr = array();
        for ($i = 0; $i < count($_dataList); $i++)
        {
            $returnArr []= $i;
        }
        return $returnArr;
    }
    
    $n1 = min($_dataList);
    
    $arr = array();
    for ($i = 0; $i < count($_dataList); $i++)
    {
        $arr []= abs($_dataList[$i] - $n1);
    }
    
    asort($arr);
    
    $arrValList = array();
    $arrKeyList = array();
    
    foreach ($arr as $k => $v)
    {
        $arrKeyList []= $k;
        $arrValList []= $v;
    }
    
    $minDist = -1;
    $dataIndex = -1;
    for ($i = 2; $i < count($arrValList); $i++)
    {
        $n2 = abs($arrValList[$i] - $arrValList[$i - 2]);
        if (($n2      <  $minDist) ||
            ($minDist == -1))
        {
            $minDist = $n2;
            $dataIndex = $i;
        }
    }
          
    $returnArr = array($arrKeyList[$dataIndex - 2],
                       $arrKeyList[$dataIndex - 1],
                       $arrKeyList[$dataIndex - 0]);
    sort($returnArr);
    return $returnArr;
}

function swtGetAverageAndVariance ($_sortArrayList, $_averageIndexList)
{
    $sortArrayList = $_sortArrayList;
    $averageIndexList = $_averageIndexList;
    
    $tmpSum = 0.0;
    for ($i = 0; $i < count($averageIndexList); $i++)
    {
        $tmpSum += floatval($sortArrayList[$averageIndexList[$i]]);
    }
    $tmpAvg = $tmpSum / count($averageIndexList);
    
    $tmpSum = 0.0;
    
    for ($i = 0; $i < count($averageIndexList); $i++)
    {
        if ($tmpAvg < 0.00001)
        {
            $f2 = 0.0;
        }
        else
        {
            $f1 = floatval($sortArrayList[$averageIndexList[$i]]);
            $f2 = ($f1 - $tmpAvg) / $tmpAvg;
            $tmpSum += ($f2 * $f2);
        }
    }
    
    $tmpVariance = sqrt($tmpSum / count($averageIndexList));
    
    $tmpRet = array();
    $tmpRet["tmpAvg"] = $tmpAvg;
    $tmpRet["tmpVariance"] = $tmpVariance;
    return $tmpRet;
}

function swtGetTmpFileName()
{
    global $swtTempFilesDir;
    
    while (1)
    {
        $tmpToken = md5("sql_script_" . time());
        $tmpFileName = $swtTempFilesDir . "/sqlTmpFile" . $tmpToken . ".txt";
        if (file_exists($tmpFileName) == false)
        {
            $tmpRes = @file_put_contents($tmpFileName, "");
            if ($tmpRes !== false)
            {
                return $tmpFileName;
            }
        }
    }
}

$returnMsg["parseFinished"] = 0;

if ($testPos >= count($testNameList))
{
    $testPos = 0;
    $testCasePos = 0;
    $resultPos++;
    if ($resultPos >= count($resultIDList))
    {
        // all tasks of this batch are finished
        // set batch finished
        
        $params1 = array($batchID);
        $sql1 = "UPDATE mis_table_batch_list " .
                "SET batch_state = \"1\" " .
                "WHERE (batch_state=\"4\") AND (batch_id=?)";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3" . $db->getError()[2];
            echo json_encode($returnMsg);
            return;
        }
        
        $returnMsg["parseFinished"] = 1;
        echo json_encode($returnMsg);
        return;
    }
}
else
{
    // do insert average
    
    $curTestName = $testNameList[$testPos];
    
    $tmpTestName = str_replace(" ", "", $curTestName);
    $tmpTestName = cleaninput($tmpTestName, 256);
    //$tmpTableName01 = $db_mis_table_name_string003 . $curTestName;
    $tmpTableName01 = $db_mis_table_name_string003 . $tmpTestName;
    //$tmpTableName02 = $db_mis_table_name_string003 . $curTestName . "_noise";
    
    $returnMsg["curTestName"] = $curTestName;
    $returnMsg["tmpTableName01"] = $tmpTableName01;
    //$returnMsg["tmpTableName02"] = $tmpTableName02;
    
    if ($testCasePos == 0)
    {
        
        $tmpBatchFolder = $swtTempFilesDir . "/batch" . $batchID;
        
        $tmpNoiseNum = 0;
        while (true)
        {
            $tmpFileName = $tmpBatchFolder . "/dataFile_" . 
                                             $resultIDList[$resultPos]  . "_" .
                                             $curTestName               . "_" .
                                             $tmpNoiseNum               . ".txt";
                                             
            if (file_exists($tmpFileName) == false)
            {
                break;
            }
            $tmpNoiseNum++;
        }
        $tmpDataNum = 0;
        if ($tmpNoiseNum > 0)
        {
            $tmpFileName = $tmpBatchFolder . "/dataFile_" . 
                                             $resultIDList[$resultPos]  . "_" .
                                             $curTestName               . "_" .
                                             0                          . ".txt";
                                             
            $n1 = filesize($tmpFileName);
            
            $t1 = pack("d", 0.0);
            $tmpDataSize = strlen($t1);
            $t1 = pack("i", 0);
            $tmpDataIDSize = strlen($t1);
            
            $tmpDataNum = intval($n1 / ($tmpDataSize * 4 + $tmpDataIDSize * 5));
        }
        
        
        $curTestCaseNum = $tmpDataNum;
        $returnMsg["curTestCaseNum"] = $curTestCaseNum;
        
        $curTestNoiseNum = $tmpNoiseNum;
        $returnMsg["curTestNoiseNum"] = $curTestNoiseNum;
    }
    
    
    if ($testCasePos >= $curTestCaseNum)
    {
        // go to next test
        $testCasePos = 0;
        $testPos++;
    }
    else
    {
        $tmpDoTestCaseNum = $oneTimeDoTestCaseNum;
        if (($testCasePos + $tmpDoTestCaseNum) > $curTestCaseNum)
        {
            $tmpDoTestCaseNum = $curTestCaseNum - $testCasePos;
        }
        
        // get noise num
        
        $tmpBatchFolder = $swtTempFilesDir . "/batch" . $batchID;
        
        $t1 = pack("d", 0.0);
        $tmpDataSize = strlen($t1);
        $t1 = pack("i", 0);
        $tmpDataIDSize = strlen($t1);
        
        $tmpFileHandleList = array();
        for ($i = 0; $i < $curTestNoiseNum; $i++)
        {
            $tmpFileName2 = $tmpBatchFolder . "/dataFile_" . 
                                             $resultIDList[$resultPos]  . "_" .
                                             $curTestName               . "_" .
                                             $i                         . ".txt";
                                             
            $tmpFileHandle = fopen($tmpFileName2, "r");
            if ($tmpFileHandle === false)
            {
                break;
            }
            $tmpFileHandleList []= $tmpFileHandle;
        }
        
        $testAverageData = "";
        for ($j = 0; $j < $tmpDoTestCaseNum; $j++)
        {
            $tmpSubTestID = -1;
            //$tmpTestCaseID = -1;
            $tmpFrameID = -1;
            $tmpGroupID = -1;
            // 0 for N/A, 1 for PASS, 2 for FAIL
            $tmpVerifyStatus = 0;
            $tmpPassRate = 0.0;
            $isFirstNoiseUpdated = false;
            
            $tmpFileDataPos = $testCasePos + $j;
            $tmpFileDataOffset = $tmpFileDataPos * ($tmpDataSize * 4 + $tmpDataIDSize * 5);
            //$tmpTestCaseID = $tmpFileDataPos;
            
            $sortArrayList = array();
            $sortArrayList2 = array();
            $sortArrayList3 = array();
            $sortArrayList4 = array();
            
            foreach ($tmpFileHandleList as $tmpFileHandle)
            {
                fseek($tmpFileHandle, $tmpFileDataOffset, SEEK_SET);
                $t1 = fread($tmpFileHandle, $tmpDataSize * 4 + $tmpDataIDSize * 5);
                $valSet = array();
                if (strlen($t1) < ($tmpDataSize * 4 + $tmpDataIDSize * 5))
                {
                    continue;
                }
                else
                {
                    $valSet = unpack("i1subTestID/d1dataValue1/d1dataValue2/d1dataValue3/d1dataValue4/" .
                                     "i1testCaseID/i1groupID/i1frameID/i1verifyStatus", $t1);
                }
                
                $tmpSubTestID = $valSet["subTestID"];
                $tmpTestCaseID = $valSet["testCaseID"];
                $tmpGroupID = $valSet["groupID"];
                $tmpFrameID = $valSet["frameID"];
                
                if ($isFirstNoiseUpdated == false)
                {
                    $tmpVerifyStatus = $valSet["verifyStatus"];
                    $tmpPassRate = floatval($valSet["dataValue3"]);
                    $isFirstNoiseUpdated = true;
                }
                
                $f1 = floatval($valSet["dataValue1"]);
                if ($f1 >= 0.0)
                {
                    $sortArrayList []= $f1;
                }
                $f1 = floatval($valSet["dataValue2"]);
                if ($f1 >= 0.0)
                {
                    $sortArrayList2 []= $f1;
                }
                $f1 = floatval($valSet["dataValue3"]);
                if ($f1 >= 0.0)
                {
                    $sortArrayList3 []= $f1;
                }
                $f1 = floatval($valSet["dataValue4"]);
                if ($f1 >= 0.0)
                {
                    $sortArrayList4 []= $f1;
                }
            }
            
            if (intval($tmpSubTestID) == -1)
            {
                // if invalid test case id
                continue;
            }
        
            $averageIndexList = swtGetAverageDataList($sortArrayList);
            $averageIndexList2 = swtGetAverageDataList($sortArrayList2);
            $averageIndexList3 = swtGetAverageDataList($sortArrayList3);
            $averageIndexList4 = swtGetAverageDataList($sortArrayList4);
            
            $tmpRet = swtGetAverageAndVariance($sortArrayList, $averageIndexList);
            $tmpAvg = $tmpRet["tmpAvg"];
            $tmpVariance = $tmpRet["tmpVariance"];
            $tmpRet = swtGetAverageAndVariance($sortArrayList2, $averageIndexList2);
            $tmpAvg2 = $tmpRet["tmpAvg"];
            $tmpVariance2 = $tmpRet["tmpVariance"];
            $tmpRet = swtGetAverageAndVariance($sortArrayList3, $averageIndexList3);
            $tmpAvg3 = $tmpRet["tmpAvg"];
            $tmpVariance3 = $tmpRet["tmpVariance"];
            $tmpRet = swtGetAverageAndVariance($sortArrayList4, $averageIndexList4);
            $tmpAvg4 = $tmpRet["tmpAvg"];
            $tmpVariance4 = $tmpRet["tmpVariance"];
            
            
            $testAverageData .= "" . $resultIDList[$resultPos] . "," .
                                $tmpSubTestID . "," . 
                                $tmpAvg . "," . 
                                $tmpVariance . "," . 
                                $tmpAvg2 . "," . 
                                $tmpVariance2 . "," . 
                                $tmpPassRate . "," . 
                                $tmpVariance3 . "," . 
                                $tmpAvg4 . "," . 
                                $tmpVariance4 . "," . 
                                $tmpTestCaseID . "," .
                                $tmpGroupID . "," .
                                $tmpFrameID . "," .
                                $tmpVerifyStatus . "\n";
        }
        
        foreach ($tmpFileHandleList as $tmpFileHandle)
        {
            fclose($tmpFileHandle);
        }
        $tmpFileHandleList = array();
        
        
        if (strlen($testAverageData) > 0)
        {
            error_reporting(E_ALL ^ E_DEPRECATED);
            
            $tmpFileName = swtGetTmpFileName();
            
            file_put_contents($tmpFileName, $testAverageData);
            $tmpPathName = dirname(__FILE__) . "/" . $tmpFileName;
            $tmpPathName = str_replace("\\", "/", $tmpPathName);
            
            $returnMsg["tmpPathName"] = $tmpPathName;
            
            $db2 = new CDoMySQL($db_username, $db_password);
            if ($db2->ReadyDB() != true)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "can't reach mysql server. line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            
            $sql1 = "LOAD DATA LOCAL INFILE \"" . $tmpPathName . "\" IGNORE INTO TABLE " . $tmpTableName01 . " " .
                    "FIELDS TERMINATED BY ',' " .
                    "LINES TERMINATED BY '\n' (result_id, sub_id," .
                    " data_value1, variance_value1," .
                    " data_value2, variance_value2," .
                    " data_value3, variance_value3," .
                    " data_value4, variance_value4," .
                    " test_case_id, group_id, frame_id, verify_status);";
            if ($db2->QueryDBNoResult($sql1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #4, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }

            if (is_writable($tmpFileName))
            {
                @unlink($tmpFileName);
            }
        }
        
        $returnMsg["tmpDoTestCaseNum"] = $tmpDoTestCaseNum;
        
        $testCasePos += $tmpDoTestCaseNum;
    }
    
}

$returnMsg["batchID"] = $batchID;
$returnMsg["resultPos"] = $resultPos;
$returnMsg["resultIDList"] = $resultIDList;
$returnMsg["testPos"] = $testPos;
$returnMsg["testCasePos"] = $testCasePos;
$returnMsg["resultNum"] = count($resultIDList);
$returnMsg["testNum"] = count($testNameList);
$returnMsg["curTestCaseNum"] = $curTestCaseNum;
$returnMsg["curTestNoiseNum"] = $curTestNoiseNum;

echo json_encode($returnMsg);
return;

?>