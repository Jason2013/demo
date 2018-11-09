<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";


$targetDir = $allReportsDir;

$returnMsg = array();
$returnMsg["batchDateList"] = array();
$returnMsg["batchIDList"] = array();

$db = new CPdoMySQL();

if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server";
    echo json_encode($returnMsg);
    return;
}


$params1 = array();
$sql1 = "SELECT batch_id FROM mis_table_batch_list " .
        "WHERE batch_state=\"1\" AND (batch_group=\"1\" OR batch_group=\"4\") ORDER BY insert_time ASC";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1, line: " . __LINE__ . ", itemStart: " . $itemStart;
    echo json_encode($returnMsg);
    return;
}

$tmpBatchIDList = array();

while ($row1 = $db->fetchRow())
{
    array_push($tmpBatchIDList, $row1[0]);
}

$batchIDList = array();

foreach ($tmpBatchIDList as $tmpID)
{
    $t1 = $targetDir . "/batch" . $tmpID;
    if (file_exists($t1))
    {
        continue;
    }
    array_push($batchIDList, $tmpID);
}


$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "query success";
$returnMsg["batchIDList"] = $batchIDList;

echo json_encode($returnMsg);

?>