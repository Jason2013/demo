<?php

//include_once "swtExcelGenFuncs.php";
include_once "../generalLibs/dopdo.php";
include_once "../generalLibs/dodb.php";
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
                         "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n" .
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
                     //"<NumberFormat ss:Format=\"Fixed\"/>" .
                     "<NumberFormat ss:Format=\"0.000\"/>" .
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
                         //"<NumberFormat ss:Format=\"Fixed\"/>" .
                         "<NumberFormat ss:Format=\"0.000\"/>" .
                         "</Style>\n";
                         
        $styleVariance = "<Style ss:ID=\"s%d\">\n" .
                         "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Bottom\"/>\n" .
                         "<Borders/>\n" .
                         "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"12\" ss:Color=\"#000000\" " .
                         "ss:Bold=\"1\"/>\n" .
                         "<Interior ss:Color=\"#A6A6A6\" ss:Pattern=\"Solid\"/>\n" .
                         "</Style>\n";
                         
        $styleAverage =  "<Style ss:ID=\"s%d\">\n" .
                         "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Bottom\"/>\n" .
                         "<Borders>\n" .
                         "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                         "ss:Color=\"#000000\"/>\n" .
                         "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                         "ss:Color=\"#000000\"/>\n" .
                         "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                         "ss:Color=\"#000000\"/>\n" .
                         "</Borders>\n" .
                         "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" " .
                         "ss:Bold=\"1\"/>\n" .
                         "<Interior ss:Color=\"#FFC000\" ss:Pattern=\"Solid\"/>\n" .
                         "</Style>";
                         
        $styleOrdinal = "<Style ss:ID=\"s%d\">\n" .
                        "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Bottom\"/>\n" .
                        "<Borders>\n" .
                        "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                        " ss:Color=\"#000000\"/>\n" .
                        "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                        " ss:Color=\"#000000\"/>\n" .
                        "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                        " ss:Color=\"#000000\"/>\n" .
                        "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                        " ss:Color=\"#000000\"/>\n" .
                        "</Borders>\n" .
                        "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" " .
                        "ss:Bold=\"1\"/>\n" .
                        "<Interior ss:Color=\"#000000\" ss:Pattern=\"Solid\"/>\n" .
                        "</Style>\n";
                             
        $styleVarianceData = "<Style ss:ID=\"s%d\">\n" .
                             "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Bottom\"/>\n" .
                             "<Borders>\n" .
                             "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                             " ss:Color=\"#000000\"/>\n" .
                             "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                             " ss:Color=\"#000000\"/>\n" .
                             "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                             " ss:Color=\"#000000\"/>\n" .
                             "</Borders>\n" .
                             "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"12\" ss:Color=\"#000000\" " .
                             "ss:Bold=\"1\"/>\n" .
                             "<Interior ss:Color=\"#A6A6A6\" ss:Pattern=\"Solid\"/>\n" .
                             "<NumberFormat ss:Format=\"Percent\"/>\n" .
                             "</Style>\n";
                             
        $styleAverageData = "<Style ss:ID=\"s%d\">\n" .
                            "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n" .
                            "<Borders>\n" .
                            "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                            " ss:Color=\"#000000\"/>\n" .
                            "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                            " ss:Color=\"#000000\"/>\n" .
                            "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                            " ss:Color=\"#000000\"/>\n" .
                            "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" " .
                            " ss:Color=\"#000000\"/>\n" .
                            "</Borders>\n" .
                            "<Interior ss:Color=\"#FFC000\" ss:Pattern=\"Solid\"/>\n" .
                            //"<NumberFormat ss:Format=\"Fixed\"/>\n" .
                            "<NumberFormat ss:Format=\"0.000\"/>\n" .
                            "</Style>";
                            
        $styleSummaryTitle01 = "<Style ss:ID=\"s%d\">\n" .
                               "<Alignment ss:Horizontal=\"Left\" ss:Vertical=\"Center\"/>\n" .
                               "<Borders>\n" .
                               "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" " .
                               " ss:Color=\"#000000\"/>\n" .
                               "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" " .
                               " ss:Color=\"#000000\"/>\n" .
                               "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" " .
                               " ss:Color=\"#000000\"/>\n" .
                               "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" " .
                               " ss:Color=\"#000000\"/>\n" .
                               "</Borders>\n" .
                               "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"14\" ss:Color=\"#FFFFFF\" " .
                               "ss:Bold=\"1\"/>\n" .
                               "<Interior ss:Color=\"#800000\" ss:Pattern=\"Solid\"/>\n" .
                               "</Style>\n";
                               
        $styleSummaryTitle02 = "<Style ss:ID=\"s%d\">\n" .
                               "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\" ss:WrapText=\"1\"/>\n" .
                               "<Borders>\n" .
                               "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                               " ss:Color=\"#000000\"/>\n" .
                               "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                               " ss:Color=\"#000000\"/>\n" .
                               "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                               " ss:Color=\"#000000\"/>\n" .
                               "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                               " ss:Color=\"#000000\"/>\n" .
                               "</Borders>\n" .
                               "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"14\" ss:Color=\"#000000\" \n" .
                               "ss:Bold=\"1\"/>\n" .
                               "<Interior ss:Color=\"#FFC000\" ss:Pattern=\"Solid\"/>\n" .
                               "<NumberFormat/>\n" .
                               "<Protection/>\n" .
                               "</Style>\n";
                             
        $styleSummaryLine01 = "<Style ss:ID=\"s%d\">\n" .
                              "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n" .
                              "<Borders>\n" .
                              "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                              " ss:Color=\"#000000\"/>\n" .
                              "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                              " ss:Color=\"#000000\"/>\n" .
                              "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                              " ss:Color=\"#000000\"/>\n" .
                              "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                              " ss:Color=\"#000000\"/>\n" .
                              "</Borders>\n" .
                              "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#808080\" \n" .
                              "ss:Bold=\"1\"/>\n" .
                              "<Interior ss:Color=\"#A03300\" ss:Pattern=\"Solid\"/>\n" .
                              "</Style>\n";
        // green back
        $styleSummaryLine02 = "<Style ss:ID=\"s%d\">\n" .
                              "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n" .
                              "<Borders>\n" .
                              "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                              " ss:Color=\"#000000\"/>\n" .
                              "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                              " ss:Color=\"#000000\"/>\n" .
                              "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                              " ss:Color=\"#000000\"/>\n" .
                              "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                              " ss:Color=\"#000000\"/>\n" .
                              "</Borders>\n" .
                              "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"12\" ss:Color=\"#000000\"/>\n" .
                              "<Interior ss:Color=\"#D9D9D9\" ss:Pattern=\"Solid\"/>\n" .
                              "<NumberFormat ss:Format=\"Percent\"/>\n" .
                              "</Style>\n";
        // red back, white font
        $styleSummaryLine03 = "<Style ss:ID=\"s%d\">\n" .
                              "<Alignment ss:Horizontal=\"Left\" ss:Vertical=\"Center\"/>\n" .
                              "<Borders>\n" .
                              "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\"\n" .
                              " ss:Color=\"#000000\"/>\n" .
                              "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                              " ss:Color=\"#000000\"/>\n" .
                              "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                              " ss:Color=\"#000000\"/>\n" .
                              "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n" .
                              " ss:Color=\"#000000\"/>\n" .
                              "</Borders>\n" .
                              "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"12\" ss:Color=\"#FFFFFF\" \n" .
                              "ss:Bold=\"1\"/>\n" .
                              "<Interior ss:Color=\"#A03300\" ss:Pattern=\"Solid\"/>\n" .
                              "</Style>\n";
                              
        $stylePlatformInfoName = "<Style ss:ID=\"s%d\">\n" .
                                 "<Alignment ss:Horizontal=\"Right\" ss:Vertical=\"Center\"/>\n" .
                                 "<Borders>\n" .
                                 "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n" .
                                 " ss:Color=\"#000000\"/>\n" .
                                 "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n" .
                                 " ss:Color=\"#000000\"/>\n" .
                                 "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n" .
                                 " ss:Color=\"#000000\"/>\n" .
                                 "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n" .
                                 " ss:Color=\"#000000\"/>\n" .
                                 "</Borders>\n" .
                                 "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\"\n" .
                                 " ss:Bold=\"1\"/>\n" .
                                 "<Interior ss:Color=\"#800000\" ss:Pattern=\"Solid\"/>\n" .
                                 "</Style>\n";
        
        $stylePlatformInfoValue = "<Style ss:ID=\"s%d\">\n" .
                                  "<Alignment ss:Horizontal=\"Right\" ss:Vertical=\"Center\"/>\n" .
                                  "<Borders>\n" .
                                  "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n" .
                                  " ss:Color=\"#000000\"/>\n" .
                                  "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n" .
                                  " ss:Color=\"#000000\"/>\n" .
                                  "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n" .
                                  " ss:Color=\"#000000\"/>\n" .
                                  "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n" .
                                  " ss:Color=\"#000000\"/>\n" .
                                  "</Borders>\n" .
                                  "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#000000\"\n" .
                                  " ss:Bold=\"1\"/>\n" .
                                  "<Interior ss:Color=\"#FFFFA0\" ss:Pattern=\"Solid\"/>\n" .
                                  "</Style>\n";
                                  
        $styleSummaryTitle03 = "<Style ss:ID=\"s%d\">\n" .
                               "<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n" .
                               "<Borders>\n" .
                               "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" " .
                               " ss:Color=\"#000000\"/>\n" .
                               "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" " .
                               " ss:Color=\"#000000\"/>\n" .
                               "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" " .
                               " ss:Color=\"#000000\"/>\n" .
                               "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" " .
                               " ss:Color=\"#000000\"/>\n" .
                               "</Borders>\n" .
                               "<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"14\" ss:Color=\"#FFFFFF\" " .
                               "ss:Bold=\"1\"/>\n" .
                               "<Interior ss:Color=\"#800000\" ss:Pattern=\"Solid\"/>\n" .
                               "</Style>\n";
                               
             
        $appendStyleList = array($styleBlackBar, $styleBlank,
                                 $styleA, $styleB,
                                 $styleData, $styleRate,
                                 $styleBLeft, $styleDataLeft,
                                 $styleC, $styleD,
                                 $styleVariance, $styleAverage, $styleOrdinal,
                                 $styleVarianceData, $styleAverageData,
                                 $styleSummaryTitle01, $styleSummaryTitle02,
                                 $styleSummaryLine01, $styleSummaryLine02, $styleSummaryLine03,
                                 $stylePlatformInfoName, $stylePlatformInfoValue,
                                 $styleSummaryTitle03);
                                 
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
        $machineIDBatchPairList = array();
        $machineIDBatchPairMap = array();
        if ($crossType == 2)
        {
            // cross build
            $machineIDBatchPairList = $machineIDPairList;
            $machineIDPairList = array();
            
            for ($i = 0; $i < (count($machineIDBatchPairList) / 2); $i++)
            {
                $machineIDBatchPairMap["" . $machineIDBatchPairList[$i * 2]] = intval($machineIDBatchPairList[$i * 2 + 1]);
            }
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
        $returnSet["machineIDBatchPairList"] = $machineIDBatchPairList;
        $returnSet["machineIDBatchPairMap"] = $machineIDBatchPairMap;
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
        $batchDateTextList = array();

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
                //$sql1 = "SELECT batch_id, DATE_FORMAT(insert_time, \"%b %e\") FROM mis_table_batch_list " .
                //        "WHERE batch_state=\"1\" AND batch_group=\"5\" ORDER BY batch_id DESC LIMIT " . $historyBatchMaxNum;
                        
                $sql1 = "SELECT batch_id, batch_group FROM mis_table_batch_list " .
                        "WHERE batch_state=\"1\" AND " .
                        "(batch_group IN (5, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309)) " .
                        "ORDER BY batch_id DESC LIMIT 1";
                
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return null;
                }
                $row1 = $db->fetchRow();
                if ($row1 == false)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return null;
                }
                $tmpBatchGroup = intval($row1[1]);

                // shaderbench report
                $sql1 = "SELECT batch_id, DATE_FORMAT(insert_time, \"%b %e\") FROM mis_table_batch_list " .
                        "WHERE batch_state=\"1\" AND " .
                        "(batch_group=\"" . $tmpBatchGroup . "\") " .
                        "ORDER BY batch_id DESC LIMIT " . $historyBatchMaxNum;
            }
            else
            {
                // if assign current batch id
                //$params1 = array($_batchID);
                //$sql1 = "SELECT batch_id, DATE_FORMAT(insert_time, \"%b %e\") FROM mis_table_batch_list " .
                //        "WHERE batch_id<=? AND batch_state=\"1\" AND batch_group=\"5\" " .
                //        "ORDER BY batch_id DESC LIMIT " . $historyBatchMaxNum;
                
                $params1 = array($_batchID);
                $sql1 = "SELECT batch_id, batch_group FROM mis_table_batch_list " .
                        "WHERE batch_id=?";
                
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return null;
                }
                $row1 = $db->fetchRow();
                if ($row1 == false)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return null;
                }
                $tmpBatchGroup = intval($row1[1]);
                
                // routine report
                $params1 = array($_batchID);
                $sql1 = "SELECT batch_id, DATE_FORMAT(insert_time, \"%b %e\") FROM mis_table_batch_list " .
                        "WHERE batch_id<=? AND batch_state=\"1\" AND " .
                        "(batch_group=\"" . $tmpBatchGroup . "\") " .
                        "ORDER BY batch_id DESC LIMIT " . $historyBatchMaxNum;
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
                $sql1 = "SELECT t0.batch_id, DATE_FORMAT(t2.insert_time, \"%b %e\") FROM mis_table_user_batch_info t0 " .
                        "LEFT JOIN mis_table_batch_list t2 " .
                        "USING (batch_id) " .
                        "WHERE t0.user_id = ? AND t0.batch_id IN " .
                        "(SELECT t1.batch_id FROM mis_table_batch_list t1 " .
                        "WHERE t1.batch_state=\"1\" AND t1.batch_group=\"0\") " .
                        "ORDER BY t0.batch_id DESC LIMIT " . $historyBatchMaxNum . "";
            }
            else
            {
                // if assign current batch id
                $params1 = array($userID, $_batchID);
                $sql1 = "SELECT t0.batch_id, DATE_FORMAT(t2.insert_time, \"%b %e\") FROM mis_table_user_batch_info t0 " .
                        "LEFT JOIN mis_table_batch_list t2 " .
                        "USING (batch_id) " .
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
            array_push($batchDateTextList, $row1[1]);
        }
        if (count($batchIDList) > 0)
        {
            $batchID = $batchIDList[0];
        }
        
        for ($i = count($batchDateTextList); $i < $historyBatchMaxNum; $i++)
        {
            array_push($batchDateTextList, "");
        }
        
        $returnSet = array();
        $returnSet["batchID"] = $batchID;
        $returnSet["batchIDList"] = $batchIDList;
        $returnSet["batchDateTextList"] = $batchDateTextList;
        return $returnSet;
    }
    
	public function getColumnCardList($_db, $_batchID)
	{
        global $returnMsg;
        global $historyBatchMaxNum;
        global $resultPos;
        global $colCardNameOrderList;
        global $colCardNameOrderIndexList;
        
        $db = $_db;
        $batchID = $_batchID;
        
        $tmpCardNameList = array();
        $tmpSysNameList = array();
        $tmpDriverNameList = array();
        
        $params1 = array($_batchID);
        $sql1 = "SELECT t0.machine_id, t0.umd_id, t1.card_id, t1.sys_id, t2.env_name, t3.env_name, t4.env_name FROM mis_table_result_list t0 " .
                "LEFT JOIN mis_table_machine_info t1 " .
                "USING (machine_id) " .
                "LEFT JOIN mis_table_environment_info t2 " .
                "ON (t1.card_id=t2.env_id) " .
                "LEFT JOIN mis_table_environment_info t3 " .
                "ON (t1.sys_id=t3.env_id) " .
                "LEFT JOIN mis_table_environment_info t4 " .
                "ON (t0.umd_id=t4.env_id) " .
                "WHERE t0.batch_id=?";
                        
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
            echo json_encode($returnMsg);
            return null;
        }
        while ($row1 = $db->fetchRow())
        {
            array_push($tmpCardNameList, $row1[4]);
            array_push($tmpSysNameList, $row1[5]);
            array_push($tmpDriverNameList, $row1[6]);
        }
        
        $tmpCardNameList = array_unique($tmpCardNameList);
        $tmpSysNameList = array_unique($tmpSysNameList);
        $tmpDriverNameList = array_unique($tmpDriverNameList);
        
        $tmpList = array();
        
        foreach ($tmpSysNameList as $tmpSysName)
        {
            foreach ($tmpCardNameList as $tmpCardName)
            {
                $t1 = $tmpCardName . "_" . $tmpSysName;
                $tmpList []= $t1;
            }
        }
        
        $tmpMachineNum = count($tmpList);
        
        $tmpMachineIndex = intval($resultPos / $tmpMachineNum);
        
        $tmpCardSysNameList = array();
        
        $tmpList2 = array();
        
        foreach ($colCardNameOrderList as $k => $v)
        {
            if ($colCardNameOrderIndexList[$k] == $tmpMachineIndex)
            {
                $tmpCardSysNameList []= $colCardNameOrderList[$k];
                $tmpList2 []= $colCardNameOrderList[$k];
            }
        }
        
        foreach ($tmpList as $val)
        {
            if (array_search($val, $tmpList2) === false)
            {
                $tmpCardSysNameList []= $val;
            }
        }
        
        $tmpCardSysNameList []= "OPT1";
        
        $returnSet = array();
        $returnSet["colCardSysNameList"] = $tmpCardSysNameList;
        $returnSet["colCardNameList"] = $tmpCardNameList;
        $returnSet["colSysNameList"] = $tmpSysNameList;
        $returnSet["colDriverNameList"] = $tmpDriverNameList;
        return $returnSet;
    }
    
	public function getNoiseNum($_db, $_resultPos)
	{
        global $resultIDList;
        global $dataTables;
        
        $db = $_db;
        
        $tmpNoiseNum = 0;
        
        foreach ($dataTables as $tmpTableName)
        {
            $tableName02 = $tmpTableName . "_noise";
            
            $params1 = array($tableName02);
            $sql1 = "SELECT table_name FROM information_schema.TABLES " .
                    "WHERE table_name = ?;";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                echo json_encode($returnMsg);
                return;
            }
            
            $row1 = $db->fetchRow();
            if ($row1 == false)
            {
                // table not exist
                continue;
            }
            
            $tmpResultID = $resultIDList[0][$_resultPos];
            
            $params1 = array($tmpResultID);
            $sql1 = "SELECT MAX(noise_id) FROM " . $tableName02 . " " .
                    "WHERE result_id = ?;";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                echo json_encode($returnMsg);
                return;
            }
            
            $row1 = $db->fetchRow();
            if ($row1 == false)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                echo json_encode($returnMsg);
                return;
            }
            $tmpNoiseNum = intval($row1[0]);
            if ($tmpNoiseNum > 0)
            {
                break;
            }
        }
        return intval($tmpNoiseNum) + 1;
    }
    
	public function getStandardResultID($_startResultID, $_resultPos)
	{
        global $resultIDList;
        global $cardNameList;
        global $driverNameList;
        global $umdStandardOrder;
        global $reportUmdNum;
        global $uniqueUmdNameList;
        
        $tmpStandardUmdName = "";
        for ($i = 0; $i < count($umdStandardOrder); $i++)
        {
            // find a standard umd name
            $isSkip = false;
            for ($j = 0; $j < $reportUmdNum; $j++)
            {
                if ($umdStandardOrder[$i] == $driverNameList[0][$j])
                {
                    if ($resultIDList[0][$j] == PHP_INT_MAX)
                    {
                        // if API missing
                        $isSkip = true;
                        break;
                    }
                }
            }
            if ($isSkip)
            {
                continue;
            }
            $tmpPos = array_search($umdStandardOrder[$i], $uniqueUmdNameList);
            if ($tmpPos !== false)
            {
                $tmpStandardUmdName = $umdStandardOrder[$i];
                break;
            }
        }
        
        if (strlen($tmpStandardUmdName) == 0)
        {
            for ($i = 0; $i < count($resultIDList[0]); $i++)
            {
                if ($resultIDList[0][$i] != PHP_INT_MAX)
                {
                    return $i;
                }
            }
        }
        
        $tmpCardStandardResultPos = -1;
        
        for ($i = 0; $i < $reportUmdNum; $i++)
        {
            if (strcmp($tmpStandardUmdName, $driverNameList[0][$_startResultID + $i]) == 0)
            {
                $tmpCardStandardResultPos = $_startResultID + $i;
                break;
            }
        }
        
        if ($tmpCardStandardResultPos == -1)
        {
            for ($i = 0; $i < count($resultIDList[0]); $i++)
            {
                if ($resultIDList[0][$i] != PHP_INT_MAX)
                {
                    return $i;
                }
            }
        }
        
        return $tmpCardStandardResultPos;
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
                //"WHERE batch_id=? AND batch_state=\"1\" AND (batch_group=\"5\")";
                "WHERE batch_id=? AND batch_state=\"1\" AND (batch_group IN (5, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309))";
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
                "(SELECT t3.test_name FROM mis_table_test_info t3 WHERE t3.test_id=t0.unit_id) AS unitName, " .
                "(SELECT t4.test_filter FROM mis_table_test_info t4 WHERE t4.test_id=t0.subject_id) AS subjectFilterName " .
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
        $subjectFilterNameList = array();
        $subjectNameFilterNumList = array();
        $unitNameList = array();
        $subjectNameFilterNumMax = 0;

        while ($row1 = $db->fetchRow())
        {
            array_push($testNameList, $row1[7]);
            array_push($subjectNameList, $row1[8]);
            $subjectNameFilterNum = intval($row1[6]);
            array_push($subjectNameFilterNumList, $subjectNameFilterNum);
            $subjectNameFilterNumMax = $subjectNameFilterNumMax < $subjectNameFilterNum ? 
                                       $subjectNameFilterNum : $subjectNameFilterNumMax;
            array_push($unitNameList, $row1[9]);
            array_push($subjectFilterNameList, explode("|", $row1[10]));
        }
        
        $returnSet = array();
        $returnSet["testNameList"] = $testNameList;
        $returnSet["subjectNameList"] = $subjectNameList;
        $returnSet["unitNameList"] = $unitNameList;
        $returnSet["subjectNameFilterNumMax"] = $subjectNameFilterNumMax;
        $returnSet["subjectNameFilterNumList"] = $subjectNameFilterNumList;
        $returnSet["subjectFilterNameList"] = $subjectFilterNameList;
        return $returnSet;
    }
    
	public function getBatchEnvironmentInfo($_db, $_batchID)
	{
        global $returnMsg;
        global $logStoreDir;
        
        $db = $_db;
        $params1 = array($_batchID);
        $sql1 = "SELECT t0.path_id, t1.path_name " .
                "FROM mis_table_batch_list t0 " .
                "LEFT JOIN mis_table_path_info t1 " .
                "USING (path_id) " .
                "WHERE t0.batch_id=?";
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
        
        $logFileFolder = $row1[1];
        
        $tmpPath = $logStoreDir . "/" . $logFileFolder . "/" . "default_info.json";
        
        $envDefaultInfo = array();
        
        if (file_exists($tmpPath))
        {
            $t1 = file_get_contents($tmpPath);
            
            $tmpObj = json_decode($t1);
            
            foreach ($tmpObj as $tmpKey => $tmpVal)
            {
                $envDefaultInfo[$tmpKey] = $tmpVal;
            }
        }
        
        $params1 = array($_batchID);
        $sql1 = "SELECT insert_time FROM mis_table_batch_list WHERE batch_id=?";
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
        
        $tmpArr = explode(" ", $row1[0]);
        $tmpDate = "";
        $tmpTime = "";
        if (count($tmpArr) >= 2)
        {
            $tmpDate = $tmpArr[0];
            $tmpTime = $tmpArr[1];
        }
        
        $envDefaultInfo["testingDate"] = $tmpDate;
        $envDefaultInfo["testingTime"] = $tmpTime;
        
        $returnSet = array();
        $returnSet["envDefaultInfo"] = $envDefaultInfo;
        $returnSet["logFileFolder"] = $logFileFolder;
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
        global $crossType;
        global $machineIDBatchPairList;
        global $swtOldUmdNameMatchList;
        
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
        $machineNameList = array();
        $sysMemNameList = array();
        
        $cardNameRealList = array();
        $sysNameRealList = array();
        
        $cardNameListFlat = array();
        $driverNameListFlat = array();
        
        $umdIndexListFlat = array();
        $tmpCardIDListFlat = array();
        $tmpSysIDListFlat = array();
        $cardIndexListFlat = array();
        $curCardIDListFlat = array();

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
            $tmpMachineNameList = array();
            $tmpSysMemNameList = array();
            
            $tmpCardNameRealList = array();
            $tmpSysNameRealList = array();
            
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
                    "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.gpu_mem_id) AS gpuMemName, " .
                    "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.name_id) AS machineName, " .
                    "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.mem_id) AS sysMemName " .
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
            $curSysID = -1;
            $umdNum = count($umdNameList);
            
            //$usedSysList = array();
            //$sysIndex = 0;
            //$maxSysNum = 3;
            
            $usedDriverList = array();
            $driverIndex = 0;
            $maxDriverNum = 6;
            
            for ($j = 0; $j < $umdNum * $maxDriverNum; $j++)
            {
                $tmpResultIDList[$j] = PHP_INT_MAX;
                $tmpMachineIDList[$j] = PHP_INT_MAX;
                // same for all machines, for single report name
                $tmpCardNameList[$j] = "";
                $tmpDriverNameList[$j] = $umdNameList[$j % $umdNum];
                $tmpChangeListNumList[$j] = PHP_INT_MAX;
                $tmpCpuNameList[$j] = "";
                // same for all machines, for single report name
                $tmpSysNameList[$j] = "";
                $tmpMainLineNameList[$j] = "";
                $tmpSClockNameList[$j] = "";
                $tmpMClockNameList[$j] = "";
                $tmpGpuMemNameList[$j] = "";
                $tmpResultTimeList[$j] = "";
                $tmpMachineNameList[$j] = "";
                $tmpSysMemNameList[$j] = "";
                
                // real machine info
                $tmpCardNameRealList[$j] = "";
                $tmpSysNameRealList[$j] = "";
            }
            
            while ($row1 = $db->fetchRow())
            {
                $tmpMachineID = intval($row1[1]);
                
                array_push($cardNameListFlat, $row1[20]);
                array_push($driverNameListFlat, $row1[21]);
                
                array_push($umdIndexListFlat, $umdIndex);
                array_push($tmpCardIDListFlat, $row1[10]);
                array_push($tmpSysIDListFlat, $row1[12]);
                array_push($cardIndexListFlat, $cardIndex);
                array_push($curCardIDListFlat, $curCardID);
                
                //$tmpCardID = intval($row1[10]);
                //$tmpSysID = intval($row1[12]);
                //$tmpKeys1 = array_keys($selectedCardIDList, $tmpCardID);
                //$tmpKeys2 = array_keys($selectedSysIDList, $tmpSysID);
                //$tmpKeys3 = array_intersect($tmpKeys1, $tmpKeys2);
                //if (count($tmpKeys3) == 0)
                //{
                //    // skip unselected cards
                //    continue;
                //}
                //$tmpDriverName = $row1[21];
                $tmpCardSysName = $row1[20] . "_" . $row1[23];
                
                //if ($umdIndex == 0)
                //{
                //    $curCardID = $tmpCardID;
                //    $curSysID = $tmpSysID;
                //    $cardIndex++;
                //    // hold enough space
                //    for ($j = 0; $j < $umdNum; $j++)
                //    {
                //        //array_push($tmpResultIDList, PHP_INT_MAX);
                //        //array_push($tmpMachineIDList, PHP_INT_MAX);
                //        //array_push($tmpCardNameList, $row1[20]);
                //        //array_push($tmpDriverNameList, $umdNameList[$j]);
                //        //array_push($tmpChangeListNumList, PHP_INT_MAX);
                //        //array_push($tmpCpuNameList, "");
                //        //array_push($tmpSysNameList, $row1[23]);
                //        //array_push($tmpMainLineNameList, "");
                //        //array_push($tmpSClockNameList, "");
                //        //array_push($tmpMClockNameList, "");
                //        //array_push($tmpGpuMemNameList, "");
                //        //array_push($tmpResultTimeList, "");
                //        //array_push($tmpMachineNameList, "");
                //        //array_push($tmpSysMemNameList, "");
                //        
                //        //$tmpResultIDList[$j] = PHP_INT_MAX;
                //        //$tmpMachineIDList[$j] = PHP_INT_MAX;
                //        //$tmpCardNameList[$j] = $row1[20];
                //        //$tmpDriverNameList[$j] = $umdNameList[$j];
                //        //$tmpChangeListNumList[$j] = PHP_INT_MAX;
                //        //$tmpCpuNameList[$j] = "";
                //        //$tmpSysNameList[$j] = $row1[23];
                //        //$tmpMainLineNameList[$j] = "";
                //        //$tmpSClockNameList[$j] = "";
                //        //$tmpMClockNameList[$j] = "";
                //        //$tmpGpuMemNameList[$j] = "";
                //        //$tmpResultTimeList[$j] = "";
                //        //$tmpMachineNameList[$j] = "";
                //        //$tmpSysMemNameList[$j] = "";
                //        
                //        //$tmpCardNameList[$j] = $row1[20];
                //        //$tmpSysNameList[$j] = $row1[23];
                //    }
                //}
                //else
                //{
                //    if (($curCardID != $tmpCardID) ||
                //        ($curSysID  != $tmpSysID))
                //    {
                //        // next card
                //        // e.g. tmpCardNameList:   jan26, jan31
                //        //      tmpDriverNameList: DX12, DX12
                //        $curCardID = $tmpCardID;
                //        $curSysID = $tmpSysID;
                //        $cardIndex++;
                //        // hold enough space
                //        for ($j = 0; $j < $umdNum; $j++)
                //        {
                //            //array_push($tmpResultIDList, PHP_INT_MAX);
                //            //array_push($tmpMachineIDList, PHP_INT_MAX);
                //            //array_push($tmpCardNameList, $row1[20]);
                //            //array_push($tmpDriverNameList, $umdNameList[$j]);
                //            //array_push($tmpChangeListNumList, PHP_INT_MAX);
                //            //array_push($tmpCpuNameList, "");
                //            //array_push($tmpSysNameList, $row1[23]);
                //            //array_push($tmpMainLineNameList, "");
                //            //array_push($tmpSClockNameList, "");
                //            //array_push($tmpMClockNameList, "");
                //            //array_push($tmpGpuMemNameList, "");
                //            //array_push($tmpResultTimeList, "");
                //            //array_push($tmpMachineNameList, "");
                //            //array_push($tmpSysMemNameList, "");
                //            
                //            //$tmpResultIDList[$j] = PHP_INT_MAX;
                //            //$tmpMachineIDList[$j] = PHP_INT_MAX;
                //            //$tmpCardNameList[$j] = $row1[20];
                //            //$tmpDriverNameList[$j] = $umdNameList[$j];
                //            //$tmpChangeListNumList[$j] = PHP_INT_MAX;
                //            //$tmpCpuNameList[$j] = "";
                //            //$tmpSysNameList[$j] = $row1[23];
                //            //$tmpMainLineNameList[$j] = "";
                //            //$tmpSClockNameList[$j] = "";
                //            //$tmpMClockNameList[$j] = "";
                //            //$tmpGpuMemNameList[$j] = "";
                //            //$tmpResultTimeList[$j] = "";
                //            //$tmpMachineNameList[$j] = "";
                //            //$tmpSysMemNameList[$j] = "";
                //            
                //            //$tmpCardNameList[$j] = $row1[20];
                //            //$tmpSysNameList[$j] = $row1[23];
                //        }
                //        $umdIndex = 0;
                //    }
                //}

                $tmpIndex = array_search($tmpCardSysName, $umdNameList);

                if ($tmpIndex !== false)
                {
                    //$n1 = $cardIndex * $umdNum + $tmpIndex;
                    //$n1 = $tmpIndex;
                    
                    $tmpDriverName = $row1[21];
                    $driverIndex = array_search($tmpDriverName, $usedDriverList);
                    if ($driverIndex === false)
                    {
                        $driverIndex = count($usedDriverList);
                        $usedDriverList []= $tmpDriverName;
                    }
                    $n1 = $driverIndex * $umdNum + $tmpIndex;
                    
                    $needUpdate = false;
                    for ($j = 0; $j < $umdNum; $j++)
                    {
                        if(strlen($tmpCardNameList[$driverIndex * $umdNum + $j]) == 0)
                        {
                            $needUpdate = true;
                            break;
                        }
                        if(strlen($tmpSysNameList[$driverIndex * $umdNum + $j]) == 0)
                        {
                            $needUpdate = true;
                            break;
                        }
                    }
                    
                    if ($needUpdate)
                    {
                        for ($j = 0; $j < $umdNum; $j++)
                        {
                            $tmpCardNameList[$driverIndex * $umdNum + $j] = $row1[20];
                            $tmpSysNameList[$driverIndex * $umdNum + $j] = $row1[23];
                        }
                    }

                    
                    $tmpResultIDList[$n1] = $row1[0];
                    $tmpMachineIDList[$n1] = $row1[1];
                    //$tmpCardNameList[$n1] = $row1[20];
                    //$tmpDriverNameList[$n1] = $row1[21];
                    $tmpDriverNameList[$n1] = $tmpCardSysName;
                    $tmpChangeListNumList[$n1] = $row1[4];
                    $tmpCpuNameList[$n1] = $row1[22];
                    //$tmpSysNameList[$n1] = $row1[23];
                    $tmpMainLineNameList[$n1] = $row1[24];
                    $tmpSClockNameList[$n1] = $row1[25];
                    $tmpMClockNameList[$n1] = $row1[26];
                    $tmpGpuMemNameList[$n1] = $row1[27];
                    $tmpResultTimeList[$n1] = $row1[7];
                    $tmpMachineNameList[$n1] = $row1[28];
                    $tmpSysMemNameList[$n1] = $row1[29];
                    
                    $tmpCardNameRealList[$n1] = $row1[20];
                    $tmpSysNameRealList[$n1] = $row1[23];
                }
                //if ($umdIndex != $tmpIndex)
                //{
                //    $umdIndex = $tmpIndex;
                //}
                //
                //$umdIndex++;
                //if ($umdIndex >= count($umdNameList))
                //{
                //    $umdIndex = 0;
                //}
            }
            
            //$usedSysNum = count($usedSysList);
            $usedDriverNum = count($usedDriverList);
            if ($usedDriverNum != $maxDriverNum)
            {
                // shrink array
                $tmpResultIDList = array_slice($tmpResultIDList, 0, $usedDriverNum * $umdNum);
                $tmpMachineIDList = array_slice($tmpMachineIDList, 0, $usedDriverNum * $umdNum);
                $tmpCardNameList = array_slice($tmpCardNameList, 0, $usedDriverNum * $umdNum);
                $tmpDriverNameList = array_slice($tmpDriverNameList, 0, $usedDriverNum * $umdNum);
                $tmpChangeListNumList = array_slice($tmpChangeListNumList, 0, $usedDriverNum * $umdNum);
                $tmpCpuNameList = array_slice($tmpCpuNameList, 0, $usedDriverNum * $umdNum);
                $tmpSysNameList = array_slice($tmpSysNameList, 0, $usedDriverNum * $umdNum);
                $tmpMainLineNameList = array_slice($tmpMainLineNameList, 0, $usedDriverNum * $umdNum);
                $tmpSClockNameList = array_slice($tmpSClockNameList, 0, $usedDriverNum * $umdNum);
                $tmpMClockNameList = array_slice($tmpMClockNameList, 0, $usedDriverNum * $umdNum);
                $tmpGpuMemNameList = array_slice($tmpGpuMemNameList, 0, $usedDriverNum * $umdNum);
                $tmpResultTimeList = array_slice($tmpResultTimeList, 0, $usedDriverNum * $umdNum);
                $tmpMachineNameList = array_slice($tmpMachineNameList, 0, $usedDriverNum * $umdNum);
                $tmpSysMemNameList = array_slice($tmpSysMemNameList, 0, $usedDriverNum * $umdNum);
                $tmpCardNameRealList = array_slice($tmpCardNameRealList, 0, $usedDriverNum * $umdNum);
                $tmpSysNameRealList = array_slice($tmpSysNameRealList, 0, $usedDriverNum * $umdNum);
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
            array_push($machineNameList, $tmpMachineNameList);
            array_push($sysMemNameList, $tmpSysMemNameList);
            
            array_push($cardNameRealList, $tmpCardNameRealList);
            array_push($sysNameRealList, $tmpSysNameRealList);
        }
        
        
        $crossBuildResultIDList = array();
        $crossBuildMachineIDList = array();
        $crossBuildCardNameList = array();
        $crossBuildDriverNameList = array();
        $crossBuildChangeListNumList = array();
        $crossBuildCpuNameList = array();
        $crossBuildSysNameList = array();
        $crossBuildMainLineNameList = array();
        $crossBuildSClockNameList = array();
        $crossBuildMClockNameList = array();
        $crossBuildGpuMemNameList = array();
        $crossBuildResultTimeList = array();
        $crossBuildMachineNameList = array();
        $crossBuildSysMemNameList = array();
        
        if ($crossType == 2)
        {
            // cross build

            for ($i = 0; $i < (count($machineIDBatchPairList) / 2); $i++)
            {
                $tmpMachineID = $machineIDBatchPairList[$i * 2];
                $tmpBatchID = $machineIDBatchPairList[$i * 2 + 1];
                
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
                $tmpMachineNameList = array();
                $tmpSysMemNameList = array();
                
                $params1 = array($tmpBatchID, $tmpMachineID);
                $sql1 = "SELECT t0.*, " .
                        "t1.*, " .
                        "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.card_id) AS cardName, " .
                        "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t0.umd_id) AS umdName, " .
                        "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.cpu_id) AS cpuName, " .
                        "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.sys_id) AS sysName, " .
                        "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.ml_id) AS mlName, " .
                        "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.s_clock_id) AS sClockName, " .
                        "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.m_clock_id) AS mClockName, " .
                        "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.gpu_mem_id) AS gpuMemName, " .
                        "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.name_id) AS machineName, " .
                        "(SELECT t2.env_name FROM mis_table_environment_info t2 WHERE t2.env_id=t1.mem_id) AS sysMemName " .
                        "FROM mis_table_result_list t0 " .
                        "LEFT JOIN mis_table_machine_info t1 " .
                        "USING (machine_id) " .
                        "WHERE batch_id = ? AND machine_id = ? ORDER BY t0.umd_id ASC";
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
                $curSysID = -1;
                $umdNum = count($umdNameList);
                while ($row1 = $db->fetchRow())
                {
                    $tmpMachineID = intval($row1[1]);
                    
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
                        $curSysID = $tmpSysID;
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
                            array_push($tmpMachineNameList, "");
                            array_push($tmpSysMemNameList, "");
                        }
                    }
                    else
                    {
                        if (($curCardID != $tmpCardID) ||
                            ($curSysID  != $tmpSysID))
                        {
                            // next card
                            // e.g. tmpCardNameList:   jan26, jan31
                            //      tmpDriverNameList: DX12, DX12
                            $curCardID = $tmpCardID;
                            $curSysID = $tmpSysID;
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
                                array_push($tmpMachineNameList, "");
                                array_push($tmpSysMemNameList, "");
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
                        $tmpMachineNameList[$n1] = $row1[28];
                        $tmpSysMemNameList[$n1] = $row1[29];
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
                array_push($crossBuildResultIDList, $tmpResultIDList);
                array_push($crossBuildMachineIDList, $tmpMachineIDList);
                array_push($crossBuildCardNameList, $tmpCardNameList);
                array_push($crossBuildDriverNameList, $tmpDriverNameList);
                array_push($crossBuildChangeListNumList, $tmpChangeListNumList);
                array_push($crossBuildCpuNameList, $tmpCpuNameList);
                array_push($crossBuildSysNameList, $tmpSysNameList);
                array_push($crossBuildMainLineNameList, $tmpMainLineNameList);
                array_push($crossBuildSClockNameList, $tmpSClockNameList);
                array_push($crossBuildMClockNameList, $tmpMClockNameList);
                array_push($crossBuildGpuMemNameList, $tmpGpuMemNameList);
                array_push($crossBuildResultTimeList, $tmpResultTimeList);
                array_push($crossBuildMachineNameList, $tmpMachineNameList);
                array_push($crossBuildSysMemNameList, $tmpSysMemNameList);
            }
            
        }
        
        $returnMsg["cardNameListFlat"] = $cardNameListFlat;
        $returnMsg["driverNameListFlat"] = $driverNameListFlat;
        
        $returnMsg["umdIndexListFlat"] = $umdIndexListFlat;
        $returnMsg["tmpCardIDListFlat"] = $tmpCardIDListFlat;
        $returnMsg["tmpSysIDListFlat"] = $tmpSysIDListFlat;
        $returnMsg["cardIndexListFlat"] = $cardIndexListFlat;
        $returnMsg["curCardIDListFlat"] = $curCardIDListFlat;
        
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
        $returnSet["machineNameList"] = $machineNameList;
        $returnSet["sysMemNameList"] = $sysMemNameList;
        
        $returnSet["cardNameRealList"] = $cardNameRealList;
        $returnSet["sysNameRealList"] = $sysNameRealList;
        
        $returnSet["crossBuildResultIDList"] =      $crossBuildResultIDList;
        $returnSet["crossBuildMachineIDList"] =     $crossBuildMachineIDList;
        $returnSet["crossBuildCardNameList"] =      $crossBuildCardNameList;
        $returnSet["crossBuildDriverNameList"] =    $crossBuildDriverNameList;
        $returnSet["crossBuildChangeListNumList"] = $crossBuildChangeListNumList;
        $returnSet["crossBuildCpuNameList"] =       $crossBuildCpuNameList;
        $returnSet["crossBuildSysNameList"] =       $crossBuildSysNameList;
        $returnSet["crossBuildMainLineNameList"] =  $crossBuildMainLineNameList;
        $returnSet["crossBuildSClockNameList"] =    $crossBuildSClockNameList;
        $returnSet["crossBuildMClockNameList"] =    $crossBuildMClockNameList;
        $returnSet["crossBuildGpuMemNameList"] =    $crossBuildGpuMemNameList;
        $returnSet["crossBuildResultTimeList"] =    $crossBuildResultTimeList;
        $returnSet["crossBuildMachineNameList"] =   $crossBuildMachineNameList;
        $returnSet["crossBuildSysMemNameList"] =    $crossBuildSysMemNameList;
        
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
                                // only for shaderbench
                                //$machineIDPairList = array($curMachineID, $cmpMachineID);
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
        global $resultTimeList;
        global $crossBuildResultTimeList;
        global $umdNameList;
        global $umdNum;
        global $crossType;
        global $machineIDBatchPairList;
        global $machineNameList;
        
        $cmpMachineID = -1;
        $cmpStartResultID = -1;
        $cmpCardName = "";
        $cmpSysName = "";
        $cmpMachineName = "";
        $curCardName = $cardNameList[0][$_resultPos];
        $curMachineID = intval($machineIDList[0][$_resultPos]);
        $curMachineName = $machineNameList[0][$_resultPos];
        $tmpTime = explode(" ", $resultTimeList[0][$_resultPos]);
        $curResultTime = $tmpTime[0];
        $cmpBatchTime = "";
        if ($crossType == 2)
        {
            // cross build
            for ($i = 0; $i < (count($machineIDBatchPairList) / 2); $i++)
            {
                if ($curMachineID == intval($machineIDBatchPairList[$i * 2]))
                {
                    $tmpTime = explode(" ", $crossBuildResultTimeList[$i][0]);
                    $cmpBatchTime = $tmpTime[0];
                    break;
                }
            }
        }
        if (count($machineIDPairList) > 0)
        {
            // get comparison machine result id list
            $curMachineID = intval($machineIDList[0][$_resultPos]);
            
            if ($curMachineID == PHP_INT_MAX)
            {
                $tmpPos = $_resultPos % count($umdNameList);
                for ($i = 1; $i < ($tmpPos + 1); $i++)
                {
                    $curMachineID = intval($machineIDList[0][$_resultPos - $i]);
                    if ($curMachineID != PHP_INT_MAX)
                    {
                        break;
                    }
                }
            }
            
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
                            $cmpMachineName = $machineNameList[0][$n1];
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
        $returnSet["curMachineID"] = $curMachineID;
        $returnSet["cmpMachineName"] = $cmpMachineName;
        $returnSet["curMachineName"] = $curMachineName;
        $returnSet["curResultTime"] = $curResultTime;
        $returnSet["cmpBatchTime"] = $cmpBatchTime;
        $returnSet["cmpStartResultID"] = $cmpStartResultID;
        $returnSet["cmpCardName"] = $cmpCardName;
        $returnSet["cmpSysName"] = $cmpSysName;
        $returnSet["curCardName"] = $curCardName;
        return $returnSet;
    }
    
	public function getHistoryNachineInfo($_startResultID,
                                          $_resultPos)
	{
        global $returnMsg;
        global $resultIDList;
        global $cardNameList;
        global $sysNameList;
        global $machineIDPairList;
        global $machineIDList;
        global $resultTimeList;
        global $crossBuildResultTimeList;
        global $umdNameList;
        global $umdNum;
        global $crossType;
        global $machineIDBatchPairList;
        global $machineNameList;
        
        $returnSet = array();
        
        if (count($resultIDList) <= 1)
        {
            $returnSet["historyStartResultID"] = -1;
            return $returnSet;
        }
        
        $tmpCardName = $cardNameList[0][$_startResultID];
        $tmpsysName = $sysNameList[0][$_startResultID];
        
        $tmpKeyList1 = array_keys($cardNameList[1], $tmpCardName);
        $tmpKeyList2 = array_keys($sysNameList[1], $tmpsysName);
        $tmpKeyList3 = array_intersect($tmpKeyList1, $tmpKeyList2);
        
        if (count($tmpKeyList3) == 0)
        {
            $returnSet["historyStartResultID"] = -1;
            return $returnSet;
        }
        
        $tmpKeys = array();
        foreach ($tmpKeyList3 as $tmpKey)
        {
            array_push($tmpKeys, $tmpKey);
        }
        
        $historyStartResultID = intval($tmpKeys[0] / $umdNum) * $umdNum;

        $returnSet["historyStartResultID"] = $historyStartResultID;
        return $returnSet;
    }
    
	public function getSubTestNum($_db, $_resultPos, $_tableName01, $_subTestNum)
	{
        global $returnMsg;
        global $resultIDList;
        global $cardStandardResultPos;
        $db = $_db;

        $subTestNum = $_subTestNum;
        $standardTestCaseNum = 0;
        $curResultTestCaseNum = 0;
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
            if ($row1 == false)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            $curResultTestCaseNum = $row1[0];
            
            $params1 = array($resultIDList[0][$cardStandardResultPos]);
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
            $standardTestCaseNum = $row1[0];
            $subTestNum = $standardTestCaseNum;
        }


        $returnSet = array();
        $returnSet["subTestNum"] = $subTestNum;
        $returnSet["standardTestCaseNum"] = $standardTestCaseNum;
        $returnSet["curResultTestCaseNum"] = $curResultTestCaseNum;
        return $returnSet;
    }
    
	public function getSubTestNumList($_db, $_isCompStandard,
                                      $_resultPos, $_cmpStartResultID,
                                      $_umdNum)
	{
        global $returnMsg;
        global $resultIDList;
        global $testNameList;
        global $db_mis_table_name_string003;
        global $subTestUmdDataMaskList;
        global $cardStandardResultPos;
        
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
                $tmpTableName = $db_mis_table_name_string003 . $testNameList[$i];
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
                $tmpTableName = $db_mis_table_name_string003 . $testNameList[$i];
                //$params1 = array($resultIDList[0][$_resultPos]);
                $params1 = array($resultIDList[0][$cardStandardResultPos]);
                //$sql1 = "SELECT COUNT(*) FROM " . $tmpTableName . "_noise WHERE (result_id=? AND noise_id=0)";
                $sql1 = "SELECT COUNT(*) FROM " . $tmpTableName . " WHERE (result_id=? AND sub_id>0)";
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
        $xmlFileName2 = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d.tmp3", $_batchID);
        // comparison sheet
        $tmpFileName = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d.tmp", $_batchID);
        // flat data
        $tmpFileName1 = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d.tmp1", $_batchID);
        // summary data
        $jsonFileName = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d.json", $_batchID);
        $jsonFileName2 = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d_2.json", $_batchID);
        $jsonFileName3 = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d_3.json", $_batchID);
        $jsonFileName4 = sprintf($_reportFolder . "/" . $_tmpCardName . "_" . $_tmpSysName . "_batch%05d_4.json", $_batchID);
        
        $returnSet = array();
        $returnSet["xmlFileName"] = $xmlFileName;
        $returnSet["xmlFileName2"] = $xmlFileName2;
        $returnSet["tmpFileName"] = $tmpFileName;
        // flat data
        $returnSet["tmpFileName1"] = $tmpFileName1;
        // summary json file for each card, has testNameList
        $returnSet["jsonFileName"] = $jsonFileName;
        $returnSet["jsonFileName2"] = $jsonFileName2;
        $returnSet["jsonFileName3"] = $jsonFileName3;
        $returnSet["jsonFileName4"] = $jsonFileName4;
        return $returnSet;
    }
    
	public function checkShiftAPI($_fileHandle,
                                  $_fileHandle2,
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
        //global $resultNoiseNum;
        global $historyBatchMaxNum;

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

        fseek($_fileHandle, 0, SEEK_END);
        fseek($_fileHandle2, 0, SEEK_END);
        if ($shiftUmd)
        {
            $firstTestPos = -1;
            $firstSubTestPos = -1;
            $sheetLinePos = 0;
            //$xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet001Ba.txt");
            $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet001Bb.txt");
            if (strlen($xmlSection) == 0)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "template file missing, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            
            $tmpList = array();
            
            for ($i = 0; $i < ($historyBatchMaxNum - 1); $i++)
            {
                $tmpList []= "R1C" . ($subjectNameFilterNumMax + 5 + $i * 2) . 
                             ":R1000000C" . ($subjectNameFilterNumMax + 5 + $i * 2);
            }
            $t1 = implode(",", $tmpList);

            $xmlSection = sprintf($xmlSection, 
                                  $t1);
                                  
            fwrite($_fileHandle, $xmlSection);
            fwrite($_fileHandle2, $xmlSection);
        }
        
        $returnSet = array();
        $returnSet["firstTestPos"] = $firstTestPos;
        $returnSet["firstSubTestPos"] = $firstSubTestPos;
        $returnSet["sheetLinePos"] = $sheetLinePos;
        return $returnSet;
    }
    
    //public function getAllUmdTestCaseNumList($_db)
    //{
    //    global $resultPos;
    //    global $reportUmdNum;
    //    global $testNameList;
    //    global $db_mis_table_name_string001;
    //    global $resultIDList;
    //    
    //    $db = $_db;
    //    
    //    $subTestNumList = array();
    //    $subTestNumMap = array();
    //    
    //    $allUmdTestCaseNumList = array();
    //    
    //    $tmpStartResultID = intval($resultPos / $reportUmdNum) * $reportUmdNum;
    //    for ($j = 0; $j < $reportUmdNum; $j++)
    //    {
    //        $tmpTestCaseList = array();
    //        for ($i = 0; $i < count($testNameList); $i++)
    //        {
    //            $tmpTableName = $db_mis_table_name_string001 . $testNameList[$i];
    //            $params1 = array($resultIDList[0][$tmpStartResultID + $j]);
    //            $sql1 = "SELECT COUNT(*) FROM " . $tmpTableName . 
    //                    "_noise WHERE result_id=? AND noise_id=0;";
    //            if ($db->QueryDB($sql1, $params1) == null)
    //            {
    //                $returnMsg["errorCode"] = 0;
    //                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
    //                echo json_encode($returnMsg);
    //                return null;
    //            }
    //            $row1 = $db->fetchRow();
    //            if ($row1 == false)
    //            {
    //                $returnMsg["errorCode"] = 0;
    //                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
    //                echo json_encode($returnMsg);
    //                return null;
    //            }
    //            $tmpSubTestNum = intval($row1[0]);
    //            
    //            $tmpTestCaseList []= $tmpSubTestNum;
    //        }
    //        $allUmdTestCaseNumList []= $tmpTestCaseList;
    //    }
    //    return $allUmdTestCaseNumList;
    //}
    
    public function getStandardUmdTestCaseNumList($_db)
    {
        //global $resultPos;
        global $reportUmdNum;
        global $testNameList;
        global $db_mis_table_name_string003;
        global $resultIDList;
        global $cardStandardResultPos;
        
        $db = $_db;
        
        $subTestNumList = array();
        $subTestNumMap = array();
        
        $tmpTestCaseList = array();
        for ($i = 0; $i < count($testNameList); $i++)
        {
            $tmpTableName = $db_mis_table_name_string003 . $testNameList[$i];
            $params1 = array($resultIDList[0][$cardStandardResultPos]);
            $sql1 = "SELECT COUNT(*) FROM " . $tmpTableName . " " .
                    "WHERE result_id=?;";
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
            
            $tmpTestCaseList []= $tmpSubTestNum;
        }

        return $tmpTestCaseList;
    }
    
    public function writePlatformInfo($_fileHandle)
    {
        global $envDefaultInfo;
        global $resultPos;
        global $startResultID;
        global $cmpStartResultID;
        global $sysNameList;
        global $cpuNameList;
        global $cardNameList;
        global $sClockNameList;
        global $mClockNameList;
        global $gpuMemNameList;
        global $sysMemNameList;
        global $changeListNumList;
        global $driverNameList;
        global $umdNameList;
        global $reportTemplateDir;
        global $startStyleID;
        global $logStoreDir;
        global $logFileFolder;
        
        $tmpRootPath = $logStoreDir . "/" . $logFileFolder;
        $cardFolderList = glob($tmpRootPath . "/*", GLOB_ONLYDIR);
        
        $machineInfoList = array();
        
        foreach ($cardFolderList as $tmpPath)
        {
            $tmpPath2 = $tmpPath . "/" . "machine_info.json";
            if (file_exists($tmpPath2))
            {
                $machineInfoList []= $tmpPath2;
            }
        }
        
        //$tmpResultPos = $startResultID;
        //
        //for ($i = 0; $i < count($umdNameList); $i++)
        //{
        //    if (strlen($cpuNameList[0][$tmpResultPos]) > 0)
        //    {
        //        break;
        //    }
        //    $tmpResultPos++;
        //}
        //
        //$cmpResultPos = $cmpStartResultID;
        //
        //if ($cmpStartResultID != -1)
        //{
        //    for ($i = 0; $i < count($umdNameList); $i++)
        //    {
        //        if (strlen($cpuNameList[0][$cmpResultPos]) > 0)
        //        {
        //            break;
        //        }
        //        $cmpResultPos++;
        //    }
        //}
        //
        //$tmpCardName = $cardNameList[0][$tmpResultPos];
        //$tmpSysName = $sysNameList[0][$tmpResultPos];
        //$cmpCardName = $cmpStartResultID == -1 ? -1 : $cardNameList[0][$cmpResultPos];
        //$cmpSysName = $cmpStartResultID == -1 ? -1 : $sysNameList[0][$cmpResultPos];
        //
        //$tmpBaseDriverVersion = "";
        //$tmpBaseDriverDate = "";
        //$cmpBaseDriverVersion = "";
        //$cmpBaseDriverDate = "";
        //
        //$tmpRead = false;
        //$cmpRead = false;
        
        $asicInfoList = array();
        
        $asicInfoList["Base_Driver_Version"] = array();
        $asicInfoList["Base_Driver_Date"]    = array();
        $asicInfoList["Vulkan_SDK_Version"]  = array();
        $asicInfoList["Microbench_Version"]  = array();
        $asicInfoList["Operating_System"]    = array();
        $asicInfoList["Test_Date"]           = array();
        $asicInfoList["Test_Time"]           = array();
        $asicInfoList["CPU"]                 = array();
        $asicInfoList["GPU"]                 = array();
        $asicInfoList["GPU_Core_Clock"]      = array();
        $asicInfoList["GPU_Memory_Clock"]    = array();
        $asicInfoList["GPU_Memory"]          = array();
        $asicInfoList["System_Memory"]       = array();
        
        foreach ($machineInfoList as $tmpPath)
        {
            $t1 = file_get_contents($tmpPath);
            $tmpObj = json_decode($t1);
            
            $tmpObj2 = array();
            foreach ($tmpObj as $tmpKey => $tmpVal)
            {
                $tmpObj2[$tmpKey] = $tmpVal;
            }
            
            //$tmpCardName1 = isset($tmpObj2["videoCardName"]) ? $tmpObj2["videoCardName"] : "";
            //$tmpSysName1 = isset($tmpObj2["systemName"]) ? $tmpObj2["systemName"] : "";
            //
            //$tmpCardNameLow = strtolower($tmpCardName);
            //$tmpSysNameLow  = strtolower($tmpSysName);
            //$tmpCardNameLow1 = strtolower($tmpCardName1);
            //$tmpSysNameLow1 = strtolower($tmpSysName1);
            //
            //if (($tmpCardNameLow == $tmpCardNameLow1) &&
            //    ($tmpSysNameLow  == $tmpSysNameLow1))
            //{
            //    $tmpBaseDriverVersion = isset($tmpObj2["mainLineName"]) ? $tmpObj2["mainLineName"] : "";
            //    $tmpBaseDriverDate = isset($tmpObj2["baseDriverDate"]) ? $tmpObj2["baseDriverDate"] : "";
            //    
            //    $tmpRead = true;
            //}
            //
            //if ($cmpStartResultID != -1)
            //{
            //    $tmpCardNameLow = strtolower($cmpCardName);
            //    $tmpSysNameLow  = strtolower($cmpSysName);
            //    $tmpCardNameLow1 = strtolower($tmpCardName1);
            //    $tmpSysNameLow1 = strtolower($tmpSysName1);
            //    
            //    if (($tmpCardNameLow == $tmpCardNameLow1) &&
            //        ($tmpSysNameLow  == $tmpSysNameLow1))
            //    {
            //        $cmpBaseDriverVersion = isset($tmpObj2["mainLineName"]) ? $tmpObj2["mainLineName"] : "";
            //        $cmpBaseDriverDate = isset($tmpObj2["baseDriverDate"]) ? $tmpObj2["baseDriverDate"] : "";
            //        
            //        $cmpRead = true;
            //    }
            //}
            //if ($tmpRead && $cmpRead)
            //{
            //    break;
            //}
            //if (($cmpStartResultID == -1) && $tmpRead)
            //{
            //    break;
            //}
            
            
            $asicInfoList["Base_Driver_Version"] []= isset($tmpObj2["mainLineName"]) ? $tmpObj2["mainLineName"] : "";
            $asicInfoList["Base_Driver_Date"]    []= isset($tmpObj2["baseDriverDate"]) ? $tmpObj2["baseDriverDate"] : "";
            $asicInfoList["Vulkan_SDK_Version"]  []= isset($envDefaultInfo["vulkanSDKVersion"]) ? $envDefaultInfo["vulkanSDKVersion"] : "";
            $asicInfoList["Microbench_Version"]  []= isset($envDefaultInfo["microbenchVersion"]) ? $envDefaultInfo["microbenchVersion"] : "";
            $asicInfoList["Operating_System"]    []= isset($tmpObj2["systemName"]) ? $tmpObj2["systemName"] : "";
            $asicInfoList["Test_Date"]           []= $envDefaultInfo["testingDate"];
            $asicInfoList["Test_Time"]           []= $envDefaultInfo["testingTime"];
            $asicInfoList["CPU"]                 []= isset($tmpObj2["cpuName"]) ? $tmpObj2["cpuName"] : "";
            $asicInfoList["GPU"]                 []= isset($tmpObj2["videoCardName"]) ? $tmpObj2["videoCardName"] : "";
            $asicInfoList["GPU_Core_Clock"]      []= isset($tmpObj2["sClockName"]) ? $tmpObj2["sClockName"] : "";
            $asicInfoList["GPU_Memory_Clock"]    []= isset($tmpObj2["mClockName"]) ? $tmpObj2["mClockName"] : "";
            $asicInfoList["GPU_Memory"]          []= isset($tmpObj2["gpuMemName"]) ? $tmpObj2["gpuMemName"] : "";
            $asicInfoList["System_Memory"]       []= isset($tmpObj2["memoryName"]) ? $tmpObj2["memoryName"] : "";
            
        }
        
        //$tableRowList = array();
        //$cmpTableRowList = array();
        //
        //$tableRowList["Base_Driver_Version"] = $tmpBaseDriverVersion;
        //$tableRowList["Base_Driver_Date"]    = $tmpBaseDriverDate;
        //$tableRowList["Vulkan_SDK_Version"] = isset($envDefaultInfo["vulkanSDKVersion"]) ? $envDefaultInfo["vulkanSDKVersion"] : "";
        //$tableRowList["Microbench_Version"] = isset($envDefaultInfo["microbenchVersion"]) ? $envDefaultInfo["microbenchVersion"] : "";
        //
        //$tableRowList["Operating_System"] = $sysNameList[0][$tmpResultPos];
        //$tableRowList["Test_Date"] = $envDefaultInfo["testingDate"];
        //$tableRowList["Test_Time"] = $envDefaultInfo["testingTime"];
        //$tableRowList["CPU"] = $cpuNameList[0][$tmpResultPos];
        //$tableRowList["GPU"] = $cardNameList[0][$tmpResultPos];
        //$tableRowList["GPU_Core_Clock"] = $sClockNameList[0][$tmpResultPos];
        //$tableRowList["GPU_Memory_Clock"] = $mClockNameList[0][$tmpResultPos];
        //$tableRowList["GPU_Memory"] = $gpuMemNameList[0][$tmpResultPos];
        //$tableRowList["System_Memory"] = $sysMemNameList[0][$tmpResultPos];
        //
        //if ($cmpStartResultID != -1)
        //{
        //    $cmpTableRowList["Base_Driver_Version"] = $cmpBaseDriverVersion;
        //    $cmpTableRowList["Base_Driver_Date"]    = $cmpBaseDriverDate;
        //    $cmpTableRowList["Vulkan_SDK_Version"] = isset($envDefaultInfo["vulkanSDKVersion"]) ? $envDefaultInfo["vulkanSDKVersion"] : "";
        //    $cmpTableRowList["Microbench_Version"] = isset($envDefaultInfo["microbenchVersion"]) ? $envDefaultInfo["microbenchVersion"] : "";
        //    
        //    $cmpTableRowList["Operating_System"] = $sysNameList[0][$cmpResultPos];
        //    $cmpTableRowList["Test_Date"] = $envDefaultInfo["testingDate"];
        //    $cmpTableRowList["Test_Time"] = $envDefaultInfo["testingTime"];
        //    $cmpTableRowList["CPU"] = $cpuNameList[0][$cmpResultPos];
        //    $cmpTableRowList["GPU"] = $cardNameList[0][$cmpResultPos];
        //    $cmpTableRowList["GPU_Core_Clock"] = $sClockNameList[0][$cmpResultPos];
        //    $cmpTableRowList["GPU_Memory_Clock"] = $mClockNameList[0][$cmpResultPos];
        //    $cmpTableRowList["GPU_Memory"] = $gpuMemNameList[0][$cmpResultPos];
        //    $cmpTableRowList["System_Memory"] = $sysMemNameList[0][$cmpResultPos];
        //}
        //
        //$apiVersionList = array();
        //
        //for ($i = 0; $i < count($umdNameList); $i++)
        //{
        //    $tmpName = "changeList" . $umdNameList[$i];
        //    if (isset($envDefaultInfo[$tmpName]))
        //    {
        //        $tmpKey = $umdNameList[$i] . " Version / CL#";
        //        $apiVersionList[$tmpKey] = $envDefaultInfo[$tmpName];
        //    }
        //}
        
        $t1 = "";
        //if ($cmpStartResultID != -1)
        //{
        //    $t1 = "<Column ss:AutoFitWidth=\"0\" ss:Width=\"200\"/>\n";
        //}
        
        for ($i = 0; $i < count($asicInfoList["GPU"]); $i++)
        {
            $t1 .= "<Column ss:AutoFitWidth=\"0\" ss:Width=\"200\"/>\n";
        }
        
        //$sheetCode = "<Worksheet ss:Name=\"PlatformInfo\">\n" .
        //             "<Table x:FullColumns=\"1\" " .
        //             "x:FullRows=\"1\" ss:DefaultRowHeight=\"15\">\n" .
        //             "<Column ss:AutoFitWidth=\"0\" ss:Width=\"200\"/>\n" .
        //             "<Column ss:AutoFitWidth=\"0\" ss:Width=\"200\"/>\n" . $t1;
                     
        $sheetCode = "<Worksheet ss:Name=\"PlatformInfo\">\n" .
                     "<Table x:FullColumns=\"1\" " .
                     "x:FullRows=\"1\" ss:DefaultRowHeight=\"15\">\n" .
                     "<Column ss:AutoFitWidth=\"0\" ss:Width=\"200\"/>\n" . $t1;
                     
        $t1 = "";
        //if ($cmpStartResultID != -1)
        //{
        //    $t1 = "<Cell ss:StyleID=\"s" . ($startStyleID + 16) . "\"><Data ss:Type=\"String\">" .
        //          $cmpSysName . " - " . $cmpCardName .
        //          "</Data></Cell>\n";
        //}
        
        for ($i = 0; $i < count($asicInfoList["GPU"]); $i++)
        {
            $t1 .= "<Cell ss:StyleID=\"s" . ($startStyleID + 16) . "\"><Data ss:Type=\"String\">" .
                   $asicInfoList["GPU"][$i] .
                   "</Data></Cell>\n";
        }
                     
        //$sheetCode .= "<Row ss:Height=\"20.0\">\n" .
        //              "<Cell ss:StyleID=\"s" . ($startStyleID + 22) . "\"><Data ss:Type=\"String\">Platform Info</Data></Cell>\n" .
        //              "<Cell ss:StyleID=\"s" . ($startStyleID + 16) . "\"><Data ss:Type=\"String\">" .
        //              $tmpSysName . " - " . $tmpCardName .
        //              "</Data></Cell>\n" .
        //              $t1 .
        //              "</Row>\n";
                      
        $sheetCode .= "<Row ss:Height=\"20.0\">\n" .
                      "<Cell ss:StyleID=\"s" . ($startStyleID + 22) . "\"><Data ss:Type=\"String\">Platform Info</Data></Cell>\n" .
                      $t1 .
                      "</Row>\n";
                     
        //foreach ($tableRowList as $tmpKey => $tmpVal)
        foreach ($asicInfoList as $tmpKey => $tmpList)
        {
            $t1 = "";
            //if ($cmpStartResultID != -1)
            //{
            //    $cmpVal = isset($cmpTableRowList[$tmpKey]) ? $cmpTableRowList[$tmpKey] : "";
            //    
            //    $t1 = "<Cell ss:StyleID=\"s" . ($startStyleID + 21) . "\"><Data ss:Type=\"String\">" . $cmpVal . "</Data></Cell>\n";
            //}
            //
            //$sheetCode .= "<Row ss:Height=\"20.0\">\n" .
            //              "<Cell ss:StyleID=\"s" . ($startStyleID + 20) . "\"><Data ss:Type=\"String\">" . $tmpKey . "</Data></Cell>\n" .
            //              "<Cell ss:StyleID=\"s" . ($startStyleID + 21) . "\"><Data ss:Type=\"String\">" . $tmpVal . "</Data></Cell>\n" .
            //              $t1 .
            //              "</Row>\n";
            
            for ($i = 0; $i < count($asicInfoList["GPU"]); $i++)
            {
                $t1 .= "<Cell ss:StyleID=\"s" . ($startStyleID + 21) . "\"><Data ss:Type=\"String\">" . $tmpList[$i] . "</Data></Cell>\n";
            }
            
            $sheetCode .= "<Row ss:Height=\"20.0\">\n" .
                          "<Cell ss:StyleID=\"s" . ($startStyleID + 20) . "\"><Data ss:Type=\"String\">" . $tmpKey . "</Data></Cell>\n" .
                          $t1 .
                          "</Row>\n";
        }
        
        //foreach ($apiVersionList as $tmpKey => $tmpVal)
        //{
        //    $t1 = "";
        //    if ($cmpStartResultID != -1)
        //    {
        //        $t1 = "<Cell ss:StyleID=\"s" . ($startStyleID + 21) . "\"><Data ss:Type=\"String\">" . $tmpVal . "</Data></Cell>\n";
        //    }
        //    
        //    $sheetCode .= "<Row ss:Height=\"20.0\">\n" .
        //                  "<Cell ss:StyleID=\"s" . ($startStyleID + 20) . "\"><Data ss:Type=\"String\">" . $tmpKey . "</Data></Cell>\n" .
        //                  "<Cell ss:StyleID=\"s" . ($startStyleID + 21) . "\"><Data ss:Type=\"String\">" . $tmpVal . "</Data></Cell>\n" .
        //                  $t1 .
        //                  "</Row>\n";
        //}

        fwrite($_fileHandle, $sheetCode);
        $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet004B.txt");
        fwrite($_fileHandle, $xmlSection);
    }
    
    public function writeSummaryVariance($_fileHandle)
    {
        global $reportUmdNum;
        global $resultUmdOrder;
        global $swtReportUmdInfo;
        global $testNameList;
        global $umdNameList;
        global $subjectNameFilterNumMax;
        global $reportTemplateDir;
        global $standardUmdTestCaseNumList;
        //global $allUmdTestCaseNumList;
        global $startStyleID;
        global $swtPreSheetName_pf;
        global $swtPreSheetNameTitle_pf;
        global $curCardName;
        global $tmpSysName;
        
        $sheetCodeStart = "<Worksheet ss:Name=\"Variance\">\n" .
                     "<Table x:FullColumns=\"1\" " .
                     "x:FullRows=\"1\" ss:DefaultRowHeight=\"15\">\n" .
                     "<Column ss:AutoFitWidth=\"0\" ss:Width=\"50\"/>\n" .
                     "<Column ss:AutoFitWidth=\"0\" ss:Width=\"100\"/>\n" .
                     "<Column ss:AutoFitWidth=\"0\" ss:Width=\"80\"/>\n";
                     //"<Column ss:AutoFitWidth=\"0\" ss:Width=\"80\"/>\n" .
                     //"<Column ss:AutoFitWidth=\"0\" ss:Width=\"80\"/>\n" .
                     //"<Column ss:AutoFitWidth=\"0\" ss:Width=\"80\"/>\n" .
                     //"<Column ss:AutoFitWidth=\"0\" ss:Width=\"80\"/>\n" .
                     //"<Column ss:AutoFitWidth=\"0\" ss:Width=\"80\"/>\n" .
                     //"<Column ss:AutoFitWidth=\"0\" ss:Width=\"80\"/>\n";
                     
        $sheetCodeColumnWidth = "";
        $sheetCodeTitle = "<Row ss:Height=\"20\">\n" .
                          "<Cell ss:StyleID=\"s84\" ss:MergeAcross=\"2\" >" .
                          //"<Data ss:Type=\"String\">" . ($curCardName . " - " . $tmpSysName) . 
                          "<Data ss:Type=\"String\">" . ($tmpSysName) . 
                          "</Data></Cell>";
                     
        $sheetCode = "<Row ss:Height=\"20\">\n" .
                      "<Cell ss:StyleID=\"s" . ($startStyleID + 15) . "\"/>\n" .
                      "<Cell ss:StyleID=\"s" . ($startStyleID + 15) . "\"><Data ss:Type=\"String\">Tests</Data></Cell>\n" .
                      "<Cell ss:StyleID=\"s" . ($startStyleID + 15) . "\"><Data ss:Type=\"String\">Test Cases</Data></Cell>\n";
                      //"<Cell ss:StyleID=\"s" . ($startStyleID + 15) . "\"/>\n";
                      
        $tmpReportUmdInfo = $swtReportUmdInfo;
        
        $compTimeNum = 0;
        $tmpUmdNameList = array();
        // compile time
        for ($i = 0; $i < $reportUmdNum; $i++)
        {
            if ($resultUmdOrder[$i] == -1)
            {
                // absent api
                continue;
            }
            $tmpArr = explode("_", $tmpReportUmdInfo[$i]);
            $sheetCode .= "<Cell ss:StyleID=\"s" . ($startStyleID + 16) . "\"><Data ss:Type=\"String\">" . 
                          //$tmpReportUmdInfo[$i] . "</Data></Cell>";
                          $tmpArr[0] . "</Data></Cell>";
                          
            $tmpUmdNameList []= $tmpReportUmdInfo[$i];
            
            $sheetCodeColumnWidth .= "<Column ss:AutoFitWidth=\"0\" ss:Width=\"80\"/>\n";
            
            $compTimeNum++;
        }
        
        // black column between comp time & exec time
        $sheetCode .= "<Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\" />";
        
        $sheetCodeColumnWidth .= "<Column ss:AutoFitWidth=\"0\" ss:Width=\"5\"/>\n";
        
        $execTimeNum = 0;
        // execution time
        for ($i = 0; $i < $reportUmdNum; $i++)
        {
            if ($resultUmdOrder[$i] == -1)
            {
                // absent api
                continue;
            }
            $tmpArr = explode("_", $tmpReportUmdInfo[$i]);
            $sheetCode .= "<Cell ss:StyleID=\"s" . ($startStyleID + 16) . "\"><Data ss:Type=\"String\">" . 
                          //$tmpReportUmdInfo[$i] . "</Data></Cell>";
                          $tmpArr[0] . "</Data></Cell>";
                          
            $tmpUmdNameList []= $tmpReportUmdInfo[$i];
            
            $sheetCodeColumnWidth .= "<Column ss:AutoFitWidth=\"0\" ss:Width=\"80\"/>\n";
            
            $execTimeNum++;
        }
        
        $sheetCodeTitle .= "<Cell ss:StyleID=\"s84\" ss:MergeAcross=\"" . ($compTimeNum - 1) . "\" >" .
                           "<Data ss:Type=\"String\">" . ($swtPreSheetNameTitle_pf[0]) . 
                           "</Data></Cell>";
        $sheetCodeTitle .= "<Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\" />";
        $sheetCodeTitle .= "<Cell ss:StyleID=\"s84\" ss:MergeAcross=\"" . ($execTimeNum - 1) . "\" >" .
                           "<Data ss:Type=\"String\">" . ($swtPreSheetNameTitle_pf[1]) . 
                           "</Data></Cell>";
        $sheetCodeTitle .= "</Row>\n";
        
        $sheetCode = $sheetCodeStart . 
                     $sheetCodeColumnWidth .
                     $sheetCodeTitle .
                     $sheetCode . "</Row>\n";
        
        $tmpLineOffset = array_fill(0, $reportUmdNum, 1);
        $tmpLineOffset2 = array_fill(0, $reportUmdNum, 1);
        
        $showRowNum = 0;
        for ($i = 0; $i < count($testNameList); $i++)
        {
            $tmpArr = explode("_", $testNameList[$i]);
            $tmpGroupName = $testNameList[$i];
            if (count($tmpArr) > 1)
            {
                $tmpGroupName = $tmpArr[1];
            }
            
            $sheetCode .= "<Row ss:Height=\"17.25\">\n" .
                          "<Cell ss:StyleID=\"s" . ($startStyleID + 17) . "\"><Data ss:Type=\"Number\">" . ($i + 1) . "</Data></Cell>\n" .
                          "<Cell ss:StyleID=\"s" . ($startStyleID + 19) . "\"><Data ss:Type=\"String\">" . $tmpGroupName . "</Data></Cell>\n" .
                          "<Cell ss:StyleID=\"s" . ($startStyleID + 19) . "\"><Data ss:Type=\"Number\">" . $standardUmdTestCaseNumList[$i] . "</Data></Cell>\n";
                          //"<Cell ss:StyleID=\"s" . ($startStyleID + 17) . "\"/>\n";
                          
            //$tmpLineOffset = array_fill(0, $reportUmdNum, 1);
            // compile time
            for ($j = 0; $j < $reportUmdNum; $j++)
            {
                if ($resultUmdOrder[$j] == -1)
                {
                    // absent api
                    continue;
                }
                
                if ($standardUmdTestCaseNumList[$i] > 0)
                {
                    $tmpArr = explode("_", $tmpReportUmdInfo[$j]);
                    $sheetCode .= "<Cell ss:StyleID=\"s" . ($startStyleID + 18) . "\" ss:Formula=\"=MAX(" . 
                                  $tmpArr[0] . "_" . $swtPreSheetName_pf[0] . "!R[" . ($tmpLineOffset[$j] - 1) . 
                                  "]C" . ($subjectNameFilterNumMax + 3) . 
                                  ":R[" . ($tmpLineOffset[$j] + $standardUmdTestCaseNumList[$i] - 1 - 1) . "]C" . 
                                  ($subjectNameFilterNumMax + 3) . ")\">" .
                                  "<Data ss:Type=\"Number\"></Data></Cell>";
                }
                else
                {
                    $sheetCode .= "<Cell ss:StyleID=\"s" . ($startStyleID + 18) . "\" >" .
                                  "</Cell>";

                }
                              
                if ($standardUmdTestCaseNumList[$i] > 0)
                {
                    $tmpLineOffset[$j] += ($standardUmdTestCaseNumList[$i] + 1);
                }
                else
                {
                    $tmpLineOffset[$j] -= 1;
                }
            }
            // black column between comp time & exec time
            $sheetCode .= "<Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\" />";
            
            //$tmpLineOffset = array_fill(0, $reportUmdNum, 1);
            // execution time
            for ($j = 0; $j < $reportUmdNum; $j++)
            {
                if ($resultUmdOrder[$j] == -1)
                {
                    // absent api
                    continue;
                }
                
                if ($standardUmdTestCaseNumList[$i] > 0)
                {
                    $tmpArr = explode("_", $tmpReportUmdInfo[$j]);
                    $sheetCode .= "<Cell ss:StyleID=\"s" . ($startStyleID + 18) . "\" ss:Formula=\"=MAX(" . 
                                  $tmpArr[0] . "_" . $swtPreSheetName_pf[1] . "!R[" . ($tmpLineOffset2[$j] - 1) . 
                                  "]C" . ($subjectNameFilterNumMax + 3) . 
                                  ":R[" . ($tmpLineOffset2[$j] + $standardUmdTestCaseNumList[$i] - 1 - 1) . "]C" . 
                                  ($subjectNameFilterNumMax + 3) . ")\">" .
                                  "<Data ss:Type=\"Number\"></Data></Cell>";
                }
                else
                {
                    $sheetCode .= "<Cell ss:StyleID=\"s" . ($startStyleID + 18) . "\" >" .
                                  "</Cell>";

                }
                              
                if ($standardUmdTestCaseNumList[$i] > 0)
                {
                    $tmpLineOffset2[$j] += ($standardUmdTestCaseNumList[$i] + 1);
                }
                else
                {
                    $tmpLineOffset2[$j] -= 1;
                }
            }
            
            
            $sheetCode .= "</Row>\n";
            
            $showRowNum++;
        }
        
        fwrite($_fileHandle, $sheetCode);
        $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet004Ba.txt");
        $t1 = "R3C4:R" . (2 + $showRowNum - 1 + 1) . "C" . (4 + count($tmpUmdNameList) - 1 + 1);
        $t2 = sprintf($xmlSection, $t1);
        
        fwrite($_fileHandle, $t2);
    }
    
    public function writeSummaryIntoReport($_tmpJsonFileName,
                                           $_fileHandle,
                                           $_tmpSummarySheetName,
                                           $_isHistorySummary)
    {
        global $reportUmdNum;
        global $swtReportUmdInfo;
        global $cmpStartResultID;
        global $crossType;
        global $resultUmdOrder;
        global $reportTemplateDir;
        
        if (file_exists($_tmpJsonFileName))
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
            
            $summarySheetHeadCode = " <Worksheet ss:Name=\"" . $_tmpSummarySheetName . "\">" .
                                    "  <Table x:FullColumns=\"1\"" .
                                    "   x:FullRows=\"1\" ss:StyleID=\"Default\" ss:DefaultRowHeight=\"15\">" .
                                    "   <Column ss:AutoFitWidth=\"0\" ss:Width=\"80\"/>";
            
            $summarySheetHeadCode2 = "   <Row ss:StyleID=\"Default\">" .
                                     "    <Cell ss:StyleID=\"s91\"><Data ss:Type=\"String\">Tests</Data></Cell>";
                                     
            if (($cmpStartResultID != -1) ||
                ($crossType == 2))
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
                        if ($_isHistorySummary == false)
                        {
                            $tmpArr1 = explode("_", $tmpReportUmdInfo[$tmpIndexList[$i + 1]]);
                            $tmpArr2 = explode("_", $tmpReportUmdInfo[$tmpIndexList[$i]]);
                            $summarySheetHeadCode2 .= "<Cell ss:StyleID=\"s92\"><Data ss:Type=\"String\">" . 
                                                     //($tmpReportUmdInfo[$tmpIndexList[$i + 1]] . " v.s " . $tmpReportUmdInfo[$tmpIndexList[$i]]) . 
                                                     ($tmpArr1[0] . " v.s " . $tmpArr2[0]) . 
                                                     "</Data></Cell>";
                        }
                        else
                        {
                            $tmpArr1 = explode("_", $tmpReportUmdInfo[$tmpIndexList[$i]]);
                            $summarySheetHeadCode2 .= "<Cell ss:StyleID=\"s92\"><Data ss:Type=\"String\">" . 
                                                     //($tmpReportUmdInfo[$tmpIndexList[$i]]) . 
                                                     ($tmpArr1[0]) . 
                                                     "</Data></Cell>";
                        }
                    }
                    else
                    {
                        if (($resultUmdOrder[$tmpIndexList[$i]] == -1) ||
                            ($resultUmdOrder[$tmpIndexList[0]] == -1))
                        {
                            continue;
                        }
                        
                        $summarySheetHeadCode .= "   <Column ss:AutoFitWidth=\"0\" ss:Width=\"500\"/>";
                        if ($_isHistorySummary == false)
                        {
                            $tmpArr1 = explode("_", $tmpReportUmdInfo[$tmpIndexList[0]]);
                            $tmpArr2 = explode("_", $tmpReportUmdInfo[$tmpIndexList[$i]]);
                            $summarySheetHeadCode2 .= "<Cell ss:StyleID=\"s92\"><Data ss:Type=\"String\">" . 
                                                     //($tmpReportUmdInfo[$tmpIndexList[$i]] . " v.s " . $tmpReportUmdInfo[$tmpIndexList[0]]) . 
                                                     // for SCPC vs NVIDIA
                                                     //($tmpReportUmdInfo[$tmpIndexList[0]] . " v.s " . $tmpReportUmdInfo[$tmpIndexList[$i]]) . 
                                                     ($tmpArr1[0] . " v.s " . $tmpArr2[0]) . 
                                                     "</Data></Cell>";
                        }
                        else
                        {
                            $tmpArr1 = explode("_", $tmpReportUmdInfo[$tmpIndexList[$i]]);
                            $summarySheetHeadCode2 .= "<Cell ss:StyleID=\"s92\"><Data ss:Type=\"String\">" . 
                                                     //($tmpReportUmdInfo[$tmpIndexList[$i]]) . 
                                                     ($tmpArr1[0]) . 
                                                     "</Data></Cell>";
                        }
                    }
                }
            }

            $summarySheetHeadCode2 .= "   </Row>";
            
            $t1 = $summarySheetHeadCode . $summarySheetHeadCode2;
            
            fwrite($_fileHandle, $t1);
            $t1 = file_get_contents($_tmpJsonFileName);
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
                if (($cmpStartResultID != -1) ||
                    ($crossType == 2))
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
                    if (($cmpStartResultID != -1) ||
                        ($crossType == 2))
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
                                      //$tmpDownRate, $tmpUpRate);
                                      $tmpUpRate, $tmpDownRate);
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
                        //if (($tmpMinRate < $tmpLmtMinRate) &&
                        //    ($tmpMaxRate < $tmpLmtMaxRate))
                        if (($tmpMinRate > $tmpLmtMinRate) &&
                                 ($tmpMaxRate > $tmpLmtMaxRate))
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
                        //else if (($tmpMinRate > $tmpLmtMinRate) &&
                        //         ($tmpMaxRate > $tmpLmtMaxRate))
                        else if (($tmpMinRate < $tmpLmtMinRate) &&
                            ($tmpMaxRate < $tmpLmtMaxRate))
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
                            if ($n8 < 0) // >
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

            fwrite($_fileHandle, $t1);
            $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet004B.txt");
            fwrite($_fileHandle, $xmlSection);
            
            unlink($_tmpJsonFileName);
        }
    }
    
	public function checkShiftCard($_xmlFileName,
                                   $_xmlFileName2,
                                   $_tmpFileName,
                                   $_tmpFileName1,
                                   $_jsonFileName,
                                   $_jsonFileName2,
                                   $_jsonFileName3,
                                   $_jsonFileName4,
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
        global $subjectNameFilterNumMax;
        global $dataColumnNum;
        global $cmpMachineID;
        global $reportUmdNum;
        global $startResultID;
        global $driverNameList;
        global $swtReportInfo;
        global $swtReportUmdInfo;
        global $resultUmdOrder;
        global $crossType;
        global $swtPreSheetNameShort_pf;
        global $swtPreSheetNameTitle_pf;
        //global $resultNoiseNum;

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

            $_fileHandle = fopen($_xmlFileName, "r+");
            fseek($_fileHandle, 0, SEEK_END);
            
            $_fileHandle2 = fopen($_xmlFileName2, "r+");
            if ($_fileHandle2 !== false)
            {
                fseek($_fileHandle2, 0, SEEK_END);
                $n1 = ftell($_fileHandle2);
                fseek($_fileHandle2, 0, SEEK_SET);
                $onceBytes = 1024;
                while ($n1 > 0)
                {
                    $n2 = $n1 > $onceBytes ? $onceBytes : $n1;
                    $t1 = fread($_fileHandle2, $n2);
                    fwrite($_fileHandle, $t1);
                    $n1 -= $n2;
                }
                fclose($_fileHandle2);
            }
            
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
                if (($cmpMachineID != -1) ||
                    ($crossType == 2))
                {
                    // if comparison with other cards
                    // $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002B1.txt");
                    $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002B4.txt");
                    //$xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002B5.txt");
                    
                    $t1 = "";
                    $tmpRange = array();
                    for ($i = 0; $i < intval($dataColumnNum / 3); $i++)
                    {
                        array_push($tmpRange, ("R6C" . ($subjectNameFilterNumMax + 4 + $i * 3) . ":R1000000C" . ($subjectNameFilterNumMax + 4 + $i * 3) . ""));
                    }
                    $t1 = implode(",", $tmpRange);
                    
                    $returnMsg["conditionFormatRange"] = $t1;
                    $returnMsg["dataColumnNum_1"] = $dataColumnNum;
                    
                    // freeze column num
                    $n2 = $subjectNameFilterNumMax + 2 + $dataColumnNum;
                    
                    $freezePanesCode = "   <FreezePanes/>\n" .
                                       "   <FrozenNoSplit/>\n" .
                                       "   <SplitHorizontal>4</SplitHorizontal>\n" .
                                       "   <TopRowBottomPane>4</TopRowBottomPane>\n";
                    
                    
                    $xmlSection = sprintf($xmlSection,
                                          $freezePanesCode,
                                          $t1);
                                          
                    $returnMsg["tmp_ConditionalFormatting0"] = $xmlSection;
                    $returnMsg["tmp_cmpMachineID0"] = $cmpMachineID;

                }
                else
                {
                    // $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002B3.txt");
                    $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet002B3a.txt");
                    
                    $t1 = "";
                    $tmpRange = array("R6C" . ($subjectNameFilterNumMax + 4) . ":R1000000C" . ($subjectNameFilterNumMax + 4) . "");
                    
                    for ($i = 1; $i < intval($dataColumnNum / 2); $i++)
                    {
                        array_push($tmpRange, "R6C" . ($subjectNameFilterNumMax + (4 + $i * 2)) . ":R1000000C" . ($subjectNameFilterNumMax + (4 + $i * 2)) . "");
                    }
                    
                    $tmpRange []= "R6C" . ($subjectNameFilterNumMax + 4 + $dataColumnNum + 1) . 
                                  ":R1000000C" . ($subjectNameFilterNumMax + 4 + $dataColumnNum + 1) . "";
                    
                    for ($i = 1; $i < intval($dataColumnNum / 2); $i++)
                    {
                        array_push($tmpRange, "R6C" . ($subjectNameFilterNumMax + (4 + $dataColumnNum + 1 + $i * 2)) . 
                                   ":R1000000C" . ($subjectNameFilterNumMax + (4 + $dataColumnNum + 1 + $i * 2)) . "");
                    }
                    
                    $t1 = implode(",", $tmpRange);
                    
                    //file_put_contents("H:/wamp64/www/benchMax/test01.json", $t1);
                    
                    // freeze column num
                    $n2 = $subjectNameFilterNumMax + 2 + $dataColumnNum;
                    
                    $freezePanesCode = "   <FreezePanes/>\n" .
                                       "   <FrozenNoSplit/>\n" .
                                       "   <SplitHorizontal>4</SplitHorizontal>\n" .
                                       "   <TopRowBottomPane>4</TopRowBottomPane>\n";
                    
                    $xmlSection = sprintf($xmlSection,
                                          $freezePanesCode,
                                          $t1);
                                          
                    $returnMsg["tmp_ConditionalFormatting"] = $xmlSection;
                    $returnMsg["tmp_cmpMachineID"] = $cmpMachineID;
                    
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
                $this->writeSummaryVariance($_fileHandle);
                
                // compile time
                $this->writeSummaryIntoReport($_jsonFileName,
                                              $_fileHandle,
                                              "Summary_" . $swtPreSheetNameTitle_pf[0],
                                              false);
                                              
                $this->writeSummaryIntoReport($_jsonFileName2,
                                              $_fileHandle,
                                              "HistorySummary_" . $swtPreSheetNameTitle_pf[0],
                                              true);
                                              
                // execution time
                $this->writeSummaryIntoReport($_jsonFileName3,
                                              $_fileHandle,
                                              "Summary_" . $swtPreSheetNameTitle_pf[1],
                                              false);
                                              
                $this->writeSummaryIntoReport($_jsonFileName4,
                                              $_fileHandle,
                                              "HistorySummary_" . $swtPreSheetNameTitle_pf[1],
                                              true);
                                              
                $this->writePlatformInfo($_fileHandle);
                
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
            $oldReportList = glob($_reportFolder . "/*.tmp3");
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
    
	public function checkReportDataColumnNum()
    {
        global $umdNameList;
        global $resultUmdOrder;
        global $validUmdNum;
        global $reportUmdNum;
        global $cmpMachineID;
        global $crossType;
        
        $dataColumnNum = 0;
        
        //if (($cmpMachineID != -1) ||
        //    ($crossType == 2))
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
    
	public function checkNeedCreateReportFile($_xmlFileName, $_xmlFileName2,
                                              $_tmpFileName, $_jsonFileName, $_jsonFileName2,
                                              $_jsonFileName3, $_jsonFileName4,
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
        global $resultUmdOrder;
        global $validUmdNum;
        global $reportUmdNum;
        global $driverNameList;
        global $testNameList;
        global $subTestNumList;
        global $subjectNameFilterNumMax;
        global $checkNeedCreateReportFile;
        global $swtReportInfo;
        global $swtReportUmdInfo;
        global $crossType;
        global $curResultTime;
        global $cmpBatchTime;
        global $curMachineName;
        global $cmpMachineName;
        global $swtPreSheetName_pf;
        global $swtPreSheetNameTitle_pf;
        global $dataColumnNum;
        

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
            $fileHandle2 = fopen($_xmlFileName2, "w+");
            
            // append styles
            fseek($fileHandle, 0, SEEK_SET);
            fwrite($fileHandle, $xmlSection);
            
            // write additional styles
            $this->writeAdditionalStyles($fileHandle);
            
            fclose($fileHandle);
            fclose($fileHandle2);
            
            $tmpReportInfo = $swtReportInfo;
            $tmpReportUmdInfo = $swtReportUmdInfo;
            
            $returnMsg["genXmlHead"] = 1;
            
            // create tmp file
            $tempFileHandle = fopen($_tmpFileName, "w+");
            $xmlSection = "";
            $t1 = "";
            //if (($_cmpMachineID != -1) ||
            //    ($crossType == 2))
            if ($_cmpMachineID != -1)
            {
                // if comparison with other card
                
                $curCardTitle = $_curCardName;
                $cmpCardTitle = $_cmpCardName;
                if ($_curCardName == $_cmpCardName)
                {
                    $curCardTitle .= "&#10;" . $_tmpSysName;
                    $cmpCardTitle .= "&#10;" . $_cmpSysName;
                }
                
                $curCardTitle2 = $_curCardName . " - " . $_tmpSysName;
                $cmpCardTitle2 = $_cmpCardName . " - " . $_cmpSysName;
                
                $curCardTitle3 = $_curCardName;
                $cmpCardTitle3 = $_cmpCardName;
                
                if ((strlen($curMachineName) > 0) &&
                    (strlen($cmpMachineName) > 0) &&
                    (strcmp($curMachineName, $cmpMachineName) != 0))
                {
                    $curCardTitle = $curMachineName;
                    $cmpCardTitle = $cmpMachineName;
                    
                    $curCardTitle2 = $curMachineName;
                    $cmpCardTitle2 = $cmpMachineName;
                    
                    $curCardTitle3 = $curMachineName;
                    $cmpCardTitle3 = $cmpMachineName;
                }
                
                if ($crossType == 2)
                {
                    $curCardTitle = $cmpBatchTime;
                    $cmpCardTitle = $curResultTime;
                    $curCardTitle2 = $cmpBatchTime;
                    $cmpCardTitle2 = $curResultTime;
                    $curCardTitle3 = $cmpBatchTime;
                    $cmpCardTitle3 = $curResultTime;
                    
                }
                
                // sheet head, comparison
                $reportCardComparisonHead = " <Worksheet ss:Name=\"Summary_Overall\">" .
                                            //"  <Table ss:ExpandedRowCount=\"%010d\" x:FullColumns=\"1\"" .
                                            "  <Table x:FullColumns=\"1\"" .
                                            "   x:FullRows=\"1\" ss:StyleID=\"s63\" ss:DefaultRowHeight=\"15\">" .
                                            "   <Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"60\"/>" .
                                            "   <Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"60\"/>" .
                                            "   <Column ss:Index=\"" . ($subjectNameFilterNumMax + 3) . 
                                            "\" ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"60\"" .
                                            "  ss:Span=\"11\"/>";
                                        
                //$t1 = sprintf($reportCardComparisonHead,
                //              $tempFileStartSheetLineNum);
                $t1 = sprintf($reportCardComparisonHead);
                // first row
                $t1 .= "   <Row ss:StyleID=\"Default\">" .
                       "    <Cell ss:StyleID=\"s84\"/>" .
                       "    <Cell ss:StyleID=\"s84\" ss:MergeAcross=\"" . ($subjectNameFilterNumMax - 0) . "\" >" .
                       "<Data ss:Type=\"String\">" . ($cmpCardTitle2 . " vs " . $curCardTitle2) . 
                       "</Data></Cell>";
                $curFirstRowAPIColumnID = 0;
                // compile time
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
                           "<Data ss:Type=\"String\">" . ($tmpReportUmdInfo[$i]) . "_" . $swtPreSheetNameTitle_pf[0] . "</Data></Cell>";
                    $curFirstRowAPIColumnID++;
                }
                // execution time
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
                           "<Data ss:Type=\"String\">" . ($tmpReportUmdInfo[$i]) . "_" . $swtPreSheetNameTitle_pf[1] . "</Data></Cell>";
                    $curFirstRowAPIColumnID++;
                }
                
                $t1 .= "   </Row>";
                
                // second row
                $t1 .= "   <Row ss:AutoFitHeight=\"0\" ss:Height=\"45\" ss:StyleID=\"Default\">" .
                       "    <Cell ss:StyleID=\"s89\"/>" .
                       "    <Cell ss:StyleID=\"s89\" ss:MergeAcross=\"" . ($subjectNameFilterNumMax - 0) . 
                       "\" ><Data ss:Type=\"String\">API</Data></Cell>";
                       
                $curFirstRowAPIColumnID = 0;
                // compile time
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
                // execution time
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
                // 3rd row
                $t1 .= "   <Row ss:StyleID=\"Default\">" .
                       "    <Cell ss:StyleID=\"s89\"/>" .
                       "    <Cell ss:StyleID=\"s89\" ss:MergeAcross=\"" . ($subjectNameFilterNumMax - 0) . 
                       "\" ><Data ss:Type=\"String\">Driver</Data></Cell>";
                       
                $curFirstRowAPIColumnID = 0;
                // compile time
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
                // execution time
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
                // this sheet max 21 columns
                
                $reportAPIComparisonHead = " <Worksheet ss:Name=\"Summary_Overall\">" .
                                           //"  <Table ss:ExpandedRowCount=\"%010d\" x:FullColumns=\"1\"" .
                                           "  <Table x:FullColumns=\"1\"" .
                                           "   x:FullRows=\"1\" ss:StyleID=\"s63\" ss:DefaultRowHeight=\"15\">" .
                                           "   <Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"60\"/>" .
                                           "   <Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"60\"/>" .
                                           "   <Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"200\"/>";

                //$t1 = sprintf($reportAPIComparisonHead);
                
                $t1 .= "   <Row ss:StyleID=\"Default\">" .
                       "    <Cell ss:StyleID=\"s84\"/>" .
                       "    <Cell ss:StyleID=\"s84\" ss:MergeAcross=\"" . ($subjectNameFilterNumMax - 0) . 
                       //"\" ><Data ss:Type=\"String\">" . ($_curCardName . " - " . $_tmpSysName) . "</Data></Cell>";
                       "\" ><Data ss:Type=\"String\">" . ($_tmpSysName) . "</Data></Cell>";
                
                $n1 = 2 + $subjectNameFilterNumMax;
                
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
                    $t1 .= "    <Cell ss:StyleID=\"s84\"><Data ss:Type=\"String\">" . $swtPreSheetNameTitle_pf[0] . "</Data></Cell>" .
                           "    <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>" .
                           "    <Cell ss:StyleID=\"s84\"><Data ss:Type=\"String\">" . $swtPreSheetNameTitle_pf[1] . "</Data></Cell>";
                           
                    $n1 += 1;
                }
                else if ($curFirstRowAPIColumnID > 1)
                {
                    //for ($i = 0; $i < $curFirstRowAPIColumnID; $i++)
                    //{
                    //    $t1 .= "    <Cell ss:StyleID=\"s84\"/>" .
                    //           "    <Cell ss:StyleID=\"s84\"/>" .
                    //           "    <Cell ss:StyleID=\"s84\"/>" .
                    //           "    <Cell ss:StyleID=\"s84\"/>";
                    //}
                    
                    $t1 .= "    <Cell ss:StyleID=\"s84\" ss:MergeAcross=\"" . ($curFirstRowAPIColumnID * 2 - 1) . 
                           "\" ><Data ss:Type=\"String\">" . $swtPreSheetNameTitle_pf[0] . "</Data></Cell>" .
                           "    <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>" .
                           "    <Cell ss:StyleID=\"s84\" ss:MergeAcross=\"" . ($curFirstRowAPIColumnID * 2 - 1) . 
                           "\" ><Data ss:Type=\"String\">" . $swtPreSheetNameTitle_pf[1] . "</Data></Cell>";
                           
                    $n1 += $curFirstRowAPIColumnID * 2;
                }
                
                $t1 .= "   </Row>";
                // 2nd row
                $t1 .= "   <Row ss:AutoFitHeight=\"0\" ss:Height=\"45\" ss:StyleID=\"Default\">" .
                       "    <Cell ss:StyleID=\"s89\"/>" .
                       "    <Cell ss:StyleID=\"s89\" ss:MergeAcross=\"" . ($subjectNameFilterNumMax - 0) . 
                       "\" ><Data ss:Type=\"String\">ASIC</Data></Cell>";
                       
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
                    
                    $tmpArr = explode("_", $tmpReportUmdInfo[$i]);
                    
                    array_push($tmpAPIList, $tmpArr[0]);
                    array_push($tmpCLList, $tmpReportInfo[$i]);
                    $curFirstRowAPIColumnID++;
                }
                if (count($tmpAPIList) == 1)
                {
                    // only 1 API
                    $t1 .= "    <Cell ss:StyleID=\"s87\"  ><Data ss:Type=\"String\">" . ($tmpAPIList[0]) . "</Data></Cell>" .
                           "    <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>" .
                           "    <Cell ss:StyleID=\"s87\"  ><Data ss:Type=\"String\">" . ($tmpAPIList[0]) . "</Data></Cell>";
                }
                else if (count($tmpAPIList) > 1)
                {
                    // more than 1 API
                    // compile time
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
                                   //"<Data ss:Type=\"String\">" . ($tmpAPIList[$i] . "&#10;vs&#10;" . $tmpAPIList[0]) . 
                                   "<Data ss:Type=\"String\">" . ($tmpAPIList[0] . "&#10;vs&#10;" . $tmpAPIList[$i]) . 
                                   "</Data></Cell>";
                        }
                    }
                    $t1 .= "    <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>";
                    // execution time
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
                                   //"<Data ss:Type=\"String\">" . ($tmpAPIList[$i] . "&#10;vs&#10;" . $tmpAPIList[0]) . 
                                   "<Data ss:Type=\"String\">" . ($tmpAPIList[0] . "&#10;vs&#10;" . $tmpAPIList[$i]) . 
                                   "</Data></Cell>";
                        }
                    }
                }
                
                $t1 .= "   </Row>";
                // 3rd row
                $t1 .= "   <Row ss:StyleID=\"Default\">" .
                       "    <Cell ss:StyleID=\"s89\"/>" .
                       "    <Cell ss:StyleID=\"s89\" ss:MergeAcross=\"" . ($subjectNameFilterNumMax - 0) . 
                       "\" ><Data ss:Type=\"String\">Driver</Data></Cell>";
                       
                if (count($tmpAPIList) == 1)
                {
                    // only 1 API
                    $t1 .= "    <Cell ss:StyleID=\"s88\"><Data ss:Type=\"String\">" . ($tmpCLList[0]) . "</Data></Cell>" .
                           "    <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>" .
                           "    <Cell ss:StyleID=\"s88\"><Data ss:Type=\"String\">" . ($tmpCLList[0]) . "</Data></Cell>";
                }
                else if (count($tmpAPIList) > 1)
                {
                    // compile time
                    for ($i = 0; $i < count($tmpCLList); $i++)
                    {
                        $t1 .= "    <Cell ss:StyleID=\"s88\"><Data ss:Type=\"String\">" . ($tmpCLList[$i]) . "</Data></Cell>" .
                               "    <Cell ss:StyleID=\"s88\"/>";
                    }
                    $t1 .= "    <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>";
                    // execution time
                    for ($i = 0; $i < count($tmpCLList); $i++)
                    {
                        $t1 .= "    <Cell ss:StyleID=\"s88\"><Data ss:Type=\"String\">" . ($tmpCLList[$i]) . "</Data></Cell>" .
                               "    <Cell ss:StyleID=\"s88\"/>";
                    }
                }
                      
                $t1 .= "   </Row>";
                
                $t1 = $reportAPIComparisonHead . 
                      "<Column ss:Index=\"" . ($n1 + 1) . "\" ss:AutoFitWidth=\"0\" ss:Width=\"5\"/>" . 
                      $t1;
                
            }

            //$t2 = sprintf("\"%010d\"", $tempFileStartSheetLineNum);
            //$n1 = strpos($t1, $t2);
            //if ($n1 === false)
            //{
            //    fclose($tempFileHandle);
            //    $returnMsg["errorCode"] = 0;
            //    $returnMsg["errorMsg"] = "template file content invalid, line: " . __LINE__;
            //    echo json_encode($returnMsg);
            //    return null;
            //}
            //// line num pos - strlen("\"")
            //$tempFileLineNumPos = 0 + $n1 + 1;
            fwrite($tempFileHandle, $t1);
            fclose($tempFileHandle);
            
            // create summary sheet temp file
            //if ($_cmpMachineID != -1)
            {
                if ((file_exists($_jsonFileName) == false) ||
                    (file_exists($_jsonFileName2) == false))
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
                    
                    if (file_exists($_jsonFileName) == false)
                    {
                        file_put_contents($_jsonFileName, $t1);
                    }
                    
                    if (file_exists($_jsonFileName2) == false)
                    {
                        file_put_contents($_jsonFileName2, $t1);
                    }
                    
                    if (file_exists($_jsonFileName3) == false)
                    {
                        file_put_contents($_jsonFileName3, $t1);
                    }
                    
                    if (file_exists($_jsonFileName4) == false)
                    {
                        file_put_contents($_jsonFileName4, $t1);
                    }
                }
            }
        }
        $returnSet = array();
        $returnSet["tempFileLineNumPos"] = $tempFileLineNumPos;
        return $returnSet;
    }
    
	public function checkStartSheet($_fileHandle, $_fileHandle2, $_tempFileHandle,
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
        //global $resultNoiseNum;
        global $swtPreSheetName_pf;

        $lineNumPos = $_lineNumPos;
        
        if (($_curTestPos     == $_firstTestPos) &&
            ($_nextSubTestPos == $_firstSubTestPos))
        {
            // start of each sheet
            //$xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet001Ab.txt");
            $xmlSection = file_get_contents($reportTemplateDir . "/sectionSheet001Ac.txt");
            if (strlen($xmlSection) == 0)
            {
                fclose($_fileHandle);
                fclose($_fileHandle2);
                fclose($_tempFileHandle);
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "template file missing, line: " . __LINE__;
                echo json_encode($returnMsg);
                return null;
            }
            
            $tmpCode = "";
            //for ($i = 0; $i < $resultNoiseNum; $i++)
            //{
            //    $tmpCode .= "<Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"120\"/>\n" .
            //                "<Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"50\"/>\n";
            //}
            
            for ($i = 0; $i < $historyBatchMaxNum; $i++)
            {
                $tmpCode .= "<Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"120\"/>\n";
                if ($i < ($historyBatchMaxNum - 1))
                {
                    $tmpCode .= "<Column ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"50\"/>\n";
                }
            }
                  
            $t3 = "<Column ss:Index=\"" . ($subjectNameFilterNumMax + 3) . 
                  "\" ss:StyleID=\"s63\" ss:AutoFitWidth=\"0\" ss:Width=\"100\"/>\n" .
                  $tmpCode;
            
            //$t1 = sprintf($xmlSection, $_tmpUmdName, $startSheetLineNum, $t3);
            
            $tmpArr = explode("_", $_tmpUmdName);
            $t1 = sprintf($xmlSection, $tmpArr[0] . "_" . $swtPreSheetName_pf[0], $t3);
            $t1a = sprintf($xmlSection, $tmpArr[0] . "_" . $swtPreSheetName_pf[1], $t3);
                          
            //$t2 = sprintf("\"%010d\"", $startSheetLineNum);
            //$n1 = strpos($t1, $t2);
            //if ($n1 === false)
            //{
            //    fclose($_fileHandle);
            //    fclose($_fileHandle2);
            //    fclose($_tempFileHandle);
            //    $returnMsg["errorCode"] = 0;
            //    $returnMsg["errorMsg"] = "template file content invalid, line: " . __LINE__;
            //    echo json_encode($returnMsg);
            //    return null;
            //}
            //// line num pos - strlen("\"")
            //$lineNumPos = ftell($_fileHandle) + $n1 + 1;
            fwrite($_fileHandle, $t1);
            fwrite($_fileHandle2, $t1a);
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
    
	public function genAverageDataForGraph($_isCompStandard, $_cmpStartResultID,
                                           $_curCardName, $_cmpCardName, $_graphCells,
                                           $_tmpSysName, $_cmpSysName)
	{
        global $returnMsg;
        global $testNameList;
        global $subTestUmdDataMaskList;
        global $umdNum;
        global $reportUmdNum;
        global $subTestNumList;
        global $subTestNumMap;
        global $cmpSubTestNumList;
        global $umdOrder;
        global $validUmdNum;
        global $resultUmdOrder;
        global $umdNameList;
        global $resultIDList;
        global $subjectNameFilterNumMax;
        global $driverNameList;
        global $startResultID;
        global $dataColumnNum;
        global $swtReportInfo;
        global $swtReportUmdInfo;
        global $crossType;
        global $curResultTime;
        global $cmpBatchTime;
        global $curMachineName;
        global $cmpMachineName;
        global $swtPreSheetName_pf;
        global $swtPreSheetNameShort_pf;

        $graphCells = $_graphCells;
        // columns have values (if true, not blank in excel table)
        //$averageColumnHasVal = array(false, false, false, false, false, false);
        $averageColumnHasVal = array_fill(0, $reportUmdNum * 2, false);
        
        $tmpReportUmdInfo = $swtReportUmdInfo;
        
        if ($_isCompStandard)
        {
            // generate average data for graph
            $t1 = "";
            //if (($_cmpStartResultID != -1) ||
            //    ($crossType == 2))
            if ($_cmpStartResultID != -1)
            {
                $tmpList1 = explode(" ", $_tmpSysName);
                $tmpList2 = explode(" ", $_cmpSysName);
                $addCurCardSysName = $_curCardName == $_cmpCardName ? "-" . $tmpList1[0] : "";
                $addCmpCardSysName = $_curCardName == $_cmpCardName ? "-" . $tmpList2[0] : "";
                
                if ($crossType == 2)
                {
                    // cross build
                    $addCurCardSysName = $cmpBatchTime;
                    $addCmpCardSysName = $curResultTime;
                }
                else
                {
                    $addCurCardSysName = $_curCardName . $addCurCardSysName;
                    $addCmpCardSysName = $_cmpCardName . $addCmpCardSysName;
                    
                    if ((strlen($curMachineName) > 0) &&
                        (strlen($cmpMachineName) > 0) &&
                        (strcmp($curMachineName, $cmpMachineName) != 0))
                    {
                        $addCurCardSysName = $curMachineName;
                        $addCmpCardSysName = $cmpMachineName;
                    }
                }
                
                $tmpAverageDataCode = "";
                $tmpCompAverageDataCode = "";
                $tmpGraphDataCode = "";
                
                $graphDataColumnNum = intval($dataColumnNum / 3);
                
                $isFirstColumn = true;
                // comile time
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
                        $tmpAverageDataCode = " <Cell ss:Index=\"" . ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1) . 
                                              "\" ss:StyleID=\"Default\"/>\n";
                        $tmpIndexCode = "ss:Index=\"" . ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 2 + $graphDataColumnNum) . "\"";
                        $tmpGraphDataCode = " <Cell ss:Index=\"" . 
                                            ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 2 + $graphDataColumnNum * 2 + 1) . 
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
                // average & graph data title
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
                                              ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 2) . 
                                              "\" ss:StyleID=\"Default\"/>\n";
                        $tmpGraphDataCode = " <Cell ss:Index=\"" . 
                                            ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 2 + $graphDataColumnNum + 2) . 
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
            
            $returnMsg["testNameList"] = $testNameList;
            $returnMsg["subTestNumList"] = $subTestNumList;
            $n1 = -1;
            
            $returnMsg["subTestNumList"] = $subTestNumList;
            $returnMsg["subTestNumMap"] = $subTestNumMap;
            $returnMsg["cmpSubTestNumList"] = $cmpSubTestNumList;
            
            $tmpRealTestNum = 0;
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
                $tmpRealTestNum++;
            }
            
            $graphCells2 = array();
            for ($i = 0; $i < count($testNameList); $i++)
            {
                if (intval($subTestNumList[$i]) == 0)
                {
                    //continue;
                }
                if (isset($subTestNumMap[$testNameList[$i]]) == false)
                {
                    continue;
                }
                if (intval($subTestNumMap[$testNameList[$i]]) == 0)
                {
                    continue;
                }
                if ($_cmpStartResultID != -1)
                {
                    if (intval($cmpSubTestNumList[$i]) == 0)
                    {
                        // skip blank test in graph
                        //continue;
                    }
                }
                
                $tmpList = explode("_", $testNameList[$i]);
                $tmpGroupName = (count($tmpList) > 1) ? $tmpList[1] : $testNameList[$i];

                $n2 = $n1 + $subTestNumList[$i] - 1;

                $t1 = "";
                $t1a = "";
                //if (($_cmpStartResultID != -1) ||
                //    ($crossType == 2))
                if ($_cmpStartResultID != -1)
                {
                    // if comparison with other cards
                    
                    $reportUmdNum = count($umdNameList);

                    $tmpVal = array_fill(0, $reportUmdNum * 2, "");
                    $tmpValHas = array_fill(0, $reportUmdNum * 2, "");
                    $tmpVal2 = array_fill(0, $reportUmdNum * 2, "");
                    $tmpValHas2 = array_fill(0, $reportUmdNum * 2, "");
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
                            $tmpIndexCode = "ss:Index=\"" . ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 2 + $graphDataColumnNum) . "\"";
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
                                                         
                        $tmpValHas2[$k] = " <Cell ss:StyleID=\"Default\" " .
                                        "ss:Formula=\"=AVERAGE(R[" . ($n1 - $tmpRealTestNum - 1) . "]C" . ($subjectNameFilterNumMax + 3 + 
                                        $dataColumnNum + $tmpColumnNum * 3) .
                                        ":R[" . ($n2 - $tmpRealTestNum - 1) . "]C" . ($subjectNameFilterNumMax + 3 + 
                                        $dataColumnNum + $tmpColumnNum * 3) . 
                                        ")\">" .
                                        "<Data ss:Type=\"Number\"></Data></Cell>\n";
                                        
                        $tmpValHas2[$reportUmdNum + $k] = " <Cell " . ($tmpIndexCode) . " ss:StyleID=\"Default\" " .
                                                         "ss:Formula=\"=AVERAGE(R[" . ($n1 - $tmpRealTestNum - 1) . "]C" . 
                                                         ($subjectNameFilterNumMax + 5 + 
                                                         $dataColumnNum + $tmpColumnNum * 3) .
                                                         ":R[" . ($n2 - $tmpRealTestNum - 1) . "]C" . 
                                                         ($subjectNameFilterNumMax + 5 + 
                                                         $dataColumnNum + $tmpColumnNum * 3) . 
                                                         ")\">" .
                                                         "<Data ss:Type=\"Number\"></Data></Cell>\n";
                                                         
                        $tmpColumnNum++;
                    }

                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        $tmpVal[$j] = "";
                        $tmpVal2[$j] = "";
                        if ($j == 0)
                        {
                            // spaces between cur card & cmp card
                            $tmpVal[$reportUmdNum + $j] = "<Cell ss:StyleID=\"Default\"></Cell>\n";
                            $tmpVal2[$reportUmdNum + $j] = $tmpVal[$reportUmdNum + $j];
                        }
                        else
                        {
                            $tmpVal[$reportUmdNum + $j] = "";
                            $tmpVal2[$reportUmdNum + $j] = "";
                        }
                        // cur card
                        if ($resultUmdOrder[$j] != -1)
                        {
                            $tmpVal[$j] = $tmpValHas[$j];
                            $tmpVal2[$j] = $tmpValHas2[$j];
                        }
                        
                        // cmp card
                        if ($resultUmdOrder[$reportUmdNum + $j] != -1)
                        {
                            $tmpVal[$reportUmdNum + $j] = $tmpValHas[$reportUmdNum + $j];
                            $tmpVal2[$reportUmdNum + $j] = $tmpValHas2[$reportUmdNum + $j];
                            if ($startIndex == -1)
                            {
                                // $startIndex = $j;
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
                        
                    $t1 = " <Cell ss:Index=\"" . ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1) . 
                          "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                          $tmpGroupName . "_" . $swtPreSheetNameShort_pf[0] . "</Data></Cell>\n";
                          
                    $t1a = " <Cell ss:Index=\"" . ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1) . 
                          "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                          $tmpGroupName . "_" . $swtPreSheetNameShort_pf[1] . "</Data></Cell>\n";
                          
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        $j = $k * 2;
                        $t1 .= $tmpVal[$j];
                        $t1 .= $tmpVal[$j + 1];
                        $t1a .= $tmpVal2[$j];
                        $t1a .= $tmpVal2[$j + 1];
                    }

                    $tmpColumnNum = 0;
                    
                    //$tmpValHas2 = "<Cell ss:StyleID=\"Default\"></Cell>\n";
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
                                             
                        $tmpValHas2[$j] = $tmpValHas[$j];
                        $tmpValHas2[$j + 1] = $tmpValHas[$j + 1];
                                        
                        // replace NV card DX12 into DX11
                        if (($this->testTempChange1($testNameList[$i])                     == true) &&
                            (strcmp(strtolower($tmpReportUmdInfo[$k]), strtolower("DX12")) == 0))
                        {
                            $tmpValHas[$j + 1] = " <Cell ss:StyleID=\"Default\" " .
                                                 "ss:Formula=\"=RC[-" . (3 + $graphDataColumnNum + $tmpColumnNum + 1) . "]/RC[-" . 
                                                 (3 + $graphDataColumnNum * 2 - $startIndex + $tmpColumnNum * 2 + 1) .
                                                 "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";

                            $tmpValHas2[$j + 1] = $tmpValHas[$j + 1];
                              
                            //$returnMsg["tmp_checkCell"] = $tmpValHas[$j + 1];
                        }
                        $returnMsg["tmpReportUmdInfo"] = $tmpReportUmdInfo;
                        $returnMsg["tmpReportUmdInfo_" . $k] = $tmpReportUmdInfo[$k];
                        $returnMsg["testNameList_" . $i] = $testNameList[$i];
                        $returnMsg["tmp_check1"] = $this->testTempChange1($testNameList[$i]) ? 112233 : 223344;
                        $returnMsg["tmp_check2"] = $tmpReportUmdInfo[$k];
                                        
                        $tmpColumnNum++;
                    }

                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        $j = $k * 2;

                        $tmpVal[$j] = "";
                        $tmpVal[$j + 1] = "";
                        $tmpVal2[$j] = "";
                        $tmpVal2[$j + 1] = "";
                        
                        // cur card
                        if (($resultUmdOrder[$k] != -1) &&
                            ($resultUmdOrder[$k + $reportUmdNum] != -1))
                        {
                            // this column has data, needs be shown in Graph
                            $averageColumnHasVal[$j] = true;
                            
                            $tmpVal[$j] = $tmpValHas[$j];
                            $tmpVal2[$j] = $tmpValHas2[$j];
                        }
                        
                        // cmp card
                        if (($resultUmdOrder[$k] != -1) &&
                            ($resultUmdOrder[$k + $reportUmdNum] != -1))
                        {
                            // this column has data, needs be shown in Graph
                            $averageColumnHasVal[$j + 1] = true;
                            
                            $tmpVal[$j + 1] = $tmpValHas[$j + 1];
                            $tmpVal2[$j + 1] = $tmpValHas2[$j + 1];
                        }
                    }

                    $t1 .= " <Cell ss:Index=\"" . 
                           ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 2 + $graphDataColumnNum * 2 + 1) . 
                           "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                           $tmpGroupName . "_" . $swtPreSheetNameShort_pf[0] . "</Data></Cell>\n";
                           
                    $t1a .= " <Cell ss:Index=\"" . 
                           ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 2 + $graphDataColumnNum * 2 + 1) . 
                           "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                           $tmpGroupName . "_" . $swtPreSheetNameShort_pf[1] . "</Data></Cell>\n";
                           
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        $j = $k * 2;
                        $t1 .= $tmpVal[$j];
                        $t1 .= $tmpVal[$j + 1];
                        $t1a .= $tmpVal2[$j];
                        $t1a .= $tmpVal2[$j + 1];
                    }
                }
                else
                {
                    // if no comparison
                    
                    $reportUmdNum = count($umdNameList);
                    //$tmpVal = array("", "", "");
                    //$tmpValHas = array("", "", "");
                    $tmpVal = array_fill(0, $reportUmdNum, "");
                    $tmpValHas = array_fill(0, $reportUmdNum, "");
                    $tmpVal2 = array_fill(0, $reportUmdNum, "");
                    $tmpValHas2 = array_fill(0, $reportUmdNum, "");
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
                        // comp time
                        $tmpValHas[$k] = " <Cell ss:StyleID=\"Default\" " .
                                         "ss:Formula=\"=AVERAGE(R[" . $n1 . "]C" . ($subjectNameFilterNumMax + 3 + $tmpColumnNum * 2) . // 6
                                         ":R[" . $n2 . "]C" . ($subjectNameFilterNumMax + 3 + $tmpColumnNum * 2) . 
                                         ")\">" .
                                         "<Data ss:Type=\"Number\"></Data></Cell>\n";
                        // exec time
                        $tmpValHas2[$k] = " <Cell ss:StyleID=\"Default\" " .
                                         "ss:Formula=\"=AVERAGE(R[" . ($n1 - $tmpRealTestNum) . "]C" . 
                                         ($subjectNameFilterNumMax + 3 + 
                                         $dataColumnNum + 1 + $tmpColumnNum * 2) . // 6
                                         ":R[" . ($n2 - $tmpRealTestNum) . "]C" . 
                                         ($subjectNameFilterNumMax + 3 + 
                                         $dataColumnNum + 1 + $tmpColumnNum * 2) . 
                                         ")\">" .
                                         "<Data ss:Type=\"Number\"></Data></Cell>\n";
                        
                        $tmpColumnNum++;
                    }
                    
                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        $tmpVal[$j] = "";
                        $tmpVal2[$j] = "";
                    }
                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        if ($resultUmdOrder[$j] != -1)
                        {
                            $tmpVal[$j] = $tmpValHas[$j];
                            $tmpVal2[$j] = $tmpValHas2[$j];
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
                            $tmpVal[$j] = "";
                            $tmpVal2[$j] = "";
                        }
                    }
                        
                    //$t1 = " <Cell ss:Index=\"" . ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1) . 
                    //      "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                    //      $tmpGroupName . "_" . $swtPreSheetNameShort_sb[0] . "</Data></Cell>\n";
                    //      
                    //$t1a = " <Cell ss:Index=\"" . ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1) . 
                    //      "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                    //      $tmpGroupName . "_" . $swtPreSheetNameShort_sb[1] . "</Data></Cell>\n";
                          
                          
                    $t1 = " <Cell ss:Index=\"" . ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1) . 
                          "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                          $tmpGroupName . "</Data></Cell>\n";
                          
                    $t1a = " <Cell ss:Index=\"" . ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1) . 
                          "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                          $tmpGroupName . "</Data></Cell>\n";
                          
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        $t1 .= $tmpVal[$k];
                        $t1a .= $tmpVal2[$k];
                    }

                    $tmpColumnNum = 0;
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        $tmpValHas[$k] = "";
                        $tmpValHas2[$k] = "";
                        if ($resultUmdOrder[$k] == -1)
                        {
                            // absent api
                            continue;
                        }
                        
                        $tmpValHas[$k] = " <Cell ss:StyleID=\"Default\" " .
                                        "ss:Formula=\"=RC[-" . (2 + $graphDataColumnNum + 1) . "]/RC[-" . 
                                        (2 + $graphDataColumnNum - $startIndex + $tmpColumnNum + 1) .
                                        "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                                        
                        $tmpValHas2[$k] = $tmpValHas[$k];
                        
                        // replace NV card DX12 into DX11
                        if (($this->testTempChange2($testNameList[$i])                     == true) &&
                            (strcmp(strtolower($tmpReportUmdInfo[$k]), strtolower("DX12")) == 0))
                        {
                            $tmpValHas[$k] = " <Cell ss:StyleID=\"Default\" " .
                                            "ss:Formula=\"=RC[-" . (2 + $graphDataColumnNum + 1 + 1) . "]/RC[-" . 
                                            (2 + $graphDataColumnNum - $startIndex + $tmpColumnNum + 1) .
                                            "]\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                                            
                            $tmpValHas2[$k] = $tmpValHas[$k];
                        }
                        
                        $tmpColumnNum++;
                    }
                    
                    for ($j = 0; $j < $reportUmdNum; $j++)
                    {
                        if ($resultUmdOrder[$j] != -1)
                        {
                            $tmpVal[$j] = $tmpValHas[$j];
                            $tmpVal2[$j] = $tmpValHas2[$j];
                        }
                        else
                        {
                            $tmpVal[$j] = "";
                            $tmpVal2[$j] = "";
                        }
                    }
                    
                    //$t1 .= " <Cell ss:Index=\"" . 
                    //       ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1 + $graphDataColumnNum + 2) . 
                    //       "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                    //       $tmpGroupName . "_" . $swtPreSheetNameShort_sb[0] . "</Data></Cell>\n";
                    //       
                    //$t1a .= " <Cell ss:Index=\"" . 
                    //       ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1 + $graphDataColumnNum + 2) . 
                    //       "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                    //       $tmpGroupName . "_" . $swtPreSheetNameShort_sb[1] . "</Data></Cell>\n";
                           
                    if ($i == 0)
                    {
                        // comp time or exec time line
                        
                        $t1 .= " <Cell ss:Index=\"" . 
                               ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1 + $graphDataColumnNum + 2) . 
                               "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                               $swtPreSheetName_pf[0] . "</Data></Cell>\n" .
                               " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                               $tmpGroupName . "</Data></Cell>\n";
                               
                        $t1a .= " <Cell ss:Index=\"" . 
                               ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1 + $graphDataColumnNum + 2) . 
                               "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                               $swtPreSheetName_pf[1] . "</Data></Cell>\n" .
                               " <Cell ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                               $tmpGroupName . "</Data></Cell>\n";
                    }
                    else
                    {
                        $t1 .= " <Cell ss:Index=\"" . 
                               ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1 + $graphDataColumnNum + 2 + 1) . 
                               "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                               $tmpGroupName . "</Data></Cell>\n";
                               
                        $t1a .= " <Cell ss:Index=\"" . 
                               ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1 + $graphDataColumnNum + 2 + 1) . 
                               "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                               $tmpGroupName . "</Data></Cell>\n";
                    }
                           
                    //$t1 .= " <Cell ss:Index=\"" . 
                    //       ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1 + $graphDataColumnNum + 2) . 
                    //       "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                    //       $tmpGroupName . "</Data></Cell>\n";
                    //       
                    //$t1a .= " <Cell ss:Index=\"" . 
                    //       ($subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1 + $graphDataColumnNum + 2) . 
                    //       "\" ss:StyleID=\"Default\"><Data ss:Type=\"String\">" .
                    //       $tmpGroupName . "</Data></Cell>\n";
                           
                    for ($k = 0; $k < $reportUmdNum; $k++)
                    {
                        $t1 .= $tmpVal[$k];
                        $t1a .= $tmpVal2[$k];
                    }
                            
                }
                array_push($graphCells, $t1);
                array_push($graphCells2, $t1a);
                $n1 = $n2 + 2;
            }
            
            foreach ($graphCells2 as $tmpVal)
            {
                $graphCells []= $tmpVal;
            }
            
            $returnMsg["graphCells"] = $graphCells;
            
            //$tmpArr = array();
            //$tmpArr["graphCells"] = $graphCells;
            //$t1 = json_encode($tmpArr);
            //file_put_contents("H:/wamp64/www/benchMax/test01.json", $t1);
        }
        


        $returnSet = array();
        $returnSet["graphCells"] = $graphCells;
        $returnSet["resultUmdOrder"] = $resultUmdOrder;
        $returnSet["averageColumnHasVal"] = $averageColumnHasVal;
        return $returnSet;
    }
    
	public function checkStartTest($_db, $_fileHandle, $_fileHandle2, $_tempFileHandle,
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
        global $subjectNameFilterNumMax;
        global $subjectFilterNameList;
        global $startGraphDataLinePos;
        global $dataColumnNum;
        global $crossType;
        //global $resultNoiseNum;
        global $historyBatchMaxNum;
        global $tableName01;
        global $resultIDList;
        global $resultPos;
        global $batchDateTextList;

        $db = $_db;
        $lineNum = $_lineNum;
        $sheetLinePos = $_sheetLinePos;
        $tempLineNum = $_tempLineNum;
        if ($_nextSubTestPos == $_firstSubTestPos)
        {
            // start of each test
            
            $tmpList = array_fill(0, ($subjectNameFilterNumMax + 1), " <Cell ss:StyleID=\"s" . ($startStyleID + 3) . "\"/>\n");
            $tmpList2 = array_fill(0, ($subjectNameFilterNumMax + 1), " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n");
            
            
            $tmpList[0] = " <Cell ss:StyleID=\"s" . ($startStyleID + 3) . "\"><Data ss:Type=\"String\">" .
                           "TestCaseID</Data></Cell>\n";
            for ($i = 0; $i < count($subjectFilterNameList[$_curTestPos]); $i++)
            {
                $tmpList[$i + 1] = " <Cell ss:StyleID=\"s" . ($startStyleID + 3) . "\"><Data ss:Type=\"String\">" .
                               "" . $subjectFilterNameList[$_curTestPos][$i] . "</Data></Cell>\n";
            }
            $tmpCode = implode("", $tmpList);
            $tmpCode2 = implode("", $tmpList2);
                    
            $tmpCode3 = "<Cell ss:StyleID=\"s" . ($startStyleID + 10) . "\"><Data ss:Type=\"String\">Variance</Data></Cell>\n";
                        
            $tmpCode4 = "<Cell ss:StyleID=\"s" . ($startStyleID + 13) . "\"/>\n";
                        
            $ordinalNumberList = array("1st", 
                                       "2nd",
                                       "3rd",
                                       "4th",
                                       "5th",
                                       "6th",
                                       "7th",
                                       "8th",
                                       "9th",
                                       "10th",
                                       "11th",
                                       "12th",
                                       "13th",
                                       "14th",
                                       "15th");
                                       
            $ordinalNameList = array("Current", 
                                     "Previous1",
                                     "Previous2",
                                     "Previous3",
                                     "Previous4",
                                     "Previous5");
                                     
            $singleGroupName = $testNameList[$_curTestPos];
            $tmpArr = explode("_", $singleGroupName);
            if (count($tmpArr) > 1)
            {
                $singleGroupName = ucwords($tmpArr[1]);
            }
                                       
            $tmpCode5 = "";
            $tmpCode6 = "";
            $tmpCode6a = "";
            
            $tmpList3 = explode("|", $unitNameList[$_curTestPos]);
            
            for ($i = 0; $i < $historyBatchMaxNum; $i++)
            {
                //$tmpCode5 .= "<Cell ss:StyleID=\"s" . ($startStyleID + 12) . "\"><Data ss:Type=\"String\">" . 
                //             $ordinalNameList[$i] . "</Data></Cell>\n";
                $tmpCode5 .= "<Cell ss:StyleID=\"s" . ($startStyleID + 12) . "\"><Data ss:Type=\"String\">" . 
                             $batchDateTextList[$i] . "</Data></Cell>\n";
                             
                if ($i < ($historyBatchMaxNum - 1))
                {
                    $tmpCode5 .= "<Cell ss:StyleID=\"s" . ($startStyleID + 12) . "\"/>\n";
                }
                             
                $tmpCode6 .= "<Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                             //$unitNameList[$_curTestPos] . "</Data></Cell>\n";
                             $tmpList3[0] . "</Data></Cell>\n";
                $tmpCode6a .= "<Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                             $tmpList3[1] . "</Data></Cell>\n";
                             
                if ($i < ($historyBatchMaxNum - 1))
                {
                    $tmpCode6 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n";
                    $tmpCode6a .= " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n";
                }
            }
                  
            $t1 = "<Row ss:StyleID=\"Default\">" .
                  " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                  $tmpCode2 .
                  $tmpCode3 .
                  $tmpCode5 .
                  "</Row>\n";
            
            $t2 = "<Row ss:StyleID=\"Default\">\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 2) . "\"><Data ss:Type=\"String\">" .
                   "" . $singleGroupName . "</Data></Cell>\n" .
                   $tmpCode .
                   $tmpCode4 .
                   $tmpCode6 .
                   "</Row>\n";
                   
            $t2a = "<Row ss:StyleID=\"Default\">\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 2) . "\"><Data ss:Type=\"String\">" .
                   "" . $singleGroupName . "</Data></Cell>\n" .
                   $tmpCode .
                   $tmpCode4 .
                   $tmpCode6a .
                   "</Row>\n";
            
            
            fwrite($_fileHandle, $t1 . $t2);
            fwrite($_fileHandle2, $t1 . $t2a);
            
            $lineNum += 2;
            
            if ($_isCompStandard)
            {
                // write comparison to tmp file
                // start of each test
                // black bar & test subject bar
                      
                $t1 = "<Row ss:StyleID=\"Default\" ss:AutoFitHeight=\"0\" ss:Height=\"5\">" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n" .
                      $tmpCode2;
                // + 1 is for black column between comp * exec time
                for ($i = 0; $i < ($dataColumnNum * 2 + 1); $i++)
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
                      "" . $singleGroupName . "</Data></Cell>\n" .
                      $tmpCode;
                      
                $t4 = "";
                $t4a = "";
                       
                if ($dataColumnNum == 1)
                {
                    $t4 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                           //"" . $unitNameList[$_curTestPos] . "</Data></Cell>\n";
                           "" . $tmpList3[0] . "</Data></Cell>\n";
                           
                    $t4a .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                           //"" . $unitNameList[$_curTestPos] . "</Data></Cell>\n";
                           "" . $tmpList3[1] . "</Data></Cell>\n";
                }
                else
                {
                    for ($i = 0; $i < ($dataColumnNum / 2); $i++)
                    {
                        $t4 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                               //"" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                               "" . $tmpList3[0] . "</Data></Cell>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n";
                               
                        $t4a .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                               "" . $tmpList3[1] . "</Data></Cell>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n";
                    }
                }
                       
                //if (($_cmpMachineID != -1) ||
                //    ($crossType == 2))
                if ($_cmpMachineID != -1)
                {
                    // if comparison with other cards
                    
                    $t4 = "";
                    $t4a = "";
                    
                    $t3 = "<Row>\n" .
                          " <Cell ss:StyleID=\"s" . ($startStyleID + 2) . "\"><Data ss:Type=\"String\">" .
                          "" . $singleGroupName . "</Data></Cell>\n" .
                          $tmpCode;
                    
                    for ($i = 0; $i < ($dataColumnNum / 3); $i++)
                    {
                        $t4 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                               //"" . $unitNameList[$_curTestPos] . "</Data></Cell>\n" .
                               "" . $tmpList3[0] . "</Data></Cell>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                               //"" . $unitNameList[$_curTestPos] . "</Data></Cell>\n";
                               "" . $tmpList3[0] . "</Data></Cell>\n";
                               
                        $t4a .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                               "" . $tmpList3[1] . "</Data></Cell>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\"/>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\"><Data ss:Type=\"String\">" .
                               "" . $tmpList3[1] . "</Data></Cell>\n";
                    }
                }
                // black column between comp & exec time
                $t5 = "    <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>";
                
                $t1 .= ($t3 . $t4 . $t5 . $t4a);
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
							
							$returnMsg["enter03"] = 3;
							$returnMsg["driverNameKeys"] = $driverNameKeys;
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
                $returnMsg["commonKeys"] = $commonKeys;
                $returnMsg["resultIDList"] = $resultIDList;
                array_push($historyResultIDList, $resultIDList[$i][$commonKeys[0]]);
            }
        }

        $returnSet = array();
        $returnSet["historyResultIDList"] = $historyResultIDList;
        return $returnSet;
    }
    
    public function getAverageDataList($_dataList)
    {
        if (count($_dataList) < 3)
        {
            $returnArr = array();
            for ($i = 0; $i < count($_dataList); $i++)
            {
                $returnArr []= $i;
            }
            return $returnArr;
        }
        
        //$n1 = array_sum($_dataList) / count($_dataList);
        $n1 = min($_dataList);
        
        $arr = array();
        for ($i = 0; $i < count($_dataList); $i++)
        {
            $arr []= abs($_dataList[$i] - $n1);
        }
        
        asort($arr);
        
        $arrValList = array();
        $arrKeyList = array();
        
        foreach ($arr as $k => $v)
        {
            $arrKeyList []= $k;
            $arrValList []= $v;
        }
        
        $minDist = -1;
        $dataIndex = -1;
        for ($i = 2; $i < count($arrValList); $i++)
        {
            $n2 = abs($arrValList[$i] - $arrValList[$i - 2]);
            if (($n2      <  $minDist) ||
                ($minDist == -1))
            {
                $minDist = $n2;
                $dataIndex = $i;
            }
        }
        
        //return array($_dataList[$arrKeyList[$dataIndex - 2]],
        //             $_dataList[$arrKeyList[$dataIndex - 1]],
        //             $_dataList[$arrKeyList[$dataIndex - 0]]);
                     
        $returnArr = array($arrKeyList[$dataIndex - 2],
                           $arrKeyList[$dataIndex - 1],
                           $arrKeyList[$dataIndex - 0]);
        sort($returnArr);
        return $returnArr;
    }
    
    public function getTmpFileName()
    {
        global $swtTempFilesDir;
        
        while (1)
        {
            $tmpToken = md5("sql_script_" . time());
            $tmpFileName = $swtTempFilesDir . "/sqlTmpFile" . $tmpToken . ".txt";
            if (file_exists($tmpFileName) == false)
            {
                $tmpRes = @file_put_contents($tmpFileName, "");
                if ($tmpRes !== false)
                {
                    return $tmpFileName;
                }
            }
        }
    }
    
	public function writeReportData($_db, $_fileHandle, $_fileHandle2, $_tempFileHandle,
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
        global $subjectNameFilterNumMax;
        //global $resultNoiseNum;
        global $historyBatchMaxNum;
        global $subTestNum;
        global $cardStandardResultPos;
        global $db_username;
        global $db_password;

        $db = $_db;
        $lineNum = $_lineNum;
        $nextSubTestPos = $_nextSubTestPos;
        
        $singleGroupName = $testName;
        $tmpArr = explode("_", $singleGroupName);
        if (count($tmpArr) > 1)
        {
            $singleGroupName = ucwords($tmpArr[1]);
        }
        
        // compile time
        
        $t1 = "";
        $tmpList = array();

        $noiseResultIDList = array();
        
        for ($i = 0; $i < $historyBatchMaxNum; $i++)
        {
            $t2 = "t" . (3 + $i);
            
            array_push($tmpList, $t2 . ".data_value1");
            $t1 .= "LEFT JOIN " . $tableName01 . " " . $t2 . " " .
                   "ON (" . $t2 . ".result_id=? AND " .
                   "t0.sub_id=" . $t2 . ".sub_id) ";
            if (($i < count($resultIDList)) && 
                ($_resultPos < count($resultIDList[$i])))
            {
                $noiseResultIDList []= $resultIDList[$i][$_resultPos];
            }
            else
            {
                $noiseResultIDList []= PHP_INT_MAX;
                //array_unshift($noiseResultIDList, PHP_INT_MAX);
            }
        }
        
        // variance data
        $t2 = "t" . (3);
        array_push($tmpList, $t2 . ".variance_value1");
        
        $noiseResultIDList []= $resultIDList[0][$cardStandardResultPos];
        
        $t3 = implode(",", $tmpList);
        //array_push($historyResultIDList, $resultIDList[0][$_resultPos]);
        
        if (strlen($t3) > 0)
        {
            $t3 = ", " . $t3;
        }
        
        // save this test to report
        // following line has no error, many question marks
        //$params1 = $historyResultIDList;
        $params1 = $noiseResultIDList;
        $returnMsg["noiseResultIDList"] = $noiseResultIDList;
        $sql1 = "SELECT t0.result_id, t0.sub_id, t0.data_value1, t0.test_case_id, " .
                "(SELECT t1.test_name FROM mis_table_test_info t1 WHERE t1.test_id=t0.sub_id) AS subTestName, " .
                "(SELECT t100.test_filter FROM mis_table_test_info t100 WHERE t100.test_id=t0.sub_id) AS subTestFilterName " .
                "" . $t3 . " " .
                "FROM " . $tableName01 . " t0 " .
                "" . $t1 . " " .
                "WHERE (t0.result_id=?) ORDER BY t0.data_id ASC LIMIT " . $nextSubTestPos . ", " . $maxSubTestNumOnce;
        if ($db->QueryDB($sql1, $params1) == null)
        {
            fclose($_fileHandle);
            fclose($_fileHandle2);
            fclose($_tempFileHandle);
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
            $returnMsg["sql1"] = $sql1;
            $returnMsg["params1"] = $params1;
            echo json_encode($returnMsg);
            return null;
        }
        
        $dataNum = 0;
        $t1 = "";
        $standardSubTestIDList = array();
        $standardSubTestNameList = array();
        $standardSubTestFilterNameList = array();
        $standardTestCaseIDList = array();
        $tmpDataList = array("", "", "", "", "",
                             "", "", "", "", "",
                             "", "", "", "", "");

        $testAverageData = "";
        $tmpTestCaseID = 0;
        while ($row1 = $db->fetchRow())
        {
            $tmpDataListXML = array("", "", "", "", "",
                                    "", "", "", "", "",
                                    "", "", "", "", "");
            $tmpData2 = "";
                                    
            //$subTestResultID = $row1[0];
            $subTestID = $row1[1];
            $subTestName = $row1[4];
            $subTestFilterName = $row1[5];
            $subTestFilterNameList = explode("|", str_replace("\"", "", $row1[5]));
            $dataValue = "" . $row1[2];
            $testCaseID = $row1[3];
            
            for ($i = 0; $i < $historyBatchMaxNum; $i++)
            {
                $n1 = 6 + $i;
                if ($n1 < count($row1))
                {
                    $tmpDataList[$i] = "" . $row1[$n1];
                    
                    if (strlen($tmpDataList[$i]) > 0)
                    {
                        $tmpDataListXML[$i] = "<Data ss:Type=\"Number\">" . $tmpDataList[$i] . "</Data>";
                    }
                }
                //$sortArrayList []= $row1[$n1];
            }
            
            $n1 = 6 + $historyBatchMaxNum;
            if ($n1 < count($row1))
            {
                $tmpData2 = $row1[$n1];
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
                           //$testCaseID .
                           ($tmpTestCaseID) .
                           "</Data></Cell>\n";
            for ($i = 0; $i < count($subTestFilterNameList); $i++)
            {
                $tmpList[$i + 1] = " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"><Data ss:Type=\"String\">" .
                               $subTestFilterNameList[$i] .
                               "</Data></Cell>\n";
            }
            $tmpCode = implode("", $tmpList);
            
            $tmpCode2 = "";
            
            for ($i = 0; $i < $historyBatchMaxNum; $i++)
            {
                             
                $rcID1 = ($subjectNameFilterNumMax + 4 + $i * 2);
                $rcID2 = ($subjectNameFilterNumMax + 4 + $i * 2 + 2);
                $tmpCode2 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" . $tmpDataListXML[$i] . "</Cell>\n";
                
                if ($i < ($historyBatchMaxNum - 1))
                {
                    $tmpCode2 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                                 "ss:Formula=\"=IF(OR(RC" . $rcID1 . "=&quot;&quot;," .
                                 "RC" . $rcID2 . "=&quot;&quot;," .
                                 "RC" . $rcID1 . "=0," .
                                 "RC" . $rcID2 . "=0" .
                                 "),&quot;&quot;," .
                                 "(RC" . $rcID1 . // 4
                                 "-RC" . $rcID2 . // 6
                                 ")/RC" . $rcID2 . ")\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                }
            }
            
            $tmpCode1 = "<Cell ss:StyleID=\"s" . ($startStyleID + 13) . "\" " .
                        "><Data ss:Type=\"Number\">" . $tmpData2 . "</Data></Cell>";
            
            // api sheet comparison
            $t1 .= "<Row ss:StyleID=\"Default\">\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 8) . "\"><Data ss:Type=\"String\">" . $singleGroupName . "</Data></Cell>\n" .
                   $tmpCode .
                   $tmpCode1 .
                   $tmpCode2 .
                   "</Row>\n";

            $lineNum++;

            if ($_isCompStandard)
            {
                //$tmpPos = array_search($testName, );
                array_push($standardSubTestIDList, $subTestID);
                array_push($standardSubTestNameList, $subTestName);
                array_push($standardSubTestFilterNameList, $subTestFilterNameList);
                //array_push($standardTestCaseIDList, $testCaseID);
                array_push($standardTestCaseIDList, $tmpTestCaseID);
            }
            $tmpTestCaseID++;
        }
        
        
        $returnMsg["dataNum"] = $dataNum;
        
        fwrite($_fileHandle, $t1);
        
        // execution time
        
        $t1 = "";
        $tmpList = array();

        $noiseResultIDList = array();
        
        for ($i = 0; $i < $historyBatchMaxNum; $i++)
        {
            $t2 = "t" . (3 + $i);
            
            array_push($tmpList, $t2 . ".data_value2");
            $t1 .= "LEFT JOIN " . $tableName01 . " " . $t2 . " " .
                   "ON (" . $t2 . ".result_id=? AND " .
                   "t0.sub_id=" . $t2 . ".sub_id) ";
            if (($i < count($resultIDList)) && 
                ($_resultPos < count($resultIDList[$i])))
            {
                $noiseResultIDList []= $resultIDList[$i][$_resultPos];
            }
            else
            {
                $noiseResultIDList []= PHP_INT_MAX;
            }
        }
        
        // variance data
        $t2 = "t" . (3);
        array_push($tmpList, $t2 . ".variance_value2");
        
        $noiseResultIDList []= $resultIDList[0][$cardStandardResultPos];
        
        $t3 = implode(",", $tmpList);
        //array_push($historyResultIDList, $resultIDList[0][$_resultPos]);
        
        if (strlen($t3) > 0)
        {
            $t3 = ", " . $t3;
        }
        
        // save this test to report
        // following line has no error, many question marks
        //$params1 = $historyResultIDList;
        $params1 = $noiseResultIDList;
        $sql1 = "SELECT t0.result_id, t0.sub_id, t0.data_value2, t0.test_case_id, " .
                "(SELECT t1.test_name FROM mis_table_test_info t1 WHERE t1.test_id=t0.sub_id) AS subTestName, " .
                "(SELECT t100.test_filter FROM mis_table_test_info t100 WHERE t100.test_id=t0.sub_id) AS subTestFilterName " .
                "" . $t3 . " " .
                "FROM " . $tableName01 . " t0 " .
                "" . $t1 . " " .
                "WHERE (t0.result_id=?) ORDER BY t0.data_id ASC LIMIT " . $nextSubTestPos . ", " . $maxSubTestNumOnce;
        if ($db->QueryDB($sql1, $params1) == null)
        {
            fclose($_fileHandle);
            fclose($_fileHandle2);
            fclose($_tempFileHandle);
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
            $returnMsg["sql1"] = $sql1;
            $returnMsg["params1"] = $params1;
            echo json_encode($returnMsg);
            return null;
        }
        
        $dataNum = 0;
        $t1 = "";
        //$standardSubTestIDList = array();
        //$standardSubTestNameList = array();
        //$standardSubTestFilterNameList = array();
        //$standardTestCaseIDList = array();
        $tmpDataList = array("", "", "", "", "",
                             "", "", "", "", "",
                             "", "", "", "", "");

        $testAverageData = "";
        while ($row1 = $db->fetchRow())
        {
            $tmpDataListXML = array("", "", "", "", "",
                                    "", "", "", "", "",
                                    "", "", "", "", "");
            $tmpData2 = "";
                                    
            //$subTestResultID = $row1[0];
            $subTestID = $row1[1];
            $subTestName = $row1[4];
            $subTestFilterName = $row1[5];
            $subTestFilterNameList = explode("|", str_replace("\"", "", $row1[5]));
            $dataValue = "" . $row1[2];
            $testCaseID = $row1[3];
            
            for ($i = 0; $i < $historyBatchMaxNum; $i++)
            {
                $n1 = 6 + $i;
                if ($n1 < count($row1))
                {
                    $tmpDataList[$i] = "" . $row1[$n1];
                    
                    if (strlen($tmpDataList[$i]) > 0)
                    {
                        $tmpDataListXML[$i] = "<Data ss:Type=\"Number\">" . $tmpDataList[$i] . "</Data>";
                    }
                }
                //$sortArrayList []= $row1[$n1];
            }
            
            $n1 = 6 + $historyBatchMaxNum;
            if ($n1 < count($row1))
            {
                $tmpData2 = $row1[$n1];
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
            
            $tmpCode2 = "";
            
            for ($i = 0; $i < $historyBatchMaxNum; $i++)
            {
                             
                $rcID1 = ($subjectNameFilterNumMax + 4 + $i * 2);
                $rcID2 = ($subjectNameFilterNumMax + 4 + $i * 2 + 2);
                $tmpCode2 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" . $tmpDataListXML[$i] . "</Cell>\n";
                
                if ($i < ($historyBatchMaxNum - 1))
                {
                    $tmpCode2 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                                 "ss:Formula=\"=IF(OR(RC" . $rcID1 . "=&quot;&quot;," .
                                 "RC" . $rcID2 . "=&quot;&quot;," .
                                 "RC" . $rcID1 . "=0," .
                                 "RC" . $rcID2 . "=0" .
                                 "),&quot;&quot;," .
                                 "(RC" . $rcID1 . // 4
                                 "-RC" . $rcID2 . // 6
                                 ")/RC" . $rcID2 . ")\"><Data ss:Type=\"Number\"></Data></Cell>\n";
                }
            }
            
            $tmpCode1 = "<Cell ss:StyleID=\"s" . ($startStyleID + 13) . "\" " .
                        "><Data ss:Type=\"Number\">" . $tmpData2 . "</Data></Cell>";
            
            // api sheet comparison
            $t1 .= "<Row ss:StyleID=\"Default\">\n" .
                   " <Cell ss:StyleID=\"s" . ($startStyleID + 8) . "\"><Data ss:Type=\"String\">" . $singleGroupName . "</Data></Cell>\n" .
                   $tmpCode .
                   $tmpCode1 .
                   $tmpCode2 .
                   "</Row>\n";

        }
        
        
        $returnMsg["dataNum"] = $dataNum;
        
        fwrite($_fileHandle2, $t1);
        
        

        $nextSubTestPos += $dataNum;
        
        //if ($dataNum == 0)
        //{
        //    $nextSubTestPos = $subTestNum;
        //}

        $returnSet = array();
        $returnSet["nextSubTestPos"] = $nextSubTestPos;
        $returnSet["lineNum"] = $lineNum;
        $returnSet["standardSubTestIDList"] = $standardSubTestIDList;
        $returnSet["standardSubTestNameList"] = $standardSubTestNameList;
        $returnSet["standardSubTestFilterNameList"] = $standardSubTestFilterNameList;
        $returnSet["standardTestCaseIDList"] = $standardTestCaseIDList;
        return $returnSet;
    }
    
    public function writeSummaryJsonPerTest($_summaryJson,
                                            $_variationJson,
                                            $_rateVal,
                                            $_summaryDataVal,
                                            $_cmpPartName,
                                            $_tmpTestCaseIndex)
    {
        global $testName;
        global $subTestNum;
        global $umdNameList;
        global $standardTestCaseIDList;
        global $standardSubTestNameList;
        
        $summaryJson = $_summaryJson;
        $variationJson = $_variationJson;
        $rateVal = $_rateVal;
        $summaryDataVal = $_summaryDataVal;
        $cmpPartName = $_cmpPartName;
        $n1 = $_tmpTestCaseIndex;
        
        $reportUmdNumn = count($umdNameList);
        
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
            
            for ($i = 0; $i < $reportUmdNumn; $i++)
            {
                $j = $i * 2;

                $tmpVal[$j] =     ($tmpVal[$j] == -1)     ? $rateVal[$i] : $tmpVal[$j];
                $tmpVal[$j + 1] = ($tmpVal[$j + 1] == -1) ? $rateVal[$i] : $tmpVal[$j + 1];
                
                $rateVal2[$j] =     ($rateVal[$i] == -1) ? $tmpVal[$j] :     $rateVal[$i];
                $rateVal2[$j + 1] = ($rateVal[$i] == -1) ? $tmpVal[$j + 1] : $rateVal[$i];
            }

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
            
            for ($i = 0; $i < $reportUmdNumn; $i++)
            {
                $j = $i * 2;
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
            }
            
            for ($i = 0; $i < $reportUmdNumn; $i++)
            {
                if ($rateVal[$i] == -1)
                {
                    continue;
                }
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
            
            $finalRateVal = array_fill(0, $reportUmdNumn * 2, -1);
            $tmpChangeFlag = array_fill(0, $reportUmdNumn * 2, false);
            
            for ($i = 0; $i < $reportUmdNumn; $i++)
            {
                $j = $i * 2;
                $finalRateVal[$j] =     min($rateVal2[$j],     $tmpVal[$j]);
                $finalRateVal[$j + 1] = max($rateVal2[$j + 1], $tmpVal[$j + 1]);
                
                $tmpChangeFlag[$j] =     $finalRateVal[$j]     != $tmpValOld[$j] ? true : false;
                $tmpChangeFlag[$j + 1] = $finalRateVal[$j + 1] != $tmpValOld[$j + 1] ? true : false;
                
                $tmpPos = $sectionPosList[3];
                
                $tmpVal[$tmpPos + $j * 4] =     $tmpChangeFlag[$j]     ? 
                                                $standardTestCaseIDList[$n1] : $tmpVal[$tmpPos + $j * 4];
                $tmpVal[$tmpPos + $j * 4 + 4] = $tmpChangeFlag[$j + 1] ? 
                                                $standardTestCaseIDList[$n1] : $tmpVal[$tmpPos + $j * 4 + 4];
                                                
                $tmpPos2 = $sectionPosList[3] + 1;
                $tmpVal[$tmpPos2 + $j * 4]     = $tmpChangeFlag[$j] ? 
                                                 $standardSubTestNameList[$n1] : $tmpVal[$tmpPos2 + $j * 4];
                $tmpVal[$tmpPos2 + $j * 4 + 4] = $tmpChangeFlag[$j + 1] ? 
                                                 $standardSubTestNameList[$n1] : $tmpVal[$tmpPos2 + $j * 4 + 4];
                                                 
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
            }
            
            $summaryJson[$testName] = array_fill(0, $sectionPosList[6], -1);
            
            for ($i = 0; $i < $reportUmdNumn; $i++)
            {
                $j = $i * 2;
                
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
        
        return $summaryJson;
    }
    
	public function writeReportCompareData($_db, $_tempFileHandle, $reportFolder,
                                           $_isCompStandard, $_umdNum, $_tempFileLineNumPos,
                                           $_startResultID, $_cmpStartResultID, $_historyStartResultID, $_tempLineNum,
                                           $_resultPos, $_sheetLinePos, $_startGraphDataLinePos,
                                           $_averageColumnHasVal)
	{
        global $returnMsg;
        global $resultIDList;
        global $driverNameList;
        global $umdNameList;
        global $umdOrder;
        global $resultUmdOrder;
        global $tableName01;
        global $testName;
        global $testNameList;
        global $subTestNumList;
        global $subTestNum;
        global $startStyleID;
        global $standardSubTestIDList;
        global $standardSubTestNameList;
        global $standardSubTestFilterNameList;
        global $subjectNameFilterNumMax;
        global $dataColumnNum;
        global $swtSheetColumnIDList;
        global $standardTestCaseIDList;
        global $graphCells;
        global $curCardName;
        global $cmpCardName;
        global $tmpSysName;
        global $cmpSysName;
        global $curMachineName;
        global $cmpMachineName;
        global $swtTempVBAConfigJsonName;
        global $graphDataStartCloumnIDCompareList;
        global $jsonFileName;
        global $jsonFileName2;
        global $jsonFileName3;
        global $jsonFileName4;
        global $reportTemplateDir;
        global $subTestUmdDataMaskList;
        global $dataColumnNum;
        global $cmpMachineID;
        global $curMachineID;
        global $crossType;
        global $machineIDBatchPairList;
        global $crossBuildResultIDList;
        global $curResultTime;
        global $cmpBatchTime;
        global $batchIDList;

        $singleGroupName = $testName;
        $tmpArr = explode("_", $singleGroupName);
        if (count($tmpArr) > 1)
        {
            $singleGroupName = ucwords($tmpArr[1]);
        }
        
        $db = $_db;
        $tempFileHandle = $_tempFileHandle;
        $umdNum = $_umdNum;
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
                         
        $graphDataArea = "" . $swtSheetColumnIDList[$subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 1 + $graphDataColumnNum + 1] . 
                         graphDataStartLineID . ":" . 
                         $swtSheetColumnIDList[$subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 2 + $graphDataColumnNum + 1 + $graphDataColumnNum] .
                         (intval(graphDataStartLineID) + count($graphCells) - 1);
                         
                         
        $returnMsg["tmp_graphCells"] = $graphCells;
        $returnMsg["tmp_graphCellsNum"] = count($graphCells);
        $returnMsg["tmp_n1"] = (intval(graphDataStartLineID) + count($graphCells) - 1);
        $returnMsg["tmp_n2"] = (intval(graphDataStartLineIDCompare) + count($graphCells) - 1);
                         
        $graphDataAreaNoBlank = $graphDataArea;
        
        //if (($_cmpStartResultID != -1) ||
        //    ($crossType == 2))
        if ($_cmpStartResultID != -1)
        {
            $hasBlank = array_search(false, $_averageColumnHasVal);
                                    
            $graphDataColumnNum = intval($dataColumnNum / 3);
                                    
            $graphDataAreaNoBlank = "" . $swtSheetColumnIDList[$subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 2 + 
                                    $graphDataColumnNum * 2] .
                                    graphDataStartLineIDCompare . ":" . 
                                    $swtSheetColumnIDList[$subjectNameFilterNumMax + 3 + ($dataColumnNum * 2) + 1 + 2 + 
                                    $graphDataColumnNum * 2 + $graphDataColumnNum * 2] .
                                    (intval(graphDataStartLineIDCompare) + count($graphCells) - 1);
            
            $graphDataArea = $graphDataAreaNoBlank;
        }
        
        $tmpJson = array();
        $tmpJson["graphDataArea"] = $graphDataArea;
        $tmpJson["graphDataAreaNoBlank"] = $graphDataAreaNoBlank;
        $tmpJson["curCardName"] = $curCardName;
        $tmpJson["cmpCardName"] = $cmpCardName;
        $tmpJson["curSysName"] = $tmpSysName;
        $tmpJson["cmpSysName"] = $cmpSysName;
        $tmpJson["curMachineName"] = $curMachineName;
        $tmpJson["cmpMachineName"] = $cmpMachineName;
        $tmpJson["cmpMachineID"] = $cmpMachineID;
        $tmpJson["crossType"] = $crossType;
        $tmpJson["curResultTime"] = $curResultTime;
        $tmpJson["cmpBatchTime"] = $cmpBatchTime;
        // use DX11, DX12, vulkan mask of Alu as overall mask
        // subTestUmdDataMaskList[0]
        $tmpMask = $subTestUmdDataMaskList[0];
        $dropArea = array();

        $tmpJson["dropArea"] = $dropArea;
        $t1 = json_encode($tmpJson);
        
        //$tmpdestPath = $reportFolder . "/" . $curCardName . "_" . $tmpSysName . "/" . $swtTempVBAConfigJsonName;
        //file_put_contents($tmpdestPath, $t1);
        
        if ($_isCompStandard)
        {
            $tmpdestPath = $reportFolder . "/" . $curCardName . "_" . $tmpSysName . "/" . $swtTempVBAConfigJsonName;
            file_put_contents($tmpdestPath, $t1);
            
            // umd data should occur as order in $umdNameList
            //$umdOrder = array(0, 1, 2, 0, 1, 2);
            
            // collect all data for all umd types
            $t2 = "";
            $selectKeyList = array();
            $n1 = 1;
            $n1a = 1;
            //$dataIndexList = array(-1, -1, -1, -1, -1, -1);
            $dataIndexList = array_fill(0, $reportUmdNumn * 3, -1);
            $dataIndexList2 = array_fill(0, $reportUmdNumn * 3, -1);
            array_push($selectKeyList, "t0.data_value1");
            $params1 = array();
            $tmpParams1 = array();
            $tmpParams2 = array();
            $tmpParams3 = array();
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
                array_push($selectKeyList, $nextTabName . ".data_value1");
                array_push($selectKeyList, $nextTabName . ".data_value2");
                $t2 .= "LEFT JOIN " . $tableName01 . " " . $nextTabName . " " .
                       "ON (" . $nextTabName . ".result_id=? AND t0.sub_id=" . $nextTabName . ".sub_id) ";
                $dataIndexList[$i] = $n1a;
                $dataIndexList2[$i] = $n1a + 1;
                array_push($tmpParams1, $resultIDList[0][$_startResultID + $i]);
                $n1++;
                $n1a += 2;
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
                    array_push($selectKeyList, $nextTabName . ".data_value1");
                    array_push($selectKeyList, $nextTabName . ".data_value2");
                    $t2 .= "LEFT JOIN " . $tableName01 . " " . $nextTabName . " " .
                           "ON (" . $nextTabName . ".result_id=? AND t0.sub_id=" . $nextTabName . ".sub_id) ";
                    $dataIndexList[$reportUmdNumn + $i] = $n1a;
                    $dataIndexList2[$reportUmdNumn + $i] = $n1a + 1;
                    array_push($tmpParams2, $resultIDList[0][$_cmpStartResultID + $i]);
                    $n1++;
                    $n1a += 2;
                }
            }
            //else if ($crossType == 2)
            //{
            //    // cross build
            //    // if comparison with other builds
            //    
            //    $tmpListIndex = -1;
            //    for ($i = 0; $i < (count($machineIDBatchPairList) / 2); $i++)
            //    {
            //        if (intval($machineIDBatchPairList[$i * 2]) == $curMachineID)
            //        {
            //            $tmpListIndex = $i;
            //            break;
            //        }
            //    }
            //    
            //    for ($i = 0; $i < $umdNum; $i++)
            //    {
            //        $nextTabName = "t" . $n1;
            //        array_push($selectKeyList, $nextTabName . ".data_value1");
            //        $t2 .= "LEFT JOIN " . $tableName01 . " " . $nextTabName . " " .
            //               "ON (" . $nextTabName . ".result_id=? AND t0.sub_id=" . $nextTabName . ".sub_id) ";
            //        $dataIndexList[$reportUmdNumn + $i] = $n1;
            //        array_push($tmpParams2, $crossBuildResultIDList[$tmpListIndex][$i]);
            //        $n1++;
            //    }
            //}
            // summary sheet2, compare with last batch
            for ($i = 0; $i < $umdNum; $i++)
            {
                $nextTabName = "t" . $n1;
                array_push($selectKeyList, $nextTabName . ".data_value1");
                array_push($selectKeyList, $nextTabName . ".data_value2");
                $t2 .= "LEFT JOIN " . $tableName01 . " " . $nextTabName . " " .
                       "ON (" . $nextTabName . ".result_id=? AND t0.sub_id=" . $nextTabName . ".sub_id) ";
                $dataIndexList[$reportUmdNumn * 2 + $i] = $n1a;
                $dataIndexList2[$reportUmdNumn * 2 + $i] = $n1a + 1;
                //$tmpResultIndex = $_historyStartResultID == -1 ? $_startResultID : $_historyStartResultID;
                //$tmpbatchIndex = $_historyStartResultID == -1 ? 0 : 1;
                
                $tmpResultIndex = $_startResultID;
                $tmpbatchIndex = (count($resultIDList) <= 1) ? 0 : 1;
                //array_push($tmpParams3, $resultIDList[$tmpbatchIndex][$tmpResultIndex + $i]);
                
                if (($tmpResultIndex + $i) < count($resultIDList[$tmpbatchIndex]))
                {
                    array_push($tmpParams3, $resultIDList[$tmpbatchIndex][$tmpResultIndex + $i]);
                }
                else
                {
                    array_push($tmpParams3, PHP_INT_MAX);
                }
                $n1++;
                $n1a += 2;
            }
            
            
            if ($crossType == 2)
            {
                // cross build
                foreach ($tmpParams2 as $tmpVal)
                {
                    array_push($params1, $tmpVal);
                }
                foreach ($tmpParams1 as $tmpVal)
                {
                    array_push($params1, $tmpVal);
                }
                foreach ($tmpParams3 as $tmpVal)
                {
                    array_push($params1, $tmpVal);
                }
            }
            else
            {
                foreach ($tmpParams1 as $tmpVal)
                {
                    array_push($params1, $tmpVal);
                }
                foreach ($tmpParams2 as $tmpVal)
                {
                    array_push($params1, $tmpVal);
                }
                foreach ($tmpParams3 as $tmpVal)
                {
                    array_push($params1, $tmpVal);
                }
            }
            
            $returnMsg["dataIndexList"] = $dataIndexList;
            $returnMsg["dataIndexList2"] = $dataIndexList2;
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
                $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__ . ", error: " . $db->getError()[2];
                $returnMsg["sql1"] = $sql1;
                $returnMsg["params1"] = $params1;
                echo json_encode($returnMsg);
                return null;
            }
            
            $returnMsg["umdNameList"] = $umdNameList;
            $returnMsg["umdName"] = $umdNameList[1];
            $returnMsg["sql1"] = $sql1;
            $returnMsg["params1"] = $params1;
            $returnMsg["tmp002"] = "";
            
            
            $summaryJson = array();
            $summaryJson2 = array();
            $summaryJson3 = array();
            $summaryJson4 = array();
            $variationJson = array();
            //if ($_cmpStartResultID != -1)
            {
                $t1 = file_get_contents($jsonFileName);
                $summaryJson = json_decode($t1, true);
                
                $t1 = file_get_contents($jsonFileName2);
                $summaryJson2 = json_decode($t1, true);
                
                $t1 = file_get_contents($jsonFileName3);
                $summaryJson3 = json_decode($t1, true);
                
                $t1 = file_get_contents($jsonFileName4);
                $summaryJson4 = json_decode($t1, true);
                
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
                
                
                $umdData = array_fill(0, $reportUmdNumn * 3, "");
                $umdDataXML = array_fill(0, $reportUmdNumn * 3, "");
                $umdDataVal = array_fill(0, $reportUmdNumn * 3, -1);
                $umdData2 = array_fill(0, $reportUmdNumn * 3, "");
                $umdDataXML2 = array_fill(0, $reportUmdNumn * 3, "");
                $umdDataVal2 = array_fill(0, $reportUmdNumn * 3, -1);
                
                for ($j = 0; $j < $umdNum; $j++)
                {
                    if ($j >= count($resultIDList[0]))
                    {
                        //continue;
                    }
                    $umdData[$j] = "";
                    $umdData2[$j] = "";
                    if ($dataIndexList[$j] != -1)
                    {
                        $umdData[$j] = "" . $row1[$dataIndexList[$j]];
                        $umdData2[$j] = "" . $row1[$dataIndexList2[$j]];
                    }
                    
                    if (strlen($umdData[$j]) > 0)
                    {
                        // if null value, leave it null
                        $umdDataXML[$j] = "<Data ss:Type=\"Number\">" . $umdData[$j] . "</Data>";
                        $umdDataVal[$j] = floatval($umdData[$j]);
                        $umdDataXML2[$j] = "<Data ss:Type=\"Number\">" . $umdData2[$j] . "</Data>";
                        $umdDataVal2[$j] = floatval($umdData2[$j]);
                    }
                    //$dataVal[$j] = floatval($umdData[$j]);
                    
                    //if (($_cmpStartResultID != -1) ||
                    //    ($crossType == 2))
                    if ($_cmpStartResultID != -1)
                    {
                        $umdData[$reportUmdNumn + $j] = "";
                        $umdData2[$reportUmdNumn + $j] = "";
                        if ($dataIndexList[$reportUmdNumn + $j] != -1)
                        {
                            $umdData[$reportUmdNumn + $j] = "" . $row1[$dataIndexList[$reportUmdNumn + $j]];
                            $umdData2[$reportUmdNumn + $j] = "" . $row1[$dataIndexList2[$reportUmdNumn + $j]];
                        }
                        if (strlen($umdData[$reportUmdNumn + $j]) > 0)
                        {
                            // if null value, leave it null
                            $umdDataXML[$reportUmdNumn + $j] = "<Data ss:Type=\"Number\">" . $umdData[$reportUmdNumn + $j] . "</Data>";
                            $umdDataVal[$reportUmdNumn + $j] = floatval($umdData[$reportUmdNumn + $j]);
                            $umdDataXML2[$reportUmdNumn + $j] = "<Data ss:Type=\"Number\">" . $umdData2[$reportUmdNumn + $j] . "</Data>";
                            $umdDataVal2[$reportUmdNumn + $j] = floatval($umdData2[$reportUmdNumn + $j]);
                        }
                    }
                    // summary sheet2, last batch data
                    $umdData[$reportUmdNumn * 2 + $j] = "";
                    $umdData2[$reportUmdNumn * 2 + $j] = "";
                    if ($dataIndexList[$reportUmdNumn * 2 + $j] != -1)
                    {
                        $umdData[$reportUmdNumn * 2 + $j] = "" . $row1[$dataIndexList[$reportUmdNumn * 2 + $j]];
                        $umdData2[$reportUmdNumn * 2 + $j] = "" . $row1[$dataIndexList2[$reportUmdNumn * 2 + $j]];
                    }
                    if (strlen($umdData[$reportUmdNumn * 2 + $j]) > 0)
                    {
                        // if null value, leave it null
                        $umdDataXML[$reportUmdNumn * 2 + $j] = "<Data ss:Type=\"Number\">" . $umdData[$reportUmdNumn * 2 + $j] . "</Data>";
                        $umdDataVal[$reportUmdNumn * 2 + $j] = floatval($umdData[$reportUmdNumn * 2 + $j]);
                        $umdDataXML2[$reportUmdNumn * 2 + $j] = "<Data ss:Type=\"Number\">" . $umdData2[$reportUmdNumn * 2 + $j] . "</Data>";
                        $umdDataVal2[$reportUmdNumn * 2 + $j] = floatval($umdData2[$reportUmdNumn * 2 + $j]);
                    }
                }

                $tmpData = array_fill(0, $reportUmdNumn * 3, "");
                $tmpDataVal = array_fill(0, $reportUmdNumn * 3, -1);
                $tmpData2 = array_fill(0, $reportUmdNumn * 3, "");
                $tmpDataVal2 = array_fill(0, $reportUmdNumn * 3, -1);
                for ($i = 0; $i < $umdNum; $i++)
                {
                    if ($umdOrder[$i] != -1)
                    {
                        $tmpData[$i] = $umdDataXML[$umdOrder[$i]];
                        $tmpData[$reportUmdNumn + $i] = $umdDataXML[$reportUmdNumn + $umdOrder[$i]];
                        $tmpData[$reportUmdNumn * 2 + $i] = $umdDataXML[$reportUmdNumn * 2 + $umdOrder[$i]];
                        
                        $tmpDataVal[$i] = $umdDataVal[$umdOrder[$i]];
                        $tmpDataVal[$reportUmdNumn + $i] = $umdDataVal[$reportUmdNumn + $umdOrder[$i]];
                        $tmpDataVal[$reportUmdNumn * 2 + $i] = $umdDataVal[$reportUmdNumn * 2 + $umdOrder[$i]];
                        
                        $tmpData2[$i] = $umdDataXML2[$umdOrder[$i]];
                        $tmpData2[$reportUmdNumn + $i] = $umdDataXML2[$reportUmdNumn + $umdOrder[$i]];
                        $tmpData2[$reportUmdNumn * 2 + $i] = $umdDataXML2[$reportUmdNumn * 2 + $umdOrder[$i]];
                        
                        $tmpDataVal2[$i] = $umdDataVal2[$umdOrder[$i]];
                        $tmpDataVal2[$reportUmdNumn + $i] = $umdDataVal2[$reportUmdNumn + $umdOrder[$i]];
                        $tmpDataVal2[$reportUmdNumn * 2 + $i] = $umdDataVal2[$reportUmdNumn * 2 + $umdOrder[$i]];
                    }
                }
                
                $tmpList = array_fill(0, ($subjectNameFilterNumMax + 1), " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"/>\n");
                
                $tmpList[0] = " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"><Data ss:Type=\"Number\">" .
                               "" . $standardTestCaseIDList[$n1] . "</Data></Cell>\n";
                for ($i = 0; $i < count($standardSubTestFilterNameList[$n1]); $i++)
                {
                    $tmpList[$i + 1] = " <Cell ss:StyleID=\"s" . ($startStyleID + 6) . "\"><Data ss:Type=\"String\">" .
                                   "" . $standardSubTestFilterNameList[$n1][$i] . "</Data></Cell>\n";
                }
                $tmpCode = implode("", $tmpList);
                
                // data rows for api comparison
                $t3 = "<Row>\n" .
                      " <Cell ss:StyleID=\"s" . ($startStyleID + 8) . "\"><Data ss:Type=\"String\">" . $singleGroupName . "</Data></Cell>\n" .
                      $tmpCode;
                       
                $tmpDataColumnNum = 0;
                $tmpDataList = array();
                $tmpDataValList = array();
                $tmpDataList2 = array();
                $tmpDataValList2 = array();
                for ($i = 0; $i < $reportUmdNumn; $i++)
                {
                    if ($resultUmdOrder[$i] == -1)
                    {
                        // absent api
                        continue;
                    }
                    array_push($tmpDataList, $tmpData[$i]);
                    array_push($tmpDataValList, $tmpDataVal[$i]);
                    array_push($tmpDataList2, $tmpData2[$i]);
                    array_push($tmpDataValList2, $tmpDataVal2[$i]);
                    $tmpDataColumnNum++;
                }
                
                if ($tmpDataColumnNum == 1)
                {
                    // 1 api
                    $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                           "" . $tmpDataList[0] . "</Cell>\n" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>" .
                           " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                           "" . $tmpDataList2[0] . "</Cell>\n";
                }
                else
                {
                    // more than 1 api
                    // compile time
                    for ($i = 0; $i < $tmpDataColumnNum; $i++)
                    {
                        if ($i < ($tmpDataColumnNum - 1))
                        {
                            // 
                            $rcID1 = ($subjectNameFilterNumMax + 5 + $i * 2);
                            $rcID2 = ($subjectNameFilterNumMax + 3 + $i * 2);
     
                            $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                                   "" . $tmpDataList[$i] . "</Cell>\n" .
                                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                                   "ss:Formula=\"=IF(OR(RC" . $rcID1 . "=&quot;&quot;," .
                                                "RC" . $rcID2 . "=&quot;&quot;," .
                                                "RC" . $rcID1 . "=0," .
                                                "RC" . $rcID2 . "=0" .
                                                "),&quot;&quot;," .
                                                "(RC" . $rcID1 . // 8
                                                "-RC" . $rcID2 . // 6
                                                ")/RC" . $rcID2 . ")\">" .
                                                "<Data ss:Type=\"Number\"></Data></Cell>\n";
                                                
                            //$t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                            //       "" . $tmpDataList[$i] . "</Cell>\n" .
                            //       " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                            //       "ss:Formula=\"=IF(OR(RC" . $rcID1 . "=&quot;&quot;," .
                            //                    "RC" . $rcID2 . "=&quot;&quot;," .
                            //                    "RC" . $rcID1 . "=0," .
                            //                    "RC" . $rcID2 . "=0" .
                            //                    "),&quot;&quot;," .
                            //                    "RC" . $rcID1 . // 8
                            //                    "/RC" . $rcID2 . ")\">" .
                            //                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                        }
                        else
                        {
                            // 
                            //$rcID1 = ($subjectNameFilterNumMax + 3 + $i * 2);
                            //$rcID2 = ($subjectNameFilterNumMax + 3);
                            $rcID2 = ($subjectNameFilterNumMax + 3 + $i * 2);
                            $rcID1 = ($subjectNameFilterNumMax + 3);
                            $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                                   "" . $tmpDataList[$i] . "</Cell>\n" .
                                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                                   "ss:Formula=\"=IF(OR(RC" . $rcID1 . "=&quot;&quot;," .
                                                "RC" . $rcID2 . "=&quot;&quot;," .
                                                "RC" . $rcID1 . "=0," .
                                                "RC" . $rcID2 . "=0" .
                                                "),&quot;&quot;," .
                                                "(RC" . $rcID1 . // 8
                                                "-RC" . $rcID2 . // 6
                                                ")/RC" . $rcID2 . ")\">" .
                                                "<Data ss:Type=\"Number\"></Data></Cell>\n";
                                                
                            //$t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                            //       "" . $tmpDataList[$i] . "</Cell>\n" .
                            //       " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                            //       "ss:Formula=\"=IF(OR(RC" . $rcID1 . "=&quot;&quot;," .
                            //                    "RC" . $rcID2 . "=&quot;&quot;," .
                            //                    "RC" . $rcID1 . "=0," .
                            //                    "RC" . $rcID2 . "=0" .
                            //                    "),&quot;&quot;," .
                            //                    "RC" . $rcID1 . // 8
                            //                    "/RC" . $rcID2 . ")\">" .
                            //                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                        }
                    }
                    
                    $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>";
                    
                    // execution time
                    for ($i = 0; $i < $tmpDataColumnNum; $i++)
                    {
                        if ($i < ($tmpDataColumnNum - 1))
                        {
                            // 
                            $rcID1 = ($subjectNameFilterNumMax + 5 + ($tmpDataColumnNum * 2) + $i * 2 + 1);
                            $rcID2 = ($subjectNameFilterNumMax + 3 + ($tmpDataColumnNum * 2) + $i * 2 + 1);
     
                            $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                                   "" . $tmpDataList2[$i] . "</Cell>\n" .
                                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                                   "ss:Formula=\"=IF(OR(RC" . $rcID1 . "=&quot;&quot;," .
                                                "RC" . $rcID2 . "=&quot;&quot;," .
                                                "RC" . $rcID1 . "=0," .
                                                "RC" . $rcID2 . "=0" .
                                                "),&quot;&quot;," .
                                                "(RC" . $rcID1 . // 8
                                                "-RC" . $rcID2 . // 6
                                                ")/RC" . $rcID2 . ")\">" .
                                                "<Data ss:Type=\"Number\"></Data></Cell>\n";
                                                
                            //$t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                            //       "" . $tmpDataList2[$i] . "</Cell>\n" .
                            //       " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                            //       "ss:Formula=\"=IF(OR(RC" . $rcID1 . "=&quot;&quot;," .
                            //                    "RC" . $rcID2 . "=&quot;&quot;," .
                            //                    "RC" . $rcID1 . "=0," .
                            //                    "RC" . $rcID2 . "=0" .
                            //                    "),&quot;&quot;," .
                            //                    "RC" . $rcID1 . // 8
                            //                    "/RC" . $rcID2 . ")\">" .
                            //                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                        }
                        else
                        {
                            // 
                            //$rcID1 = ($subjectNameFilterNumMax + 3 + ($tmpDataColumnNum * 2) + $i * 2 + 1);
                            //$rcID2 = ($subjectNameFilterNumMax + 3 + ($tmpDataColumnNum * 2) + 1);
                            $rcID2 = ($subjectNameFilterNumMax + 3 + ($tmpDataColumnNum * 2) + $i * 2 + 1);
                            $rcID1 = ($subjectNameFilterNumMax + 3 + ($tmpDataColumnNum * 2) + 1);
                            $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                                   "" . $tmpDataList2[$i] . "</Cell>\n" .
                                   " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                                   "ss:Formula=\"=IF(OR(RC" . $rcID1 . "=&quot;&quot;," .
                                                "RC" . $rcID2 . "=&quot;&quot;," .
                                                "RC" . $rcID1 . "=0," .
                                                "RC" . $rcID2 . "=0" .
                                                "),&quot;&quot;," .
                                                "(RC" . $rcID1 . // 8
                                                "-RC" . $rcID2 . // 6
                                                ")/RC" . $rcID2 . ")\">" .
                                                "<Data ss:Type=\"Number\"></Data></Cell>\n";
                                                
                            //$t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                            //       "" . $tmpDataList2[$i] . "</Cell>\n" .
                            //       " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                            //       "ss:Formula=\"=IF(OR(RC" . $rcID1 . "=&quot;&quot;," .
                            //                    "RC" . $rcID2 . "=&quot;&quot;," .
                            //                    "RC" . $rcID1 . "=0," .
                            //                    "RC" . $rcID2 . "=0" .
                            //                    "),&quot;&quot;," .
                            //                    "RC" . $rcID1 . // 8
                            //                    "/RC" . $rcID2 . ")\">" .
                            //                    "<Data ss:Type=\"Number\"></Data></Cell>\n";
                        }
                    }
                }
                
                //*/
                // compile time
                $summaryDataVal = array_fill(0, $reportUmdNumn * 2, -1);
                $cmpPartName = array_fill(0, $reportUmdNumn * 2, "");
                // for summary sheet2, cur & last batch
                $summaryDataVal2 = array_fill(0, $reportUmdNumn * 2, -1);
                $cmpPartName2 = array_fill(0, $reportUmdNumn * 2, "");
                // execution time
                $summaryDataVal5 = array_fill(0, $reportUmdNumn * 2, -1);
                $cmpPartName5 = array_fill(0, $reportUmdNumn * 2, "");
                $summaryDataVal6 = array_fill(0, $reportUmdNumn * 2, -1);
                $cmpPartName6 = array_fill(0, $reportUmdNumn * 2, "");
                //if (($_cmpStartResultID != -1) ||
                //    ($crossType == 2))
                if ($_cmpStartResultID != -1)
                {
                    // data rows for asic comparison
                    $t3 = "<Row>\n" .
                          " <Cell ss:StyleID=\"s" . ($startStyleID + 8) . "\"><Data ss:Type=\"String\">" . $singleGroupName . "</Data></Cell>\n" .
                          $tmpCode;
                    
                    $tmpDataColumnNum = 0;
                    $tmpDataList = array();
                    $tmpDataList2 = array();
                    $tmpDataValList = array();
                    $tmpDataValList2 = array();
                    $tmpDataList_2 = array();
                    $tmpDataList2_2 = array();
                    $tmpDataValList_2 = array();
                    $tmpDataValList2_2 = array();
                    for ($i = 0; $i < $reportUmdNumn; $i++)
                    {
                        if (($resultUmdOrder[$i] == -1) ||
                            ($resultUmdOrder[$reportUmdNumn + $i] == -1))
                        {
                            // absent api
                            continue;
                        }
                        array_push($tmpDataList, $tmpData[$i]);
                        array_push($tmpDataList2, $tmpData[$reportUmdNumn + $i]);
                        array_push($tmpDataValList, $tmpDataVal[$i]);
                        array_push($tmpDataValList2, $tmpDataVal[$reportUmdNumn + $i]);
                        array_push($tmpDataList_2, $tmpData2[$i]);
                        array_push($tmpDataList2_2, $tmpData2[$reportUmdNumn + $i]);
                        array_push($tmpDataValList_2, $tmpDataVal2[$i]);
                        array_push($tmpDataValList2_2, $tmpDataVal2[$reportUmdNumn + $i]);
                        $tmpDataColumnNum++;
                    }
                    
                    for ($i = 0; $i < count($tmpDataList2); $i++)
                    {
                        array_push($tmpDataList, $tmpDataList2[$i]);
                        array_push($tmpDataValList, $tmpDataValList2[$i]);
                        array_push($tmpDataList_2, $tmpDataList2_2[$i]);
                        array_push($tmpDataValList_2, $tmpDataValList2_2[$i]);
                    }
                    // compile time
                    for ($i = 0; $i < $tmpDataColumnNum; $i++)
                    {
                        $rcID1 = ($subjectNameFilterNumMax + 5 + $i * 3);
                        $rcID2 = ($subjectNameFilterNumMax + 3 + $i * 3);
                               
                        $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                               "" . $tmpDataList[$i] . "</Cell>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                               "ss:Formula=\"=IF(OR(RC" . $rcID1 . "=&quot;&quot;," .
                               "RC" . $rcID2 . "=&quot;&quot;," .
                               "RC" . $rcID1 . "=0," .
                               "RC" . $rcID2 . "=0" .
                               "),&quot;&quot;," .
                               "(RC" . $rcID1 . // 8
                               "-RC" . $rcID2 . // 6
                               ")/RC" . $rcID2 . ")\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                               "" . $tmpDataList[$tmpDataColumnNum + $i] . "</Cell>\n";
                    }
                    // execution time
                    for ($i = 0; $i < $tmpDataColumnNum; $i++)
                    {
                        $rcID1 = ($subjectNameFilterNumMax + 5 + $i * 3);
                        $rcID2 = ($subjectNameFilterNumMax + 3 + $i * 3);
                               
                        $t3 .= " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                               "" . $tmpDataList_2[$i] . "</Cell>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 5) . "\" " .
                               "ss:Formula=\"=IF(OR(RC" . $rcID1 . "=&quot;&quot;," .
                               "RC" . $rcID2 . "=&quot;&quot;," .
                               "RC" . $rcID1 . "=0," .
                               "RC" . $rcID2 . "=0" .
                               "),&quot;&quot;," .
                               "(RC" . $rcID1 . // 8
                               "-RC" . $rcID2 . // 6
                               ")/RC" . $rcID2 . ")\"><Data ss:Type=\"Number\"></Data></Cell>\n" .
                               " <Cell ss:StyleID=\"s" . ($startStyleID + 4) . "\">" .
                               "" . $tmpDataList_2[$tmpDataColumnNum + $i] . "</Cell>\n";
                    }
                    
                    // compile time
                    $rateVal = array_fill(0, $reportUmdNumn, -1);
                    $summaryDataVal = array_fill(0, $reportUmdNumn * 2, -1);
                    $cmpPartName = array_fill(0, $reportUmdNumn * 2, "");
                    
                    // for summary sheet2, cur & last batch
                    $rateVal3 = array_fill(0, $reportUmdNumn, -1);
                    $summaryDataVal2 = array_fill(0, $reportUmdNumn * 2, -1);
                    $cmpPartName2 = array_fill(0, $reportUmdNumn * 2, "");
                    // execution time
                    $rateVal5 = array_fill(0, $reportUmdNumn, -1);
                    $summaryDataVal5 = array_fill(0, $reportUmdNumn * 2, -1);
                    $cmpPartName5 = array_fill(0, $reportUmdNumn * 2, "");
                    
                    $rateVal6 = array_fill(0, $reportUmdNumn, -1);
                    $summaryDataVal6 = array_fill(0, $reportUmdNumn * 2, -1);
                    $cmpPartName6 = array_fill(0, $reportUmdNumn * 2, "");
                    
                    for ($i = 0; $i < $reportUmdNumn; $i++)
                    {
                        // compile time
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
                        
                        // execution time
                        if (($tmpDataVal2[$i] > 0) &&
                            ($tmpDataVal2[$reportUmdNumn + $i] > 0))
                        {
                            $rateVal5[$i] = ($tmpDataVal2[$reportUmdNumn + $i] - $tmpDataVal2[$i]) / $tmpDataVal2[$i];
                        }
                        
                        $j = $i * 2;
                        $summaryDataVal5[$j] = $tmpDataVal2[$i];
                        $summaryDataVal5[$j + 1] = $tmpDataVal2[$reportUmdNumn + $i];
                        
                        $cmpPartName5[$j] = $curCardName;
                        $cmpPartName5[$j + 1] = $cmpCardName;
                    }
                }
                else
                {
                    // no comparison card
                    //$rateVal = array(-1, -1, -1);
                    // compile time
                    $rateVal = array_fill(0, $reportUmdNumn, -1);
                    $summaryDataVal = array_fill(0, $reportUmdNumn * 2, -1);
                    $cmpPartName = array_fill(0, $reportUmdNumn * 2, "");
                    
                    // for summary sheet2, cur & last batch
                    $rateVal3 = array_fill(0, $reportUmdNumn, -1);
                    $summaryDataVal2 = array_fill(0, $reportUmdNumn * 2, -1);
                    $cmpPartName2 = array_fill(0, $reportUmdNumn * 2, "");
                    // execution time
                    $rateVal5 = array_fill(0, $reportUmdNumn, -1);
                    $summaryDataVal5 = array_fill(0, $reportUmdNumn * 2, -1);
                    $cmpPartName5 = array_fill(0, $reportUmdNumn * 2, "");
                    $rateVal6 = array_fill(0, $reportUmdNumn, -1);
                    $summaryDataVal6 = array_fill(0, $reportUmdNumn * 2, -1);
                    $cmpPartName6 = array_fill(0, $reportUmdNumn * 2, "");
                    
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
                    
                    for ($i = 0; $i < count($tmpIndexList); $i++)
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
                            //$tmpIndex = 0;
                            //$tmpIndex2 = $tmpIndexList[$i];
                            
                            // for SCPC vs NVIDIA
                            $tmpIndex2 = 0;
                            $tmpIndex = $tmpIndexList[$i];
                        }
                        // compile time
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
                        
                        // execution time
                        if (($tmpDataVal2[$tmpIndex] > 0) &&
                            ($tmpDataVal2[$tmpIndex2] > 0))
                        {
                            $rateVal5[$tmpIndexList[$i]] = ($tmpDataVal2[$tmpIndex2] - $tmpDataVal2[$tmpIndex]) / $tmpDataVal2[$tmpIndex];
                        }
                        
                        $j = $tmpIndexList[$i] * 2;
                        $summaryDataVal5[$j] = $tmpDataVal2[$tmpIndex];
                        $summaryDataVal5[$j + 1] = $tmpDataVal2[$tmpIndex2];
                        
                        $cmpPartName5[$j] = $umdNameList[$tmpIndex];
                        $cmpPartName5[$j + 1] = $umdNameList[$tmpIndex2];
                    }

                }
                // for summary sheet2, cur & last batch
                for ($i = 0; $i < $reportUmdNumn; $i++)
                {
                    // compile time
                    if (($tmpDataVal[$i] > 0) &&
                        ($tmpDataVal[$reportUmdNumn * 2 + $i] > 0))
                    {
                        $rateVal3[$i] = ($tmpDataVal[$i] - $tmpDataVal[$reportUmdNumn * 2 + $i]) / $tmpDataVal[$reportUmdNumn * 2 + $i];
                    }
                    
                    $j = $i * 2;
                    $summaryDataVal2[$j] = $tmpDataVal[$reportUmdNumn * 2 + $i];
                    $summaryDataVal2[$j + 1] = $tmpDataVal[$i];
                    
                    $cmpPartName2[$j] = count($batchIDList) > 1 ? $batchIDList[1] : $batchIDList[0];
                    $cmpPartName2[$j + 1] = $batchIDList[0];
                    
                    // execution time
                    if (($tmpDataVal2[$i] > 0) &&
                        ($tmpDataVal2[$reportUmdNumn * 2 + $i] > 0))
                    {
                        $rateVal6[$i] = ($tmpDataVal2[$i] - $tmpDataVal2[$reportUmdNumn * 2 + $i]) / $tmpDataVal2[$reportUmdNumn * 2 + $i];
                    }
                    
                    $j = $i * 2;
                    $summaryDataVal6[$j] = $tmpDataVal2[$reportUmdNumn * 2 + $i];
                    $summaryDataVal6[$j + 1] = $tmpDataVal2[$i];
                    
                    $cmpPartName6[$j] = count($batchIDList) > 1 ? $batchIDList[1] : $batchIDList[0];
                    $cmpPartName6[$j + 1] = $batchIDList[0];
                }
                // compile time
                $summaryJson = $this->writeSummaryJsonPerTest($summaryJson,
                                                              $variationJson,
                                                              $rateVal,
                                                              $summaryDataVal,
                                                              $cmpPartName,
                                                              $n1);
                
                $summaryJson2 = $this->writeSummaryJsonPerTest($summaryJson2,
                                                               $variationJson,
                                                               $rateVal3,
                                                               $summaryDataVal2,
                                                               $cmpPartName2,
                                                               $n1);
                                                               
                //$tmpArr = array();
                //$tmpArr["rateVal"] = $rateVal;
                //$tmpArr["summaryDataVal"] = $summaryDataVal;
                //$tmpArr["cmpPartName"] = $cmpPartName;
                //$tmpArr["rateVal3"] = $rateVal3;
                //$tmpArr["summaryDataVal2"] = $summaryDataVal2;
                //$tmpArr["cmpPartName2"] = $cmpPartName2;
                //$tmpArr["tmpDataVal"] = $tmpDataVal;
                //$tmpArr["tmpDataVal2"] = $tmpDataVal2;
                //$tmpArr["umdDataVal"] = $umdDataVal;
                //$tmpArr["umdDataVal2"] = $umdDataVal2;
                //$tmpArr["row1"] = $row1;
                //$tmpArr["dataIndexList2"] = $dataIndexList2;
                //$tmpArr["sql1"] = $sql1;
                //$tmpArr["params1"] = $params1;
                //$tmpArr["_startResultID"] = $_startResultID;
                //$tmpArr["_historyStartResultID"] = $_historyStartResultID;
                //$tmpArr["resultIDList"] = $resultIDList;
                //
                //$tx1 = json_encode($tmpArr);
                //file_put_contents("H:/wamp64/www/benchMax/test01.json", $tx1);
                                                               
                                                               
                // execution time
                $summaryJson3 = $this->writeSummaryJsonPerTest($summaryJson3,
                                                              $variationJson,
                                                              $rateVal5,
                                                              $summaryDataVal5,
                                                              $cmpPartName5,
                                                              $n1);
                                                              
                $summaryJson4 = $this->writeSummaryJsonPerTest($summaryJson4,
                                                              $variationJson,
                                                              $rateVal6,
                                                              $summaryDataVal6,
                                                              $cmpPartName6,
                                                              $n1);
                
                
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
            
            //fseek($tempFileHandle, $_tempFileLineNumPos, SEEK_SET);
            //// line num is 10 digis number, like: 0000000011
            //$t1 = fread($tempFileHandle, 10);
            //$n1 = intval($t1);
            //$n1 += $_tempLineNum;
            ////$sheetLinePos = $n1;
            //fseek($tempFileHandle, $_tempFileLineNumPos, SEEK_SET);
            //$t1 = sprintf("%010d", $n1);
            ////$t1 = "1234567890";
            //fwrite($tempFileHandle, $t1);
            
            //if ($_cmpStartResultID != -1)
            {
                // write summary sheet json
                $t1 = json_encode($summaryJson);
                file_put_contents($jsonFileName, $t1);
                
                $t1 = json_encode($summaryJson2);
                file_put_contents($jsonFileName2, $t1);
                
                $t1 = json_encode($summaryJson3);
                file_put_contents($jsonFileName3, $t1);
                
                $t1 = json_encode($summaryJson4);
                file_put_contents($jsonFileName4, $t1);
            }
        }

        $returnSet = array();
        $returnSet["sheetLinePos"] = $sheetLinePos;
        return $returnSet;
    }
}


?>