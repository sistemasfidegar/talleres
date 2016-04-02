<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Model extends CI_Model {

    var $sql = '';

    public function __construct() {
        parent::__construct();
    }

    function bindParameters($binds) {
        krsort($binds);
        $safe = '$1G#$2T#$3E$#';
        $this->sql = str_replace(':', ':' . $safe, $this->sql);
        $this->sql = str_replace(':-', ':-' . $safe, $this->sql);
        $this->sql = str_replace('->', '->' . $safe, $this->sql);
        foreach ($binds as $key => $value) {

            if (is_array($value)) {
                if (is_null($value[0])) {
                    $this->sql = str_replace(':' . $safe . $key, "NULL", $this->sql);
                    $this->sql = str_replace(':-' . $safe . $key, "NULL", $this->sql);
                } else {
                    $this->sql = str_replace(':' . $safe . $key, pg_escape_string($value[0]), $this->sql);
                    $this->sql = str_replace(':-' . $safe . $key, "'" . pg_escape_string($value[0]) . "'", $this->sql);
                }
            } else {
                if (is_null($value))
                    $this->sql = str_replace(':' . $safe . $key, "NULL", $this->sql);
                else if (gettype($value) == "string")
                    $this->sql = str_replace(':' . $safe . $key, "'" . pg_escape_string($value) . "'", $this->sql);
                else
                    $this->sql = str_replace(':' . $safe . $key, $value, $this->sql);
                $this->sql = str_replace('->' . $safe . $key, $value, $this->sql);
            }
        }
        //return $this->sql;
    }

    function getSql() {
        return $this->sql;
    }

}

?>
