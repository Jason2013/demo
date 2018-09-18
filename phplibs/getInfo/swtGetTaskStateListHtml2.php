<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../userManage/swtUserManager.php";

//$dayBack = intval($_POST["dayBack"]);
$pageID = intval($_POST["pageID"]);
$reportType = intval($_POST["reportType"]);
$batchGroup = intval($_POST["batchGroup"]);

$pageItemNum = 20;
//$allReportsDir = "../allReports";

$returnMsg = array();
$returnMsg["htmlCode"] = "";

$userChecker = new CUserManger();
$userID = $userChecker->getUserID();

$db = new CPdoMySQL();

if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server";
    echo json_encode($returnMsg);
    return;
}

if ($userChecker->isManager())
{
    $params1 = array();
    $sql1 = "SELECT COUNT(*) " .
            "FROM mis_table_batch_list";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #1, line: " . __LINE__;
        echo json_encode($returnMsg);
        return;
    }
}
else
{
    $params1 = array($userID);
    $sql1 = "SELECT COUNT(*) " .
            "FROM mis_table_user_batch_info t0 " .
            "WHERE t0.user_id = ? AND t0.batch_id IN (SELECT t1.batch_id FROM mis_table_batch_list t1 ORDER BY t1.batch_id DESC)";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #1, line: " . __LINE__;
        echo json_encode($returnMsg);
        return;
    }
    $returnMsg["tmpCheck3"] = $userID;
}
$row1 = $db->fetchRow();
if ($row1 == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}
$batchNum = $row1[0];

$itemStart = $pageID * $pageItemNum;
$itemEnd = $itemStart + $pageItemNum;
$itemEnd = $itemEnd > $batchNum ? $batchNum : $itemEnd;
//$itemStart = intval($itemEnd / $pageItemNum) * $pageItemNum;



$params1 = array();
$sql1 = "";
if ($reportType == 0)
{
    if ($batchGroup == -1)
    {
        // all batches
        $params1 = array($itemStart, ($itemEnd - $itemStart));
        $sql1 = "SELECT t0.*, t1.path_name " .
                "FROM mis_table_batch_list t0 " .
                "LEFT JOIN mis_table_path_info t1 " .
                "USING (path_id) " .
                "ORDER BY t0.insert_time DESC LIMIT ?, ?";
    }
    else
    {
        // not all batches
        // if batch_group == 0, for outside users
        $params1 = array($batchGroup, $userID, $itemStart, ($itemEnd - $itemStart));
        $sql1 = "SELECT t0.*, t1.path_name " .
                "FROM mis_table_batch_list t0 " .
                "LEFT JOIN mis_table_path_info t1 " .
                "USING (path_id) " .
                "WHERE t0.batch_group = ? AND " .
                "t0.batch_id IN (SELECT t2.batch_id FROM mis_table_user_batch_info t2 WHERE t2.user_id = ? ORDER BY t2.batch_id DESC) " .
                "ORDER BY t0.insert_time DESC LIMIT ?, ?";
                
        $returnMsg["tmpCheck"] = 2;
        $returnMsg["tmpCheck2"] = $params1;
    }
}
else if ($reportType == 1)
{
    $params1 = array();
    $sql1 = "SELECT t0.*, t1.path_name " .
            "FROM mis_table_batch_list t0 " .
            "LEFT JOIN mis_table_path_info t1 " .
            "USING (path_id) " .
            "ORDER BY t0.insert_time ASC";
}
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1, line: " . __LINE__ . ", itemStart: " . $itemStart;
    echo json_encode($returnMsg);
    return;
}

$batchIDList = array();
$batchInsertTimeList = array();
$batchStateList = array();
$batchStateNameList = array();
$batchGroupNameList = array();
$logPathNameList = array();

while ($row1 = $db->fetchRow())
{
    array_push($batchIDList, $row1[0]);
    array_push($batchInsertTimeList, $row1[1]);
    array_push($batchStateList, $row1[2]);
    //array_push($batchGroupNameList, $row1[3]);
    array_push($logPathNameList, $row1[5]);
    $t1 = "not clear";
    if ($row1[2] < count($swtTestBatchStateString))
    {
        $t1 = $swtTestBatchStateString[$row1[2]];
    }
    array_push($batchStateNameList, $t1);
    
    $t1 = "not clear";
    $batchGroupID = intval($row1[3]);
    if ($batchGroupID < count($swtTestBatchGroupString))
    {
        $t1 = $swtTestBatchGroupString[$batchGroupID];
    }
    else if (($batchGroupID >= 100) && ($batchGroupID < (100 + count($swtTestBatchGroupStringEx))))
    {
        $n1 = $batchGroupID - 100;
        $t1 = $swtTestBatchGroupStringEx[$n1];
    }
    else if (($batchGroupID >= 200) && ($batchGroupID < (200 + count($swtTestBatchGroupStringEx2))))
    {
        $n1 = $batchGroupID - 200;
        $t1 = $swtTestBatchGroupStringEx2[$n1];
    }
    array_push($batchGroupNameList, $t1);
}

if (count($batchIDList) == 0)
{
    $returnMsg["errorCode"] = 2;
    $returnMsg["errorMsg"] = "no records found";
    $returnMsg["batchNum"] = $batchNum;
    $returnMsg["itemStart"] = $itemStart;
    $returnMsg["itemEnd"] = $itemEnd;
    $returnMsg["userID"] = $userID;
    echo json_encode($returnMsg);
    return;
}

$batchHasReportList = array();
foreach ($batchIDList as $tmpID)
{
    $t1 = $allReportsDir . "/batch" . $tmpID;
    if (file_exists($t1))
    {
        array_push($batchHasReportList, 1);
    }
    else
    {
        array_push($batchHasReportList, 0);
    }
}

$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "query success";
//$returnMsg["htmlCode"] = $htmlCode;
$returnMsg["batchIDList"] = $batchIDList;
$returnMsg["batchHasReportList"] = $batchHasReportList;
$returnMsg["batchNum"] = $batchNum;
$returnMsg["itemStart"] = $itemStart;
$returnMsg["itemEnd"] = $itemEnd;
$returnMsg["pageItemNum"] = $pageItemNum;
$returnMsg["batchInsertTimeList"] = $batchInsertTimeList;
$returnMsg["batchStateList"] = $batchStateList;
$returnMsg["batchStateNameList"] = $batchStateNameList;
$returnMsg["batchGroupNameList"] = $batchGroupNameList;
$returnMsg["logPathNameList"] = $logPathNameList;

echo json_encode($returnMsg);

?>