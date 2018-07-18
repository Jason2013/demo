<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

//$dayBack = intval($_POST["dayBack"]);
$checkDate = cleanDateTime($_POST["checkDate"]);
$pageItemNum = 20;

$returnMsg = array();
$returnMsg["batchDateList"] = array();
$returnMsg["batchIDList"] = array();

//$checkDate = "2016-06-06";

$db = new CPdoMySQL();

if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server";
    echo json_encode($returnMsg);
    return;
}

$t1 = "";
if (strlen($checkDate) == strlen("MM-dd-yyyy"))
{
    $t1 = "insert_time >= DATE_ADD(\"" . $checkDate . "\", INTERVAL - DAY(\"" . $checkDate . "\") + 1 DAY) AND " .
          "insert_time < DATE_ADD(DATE_ADD(\"" . $checkDate . "\", INTERVAL - DAY(\"" . $checkDate . "\") + 1 DAY), INTERVAL 1 MONTH)";
}
else
{
    $t1 = "insert_time >= DATE_ADD(CURDATE(), INTERVAL - DAY(CURDATE()) + 1 DAY) AND " .
          "insert_time < DATE_ADD(DATE_ADD(CURDATE(), INTERVAL - DAY(CURDATE()) + 1 DAY), INTERVAL 1 MONTH)";
}


$params1 = array();
$sql1 = "SELECT * " .
        "FROM mis_table_batch_list " .
        "WHERE " . $t1 . " AND (batch_group=\"1\" OR batch_group=\"4\") " .
        "ORDER BY insert_time ASC";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1, line: " . __LINE__ . ", itemStart: " . $itemStart;
    echo json_encode($returnMsg);
    return;
}

$batchIDList = array();
$batchInsertTimeList = array();
$batchDateList = array();

while ($row1 = $db->fetchRow())
{
    $batchState = $row1[2];
    if (strcmp($batchState, "1") != 0)
    {
        //only keep finished batch
        continue;
    }
    array_push($batchIDList, $row1[0]);
    array_push($batchInsertTimeList, $row1[1]);
    $tmpDate = substr($row1[1], 0, 10);
    array_push($batchDateList, $tmpDate);
}

if (count($batchIDList) == 0)
{
    $returnMsg["errorCode"] = 2;
    $returnMsg["errorMsg"] = "no records found";
    $returnMsg["batchNum"] = 0;
    echo json_encode($returnMsg);
    return;
}

$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "query success";
$returnMsg["batchIDList"] = $batchIDList;
$returnMsg["batchDateList"] = $batchDateList;

echo json_encode($returnMsg);

?>