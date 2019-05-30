<?php

include_once "../generalLibs/dopdo.php";
include_once "../generalLibs/dodb.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "../userManage/swtUserManager.php";


class CGenTemplteReport
{
	public $macroList = array();
    public $userTemplate = array();
    public $styleList = array();
    
    public $templateHeaderName = "";
    public $templateBodyName = "";
    public $templateRowCellNum = 0;
    public $templateGraphSourceList = array();
    public $templateReportType = "";

	public function __construct()
	{
        //global $db_dbname;

        //$this->dbResult = null;
	}
	public function __destruct()
	{
        //$this->clearResult();
    }
    
    public function updateTemplateMacro($_key, $_value)
    {
        $this->macroList["" . $_key] = $_value;
    }
    
    public function preParseTemplate($_curTemplatePath)
    {
        global $returnMsg;
        
        $t1 = file_get_contents($_curTemplatePath);
        $tmpTemplate = json_decode($t1, true);
        
        if ($tmpTemplate == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "template format invalid, line: " . __LINE__;
            echo json_encode($returnMsg);
            return null;
        }
        
        $tmpMacroList = array();
        
        if (isset($tmpTemplate["ReportType"]))
        {
            //if ($tmpTemplate["ReportType"] != "ShaderBench")
            //{
            //    $returnMsg["errorCode"] = 0;
            //    $returnMsg["errorMsg"] = "template key value not right, line: " . __LINE__;
            //    echo json_encode($returnMsg);
            //    return null;
            //}
            $this->templateReportType = $tmpTemplate["ReportType"];
        }
        else
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "template key not defined, line: " . __LINE__;
            echo json_encode($returnMsg);
            return null;
        }
        
        // #APINameList
        if (isset($tmpTemplate["Macro"]["APINameList"]))
        {
            $tmpMacroList["APINameList"] = $tmpTemplate["Macro"]["APINameList"];
        }
        else
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "template key not defined, line: " . __LINE__;
            echo json_encode($returnMsg);
            return null;
        }
        
        // #AsicNameList, #AsicNum
        if (isset($tmpTemplate["Macro"]["AsicNameList"]))
        {
            $tmpMacroList["AsicNameList"] = $tmpTemplate["Macro"]["AsicNameList"];
            $tmpMacroList["AsicNum"] = count($tmpTemplate["Macro"]["AsicNameList"]);
        }
        else
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "template key not defined, line: " . __LINE__;
            echo json_encode($returnMsg);
            return null;
        }
        
        // #CompilerNameList
        // ShaderBench
        if (isset($tmpTemplate["Macro"]["CompilerNameList"]))
        {
            $tmpMacroList["CompilerNameList"] = $tmpTemplate["Macro"]["CompilerNameList"];
        }
        
        // #AsicSystemNameList
        // FrameBench
        if (isset($tmpTemplate["Macro"]["AsicSystemNameList"]))
        {
            $tmpMacroList["AsicSystemNameList"] = $tmpTemplate["Macro"]["AsicSystemNameList"];
        }
        
        $tmpTemplateCellStyleList = array();
        foreach ($tmpTemplate["Macro"] as $key => $val)
        {
            if (isset($tmpTemplate["Macro"][$key]["BGColor"]) &&
                isset($tmpTemplate["Macro"][$key]["FontColor"]))
            {
                // is cell style
                
                $tmpTemplateCellStyleList[$key] = array();
                $tmpTemplateCellStyleList[$key]["BGColor"] = $tmpTemplate["Macro"][$key]["BGColor"];
                $tmpTemplateCellStyleList[$key]["FontColor"] = $tmpTemplate["Macro"][$key]["FontColor"];
                $tmpTemplateCellStyleList[$key]["styleID"] = 0;
                if (isset($tmpTemplate["Macro"][$key]["Bold"]))
                {
                    
                    $tmpTemplateCellStyleList[$key]["Bold"] = 1;
                }
                if (isset($tmpTemplate["Macro"][$key]["NumberFormat"]))
                {
                    $tmpTemplateCellStyleList[$key]["NumberFormat"] = $tmpTemplate["Macro"][$key]["NumberFormat"];
                }
                if (isset($tmpTemplate["Macro"][$key]["HorizonAlign"]))
                {
                    
                    $tmpTemplateCellStyleList[$key]["HorizonAlign"] = $tmpTemplate["Macro"][$key]["HorizonAlign"];
                }
                if (isset($tmpTemplate["Macro"][$key]["WrapText"]))
                {
                    
                    $tmpTemplateCellStyleList[$key]["WrapText"] = 1;
                }
            }
        }
        
        $this->macroList = $tmpMacroList;
        $this->userTemplate = $tmpTemplate;
        $this->styleList = $tmpTemplateCellStyleList;
        
        $returnSet = array();
        //$returnSet["templateMacroList"] = $tmpMacroList;
        //$returnSet["userDefinedReportTemplate"] = $tmpTemplate;
        //$returnSet["templateCellStyleList"] = $tmpTemplateCellStyleList;
        return $returnSet;
    }
    
	public function getStyleIDList()
	{
        global $startStyleID;
        global $appendStyleList;
        
        $t3 = "";
        $n1 = $startStyleID;
        foreach ($appendStyleList as $tmpStyle)
        {
            $n1++;
        }

        foreach($this->styleList as $key => $val)
        {
            $this->styleList[$key]["styleID"] = $n1;
            $n1++;
            $n1++;
        }
    }
    
    public function loadTemplateMacroList()
    {
        global $returnMsg;
        global $tmpSysName;
        global $testName;
        global $swtReportInfo;
        global $swtReportInfoPre;
        global $unitNameList;
        global $curTestPos;
        global $tmpUmd2Name;
        //global $xmlWriterTemplate;
        
        // #APIName
        $this->macroList["APIName"] = $tmpUmd2Name;
        
        // #SysName
        $this->macroList["SysName"] = $tmpSysName;
        
        // #TestName
        $this->macroList["TestName"] = $testName;
        $tmpArr = explode("_", $testName);
        if (count($tmpArr) > 1)
        {
            $this->macroList["TestName"] = ucwords($tmpArr[1]);
        }
        
        // #ChangeList, #ChangeList_Prev
        $this->macroList["ChangeList"] = $swtReportInfo;
        $this->macroList["ChangeList_Prev"] = $swtReportInfoPre;
        
        // #CellValue
        $this->macroList["CellValue"] = false;
        
        // #ColumnID
        $this->macroList["ColumnID"] = false;
        
        // #MachineResult_CompileTime,
        // #MachineResult_CompileTime_Prev
        // #MachineResult_ExecutionTime
        // #MachineResult_ExecutionTime_Prev
        $this->macroList["MachineResult_CompileTime"] = false;
        $this->macroList["MachineResult_CompileTime_Prev"] = false;
        $this->macroList["MachineResult_RecordTime"] = false;
        $this->macroList["MachineResult_RecordTime_Prev"] = false;
        $this->macroList["MachineResult_ExecutionTime"] = false;
        $this->macroList["MachineResult_ExecutionTime_Prev"] = false;
        
        // #RenderQuality
        $this->macroList["RenderQuality"] = false;
        
        // #ResultUnit_ExecutionTime
        // #ResultUnit_CompileTime
        // #ResultUnit_RecordTime
        $tmpList = explode("|", $unitNameList[$curTestPos]);
        $this->macroList["ResultUnit_ExecutionTime"] = $tmpList[1];
        $this->macroList["ResultUnit_CompileTime"] = $tmpList[0];
        $this->macroList["ResultUnit_RecordTime"] = $tmpList[0];
        
        if ($this->templateReportType == "FrameBench")
        {
            $this->macroList["ResultUnit_ExecutionTime"] = $tmpList[0];
            $this->macroList["ResultUnit_CompileTime"] = $tmpList[1];
            $this->macroList["ResultUnit_RecordTime"] = $tmpList[1];
        }
        
        // #TestCaseID
        $this->macroList["TestCaseID"] = 0;
        
        // #TestCaseParam
        $this->macroList["TestCaseParam"] = "";
        
        // #TestCaseSelfID
        $this->macroList["TestCaseSelfID"] = 0;
        
        $tmpTemplateHeaderName = "";
        $tmpTemplateBodyName = "";
        
        if (isset($this->userTemplate["SheetList"]))
        {
            for ($i = 0; $i < count($this->userTemplate["SheetList"]); $i++)
            {
                if ($this->userTemplate["SheetList"][$i]["SheetType"] == "Customized")
                {
                    $t1 = $this->getTemplateText($this->userTemplate["SheetList"][$i]["SheetName"]);
                    
                    if ($t1 == $tmpUmd2Name)
                    {
                        // current template
                        $tmpTemplateHeaderName = $this->userTemplate["SheetList"][$i]["HeaderStyle"];
                        $tmpTemplateBodyName = $this->userTemplate["SheetList"][$i]["BodyStyle"];
                        
                        break;
                    }
                }
            }
        }
        else
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "template key not defined, line: " . __LINE__;
            echo json_encode($returnMsg);
            return null;
        }
        
        $tmpRowCellNum = 0;
        if (strlen($tmpTemplateHeaderName) > 0)
        {
            // calculate cell number of each row
            if (isset($this->userTemplate[$tmpTemplateHeaderName]))
            {
                for ($i = 0; $i < count($this->userTemplate[$tmpTemplateHeaderName]["Row0"]); $i++)
                {
                    if (isset($this->userTemplate[$tmpTemplateHeaderName]["Row0"][$i]["CellNum"]))
                    {
                        $tmpRowCellNum += intval("" . $this->getTemplateValue($this->userTemplate[$tmpTemplateHeaderName]["Row0"][$i]["CellNum"]));
                    }
                    else
                    {
                        $tmpRowCellNum++;
                    }
                }
            }
        }
        
        $tmpRowCellIndex = 0;
        $tmpGraphSourceList = array();
        if (strlen($tmpTemplateBodyName) > 0)
        {
            // calculate cell number of each row
            if (isset($this->userTemplate[$tmpTemplateBodyName]))
            {
                for ($i = 0; $i < count($this->userTemplate[$tmpTemplateBodyName]["Row0"]); $i++)
                {
                    if (isset($this->userTemplate[$tmpTemplateBodyName]["Row0"][$i]["CellNum"]))
                    {
                        $n1 = intval("" . $this->getTemplateValue($this->userTemplate[$tmpTemplateBodyName]["Row0"][$i]["CellNum"]));
                        
                        if (isset($this->userTemplate[$tmpTemplateBodyName]["Row0"][$i]["Columns"]))
                        {
                            // multiple columns
                            $tmpIndex = 0;
                            $tmpList = array();
                            foreach ($this->userTemplate[$tmpTemplateBodyName]["Row0"][$i]["Columns"] as $tmpSubCell)
                            {
                                if (isset($tmpSubCell["GraphSource"]))
                                {
                                    $tmpList []= $tmpRowCellIndex + $tmpIndex;
                                }
                                $tmpIndex++;
                            }
                            if (count($tmpList) > 0)
                            {
                                $tmpGraphSourceList []= $tmpList;
                            }
                        }
                        
                        $tmpRowCellIndex += $n1;
                    }
                    else
                    {
                        $tmpRowCellIndex++;
                    }
                }
            }
        }
        
        $this->templateHeaderName = $tmpTemplateHeaderName;
        $this->templateBodyName = $tmpTemplateBodyName;
        $this->templateRowCellNum = $tmpRowCellNum;
        $this->templateGraphSourceList = $tmpGraphSourceList;
        
        $returnSet = array();
        return $returnSet;
    }
    
    public function getTemplateText($_data)
    {
        // donot calculate, just link text
        // like "#AsicNameList[0] [Current] vs #AsicNameList[0] [Previous]"
        // for calculate, use getTemplateValue
        global $returnMsg;
        //global $templateMacroList;
        global $templateMacroTag;
        
        $tmpPosList = array();
        
        $n1 = 0;
        while (($n1 = strpos($_data, $templateMacroTag, $n1)) !== false)
        {
            $tmpPosList []= $n1;
            $n1++;
        }
        
        $t2 = "";
        $n2 = 0;
        $macroKey = "";
        
        for ($i = 0; $i < count($tmpPosList); $i++)
        {
            $macroKey = "";
            foreach ($this->macroList as $key => $val)
            {
                $keyLen = strlen($key);
                $t1 = substr($_data, $tmpPosList[$i] + 1, $keyLen);
                
                if (($key              == $t1) &&
                    (strlen($macroKey) <  $keyLen))
                {
                    // macro used in data
                    $macroKey = $key;
                }
            }
            
            if (strlen($macroKey) > 0)
            {
                // longest matched macro
                $n3 = $tmpPosList[$i] + strlen($macroKey) + 1;
                $tmpPos1 = strpos($_data, "[", $n3);
                $tmpPos2 = strpos($_data, "]", $n3);
                
                $tmpArrayIndex = "";
                
                if (($tmpPos1 !== false) &&
                    ($tmpPos2 !== false) &&
                    ($tmpPos1 ==  $n3) &&
                    ($tmpPos2 >   $tmpPos1))
                {
                    $t1 = substr($_data, $tmpPos1 + 1, $tmpPos2 - $tmpPos1 - 1);
                    if (is_numeric($t1))
                    {
                        $tmpArrayIndex = $t1;
                    }
                }
                
                $t2 .= substr($_data, $n2, $tmpPosList[$i] - $n2);
                if (strlen($tmpArrayIndex) > 0)
                {
                    // array
                    
                    if (isset($this->macroList[$macroKey][intval($tmpArrayIndex)]) == false)
                    {
                        $returnMsg["mismacroKey"] = $macroKey;
                        $returnMsg["mismacroArrayIndex"] = $tmpArrayIndex;
                        
                        $t2 .= "";
                    }
                    else
                    {
                        $t2 .= $this->macroList[$macroKey][intval($tmpArrayIndex)];
                    }
                    
                    $n2 = $tmpPos2 + 1;
                }
                else
                {
                    $t2 .= $this->macroList[$macroKey];
                    $n2 = $tmpPosList[$i] + strlen($macroKey) + 1;
                }
            }
        }
        
        $t1 = substr($_data, $n2);
        $t2 .= $t1;
        
        return $t2;
    }
    
    public function getTemplateValue($_data)
    {
        // calculate expression
        // like "(#AsicNum + 1) * 2 - 1"
        global $returnMsg;
        //global $templateMacroList;
        global $templateMacroTag;
        
        $tmpPosList = array();
        
        $n1 = 0;
        while (($n1 = strpos($_data, $templateMacroTag, $n1)) !== false)
        {
            $tmpPosList []= $n1;
            $n1++;
        }
        
        $t2 = "";
        $n2 = 0;
        $macroKey = "";
        
        for ($i = 0; $i < count($tmpPosList); $i++)
        {
            $macroKey = "";
            foreach ($this->macroList as $key => $val)
            {
                $keyLen = strlen($key);
                $t1 = substr($_data, $tmpPosList[$i] + 1, $keyLen);
                
                if (($key              == $t1) &&
                    (strlen($macroKey) <  $keyLen))
                {
                    // macro used in data
                    $macroKey = $key;
                }
            }
            
            if (strlen($macroKey) > 0)
            {
                // longest matched macro
                $n3 = $tmpPosList[$i] + strlen($macroKey) + 1;
                $tmpPos1 = strpos($_data, "[", $n3);
                $tmpPos2 = strpos($_data, "]", $n3);
                
                $tmpArrayIndex = "";
                
                if (($tmpPos1 !== false) &&
                    ($tmpPos2 !== false) &&
                    ($tmpPos1 ==  $n3) &&
                    ($tmpPos2 >   $tmpPos1))
                {
                    $t1 = substr($_data, $tmpPos1 + 1, $tmpPos2 - $tmpPos1 - 1);
                    if (is_numeric($t1))
                    {
                        $tmpArrayIndex = $t1;
                    }
                }
                
                $t2 .= substr($_data, $n2, $tmpPosList[$i] - $n2);
                
                $t2 .= "\$this->macroList[\"" . $macroKey . "\"]";
                $n2 = $tmpPosList[$i] + strlen($macroKey) + 1;
            }
        }
        
        $t1 = substr($_data, $n2);
        $t2 .= $t1;
        
        $t1 = eval("return " . $t2 . ";");
        
        return $t1;
    }
    
    public function getTemplateIfExp($_data)
    {
        // get value of "if" expression
        // like "#CellValue <= -0.03: 'red'; (#CellValue > 0.03 && #CellValue < 1000): 'lime'"
        // like "#TestCaseSelfID == 0: 'DARKRED_BACKGROUND_WHITE_FONT'; #TestCaseSelfID > 0: 'DARKRED_BACKGROUND_DARKRED_FONT'"
        global $returnMsg;
        //global $templateMacroList;
        global $templateMacroTag;
        global $templateExpSepTag;
        global $templateExpSetTag;
        
        // check if is "if" expression
        
        $tmpList = explode($templateExpSepTag, $_data);
        $tmpList2 = array();
        
        $b1 = true;
        for ($i = 0; $i < count($tmpList); $i++)
        {
            $tmpPos = strpos($tmpList[$i], $templateExpSetTag);
            
            if ($tmpPos === false)
            {
                $b1 = false;
                break;
            }
            $tmpList2 []= $tmpPos;
        }
        
        if ($b1 == false)
        {
            // not "if" expression
            return $_data;
        }
        
        // is "if" expression
        for ($i = 0; $i < count($tmpList); $i++)
        {
            $t1 = substr($tmpList[$i], 0, $tmpList2[$i]);
            if ($this->getTemplateValue($t1) == true)
            {
                // "if" is true
                $t1 = substr($tmpList[$i], $tmpList2[$i] + 1);
                $tmpPos1 = strpos($tmpList[$i], "'", $tmpList2[$i] + 1);
                $tmpPos2 = strrpos($tmpList[$i], "'");
                
                if (($tmpPos1 !== false) &&
                    ($tmpPos2 !== false) &&
                    ($tmpPos2 >   $tmpPos1))
                {
                    $t1 = substr($tmpList[$i], $tmpPos1 + 1, $tmpPos2 - $tmpPos1 - 1);
                    return $t1;
                }
            }
        }
        
        return $_data;
    }
    
    public function getCellStyleID($_styleName, $_isTestStart = false)
    {
        global $returnMsg;
        // get cell styleID
        
        $t1 = "Default";
        
        if (array_key_exists($_styleName, $this->styleList) == false)
        {
            return $t1;
        }
        
        $n1 = $this->styleList[$_styleName]["styleID"];
        
        //if ($n1 == 0)
        {
            $returnMsg["misStyleID"] = $_styleName;
            $returnMsg["misStyleID2"] = $n1;
            $returnMsg["styleList"] = $this->styleList;
        }
        
        if ($_isTestStart)
        {
            return "s" . ($n1 + 1);
        }
        return "s" . $n1;
    }
    
    public function getMainPageXMLCodeHeader()
    {
        global $returnMsg;
        global $startStyleID;
        
        $tmpXML = "";
        
        if (isset($this->userTemplate[$this->templateHeaderName]) == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "template not found, line: " . __LINE__;
            echo json_encode($returnMsg);
            return null;
        }
        
        $tmpAsicNameLenMax = 9;
        for ($i = 0; $i < count($this->macroList["AsicNameList"]); $i++)
        {
            $n1 = strlen($this->macroList["AsicNameList"][$i]);
            $tmpAsicNameLenMax = max($tmpAsicNameLenMax, $n1);
        }
        $tmpResultColumnWidth = $tmpAsicNameLenMax * 6;
        
        $defaultColumnWidthList = array(60, 60, 400);
        $t1 = "";
        $curAPIName = $this->macroList["APIName"];
        $tmpXMLHead = "<Worksheet ss:Name=\"" . $curAPIName . "\">\n" .
                      "<Table x:FullColumns=\"1\" x:FullRows=\"1\" ss:StyleID=\"Default\" ss:DefaultRowHeight=\"15\">\n" .
                      "<Column ss:StyleID=\"Default\" ss:AutoFitWidth=\"0\" ss:Width=\"60\"/>\n" .
                      "<Column ss:StyleID=\"Default\" ss:AutoFitWidth=\"0\" ss:Width=\"60\"/>\n" .
                      "<Column ss:StyleID=\"Default\" ss:AutoFitWidth=\"0\" ss:Width=\"400\"/>\n";
        $isFirstRow = true;
        foreach($this->userTemplate[$this->templateHeaderName] as $key => $tmpRow)
        {
            // generate rows
            $tmpCellIndex = 0;
            $tmpRowHeightMax = 15;
            
            $t2 = "";
            foreach ($tmpRow as $tmpCell)
            {
                if (isset($tmpCell["Splitter"]))
                {
                    // black splitter
                    $t2 .= "<Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n";
                    
                    if ($isFirstRow && ($tmpCellIndex >= 3))
                    {
                        $tmpXMLHead .= "<Column ss:AutoFitWidth=\"0\" ss:Width=\"3\"/>\n";
                    }
                    
                    $tmpCellIndex++;
                    continue;
                }
                $tmpCellNum = 1;
                if (is_numeric($tmpCell["CellNum"]))
                {
                    $tmpCellNum = intval($tmpCell["CellNum"]);
                }
                else
                {
                    // is expression
                    $tmpCellNum = $this->getTemplateValue($tmpCell["CellNum"]);
                }
                
                if ($isFirstRow && ($tmpCellIndex >= 3))
                {
                    for ($i = 0; $i < $tmpCellNum; $i++)
                    {
                        $tmpXMLHead .= "<Column ss:AutoFitWidth=\"0\" ss:Width=\"" . $tmpResultColumnWidth . "\"/>\n";
                    }
                }
                
                $isMerged = isset($tmpCell["Merged"]) && ($tmpCell["Merged"] == 1);
                
                $tmpStyleID = "Default";
                if (isset($tmpCell["DefaultColoring"]))
                {
                    $tmpStyleID = $this->getCellStyleID($tmpCell["DefaultColoring"]);
                }
                
                if (isset($tmpCell["Data"]))
                {
                    // single cell
                    $tmpData = $this->getTemplateText($tmpCell["Data"]);
                    
                    $tmpList = explode("&#10;", $tmpData);
                    $n1 = count($tmpList) * 20;
                    $tmpRowHeightMax = max($tmpRowHeightMax, $n1);
                    
                    if ($isMerged && ($tmpCellNum > 1))
                    {
                        $t2 .= "<Cell ss:StyleID=\"" . $tmpStyleID . "\" ss:MergeAcross=\"" . ($tmpCellNum - 1) . "\" >" .
                               "<Data ss:Type=\"String\">" . $tmpData . "</Data></Cell>\n";
                    }
                    else
                    {
                        $t2 .= "<Cell ss:StyleID=\"" . $tmpStyleID . "\">" .
                               "<Data ss:Type=\"String\">" . $tmpData . "</Data></Cell>\n";
                    }
                }
                else if (isset($tmpCell["Columns"]))
                {
                    // multiple cells
                    foreach ($tmpCell["Columns"] as $tmpSubCell)
                    {
                        $tmpData = $this->getTemplateText($tmpSubCell["Data"]);
                        
                        $tmpList = explode("&#10;", $tmpData);
                        $n1 = count($tmpList) * 20;
                        $tmpRowHeightMax = max($tmpRowHeightMax, $n1);
                        
                        $tmpSubStyleID = $tmpStyleID;
                        if (isset($tmpSubCell["DefaultColoring"]))
                        {
                            $tmpSubStyleID = $this->getCellStyleID($tmpSubCell["DefaultColoring"]);
                        }
                        
                        $t2 .= "<Cell ss:StyleID=\"" . $tmpSubStyleID . "\">" .
                               "<Data ss:Type=\"String\">" . $tmpData . "</Data></Cell>\n";
                    }
                }
                
                $tmpCellIndex += $tmpCellNum;
            }
            
            $tmpRowHeightMax = $tmpRowHeightMax <= 20 ? 15 : $tmpRowHeightMax;
            
            $t1 .= "<Row ss:AutoFitHeight=\"0\" ss:Height=\"" . $tmpRowHeightMax . "\" ss:StyleID=\"Default\">\n";
            $t1 .= $t2;
            $t1 .= "</Row>\n";
            $isFirstRow = false;
        }
        
        $tmpXML .= $tmpXMLHead;
        $tmpXML .= $t1;
        
        return $tmpXML;
    }
    
    public function loadTemplateMacroListRest($_tmpMacroList)
    {
        global $returnMsg;
        
        foreach ($_tmpMacroList as $key => $val)
        {
            $this->updateTemplateMacro($key, $val);
        }
    }
    
    public function getMainPageXMLCodeBody()
    {
        global $returnMsg;
        global $startStyleID;
        global $swtSheetColumnIDList;
        
        $tmpXML = "";
        
        if (isset($this->userTemplate[$this->templateBodyName]) == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "template not found, line: " . __LINE__;
            echo json_encode($returnMsg);
            return null;
        }
        

        foreach($this->userTemplate[$this->templateBodyName] as $key => $tmpRow)
        {
            // generate rows
            $tmpCellIndex = 0;
            
            $t2 = "";
            foreach ($tmpRow as $tmpCell)
            {
                if (isset($tmpCell["Splitter"]))
                {
                    // black splitter
                    $t2 .= "<Cell ss:StyleID=\"s" . ($startStyleID + 0) . "\"/>\n";
                    
                    $tmpCellIndex++;
                    continue;
                }
                $tmpCellNum = 1;
                if (is_numeric($tmpCell["CellNum"]))
                {
                    $tmpCellNum = intval($tmpCell["CellNum"]);
                }
                else
                {
                    // is expression
                    $tmpCellNum = $this->getTemplateValue($tmpCell["CellNum"]);
                }
                
                $isMerged = isset($tmpCell["Merged"]) && ($tmpCell["Merged"] == 1);
                
                $tmpStyleID = "Default";
                if (isset($tmpCell["DefaultColoring"]))
                {
                    $tmpName = $this->getTemplateIfExp($tmpCell["DefaultColoring"]);
                    $isTestStart = $this->macroList["TestCaseSelfID"] == 0;
                    $tmpStyleID = $this->getCellStyleID($tmpName, $isTestStart);
                    
                    $returnMsg["tmpStyleID"] = $tmpStyleID;
                    $returnMsg["tmpStyleName"] = $tmpName;
                }
                
                if (isset($tmpCell["Data"]))
                {
                    // single cell
                    $tmpData = $this->getTemplateText($tmpCell["Data"]);
                    
                    if ($isMerged && ($tmpCellNum > 1))
                    {
                        $t2 .= "<Cell ss:StyleID=\"" . $tmpStyleID . "\" ss:MergeAcross=\"" . ($tmpCellNum - 1) . "\" >" .
                               "<Data ss:Type=\"String\">" . $tmpData . "</Data></Cell>\n";
                    }
                    else
                    {
                        $t2 .= "<Cell ss:StyleID=\"" . $tmpStyleID . "\">" .
                               "<Data ss:Type=\"String\">" . $tmpData . "</Data></Cell>\n";
                    }
                }
                else if (isset($tmpCell["Columns"]))
                {
                    // multiple cells
                    
                    $tmpMacroList = array();
                    $tmpMacroList["ColumnID"] = array();
                    
                    for ($i = 0; $i < $tmpCellNum; $i++)
                    {
                        $tmpMacroList["ColumnID"] []= $tmpCellIndex + $i + 1;
                    }
                    
                    $this->loadTemplateMacroListRest($tmpMacroList);
                    
                    foreach ($tmpCell["Columns"] as $tmpSubCell)
                    {
                        $isFormula = isset($tmpSubCell["IsFormula"]);
                        $tmpData = "";
                        
                        $tmpSubStyleID = $tmpStyleID;
                        if (isset($tmpSubCell["DefaultColoring"]))
                        {
                            $isTestStart = $this->macroList["TestCaseSelfID"] == 0;
                            $tmpSubStyleID = $this->getCellStyleID($tmpSubCell["DefaultColoring"], $isTestStart);
                            
                            $returnMsg["tmpSubStyleID"] = $tmpSubStyleID;
                            $returnMsg["tmpStyleName"] = $tmpSubCell["DefaultColoring"];
                        }
                        
                        $returnMsg["templateCellData"] = $tmpSubCell["Data"];
                        $returnMsg["tmpCellIndex"] = $tmpCellIndex;
                        $returnMsg["macroList"] = $this->macroList;
                        
                        $tmpData = $this->getTemplateText($tmpSubCell["Data"]);
                        $tmpData = $tmpData == -1 ? "" : $tmpData;
                        
                        $tmpDataType = is_numeric($tmpData) ? "Number" : "String";
                        
                        if ($isFormula)
                        {
                            $t2 .= "<Cell ss:StyleID=\"" . $tmpSubStyleID . "\" " .
                                   "ss:Formula=\"=" . $tmpData . "\">" .
                                   "<Data ss:Type=\"" . $tmpDataType . "\"></Data></Cell>\n";
                        }
                        else
                        {
                            $t2 .= "<Cell ss:StyleID=\"" . $tmpSubStyleID . "\">" .
                                   "<Data ss:Type=\"" . $tmpDataType . "\">" . $tmpData . "</Data></Cell>\n";
                        }

                    }
                }
                
                $tmpCellIndex += $tmpCellNum;
            }
            
            $t1 = "<Row ss:StyleID=\"Default\">\n";
            $t1 .= $t2;
            //$t1 .= "</Row>\n";
            $isFirstRow = false;
        }
        
        $tmpXML .= $t1;
        
        return $tmpXML;
    }
}

?>