<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";


$returnMsg = array();
$returnMsg["htmlCode"] = "";

$db = new CPdoMySQL();

if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server";
    echo json_encode($returnMsg);
    return;
}

$params1 = array();
$sql1 = "SELECT t0.* FROM mis_table_environment_info t0 " .
        "WHERE t0.env_type = 0 GROUP BY t0.env_name " .
        "";
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1";
    echo json_encode($returnMsg);
    return;
}

while ($row1 = $db->fetchRow())
{
    echo "cardName: " . $row1[1] . ", cardID: " . $row1[0] . "<br />\n";
}

?>