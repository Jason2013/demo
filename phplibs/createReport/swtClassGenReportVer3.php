<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../userManage/swtUserManager.php";

class CGenReport
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
    
	public function getXMLCodePiece()
	{
        $styleBlackBar = "<Style ss:ID=\"s%d\">\n" .
                         "<Interior ss:Color=\"#000000\" ss:Pattern=\"Solid\"/>\n" .
                         "</Style>\n";
                         
        $styleA = "<Style ss:ID=\"s%d\">\n" .
                  "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n" .
                  "<Borders>\n" .
                  "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                  "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                  "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                  "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                  "</Borders>\n" .
                  "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n" .
                  "<Interior ss:Color=\"#800000\" ss:Pattern=\"Solid\"/>\n" .
                  "</Style>\n";
                  
        $styleB = "<Style ss:ID=\"s%d\">\n" . // Center
                  "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n" .
                  "<Borders>\n" .
                  "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                  "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                  "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                  "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                  "</Borders>\n" .
                  "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n" .
                  "<Interior ss:Color=\"#A03300\" ss:Pattern=\"Solid\"/>\n" .
                  "</Style>\n";
                  
        $styleC = "<Style ss:ID=\"s%d\">\n" .
                  "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n" .
                  "<Borders>\n" .
                  "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                  "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                  "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                  "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                  "</Borders>\n" .
                  "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#800000\" ss:Bold=\"1\"/>\n" .
                  "<Interior ss:Color=\"#800000\" ss:Pattern=\"Solid\"/>\n" .
                  "</Style>\n";
                  
        $styleD = "<Style ss:ID=\"s%d\">\n" .
                  "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n" .
                  "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#ffffff\" ss:Bold=\"1\"/>\n" .
                  "<Interior ss:Color=\"#ffffff\" ss:Pattern=\"Solid\"/>\n" .
                  "</Style>\n";
                  
        $styleData = "<Style ss:ID=\"s%d\">\n" .
                     "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n" .
                     "<Borders>\n" .
                     "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                     "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                     "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                     "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                     "</Borders>\n" .
                     "<Interior ss:Color=\"#FFFFA0\" ss:Pattern=\"Solid\"/>\n" .
                     "<NumberFormat ss:Format=\"Fixed\"/>" .
                     "</Style>\n";
                  
        $styleRate = "<Style ss:ID=\"s%d\">\n" .
                     "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n" .
                     "<Borders>\n" .
                     "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                     "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                     "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                     "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                     "</Borders>\n" .
                     "<Interior ss:Color=\"#D0FFFF\" ss:Pattern=\"Solid\"/>\n" .
                     "<NumberFormat ss:Format=\"Percent\"/>\n" .
                     "</Style>\n";
                     
        $styleBlank = "<Style ss:ID=\"s%d\">\n" .
                      "<Interior/>\n" .
                      "</Style>\n";
             
        $styleBLeft = "<Style ss:ID=\"s%d\">\n" . // Center
                      "<Alignment ss:Horizontal=\"Left\" ss:Vertical=\"Center\"/>\n" .
                      "<Borders>\n" .
                      "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                      "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                      "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                      "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                      "</Borders>\n" .
                      "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n" .
                      "<Interior ss:Color=\"#A03300\" ss:Pattern=\"Solid\"/>\n" .
                      "</Style>\n";
                  
        $styleDataLeft = "<Style ss:ID=\"s%d\">\n" .
                         "<Alignment ss:Horizontal=\"Left\" ss:Vertical=\"Center\"/>\n" .
                         "<Borders>\n" .
                         "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                         "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                         "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                         "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n" .
                         "</Borders>\n" .
                         "<Interior ss:Color=\"#FFFFA0\" ss:Pattern=\"Solid\"/>\n" .
                         "<NumberFormat ss:Format=\"Fixed\"/>" .
                         "</Style>\n";
             
        $appendStyleList = array($styleBlackBar, $styleBlank,
                                 $styleA, $styleB,
                                 $styleData, $styleRate,
                                 $styleBLeft, $styleDataLeft,
                                 $styleC, $styleD);
                                 
        $allStylesEndTag = "</Styles>\n";
        $allSheetsEndTag = "</Workbook>";
        
        $returnSet = array();
        $returnSet["appendStyleList"] = $appendStyleList;
        $returnSet["allStylesEndTag"] = $allStylesEndTag;
        $returnSet["allSheetsEndTag"] = $allSheetsEndTag;
        return $returnSet;
    }
    
	public function writeAdditionalStyles($_fileHandle)
	{
        global $startStyleID;
        global $allStylesEndTag;
        global $appendStyleList;
        
        $t3 = "";
        $n1 = $startStyleID;
        foreach ($appendStyleList as $tmpStyle)
        {
            $t3 .= sprintf($tmpStyle, $n1);
            $n1++;
        }

        fwrite($_fileHandle, $t3);
        fwrite($_fileHandle, $allStylesEndTag);
    }

	public function checkInputMachineID($_machineIDPair, $_checkedMachineIDList)
	{
        global $returnMsg;
        global $crossType;
        
        $machineIDPairList = array();
        if (strlen($_machineIDPair) > 0)
        {
            $machineIDPairList = explode(",", $_machineIDPair);
        }
        if ($_checkedMachineIDList === false)
        {
            $_checkedMachineIDList = "";
        }

        $checkedMachineIDList = explode(",", $_checkedMachineIDList);

        foreach ($machineIDPairList as $tmpID)
        {
            $tmpPos = array_search($tmpID, $checkedMachineIDList);
            if ($tmpPos === false)
            {
                array_push($checkedMachineIDList, $tmpID);
            }
        }

        for ($i = 0; $i < count($checkedMachineIDList); $i++)
        {
            if (strlen($checkedMachineIDList[$i]) == 0)
            {
                continue;
            }
            $checkedMachineIDList[$i] = intval($checkedMachineIDList[$i]);
        }

        $checkedMachineIDListString = implode(",", $checkedMachineIDList);
        
        $returnSet = array();
        $returnSet["machineIDPairList"] = $machineIDPairList;
        $returnSet["checkedMachineIDList"] = $checkedMachineIDList;
        $returnSet["checkedMachineIDListString"] = $checkedMachineIDListString;
        
        $returnSet["folderNum"] = count($checkedMachineIDList);
        if ($crossType == 10)
        {
            // cross API report
            $returnSet["folderNum"] = count($checkedMachineIDList);
        }
        else if ($crossType == 11)
        {
            // cross ASIC report
            $returnSet["folderNum"] = intval(count($machineIDPairList) / 2);
        }
        
        return $returnSet;
    }

	public function getBatchID($_db, $_batchID)
	{
        global $returnMsg;
        global $historyBatchMaxNum;
        $db = $_db;
        $batchID = $_batchID;
        $batchIDList = array();

        $b1 = true;
        if ($_batchID == -1)
        {
            $b1 = false;
        }
        else
        {
            $b1 = $this->checkBatchValid($_db, $_batchID);
        }
        
        $userChecker = new CUserManger();

        $params1 = array();
        $sql1 = "";
        
        if ($userChecker->isManager())
        {
            // manager login
            if ($b1 == false)
            {
                // if not assign current batch id
                $sql1 = "SELECT batch_id FROM mis_table_batch_list " .
                        "WHERE batch_state=\"1\" AND (batch_group=\"1\" OR batch_group=\"2\") ORDER BY insert_time DESC LIMIT " . $historyBatchMaxNum;
            }
            else
            {
                // if assign current batch id
                $params1 = array($_batchID);
                $sql1 = "SELECT batch_id FROM mis_table_batch_list " .
                        "WHERE batch_id<=? AND batch_state=\"1\" AND (batch_group=\"1\" OR batch_group=\"2\") " .
                        "ORDER BY insert_time DESC LIMIT " . $historyBatchMaxNum;
            }
        }
        else
        {
            // outside user
            $userID = $userChecker->getUserID();
            if ($b1 == false)
            {
                // if not assign current batch id
                $params1 = array($userID);
                $sql1 = "SELECT t0.batch_id FROM mis_table_user_batch_info t0 " .
                        "WHERE t0.user_id = ? AND t0.batch_id IN " .
                        "(SELECT t1.batch_id FROM mis_table_batch_list t1 " .
                        "WHERE t1.batch_state=\"1\" AND t1.batch_group=\"0\") " .
                        "ORDER BY t0.batch_id DESC LIMIT " . $historyBatchMaxNum . "";
            }
            else
            {
                // if assign current batch id
                $params1 = array($userID, $_batchID);
                $sql1 = "SELECT t0.batch_id FROM mis_table_user_batch_info t0 " .
                        "WHERE t0.user_id = ? AND t0.batch_id <= ? AND t0.batch_id IN " .
                        "(SELECT t1.batch_id FROM mis_table_batch_list t1 " .
                        "WHERE t1.batch_state=\"1\" AND t1.batch_group=\"0\") " .
                        "ORDER BY t0.batch_id DESC LIMIT " . $historyBatchMaxNum . "";
            }
        }

        
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            echo json_encode($returnMsg);
            return null;
        }
        while ($row1 = $db->fetchRow())
        {
            array_push($batchIDList, intval($row1[0]));
        }
        if (count($batchIDList) > 0)
        {
            $batchID = $batchIDList[0];
        }
        
        $returnSet = array();
        $returnSet["batchID"] = $batchID;
        $returnSet["batchIDList"] = $batchIDList;
        return $returnSet;
    }
    
	public function prepareReportFolder($_batchID, $_curReportFolder)
	{
        global $downloadReportDir;
        
        $reportFolder = "";

        $curReportFolder = sprintf("%05d", $_curReportFolder);
        $batchFolder = $downloadReportDir . "/batch" . $_batchID;

        $reportFolder = $batchFolder . "/" . $curReportFolder;

        $returnSet = array();
        $returnSet["reportFolder"] = $reportFolder;
        $returnSet["curReportFolder"] = $_curReportFolder;
        return $returnSet;
    }
    
	public function getConnectValues($_reportFolder)
	{
        global $returnMsg;
        global $swtTempReportConfigJsonName;

        $tmpFileName = $_reportFolder . "/" . $swtTempReportConfigJsonName;
        
        $returnSet = array();
        if (file_exists($tmpFileName))
        {
            $t1 = file_get_contents($tmpFileName);
            $tmpJson = json_decode($t1);
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $returnSet["" . $tmpKey] = $tmpVal;
            }
            
            $tmpJson = $returnSet["allFileTestPosList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpV1 = array();
                foreach ($tmpVal as $k1 => $v1)
                {
                    $tmpV1[$k1] = $v1;
                }
                $tmpArray[$tmpKey] = $tmpV1;
            }
            $returnSet["allFileTestPosList"] = $tmpArray;
            
            $tmpJson = $returnSet["allFileTestCaseNumList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpV1 = array();
                foreach ($tmpVal as $k1 => $v1)
                {
                    $tmpV1[$k1] = $v1;
                }
                $tmpArray[$tmpKey] = $tmpV1;
            }
            $returnSet["allFileTestCaseNumList"] = $tmpArray;
            
            $tmpJson = $returnSet["allFileSubTestNameFilterNumMaxList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpArray[$tmpKey] = $tmpVal;
            }
            $returnSet["allFileSubTestNameFilterNumMaxList"] = $tmpArray;
            
            $tmpJson = $returnSet["allFileFolderTestNameList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpV1 = array();
                foreach ($tmpVal as $k1 => $v1)
                {
                    $tmpV1[$k1] = $v1;
                }
                $tmpArray[$tmpKey] = $tmpV1;
            }
            $returnSet["allFileFolderTestNameList"] = $tmpArray;
            
            $tmpJson = $returnSet["allFileReportUmdNameList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpV1 = array();
                foreach ($tmpVal as $k1 => $v1)
                {
                    $tmpV1[$k1] = $v1;
                }
                $tmpArray[$tmpKey] = $tmpV1;
            }
            $returnSet["allFileReportUmdNameList"] = $tmpArray;
            
            $tmpJson = $returnSet["allFileTestCaseUmdDataMaskList"];
            $tmpArray = array();
            foreach ($tmpJson as $tmpKey => $tmpVal)
            {
                $tmpV1 = array();
                foreach ($tmpVal as $k1 => $v1)
                {
                    $tmpV1[$k1] = $v1;
                }
                $tmpArray[$tmpKey] = $tmpV1;
            }
            $returnSet["allFileTestCaseUmdDataMaskList"] = $tmpArray;
        }
        else
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "connection JSON not found, line: " . __LINE__;
            echo json_encode($returnMsg);
            return null;
        }

        return $returnSet;
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
    
	public function checkBatchNum($_db, $_batchID)
	{
        global $returnMsg;
        $db = $_db;

        $b1 = $this->checkBatchValid($_db, $_batchID);

        if ($b1 == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "specified batch id not exist or invalid";
            echo json_encode($returnMsg);
            return false;
        }
        return true;
    }
    
	public function checkBatchValid($_db, $_batchID)
	{
        global $returnMsg;
        $db = $_db;
        $params1 = array($_batchID);
        $sql1 = "SELECT COUNT(*) FROM mis_table_batch_list " .
                "WHERE batch_id=? AND batch_state=\"1\" AND (batch_group=\"1\" OR batch_group=\"2\" OR batch_group=\"0\")";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            echo json_encode($returnMsg);
            return false;
        }
        $row1 = $db->fetchRow();
        $batchNum = 0;
        if ($row1 == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            echo json_encode($returnMsg);
            return false;
        }
        $batchNum = $row1[0];

        if ($batchNum == 0)
        {
            return false;
        }
        return true;
    }
    
	public function getTestTitleInfo($_curMachineID)
	{
        global $returnMsg;
        global $connectDataSet;
        global $swtUmdNameList;
        global $swtUmdNameList_old;
        global $swtUmdNameList_final;

        $tmpKeys = array_keys($connectDataSet["machineIDList"], $_curMachineID);
        if ($tmpKeys === false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "machine id not found in checked list" . __LINE__;
            echo json_encode($returnMsg);
            return false;
        }
        
        $addedTestNum = 0;
        $testNameList = array();
        $testCaseNumList = array();
        $testCaseNumMap = array();
        $testCaseUmdDataMaskList = array();
        $reportUmdNameList = array();
        $subjectNameFilterNumMax = 0;
        foreach ($tmpKeys as $tmpIndex)
        {
            $tmpTestNum = count($connectDataSet["allFileFolderTestNameList"][$tmpIndex]);
            
            for ($i = 0; $i < $tmpTestNum; $i++)
            {
                $tmpTestName = $connectDataSet["allFileFolderTestNameList"][$tmpIndex][$i];
                $tmpTestCaseNum = $connectDataSet["allFileTestCaseNumList"][$tmpIndex][$tmpTestName];
                $tmpUmdDataMask = $connectDataSet["allFileTestCaseUmdDataMaskList"][$tmpIndex][$i];
                
                $testCaseNumMap[$tmpTestName] = $tmpTestCaseNum;
                
                $tmpPos = array_search($tmpTestName, $testNameList);
                if ($tmpPos === false)
                {
                    array_push($testNameList, $tmpTestName);
                    array_push($testCaseNumList, $tmpTestCaseNum);
                    array_push($testCaseUmdDataMaskList, $tmpUmdDataMask);
                }
                else
                {
                    $testCaseNumList[$tmpPos] = $tmpTestCaseNum;
                    $testCaseUmdDataMaskList[$tmpPos] = $tmpUmdDataMask;
                }
            }
            
            $tmpUmdNum = count($connectDataSet["allFileReportUmdNameList"][$tmpIndex]);
            for ($i = 0; $i < $tmpUmdNum; $i++)
            {
                $tmpUmdName = $connectDataSet["allFileReportUmdNameList"][$tmpIndex][$i];
                $tmpPos = array_search($tmpUmdName, $swtUmdNameList_old);
                if ($tmpPos !== false)
                {
                    $tmpUmdName = $swtUmdNameList_final[$tmpPos];
                }
                if (array_search($tmpUmdName, $reportUmdNameList) === false)
                {
                    array_push($reportUmdNameList, $tmpUmdName);
                }
            }
            $tmpVal = $connectDataSet["allFileSubTestNameFilterNumMaxList"][$tmpIndex];
            $subjectNameFilterNumMax = $tmpVal > $subjectNameFilterNumMax ? $tmpVal : $subjectNameFilterNumMax;
        }
        
        $returnSet = array();
        $returnSet["testNameList"] = $testNameList;
        $returnSet["testCaseNumList"] = $testCaseNumList;
        $returnSet["testCaseUmdDataMaskList"] = $testCaseUmdDataMaskList;
        $returnSet["reportUmdNameList"] = $reportUmdNameList;
        $returnSet["testCaseNumMap"] = $testCaseNumMap;
        $returnSet["subjectNameFilterNumMax"] = $subjectNameFilterNumMax;
        return $returnSet;
    }
    
	public function getCompareMachineInfo($_folderID, $_folderNum)
	{
        global $returnMsg;
        global $machineIDPairList;
        global $checkedMachineIDList;
        global $crossType;
        global $connectDataSet;
        
        $cmpMachineID = -1;
        $curMachineID = -1;
        $cmpMachineName = "";
        $curMachineName = "";
        $cmpCardName = "";
        $cmpSysName = "";
        $curCardName = "";
        $curSysName = "";
        
        $returnSet = array();
        $returnSet["cmpMachineID"] = $cmpMachineID;
        $returnSet["curMachineID"] = $curMachineID;
        $returnSet["cmpMachineName"] = $cmpMachineName;
        $returnSet["curMachineName"] = $curMachineName;
        $returnSet["cmpCardName"] = $cmpCardName;
        $returnSet["cmpSysName"] = $cmpSysName;
        $returnSet["curCardName"] = $curCardName;
        $returnSet["curSysName"] = $curSysName;
        
        if ($_folderID >= $_folderNum)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "invalid folderID, line: " . __LINE__;
            echo json_encode($returnMsg);
            return false;
        }
        
        if ($crossType == 10)
        {
            // cross API
            $curMachineID = intval($checkedMachineIDList[$_folderID]);
            
            $tmpPos = array_search($curMachineID, $connectDataSet["machineIDList"]);
            if ($tmpPos === false)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "machine id not found in checked list" . __LINE__;
                echo json_encode($returnMsg);
                return false;
            }
            $curCardSysName = $connectDataSet["cardNameList"][$tmpPos];
            $tmpVal = explode("_", $curCardSysName);
            
            $curCardName = $tmpVal[0];
            $curMachineName = $tmpVal[0];
            $curSysName = $tmpVal[1];
        }
        else if ($crossType == 11)
        {
            // cross ASIC
            $curMachineID = intval($machineIDPairList[$_folderID * 2]);
            $cmpMachineID = intval($machineIDPairList[$_folderID * 2 + 1]);
            
            $tmpPos1 = array_search($curMachineID, $connectDataSet["machineIDList"]);
            if ($tmpPos1 === false)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "cur machine id not found in paired list" . __LINE__;
                echo json_encode($returnMsg);
                return false;
            }
            $tmpPos2 = array_search($cmpMachineID, $connectDataSet["machineIDList"]);
            if ($tmpPos2 === false)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "cmp machine id not found in paired list" . __LINE__;
                echo json_encode($returnMsg);
                return false;
            }
            
            $curCardSysName = $connectDataSet["cardNameList"][$tmpPos1];
            $tmpVal = explode("_", $curCardSysName);
            
            $curCardName = $tmpVal[0];
            $curMachineName = $tmpVal[0];
            $curSysName = $tmpVal[1];
            
            $curCardSysName = $connectDataSet["cardNameList"][$tmpPos2];
            $tmpVal = explode("_", $curCardSysName);
            
            $cmpCardName = $tmpVal[0];
            $cmpMachineName = $tmpVal[0];
            $cmpSysName = $tmpVal[1];
        }

        $returnSet["cmpMachineID"] = $cmpMachineID;
        $returnSet["curMachineID"] = $curMachineID;
        $returnSet["cmpMachineName"] = $cmpMachineName;
        $returnSet["curMachineName"] = $curMachineName;
        $returnSet["cmpCardName"] = $cmpCardName;
        $returnSet["cmpSysName"] = $cmpSysName;
        $returnSet["curCardName"] = $curCardName;
        $returnSet["curSysName"] = $curSysName;
        return $returnSet;
    }
    
	public function getSubTestNum($_curTestPos, $_curMachineID)
	{
        global $returnMsg;
        global $connectDataSet;

        $tmpKeys = array_keys($connectDataSet["machineIDList"], $_curMachineID);
        if ($tmpKeys === false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "machine id not found in checked list" . __LINE__;
            echo json_encode($returnMsg);
            return false;
        }
        
        $addedTestNum = 0;
        $tmpTestName = "";
        $tmpTestNameMap = array();
        $tmpTestIndex = 0;
        foreach ($tmpKeys as $tmpIndex)
        {
            $tmpTestNum = count($connectDataSet["allFileFolderTestNameList"][$tmpIndex]);
            for ($i = 0; $i < $tmpTestNum; $i++)
            {
                $tmpName = $connectDataSet["allFileFolderTestNameList"][$tmpIndex][$i];
                if ($tmpTestIndex >= $_curTestPos)
                {
                    $tmpTestName = $tmpName;
                    break;
                }
                if (array_key_exists($tmpName, $tmpTestNameMap) === false)
                {
                    $tmpTestNameMap[$tmpName] = true;
                    $tmpTestIndex++;
                }
            }
            if (strlen($tmpTestName) > 0)
            {
                break;
            }
        }
        
        $subTestNum = 0;
        if (strlen($tmpTestName) > 0)
        {
            foreach ($tmpKeys as $tmpIndex)
            {
                $tmpResult = array_key_exists($tmpTestName, $connectDataSet["allFileTestCaseNumList"][$tmpIndex]);
                if ($tmpResult == true)
                {
                    $subTestNum = $connectDataSet["allFileTestCaseNumList"][$tmpIndex][$tmpTestName];
                    //break;
                }
            }
        }

        $returnSet = array();
        $returnSet["subTestNum"] = $subTestNum;
        return $returnSet;
    }
    
	public function getSubTestNumList($_db, $_isCompStandard,
                                      $_resultPos, $_cmpStartResultID,
                                      $_umdNum)
	{
        global $returnMsg;
        global $resultIDList;
        global $testNameList;
        global $db_mis_table_name_string001;
        global $subTestUmdDataMaskList;
        $db = $_db;

        $subTestNumList = array();
        $subTestNumMap = array();
        $cmpSubTestNumList = array();
        if (count($subTestUmdDataMaskList) == 0)
        {
            $subTestUmdDataMaskList = array_fill(0, count($testNameList), 0);
        }
        $skipTestNameList = array();
        
        $tmpStartResultID = intval($_resultPos / $_umdNum) * $_umdNum;
        for ($j = 0; $j < $_umdNum; $j++)
        {
            for ($i = 0; $i < count($testNameList); $i++)
            {
                $tmpTableName = $db_mis_table_name_string001 . $testNameList[$i];
                $params1 = array($resultIDList[0][$tmpStartResultID + $j]);
                $sql1 = "SELECT COUNT(*) FROM " . $tmpTableName . " WHERE result_id=?";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                    echo json_encode($returnMsg);
                    return null;
                }
                $row1 = $db->fetchRow();
                if ($row1 == false)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                    echo json_encode($returnMsg);
                    return null;
                }
                $tmpSubTestNum = intval($row1[0]);
                
                $tmpMask = ($tmpSubTestNum == 0) ? 0 : 1;
                // set mask for present of DX11, DX12, Vulkan
                
                for ($l = 0; $l < $j; $l++)
                {
                    $tmpMask *= 10;
                }
                // set mask to skip blank data column in average data for graph
                $subTestUmdDataMaskList[$i] |= $tmpMask;
            }
        }
        
        if ($_isCompStandard)
        {
            for ($i = 0; $i < count($testNameList); $i++)
            {
                $tmpTableName = $db_mis_table_name_string001 . $testNameList[$i];
                $params1 = array($resultIDList[0][$_resultPos]);
                $sql1 = "SELECT COUNT(*) FROM " . $tmpTableName . " WHERE result_id=?";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                    echo json_encode($returnMsg);
                    return null;
                }
                $row1 = $db->fetchRow();
                if ($row1 == false)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                    echo json_encode($returnMsg);
                    return null;
                }
                $tmpSubTestNum = intval($row1[0]);
                array_push($subTestNumList, $tmpSubTestNum);
                $subTestNumMap[$testNameList[$i]] = intval($tmpSubTestNum);
                
                
                if ($_cmpStartResultID != -1)
                {
                    // if compare with other card
                    $tmpPos = $_resultPos % $_umdNum;
                    $tmpCmpResultID = $resultIDList[0][$_cmpStartResultID + $tmpPos];
                    
                    $returnMsg["test__umdNum"] = $_umdNum;
                    $returnMsg["test__resultPos"] = $_resultPos;
                    $returnMsg["test__cmpStartResultID"] = $_cmpStartResultID;
                    $returnMsg["test_tmpPos"] = $tmpPos;
                    $returnMsg["test_tmpCmpResultID"] = $tmpCmpResultID;
                    
                    $params1 = array($tmpCmpResultID);
                    $sql1 = "SELECT COUNT(*) FROM " . $tmpTableName . " WHERE result_id=?";
                    if ($db->QueryDB($sql1, $params1) == null)
                    {
                        $returnMsg["errorCode"] = 0;
                        $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                        echo json_encode($returnMsg);
                        return null;
                    }
                    $row1 = $db->fetchRow();
                    if ($row1 == false)
                    {
                        $returnMsg["errorCode"] = 0;
                        $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                        echo json_encode($returnMsg);
                        return null;
                    }
                    $tmpSubTestNum = intval($row1[0]);
                    array_push($cmpSubTestNumList, $tmpSubTestNum);
                    if ($tmpSubTestNum == 0)
                    {
                        // set to skip test in report sheet if has no data
                        //array_push($skipTestNameList, $testNameList[$i]);
                    }
                }
            }
        }

        $returnSet = array();
        $returnSet["subTestNumList"] = $subTestNumList;
        $returnSet["subTestNumMap"] = $subTestNumMap;
        $returnSet["cmpSubTestNumList"] = $cmpSubTestNumList;
        $returnSet["skipTestNameList"] = $skipTestNameList;
        $returnSet["subTestUmdDataMaskList"] = $subTestUmdDataMaskList;
        return $returnSet;
    }
    
	public function getReportFileNames($_reportFolder, $_tmpCardName, $_tmpSysName, $_batchID)
	{
        global $returnMsg;

        // main xml file
        $xmlFileName = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d.tmp2", $_batchID);
        // comparison sheet
        $tmpFileName = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d.tmp", $_batchID);
        // flat data
        $tmpFileName1 = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d.tmp1", $_batchID);
        // summary data
        $jsonFileName = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d.json", $_batchID);
        
        $returnSet = array();
        // API sheets
        $returnSet["xmlFileName"] = $xmlFileName;
        // comparison sheet
        $returnSet["tmpFileName"] = $tmpFileName;
        // flat data
        $returnSet["tmpFileName1"] = $tmpFileName1;
        // summary json file for each card, has testNameList
        $returnSet["jsonFileName"] = $jsonFileName;
        return $returnSet;
    }
    
	public function checkShiftAPI($_fileHandle,
                                  $_resultPos,
                                  $_tmpUmdName,
                                  $_firstTestPos,
                                  $_firstSubTestPos,
                                  $_sheetLinePos)
	{
        global $returnMsg;
        global $driverNameList;
        global $reportTemplateDir;
        global $subjectNameFilterNumMax;

        $firstTestPos = $_firstTestPos;
        $firstSubTestPos = $_firstSubTestPos;
        $sheetLinePos = $_sheetLinePos;
        
        $shiftUmd = false;
        $nextUmdPos = $_resultPos + 1;
        if ($nextUmdPos >= count($driverNameList[0]))
        {
            $shiftUmd = true;
        }
        else if (strcmp($_tmpUmdName, $driverNameList[0][$nextUmdPos]) != 0)
        {
            $shiftUmd = true;
        }
        
        //$fileHandle = fopen($_xmlFileName, "r+");
        fseek($_fileHandle, 0, SEEK_END);
        if ($shiftUmd)
        {
            $firstTestPos = -1;
            $firstSubTestPos = -1;
            $sheetLinePos = 0;
            $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet001B.txt");
            if (strlen($xmlSection) == 0)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "template file missing, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            
            $xmlSection = sprintf($xmlSection, 
                                  $subjectNameFilterNumMax + 4, $subjectNameFilterNumMax + 4,
                                  $subjectNameFilterNumMax + 6, $subjectNameFilterNumMax + 6,
                                  $subjectNameFilterNumMax + 8, $subjectNameFilterNumMax + 8);
            fwrite($_fileHandle, $xmlSection);
        }
        
        $returnSet = array();
        $returnSet["firstTestPos"] = $firstTestPos;
        $returnSet["firstSubTestPos"] = $firstSubTestPos;
        $returnSet["sheetLinePos"] = $sheetLinePos;
        return $returnSet;
    }
    
	public function checkShiftCard($_xmlFileName,
                                   $_tmpFileName,
                                   $_tmpFileName1,
                                   $_jsonFileName,
                                   $_allSheetsEndTag,
                                   $_sheetLinePos,
                                   $_tmpCardName,
                                   $_curSysName,
                                   $_cmpMachineID,
                                   $_cmpCardName)
	{
        global $returnMsg;
        global $reportTemplateDir;
        global $cmpMachineID;
        global $subjectNameFilterNumMax;
        global $dataColumnNum;
        global $reportUmdNum;
        global $swtReportInfo;
        global $swtReportUmdInfo;
        global $resultUmdOrder;
        global $crossType;

        $sheetLinePos = $_sheetLinePos;
        
        $tmpSrc = 0;
        $shiftCard = true;

        if ($shiftCard)
        {
            if (file_exists($_xmlFileName) == false)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["tmpSrc"] = $tmpSrc;
                $returnMsg["errorMsg"] = "temp file: " . $_xmlFileName . " missing, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            if (file_exists($_tmpFileName) == false)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["tmpSrc"] = $tmpSrc;
                $returnMsg["errorMsg"] = "temp file: " . $_tmpFileName . " missing, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }

            $_fileHandle = fopen($_xmlFileName, "r+");
            fseek($_fileHandle, 0, SEEK_END);
            
            $sheetLinePos = 0;
            $tmpFileHandle = fopen($_tmpFileName, "r");
            if ($tmpFileHandle !== false)
            {
                fseek($tmpFileHandle, 0, SEEK_END);
                $n1 = ftell($tmpFileHandle);
                fseek($tmpFileHandle, 0, SEEK_SET);
                $onceBytes = 1024;
                while ($n1 > 0)
                {
                    $n2 = $n1 > $onceBytes ? $onceBytes : $n1;
                    $t1 = fread($tmpFileHandle, $n2);
                    fwrite($_fileHandle, $t1);
                    $n1 -= $n2;
                }
                $xmlSection = "";
                if ($cmpMachineID != -1)
                {
                    // if comparison with other cards
                    // $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002B1.txt");
                    $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002B4.txt");
                    
                    $t1 = "";
                    $tmpRange = array();
                    for ($i = 0; $i < intval($dataColumnNum / 3); $i++)
                    {
                        array_push($tmpRange, ("R6C" . ($subjectNameFilterNumMax + 4 + $i * 3) . ":R1000000C" . ($subjectNameFilterNumMax + 4 + $i * 3) . ""));
                    }
                    $t1 = implode(",", $tmpRange);
                    
                    //$returnMsg["conditionFormatRange"] = $t1;
                    //$returnMsg["dataColumnNum_1"] = $dataColumnNum;
                    
                    // freeze column num
                    $n2 = $subjectNameFilterNumMax + 2 + $dataColumnNum;
                    
                    $freezePanesCode = "   <FreezePanes/>\n" .
                                       "   <FrozenNoSplit/>\n" .
                                       "   <SplitHorizontal>4</SplitHorizontal>\n" .
                                       "   <TopRowBottomPane>4</TopRowBottomPane>\n";

                    $xmlSection = sprintf($xmlSection,
                                          $freezePanesCode,
                                          $t1);
                                          
                    //$returnMsg["tmp_ConditionalFormatting0"] = $xmlSection;
                    //$returnMsg["tmp_cmpMachineID0"] = $cmpMachineID;

                }
                else
                {
                    $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002B3.txt");
                    
                    $t1 = "";
                    $tmpRange = array("R6C" . ($subjectNameFilterNumMax + 4) . ":R1000000C" . ($subjectNameFilterNumMax + 4) . "");
                    
                    for ($i = 1; $i < intval($dataColumnNum / 2); $i++)
                    {
                        array_push($tmpRange, "R6C" . ($subjectNameFilterNumMax + (4 + $i * 2)) . ":R1000000C" . ($subjectNameFilterNumMax + (4 + $i * 2)) . "");
                    }
                    $t1 = implode(",", $tmpRange);
                    
                    // freeze column num
                    $n2 = $subjectNameFilterNumMax + 2 + $dataColumnNum;
                    
                    $freezePanesCode = "   <FreezePanes/>\n" .
                                       "   <FrozenNoSplit/>\n" .
                                       "   <SplitHorizontal>4</SplitHorizontal>\n" .
                                       "   <TopRowBottomPane>4</TopRowBottomPane>\n";
                    
                    $xmlSection = sprintf($xmlSection,
                                          $freezePanesCode,
                                          $t1);
                                          
                    //$returnMsg["tmp_ConditionalFormatting"] = $xmlSection;
                    //$returnMsg["tmp_cmpMachineID"] = $cmpMachineID;

                }
                if (strlen($xmlSection) == 0)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "template file missing, line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return null;
                }
                fwrite($_fileHandle, $xmlSection);
                
                fclose($tmpFileHandle);
            }
            
            // summary sheet temp file
            //if ($cmpStartResultID != -1)
            {
                if (file_exists($_jsonFileName))
                {
                    $sectionPosList = array(0,
                                            $reportUmdNum * 2,
                                            $reportUmdNum * 2 + $reportUmdNum * 2,
                                            $reportUmdNum * 2 + $reportUmdNum * 2 + 1,
                                            $reportUmdNum * 2 + $reportUmdNum * 2 + 1 + $reportUmdNum * 8,
                                            $reportUmdNum * 2 + $reportUmdNum * 2 + 1 + $reportUmdNum * 8 + $reportUmdNum * 2,
                                            $reportUmdNum * 2 + $reportUmdNum * 2 + 1 + 
                                            $reportUmdNum * 8 + $reportUmdNum * 2 + $reportUmdNum * 4
                                            );
                    
                    $tmpReportUmdInfo = $swtReportUmdInfo;
                    
                    $summarySheetHeadCode = " <Worksheet ss:Name=\"Summary\">" .
                                            "  <Table x:FullColumns=\"1\"" .
                                            "   x:FullRows=\"1\" ss:StyleID=\"Default\" ss:DefaultRowHeight=\"15\">" .
                                            "   <Column ss:AutoFitWidth=\"0\" ss:Width=\"80\"/>";
                    
                    $summarySheetHeadCode2 = "   <Row ss:StyleID=\"Default\">" .
                                             "    <Cell ss:StyleID=\"s91\"><Data ss:Type=\"String\">Tests</Data></Cell>";
                                             
                    if ($cmpMachineID != -1)
                    {
                        // asic comparison
                        for ($i = 0; $i < $reportUmdNum; $i++)
                        {
                            if (($resultUmdOrder[$i] == -1) ||
                                ($resultUmdOrder[$reportUmdNum + $i] == -1))
                            {
                                continue;
                            }
                            
                            $summarySheetHeadCode .= "   <Column ss:AutoFitWidth=\"0\" ss:Width=\"500\"/>";
                            $summarySheetHeadCode2 .= "<Cell ss:StyleID=\"s92\"><Data ss:Type=\"String\">" . 
                                                     ($tmpReportUmdInfo[$i]) . 
                                                     "</Data></Cell>";
                        }
                    }
                    else
                    {
                        // api comparison
                        
                        $tmpIndexList = array();
                        for ($i = 0; $i < $reportUmdNum; $i++)
                        {
                            if ($resultUmdOrder[$i] == -1)
                            {
                                continue;
                            }
                            array_push($tmpIndexList, $i);
                        }
                        
                        for ($i = 0; $i < count($tmpIndexList); $i++)
                        {
                            if ($i < (count($tmpIndexList) - 1))
                            {
                                if (($resultUmdOrder[$tmpIndexList[$i]] == -1) ||
                                    ($resultUmdOrder[$tmpIndexList[$i + 1]] == -1))
                                {
                                    continue;
                                }
                                
                                $summarySheetHeadCode .= "   <Column ss:AutoFitWidth=\"0\" ss:Width=\"500\"/>";
                                $summarySheetHeadCode2 .= "<Cell ss:StyleID=\"s92\"><Data ss:Type=\"String\">" . 
                                                         ($tmpReportUmdInfo[$tmpIndexList[$i + 1]] . " v.s " . $tmpReportUmdInfo[$tmpIndexList[$i]]) . 
                                                         "</Data></Cell>";
                            }
                            else
                            {
                                if (($resultUmdOrder[$tmpIndexList[$i]] == -1) ||
                                    ($resultUmdOrder[$tmpIndexList[0]] == -1))
                                {
                                    continue;
                                }
                                
                                $summarySheetHeadCode .= "   <Column ss:AutoFitWidth=\"0\" ss:Width=\"500\"/>";
                                $summarySheetHeadCode2 .= "<Cell ss:StyleID=\"s92\"><Data ss:Type=\"String\">" . 
                                                         ($tmpReportUmdInfo[$tmpIndexList[$i]] . " v.s " . $tmpReportUmdInfo[$tmpIndexList[0]]) . 
                                                         "</Data></Cell>";
                            }
                        }
                    }

                    $summarySheetHeadCode2 .= "   </Row>";
                    
                    $t1 = $summarySheetHeadCode . $summarySheetHeadCode2;
                    
                    fwrite($_fileHandle, $t1);
                    $t1 = file_get_contents($_jsonFileName);
                    $summaryJson = json_decode($t1, true);
                    
                    $t1 = file_get_contents($reportTemplateDir . "/../reportConfig/summarySheet.json");
                    $variationJson = json_decode($t1, true);
                    
                    $t1 = "";
                    foreach ($summaryJson as $k=>$v)
                    {
                        
                        $t1 .= "<Row ss:StyleID=\"Default\">\n";
                        $t1 .= "<Cell ss:MergeDown=\"1\" ss:StyleID=\"s93\"><Data ss:Type=\"String\">" . $k . "</Data></Cell>\n";
                        $t7 = "<Row ss:StyleID=\"Default\" ss:Height=\"30\">\n";
                        $t6 = "<Row ss:StyleID=\"Default\" >\n";
                        $t4 = "";
                        
                        $tmpVal = $variationJson["defaultVariation"];
                        if (array_key_exists($k, $variationJson))
                        {
                            $tmpVal = $variationJson[$k];
                        }
                        
                        //$tmpSum = intval($v[12]);
                        $tmpSum = intval($v[$sectionPosList[2]]);
                        
                        $hasContent = false;
                        $tmpColumnNum = 0;
                        
                        $tmpIndexList = array();
                        $tmpLoopNum = 0;
                        
                        // to deal with random api missing
                        if ($cmpMachineID != -1)
                        {
                            for ($i = 0; $i < $reportUmdNum; $i++)
                            {
                                array_push($tmpIndexList, $i);
                            }
                            $tmpLoopNum = $reportUmdNum;
                        }
                        else
                        {
                            for ($i = 0; $i < $reportUmdNum; $i++)
                            {
                                if ($resultUmdOrder[$i] == -1)
                                {
                                    continue;
                                }
                                array_push($tmpIndexList, $i);
                            }
                            $tmpLoopNum = count($tmpIndexList);
                        }
                        
                        for ($i = 0; $i < $tmpLoopNum; $i++)
                        {
                            // skip absent api
                            if ($cmpMachineID != -1)
                            {
                                if (($resultUmdOrder[$i] == -1) ||
                                    ($resultUmdOrder[$reportUmdNum + $i] == -1))
                                {
                                    continue;
                                } 
                            }
                            
                            $j = $tmpIndexList[$i] * 2;
                            
                            $tmpMin = floatval($v[$tmpIndexList[$i] * 2]);
                            $tmpMax = floatval($v[$tmpIndexList[$i] * 2 + 1]);
                            $tmpMinRate = round($tmpMin * 100.0);
                            $tmpMaxRate = round($tmpMax * 100.0);
                            $tmpLmtMinRate = round($tmpVal[0] * 100.0);
                            $tmpLmtMaxRate = round($tmpVal[1] * 100.0);
                            $tmpUp = intval($v[$sectionPosList[1] + $tmpIndexList[$i] * 2 + 1]);
                            $tmpDown = intval($v[$sectionPosList[1] + $tmpIndexList[$i] * 2]);
                            $tmpUpRate = intval($tmpSum == 0 ? 0 : $tmpUp * 100.0 / $tmpSum);
                            $tmpDownRate = intval($tmpSum == 0 ? 0 : $tmpDown * 100.0 / $tmpSum);
                            
                            $lossRateSum = $v[$sectionPosList[5] + $tmpIndexList[$i] * 4];
                            $lossRateNum = $v[$sectionPosList[5] + $tmpIndexList[$i] * 4 + 1];
                            $gainRateSum = $v[$sectionPosList[5] + $tmpIndexList[$i] * 4 + 2];
                            $gainRateNum = $v[$sectionPosList[5] + $tmpIndexList[$i] * 4 + 3];
                            
                            $t2 = "Even";
                            $isEven = true;
                            // grey font
                            $t3 = "s95";
                            $t5 = "";
                            if (($tmpMin == -1) ||
                                ($tmpMax == -1))
                            {
                                $t2 = "N/A";
                                $isEven = false;
                                
                            }
                            else if (($tmpMinRate < $tmpLmtMinRate) ||
                                     ($tmpMaxRate > $tmpLmtMaxRate))
                            {
                                // black font
                                $t3 = "s94";
                                $t2 = sprintf("[%d%%, %d%%], %d%% test cases drop and %d%% test cases gain",
                                              $tmpMinRate, $tmpMaxRate,
                                              $tmpDownRate, $tmpUpRate);
                                $isEven = false;
                            }

                            if (($tmpMin == -1) ||
                                ($tmpMax == -1))
                            {
                                // black
                                $t3 = "s94";
                            }
                            else
                            {
                                if (($tmpMinRate < $tmpLmtMinRate) &&
                                    ($tmpMaxRate < $tmpLmtMaxRate))
                                {
                                    // all down, font red
                                    $t3 = "s98";
                                    $n3 = $lossRateNum == 0 ? 0 : round(($lossRateSum * 100) / $lossRateNum);
                                    $n4 = $gainRateNum == 0 ? 0 : round(($gainRateSum * 100) / $gainRateNum);
                                    $n5 = abs($n3 + $n4);
                                    $n5 = $n5 > maxAverageStyleRate ? maxAverageStyleRate : $n5;
                                    $n6 = intval($n5 / maxAverageStyleRate * (reportStyleRedNum - 1));
                                    $n7 = intval(reportStyleRedStart) + $n6;
                                    $t3 = "s" . $n7;
                                }
                                else if (($tmpMinRate > $tmpLmtMinRate) &&
                                         ($tmpMaxRate > $tmpLmtMaxRate))
                                {
                                    // all up, font green
                                    $t3 = "s99";
                                    $n3 = $lossRateNum == 0 ? 0 : round(($lossRateSum * 100) / $lossRateNum);
                                    $n4 = $gainRateNum == 0 ? 0 : round(($gainRateSum * 100) / $gainRateNum);
                                    $n5 = abs($n3 + $n4);
                                    $n5 = $n5 > maxAverageStyleRate ? maxAverageStyleRate : $n5;
                                    $n6 = intval($n5 / maxAverageStyleRate * (reportStyleGreenNum - 1));
                                    $n7 = intval(reportStyleGreenStart) + $n6;
                                    $t3 = "s" . $n7;
                                }
                                else if ($isEven == false)
                                {
                                    // not even, not N/A, not pure gain or loss
                                    $n3 = $lossRateNum == 0 ? 0 : round(($lossRateSum * 100) / $lossRateNum);
                                    $n4 = $gainRateNum == 0 ? 0 : round(($gainRateSum * 100) / $gainRateNum);
                                    $n5 = abs($n3 + $n4);
                                    $n8 = ($n3 + $n4);
                                    $n5 = $n5 > maxAverageStyleRate ? maxAverageStyleRate : $n5;
                                    if ($n8 > 0)
                                    {
                                        // gain green
                                        $n6 = intval($n5 / maxAverageStyleRate * (reportStyleGreenNum - 1));
                                        $n7 = intval(reportStyleGreenStart) + $n6;
                                        $t3 = "s" . $n7;
                                    }
                                    else if ($n8 == 0)
                                    {
                                        // black
                                        $t3 = "s94";
                                    }
                                    else
                                    {
                                        // loss red
                                        $n6 = intval($n5 / maxAverageStyleRate * (reportStyleRedNum - 1));
                                        $n7 = intval(reportStyleRedStart) + $n6;
                                        $t3 = "s" . $n7;
                                    }
                                }
                                
                                if ($isEven == true)
                                {
                                    $t5 = "";
                                }
                                else
                                {
                                    // if not even
                                    if ($tmpMinRate == 0)
                                    {
                                        $t5 = sprintf("&#10;" .
                                                      "#%d,\t%s,\t%d,\t%d%%,\t%d",
                                                      $v[$sectionPosList[3] + 4 + $tmpIndexList[$i] * 8], 
                                                      $v[$sectionPosList[3] + 4 + $tmpIndexList[$i] * 8 + 1],
                                                      $v[$sectionPosList[3] + 4 + $tmpIndexList[$i] * 8 + 2], 
                                                      $tmpMaxRate, $v[$sectionPosList[3] + 4 + $tmpIndexList[$i] * 8 + 3]);

                                    }
                                    else if ($tmpMaxRate == 0)
                                    {
                                        $t5 = sprintf("#%d,\t%s,\t%d,\t%d%%,\t%d" .
                                                      "&#10;",
                                                      $v[$sectionPosList[3] + $tmpIndexList[$i] * 8], 
                                                      $v[$sectionPosList[3] + $tmpIndexList[$i] * 8 + 1],
                                                      $v[$sectionPosList[3] + $tmpIndexList[$i] * 8 + 2], 
                                                      $tmpMinRate, $v[$sectionPosList[3] + $tmpIndexList[$i] * 8 + 3]);
                                    }
                                    else
                                    {
                                        $t5 = sprintf("#%d,\t%s,\t%d,\t%d%%,\t%d&#10;" .
                                                      "#%d,\t%s,\t%d,\t%d%%,\t%d",
                                                      $v[$sectionPosList[3] + $tmpIndexList[$i] * 8], 
                                                      $v[$sectionPosList[3] + $tmpIndexList[$i] * 8 + 1],
                                                      $v[$sectionPosList[3] + $tmpIndexList[$i] * 8 + 2], 
                                                      $tmpMinRate, $v[$sectionPosList[3] + $tmpIndexList[$i] * 8 + 3],
                                                      $v[$sectionPosList[3] + 4 + $tmpIndexList[$i] * 8], 
                                                      $v[$sectionPosList[3] + 4 + $tmpIndexList[$i] * 8 + 1],
                                                      $v[$sectionPosList[3] + 4 + $tmpIndexList[$i] * 8 + 2], 
                                                      $tmpMaxRate, $v[$sectionPosList[3] + 4 + $tmpIndexList[$i] * 8 + 3]);
                                    }
                                }
                            }
                            
                            $t1 .= "<Cell ss:StyleID=\"" . $t3 . "\"><Data ss:Type=\"String\">" . $t2 . "</Data></Cell>\n";
                            // summary sheet
                            $t4 .= "<Cell ss:Index=\"" . (2 + $tmpColumnNum) . 
                                   "\" ss:StyleID=\"s100\"><Data ss:Type=\"String\">" . $t5 . 
                                   "</Data></Cell>\n";
                                   
                            if (strlen($t5) > 0)
                            {
                                $hasContent = true;
                            }
                            
                            $tmpColumnNum++;
                        }

                        $t1 .= "</Row>\n";
                        $t4 .= "</Row>\n";
                        
                        if ($hasContent)
                        {
                            $t4 = $t7 . $t4;
                        }
                        else
                        {
                            $t4 = $t6 . $t4;
                        }
                        
                        $t1 .= $t4;
                    }
                    
                    //file_put_contents($_jsonFileName, $t1);
                    
                    fwrite($_fileHandle, $t1);
                    $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet004B.txt");
                    fwrite($_fileHandle, $xmlSection);
                    
                    unlink($_jsonFileName);
                }
            }
            
            // save flatdata into separate report
            if (file_exists($_tmpFileName1))
            {
                // *.tmp1
                $n1 = strlen($_tmpFileName1) - strlen(".tmp1");
                $t1 = substr($_tmpFileName1, 0, $n1);
                $t1 .= "(FlatData).xml";
                rename($_tmpFileName1, $t1);
            }

            // end this xml
            fwrite($_fileHandle, $_allSheetsEndTag);
            fclose($_fileHandle);
            
            
            
        }
        
        $returnSet = array();
        $returnSet["funcSuccess"] = true;
        $returnSet["sheetLinePos"] = $sheetLinePos;
        return $returnSet;
    }
    
	public function checkAllReportsFinished($_reportFolder,
                                            $_folderID,
                                            $_folderNum)
	{
        global $returnMsg;

        if ($_folderID >= $_folderNum)
        {
            // rename final report files
            $oldReportList = glob($_reportFolder . "/*.tmp2");
            foreach ($oldReportList as $tmpPath)
            {
                $newPath = substr($tmpPath, 0, strlen($tmpPath) - strlen("tmp2")) . "xml";
                rename($tmpPath, $newPath);
            }
            // clear tmp files
            $oldReportList = glob($_reportFolder . "/*.tmp");
            foreach ($oldReportList as $tmpPath)
            {
                unlink($tmpPath);
            }
            $oldReportList = glob($_reportFolder . "/*.tmp1");
            foreach ($oldReportList as $tmpPath)
            {
                unlink($tmpPath);
            }
            // end of generating reports

            $this->delConnectJson($_reportFolder);
            
            $returnMsg["errorCode"] = 1;
            $returnMsg["compileFinished"] = 1;
            $returnMsg["errorMsg"] = "report finished";
            echo json_encode($returnMsg);
            return null;
        }

        return true;
    }
    
	public function checkReportDataColumnNum()
    {
        global $umdNameList;
        global $resultUmdOrder;
        global $reportUmdNum;
        global $cmpMachineID;
        global $crossType;
        
        $dataColumnNum = 0;
        
        if ($cmpMachineID != -1)
        {
            // asic comparison
            $curFirstRowAPIColumnID = 0;
            for ($i = 0; $i < $reportUmdNum; $i++)
            {
                if (($resultUmdOrder[$i] == -1) ||
                    ($resultUmdOrder[$reportUmdNum + $i] == -1))
                {
                    // absent api
                    continue;
                }
                $curFirstRowAPIColumnID++;
            }
            $dataColumnNum = $curFirstRowAPIColumnID * 3;
        }
        else
        {
            // api comparison
            $curFirstRowAPIColumnID = 0;
            for ($i = 0; $i < $reportUmdNum; $i++)
            {
                if ($resultUmdOrder[$i] == -1)
                {
                    // absent api
                    continue;
                }
                $curFirstRowAPIColumnID++;
            }

            if ($curFirstRowAPIColumnID == 1)
            {
                $dataColumnNum = 1;
            }
            else
            {
                $dataColumnNum = $curFirstRowAPIColumnID * 2;
            }
        }
        
        if (($curFirstRowAPIColumnID == 0) &&
            ($crossType != 2))
        {
            // no data
            return null;
        }
        
        $returnSet = array();
        $returnSet["dataColumnNum"] = $dataColumnNum;
        return $returnSet;
    }
    
	public function checkNeedCreateReportFile($_xmlFileName, $_tmpFileName, $_jsonFileName,
                                              $_cmpMachineID,
                                              $_curCardName, $_curSysName,
                                              $_cmpCardName, $_cmpSysName)
	{
        global $returnMsg;
        global $reportTemplateDir;
        global $umdNameList;
        global $resultUmdOrder;
        global $reportUmdNum;
        global $testNameList;
        global $subTestNumList;
        global $subjectNameFilterNumMax;
        global $swtReportInfo;
        global $swtReportUmdInfo;
        global $crossType;
        global $curResultTime;
        global $cmpBatchTime;
        global $curMachineName;
        global $cmpMachineName;
        
        if (file_exists($_xmlFileName) == false)
        {
            // copy file template to modify
            $xmlSection = file_get_contents($reportTemplateDir . "/sectionHead001.txt");
            
            if (strlen($xmlSection) == 0)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "template file missing, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }

            $fileHandle = fopen($_xmlFileName, "w+");
            
            // append styles
            fseek($fileHandle, 0, SEEK_SET);
            fwrite($fileHandle, $xmlSection);
            
            // write additional styles
            $this->writeAdditionalStyles($fileHandle);
            
            fclose($fileHandle);
            
            
            $tmpReportInfo = $swtReportInfo;
            $tmpReportUmdInfo = $swtReportUmdInfo;
            
            $returnMsg["genXmlHead"] = 1;
            
            // create tmp file
            $tempFileHandle = fopen($_tmpFileName, "w+");
            $xmlSection = "";
            $t1 = "";
            if ($_cmpMachineID != -1)
            {
                // if comparison with other card
                
                $curCardTitle = $_curCardName;
                $cmpCardTitle = $_cmpCardName;
                if ($_curCardName == $_cmpCardName)
                {
                    $curCardTitle .= "&#10;" . $_curSysName;
                    $cmpCardTitle .= "&#10;" . $_cmpSysName;
                }
                
                $curCardTitle2 = $_curCardName . " - " . $_curSysName;
                $cmpCardTitle2 = $_cmpCardName . " - " . $_cmpSysName;
                
                $curCardTitle3 = $_curCardName;
                $cmpCardTitle3 = $_cmpCardName;
                
                
                // sheet head, comparison
                $reportCardComparisonHead = " <Worksheet ss:Name=\"Cross-API_Comparison\">" .
                                            "  <Table x:FullColumns=\"1\"" . // ss:ExpandedRowCount=\"%010d\"
                                            "   x:FullRows=\"1\" ss:StyleID=\"s63\" ss:DefaultRowHeight=\"15\">" .
                                            "   <Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"60\"/>" .
                                            "   <Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"60\"/>" .
                                            "   <Column ss:Index=\"" . ($subjectNameFilterNumMax + 3) . 
                                            "\" ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"60\"" .
                                            "  ss:Span=\"11\"/>";
                                        
                $t1 = $reportCardComparisonHead;
                // first row
                $t1 .= "   <Row ss:StyleID=\"Default\">" .
                       "    <Cell ss:StyleID=\"s84\"/>" .
                       "    <Cell ss:StyleID=\"s84\" ss:MergeAcross=\"" . ($subjectNameFilterNumMax - 0) . "\" >" .
                       "<Data ss:Type=\"String\">" . ($cmpCardTitle2 . " vs " . $curCardTitle2) . 
                       "</Data></Cell>";
                $curFirstRowAPIColumnID = 0;
                for ($i = 0; $i < $reportUmdNum; $i++)
                {
                    if (($resultUmdOrder[$i] == -1) ||
                        ($resultUmdOrder[$reportUmdNum + $i] == -1))
                    {
                        // absent api
                        continue;
                    }
                    $t1 .= "    <Cell ss:Index=\"" . ($subjectNameFilterNumMax + 3 + $curFirstRowAPIColumnID * 3) . 
                           "\" ss:MergeAcross=\"2\" ss:StyleID=\"s84\" >" .
                           "<Data ss:Type=\"String\">" . ($tmpReportUmdInfo[$i]) . "</Data></Cell>";
                    $curFirstRowAPIColumnID++;
                }
                
                $t1 .= "   </Row>";
                
                // second row
                // ss:AutoFitHeight=\"1\"
                $t1 .= "   <Row ss:Height=\"100\" ss:StyleID=\"Default\">" .
                       "    <Cell ss:StyleID=\"s89\"/>" .
                       "    <Cell ss:StyleID=\"s89\" ss:MergeAcross=\"" . ($subjectNameFilterNumMax - 0) . 
                       "\" ><Data ss:Type=\"String\">API</Data></Cell>";
                       
                $curFirstRowAPIColumnID = 0;
                for ($i = 0; $i < $reportUmdNum; $i++)
                {
                    if (($resultUmdOrder[$i] == -1) ||
                        ($resultUmdOrder[$reportUmdNum + $i] == -1))
                    {
                        // absent api
                        continue;
                    }
                    $t1 .= "    <Cell ss:StyleID=\"s87\"><Data ss:Type=\"String\">" . ($curCardTitle) . "</Data></Cell>" .
                           "    <Cell ss:StyleID=\"s87\"><Data ss:Type=\"String\">" . ($cmpCardTitle3 . "&#10;vs&#10;" . $curCardTitle3) . "</Data></Cell>" .
                           "    <Cell ss:StyleID=\"s87\"><Data ss:Type=\"String\">" . ($cmpCardTitle) . "</Data></Cell>";
                    $curFirstRowAPIColumnID++;
                }
                
                $t1 .= "   </Row>";
                
                $t1 .= "   <Row ss:StyleID=\"Default\">" .
                       "    <Cell ss:StyleID=\"s89\"/>" .
                       "    <Cell ss:StyleID=\"s89\" ss:MergeAcross=\"" . ($subjectNameFilterNumMax - 0) . 
                       "\" ><Data ss:Type=\"String\">Driver</Data></Cell>";
                       
                $curFirstRowAPIColumnID = 0;
                for ($i = 0; $i < $reportUmdNum; $i++)
                {
                    if (($resultUmdOrder[$i] == -1) ||
                        ($resultUmdOrder[$reportUmdNum + $i] == -1))
                    {
                        // absent api
                        continue;
                    }
                    $t1 .= "    <Cell ss:StyleID=\"s88\" ><Data ss:Type=\"String\">" . ($tmpReportInfo[$i]) . "</Data></Cell>" .
                           "    <Cell ss:StyleID=\"s88\"/>" .
                           "    <Cell ss:StyleID=\"s88\"/>";
                    $curFirstRowAPIColumnID++;
                }
                
                $t1 .= "   </Row>";
            }
            else
            {
                //$xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002Aa.txt");
                
                $reportAPIComparisonHead = " <Worksheet ss:Name=\"Cross-API_Comparison\">" .
                                           "  <Table x:FullColumns=\"1\"" . // ss:ExpandedRowCount=\"%010d\"
                                           "   x:FullRows=\"1\" ss:StyleID=\"s63\" ss:DefaultRowHeight=\"15\">" .
                                           "   <Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"60\"/>" .
                                           "   <Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"60\"/>";
                
                $t1 = $reportAPIComparisonHead;
                
                $t1 .= "   <Row ss:StyleID=\"Default\">" .
                       "    <Cell ss:StyleID=\"s84\"/>" .
                       "    <Cell ss:StyleID=\"s84\" ss:MergeAcross=\"" . ($subjectNameFilterNumMax - 0) . 
                       "\" ><Data ss:Type=\"String\">" . ($_curCardName . " - " . $_curSysName) . "</Data></Cell>";
                
                
                $curFirstRowAPIColumnID = 0;
                for ($i = 0; $i < $reportUmdNum; $i++)
                {
                    if ($resultUmdOrder[$i] == -1)
                    {
                        // absent api
                        continue;
                    }
                    if ($curFirstRowAPIColumnID == 0)
                    {
                        $t1 .= "    <Cell ss:StyleID=\"s84\"/>";
                    }
                    else if ($curFirstRowAPIColumnID == 1)
                    {
                        $t1 .= "    <Cell ss:StyleID=\"s84\"/>" .
                               "    <Cell ss:StyleID=\"s84\"/>" .
                               "    <Cell ss:StyleID=\"s84\"/>";
                    }
                    else
                    {
                        $t1 .= "    <Cell ss:StyleID=\"s84\"/>" .
                               "    <Cell ss:StyleID=\"s84\"/>";
                    }

                    $curFirstRowAPIColumnID++;
                }
                
                $t1 .= "   </Row>";
                
                $t1 .= "   <Row ss:AutoFitHeight=\"0\" ss:Height=\"45\" ss:StyleID=\"Default\">" .
                       "    <Cell ss:StyleID=\"s89\"/>" .
                       "    <Cell ss:StyleID=\"s89\" ss:MergeAcross=\"" . ($subjectNameFilterNumMax - 0) . 
                       "\" ><Data ss:Type=\"String\">API</Data></Cell>";
                       
                $curFirstRowAPIColumnID = 0;
                $tmpAPIList = array();
                $tmpCLList = array();
                for ($i = 0; $i < $reportUmdNum; $i++)
                {
                    if ($resultUmdOrder[$i] == -1)
                    {
                        // absent api
                        continue;
                    }
                    array_push($tmpAPIList, $tmpReportUmdInfo[$i]);
                    array_push($tmpCLList, $tmpReportInfo[$i]);
                    $curFirstRowAPIColumnID++;
                }
                if (count($tmpAPIList) == 1)
                {
                    // only 1 API
                    $t1 .= "    <Cell ss:StyleID=\"s87\"  ><Data ss:Type=\"String\">" . ($tmpAPIList[0]) . "</Data></Cell>";
                }
                else if (count($tmpAPIList) > 1)
                {
                    // more than 1 API
                    for ($i = 0; $i < count($tmpAPIList); $i++)
                    {
                        $t1 .= "    <Cell ss:StyleID=\"s87\"  ><Data ss:Type=\"String\">" . ($tmpAPIList[$i]) . "</Data></Cell>";
                        if ($i < (count($tmpAPIList) - 1))
                        {
                            $t1 .= "    <Cell ss:StyleID=\"s87\">" .
                                   "<Data ss:Type=\"String\">" . ($tmpAPIList[$i + 1] . "&#10;vs&#10;" . $tmpAPIList[$i]) . 
                                   "</Data></Cell>";
                        }
                        else
                        {
                            $t1 .= "    <Cell ss:StyleID=\"s87\">" .
                                   "<Data ss:Type=\"String\">" . ($tmpAPIList[$i] . "&#10;vs&#10;" . $tmpAPIList[0]) . 
                                   "</Data></Cell>";
                        }
                    }
                }
                
                $t1 .= "   </Row>";
                       
                $t1 .= "   <Row ss:StyleID=\"Default\">" .
                       "    <Cell ss:StyleID=\"s89\"/>" .
                       "    <Cell ss:StyleID=\"s89\" ss:MergeAcross=\"" . ($subjectNameFilterNumMax - 0) . 
                       "\" ><Data ss:Type=\"String\">Driver</Data></Cell>";
                       
                if (count($tmpAPIList) == 1)
                {
                    // only 1 API
                    $t1 .= "    <Cell ss:StyleID=\"s88\"><Data ss:Type=\"String\">" . ($tmpCLList[0]) . "</Data></Cell>";
                }
                else if (count($tmpAPIList) > 1)
                {
                    for ($i = 0; $i < count($tmpCLList); $i++)
                    {
                        $t1 .= "    <Cell ss:StyleID=\"s88\"><Data ss:Type=\"String\">" . ($tmpCLList[$i]) . "</Data></Cell>" .
                               "    <Cell ss:StyleID=\"s88\"/>";
                    }
                }
                      
                $t1 .= "   </Row>";
                
            }

            fwrite($tempFileHandle, $t1);
            fclose($tempFileHandle);
            
            // create summary sheet temp file
            if (file_exists($_jsonFileName) == false)
            {
                $sectionPosList = array(0,
                                        $reportUmdNum * 2,
                                        $reportUmdNum * 2 + $reportUmdNum * 2,
                                        $reportUmdNum * 2 + $reportUmdNum * 2 + 1,
                                        $reportUmdNum * 2 + $reportUmdNum * 2 + 1 + $reportUmdNum * 8,
                                        $reportUmdNum * 2 + $reportUmdNum * 2 + 1 + $reportUmdNum * 8 + $reportUmdNum * 2,
                                        $reportUmdNum * 2 + $reportUmdNum * 2 + 1 + 
                                        $reportUmdNum * 8 + $reportUmdNum * 2 + $reportUmdNum * 4
                                        );
                               
                $tmpObj = array();
                
                foreach ($testNameList as $tmpName)
                {
                    $tmpObj[$tmpName] = array_fill(0, $sectionPosList[6], -1);

                    for ($i = 0; $i < $reportUmdNum; $i++)
                    {
                        $j = $i * 2;
                        
                        $tmpObj[$tmpName][$j] =     -1;
                        $tmpObj[$tmpName][$j + 1] = -1;
                        
                        $tmpPos = $sectionPosList[1];
                        $tmpObj[$tmpName][$tmpPos + $j] =     0;
                        $tmpObj[$tmpName][$tmpPos + $j + 1] = 0;
                        
                        $tmpPos2 = $sectionPosList[2];
                        $tmpObj[$tmpName][$tmpPos2] = 0;
                        
                        $tmpPos3 = $sectionPosList[3];
                        $tmpObj[$tmpName][$tmpPos3 + $j * 4] =     -1;
                        $tmpObj[$tmpName][$tmpPos3 + $j * 4 + 1] = "";
                        $tmpObj[$tmpName][$tmpPos3 + $j * 4 + 2] = 0;
                        $tmpObj[$tmpName][$tmpPos3 + $j * 4 + 3] = 0;
                        $tmpObj[$tmpName][$tmpPos3 + $j * 4 + 4] = -1;
                        $tmpObj[$tmpName][$tmpPos3 + $j * 4 + 5] = "";
                        $tmpObj[$tmpName][$tmpPos3 + $j * 4 + 6] = 0;
                        $tmpObj[$tmpName][$tmpPos3 + $j * 4 + 7] = 0;
                        
                        $tmpPos4 = $sectionPosList[4];
                        $tmpObj[$tmpName][$tmpPos4 + $j] =     "";
                        $tmpObj[$tmpName][$tmpPos4 + $j + 1] = "";
                        
                        $tmpPos5 = $sectionPosList[5];
                        $tmpObj[$tmpName][$tmpPos5 + $i * 4] =     0;
                        $tmpObj[$tmpName][$tmpPos5 + $i * 4 + 1] = 0;
                        $tmpObj[$tmpName][$tmpPos5 + $i * 4 + 2] = 0;
                        $tmpObj[$tmpName][$tmpPos5 + $i * 4 + 3] = 0;
                    }
                }
                
                $t1 = json_encode($tmpObj);
                
                if (file_exists($_jsonFileName))
                {
                    unlink($_jsonFileName);
                }
                file_put_contents($_jsonFileName, $t1);
            }
        }
        $returnSet = array();
        $returnSet["createFile"] = "done";
        return $returnSet;
    }
    
	public function checkStartSheet($_fileHandle, $_tempFileHandle,
                                    $_curTestPos, $_nextSubTestPos, $_firstTestPos, $_firstSubTestPos,
                                    $_lineNumPos, $_resultPos, $_umdNum,
                                    $_tmpUmdName, $_tmpCardName, $_tmpSysName)
	{
        global $returnMsg;
        global $historyBatchMaxNum;
        global $resultIDList;
        global $mainLineNameList;
        global $sClockNameList;
        global $mClockNameList;
        global $gpuMemNameList;
        global $cpuNameList;
        global $cardNameList;
        global $sysNameList;
        global $resultTimeList;
        global $changeListNumList;
        global $startSheetLineNum;
        global $reportTemplateDir;
        global $subjectNameFilterNumMax;

        $lineNumPos = $_lineNumPos;
        
        if (($_curTestPos     == $_firstTestPos) &&
            ($_nextSubTestPos == $_firstSubTestPos))
        {
            // start of each sheet
            $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet001Aa.txt");
            if (strlen($xmlSection) == 0)
            {
                fclose($_fileHandle);
                fclose($_tempFileHandle);
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "template file missing, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            
            // feed report head info
            $tmpReportInfo = array("", "", "", "",
                                   "", "", "", "",
                                   "", "", "", "",
                                   "", "", "", "",
                                   "", "", "", "",
                                   "", "", "", "",
                                   "", "", "", "",
                                   "", "", "", "");
            for ($i = 0; $i < $historyBatchMaxNum; $i++)
            {
                $validResultPos = $_resultPos;
                if ($i < count($resultIDList))
                {
                    // resultPos may be different for former batches
                    $cardNameKeys = array_keys($cardNameList[$i], $_tmpCardName);
                    $sysNameKeys = array_keys($sysNameList[$i], $_tmpSysName);
                    $commonKeys = array_intersect($cardNameKeys, $sysNameKeys);
                    if (count($commonKeys) == 0)
                    {
                        continue;
                    }
                    $tmpKeys = array();
                    foreach ($commonKeys as $tmpKey)
                    {
                        array_push($tmpKeys, $tmpKey);
                    }
                    //$validResultPos = intval($tmpKeys[0]);
                }
                if (($i              < count($resultIDList)) &&
                    ($validResultPos < count($mainLineNameList[$i])))
                {
                    // loop all comparison batches of one umd
                    $tmpReportInfo[$historyBatchMaxNum * 0 + $i] = $mainLineNameList[$i][$validResultPos];
                    $tmpReportInfo[$historyBatchMaxNum * 1 + $i] = substr($resultTimeList[$i][$validResultPos], 0, 10);
                    $tmpReportInfo[$historyBatchMaxNum * 2 + $i] = substr($resultTimeList[$i][$validResultPos], 11);
                    $tmpReportInfo[$historyBatchMaxNum * 3 + $i] = $sClockNameList[$i][$validResultPos];
                    $tmpReportInfo[$historyBatchMaxNum * 4 + $i] = $mClockNameList[$i][$validResultPos];
                    $tmpReportInfo[$historyBatchMaxNum * 5 + $i] = $gpuMemNameList[$i][$validResultPos];
                    $tmpReportInfo[$historyBatchMaxNum * 6 + $i] = $cpuNameList[$i][$validResultPos];
                    $tmpReportInfo[$historyBatchMaxNum * 7 + $i] = "CL#" . $changeListNumList[$i][$validResultPos];
                }
            }
            
            //$returnMsg["tmpReportInfo"] = $tmpReportInfo;
            //$returnMsg["mainLineNameList"] = $mainLineNameList;
            
            $t3 = "<Column ss:Index=\"" . ($subjectNameFilterNumMax + 3) . 
                  "\" ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"120\"/>\n" .
                  "<Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"50\"/>\n" .
                  "<Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"120\"/>\n" .
                  "<Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"50\"/>\n" .
                  "<Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"120\"/>\n" .
                  "<Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"50\"/>\n" .
                  "<Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"120\"/>\n";
            
            $t1 = sprintf($xmlSection, $_tmpUmdName, $startSheetLineNum, $t3,
                          $subjectNameFilterNumMax - 0,
                          $subjectNameFilterNumMax - 0,
                          $tmpReportInfo[0], $tmpReportInfo[1], $tmpReportInfo[2], $tmpReportInfo[3],
                          $subjectNameFilterNumMax - 0,
                          $subjectNameFilterNumMax - 0,
                          $tmpReportInfo[4], $tmpReportInfo[5], $tmpReportInfo[6], $tmpReportInfo[7],
                          $subjectNameFilterNumMax - 0,
                          $tmpReportInfo[8], $tmpReportInfo[9], $tmpReportInfo[10], $tmpReportInfo[11],
                          $subjectNameFilterNumMax - 0,
                          $tmpReportInfo[12], $tmpReportInfo[13], $tmpReportInfo[14], $tmpReportInfo[15],
                          $subjectNameFilterNumMax - 0,
                          $tmpReportInfo[16], $tmpReportInfo[17], $tmpReportInfo[18], $tmpReportInfo[19],
                          $subjectNameFilterNumMax - 0,
                          $tmpReportInfo[20], $tmpReportInfo[21], $tmpReportInfo[22], $tmpReportInfo[23],
                          $subjectNameFilterNumMax - 0,
                          $tmpReportInfo[24], $tmpReportInfo[25], $tmpReportInfo[26], $tmpReportInfo[27],
                          $subjectNameFilterNumMax - 0,
                          $subjectNameFilterNumMax - 0,
                          $tmpReportInfo[28], $tmpReportInfo[29], $tmpReportInfo[30], $tmpReportInfo[31]);
                          
            $t2 = sprintf("\"%010d\"", $startSheetLineNum);
            $n1 = strpos($t1, $t2);
            if ($n1 === false)
            {
                fclose($_fileHandle);
                fclose($_tempFileHandle);
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "template file content invalid, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            // line num pos - strlen("\"")
            $lineNumPos = ftell($_fileHandle) + $n1 + 1;
            fwrite($_fileHandle, $t1);
        }

        $returnSet = array();
        $returnSet["lineNumPos"] = $lineNumPos;
        return $returnSet;
    }
    
	public function testTempChange1($_curTestName)
	{
        global $cmpCardName;
        
        $t1 = strtolower($cmpCardName);
        if (strpos($t1, "gt") === false)
        {
            return false;
        }
        $t1 = strtolower($_curTestName);
        if (strcmp($t1, strtolower("CSFillRate")) == 0)
        {
            return true;
        }
        if (strcmp($t1, strtolower("CsMandelbrot")) == 0)
        {
            return true;
        }
        return false;
    }
    
	public function testTempChange2($_curTestName)
	{
        global $curCardName;
        
        $t1 = strtolower($curCardName);
        if (strpos($t1, "gt") === false)
        {
            return false;
        }
        $t1 = strtolower($_curTestName);
        if (strcmp($t1, strtolower("CSFillRate")) == 0)
        {
            return true;
        }
        if (strcmp($t1, strtolower("CsMandelbrot")) == 0)
        {
            return true;
        }
        return false;
    }
    
	public function genAverageDataForGraph($_isCompStandard, $_cmpMachineID,
                                           $_curCardName, $_cmpCardName, $_graphCells,
                                           $_curSysName, $_cmpSysName)
	{
        global $returnMsg;
        global $testNameList;
        global $subTestUmdDataMaskList;
        global $reportUmdNum;
        global $subTestNumList;
        global $subTestNumMap;
        global $umdOrder;
        global $resultUmdOrder;
        global $umdNameList;
        global $subjectNameFilterNumMax;
        global $dataColumnNum;
        global $swtReportInfo;
        global $swtReportUmdInfo;
        global $crossType;
        global $curMachineName;
        global $cmpMachineName;

        $graphCells = $_graphCells;
        // columns have values (if true, not blank in excel table)
        //$averageColumnHasVal = array(false, false, false, false, false, false);
        $averageColumnHasVal = array_fill(0, $reportUmdNum * 2, false);
        
        $tmpReportUmdInfo = $swtReportUmdInfo;
        
        //if (false)
        if ($_isCompStandard)
        {
            // generate average data for graph
            $t1 = "";
            if ($_cmpMachineID != -1)
            {
                $tmpList1 = explode(" ", $_curSysName);
                $tmpList2 = explode(" ", $_cmpSysName);
                $addCurCardSysName = $_curCardName == $_cmpCardName ? "-" . $tmpList1[0] : "";
                $addCmpCardSysName = $_curCardName == $_cmpCardName ? "-" . $tmpList2[0] : "";
                
                {
                    $addCurCardSysName = $_curCardName . $addCurCardSysName;
                    $addCmpCardSysName = $_cmpCardName . $addCmpCardSysName;
                }
                
                $tmpAverageDataCode = "";
                $tmpCompAverageDataCode = "";
                $tmpGraphDataCode = "";
                
                $graphDataColumnNum = intval($dataColumnNum / 3);
                
                $isFirstColumn = true;
                for ($i = 0; $i < $reportUmdNum; $i++)
                {
                    if (($resultUmdOrder[$i] == -1) ||
                        ($resultUmdOrder[$reportUmdNum + $i] == -1))
                    {
                        // absent api
                        continue;
                    }
                    
                    $tmpIndexCode = "";
                    if ($isFirstColumn)
                    {
                        $tmpAverageDataCode = " <Cell ss:Index=\"" . ($subjectNameFilterNumMax + 3 + $dataColumnNum + 1) . 
                                              "\" ss:StyleID=\"Default\"/>\n";
                        $tmpIndexCode = "ss:Index=\"" . ($subjectNameFilterNumMax + 3 + $dataColumnNum + 1 + 2 + $graphDataColumnNum) . "\"";
                        $tmpGraphDataCode = " <Cell ss:Index=\"" . 
                                            ($subjectNameFilterNumMax + 3 + $dataColumnNum + 1 + 2 + $graphDataColumnNum * 2 + 1) . 
                                            "\" ss:StyleID=\"Default\"/>\n";
                        $isFirstColumn = false;
                    }
                    
                    $tmpAverageDataCode .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" . ($tmpReportUmdInfo[$i]) . "-" . 
                                           $addCurCardSysName . "</Data></Cell>\n";
                    $tmpCompAverageDataCode .= " <Cell " . $tmpIndexCode .
                                               " ss:StyleID=\"Default\"><Data ss:Type=\"String\">" . ($tmpReportUmdInfo[$i]) . "-" . 
                                               $addCmpCardSysName . "</Data></Cell>\n";
                    $tmpGraphDataCode .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" . ($tmpReportUmdInfo[$i]) . "-" . 
                                         $addCurCardSysName . "</Data></Cell>\n" .
                                         " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" . ($tmpReportUmdInfo[$i]) . "-" . 
                                         $addCmpCardSysName . "</Data></Cell>\n";
                }
                
                $t1 = $tmpAverageDataCode . $tmpCompAverageDataCode . $tmpGraphDataCode;
            }
            else
            {
                // if no comparison
                
                $graphDataColumnNum = 0;
                if ($dataColumnNum == 1)
                {
                    $graphDataColumnNum = 1;
                }
                else
                {
                    $graphDataColumnNum = intval($dataColumnNum / 2);
                }
                
                $tmpAverageDataCode = "";
                $tmpGraphDataCode = "";
                
                $isFirstColumn = true;
                for ($i = 0; $i < $reportUmdNum; $i++)
                {
                    if ($resultUmdOrder[$i] == -1)
                    {
                        // absent api
                        continue;
                    }
                    if ($isFirstColumn)
                    {
                        $tmpAverageDataCode = " <Cell ss:Index=\"" . 
                                              ($subjectNameFilterNumMax + 3 + $dataColumnNum + 1) . 
                                              "\" ss:StyleID=\"Default\"/>\n";
                        $tmpGraphDataCode = " <Cell ss:Index=\"" . 
                                            ($subjectNameFilterNumMax + 3 + $dataColumnNum + 1 + $graphDataColumnNum + 2) . 
                                            "\" ss:StyleID=\"Default\"/>\n";
                        $isFirstColumn = false;
                    }
                    $tmpAverageDataCode .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" . ($tmpReportUmdInfo[$i]) . 
                                           "</Data></Cell>\n";
                    $tmpGraphDataCode .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" . ($tmpReportUmdInfo[$i]) . 
                                         "</Data></Cell>\n";
                }
                
                $t1 = $tmpAverageDataCode . $tmpGraphDataCode;

            }
            array_push($graphCells, $t1);
            
            //$returnMsg["testNameList"] = $testNameList;
            //$returnMsg["subTestNumList"] = $subTestNumList;
            $n1 = -1;
            //$returnMsg["subTestNumMap"] = $subTestNumMap;
            
            for ($i = 0; $i < count($testNameList); $i++)
            {
                if (isset($subTestNumMap[$testNameList[$i]]) == false)
                {
                    continue;
                }
                if (intval($subTestNumMap[$testNameList[$i]]) == 0)
                {
                    continue;
                }

                $n2 = $n1 + $subTestNumList[$i] - 1;

                $t1 = "";
                if ($_cmpMachineID != -1)
                {
                    // if comparison with other cards
                    $reportUmdNum = count($umdNameList);
                    $tmpVal = array_fill(0, $reportUmdNum * 2, "");
                    $tmpValHas = array_fill(0, $reportUmdNum * 2, "");
                    $startIndex = -1;
                    
                    $graphDataColumnNum = intval($dataColumnNum / 3);
                    
                    $isFirstColumn = true;
                    $tmpColumnNum = 0;
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        if (($resultUmdOrder[$k] == -1) ||
                            ($resultUmdOrder[$reportUmdNum + $k] == -1))
                        {
                            // absent api
                            continue;
                        }
                        
                        $tmpIndexCode = "";
                        if ($isFirstColumn)
                        {
                            $tmpIndexCode = "ss:Index=\"" . ($subjectNameFilterNumMax + 3 + $dataColumnNum + 1 + 2 + $graphDataColumnNum) . "\"";
                            $isFirstColumn = false;
                        }
                        $tmpValHas[$k] = " <Cell ss:StyleID=\"Default\" " .
                                        "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C" . ($subjectNameFilterNumMax + 3 + $tmpColumnNum * 3) .
                                        ":R[" . $n2 . "]C" . ($subjectNameFilterNumMax + 3 + $tmpColumnNum * 3) . 
                                        ")\">" .
                                        "<Data ss:Type=\"Number\"></Data></Cell>\n";
                                        
                        $tmpValHas[$reportUmdNum + $k] = " <Cell " . ($tmpIndexCode) . " ss:StyleID=\"Default\" " .
                                                         "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C" . ($subjectNameFilterNumMax + 5 + $tmpColumnNum * 3) .
                                                         ":R[" . $n2 . "]C" . ($subjectNameFilterNumMax + 5 + $tmpColumnNum * 3) . 
                                                         ")\">" .
                                                         "<Data ss:Type=\"Number\"></Data></Cell>\n";
                                                         
                        $tmpColumnNum++;
                    }
                    
                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        $tmpVal[$j] = "";
                        if ($j == 0)
                        {
                            // spaces between cur card & cmp card
                            $tmpVal[$reportUmdNum + $j] = "<Cell ss:StyleID=\"Default\"></Cell>\n";
                        }
                        else
                        {
                            $tmpVal[$reportUmdNum + $j] = "";
                        }
                        // cur card
                        if ($resultUmdOrder[$j] != -1)
                        {
                            $tmpVal[$j] = $tmpValHas[$j];
                        }
                        
                        // cmp card
                        if ($resultUmdOrder[$reportUmdNum + $j] != -1)
                        {
                            $tmpVal[$reportUmdNum + $j] = $tmpValHas[$reportUmdNum + $j];
                            if ($startIndex == -1)
                            {
                                $startIndex = 0;
                                $tmpMask = $subTestUmdDataMaskList[$i];
                                $checkMask = 1;
                                for ($l = 0; $l < $reportUmdNum; $l++)
                                {
                                    if (($resultUmdOrder[$l] == -1) ||
                                        ($resultUmdOrder[$reportUmdNum + $l] == -1))
                                    {
                                        $checkMask *= 10;
                                    }
                                    else
                                    {
                                        break;
                                    }
                                }
                                $tmpAdd = 0;
                                for ($l = 0; $l < $reportUmdNum; $l++)
                                {
                                    if ($tmpMask & $checkMask)
                                    {
                                        // jump to DX12, if DX11 is null, jump to vulkan if DX12 is null in this test
                                        $startIndex += $tmpAdd;
                                        break;
                                    }
                                    $tmpAdd++;
                                    $checkMask *= 10;
                                }
                            }
                        }
                    }
                        
                    $t1 = " <Cell ss:Index=\"" . ($subjectNameFilterNumMax + 3 + $dataColumnNum + 1) . 
                          "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                          $testNameList[$i] . "</Data></Cell>\n";
                          
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        $j = $k * 2;
                        $t1 .= $tmpVal[$j];
                        $t1 .= $tmpVal[$j + 1];
                    }
                           
                    $tmpColumnNum = 0;
                    
                    $tmpValHas2 = "<Cell ss:StyleID=\"Default\"></Cell>\n";
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        if (($resultUmdOrder[$k] == -1) ||
                            ($resultUmdOrder[$reportUmdNum + $k] == -1))
                        {
                            // absent api
                            continue;
                        }
                        $j = $k * 2;
                        $tmpValHas[$j] = " <Cell ss:StyleID=\"Default\" " .
                                         "ss:Formula=\"=RC[-" . (3 + $graphDataColumnNum * 2 + $tmpColumnNum) . "]/RC[-" . 
                                         (3 + $graphDataColumnNum * 2 - $startIndex + $tmpColumnNum * 2) .
                                         "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                        $tmpValHas[$j + 1] = " <Cell ss:StyleID=\"Default\" " .
                                             "ss:Formula=\"=RC[-" . (3 + $graphDataColumnNum + $tmpColumnNum) . "]/RC[-" . 
                                             (3 + $graphDataColumnNum * 2 - $startIndex + $tmpColumnNum * 2 + 1) .
                                             "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                                        
                        // replace NV card DX12 into DX11
                        if (($this->testTempChange1($testNameList[$i])                     == true) &&
                            (strcmp(strtolower($tmpReportUmdInfo[$k]), strtolower("DX12")) == 0))
                        {
                            $tmpValHas[$j + 1] = " <Cell ss:StyleID=\"Default\" " .
                                                 "ss:Formula=\"=RC[-" . (3 + $graphDataColumnNum + $tmpColumnNum + 1) . "]/RC[-" . 
                                                 (3 + $graphDataColumnNum * 2 - $startIndex + $tmpColumnNum * 2 + 1) .
                                                 "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";

                            //$returnMsg["tmp_checkCell"] = $tmpValHas[$j + 1];
                        }
                        //$returnMsg["tmpReportUmdInfo"] = $tmpReportUmdInfo;
                        //$returnMsg["tmpReportUmdInfo_" . $k] = $tmpReportUmdInfo[$k];
                        //$returnMsg["testNameList_" . $i] = $testNameList[$i];
                        //$returnMsg["tmp_check1"] = $this->testTempChange1($testNameList[$i]) ? 112233 : 223344;
                        //$returnMsg["tmp_check2"] = $tmpReportUmdInfo[$k];
                                        
                        $tmpColumnNum++;
                    }
                    
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        $j = $k * 2;
                        $tmpVal[$j] = "";
                        $tmpVal[$j + 1] = "";
                        // cur card
                        if (($resultUmdOrder[$k] != -1) &&
                            ($resultUmdOrder[$k + $reportUmdNum] != -1))
                        {
                            // this column has data, needs be shown in Graph
                            $averageColumnHasVal[$j] = true;
                            
                            $tmpVal[$j] = $tmpValHas[$j];
                        }
                        
                        // cmp card
                        if (($resultUmdOrder[$k] != -1) &&
                            ($resultUmdOrder[$k + $reportUmdNum] != -1))
                        {
                            // this column has data, needs be shown in Graph
                            $averageColumnHasVal[$j + 1] = true;
                            
                            $tmpVal[$j + 1] = $tmpValHas[$j + 1];
                        }
                    }
                    
                    $t1 .= " <Cell ss:Index=\"" . 
                           ($subjectNameFilterNumMax + 3 + $dataColumnNum + 1 + 2 + $graphDataColumnNum * 2 + 1) . 
                           "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                           $testNameList[$i] . "</Data></Cell>\n";
                           
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        $j = $k * 2;
                        $t1 .= $tmpVal[$j];
                        $t1 .= $tmpVal[$j + 1];
                    }
                           
                }
                else
                {
                    // if no comparison
                    $reportUmdNum = count($umdNameList);
                    $tmpVal = array_fill(0, $reportUmdNum, "");
                    $tmpValHas = array_fill(0, $reportUmdNum, "");
                    $startIndex = -1;
                    
                    if ($dataColumnNum == 1)
                    {
                        $graphDataColumnNum = 1;
                    }
                    else
                    {
                        $graphDataColumnNum = intval($dataColumnNum / 2);
                    }
                    
                    $isFirstColumn = true;
                    $tmpColumnNum = 0;
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        if ($resultUmdOrder[$k] == -1)
                        {
                            // absent api
                            continue;
                        }
                        $tmpValHas[$k] = " <Cell ss:StyleID=\"Default\" " .
                                         "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C" . ($subjectNameFilterNumMax + 3 + $tmpColumnNum * 2) . // 6
                                         ":R[" . $n2 . "]C" . ($subjectNameFilterNumMax + 3 + $tmpColumnNum * 2) . 
                                         ")\">" .
                                         "<Data ss:Type=\"Number\"></Data></Cell>\n";
                        
                        $tmpColumnNum++;
                    }
                    
                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        $tmpVal[$j] = "";
                    }
                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        if ($resultUmdOrder[$j] != -1)
                        {
                            $tmpVal[$j] = $tmpValHas[$j];

                            if ($startIndex == -1)
                            {
                                $startIndex = 0;
                                $tmpMask = $subTestUmdDataMaskList[$i];
                                $checkMask = 1;
                                for ($l = 0; $l < $reportUmdNum; $l++)
                                {
                                    if ($resultUmdOrder[$l] == -1)
                                    {
                                        $checkMask *= 10;
                                    }
                                    else
                                    {
                                        break;
                                    }
                                }
                                $tmpAdd = 0;
                                for ($l = 0; $l < $reportUmdNum; $l++)
                                {
                                    if ($tmpMask & $checkMask)
                                    {
                                        // jump to DX12, if DX11 is null, jump to vulkan if DX12 is null in this test
                                        $startIndex += $tmpAdd;
                                        break;
                                    }
                                    $tmpAdd++;
                                    $checkMask *= 10;
                                }
                            }
                        }
                        else
                        {
                            $tmpVal[$j] = "";
                        }
                    }

                    $t1 = " <Cell ss:Index=\"" . ($subjectNameFilterNumMax + 3 + $dataColumnNum + 1) . 
                          "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                          $testNameList[$i] . "</Data></Cell>\n";
                          
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        $t1 .= $tmpVal[$k];
                    }
                    
                    $tmpColumnNum = 0;
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        $tmpValHas[$k] = "";
                        if ($resultUmdOrder[$k] == -1)
                        {
                            // absent api
                            continue;
                        }
                        
                        $tmpValHas[$k] = " <Cell ss:StyleID=\"Default\" " .
                                        "ss:Formula=\"=RC[-" . (2 + $graphDataColumnNum) . "]/RC[-" . 
                                        (2 + $graphDataColumnNum - $startIndex + $tmpColumnNum) .
                                        "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                        
                        // replace NV card DX12 into DX11
                        if (($this->testTempChange2($testNameList[$i])                     == true) &&
                            (strcmp(strtolower($tmpReportUmdInfo[$k]), strtolower("DX12")) == 0))
                        {
                            $tmpValHas[$k] = " <Cell ss:StyleID=\"Default\" " .
                                            "ss:Formula=\"=RC[-" . (2 + $graphDataColumnNum + 1) . "]/RC[-" . 
                                            (2 + $graphDataColumnNum - $startIndex + $tmpColumnNum) .
                                            "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                        }
                        
                        $tmpColumnNum++;
                    }

                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        if ($resultUmdOrder[$j] != -1)
                        {
                            $tmpVal[$j] = $tmpValHas[$j];
                        }
                        else
                        {
                            $tmpVal[$j] = "";
                        }
                    }
                    
                    $t1 .= " <Cell ss:Index=\"" . 
                           ($subjectNameFilterNumMax + 3 + $dataColumnNum + 1 + $graphDataColumnNum + 2) . 
                           "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                           $testNameList[$i] . "</Data></Cell>\n";
                           
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        $t1 .= $tmpVal[$k];
                    }

                }
                array_push($graphCells, $t1);
                $n1 = $n2 + 2;
            }
            //$returnMsg["graphCells"] = $graphCells;
        }

        $returnSet = array();
        $returnSet["graphCells"] = $graphCells;
        $returnSet["resultUmdOrder"] = $resultUmdOrder;
        $returnSet["averageColumnHasVal"] = $averageColumnHasVal;
        return $returnSet;
    }
    
	public function checkStartTest($_fileHandle, $_tempFileHandle,
                                   $_curTestPos, $_curTestName, $_testSubjectNameList, $_unitSubject,
                                   $_isCompStandard, $_cmpMachineID, $_sheetLinePos)
	{
        global $returnMsg;
        global $startStyleID;
        global $graphCells;
        global $testNameList;
        global $subjectNameList;
        global $subjectNameFilterNumMax;
        global $startGraphDataLinePos;
        global $dataColumnNum;
        global $crossType;

        //$lineNum = $_lineNum;
        $sheetLinePos = $_sheetLinePos;
        //$tempLineNum = $_tempLineNum;
        {
            
            $tmpList = array_fill(0, ($subjectNameFilterNumMax + 1), " <Cell ss:StyleID=\"s" . ($startStyleID + 3) . "\"/>\n");
            $tmpList2 = array_fill(0, ($subjectNameFilterNumMax + 1), " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n");
            
            
            $tmpList[0] = " <Cell ss:StyleID=\"s" . ($startStyleID + 3) . "\"><Data ss:Type=\"String\">" .
                           "TestCaseID</Data></Cell>\n";
            for ($i = 0; $i < count($_testSubjectNameList); $i++)
            {
                $tmpList[$i + 1] = " <Cell ss:StyleID=\"s" . ($startStyleID + 3) . "\"><Data ss:Type=\"String\">" .
                               "" . $_testSubjectNameList[$i] . "</Data></Cell>\n";
            }
            $tmpCode = implode("", $tmpList);
            $tmpCode2 = implode("", $tmpList2);
            
            if ($_isCompStandard)
            {
                // write comparison to tmp file
                // start of each test
                // black bar & test subject bar

                $t1 = "<Row ss:StyleID=\"Default\">" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                      $tmpCode2;
                      
                for ($i = 0; $i < $dataColumnNum; $i++)
                {
                    $t1 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n";
                }

                if (count($graphCells) > 0)
                {
                    $n2 = $sheetLinePos - $startGraphDataLinePos;
                    if (($n2 >= 0) &&
                        ($n2 <  count($graphCells)))
                    {
                        // add average data for graph to right
                        // this is for each test start bar (2 lines)
                        $t1 .= $graphCells[$n2];
                    }
                }
                $t1 .= "</Row>\n";
                $sheetLinePos++;
                       
                $t3 = "<Row>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 2) . "\"><Data ss:Type=\"String\">" .
                      "" . $_curTestName . "</Data></Cell>\n" .
                      $tmpCode;
                       
                if ($dataColumnNum == 1)
                {
                    $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                           "" . $_unitSubject . "</Data></Cell>\n";
                }
                else
                {
                    for ($i = 0; $i < ($dataColumnNum / 2); $i++)
                    {
                        $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                               "" . $_unitSubject . "</Data></Cell>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n";
                    }
                }

                if ($_cmpMachineID != -1)
                {
                    // if comparison with other cards
                    $t3 = "<Row>\n" .
                          " <Cell ss:StyleID=\"s" . ($startStyleID + 2) . "\"><Data ss:Type=\"String\">" .
                          "" . $_curTestName . "</Data></Cell>\n" .
                          $tmpCode;
                    
                    for ($i = 0; $i < ($dataColumnNum / 3); $i++)
                    {
                        $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                               "" . $_unitSubject . "</Data></Cell>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                               "" . $_unitSubject . "</Data></Cell>\n";
                    }
                    
                }
                $t1 .= $t3;
                if (count($graphCells) > 0)
                {
                    $n2 = $sheetLinePos - $startGraphDataLinePos;
                    if (($n2 >= 0) &&
                        ($n2 <  count($graphCells)))
                    {
                        // add average data for graph to right
                        // this is for each test start bar (2 lines)
                        $t1 .= $graphCells[$n2];
                    }
                }
                $t1 .= "</Row>\n";
                $sheetLinePos++;
                
                fwrite($_tempFileHandle, $t1);
                //$tempLineNum += 2;
            }
        }

        $returnSet = array();
        $returnSet["sheetLinePos"] = $sheetLinePos;
        return $returnSet;
    }
    
	public function getHistoryResultIDList($_resultPos)
	{
        global $returnMsg;
        global $resultIDList;
        global $cardNameList;
        global $sysNameList;
        global $driverNameList;
        global $swtOldUmdNameMatchList;
		global $reportUmdNum;
        
        $historyResultIDList = array();
        for ($i = 1; $i < count($resultIDList); $i++)
        {
            $cardNameKeys = array_keys($cardNameList[$i], $cardNameList[0][$_resultPos]);
            $sysNameKeys = array_keys($sysNameList[$i], $sysNameList[0][$_resultPos]);
            $driverNameKeys = array_keys($driverNameList[$i], $driverNameList[0][$_resultPos]);
            $returnMsg["enter01"] = 1;
			$tmpMachineNum = intval(count($driverNameList[$i]) / $reportUmdNum);
            if (count($driverNameKeys) < $tmpMachineNum)
            {
                $returnMsg["enter02"] = 2;
                $tmpCount = intval(count($swtOldUmdNameMatchList) / 2);
                for ($j = 0; $j < $tmpCount; $j++)
                {
                    if (strcmp($swtOldUmdNameMatchList[$j * 2], $driverNameList[0][$_resultPos]) == 0)
                    {
                        $tmpKeys = array_keys($driverNameList[$i], $swtOldUmdNameMatchList[$j * 2 + 1]);
                        if (count($tmpKeys) > 0)
                        {
							foreach ($tmpKeys as $tmpVal)
							{
								$tmpPos = array_search($tmpVal, $driverNameKeys);
								if ($tmpPos === false)
								{
									array_push($driverNameKeys, $tmpVal);
								}
							}
							
							//$returnMsg["enter03"] = 3;
							//$returnMsg["driverNameKeys"] = $driverNameKeys;
                            //break;
							if (count($driverNameKeys) >= $tmpMachineNum)
							{
								break;
							}
                        }
                    }
                }
            }
            $commonKeys1 = array_intersect($cardNameKeys, $sysNameKeys);
            $commonKeys2 = array_intersect($cardNameKeys, $driverNameKeys);
            $commonKeys3 = array_intersect($commonKeys1, $commonKeys2);
            $commonKeys = array();
            foreach ($commonKeys3 as $tmpVal)
            {
                array_push($commonKeys, $tmpVal);
            }
            if (($commonKeys        == false) ||
                (count($commonKeys) == 0))
            {
                array_push($historyResultIDList, PHP_INT_MAX);
            }
            else
            {
                //$returnMsg["commonKeys"] = $commonKeys;
                //$returnMsg["resultIDList"] = $resultIDList[$i];
                array_push($historyResultIDList, $resultIDList[$i][$commonKeys[0]]);
            }
        }

        $returnSet = array();
        $returnSet["historyResultIDList"] = $historyResultIDList;
		//$returnMsg["historyResultIDList"] = $historyResultIDList;
        return $returnSet;
    }
    
	public function writeReportData($_db, $_fileHandle, $_tempFileHandle,
                                    $_resultPos, $_nextSubTestPos,
                                    $_isCompStandard,
                                    $_lineNum)
	{
        global $returnMsg;
        global $startStyleID;
        global $resultIDList;
        global $historyResultIDList;
        global $tableName01;
        global $testName;
        global $maxSubTestNumOnce;
        global $subTestNumList;
        //global $cmpSubTestNumList;
        //global $skipTestNameList;
        global $subjectNameFilterNumMax;

        $db = $_db;
        $lineNum = $_lineNum;
        $nextSubTestPos = $_nextSubTestPos;
        
        $t1 = "";
        $tmpList = array();
        for ($i = 0; $i < count($historyResultIDList); $i++)
        {
            $t2 = "t" . (2 + $i);
            
            array_push($tmpList, $t2 . ".data_value");
            $t1 .= "LEFT JOIN " . $tableName01 . " " . $t2 . " " .
                   "ON (" . $t2 . ".result_id=? AND t0.sub_id=" . $t2 . ".sub_id) ";
        }
        $t3 = implode(",", $tmpList);
        array_push($historyResultIDList, $resultIDList[0][$_resultPos]);
        
        if (strlen($t3) > 0)
        {
            $t3 = ", " . $t3;
        }
        
        // save this test to report
        // following line has no error, many question marks
        $params1 = $historyResultIDList;
        //array($resultIDList[0][$resultPos]);
        $sql1 = "SELECT t0.sub_id, t0.data_value, t0.test_case_id, " .
                "(SELECT t1.test_name FROM mis_table_test_info t1 WHERE t1.test_id=t0.sub_id) AS subTestName, " .
                "(SELECT t100.test_filter FROM mis_table_test_info t100 WHERE t100.test_id=t0.sub_id) AS subTestFilterName " .
                "" . $t3 . " " .
                "FROM " . $tableName01 . " t0 " .
                "" . $t1 . " " .
                "WHERE t0.result_id=? ORDER BY t0.data_id ASC LIMIT " . $nextSubTestPos . ", " . $maxSubTestNumOnce;
        if ($db->QueryDB($sql1, $params1) == null)
        {
            fclose($_fileHandle);
            fclose($_tempFileHandle);
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            //$returnMsg["historyResultIDList"] = $historyResultIDList;
            //$returnMsg["t3"] = $t3;
            //$returnMsg["t1"] = $t1;
            echo json_encode($returnMsg);
            return null;
        }
        
        $dataNum = 0;
        $t1 = "";
        $standardSubTestIDList = array();
        $standardSubTestNameList = array();
        $standardSubTestFilterNameList = array();
        $standardTestCaseIDList = array();
        $tmpDataList = array("", "", "", "", "");

        while ($row1 = $db->fetchRow())
        {
            $dataValueXML = "";
            $tmpDataListXML = array("", "", "", "", "");
            
            $subTestID = $row1[0];
            $subTestName = $row1[3];
            $subTestFilterName = $row1[3];
            $subTestFilterNameList = explode("|", str_replace("\"", "", $row1[4]));
            $dataValue = "" . $row1[1];
            $testCaseID = $row1[2];
            if (strlen($dataValue) > 0)
            {
                $dataValueXML = "<Data ss:Type=\"Number\">" . $dataValue . "</Data>";
            }
            
            for ($i = 1; $i < count($resultIDList); $i++)
            {
                $n1 = 5 + $i - 1;
                if ($n1 < count($row1))
                {
                    $tmpDataList[$i - 1] = "" . $row1[$n1];
                    
                    if (strlen($tmpDataList[$i - 1]) > 0)
                    {
                        $tmpDataListXML[$i - 1] = "<Data ss:Type=\"Number\">" . $tmpDataList[$i - 1] . "</Data>";
                    }
                }
            }
            
            $dataNum++;
            
            if ((strlen($subTestName) == 0) ||
                (strlen($dataValue)   == 0))
            {
                // if invalid subtest
                continue;
            }
            
            $tmpList = array_fill(0, ($subjectNameFilterNumMax + 1), " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"/>\n");
            
            $tmpList[0] = " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"><Data ss:Type=\"Number\">" .
                           $testCaseID .
                           "</Data></Cell>\n";
            for ($i = 0; $i < count($subTestFilterNameList); $i++)
            {
                $tmpList[$i + 1] = " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"><Data ss:Type=\"String\">" .
                               $subTestFilterNameList[$i] .
                               "</Data></Cell>\n";
            }
            $tmpCode = implode("", $tmpList);
            
            // api sheet comparison
            $t1 .= "<Row ss:StyleID=\"Default\">\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 8) . "\"><Data ss:Type=\"String\">" . $testName . "</Data></Cell>\n" .
                   //" <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"><Data ss:Type=\"String\">" . $subTestName . "</Data></Cell>\n" .
                   $tmpCode .
                   //" <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                   //" <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                   //" <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" . $dataValueXML . "</Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                   "ss:Formula=\"=(RC" . ($subjectNameFilterNumMax + 3) . // 6
                   "-RC" . ($subjectNameFilterNumMax + 5) . // 8
                   ")/RC" . ($subjectNameFilterNumMax + 5) . "\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" . $tmpDataListXML[0] . "</Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                   "ss:Formula=\"=(RC" . ($subjectNameFilterNumMax + 5) . // 8
                   "-RC" . ($subjectNameFilterNumMax + 7) . // 10
                   ")/RC" . ($subjectNameFilterNumMax + 7) . "\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" . $tmpDataListXML[1] . "</Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                   "ss:Formula=\"=(RC" . ($subjectNameFilterNumMax + 7) . // 10
                   "-RC" . ($subjectNameFilterNumMax + 9) . // 12
                   ")/RC" . ($subjectNameFilterNumMax + 9) . "\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" . $tmpDataListXML[2] . "</Cell>\n" .
                   "</Row>\n";

            $lineNum++;

            if ($_isCompStandard)
            {
                //$tmpPos = array_search($testName, );
                array_push($standardSubTestIDList, $subTestID);
                array_push($standardSubTestNameList, $subTestName);
                array_push($standardSubTestFilterNameList, $subTestFilterNameList);
                array_push($standardTestCaseIDList, $testCaseID);
            }
        }
        
        fwrite($_fileHandle, $t1);

        $nextSubTestPos += $dataNum;

        $returnSet = array();
        $returnSet["nextSubTestPos"] = $nextSubTestPos;
        $returnSet["lineNum"] = $lineNum;
        $returnSet["standardSubTestIDList"] = $standardSubTestIDList;
        $returnSet["standardSubTestNameList"] = $standardSubTestNameList;
        $returnSet["standardSubTestFilterNameList"] = $standardSubTestFilterNameList;
        $returnSet["standardTestCaseIDList"] = $standardTestCaseIDList;
        return $returnSet;
    }
    
	public function testWriteFlatDataReportHead($_curTestPos,
                                                $_curTestName,
                                                $_tmpFileName1)
	{
        global $returnMsg;
        global $templateFileName0;
        global $templateFileName1;
        global $templateFileName3;
        global $isProduceFlatData;
        
        if ($_curTestPos == 0)
        {
            // add sheet head to tmp file
            $fileHandle = fopen($_tmpFileName1, "w");
            
            // report head
            $t1 = file_get_contents($templateFileName0);
            fwrite($fileHandle, $t1);
            // style end tag
            $this->writeAdditionalStyles($fileHandle);
            
            fclose($fileHandle);
        }
        
        $fileHandle = fopen($_tmpFileName1, "r+");
        fseek($fileHandle, 0, SEEK_END);
        
        // add new sheet
        $t1 = file_get_contents($templateFileName3);
        $t1 = sprintf($t1, $_curTestName);
        fwrite($fileHandle, $t1);
        
        fclose($fileHandle);
    }
    
	public function testWriteFlatDataReportEnd($_curTestPos,
                                               $_testNum,
                                               $_tmpFileName1)
	{
        global $returnMsg;
        global $allSheetsEndTag;
        global $templateFileName4;

        $fileHandle = fopen($_tmpFileName1, "r+");
        fseek($fileHandle, 0, SEEK_END);

        //// sheet content
        //// blank line between 2 tests
        //$t1 = "<Row ss:StyleID=\"Default\">\n";
        //$t1 .= "<Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\"></Data></Cell>\n";
        //$t1 .= "</Row>\n";
        //
        //fwrite($fileHandle, $t1);
        
        // end sheet
        $t1 = file_get_contents($templateFileName4);
        fwrite($fileHandle, $t1);

        fclose($fileHandle);
        
        if ($_curTestPos >= $_testNum)
        {
            // add sheet end
            $fileHandle = fopen($_tmpFileName1, "r+");
            fseek($fileHandle, 0, SEEK_END);
            fwrite($fileHandle, $allSheetsEndTag);
            
            fclose($fileHandle);
        }
    }
    
    public function addLinesToFlatData($_tmpTestName,
                                       $_isComp,
                                       $_dataSet,
                                       $_testCaseIDKeyAPI,
                                       $_fileHandleFlatData)
    {
        global $returnMsg;
        global $testCaseIDColumnName;
        global $flatDataBuffer;
        global $curCardName;
        global $curSysName;
        global $cmpCardName;
        global $cmpSysName;
        
        //while($dataSet = fgetcsv($_srcFileHandle, 0, ","))
        $dataSet = $_dataSet;
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
                $tmpSrcTestName = $dataSet[0];
                
                if (strlen($tmpSrcTestName) > 0)
                {
                    // start of a test
                    $isTitleLine = true;
                    
                    if ($_isComp == false)
                    {
                        // base card title
                        array_push($dataSet, "ASIC | Driver");
                        array_push($dataSet, "OS");
                    }
                    //if (strcmp($_tmpTestName, $tmpSrcTestName) != 0)
                    //{
                    //    // test lines end
                    //    if ($_isComp == true)
                    //    {
                    //        // add an empty line at end of a test
                    //        $t1 = "<Row ss:StyleID=\"Default\">\n";
                    //        $t1 .= "<Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\"></Data></Cell>\n";
                    //        $t1 .= "</Row>\n";
                    //        //fwrite($_destFileHandle, $t1);
                    //        $flatDataBuffer .= $t1;
                    //    }
                    //    //break;
                    //    return;
                    //}
                    if ($_isComp == true)
                    {
                        // skip comp card test title line
                        //continue;
                        return;
                    }
                }
                else
                {
                    if ($_isComp == false)
                    {
                        // base card data line
                        $tmpList1 = explode(" ", $curSysName);
                        array_push($dataSet, $curCardName);
                        array_push($dataSet, $tmpList1[0]);
                    }
                    else
                    {
                        // cmp card data line
                        $tmpList1 = explode(" ", $cmpSysName);
                        array_push($dataSet, $cmpCardName);
                        array_push($dataSet, $tmpList1[0]);
                    }
                }
            }
            if ($dataSetSize < 3)
            {
                // skip empty line
                return;
            }
            
            // add lines to dest tmp file
            $t1 = "<Row ss:StyleID=\"Default\">\n";

            $i = 0;
            $j = -1;
            foreach ($dataSet as $tmpData)
            {
                $j++;
                $t2 = $tmpData;
                if (($i != 0) && (strlen($t2) == 0))
                {
                    continue;
                }
                
                if ($j == $_testCaseIDKeyAPI)
                {
                    continue;
                }
                
                $t3 = "";
                
                if (($i                 == 1) &&
                    ($_testCaseIDKeyAPI != -1))
                {
                    // testCaseID column
                    if ($isTitleLine == true)
                    {
                        $t3 = "<Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" . $testCaseIDColumnName . "</Data></Cell>\n";
                    }
                    else
                    {
                        $t3 = "<Cell ss:StyleID=\"Default\"><Data ss:Type=\"Number\">" . $dataSet[$_testCaseIDKeyAPI] . "</Data></Cell>\n";
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
                
                
                $t1 .= $t3;
                
                $i++;
            }
            $t1 .= "</Row>\n";
            //fwrite($_destFileHandle, $t1);
            //$flatDataBuffer .= $t1;
            
            //$fileHandle = fopen($_tmpFileName1, "r+");
            //fseek($fileHandle, 0, SEEK_END);
            fwrite($_fileHandleFlatData, $t1);
            
            //fclose($fileHandle);
        }
    }
    
	public function getCSVLine($_strLine)
	{
        $dataList = array();
        
        $strlineLen = strlen($_strLine);
        
        $i = 0;
        $tmpTag = "";
        while($i < $strlineLen)
        {
            $curChar = $_strLine[$i];
            if (($curChar != ' ') &&
                ($curChar != '\t') &&
                ($curChar != '\n') &&
                ($curChar != '\r') &&
                ($curChar != '\0') &&
                ($curChar != '\x0B'))
            {
                if ($curChar == ',')
                {
                    array_push($dataList, $tmpTag);
                    $tmpTag = "";
                }
                else
                {
                    $tmpTag .= $curChar;
                }
            }
            $i++;
        }
        array_push($dataList, $tmpTag);

        return $dataList;
    }
    
	public function getCSVLine2($_dataField, $_dataFieldIndex)
	{
        $dataList = array();
        
        $strlineLen = strlen($_dataField);
        
        if ($_dataFieldIndex >= $strlineLen)
        {
            return false;
        }
        
        $i = $_dataFieldIndex;
        $tmpTag = "";
        //$reachReturn = false;
        while($i < $strlineLen)
        {
            $curChar = $_dataField[$i++];
            if ($curChar == "\n")
            {
                break;
            }
            
            if (($curChar != " ") &&
                ($curChar != "\t") &&
                ($curChar != "\0") &&
                ($curChar != "\r") &&
                ($curChar != "\x0B"))
            {
                if ($curChar == ",")
                {
                    array_push($dataList, $tmpTag);
                    $tmpTag = "";
                }
                else
                {
                    $tmpTag .= $curChar;
                }
            }
        }
        array_push($dataList, $tmpTag);

        $dataResult = array();
        $dataResult["data"] = $dataList;
        $dataResult["dataFieldIndex"] = $i;
        return $dataResult;
    }
    
	public function isNumericSimple($_data)
	{
        if (strlen($_data) == 0)
        {
            return false;
        }
        $tmpChar = $_data[0];
        if (($tmpChar < '0') ||
            ($tmpChar > '9'))
        {
            return false;
        }
        return true;
    }
    
	public function getTestResultData($_tmpMachineID, $_curTestName, $_isMainMachineID, $_fileHandleFlatData)
	{
        global $returnMsg;
        global $testCaseDataList;
        global $testCaseIDList;
        global $testCaseIDMap;
        global $testCaseFilterNameList;
        global $connectDataSet;
        global $reportUmdNameList;
        global $umdNameList;
        global $reportUmdNum;
        global $curTestPos;
        global $isProduceFlatData;
        global $subTestNumList;
        global $subTestNum;
        global $testNameList;
        global $swtUmdNameList_old;
        global $swtUmdNameList_final;
        global $swtUmdNameList;
        
        if ($_tmpMachineID == -1)
        {
            $returnSet = array();
            return $returnSet;
        }
        
        $curTestName = $_curTestName;
        $tmpKeys = array_keys($connectDataSet["machineIDList"], $_tmpMachineID);
        if ($tmpKeys === false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "machine id not found in checked list" . __LINE__;
            echo json_encode($returnMsg);
            return null;
        }
        
        $tmpFileIndex = -1;
        $tmpTestFileOffset = 0;
        $tmpTestFileOffsetEnd = -1;
        foreach ($tmpKeys as $tmpIndex)
        {
            if (array_key_exists($curTestName, $connectDataSet["allFileTestPosList"][$tmpIndex]))
            {
                $tmpFileIndex = intval($tmpIndex);
                $tmpTestFileOffset = intval($connectDataSet["allFileTestPosList"][$tmpIndex][$curTestName]);
                $tmpTestFileOffsetEnd = -1;
                
                if (($curTestPos + 1) < count($testNameList))
                {
                    $nextTestName = $testNameList[$curTestPos + 1];
                    if (array_key_exists($nextTestName, $connectDataSet["allFileTestPosList"][$tmpIndex]))
                    {
                        $tmpTestFileOffsetEnd = intval($connectDataSet["allFileTestPosList"][$tmpIndex][$nextTestName]);
                    }
                }
            }
        }
        if ($tmpFileIndex == -1)
        {
            //$returnMsg["errorCode"] = 1;
            $returnMsg["errorMsg"] = "test not found in file list" . __LINE__;
            //echo json_encode($returnMsg);
            return null;
        }
        
        $testSubjectNameList = array();
        $unitSubject = "";

        $tmpResultFileName = $connectDataSet["allFileList"][$tmpFileIndex];
        
        $handle = fopen($tmpResultFileName, "r");
        
        if (($tmpTestFileOffsetEnd == -1) ||
            ($tmpTestFileOffsetEnd <  $tmpTestFileOffset))
        {
            fseek($handle, 0, SEEK_END);
            $tmpTestFileOffsetEnd = ftell($handle);
        }
        
        $returnMsg["tmpTestFileOffset"] = $tmpTestFileOffset;
        $returnMsg["tmpTestFileOffsetEnd"] = $tmpTestFileOffsetEnd;
        
        fseek($handle, $tmpTestFileOffset, SEEK_SET);
        $testName = "";
        // test name,
        // sub test name,
        // data,
        // api
        $minColumnNum = 4;
        $dataKeyAPI = -1;
        $testCaseIDKeyAPI = -1;
        $dataKeyDataColumnID = -1;
        $subTestNameFilterNum = 0;
        
        $dataField = fread($handle, $tmpTestFileOffsetEnd - $tmpTestFileOffset);
        
        $dataFieldIndex = 0;
        //while ($data = fgetcsv($handle, 0, ","))
        $curTestCaseIDNum = 0;
        while ($dataResult = $this->getCSVLine2($dataField, $dataFieldIndex))
        {
            $data = $dataResult["data"];
            $dataFieldIndex = $dataResult["dataFieldIndex"];
            $num = count($data);
            if ($num < $minColumnNum)
            {
                continue;
            }
            //for ($i = 0; $i < count($data); $i++)
            //{
            //    $data[$i] = trim($data[$i]);
            //}
            $tmpName = $data[0];
            if (strlen($tmpName) > 0)
            {
                // if this line is title line of each test
                $testName = $tmpName;
                
                if (strcmp($curTestName, $testName) != 0)
                {
                    break;
                }
                
                $dataKeyAPI = array_search("API", $data);
                $testCaseIDKeyAPI = array_search("TestCaseId#", $data);
                
                for ($i = 1; $i < count($data); $i++)
                {
                    $tmpPos = strpos($data[$i], "/");
                    if ($tmpPos !== false)
                    {
                        // data column id
                        $dataKeyDataColumnID = $i;
                        $subTestNameFilterNum = $dataKeyDataColumnID - 1;
                        
                        $returnMsg["testName"] = $testName;
                        $returnMsg["dataKeyDataColumnID"] = $dataKeyDataColumnID;
                        $returnMsg["subTestNameFilterNum"] = $subTestNameFilterNum;
                        break;
                    }
                    else if ($data[$i] == "FPS") // randomsphere
                    {
                        // data column id
                        $dataKeyDataColumnID = $i;
                        $subTestNameFilterNum = $dataKeyDataColumnID - 1;
                        
                        $returnMsg["testName"] = $testName;
                        $returnMsg["dataKeyDataColumnID"] = $dataKeyDataColumnID;
                        $returnMsg["subTestNameFilterNum"] = $subTestNameFilterNum;
                        break;
                    }
                }

                for ($i = 1; $i < $dataKeyDataColumnID; $i++)
                {
                    if (strlen($data[$i]) == 0)
                    {
                        continue;
                    }
                    array_push($testSubjectNameList, $data[$i]);
                }
                $unitSubject = $data[$dataKeyDataColumnID];
            }
            else
            {
                // result data line
                if ($testCaseIDKeyAPI == -1)
                {
                    continue;
                }
                if ($dataKeyDataColumnID == -1)
                {
                    continue;
                }
                $tmpCaseID = intval($data[$testCaseIDKeyAPI]);
                $tmpPos = isset($testCaseIDList[$tmpCaseID]);
                $tmpUmdName = $data[$dataKeyAPI];
                $tmpPos1 = array_search($tmpUmdName, $swtUmdNameList_old);
                if ($tmpPos1 !== false)
                {
                    $tmpUmdName = $swtUmdNameList_final[$tmpPos1];
                }
                

                $testFilterNameList = array();
                $tmpDataList = array();
                
                if (($tmpPos           == false) &&
                    ($_isMainMachineID == true))
                //if ($_isMainMachineID == true)
                {
                    //$tmpIndex = count($testCaseIDList);
                    $tmpIndex = $curTestCaseIDNum++;
                    //$testCaseIDMap[$tmpCaseID] = $tmpIndex;
                    //array_push($testCaseIDList, $tmpCaseID);
                    $testCaseIDList[$tmpCaseID] = $tmpIndex;
                    
                    $testFilterNameList = array();
                    for ($i = 1; $i < $dataKeyDataColumnID; $i++)
                    {
                        if (strlen($data[$i]) == 0)
                        {
                            continue;
                        }
                        array_push($testFilterNameList, $data[$i]);
                    }
                    array_push($testCaseFilterNameList, $testFilterNameList);
                    
                    $tmpDataList = array_fill(0, $reportUmdNum * 2, "");
                    foreach ($tmpDataList as $v)
                    {
                        array_push($testCaseDataList, $v);
                    }
                    //array_merge($testCaseDataList, $tmpDataList);
                    
                    $tmpUmdPos = array_search($tmpUmdName, $umdNameList);
                    $tmpVal = $data[$dataKeyDataColumnID];
                    if (($tmpUmdPos !== false) &&
                        //(is_numeric($tmpVal)))
                        ($this->isNumericSimple($tmpVal)))
                    {
                        $testCaseDataList[$tmpIndex * $reportUmdNum * 2 + $tmpUmdPos] = $tmpVal;
                    }
                }
                else if ($tmpPos == true)
                //else
                {
                    //$tmpIndex = $testCaseIDMap[$tmpCaseID];
                    $tmpIndex = $testCaseIDList[$tmpCaseID];
                    
                    $tmpUmdPos = array_search($tmpUmdName, $umdNameList);
                    $tmpVal = $data[$dataKeyDataColumnID];
                    
                    if ($_isMainMachineID == true)
                    {
                        if (($tmpUmdPos !== false) &&
                            //(is_numeric($tmpVal)))
                            ($this->isNumericSimple($tmpVal)))
                        {
                            $testCaseDataList[$tmpIndex * $reportUmdNum * 2 + $tmpUmdPos] = $tmpVal;
                        }
                    }
                    else
                    {
                        if (($tmpUmdPos !== false) &&
                            //(is_numeric($tmpVal)))
                            ($this->isNumericSimple($tmpVal)))
                        {
                            $testCaseDataList[$tmpIndex * $reportUmdNum * 2 + $reportUmdNum + $tmpUmdPos] = $tmpVal;
                        }
                    }
                }
                
                // temp code
                //for ($k = 1; $k < $subTestNum; $k++)
                //{
                //    array_push($testCaseIDList, $tmpCaseID + $k);
                //    array_push($testCaseFilterNameList, $testFilterNameList);
                //    foreach ($tmpDataList as $v)
                //    {
                //        array_push($testCaseDataList, $v);
                //    }
                //    //array_merge($testCaseDataList, $tmpDataList);
                //}
                //
                //break;
                
            }
            
            if ($_fileHandleFlatData !== null)
            {
                // append flatdata lines
                if ($_isMainMachineID == true)
                {
                    // base card
                    $this->addLinesToFlatData($curTestName,
                                              false,
                                              $data,
                                              $testCaseIDKeyAPI,
                                              $_fileHandleFlatData);
                }
                else
                {
                    // comparison card
                    $this->addLinesToFlatData($curTestName,
                                              true,
                                              $data,
                                              $testCaseIDKeyAPI,
                                              $_fileHandleFlatData);
                }
            }
            
            
            
            
            
        }
        fclose($handle);
        
        
        $returnSet = array();
        $returnSet["testSubjectNameList"] = $testSubjectNameList;
        $returnSet["unitSubject"] = $unitSubject;
        return $returnSet;
    }
    
	public function writeReportCompareData($_tempFileHandle, $reportFolder,
                                           $_isCompStandard, $_reportUmdNum,
                                           $_sheetLinePos, $_startGraphDataLinePos,
                                           $_averageColumnHasVal)
	{
        global $returnMsg;
        global $umdNameList;
        global $umdOrder;
        global $resultUmdOrder;
        global $tableName01;
        global $testName;
        global $testNameList;
        global $subTestNumList;
        global $subTestNum;
        global $startStyleID;
        global $subjectNameFilterNumMax;
        global $dataColumnNum;
        global $swtSheetColumnIDList;
        global $graphCells;
        global $curCardName;
        global $cmpCardName;
        global $curSysName;
        global $cmpSysName;
        global $curMachineName;
        global $cmpMachineName;
        global $swtTempVBAConfigJsonName;
        global $graphDataStartCloumnIDCompareList;
        global $jsonFileName;
        global $reportTemplateDir;
        global $subTestUmdDataMaskList;
        global $dataColumnNum;
        global $cmpMachineID;
        global $curMachineID;
        global $crossType;
        global $machineIDBatchPairList;
        global $testCaseDataList;
        global $testCaseIDList;
        global $testCaseIDMap;
        global $testCaseFilterNameList;

        $tempFileHandle = $_tempFileHandle;
        $reportUmdNumn = count($umdNameList);
        $sheetLinePos = $_sheetLinePos;
                         
        $graphDataColumnNum = 0;
        if ($dataColumnNum == 1)
        {
            $graphDataColumnNum = 1;
        }
        else
        {
            $graphDataColumnNum = intval($dataColumnNum / 2);
        }
                         
        $graphCellsNum = count($graphCells);
        $graphDataArea = "" . $swtSheetColumnIDList[$subjectNameFilterNumMax + 3 + $dataColumnNum + 1 + $graphDataColumnNum + 1] . 
                         graphDataStartLineID . ":" . 
                         $swtSheetColumnIDList[$subjectNameFilterNumMax + 3 + $dataColumnNum + 1 + $graphDataColumnNum + 1 + $graphDataColumnNum] .
                         (intval(graphDataStartLineID) + $graphCellsNum - 1);
                         
                         
        //$returnMsg["tmp_graphCells"] = $graphCells;
        //$returnMsg["tmp_graphCellsNum"] = $graphCellsNum;
        //$returnMsg["tmp_n1"] = (intval(graphDataStartLineID) + count($graphCells) - 1);
        //$returnMsg["tmp_n2"] = (intval(graphDataStartLineIDCompare) + count($graphCells) - 1);
                         
        $graphDataAreaNoBlank = $graphDataArea;
        
        if ($cmpMachineID != -1)
        {
            $hasBlank = array_search(false, $_averageColumnHasVal);
                                    
            $graphDataColumnNum = intval($dataColumnNum / 3);
                                    
            $graphDataAreaNoBlank = "" . $swtSheetColumnIDList[$subjectNameFilterNumMax + 3 + $dataColumnNum + 1 + 2 + $graphDataColumnNum * 2] .
                                    graphDataStartLineIDCompare . ":" . 
                                    $swtSheetColumnIDList[$subjectNameFilterNumMax + 3 + $dataColumnNum + 1 + 2 + $graphDataColumnNum * 2 + $graphDataColumnNum * 2] .
                                    (intval(graphDataStartLineIDCompare) + $graphCellsNum - 1);
            
            $graphDataArea = $graphDataAreaNoBlank;
        }
        
        $tmpJson = array();
        $tmpJson["graphDataArea"] = $graphDataArea;
        $tmpJson["graphDataAreaNoBlank"] = $graphDataAreaNoBlank;
        $tmpJson["curCardName"] = $curCardName;
        $tmpJson["cmpCardName"] = $cmpCardName;
        $tmpJson["curSysName"] = $curSysName;
        $tmpJson["cmpSysName"] = $cmpSysName;
        $tmpJson["curMachineName"] = $curMachineName;
        $tmpJson["cmpMachineName"] = $cmpMachineName;
        $tmpJson["cmpMachineID"] = $cmpMachineID;
        $tmpJson["crossType"] = $crossType;
        // use DX11, DX12, vulkan mask of Alu as overall mask
        // subTestUmdDataMaskList[0]
        $tmpMask = $subTestUmdDataMaskList[0];
        $dropArea = array();

        $tmpJson["dropArea"] = $dropArea;
        $t1 = json_encode($tmpJson);
        
        if ($_isCompStandard)
        {
            $tmpdestPath = $reportFolder . "/" . $curCardName . "_" . $curSysName . "/" . $swtTempVBAConfigJsonName;
            if (file_exists($tmpdestPath))
            {
                unlink($tmpdestPath);
            }
            file_put_contents($tmpdestPath, $t1);
            
            // umd data should occur as order in $umdNameList
            //$umdOrder = array(0, 1, 2, 0, 1, 2);
            
            // collect all data for all umd types
            
            $summaryJson = array();
            $variationJson = array();
            //if ($_cmpStartResultID != -1)
            {
                $t1 = file_get_contents($jsonFileName);
                $summaryJson = json_decode($t1, true);
                
                $t1 = file_get_contents($reportTemplateDir . "/../reportConfig/summarySheet.json");
                $variationJson = json_decode($t1, true);
            }
            $t1 = "";
            //while ($row1 = $db->fetchRow())
            //$curTestCaseNum = count($testCaseIDList);
            //for ($k = 0; $k < $curTestCaseNum; $k++)
            foreach ($testCaseIDList as $tmpTestCaseID => $k)
            {
                $umdData = array_fill(0, $reportUmdNumn * 2, "");
                $umdDataXML = array_fill(0, $reportUmdNumn * 2, "");
                $umdDataVal = array_fill(0, $reportUmdNumn * 2, -1);
                
                $tmpData = array_fill(0, $reportUmdNumn * 2, "");
                $tmpDataVal = array_fill(0, $reportUmdNumn * 2, -1);
                
                $tmpDataColumnNum = 0;
                $tmpDataList = array();
                $tmpDataValList = array();
                
                for ($i = 0; $i < $_reportUmdNum; $i++)
                {
                    $umdData[$i] = "" . $testCaseDataList[$k * $reportUmdNumn * 2 + $i];
                    
                    if (strlen($umdData[$i]) > 0)
                    {
                        // if null value, leave it null
                        $umdDataXML[$i] = "<Data ss:Type=\"Number\">" . $umdData[$i] . "</Data>";
                        $umdDataVal[$i] = floatval($umdData[$i]);
                    }
                    
                    if ($cmpMachineID != -1)
                    {
                        $umdData[$reportUmdNumn + $i] = "" . $testCaseDataList[$k * $reportUmdNumn * 2 + $reportUmdNumn + $i];

                        if (strlen($umdData[$reportUmdNumn + $i]) > 0)
                        {
                            // if null value, leave it null
                            $umdDataXML[$reportUmdNumn + $i] = "<Data ss:Type=\"Number\">" . $umdData[$reportUmdNumn + $i] . "</Data>";
                            $umdDataVal[$reportUmdNumn + $i] = floatval($umdData[$reportUmdNumn + $i]);
                        }
                    }

                    if ($umdOrder[$i] != -1)
                    {
                        $tmpData[$i] = $umdDataXML[$umdOrder[$i]];
                        $tmpData[$reportUmdNumn + $i] = $umdDataXML[$reportUmdNumn + $umdOrder[$i]];
                        
                        $tmpDataVal[$i] = $umdDataVal[$umdOrder[$i]];
                        $tmpDataVal[$reportUmdNumn + $i] = $umdDataVal[$reportUmdNumn + $umdOrder[$i]];
                    }
                    
                    if ($resultUmdOrder[$i] != -1)
                    {
                        array_push($tmpDataList, $tmpData[$i]);
                        //array_push($tmpDataValList, $tmpDataVal[$i]);
                        $tmpDataColumnNum++;
                    }
                }
                
                $tmpList = array_fill(0, ($subjectNameFilterNumMax + 1), " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"/>\n");
                
                $tmpList[0] = " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"><Data ss:Type=\"Number\">" .
                               "" . $tmpTestCaseID . "</Data></Cell>\n";
                $tmpLoopNum = count($testCaseFilterNameList[$k]);
                for ($i = 0; $i < $tmpLoopNum; $i++)
                {
                    $tmpList[$i+ 1] = " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"><Data ss:Type=\"String\">" .
                                   "" . $testCaseFilterNameList[$k][$i] . "</Data></Cell>\n";
                }
                $tmpCode = implode("", $tmpList);
                
                // data rows for api comparison
                $t3 = "<Row>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 8) . "\"><Data ss:Type=\"String\">" . $testName . "</Data></Cell>\n" .
                      $tmpCode;

                if ($tmpDataColumnNum == 1)
                {
                    // 1 api
                    $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                           "" . $tmpDataList[0] . "</Cell>\n";
                }
                else
                {
                    // more than 1 api
                    
                    for ($i = 0; $i < $tmpDataColumnNum; $i++)
                    {
                        if ($i < ($tmpDataColumnNum - 1))
                        {
                            // 
                            $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                                   "" . $tmpDataList[$i] . "</Cell>\n" .
                                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                                   "ss:Formula=\"=(RC" . ($subjectNameFilterNumMax + 5 + $i * 2) . // 8
                                                "-RC" . ($subjectNameFilterNumMax + 3 + $i * 2) . // 6
                                                ")/RC" . ($subjectNameFilterNumMax + 3 + $i * 2) . "\">" .
                                                "<Data ss:Type=\"Number\"></Data></Cell>\n";
                        }
                        else
                        {
                            // 
                            $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                                   "" . $tmpDataList[$i] . "</Cell>\n" .
                                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                                   "ss:Formula=\"=(RC" . ($subjectNameFilterNumMax + 3 + $i * 2) . // 8
                                                "-RC" . ($subjectNameFilterNumMax + 3) . // 6
                                                ")/RC" . ($subjectNameFilterNumMax + 3) . "\">" .
                                                "<Data ss:Type=\"Number\"></Data></Cell>\n";
                        }
                    }
                }          

                $summaryDataVal = array_fill(0, $reportUmdNumn * 2, -1);
                $cmpPartName = array_fill(0, $reportUmdNumn * 2, "");
                if ($cmpMachineID != -1)
                {
                    // data rows for asic comparison
                    $t3 = "<Row>\n" .
                          " <Cell ss:StyleID=\"s" . ($startStyleID + 8) . "\"><Data ss:Type=\"String\">" . $testName . "</Data></Cell>\n" .
                          $tmpCode;
                    
                    $tmpDataColumnNum = 0;
                    $tmpDataList = array();
                    $tmpDataList2 = array();
                    $tmpDataValList = array();
                    $tmpDataValList2 = array();
                    
                    $rateVal = array_fill(0, $reportUmdNumn, -1);
                    $summaryDataVal = array_fill(0, $reportUmdNumn * 2, -1);
                    $cmpPartName = array_fill(0, $reportUmdNumn * 2, "");
                    
                    for ($i = 0; $i < $reportUmdNumn; $i++)
                    {
                        if (($resultUmdOrder[$i] != -1) &&
                            ($resultUmdOrder[$reportUmdNumn + $i] != -1))
                        {
                            array_push($tmpDataList, $tmpData[$i]);
                            array_push($tmpDataList2, $tmpData[$reportUmdNumn + $i]);
                            array_push($tmpDataValList, $tmpDataVal[$i]);
                            array_push($tmpDataValList2, $tmpDataVal[$reportUmdNumn + $i]);
                            $tmpDataColumnNum++;
                        }
                        
                        if (($tmpDataVal[$i] > 0) &&
                            ($tmpDataVal[$reportUmdNumn + $i] > 0))
                        {
                            $rateVal[$i] = ($tmpDataVal[$reportUmdNumn + $i] - $tmpDataVal[$i]) / $tmpDataVal[$i];
                        }
                        
                        $j = $i * 2;
                        $summaryDataVal[$j] = $tmpDataVal[$i];
                        $summaryDataVal[$j + 1] = $tmpDataVal[$reportUmdNumn + $i];
                        
                        $cmpPartName[$j] = $curCardName;
                        $cmpPartName[$j + 1] = $cmpCardName;
                    }
                    
                    for ($i = 0; $i < count($tmpDataList2); $i++)
                    {
                        array_push($tmpDataList, $tmpDataList2[$i]);
                        array_push($tmpDataValList, $tmpDataValList2[$i]);
                    }
                    
                    for ($i = 0; $i < $tmpDataColumnNum; $i++)
                    {
                        $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                               "" . $tmpDataList[$i] . "</Cell>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                               "ss:Formula=\"=(RC" . ($subjectNameFilterNumMax + 5 + $i * 3) . // 8
                               "-RC" . ($subjectNameFilterNumMax + 3 + $i * 3) . // 6
                               ")/RC" . ($subjectNameFilterNumMax + 3 + $i * 3) . "\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                               "" . $tmpDataList[$tmpDataColumnNum + $i] . "</Cell>\n";
                    }
                }
                else
                {
                    // no comparison card
                    $rateVal = array_fill(0, $reportUmdNumn, -1);
                    $summaryDataVal = array_fill(0, $reportUmdNumn * 2, -1);
                    $cmpPartName = array_fill(0, $reportUmdNumn * 2, "");
                    
                    $tmpIndexList = array();
                    for ($i = 0; $i < $reportUmdNumn; $i++)
                    {
                        if ($resultUmdOrder[$i] == -1)
                        {
                            // absent api
                            continue;
                        }
                        array_push($tmpIndexList, $i);
                    }
                    
                    $tmpLoopNum = count($tmpIndexList);
                    for ($i = 0; $i < $tmpLoopNum; $i++)
                    {
                        $tmpIndex = 0;
                        $tmpIndex2 = 0;
                        if ($i < (count($tmpIndexList) - 1))
                        {
                            $tmpIndex = $tmpIndexList[$i];
                            $tmpIndex2 = $tmpIndexList[$i + 1];
                        }
                        else
                        {
                            $tmpIndex = 0;
                            $tmpIndex2 = $tmpIndexList[$i];
                        }
                        
                        if (($tmpDataVal[$tmpIndex] > 0) &&
                            ($tmpDataVal[$tmpIndex2] > 0))
                        {
                            $rateVal[$tmpIndexList[$i]] = ($tmpDataVal[$tmpIndex2] - $tmpDataVal[$tmpIndex]) / $tmpDataVal[$tmpIndex];
                        }
                        
                        $j = $tmpIndexList[$i] * 2;
                        $summaryDataVal[$j] = $tmpDataVal[$tmpIndex];
                        $summaryDataVal[$j + 1] = $tmpDataVal[$tmpIndex2];
                        
                        $cmpPartName[$j] = $umdNameList[$tmpIndex];
                        $cmpPartName[$j + 1] = $umdNameList[$tmpIndex2];
                    }

                }
                
                $sectionPosList = array(0,
                                        $reportUmdNumn * 2,
                                        $reportUmdNumn * 2 + $reportUmdNumn * 2,
                                        $reportUmdNumn * 2 + $reportUmdNumn * 2 + 1,
                                        $reportUmdNumn * 2 + $reportUmdNumn * 2 + 1 + $reportUmdNumn * 8,
                                        $reportUmdNumn * 2 + $reportUmdNumn * 2 + 1 + $reportUmdNumn * 8 + $reportUmdNumn * 2,
                                        $reportUmdNumn * 2 + $reportUmdNumn * 2 + 1 + 
                                        $reportUmdNumn * 8 + $reportUmdNumn * 2 + $reportUmdNumn * 4
                                        );
                
                if (array_key_exists($testName, $summaryJson))
                {
                    // data for summary sheet
                    $tmpVal = $summaryJson[$testName];
                    $tmpValOld = $tmpVal;
                    // loss: testcaseid, testcasename,
                    // gain: testcaseid, testcasename,
                    // comp0, comp1,
                    
                    $rateVal2 = array_fill(0, $reportUmdNumn * 2, -1);
                    
                    $tmpVariation = $variationJson["defaultVariation"];
                    if (array_key_exists($testName, $variationJson))
                    {
                        $tmpVariation = $variationJson[$testName];
                    }
                    // get test case up / down num for each test
                    
                    $lossRateSum = array_fill(0, $reportUmdNumn, -1);
                    $lossRateNum = array_fill(0, $reportUmdNumn, -1);
                    $gainRateSum = array_fill(0, $reportUmdNumn, -1);
                    $gainRateNum = array_fill(0, $reportUmdNumn, -1);
                    
                    
                    $finalRateVal = array_fill(0, $reportUmdNumn * 2, -1);
                    $tmpChangeFlag = array_fill(0, $reportUmdNumn * 2, false);
                    
                    
                    $summaryJson[$testName] = array_fill(0, $sectionPosList[6], -1);
                    
                    for ($i = 0; $i < $reportUmdNumn; $i++)
                    {
                        $j = $i * 2;

                        $tmpVal[$j] =     ($tmpVal[$j] == -1)     ? $rateVal[$i] : $tmpVal[$j];
                        $tmpVal[$j + 1] = ($tmpVal[$j + 1] == -1) ? $rateVal[$i] : $tmpVal[$j + 1];
                        
                        $rateVal2[$j] =     ($rateVal[$i] == -1) ? $tmpVal[$j] :     $rateVal[$i];
                        $rateVal2[$j + 1] = ($rateVal[$i] == -1) ? $tmpVal[$j + 1] : $rateVal[$i];

                        
                        $tmpPos = $sectionPosList[1];

                        $tmpVal[$j + $tmpPos] =     (($rateVal[$i] < $tmpVariation[0]) && ($rateVal[$i] != -1)) ? 
                                                    ($tmpVal[$j + $tmpPos] + 1)     : $tmpVal[$j + $tmpPos];
                        $tmpVal[$j + $tmpPos + 1] = (($rateVal[$i] > $tmpVariation[1]) && ($rateVal[$i] != -1)) ? 
                                                    ($tmpVal[$j + $tmpPos + 1] + 1) : $tmpVal[$j + $tmpPos + 1];
                        
                        $tmpPos2 = $sectionPosList[5];
                        $lossRateSum[$i] = $tmpVal[$tmpPos2 + $i * 4];
                        $lossRateNum[$i] = $tmpVal[$tmpPos2 + 1 + $i * 4];
                        $gainRateSum[$i] = $tmpVal[$tmpPos2 + 2 + $i * 4];
                        $gainRateNum[$i] = $tmpVal[$tmpPos2 + 3 + $i * 4];

                        
                        if ($rateVal[$i] != -1)
                        {
                            if ($rateVal[$i] < $tmpVariation[0])
                            {
                                // less than -3%
                                $lossRateSum[$i] += ($rateVal[$i] - $tmpVariation[0]);
                                $lossRateNum[$i]++;
                            }
                            else if ($rateVal[$i] > $tmpVariation[1])
                            {
                                // greater than 3%
                                $gainRateSum[$i] += ($rateVal[$i] - $tmpVariation[1]);
                                $gainRateNum[$i]++;
                            }
                        }
 
 
                        $finalRateVal[$j] =     min($rateVal2[$j],     $tmpVal[$j]);
                        $finalRateVal[$j + 1] = max($rateVal2[$j + 1], $tmpVal[$j + 1]);
                        
                        $tmpChangeFlag[$j] =     $finalRateVal[$j]     != $tmpValOld[$j] ? true : false;
                        $tmpChangeFlag[$j + 1] = $finalRateVal[$j + 1] != $tmpValOld[$j + 1] ? true : false;
                        
                        $tmpPos = $sectionPosList[3];
                        
                        $tmpVal[$tmpPos + $j * 4] =     $tmpChangeFlag[$j]     ? 
                                                        $tmpTestCaseID : $tmpVal[$tmpPos + $j * 4];
                        $tmpVal[$tmpPos + $j * 4 + 4] = $tmpChangeFlag[$j + 1] ? 
                                                        $tmpTestCaseID : $tmpVal[$tmpPos + $j * 4 + 4];
                                                        
                        $tmpPos2 = $sectionPosList[3] + 1;
                        $tmpVal[$tmpPos2 + $j * 4]     = $tmpChangeFlag[$j] ? 
                                                         implode("_", $testCaseFilterNameList[$k]) : $tmpVal[$tmpPos2 + $j * 4];
                        $tmpVal[$tmpPos2 + $j * 4 + 4] = $tmpChangeFlag[$j + 1] ? 
                                                         implode("_", $testCaseFilterNameList[$k]) : $tmpVal[$tmpPos2 + $j * 4 + 4];
                                                         
                        $tmpPos3 = $sectionPosList[3] + 2;
                        $tmpVal[$tmpPos3 + $j * 4] =     $tmpChangeFlag[$j] ? 
                                                         $summaryDataVal[$j] : $tmpVal[$tmpPos3 + $j * 4];
                        $tmpVal[$tmpPos3 + $j * 4 + 4] = $tmpChangeFlag[$j + 1] ? 
                                                         $summaryDataVal[$j] : $tmpVal[$tmpPos3 + $j * 4 + 4];
                                                         
                        $tmpPos4 = $sectionPosList[3] + 3;
                        $tmpVal[$tmpPos4 + $j * 4] =     $tmpChangeFlag[$j] ? 
                                                         $summaryDataVal[$j + 1] : $tmpVal[$tmpPos4 + $j * 4];
                        $tmpVal[$tmpPos4 + $j * 4 + 4] = $tmpChangeFlag[$j + 1] ? 
                                                         $summaryDataVal[$j + 1] : $tmpVal[$tmpPos4 + $j * 4 + 4];

                        
                        $summaryJson[$testName][$j] =     $finalRateVal[$j];
                        $summaryJson[$testName][$j + 1] = $finalRateVal[$j + 1];
                        
                        $tmpPos = $sectionPosList[1];
                        $summaryJson[$testName][$tmpPos + $j] =     $tmpVal[$tmpPos + $j];
                        $summaryJson[$testName][$tmpPos + $j + 1] = $tmpVal[$tmpPos + $j + 1];
                        
                        $tmpPos2 = $sectionPosList[2];
                        $summaryJson[$testName][$tmpPos2] = $subTestNum;
                        
                        $tmpPos3 = $sectionPosList[3];
                        $summaryJson[$testName][$tmpPos3 + $j * 4] = $tmpVal[$tmpPos3 + $j * 4];
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 1] = $tmpVal[$tmpPos3 + $j * 4 + 1];
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 2] = $tmpVal[$tmpPos3 + $j * 4 + 2];
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 3] = $tmpVal[$tmpPos3 + $j * 4 + 3];
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 4] = $tmpVal[$tmpPos3 + $j * 4 + 4];
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 5] = $tmpVal[$tmpPos3 + $j * 4 + 5];
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 6] = $tmpVal[$tmpPos3 + $j * 4 + 6];
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 7] = $tmpVal[$tmpPos3 + $j * 4 + 7];
                        
                        $tmpPos4 = $sectionPosList[4];
                        $summaryJson[$testName][$tmpPos4 + $j] = $cmpPartName[$j];
                        $summaryJson[$testName][$tmpPos4 + $j + 1] = $cmpPartName[$j + 1];
                        
                        $tmpPos5 = $sectionPosList[5];
                        $summaryJson[$testName][$tmpPos5 + $i * 4] =     $lossRateSum[$i];
                        $summaryJson[$testName][$tmpPos5 + $i * 4 + 1] = $lossRateNum[$i];
                        $summaryJson[$testName][$tmpPos5 + $i * 4 + 2] = $gainRateSum[$i];
                        $summaryJson[$testName][$tmpPos5 + $i * 4 + 3] = $gainRateNum[$i];
                    }

                }
                else
                {
                    $summaryJson[$testName] = array_fill(0, $sectionPosList[6], -1);
                    
                    for ($i = 0; $i < $reportUmdNumn; $i++)
                    {
                        $j = $i * 2;
                        
                        $summaryJson[$testName][$j] =     $rateVal[$i];
                        $summaryJson[$testName][$j + 1] = $rateVal[$i];
                        
                        $tmpPos = $sectionPosList[1];
                        $summaryJson[$testName][$tmpPos + $j] =     0;
                        $summaryJson[$testName][$tmpPos + $j + 1] = 0;
                        
                        $tmpPos2 = $sectionPosList[2];
                        $summaryJson[$testName][$tmpPos2] = 0;
                        
                        $tmpPos3 = $sectionPosList[3];
                        $summaryJson[$testName][$tmpPos3 + $j * 4] =     -1;
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 1] = "";
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 2] = 0;
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 3] = 0;
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 4] = -1;
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 5] = "";
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 6] = 0;
                        $summaryJson[$testName][$tmpPos3 + $j * 4 + 7] = 0;
                        
                        $tmpPos4 = $sectionPosList[4];
                        $summaryJson[$testName][$tmpPos4 + $j] =     "";
                        $summaryJson[$testName][$tmpPos4 + $j + 1] = "";
                        
                        $tmpPos5 = $sectionPosList[5];
                        $summaryJson[$testName][$tmpPos5 + $i * 4] =     0;
                        $summaryJson[$testName][$tmpPos5 + $i * 4 + 1] = 0;
                        $summaryJson[$testName][$tmpPos5 + $i * 4 + 2] = 0;
                        $summaryJson[$testName][$tmpPos5 + $i * 4 + 3] = 0;
                    }

                }
                
                $t1 .= $t3;
                $graphCellsNum = count($graphCells);
                if ($graphCellsNum > 0)
                {
                    $n2 = $sheetLinePos - $_startGraphDataLinePos;
                    if (($n2 >= 0) &&
                        ($n2 <  $graphCellsNum))
                    {
                        // add average data for graph to right
                        $t1 .= $graphCells[$n2]; 
                    }
                }
                
                $t1 .= "</Row>\n";
                       
                $sheetLinePos++;
            }
            
            fwrite($tempFileHandle, $t1);
            
            //if ($_cmpStartResultID != -1)
            {
                // write summary sheet json
                $t1 = json_encode($summaryJson);
                if (file_exists($jsonFileName))
                {
                    unlink($jsonFileName);
                }
                file_put_contents($jsonFileName, $t1);
            }
        }

        $returnSet = array();
        $returnSet["sheetLinePos"] = $sheetLinePos;
        return $returnSet;
    }
}


?>