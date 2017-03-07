<?php

include_once "../configuration/swtConfig.php";

$batchID = intval($_POST["batchID"]);
$fileID = intval($_POST["fileID"]);

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "zip report success";

$reportFolder = $allReportsDir . "/batch" . $batchID;
$oldReportXMLList = glob($reportFolder . "/*.xlsm");

if ($fileID < count($oldReportXMLList))
{
    // zip one by one file
    $tmpPath = $oldReportXMLList[$fileID];
    $n1 = strlen($tmpPath);
    $t1 = substr($tmpPath, 0, $n1 - 4);
    $t1 .= "zip";
    
    $zip = new ZipArchive();
    $filename = $t1;

    if ($zip->open($filename, ZipArchive::CREATE) !== TRUE)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "can't open file: " . $filename;
        echo json_encode($returnMsg);
    }
    $zip->addFile($tmpPath, basename($tmpPath));

    $zip->close($tmpPath);
    
    $returnMsg["errorCode"] = 2;
    $returnMsg["errorMsg"] = "zipping XLSM to ZIP: (" . ($fileID + 1) . " / " . count($oldReportXMLList) . ")";
}

if (($fileID + 1) >= count($oldReportXMLList))
{
    foreach ($oldReportXMLList as $tmpPath)
    {
        unlink($tmpPath);
    }
    
    // zip finished
    $returnMsg["errorCode"] = 1;
    $returnMsg["errorMsg"] = "zip report success";
}

echo json_encode($returnMsg);
return;

?>