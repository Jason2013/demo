<?php

include_once "../configuration/swtMISConst.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../generalLibs/swtCommonLib.php";

$machineIDList = $_POST["machineIDList"];
$uploadTockenList = $_POST["uploadTockenList"];
$changeListSet = $_POST["changeListSet"];
$umdTypeList = $_POST["umdTypeList"];

$machineIDList = explode(",", $machineIDList);
$uploadTockenList = explode(",", $uploadTockenList);
$changeListSet = explode(",", $changeListSet);
$umdTypeList = explode(",", $umdTypeList);

$returnMsg = array();

if (count($machineIDList) == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input machine id list empty";
    echo json_encode($returnMsg);
    return;
}

if (count($uploadTockenList) == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input upload tocken list empty";
    echo json_encode($returnMsg);
    return;
}

for ($i = 0; $i < count($machineIDList); $i++)
{
    $machineIDList[$i] = intval($machineIDList[$i]);
}

for ($i = 0; $i < count($uploadTockenList); $i++)
{
    $uploadTockenList[$i] = cleaninput($uploadTockenList[$i], 32);
    $umdTypeList[$i] = cleaninput($umdTypeList[$i], 128);
    $changeListSet[$i] = isset($changeListSet[$i]) ? intval($changeListSet[$i]) : 0;
    
    if (strlen($uploadTockenList[$i]) == 0)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "input upload tocken list invalid";
        echo json_encode($returnMsg);
        return;
    }
    if (strlen($umdTypeList[$i]) == 0)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "input umd name invalid";
        echo json_encode($returnMsg);
        return;
    }
}

$db = new CPdoMySQL();

if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server";
    echo json_encode($returnMsg);
    return;
}

$uploadTockenListString = "";

for ($i = 0; $i < count($uploadTockenList); $i++)
{
    $uploadTockenListString .= ("'" . $uploadTockenList[$i] . "'");
    if ($i < (count($uploadTockenList) - 1))
    {
        $uploadTockenListString .= ",";
    }
}

for ($i = 0; $i < count($umdTypeList); $i++)
{
    // update umd name
    $params1 = array($umdTypeList[$i]);
    $sql1 = "SELECT env_id FROM mis_table_environment_info " .
            "WHERE env_name=? AND env_type=\"4\"";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3";
        return $returnMsg;
    }
    $row1 = $db->fetchRow();
    $umdID = -1;
    if ($row1 == false)
    {
        // insert new driver type, like Vulkan, DX11, DX12
        $params1 = array($umdTypeList[$i]);
        $sql1 = "INSERT IGNORE INTO mis_table_environment_info " .
                "(env_name, env_type) " .
                "VALUES (?, \"4\")";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3a";
            return $returnMsg;
        }
        $umdID = $db->getInsertID();
    }
    else
    {
        $umdID = $row1[0];
    }
    $params1 = array($umdID, $uploadTockenList[$i]);
    // set old blank batches time out
    $sql1 = "UPDATE mis_table_upload_info " .
            "SET umd_id=? " .
            "WHERE upload_tocken=?";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #5b";
        echo json_encode($returnMsg);
        return;
    }
}

$params1 = array();
$sql1 = "SELECT upload_id, umd_id, upload_path FROM mis_table_upload_info " .
        "WHERE upload_tocken IN (" . $uploadTockenListString . ")";
        
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1";
    echo json_encode($returnMsg);
    return;
}

$uploadIDList = array();
$umdIDList = array();
$uploadPathList = array();

while ($row1 = $db->fetchRow())
{
    array_push($uploadIDList, $row1[0]);
    array_push($umdIDList, $row1[1]);
    array_push($uploadPathList, $row1[2]);
}

if (count($uploadIDList) == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no uploaded driver match";
    echo json_encode($returnMsg);
    return;
}
    
$params1 = array();
$sql1 = "INSERT INTO mis_table_batch_list " .
        "(insert_time, batch_state) " .
        "VALUES (NOW(), \"0\")";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #3a";
    echo json_encode($returnMsg);
    return;
}
$batchID = $db->getInsertID();
$resultIDList = array();

for ($i = 0; $i < count($machineIDList); $i++)
{
    for ($j = 0; $j < count($umdIDList); $j++)
    {
        $params1 = array($machineIDList[$i], $batchID, $umdIDList[$j], $changeListSet[$j]);
        $sql1 = "INSERT INTO mis_table_result_list " .
                "(machine_id, batch_id, umd_id, cl_value, path_id, result_state, insert_time) " .
                "VALUES (?, ?, ?, ?, " . PHP_INT_MAX . ", \"0\", NOW())";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #2b";
            echo json_encode($returnMsg);
            return;
        }
        $tmpID = $db->getInsertID();
        array_push($resultIDList, $tmpID);
    }
}

for ($i = 0; $i < count($resultIDList); $i++)
{
    $j = $i % count($umdIDList);
    $params1 = array($resultIDList[$i], $uploadIDList[$j]);
    $sql1 = "INSERT INTO mis_table_task_list " .
            "(result_id, upload_id, insert_time, task_state) " .
            "VALUES (?, ?, NOW(), \"0\")";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3";
        echo json_encode($returnMsg);
        return;
    }
}

$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "task submitted successfully";
echo json_encode($returnMsg);

    
?>