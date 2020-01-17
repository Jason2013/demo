<?php

include_once "code01.php";

class CPdoMySQL
{
	public $dbResult = null;
	public $dbHandle = null;
    public $dbStatement = null;
	public $dbError  = null;
    public $selfError  = null;
	
	public $dbUN = "";
	public $dbPW = "";
    public $hasResult = false;

    public function __construct()
    {
        global $db_server;
        global $db_dbname;
        global $db_username;
        global $db_password;

        $this->dbResult = null;
        $dsn = "mysql:dbname=$db_dbname;host=$db_server;charset=utf8";

        $this->dbError = null;
        try {
            $this->dbHandle = new PDO($dsn, $db_username, $db_password, array(
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_EMULATE_PREPARES => false
            ));
            // PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true)
            // PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8, NAMES utf8;"
        } catch (PDOException $e) {
            $this->dbError = $e->getMessage();
        }
    }
	public function __destruct()
	{
        $this->clearResult();
        //$this->dbStatement = null;
        $this->dbHandle = null;
    }
    
	public function getError()
	{
        return $this->dbError;
    }
    
	public function QueryDBDirect($cmd)
	{
        $this->clearResult();
        $this->dbError = null;

        $this->selfError = "4";
        $result = $this->dbHandle->query($cmd);
        $this->dbError = $this->dbHandle->errorInfo();
        return $result;
    }
	
	public function QueryDB($cmd, $params)
	{
        $this->clearResult();
        $this->dbError = null;
        try
        {
            $this->dbStatement = $this->dbHandle->prepare($cmd, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
        }
        catch (PDOException $e)
        {
            $this->dbError = $e->getMessage();
            $this->selfError = "1";
            return null;
        }
        if ($this->dbStatement == false)
        {
            $this->dbError = $this->dbHandle->errorInfo();
            $this->selfError = "2";
            return null;
        }
        if ($params == null || count($params) == 0)
        {
            $this->selfError = "3";
            $result = $this->dbStatement->execute();
            $this->dbError = $this->dbStatement->errorInfo();
            return $result;
        }
        $result = $this->dbStatement->execute($params);
        $this->dbError = $this->dbStatement->errorInfo();
        return $result;
	}
    
	public function fetchRow()
	{
        if ($this->dbStatement != null)
        {
            return $this->dbStatement->fetch();
        }
        return false;
    }
    
	public function fetchColumn()
	{
        if ($this->dbStatement != null)
        {
            return $this->dbStatement->fetchColumn();
        }
        return false;
    }
    
	public function fetchAll()
	{
        if ($this->dbStatement != null)
        {
            return $this->dbStatement->fetchAll();
        }
        return false;
    }
    
	public function getRowNum()
	{
        // not for select
        if ($this->dbStatement != null)
        {
            return $this->dbStatement->rowCount();
        }
        return 0;
    }
    
	public function getInsertID()
	{
        if ($this->dbHandle != null)
        {
            return $this->dbHandle->lastInsertId();
        }
        return -1;
	}

	public function clearResult()
	{
        if ($this->dbStatement != null)
        {
            $this->dbStatement->fetchAll();
            $this->dbStatement->nextRowset();
            $this->dbStatement->closeCursor();
            $this->dbStatement = null;
        }
    }
}


?>