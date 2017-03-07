<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

$dayBack = intval($_POST["dayBack"]);

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

$params1 = array();
$sql1 = "SELECT * " .
        "FROM mis_table_batch_list " .
        "WHERE insert_time >= DATE_SUB(CURDATE(), INTERVAL " . $dayBack . " DAY) AND " .
        "insert_time < DATE_SUB(CURDATE(), INTERVAL " . ($dayBack - 1) . " DAY) ORDER BY insert_time DESC";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1";
    echo json_encode($returnMsg);
    return;
}

$batchIDList = array();
$batchInsertTimeList = array();
$batchStateList = array();
$batchStateNameList = array();

while ($row1 = $db->fetchRow())
{
    array_push($batchIDList, $row1[0]);
    array_push($batchInsertTimeList, $row1[1]);
    array_push($batchStateList, $row1[2]);
    $t1 = "not clear";
    if ($row1[2] < count($swtTestBatchStateString))
    {
        $t1 = $swtTestBatchStateString[$row1[2]];
    }
    array_push($batchStateNameList, $t1);
}

if (count($batchIDList) == 0)
{
    $returnMsg["errorCode"] = 2;
    $returnMsg["errorMsg"] = "no records found";
    //$returnMsg["htmlCode"] = $htmlCode;
    echo json_encode($returnMsg);
    return;
}

$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "query success";
//$returnMsg["htmlCode"] = $htmlCode;
$returnMsg["batchIDList"] = $batchIDList;
$returnMsg["batchInsertTimeList"] = $batchInsertTimeList;
$returnMsg["batchStateList"] = $batchStateList;
$returnMsg["batchStateNameList"] = $batchStateNameList;
echo json_encode($returnMsg);

?>