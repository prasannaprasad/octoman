<?php

include_once("MysqlConnector.php");

class DBConnection
{

    public static function getInstance()
    {
        static $db  = null;
        if ($db == null )
            $db = new DBConnection();

        return $db;
    }

    private $_handle = null;

    private function __construct()
    {
        $this->_handle = new MysqlConnector('octoman', 'root', 'freezingbear', 'freezingbearbackend.czqczfkbvqd0.us-west-2.rds.amazonaws.com');

    }

    public function getHandle()
    {
        return $this->_handle;
    }

}