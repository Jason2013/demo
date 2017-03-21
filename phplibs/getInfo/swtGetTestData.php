<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

$cardID = intval($_POST["cardPosID"]);
$testID = intval($_POST["testPosID"]);
$subTestPosID = intval($_POST["subTestPosID"]);

$historyBatchMaxNum = 50;

$umdStandardOrder = $swtUmdStandardOrder;

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "compile report success";

$db = new CPdoMySQL();
if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$batchIDList = array();
$batchTimeList = array();

$params1 = array();
$sql1 = "SELECT batch_id, insert_time " .
        "FROM mis_table_batch_list " .
        "WHERE batch_state=\"1\" AND batch_group=\"1\" ORDER BY insert_time DESC LIMIT " . $historyBatchMaxNum;
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

while ($row1 = $db->fetchRow())
{
    array_push($batchIDList, intval($row1[0]));
    array_push($batchTimeList, $row1[1]);
}

if (count($batchIDList) == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no valid batch ID found";
    echo json_encode($returnMsg);
    return;
}

// use latest batch as template
$batchID = $batchIDList[0];

$params1 = array($batchID);
$sql1 = "SELECT t0.*, " .
        "(SELECT t1.test_name FROM mis_table_test_info t1 WHERE t1.test_id=t0.test_id) AS testName, " .
        "(SELECT t2.test_name FROM mis_table_test_info t2 WHERE t2.test_id=t0.subject_id) AS subjectName, " .
        "(SELECT t3.test_name FROM mis_table_test_info t3 WHERE t3.test_id=t0.unit_id) AS unitName " .
        "FROM mis_table_test_subject_list t0 " .
        "WHERE t0.batch_id=? ORDER BY t0.list_id ASC";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
    echo json_encode($returnMsg);
    return;
}

$testIDList = array();
$testNameList = array();
$subjectNameList = array();
$unitNameList = array();

while ($row1 = $db->fetchRow())
{
    array_push($testIDList, $row1[2]);
    array_push($testNameList, $row1[6]);
    array_push($subjectNameList, $row1[7]);
    array_push($unitNameList, $row1[8]);
}

/*
if ($curTestPos >= count($testNameList))
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input test pos invalid";
    echo json_encode($returnMsg);
    return;
}

$testName = $testNameList[$curTestPos];
$tableName01 = $db_mis_table_name_string001 . $testName;
//*/

$resultIDList = array();
$machineIDList = array();
$cardIDList = array();
$cardNameList = array();
$driverNameList = array();
$changeListNumList = array();
$cpuNameList = array();
$sysNameList = array();
$mainLineNameList = array();
$resultTimeList = array();

foreach ($batchIDList as $tmpBatchID)
{
    $tmpResultIDList = array();
    $tmpMachineIDList = array();
    $tmpCardIDList = array();
    $tmpCardNameList = array();
    $tmpDriverNameList = array();
    $tmpChangeListNumList = array();
    $tmpCpuNameList = array();
    $tmpSysNameList = array();
    $tmpMainLineNameList = array();
    $tmpResultTimeList = array();
    $params1 = array($tmpBatchID);
    $sql1 = "SELECT t0.*, " .
            "t1.*, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.card_id) AS cardName, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t0.umd_id) AS umdName, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.cpu_id) AS cpuName, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.sys_id) AS sysName, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.ml_id) AS mlName " .
            "FROM mis_table_result_list t0 " .
            "LEFT JOIN mis_table_machine_info t1 " .
            "USING (machine_id) " .
            "WHERE batch_id=? ORDER BY t0.machine_id, t0.umd_id ASC";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . $db->getError()[2];
        echo json_encode($returnMsg);
        return;
    }

    while ($row1 = $db->fetchRow())
    {
        array_push($tmpResultIDList, $row1[0]);
        array_push($tmpMachineIDList, $row1[1]);
        array_push($tmpCardIDList, $row1[10]);
        array_push($tmpCardNameList, $row1[20]);
        array_push($tmpDriverNameList, $row1[21]);
        array_push($tmpChangeListNumList, $row1[4]);
        array_push($tmpCpuNameList, $row1[22]);
        array_push($tmpSysNameList, $row1[23]);
        array_push($tmpMainLineNameList, $row1[24]);
        array_push($tmpResultTimeList, $row1[7]);
    }
    array_push($resultIDList, $tmpResultIDList);
    array_push($machineIDList, $tmpMachineIDList);
    array_push($cardIDList, $tmpCardIDList);
    array_push($cardNameList, $tmpCardNameList);
    array_push($driverNameList, $tmpDriverNameList);
    array_push($changeListNumList, $tmpChangeListNumList);
    array_push($cpuNameList, $tmpCpuNameList);
    array_push($sysNameList, $tmpSysNameList);
    array_push($mainLineNameList, $tmpMainLineNameList);
    array_push($resultTimeList, $tmpResultTimeList);
}

$tmpCardIDList = array_unique($cardIDList[0]);
$tmpCardNameList = array_unique($cardNameList[0]);
$tmpDriverNameList = array_unique($driverNameList[0]);

$uniqueCardIDList = array();
$uniqueCardNameList = array();
$uniqueDriverNameList = array();

foreach ($tmpCardIDList as $tmpName)
{
    array_push($uniqueCardIDList, $tmpName);
}
foreach ($tmpCardNameList as $tmpName)
{
    array_push($uniqueCardNameList, $tmpName);
}
foreach ($tmpDriverNameList as $tmpName)
{
    array_push($uniqueDriverNameList, $tmpName);
}

$cardNum = count($uniqueCardNameList);
$umdNum = count($uniqueDriverNameList);

$cardPosID = array_search($cardID, $uniqueCardIDList);
if ($cardPosID === false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no valid card ID found";
    echo json_encode($returnMsg);
    return;
}
$testPosID = array_search($testID, $testIDList);
if ($testPosID === false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no valid test ID found";
    echo json_encode($returnMsg);
    return;
}
$testName = $testNameList[$testPosID];
$tableName01 = $db_mis_table_name_string001 . $testName;
$testUnit = $unitNameList[$testPosID];

$umdNameStandard = "";
for ($i = 0; $i < count($umdStandardOrder); $i++)
{
    // find a standard umd name
    $tmpPos = array_search($umdStandardOrder[$i], $uniqueDriverNameList);
    if ($tmpPos !== false)
    {
        $umdNameStandard = $umdStandardOrder[$i];
        break;
    }
}

$testValList = array();
$subTestNum = 0;
$subTestName = "";
for ($i = 0; $i < count($batchIDList); $i++)
{
    //$tmpTestValList = array();
    for ($j = 0; $j < $umdNum; $j++)
    {
        $n1 = $cardPosID * $umdNum;
        $tmpResultID = $resultIDList[$i][$n1 + $j];
        $tmpDriverName = $driverNameList[$i][$n1 + $j];
        
        if ($subTestPosID == -1)
        {
            $params1 = array($tmpResultID, $testID);
            $sql1 = "SELECT data_value " .
                    "FROM mis_table_average_test_data " .
                    "WHERE result_id=? AND test_id=?";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                echo json_encode($returnMsg);
                return;
            }
            $row1 = $db->fetchRow();
            $testVal = 0;
            if ($row1 == false)
            {
                $params1 = array($tmpResultID);
                $sql1 = "SELECT AVG(data_value) " .
                        "FROM " . $tableName01 . " " .
                        "WHERE result_id=?";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return;
                }
                $row1 = $db->fetchRow();
                if ($row1 != false)
                {
                    $testVal = $row1[0];
                }
                
                $params1 = array($tmpResultID, $testID, $testVal);
                $sql1 = "INSERT INTO mis_table_average_test_data " .
                        "(result_id, test_id, data_value) " .
                        "VALUES (?, ?, ?)";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["sql1"] = $sql1;
                    $returnMsg["errorMsg"] = "query mysql table failed #2b, line: " . __LINE__;
                    return -1;
                }
                $resultID = $db->getInsertID();
            }
            else
            {
                // if has former record
                $testVal = $row1[0];
            }
            array_push($testValList, $testVal);
        }
        else
        {
            $params1 = array($tmpResultID, $subTestPosID);
            $sql1 = "SELECT t0.sub_id, t0.data_value, t1.test_name " .
                    "FROM " . $tableName01 . " t0 " .
                    "LEFT JOIN mis_table_test_info t1 " .
                    "ON (t0.sub_id = t1.test_id) " .
                    "WHERE t0.result_id=? LIMIT ?, 1";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                echo json_encode($returnMsg);
                return;
            }
            $row1 = $db->fetchRow();
            $testVal = 0;
            if ($row1 != false)
            {
                $testVal = $row1[1];
            }
            if ($i == 0 && $j == 0)
            {
                $subTestName = $row1[2];
            }
            array_push($testValList, $testVal);
        }
        
        if (($i == 0) && (strlen($umdNameStandard) > 0))
        {
            if (strcmp($umdNameStandard, $tmpDriverName) == 0)
            {
                // get subtest num
                $params1 = array($tmpResultID);
                $sql1 = "SELECT COUNT(*) " .
                        "FROM " . $tableName01 . " " .
                        "WHERE result_id=?";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return;
                }
                $row1 = $db->fetchRow();
                if ($row1 == false)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return;
                }
                $subTestNum = $row1[0];
            }
        }
    }
    //array_push($testValList, $tmpTestValList);
}


$returnMsg["uniqueCardNameList"] = $uniqueCardNameList;
$returnMsg["uniqueCardIDList"] = $uniqueCardIDList;
$returnMsg["uniqueDriverNameList"] = $uniqueDriverNameList;
$returnMsg["testName"] = $testName;
$returnMsg["testNameList"] = $testNameList;
$returnMsg["testIDList"] = $testIDList;
$returnMsg["batchTimeList"] = $batchTimeList;
$returnMsg["testUnit"] = $testUnit;
$returnMsg["testValList"] = $testValList;
$returnMsg["subTestNum"] = $subTestNum;
$returnMsg["subTestName"] = $subTestName;

echo json_encode($returnMsg);


?>