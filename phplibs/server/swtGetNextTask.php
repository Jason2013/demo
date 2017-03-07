<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../generalLibs/swtCommonLib.php";

//$json = file_get_contents('php://input');
//$obj = json_decode($json, true);
//$clientCmd = intval($obj["clientCmd"]);

$machineID = intval($_POST["machineID"]);

$returnMsg = array();

$returnMsg["serverCmd"] = serverDoNothing;

if ($machineID < 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input machine id invalid";
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

// set old tasks time out
$params1 = array();
$sql1 = "SELECT batch_id " .
        "FROM mis_table_batch_list " .
        "WHERE batch_state=\"0\" AND TIMESTAMPDIFF(HOUR, insert_time, NOW()) > " . taskTimeOutHours;
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1";
    echo json_encode($returnMsg);
    return;
}
$batchIDList = array();
while ($row1 = $db->fetchRow())
{
    array_push($batchIDList, $row1[0]);
}
//array_push($batchIDList, "12");
$batchIDListString = implode(",", $batchIDList);

if (count($batchIDList) > 0)
{
    // set old tasks time out
    $params1 = array();
    $sql1 = "SELECT result_id " .
            "FROM mis_table_result_list " .
            "WHERE result_state=\"0\" AND batch_id IN (" . $batchIDListString . ")";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #2";
        echo json_encode($returnMsg);
        return;
    }
    $resultIDList = array();
    while ($row1 = $db->fetchRow())
    {
        array_push($resultIDList, $row1[0]);
    }
    $resultIDListString = implode(",", $resultIDList);
    
    if (count($resultIDList) > 0)
    {
        $params1 = array();
        // set old tasks time out
        $sql1 = "UPDATE mis_table_task_list " .
                "SET task_state = \"3\" " .
                "WHERE (task_state=\"0\") AND (result_id IN (" . $resultIDListString . "))";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3" . $db->getError()[2];
            echo json_encode($returnMsg);
            return;
        }
    }
    $params1 = array();
    // set old blank results time out
    $sql1 = "UPDATE mis_table_result_list " .
            "SET result_state = \"3\" " .
            "WHERE result_state=\"0\" AND result_id IN (" . $resultIDListString . ")";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #4";
        echo json_encode($returnMsg);
        return;
    }
    $params1 = array();
    // set old blank batches time out
    $sql1 = "UPDATE mis_table_batch_list " .
            "SET batch_state = \"3\" " .
            "WHERE batch_state=\"0\" AND batch_id IN (" . $batchIDListString . ")";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #5";
        echo json_encode($returnMsg);
        return;
    }
}

// get queued tasks
$sql1 = "SELECT t0.result_id, t0.batch_id, t0.umd_id, " .
        "t1.env_name AS umdName, " .
        "t2.upload_id AS uploadID, " .
        "t3.upload_path AS uploadPath " .
        "FROM mis_table_result_list t0 " .
        "LEFT JOIN mis_table_environment_info t1 " .
        "ON (t0.umd_id = t1.env_id) " .
        "LEFT JOIN mis_table_task_list t2 " .
        "USING (result_id) " .
        "LEFT JOIN mis_table_upload_info t3 " .
        "ON (t2.upload_id = t3.upload_id) " .
        "WHERE t0.machine_id = " . $machineID . " AND t0.result_state=\"0\" " .
        "ORDER BY batch_id ASC"; // GROUP BY batch_id

if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #6";
    echo json_encode($returnMsg);
    return;
}

$resultIDList = array();
$batchID = -1;
$umdNameList = array();
$uploadPathList = array();

$n1 = 0;

while ($row1 = $db->fetchRow())
{
    $tmpID = $row1[1];
    if (($batchID != -1) && ($batchID != $tmpID))
    {
        //break;
    }
    $batchID = $tmpID;
    array_push($resultIDList, $row1[0]);
    array_push($umdNameList, $row1[3]);
    array_push($uploadPathList, $row1[5]);
}
if (count($resultIDList) == 0)
{
    $returnMsg["errorCode"] = 1;
    $returnMsg["errorMsg"] = "no task queued";
    $returnMsg["resultIDNum"] = 0;
    echo json_encode($returnMsg);
    return;
}


$returnMsg["serverCmd"] = serverGotTaskSuccess;

$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "get task ok";
$returnMsg["batchID"] = $batchID;
$returnMsg["resultIDNum"] = count($resultIDList);
$returnMsg["resultIDList"] = $resultIDList;
$returnMsg["umdNameList"] = $umdNameList;
$returnMsg["uploadPathList"] = $uploadPathList;
echo json_encode($returnMsg);

?>