<?php
namespace Utilities {

    function saveFuncVars($filename, $args, $globals)
    {
        $values = Array();
        $values["args"] = $args;

        $global_vars = Array();
        foreach ($globals as $gvar_name) {
            $global_vars[$gvar_name] = $GLOBALS[$gvar_name];
        }
        $values["globals"] = $global_vars;

        file_put_contents($filename, serialize($values));
    }

    function loadFuncVars($filename)
    {
        $str = file_get_contents($filename);
        $values = unserialize($str);

        foreach ($values["globals"] as $key => $value) {
            $GLOBALS[$key] = $value;
        }

        return $values["args"];
    }

}