<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

$batchID = intval($_POST["batchID"]);
//$batchID = 270;

$historyBatchMaxNum = 50;

$umdNameList = $swtUmdNameList;
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

$params1 = array($batchID);
$sql1 = "SELECT COUNT(*) " .
        "FROM mis_table_batch_list " .
        "WHERE batch_id = ? AND batch_state = 1 AND batch_group=\"1\"";
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
$batchNum = intval($row1[0]);

if ($batchNum == 0)
{
    // if not valid batchID, use latest batchID
    $params1 = array();
    $sql1 = "SELECT batch_id " .
            "FROM mis_table_batch_list " .
            "WHERE batch_state = 1 AND batch_group=\"1\" " .
            "ORDER BY insert_time DESC LIMIT 1";
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
        $returnMsg["errorMsg"] = "no valid batch ID found";
        echo json_encode($returnMsg);
        return;
    }
    $batchID = $row1[0];
}

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
    array_push($testNameList, $row1[5]);
    array_push($subjectNameList, $row1[6]);
    array_push($unitNameList, $row1[7]);
}

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
$reportFileNameList = array();

$batchName = sprintf("%05d", $batchID);
$reportFolder = "../" . $allReportsDirFromRoot . "/batch" . $batchID;

$params1 = array($batchID);
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
        "WHERE batch_id=? ORDER BY t1.sys_id, t1.card_id, t0.umd_id ASC";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . $db->getError()[2];
    echo json_encode($returnMsg);
    return;
}

$i = 0;
while ($row1 = $db->fetchRow())
{
    $tmpDriverName = $row1[21];
    // if this machine has umd less than DX11, DX12, Vulkan
    $tmpIndex = array_search($tmpDriverName, $umdNameList);
    if ($tmpIndex == (count($umdNameList) - 1))
    {
        if ($i != $tmpIndex)
        {
            for ($j = 0; $j < ($tmpIndex - $i); $j++)
            {
                array_push($resultIDList, PHP_INT_MAX);
                array_push($machineIDList, PHP_INT_MAX);
                array_push($cardIDList, $row1[10]);
                array_push($cardNameList, $row1[20]);
                array_push($driverNameList, $umdNameList[$j]);
                array_push($changeListNumList, PHP_INT_MAX);
                array_push($cpuNameList, $row1[22]);
                array_push($sysNameList, $row1[23]);
                array_push($mainLineNameList, $row1[24]);
                array_push($resultTimeList, $row1[7]);
                array_push($reportFileNameList, $row1[20] . "_" . $row1[23] . "_batch" . $batchName . ".zip");
            }
            $i = $tmpIndex;
        }
    }
    $i++;
    if ($i >= count($umdNameList))
    {
        $i = 0;
    }
    
    array_push($resultIDList, $row1[0]);
    array_push($machineIDList, $row1[1]);
    array_push($cardIDList, $row1[10]);
    array_push($cardNameList, $row1[20]);
    array_push($driverNameList, $row1[21]);
    array_push($changeListNumList, $row1[4]);
    array_push($cpuNameList, $row1[22]);
    array_push($sysNameList, $row1[23]);
    array_push($mainLineNameList, $row1[24]);
    array_push($resultTimeList, $row1[7]);
    array_push($reportFileNameList, $row1[20] . "_" . $row1[23] . "_batch" . $batchName . ".zip");
}

$tmpCardIDList = array_unique($cardIDList);
$tmpCardNameList = array_unique($cardNameList);
$tmpDriverNameList = array_unique($driverNameList);
$tmpSysNameList = array_unique($sysNameList);
$tmpMachineIDList = array_unique($machineIDList);

$uniqueCardIDList = array();
$uniqueCardNameList = array();
$uniqueDriverNameList = array();
$uniqueSysNameList = array();
$uniqueMachineIDList = array();

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
foreach ($tmpSysNameList as $tmpName)
{
    array_push($uniqueSysNameList, $tmpName);
}
foreach ($tmpMachineIDList as $tmpName)
{
    if (intval($tmpName) == PHP_INT_MAX)
    {
        continue;
    }
    array_push($uniqueMachineIDList, $tmpName);
}

$cardNum = count($uniqueCardNameList);
$umdNum = count($uniqueDriverNameList);
$machineNum = count($uniqueMachineIDList);

/*
$cardPosID = array_search($cardID, $uniqueCardIDList);
if ($cardPosID === false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no valid card ID found";
    echo json_encode($returnMsg);
    return;
}
//*/

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
for ($i = 0; $i < count($testNameList); $i++)
{
    //continue;
    $testName = $testNameList[$i];
    $tableName01 = $db_mis_table_name_string001 . $testName;
    $testUnit = $unitNameList[$i];
    $testID = $testIDList[$i];
    for ($k = 0; $k < $machineNum; $k++)
    {
        for ($j = 0; $j < $umdNum; $j++)
        {
            $n1 = $k * $umdNum;
            $tmpResultID = $resultIDList[$n1 + $j];
            $tmpDriverName = $driverNameList[$n1 + $j];
            $testVal = 0;
            if ($tmpResultID != PHP_INT_MAX)
            {
                // if resultID valid
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
            }
            array_push($testValList, $testVal);
            
        }
    }
    //array_push($testValList, $tmpTestValList);
}

$returnMsg["machineNum"] = $machineNum;
$returnMsg["resultIDList"] = $resultIDList;
$returnMsg["uniqueMachineIDList"] = $uniqueMachineIDList;
$returnMsg["uniqueCardNameList"] = $uniqueCardNameList;
$returnMsg["uniqueCardIDList"] = $uniqueCardIDList;
$returnMsg["uniqueDriverNameList"] = $uniqueDriverNameList;
$returnMsg["uniqueSysNameList"] = $uniqueSysNameList;
$returnMsg["cardNameList"] = $cardNameList;
$returnMsg["sysNameList"] = $sysNameList;
$returnMsg["testNameList"] = $testNameList;
$returnMsg["testIDList"] = $testIDList;
$returnMsg["testValList"] = $testValList;
$returnMsg["machineIDList"] = $machineIDList;
$returnMsg["reportFileNameList"] = $reportFileNameList;
$returnMsg["reportFolder"] = $reportFolder;

echo json_encode($returnMsg);


?>