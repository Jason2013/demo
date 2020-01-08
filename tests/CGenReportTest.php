<?php

use PHPUnit\Framework\TestCase;

final class CGenReportTest extends TestCase
{
    static public function FilesAreEqual($file1, $file2)
    {
        $str1 = file_get_contents($file1);
        $str2 = file_get_contents($file2);
        return $str1 == $str2;
    }

    public function testCheckNeedCreateReportFile()
    {
        global $returnMsg;

        global $tempFileStartSheetLineNum;
        $tempFileStartSheetLineNum = 4;

        global $reportTemplateDir;
        $reportTemplateDir = "../../template/temple/fileSections";

        global $resultUmdOrder;
        $resultUmdOrder = [0, 1, 2, 3, -1, -1, -1, -1, -1, -1];

        global $reportUmdNum;
        $reportUmdNum = 5;

        global $testNameList;
        $testNameList = [
            "PSAlu",
            "VSAlu",
            "HWContextRoll",
            "VertexFetch",
            "CbUpdate",
            "PrimSetup",
            "PrimFilter",
            "PrimClipping",
            "GSTriAmpl",
            "GSAluHeavy",
            "GSPointAmpl",
            "GSPointSprites",
            "UnigineTess",
            "QuadTess",
            "TriSizeFill",
            "CoherencyStall",
            "PSPostProcess",
            "Branching",
            "CSFillRate",
            "AsyncCompute",
            "Resolve",
            "SmallBatch",
            "ShaderCompile",
            "DepthOp",
            "DepthClear",
            "ColorClear",
            "MultipleRT",
            "Fillrate",
            "TexFetch",
            "TexDimension",
            "ResCopy",
            "Interpolation",
            "CsMandelbrot",
            "LightProbeSampling",
            "CSAlu",
            "CSLatency",
            "ShaderLaunch",
            "RayTriangle"];

        global $subjectNameFilterNumMax;
        $subjectNameFilterNumMax = 9;

        global $swtReportInfo;
        $swtReportInfo = [
            "CL#2046052",
            "CL#2046142",
            "CL#2046117",
            "CL#2045729",
            "CL#2147483647"];

        global $swtReportUmdInfo;
        $swtReportUmdInfo = [
            "D3D11",
            "D3D12",
            "Vulkan",
            "OpenGL",
            "Metal"];

        global $curMachineName;
        $curMachineName = "rokeyTestMachine001";

        global $cmpMachineName;
        $cmpMachineName = "";

        global $tmpFileName1;
        $tmpFileName1 = "../../report/batch1318/00005/GTX1080_Win10 64 bit.tmp1";

        global $colMachineNum;
        $colMachineNum = 1;

        global $colCardNameList;
        $colCardNameList = ["GTX1080"];

        global $colSysNameList;
        $colSysNameList = ["Win10 64 bit"];

        $_xmlFileName = "../../report/batch1318/00005/GTX1080_Win10 64 bit.tmp2";
        $_tmpFileName = "../../report/batch1318/00005/GTX1080_Win10 64 bit.tmp";
        $_jsonFileName = "../../report/batch1318/00005/GTX1080_Win10 64 bit.json";
        $_jsonFileName2 = "../../report/batch1318/00005/GTX1080_Win10 64 bit_2.json";

        $_umdNum = null;
        $_startResultID = null;
        $_cmpMachineID = -1;
        $_resultPos = null;

        $_tempFileLineNumPos = 73;

        $_curCardName = "GTX1080";
        $_tmpSysName = "Win10 64 bit";
        $_cmpCardName = "";
        $_cmpSysName = "";

        global $startStyleID;
        $startStyleID = 117;

        global $allStylesEndTag;
        $allStylesEndTag = "</Styles>\n";

        global $appendStyleList;
        $appendStyleList = [
            "<Style ss:ID=\"s%d\">\n<Interior ss:Color=\"#000000\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Interior/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n<Interior ss:Color=\"#800000\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n<Interior ss:Color=\"#A03300\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n</Borders>\n<Interior ss:Color=\"#FFFFA0\" ss:Pattern=\"Solid\"/>\n<NumberFormat ss:Format=\"0.000\"/></Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n</Borders>\n<Interior ss:Color=\"#D0FFFF\" ss:Pattern=\"Solid\"/>\n<NumberFormat ss:Format=\"Percent\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Left\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n<Interior ss:Color=\"#A03300\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Left\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n</Borders>\n<Interior ss:Color=\"#FFFFA0\" ss:Pattern=\"Solid\"/>\n<NumberFormat ss:Format=\"0.000\"/></Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#800000\" ss:Bold=\"1\"/>\n<Interior ss:Color=\"#800000\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#ffffff\" ss:Bold=\"1\"/>\n<Interior ss:Color=\"#ffffff\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Bottom\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#000000\" ss:Bold=\"1\"/>\n<Interior ss:Color=\"#D9D9D9\" ss:Pattern=\"Solid\"/>\n<NumberFormat ss:Format=\"Percent\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Bottom\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n<Interior ss:Color=\"#FFC000\" ss:Pattern=\"Solid\"/>\n</Style>",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Bottom\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n<Interior ss:Color=\"#000000\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Bottom\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#000000\" />\n<Interior ss:Color=\"#D9D9D9\" ss:Pattern=\"Solid\"/>\n<NumberFormat ss:Format=\"Percent\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"  ss:Color=\"#000000\"/>\n</Borders>\n<Interior ss:Color=\"#FFC000\" ss:Pattern=\"Solid\"/>\n<NumberFormat ss:Format=\"0.000\"/>\n</Style>",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Left\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\"  ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n<Interior ss:Color=\"#800000\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\" ss:WrapText=\"1\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#000000\" \nss:Bold=\"1\"/>\n<Interior ss:Color=\"#FFC000\" ss:Pattern=\"Solid\"/>\n<NumberFormat/>\n<Protection/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#808080\" \nss:Bold=\"1\"/>\n<Interior ss:Color=\"#A03300\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#000000\"/>\n<Interior ss:Color=\"#D9D9D9\" ss:Pattern=\"Solid\"/>\n<NumberFormat ss:Format=\"Percent\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Left\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\"\n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\" \n ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" \nss:Bold=\"1\"/>\n<Interior ss:Color=\"#A03300\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Right\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\"\n ss:Bold=\"1\"/>\n<Interior ss:Color=\"#800000\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Right\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"\n ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#000000\"\n ss:Bold=\"1\"/>\n<Interior ss:Color=\"#FFFFA0\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n<Borders>\n<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\"  ss:Color=\"#000000\"/>\n<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"3\"  ss:Color=\"#000000\"/>\n</Borders>\n<Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"14\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n<Interior ss:Color=\"#800000\" ss:Pattern=\"Solid\"/>\n</Style>\n",
            "<Style ss:ID=\"s%d\">\n<Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n<Interior/>\n<NumberFormat ss:Format=\"Percent\"/>\n</Style>\n"];

        $folder = __DIR__ . "/../report/batch1318/00005";
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $userInfo["isManager"] = true;
        $report = new CGenReport($userInfo);
        $resultSet = $report->checkNeedCreateReportFile($_xmlFileName, $_tmpFileName, $_jsonFileName, $_jsonFileName2,
            $_umdNum, $_startResultID, $_cmpMachineID, $_resultPos,
            $_tempFileLineNumPos,
            $_curCardName, $_tmpSysName,
            $_cmpCardName, $_cmpSysName);

        $this->assertTrue(!is_null($resultSet));
//        $this->assertTrue($returnMsg["errorCode"] == 0);


        $target_file1 = __DIR__ . "/../report/batch1318/00005/GTX1080_Win10 64 bit.tmp1";
        $target_file2 = __DIR__ . "/../report/batch1318/00005/GTX1080_Win10 64 bit.tmp2";

        $expected_file1 = __DIR__ . "/data/GTX1080_Win10 64 bit.tmp1";
        $expected_file2 = __DIR__ . "/data/GTX1080_Win10 64 bit.tmp2";

        $this->assertTrue(static::FilesAreEqual($target_file1, $expected_file1));
        $this->assertTrue(static::FilesAreEqual($target_file2, $expected_file2));
    }
}
