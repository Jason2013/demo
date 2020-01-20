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

        foreach ($values["globals"] as $key => $val) {
            $GLOBALS[$key] = $val;
        }

        return $values["args"];
    }

    function SaveVarsToFile($filename, $headers, $values)
    {
        function valueStr($value)
        {
            $newValues = Array();
            foreach ($value as $val) {
                $str = (string)$val;
                if (strstr($val, ',') !== false) {
                    $str = "\"$str\"";
                }
                array_push($newValues, $str);
            }
            return implode(',', $newValues);
        }

        if (!file_exists($filename)) {
            $handle = fopen($filename, "w");
            fwrite($handle, implode(',', $headers) . "\n");
            fwrite($handle, valueStr($values) . "\n");
        } else {
            $handle = fopen($filename, "a");
            fwrite($handle, valueStr($values) . "\n");
        }
        fclose($handle);
    }

}