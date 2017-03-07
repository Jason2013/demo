<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

date_default_timezone_set('PRC');

$zipFileName = "resultFiles";
$tmpResultPath = $swtTempResultPath;

$returnMsg = array();
$returnMsg["errorCode"] = 0;


$dateFolderList = glob($tmpResultPath . "/*");

if (count($dateFolderList) == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no results found";
    echo json_encode($returnMsg);
    return;
}

$resultsTree = array();
$resultsDateList = array();

$i = 0;
foreach ($dateFolderList as $tmpFolder)
{
    if (is_dir($tmpFolder))
    {
        $dateName = basename($tmpFolder);
        
        array_push($resultsTree, array());
        
        $resultFolderList = glob($tmpFolder . "/*");
        
        foreach ($resultFolderList as $tmpSubFolder)
        {
            $tmpFilePath = $tmpSubFolder . "/machine_info.json";
            if (file_exists($tmpFilePath))
            {
                $t1 = file_get_contents($tmpFilePath);
                
                $jsonObj = json_decode($t1);
                
                array_push($resultsTree[$i], $jsonObj->videoCardName);
                array_push($resultsTree[$i], $jsonObj->cpuName);
                array_push($resultsTree[$i], $jsonObj->systemName);
                
                $folderTime = fileatime($tmpSubFolder);
                $folderTimeName = date("Y-m-d H:i:s", $folderTime);
                array_push($resultsTree[$i], $folderTimeName);
                
                $t1 = "";
                $tmpFilePath2 = $tmpSubFolder . "/test_results.txt";
                
                if (file_exists($tmpFilePath2))
                {
                    $testNameList = array();
                    $handle = fopen($tmpFilePath2, "r");
                    
                    
                    // get start test name
                    while ($data = fgetcsv($handle, 0, ","))
                    {
                        $num = count($data);
                        if ($num == 0)
                        {
                            continue;
                        }
                        $testName = trim($data[0]);

                        if (strlen($testName) > 0)
                        {
                            array_push($testNameList, $testName);
                            break;
                        }
                    }
                    
                    // get end test name
                    fseek($handle, 0, SEEK_END);
                    $fileSize = ftell($handle);
                    
                    $sectionLen = 4096;
                    $filePos = $fileSize - $sectionLen;
                    $fileLastPos = $fileSize;
                    
                    while($fileLastPos > 0)
                    {
                        fseek($handle, $filePos, SEEK_SET);
                        if ($filePos > 0)
                        {
                            fgets($handle);
                            $tmpPos = ftell($handle);
                            if ($filePos == $tmpPos)
                            {
                                // if file error
                                break;
                            }
                            $filePos = $tmpPos;
                        }
                        
                        $destTestName = "";
                        
                        while ($data = fgetcsv($handle, 0, ","))
                        {
                            $num = count($data);
                            if ($num == 0)
                            {
                                continue;
                            }
                            $testName = trim($data[0]);

                            if (strlen($testName) > 0)
                            {
                                $destTestName = $testName;
                                //array_push($testNameList, $destTestName);
                            }
                            
                            $n1 = ftell($handle);
                            if ($n1 > $fileLastPos)
                            {
                                break;
                            }
                        }
                        if (strlen($destTestName) > 0)
                        {
                            array_push($testNameList, $destTestName);
                            break;
                        }
                    
                        $fileLastPos = $filePos;
                        $filePos = $filePos - $sectionLen;
                        if ($filePos < 0)
                        {
                            $filePos = 0;
                        }
                    }
                    
                    /*
                    // jump to start csv line of this call
                    while ($data = fgetcsv($handle, 0, ","))
                    {
                        $num = count($data);
                        if ($num == 0)
                        {
                            continue;
                        }
                        $testName = trim($data[0]);

                        if (strlen($testName) > 0)
                        {
                            array_push($testNameList, $testName);
                        }
                    }
                    //*/
                    fclose($handle);
                    
                    //$t1 = implode(",", $testNameList);
                    $t1 = $testNameList[0] . " to " . $testNameList[1];
                }
                array_push($resultsTree[$i], $t1);
                
                $resultFolderName = basename($tmpSubFolder);
                array_push($resultsTree[$i], $resultFolderName);
            }
        }
        $i++;
        array_push($resultsDateList, $dateName);
    }
}






$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "get info success";
$returnMsg["resultsTree"] = $resultsTree;
$returnMsg["resultsDateList"] = $resultsDateList;
$returnMsg["resultsDataNum"] = 6;
echo json_encode($returnMsg);
return;




?>