<?php

return;

//include_once "swtExcelGenFuncs.php";
include_once "dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "genfuncs.php";
include_once "code01.php";

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

$fromNameList = array("AMD Baffin XT",
                      "AMD Ellesmere XT",
                      "AMD Fiji XT",
                      "Nvidia GeForce GTX 980 Ti",
                      "Microsoft Windows 10 (10.0) Professional 64-bit");
$toNameList = array("Baffin XT",
                    "Ellesmere XT",
                    "Fiji XT",
                    "GTX 980 Ti",
                    "Win10 64 bit");
                    
if (count($fromNameList) != count($toNameList))
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input names count invalid";
    echo json_encode($returnMsg);
    return;
}

$overlapNameList = array();

foreach ($toNameList as $tmpName)
{
    $params1 = array($tmpName);
    $sql1 = "SELECT COUNT(*) FROM mis_table_environment_info " .
            "WHERE env_name = ?";
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
    $nameNum = intval($row1[0]);
    if ($nameNum > 0)
    {
        array_push($overlapNameList, $tmpName);
    }
}

if (count($overlapNameList) > 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "target name overlaps, they are: " . implode($overlapNameList, ",");
    echo json_encode($returnMsg);
    return;
}

$replaceNum = 0;
$i = 0;
foreach ($fromNameList as $tmpName)
{
    $targetName = $toNameList[$i++];
    $params1 = array($tmpName);
    $sql1 = "SELECT env_id FROM mis_table_environment_info " .
            "WHERE env_name = ?";
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
        continue;
    }
    $envID = $row1[0];
    
    $params1 = array($targetName, $envID);
    $sql1 = "UPDATE mis_table_environment_info " .
            "SET env_name = ? " .
            "WHERE env_id = ?";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3" . $db->getError()[2];
        echo json_encode($returnMsg);
        return -1;
    }
    
    $replaceNum++;
}


$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "replace success, selected name num: " . count($fromNameList) .
                         ", replaced num: " . $replaceNum;
echo json_encode($returnMsg);
return;


?>