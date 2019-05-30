<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../userManage/swtUserManager.php";

$batchID = intval($_POST["batchID"]);

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

$isBatchIDAssigned = false;
if ($batchID != -1)
{
    $isBatchIDAssigned = true;
}

if ($isBatchIDAssigned == false)
{
    $params1 = array();
    $sql1 = "";
    if ($userChecker->isManager())
    {
        // manager login
        $params1 = array();
        $sql1 = "SELECT batch_id FROM mis_table_batch_list " .
                "WHERE batch_state=\"1\" AND (batch_group IN (3, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209)) " .
                "ORDER BY insert_time DESC LIMIT 1";
    }
    else
    {
        $userID = $userChecker->getUserID();
        $params1 = array($userID);
        $sql1 = "SELECT t0.batch_id FROM mis_table_user_batch_info t0 " .
                "WHERE t0.user_id = ? AND t0.batch_id IN (SELECT t1.batch_id FROM mis_table_batch_list t1 " .
                "WHERE t1.batch_state=\"1\" AND (t1.batch_group IN (6))) " .
                "ORDER BY t0.insert_time DESC LIMIT 1";
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
}

$shaderBenchBatchID = $batchID;

if ($isBatchIDAssigned == false)
{
    $params1 = array();
    $sql1 = "";
    if ($userChecker->isManager())
    {
        // manager login
        $params1 = array();
        $sql1 = "SELECT batch_id FROM mis_table_batch_list " .
                "WHERE batch_state=\"1\" AND (batch_group IN (5, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309)) " .
                "ORDER BY insert_time DESC LIMIT 1";
    }
    else
    {
        $userID = $userChecker->getUserID();
        $params1 = array($userID);
        $sql1 = "SELECT t0.batch_id FROM mis_table_user_batch_info t0 " .
                "WHERE t0.user_id = ? AND t0.batch_id IN (SELECT t1.batch_id FROM mis_table_batch_list t1 " .
                "WHERE t1.batch_state=\"1\" AND (t1.batch_group IN (7))) " .
                "ORDER BY t0.insert_time DESC LIMIT 1";
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
}

$frameBenchBatchID = $batchID;

$reportFileNameList = array();
$reportAllList = array();
$reportSortList = array();

function checkFilesInFolder($_path)
{
    global $reportFileNameList;
    
    if (is_dir($_path) == false)
    {
        return 0;
    }
    $tmpFolderName = basename($_path);
    $tmpList = glob($_path . "/*.xlsm");
    
    if (count($tmpList) > 0)
    {
        foreach ($tmpList as $tmpName2)
        {
            $t1 = substr($tmpName2, strlen("../"));
            array_push($reportFileNameList, $t1);
        }
    }
    return count($tmpList);
}

if ($isBatchIDAssigned)
{
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
                $n1 = checkFilesInFolder($tmpName);
                if ($n1 > 0)
                {
                    // found reports
                    break;
                }
                
                $tmpList2 = glob($tmpName . "/*", GLOB_ONLYDIR);
                
                if (count($tmpList2) > 0)
                {
                    foreach ($tmpList2 as $tmpName2)
                    {
                        $n1 = checkFilesInFolder($tmpName2);
                    }
                    break;
                }
            }
        }
    }
    
    $reportAllList []= $reportFileNameList;
}
else
{
    $reportFileNameList = array();
    
    $reportFolderName = $downloadReportDir . "/batch" . $shaderBenchBatchID;

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
                $n1 = checkFilesInFolder($tmpName);
                if ($n1 > 0)
                {
                    // found reports
                    break;
                }
                
                $tmpList2 = glob($tmpName . "/*", GLOB_ONLYDIR);
                
                if (count($tmpList2) > 0)
                {
                    foreach ($tmpList2 as $tmpName2)
                    {
                        $n1 = checkFilesInFolder($tmpName2);
                    }
                    break;
                }
            }
        }
    }
    
    $reportAllList []= $reportFileNameList;
    
    $reportFileNameList = array();
    
    $reportFolderName = $downloadReportDir . "/batch" . $frameBenchBatchID;

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
                $n1 = checkFilesInFolder($tmpName);
                if ($n1 > 0)
                {
                    // found reports
                    break;
                }
                
                $tmpList2 = glob($tmpName . "/*", GLOB_ONLYDIR);
                
                if (count($tmpList2) > 0)
                {
                    foreach ($tmpList2 as $tmpName2)
                    {
                        $n1 = checkFilesInFolder($tmpName2);
                    }
                    break;
                }
            }
        }
    }
    
    $reportAllList []= $reportFileNameList;
    
    $reportSortList = array("ShaderBench", "FrameBench");
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

//$returnMsg["reportFileNameList"] = $reportFileNameList;
$returnMsg["reportAllList"] = $reportAllList;
$returnMsg["reportSortList"] = $reportSortList;
//$returnMsg["reportFolderName"] = $reportFolderName;
//$returnMsg["batchID"] = $batchID;

echo json_encode($returnMsg);

?>