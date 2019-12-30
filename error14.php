<?php

class MyPDO
{
    function __construct()
    {
        echo "<p>MyPDO::__construct()</p>";
        $conn = new PDO("mysql:host=localhost;dbname=db_mis", "root", "dgxqh523120");
        return $conn;
    }

    function __destruct()
    {
        echo "<p>MyPDO::__destruct()</p>";
    }
}
