<?php

namespace Utilities {

    class ReportCache
    {
        private $cacheFileName;
        private $values;

        public function __construct($cacheFileName)
        {
            $this->cacheFileName = $cacheFileName;
            if (file_exists($this->cacheFileName)) {
                $this->values = unserialize(file_get_contents($this->cacheFileName));
            }
        }

        public function __destruct()
        {
            file_put_contents($this->cacheFileName, serialize($this->values));
        }

        private static function cacheID($name, $key = null)
        {
            $keys = [$name];
            if (!is_null($key)) {
                array_push($keys, $key);
            }
            return serialize($keys);
        }

        public function hasValue($name, $key = null)
        {
            return isset($this->values[self::cacheID($name, $key)]);
        }

        public function getValue($name, $key = null)
        {
            return $this->values[self::cacheID($name, $key)];
        }

        public function setValue($name, $value, $key = null)
        {
            $this->values[self::cacheID($name, $key)] = $value;
        }
    }

}