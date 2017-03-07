<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

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

$needBatchIDList = array();
$needMachineIDList = array();

$params1 = array();
$sql1 = "SELECT batch_id FROM mis_table_batch_list " .
        "WHERE batch_state=\"1\" AND batch_group=\"1\" ORDER BY insert_time ASC";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}
while($row1 = $db->fetchRow())
{
    
}

?>