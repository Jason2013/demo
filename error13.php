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

    echo "No exception";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . " [CODE]: " . $e->getCode() . " [TRACE]: " . $e->getTraceAsString();
}
