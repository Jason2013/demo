<?php

return;

include_once "../configuration/swtConfig.php";

$targetPath = $logStoreDir;

function swtTraverseDir($_path)
{
    $folderList = glob($_path . "/*", GLOB_ONLYDIR);
    foreach ($folderList as $tmpPath)
    {
        swtTraverseDir($tmpPath);
    }
    $fileList = glob($_path . "/machine_info.json");
    foreach ($fileList as $tmpPath)
    {
        $t1 = file_get_contents($tmpPath);
        $obj = json_decode($t1);
        $n1 = strpos($obj->videoCardName, "AMD ");
        if ($n1 !== false)
        {
            $t1 = str_replace("AMD ", "", $obj->videoCardName);
            $obj->videoCardName = $t1;
        }
        $n1 = strpos($obj->videoCardName, "Nvidia");
        if ($n1 !== false)
        {
            $obj->videoCardName = "GTX 980 Ti";
        }
        $n1 = strpos($obj->systemName, "Microsoft");
        if ($n1 !== false)
        {
            $obj->systemName = "Win10 64 bit";
        }
        $t1 = json_encode($obj);
        file_put_contents($tmpPath, $t1);
    }
}

swtTraverseDir($targetPath);

echo "done";

?>