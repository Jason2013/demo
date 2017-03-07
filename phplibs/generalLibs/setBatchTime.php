<?php

//include_once "swtExcelGenFuncs.php";
include_once "dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "genfuncs.php";
include_once "code01.php";

$batchID = intval($_POST["batchID"]);
$tagetDate = cleanDateTime($_POST["tagetDate"]);

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "set batch time success";


$db = new CPdoMySQL();
if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$params1 = array($batchID);
$sql1 = "SELECT COUNT(*) FROM mis_table_batch_list " .
        "WHERE batch_id=?";
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

$batchNum = $row1[0];
if ($batchNum == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "invalid batch ID";
    echo json_encode($returnMsg);
    return;
}


$params1 = array($tagetDate, $batchID);
$sql1 = "UPDATE mis_table_batch_list " .
        "SET insert_time=? " .
        "WHERE batch_id=?";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$params1 = array($batchID);
$sql1 = "SELECT insert_time FROM mis_table_batch_list " .
        "WHERE batch_id=?";
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
    $returnMsg["errorMsg"] = "invalid batch ID";
    echo json_encode($returnMsg);
    return;
}
$insertTime = $row1[0];

$returnMsg["insertTime"] = $insertTime;

echo json_encode($returnMsg);

?>