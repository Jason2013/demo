<?php

include_once "error14.php";

class customException extends Exception
{
    public function errorMessage()
    {
        $errorMsg = "Error in " . $this->getLine() . " in file " . $this->getFile() . " <b>" . $this->getMessage() . "</b>:" .
            " is not a valid email.";
        return $errorMsg;
    }
}

$email = "abc@abc.com";

try {
//trigger exception
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
        throw new customException($email);
    }

    $pdoObj = new MyPDO();
    $conn = $pdoObj->getConn();

    echo "<h1>PDO::query and PDOStatement::fetch</h1>";

    $stmt = $conn->query("select schema_name, default_character_set_name from information_schema.schemata");
    while ($row = $stmt->fetch())
    {
        echo $row[0] . ", " . $row[1] . "</br>";
    }

    echo "<h1>PDO::prepare (positional placeholders) and PDOStatement::execute</h1>";

    $stmt = $conn->prepare("select schema_name, default_character_set_name from information_schema.schemata where schema_name = ?");
    $stmt->execute(["db_mis"]);
    while ($row = $stmt->fetch())
    {
        echo $row[0] . ", " . $row[1] . "</br>";
    }

    echo "<h1>PDO::prepare (named placeholders) and PDOStatement::execute</h1>";

    $stmt = $conn->prepare("select schema_name, default_character_set_name from information_schema.schemata where schema_name = :schema_name");
    $stmt->execute(["schema_name" => "db_mis"]);
    echo "<table border='1'>";
    while ($row = $stmt->fetch())
    {
        echo "<tr>";
        echo "<td>$row[0]</td><td>$row[1]</td>";
        echo "</tr>";
    }
    echo "</table>";

    $attributes = array(
        "AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION", "CONNECTION_STATUS",
        "ORACLE_NULLS", "PERSISTENT",
//        "PREFETCH",
        "SERVER_INFO", "SERVER_VERSION",
//        "TIMEOUT"
    );

    foreach ($attributes as $val) {
        echo "PDO::ATTR_$val: ";
        echo $conn->getAttribute(constant("PDO::ATTR_$val")) . "</br>";
    }

    echo "No exception";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . " [CODE]: " . $e->getCode() . " [TRACE]: " . $e->getTraceAsString();
}
