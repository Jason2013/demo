<?php

include_once "swtConfig.php";
include_once "swtStrManager.php";

$swtErrorMsg = new CSwtStrManager($swtSiteLang, 2);

$swtErrorMsg->addString("输入用户身份信息有误！请联系管理员！", "invalid user info! please contact webmaser!"); // 0
$swtErrorMsg->addString("数据库连接失败！", "can't connect to database!"); // 1
$swtErrorMsg->addString("数据库操作失败！", "invalid database request!"); // 2
$swtErrorMsg->addString("无此数据库表！", "database tablename doesn't exist!"); // 3
$swtErrorMsg->addString("登陆成功！", "You logged in!"); // 4
$swtErrorMsg->addString("两次输入的密码不同！", "password & repeated password do not match!"); // 5
$swtErrorMsg->addString("输入信息不能为空！", "some input area are not filled!"); // 6
$swtErrorMsg->addString("用户名被占用！", "username was taken by other people!"); // 7
$swtErrorMsg->addString("新用户注册成功！", "register successful!"); // 8
$swtErrorMsg->addString("用户名不存在！", "username not found!"); // 9
$swtErrorMsg->addString("输入密码不正确！", "password incorrect!"); // 10
$swtErrorMsg->addString("请登录，否则无法记录本次升旗！", "please log in, or your action of raising flag will not be saved!"); // 11
$swtErrorMsg->addString("今日升旗已记录！", "today's action of raising flag has been saved!"); // 12
$swtErrorMsg->addString("选定时段内，未有记录！", "in selected time period, no data is found!"); // 13
$swtErrorMsg->addString("记录成功导出！", "data is printed successfully!"); // 14
$swtErrorMsg->addString("数据库无此字段！", "no such database field found!"); // 15
$swtErrorMsg->addString("信息修改成功！", "information is updated!"); // 16
$swtErrorMsg->addString("输入信息已被他人占用，请换用其他！", "the information inputed has been taken by others, please change!"); // 17
$swtErrorMsg->addString("请先登陆！", "please log in first!"); // 18
$swtErrorMsg->addString("输入信息不正确！", "input information is not correct!"); // 19
$swtErrorMsg->addString("验证码不正确！", "captcha is not correct!"); // 20
$swtErrorMsg->addString("今日升旗未记录！", "today's action of raising flag has not been saved!"); // 21
$swtErrorMsg->addString("未查到相关记录！", "no related records found!"); // 22
$swtErrorMsg->addString("每日提交主题不可超过：", "topics you submit today can't be more than: "); // 23
$swtErrorMsg->addString("每日提交回复不可超过：", "articles you submit today can't be more than: "); // 24
$swtErrorMsg->addString("输入信息提交成功！", "input message submitted!"); // 25
$swtErrorMsg->addString("未查到相关主题！", "no related topic found!"); // 26
$swtErrorMsg->addString("需要关联本站帐号！", "user account needs be attached!"); // 27
$swtErrorMsg->addString("帐号关联成功！", "user account is attached!"); // 28
$swtErrorMsg->addString("用户帐号被禁用！", "user account is prohibited!"); // 29

?>