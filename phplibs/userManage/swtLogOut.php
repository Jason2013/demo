<?php

include_once "../generalLibs/genfuncs.php";
include_once "swtUserManager.php";


$returnMsg = array();
$returnMsg["errorCode"] = 1;
$returnMsg["errorMsg"] = "logout success";

$userChecker = new CUserManger();
$userChecker->logOut();

echo json_encode($returnMsg);
return;

?>