<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

$batchGroup = intval($_POST["batchGroup"]);
$batchState = intval($_POST["batchState"]);
$batchNum = intval($_POST["batchNum"]);

$returnMsg = array();

$db = new CPdoMySQL();

if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server";
    echo json_encode($returnMsg);
    return;
}

$params1 = array();
$sql1 = "";
if ($batchNum == -1)
{
    //$params1 = array($batchGroup, $batchState);
    $params1 = array($batchState);
    $sql1 = "SELECT * " .
            "FROM mis_table_batch_list " .
            "WHERE (batch_group=1 OR batch_group=4) AND batch_state = ? " .
            "ORDER BY insert_time DESC";
}
else
{
    //$params1 = array($batchGroup, $batchState, $batchNum);
    $params1 = array($batchState, $batchNum);
    $sql1 = "SELECT * " .
            "FROM mis_table_batch_list " .
            "WHERE (batch_group=1 OR batch_group=4) AND batch_state = ? " .
            "ORDER BY insert_time DESC LIMIT ?";
}

if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1, line: " . __LINE__ . ", sql1: " . $sql1;
    echo json_encode($returnMsg);
    return;
}

$batchIDList = array();

while ($row1 = $db->fetchRow())
{
    array_push($batchIDList, $row1[0]);
}



$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "query success";
$returnMsg["batchIDList"] = $batchIDList;

echo json_encode($returnMsg);

?>