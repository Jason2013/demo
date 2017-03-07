<?php

include_once "../generalLibs/dopdo.php";
include_once "../generalLibs/dodb.php";
include_once "../configuration/swtMISConst.php";
include_once "../configuration/swtConfig.php";
include_once "../server/swtHeartBeatFuncs.php";
include_once "../generalLibs/genfuncs.php";
include_once "../generalLibs/code01.php";

// this php script is to parse microbench results mannually
// support multi-piece log files

$logFolderName = cleaninput($_POST["logFolderName"], 128);
$nextResultFileID = intval($_POST["nextResultFileID"]);
$resultFileNum = intval($_POST["resultFileNum"]);
$batchID = intval($_POST["batchID"]);
$reportGroup = intval($_POST["reportGroup"]);
$nextLineID = intval($_POST["nextLineID"]);
$curTestID = intval($_POST["curTestID"]);
$nextSubTestID = intval($_POST["nextSubTestID"]);
$curFileLineNum = intval($_POST["curFileLineNum"]);

$targetLogFileName = "test_results.txt";
$targetLogFileName2 = "test_results.csv";
$machineInfoFileName = "machine_info.json";
$defaultInfoFileName = "default_info.json";
$changeListJsonTag = "changeList";

$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "insert data to database success";
$returnMsg["nextResultFileID"] = $nextResultFileID;
$returnMsg["nextLineID"] = $nextLineID;
$returnMsg["resultFileNum"] = $resultFileNum;
$returnMsg["batchID"] = $batchID;
$returnMsg["curTestID"] = $curTestID;
$returnMsg["nextSubTestID"] = $nextSubTestID;
$returnMsg["curFileLineNum"] = $curFileLineNum;
$returnMsg["parseFinished"] = 0;
$globalResultIDList = array();
$defaultInfo = null;
//$curFileLineNum = 0;

function swtGetFileLinesNum($_pathName)
{
    global $curFileLineNum;
    if ($curFileLineNum > 0)
    {
        return $curFileLineNum;
    }
    $line = 0;
    $fp = fopen($_pathName , 'r');  
    if ($fp)
    {
        while (stream_get_line($fp,8192,"\n"))
        {
           $line++;
        }
        fclose($fp);
    }   
    return $line;  
}

function swtGetResultID($_batchID, $_machineID, $_umdID, $_CL, $_state)
{
    global $returnMsg;
    $db = new CPdoMySQL();
    if ($db->getError() != null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "can't reach mysql server, line: " . __LINE__;
        return -1;
    }
    
    $params1 = array($_machineID, $_batchID, $_umdID);
    $sql1 = "SELECT result_id FROM mis_table_result_list " .
            "WHERE machine_id=? AND batch_id=? AND umd_id=?";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3, line: " . __LINE__;
        return -1;
    }
    $row1 = $db->fetchRow();
    $resultID = -1;
    if ($row1 == false)
    {
        $params1 = array($_machineID, $_batchID, $_umdID, $_CL, $_state);
        $sql1 = "INSERT INTO mis_table_result_list " .
                "(machine_id, batch_id, umd_id, cl_value, path_id, result_state, insert_time) " .
                "VALUES (?, ?, ?, ?, \"0\", ?, NOW())";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["sql1"] = $sql1;
            $returnMsg["errorMsg"] = "query mysql table failed #2b, line: " . __LINE__ . implode(",", $params1);
            return -1;
        }
        $resultID = $db->getInsertID();
    }
    else
    {
        $resultID = $row1[0];
    }

    return $resultID;
}

function swtGetBatchID($_state)
{
    global $returnMsg;
    global $logFolderName;
    global $reportGroup;
    
    $db = new CPdoMySQL();
    if ($db->getError() != null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "can't reach mysql server";
        return -1;
    }
    $batchGroup = $reportGroup;
    if (intval($_state) == 4)
    {
        // if routine batch
        //$batchGroup = 1;
    }
    
    $params1 = array($logFolderName);
    $sql1 = "SELECT * FROM mis_table_path_info " .
            "WHERE path_name=?";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__;
        return -1;
    }
    $row1 = $db->fetchRow();
    $pathID = -1;
    if ($row1 == false)
    {
        $params1 = array($logFolderName);
        $sql1 = "INSERT INTO mis_table_path_info " .
                "(path_name) " .
                "VALUES (?)";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3a, line: " . __LINE__;
            return -1;
        }
        $pathID = $db->getInsertID();
    }
    else
    {
        $pathID = $row1[0];
    }
    
    $params1 = array($_state, $batchGroup, $pathID);
    $sql1 = "INSERT INTO mis_table_batch_list " .
            "(insert_time, batch_state, batch_group, path_id) " .
            "VALUES (NOW(), ?, ?, ?)";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3a";
        return -1;
    }
    $batchID = $db->getInsertID();
    return $batchID;
}

function swtGetUmdID($_umdName)
{
    global $returnMsg;
    $db = new CPdoMySQL();
    if ($db->getError() != null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "can't reach mysql server";
        return -1;
    }
    $params1 = array($_umdName);
    $sql1 = "SELECT env_id FROM mis_table_environment_info " .
            "WHERE env_name=? AND env_type=\"4\"";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3";
        return -1;
    }
    $row1 = $db->fetchRow();
    $umdID = -1;
    if ($row1 == false)
    {
        // insert new driver type, like Vulkan, DX11, DX12
        $params1 = array($_umdName);
        $sql1 = "INSERT IGNORE INTO mis_table_environment_info " .
                "(env_name, env_type) " .
                "VALUES (?, \"4\")";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3a";
            return -1;
        }
        $umdID = $db->getInsertID();
    }
    else
    {
        $umdID = $row1[0];
    }
    return $umdID;
}

function swtFeedData($_db, $_subTestList, $_dataList, $_testName)
{
    global $db_username;
    global $db_password;
    global $returnMsg;
    global $db_mis_table_name_string001;
    global $swtTempFilesDir;

    if (strlen($_testName) == 0)
    {
        return;
    }
    if ((strlen($_subTestList) == 0) &&
        (strlen($_dataList)    == 0))
    {
        return;
    }
    
    $tmpFileName = "";
    while (1)
    {
        $tmpToken = md5("sql_script_" . time());
        $tmpFileName = $swtTempFilesDir . "/sqlTmpFile" . $tmpToken . ".txt";
        if (file_exists($tmpFileName) == false)
        {
            break;
        }
    }
    
    error_reporting(E_ALL ^ E_DEPRECATED);
    
    $db = new CDoMySQL($db_username, $db_password);
    if ($db->ReadyDB() != true)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "can't reach mysql server";
        return -1;
    }
    
    if (strlen($_subTestList) > 0)
    {
        file_put_contents($tmpFileName, $_subTestList);
        $tmpPathName = dirname(__FILE__) . "/" . $tmpFileName;
        $tmpPathName = str_replace("\\", "/", $tmpPathName);
        
        $sql1 = "LOAD DATA LOCAL INFILE \"" . $tmpPathName . "\" IGNORE INTO TABLE mis_table_test_info " .
                "FIELDS TERMINATED BY ',' " .
                "LINES TERMINATED BY '\n' (test_name, test_type);";
        if ($db->QueryDBNoResult($sql1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["sql1"] = $sql1;
            $returnMsg["tmpPathName"] = $tmpPathName;
            $returnMsg["errorMsg"] = "query mysql table failed #4, line: " . __LINE__ . ", error: " . $db->dbError;
            return -1;
        }
        unlink($tmpFileName);
    }
    
    if (strlen($_dataList) > 0)
    {
        file_put_contents($tmpFileName, $_dataList);
        $tmpPathName = dirname(__FILE__) . "/" . $tmpFileName;
        $tmpPathName = str_replace("\\", "/", $tmpPathName);
    
        $sql1 = "CREATE TEMPORARY TABLE IF NOT EXISTS tmp_table_test_data1 " .
                "( data_id INT UNSIGNED AUTO_INCREMENT, " .
                "  result_id INT UNSIGNED, " .
                "  sub_name VARCHAR(128), " .
                "  data_value FLOAT," .
                "  test_case_id INT," .
                "  PRIMARY KEY (data_id));";
        if ($db->QueryDBNoResult($sql1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #4, line: " . __LINE__ . ", error: " . $db->dbError;
            return -1;
        }
        $sql1 = "DELETE FROM tmp_table_test_data1;";
        if ($db->QueryDBNoResult($sql1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #4, line: " . __LINE__ . ", error: " . $db->dbError;
            return -1;
        }
        $sql1 = "LOAD DATA INFILE \"" . $tmpPathName . "\" IGNORE INTO TABLE tmp_table_test_data1 " .
                "FIELDS TERMINATED BY ',' " .
                "LINES TERMINATED BY '\n' (result_id, sub_name, data_value, test_case_id);";
        if ($db->QueryDBNoResult($sql1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #4, line: " . __LINE__;
            return -1;
        }
        
        $tableName01 = $db_mis_table_name_string001 . cleaninput($_testName, 256);
                
        $sql1 = "INSERT IGNORE INTO " . $tableName01 . " " .
                "(result_id, sub_id, data_value, test_case_id) " .
                "(SELECT t0.result_id, t1.test_id, t0.data_value, t0.test_case_id " .
                "FROM tmp_table_test_data1 t0, mis_table_test_info t1 " .
                "WHERE t0.sub_name=t1.test_name AND t1.test_type=\"2\" " .
                "ORDER BY t0.data_id ASC);";
                
        $sql1 = "REPLACE INTO " . $tableName01 . " " .
                "(result_id, sub_id, data_value, test_case_id) " .
                "(SELECT t0.result_id, t1.test_id, t0.data_value, t0.test_case_id " .
                "FROM tmp_table_test_data1 t0, mis_table_test_info t1 " .
                "WHERE t0.sub_name=t1.test_name AND t1.test_type=\"2\" " .
                "ORDER BY t0.data_id ASC);";
                
        if ($db->QueryDBNoResult($sql1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["sql1"] = $sql1;
            $returnMsg["errorMsg"] = "query mysql table failed #4, line: " . __LINE__ . ", error: " . $db->dbError;
            return -1;
        }
        unlink($tmpFileName);
    }

}

function swtParseLogFile($_pathName, $_machineID)
{
    global $returnMsg;
    global $globalResultIDList;
    global $changeListJsonTag;
    global $db_mis_table_create_string001;
    global $db_mis_table_name_string001;
    global $defaultInfo;
    global $nextLineID;
    global $batchID;
    global $curTestID;
    global $nextSubTestID;
    
    if (file_exists($_pathName) == false)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "log file is missing, line: " . __LINE__ . "path: " . $_pathName;
        return -1;
    }

    $db = new CPdoMySQL();
    if ($db->getError() != null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "can't reach mysql server";
        return -1;
    }
    
    $returnMsg["resultIDList"] = "";

    $resultIDList = array();
    $umdNameList = array();
    // test name,
    // sub test name,
    // data,
    // api
    $minColumnNum = 4;

    $testName = "";
    $umdName = "";
    $umdID = -1;
    $umdIDMap = array();
    $tableName01 = "";
    $dataKeyAPI = -1;

    $feedSubTestNameString = "";
    $feedSubTestDataString = "";

    
    $maxInsertDataNum = 10000;
    
    $row = 0;
    $dataRow = 0;
    $tmpTestID = 0;
    $tmpSubTestID = 0;
    $tmpLineID = 0;
    $handle = fopen($_pathName, "r");
    $data = false;
    
    // jump to start csv line of this call
    while ($data = fgetcsv($handle, 0, ","))
    {
        $tmpLineID++;
        
        $num = count($data);
        if ($num < $minColumnNum)
        {
            continue;
        }
        for ($i = 0; $i < count($data); $i++)
        {
            $data[$i] = trim($data[$i]);
        }
        $tmpName = trim($data[0]);

        if (strlen($tmpName) > 0)
        {
            // if this line is title line of each test
            $testName = $tmpName;
            $dataKeyAPI = array_search("API", $data);
            $tmpTestID++;
            $tmpSubTestID = 0;
            
            if (($tmpTestID - 1) >= $curTestID)
            {
                // if this line is start of each test
                // try create table if not exist
                $sql1 = sprintf($db_mis_table_create_string001, cleaninput($testName, 256));
                $params1 = array();
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    fclose($handle);
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["sql1"] = $sql1;
                    $returnMsg["errorMsg"] = "query mysql table failed #4, line: " . __LINE__;
                    return -1;
                }
                $tableName01 = $db_mis_table_name_string001 . cleaninput($testName, 256);

                $subTestSubject = $data[1];
                $unitSubject = $data[2];
                // insert testName, subTestSubject, unitSubject
                $testNameInsertSQL = "INSERT IGNORE INTO mis_table_test_info " .
                                     "(test_name, test_type) VALUES ";
                $testNameInsertSQL .= "(?, \"0\"), ";
                $testNameInsertSQL .= "(?, \"1\"), ";
                $testNameInsertSQL .= "(?, \"3\");";
                
                $sql1 = $testNameInsertSQL;
                $params1 = array($testName, $subTestSubject, $unitSubject);
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    fclose($handle);
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3" . $db->getError()[2] . ", line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return -1;
                }
                
                if ($batchID == -1)
                {
                    $batchID = swtGetBatchID("4");
                    if ($batchID == -1)
                    {
                        fclose($handle);
                        $returnMsg["errorCode"] = 0;
                        $returnMsg["errorMsg"] = "get batch id failed";
                        return -1;
                    }
                }
                
                $sql1 = "SELECT test_id FROM mis_table_test_info WHERE test_name=? AND test_type=\"0\" LIMIT 1";
                $params1 = array($testName);
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    fclose($handle);
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #4" . $db->getError()[2] . ", line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return -1;
                }
                $row1 = $db->fetchRow();
                if ($row1 == false)
                {
                    fclose($handle);
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #4" . $db->getError()[2] . ", line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return -1;
                }
                $tableTestID = $row1[0];
                
                $sql1 = "SELECT COUNT(*) FROM mis_table_test_subject_list " .
                        "WHERE batch_id = ? AND test_id = ?";
                $params1 = array($batchID, $tableTestID);
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    fclose($handle);
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #4" . $db->getError()[2] . ", line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return -1;
                }
                $row1 = $db->fetchRow();
                if ($row1 == false)
                {
                    fclose($handle);
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #4" . $db->getError()[2] . ", line: " . __LINE__;
                    echo json_encode($returnMsg);
                    return -1;
                }
                $tableTestIDNum = intval($row1[0]);
                
                if ($tableTestIDNum == 0)
                {
                    // new test name for this batch
                    $testSubjectListInsertSQL = "INSERT IGNORE INTO mis_table_test_subject_list " .
                                                "(batch_id, test_id, subject_id, unit_id) VALUES " .
                                                "(?, " .
                                                " (SELECT test_id FROM mis_table_test_info WHERE test_name=? LIMIT 1), " .
                                                " (SELECT test_id FROM mis_table_test_info WHERE test_name=? LIMIT 1), " .
                                                " (SELECT test_id FROM mis_table_test_info WHERE test_name=? LIMIT 1));";
                                                 
                    $sql1 = $testSubjectListInsertSQL;
                    $params1 = array($batchID, $testName, $subTestSubject, $unitSubject);
                    if ($db->QueryDB($sql1, $params1) == null)
                    {
                        fclose($handle);
                        $returnMsg["errorCode"] = 0;
                        $returnMsg["errorMsg"] = "query mysql table failed #4" . $db->getError()[2] . ", line: " . __LINE__;
                        echo json_encode($returnMsg);
                        return -1;
                    }
                }
            }
        }
        else
        {
            $tmpSubTestID++;
        }
        if (($tmpTestID - 1) >= $curTestID)
        {
            if (($tmpSubTestID) >= $nextSubTestID)
            {
                // resume last parse
                break;
            }
        }
    }
    
    $tmpTestID = 0;
    $tmpSubTestID = 0;
    
    while ($data = fgetcsv($handle, 0, ","))
    {
        $tmpLineID++;
        $nextLineID++;
        $num = count($data);
        if ($num < $minColumnNum)
        {
            continue;
        }
        for ($i = 0; $i < count($data); $i++)
        {
            $data[$i] = trim($data[$i]);
        }
        $tmpName = $data[0];

        if (strlen($tmpName) > 0)
        {
            // if this line is title line of each test
            swtFeedData($db, $feedSubTestNameString, $feedSubTestDataString, $testName);
            $feedSubTestNameString = "";
            $feedSubTestDataString = "";

            //$testName = $tmpName;
            //$dataKeyAPI = array_search("API", $data);
            $tmpTestID++;
            $curTestID++;
            $nextSubTestID = 0;
            fclose($handle);
            return -1;
        }
        else
        {
            // if this line is sub test
            $tmpSubTestID++;
            $nextSubTestID++;
            
            if ($tmpSubTestID >= $maxInsertDataNum)
            {
                swtFeedData($db, $feedSubTestNameString, $feedSubTestDataString, $testName);
                $feedSubTestNameString = "";
                $feedSubTestDataString = "";

                $nextSubTestID--;
                fclose($handle);
                return -1;
            }

            //if ((strlen($testName) > 0)    &&
            //    ($batchID != -1))
            if ((strlen($testName) > 0)    &&
                ($batchID != -1))
            {
                $subTestName = $data[1];
                $dataValue = $data[2];
                $testCaseID = $data[3];

                if ($dataKeyAPI !== false)
                {
                    $umdName = $data[$dataKeyAPI];
                }
                if (strlen($umdName) == 0)
                {
                    continue;
                }
                
                $umdID = -1;
                if (array_key_exists($umdName, $umdIDMap))
                {
                    $umdID = $umdIDMap[$umdName];
                }
                if ((($umdID == null) ||
                    ($umdID == -1))   &&
                    (strlen($dataValue) > 0)   &&
                    (is_numeric($dataValue) == true))
                {
                    // get umd driver id
                    $umdID = swtGetUmdID($umdName);
                    $umdIDMap[$umdName] = $umdID;
                    if ($umdID == -1)
                    {
                        fclose($handle);
                        $returnMsg["errorCode"] = 0;
                        $returnMsg["errorMsg"] = "invalid umd driver name";
                        return -1;
                    }
                    
                    // like changeListDX11, changeListDX12, changeListVulkan
                    $t1 = $changeListJsonTag . $umdName;
                    $changeList = "0";
                    if ((array_key_exists($t1, $defaultInfo)  == true) &&
                        ($defaultInfo[$t1]                    != null) &&
                        (is_numeric($defaultInfo[$t1])        == true))
                    {
                        $changeList = $defaultInfo[$t1];
                    }
                    
                    $resultID = swtGetResultID($batchID, $_machineID, $umdID, $changeList, "4");
                    if ($resultID == -1)
                    {
                        fclose($handle);
                        $returnMsg["errorCode"] = 0;
                        $returnMsg["errorMsg"] .= " | get result id failed";
                        return -1;
                    }
                    
                    array_push($resultIDList, $resultID);
                    array_push($umdNameList, $umdName);
                }
                
                if ((strlen($subTestName) > 0) &&
                    (strlen($dataValue) > 0)   &&
                    (strlen($umdName) > 0)     &&
                    (is_numeric($dataValue) == true))
                {
                    // if data is valid
                    $tmpKey = array_search($umdName, $umdNameList);
                    
                    if (($tmpKey !== false) &&
                        ($tmpKey < count($resultIDList)))
                    {
                        //$dataRow++;
                        
                        $feedSubTestNameString .= "\"" . $subTestName . "\",2\n";

                        $feedSubTestDataString .= "" . $resultIDList[$tmpKey] . ",\"" . $subTestName . "\"," .
                                                  $dataValue . ", " . $testCaseID . "\n";
                        
                    }
                }
            }
        }
        
    }
    fclose($handle);
    
    swtFeedData($db, $feedSubTestNameString, $feedSubTestDataString, $testName);

    $curTestID = 0;
    $nextSubTestID = 0;
    $nextLineID = 0;
    $curFileLineNum = 0;
    
    // set result finished
    if (count($resultIDList) > 0)
    {
        $resultIDListString = implode(",", $resultIDList);
        
        //echo "--" . $resultIDListString;
        
        $params1 = array();
        $sql1 = "UPDATE mis_table_result_list " .
                "SET result_state = \"1\" " .
                "WHERE (result_state=\"4\") AND (result_id IN (" . $resultIDListString . "))";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3" . $db->getError()[2];
            return -1;
        }
    }

    return 1;
}

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

// start parse, above are globals & functions
if (strlen($logFolderName) == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input folder name invalid";
    echo json_encode($returnMsg);
    return;
}

$t1 = $logStoreDir . "/" . $logFolderName . "/" . $defaultInfoFileName;
if (file_exists($t1) == false)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "default info json file missing";
    echo json_encode($returnMsg);
    return;
}
$json = file_get_contents($t1);
$defaultInfo = json_decode($json, true);

$cardFolderList = glob($logStoreDir . "/" . $logFolderName . "/*", GLOB_ONLYDIR);

if (count($cardFolderList) == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input folder is empty";
    echo json_encode($returnMsg);
    return;
}

$cardNameList = array();

//$batchID = -1;

//$resultFileNum = 0
if ($resultFileNum == 0)
{
    // get result file num, to show parsing percent
    foreach ($cardFolderList as $tmpPath)
    {
        $t1 = $tmpPath . "/" . $machineInfoFileName;
        if (file_exists($t1) == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "machine info json file missing, under folder: " . $tmpPath;
            break;
        }
        
        $pieceFolderList = glob($tmpPath . "/*", GLOB_ONLYDIR);
        
        if (count($pieceFolderList) == 0)
        {
            // no piece folder
            // maybe log files not in piece folder
            $t1 = $tmpPath . "/" . $targetLogFileName;
            $tmpResult = -1;
            if (file_exists($t1))
            {
                $tmpResult = 1;
            }
            if ($tmpResult == -1)
            {
                $t1 = $tmpPath . "/" . $targetLogFileName2;
            }
            if (file_exists($t1))
            {
                $tmpResult = 1;
            }
            if ($tmpResult == 1)
            {
                $resultFileNum++;
            }
        }
        else
        {
            // log file in piece folders
            foreach ($pieceFolderList as $piecePath)
            {
                $t1 = $piecePath . "/" . $targetLogFileName;
                $tmpResult = -1;
                if (file_exists($t1))
                {
                    $tmpResult = 1;
                }
                if ($tmpResult == -1)
                {
                    $t1 = $piecePath . "/" . $targetLogFileName2;
                }
                if (file_exists($t1))
                {
                    $tmpResult = 1;
                }
                if ($tmpResult == 1)
                {
                    $resultFileNum++;
                }
            }
        }
    }

}

if ($resultFileNum == 0)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "no valid result files found under this path";
    echo json_encode($returnMsg);
    return;
}

$curResultFileID = 0;
$parseFinished = 0;
$parseFolderNum = 0;
$curFileLineNum = 0;
foreach ($cardFolderList as $tmpPath)
{
    $machineID = -1;
    $t1 = $tmpPath . "/" . $machineInfoFileName;
    if (file_exists($t1))
    {
        $machineID = swtGetMachineID($t1);
    }
    else
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "machine info json file missing, under folder: " . $tmpPath;
        break;
    }
    
    if ($machineID == -1)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "invalid mahine info";
        break;
    }
    
    $returnMsg["machineID"] = $machineID;
    
    $pieceFolderList = glob($tmpPath . "/*", GLOB_ONLYDIR);
    
    if (count($pieceFolderList) == 0)
    {
        // no piece folder
        // maybe log files not in piece folder
        $t1 = $tmpPath . "/" . $targetLogFileName;
        $tmpResult = -1;
        if (file_exists($t1))
        {
            $tmpResult = 1;
        }
        if ($tmpResult == -1)
        {
            $t1 = $tmpPath . "/" . $targetLogFileName2;
        }
        if (file_exists($t1))
        {
            $tmpResult = 1;
        }
        if ($tmpResult == 1)
        {
            if (($curResultFileID++) >= $nextResultFileID)
            {
                $curFileLineNum = swtGetFileLinesNum($t1);
                $tmpResult = swtParseLogFile($t1, $machineID);
                if ($tmpResult == -1)
                {
                    break;
                }
                $nextResultFileID++;
            }
        }
        if ($tmpResult == -1)
        {
            break;
        }
    }
    else
    {
        // log file in piece folders
        foreach ($pieceFolderList as $piecePath)
        {
            $t1 = $piecePath . "/" . $targetLogFileName;
            $tmpResult = -1;
            if (file_exists($t1))
            {
                $tmpResult = 1;
            }
            if ($tmpResult == -1)
            {
                $t1 = $piecePath . "/" . $targetLogFileName2;
            }
            if (file_exists($t1))
            {
                $tmpResult = 1;
            }
            if ($tmpResult == 1)
            {
                if (($curResultFileID++) >= $nextResultFileID)
                {
                    $curFileLineNum = swtGetFileLinesNum($t1);
                    $tmpResult = swtParseLogFile($t1, $machineID);
                    $returnMsg["tmpResult"] = $tmpResult;
                    if ($tmpResult == -1)
                    {
                        break;
                    }
                    $nextResultFileID++;
                }
            }
            if ($tmpResult == -1)
            {
                break;
            }
        }
        if ($tmpResult == -1)
        {
            break;
        }
    }
    //$nextLineID = 0;
    
    $returnMsg["check"] = $parseFolderNum;
    $parseFolderNum++;
    //$returnMsg["tmpResult"] = $tmpResult;
}

if ($parseFolderNum >= count($cardFolderList))
{
    $parseFinished = 1;
}

$returnMsg["curTestID"] = $curTestID;
$returnMsg["nextSubTestID"] = $nextSubTestID;
$returnMsg["nextLineID"] = $nextLineID;
$returnMsg["curFileLineNum"] = $curFileLineNum;
$returnMsg["nextResultFileID"] = $nextResultFileID;
$returnMsg["resultFileNum"] = $resultFileNum;
$returnMsg["parseFinished"] = $parseFinished;
$returnMsg["batchID"] = $batchID;

// set batch finished
if (($batchID       != -1) &&
    ($parseFinished == 1))
{
    // all tasks of this batch are finished
    $db = new CPdoMySQL();
    if ($db->getError() != null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "can't reach mysql server";
        echo json_encode($returnMsg);
        return;
    }
    $params1 = array($batchID);
    $sql1 = "UPDATE mis_table_batch_list " .
            "SET batch_state = \"1\" " .
            "WHERE (batch_state=\"4\") AND (batch_id=?)";
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #3" . $db->getError()[2];
        echo json_encode($returnMsg);
        return;
    }
}

echo json_encode($returnMsg);

?>