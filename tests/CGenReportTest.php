<?php

use PHPUnit\Framework\TestCase;

final class CGenReportTest extends TestCase
{
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

        $report = new CGenReport();
        $resultSet = $report->checkNeedCreateReportFile($_xmlFileName, $_tmpFileName, $_jsonFileName, $_jsonFileName2,
            $_umdNum, $_startResultID, $_cmpMachineID, $_resultPos,
            $_tempFileLineNumPos,
            $_curCardName, $_tmpSysName,
            $_cmpCardName, $_cmpSysName);

        $this->assertTrue(!is_null($resultSet));
        $this->assertTrue($returnMsg["errorCode"] == 0);
    }
}
