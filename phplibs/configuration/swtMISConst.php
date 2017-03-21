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
 

$swtUmdNameList = array("DX11", "DX12", "Vulkan");
$swtUmdStandardOrder = array("Vulkan", "DX12", "DX11");

$swtSheetColumnIDList = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", 
                              "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
                              "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", 
                              "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ", 
                              "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM",
                              "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ");

define("graphDataStartLineID", "7");
define("graphDataStartLineIDCompare", "7");
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

$swtStartStyleID = 101;

?>