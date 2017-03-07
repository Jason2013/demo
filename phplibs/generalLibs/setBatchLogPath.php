<?php

//include_once "swtExcelGenFuncs.php";
include_once "dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "genfuncs.php";
include_once "code01.php";

$batchID = intval($_POST["batchID"]);
$pathName = cleaninput($_POST["pathName"], 256);

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "set batch result files path success";


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
    $returnMsg["errorMsg"] = "batch ID invalid";
    echo json_encode($returnMsg);
    return;
}

$params1 = array($pathName);
$sql1 = "SELECT * FROM mis_table_path_info " .
        "WHERE path_name=?";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}
$row1 = $db->fetchRow();
$pathID = -1;
if ($row1 == false)
{
    $params1 = array($pathName);
    $sql1 = "INSERT INTO mis_table_path_info " .
            "(path_name) " .
            "VALUES (?)";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__;
        echo json_encode($returnMsg);
        return;
    }
    $pathID = $db->getInsertID();
}
else
{
    $pathID = $row1[0];
}

$params1 = array($pathID, $batchID);
$sql1 = "UPDATE mis_table_batch_list " .
        "SET path_id = ? " .
        "WHERE batch_id=?";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3" . $db->getError()[2];
    echo json_encode($returnMsg);
    return;
}

echo json_encode($returnMsg);

?>