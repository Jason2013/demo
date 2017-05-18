<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

$batchID = intval($_POST["batchID"]);

$historyBatchMaxNum = 50;

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "compile report success";

$umdNameList = $swtUmdNameList;


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
    array_push($testNameList, $row1[6]);
    array_push($subjectNameList, $row1[7]);
    array_push($unitNameList, $row1[8]);
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
$umdIndex = 0;
$cardIndex = -1;
$curCardID = -1;
$curSysID = -1;
$umdNum = count($umdNameList);
while ($row1 = $db->fetchRow())
{
    $tmpCardID = intval($row1[10]);
    $tmpSysID = intval($row1[12]);
    $tmpDriverName = $row1[21];
    
    if ($umdIndex == 0)
    {
        $curCardID = $tmpCardID;
        $curSysID = $tmpSysID;
        $cardIndex++;
        // hold enough space
        for ($j = 0; $j < $umdNum; $j++)
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
            //array_push($reportFileNameList, $row1[20] . "_" . $row1[23] . "_batch" . $batchName . ".zip");
        }
    }
    else
    {
        if (($curCardID != $tmpCardID) ||
            ($curSysID  != $tmpSysID))
        {
            // next card
            // e.g. tmpCardNameList:   jan26, jan31
            //      tmpDriverNameList: DX12, DX12
            $curCardID = $tmpCardID;
            $curSysID = $tmpSysID;
            $cardIndex++;
            // hold enough space
            for ($j = 0; $j < $umdNum; $j++)
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
                //array_push($reportFileNameList, $row1[20] . "_" . $row1[23] . "_batch" . $batchName . ".zip");
            }
            $umdIndex = 0;
        }
    }

    $tmpIndex = array_search($tmpDriverName, $umdNameList);
    if ($tmpIndex !== false)
    {
        $n1 = $cardIndex * $umdNum + $tmpIndex;
        
        $resultIDList[$n1] = $row1[0];
        $machineIDList[$n1] = $row1[1];
        $cardIDList[$n1] = $row1[10];
        $cardNameList[$n1] = $row1[20];
        $driverNameList[$n1] = $row1[21];
        $changeListNumList[$n1] = $row1[4];
        $cpuNameList[$n1] = $row1[22];
        $sysNameList[$n1] = $row1[23];
        $mainLineNameList[$n1] = $row1[24];
        $resultTimeList[$n1] = $row1[7];
        //$reportFileNameList[$n1] = $row1[20] . "_" . $row1[23] . "_batch" . $batchName . ".zip";
    }
    if ($umdIndex != $tmpIndex)
    {
        $umdIndex = $tmpIndex;
    }
    
    $umdIndex++;
    if ($umdIndex >= count($umdNameList))
    {
        $umdIndex = 0;
    }
    
    
    
    //$tmpDriverName = $row1[21];
    //// if this machine has umd less than DX11, DX12, Vulkan
    //$tmpIndex = array_search($tmpDriverName, $umdNameList);
    //if ($tmpIndex == (count($umdNameList) - 1))
    //{
    //    if ($i != $tmpIndex)
    //    {
    //        for ($j = 0; $j < ($tmpIndex - $i); $j++)
    //        {
    //            array_push($resultIDList, PHP_INT_MAX);
    //            array_push($machineIDList, PHP_INT_MAX);
    //            array_push($cardIDList, $row1[10]);
    //            array_push($cardNameList, $row1[20]);
    //            array_push($driverNameList, $umdNameList[$j]);
    //            array_push($changeListNumList, PHP_INT_MAX);
    //            array_push($cpuNameList, $row1[22]);
    //            array_push($sysNameList, $row1[23]);
    //            array_push($mainLineNameList, $row1[24]);
    //            array_push($resultTimeList, $row1[7]);
    //        }
    //        $i = $tmpIndex;
    //    }
    //}
    //$i++;
    //if ($i >= count($umdNameList))
    //{
    //    $i = 0;
    //}
    //
    //array_push($resultIDList, $row1[0]);
    //array_push($machineIDList, $row1[1]);
    //array_push($cardIDList, $row1[10]);
    //array_push($cardNameList, $row1[20]);
    //array_push($driverNameList, $row1[21]);
    //array_push($changeListNumList, $row1[4]);
    //array_push($cpuNameList, $row1[22]);
    //array_push($sysNameList, $row1[23]);
    //array_push($mainLineNameList, $row1[24]);
    //array_push($resultTimeList, $row1[7]);
}

$tmpCardIDList = array_unique($cardIDList);
$tmpCardNameList = array_unique($cardNameList);
$tmpDriverNameList = array_unique($driverNameList);
$tmpSysNameList = array_unique($sysNameList);

$uniqueCardIDList = array();
$uniqueCardNameList = array();
$uniqueDriverNameList = array();
$uniqueSysNameList = array();

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

$cardNum = count($uniqueCardNameList);
$umdNum = count($uniqueDriverNameList);
$sysNum = count($uniqueSysNameList);


$returnMsg["uniqueCardNameList"] = $uniqueCardNameList;
$returnMsg["uniqueCardIDList"] = $uniqueCardIDList;
$returnMsg["uniqueDriverNameList"] = $uniqueDriverNameList;
$returnMsg["uniqueSysNameList"] = $uniqueSysNameList;
$returnMsg["resultIDList"] = $resultIDList;
$returnMsg["cardIDList"] = $cardIDList;
$returnMsg["cardNameList"] = $cardNameList;
$returnMsg["sysNameList"] = $sysNameList;
$returnMsg["testNameList"] = $testNameList;
$returnMsg["testIDList"] = $testIDList;
echo json_encode($returnMsg);


?>