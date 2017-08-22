<?php

include_once __DIR__ . "/../configuration/swtMISConst.php";
//include_once "genfuncs.php";
include_once __DIR__ . "/../generalLibs/dopdo.php";
include_once __DIR__ . "/../generalLibs/swtCommonLib.php";
include_once __DIR__ . "/../generalLibs/genfuncs.php";

class CClientHeartBeat
{
	public $dbResult = null;
    
	public function __construct()
	{
		
	}
	public function __destruct()
	{
        
    }
    
	public function parseClientCmd($_info)
	{
        $clientCmd = intval($_info["clientCmd"]);
        $machineID = isset($_info["machineID"]) ? intval($_info["machineID"]) : -1;
        $returnMsg = array();
        $returnMsg["serverCmd"] = serverDoNothing;
        switch ($clientCmd)
        {
            case clientSendHeartBeat:
            {
                $tmpMsg = $this->updateHeartBeat($_info);
                $returnMsg["serverCmd"] = $tmpMsg["serverCmd"];
                $returnMsg["machineID"] = $machineID;
                $returnMsg["errorMsg"] = isset($tmpMsg["errorMsg"]) ? $tmpMsg["errorMsg"] : "";
                break;
            }
            case clientSendMachineInfo:
            {
                $tmpMsg = $this->updateMachineInfo($_info);
                if ($tmpMsg["errorCode"] == 1)
                {
                    $returnMsg["serverCmd"] = serverReturnHeartBeatSuccess;
                    $returnMsg["machineID"] = $tmpMsg["machineID"];
                    $returnMsg["errorMsg"] = isset($tmpMsg["errorMsg"]) ? $tmpMsg["errorMsg"] : "";
                }
                else
                {
                    $returnMsg["serverCmd"] = serverReturnHeartBeatNotSuccess;
                    $returnMsg["machineID"] = -1;
                    $returnMsg["errorMsg"] = isset($tmpMsg["errorMsg"]) ? $tmpMsg["errorMsg"] : "";
                }
                break;
            }
        }
        return $returnMsg;
    }
    
	public function updateHeartBeat($_info)
	{
        $machineID = intval($_info["machineID"]);
        $returnMsg = array();
        if ($machineID == -1)
        {
            // machine first online
            $returnMsg["serverCmd"] = serverRequireClientMachineInfo;
            $returnMsg["errorMsg"] = "machine id not valid";
        }
        else
        {
            // update machine heart beat time to mysql
            $returnMsg["serverCmd"] = serverDoNothing;
            $returnMsg["errorMsg"] = "server requires nothing";
            $db = new CPdoMySQL();

            if ($db->getError() != null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "can't reach mysql server";
                return $returnMsg;
            }
            
            $tmpIP = swt_get_client_ip();

            $params1 = array($machineID);
                    
            $sql1 = "SELECT COUNT(*) FROM mis_table_machine_health_info " .
                    "WHERE machine_id=?";
                    
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #1";
                return $returnMsg;
            }
            $row1 = $db->fetchRow();
            if ($row1 == false)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #2";
                return $returnMsg;
            }
            if ($row1[0] == 0)
            {
                $params1 = array($machineID, $tmpIP);
                $sql1 = "INSERT INTO mis_table_machine_health_info " .
                        "(machine_id, machine_state, machine_ip, heartbeat_time) " .
                        "VALUES (?, \"1\", ?, NOW())";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #3a";
                    return $returnMsg;
                }
            }
            else
            {
                $sql1 = "UPDATE mis_table_machine_health_info " .
                        "SET machine_ip=?, heartbeat_time=NOW() WHERE machine_id=?";
                $params1 = array($tmpIP, $machineID);
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #4a, " . $db->getError()[2];
                    return $returnMsg;
                }
            }
        }
        return $returnMsg;
	}
	
	public function updateMachineInfo($_info)
	{
        $machineName   = $_info["machineName"];
        $videoCardName = $_info["videoCardName"];
        $cpuName       = $_info["cpuName"];
        $systemName    = $_info["systemName"];
        $memoryName    = $_info["memoryName"];
        $chipsetName   = $_info["chipsetName"];
        $mainLineName  = $_info["mainLineName"];
        $sClockName    = $_info["sClockName"];
        $mClockName    = $_info["mClockName"];
        $gpuMemName    = $_info["gpuMemName"];
        $machineID = -1;
        
        $returnMsg = array();
        
        $db = new CPdoMySQL();

        if ($db->getError() != null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "can't reach mysql server";
            return $returnMsg;
        }
        
        $envTypeList = array(5, 0, 1, 2, 6, 7, 3, 9, 10, 11);
        $envNameList = array($machineName,
                             $videoCardName,
                             $cpuName,
                             $systemName,
                             $memoryName,
                             $chipsetName,
                             $mainLineName,
                             $sClockName,
                             $mClockName,
                             $gpuMemName);
        $envIDList = array();

        $params1 = array($machineName,
                         $videoCardName,
                         $cpuName,
                         $systemName,
                         $memoryName,
                         $chipsetName,
                         $mainLineName,
                         $sClockName,
                         $mClockName,
                         $gpuMemName);
                
        $sql1 = "SELECT " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"5\" AND tei.env_name=?) AS machineNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"0\" AND tei.env_name=?) AS videoCardNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"1\" AND tei.env_name=?) AS cpuNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"2\" AND tei.env_name=?) AS systemNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"6\" AND tei.env_name=?) AS memoryNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"7\" AND tei.env_name=?) AS chipsetNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"3\" AND tei.env_name=?) AS mainLineNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"9\" AND tei.env_name=?) AS sClockNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"10\" AND tei.env_name=?) AS mClockNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"11\" AND tei.env_name=?) AS gpuMemNameID " .
                "FROM mis_table_environment_info tei";
                
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #1";
            return $returnMsg;
        }
        $row1 = $db->fetchRow();
        $b1 = false;
        // check, if new names occur
        if ($row1 == false)
        {
            $b1 = true;
            for ($i = 0; $i < count($envNameList); $i++)
            {
                $envIDList[$i] = -1;
            }
        }
        else
        {
            for ($i = 0; $i < count($envNameList); $i++)
            {
                if ($row1[$i] == NULL)
                {
                    $b1 = true;
                    $envIDList[$i] = -1;
                }
                else
                {
                    $envIDList[$i] = $row1[$i];
                }
            }
        }
        if ($b1 == false)
        {
            // if all names are not new, get machine id
            $params1 = ($envIDList);
            $sql1 = "SELECT machine_id " .
                    "FROM mis_table_machine_info WHERE " .
                    "name_id=? AND card_id=? AND cpu_id=? AND sys_id=? " .
                    "AND mem_id=? AND chipset_id=? AND ml_id=? " .
                    "AND s_clock_id=? AND m_clock_id=? AND gpu_mem_id=?";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #5";
                return $returnMsg;
            }
            $row1 = $db->fetchRow();
            if ($row1 == false)
            {
                $b1 = true;
            }
            $machineID = $row1[0];
        }
        
        if ($b1)
        {
            // no such machine ever online
            // insert new
            for ($i = 0; $i < count($envNameList); $i++)
            {
                if ($envIDList[$i] != -1)
                {
                    continue;
                }
                $params1 = array($envTypeList[$i], $envNameList[$i]);
                $sql1 = "SELECT env_id FROM mis_table_environment_info WHERE env_type=? AND env_name=?";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #2";
                    return $returnMsg;
                }
                $row1 = $db->fetchRow();
                if ($row1 == false)
                {
                    $params1 = array($envTypeList[$i], $envNameList[$i]);
                    $sql1 = "INSERT IGNORE INTO mis_table_environment_info " .
                            "(env_type, env_name) VALUES (?, ?)";
                    if ($db->QueryDB($sql1, $params1) == null)
                    {
                        $returnMsg["errorCode"] = 0;
                        $returnMsg["errorMsg"] = "query mysql table failed #3";
                        return $returnMsg;
                    }
                    $envIDList[$i] = $db->getInsertID();
                }
                else
                {
                    $envIDList[$i] = $row1[0];
                }
            }
            
            $params1 = ($envIDList);
            $sql1 = "INSERT INTO mis_table_machine_info " .
                    "(name_id, card_id, cpu_id, sys_id, mem_id, chipset_id, ml_id, s_clock_id, m_clock_id, gpu_mem_id, insert_time) " .
                    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #4";
                return $returnMsg;
            }
            $machineID = $db->getInsertID();
        }
        
        $returnMsg["errorCode"] = 1;
        $returnMsg["errorMsg"] = "get machine id success";
        $returnMsg["machineID"] = $machineID;
        return $returnMsg;
	}
    
	public function updateMachineInfo2($_runLogPath, $_machineName)
	{
        $tmpInfo = array();
        
        $tmpInfo["machineName"]   = "";
        $tmpInfo["videoCardName"] = "";        
        $tmpInfo["cpuName"]       = "";        
        $tmpInfo["systemName"]    = "";        
        $tmpInfo["memoryName"]    = "";        
        $tmpInfo["chipsetName"]   = "";        
        $tmpInfo["mainLineName"]  = "";        
        $tmpInfo["sClockName"]    = "";        
        $tmpInfo["mClockName"]    = "";        
        $tmpInfo["gpuMemName"]    = "";        
        
        $tmpNameList = array("machineName",
                             "videoCardName",
                             "cpuName",
                             "systemName",
                             "memoryName",
                             "chipsetName",
                             "mainLineName",
                             "sClockName",
                             "mClockName",
                             "gpuMemName");
        $tmpTagList = array("",
                            "GPU:",
                            "CPU:",
                            "Operating System:",
                            "System Memory:",
                            "",
                            "",
                            "",
                            "",
                            "Video Memory:");
        
        $machineID = -1;
        
        $returnMsg = array();
        
        $readFileLineNum = 20;
        
        $handle = @fopen($_runLogPath, "r");
        if ($handle == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "can't find target runlog.txt";
            return $returnMsg;
        }
        
        $fileLineList = array();
        
        for ($i = 0; $i < $readFileLineNum; $i++)
        {
            if (feof($handle))
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "file incomplete runlog.txt";
                return $returnMsg;
            }
            $t1 = fgets($handle);
            if ($t1 != false)
            {
                array_push($fileLineList, $t1);
            }
        }
        
        fclose($handle);
        
        for ($i = 0; $i < count($fileLineList); $i++)
        {
            for ($j = 0; $j < count($tmpTagList); $j++)
            {
                if (strlen($tmpTagList[$j]) == 0)
                {
                    continue;
                }
                $tmpPos = strpos($fileLineList[$i], $tmpTagList[$j]);
                if ($tmpPos !== false)
                {
                    $tmpVal = substr($fileLineList[$i], $tmpPos + strlen($tmpTagList[$j]));
                    $tmpVal = trim($tmpVal);
                    
                    $tmpInfo[$tmpNameList[$j]] = $tmpVal;
                    break;
                }
            }
        }
        
        $machineName   = cleanFolderName($_machineName, 256); //$tmpInfo["machineName"];
        //$videoCardName = cleanFolderName($tmpInfo["videoCardName"], 256);
        $videoCardName = cleanFolderName($_machineName, 256);
        $cpuName       = cleanFolderName($tmpInfo["cpuName"], 256);
        $systemName    = cleanFolderName($tmpInfo["systemName"], 256);
        $memoryName    = cleanFolderName($tmpInfo["memoryName"], 256);
        $chipsetName   = cleanFolderName($tmpInfo["chipsetName"], 256);
        $mainLineName  = cleanFolderName($tmpInfo["mainLineName"], 256);
        $sClockName    = cleanFolderName($tmpInfo["sClockName"], 256);
        $mClockName    = cleanFolderName($tmpInfo["mClockName"], 256);
        $gpuMemName    = cleanFolderName($tmpInfo["gpuMemName"], 256);
        
        $db = new CPdoMySQL();

        if ($db->getError() != null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "can't reach mysql server";
            return $returnMsg;
        }
        
        $envTypeList = array(5, 0, 1, 2, 6, 7, 3, 9, 10, 11);
        $envNameList = array($machineName,
                             $videoCardName,
                             $cpuName,
                             $systemName,
                             $memoryName,
                             $chipsetName,
                             $mainLineName,
                             $sClockName,
                             $mClockName,
                             $gpuMemName);
        $envIDList = array();

        $params1 = array($machineName,
                         $videoCardName,
                         $cpuName,
                         $systemName,
                         $memoryName,
                         $chipsetName,
                         $mainLineName,
                         $sClockName,
                         $mClockName,
                         $gpuMemName);
                
        $sql1 = "SELECT " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"5\" AND tei.env_name=?) AS machineNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"0\" AND tei.env_name=?) AS videoCardNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"1\" AND tei.env_name=?) AS cpuNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"2\" AND tei.env_name=?) AS systemNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"6\" AND tei.env_name=?) AS memoryNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"7\" AND tei.env_name=?) AS chipsetNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"3\" AND tei.env_name=?) AS mainLineNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"9\" AND tei.env_name=?) AS sClockNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"10\" AND tei.env_name=?) AS mClockNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"11\" AND tei.env_name=?) AS gpuMemNameID " .
                "FROM mis_table_environment_info tei";
                
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #1";
            return $returnMsg;
        }
        $row1 = $db->fetchRow();
        $b1 = false;
        // check, if new names occur
        if ($row1 == false)
        {
            $b1 = true;
            for ($i = 0; $i < count($envNameList); $i++)
            {
                $envIDList[$i] = -1;
            }
        }
        else
        {
            for ($i = 0; $i < count($envNameList); $i++)
            {
                if ($row1[$i] == NULL)
                {
                    $b1 = true;
                    $envIDList[$i] = -1;
                }
                else
                {
                    $envIDList[$i] = $row1[$i];
                }
            }
        }
        if ($b1 == false)
        {
            // if all names are not new, get machine id
            $params1 = ($envIDList);
            $sql1 = "SELECT machine_id " .
                    "FROM mis_table_machine_info WHERE " .
                    "name_id=? AND card_id=? AND cpu_id=? AND sys_id=? " .
                    "AND mem_id=? AND chipset_id=? AND ml_id=? " .
                    "AND s_clock_id=? AND m_clock_id=? AND gpu_mem_id=?";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #5";
                return $returnMsg;
            }
            $row1 = $db->fetchRow();
            if ($row1 == false)
            {
                $b1 = true;
            }
            $machineID = $row1[0];
        }
        
        if ($b1)
        {
            // no such machine ever online
            // insert new
            for ($i = 0; $i < count($envNameList); $i++)
            {
                if ($envIDList[$i] != -1)
                {
                    continue;
                }
                $params1 = array($envTypeList[$i], $envNameList[$i]);
                $sql1 = "SELECT env_id FROM mis_table_environment_info WHERE env_type=? AND env_name=?";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #2";
                    return $returnMsg;
                }
                $row1 = $db->fetchRow();
                if ($row1 == false)
                {
                    $params1 = array($envTypeList[$i], $envNameList[$i]);
                    $sql1 = "INSERT IGNORE INTO mis_table_environment_info " .
                            "(env_type, env_name) VALUES (?, ?)";
                    if ($db->QueryDB($sql1, $params1) == null)
                    {
                        $returnMsg["errorCode"] = 0;
                        $returnMsg["errorMsg"] = "query mysql table failed #3";
                        return $returnMsg;
                    }
                    $envIDList[$i] = $db->getInsertID();
                }
                else
                {
                    $envIDList[$i] = $row1[0];
                }
            }
            
            $params1 = ($envIDList);
            $sql1 = "INSERT INTO mis_table_machine_info " .
                    "(name_id, card_id, cpu_id, sys_id, mem_id, chipset_id, ml_id, s_clock_id, m_clock_id, gpu_mem_id, insert_time) " .
                    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #4";
                return $returnMsg;
            }
            $machineID = $db->getInsertID();
        }
        
        $returnMsg["errorCode"] = 1;
        $returnMsg["errorMsg"] = "get machine id success";
        $returnMsg["machineID"] = $machineID;
        $returnMsg["machineInfo"] = $tmpInfo;
        return $returnMsg;
	}
    
	public function updateMachineInfo3($_tmpInfo, $_machineName)
	{
        $tmpInfo = $_tmpInfo;
        
        //$machineName   = cleanFolderName($tmpInfo["machineName"], 256);
        //$videoCardName = cleanFolderName($tmpInfo["videoCardName"], 256);
        $machineName   = cleanFolderName($_machineName, 256);
        $videoCardName = cleanFolderName($_machineName, 256);
        $cpuName       = cleanFolderName($tmpInfo["cpuName"], 256);
        $systemName    = cleanFolderName($tmpInfo["systemName"], 256);
        $memoryName    = cleanFolderName($tmpInfo["memoryName"], 256);
        $chipsetName   = cleanFolderName($tmpInfo["chipsetName"], 256);
        $mainLineName  = cleanFolderName($tmpInfo["mainLineName"], 256);
        $sClockName    = cleanFolderName($tmpInfo["sClockName"], 256);
        $mClockName    = cleanFolderName($tmpInfo["mClockName"], 256);
        $gpuMemName    = cleanFolderName($tmpInfo["gpuMemName"], 256);
        
        $db = new CPdoMySQL();

        if ($db->getError() != null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "can't reach mysql server";
            return $returnMsg;
        }
        
        $envTypeList = array(5, 0, 1, 2, 6, 7, 3, 9, 10, 11);
        $envNameList = array($machineName,
                             $videoCardName,
                             $cpuName,
                             $systemName,
                             $memoryName,
                             $chipsetName,
                             $mainLineName,
                             $sClockName,
                             $mClockName,
                             $gpuMemName);
        $envIDList = array();

        $params1 = array($machineName,
                         $videoCardName,
                         $cpuName,
                         $systemName,
                         $memoryName,
                         $chipsetName,
                         $mainLineName,
                         $sClockName,
                         $mClockName,
                         $gpuMemName);
                
        $sql1 = "SELECT " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"5\" AND tei.env_name=?) AS machineNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"0\" AND tei.env_name=?) AS videoCardNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"1\" AND tei.env_name=?) AS cpuNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"2\" AND tei.env_name=?) AS systemNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"6\" AND tei.env_name=?) AS memoryNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"7\" AND tei.env_name=?) AS chipsetNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"3\" AND tei.env_name=?) AS mainLineNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"9\" AND tei.env_name=?) AS sClockNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"10\" AND tei.env_name=?) AS mClockNameID, " .
                "(SELECT tei.env_id FROM mis_table_environment_info tei WHERE tei.env_type=\"11\" AND tei.env_name=?) AS gpuMemNameID " .
                "FROM mis_table_environment_info tei";
                
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #1";
            return $returnMsg;
        }
        $row1 = $db->fetchRow();
        $b1 = false;
        // check, if new names occur
        if ($row1 == false)
        {
            $b1 = true;
            for ($i = 0; $i < count($envNameList); $i++)
            {
                $envIDList[$i] = -1;
            }
        }
        else
        {
            for ($i = 0; $i < count($envNameList); $i++)
            {
                if ($row1[$i] == NULL)
                {
                    $b1 = true;
                    $envIDList[$i] = -1;
                }
                else
                {
                    $envIDList[$i] = $row1[$i];
                }
            }
        }
        if ($b1 == false)
        {
            // if all names are not new, get machine id
            $params1 = ($envIDList);
            $sql1 = "SELECT machine_id " .
                    "FROM mis_table_machine_info WHERE " .
                    "name_id=? AND card_id=? AND cpu_id=? AND sys_id=? " .
                    "AND mem_id=? AND chipset_id=? AND ml_id=? " .
                    "AND s_clock_id=? AND m_clock_id=? AND gpu_mem_id=?";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #5";
                return $returnMsg;
            }
            $row1 = $db->fetchRow();
            if ($row1 == false)
            {
                $b1 = true;
            }
            $machineID = $row1[0];
        }
        
        if ($b1)
        {
            // no such machine ever online
            // insert new
            for ($i = 0; $i < count($envNameList); $i++)
            {
                if ($envIDList[$i] != -1)
                {
                    continue;
                }
                $params1 = array($envTypeList[$i], $envNameList[$i]);
                $sql1 = "SELECT env_id FROM mis_table_environment_info WHERE env_type=? AND env_name=?";
                if ($db->QueryDB($sql1, $params1) == null)
                {
                    $returnMsg["errorCode"] = 0;
                    $returnMsg["errorMsg"] = "query mysql table failed #2";
                    return $returnMsg;
                }
                $row1 = $db->fetchRow();
                if ($row1 == false)
                {
                    $params1 = array($envTypeList[$i], $envNameList[$i]);
                    $sql1 = "INSERT IGNORE INTO mis_table_environment_info " .
                            "(env_type, env_name) VALUES (?, ?)";
                    if ($db->QueryDB($sql1, $params1) == null)
                    {
                        $returnMsg["errorCode"] = 0;
                        $returnMsg["errorMsg"] = "query mysql table failed #3";
                        return $returnMsg;
                    }
                    $envIDList[$i] = $db->getInsertID();
                }
                else
                {
                    $envIDList[$i] = $row1[0];
                }
            }
            
            $params1 = ($envIDList);
            $sql1 = "INSERT INTO mis_table_machine_info " .
                    "(name_id, card_id, cpu_id, sys_id, mem_id, chipset_id, ml_id, s_clock_id, m_clock_id, gpu_mem_id, insert_time) " .
                    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            if ($db->QueryDB($sql1, $params1) == null)
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "query mysql table failed #4";
                return $returnMsg;
            }
            $machineID = $db->getInsertID();
        }
        
        $returnMsg["errorCode"] = 1;
        $returnMsg["errorMsg"] = "get machine id success";
        $returnMsg["machineID"] = $machineID;
        //$returnMsg["machineInfo"] = $tmpInfo;
        return $returnMsg;
	}
    
	public function getMachineInfoWithoutJson($_machineFolderPath, $_machineName)
	{
        $tmpInfo = array();
        
        $tmpInfo["machineName"]   = "";
        $tmpInfo["videoCardName"] = "";        
        $tmpInfo["cpuName"]       = "";        
        $tmpInfo["systemName"]    = "";        
        $tmpInfo["memoryName"]    = "";        
        $tmpInfo["chipsetName"]   = "";        
        $tmpInfo["mainLineName"]  = "";        
        $tmpInfo["sClockName"]    = "";        
        $tmpInfo["mClockName"]    = "";        
        $tmpInfo["gpuMemName"]    = "";        
        
        $tmpNameList = array("machineName",
                             "videoCardName",
                             "cpuName",
                             "systemName",
                             "memoryName",
                             "chipsetName",
                             "mainLineName",
                             "sClockName",
                             "mClockName",
                             "gpuMemName");
        $tmpTagList = array("",
                            "GPU:",
                            "CPU:",
                            "Operating System:",
                            "System Memory:",
                            "",
                            "",
                            "",
                            "",
                            "Video Memory:");
        
        $machineID = -1;
        
        $returnMsg = array();
        
        $readFileLineNum = 20;
        
        $runLogFileName = "runlog.txt";
        
        //$machineName = basename($_machineFolderPath);
        $machineName = $_machineName;
        
        $pieceFolderList = glob($_machineFolderPath . "/*", GLOB_ONLYDIR);
        $runLogPath = "";
        
        if (count($pieceFolderList) == 0)
        {
            // no piece folder
            // maybe log files not in piece folder
            $t2 = $_machineFolderPath . "/" . $runLogFileName;
            
            if (file_exists($t2))
            {
                $runLogPath = $t2;
            }
        }
        else
        {
            // log file in piece folders
            foreach ($pieceFolderList as $piecePath)
            {
                $t2 = $piecePath . "/" . $runLogFileName;

                if (file_exists($t2))
                {
                    $runLogPath = $t2;
                    break;
                }
            }
        }
        if (strlen($runLogPath) == 0)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "file not found runlog.txt";
            return $returnMsg;
        }
        
        $handle = @fopen($runLogPath, "r");
        if ($handle == false)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "can't find target runlog.txt";
            return $returnMsg;
        }
        
        $fileLineList = array();
        
        for ($i = 0; $i < $readFileLineNum; $i++)
        {
            if (feof($handle))
            {
                $returnMsg["errorCode"] = 0;
                $returnMsg["errorMsg"] = "file incomplete runlog.txt";
                return $returnMsg;
            }
            $t1 = fgets($handle);
            if ($t1 != false)
            {
                array_push($fileLineList, $t1);
            }
        }
        
        fclose($handle);
        
        for ($i = 0; $i < count($fileLineList); $i++)
        {
            for ($j = 0; $j < count($tmpTagList); $j++)
            {
                if (strlen($tmpTagList[$j]) == 0)
                {
                    continue;
                }
                $tmpPos = strpos($fileLineList[$i], $tmpTagList[$j]);
                if ($tmpPos !== false)
                {
                    $tmpVal = substr($fileLineList[$i], $tmpPos + strlen($tmpTagList[$j]));
                    $tmpVal = trim($tmpVal);
                    
                    $tmpInfo[$tmpNameList[$j]] = cleanFolderName($tmpVal, 256);
                    break;
                }
            }
        }
        
        //$tmpInfo["machineName"] = $machineName;
        $machineName   = cleanFolderName($machineName, 256); //$tmpInfo["machineName"];
        //$videoCardName = cleanFolderName($tmpInfo["videoCardName"], 256);
        $videoCardName = cleanFolderName($machineName, 256);
        $cpuName       = cleanFolderName($tmpInfo["cpuName"], 256);
        $systemName    = cleanFolderName($tmpInfo["systemName"], 256);
        $memoryName    = cleanFolderName($tmpInfo["memoryName"], 256);
        $chipsetName   = cleanFolderName($tmpInfo["chipsetName"], 256);
        $mainLineName  = cleanFolderName($tmpInfo["mainLineName"], 256);
        $sClockName    = cleanFolderName($tmpInfo["sClockName"], 256);
        $mClockName    = cleanFolderName($tmpInfo["mClockName"], 256);
        $gpuMemName    = cleanFolderName($tmpInfo["gpuMemName"], 256);
        
        $tmpInfo["videoCardNameReal"] = $tmpInfo["videoCardName"];
        $tmpInfo["videoCardName"] = $machineName;
        
        $returnMsg["errorCode"] = 1;
        $returnMsg["errorMsg"] = "get machine info success";
        $returnMsg = array_merge($returnMsg, $tmpInfo);
        $returnMsg["machineInfo"] = $tmpInfo;
        return $returnMsg;
    }
    
	public function reportError($_info)
	{
    }
}

?>