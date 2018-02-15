<?php

abstract class BaseModel{
    protected $db;
    protected $host="localhost";
    protected $username="revelsof_erp";
    protected $password="root123456";
    protected $db_name="revelsof_erp";

    function __construct(){
        $this->db = mysqli_connect($host, $username, $password, $db_name);

        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }
}

?>