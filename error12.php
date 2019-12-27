<?php

//for ($i =0; $i<10; $i++) { //in range(10)) {
//    echo $i . "<br />";
//}

if (!file_exists("welcome.txt")) {
    die("file not found");
} else {
    $file = fopen("welcome.txt", "r");
}