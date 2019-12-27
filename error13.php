<?php

class customException extends Exception
{
    public function errorMessage()
    {
        $errorMsg = "Error in " . $this->getLine() . " in file " . $this->getFile() . " <b>" . $this->getMessage() . "</b>:" .
            " is not a valid email.";
        return $errorMsg;
    }
}

$email = "abc@aa...com";

try {
//trigger exception
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
        throw new customException($email);
    }
    echo "No exception";
} catch (Exception $e) {
    echo "Exception: " . $e->errorMessage();
}
