<?php

//error handler function
function customError($errno, $errstr, $error_file, $error_line)//, $error_context)
{
    echo "<b>Error:</b> [$errno] [$error_file:$error_line][$error_line] $errstr";
}

//set error handler
set_error_handler("customError");

$a=1;
$b="hello";

echo "hello, world! $a, \$b</br>";

if ($a < 2)
{
    trigger_error("\$a must not less than 2", E_USER_WARNING);
}
