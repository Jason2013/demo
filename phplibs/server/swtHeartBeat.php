<?php

include_once "../configuration/swtMISConst.php";
include_once "swtHeartBeatFuncs.php";

$json = file_get_contents('php://input');
$obj = json_decode($json, true);
$clientCmd = intval($obj["clientCmd"]);
$serverCmd = serverDoNothing;

$clientCmdParser = new CClientHeartBeat;
$tmpMsg = $clientCmdParser->parseClientCmd($obj);



$retJson = array();
$retJson["serverCmd"] = $tmpMsg["serverCmd"];
$retJson["machineID"] = $tmpMsg["machineID"];
$retJson["errorMsg"] = isset($tmpMsg["errorMsg"]) ? $tmpMsg["errorMsg"] : "";

echo json_encode($retJson);

?>