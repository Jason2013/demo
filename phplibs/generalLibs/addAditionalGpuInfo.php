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
$returnMsg["errorMsg"] = "set additional info success";


$gpuNameList = array("Baffin XT",
                     "Ellesmere XT",
                     "Fiji XT",
                     "GTX 980 Ti");

$gpuAdditionalInfo = array("1000", "1500", "4GB/GDDR5",
                           "1000", "1500", "8GB/GDDR5",
                           "1000", "500",  "4GB/HBM",
                           "",     "",     "6GB/GDDR5");

$gpuAdditionalInfoType = array(9, 10, 11);

$db = new CPdoMySQL();
if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
    echo json_encode($returnMsg);
    return;
}


$i = 0;
foreach ($gpuNameList as $tmpName)
{
    $params1 = array($tmpName);
    $sql1 = "SELECT env_id FROM mis_table_environment_info " .
            "WHERE env_type=0 AND env_name=?";
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
    $cardID = $row1[0];
    
    $tmpInfoIDList = array();
    for ($j = 0; $j < 3; $j++)
    {
        $tmpInfoName = $gpuAdditionalInfo[$i * 3 + $j];
        $tmpInfoType = $gpuAdditionalInfoType[$j];
        
        $params1 = array($tmpInfoType, $tmpInfoName);
        $sql1 = "SELECT env_id FROM mis_table_environment_info " .
                "WHERE env_type=? AND env_name=?";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            echo json_encode($returnMsg);
            return;
        }
        $row1 = $db->fetchRow();
        $tmpInfoID = "";
        if ($row1 == false)
        {
            $params1 = array($tmpInfoName, $tmpInfoType);
            $sql1 = "INSERT INTO mis_table_environment_info " .
                    "(env_name, env_type) " .
                    "VALUES (?, ?)";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                echo json_encode($returnMsg);
                return;
            }
            $tmpInfoID = $db->getInsertID();
        }
        else
        {
            $tmpInfoID = $row1[0];
        }
        array_push($tmpInfoIDList, $tmpInfoID);
    }
    
    $returnMsg["tmpInfoIDList" . $i] = $tmpInfoIDList;
    $returnMsg["cardID" . $i] = $cardID;
    
    $params1 = array($tmpInfoIDList[0], $tmpInfoIDList[1], $tmpInfoIDList[2], $cardID);
    $sql1 = "UPDATE mis_table_machine_info " .
            "SET s_clock_id=?, m_clock_id=?, gpu_mem_id=? " .
            "WHERE card_id=? AND s_clock_id IS NULL AND m_clock_id IS NULL AND gpu_mem_id IS NULL";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
        echo json_encode($returnMsg);
        return;
    }
    
    $i++;
}

echo json_encode($returnMsg);

echo "done";

?>