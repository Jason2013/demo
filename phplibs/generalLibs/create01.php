<?php

//return;

include_once "code01.php";
include_once "dopdo.php";

function db_create_data_table($testID)
{
    global $db_table_data_surname;
    global $db_mis_table_create_string001;
    $tableName = db_get_tablename_from_name_and_index($db_table_data_surname, $testID);
    
    $sql = sprintf($db_mis_table_create_string001, $tableName);
    
    return $sql;
}

$sql1 = $db_create_db01;
$tmpLink = mysql_connect( $db_server, $db_username, $db_password );
//echo "" . $this->dbUN . "-" . $this->dbPW;
if( ! $tmpLink )
{
    die( "link mysql database error! " . mysql_error() );
}

if ( ! mysql_query($sql1))
{
	die( "can not create database! " . mysql_error() );
}
echo "create database ok! <br />";

if ($tmpLink)
{
    mysql_close($tmpLink);
}


$db = new CPdoMySQL();

if ($db->getError() != null)
{
	die( "can not connect database! " . $db->getError() );
}
echo "connect database ok! <br />";


$params1 = array();

/*
$sql1 = $db_use_db01;
if ($db->QueryDB($sql1, $params1) == null)
{
	die( "can not select database! " . $db->getError() );
}
echo "select database ok! <br />";
*/

$i = 0;
foreach( $db_create_table01 as $sql_cmd )
{
    $sql1 = $sql_cmd;
	if ($db->QueryDB($sql1, $params1) == null)
	{
		die( "can not create table " . $i . $db->getError()[2] );
	}
	echo "create table " . $i . " ok! <br />";
	
	$i++;
}

//*/
?>