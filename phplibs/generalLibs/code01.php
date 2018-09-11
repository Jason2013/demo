<?php

include_once __dir__ . "/../configuration/swtErrorMsg.php";
include_once __dir__ . "/../configuration/swtUIStrings.php";

//$db_server = "127.0.0.1";
//$db_username = "root";
//$db_password = "dgxqh523120";
//$db_dbname = "db_mis";

//$db_server = "ATLVMYSQLDP21";
$db_server = "Srdcvmysqldp1";
$db_username = "davychen";
$db_password = "davychen7$";
$db_dbname = "db_gfxbench";


$db_table_surname = "mis_table";
$db_table_data_surname = "mis_table_data";

$db_create_db01 = "CREATE DATABASE IF NOT EXISTS " . $db_dbname;
$db_create_db02 = "DROP DATABASE IF EXISTS " . $db_dbname;
$db_use_db01 = "USE " . $db_dbname;

// static tables
$db_create_table01 = array();

// test name related, if new test name appears, new tables will be created
$db_create_table02 = array();

function db_get_tablename()
{
    global $db_create_table01;
    global $db_table_surname;
    return ($db_table_surname . sprintf("%05d", count($db_create_table01)));
}

function db_get_tablename_from_index($index)
{
    global $db_create_table01;
    global $db_table_surname;
    if ($index >= count($db_create_table01))
    {
        die($swtErrorMsg->getErrorString(3));
    }
    return ($db_table_surname . sprintf("%05d", $index));
}

function db_get_tablename_from_name_and_index($tableName, $tableID)
{
    return ($tableName . sprintf("%05d", $tableID));
}

$swtTestBatchStateString = array("submitted",
                                 "finished",
                                 "skipped",
                                 "time out",
                                 "reserved");
                                 
$swtTestBatchGroupString = array("out user",
                                 "routine report",
                                 "temp report",
                                 "shaderBench",
                                 "skynet report");
                                 
$swtTestBatchGroupStringEx = array("report slot01",
                                   "report slot02",
                                   "report slot03",
                                   "report slot04",
                                   "report slot05",
                                   "report slot06",
                                   "report slot07",
                                   "report slot08",
                                   "report slot09",
                                   "report slot10");

// show tables like 'mis_table_data_test_%'
// show columns from tablename
                    
$dataTables = array("mis_table_data_test_alu",
                    "mis_table_data_test_branching",
                    "mis_table_data_test_cbupdate",
                    "mis_table_data_test_coherencystall",
                    "mis_table_data_test_colorclear",
                    "mis_table_data_test_computetest",
                    "mis_table_data_test_csfillrate",
                    "mis_table_data_test_csmandelbrot",
                    "mis_table_data_test_depthclear",
                    "mis_table_data_test_depthop",
                    "mis_table_data_test_fillrate",
                    "mis_table_data_test_gsaluheavy",
                    "mis_table_data_test_gspointampl",
                    "mis_table_data_test_gspointsprites",
                    "mis_table_data_test_gstriampl",
                    "mis_table_data_test_hwcontextroll",
                    "mis_table_data_test_multiplert",
                    "mis_table_data_test_oglstatevalidation",
                    "mis_table_data_test_primfilter",
                    "mis_table_data_test_primsetup",
                    "mis_table_data_test_ps_postprocess",
                    "mis_table_data_test_quadtess",
                    "mis_table_data_test_asynccompute",
                    "mis_table_data_test_rescopy",
                    "mis_table_data_test_resolve",
                    "mis_table_data_test_shadercompile",
                    "mis_table_data_test_smallbatch",
                    "mis_table_data_test_texfetch",
                    "mis_table_data_test_triangletest",
                    "mis_table_data_test_trisizefill",
                    "mis_table_data_test_uniginetess",
                    "mis_table_data_test_vertexfetch");
                    
                    
foreach ($dataTables as $tmpName)
{
    //$t1 = "DROP TABLE IF EXISTS " . $tmpName . ";";
    //array_push($db_create_table01, $t1);
    
    //$t1 = "ALTER TABLE " . $tmpName . " ADD test_case_id INT DEFAULT -1";
    //array_push($db_create_table01, $t1);
}
                                 
// mis_table_environment_info 0
//array_push($db_create_table01, "DROP TABLE IF EXISTS mis_table_environment_info");
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_environment_info " .
                               "( env_id INT UNSIGNED AUTO_INCREMENT, " .
                               "  env_name VARCHAR(128), " . // hardware name
                               "  env_type INT UNSIGNED, " .
                               "  PRIMARY KEY (env_id), " .
                               "  UNIQUE (env_name, env_type) " .
                               ")"); // 0 for video card, 
                                     // like fiji nano, baffin, NV 950, NV 980 Ti
                                     // 1 for cpu,        like intel i7
                                     // 2 for system name, like Win10 64
                                     // 3 for driver main line, like 16.20
                                     // 4 for umd name,  like DXC, DXCP, DX11, DX12, Vulkan
                                     // 5 for cumputer name
                                     // 6 for sys mem size, like 8GB
                                     // 7 for chipset
                                     // 8 for desktop mode, like 1024 X 768 X 32 X 60
                                     // 9 s_clock
                                     // 10 m_clock
                                     // 11 gpu_mem

//array_push($db_create_table01, "ALTER TABLE mis_table_test_info ADD test_filter VARCHAR(128)");
// mis_table_test_info 1
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_test_info " .
                               "( test_id INT UNSIGNED AUTO_INCREMENT, " .
                               "  test_name VARCHAR(128), " . // test name
                               "  test_type INT UNSIGNED," .
                               "  test_filter VARCHAR(128), " .
                               "  PRIMARY KEY (test_id), " .
                               "  UNIQUE (test_name, test_type) " .
                               " )"); // 0 for test name,     like DepthClear
                                      // 1 for sub test subject, like Format_DsSize_Samples
                                      // 2 for sub-test name,  
                                      // like D16_UNORM_1024x1024_1xAA
                                      // 3 for unit subject, like GInstr/s
                                      // 4 for group name of shaderBench
                                
// mis_table_path_info 2
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_path_info " .
                               "( path_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, " .
                               "  path_name VARCHAR(256) )");  // folder name
                               
// mis_table_machine_info 3
//array_push($db_create_table01, "ALTER TABLE mis_table_machine_info ADD COLUMN s_clock_id INT UNSIGNED AFTER ml_id;");
//array_push($db_create_table01, "ALTER TABLE mis_table_machine_info ADD COLUMN m_clock_id INT UNSIGNED AFTER s_clock_id;");
//array_push($db_create_table01, "ALTER TABLE mis_table_machine_info ADD COLUMN gpu_mem_id INT UNSIGNED AFTER m_clock_id;");
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_machine_info " .
                               "( machine_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, " .
                               "  name_id INT UNSIGNED, " .  // computer name index
                               "  card_id INT UNSIGNED, " .  // video card name index
                               "  cpu_id INT UNSIGNED,  " .  // cpu name index
                               "  sys_id INT UNSIGNED,  " .  // system name index
                               "  mem_id INT UNSIGNED,  " .  // system mem size index
                               "  chipset_id INT UNSIGNED, " . // main board chipset index
                               "  ml_id INT UNSIGNED,      " . // driver main line index
                               "  s_clock_id INT UNSIGNED, " . // gpu s_clock
                               "  m_clock_id INT UNSIGNED, " . // gpu m_clock
                               "  gpu_mem_id INT UNSIGNED, " . // gpu mem size
                               "  insert_time DATETIME )");  

// mis_table_result_list 4
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_result_list " .
                               "( result_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, " .
                               "  machine_id INT UNSIGNED, " .
                               "  batch_id INT UNSIGNED, " .
                               "  umd_id INT UNSIGNED,   " .   // driver name index, like DXC, DXCP, DX11, DX12, Vulkan
                               "  cl_value INT UNSIGNED, " .   // change list number
                               "  path_id INT UNSIGNED,  " .   // out of use, log file path
                               "  result_state INT UNSIGNED, " . // 0 for submitted, 1 for finished, 2 for skipped, 3 for time out, 4 for reserved
                               "  insert_time DATETIME )");

// mis_table_batch_list 5
//array_push($db_create_table01, "ALTER TABLE mis_table_batch_list ADD path_id INT UNSIGNED");
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_batch_list " .
                               "( batch_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, " .
                               "  insert_time DATETIME,     " . // submit time
                               "  batch_state INT UNSIGNED, " . // 0 for submitted, 1 for finished, 2 for skipped, 3 for time out
                               "  batch_group INT UNSIGNED, " . // 0 for outside user, 
                                                                // 1 for routine run every week
                                                                // 2 for temp report, 
                                                                // 3 for shaderBench report
                                                                // 4 for skynet report
                                                                // 100 - 109 report slot01 - report slot10
                               "  path_id INT UNSIGNED )");     // in use, log file path
//*/
// mis_table_task_list 6 
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_task_list " .
                               "( task_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, " .
                               "  result_id INT UNSIGNED,   " .
                               "  upload_id INT UNSIGNED,   " . // upload file path
                               "  insert_time DATETIME,     " . // submit time
                               "  task_state INT UNSIGNED )");  // 0 for submitted, 1 for finished, 2 for skipped, 3 for time out

// mis_table_average_test_data 7
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_average_test_data " .
                               "( data_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, " .
                               "  result_id INT UNSIGNED, " . // driver index 
                               "  test_id INT UNSIGNED,   " . // test index  
                               "  data_value FLOAT )");       // value

//array_push($db_create_table01, "DROP TABLE IF EXISTS mis_table_machine_health_info");
                               
// mis_table_machine_health_info 8
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_machine_health_info " .
                               "( info_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, " .
                               "  machine_id INT UNSIGNED,    " . // machine index 
                               "  machine_state INT UNSIGNED, " . // 0 for not in use, 1 for in use
                               "  machine_ip VARCHAR(32),     " . // ip address
                               "  heartbeat_time DATETIME )");
                               
// mis_table_log_info 9
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_log_info " .
                               "( log_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, " .
                               "  log_content VARCHAR(256), " .
                               "  insert_time DATETIME )");
                               
// mis_table_upload_info 10
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_upload_info " .
                               "( upload_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, " .
                               "  umd_id INT UNSIGNED,       " . // driver name index, like DXC, DXCP, DX11, DX12, Vulkan
                               "  upload_tocken VARCHAR(32), " . // tocken to state upload page visite
                               "  upload_path VARCHAR(256),  " .
                               "  insert_time DATETIME )");

//array_push($db_create_table01, "DROP TABLE IF EXISTS mis_table_test_subject_list");
//array_push($db_create_table01, "ALTER TABLE mis_table_test_subject_list ADD filter_num INT UNSIGNED");
// mis_table_test_subject_list 11
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_test_subject_list " .
                               "( list_id INT UNSIGNED AUTO_INCREMENT, " .
                               "  batch_id INT UNSIGNED, " . // 
                               "  test_id INT UNSIGNED, " .  // test name
                               "  subject_id INT UNSIGNED, " .  // sub test subject
                               "  unit_id INT UNSIGNED, " .
                               "  filter_num INT UNSIGNED, " .
                               "  PRIMARY KEY (list_id), " .
                               "  UNIQUE (batch_id, test_id, subject_id, unit_id) " .
                               ")");  // unit name

// array_push($db_create_table01, "DROP TABLE IF EXISTS mis_table_report_info");
// mis_table_report_info 12
/*
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_report_info " .
                               "( report_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, " .
                               "  batch_id INT UNSIGNED,       " .
                               "  gen_percent INT UNSIGNED,    " .
                               "  report_state INT UNSIGNED,   " . // 0 for submitted, 1 for finished, 2 for skipped, 3 for time out
                               "  report_token VARCHAR(32),    " .
                               "  finish_time DATETIME,        " .
                               "  insert_time DATETIME )");
//*/
// mis_table_result_file_info 12
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_result_file_info " .
                               "( result_file_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, " .
                               "  machine_id INT UNSIGNED,   " .
                               "  folder_id INT UNSIGNED,    " .
                               "  test_list TEXT,            " .
                               "  insert_time DATETIME )");
                               
// mis_table_user_info
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_user_info " .
                               "( user_id INT UNSIGNED AUTO_INCREMENT, " .
                               "  user_name VARCHAR(128), " .
                               "  pass_word VARCHAR(64), " .
                               "  nick_name VARCHAR(128), " .
                               "  user_type INT UNSIGNED, " .
                               "  create_time DATETIME, " .
                               "  login_time DATETIME, " .
                               "  login_ip VARCHAR(64), " .
                               "  PRIMARY KEY (user_id) " .
                               ")");
                               
// mis_table_user_batch_info
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_user_batch_info " .
                               "( info_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, " .
                               "  user_id INT UNSIGNED, " .
                               "  batch_id INT UNSIGNED, " .
                               "  insert_time DATETIME )");
                               
// runlog.txt tests runtime cost
array_push($db_create_table01, "CREATE TABLE IF NOT EXISTS mis_table_data_test_Statistics " .
                               "( data_id INT UNSIGNED AUTO_INCREMENT, " .
                               "  result_id INT UNSIGNED, " .  // machine-API index 
                               "  test_id INT UNSIGNED,    " . // test index
                               "  data_value DOUBLE," .        // average
                               "  data_value2 DOUBLE," .       // variance
                               "  PRIMARY KEY (data_id), " .
                               "  UNIQUE (result_id, test_id))");// value
                               
//array_push($db_create_table01, "CREATE INDEX idx_env_name ON mis_table_environment_info (env_name);");
                               
//array_push($db_create_table01, "CREATE INDEX idx_test_name ON mis_table_test_info (test_name)");
                               
// mis_table_data_list_00001 2
// ALTER TABLE mis_table_data_test_alu MODIFY COLUMN data_value DOUBLE;
// show columns from mis_table_data_test_alu;
// show tables like 'mis_table_data_test_%'
$db_mis_table_create_string001 = "CREATE TABLE IF NOT EXISTS mis_table_data_test_%s " .
                                 "( data_id INT UNSIGNED AUTO_INCREMENT, " .
                                 "  result_id INT UNSIGNED, " .  // driver index 
                                 "  sub_id INT UNSIGNED,    " .  // sub test index
                                 "  data_value DOUBLE," .        // average
                                 "  data_value2 DOUBLE," .       // variance
                                 "  test_case_id INT," .
                                 "  PRIMARY KEY (data_id), " .
                                 "  UNIQUE (result_id, sub_id))";// value
                                 
$db_mis_table_create_string002 = "CREATE TABLE IF NOT EXISTS mis_table_data_test_%s_noise " .
                                 "( data_id INT UNSIGNED AUTO_INCREMENT, " .
                                 "  result_id INT UNSIGNED, " .  // driver index 
                                 "  sub_id INT UNSIGNED,    " .  // sub test index
                                 "  data_value DOUBLE," .
                                 "  test_case_id INT," .
                                 "  noise_id INT," .             // noise id
                                 "  PRIMARY KEY (data_id), " .
                                 "  UNIQUE (result_id, sub_id, noise_id))";// value

$db_mis_table_name_string001 = "mis_table_data_test_";

$db_mis_table_create_string004 = "CREATE TABLE IF NOT EXISTS mis_table_data_shadertest_%s " .
                                 "( data_id INT UNSIGNED AUTO_INCREMENT, " .
                                 "  result_id INT UNSIGNED, " .  // driver index 
                                 "  sub_id INT UNSIGNED,    " .  // sub test index
                                 "  data_value1 DOUBLE," .       // Compile Time(ms)
                                 "  variance_value1 DOUBLE," .
                                 "  data_value2 DOUBLE," .       // Execution Time(ms)
                                 "  variance_value2 DOUBLE," .
                                 "  data_value3 DOUBLE," .       // Shaders/s
                                 "  variance_value3 DOUBLE," .
                                 "  data_value4 DOUBLE," .       // FPS
                                 "  variance_value4 DOUBLE," .
                                 "  test_case_id INT," .
                                 "  group_id INT," .
                                 "  PRIMARY KEY (data_id), " .
                                 "  UNIQUE (result_id, sub_id))";// value

$db_mis_table_create_string003 = "CREATE TABLE IF NOT EXISTS mis_table_data_shadertest_%s_noise " .
                                 "( data_id INT UNSIGNED AUTO_INCREMENT, " .
                                 "  result_id INT UNSIGNED, " .  // driver index 
                                 "  sub_id INT UNSIGNED,    " .  // sub test index
                                 "  data_value1 DOUBLE," .       // Compile Time(ms)
                                 "  data_value2 DOUBLE," .       // Execution Time(ms)
                                 "  data_value3 DOUBLE," .       // Shaders/s
                                 "  data_value4 DOUBLE," .       // FPS
                                 "  test_case_id INT," .
                                 "  noise_id INT," .
                                 "  group_id INT," .
                                 "  PRIMARY KEY (data_id), " .
                                 "  UNIQUE (result_id, sub_id, noise_id))";// value

$db_mis_table_name_string002 = "mis_table_data_shadertest_";
                 
// show columns from mis_table_data_test_alu_noise;
// ALTER TABLE mis_table_data_test_vertexfetch_noise MODIFY COLUMN data_value DOUBLE;
// ALTER TABLE mis_table_data_test_vertexfetch ADD COLUMN data_value2 DOUBLE AFTER data_value;

/*
mis_table_data_test_alu                      
mis_table_data_test_alu_noise                
mis_table_data_test_branching                
mis_table_data_test_branching_noise          
mis_table_data_test_cbupdate                 
mis_table_data_test_cbupdate_noise           
mis_table_data_test_coherencystall           
mis_table_data_test_coherencystall_noise     
mis_table_data_test_colorclear               
mis_table_data_test_colorclear_noise         
mis_table_data_test_computetest              
mis_table_data_test_csfillrate               
mis_table_data_test_csfillrate_noise         
mis_table_data_test_csmandelbrot             
mis_table_data_test_csmandelbrot_noise       
mis_table_data_test_depthclear               
mis_table_data_test_depthclear_noise         
mis_table_data_test_depthop                  
mis_table_data_test_depthop_noise            
mis_table_data_test_fillrate                 
mis_table_data_test_fillrate_noise           
mis_table_data_test_gsaluheavy               
mis_table_data_test_gsaluheavy_noise         
mis_table_data_test_gspointampl              
mis_table_data_test_gspointampl_noise        
mis_table_data_test_gspointsprites           
mis_table_data_test_gspointsprites_noise     
mis_table_data_test_gstriampl                
mis_table_data_test_gstriampl_noise          
mis_table_data_test_hwcontextroll            
mis_table_data_test_hwcontextroll_noise      
mis_table_data_test_interpolation            
mis_table_data_test_interpolation_noise      
mis_table_data_test_lightprobesampling       
mis_table_data_test_lightprobesampling_noise 
mis_table_data_test_multiplert               
mis_table_data_test_multiplert_noise         
mis_table_data_test_oglstatevalidation       
mis_table_data_test_primfilter               
mis_table_data_test_primfilter_noise         
mis_table_data_test_primsetup                
mis_table_data_test_primsetup_noise          
mis_table_data_test_ps_postprocess           
mis_table_data_test_pspostprocess            
mis_table_data_test_pspostprocess_noise      
mis_table_data_test_quadtess                 
mis_table_data_test_quadtess_noise           
mis_table_data_test_asynccompute             
mis_table_data_test_asynccompute_noise       
mis_table_data_test_rescopy                  
mis_table_data_test_rescopy_noise            
mis_table_data_test_resolve                  
mis_table_data_test_resolve_noise            
mis_table_data_test_shadercompile            
mis_table_data_test_shadercompile_noise      
mis_table_data_test_smallbatch               
mis_table_data_test_smallbatch_noise         
mis_table_data_test_texfetch                 
mis_table_data_test_texfetch_noise           
mis_table_data_test_triangletest             
mis_table_data_test_trisizefill              
mis_table_data_test_trisizefill_noise        
mis_table_data_test_uniginetess              
mis_table_data_test_uniginetess_noise        
mis_table_data_test_vertexfetch              
mis_table_data_test_vertexfetch_noise        
//*/
                               
                               
                               
                               
?>