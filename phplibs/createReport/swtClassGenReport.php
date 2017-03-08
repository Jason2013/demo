<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

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
        
        //$returnMsg["_checkedMachineIDList"] = $_checkedMachineIDList;
        //$returnMsg["_checkedMachineIDList2"] = $checkedMachineIDList;

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

        $params1 = array();
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
    
	public function prepareReportFolder($_reportType, $_batchID, $_curReportFolder)
	{
        global $allReportsDir;
        global $downloadReportDir;
        
        $reportFolder = "";
        if ($_reportType == 0)
        {
            $curReportFolder = sprintf("%05d", $_curReportFolder);
            $batchFolder = $downloadReportDir . "/batch" . $_batchID;

            $reportFolder = $batchFolder . "/" . $curReportFolder;
        }
        else if ($_reportType == 1)
        {
            // generate all reports
            $reportFolder = $allReportsDir . "/batch" . $_batchID;
        }

        $returnSet = array();
        $returnSet["reportFolder"] = $reportFolder;
        $returnSet["curReportFolder"] = $_curReportFolder;
        return $returnSet;
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
                "WHERE batch_id=? AND batch_state=\"1\" AND (batch_group=\"1\" OR batch_group=\"2\")";
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
    
	public function getTestTitleInfo($_db, $_batchID)
	{
        global $returnMsg;
        $db = $_db;
        $params1 = array($_batchID);
        $sql1 = "SELECT DISTINCT t0.test_id, t0.*, " .
                "(SELECT t1.test_name FROM mis_table_test_info t1 WHERE t1.test_id=t0.test_id) AS testName, " .
                "(SELECT t2.test_name FROM mis_table_test_info t2 WHERE t2.test_id=t0.subject_id) AS subjectName, " .
                "(SELECT t3.test_name FROM mis_table_test_info t3 WHERE t3.test_id=t0.unit_id) AS unitName " .
                "FROM mis_table_test_subject_list t0 " .
                "WHERE t0.batch_id=? ORDER BY t0.list_id ASC";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
            echo json_encode($returnMsg);
            return null;
        }

        $testNameList = array();
        $subjectNameList = array();
        $unitNameList = array();

        while ($row1 = $db->fetchRow())
        {
            array_push($testNameList, $row1[6]);
            array_push($subjectNameList, $row1[7]);
            array_push($unitNameList, $row1[8]);
        }
        
        $returnSet = array();
        $returnSet["testNameList"] = $testNameList;
        $returnSet["subjectNameList"] = $subjectNameList;
        $returnSet["unitNameList"] = $unitNameList;
        return $returnSet;
    }
    
	public function getSelectedMachineInfo($_db, $_batchID, $_checkedMachineIDListString)
	{
        global $returnMsg;
        $db = $_db;
        
        $checkedMachineIDListString = $_checkedMachineIDListString;

        if (strlen($checkedMachineIDListString) == 0)
        {
            $params1 = array($_batchID);
            $sql1 = "SELECT machine_id " .
                    "FROM mis_table_result_list " .
                    "WHERE batch_id=? ";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                echo json_encode($returnMsg);
                return null;
            }
            $tmpList = array();
            while ($row1 = $db->fetchRow())
            {
                array_push($tmpList, intval($row1[0]));
            }
            $checkedMachineIDListString = implode(",", $tmpList);
        }
        
        if (strlen($checkedMachineIDListString) == 0)
        {
            $returnMsg["errorCode"] = 1;
            $returnMsg["compileFinished"] = 1;
            $returnMsg["errorMsg"] = "no machine selected";
            echo json_encode($returnMsg);
            return null;
        }
        
        $returnMsg["checkedMachineIDListString"] = $checkedMachineIDListString;
        
        $params1 = array();
        $sql1 = "SELECT card_id, sys_id " .
                "FROM mis_table_machine_info " .
                "WHERE machine_id IN (" . $checkedMachineIDListString . ")";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
            echo json_encode($returnMsg);
            return null;
        }

        $selectedCardIDList = array();
        $selectedSysIDList = array();

        while ($row1 = $db->fetchRow())
        {
            // selected cards to generate reports
            // skip others
            array_push($selectedCardIDList, intval($row1[0]));
            array_push($selectedSysIDList, intval($row1[1]));
        }
        
        $returnSet = array();
        $returnSet["selectedCardIDList"] = $selectedCardIDList;
        $returnSet["selectedSysIDList"] = $selectedSysIDList;
        return $returnSet;
    }
    
	public function getBatchInfo($_db, $_batchIDList)
	{
        global $returnMsg;
        global $selectedCardIDList;
        global $selectedSysIDList;
        global $umdNameList;
        $db = $_db;

        $resultIDList = array();
        $machineIDList = array();
        $cardNameList = array();
        $driverNameList = array();
        $changeListNumList = array();
        $cpuNameList = array();
        $sysNameList = array();
        $mainLineNameList = array();
        $sClockNameList = array();
        $mClockNameList = array();
        $gpuMemNameList = array();
        $resultTimeList = array();
        
        $cardNameListFlat = array();
        $driverNameListFlat = array();

        foreach ($_batchIDList as $tmpBatchID)
        {
            $tmpResultIDList = array();
            $tmpMachineIDList = array();
            $tmpCardNameList = array();
            $tmpDriverNameList = array();
            $tmpChangeListNumList = array();
            $tmpCpuNameList = array();
            $tmpSysNameList = array();
            $tmpMainLineNameList = array();
            $tmpSClockNameList = array();
            $tmpMClockNameList = array();
            $tmpGpuMemNameList = array();
            $tmpResultTimeList = array();
            
            $params1 = array($tmpBatchID);
            $sql1 = "SELECT t0.*, " .
                    "t1.*, " .
                    "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.card_id) AS cardName, " .
                    "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t0.umd_id) AS umdName, " .
                    "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.cpu_id) AS cpuName, " .
                    "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.sys_id) AS sysName, " .
                    "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.ml_id) AS mlName, " .
                    "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.s_clock_id) AS sClockName, " .
                    "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.m_clock_id) AS mClockName, " .
                    "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.gpu_mem_id) AS gpuMemName " .
                    "FROM mis_table_result_list t0 " .
                    "LEFT JOIN mis_table_machine_info t1 " .
                    "USING (machine_id) " .
                    "WHERE batch_id=? ORDER BY t0.machine_id, t0.umd_id ASC";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . $db->getError()[2];
                echo json_encode($returnMsg);
                return null;
            }

            $umdIndex = 0;
            $cardIndex = -1;
            $curCardID = -1;
            $umdNum = count($umdNameList);
            while ($row1 = $db->fetchRow())
            {
                array_push($cardNameListFlat, $row1[20]);
                array_push($driverNameListFlat, $row1[21]);
                
                $tmpCardID = intval($row1[10]);
                $tmpSysID = intval($row1[12]);
                $tmpKeys1 = array_keys($selectedCardIDList, $tmpCardID);
                $tmpKeys2 = array_keys($selectedSysIDList, $tmpSysID);
                $tmpKeys3 = array_intersect($tmpKeys1, $tmpKeys2);
                if (count($tmpKeys3) == 0)
                {
                    // skip unselected cards
                    continue;
                }
                $tmpDriverName = $row1[21];
                
                if ($umdIndex == 0)
                {
                    $curCardID = $tmpCardID;
                    $cardIndex++;
                    // hold enough space
                    for ($j = 0; $j < $umdNum; $j++)
                    {
                        array_push($tmpResultIDList, PHP_INT_MAX);
                        array_push($tmpMachineIDList, PHP_INT_MAX);
                        array_push($tmpCardNameList, $row1[20]);
                        array_push($tmpDriverNameList, $umdNameList[$j]);
                        array_push($tmpChangeListNumList, PHP_INT_MAX);
                        array_push($tmpCpuNameList, "");
                        array_push($tmpSysNameList, $row1[23]);
                        array_push($tmpMainLineNameList, "");
                        array_push($tmpSClockNameList, "");
                        array_push($tmpMClockNameList, "");
                        array_push($tmpGpuMemNameList, "");
                        array_push($tmpResultTimeList, "");
                    }
                }
                else
                {
                    if ($curCardID != $tmpCardID)
                    {
                        // next card
                        // e.g. tmpCardNameList:   jan26, jan31
                        //      tmpDriverNameList: DX12, DX12
                        $curCardID = $tmpCardID;
                        $cardIndex++;
                        // hold enough space
                        for ($j = 0; $j < $umdNum; $j++)
                        {
                            array_push($tmpResultIDList, PHP_INT_MAX);
                            array_push($tmpMachineIDList, PHP_INT_MAX);
                            array_push($tmpCardNameList, $row1[20]);
                            array_push($tmpDriverNameList, $umdNameList[$j]);
                            array_push($tmpChangeListNumList, PHP_INT_MAX);
                            array_push($tmpCpuNameList, "");
                            array_push($tmpSysNameList, $row1[23]);
                            array_push($tmpMainLineNameList, "");
                            array_push($tmpSClockNameList, "");
                            array_push($tmpMClockNameList, "");
                            array_push($tmpGpuMemNameList, "");
                            array_push($tmpResultTimeList, "");
                        }
                        $umdIndex = 0;
                    }
                }

                $tmpIndex = array_search($tmpDriverName, $umdNameList);
                if ($tmpIndex !== false)
                {
                    $n1 = $cardIndex * $umdNum + $tmpIndex;
                    $tmpResultIDList[$n1] = $row1[0];
                    $tmpMachineIDList[$n1] = $row1[1];
                    $tmpCardNameList[$n1] = $row1[20];
                    $tmpDriverNameList[$n1] = $row1[21];
                    $tmpChangeListNumList[$n1] = $row1[4];
                    $tmpCpuNameList[$n1] = $row1[22];
                    $tmpSysNameList[$n1] = $row1[23];
                    $tmpMainLineNameList[$n1] = $row1[24];
                    $tmpSClockNameList[$n1] = $row1[25];
                    $tmpMClockNameList[$n1] = $row1[26];
                    $tmpGpuMemNameList[$n1] = $row1[27];
                    $tmpResultTimeList[$n1] = $row1[7];
                }
                if ($umdIndex != $tmpIndex)
                {
                    $umdIndex = $tmpIndex;
                }
                
                $umdIndex++;
                if ($umdIndex >= count($umdNameList))
                {
                    $umdIndex = 0;
                }
            }
            array_push($resultIDList, $tmpResultIDList);
            array_push($machineIDList, $tmpMachineIDList);
            array_push($cardNameList, $tmpCardNameList);
            array_push($driverNameList, $tmpDriverNameList);
            array_push($changeListNumList, $tmpChangeListNumList);
            array_push($cpuNameList, $tmpCpuNameList);
            array_push($sysNameList, $tmpSysNameList);
            array_push($mainLineNameList, $tmpMainLineNameList);
            array_push($sClockNameList, $tmpSClockNameList);
            array_push($mClockNameList, $tmpMClockNameList);
            array_push($gpuMemNameList, $tmpGpuMemNameList);
            array_push($resultTimeList, $tmpResultTimeList);
        }
        
        $returnMsg["cardNameListFlat"] = $cardNameListFlat;
        $returnMsg["driverNameListFlat"] = $driverNameListFlat;
        
        $returnSet = array();
        $returnSet["resultIDList"] = $resultIDList;
        $returnSet["machineIDList"] = $machineIDList;
        $returnSet["cardNameList"] = $cardNameList;
        $returnSet["driverNameList"] = $driverNameList;
        $returnSet["changeListNumList"] = $changeListNumList;
        $returnSet["cpuNameList"] = $cpuNameList;
        $returnSet["sysNameList"] = $sysNameList;
        $returnSet["mainLineNameList"] = $mainLineNameList;
        $returnSet["sClockNameList"] = $sClockNameList;
        $returnSet["mClockNameList"] = $mClockNameList;
        $returnSet["gpuMemNameList"] = $gpuMemNameList;
        $returnSet["resultTimeList"] = $resultTimeList;
        return $returnSet;
    }
    
	public function checkDefaultMachinePair($_resultPos)
	{
        global $returnMsg;
        global $cardNameList;
        global $sysNameList;
        global $machineIDPairList;
        global $machineIDList;
        global $reportType;
        global $ubuntuCheckWord;
        
        //if ($reportType == 1)
        {
            // if gen all reports
            //if (count($machineIDPairList) == 0)
            {
                $curSysNameFlat = strtolower($sysNameList[0][$_resultPos]);
                $curCardName = $cardNameList[0][$_resultPos];
                $curSysName = $sysNameList[0][$_resultPos];
                $curMachineID = $machineIDList[0][$_resultPos];
                
                if (($curMachineID != PHP_INT_MAX) &&
                    (strstr($curSysNameFlat, $ubuntuCheckWord) !== false))
                {
                    // if cur machine is ubuntu, cmp it with win
                    $tmpList = array_keys($cardNameList[0], $curCardName);

                    foreach ($tmpList as $k)
                    {
                        $tmpID = intval($k);
                        if ($sysNameList[0][$tmpID] != $curSysName)
                        {
                            // win pos
                            $cmpMachineID = $machineIDList[0][$tmpID];
                            if ($cmpMachineID == PHP_INT_MAX)
                            {
                                continue;
                            }
                            
                            if (count($machineIDPairList) == 0)
                            {
                                $machineIDPairList = array($curMachineID, $cmpMachineID);
                            }
                            else
                            {
                                $tmpList2 = array_keys($machineIDPairList, $curMachineID);
                                $b1 = false;
                                foreach ($tmpList2 as $k2)
                                {
                                    $tmpID2 = intval($k2);
                                    if (($tmpID2 % 2) == 0)
                                    {
                                        $b1 = true;
                                        break;
                                    }
                                }
                                
                                if ($b1 == false)
                                {
                                    array_push($machineIDPairList, $curMachineID);
                                    array_push($machineIDPairList, $cmpMachineID);
                                }
                            }
                            $returnMsg["machineIDPairList"] = $machineIDPairList;
                            $returnMsg["curCardName"] = $curCardName;
                            $returnMsg["tmpList"] = $tmpList;
                            $returnMsg["cardNameList[0]"] = $cardNameList[0];
                            $returnMsg["machineIDList[0]"] = $machineIDList[0];
                            
                            break;
                        }
                    }
                }
            }
        }
    }
    
	public function getCompareMachineInfo($_resultPos)
	{
        global $returnMsg;
        global $cardNameList;
        global $sysNameList;
        global $machineIDPairList;
        global $machineIDList;
        global $umdNameList;
        global $umdNum;
        
        $cmpMachineID = -1;
        $cmpStartResultID = -1;
        $cmpCardName = "";
        $cmpSysName = "";
        $curCardName = $cardNameList[0][$_resultPos];
        if (count($machineIDPairList) > 0)
        {
            // get comparison machine result id list
            $curMachineID = intval($machineIDList[0][$_resultPos]);
            $tmpNum = count($machineIDPairList) / 2;
            $returnMsg["tmpNum"] = $tmpNum;
            for ($i = 0; $i < $tmpNum; $i++)
            {
                $returnMsg["curMachineID"] = $curMachineID;
                if ($curMachineID == intval($machineIDPairList[$i * 2]))
                {
                    $n1 = $i * 2 + 1;
                    $returnMsg["n1"] = $n1;
                    if ($n1 < count($machineIDPairList))
                    {
                        $cmpMachineID = $machineIDPairList[$n1];
                        
                        $n1 = array_search($cmpMachineID, $machineIDList[0]);
                        $returnMsg["n1-1"] = $n1;
                        if ($n1 !== false)
                        {
                            $cmpStartResultID = intval($n1 / $umdNum) * $umdNum;
                            
                            $returnMsg["cmpStartResultID"] = $cmpStartResultID;
                            $returnMsg["cmpStartResultID1"] = $n1;
                            
                            $cmpCardName = $cardNameList[0][$n1];
                            $cmpSysName = $sysNameList[0][$n1];
                        }
                    }
                    break;
                }
            }
        }
        
        $returnMsg["machineIDPairList"] = $machineIDPairList;
        $returnMsg["cmpMachineID"] = $cmpMachineID;
        

        $returnSet = array();
        $returnSet["cmpMachineID"] = $cmpMachineID;
        $returnSet["cmpStartResultID"] = $cmpStartResultID;
        $returnSet["cmpCardName"] = $cmpCardName;
        $returnSet["cmpSysName"] = $cmpSysName;
        $returnSet["curCardName"] = $curCardName;
        return $returnSet;
    }
    
	public function getSubTestNum($_db, $_resultPos, $_tableName01, $_subTestNum)
	{
        global $returnMsg;
        global $resultIDList;
        $db = $_db;

        $subTestNum = $_subTestNum;
        if ($subTestNum <= 0)
        {
            $params1 = array($resultIDList[0][$_resultPos]);
            $sql1 = "SELECT COUNT(*) FROM " . $_tableName01 . " " .
                    "WHERE result_id=?";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            $row1 = $db->fetchRow();
            $subTestNum = 0;
            if ($row1 == false)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            $subTestNum = $row1[0];
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
                        array_push($skipTestNameList, $testNameList[$i]);
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

        $xmlFileName = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d.tmp2", $_batchID);
        $tmpFileName = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d.tmp", $_batchID);
        $tmpFileName1 = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d.tmp1", $_batchID);
        $jsonFileName = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d.json", $_batchID);
        
        $returnSet = array();
        $returnSet["xmlFileName"] = $xmlFileName;
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
                                   $_resultPos,
                                   $_sheetLinePos,
                                   $_tmpUmdName,
                                   $_tmpCardName,
                                   $_tmpSysName,
                                   $_cmpMachineID,
                                   $_cmpCardName)
	{
        global $returnMsg;
        global $cardNameList;
        global $sysNameList;
        global $reportTemplateDir;
        global $cmpStartResultID;

        $sheetLinePos = $_sheetLinePos;
        
        $tmpSrc = 0;
        $shiftCard = false;
        $nextCardPos = $_resultPos + 1;
        if ($nextCardPos >= count($cardNameList[0]))
        {
            $tmpSrc = 1;
            $shiftCard = true;
        }
        else if ((strcmp($_tmpCardName, $cardNameList[0][$nextCardPos]) != 0) ||
                 (strcmp($_tmpSysName, $sysNameList[0][$nextCardPos]) != 0))
        {
            $tmpSrc = 2;
            $shiftCard = true;
        }
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
            //if (file_exists($_jsonFileName) == false)
            //{
            //    $returnMsg["errorCode"] = 0;
            //    $returnMsg["tmpSrc"] = $tmpSrc;
            //    $returnMsg["errorMsg"] = "temp file: " . $_jsonFileName . " missing, line: " . __LINE__;
            //    echo json_encode($returnMsg);
            //    return null;
            //}
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
                if ($_cmpMachineID != -1)
                {
                    // if comparison with other cards
                    // $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002B1.txt");
                    $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002B2.txt");
                }
                else
                {
                    $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002B.txt");
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
                    $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet004A.txt");
                    
                    $t1 = sprintf($xmlSection, "DXCP v.s DXX", "Vulkan v.s DXCP", "Vulkan v.s DXX");
                    if ($cmpStartResultID != -1)
                    {
                        $t1 = sprintf($xmlSection, "DXX", "DXCP", "Vulkan");
                    }
                    
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
                        
                        $tmpSum = intval($v[12]);
                        
                        $hasContent = false;
                        for ($i = 0; $i < 3; $i++)
                        {
                            $tmpMin = floatval($v[$i * 2]);
                            $tmpMax = floatval($v[$i * 2 + 1]);
                            $tmpMinRate = round($tmpMin * 100.0);
                            $tmpMaxRate = round($tmpMax * 100.0);
                            $tmpLmtMinRate = round($tmpVal[0] * 100.0);
                            $tmpLmtMaxRate = round($tmpVal[1] * 100.0);
                            $tmpUp = intval($v[$i * 2 + 7]);
                            $tmpDown = intval($v[$i * 2 + 6]);
                            $tmpUpRate = intval($tmpSum == 0 ? 0 : $tmpUp * 100.0 / $tmpSum);
                            $tmpDownRate = intval($tmpSum == 0 ? 0 : $tmpDown * 100.0 / $tmpSum);
                            
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
                                }
                                else if (($tmpMinRate > $tmpLmtMinRate) &&
                                         ($tmpMaxRate > $tmpLmtMaxRate))
                                {
                                    // all up, font green
                                    $t3 = "s99";
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
                                        $t5 = sprintf(//"TestCaseId,\tCaseName,\t%s,\t%%,\t%s&#10;" .
                                                      "&#10;" .
                                                      "#%d,\t%s,\t%d,\t%d%%,\t%d",
                                                      //$v[$i * 2 + 38], $v[$i * 2 + 37],
                                                      $v[$i * 8 + 17], $v[$i * 8 + 18],
                                                      $v[$i * 8 + 19], $tmpMaxRate, $v[$i * 8 + 20]);

                                    }
                                    else if ($tmpMaxRate == 0)
                                    {
                                        $t5 = sprintf(//"TestCaseId,\tCaseName,\t%s,\t%%,\t%s&#10;" .
                                                      "#%d,\t%s,\t%d,\t%d%%,\t%d" .
                                                      "&#10;",
                                                      //$v[$i * 2 + 38], $v[$i * 2 + 37],
                                                      $v[$i * 8 + 13], $v[$i * 8 + 14],
                                                      $v[$i * 8 + 15], $tmpMinRate, $v[$i * 8 + 16]);
                                    }
                                    else
                                    {
                                        $t5 = sprintf(//"TestCaseId,\tCaseName,\t%s,\t%%,\t%s&#10;" .
                                                      "#%d,\t%s,\t%d,\t%d%%,\t%d&#10;" .
                                                      "#%d,\t%s,\t%d,\t%d%%,\t%d",
                                                      //$v[$i * 2 + 38], $v[$i * 2 + 37],
                                                      $v[$i * 8 + 13], $v[$i * 8 + 14],
                                                      $v[$i * 8 + 15], $tmpMinRate, $v[$i * 8 + 16],
                                                      $v[$i * 8 + 17], $v[$i * 8 + 18],
                                                      $v[$i * 8 + 19], $tmpMaxRate, $v[$i * 8 + 20]);
                                    }
                                }
                            }
                            
                            $t1 .= "<Cell ss:StyleID=\"" . $t3 . "\"><Data ss:Type=\"String\">" . $t2 . "</Data></Cell>\n";
                            
                            $t4 .= "<Cell ss:Index=\"" . (2 + $i) . 
                                   "\" ss:StyleID=\"s100\"><Data ss:Type=\"String\">" . $t5 . 
                                   "</Data></Cell>\n";
                            if (strlen($t5) > 0)
                            {
                                $hasContent = true;
                            }
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
            //$tmpFileHandle = fopen($_tmpFileName1, "r");
            //if ($tmpFileHandle !== false)
            //{
            //    fseek($tmpFileHandle, 0, SEEK_END);
            //    $n1 = ftell($tmpFileHandle);
            //    fseek($tmpFileHandle, 0, SEEK_SET);
            //    $onceBytes = 1024;
            //    while ($n1 > 0)
            //    {
            //        $n2 = $n1 > $onceBytes ? $onceBytes : $n1;
            //        $t1 = fread($tmpFileHandle, $n2);
            //        fwrite($_fileHandle, $t1);
            //        $n1 -= $n2;
            //    }
            //    fclose($tmpFileHandle);
            //}
            //*/
            // end this xml
            fwrite($_fileHandle, $_allSheetsEndTag);
            fclose($_fileHandle);
            
            
            
        }
        
        $returnSet = array();
        $returnSet["funcSuccess"] = true;
        $returnSet["sheetLinePos"] = $sheetLinePos;
        return $returnSet;
    }
    
	public function checkSkipReportGen($_reportType, $_reportFolder)
	{
        global $returnMsg;

        $b1 = false;
        if ($_reportType == 1)
        {
            // if gen history reports
            $oldReportList = array();
            $oldReportList[0] = glob($_reportFolder . "/*.tmp2");
            $oldReportList[1] = glob($_reportFolder . "/*.tmp");
            $oldReportList[2] = glob($_reportFolder . "/*.tmp1");
            
            $n1 = count($oldReportList[0]) + count($oldReportList[1]);
            if ($n1 > 0)
            {
                // if temp files in folder
                // do generate report

                $b1 = false;
                $returnMsg["checkcheck"] = 2;
            }
            else
            {
                $oldReportXMLList = glob($_reportFolder . "/*.zip");
                $returnMsg["checkcheckpath"] = $_reportFolder;
                if (count($oldReportXMLList) > 0)
                {
                    // if xml reports in folder and no temp files,
                    // skip generate
                    $b1 = true;
                    $returnMsg["checkcheck"] = 1;
                }
                else
                {
                    $b1 = false;
                    $returnMsg["checkcheck"] = 3;
                }
            }
        }
        //
        /*
        for ($i = 0; $i < count($oldReportList); $i++)
        {
            foreach ($oldReportList[$i] as $tmpPath)
            {
                //unlink($tmpPath);
            }
        }
        //*/
        
        if ($b1 == true)
        {
            // skip report
            $returnMsg["errorCode"] = 1;
            $returnMsg["compileFinished"] = 1;
            $returnMsg["errorMsg"] = "report finished";
            echo json_encode($returnMsg);
            return true;
        }
        
        return false;
    }
    
	public function checkAllReportsFinished($_db, $_resultPos, $_reportFolder, $_batchID)
	{
        global $returnMsg;
        global $resultIDList;

        $db = $_db;

        if ($_resultPos >= count($resultIDList[0]))
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
            
            /*
            $params1 = array($_batchID);
            $sql1 = "UPDATE mis_table_report_info " .
                    "SET gen_percent = \"100\", report_state=\"1\", finish_time=NOW() " .
                    "WHERE batch_id=?";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            //*/
            
            $returnMsg["errorCode"] = 1;
            $returnMsg["compileFinished"] = 1;
            $returnMsg["errorMsg"] = "report finished";
            echo json_encode($returnMsg);
            return null;
        }

        return true;
    }
    
	public function checkNeedCreateReportFile($_xmlFileName, $_tmpFileName, $_jsonFileName,
                                              $_umdNum, $_startResultID, $_cmpMachineID, $_resultPos,
                                              $_tempFileLineNumPos,
                                              $_curCardName, $_tmpSysName,
                                              $_cmpCardName, $_cmpSysName)
	{
        global $returnMsg;
        global $tempFileStartSheetLineNum;
        global $startStyleID;
        global $allStylesEndTag;
        global $appendStyleList;
        global $changeListNumList;
        global $resultIDList;
        global $reportTemplateDir;
        global $umdNameList;
        global $driverNameList;
        global $testNameList;
        global $subTestNumList;

        $tempFileLineNumPos = $_tempFileLineNumPos;
        if (file_exists($_xmlFileName)    == false)
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
            
            //$t3 = "";
            //$n1 = $startStyleID;
            //foreach ($appendStyleList as $tmpStyle)
            //{
            //    $t3 .= sprintf($tmpStyle, $n1);
            //    $n1++;
            //}
            //
            //fwrite($fileHandle, $t3);
            //fwrite($fileHandle, $allStylesEndTag);
            
            fclose($fileHandle);
            
            // feed report head info
            $tmpReportInfo = array("", "", "", "");
            $tmpReportUmdInfo = array("", "", "", "");
            for ($i = 0; $i < $_umdNum; $i++)
            {
                // loop all comparison batches of one umd
                $tmpReportInfo[$i] = "CL#" . $changeListNumList[0][$_startResultID + $i];
                $tmpReportUmdInfo[$i] = $driverNameList[0][$_startResultID + $i];
            }
            
            $returnMsg["genXmlHead"] = 1;
            
            // create tmp file
            $tempFileHandle = fopen($_tmpFileName, "w+");
            $xmlSection = "";
            $t1 = "";
            if ($_cmpMachineID != -1)
            {
                // if comparison with other card
                // $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002A1.txt");
                $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002A2.txt");
                // this sheet max 34 columns
                
                $curCardTitle = $_curCardName;
                $cmpCardTitle = $_cmpCardName;
                if ($_curCardName == $_cmpCardName)
                {
                    $curCardTitle .= "&#10;" . $_tmpSysName;
                    $cmpCardTitle .= "&#10;" . $_cmpSysName;
                }
                
                $t1 = sprintf($xmlSection, 34, $tempFileStartSheetLineNum,
                              $_cmpCardName . " - " . $_cmpSysName . " vs " . $_curCardName . " - " . $_tmpSysName,
                              $tmpReportUmdInfo[0], // api name
                              $tmpReportUmdInfo[1], //
                              $tmpReportUmdInfo[2], //
                              $curCardTitle,
                              $_cmpCardName . "&#10;vs&#10;" . $_curCardName,
                              $cmpCardTitle,
                              $curCardTitle,
                              $_cmpCardName . "&#10;vs&#10;" . $_curCardName,
                              $cmpCardTitle,
                              $curCardTitle,
                              $_cmpCardName . "&#10;vs&#10;" . $_curCardName,
                              $cmpCardTitle,
                              $tmpReportInfo[0],
                              $tmpReportInfo[1],
                              $tmpReportInfo[2]);
            }
            else
            {
                $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002A.txt");
                // this sheet max 21 columns
                $t1 = sprintf($xmlSection, 21, $tempFileStartSheetLineNum,
                              $_curCardName . " - " . $_tmpSysName,
                              $tmpReportInfo[0],
                              $tmpReportInfo[1],
                              $tmpReportInfo[2],
                              $tmpReportInfo[3]);
            }
            if (strlen($xmlSection) == 0)
            {
                fclose($tempFileHandle);
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "template file missing, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            $t2 = sprintf("\"%010d\"", $tempFileStartSheetLineNum);
            $n1 = strpos($t1, $t2);
            if ($n1 === false)
            {
                fclose($tempFileHandle);
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "template file content invalid, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            // line num pos - strlen("\"")
            $tempFileLineNumPos = 0 + $n1 + 1;
            fwrite($tempFileHandle, $t1);
            fclose($tempFileHandle);
            
            // create summary sheet temp file
            //if ($_cmpMachineID != -1)
            {
                if (file_exists($_jsonFileName) == false)
                {
                    $tmpObj = array();
                    foreach ($testNameList as $tmpName)
                    {
                        $tmpObj[$tmpName] = array(-1, -1, -1, -1, -1, -1,
                                                  0, 0, 0, 0, 0, 0,
                                                  0,
                                                  -1, "", 0, 0,
                                                  -1, "", 0, 0,
                                                  -1, "", 0, 0,
                                                  -1, "", 0, 0,
                                                  -1, "", 0, 0,
                                                  -1, "", 0, 0,
                                                  "", "",
                                                  "", "",
                                                  "", "");
                    }
                    $t1 = json_encode($tmpObj);
                    
                    file_put_contents($_jsonFileName, $t1);
                }
            }
        }
        $returnSet = array();
        $returnSet["tempFileLineNumPos"] = $tempFileLineNumPos;
        $returnSet["tempFileLineNumPos"] = $tempFileLineNumPos;
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

        $lineNumPos = $_lineNumPos;
        
        if (($_curTestPos     == $_firstTestPos) &&
            ($_nextSubTestPos == $_firstSubTestPos))
        {
            // start of each sheet
            $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet001A.txt");
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
            
            $returnMsg["tmpReportInfo"] = $tmpReportInfo;
            //$returnMsg["mainLineNameList"] = $mainLineNameList;
            
            $t1 = sprintf($xmlSection, $_tmpUmdName, $startSheetLineNum,
                          $tmpReportInfo[0], $tmpReportInfo[1], $tmpReportInfo[2], $tmpReportInfo[3],
                          $tmpReportInfo[4], $tmpReportInfo[5], $tmpReportInfo[6], $tmpReportInfo[7],
                          $tmpReportInfo[8], $tmpReportInfo[9], $tmpReportInfo[10], $tmpReportInfo[11],
                          $tmpReportInfo[12], $tmpReportInfo[13], $tmpReportInfo[14], $tmpReportInfo[15],
                          $tmpReportInfo[16], $tmpReportInfo[17], $tmpReportInfo[18], $tmpReportInfo[19],
                          $tmpReportInfo[20], $tmpReportInfo[21], $tmpReportInfo[22], $tmpReportInfo[23],
                          $tmpReportInfo[24], $tmpReportInfo[25], $tmpReportInfo[26], $tmpReportInfo[27],
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
    
	public function genAverageDataForGraph($_isCompStandard, $_cmpStartResultID,
                                           $_curCardName, $_cmpCardName, $_graphCells,
                                           $_tmpSysName, $_cmpSysName)
	{
        global $returnMsg;
        global $testNameList;
        global $subTestUmdDataMaskList;
        global $umdNum;
        global $subTestNumList;
        global $cmpSubTestNumList;
        global $umdOrder;
        global $resultUmdOrder;
        global $umdNameList;
        global $resultIDList;

        $graphCells = $_graphCells;
        // columns have values (if true, not blank in excel table)
        $averageColumnHasVal = array(false, false, false, false, false, false);
        if ($_isCompStandard)
        {
            // generate average data for graph
            $t1 = "";
            if ($_cmpStartResultID != -1)
            {
                $addCurSysName = $_curCardName == $_cmpCardName ? "-" . $_tmpSysName : "";
                $addCmpSysName = $_curCardName == $_cmpCardName ? "-" . $_cmpSysName : "";
                
                // if comparison with other cards
                $t1 = " <Cell ss:Index=\"19\" ss:StyleID=\"Default\"/>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">DX11-" . $_curCardName . "</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">DX12-" . $_curCardName . "</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">VULKAN-" . $_curCardName . "</Data></Cell>\n";
            
                $t1 .= " <Cell ss:Index=\"24\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">DX11-" . $_cmpCardName . "</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">DX12-" . $_cmpCardName . "</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">VULKAN-" . $_cmpCardName . "</Data></Cell>\n";
                $t1 .= " <Cell ss:Index=\"28\" ss:StyleID=\"Default\"/>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">DX11-" . $_curCardName . $addCurSysName . "</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">DX12-" . $_curCardName . $addCurSysName . "</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">VULKAN-" . $_curCardName . $addCurSysName . "</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">DX11-" . $_cmpCardName . $addCmpSysName . "</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">DX12-" . $_cmpCardName . $addCmpSysName . "</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">VULKAN-" . $_cmpCardName . $addCmpSysName . "</Data></Cell>\n";
            }
            else
            {
                // if no comparison
                $t1 = " <Cell ss:Index=\"13\" ss:StyleID=\"Default\"/>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">DX11</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">DX12</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">VULKAN</Data></Cell>\n";
                
                $t1 .= " <Cell ss:Index=\"18\" ss:StyleID=\"Default\"/>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">DX11</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">DX12</Data></Cell>\n";
                $t1 .= " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">VULKAN</Data></Cell>\n";
            }
            array_push($graphCells, $t1);
            
            $returnMsg["testNameList"] = $testNameList;
            $returnMsg["subTestNumList"] = $subTestNumList;
            $n1 = -1;
            
            $returnMsg["subTestNumList"] = $subTestNumList;
            $returnMsg["cmpSubTestNumList"] = $cmpSubTestNumList;
            
            for ($i = 0; $i < count($testNameList); $i++)
            {
                if (intval($subTestNumList[$i]) == 0)
                {
                    continue;
                }
                if ($_cmpStartResultID != -1)
                {
                    if (intval($cmpSubTestNumList[$i]) == 0)
                    {
                        // skip blank test in graph
                        continue;
                    }
                }

                $n2 = $n1 + $subTestNumList[$i] - 1;

                $t1 = "";
                if ($_cmpStartResultID != -1)
                {
                    // if comparison with other cards
                    
                    $reportUmdNum = count($umdNameList);
                    $tmpVal = array("", "", "", "", "", "");
                    $tmpValHas = array("", "", "", "", "", "");
                    $startIndex = -1;
                    
                    $tmpValHas[0] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C6:R[" . $n2 . "]C6)\">" .
                                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[1] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C9:R[" . $n2 . "]C9)\">" .
                                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[2] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C12:R[" . $n2 . "]C12)\">" .
                                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[3] = " <Cell ss:Index=\"24\" ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C8:R[" . $n2 . "]C8)\">" .
                                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[4] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C11:R[" . $n2 . "]C11)\">" .
                                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[5] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C14:R[" . $n2 . "]C14)\">" .
                                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                    
                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        $tmpVal[$j] = "<Cell ss:StyleID=\"Default\"></Cell>\n";
                        if ($j == 0)
                        {
                            // spaces between cur card & cmp card
                            $tmpVal[$reportUmdNum + $j] = " <Cell ss:Index=\"24\" ss:StyleID=\"Default\" />\n";
                        }
                        else
                        {
                            $tmpVal[$reportUmdNum + $j] = "<Cell ss:StyleID=\"Default\"></Cell>\n";
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
                                // $startIndex = $j;
                                $startIndex = 0;
                                $tmpMask = $subTestUmdDataMaskList[$i];
                                $checkMask = 1;
                                $tmpAdd = 0;
                                for ($l = 0; $l < $umdNum; $l++)
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
                        
                    $t1 = " <Cell ss:Index=\"19\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                          $testNameList[$i] . "</Data></Cell>\n";
                    $t1 .= $tmpVal[0];
                    $t1 .= $tmpVal[1];
                    $t1 .= $tmpVal[2];
                    
                    $t1 .= $tmpVal[3];
                    $t1 .= $tmpVal[4];
                    $t1 .= $tmpVal[5];
                           

                    $tmpValHas[0] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=RC[-9]/RC[-" . (9 - $startIndex) .
                                    "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[1] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=RC[-9]/RC[-" . (10 - $startIndex) .
                                    "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[2] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=RC[-9]/RC[-" . (11 - $startIndex) .
                                    "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[3] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=RC[-8]/RC[-" . (12 - $startIndex) .
                                    "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[4] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=RC[-8]/RC[-" . (13 - $startIndex) .
                                    "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[5] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=RC[-8]/RC[-" . (14 - $startIndex) .
                                    "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                    
                    
                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        $tmpVal[$j] = "<Cell ss:StyleID=\"Default\"></Cell>\n";
                        $tmpVal[$reportUmdNum + $j] = "<Cell ss:StyleID=\"Default\"></Cell>\n";
                        // cur card
                        if (($resultUmdOrder[$j] != -1) &&
                            ($resultUmdOrder[$reportUmdNum + $j] != -1))
                        {
                            // this column has data, needs be shown in Graph
                            $averageColumnHasVal[$j] = true;
                            
                            $tmpVal[$j] = $tmpValHas[$j];
                        }
                        
                        // cmp card
                        if (($resultUmdOrder[$j] != -1) &&
                            ($resultUmdOrder[$reportUmdNum + $j] != -1))
                        {
                            // this column has data, needs be shown in Graph
                            $averageColumnHasVal[$reportUmdNum + $j] = true;
                            
                            $tmpVal[$reportUmdNum + $j] = $tmpValHas[$reportUmdNum + $j];
                        }
                    }
                      
                    $t1 .= " <Cell ss:Index=\"28\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                           $testNameList[$i] . "</Data></Cell>\n";
                           
                    $t1 .= $tmpVal[0];
                    $t1 .= $tmpVal[1];
                    $t1 .= $tmpVal[2];
                    $t1 .= $tmpVal[3];
                    $t1 .= $tmpVal[4];
                    $t1 .= $tmpVal[5];
                    
                }
                else
                {
                    // if no comparison
                    
                    $reportUmdNum = count($umdNameList);
                    $tmpVal = array("", "", "");
                    $tmpValHas = array("", "", "");
                    $startIndex = -1;
                    
                    $tmpValHas[0] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C6:R[" . $n2 . "]C6)\">" .
                                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[1] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C8:R[" . $n2 . "]C8)\">" .
                                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[2] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C10:R[" . $n2 . "]C10)\">" .
                                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                    
                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        $tmpVal[$j] = "<Cell ss:StyleID=\"Default\"></Cell>\n";
                    }
                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        if ($resultUmdOrder[$j] != -1)
                        {
                            $tmpVal[$j] = $tmpValHas[$j];
                            //if ($startIndex == -1)
                            //{
                            //    $startIndex = $j;
                            //}
                            if ($startIndex == -1)
                            {
                                // $startIndex = $j;
                                $startIndex = 0;
                                $tmpMask = $subTestUmdDataMaskList[$i];
                                $checkMask = 1;
                                $tmpAdd = 0;
                                for ($l = 0; $l < $umdNum; $l++)
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
                            $tmpVal[$j] = "<Cell ss:StyleID=\"Default\"></Cell>\n";
                        }
                    }
                        
                    $t1 = " <Cell ss:Index=\"13\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                          $testNameList[$i] . "</Data></Cell>\n";
                    $t1 .= $tmpVal[0];
                    $t1 .= $tmpVal[1];
                    $t1 .= $tmpVal[2];
                    
                    $tmpValHas[0] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=RC[-5]/RC[-" . (5 - $startIndex) .
                                    "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[1] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=RC[-5]/RC[-" . (6 - $startIndex) .
                                    "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                    $tmpValHas[2] = " <Cell ss:StyleID=\"Default\" " .
                                    "ss:Formula=\"=RC[-5]/RC[-" . (7 - $startIndex) .
                                    "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                    
                    
                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        if ($resultUmdOrder[$j] != -1)
                        {
                            $tmpVal[$j] = $tmpValHas[$j];
                        }
                        else
                        {
                            $tmpVal[$j] = "<Cell ss:StyleID=\"Default\"></Cell>\n";
                        }
                    }
                    
                    $t1 .= " <Cell ss:Index=\"18\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                           $testNameList[$i] . "</Data></Cell>\n";
                    $t1 .= $tmpVal[0];
                    $t1 .= $tmpVal[1];
                    $t1 .= $tmpVal[2];
                        
                }
                array_push($graphCells, $t1);
                $n1 = $n2 + 2;
            }
            $returnMsg["graphCells"] = $graphCells;
        }

        $returnSet = array();
        $returnSet["graphCells"] = $graphCells;
        $returnSet["resultUmdOrder"] = $resultUmdOrder;
        $returnSet["averageColumnHasVal"] = $averageColumnHasVal;
        return $returnSet;
    }
    
	public function checkStartTest($_fileHandle, $_tempFileHandle,
                                   $_nextSubTestPos, $_firstSubTestPos, $_curTestPos, 
                                   $_isCompStandard, $_cmpMachineID,
                                   $_lineNum, $_sheetLinePos, $_tempLineNum)
	{
        global $returnMsg;
        global $startStyleID;
        global $graphCells;
        global $unitNameList;
        global $testNameList;
        global $subjectNameList;
        global $startGraphDataLinePos;

        $lineNum = $_lineNum;
        $sheetLinePos = $_sheetLinePos;
        $tempLineNum = $_tempLineNum;
        if ($_nextSubTestPos == $_firstSubTestPos)
        {
            // start of each test
            // black bar & test subject bar
            //$t1 = "<Row ss:StyleID=\"Default\">\n" .
            //      "<Cell ss:MergeAcross=\"11\" ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
            //      "</Row>\n";
                  
            $t1 = "<Row ss:StyleID=\"Default\">" .
                  // " <Cell ss:MergeAcross=\"1\" ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" . // 
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  "</Row>\n";
            
            $t1 .= "<Row ss:StyleID=\"Default\">\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 2) . "\"><Data ss:Type=\"String\">" .
                   "" . $testNameList[$_curTestPos] . "</Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 3) . "\"><Data ss:Type=\"String\">" .
                   "" . $subjectNameList[$_curTestPos] . "</Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                   "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                   "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                   "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                   "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                   "</Row>\n";
            fwrite($_fileHandle, $t1);
            
            $lineNum += 2;
            
            if ($_isCompStandard)
            {
                // write comparison to tmp file
                // start of each test
                // black bar & test subject bar
                //$t1 = "<Row ss:StyleID=\"Default\">\n" .
                //      "<Cell ss:MergeAcross=\"10\" ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n";
                      
                $t1 = "<Row ss:StyleID=\"Default\">" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n";
                      //"</Row>";
                      
                //if (count($machineIDPairList) > 0)
                if ($_cmpMachineID != -1)
                {
                    // if comparison with other cards
                    //$t1 .= "<Cell ss:Index=\"13\" ss:MergeAcross=\"4\" ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n";
                    
                    //$t1 .= "<Cell ss:Index=\"13\" ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n";
                    //$t1 .= "<Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n";
                    $t1 .= "<Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n";
                    $t1 .= "<Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n";
                    $t1 .= "<Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n";
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
                       
                $t3  = "<Row>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 2) . "\"><Data ss:Type=\"String\">" .
                       "" . $testNameList[$_curTestPos] . "</Data></Cell>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 3) . "\"><Data ss:Type=\"String\">" .
                       "" . $subjectNameList[$_curTestPos] . "</Data></Cell>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                       "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                       "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                       "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n";
                       
                //if (count($machineIDPairList) > 0)
                if ($_cmpMachineID != -1)
                {
                    // if comparison with other cards
                    
                    $t3  = "<Row>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 2) . "\"><Data ss:Type=\"String\">" .
                           "" . $testNameList[$_curTestPos] . "</Data></Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 3) . "\"><Data ss:Type=\"String\">" .
                           "" . $subjectNameList[$_curTestPos] . "</Data></Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                           "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                           "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                           "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                           "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                           "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                           "" . $unitNameList[$_curTestPos] . "</Data></Cell>\n";
                    
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
                $tempLineNum += 2;
            }
        }

        $returnSet = array();
        $returnSet["lineNum"] = $lineNum;
        $returnSet["sheetLinePos"] = $sheetLinePos;
        $returnSet["tempLineNum"] = $tempLineNum;
        return $returnSet;
    }
    
	public function getHistoryResultIDList($_resultPos)
	{
        global $returnMsg;
        global $resultIDList;
        global $cardNameList;
        global $sysNameList;
        global $driverNameList;
        
        $historyResultIDList = array();
        for ($i = 1; $i < count($resultIDList); $i++)
        {
            $cardNameKeys = array_keys($cardNameList[$i], $cardNameList[0][$_resultPos]);
            $sysNameKeys = array_keys($sysNameList[$i], $sysNameList[0][$_resultPos]);
            $driverNameKeys = array_keys($driverNameList[$i], $driverNameList[0][$_resultPos]);
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
                $returnMsg["commonKeys"] = $commonKeys;
                $returnMsg["resultIDList"] = $resultIDList[$i];
                array_push($historyResultIDList, $resultIDList[$i][$commonKeys[0]]);
            }
        }

        $returnSet = array();
        $returnSet["historyResultIDList"] = $historyResultIDList;
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
        global $cmpSubTestNumList;
        global $skipTestNameList;

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
                "(SELECT t1.test_name FROM mis_table_test_info t1 WHERE t1.test_id=t0.sub_id) AS subTestName " .
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
            $returnMsg["historyResultIDList"] = $historyResultIDList;
            $returnMsg["t3"] = $t3;
            $returnMsg["t1"] = $t1;
            echo json_encode($returnMsg);
            return null;
        }
        
        $dataNum = 0;
        $t1 = "";
        $standardSubTestIDList = array();
        $standardSubTestNameList = array();
        $standardTestCaseIDList = array();
        $tmpDataList = array("", "", "", "", "");

        while ($row1 = $db->fetchRow())
        {
            $dataValueXML = "";
            $tmpDataListXML = array("", "", "", "", "");
            
            $subTestID = $row1[0];
            $subTestName = $row1[3];
            $dataValue = "" . $row1[1];
            $testCaseID = $row1[2];
            if (strlen($dataValue) > 0)
            {
                $dataValueXML = "<Data ss:Type=\"Number\">" . $dataValue . "</Data>";
            }
            
            for ($i = 1; $i < count($resultIDList); $i++)
            {
                $n1 = 4 + $i - 1;
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
            // api sheet comparison
            $t1 .= "<Row ss:StyleID=\"Default\">\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 8) . "\"><Data ss:Type=\"String\">" . $testName . "</Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"><Data ss:Type=\"String\">" . $subTestName . "</Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" . $dataValueXML . "</Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                   "ss:Formula=\"=(RC6-RC8)/RC8\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" . $tmpDataListXML[0] . "</Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                   "ss:Formula=\"=(RC8-RC10)/RC10\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" . $tmpDataListXML[1] . "</Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                   "ss:Formula=\"=(RC10-RC12)/RC12\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" . $tmpDataListXML[2] . "</Cell>\n" .
                   "</Row>\n";

            $lineNum++;

            if ($_isCompStandard)
            {
                //$tmpPos = array_search($testName, );
                array_push($standardSubTestIDList, $subTestID);
                array_push($standardSubTestNameList, $subTestName);
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
        $returnSet["standardTestCaseIDList"] = $standardTestCaseIDList;
        return $returnSet;
    }
    
	public function writeReportCompareData($_db, $_tempFileHandle, $reportFolder,
                                           $_isCompStandard, $_umdNum, $_tempFileLineNumPos,
                                           $_startResultID, $_cmpStartResultID, $_tempLineNum,
                                           $_resultPos, $_sheetLinePos, $_startGraphDataLinePos,
                                           $_averageColumnHasVal)
	{
        global $returnMsg;
        global $resultIDList;
        global $driverNameList;
        global $umdNameList;
        global $umdOrder;
        global $tableName01;
        global $testName;
        global $testNameList;
        global $subTestNumList;
        global $subTestNum;
        global $startStyleID;
        global $standardSubTestIDList;
        global $standardSubTestNameList;
        global $standardTestCaseIDList;
        global $graphCells;
        global $curCardName;
        global $cmpCardName;
        global $tmpSysName;
        global $swtTempVBAConfigJsonName;
        global $graphDataStartCloumnIDCompareList;
        global $jsonFileName;
        global $reportTemplateDir;

        $db = $_db;
        $tempFileHandle = $_tempFileHandle;
        $umdNum = $_umdNum;
        $reportUmdNumn = count($umdNameList);
        $sheetLinePos = $_sheetLinePos;
        
        $graphDataArea = "" . graphDataStartCloumnID . 
                         graphDataStartLineID . ":" . 
                         graphDataEndCloumnID .
                         (intval(graphDataStartLineID) + count($graphCells) - 1);
        
        if ($_cmpStartResultID != -1)
        {
            $hasBlank = array_search(false, $_averageColumnHasVal);
            
            if ($hasBlank === false)
            {
                // all columns have no blank
                $graphDataArea = "" . graphDataStartCloumnIDCompare .
                                 graphDataStartLineID . ":" . 
                                 graphDataEndCloumnIDCompare .
                                 (intval(graphDataStartLineID) + count($graphCells) - 1);
            }
            else
            {
                // let Graph area skip blank
                $tmpArea = array();
                
                $t1 = "" . $graphDataStartCloumnIDCompareList[0] .
                           graphDataStartLineID . ":" . 
                           $graphDataStartCloumnIDCompareList[0] .
                           (intval(graphDataStartLineID) + count($graphCells) - 1);
                array_push($tmpArea, $t1);
                for ($i = 0; $i < count($_averageColumnHasVal); $i++)
                {
                    $t1 = "" . $graphDataStartCloumnIDCompareList[$i + 1] .
                               graphDataStartLineID . ":" . 
                               $graphDataStartCloumnIDCompareList[$i + 1] .
                               (intval(graphDataStartLineID) + count($graphCells) - 1);
                           
                    if ($_averageColumnHasVal[$i] == true)
                    {
                        array_push($tmpArea, $t1);
                    }
                }
                $graphDataArea = implode(",", $tmpArea);
            }
        }
        
        $tmpJson = array();
        $tmpJson["graphDataArea"] = $graphDataArea;
        $t1 = json_encode($tmpJson);
        
        $tmpdestPath = $reportFolder . "/" . $curCardName . "_" . $tmpSysName . "/" . $swtTempVBAConfigJsonName;
        file_put_contents($tmpdestPath, $t1);
        
        if ($_isCompStandard)
        {
            // umd data should occur as order in $umdNameList
            //$umdOrder = array(0, 1, 2, 0, 1, 2);
            
            // collect all data for all umd types
            $t2 = "";
            $selectKeyList = array();
            $n1 = 1;
            $dataIndexList = array(-1, -1, -1, -1, -1, -1);
            array_push($selectKeyList, "t0.data_value");
            $params1 = array();
            for ($i = 0; $i < $reportUmdNumn; $i++)
            {
                if ($i >= count($resultIDList[0]))
                {
                    //continue;
                }
                $n3 = array_search($driverNameList[0][$_startResultID + $i], $umdNameList);
                if ($n3 !== false)
                {
                    $umdOrder[$i] = $n3;
                    $umdOrder[$reportUmdNumn + $i] = $n3;
                }
                if (($_startResultID + $i) == $_resultPos)
                {
                    //continue;
                }
                $nextTabName = "t" . $n1;
                array_push($selectKeyList, $nextTabName . ".data_value");
                $t2 .= "LEFT JOIN " . $tableName01 . " " . $nextTabName . " " .
                       "ON (" . $nextTabName . ".result_id=? AND t0.sub_id=" . $nextTabName . ".sub_id) ";
                $dataIndexList[$i] = $n1;
                array_push($params1, $resultIDList[0][$_startResultID + $i]);
                $n1++;
            }
            if ($_cmpStartResultID != -1)
            {
                // if comparison with other cards
                for ($i = 0; $i < $umdNum; $i++)
                {
                    if ($i >= count($resultIDList[0]))
                    {
                        //continue;
                    }
                    if (($_startResultID + $i) == $_resultPos)
                    {
                        //continue;
                    }
                    $nextTabName = "t" . $n1;
                    array_push($selectKeyList, $nextTabName . ".data_value");
                    $t2 .= "LEFT JOIN " . $tableName01 . " " . $nextTabName . " " .
                           "ON (" . $nextTabName . ".result_id=? AND t0.sub_id=" . $nextTabName . ".sub_id) ";
                    $dataIndexList[$reportUmdNumn + $i] = $n1;
                    array_push($params1, $resultIDList[0][$_cmpStartResultID + $i]);
                    $n1++;
                }
            }
            
            $returnMsg["dataIndexList"] = $dataIndexList;
            $returnMsg["umdOrder"] = $umdOrder;
            $returnMsg["resultIDList[0]"] = $resultIDList[0];
            $returnMsg["driverNameList[0]"] = $driverNameList[0];
            $returnMsg["umdNameList"] = $umdNameList;
            $returnMsg["_startResultID"] = $_startResultID;
            $returnMsg["_cmpStartResultID"] = $_cmpStartResultID;
            
            array_push($params1, $resultIDList[0][$_resultPos]);
            $t4 = implode(",", $selectKeyList);
            $t1 = implode(",", $standardSubTestIDList);
            $sql1 = "SELECT " . $t4 . " FROM " . $tableName01 . " t0 " .
                    "" . $t2 . " " .
                    "WHERE t0.result_id=? AND t0.sub_id IN (" . $t1 . ") ORDER BY t0.data_id ASC";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                fclose($tempFileHandle);
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            
            $returnMsg["umdNameList"] = $umdNameList;
            $returnMsg["umdName"] = $umdNameList[1];
            $returnMsg["sql1"] = $sql1;
            $returnMsg["params1"] = $params1;
            $returnMsg["tmp002"] = "";
            
            
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
            $n1 = 0;
            while ($row1 = $db->fetchRow())
            {
                if ($n1 < 5)
                {
                    $t9 = implode(",", $row1);
                    $returnMsg["tmp002"] .= $t9;
                }
                
                $umdData = array("", "", "", "", "", "");
                $umdDataXML = array("", "", "", "", "", "");
                $umdDataVal = array(-1, -1, -1, -1, -1, -1);
                for ($j = 0; $j < $umdNum; $j++)
                {
                    if ($j >= count($resultIDList[0]))
                    {
                        //continue;
                    }
                    $umdData[$j] = "";
                    if ($dataIndexList[$j] != -1)
                    {
                        $umdData[$j] = "" . $row1[$dataIndexList[$j]];
                    }
                    
                    if (($curCardName     == "Fiji XT") &&
                        ($j == 1)    &&
                        (is_numeric($umdData[$j]) == true))
                    {
                        $umdData[$j] = "" . (floatval($umdData[$j]) / 1.000);
                    }
                    
                    if (strlen($umdData[$j]) > 0)
                    {
                        // if null value, leave it null
                        $umdDataXML[$j] = "<Data ss:Type=\"Number\">" . $umdData[$j] . "</Data>";
                        $umdDataVal[$j] = floatval($umdData[$j]);
                    }
                    //$dataVal[$j] = floatval($umdData[$j]);
                    
                    if ($_cmpStartResultID != -1)
                    {
                        $umdData[$reportUmdNumn + $j] = "";
                        if ($dataIndexList[$reportUmdNumn + $j] != -1)
                        {
                            $umdData[$reportUmdNumn + $j] = "" . $row1[$dataIndexList[$reportUmdNumn + $j]];
                        }
                        if (strlen($umdData[$reportUmdNumn + $j]) > 0)
                        {
                            // if null value, leave it null
                            $umdDataXML[$reportUmdNumn + $j] = "<Data ss:Type=\"Number\">" . $umdData[$reportUmdNumn + $j] . "</Data>";
                            $umdDataVal[$reportUmdNumn + $j] = floatval($umdData[$reportUmdNumn + $j]);
                        }
                    }
                }
                
                $tmpData = array("", "", "", "", "", "");
                $tmpDataVal = array(-1, -1, -1, -1, -1, -1);
                for ($i = 0; $i < $umdNum; $i++)
                {
                    if ($umdOrder[$i] != -1)
                    {
                        $tmpData[$i] = $umdDataXML[$umdOrder[$i]];
                        $tmpData[$reportUmdNumn + $i] = $umdDataXML[$reportUmdNumn + $umdOrder[$i]];
                        
                        $tmpDataVal[$i] = $umdDataVal[$umdOrder[$i]];
                        $tmpDataVal[$reportUmdNumn + $i] = $umdDataVal[$reportUmdNumn + $umdOrder[$i]];
                    }
                }
                
                $t3  = "<Row>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 8) . "\"><Data ss:Type=\"String\">" . $testName . "</Data></Cell>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"><Data ss:Type=\"String\">" .
                       "" . $standardSubTestNameList[$n1] . "</Data></Cell>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                       "" . $tmpData[0] . "</Cell>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                       "ss:Formula=\"=(RC8-RC6)/RC6\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                       "" . $tmpData[1] . "</Cell>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                       "ss:Formula=\"=(RC10-RC8)/RC8\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                       "" . $tmpData[2] . "</Cell>\n" .
                       " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                       "ss:Formula=\"=(RC10-RC6)/RC6\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                       
                $summaryDataVal = array(-1, -1, -1, -1, -1, -1);
                $cmpPartName = array("", "", "", "", "", "");
                if ($_cmpStartResultID != -1)
                {
                    $t3  = "<Row>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 8) . "\"><Data ss:Type=\"String\">" . $testName . "</Data></Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"><Data ss:Type=\"String\">" .
                           "" . $standardSubTestNameList[$n1] . "</Data></Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 1) . "\"/>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                           "" . $tmpData[0] . "</Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                           "ss:Formula=\"=(RC8-RC6)/RC6\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                           "" . $tmpData[3] . "</Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                           "" . $tmpData[1] . "</Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                           "ss:Formula=\"=(RC11-RC9)/RC9\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                           "" . $tmpData[4] . "</Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                           "" . $tmpData[2] . "</Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                           "ss:Formula=\"=(RC14-RC12)/RC12\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                           "" . $tmpData[5] . "</Cell>\n";
                           

                    $rateVal = array(-1, -1, -1);
                    if (($tmpDataVal[0] > 0) &&
                        ($tmpDataVal[3] > 0))
                    {
                        $rateVal[0] = ($tmpDataVal[3] - $tmpDataVal[0]) / $tmpDataVal[0];
                    }
                    if (($tmpDataVal[1] > 0) &&
                        ($tmpDataVal[4] > 0))
                    {
                        $rateVal[1] = ($tmpDataVal[4] - $tmpDataVal[1]) / $tmpDataVal[1];
                    }
                    if (($tmpDataVal[2] > 0) &&
                        ($tmpDataVal[5] > 0))
                    {
                        $rateVal[2] = ($tmpDataVal[5] - $tmpDataVal[2]) / $tmpDataVal[2];
                    }
                    $summaryDataVal = array($tmpDataVal[0], $tmpDataVal[3],
                                            $tmpDataVal[1], $tmpDataVal[4],
                                            $tmpDataVal[2], $tmpDataVal[5]);
                    $cmpPartName = array($curCardName, $cmpCardName,
                                         $curCardName, $cmpCardName,
                                         $curCardName, $cmpCardName);
                }
                else
                {
                    // no comparison card
                    $rateVal = array(-1, -1, -1);
                    if (($tmpDataVal[0] > 0) &&
                        ($tmpDataVal[1] > 0))
                    {
                        $rateVal[0] = ($tmpDataVal[1] - $tmpDataVal[0]) / $tmpDataVal[0];
                    }
                    if (($tmpDataVal[1] > 0) &&
                        ($tmpDataVal[2] > 0))
                    {
                        $rateVal[1] = ($tmpDataVal[2] - $tmpDataVal[1]) / $tmpDataVal[1];
                    }
                    if (($tmpDataVal[0] > 0) &&
                        ($tmpDataVal[2] > 0))
                    {
                        $rateVal[2] = ($tmpDataVal[2] - $tmpDataVal[0]) / $tmpDataVal[0];
                    }
                    $summaryDataVal = array($tmpDataVal[0], $tmpDataVal[1],
                                            $tmpDataVal[1], $tmpDataVal[2],
                                            $tmpDataVal[0], $tmpDataVal[2]);
                                            
                    $cmpPartName = array($umdNameList[0], $umdNameList[1],
                                         $umdNameList[1], $umdNameList[2],
                                         $umdNameList[0], $umdNameList[2]);
                }
                
                if (array_key_exists($testName, $summaryJson))
                {
                    // data for summary sheet
                    $tmpVal = $summaryJson[$testName];
                    $tmpValOld = $tmpVal;
                    // loss: testcaseid, testcasename,
                    // gain: testcaseid, testcasename,
                    // comp0, comp1,
                    $sumInfo = array(0, 0, 0, 0, // loss: testcase id, testcase name, loss: comp0, comp1
                                     0, 0, 0, 0, // gain: testcase id, testcase name, gain: comp0, comp1
                                     0, 0, 0, 0,
                                     0, 0, 0, 0,
                                     0, 0, 0, 0,
                                     0, 0, 0, 0);
                    $tmpVal[0] = ($tmpVal[0] == -1) ? $rateVal[0] : $tmpVal[0];
                    $tmpVal[1] = ($tmpVal[1] == -1) ? $rateVal[0] : $tmpVal[1];
                    $tmpVal[2] = ($tmpVal[2] == -1) ? $rateVal[1] : $tmpVal[2];
                    $tmpVal[3] = ($tmpVal[3] == -1) ? $rateVal[1] : $tmpVal[3];
                    $tmpVal[4] = ($tmpVal[4] == -1) ? $rateVal[2] : $tmpVal[4];
                    $tmpVal[5] = ($tmpVal[5] == -1) ? $rateVal[2] : $tmpVal[5];
                    
                    $rateVal2 = array(($rateVal[0] == -1) ? $tmpVal[0] : $rateVal[0],
                                      ($rateVal[0] == -1) ? $tmpVal[1] : $rateVal[0],
                                      ($rateVal[1] == -1) ? $tmpVal[2] : $rateVal[1],
                                      ($rateVal[1] == -1) ? $tmpVal[3] : $rateVal[1],
                                      ($rateVal[2] == -1) ? $tmpVal[4] : $rateVal[2],
                                      ($rateVal[2] == -1) ? $tmpVal[5] : $rateVal[2]);
                                      

                    
                    $tmpVariation = $variationJson["defaultVariation"];
                    if (array_key_exists($testName, $variationJson))
                    {
                        $tmpVariation = $variationJson[$testName];
                    }
                    // get test case up / down num for each test
                    $tmpVal[6] = (($rateVal[0] < $tmpVariation[0]) && ($rateVal[0] != -1)) ? ($tmpVal[6] + 1) : $tmpVal[6];
                    $tmpVal[7] = (($rateVal[0] > $tmpVariation[1]) && ($rateVal[0] != -1)) ? ($tmpVal[7] + 1) : $tmpVal[7];
                    $tmpVal[8] = (($rateVal[1] < $tmpVariation[0]) && ($rateVal[1] != -1)) ? ($tmpVal[8] + 1) : $tmpVal[8];
                    $tmpVal[9] = (($rateVal[1] > $tmpVariation[1]) && ($rateVal[1] != -1)) ? ($tmpVal[9] + 1) : $tmpVal[9];
                    $tmpVal[10] = (($rateVal[2] < $tmpVariation[0]) && ($rateVal[2] != -1)) ? ($tmpVal[10] + 1) : $tmpVal[10];
                    $tmpVal[11] = (($rateVal[2] > $tmpVariation[1]) && ($rateVal[2] != -1)) ? ($tmpVal[11] + 1) : $tmpVal[11];
                    
                    
                    $finalRateVal = array(min($rateVal2[0], $tmpVal[0]), max($rateVal2[1], $tmpVal[1]),
                                          min($rateVal2[2], $tmpVal[2]), max($rateVal2[3], $tmpVal[3]),
                                          min($rateVal2[4], $tmpVal[4]), max($rateVal2[5], $tmpVal[5]));
                    
                    
                    // test case id
                    $tmpChangeFlag = array(false, false,
                                           false, false,
                                           false, false);
                    $tmpChangeFlag[0] = $finalRateVal[0] != $tmpValOld[0] ? true : false;
                    $tmpChangeFlag[1] = $finalRateVal[1] != $tmpValOld[1] ? true : false;
                    $tmpChangeFlag[2] = $finalRateVal[2] != $tmpValOld[2] ? true : false;
                    $tmpChangeFlag[3] = $finalRateVal[3] != $tmpValOld[3] ? true : false;
                    $tmpChangeFlag[4] = $finalRateVal[4] != $tmpValOld[4] ? true : false;
                    $tmpChangeFlag[5] = $finalRateVal[5] != $tmpValOld[5] ? true : false;
                    
                    // test case id
                    $tmpVal[13] = $tmpChangeFlag[0] ? $standardTestCaseIDList[$n1] : $tmpVal[13];
                    $tmpVal[17] = $tmpChangeFlag[1] ? $standardTestCaseIDList[$n1] : $tmpVal[17];
                    $tmpVal[21] = $tmpChangeFlag[2] ? $standardTestCaseIDList[$n1] : $tmpVal[21];
                    $tmpVal[25] = $tmpChangeFlag[3] ? $standardTestCaseIDList[$n1] : $tmpVal[25];
                    $tmpVal[29] = $tmpChangeFlag[4] ? $standardTestCaseIDList[$n1] : $tmpVal[29];
                    $tmpVal[33] = $tmpChangeFlag[5] ? $standardTestCaseIDList[$n1] : $tmpVal[33];
                    
                    // test case name
                    $tmpVal[14] = $tmpChangeFlag[0] ? $standardSubTestNameList[$n1] : $tmpVal[14];
                    $tmpVal[18] = $tmpChangeFlag[1] ? $standardSubTestNameList[$n1] : $tmpVal[18];
                    $tmpVal[22] = $tmpChangeFlag[2] ? $standardSubTestNameList[$n1] : $tmpVal[22];
                    $tmpVal[26] = $tmpChangeFlag[3] ? $standardSubTestNameList[$n1] : $tmpVal[26];
                    $tmpVal[30] = $tmpChangeFlag[4] ? $standardSubTestNameList[$n1] : $tmpVal[30];
                    $tmpVal[34] = $tmpChangeFlag[4] ? $standardSubTestNameList[$n1] : $tmpVal[34];

                    // test case val
                    $tmpVal[15] = $tmpChangeFlag[0] ? $summaryDataVal[0] : $tmpVal[15];
                    $tmpVal[19] = $tmpChangeFlag[1] ? $summaryDataVal[0] : $tmpVal[19];
                    $tmpVal[23] = $tmpChangeFlag[2] ? $summaryDataVal[2] : $tmpVal[23];
                    $tmpVal[27] = $tmpChangeFlag[3] ? $summaryDataVal[2] : $tmpVal[27];
                    $tmpVal[31] = $tmpChangeFlag[4] ? $summaryDataVal[4] : $tmpVal[31];
                    $tmpVal[35] = $tmpChangeFlag[5] ? $summaryDataVal[4] : $tmpVal[35];
                    
                    $tmpVal[16] = $tmpChangeFlag[0] ? $summaryDataVal[1] : $tmpVal[16];
                    $tmpVal[20] = $tmpChangeFlag[1] ? $summaryDataVal[1] : $tmpVal[20];
                    $tmpVal[24] = $tmpChangeFlag[2] ? $summaryDataVal[3] : $tmpVal[24];
                    $tmpVal[28] = $tmpChangeFlag[3] ? $summaryDataVal[3] : $tmpVal[28];
                    $tmpVal[32] = $tmpChangeFlag[4] ? $summaryDataVal[5] : $tmpVal[32];
                    $tmpVal[36] = $tmpChangeFlag[5] ? $summaryDataVal[5] : $tmpVal[36];
                    
                    
                    $summaryJson[$testName] = array($finalRateVal[0], $finalRateVal[1],
                                                    $finalRateVal[2], $finalRateVal[3],
                                                    $finalRateVal[4], $finalRateVal[5],
                                                    $tmpVal[6], $tmpVal[7], 
                                                    $tmpVal[8], $tmpVal[9], 
                                                    $tmpVal[10], $tmpVal[11],
                                                    $subTestNum,
                                                    $tmpVal[13], $tmpVal[14], $tmpVal[15], $tmpVal[16], 
                                                    $tmpVal[17], $tmpVal[18], $tmpVal[19], $tmpVal[20],
                                                    $tmpVal[21], $tmpVal[22], $tmpVal[23], $tmpVal[24],
                                                    $tmpVal[25], $tmpVal[26], $tmpVal[27], $tmpVal[28],
                                                    $tmpVal[29], $tmpVal[30], $tmpVal[31], $tmpVal[32],
                                                    $tmpVal[33], $tmpVal[34], $tmpVal[35], $tmpVal[36],
                                                    $cmpPartName[0], $cmpPartName[1],
                                                    $cmpPartName[2], $cmpPartName[3],
                                                    $cmpPartName[4], $cmpPartName[5]);
                }
                else
                {
                    $summaryJson[$testName] = array($rateVal[0], $rateVal[0],
                                                    $rateVal[1], $rateVal[1],
                                                    $rateVal[2], $rateVal[2],
                                                    0, 0, 0, 0, 0, 0, 0,
                                                    -1, "", 0, 0,
                                                    -1, "", 0, 0,
                                                    -1, "", 0, 0,
                                                    -1, "", 0, 0,
                                                    -1, "", 0, 0,
                                                    -1, "", 0, 0,
                                                    "", "", // cmp 2 part name, like Vega10 vs Fiji XT, DX12 vs DX11
                                                    "", "",
                                                    "", "");
                }
                
                $t1 .= $t3;
                if (count($graphCells) > 0)
                {
                    $n2 = $sheetLinePos - $_startGraphDataLinePos;
                    if (($n2 >= 0) &&
                        ($n2 <  count($graphCells)))
                    {
                        // add average data for graph to right
                        $t1 .= $graphCells[$n2]; 
                    }
                }
                
                $t1 .= "</Row>\n";
                       
                $_tempLineNum++;
                $n1++;
                $sheetLinePos++;
            }
            
            fwrite($tempFileHandle, $t1);
            
            fseek($tempFileHandle, $_tempFileLineNumPos, SEEK_SET);
            // line num is 10 digis number, like: 0000000011
            $t1 = fread($tempFileHandle, 10);
            $n1 = intval($t1);
            $n1 += $_tempLineNum;
            //$sheetLinePos = $n1;
            fseek($tempFileHandle, $_tempFileLineNumPos, SEEK_SET);
            $t1 = sprintf("%010d", $n1);
            //$t1 = "1234567890";
            fwrite($tempFileHandle, $t1);
            
            //if ($_cmpStartResultID != -1)
            {
                // write summary sheet json
                $t1 = json_encode($summaryJson);
                file_put_contents($jsonFileName, $t1);
            }
        }

        $returnSet = array();
        $returnSet["sheetLinePos"] = $sheetLinePos;
        return $returnSet;
    }
}


?>