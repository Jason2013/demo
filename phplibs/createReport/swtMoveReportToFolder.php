<?php

include_once "../configuration/swtConfig.php";
include_once "../configuration/swtMISConst.php";
include_once "../generalLibs/genfuncs.php";

$batchID = intval($_POST["batchID"]);
$curReportFolder = intval($_POST["curReportFolder"]);
$crossType = intval($_POST["crossType"]);

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "move reports to folder success";

$curReportFolder = sprintf("%05d", $curReportFolder);
$batchFolder = $downloadReportDir . "/batch" . $batchID;

$reportFolder = $batchFolder . "/" . $curReportFolder;

$returnMsg["reportFolder"] = $reportFolder;

$targetPath = $reportFolder;

if ($crossType == 10)
{
    // cross api
    $targetPath .= "/" . $reportFolderCheckWord . "crossAPI";
    mkdir($targetPath);
}
else if ($crossType == 11)
{
    // cross machine
    $targetPath .= "/" . $reportFolderCheckWord . "crossMachine";
    mkdir($targetPath);
}

$oldReportXMLList = glob($reportFolder . "/*.xlsm");


foreach ($oldReportXMLList as $tmpPath)
{
    $t1 = $targetPath . "/" . basename($tmpPath);
    copy($tmpPath, $t1);
    unlink($tmpPath);
}

// zip finished
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "move reports to folder success";

echo json_encode($returnMsg);
return;

?>