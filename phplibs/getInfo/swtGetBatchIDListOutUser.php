<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../userManage/swtUserManager.php";

$batchGroup = intval($_POST["batchGroup"]);
$batchState = intval($_POST["batchState"]);
$batchNum = intval($_POST["batchNum"]);

$returnMsg = array();

$userChecker = new CUserManger();

$returnMsg["isUser"] = $userChecker->isUser();
$returnMsg["isManager"] = $userChecker->isManager();

if ($userChecker->isUser() == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "user needs login";
    $returnMsg["batchIDList"] = array();
    echo json_encode($returnMsg);
    return;
}

if ($userChecker->isManager() == true)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "don't login as manager";
    $returnMsg["batchIDList"] = array();
    echo json_encode($returnMsg);
    return;
}

$userName = $userChecker->getUserName();
if (strlen($userName) == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "user name invalid";
    $returnMsg["batchIDList"] = array();
    echo json_encode($returnMsg);
    return;
}

$db = new CPdoMySQL();

if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server";
    echo json_encode($returnMsg);
    return;
}

$params1 = array($userName);
$sql1 = "SELECT user_id FROM mis_table_user_info " .
        "WHERE user_name = ?";
        
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1, line: " . __LINE__ . ", sql1: " . $sql1;
    echo json_encode($returnMsg);
    return;
}
$row1 = $db->fetchRow();

if ($row1 == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "user not found";
    $returnMsg["batchIDList"] = array();
    echo json_encode($returnMsg);
    return;
}
$userID = intval($row1[0]);

$params1 = array($userID, $batchNum);
$sql1 = "SELECT * FROM mis_table_user_batch_info " .
        "WHERE user_id = ? AND batch_id IN (SELECT batch_id FROM mis_table_batch_list) " .
        "ORDER BY insert_time DESC LIMIT ?";

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
    array_push($batchIDList, $row1[2]);
}

$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "query success";
$returnMsg["batchIDList"] = $batchIDList;

echo json_encode($returnMsg);

?>