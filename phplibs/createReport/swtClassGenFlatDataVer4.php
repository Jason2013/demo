<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../userManage/swtUserManager.php";

class CGenReportFlatData
{
	//public $dbResult = null;

	public function __construct()
	{
        //global $db_dbname;

        //$this->dbResult = null;
	}
	public function __destruct()
	{
        //$this->clearResult();
    }
    
	public function getSrcResultPathName($_db, $_batchID)
	{
        global $returnMsg;
        
        $db = $_db;
        $batchID = $_batchID;
        $pathID = -1;
        $pathName = "";
        
        $userChecker = new CUserManger();
        
        if ($batchID == -1)
        {
            if ($userChecker->isManager())
            {
                // manager login
                $params1 = array();
                $sql1 = "SELECT t0.*, t1.* FROM mis_table_batch_list t0 " .
                        "LEFT JOIN mis_table_path_info t1 " .
                        "USING (path_id) " .
                        "WHERE t0.batch_state=\"1\" AND (t0.batch_group=\"1\" OR t0.batch_group=\"2\") ORDER BY t0.insert_time DESC LIMIT 1";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return false;
                }
                $row1 = $db->fetchRow();
                if ($row1 == false)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "no result availabe in database, line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return false;
                }
                $batchID = $row1[0];
                $pathID = $row1[4];
                $pathName = $row1[6];
            }
            else
            {
                // outside user login
                $userID = $userChecker->getUserID();
                $params1 = array($userID);
                
                $sql1 = "SELECT t0.batch_id, t1.path_id, t2.path_name FROM mis_table_user_batch_info t0 " .
                        "LEFT JOIN mis_table_batch_list t1 " .
                        "USING (batch_id) " .
                        "LEFT JOIN mis_table_path_info t2 " .
                        "ON (t1.path_id = t2.path_id) " .
                        "WHERE t0.user_id = ? AND t0.batch_id IN " .
                        "(SELECT t3.batch_id FROM mis_table_batch_list t3 WHERE t3.batch_state = \"1\" AND t3.batch_group = \"0\") " .
                        "ORDER BY t0.batch_id DESC LIMIT 1";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return false;
                }
                $row1 = $db->fetchRow();
                if ($row1 == false)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["userID"] = $userID;
                    $returnMsg["errorMsg"] = "no result availabe in database, line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return false;
                }
                $batchID = $row1[0];
                $pathID = $row1[1];
                $pathName = $row1[2];
            }
        }
        else
        {
            $params1 = array($batchID);
            $sql1 = "SELECT t0.*, t1.* FROM mis_table_batch_list t0 " .
                    "LEFT JOIN mis_table_path_info t1 " .
                    "USING (path_id) " .
                    "WHERE t0.batch_id=?";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                echo json_encode($returnMsg);
                return false;
            }
            $row1 = $db->fetchRow();
            if ($row1 == false)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "no result availabe in database, line: " . __LINE__;
                echo json_encode($returnMsg);
                return false;
            }
            $pathID = $row1[4];
            $pathName = $row1[6];
        }


        $returnSet = array();
        $returnSet["batchID"] = $batchID;
        $returnSet["pathName"] = $pathName;
        return $returnSet;
    }
    
	public function getReportFolder($_batchID, $_reportType, $_curReportFolder)
	{
        global $returnMsg;
        global $downloadReportDir;
        global $allReportsDir;
        
        $curReportFolder = $_curReportFolder;

        $batchID = $_batchID;

        $reportFolder = "";
        $oldReportList = array();

        if ($_reportType == 0)
        {
            // routine report
            $batchFolder = $downloadReportDir . "/batch" . $batchID;

            if (file_exists($batchFolder) == false)
            {
                mkdir($batchFolder);
            }

            if ($curReportFolder == -1)
            {
                // create a folder to save report
                $i = 1;
                while (1)
                {
                    $t1 = sprintf("%05d", $i);
                    $t2 = $batchFolder . "/" . $t1;
                    if (file_exists($t2) == false)
                    {
                        mkdir($t2);
                        $reportFolder = $t2;
                        $curReportFolder = $i;
                        break;
                    }
                    $i++;
                }
            }
            else
            {
                // already assigned a report folder
                $t1 = sprintf("%05d", $curReportFolder);
                $reportFolder = $batchFolder . "/" . $t1;
            }
        }
        else if ($_reportType == 1)
        {
            // generate all reports
            $reportFolder = $allReportsDir . "/batch" . $batchID;
            
            $oldReportList[0] = glob($reportFolder . "/*.tmp2");
            $oldReportList[1] = glob($reportFolder . "/*.tmp");
            $oldReportList[2] = glob($reportFolder . "/*.tmp1");
            $oldReportXMLList = glob($reportFolder . "/*.xml");
            $oldReportZipList = glob($reportFolder . "/*.zip");
            
            $n1 = count($oldReportList[0]) + count($oldReportList[1]) + count($oldReportList[2]);
            
            if (($n1                      == 0) &&
                (count($oldReportZipList) >  0))
            {
                // check if can skip
                $returnSet = array();
                $returnSet["parseFinished"] = 1;
                $returnSet["reportFolder"] = $reportFolder;
                $returnSet["curReportFolder"] = $curReportFolder;
                $returnSet["batchID"] = $batchID;
                $returnSet["oldReportXMLList"] = $oldReportZipList;
                $returnSet["check0818"] = 1;
                //echo json_encode($returnMsg);
                return $returnSet;
            }
            
            if (file_exists($reportFolder) == false)
            {
                mkdir($reportFolder);
            }
        }

        $returnSet = array();
        $returnSet["parseFinished"] = 0;
        $returnSet["reportFolder"] = $reportFolder;
        $returnSet["curReportFolder"] = $curReportFolder;
        return $returnSet;
    }
    
	public function getConnectValues($_reportFolder)
	{
        global $returnMsg;
        global $swtTempReportConfigJsonName;

        $tmpFileName = $_reportFolder . "/" . $swtTempReportConfigJsonName;
        
        $returnSet = array();
        if (file_exists($tmpFileName) == false)
        {
            $returnSet["allFileList"] = array();
            $returnSet["allFileSizeList"] = array();
            $returnSet["reportUmdNameList"] = array();
            $returnSet["allFileReportUmdNameList"] = array();
            //$returnSet["allFileTestPosList"] = array();
            //$returnSet["allFileTestCaseNumList"] = array();
            //$returnSet["allFileSubTestNameFilterNumMaxList"] = array();
            //$returnSet["allFileFolderTestNameList"] = array();
            //$returnSet["allFileReportUmdNameList"] = array();
            //$returnSet["allFileTestCaseUmdDataMaskList"] = array();

            $returnSet["cardNameList"] = array();
            $returnSet["machineIDList"] = array();
            $returnSet["allFolderList"] = array();
            $returnSet["cardSysNameMachineIDDict"] = array();
            $returnSet["fileID"] =    0;
            $returnSet["columnNum"] = 0;
            $returnSet["rowNum"] =    0;
            
            $t1 = json_encode($returnSet);
            file_put_contents($tmpFileName, $t1);
        }
        else
        {
            $t1 = file_get_contents($tmpFileName);
            $tmpJson = json_decode($t1);
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $returnSet["" . $tmpKey] = $tmpVal;
            }
            
            $tmpJson = $returnSet["cardSysNameMachineIDDict"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpArray["" . $tmpKey] = $tmpVal;
            }
            $returnSet["cardSysNameMachineIDDict"] = $tmpArray;
            
            $tmpJson = $returnSet["allFileReportUmdNameList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpVal)
            {
                array_push($tmpArray, $tmpVal);
            }
            $returnSet["allFileReportUmdNameList"] = $tmpArray;
            
            /*
            $tmpJson = $returnSet["allFileTestPosList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpArray["" . $tmpKey] = $tmpVal;
            }
            $returnSet["allFileTestPosList"] = $tmpArray;
            
            $tmpJson = $returnSet["allFileTestCaseNumList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpArray["" . $tmpKey] = $tmpVal;
            }
            $returnSet["allFileTestCaseNumList"] = $tmpArray;
            
            $tmpJson = $returnSet["allFileSubTestNameFilterNumMaxList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpArray["" . $tmpKey] = $tmpVal;
            }
            $returnSet["allFileSubTestNameFilterNumMaxList"] = $tmpArray;
            
            $tmpJson = $returnSet["allFileFolderTestNameList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpArray["" . $tmpKey] = $tmpVal;
            }
            $returnSet["allFileFolderTestNameList"] = $tmpArray;
            
            $tmpJson = $returnSet["allFileReportUmdNameList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpArray["" . $tmpKey] = $tmpVal;
            }
            $returnSet["allFileReportUmdNameList"] = $tmpArray;
            
            $tmpJson = $returnSet["allFileTestCaseUmdDataMaskList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpArray["" . $tmpKey] = $tmpVal;
            }
            $returnSet["allFileTestCaseUmdDataMaskList"] = $tmpArray;
            
            //*/
        }

        return $returnSet;
    }
    
	public function setConnectValues($_valueSet, $_reportFolder)
	{
        global $returnMsg;
        global $swtTempReportConfigJsonName;

        $tmpFileName = $_reportFolder . "/" . $swtTempReportConfigJsonName;
        

        $t1 = json_encode($_valueSet);
        file_put_contents($tmpFileName, $t1);

        return;
    }

	public function delConnectJson($_reportFolder)
	{
        global $returnMsg;
        global $swtTempReportConfigJsonName;

        $tmpFileName = $_reportFolder . "/" . $swtTempReportConfigJsonName;
        
        if (file_exists($tmpFileName))
        {
            //unlink($tmpFileName);
        }

        return;
    }
    
	public function getMachineIDInfo($_db, $_machineIDPair)
	{
        global $returnMsg;
        global $swtTempReportConfigJsonName;
        
        $db = $_db;

        $machineIDCardNameDict = array();
        $machineIDSysNameDict = array();
        $machineIDCardNameSysNameDict = array();

        $machineIDPairStr = implode(",", $_machineIDPair);

        if (count($_machineIDPair) >= 2)
        {
            // if has compare card
            // get machineID pair card name
            $params1 = array();
            $sql1 = "SELECT t0.machine_id, t0.card_id, t0.sys_id, t1.env_name, t2.env_name FROM mis_table_machine_info t0 " .
                    "LEFT JOIN mis_table_environment_info t1 " .
                    "ON (t0.card_id = t1.env_id) " .
                    "LEFT JOIN mis_table_environment_info t2 " .
                    "ON (t0.sys_id = t2.env_id) " .
                    "WHERE t0.machine_id IN (" . $machineIDPairStr . ")";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                echo json_encode($returnMsg);
                return;
            }

            while ($row1 = $db->fetchRow())
            {
                $machineIDCardNameDict["" . $row1[0]] = "" . $row1[3];
                $machineIDSysNameDict["" . $row1[0]] = "" . $row1[4];
                $machineIDCardNameSysNameDict["" . $row1[0]] = "" . $row1[3] . "_" . $row1[4];
            }
        }

        $returnSet = array();
        $returnSet["machineIDCardNameDict"] = $machineIDCardNameDict;
        $returnSet["machineIDSysNameDict"] = $machineIDSysNameDict;
        $returnSet["machineIDCardNameSysNameDict"] = $machineIDCardNameSysNameDict;
        return $returnSet;
    }
    
    public function checkFiles($_parentFolder, $_cardName, $_curMachineID, $_level, $_folderName)
    {
        global $returnMsg;
        global $allFileList;
        global $allFileSizeList;
        global $allFolderList;
        global $cardNameList;
        global $machineIDList;
        global $resultFileName1;
        global $resultFileName2;
        global $machineInfoFileName;
        global $resultFileName1;
        global $resultFileName2;
        global $resultFileName3;
        global $reportFolder;
        global $machineIDCardNameDict;
        global $machineIDSysNameDict;
        global $cardSysNameMachineIDDict;
        global $cardSysNameMachineIDDictNew;
        global $machineIDPair;
        global $crossType;
        
        $fileList = glob($_parentFolder . "\\*");
        $folderList = array();
        foreach ($fileList as $tmpName)
        {
            if (is_dir($tmpName) == true)
            {
                // is folder name
                array_push($folderList, $tmpName);
                // array_push($allFolderList, $tmpName);
            }
            else
            {
                // is file name
                $t1 = basename($tmpName);
                if ((strcmp($t1, $resultFileName1) == 0) ||
                    (strcmp($t1, $resultFileName2) == 0))
                {
                    array_push($allFileList, $tmpName);
                    array_push($allFileSizeList, filesize($tmpName));
                    array_push($cardNameList, $_cardName);
                    array_push($machineIDList, $_curMachineID);
                    array_push($allFolderList, basename($_folderName));
                    
                    $tmpSrcFolder = substr($tmpName, 0, strlen($tmpName) - strlen($t1));
                    $tmpSrcPath = $tmpSrcFolder . $resultFileName3;
                    $tmpDestFolder = $reportFolder . "/" . $_cardName;
                    $tmpDestPath = $tmpDestFolder . "/" . $resultFileName3;
                    if (file_exists($tmpSrcPath))
                    {
                        // copy runlog.txt to report folder
                        // will be attached to report later
                        if (is_dir($tmpDestFolder) == false)
                        {
                            //$returnMsg["tmp---006:"] .= $tmpDestFolder . ",";
                            mkdir($tmpDestFolder);
                        }
                        copy($tmpSrcPath, $tmpDestPath);
                    }
                    else
                    {
                        $returnMsg["errorCode"] = 0;
                        $returnMsg["errorMsg"] = "file: " . $resultFileName3 . " missing, line: " . __LINE__;
                        echo json_encode($returnMsg);
                        return false;
                    }
                }
            }
        }
        foreach ($folderList as $tmpName)
        {
            $t1 = $tmpName . "\\..\\" . $machineInfoFileName;
            $t2 = $tmpName . "\\" . $resultFileName3;
            $cardName = "";
            $curMachineID = -1;
            
            $returnMsg["tmp---004:"] .= $crossType . ",";
            $returnMsg["tmp---005:"] .= $t2 . ",";
            
            
            if ((file_exists($t1) == true) &&
                ($crossType       <  10))
            {
                // use json
                $t2 = file_get_contents($t1);
                $obj = json_decode($t2);
                $cardName = $obj->videoCardName . "_" . $obj->systemName;
                
                if (count($machineIDPair) >= 2)
                {
                    // if has compare card
                    $cardNameKeys = array_keys($machineIDCardNameDict, $obj->videoCardName);
                    $sysNameKeys = array_keys($machineIDSysNameDict, $obj->systemName);
                    
                    // get machineID of certain card & system
                    $tmpKeys = array_intersect($cardNameKeys, $sysNameKeys);
                    $commonKeys = array();
                    foreach ($tmpKeys as $tmpKey)
                    {
                        array_push($commonKeys, $tmpKey);
                    }
                    
                    $cardSysNameMachineIDDict[$cardName] = count($commonKeys) > 0 ? $commonKeys[0] : -1;
                }
            }
            else if ((file_exists($t2) == true) &&
                     ($crossType       >= 10))
            {
                // donot use json
                $machineFolderPath = $tmpName . "\\";
                $clientCmdParser = new CClientHeartBeat;
                $tmpMachineName = basename($_level == 0 ? $tmpName : $_folderName);
                $obj = $clientCmdParser->getMachineInfoWithoutJson($machineFolderPath, $tmpMachineName);
                
                $cardName = $obj["videoCardName"] . "_" . $obj["systemName"];
                
                $returnMsg["tmp---003:"] .= $cardName . "," . $machineFolderPath . ",";
                
                $obj2 = $clientCmdParser->updateMachineInfo3($obj["machineInfo"], $tmpMachineName);
                $curMachineID = $obj2["machineID"];
                
                
                if (count($machineIDPair) >= 2)
                {
                    // if has compare card
                    $cardNameKeys = array_keys($machineIDCardNameDict, $obj["videoCardName"]);
                    $sysNameKeys = array_keys($machineIDSysNameDict, $obj["systemName"]);
                    
                    // get machineID of certain card & system
                    $tmpKeys = array_intersect($cardNameKeys, $sysNameKeys);
                    $commonKeys = array();
                    foreach ($tmpKeys as $tmpKey)
                    {
                        array_push($commonKeys, $tmpKey);
                    }
                    
                    $cardSysNameMachineIDDict[$cardName] = count($commonKeys) > 0 ? $commonKeys[0] : -1;
                }
            }
            $tmpFolderName = $_level == 0 ? $tmpName : $_folderName;
            $this->checkFiles($tmpName, $cardName, $curMachineID, $_level + 1, $tmpFolderName);
        }
        //print_r($folderList);
        $cardSysNameMachineIDDictNew = $cardSysNameMachineIDDict;
        return true;
    }
    
	public function getAllFileList($_batchPathName)
	{
        global $returnMsg;
        global $allFileList;
        global $allFolderList;
        global $cardNameList;

        $tmpResult = $this->checkFiles($_batchPathName, "", -1, 0, "");

        return $tmpResult;
    }
    
	public function testWriteSheetHead($_batchID,
                                       $_fileID,
                                       $_uniqueCardNameList,
                                       $_reportFolder,
                                       $_outFileNameLater)
	{
        global $returnMsg;
        global $templateFileName0;
        global $templateFileName3;
        global $templateFileName1;
        global $allStylesEndTag;
        global $columnNum;
        global $rowNum;
        global $xmlWriter;

        if ($_fileID == 0)
        {
            // add sheet head to tmp file
            //foreach ($_uniqueCardNameList as $tmpName)
            //{
            //    $tmpFileName = sprintf($_reportFolder . "/" . $tmpName . $_outFileNameLater, $_batchID);
            //    $fileHandle = fopen($tmpFileName, "w");
            //    
            //    // report head
            //    $t1 = file_get_contents($templateFileName0);
            //    fwrite($fileHandle, $t1);
            //    // style end tag
            //    $xmlWriter->writeAdditionalStyles($fileHandle);
            //    //$t1 = file_get_contents($templateFileName3);
            //    ////$t1 = sprintf($t1, 0, 0);
            //    //fwrite($fileHandle, $t1);
            //    
            //    fclose($fileHandle);
            //}
            $columnNum = 0;
            $rowNum = 0;
        }
        return;
    }
    
	public function testWriteSheetEnd($_batchID,
                                      $_fileID,
                                      $_uniqueCardNameList,
                                      $_reportFolder,
                                      $_outFileNameLater,
                                      $_curReportFolder)
	{
        global $returnMsg;
        global $templateFileName2;
        global $allSheetsEndTag;
        global $columnNum;
        global $rowNum;

        if ($_fileID >= count($_uniqueCardNameList))
        {
            // add sheet end
            //foreach ($_uniqueCardNameList as $tmpName)
            //{
            //    $tmpFileName = sprintf($_reportFolder . "/" . $tmpName . $_outFileNameLater, $_batchID);
            //    $fileHandle = fopen($tmpFileName, "r+");
            //    fseek($fileHandle, 0, SEEK_END);
            //    //$t1 = file_get_contents($templateFileName2);
            //    fwrite($fileHandle, $allSheetsEndTag);
            //    
            //    fclose($fileHandle);
            //}
            
            $returnMsg["parseFinished"] = 1;
            $returnMsg["curReportFolder"] = $_curReportFolder;
            
            $this->delConnectJson($_reportFolder);
            
            echo json_encode($returnMsg);
            return false;
        }
        return true;
    }
    
	public function getDoubleMachineID($_tmpCardName,
                                       $_cardSysNameMachineIDDict,
                                       $_machineIDPair)
	{
        global $returnMsg;
        //global $machineIDPair
        $curMachineID = -1;
        $curPairMachineID = -1;

        if (array_key_exists($_tmpCardName, $_cardSysNameMachineIDDict))
        {
            $curMachineID = intval($_cardSysNameMachineIDDict[$_tmpCardName]);
            
            $n1 = intval(count($_machineIDPair) * 0.5);
            for ($i = 0; $i < $n1; $i++)
            {
                if ($_machineIDPair[$i * 2] == $curMachineID)
                {
                    // get compare card id if has
                    $curPairMachineID = $_machineIDPair[$i * 2 + 1];
                    break;
                }
            }
        }
        

        $returnSet = array();
        $returnSet["curMachineID"] = $curMachineID;
        $returnSet["curPairMachineID"] = $curPairMachineID;
        return $returnSet;
    }
    
    public function getTestCaseUmdDataMask($_tmpUmdTestCaseNumList)
    {
        global $swtUmdNameList;
        
        if (count($_tmpUmdTestCaseNumList) > 0)
        {
            // set last test umd data mask
            $tmpAllMask = 0;
            for ($j = 0; $j < count($swtUmdNameList); $j++)
            {
                $tmpUmdName = $swtUmdNameList[$j];
                if ((array_key_exists($tmpUmdName, $_tmpUmdTestCaseNumList)) &&
                    ($_tmpUmdTestCaseNumList[$tmpUmdName] > 0))
                {
                    $tmpMask = 1;
                    for ($l = 0; $l < $j; $l++)
                    {
                        $tmpMask *= 10;
                    }
                    $tmpAllMask |= $tmpMask;
                }
            }
            return $tmpAllMask;
        }
        return -1;
    }
    
    public function getTestStartPos($_curCardNameList)
    {
        global $allFileList;
        global $visitedTestNameList;
        global $tmpCardName;
        global $swtUmdNameList;
        
        $testStartPosList = array();
        $testCaseNumList = array();
        $folderTestNameList = array();
        $subTestNameFilterNumMaxList = array();
        $reportUmdNameList = array();
        $testCaseUmdDataMaskList = array();
        // find all start pos in file for each test
        for ($i = 0; $i < count($_curCardNameList); $i++)
        {
            array_push($testStartPosList, array());
            array_push($testCaseNumList, array());
            array_push($folderTestNameList, array());
            array_push($subTestNameFilterNumMaxList, array());
            //array_push($reportUmdNameList, array());
            array_push($testCaseUmdDataMaskList, array());
            
            $curTmpFileName = $allFileList[$_curCardNameList[$i]];
            $resultFileHandle = fopen($curTmpFileName, "r");

            $tmpPos = ftell($resultFileHandle);
            $tmpTestCaseNum = 0;
            $lastTestName = "";
            $tmpTestName = "";
            $tmpAPIPos = 0;
            $lastAPIName = "";
            $subTestNameFilterNumMax = 0;
            $tmpTestIndex = -1;
            $dataKeyDataColumnID = 0;
            $tmpUmdTestCaseNumList = array();
            while($dataSet = fgetcsv($resultFileHandle, 0, ","))
            {
                $dataSetSize = count($dataSet);
                
                if ($dataSetSize > 0)
                {
                    $tmpDataSet = $dataSet;
                    $dataSet = array();
                    foreach ($tmpDataSet as $tmpData)
                    {
                        array_push($dataSet, trim($tmpData));
                    }
                    $curTestName = $dataSet[0];
                    if (strlen($curTestName) > 0)
                    {
                        // test title line
                        $tmpTestIndex++;
                        $subTestNameFilterNum = 0;
                        for ($j = 1; $j < count($dataSet); $j++)
                        {
                            $tmpIndex = strpos($dataSet[$j], "/");
                            if ($tmpIndex !== false)
                            {
                                // data column id
                                $dataKeyDataColumnID = $j;
                                $subTestNameFilterNum = $dataKeyDataColumnID - 1;
                                break;
                            }
                            else if ($dataSet[$j] == "FPS") // randomsphere
                            {
                                // data column id
                                $dataKeyDataColumnID = $j;
                                $subTestNameFilterNum = $dataKeyDataColumnID - 1;
                                break;
                            }
                        }
                        $subTestNameFilterNumMax = $subTestNameFilterNumMax < $subTestNameFilterNum ? 
                                                   $subTestNameFilterNum : $subTestNameFilterNumMax;
                        
                        $testStartPosList[$i][$curTestName] = $tmpPos;
                        $subTestNameFilterNumMaxList[$i] = $subTestNameFilterNumMax;
                        $lastTestName = $tmpTestName;
                        $tmpTestName = $curTestName;
                        
                        $tmpAPIPos = array_search("API", $dataSet);
                        
                        // all tests before the last test
                        if (strlen($lastTestName) > 0)
                        {
                            $testCaseNumList[$i][$lastTestName] = $tmpTestCaseNum;
                            //$testCaseNumList[$i][$lastTestName] = $lastAPIName . "_" . $tmpAPIPos;
                        }
                        $lastAPIName = "";
                        $tmpTestCaseNum = 0;
                        
                        $tmpPos = array_search($curTestName, $folderTestNameList[$i]);
                        if ($tmpPos === false)
                        {
                            array_push($folderTestNameList[$i], $curTestName);
                            array_push($testCaseUmdDataMaskList[$i], 0);
                        }
                        
                        $tmpMask = $this->getTestCaseUmdDataMask($tmpUmdTestCaseNumList);
                        if ($tmpMask != -1)
                        {
                            $tmpPos = array_search($lastTestName, $folderTestNameList[$i]);
                            if ($tmpPos !== false)
                            {
                                $testCaseUmdDataMaskList[$i][$tmpPos] = $tmpMask;
                            }
                        }
                        $tmpUmdTestCaseNumList = array();
                    }
                    else if ($tmpAPIPos < $dataSetSize)
                    {
                        // test data line
                        $tmpUmdName = $dataSet[$tmpAPIPos];
                        if (strlen($lastAPIName) == 0)
                        {
                            $lastAPIName = $tmpUmdName;
                            $tmpTestCaseNum++;
                        }
                        else if (strcmp($lastAPIName, $tmpUmdName) == 0)
                        {
                            $tmpTestCaseNum++;
                        }
                        if (is_numeric($dataSet[$dataKeyDataColumnID]))
                        {
                            // skip N/A
                            if (array_key_exists($tmpUmdName, $tmpUmdTestCaseNumList))
                            {
                                // test case num per API
                                $tmpUmdTestCaseNumList[$tmpUmdName]++;
                            }
                            else
                            {
                                $tmpUmdTestCaseNumList[$tmpUmdName] = 1;
                            }
                            if ($tmpTestIndex == 0)
                            {
                                // only first test
                                if (array_search($tmpUmdName, $reportUmdNameList[$i]) === false)
                                {
                                    // save used API names
                                    array_push($reportUmdNameList[$i], $tmpUmdName);
                                }
                            }
                        }
                    }
                    if (array_search($curTestName, $visitedTestNameList) === false)
                    {
                        // save start pos in file for each test
                        array_push($visitedTestNameList, $curTestName);
                    }
                }
                $tmpPos = ftell($resultFileHandle);
            }
            // the last test
            if (strlen($tmpTestName) > 0)
            {
                $testCaseNumList[$i][$tmpTestName] = $tmpTestCaseNum;
                
                $tmpMask = $this->getTestCaseUmdDataMask($tmpUmdTestCaseNumList);
                if ($tmpMask != -1)
                {
                    $tmpPos = array_search($tmpTestName, $folderTestNameList[$i]);
                    if ($tmpPos !== false)
                    {
                        $testCaseUmdDataMaskList[$i][$tmpPos] = $tmpMask;
                    }
                }
            }
            
            fclose($resultFileHandle);
        }
        
        $returnSet = array();
        $returnSet["testStartPosList"] = $testStartPosList;
        $returnSet["testCaseNumList"] = $testCaseNumList;
        $returnSet["folderTestNameList"] = $folderTestNameList;
        $returnSet["subTestNameFilterNumMaxList"] = $subTestNameFilterNumMaxList;
        $returnSet["reportUmdNameList"] = $reportUmdNameList;
        $returnSet["testCaseUmdDataMaskList"] = $testCaseUmdDataMaskList;

        return $returnSet;
    }
    
    public function getReportUmdNameList($_curCardNameList)
    {
        global $allFileList;
        
        $reportUmdNameList = array();
        $tmpFinished = false;
        // find all start pos in file for each test
        for ($i = 0; $i < count($_curCardNameList); $i++)
        {
            array_push($reportUmdNameList, array());
            $curTmpFileName = $allFileList[$_curCardNameList[$i]];
            $resultFileHandle = fopen($curTmpFileName, "r");

            $tmpAPIPos = 0;
            $tmpTestIndex = -1;
            $dataKeyDataColumnID = 0;
            $curTestName = "";
            while($dataSet = fgetcsv($resultFileHandle, 0, ","))
            {
                $dataSetSize = count($dataSet);
                
                if ($dataSetSize > 0)
                {
                    $tmpDataSet = $dataSet;
                    $dataSet = array();
                    foreach ($tmpDataSet as $tmpData)
                    {
                        array_push($dataSet, trim($tmpData));
                    }
                    $curTestName = $dataSet[0];
                    if (strlen($curTestName) > 0)
                    {
                        // test title line
                        $tmpTestIndex++;
                        if ($tmpTestIndex > 0)
                        {
                            $tmpFinished = true;
                            break;
                        }
                        
                        for ($j = 1; $j < count($dataSet); $j++)
                        {
                            $tmpIndex = strpos($dataSet[$j], "/");
                            if ($tmpIndex !== false)
                            {
                                // data column id
                                $dataKeyDataColumnID = $j;
                                break;
                            }
                            else if ($dataSet[$j] == "FPS") // randomsphere
                            {
                                // data column id
                                $dataKeyDataColumnID = $j;
                                break;
                            }
                        }
                        
                        $tmpAPIPos = array_search("API", $dataSet);
                    }
                    else if ($tmpAPIPos < $dataSetSize)
                    {
                        // test data line
                        $tmpUmdName = $dataSet[$tmpAPIPos];

                        if (is_numeric($dataSet[$dataKeyDataColumnID]))
                        {
                            if ($tmpTestIndex == 0)
                            {
                                // only first test
                                if (array_search($tmpUmdName, $reportUmdNameList[$i]) === false)
                                {
                                    // save used API names
                                    array_push($reportUmdNameList[$i], $tmpUmdName);
                                }
                            }
                        }
                    }
                    else if (strlen($curTestName) > 0)
                    {
                        $tmpFinished = true;
                        break;
                    }
                }
                else if (strlen($curTestName) > 0)
                {
                    $tmpFinished = true;
                    break;
                }
            }
            
            fclose($resultFileHandle);
        }
        
        $returnSet = array();
        $returnSet["reportUmdNameList"] = $reportUmdNameList;

        return $returnSet;
    }
    
}


?>