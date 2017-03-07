<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

include_once "../../phplibs/dopdo.php";
include_once "../../phplibs/swtMISConst.php";
include_once "../../phplibs/genfuncs.php";

$umdType = cleaninput($_POST["driverType01"], 128);

// Define a destination
$targetFolder = '../../driverStore'; // Relative to the root
$verifyToken = md5('unique_salt' . $_POST['timestamp']);

$date = new DateTime(null, new DateTimeZone('PRC'));
$folder01 = $date->format('Y-m-d-H-i') . "";
$fullFolder01 = $targetFolder . "/" . $folder01;

if (file_exists($fullFolder01) == false)
{
    // make first sub folder under /driverStore
    // like /driverStore/2016-03-30-17-17/
    mkdir($fullFolder01);
}

$arr1 = glob($fullFolder01 . "/*.", GLOB_ONLYDIR);
$dirNum = count($arr1);

$folder02 = "";
$fullFolder02 = "";
do
{
    $folder02 = sprintf("driver%05d", $dirNum);
    $fullFolder02 = $fullFolder01 . "/" . $folder02;
    $dirNum++;
}
while(file_exists($fullFolder02));
// make second class sub folder under /driverStore
// like /driverStore/2016-03-30-17-17/driver00001/
mkdir($fullFolder02);

$saveDirName = $folder01 . "/" . $folder02;

$returnMsg = array();

$db = new CPdoMySQL();

if ($db->getError() != null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "can't reach mysql server";
    return $returnMsg;
}

$sql1 = "SELECT upload_id, upload_path FROM mis_table_upload_info " .
        "WHERE TIMESTAMPDIFF(HOUR, insert_time, NOW()) > " . uploadDriverFileTimeOutHours;
        
if ($db->QueryDB($sql1, $params1) == null)
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "query mysql table failed #1";
    return $returnMsg;
}
$uploadIDList = array();

while ($row1 = $db->fetchRow())
{
    // delete time out driver
    $t1 = $targetFolder . "/" . $row1[1];
    if (file_exists($t1))
    {
        unlink($t1);
    }
    array_push($uploadIDList, $row1[0]);
}

$t1 = "";

for ($i = 0; $i < count($uploadIDList); $i++)
{
    $comma = ", ";
    if ($i == (count($uploadIDList) - 1))
    {
        $comma = "";
    }
    $t1 .= $uploadIDList[$i];
    $t1 .= $comma;
}

if (count($uploadIDList))
{
    // clear time out driver path in database
    $sql1 = "DELETE FROM mis_table_upload_info " .
            "WHERE upload_id IN (" . $t1 . ")";
            
    if ($db->QueryDB($sql1, $params1) == null)
    {
        $returnMsg["errorCode"] = 0;
        $returnMsg["errorMsg"] = "query mysql table failed #2";
        return $returnMsg;
    }
}



if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
    $targetFile = $fullFolder02 . "/" . $_FILES['Filedata']['name'];
	
	// Validate the file type
	$fileTypes = array('dll', 'so'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
    
	if (in_array($fileParts['extension'],$fileTypes))
    {
		move_uploaded_file($tempFile, $targetFile);
        
        
        // insert new uploaded driver
        $params1 = array($verifyToken, $saveDirName . "/" . $_FILES['Filedata']['name']);
        $sql1 = "INSERT INTO mis_table_upload_info " .
                "(umd_id, upload_tocken, upload_path, insert_time) " .
                "VALUES (" . PHP_INT_MAX . ", ?, ?, NOW())";
        if ($db->QueryDB($sql1, $params1) == null)
        {
            $returnMsg["errorCode"] = 0;
            $returnMsg["errorMsg"] = "query mysql table failed #3a";
            return $returnMsg;
        }
        
        
		echo '1';
	} else
    {
		echo 'Invalid file type.';
	}
}
?>