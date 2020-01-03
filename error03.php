<?php

//echo microtime();
//echo microtime();
//
//$num = 1;
//if ($num > 1) {
//    $g_var = "tom";
//}

function microtime_float()
{
    global $g_var;
    list($usec, $sec) = explode(" ", microtime());
    $g_var = "hello";
    return ((float)$usec + (float)$sec);
}

$myvar["name"] = "Alice";
$myvar["count"] = 1;

file_put_contents("my_test_config.json", json_encode($myvar));

function _getIDNum()
{
    $filename = "my_test_config.txt";
    $json = file_get_contents($filename);
    if (!$json) {
        $result["id"] = 0;
    } else {
        $result = json_decode($json, true);
    }
    $result["id"] += 1;
    $json = json_encode($result);
    file_put_contents($filename, $json);
    return $result["id"];
}

function _getID()
{
    $id = _getIDNum();
    return sprintf("%05d", $id);
}

$logfile = "my_test_file_operation.txt";

function _logMsg($handle, $op, $msg = null)
{
    global $fileHandles;
    global $logfile;

    $id = _getID();
//    $op = "fwrite";
    $filename = $fileHandles[$handle];
    $logmsg = "$id $filename $op\n";
    if ($msg) {
        $logmsg .= ">>> begin\n$msg<<< end\n";
    }
    file_put_contents($logfile, $logmsg, FILE_APPEND);
}

function _fopen($filename, $mode)
{
    $handle = fopen($filename, $mode);

    global $fileHandles;
    $fileHandles[$handle] = $filename;

    return $handle;
}

function _fwrite($handle, $str)
{
    fwrite($handle, $str);

    _logMsg($handle, "fwrite", $str);

//    global $fileHandles;
//    $id = _getID();
//    $op = "fwrite";
//    $filename = $fileHandles[$handle];
//    $msg = "$id $filename $op\n>>> begin\n$str<<< end\n";
//    file_put_contents($msg, FILE_APPEND);
}

function _fclose($handle)
{
    fclose($handle);
    _logMsg($handle, "fclose");//, "");
}


$filename = "my_test2.txt";

$myhandle = _fopen($filename, "w");
_fwrite($myhandle, "hello, world!\n");
_fclose($myhandle);

//    "my_test1.txt", "w")

//
//$time_start = microtime_float();
//
//// Sleep for a while
////usleep(1000000);
//
//$time_end = microtime_float();
//$time = $time_end - $time_start;
//
//echo "\nDid nothing in type($time) seconds\n";
//
//echo 1+2;
//echo $g_var;
//
//for ($i=0; $i<5; ++$i){
//    $num12["key"] = 10;
//
//}
