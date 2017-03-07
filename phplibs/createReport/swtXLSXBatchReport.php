<?php

include_once "../configuration/swtConfig.php";
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

if ($fileID < count($oldReportXMLList))
{
    // zip one by one file
    $tmpPath = $oldReportXMLList[$fileID];
    $n1 = strlen($tmpPath);
    $t1 = substr($tmpPath, 0, $n1 - 3);
    $filename = $t1 . "xlsx";
    $filename2 = $t1 . "xlsm";
    
    $tmpFileName = basename($filename);
    $tmpFileNameSection = explode("_", $tmpFileName);
    $tmpCardName = "";
    $tmpSysName = "";
    if (count($tmpFileNameSection) >= 2)
    {
        $tmpCardName = $tmpFileNameSection[0];
        $tmpSysName = $tmpFileNameSection[1];
    }
    
    $isFlatData = strpos($tmpFileName, "(FlatData)");
    
    $tmpSrcFolder = substr($filename, 0, strlen($filename) - strlen($tmpFileName));
    $tmpSrcFolder2 = $tmpSrcFolder . $tmpCardName . "_" . $tmpSysName;
    $tmpSrcFilePath = $tmpSrcFolder2 . "/" . $resultFileName3;
    $tmpVBAConfigPath = $reportFolder . "/" . $tmpFileNameSection[0] .
                        "_" . $tmpFileNameSection[1] . "/" . $swtTempVBAConfigJsonName;
    $tmpVBAPath = $reportFolder . "/" . $swtTempVBAName;
    
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
            
        }
        

        
        $excel->ActiveWorkbook->Sheets(1)->Activate();
        
        $excel->ActiveWorkbook->SaveAs("" . __dir__ . "/" . $filename, 51);
        $excel->ActiveWorkbook->Close();

        $excel->WorkBooks->Close();
        $excel->Quit();
        
        /*
        //echo "" . $tmpVBAConfigPath;
        if (file_exists($tmpVBAConfigPath))
        {
            // add graph
            $excel = new COM("Excel.Application");
            
            $workBook = $excel->WorkBooks->Open("" . __dir__ . "/" . $filename);
            
            $t1 = file_get_contents($tmpVBAConfigPath);
            $vbaConfig = json_decode($t1);
            
            $t2 = file_get_contents("../../vbaLibs/createGraph01.vba");
            
            $t2 = sprintf($t2, $vbaConfig->graphDataArea, $tmpCardName);
            file_put_contents($tmpVBAPath, $t2);
            
            $workBook->VBProject->VBComponents->Item(1)->CodeModule->AddFromFile(__dir__ . "\\" . $tmpVBAPath);
            
            $excel->Run("ThisWorkbook.createGraph01");
            
            $excel->ActiveWorkbook->Sheets(1)->Activate();
            
            $excel->ActiveWorkbook->SaveAs("" . __dir__ . "/" . $filename2, 52);
            $excel->ActiveWorkbook->Close();

            $excel->WorkBooks->Close();
            $excel->Quit();
            
            unlink($tmpVBAConfigPath);
            unlink($tmpVBAPath);
            //unlink($filename);
        }
        //*/

    }
    catch (Exception $e)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = $e->getMessage();
        
        echo json_encode($returnMsg);
        return;
    }
    
    $returnMsg["errorCode"] = 2;
    $returnMsg["errorMsg"] = "converting XML to XLSX: (" . ($fileID + 1) . " / " . count($oldReportXMLList) . ")";
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
    
}



echo json_encode($returnMsg);
return;

?>