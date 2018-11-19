<?php

define("serverDoNothing", "0");
define("serverRequireClientMachineInfo", "10");
define("serverReturnHeartBeatSuccess", "11");
define("serverReturnHeartBeatNotSuccess", "12");
define("serverGotTaskSuccess", "13");
define("serverCloseTask", "14");

define("clientDoNothing", "0");
define("clientSendHeartBeat", "10");
define("clientSendMachineInfo", "11");
define("clientQueryTask", "12");

define("availableMachineCoolHours", "2");
define("availableMachineCoolMins", "15");
define("uploadDriverFileTimeOutHours", "20");
define("taskTimeOutHours", "10");
define("clearDriverFolderTimeOutDays", "7");

$swtClientMachineConfigurationNames = array("machineName",
                                            "videoCardName",
                                            "cpuName",
                                            "systemName",
                                            "memoryName",
                                            "chipsetName",
                                            "mainLineName",
                                            "machineIP",
                                            "machineID");
 

//$swtUmdNameList = array("DX11", "DX12", "Vulkan");
//$swtUmdStandardOrder = array("Vulkan", "DX12", "DX11");

//$swtUmdNameList = array("DX11", "DX12", "Vulkan", "OGL");
//$swtUmdStandardOrder = array("Vulkan", "DX12", "DX11", "OGL");

//$swtUmdNameList = array("D3D11", "D3D12", "Vulkan", "OGL");
//$swtUmdNameList_old =   array("DX11",  "DX12",  "Vulkan", "OGL", "D3D11_NATIVE");
//$swtUmdNameList_final = array("D3D11", "D3D12", "Vulkan", "OGL", "D3D11");
//$swtUmdStandardOrder = array("Vulkan", "D3D12", "D3D11", "OGL");

$swtUmdNameList = array("D3D11", "D3D12", "Vulkan", "OpenGL", "Metal");
$swtUmdNameList_old =   array("DX11",  "DX12",  "Vulkan", "OpenGL", "OGL",    "D3D11_NATIVE", "Metal");
$swtUmdNameList_final = array("D3D11", "D3D12", "Vulkan", "OpenGL", "OpenGL", "D3D11",        "Metal");
$swtUmdStandardOrder = array("Vulkan", "D3D11", "D3D12", "OpenGL", "Metal");
//$swtUmdStandardOrder = array("D3D11", "D3D12", "Vulkan", "OpenGL", "Metal");

//$swtUmdNameList_sb = array("SCPC", "LLPC", "RADV", "NVIDIA", "OPT1");
//$swtUmdStandardOrder_sb = array("SCPC", "LLPC", "RADV", "NVIDIA", "OPT1");
$swtUmdNameList_sb = array("RADV", "SCPC", "LLPC", "NVIDIA", "OPT1");
$swtUmdStandardOrder_sb = array("RADV", "SCPC", "LLPC", "NVIDIA", "OPT1");

//$swtPreSheetName_sb = array("compileTime", "executionTime");
//$swtPreSheetNameTitle_sb = array("Compile Time", "Execution Time");
//$swtPreSheetNameShort_sb = array("compTime", "execTime");

$swtPreSheetName_sb = array("executionTime", "compileTime");
$swtPreSheetNameTitle_sb = array("Execution Time", "Compile Time");
$swtPreSheetNameShort_sb = array("execTime", "compTime");

$swtPreSheetName_pf = array("executionTime", "compileTime");
$swtPreSheetNameTitle_pf = array("Execution Time", "Compile Time");
$swtPreSheetNameShort_pf = array("execTime", "compTime");

//$swtUmdNameList = array("VG10_40CU_1600e_945m",
//                        "VG10_64CU_1600e_945m",
//                        "GTX1080_Fixed",
//                        "GTX1080_Default");
//$swtUmdStandardOrder = array("VG10_40CU_1600e_945m",
//                             "VG10_64CU_1600e_945m",
//                             "GTX1080_Fixed",
//                             "GTX1080_Default");

$swtOldUmdNameMatchList = array("D3D11", "DX11", "D3D12", "DX12");

//$swtUmdNameList = array("DX11", "DX12", "Vulkan");
//$swtUmdStandardOrder = array("Vulkan", "DX12", "DX11");

$swtSheetColumnIDList = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", 
                              "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
                              "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", 
                              "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ", 
                              "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM",
                              "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ");

define("graphDataStartLineID", "6");
define("graphDataStartLineIDCompare", "6");
define("graphDataStartCloumnID", "R");
define("graphDataEndCloumnID", "U");
define("graphDataStartCloumnIDIndex", 17);
define("graphDataEndCloumnIDIndex", 20);
define("graphDataStartCloumnIDCompare", "AB");
define("graphDataEndCloumnIDCompare", "AH");
define("graphDataStartCloumnIDCompareIndex", 27);
define("graphDataEndCloumnIDCompareIndex", 33);
$graphDataStartCloumnIDCompareList = array("AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM",
                                           "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ",
                                           "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM");


$ubuntuCheckWord = "ubuntu";
$reportFolderCheckWord = "report_";

$swtStartStyleID = 117;
define("reportStyleRedStart", 101);
define("reportStyleRedNum", 8);
define("reportStyleGreenStart", 109);
define("reportStyleGreenNum", 8);
define("maxAverageStyleRate", 100);

$swtNoiseDataExpireDayNum = 200;

$swtMicrobenchDocsTestNameUrl = "http://gfxbench/docs/Microbench/%s/%s.html";

?>