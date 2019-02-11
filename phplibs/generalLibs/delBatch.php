<?php

//include_once "swtExcelGenFuncs.php";
include_once "dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "genfuncs.php";
include_once "code01.php";
include_once "../userManage/swtUserManager.php";

$startBatchID = intval($_POST["startBatchID"]);
$endBatchID = intval($_POST["endBatchID"]);

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "del batches success";

$userChecker = new CUserManger();
if ($userChecker->tryLogIn() == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "you need log in";
    echo json_encode($returnMsg);
    return;
}

if ($userChecker->isUser() == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "you need log in";
    echo json_encode($returnMsg);
    return;
}

if (($startBatchID != $endBatchID) &&
    ($userChecker->isManager() == false))
{
    // not a manager can't del more than 1 batch
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "you don't have enough right";
    echo json_encode($returnMsg);
    return;
}

$db = new CPdoMySQL();
if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$userID = $userChecker->getUserID();

if (($startBatchID == $endBatchID) &&
    ($userChecker->isManager() == false))
{
    $params1 = array($userID, $startBatchID);
    $sql1 = "SELECT COUNT(*) FROM mis_table_user_batch_info " .
            "WHERE user_id = ? AND batch_id = ?";
            
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
    $userNum = intval($row1[0]);
    if ($userNum == 0)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "you don't have the right";
        echo json_encode($returnMsg);
        return;
    }
}

$logPathList = array();
$params1 = array($startBatchID, $endBatchID);
$sql1 = "SELECT t0.*, t1.path_name " .
        "FROM mis_table_batch_list t0 " .
        "LEFT JOIN mis_table_path_info t1 " .
        "USING (path_id) " .
        "WHERE t0.batch_id>=? AND t0.batch_id<=?";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$batchIDList = array();
while($row1 = $db->fetchRow())
{
    array_push($batchIDList, $row1[0]);
    array_push($logPathList, $row1[5]);
}

$params1 = array($startBatchID, $endBatchID);
$sql1 = "DELETE FROM mis_table_test_subject_list " .
        "WHERE batch_id>=? AND batch_id<=?";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

foreach ($batchIDList as $batchID)
{
    $params1 = array($startBatchID, $endBatchID);
    $sql1 = "SELECT result_id FROM mis_table_result_list " .
            "WHERE batch_id>=? AND batch_id<=?";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
        echo json_encode($returnMsg);
        return;
    }
    $resultIDList = array();
    while($row1 = $db->fetchRow())
    {
        array_push($resultIDList, $row1[0]);
    }
    $resultIDListString = implode(",", $resultIDList);
    
    if (count($resultIDList) > 0)
    {
        $params1 = array();
        $sql1 = "DELETE FROM mis_table_task_list " .
                "WHERE result_id IN (" . $resultIDListString . ")";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
            echo json_encode($returnMsg);
            return;
        }
        
        $params1 = array();
        $sql1 = "DELETE FROM mis_table_average_test_data " .
                "WHERE result_id IN (" . $resultIDListString . ")";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            echo json_encode($returnMsg);
            return;
        }
        
        
        $params1 = array($batchID);
        $sql1 = "SELECT DISTINCT t0.test_id, t0.list_id, " .
                "(SELECT t1.test_name FROM mis_table_test_info t1 WHERE t1.test_id=t0.test_id) AS testName " .
                "FROM mis_table_test_subject_list t0 " .
                "WHERE t0.batch_id=? ORDER BY t0.list_id ASC";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
            echo json_encode($returnMsg);
            return;
        }

        $testNameList = array();

        while ($row1 = $db->fetchRow())
        {
            array_push($testNameList, $row1[2]);
        }
        
        //foreach ($dataTables as $tableName)
        foreach ($testNameList as $tmpTestName)
        {
            $tableName = $db_mis_table_name_string001 . $tmpTestName;
            //$tableName2 = $db_mis_table_name_string002 . $tmpTestName;
            $tmpName = str_replace(" ", "", $tmpTestName);
            $tmpName = cleaninput($tmpName, 256);
            $tableName2 = $db_mis_table_name_string002 . $tmpName;
            
            $params1 = array();
            $sql1 = "DELETE FROM " . $tableName . " " .
                    "WHERE result_id IN (" . $resultIDListString . ")";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                echo json_encode($returnMsg);
                return;
            }
            
            $params1 = array($tableName . "_noise");
            $sql1 = "SELECT table_name FROM information_schema.TABLES " .
                    "WHERE table_name = ?;";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                echo json_encode($returnMsg);
                return;
            }
            
            $row1 = $db->fetchRow();
            if ($row1 !== false)
            {
                $params1 = array();
                $sql1 = "DELETE IGNORE FROM " . $tableName . "_noise " .
                        "WHERE result_id IN (" . $resultIDListString . ")";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                    echo json_encode($returnMsg);
                    return;
                }
            }
            
            $params1 = array();
            $sql1 = "DELETE FROM " . $tableName2 . "_noise " .
                    "WHERE result_id IN (" . $resultIDListString . ")";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                echo json_encode($returnMsg);
                return;
            }
        }
        
        $params1 = array();
        $sql1 = "DELETE FROM mis_table_result_list " .
                "WHERE result_id IN (" . $resultIDListString . ")";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            echo json_encode($returnMsg);
            return;
        }
    }
}

$params1 = array($startBatchID, $endBatchID);
$sql1 = "DELETE FROM mis_table_batch_list " .
        "WHERE batch_id>=? AND batch_id<=?";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

// delete batch_id records for outside users
$params1 = array($startBatchID, $endBatchID);
$sql1 = "DELETE FROM mis_table_user_batch_info " .
        "WHERE batch_id>=? AND batch_id<=?";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

for ($i = 0; $i < count($batchIDList); $i++)
{
    // delete log files
    $batchPathName = $logStoreDir . "/batch" . $batchIDList[$i];

    if (file_exists($batchPathName) == false)
    {
        $batchPathName = $logStoreDir . "/" . $logPathList[$i];
    }
    swtDelFileTree($batchPathName);
}

$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "del batches success";

echo json_encode($returnMsg);

?>