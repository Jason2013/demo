<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../generalLibs/genfuncs.php";

//$logFileNum = cleaninput($_POST["logFileNum"], 128);

$returnMsg = array();
$returnMsg["serverCmd"] = serverDoNothing;

$logFileNum = intval($_POST["logFileNum"]);
if ($logFileNum == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no file specified";
    echo json_encode($returnMsg);
    return;
}

$resultNum = intval($_POST["resultNum"]);
if ($resultNum == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no result id specified";
    echo json_encode($returnMsg);
    return;
}

$batchID = intval($_POST["batchID"]);
$resultIDList = explode(",", $_POST["resultIDList"]);
if (count($resultIDList) != $resultNum)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "result id invalid";
    echo json_encode($returnMsg);
    return;
}

if (count($resultIDList) == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no result id inputed";
    echo json_encode($returnMsg);
    return;
}

for ($i = 0; $i < count($resultIDList); $i++)
{
    $resultIDList[$i] = intval($resultIDList[$i]);
}

// make save path begin
$targetFolder = $logStoreDir; // Relative to the root

$db = new CPdoMySQL();

if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server";
    echo json_encode($returnMsg);
    return;
}

$folder02 = "";
$fullFolder02 = "";


$umdName = array();
$machineName = array();
$cardName = array();
$cpuName = array();
$systemName = array();
$memName = array();
$shipSetName = array();
$mainLineName = array();
$changeListName = array();
$sClockName = array();
$mClockName = array();
$gpuMemName = array();
$t1 = implode(",", $resultIDList);

$params1 = array();
$sql1 = "SELECT COUNT(*) " .
        "FROM mis_table_result_list t0 " .
        "WHERE t0.result_id IN (" . $t1 . ") AND t0.result_state=\"0\""; // 
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3";
    echo json_encode($returnMsg);
    return $returnMsg;
}
$row1 = $db->fetchRow();
if ($row1 == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3";
    echo json_encode($returnMsg);
    return $returnMsg;
}

if ($row1[0] == 0)
{
    // no need to get these log files
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "result id not valid or finished";
    echo json_encode($returnMsg);
    return;
}

$params1 = array();
$sql1 = "SELECT t0.*, t1.path_name, t2.*, " .
        "(SELECT t3.env_name FROM mis_table_environment_info t3 WHERE t0.umd_id=t3.env_id) AS umdName, " .
        "(SELECT t3.env_name FROM mis_table_environment_info t3 WHERE t2.name_id=t3.env_id) AS machineName, " .
        "(SELECT t3.env_name FROM mis_table_environment_info t3 WHERE t2.card_id=t3.env_id) AS cardName, " .
        "(SELECT t3.env_name FROM mis_table_environment_info t3 WHERE t2.cpu_id=t3.env_id) AS cpuName, " .
        "(SELECT t3.env_name FROM mis_table_environment_info t3 WHERE t2.sys_id=t3.env_id) AS systemName, " .
        "(SELECT t3.env_name FROM mis_table_environment_info t3 WHERE t2.mem_id=t3.env_id) AS memName, " .
        "(SELECT t3.env_name FROM mis_table_environment_info t3 WHERE t2.chipset_id=t3.env_id) AS chipSetName, " .
        "(SELECT t3.env_name FROM mis_table_environment_info t3 WHERE t2.ml_id=t3.env_id) AS mainLineName, " .
        "(SELECT t3.env_name FROM mis_table_environment_info t3 WHERE t2.s_clock_id=t3.env_id) AS sClockName, " .
        "(SELECT t3.env_name FROM mis_table_environment_info t3 WHERE t2.m_clock_id=t3.env_id) AS mClockName, " .
        "(SELECT t3.env_name FROM mis_table_environment_info t3 WHERE t2.gpu_mem_id=t3.env_id) AS gpuMemName " .
        "FROM mis_table_result_list t0 " .
        "LEFT JOIN mis_table_path_info t1 " .
        "USING (path_id) " .
        "LEFT JOIN mis_table_machine_info t2 " .
        "USING (machine_id) " .
        "WHERE t0.result_id IN (" . $t1 . ") AND t0.result_state=\"0\""; // 
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3";
    echo json_encode($returnMsg);
    return $returnMsg;
}
while($row1 = $db->fetchRow())
{
    //$batchID = $row1[2];
    //$fullFolder02 = $row1[8];
    array_push($umdName, $row1[21]);
    array_push($machineName, $row1[22]);
    array_push($cardName, $row1[23]);
    array_push($cpuName, $row1[24]);
    array_push($systemName, $row1[25]);
    array_push($memName, $row1[26]);
    array_push($chipSetName, $row1[27]);
    array_push($mainLineName, $row1[28]);
    array_push($sClockName, $row1[29]);
    array_push($mClockName, $row1[30]);
    array_push($gpuMemName, $row1[31]);
    array_push($changeListName, $row1[4]);
}

if (count($umdName) == 0)
{
    // no need to get these log files
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "result id not valid or finished";
    echo json_encode($returnMsg);
    return;
}

$batchFolderName = $targetFolder . "/batch" . $batchID;
$cardFolderName = $batchFolderName . "/" . $cardName[0];
$cardFolderName = str_replace("\s", "_", $cardFolderName);

if (file_exists($batchFolderName) == false)
{
    // ex. /logStore/batch267
    mkdir($batchFolderName);
}
if (file_exists($cardFolderName) == false)
{
    // ex. /logStore/batch267/AMD_Fiji_XT
    mkdir($cardFolderName);
}

$jsonFileName1 = $batchFolderName . "/default_info.json";
$jsonFileName2 = $cardFolderName . "/machine_info.json";

if (file_exists($jsonFileName1) == false)
{
    // set json info file
    $json1 = array();
    $json1["resultFolderList"] = "[\"./\", \"./\", \"./\"]";
    foreach ($i = 0; $i < count($umdName); $i++)
    {
        $json1["changeList" . $umdName[$i]] = $changeListName[$i];
    }
    $t1 = json_encode($json1);
    file_put_contents($jsonFileName1, $t1);
}

if (file_exists($jsonFileName2) == false)
{
    // set json info file
    $json1 = array();
    $json1["machineName"] = $machineName[0];
    $json1["videoCardName"] = $cardName[0];
    $json1["cpuName"] = $cpuName[0];
    $json1["systemName"] = $systemName[0];
    $json1["memoryName"] = $memName[0];
    $json1["chipsetName"] = $chipSetName[0];
    $json1["mainLineName"] = $mainLineName[0];
    $json1["sClockName"] = $sClockName[0];
    $json1["mClockName"] = $mClockName[0];
    $json1["gpuMemName"] = $gpuMemName[0];

    $t1 = json_encode($json1);
    file_put_contents($jsonFileName2, $t1);
}

$i = 1;
while (1)
{
    $folder02 = sprintf("log%05d", $i);
    $fullFolder02 = $cardFolderName . "/" . $folder02;
    if (file_exists($fullFolder02) == false)
    {
        mkdir($fullFolder02);
        break;
    }
    $i++;
}
// make save path end

for ($i = 0; $i < $logFileNum; $i++)
{
    $t1 = sprintf("logFile%02d", $i);
    //$t2 = sprintf("logFileName%02d", $i);
    //$tmpFileName = cleaninput($_POST[$t2], 128);
    
    if (empty($_FILES[$t1]))
    {
        continue;
    }
    
    $tempFile = $_FILES[$t1]['tmp_name'];
    $targetFile = $fullFolder02 . "/" . $_FILES[$t1]['name'];
    
    // Validate the file type
    $fileTypes = array('txt'); // File extensions
    $fileParts = pathinfo($_FILES[$t1]['name']);
    
    if (in_array($fileParts['extension'], $fileTypes))
    {
        move_uploaded_file($tempFile, $targetFile);
    }
}

/*
// save log path
$params1 = array($batchFolderName);
$sql1 = "INSERT INTO mis_table_path_info " .
        "(path_name) " .
        "VALUES (?)";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3a";
    echo json_encode($returnMsg);
    return $returnMsg;
}
$pathID = $db->getInsertID();

for ($i = 0; $i < count($resultIDList); $i++)
{
    $params1 = array($pathID, $resultIDList[$i]);
    $sql1 = "UPDATE mis_table_result_list " .
            "SET path_id=? " .
            "WHERE result_id=?";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3";
        echo json_encode($returnMsg);
        return $returnMsg;
    }
}
//*/
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "log files saving success, " . $logFileNum;
echo json_encode($returnMsg);


?>