<?php

include_once "genfuncs.php";


function swt_create_guid() {
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid = chr(123)// "{"
    .substr($charid, 0, 8).$hyphen
    .substr($charid, 8, 4).$hyphen
    .substr($charid,12, 4).$hyphen
    .substr($charid,16, 4).$hyphen
    .substr($charid,20,12)
    .chr(125);// "}"
    return $uuid;
}

//{9B6ECE4C-2295-5A9C-744D-7872E6227320}
//38

function swt_create_guid_clean() {
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $uuid = substr($charid, 0, 32);
    return $uuid;
}

function swt_get_client_ip() 
{ 
    $remote_addr = ''; 
    if (getenv('HTTP_CLIENT_IP'))
    { 
        $remote_addr = getenv('HTTP_CLIENT_IP'); 
    } else if (getenv('HTTP_X_FORWARDED_FOR'))
    { 
        $remote_addr = getenv('HTTP_X_FORWARDED_FOR'); 
    } else if (getenv('REMOTE_ADDR'))
    { 
        $remote_addr = getenv('REMOTE_ADDR'); 
    } else
    { 
        $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown'; 
    } 
    return cleaninput($remote_addr, 32); 
}

function swt_send_simple_mail($mailTo, $mailSubject, $htmlContent, $textContent)
{
    require '../phpmailer/class.smtp.php';
    require '../phpmailer/class.phpmailer.php';

    $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.163.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'davychen1314';                 // SMTP username
    $mail->Password = '3Sheng3Shi';                           // SMTP password
    //$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    //$mail->Port = 465;                                    // TCP port to connect to
    $mail->CharSet  = "utf-8";

    $mail->From = 'davychen1314@163.com';
    $mail->FromName = 'jidewo.me';
    //$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
    $mail->addAddress("" . $mailTo);               // Name is optional
    $mail->addReplyTo('davychen1314@163.com', 'jidewo.me');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = '' . $mailSubject;
    // html content
    $mail->Body    = '' . $htmlContent;
    // text content
    $mail->AltBody = '' . $textContent;

    if(!$mail->send())
    {
        echo '邮件发送失败！';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        return false;
    }
    else
    {
        echo 'Bingo！邮件发送成功！';
        return true;
    }
}

function swt_get_cur_datetime()
{
    $date1 = new DateTime("now", new DateTimeZone('PRC'));
    return $date1->format('Y-m-d H:i:s'); // 2011-03-03 00:00:00
}

function swt_get_cur_date()
{
    $date1 = new DateTime("now", new DateTimeZone('PRC'));
    return $date1->format('Y-m-d'); // 2011-03-03
}

function swt_get_datetime_with_month_diff($monthDiff)
{
    $date1 = new DateTime("now", new DateTimeZone('PRC'));
    if ($monthDiff > 0)
    {
        $date1->add(new DateInterval("P" . $monthDiff . "M"));
    }
    else if ($monthDiff < 0)
    {
        $date1->sub(new DateInterval("P" . abs($monthDiff) . "M"));
    }
    return $date1->format('Y-m-d H:i:s'); // 2011-03-03 00:00:00
}

function swt_get_date_with_day_diff($dayDiff)
{
    $date1 = new DateTime("now", new DateTimeZone('PRC'));
    if ($dayDiff > 0)
    {
        $date1->add(new DateInterval("P" . $dayDiff . "D"));
    }
    else if ($dayDiff < 0)
    {
        $date1->sub(new DateInterval("P" . abs($dayDiff) . "D"));
    }
    return $date1->format('Y-m-d'); // 2011-03-03
}

function swt_check_malicious_refresh($prePath)
{
/*    
    global $g_maliciousIPList;
    global $g_lastUpdateMaliciousIPListTimeStamp;
    include $prePath . "genFiles/malicious_ips.php";

//echo $g_maliciousIPList[0];

    $curIP = swt_get_client_ip();
    
    if (array_search($curIP, $g_maliciousIPList) !== false)
    {
        die("your ip is not allowed today!");
        return;
    }
    
    $b1 = false;
    $filePath1 = $prePath . "genFiles/malicious_ips.txt";
    $filePath2 = $prePath . "genFiles/malicious_ips.php";
    if (file_exists($filePath1))
    {
        // check if ip list is from yesterday and can be cleared
        date_default_timezone_set("Asia/Shanghai");
        $curDate = getdate();
        $todayStartTime = mktime(0, 0, 0, (int)$curDate["mon"], (int)$curDate["mday"], (int)$curDate["year"]);
        $modTime = filemtime($filePath1);
        if ($todayStartTime > $modTime)
        {
            $b1 = true;
        }
        //echo "+++" . $curDate["mon"] . "+++" . $curDate["mday"] . "+++" . $curDate["year"];
    }
    else
    {
        $b1 = true;
    }
    if ($b1)
    {
        // clear ip banning list
        $handle = fopen($filePath1, 'w+');
        fclose($handle);
        $g_maliciousIPList = array();
        $g_lastUpdateMaliciousIPListTimeStamp = time();
        
        $handle = fopen($filePath2, 'w+');
        fseek($handle, 0, SEEK_SET);
        fwrite($handle, "<?php\n");
        fwrite($handle, "// this file is auto generated!\n");
        fwrite($handle, "\$g_maliciousIPList = array();\n");
        fwrite($handle, "?>\n");
        fclose($handle);
    }

    $sessName = md5($curIP);
    //echo "" . swt_get_client_ip();
    $timeStampName = $sessName . "_timeStamp";
    $visitedNumName = $sessName . "_visitedNum";
    
    if ((isset($_SESSION[$timeStampName]) == false) || (isset($_SESSION[$visitedNumName]) == false))
    {
        $_SESSION[$timeStampName] = time();
        $_SESSION[$visitedNumName] = 0;
        return;
    }
    $_SESSION[$visitedNumName]++;
    global $g_maxPageRefreshRatePerMin;
    if ($_SESSION[$visitedNumName] > $g_maxPageRefreshRatePerMin)
    {
        $diffTime = time() - $_SESSION[$timeStampName];
        $_SESSION[$timeStampName] = $curTime;
        $_SESSION[$visitedNumName] = 0;
        // refresh rate in 1 min
        if ($diffTime < 60)
        {
            // set ip baned
            $handle = fopen($filePath1, 'r+');
            $fileSize1 = filesize($filePath1);
            $fileContent1 = "";
            $g_maliciousIPList = array();
            if ($fileSize1 > 0)
            {
                fseek($handle, 0, SEEK_SET);
                $fileContent1 = fread($handle, $fileSize1);
                $g_maliciousIPList = explode(" ", $fileContent1);
            }
            if (array_search($curIP, $g_maliciousIPList) === false)
            {
                array_push($g_maliciousIPList, $curIP);
                fseek($handle, 0, SEEK_END);
                fwrite($handle, $curIP . " ");
            }
            fclose($handle);
            $handle = fopen($filePath2, 'w+');
            fseek($handle, 0, SEEK_SET);
            fwrite($handle, "<?php\n");
            fwrite($handle, "// this file is auto generated!\n");
            fwrite($handle, "\$g_maliciousIPList = array();\n");
            foreach ($g_maliciousIPList as $tmpIP)
            {   if (strlen($tmpIP) == 0)
                {
                    continue;
                }
                fwrite($handle, "array_push(\$g_maliciousIPList, \"" . $tmpIP . "\");\n");
            }
            fwrite($handle, "?>\n");
            fclose($handle);
            die("your page refresh rate is not necessary!");
            return;
        }

    }
//*/
}

?>