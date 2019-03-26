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
$curMachineID = -1;

$vulkanReportName = "";
$vulkanReportFinalName = "";

if ($fileID < count($oldReportXLSXList))
{
    // zip one by one file
    $tmpPath = $oldReportXLSXList[$fileID];
    $n1 = strlen($tmpPath);
    $t1 = substr($tmpPath, 0, $n1 - 4);
    //$filename = $t1 . "xlsx";
    $filename2 = $t1 . "xlsm";
    
    $tmpFileName = basename($tmpPath);
    $tmpFileBaseName = basename($tmpPath, ".xlsx");
    
    $tmpPos = strpos($tmpFileBaseName, "(FlatData)");
    $isFlatData = $tmpPos !== false;
    
    if ($isFlatData)
    {
        $tmpFileBaseName = substr($tmpFileBaseName, 0, strlen($tmpFileBaseName) - strlen("(FlatData)"));
    }
    
    //$tmpFileNameSection = explode("_", $tmpFileName);
    //$tmpCardName = "";
    //$tmpSysName = "";
    //$tmpUmd2Name = "";
    //$tmpUmd2Name_ = "";
    //if (count($tmpFileNameSection) >= 2)
    //{
    //    $tmpCardName = $tmpFileNameSection[0];
    //    $tmpSysName = $tmpFileNameSection[1];
    //}
    //if (count($tmpFileNameSection) >= 3)
    //{
    //    if (array_search($tmpFileNameSection[2], $swtUmdNameList) !== false)
    //    {
    //        $tmpUmd2Name = $tmpFileNameSection[2];
    //        $tmpUmd2Name_ = $tmpFileNameSection[2] . "_";
    //    }
    //}

    //$tmpVBAConfigPath = $reportFolder . "/" . $tmpFileNameSection[0] .
    //                    "_" . $tmpFileNameSection[1] .
    //                    "/" . $tmpUmd2Name_ . $swtTempVBAConfigJsonName;
                        
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
                        
    //$tmpVBAConfigPath = $reportFolder . "/" . $tmpFileBaseName . "_" . $swtTempVBAConfigJsonName;
    $vbaConfig = null;
    $tmpCardName = "";
    $tmpSysName = "";
    $tmpUmd2Name = "";
    $tmpUmd2Name_ = "";
                        
    $tmpVBAPath = $reportFolder . "/" . $swtTempVBAName;
    
    $vbaConfig = null;
    $vbaConfigList = array();
    $vbaConfigPathList = array();
    $uniqueDriver2NameList = array();
    
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
        
        if (isset($vbaConfig->curMachineID))
        {
            $curMachineID = $vbaConfig->curMachineID;
        }
        
        if (isset($vbaConfig->uniqueDriver2NameList))
        {
            $uniqueDriver2NameList = explode(",", $vbaConfig->uniqueDriver2NameList);
            
            for ($i = 0; $i < count($uniqueDriver2NameList); $i++)
            {
                //$tmpPath2 = $reportFolder . "/" . $tmpFileNameSection[0] .
                //           "_" . $tmpFileNameSection[1] .
                //           "/" . $uniqueDriver2NameList[$i] . "_" . $swtTempVBAConfigJsonName;
                           
                $tmpPath2 = $reportFolder . "/" . $tmpCardName .
                           "_" . $tmpSysName .
                           "/" . $uniqueDriver2NameList[$i] . "_" . $swtTempVBAConfigJsonName;
                           
                $vbaConfigPathList []= $tmpPath2;
                if (file_exists($tmpPath2))
                {
                    $t1 = file_get_contents($tmpPath2);
                    $tmpConfig = json_decode($t1, true);
                    
                    $vbaConfigList []= $tmpConfig;
                }
            }
        }
        
        $returnMsg["vbaConfigList"] = $vbaConfigList;
        $returnMsg["vbaConfigPathList"] = $vbaConfigPathList;
        
        if (isset($vbaConfig->tmpUmd2Name))
        {
            $tmpUmd2Name = $vbaConfig->tmpUmd2Name;
        }
    }
    
    //$isFlatData = strpos($tmpFileName, "(FlatData)");
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
            $returnMsg["errorMsg"] = $e->getMessage() . ", line: " . __LINE__;
            
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
            //if (file_exists($tmpVBAConfigPath))
                
    //{
    //    $t1 = "";
    //    $t2 = "H:\\wamp64\\www\\benchMax\\test01.txt";
    //    if (file_exists($t2))
    //    {
    //        $t1 = file_get_contents($t2);
    //    }
    //
    //    $t1 .= "\n";
    //    $t1 .= $filename2 . "\n";
    //    $t1 .= $tmpPath . "\n";
    //    $t1 .= $tmpVBAConfigPath;
    //    
    //    file_put_contents($t2, $t1);
    //}
            
            if ($vbaConfig != null)
            {
        

        
                // add graph
                $excel = new COM("Excel.Application");
                
                $workBook = $excel->WorkBooks->Open("" . __dir__ . "/" . $tmpPath);
                
                //$t1 = file_get_contents($tmpVBAConfigPath);
                //$vbaConfig = json_decode($t1);
                
                $t2 = file_get_contents("../../vbaLibs/createGraph01.vba");
                $t2a = file_get_contents("../../vbaLibs/createGraphShaderBench.vba");
                
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
                
                $tmpArea2 = explode(",", $vbaConfig->graphDataArea);
                if (isset($vbaConfig->graphDataArea2))
                {
                    $tmpArea2 = explode(",", $vbaConfig->graphDataArea2);
                }
                
                $tmpRange = array();
                $t4a = "";
                if (count($tmpArea2) == 1)
                {
                    $t4a = "destSheet.Range(\"" . $tmpArea2[0] . "\")";
                }
                else
                {
                    for ($i = 0; $i < count($tmpArea2); $i++)
                    {
                        $t3 = "destSheet.Range(\"" . $tmpArea2[$i] . "\")";
                        array_push($tmpRange, $t3);
                    }
                    $t4a = implode(",", $tmpRange);
                    $t4a = "Application.Union(" . $t4a . ")";
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
                          "        \"Microbench Performance relative to DXX          \" & Chr(13) & \"%s\"\n" .
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
                
                //if (($vbaConfig->reportType == 3) &&
                //    isset($vbaConfig->shrinkColumnArea))
                //{
                //    // framebench
                //    $codePiece1 .= "\n";
                //    $codePiece1 .= "Columns(\"" . $vbaConfig->shrinkColumnArea . "\").ColumnWidth = 0\n";
                //}
                
                // framebench
                // set first title & second title of charts
                //if ($vbaConfig->reportType == 3)
                //{
                //    if (isset($vbaConfig->graphTitle) &&
                //        isset($vbaConfig->chartSecondTitle))
                //    {
                //        // if has title & second title
                //        $codePiece1 .= "\n";
                //        $codePiece1 .= "Call setSecondTitleColor(\"" . $vbaConfig->graphTitle . "\", " .
                //                                                "\"" . $vbaConfig->chartSecondTitle . "\")\n";
                //    }
                //    
                //    if (isset($vbaConfig->graphDataBarNum) &&
                //        isset($vbaConfig->graphDataBarNum2))
                //    {
                //        // if has bars num in charts, set percent tag to bars
                //        $codePiece1 .= "\n";
                //        $codePiece1 .= "Call setCharBarPercentTag(" . $vbaConfig->graphDataBarNum . ", " .
                //                                                 "" . $vbaConfig->graphDataBarNum2 . ")\n";
                //    }
                //    //else if (isset($vbaConfig->graphDataBarNum))
                //    //{
                //    //    $codePiece1 .= "\n";
                //    //    $codePiece1 .= "Call setCharBarPercentTag(" . $vbaConfig->graphDataBarNum . ", " .
                //    //                                             "0)\n";
                //    //}
                //}
                
                // framebench
                //if (($vbaConfig->reportType == 3) &&
                //    ($vbaConfig->testBarNum == 2))
                //{
                //    $codePiece1 .= "\n";
                //    $codePiece1 .= "setCharBarColor(" . $vbaConfig->testNameNum . ")\n";
                //    
                //    $codePiece1 .= "\n";
                //    $codePiece1 .= "Call removeChartLegend()\n";
                //}
                
                // microbench outuser del one of chart
                if ($vbaConfig->reportType == 4)
                {
                    $codePiece1 .= "\n";
                    $codePiece1 .= "Call removeMBOutUserSecChart()\n";
                }
                
                $titleAdd = $tmpCardName;
                if (($vbaConfig->reportType == 2) ||
                    ($vbaConfig->reportType == 3))
                {
                    $titleAdd = "";
                }
                              
                $t2 = sprintf($t2, $t4, $vbaConfig->graphTitle . $titleAdd, 
                              $t4a, $vbaConfig->graphTitle . $titleAdd, 
                              $vbaConfig->graphDataAreaNoBlank, $codePiece1);
                              
                //$t2a = sprintf($t2a, $t4, $vbaConfig->graphTitle . $titleAdd, 
                //              $t4a, $vbaConfig->graphTitle . $titleAdd, 
                //              $vbaConfig->graphDataAreaNoBlank, $codePiece1);
                              
                file_put_contents($tmpVBAPath, $t2);
                
                if (($vbaConfig->reportType == 2) ||
                    ($vbaConfig->reportType == 3))
                {
                    // shaderbench, framebench
                    if ($tmpUmd2Name == $uniqueDriver2NameList[count($uniqueDriver2NameList) - 1])
                    {
                        // vulkan
                        // Call SetSheetGraph("Vulkan", "X5:AA31", "888", "Z5:AA31")
                        $t1 = "";
                        for ($i = 0; $i < count($uniqueDriver2NameList); $i++)
                        {
                            $tmpArea2 = explode(",", $vbaConfigList[$i]["graphDataArea"]);
                            $tmpArea3 = $vbaConfigList[$i]["graphDataAreaNoBlank"];
                            
                            $t1 .= "Call SetSheetGraph(\"" . $uniqueDriver2NameList[$i] . "\", \"" . $tmpArea2[0] . "\", " .
                                   "\"" . $vbaConfig->graphTitle . $titleAdd . "\", \"" . $tmpArea3 . "\", " .
                                   "\"" . $vbaConfig->graphTitle . "\", \"" . $vbaConfig->chartSecondTitle . "\", " .
                                   "" . $vbaConfig->graphDataBarNum . ")\n";
                        }
                        
                        $codePiece1 = $t1 . $codePiece1;
                        
                        //$tmpFileName2 = basename($filename2);
                        //$tmpFileNameSection2 = explode("_", $tmpFileName2);
                        //$t1 = substr($filename2, 0, strlen($filename2) - strlen($tmpFileName2));
                        //if (count($tmpFileNameSection2) >= 4)
                        //{
                        //    $vulkanReportName = $filename2;
                        //    $vulkanReportFinalName = $t1 . $tmpFileNameSection2[0] . "_" .
                        //                             $tmpFileNameSection2[1] . "_" .
                        //                             $tmpFileNameSection2[3];
                        //}
                        $vulkanReportName = $filename2;
                        $vulkanReportFinalName = $reportFolder . "/" . $tmpCardName .
                                                 "_" . $tmpSysName . ".xlsm";
                    }
                    else
                    {
                        $t1 = "Call SetSheetGraph(\"" . $tmpUmd2Name . "\", \"" . $tmpArea[0] . "\", " .
                              "\"" . $vbaConfig->graphTitle . $titleAdd . "\", \"" . $vbaConfig->graphDataAreaNoBlank . "\", " .
                              "\"" . $vbaConfig->graphTitle . "\", \"" . $vbaConfig->chartSecondTitle . "\", " .
                              "" . $vbaConfig->graphDataBarNum . ")\n";
                               
                        $codePiece1 = $t1 . $codePiece1;
                    }
                    
                    $t2a = sprintf($t2a, $codePiece1);
                    
                    file_put_contents($tmpVBAPath, $t2a);
                }
                
                $workBook->VBProject->VBComponents->Item(1)->CodeModule->AddFromFile(__dir__ . "\\" . $tmpVBAPath);
                
                $excel->Run("ThisWorkbook.createGraph01");
                
                $excel->ActiveWorkbook->Sheets(1)->Activate();
                
                $excel->ActiveWorkbook->SaveAs("" . __dir__ . "/" . $filename2, 52);
                $excel->ActiveWorkbook->Close();

                $excel->WorkBooks->Close();
                $excel->Quit();
                
                
                if ((strlen($vulkanReportName) > 0) &&
                    (strlen($vulkanReportFinalName) > 0))
                {
                    // for shaderbench
                    if (file_exists($vulkanReportName))
                    {
                        rename($vulkanReportName, $vulkanReportFinalName);
                    }
                }
                
                //unlink($tmpVBAConfigPath);
                unlink($tmpVBAPath);
            }
        }
        catch (Exception $e)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = $e->getMessage() . ", line: " . __LINE__;
            
            echo json_encode($returnMsg);
            return;
        }
    }
    
    $returnMsg["errorCode"] = 2;
    $returnMsg["errorMsg"] = "generating Graphs: (" . ($fileID + 1) . " / " . count($oldReportXLSXList) . ")";
    $returnMsg["curMachineID"] = $curMachineID;
    $returnMsg["fileID"] = $fileID + 1;
    $returnMsg["fileNum"] = count($oldReportXMLList);
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
    $returnMsg["curMachineID"] = $curMachineID;
}

echo json_encode($returnMsg);
return;

?>