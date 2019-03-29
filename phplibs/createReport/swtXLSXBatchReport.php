<?php

include_once "../configuration/swtConfig.php";
include_once "../configuration/swtMISConst.php";
include_once "../generalLibs/genfuncs.php";

$batchID = intval($_POST["batchID"]);
$fileID = intval($_POST["fileID"]);
$reportType = intval($_POST["reportType"]);
$curReportFolder = intval($_POST["curReportFolder"]);

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "convert report success";

$reportFolder = $allReportsDir . "/batch" . $batchID;
if ($reportType == 0)
{
    $curReportFolder = sprintf("%05d", $curReportFolder);
    $batchFolder = $downloadReportDir . "/batch" . $batchID;

    $reportFolder = $batchFolder . "/" . $curReportFolder;
}
$returnMsg["reportFolder"] = $reportFolder;
$oldReportXMLList = glob($reportFolder . "/*.xml");
$resultFileName3 = "runlog.txt";
$resultSheetName = "runlog";

$curMachineID = -1;

if ($fileID < count($oldReportXMLList))
{
    // zip one by one file
    $tmpPath = $oldReportXMLList[$fileID];
    $n1 = strlen($tmpPath);
    $t1 = substr($tmpPath, 0, $n1 - 3);
    $filename = $t1 . "xlsx";
    //$filename2 = $t1 . "xlsm";
    
    $tmpFileName = basename($filename);
    $tmpFileBaseName = basename($filename, ".xlsx");
    
    $tmpPos = strpos($tmpFileBaseName, "(FlatData)");
    $isFlatData = $tmpPos !== false;
    
    if ($isFlatData)
    {
        $tmpFileBaseName = substr($tmpFileBaseName, 0, strlen($tmpFileBaseName) - strlen("(FlatData)"));
    }
    
    $tmpList = explode("_", $tmpFileBaseName);
    $tmpUmd2Name = $tmpList[count($tmpList) - 1];
    $tmpUmd2Name_ = strlen($tmpUmd2Name) > 0 ? $tmpUmd2Name . "_" : "";
    $tmpFolderBaseName = substr($tmpFileBaseName, 0, strlen($tmpFileBaseName) - strlen($tmpUmd2Name) - 1);
    if (is_dir($reportFolder . "/" . $tmpFileBaseName))
    {
        $tmpFolderBaseName = $tmpFileBaseName;
        $tmpUmd2Name = "";
        $tmpUmd2Name_ = "";
    }
    
    $tmpVBAConfigPath = $reportFolder . "/" . $tmpFolderBaseName . "/" . $tmpUmd2Name_ . $swtTempVBAConfigJsonName;
    $vbaConfig = null;
    $tmpCardName = "";
    $tmpSysName = "";
    $tmpUmd2Name = "";
    $tmpUmd2Name_ = "";
    
    $repCardNameList = array();
    $repSysNameList = array();
    
    if (file_exists($tmpVBAConfigPath))
    {
        $t1 = file_get_contents($tmpVBAConfigPath);
        $vbaConfig = json_decode($t1);
        
        $reportNameList = explode(",", $vbaConfig->reportNameList);
        $repCardNameList = explode(",", $vbaConfig->repCardNameList);
        $repSysNameList = explode(",", $vbaConfig->repSysNameList);
        $repDriver2NameList = explode(",", $vbaConfig->repDriver2NameList);
        
        $tmpPos = array_search($tmpFileBaseName, $reportNameList);
        
        if ($tmpPos !== false)
        {
            $tmpCardName = $repCardNameList[$tmpPos];
            $tmpSysName = $repSysNameList[$tmpPos];
            $tmpUmd2Name = $repDriver2NameList[$tmpPos];
            $tmpUmd2Name_ = $tmpUmd2Name . "_";
        }
    }
    
    //$tmpFileNameSection = explode("_", $tmpFileName);
    //$tmpCardName = "";
    //$tmpSysName = "";
    //$tmpUmd2Name = "";
    //if (count($tmpFileNameSection) >= 2)
    //{
    //    $tmpCardName = $tmpFileNameSection[0];
    //    $tmpSysName = $tmpFileNameSection[1];
    //}
    //if (count($tmpFileNameSection) >= 3)
    //{
    //    if (array_search($tmpFileNameSection[2], $swtUmdNameList) !== false)
    //    {
    //        $tmpUmd2Name = $tmpFileNameSection[2] . "_";
    //    }
    //}
    
    //$isFlatData = strpos($tmpFileName, "(FlatData)");
    
    $tmpSrcFolder = substr($filename, 0, strlen($filename) - strlen($tmpFileName));
    $tmpSrcFolder2 = $tmpSrcFolder . $tmpCardName . "_" . $tmpSysName;
    $tmpSrcFilePath = $tmpSrcFolder2 . "/" . $resultFileName3;
    //$tmpVBAConfigPath = $reportFolder . "/" . $tmpFileNameSection[0] .
    //                    "_" . $tmpFileNameSection[1] .
    //                    "/" . $tmpUmd2Name . $swtTempVBAConfigJsonName;
    //$tmpVBAPath = $reportFolder . "/" . $swtTempVBAName;
    
    try
    {
        $excel = new COM("Excel.Application");
        
        $workBook = $excel->WorkBooks->Open("" . __dir__ . "/" . $tmpPath);

        if (file_exists($tmpSrcFilePath) &&
            ($isFlatData == false))
        {
            // if runlog.txt is ready
            $tmpSheet = $excel->ActiveWorkbook->Sheets($excel->ActiveWorkbook->Sheets->Count);
            
            $tmpSheet = $excel->ActiveWorkbook->Sheets->Add(null, $tmpSheet);
            $tmpSheet->Activate();
            $tmpSheet->Name = $resultSheetName;
            
            $tmpSheet = $excel->ActiveWorkbook->Sheets($excel->ActiveWorkbook->Sheets->Count);
            $tmpSheet->OLEObjects->Add(null, "" . __dir__ . "/" . $tmpSrcFilePath);
            
            if (($vbaConfig->reportType == 2) ||
                ($vbaConfig->reportType == 3))
            {
                // shaderbench, framebench
                $tmpMachineLabelList = explode(",", $vbaConfig->machineLabelList);
                
                for ($i = 0; $i < count($tmpMachineLabelList); $i++)
                {
                    $tmpName = $tmpMachineLabelList[$i];
                    
                    //$t1 = "H:\\wamp64\\www\\benchMax\\test01.txt";
                    //$t2 = "";
                    //if (file_exists($t1))
                    //{
                    //    $t2 = file_get_contents($t1);
                    //}
                    //$t2 .= $tmpName . "\n\r";
                    //file_put_contents($t1, $t2);
                    
                    if ($tmpName == ($tmpCardName . "_" . $tmpSysName))
                    {
                        continue;
                    }
                    
                    $tmpPath = $tmpSrcFolder . $tmpName . "/" . $resultFileName3;
                    
                    if (file_exists($tmpPath))
                    {
                        $tmpSheet->OLEObjects->Add(null, "" . __dir__ . "/" . $tmpPath);
                    }
                }
            }
            
        }
        

        
        $excel->ActiveWorkbook->Sheets(1)->Activate();
        
        $excel->ActiveWorkbook->SaveAs("" . __dir__ . "/" . $filename, 51);
        $excel->ActiveWorkbook->Close();

        $excel->WorkBooks->Close();
        $excel->Quit();

    }
    catch (Exception $e)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = $e->getMessage();
        
        echo json_encode($returnMsg);
        return;
    }
    
    //if (file_exists($tmpVBAConfigPath))
    //{
    //    $t1 = file_get_contents($tmpVBAConfigPath);
    //    $vbaConfig = json_decode($t1);
    //    
    //    if (isset($vbaConfig->curMachineID))
    //    {
    //        $curMachineID = $vbaConfig->curMachineID;
    //    }
    //}
    
    $returnMsg["errorCode"] = 2;
    $returnMsg["errorMsg"] = "converting XML to XLSX: (" . ($fileID + 1) . " / " . count($oldReportXMLList) . ")";
    $returnMsg["curMachineID"] = $curMachineID;
    $returnMsg["fileID"] = $fileID + 1;
    $returnMsg["fileNum"] = count($oldReportXMLList);
}

if (($fileID + 1) >= count($oldReportXMLList))
{
    foreach ($oldReportXMLList as $tmpPath)
    {
        unlink($tmpPath);
        
        //$t1 = substr($tmpPath, 0, strlen($tmpPath) - 3);
        //$t1 .= "xlsx";
        //unlink($t1);
    }
    
    /*
    $tmpFolderList = glob($reportFolder . "/*");
    foreach ($tmpFolderList as $tmpPath)
    {
        if (is_dir($tmpPath))
        {
            // delete temp runlog.txt
            swtDelFileTree($tmpPath);
        }
    }
    //*/
    // zip finished
    $returnMsg["errorCode"] = 1;
    $returnMsg["errorMsg"] = "convert report success";
    $returnMsg["curMachineID"] = $curMachineID;
}



echo json_encode($returnMsg);
return;

?>