<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";
include_once "swtHeartBeatFuncs.php";

date_default_timezone_set('PRC');

$dx11CL = intval($_POST["dx11CL"]);
$dx12CL = intval($_POST["dx12CL"]);
$vulkanCL = intval($_POST["vulkanCL"]);

$t1 = $_POST["resultDateList"];
$resultDateList = explode(",", $t1);
for ($i = 0; $i < count($resultDateList); $i++)
{
    $resultDateList[$i] = cleaninput($resultDateList[$i], 10);
}

$t1 = $_POST["resultFolderList"];
$resultFolderList = explode(",", $t1);
for ($i = 0; $i < count($resultFolderList); $i++)
{
    $resultFolderList[$i] = cleaninput($resultFolderList[$i], 12);
}

$zipFileName = "resultFiles";
$tmpResultPath = $swtTempResultPath;

$returnMsg = array();
$returnMsg["errorCode"] = 1;

function swtGetMachineID($_pathName)
{
    global $returnMsg;
    $json = file_get_contents($_pathName);
    $obj = json_decode($json, true);
    $obj["clientCmd"] = clientSendMachineInfo;

    $clientCmdParser = new CClientHeartBeat;
    $tmpMsg = $clientCmdParser->parseClientCmd($obj);

    $returnMsg["errorMsg"] = isset($tmpMsg["errorMsg"]) ? $tmpMsg["errorMsg"] : "";
    return $tmpMsg["machineID"];
}


if (count($resultFolderList) == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no results found";
    echo json_encode($returnMsg);
    return;
}

$machineIDList = array();
$resultPathList = array();

for ($i = 0; $i < count($resultFolderList); $i++)
{
    $resultFolderPath = $tmpResultPath . "/" . $resultDateList[$i] . "/" . $resultFolderList[$i];
    $t1 = $resultFolderPath . "/machine_info.json";
    
    $machineID = intval(swtGetMachineID($t1));
    
    $tmpKey = array_search ($machineID, $machineIDList);
    if ($tmpKey === false)
    {
        array_push($machineIDList, $machineID);
        array_push($resultPathList, array($resultFolderPath));
    }
    else
    {
        array_push($resultPathList[$tmpKey], $resultFolderPath);
    }
}

// $logStoreDir
$tmpDateName = date("Y-m-d");
$i = 1;
$finalFolderPath = "";
$logFolderName = "";
while (1)
{
    $logFolderName = $tmpDateName . "-skynet" . sprintf("%04d", $i);
    $t1 = $logStoreDir . "/" . $logFolderName;
    if (is_dir($t1) == false)
    {
        mkdir($t1);
        $finalFolderPath = $t1;
        
        // copy changelist file
        $tmpSrcPath = $resultPathList[0][0] . "/default_info.json";
        
        $t3 = file_get_contents($tmpSrcPath);
        $tmpJson = json_decode($t3);
        
        $tmpJson->changeListDX11 = "" . $dx11CL;
        $tmpJson->changeListDX12 = "" . $dx12CL;
        $tmpJson->changeListVulkan = "" . $vulkanCL;
        
        $t4 = json_encode($tmpJson);
        $tmpDestPath = $finalFolderPath . "/default_info.json";
        file_put_contents($tmpDestPath, $t4);
        
        //copy($t2, $finalFolderPath . "/default_info.json");
        break;
    }
    $i++;
}



for ($i = 0; $i < count($machineIDList); $i++)
{
    $t1 = $finalFolderPath . "/machine" . sprintf("%04d", $machineIDList[$i]);
    if (is_dir($t1) == false)
    {
        mkdir($t1);
        
        // copy machine config file
        $t2 = $resultPathList[$i][0] . "/machine_info.json";
        copy($t2, $t1 . "/machine_info.json");
    }

    for ($j = 0; $j < count($resultPathList[$i]); $j++)
    {
        $tmpList = glob($resultPathList[$i][$j] . "/*.txt");
        $t2 = $t1 . "/" . sprintf("%04d", $j + 1);
        
        if (is_dir($t2) == false)
        {
            mkdir($t2);
        }
        
        foreach ($tmpList as $tmpPath)
        {
            $t3 = basename($tmpPath);
            copy($tmpPath, $t2 . "/" . $t3);
        }
    }
}

$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "move result files success";
$returnMsg["logFolderName"] = $logFolderName;
echo json_encode($returnMsg);

?>