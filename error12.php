<?php

//for ($i =0; $i<10; $i++) { //in range(10)) {
//    echo $i . "<br />";
//}


//error handler function
function customError($errno, $errstr)
{
    echo "<b>Error:</b> [$errno] $errstr";
}

//set error handler
set_error_handler("customError");

//trigger error
$file = fopen("welcome.txt", "r");
