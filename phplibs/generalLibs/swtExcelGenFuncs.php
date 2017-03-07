<?php

function swtExcelCellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}

function swtExcelCellFontColor($cells, $color, $bold){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray(array(
        'font'  => array(
            'bold'  => $bold,
            'color' => array('rgb' => $color),
            //'size'  => 15,
            //'name'  => 'Verdana'
        )
    ));
}



//cellColor('B5', 'F28A8C');

$swtExcelReportHeadParam = array("",
                                 "Base Driver Version",
                                 "Base Driver Date",
                                 "Date",
                                 "Time",
                                 "GPU Core Clock",
                                 "GPU Memory Clock",
                                 "GPU Memory",
                                 "CPU",
                                 "Staging Build",
                                 "Driver");

                                 
function swtReplaceXmlTag($_text)
{
    $toSearch = array("&lt;", "&gt;");
    $toReplaceTo = array("<", ">");
    
    $finalContent = str_replace($toSearch, $toReplaceTo, $_text);
    return $finalContent;
}
                                 
function swtGetTagOffsetInFile($_fileHandle, $_tag, $_dirForward, $_offset)
{
    $sizeOfOneCall = 2048;
    $sizeBackCheck = 64;
    
    $fileOffset = $_offset;
    fseek($_fileHandle, 0, SEEK_END);
    $fileLength = ftell($_fileHandle);
    
    while (1)
    {
        $t1 = "";
        $b1 = false;
        if ($_dirForward)
        {
            fseek($_fileHandle, $fileOffset, SEEK_SET);
            $n1 = $fileLength - $fileOffset;
            $n1 = $n1 > $sizeOfOneCall ? $sizeOfOneCall : $n1;
            $t1 = fread($_fileHandle, $n1);
            
            $t1 = swtReplaceXmlTag($t1);
            
            $n2 = strpos($t1, $_tag);
            if ($n1 !== false)
            {
                return ($fileOffset + $n2);
            }
            
            if (($fileOffset + $sizeOfOneCall) >= $fileLength)
            {
                $b1 = true;
            }
            else
            {
                $fileOffset += $sizeOfOneCall;
                $fileOffset -= $sizeBackCheck;
            }
        }
        else
        {
            $n1 = $fileLength - $fileOffset;
            $n1 = $n1 > $sizeOfOneCall ? $sizeOfOneCall : $n1;
            fseek($_fileHandle, $fileOffset + $n1, SEEK_END);
            $t1 = fread($_fileHandle, $n1);
            
            $t1 = swtReplaceXmlTag($t1);
            
            $n2 = strrpos($t1, $_tag);
            if ($n1 !== false)
            {
                return ($fileLength - $fileOffset - $n1 + $n2);
            }
            
            if (($fileOffset + $sizeOfOneCall) >= $fileLength)
            {
                $b1 = true;
            }
            else
            {
                $fileOffset += $sizeOfOneCall;
                $fileOffset -= $sizeBackCheck;
            }
        }
        if ($b1)
        {
            return -1;
        }
    }
}

function swtGetXmlLineTagValue($_fileHandle, $_tag, $_valueName, $_offset)
{
    $maxLineLength = 512;
    
    fseek($_fileHandle, 0, SEEK_END);
    $fileLength = ftell($_fileHandle);
    
    $n1 = $fileLength - $_offset;
    $n1 = $n1 > $maxLineLength ? $maxLineLength : $n1;
    
    fseek($_fileHandle, $_offset, SEEK_SET);
    $t1 = fread($_fileHandle, $n1);
    
    $t1 = swtReplaceXmlTag($t1);
    
    $tag1 = "<" . $_tag;
    $n2 = strpos($t1, $tag1);
    if ($n2 === false)
    {
        return "";
    }
    $t1 = substr($t1, $n2 + strlen($tag1));
    $n2 = strpos($t1, ">");
    if ($n2 === false)
    {
        return "";
    }
    $t1 = substr($t1, 0, $n2);
    
    $tag1 = $_valueName . "=\"";
    $n2 = strpos($t1, $tag1);
    if ($n2 === false)
    {
        return "";
    }
    $n3 = strpos($t1, "\"");
    if ($n3 === false)
    {
        return "";
    }
    $t2 = substr($t1, $n2 + strlen($tag1), $n3);
    return $t2;
}

?>