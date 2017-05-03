<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../userManage/swtUserManager.php";

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "compile report success";

$db = new CPdoMySQL();
if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$userChecker = new CUserManger();

if ($userChecker->isUser() == false)
{
    // not a user
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "you need to login";
    echo json_encode($returnMsg);
    return;
}

$params1 = array();
$sql1 = "";
if ($userChecker->isManager())
{
    // manager login
    $params1 = array();
    $sql1 = "SELECT batch_id FROM mis_table_batch_list " .
            "WHERE batch_state=\"1\" AND (batch_group=\"1\" OR batch_group=\"2\") ORDER BY insert_time DESC LIMIT 1";
            //"WHERE batch_state=\"1\" AND batch_group=\"1\" ORDER BY insert_time DESC LIMIT 1";
}
else
{
    $userID = $userChecker->getUserID();
    $params1 = array($userID);
    $sql1 = "SELECT t0.batch_id FROM mis_table_user_batch_info t0 " .
            "WHERE t0.user_id = ? AND t0.batch_id IN (SELECT t1.batch_id FROM mis_table_batch_list t1 " .
            "WHERE t1.batch_state=\"1\" AND t1.batch_group=\"0\") " .
            "ORDER BY t0.batch_id DESC LIMIT 1";
}
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
    $returnMsg["userID"] = $userID;
    $returnMsg["sql1"] = $sql1;
    echo json_encode($returnMsg);
    return;
}
$row1 = $db->fetchRow();

if ($row1 == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no batch found, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$batchID = $row1[0];

$params1 = array($batchID);
$sql1 = "SELECT DISTINCT (t0.machine_id), t1.card_id, t2.env_name, t3.env_name " .
        "FROM mis_table_result_list t0 " .
        "LEFT JOIN mis_table_machine_info t1 " .
        "USING (machine_id) " .
        "LEFT JOIN mis_table_environment_info t2 " .
        "ON (t2.env_type=0 AND t2.env_id=t1.card_id) " .
        "LEFT JOIN mis_table_environment_info t3 " .
        "ON (t3.env_type=2 AND t3.env_id=t1.sys_id) " .
        "WHERE t0.batch_id=? ORDER BY t0.insert_time ASC";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}

$machineIDList = array();
$resultIDList = array();
$cardIDList = array();
$cardNameList = array();
$systemNameList = array();

while ($row1 = $db->fetchRow())
{
    array_push($machineIDList, $row1[0]);
    //array_push($resultIDList, $row1[1]);
    array_push($cardIDList, $row1[1]);
    array_push($cardNameList, $row1[2]);
    array_push($systemNameList, $row1[3]);
}

$reportFileNameList = array();
$reportFolderName = $downloadReportDir . "/batch" . $batchID;
if ((file_exists($reportFolderName) == true) &&
    (is_dir($reportFolderName)      == true))
{
    $tmpList = glob($reportFolderName . "/*", GLOB_ONLYDIR);
    $lastFolderName = "";
    if (count($tmpList) > 0)
    {
        $tmpReverseList = array_reverse($tmpList);
        
        foreach ($tmpReverseList as $tmpName)
        {
            $tmpList = glob($tmpName . "/*.xlsm");
            if (count($tmpList) > 0)
            {
                foreach ($tmpList as $tmpName2)
                {
                    $t1 = substr($tmpName2, strlen("../"));
                    array_push($reportFileNameList, $t1);
                }
                //$reportFileNameList = $tmpList;
                break;
            }
        }
    }
}

// delete old reports
$tmpPath = $downloadReportDir . "/batch*";
$tmpList = glob($tmpPath, GLOB_ONLYDIR);
$curTime = time();
// 7 days
$noTouchTimeLen = 7 * 24 * 3600;

foreach ($tmpList as $tmpName)
{
    $batchName = "batch" . $batchID;
    $t1 = substr($tmpName, strlen($tmpName) - strlen($batchName), strlen($batchName));
    if (strcmp($t1, $batchName) != 0)
    {
        $n1 = swtGetFileTreeLastAccessTime($tmpName);
        if (($curTime - $n1) > $noTouchTimeLen)
        {
            swtDelFileTree($tmpName);
        }
    }
}

$returnMsg["machineIDList"] = $machineIDList;
$returnMsg["cardIDList"] = $cardIDList;
$returnMsg["cardNameList"] = $cardNameList;
$returnMsg["systemNameList"] = $systemNameList;
$returnMsg["reportFileNameList"] = $reportFileNameList;
$returnMsg["reportFolderName"] = $reportFolderName;

echo json_encode($returnMsg);

?>