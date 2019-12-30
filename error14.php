<?php

class MyPDO
{
    private $conn = null;

    function __construct()
    {
        echo "<p>MyPDO::__construct()</p>";
        $this->conn = new PDO("mysql:host=localhost;dbname=db_mis;charset=utf8", "root", "dgxqh523120");
    }

    function __destruct()
    {
        echo "<p>MyPDO::__destruct()</p>";
    }

    function getConn() {
        return $this->conn;
    }
}
