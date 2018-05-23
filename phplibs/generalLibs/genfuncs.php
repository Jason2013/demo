<?php

// user image is smaller than 200 x 200 px
$swtUserImageMaxSize = 200;

$swtUserImageType = array();
array_push($swtUserImageType, ".jpg");
array_push($swtUserImageType, ".jpeg");
array_push($swtUserImageType, ".gif");
array_push($swtUserImageType, ".png");

function cleaninput( $str, $len)
{
 $str = preg_replace( "/[^". chr(0xa1) ."-". chr(0xff) ."a-zA-Z0-9_.@-]/", "", $str);
 $n = strlen( $str);
 if( $n > $len)
 return substr( $str, 0, $len);
 else
 return substr( $str, 0, $n);
}

function cleanFolderName( $str, $len)
{
 $str = preg_replace( "/[^a-zA-Z0-9.\-\,\(\)]/", " ", $str);
 $n = strlen( $str);
 if( $n > $len)
 return substr( $str, 0, $len);
 else
 return substr( $str, 0, $n);
}

function cleanPath( $str, $len)
{
 $str = preg_replace( "/[^a-zA-Z0-9_.:\-\\\\\\/]/", "", $str);
 $n = strlen( $str);
 if( $n > $len)
 return substr( $str, 0, $len);
 else
 return substr( $str, 0, $n);
}

function cleanText( $str, $len)
{
 $str = preg_replace( "/[^". chr(0xa0) ."-". chr(0xff) ."a-zA-Z0-9!@#$%*()-=_+;:,.?\s\n]/", "", $str);
 $n = strlen( $str);
 if( $n > $len)
 return substr( $str, 0, $len);
 else
 return substr( $str, 0, $n);
}

function cleanDateTime($str)
{
 $str = preg_replace( "/[^". chr(0xa1) ."-". chr(0xff) ."a-zA-Z0-9-:\s]/", "", $str);
 $n = strlen( $str);
 $len = 19;
 if( $n > $len)
 return substr( $str, 0, $len);
 else
 return substr( $str, 0, $n);
}

// UTF8
// 0xEA84BF~0xEAA980
// GB
// 0xA13F~0xAA40

function cleanTextUTF8( $str, $len)
{
 $str = preg_replace( "/[^\x{4e00}-\x{9fa5}\x{3001}-\x{3011}\x{201d}-\x{2026}\x{ff01}-\x{ff1f}a-zA-Z0-9!@#$%*()-=_+;:,.?\s\n]/u", "", $str);
 $n = strlen( $str);
 if( $n > $len)
 return substr( $str, 0, $len);
 else
 return substr( $str, 0, $n);
}

//ELF Hash Function
function ELFHash( $str )
{
	$hash = 0;
	$x = 0;
	$n1 = 0;
	$n2 = strlen( $str );

	while( $n1 < $n2 )
	{
		$hash = ( (int)$hash << 4 ) + ( ord( substr( $str, $n1++, 1 ) ) );
		if( ( $x = $hash & 0xF0000000 ) != 0 )
		{
			$hash ^= ( $x >> 24 );
			$hash &= ~$x;
		}
	}
	return ( $hash & 0x7FFFFFFF );
}

function GBK2UTF8($str)
{
    return iconv("GB18030", "UTF-8//IGNORE", $str);
}

function swtGetSessionCookie($_name)
{
    if (isset($_SESSION[$_name]))
    {
        setcookie($_name, $_SESSION[$_name], time() + 3600 * 24 * 365, "/");
        return $_SESSION[$_name];
    }
    if (isset($_COOKIE[$_name]))
    {
        $t1 = iconv("UTF-8", "GBK", $_COOKIE[$_name]);
        $t1 = cleaninput($t1, 1024);
        $t1 = iconv("GBK", "UTF-8", $t1);
        $_SESSION[$_name] = $t1;
        return $t1;
    }
    return null;
}

function swtGetSessionCookieDateTime($_name)
{
    if (isset($_SESSION[$_name]) && (strlen("" . $_SESSION[$_name]) > 0))
    {
        setcookie($_name, $_SESSION[$_name], time() + 3600 * 24 * 365, "/");
        return $_SESSION[$_name];
    }
    if (isset($_COOKIE[$_name]))
    {
        $t1 = $_COOKIE[$_name];
        $t1 = cleanDateTime($t1);
        $_SESSION[$_name] = $t1;
        return $t1;
    }
    return null;
}

function swtGetSessionCookieWithLen($_name, $_len)
{
    if (isset($_SESSION[$_name]))
    {
        setcookie($_name, $_SESSION[$_name], time() + 3600 * 24 * 365, "/");
        return $_SESSION[$_name];
    }
    if (isset($_COOKIE[$_name]))
    {
        $t1 = iconv("UTF-8", "GBK", $_COOKIE[$_name]);
        $t1 = cleaninput($t1, $_len);
        $t1 = iconv("GBK", "UTF-8", $t1);
        $_SESSION[$_name] = $t1;
        return $t1;
    }
    return null;
}

function swtGetSessionCookieReadOnly($_name)
{
    if (isset($_SESSION[$_name]))
    {
        return $_SESSION[$_name];
    }
    if (isset($_COOKIE[$_name]))
    {
        $t1 = iconv("UTF-8", "GBK", $_COOKIE[$_name]);
        $t1 = cleaninput($t1, 1024);
        $t1 = iconv("GBK", "UTF-8", $t1);
        return $t1;
    }
    return null;
}

function swtGetSessionCookieWithLenReadOnly($_name, $_len)
{
    if (isset($_SESSION[$_name]))
    {
        return $_SESSION[$_name];
    }
    if (isset($_COOKIE[$_name]))
    {
        $t1 = iconv("UTF-8", "GBK", $_COOKIE[$_name]);
        $t1 = cleaninput($t1, $_len);
        $t1 = iconv("GBK", "UTF-8", $t1);
        return $t1;
    }
    return null;
}

function swtSetSessionCookie($_name, $_val)
{
    $_SESSION[$_name] = $_val;
    setcookie($_name, $_val, time() + 3600 * 24 * 365, "/");
}

function swtSetSessionCookieWithTime($_name, $_val, $_time)
{
    $_SESSION[$_name] = $_val;
    setcookie($_name, $_val, $_time, "/");
}

function swtDelSessionCookie($_name)
{
    unset($_SESSION[$_name]);
    setcookie($_name, "", time() - 3600, "/");
}

function swtGetUserImageLink($_userID)
{
    global $swtUserImageType;
    
    $userImagePath0 = "../pics/userDefaultImage.jpg";
    
    if ($_userID == -1)
    {
        return $userImagePath0;
    }
    
    $userImagePath1 = "../userImages/u" . $_userID;
    $userImagePath2 = "";
    for ($i = 0; $i < count($swtUserImageType); $i++)
    {
        $userImagePath2 = $userImagePath1 . $swtUserImageType[$i];
        if (file_exists($userImagePath2))
        {
            return $userImagePath2;
        }
    }

    return $userImagePath0;
    //return false;
}

function swtDelFileTree($dir)
{
     foreach(glob($dir . '/*') as $file)
     {
         if(is_dir($file))
             swtDelFileTree($file);
         else
             @unlink($file);
     }
     rmdir($dir);
}

// copy folder
function recurse_copy($src, $dst)
{ 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ($file = readdir($dir)))
    { 
        if (( $file != '.' ) && ( $file != '..' ))
        { 
            if ( is_dir($src . '/' . $file) )
            { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else
            { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
}

// copy folder skip copying existing files
function recurse_copy_fast($src, $dst)
{ 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ($file = readdir($dir)))
    { 
        if (( $file != '.' ) && ( $file != '..' ))
        { 
            if ( is_dir($src . '\\' . $file) )
            { 
                recurse_copy_fast($src . '\\' . $file, $dst . '\\' . $file); 
            } 
            else if (file_exists($dst . '\\' . $file) == false)
            { 
                @copy($src . '\\' . $file, $dst . '\\' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 

function swtGetFileTreeLastAccessTime($dir)
{
    $lastTime = 0;
    foreach(glob($dir . '/*') as $file)
    {
        if(is_dir($file))
        {
            $lastTime = swtGetFileTreeLastAccessTime($file);
        }
        else
        {
            $curFileTime = fileatime($file);
            //echo "--" . $curFileTime;
            $lastTime = $curFileTime > $lastTime ? $curFileTime : $lastTime;
        }
    }
    return $lastTime;
}

?>