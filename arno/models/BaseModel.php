<?php

abstract class BaseModel{

    public $page_url="http://arno-thailand.revelsoft.co.th/arno/admin/";
    public $supplier_page_url="http://arno-thailand.revelsoft.co.th/arno/supplier";
    public static $db;
    protected $host="localhost";
    
    protected $username="root";
    //protected $username="revelsof_erp";
    
    protected $password="root123456";

    // protected $db_name="revelsoft_erp_arno";
    protected $db_name="revelsof_erp-ar";

    function __construct(){
        static::$db = mysqli_connect($host, $username, $password, $db_name);
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            
        }
    }
}

?>