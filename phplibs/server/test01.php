<?php

include_once "../generalLibs/dopdo.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

$tmpResultPath = "../../tmpResultFile";
// delete old reports
$tmpPath = $tmpResultPath . "/*";
$tmpList = glob($tmpPath, GLOB_ONLYDIR);
$curTime = time();
// 3 days
$noTouchTimeLen = 3 * 24 * 3600;

foreach ($tmpList as $tmpName)
{
    $n1 = swtGetFileTreeLastAccessTime($tmpName);
    
    echo $tmpName . "<br />";
    echo $n1 . "<br />";
    echo $curTime . "<br />";
    
    if (($curTime - $n1) > $noTouchTimeLen)
    {
        //swtDelFileTree($tmpName);
    }
}

?>