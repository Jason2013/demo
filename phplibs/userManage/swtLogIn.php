<?php


include_once "../generalLibs/genfuncs.php";
include_once "swtUserManager.php";

$userChecker = new CUserManger();

$userName = cleaninput($_POST["userName"], 32);
$passWord = cleaninput($_POST["passWord"], 32);

$returnMsg = array();
$returnMsg["errorCode"] = 0;
$returnMsg["errorMsg"] = "login failed";

if ((strlen($userName) == 0) ||
    (strlen($passWord) == 0))
{
    $returnMsg["errorCode"] = 0;
    $returnMsg["errorMsg"] = "input info empty";
    echo json_encode($returnMsg);
    return;
}

if ($userChecker->logIn($userName, $passWord))
{
    $returnMsg["errorCode"] = 1;
    $returnMsg["errorMsg"] = "login success";
    echo json_encode($returnMsg);
    return;
}

echo json_encode($returnMsg);
return;

?>