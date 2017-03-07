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

define("graphDataStartLineID", "7");
define("graphDataStartCloumnID", "R");
define("graphDataEndCloumnID", "U");
define("graphDataStartCloumnIDCompare", "AB");
$graphDataStartCloumnIDCompareList = array("AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM");
define("graphDataEndCloumnIDCompare", "AH");

$ubuntuCheckWord = "ubuntu";

$swtStartStyleID = 100;

?>