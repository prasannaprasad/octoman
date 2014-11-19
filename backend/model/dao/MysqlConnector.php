<?php

include_once('application/WebServiceException.php');

class MysqlConnector
{
    var $result;
    var $records;
    var $hostname;
    var $username;
    var $password;
    var $database;
    var $conn = NULL;

    public  function __construct($database, $username, $password, $hostname)
    {
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->hostname = $hostname;

        $this->conn    = mysqli_connect($this->hostname, $this->username, $this->password,$this->database);

        error_log("!!!" . mysqli_connect_error());
        if(mysqli_connect_error())
            throw new WebServiceException("Unable to connect to DB",2014,__FILE__,__LINE__);
        return $this->conn;

    }


    public function getSingleRecord($query)
    {
        error_log("Executing in getSingleRecord: " . $query);
        $this->result = mysqli_query($this->conn,$query);
        if(!$this->result)
            throw new WebServiceException("Unable to execute query" . mysqli_error($this->conn),2016,__FILE__,__LINE__);
        return mysqli_fetch_assoc($this->result);
    }

    public function getRecords($query)
    {
        $res_array = array();
        error_log("Executing: $query");
        $this->result = mysqli_query($this->conn,$query);
        if(!$this->result)
            throw new WebServiceException("Unable to execute query  " . mysqli_error($this->conn) ,2017,__FILE__,__LINE__);


        while($data = mysqli_fetch_assoc($this->result))
            $res_array[] = $data;
        return $res_array;

    }

    public function getPreparedStatement($query)
    {
        if (!($stmt = mysqli_prepare($this->conn,$query)))
            throw new WebServiceException("Unable to prepare query  " . mysqli_error($this->conn) ,2022,__FILE__,__LINE__);
        return $stmt;
    }

    public function executeQuery($query)
    {
        error_log("Executing in executeQuery: $query");
        $this->result = mysqli_query($this->conn,$query);
        if(!$this->result)
            throw new WebServiceException("Unable to execute query",2015,__FILE__,__LINE__);
        return mysqli_insert_id($this->conn);
    }


}