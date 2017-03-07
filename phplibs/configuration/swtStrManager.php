<?php

include_once "swtConfig.php";

class CSwtStrManager
{
    public $allString = array();
    // current language
    public $curLang = 0;

	public function __construct( $_lang, $_langNum )
	{
        if ($_lang >= $_langNum)
        {
            die("当前自然语言超出支持语言数量！(current human language is not supported!)\n");
        }
		$this->curLang = $_lang;
        for ($i = 0; $i < $_langNum; $i++)
        {
            $this->allString[$i] = array();
        }
	}
    
    // do not use
    private function getStringGeneral($_index, $_lang)
    {
        if ($_lang >= count($this->allString))
        {
            return "不支持此种自然语言！(this human language is not supported!)\n";
        }
        if ($_index >= count($this->allString[$_lang]))
        {
            return "自然语言字串索引超出维度！(error msg index is invalid!)\n";
        }
        return $this->allString[$_lang][$_index];
    }

    public function addString($cnmsg, $enmsg)
    {   
        if (strlen($cnmsg) == 0 || strlen($enmsg) == 0)
        {
            return;
        }
        array_push($this->allString[0], $cnmsg);
        array_push($this->allString[1], $enmsg);
    }
	
    public function getString($_index)
    {
        return $this->getStringGeneral($_index, $this->curLang);
    }
    
    public function chooseString($_cnStr, $_enStr)
    {
        $t1 = array($_cnStr, $_enStr);
        return $t1[$this->curLang];
    }
    
    public function getErrorString($_index)
    {
        $errorHead0 = "[error # " . $_index . "] - ";
        return $errorHead0 . $this->getStringGeneral($_index, $this->curLang);
    }
    
    public function getErrorStringMore($_index, $_ref)
    {
        $errorHead0 = "[error # " . $_index . "] - [ref # " . $_ref . "]";
        return $errorHead0 . $this->getStringGeneral($_index, $this->curLang);
    }
    
    public function getMaxErrorNum()
    {
        return count($this->allString[$this->curLang]);
    }
}


?>