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
$testIDList = array();

while ($row1 = $db->fetchRow())
{
    array_push($testNameList, $row1[2]);
    array_push($testIDList, $row1[0]);
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
        
        $tmpBatchFolder = $swtTempFilesDir . "/batch" . $batchID;
        if (file_exists($tmpBatchFolder))
        {
            //swtDelFileTree($tmpBatchFolder);
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
    $curTestID = $testIDList[$testPos];
    
    $tmpTableName01 = $db_mis_table_name_string001 . $curTestName;
    $tmpTableName02 = $db_mis_table_name_string001 . $curTestName . "_noise";
    
    $returnMsg["curTestName"] = $curTestName;
    $returnMsg["tmpTableName01"] = $tmpTableName01;
    $returnMsg["tmpTableName02"] = $tmpTableName02;
    
    // clear expired 5 noise data
    //if (($resultPos == 0) &&
    //    ($testCasePos == 0))
    //{
    //    $params1 = array();
    //    $sql1 = "SELECT DISTINCT t0.result_id, " .
    //            "DATEDIFF(NOW(), (SELECT t1.insert_time FROM mis_table_result_list t1 WHERE t1.result_id=t0.result_id)) " .
    //            "FROM " . $tmpTableName02 . " t0;";
    //    
    //    if ($db->QueryDB($sql1, $params1) == null)
    //    {
    //        $returnMsg["errorCode"] = 0;
    //        $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
    //        echo json_encode($returnMsg);
    //        return null;
    //    }
    //    
    //    $expiredResultIDList = array();
    //    
    //    while ($row1 = $db->fetchRow())
    //    {
    //        $tmpDays = intval($row1[1]);
    //        
    //        if ($tmpDays >= $swtNoiseDataExpireDayNum)
    //        {
    //            array_push($expiredResultIDList, $row1[0]);
    //        }
    //    }
    //    
    //    foreach ($expiredResultIDList as $tmpResultID)
    //    {
    //        $params1 = array($tmpResultID);
    //        $sql1 = "DELETE FROM " . $tmpTableName02 . " " .
    //                "WHERE result_id=?";
    //        if ($db->QueryDB($sql1, $params1) == null)
    //        {
    //            $returnMsg["errorCode"] = 0;
    //            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
    //            echo json_encode($returnMsg);
    //            return null;
    //        }
    //    }
    //}
    

    
    // calc and insert average
    
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
            
            $tmpDataNum = intval($n1 / ($tmpDataSize + $tmpDataIDSize));
        }
        
        
        $curTestCaseNum = $tmpDataNum;
        $returnMsg["curTestCaseNum"] = $curTestCaseNum;
        
        $curTestNoiseNum = $tmpNoiseNum;
        $returnMsg["curTestNoiseNum"] = $curTestNoiseNum;
    }
    
    // insert runlog test runtime tables
    if (($testPos     == 0) &&
        ($testCasePos == 0))
    {
        for ($j = 0; $j < count($testNameList); $j++)
        {
            // insert runlog test runtime table
            $tmpTestName = $testNameList[$j];
            $tmpTestID = $testIDList[$j];
            $tmpResultID = $resultIDList[$resultPos];
            
            //$tmpJsonPath = $reportFolder . "/" . $swtTempRunLogJsonName;
            $tmpJsonPath = $swtTempFilesDir . "/batch" . $batchID . "/" . $swtTempRunLogJsonName;
            
            $tmpObj = array();
            if (file_exists($tmpJsonPath))
            {
                $t2 = file_get_contents($tmpJsonPath);
                $tmpObj = json_decode($t2, true);
            }
            
            $sortArrayList = array();
            for ($i = 0; $i < $curTestNoiseNum; $i++)
            {
                $tmpKey = $tmpResultID               . "_" .
                          strtolower($tmpTestName)   . "_" .
                          $i;
                if (array_key_exists($tmpKey, $tmpObj))
                {
                    $sortArrayList []= floatval($tmpObj[$tmpKey]);
                }
            }
            
            $tmpAvg = 0.0;
            $tmpVariance = 0.0;
            
            if (count($sortArrayList) > 0)
            {
                $averageIndexList = swtGetAverageDataList($sortArrayList);
                
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
            }
            
            
            $params1 = array($tmpResultID, $tmpTestID, $tmpAvg, $tmpVariance);
            $sql1 = "INSERT IGNORE INTO mis_table_data_test_Statistics " .
                    "(result_id, test_id, data_value, data_value2) " .
                    "VALUES (?, ?, ?, ?)";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3" . $db->getError()[2] . ", line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
        }
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
            $tmpTestCaseID = -1;
            
            $tmpFileDataPos = $testCasePos + $j;
            $tmpFileDataOffset = $tmpFileDataPos * ($tmpDataSize + $tmpDataIDSize);
            $tmpTestCaseID = $tmpFileDataPos;
            
            $sortArrayList = array();
            foreach ($tmpFileHandleList as $tmpFileHandle)
            {
                fseek($tmpFileHandle, $tmpFileDataOffset, SEEK_SET);
                $t1 = fread($tmpFileHandle, $tmpDataSize + $tmpDataIDSize);
                $valSet = array();
                if (strlen($t1) < ($tmpDataSize + $tmpDataIDSize))
                {
                    continue;
                }
                else
                {
                    $valSet = unpack("i1subTestID/d1dataValue", $t1);
                }
                
                $tmpSubTestID = $valSet["subTestID"];
                
                $f1 = floatval($valSet["dataValue"]);
                if ($f1 > 0.0)
                {
                    $sortArrayList []= $f1;
                }
            }
            
            $tmpAvg = 0.0;
            $tmpVariance = 0.0;
            
            if (count($sortArrayList) > 0)
            {
                $averageIndexList = swtGetAverageDataList($sortArrayList);
                
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
            }
            
            $testAverageData .= "" . $resultIDList[$resultPos] . "," .
                                $tmpSubTestID . "," . $tmpAvg . "," . $tmpVariance . "," . $tmpTestCaseID . "\n";
            
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
                    "LINES TERMINATED BY '\n' (result_id, sub_id, data_value, data_value2, test_case_id);";
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