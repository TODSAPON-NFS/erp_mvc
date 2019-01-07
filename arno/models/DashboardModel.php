<?php

require_once("BaseModel.php");
class DashboardModel extends BaseModel{

    private $maintenance_stock;

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
            
        }
        mysqli_set_charset(static::$db,"utf8");

        // $this->maintenance_stock =  new MaintenanceStockModel;
    }

    function getInvoiceCustomerBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = "",$begin = "0", $lock_1 = "0", $lock_2 = "0" ){

        $str_customer = "";
        $str_date = "";
        $str_user = "";
        $str_lock = "";

        if($lock_1 == "1" && $lock_2 == "1"){
            $str_lock = "AND (paper_lock_1 = '0' OR paper_lock_2 = '0')";
        }else if ($lock_1 == "1") {
            $str_lock = "AND paper_lock_1 = '0' ";
        }else if($lock_2 == "1"){
            $str_lock = "AND paper_lock_2 = '0' ";
        }



        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        $sql = " SELECT tb_invoice_customer.invoice_customer_id, 
        invoice_customer_code, 
        invoice_customer_date, 
        invoice_customer_total_price,
        invoice_customer_vat,
        invoice_customer_vat_price,
        invoice_customer_net_price,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        invoice_customer_term, 
        invoice_customer_due, 
        invoice_customer_name,
        IFNULL(tb2.customer_name_en,'-') as customer_name  
        FROM tb_invoice_customer 
        LEFT JOIN tb_user as tb1 ON tb_invoice_customer.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_invoice_customer.customer_id = tb2.customer_id 
        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
        LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id   
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_invoice_customer.invoice_customer_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
            invoice_customer_code LIKE ('%$keyword%') 
            OR  product_code LIKE ('%$keyword%') 
            OR  product_name LIKE ('%$keyword%') 
        ) 
        AND invoice_customer_begin = '$begin' 
        $str_lock 
        $str_customer 
        $str_date 
        $str_user   
        GROUP BY tb_invoice_customer.invoice_customer_id
        ORDER BY invoice_customer_code ASC 
         ";

        //echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getNetPriceGroupByDate(){
        $sql = " SELECT DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
        SUM(invoice_customer_net_price) AS 'net_price' 
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        -- WHERE DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '2018'
        GROUP BY invoice_date
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getNetPriceGroupByCustomer(){
        $sql = "SELECT tb_invoice_customer.customer_id,tb_customer.customer_code AS code ,tb_customer.customer_name_en AS customer_name, 
        DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
        SUM(invoice_customer_net_price) AS 'net_price' 
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '2018'
        GROUP BY tb_invoice_customer.customer_id
        ORDER BY SUM(invoice_customer_net_price) DESC
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getNetPriceGroupByCustomerLimit($page_start,$page_end){
        $sql = "SELECT tb_invoice_customer.customer_id,tb_customer.customer_code AS code ,tb_customer.customer_name_th AS customer_name, 
        DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
        SUM(invoice_customer_net_price) AS 'net_price' 
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '2018'
        GROUP BY tb_invoice_customer.customer_id
        ORDER BY SUM(invoice_customer_net_price) DESC LIMIT $page_start,$page_end
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getNetPriceGroupByAllSales(){
        $sql = "SELECT tb_user.user_id,CONCAT(tb_user.user_name,'  ',tb_user.user_lastname) AS sales_name , 
        DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
        SUM(invoice_customer_net_price) AS 'net_price' 
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '2018'
        GROUP BY tb_user.user_id  
        ORDER BY SUM(invoice_customer_net_price) DESC 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getNetPriceGroupBySales($sales){
        $str = "";
        if($sales !=""){
            $str = " tb_invoice_customer.employee_id = '$sales' " ;
        }
        $sql = "SELECT tb_user.user_id,tb_user.user_name AS 'sales_name' , 
        DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') AS invoice_date ,
        SUM(invoice_customer_net_price) AS 'net_price' 
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE $str
        GROUP BY invoice_date 
        ORDER BY invoice_date 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getNetPriceBySales($sales){
        $str = "";
        if($sales !=""){
            $str = " tb_invoice_customer.employee_id = '$sales' " ;
        }
        $sql = "SELECT tb_user.user_id,tb_user.user_name AS 'sales_name' , 
        DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
        SUM(invoice_customer_net_price) AS 'net_price' 
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE $str
        GROUP BY invoice_date
        ORDER BY invoice_date 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }    
    function getCustomerAll(){
        $sql = "SELECT * 
        FROM `tb_customer` 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
        
    }
    // -- LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
    // -- LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 

}
?>