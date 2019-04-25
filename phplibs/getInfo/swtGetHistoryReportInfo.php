<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";


$umdNameList = $swtUmdNameList;
//$allReportsDir = "../allReports";
//$allReportsDirFromRoot = "allReports";

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
        "WHERE batch_state=\"1\" AND (batch_group=\"1\" OR batch_group=\"4\") ORDER BY insert_time DESC";
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

$resultIDList = array();
$machineIDList = array();
$cardIDList = array();
$cardNameList = array();
$driverNameList = array();
$changeListNumList = array();
$cpuNameList = array();
$sysNameList = array();
$mainLineNameList = array();
$sClockNameList = array();
$mClockNameList = array();
$gpuMemNameList = array();
$resultTimeList = array();

$allCardNameList = array();
$allSysNameList = array();

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
    $tmpSClockNameList = array();
    $tmpMClockNameList = array();
    $tmpGpuMemNameList = array();
    $tmpResultTimeList = array();
    $params1 = array($tmpBatchID);
    $sql1 = "SELECT t0.*, " .
            "t1.*, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.card_id) AS cardName, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t0.umd_id) AS umdName, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.cpu_id) AS cpuName, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.sys_id) AS sysName, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.ml_id) AS mlName, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.s_clock_id) AS sClockName, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.m_clock_id) AS mClockName, " .
            "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.gpu_mem_id) AS gpuMemName " .
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
                array_push($tmpResultIDList, PHP_INT_MAX);
                array_push($tmpMachineIDList, PHP_INT_MAX);
                array_push($tmpCardNameList, $row1[20]);
                array_push($tmpDriverNameList, $umdNameList[$j]);
                array_push($tmpChangeListNumList, PHP_INT_MAX);
                array_push($tmpCpuNameList, "");
                array_push($tmpSysNameList, $row1[23]);
                array_push($tmpMainLineNameList, "");
                array_push($tmpSClockNameList, "");
                array_push($tmpMClockNameList, "");
                array_push($tmpGpuMemNameList, "");
                array_push($tmpResultTimeList, "");
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
                    array_push($tmpResultIDList, PHP_INT_MAX);
                    array_push($tmpMachineIDList, PHP_INT_MAX);
                    array_push($tmpCardNameList, $row1[20]);
                    array_push($tmpDriverNameList, $umdNameList[$j]);
                    array_push($tmpChangeListNumList, PHP_INT_MAX);
                    array_push($tmpCpuNameList, "");
                    array_push($tmpSysNameList, $row1[23]);
                    array_push($tmpMainLineNameList, "");
                    array_push($tmpSClockNameList, "");
                    array_push($tmpMClockNameList, "");
                    array_push($tmpGpuMemNameList, "");
                    array_push($tmpResultTimeList, "");
                }
                $umdIndex = 0;
            }
        }

        $tmpIndex = array_search($tmpDriverName, $umdNameList);
        if ($tmpIndex !== false)
        {
            $n1 = $cardIndex * $umdNum + $tmpIndex;
            $tmpResultIDList[$n1] = $row1[0];
            $tmpMachineIDList[$n1] = $row1[1];
            $tmpCardNameList[$n1] = $row1[20];
            $tmpDriverNameList[$n1] = $row1[21];
            $tmpChangeListNumList[$n1] = $row1[4];
            $tmpCpuNameList[$n1] = $row1[22];
            $tmpSysNameList[$n1] = $row1[23];
            $tmpMainLineNameList[$n1] = $row1[24];
            $tmpSClockNameList[$n1] = $row1[25];
            $tmpMClockNameList[$n1] = $row1[26];
            $tmpGpuMemNameList[$n1] = $row1[27];
            $tmpResultTimeList[$n1] = $row1[7];
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
        //*/
        if (array_search($row1[20], $allCardNameList) === false)
        {
            array_push($allCardNameList, $row1[20]);
        }
        if (array_search($row1[23], $allSysNameList) === false)
        {
            array_push($allSysNameList, $row1[23]);
        }
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
    array_push($sClockNameList, $tmpSClockNameList);
    array_push($mClockNameList, $tmpMClockNameList);
    array_push($gpuMemNameList, $tmpGpuMemNameList);
    array_push($resultTimeList, $tmpResultTimeList);
}

$xmlFileNameList = array();
$finalCardNameList = array();
$finalDriverNameList = array();
$finalChangeListNumList = array();
$finalCpuNameList = array();
$finalSysNameList = array();
$finalMainLineNameList = array();
$finalSClockNameList = array();
$finalMClockNameList = array();
$finalGpuMemNameList = array();

$returnMsg["changeListNumList"] = $changeListNumList;
$returnMsg["cpuNameList"] = $cpuNameList;
$returnMsg["mainLineNameList"] = $mainLineNameList;
$returnMsg["sClockNameList"] = $sClockNameList;
$returnMsg["mClockNameList"] = $mClockNameList;
$returnMsg["gpuMemNameList"] = $gpuMemNameList;

$checkCheck = array();

function swtGetContentInArray($_valList, $_firstIndex, $_keyList)
{
    foreach ($_keyList as $tmpKey)
    {
        $t1 = $_valList[$_firstIndex][$tmpKey];
        if (strlen($t1) > 0)
        {
            return $t1;
        }
    }
}

for ($i = 0; $i < count($batchIDList); $i++)
{
    $tmpList1 = array_unique($cardNameList[$i]);
    $tmpList2 = array_unique($sysNameList[$i]);
    $tmpList3 = array();
    $tmpList4 = array();
    $tmpList5 = array();
    $tmpList6 = array();
    $tmpList7 = array();
    $tmpList8 = array();
    $tmpList9 = array();
    $tmpList10 = array();
    $tmpList11 = array();
    $tmpList12 = array();
    
    foreach ($tmpList2 as $tmpName2)
    {
        foreach ($tmpList1 as $tmpName1)
        {
            $batchName = sprintf("batch%d", $batchIDList[$i]);
            $xmlFileNameFromRoot = sprintf($allReportsDirFromRoot . "/" . $batchName . "/" .
                                   $tmpName1 . "_" . $tmpName2 . "_batch%05d.zip",
                                   $batchIDList[$i]);
            $xmlFileName = sprintf($allReportsDir . "/" . $batchName . "/" .
                           $tmpName1 . "_" . $tmpName2 . "_batch%05d.zip",
                           $batchIDList[$i]);
                           
            if (file_exists($xmlFileName) == false)
            {
                $xmlFileNameFromRoot = sprintf($allReportsDirFromRoot . "/" . $batchName . "/" .
                                       $tmpName1 . "_" . $tmpName2 . ".zip",
                                       $batchIDList[$i]);
                $xmlFileName = sprintf($allReportsDir . "/" . $batchName . "/" .
                               $tmpName1 . "_" . $tmpName2 . ".zip",
                               $batchIDList[$i]);
            }
                           
            if (file_exists($xmlFileName))
            {
                $tmpKeys1 = array_keys($cardNameList[$i], $tmpName1);
                $tmpKeys2 = array_keys($sysNameList[$i], $tmpName2);
                $tmpKeys3 = array_intersect($tmpKeys1, $tmpKeys2);
                
                $tmpList = array();
                $tmpKeys4 = array();
                foreach ($tmpKeys3 as $tmpKey)
                {
                    if ($resultIDList[$i][$tmpKey] != PHP_INT_MAX)
                    {
                        array_push($tmpList, $driverNameList[$i][$tmpKey]);
                    }
                    array_push($tmpKeys4, $tmpKey);
                }
                $t1 = implode(",", $tmpList);
                array_push($tmpList6, $t1);
                
                array_push($tmpList4, swtGetContentInArray($changeListNumList, $i, $tmpKeys4));
                array_push($tmpList5, swtGetContentInArray($cpuNameList, $i, $tmpKeys4));
                array_push($tmpList7, swtGetContentInArray($mainLineNameList, $i, $tmpKeys4));
                array_push($tmpList10, swtGetContentInArray($sClockNameList, $i, $tmpKeys4));
                array_push($tmpList11, swtGetContentInArray($mClockNameList, $i, $tmpKeys4));
                array_push($tmpList12, swtGetContentInArray($gpuMemNameList, $i, $tmpKeys4));
                
                array_push($tmpList8, $tmpName1);
                array_push($tmpList9, $tmpName2);
                array_push($tmpList3, $xmlFileNameFromRoot);
            }
        }
    }
    
    array_push($xmlFileNameList, $tmpList3);
    array_push($finalChangeListNumList, $tmpList4);
    array_push($finalCpuNameList, $tmpList5);
    array_push($finalDriverNameList, $tmpList6);
    array_push($finalMainLineNameList, $tmpList7);
    array_push($finalSClockNameList, $tmpList10);
    array_push($finalMClockNameList, $tmpList11);
    array_push($finalGpuMemNameList, $tmpList12);
    array_push($finalCardNameList, $tmpList8);
    array_push($finalSysNameList, $tmpList9);
}

$returnMsg["changeListNumList"] = $changeListNumList;

$returnMsg["finalDriverNameList"] = $finalDriverNameList;
$returnMsg["finalCardNameList"] = $finalCardNameList;
$returnMsg["driverNameList"] = $driverNameList;
$returnMsg["finalChangeListNumList"] = $finalChangeListNumList;
$returnMsg["finalCpuNameList"] = $finalCpuNameList;
$returnMsg["finalSysNameList"] = $finalSysNameList;
$returnMsg["finalMainLineNameList"] = $finalMainLineNameList;
$returnMsg["finalSClockNameList"] = $finalSClockNameList;
$returnMsg["finalMClockNameList"] = $finalMClockNameList;
$returnMsg["finalGpuMemNameList"] = $finalGpuMemNameList;
$returnMsg["allCardNameList"] = $allCardNameList;
$returnMsg["allSysNameList"] = $allSysNameList;
$returnMsg["batchTimeList"] = $batchTimeList;
$returnMsg["batchIDList"] = $batchIDList;
$returnMsg["xmlFileNameList"] = $xmlFileNameList;

echo json_encode($returnMsg);


?>