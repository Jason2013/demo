<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

$batchID = intval($_POST["batchID"]);

$returnMsg = array();
$returnMsg["htmlCode"] = "";

$db = new CPdoMySQL();

if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server";
    echo json_encode($returnMsg);
    return;
}

$params1 = array($batchID);
$sql1 = "SELECT t0.machine_id, t0.result_id, t0.umd_id, t1.*, " .
        "(SELECT env_name FROM mis_table_environment_info WHERE env_id=t1.name_id), " .
        "(SELECT env_name FROM mis_table_environment_info WHERE env_id=t1.card_id), " .
        "(SELECT env_name FROM mis_table_environment_info WHERE env_id=t1.sys_id) " .
        "FROM mis_table_result_list t0 " .
        "LEFT JOIN mis_table_machine_info t1 " .
        "USING (machine_id) " .
        "WHERE t0.batch_id=? GROUP BY t0.machine_id";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1";
    echo json_encode($returnMsg);
    return;
}

$resultIDList = array();
$machineIDList = array();
$umdIDList = array();
$machineNameIDList = array();
$cardIDList = array();
$sysIDList = array();
$machineNameList = array();
$cardNameList = array();
$sysNameList = array();

while ($row1 = $db->fetchRow())
{
    array_push($resultIDList, $row1[1]);
    array_push($machineIDList, $row1[0]);
    array_push($umdIDList, $row1[2]);
    array_push($machineNameIDList, $row1[4]);
    array_push($cardIDList, $row1[5]);
    array_push($sysIDList, $row1[7]);
    array_push($machineNameList, $row1[15]);
    array_push($cardNameList, $row1[16]);
    array_push($sysNameList, $row1[17]);
}

$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "query success";
$returnMsg["machineNameList"] = $machineNameList;
$returnMsg["cardNameList"] = $cardNameList;
$returnMsg["sysNameList"] = $sysNameList;
echo json_encode($returnMsg);


?>