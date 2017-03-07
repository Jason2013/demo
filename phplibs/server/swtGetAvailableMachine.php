<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../generalLibs/swtCommonLib.php";


// clear old drivers & folders
// swtDelFileTree

date_default_timezone_set("Asia/Shanghai");
$files = glob($driverStoreDir . "/*");
foreach ($files as $p1)
{
    $fileTime = filectime($p1);
    $curTime = time();
    // diff of days
    $n1 = ($curTime - $fileTime) / (3600 * 24);
    if ($n1 > clearDriverFolderTimeOutDays)
    {
        swtDelFileTree($p1);
    }
}

$retJson = array();

$retJson["machineNum"]    = 0;
$retJson["keyNum"]        = count($swtClientMachineConfigurationNames);
$retJson["keyNameList"]   = $swtClientMachineConfigurationNames;
$retJson["keyValueList"]  = array();

for ($i = 0; $i < $retJson["keyNum"]; $i++)
{
    $retJson["keyValueList"][$i] = array();
}

$db = new CPdoMySQL();

if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server";
    echo json_encode($returnMsg);
    return;
}

$tmpIP = swt_get_client_ip();

$params1 = array();
        
$sql1 = "SELECT machine_id, machine_ip " .
        "FROM mis_table_machine_health_info " .
        "WHERE TIMESTAMPDIFF(HOUR, heartbeat_time, NOW()) <= " . availableMachineCoolHours;
        
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1";
    echo json_encode($returnMsg);
    return;
}
$machineNum = 0;
//$machineIDList = array();
while($row1 = $db->fetchRow())
{
    $retJson["keyValueList"][7][$machineNum] = $row1[1];
    $retJson["keyValueList"][8][$machineNum] = $row1[0];
    
    $retJson["machineID"][$machineNum] = $row1[0];
    $retJson["machineIP"][$machineNum] = $row1[1];
    $machineNum++;
}
if ($machineNum == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no available machine found";
    echo json_encode($returnMsg);
    return;
}

for ($i = 0; $i < $machineNum; $i++)
{
    $sql1 = "SELECT * FROM mis_table_machine_info " .
            "WHERE machine_id=?";
    //var_dump($retJson["machineID"][$i] . "<br />\n\r");
    $n1 = intval($retJson["machineID"][$i]);
    //echo $n1;
    $params1 = array($n1);
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #2";
        echo json_encode($returnMsg);
        return;
    }
    $row1 = $db->fetchRow();
    if ($row1 == false)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "machineID not found #1";
        echo json_encode($returnMsg);
        return;
    }
    $envTypeList = array(5, 0, 1, 2, 6, 7, 3, 9, 10, 11);
    $sql1 = "SELECT " .
            "(SELECT env_name FROM mis_table_environment_info WHERE env_id=? AND env_type=\"" . $envTypeList[0] . "\") AS machineName, " .
            "(SELECT env_name FROM mis_table_environment_info WHERE env_id=? AND env_type=\"" . $envTypeList[1] . "\") AS videoCardName, " .
            "(SELECT env_name FROM mis_table_environment_info WHERE env_id=? AND env_type=\"" . $envTypeList[2] . "\") AS cpuName, " .
            "(SELECT env_name FROM mis_table_environment_info WHERE env_id=? AND env_type=\"" . $envTypeList[3] . "\") AS systemName, " .
            "(SELECT env_name FROM mis_table_environment_info WHERE env_id=? AND env_type=\"" . $envTypeList[4] . "\") AS memoryName, " .
            "(SELECT env_name FROM mis_table_environment_info WHERE env_id=? AND env_type=\"" . $envTypeList[5] . "\") AS chipsetName, " .
            "(SELECT env_name FROM mis_table_environment_info WHERE env_id=? AND env_type=\"" . $envTypeList[6] . "\") AS mainLineName, " .
            "(SELECT env_name FROM mis_table_environment_info WHERE env_id=? AND env_type=\"" . $envTypeList[7] . "\") AS sClockName, " .
            "(SELECT env_name FROM mis_table_environment_info WHERE env_id=? AND env_type=\"" . $envTypeList[8] . "\") AS mClockName, " .
            "(SELECT env_name FROM mis_table_environment_info WHERE env_id=? AND env_type=\"" . $envTypeList[9] . "\") AS gpuMemName " .
            "FROM mis_table_environment_info ";

    $params1 = array($row1[1],
                     $row1[2],
                     $row1[3],
                     $row1[4],
                     $row1[5],
                     $row1[6],
                     $row1[7],
                     $row1[8],
                     $row1[9],
                     $row1[10]
                     );
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #2";
        echo json_encode($returnMsg);
        return;
    }
    $row1 = $db->fetchRow();
    if ($row1 == false)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "machine configuration names not found #1";
        echo json_encode($returnMsg);
        return;
    }
    
    for ($j = 0; $j < 10; $j++)
    {
        $retJson["keyValueList"][$j][$i] = $row1[$j];
    }
}

$retJson["machineNum"] = $machineNum;
$retJson["errorCode"] = 1;
$retJson["errorMsg"] = "get available machines list ok";

echo json_encode($retJson);

?>