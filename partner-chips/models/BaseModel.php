<?php

abstract class BaseModel{

    protected $page_url="http://arno-thailand.revelsoft.co.th/admin/";
    public static $db;
    protected $host="localhost";
    
    protected $username="root";
    //protected $username="revelsof_erp";
    
    protected $password="root123456";

    protected $db_name="revelsof_erp_pc";
    //protected $db_name="revelsof_erppc";

    function __construct(){
        static::$db = mysqli_connect($host, $username, $password, $db_name);
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }
}

?>