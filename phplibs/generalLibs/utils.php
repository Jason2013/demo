<?php
namespace Utilities {

    function saveFuncVars($filename, $args)
    {
        $values = Array();
        $values["args"] = $args;

        $global_vars = Array();
        foreach ($GLOBALS as $key => $val) {
            if ($key == "GLOBALS") {
                continue;
            }
            $global_vars[$key] = $GLOBALS[$key];
        }
        $values["globals"] = $global_vars;

        file_put_contents($filename, serialize($values));
    }

    function loadFuncVars($filename)
    {
        $str = file_get_contents($filename);
        $values = unserialize($str);

        foreach ($values["globals"] as $key => $val){
            $GLOBALS[$key] = $val;
        }

        return $values["args"];
    }

}