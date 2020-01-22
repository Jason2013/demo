<?php

try {
    //
    $hello = "hello";

//    $excel = new COM("Excel.Applicationxx");


    $excel = new COM('excel.application') or die("Excel could not be started.");
    $excel->Visible = 1;
    $excel->DisplayAlerts = 0;
// add a workbook
    $excel->Workbooks->Add();

// save
    $Workbook = $excel->Workbooks[1];
    $Workbook->SaveAs("C:\\xampp\\htdocs\\foo\\excel\\test.xlsx");

    $Workbook->Worksheets->Add();
    $Worksheet = $Workbook->Worksheets[1];

    $cell = $Worksheet->Cells(1, 2);


    $excel->Quit();
//    $excel->
//    throw new Exception("My Error");
//    echo 1/0;
}
catch (Exception $ex){
    echo $ex->getMessage();
}
    finally{
    if (isset($excel)) {
        $excel->Quit();
    }
echo $hello;
    }

//echo microtime();
//echo microtime();
//
//$num = 1;
//if ($num > 1) {
//    $g_var = "tom";
//}
//
//error_reporting(E_STRICT);
//
//function microtime_float()
//{
//    global $g_var;
//    list($usec, $sec) = explode(" ", microtime());
//    $g_var = "hello";
//    return ((float)$usec + (float)$sec);
//}
//
//$myvar["name"] = "Alice";
//$myvar["count"] = 1;
//
//file_put_contents("my_test_config.json", json_encode($myvar));
//function _getIDNum($inc)
//{
//    $filename = "my_test_config.txt";
//    $json = file_get_contents($filename);
//    if (!$json) {
//        $result["id"] = 1;
//        $json = json_encode($result);
//        file_put_contents($filename, $json);
//    } else {
//        $result = json_decode($json, true);
//    }
//
//    $retval = $result["id"];
//
//    if ($inc) {
//        $result["id"] += 1;
//        $json = json_encode($result);
//        file_put_contents($filename, $json);
//    }
//    return $retval;
//}
//
//function _getID($inc = true)
//{
//    $id = _getIDNum($inc);
//    return sprintf("%05d", $id);
//}
//
//$logfile = "my_test_file_operation.txt";
//
//function _logMsg($handle, $op, $msg = null)
//{
//    global $fileHandles;
//    global $logfile;
//
//    $id = _getID();
//    $filename = $fileHandles[(int)$handle];
//    $opstr = sprintf("%-6s", $op);
//    $logmsg = "[$id] [$opstr] $filename\n";
//    if ($msg) {
//        $logmsg .= ">>> begin\n$msg<<< end\n";
//    }
//    file_put_contents($logfile, $logmsg, FILE_APPEND);
//}
//
//function _fopen($filename, $mode)
//{
//    $handle = fopen($filename, $mode);
//
//    global $fileHandles;
//    $fileHandles[(int)$handle] = $filename;
//
//    _logMsg($handle, "fopen");
//    return $handle;
//}
//
//function _fwrite($handle, $str)
//{
//    fwrite($handle, $str);
//
//    _logMsg($handle, "fwrite", $str);
//}
//
//function _fclose($handle)
//{
//    fclose($handle);
//    _logMsg($handle, "fclose");
//
//    global $fileHandles;
//    $filename = $fileHandles[$handle];
//    copy($filename, $filename . "." . _getID(false));
//}
//$filename = "my_test2.txt";
//
//$myhandle = _fopen($filename, "w");
//_fwrite($myhandle, "hello, world!\n");
//_fclose($myhandle);
