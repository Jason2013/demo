<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";


$pageSize = 1000;
$pageID = intval($_POST["pageID"]);



$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "converting";


$db = new CPdoMySQL();
if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$params1 = array();
$sql1 = "SELECT COUNT(*) " .
        "FROM mis_table_test_info " .
        "WHERE test_type=2";
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

$testCaseNum = intval($row1[0]);

$visitedNum = $pageID * $pageSize;

if ($visitedNum >= $testCaseNum)
{
    // finished
    $returnMsg["errorCode"] = 2;
    $returnMsg["errorMsg"] = "convert finished";
    echo json_encode($returnMsg);
    return;
}

$toVisitNum = $testCaseNum - $visitedNum;
$toVisitNum = $toVisitNum > $pageSize ? $pageSize : $toVisitNum;

$testIDList = array();
$filterNameList = array();


$params1 = array();
$sql1 = "SELECT test_id, test_filter " .
        "FROM mis_table_test_info " .
        "WHERE test_type=2 LIMIT " . $visitedNum . ", " . $toVisitNum;
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

while ($row1 = $db->fetchRow())
{
    $testID = $row1[0];
    $testFilter = $row1[1];
    $tmpPos = strpos($testFilter, ",");
    if ($tmpPos !== false)
    {
        array_push($testIDList, $testID);
        array_push($filterNameList, str_replace(",", "|", $testFilter));
    }
}

for ($i = 0; $i < count($testIDList); $i++)
{
    $params1 = array($filterNameList[$i], $testIDList[$i]);
    $sql1 = "UPDATE mis_table_test_info " .
            "SET test_filter=? " .
            "WHERE test_id=?";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
        echo json_encode($returnMsg);
        return;
    }
}

$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "converting";
$returnMsg["testCaseNum"] = $testCaseNum;
$returnMsg["from"] = $visitedNum;
$returnMsg["num"] = $toVisitNum;
echo json_encode($returnMsg);


?>