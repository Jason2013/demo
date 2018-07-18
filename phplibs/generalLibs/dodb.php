<?php

include_once "code01.php";

class CDoMySQL
{
	public $dbResult = null;
	public $dbLink = NULL;
	public $dbError = "no error!";
	
	public $dbUN = "";
	public $dbPW = "";
    public $hasResult = false;
    
	public function __construct( $un, $pw )
	{
		$this->dbUN = $un;
		$this->dbPW = $pw;
        $this->dbResult = null;
	}
	public function __destruct()
	{
        $this->CloseDB();
    }
	
	public function ReadyDB()
	{
        global $db_server;
        
		$this->dbLink = @mysql_connect( $db_server, $this->dbUN, $this->dbPW );
        //echo "" . $this->dbUN . "-" . $this->dbPW;
		if( ! $this->dbLink )
		{
			$this->dbError = mysql_error();
            $this->dbResult = null;
			return NULL;
		}
		
        global $db_dbname;
        
		$this->dbResult = mysql_select_db( $db_dbname, $this->dbLink );
		if( ! $this->dbResult )
		{
            echo mysql_error();
			
			$this->dbError = mysql_error();
            mysql_close( $this->dbLink );
            $this->dbResult = null;
			return NULL;
		}
		$this->dbResult = null;
		return TRUE;
	}
	
	public function QueryDB( $cmd )
	{
        if (($this->dbResult != null) && ($this->hasResult == true))
        {
            $this->freeQuery();
        }
		$this->dbResult = @mysql_query( $cmd );
		if( ! $this->dbResult )
		{
			$this->dbError = mysql_error();
			return NULL;
		}
        $this->hasResult = true;
		return TRUE;
	}
    
	public function FetchResult()
	{
        return mysql_fetch_row($this->dbResult);
    }
    
	public function QueryDBNoResult( $cmd )
	{
        if (($this->dbResult != null) && ($this->hasResult == true))
        {
            $this->freeQuery();
        }
		$this->dbResult = @mysql_query( $cmd );
		if( ! $this->dbResult )
		{
			$this->dbError = mysql_error();
			return NULL;
		}
        $this->hasResult = false;
		return TRUE;
	}
    
	public function getInsertID()
	{
		return mysql_insert_id();
	}
    
	public function freeQuery()
	{
        mysql_free_result($this->dbResult);
        $this->dbResult = null;
        $this->hasResult = false;
    }
	
	public function CloseDB()
	{
		if( $this->dbLink )
		{
			@mysql_close( $this->dbLink );
		}
	}
}


?>