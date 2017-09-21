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
$oldReportXLSXList = glob($reportFolder . "/*.xlsx");
$oldReportXMLList = glob($reportFolder . "/*.xml");

$returnMsg["oldReportXLSXList"] = $oldReportXLSXList;
$returnMsg["fileID"] = $fileID;

if ($fileID < count($oldReportXLSXList))
{
    // zip one by one file
    $tmpPath = $oldReportXLSXList[$fileID];
    $n1 = strlen($tmpPath);
    $t1 = substr($tmpPath, 0, $n1 - 4);
    //$filename = $t1 . "xlsx";
    $filename2 = $t1 . "xlsm";
    
    $tmpFileName = basename($tmpPath);
    $tmpFileNameSection = explode("_", $tmpFileName);
    $tmpCardName = "";
    $tmpSysName = "";
    if (count($tmpFileNameSection) >= 2)
    {
        $tmpCardName = $tmpFileNameSection[0];
        $tmpSysName = $tmpFileNameSection[1];
    }

    $tmpVBAConfigPath = $reportFolder . "/" . $tmpFileNameSection[0] .
                        "_" . $tmpFileNameSection[1] . "/" . $swtTempVBAConfigJsonName;
    $tmpVBAPath = $reportFolder . "/" . $swtTempVBAName;
    
    $isFlatData = strpos($tmpFileName, "(FlatData)");
    if ($isFlatData !== false)
    {
        // flat data
        try
        {
            $excel = new COM("Excel.Application");
            
            $workBook = $excel->WorkBooks->Open("" . __dir__ . "/" . $tmpPath);
            
            $t2 = file_get_contents("../../vbaLibs/flatData01.vba");
            
            file_put_contents($tmpVBAPath, $t2);
            
            $workBook->VBProject->VBComponents->Item(1)->CodeModule->AddFromFile(__dir__ . "\\" . $tmpVBAPath);
            
            $excel->Run("ThisWorkbook.flatData01");
            
            $excel->ActiveWorkbook->Sheets(1)->Activate();
            
            $excel->ActiveWorkbook->SaveAs("" . __dir__ . "/" . $filename2, 52);
            $excel->ActiveWorkbook->Close();

            $excel->WorkBooks->Close();
            $excel->Quit();
            
            unlink($tmpVBAPath);

        }
        catch (Exception $e)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = $e->getMessage();
            
            echo json_encode($returnMsg);
            return;
        }
    }
    else
    {
        // main report
        try
        {
            //echo $tmpVBAConfigPath;
            if (file_exists($tmpVBAConfigPath))
            {
                // add graph
                $excel = new COM("Excel.Application");
                
                $workBook = $excel->WorkBooks->Open("" . __dir__ . "/" . $tmpPath);
                
                $t1 = file_get_contents($tmpVBAConfigPath);
                $vbaConfig = json_decode($t1);
                
                $t2 = file_get_contents("../../vbaLibs/createGraph01.vba");
                
                $tmpArea = explode(",", $vbaConfig->graphDataArea);
                
                $tmpRange = array();
                $t4 = "";
                if (count($tmpArea) == 1)
                {
                    $t4 = "destSheet.Range(\"" . $tmpArea[0] . "\")";
                }
                else
                {
                    for ($i = 0; $i < count($tmpArea); $i++)
                    {
                        $t3 = "destSheet.Range(\"" . $tmpArea[$i] . "\")";
                        array_push($tmpRange, $t3);
                    }
                    $t4 = implode(",", $tmpRange);
                    $t4 = "Application.Union(" . $t4 . ")";
                }
                // dropArea
                $codePiece1 = "";
                //for ($i = 0; $i < count($vbaConfig->dropArea); $i++)
                //{
                //    if (strlen($vbaConfig->dropArea[$i]) > 0)
                //    {
                //        $codePiece1 .= "    Columns(\"" . $vbaConfig->dropArea[$i] . "\").Select\n" .
                //                       "    Selection.Delete Shift:=xlToLeft\n";
                //    }
                //}
                if (($vbaConfig->cmpMachineID != -1) ||
                    ($vbaConfig->crossType == 2))
                {
                    $t5 = "    myChart.Activate\n" .
                          "    ActiveChart.ChartTitle.Select\n" .
                          "    Selection.Format.TextFrame2.TextRange.Characters.Text = _\n" .
                          "        \"Microbench Performance relative to DXX - Fiji XT\" & Chr(13) & \"%s\"\n" .
                          "    With Selection.Format.TextFrame2.TextRange.Characters(50, %d).Font.Fill\n" .
                          "        .Visible = msoTrue\n" .
                          "        .ForeColor.ObjectThemeColor = msoThemeColorAccent6\n" .
                          "        .ForeColor.TintAndShade = 0\n" .
                          "        .ForeColor.Brightness = 0\n" .
                          "        .Transparency = 0\n" .
                          "        .Solid\n" .
                          "    End With\n";
                    $t6 = $vbaConfig->cmpCardName . " vs " . $vbaConfig->curCardName;
                    if (strcmp($vbaConfig->cmpCardName, $vbaConfig->curCardName) == 0)
                    {
                        $tmpList1 = explode(" ", $vbaConfig->cmpSysName);
                        $tmpList2 = explode(" ", $vbaConfig->curSysName);
                        $t6 = $tmpList1[0] . " vs " . $tmpList2[0];
                    }
                    if ((strlen($vbaConfig->curMachineName) > 0) &&
                        (strlen($vbaConfig->cmpMachineName) > 0) &&
                        (strcmp($vbaConfig->curMachineName, $vbaConfig->cmpMachineName) != 0))
                    {
                        // if no json, use folder names
                        $t6 = $vbaConfig->cmpMachineName . " vs " . $vbaConfig->curMachineName;
                    }
                    
                    if ($vbaConfig->crossType == 2)
                    {
                        // cross build
                        $t6 = $vbaConfig->cmpResultTime . " vs " . $vbaConfig->curBatchTime;
                    }
                    
                    $n1 = strlen($t6);
                    $codePiece1 = sprintf($t5, $t6, $n1);
                }
                
                // $vbaConfig->graphDataArea
                $t2 = sprintf($t2, $t4, $tmpCardName, $vbaConfig->graphDataAreaNoBlank, $codePiece1);
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
            }
        }
        catch (Exception $e)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = $e->getMessage();
            
            echo json_encode($returnMsg);
            return;
        }
    }
    
    $returnMsg["errorCode"] = 2;
    $returnMsg["errorMsg"] = "generating Graphs: (" . ($fileID + 1) . " / " . count($oldReportXLSXList) . ")";
}

if (($fileID + 1) >= count($oldReportXLSXList))
{
    foreach ($oldReportXLSXList as $tmpPath)
    {
        unlink($tmpPath);
    }
    
    $tmpFolderList = glob($reportFolder . "/*");
    foreach ($tmpFolderList as $tmpPath)
    {
        $b1 = true;
        $t1 = basename($tmpPath);
        $tmpPos = strpos($t1, $reportFolderCheckWord);
        $b1 = $tmpPos === false;
        if (is_dir($tmpPath) && $b1)
        {
            // delete temp runlog.txt
            swtDelFileTree($tmpPath);
        }
    }
    
    // zip finished
    $returnMsg["errorCode"] = 1;
    $returnMsg["errorMsg"] = "convert report success";
    
}

echo json_encode($returnMsg);
return;

?>