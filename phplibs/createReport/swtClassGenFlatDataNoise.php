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
                        "WHERE t0.batch_state=\"1\" AND " .
                        "(t0.batch_group=\"1\" OR t0.batch_group=\"2\" OR t0.batch_group=\"4\") " .
                        "ORDER BY t0.insert_time DESC LIMIT 1";
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
            $returnSet["cardNameList"] = array();
            $returnSet["machineIDList"] = array();
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
            unlink($tmpFileName);
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
        global $allRunLogFileList;
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
                    array_push($cardNameList, $_cardName);
                    array_push($machineIDList, $_curMachineID);
                    
                    $tmpSrcFolder = substr($tmpName, 0, strlen($tmpName) - strlen($t1));
                    $tmpSrcPath = $tmpSrcFolder . $resultFileName3;
                    $tmpDestFolder = $reportFolder . "/" . $_cardName;
                    $tmpDestPath = $tmpDestFolder . "/" . $resultFileName3;
                    if (file_exists($tmpSrcPath))
                    {
                        $allRunLogFileList []= $tmpSrcPath;
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
            $t3 = $tmpName . "\\..\\..\\" . $machineInfoFileName;
            $t2 = $tmpName . "\\" . $resultFileName3;
            $cardName = "";
            $curMachineID = -1;
            
            $returnMsg["tmp---004:"] .= $crossType . ",";
            $returnMsg["tmp---005:"] .= $t2 . ",";
            
            if ((file_exists($t1) == false) && 
                (file_exists($t3) == true))
            {
                $t1 = $t3;
            }
            
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
                $obj = $clientCmdParser->getMachineInfoWithoutJson($machineFolderPath);
                
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
        global $allRunLogFileList;
        global $allFolderList;
        global $cardNameList;
        global $swtTempReportConfigJsonName2;
        global $reportFolder;

        $tmpResult = $this->checkFiles($_batchPathName, "", -1, 0, "");
        
        // save runlog.txt paths for reading tests run time
        // on generating reports
        $tmpObj = array();
        $tmpObj["allRunLogFileList"] = $allRunLogFileList;
        $tmpObj["cardNameList"] = $cardNameList;
        
        $t1 = json_encode($tmpObj);
        file_put_contents($reportFolder . "/" . $swtTempReportConfigJsonName2,
                          $t1);

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
            foreach ($_uniqueCardNameList as $tmpName)
            {
                $tmpFileName = sprintf($_reportFolder . "/" . $tmpName . $_outFileNameLater, $_batchID);
                $fileHandle = fopen($tmpFileName, "w");
                
                // report head
                $t1 = file_get_contents($templateFileName0);
                fwrite($fileHandle, $t1);
                // style end tag
                $xmlWriter->writeAdditionalStyles($fileHandle);
                //$t1 = file_get_contents($templateFileName3);
                ////$t1 = sprintf($t1, 0, 0);
                //fwrite($fileHandle, $t1);
                
                fclose($fileHandle);
            }
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
            foreach ($_uniqueCardNameList as $tmpName)
            {
                $tmpFileName = sprintf($_reportFolder . "/" . $tmpName . $_outFileNameLater, $_batchID);
                $fileHandle = fopen($tmpFileName, "r+");
                fseek($fileHandle, 0, SEEK_END);
                //$t1 = file_get_contents($templateFileName2);
                fwrite($fileHandle, $allSheetsEndTag);
                
                fclose($fileHandle);
            }
            
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
    
    public function getTestStartPos($_curCardNameList)
    {
        global $allFileList;
        global $visitedTestNameList;
        global $tmpCardName;
        
        $testStartPosList = array();
        // find all start pos in file for each test
        for ($i = 0; $i < count($_curCardNameList); $i++)
        {
            array_push($testStartPosList, array());
            
            $curTmpFileName = $allFileList[$_curCardNameList[$i]];
            $resultFileHandle = fopen($curTmpFileName, "r");

            $tmpPos = ftell($resultFileHandle);
            while($dataSet = fgetcsv($resultFileHandle, 0, ","))
            {
                $dataSetSize = count($dataSet);
                
                if ($dataSetSize > 0)
                {
                    $tmpTestName = trim($dataSet[0]);
                    
                    $isValid = true;
                    $tmpArr = array();
                    $tmpArr[$tmpTestName] = $tmpPos;
                    $t1 = json_encode($tmpArr);
                    if (strlen(trim($t1)) == 0)
                    {
                        $isValid = false;
                    }
                    
                    if ((strlen($tmpTestName) > 0) && $isValid)
                    {
                        $testStartPosList[$i][$tmpTestName] = $tmpPos;
                    }
                    if (array_search($tmpTestName, $visitedTestNameList) === false)
                    {
                        // save start pos in file for each test
                        array_push($visitedTestNameList, $tmpTestName);
                    }
                }
                $tmpPos = ftell($resultFileHandle);
            }
            
            fclose($resultFileHandle);
        }
        
        return $testStartPosList;
    }
    
    public function addLinesToFlatData($_srcFileHandle,
                                       $_destFileHandle,
                                       $_tmpCardName,
                                       $_tmpTestName,
                                       $_isComp,
                                       $_apiAddText)
    {
        global $columnNum;
        global $rowNum;
        global $returnMsg;
        global $tmpCardName;
        global $startStyleID;
        global $testCaseIDColumnName;
        
        $tmpList = explode("_", $_tmpCardName);
        //$tmpListCmp = explode("_", $_tmpCmpCardName);
        $curCardName = $tmpList[0];
        $curSysName = $tmpList[1];
        
        $curTestName = "";
        $testCaseIDPos = -1;
        $testColumnNum = 0;
        
        $mainContent = "";
        while($dataSet = fgetcsv($_srcFileHandle, 0, ","))
        {
            $tmpDataSet = $dataSet;
            $dataSet = array();
            foreach ($tmpDataSet as $tmpVal)
            {
                array_push($dataSet, trim($tmpVal));
            }
            $isTitleLine = false;
            
            $dataSetSize = count($dataSet);
            if ($dataSetSize > 0)
            {
                $tmpSrcTestName = trim($dataSet[0]);
                
                if (strlen($tmpSrcTestName) > 0)
                {
                    // start of a test
                    $curTestName = $tmpSrcTestName;
                    $testCaseIDPos = array_search($testCaseIDColumnName, $dataSet);
                    $isTitleLine = true;
                    
                    $testColumnNum = $dataSetSize;
                    
                    if ($_isComp == false)
                    {
                        array_push($dataSet, "ASIC | Driver");
                        array_push($dataSet, "OS");
                    }
                    if (strcmp($_tmpTestName, $tmpSrcTestName) != 0)
                    {
                        // test lines end
                        //$returnMsg["tmpStr1"] .= "x";
                        if ($_isComp == true)
                        {
                            // add an empty line at end of a test
                            $t1 = "<Row ss:StyleID=\"Default\">\n";
                            $t1 .= "<Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\"></Data></Cell>\n";
                            $t1 .= "</Row>\n";
                            //fwrite($_destFileHandle, $t1);
                            $mainContent .= $t1;
                            $rowNum++;
                        }
                        break;
                    }
                    if ($_isComp == true)
                    {
                        // skip comp card test title line
                        continue;
                    }
                }
                else
                {
                    $tmpList1 = explode(" ", $curSysName);
                    array_push($dataSet, $curCardName);
                    array_push($dataSet, $tmpList1[0]);
                }
            }
            if ($dataSetSize < 3)
            {
                // skip empty line
                continue;
            }
            if ($dataSetSize < $testColumnNum)
            {
                // invalid line
                continue;
            }
            
            $dataSetSize = count($dataSet);
            $columnNum = $columnNum < $dataSetSize ? $dataSetSize : $columnNum;
            
            // add lines to dest tmp file
            $t1 = "<Row ss:StyleID=\"Default\">\n";
            $i = 0;
            $isDX12 = false;
            $tmpVal = 0;
            foreach ($dataSet as $tmpData)
            {
                if ($i == 14)
                {
                    $t2 = trim($tmpData);
                    if ($t2 == "DX12")
                    {
                        $isDX12 = true;
                    }
                }
                $i++;
            }
            $i = 0;
            $j = -1;
            foreach ($dataSet as $tmpData)
            {
                $j++;
                $t2 = trim($tmpData);
                if (($i != 0) && (strlen($t2) == 0))
                {
                    continue;
                }
                
                if ($j == $testCaseIDPos)
                {
                    continue;
                }
                
                // skip testname colummn, cause already in testname sheet
                if ($j == 0)
                {
                    continue;
                }
                
                $t3 = "";
                
                if (($i             == 1) &&
                    ($testCaseIDPos != -1))
                {
                    // testCaseID column
                    if ($isTitleLine == true)
                    {
                        $t3 = "<Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" . $testCaseIDColumnName . "</Data></Cell>\n";
                    }
                    else
                    {
                        $t3 = "<Cell ss:StyleID=\"Default\"><Data ss:Type=\"Number\">" . $dataSet[$testCaseIDPos] . "</Data></Cell>\n";
                    }
                }
                
                if (is_numeric($t2))
                {
                    $t3 .= "<Cell ss:StyleID=\"Default\"><Data ss:Type=\"Number\">" . $t2 . "</Data></Cell>\n";
                }
                else
                {
                    $t3 .= "<Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" . $t2 . "</Data></Cell>\n";
                }
                
                //if (($curCardName == "Fiji XT") &&
                //    ($isDX12      == true) &&
                //    ($i == 9))
                //{
                //    //$t3 = "<Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" . (floatval($t2) / 1.000) . "</Data></Cell>\n";
                //}
                //else
                //{
                //    $t3 = "<Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" . $t2 . "</Data></Cell>\n";
                //}
                
                if ((strlen(trim($dataSet[0])) == 0) &&
                    (strlen($_apiAddText) > 0) &&
                    ($i == 16))
                {
                    //$t3 = "<Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" . $_apiAddText . $t2 . "</Data></Cell>\n";
                }
                
                $t1 .= $t3;
                
                $i++;
            }
            $t1 .= "</Row>\n";
            //fwrite($_destFileHandle, $t1);
            $mainContent .= $t1;
            $rowNum++;
        }
        
        return $mainContent;
    }
    
	public function dumpLines($_visitedTestNameList,
                              $_curCardNameList,
                              $_pairCardNameList,
                              $_testStartPosList,
                              $_pairTestStartPosList,
                              $_fileHandle,
                              $_resultFileHandleList,
                              $_pairResultFileHandleList,
                              $_tmpCardName,
                              $_curPairMachineID,
                              $_machineIDCardNameSysNameDict)
	{
        global $returnMsg;
        global $templateFileName3;
        global $templateFileName4;
        global $curCardNameList;
        global $pairCardNameList;
        global $allFileList;

        // add rows to tmp file
        foreach ($_visitedTestNameList as $tmpTestName)
        {
            if (strlen($tmpTestName) == 0)
            {
                continue;
            }
            //$isValid = true;
            //$tmpArr = array();
            //$tmpArr[$tmpTestName] = 1;
            //$t1 = json_encode($tmpArr);
            //if (strlen(trim($t1)) == 0)
            //{
            //    $isValid = false;
            //}
            //if ($isValid == false)
            //{
            //    continue;
            //}
            
            $hasContent = false;
            $mainContent = "";
            
            //// add new sheet
            //$t1 = file_get_contents($templateFileName3);
            //$t1 = sprintf($t1, $tmpTestName);
            //fwrite($_fileHandle, $t1);
            
            for ($i = 0; $i < count($_curCardNameList); $i++)
            {
                if (array_key_exists($tmpTestName, $_testStartPosList[$i]))
                {
                    
                    $tmpPos = $_testStartPosList[$i][$tmpTestName];
                    
                    $curTmpFileName = $allFileList[$curCardNameList[$i]];
                    $resultFileHandle = fopen($curTmpFileName, "r"); 
                    
                    //fseek($_resultFileHandleList[$i], $tmpPos, SEEK_SET);
                    fseek($resultFileHandle, $tmpPos, SEEK_SET);

                    if ($i == 0)
                    {
                        //$this->addLinesToFlatData($_resultFileHandleList[$i],
                        //                          $_fileHandle,
                        //                          $_tmpCardName,
                        //                          $tmpTestName,
                        //                          false,
                        //                          ""); // PBBOff-
                                                  
                        $t1 = $this->addLinesToFlatData($resultFileHandle,
                                                  $_fileHandle,
                                                  $_tmpCardName,
                                                  $tmpTestName,
                                                  false,
                                                  ""); // PBBOff-
                                                  
                        $mainContent .= $t1;
                    }
                    else
                    {
                        //$this->addLinesToFlatData($_resultFileHandleList[$i],
                        //                          $_fileHandle,
                        //                          $_tmpCardName,
                        //                          $tmpTestName,
                        //                          true,
                        //                          ""); // PBBOn-
                                                  
                        $t1 = $this->addLinesToFlatData($resultFileHandle,
                                                  $_fileHandle,
                                                  $_tmpCardName,
                                                  $tmpTestName,
                                                  true,
                                                  ""); // PBBOn-
                        $mainContent .= $t1;
                    }
                    fclose($resultFileHandle);
                }
            }
            
            if ($_curPairMachineID != -1)
            {
                // if has compare card
                $returnMsg["_pairCardNameList"] = $_pairCardNameList;
                $returnMsg["pairTestStartPosList"] = $_pairTestStartPosList;
                $returnMsg["tmpTestName"] = $tmpTestName;
                for ($i = 0; $i < count($_pairCardNameList); $i++)
                {
                    if (array_key_exists($tmpTestName, $_pairTestStartPosList[$i]))
                    {
                        $returnMsg["CardNameSysName"] = $_machineIDCardNameSysNameDict[$_curPairMachineID];
                        $returnMsg["machineIDCardNameSysNameDict"] = $_machineIDCardNameSysNameDict;
                
                        $tmpPos = $_pairTestStartPosList[$i][$tmpTestName];
                        
                        $curTmpFileName = $allFileList[$pairCardNameList[$i]];
                        $resultFileHandle = fopen($curTmpFileName, "r"); 
                        
                        //fseek($_pairResultFileHandleList[$i], $tmpPos, SEEK_SET);
                        fseek($resultFileHandle, $tmpPos, SEEK_SET);

                        //$this->addLinesToFlatData($_pairResultFileHandleList[$i],
                        //                          $_fileHandle,
                        //                          $_machineIDCardNameSysNameDict[$_curPairMachineID],
                        //                          $tmpTestName,
                        //                          true,
                        //                          "");
                                                  
                        $t1 = $this->addLinesToFlatData($resultFileHandle,
                                                  $_fileHandle,
                                                  $_machineIDCardNameSysNameDict[$_curPairMachineID],
                                                  $tmpTestName,
                                                  true,
                                                  "");
                        $mainContent .= $t1;
                        fclose($resultFileHandle);
                    }
                }
            }
            
            if (strlen($mainContent) > 0)
            {
                // add new sheet
                $t1 = file_get_contents($templateFileName3);
                $t1 = sprintf($t1, $tmpTestName);
                fwrite($_fileHandle, $t1);

                // write sheet content
                fwrite($_fileHandle, $mainContent);
                
                // end sheet
                $t1 = file_get_contents($templateFileName4);
                fwrite($_fileHandle, $t1);
            }
        }
        
        return;
    }
    
}


?>