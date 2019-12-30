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

    $conn = new MyPDO();

    $stmt = $conn->getConn()->query("select schema_name, default_character_set_name from information_schema.schemata");
    while ($row = $stmt->fetch())
    {
        echo $row[0] . ", " . $row[1] . "</br>";
    }

    $stmt = $conn->getConn()->prepare("select schema_name, default_character_set_name from information_schema.schemata where schema_name = ?");
    $stmt->execute(["db_mis"]);
    while ($row = $stmt->fetch())
    {
        echo $row[0] . ", " . $row[1] . "</br>";
    }

    echo "No exception";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . " [CODE]: " . $e->getCode() . " [TRACE]: " . $e->getTraceAsString();
}