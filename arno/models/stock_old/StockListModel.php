<?php

require_once("BaseModel.php");
class StockListModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getStockLogListByDate($date_start = '', $date_end = ''){
        $str = " AND STR_TO_DATE(stock_date,'%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('$date_start','%Y-%m-%d %H:%i:%s') AND STR_TO_DATE(stock_date,'%Y-%m-%d %H:%i:%s') <= STR_TO_DATE('$date_end','%Y-%m-%d %H:%i:%s') ";

        $sql_old_in = "SELECT SUM(qty) 
        FROM tb_stock_log 
        WHERE tb_stock_log.product_id = tb.product_id 
        AND stock_log_type = 'in' 
        AND STR_TO_DATE(stock_date,'%Y-%m-%d %H:%i:%s') < STR_TO_DATE('$date_start','%Y-%m-%d %H:%i:%s')";


        $sql_old_out = "SELECT SUM(qty) 
        FROM tb_stock_log 
        WHERE tb_stock_log.product_id = tb.product_id 
        AND stock_log_type = 'out' 
        AND STR_TO_DATE(stock_date,'%Y-%m-%d %H:%i:%s') < STR_TO_DATE('$date_start','%Y-%m-%d %H:%i:%s')";


        $sql_in = "SELECT SUM(qty) 
        FROM tb_stock_log 
        WHERE tb_stock_log.product_id = tb.product_id 
        AND stock_log_type = 'in' ".$str;

        $sql_out = "SELECT SUM(qty) 
        FROM tb_stock_log 
        WHERE tb_stock_log.product_id = tb.product_id 
        AND stock_log_type = 'out' ".$str;

        $sql_borrow_in = "SELECT SUM(qty) 
        FROM tb_stock_log 
        WHERE tb_stock_log.product_id = tb.product_id 
        AND stock_log_type = 'borrow_in' ".$str;

        $sql_borrow_out = "SELECT SUM(qty) 
        FROM tb_stock_log 
        WHERE tb_stock_log.product_id = tb.product_id 
        AND stock_log_type = 'borrow_out' ".$str;

        $sql_minimum = "SELECT SUM(minimum_stock) 
        FROM tb_product_customer 
        WHERE tb_product_customer.product_id = tb.product_id 
        AND product_status = 'Active' ";

        $sql_safety = "SELECT SUM(safety_stock) 
        FROM tb_product_customer 
        WHERE tb_product_customer.product_id = tb.product_id 
        AND product_status = 'Active' ";

        $sql = "SELECT product_id,  CONCAT(product_code_first,product_code) as product_code, 
        product_name, product_type, product_status ,
        (IFNULL(($sql_old_in),0) - IFNULL(($sql_old_out),0)) as stock_old,
        IFNULL(($sql_in),0) as stock_in,
        IFNULL(($sql_out),0) as stock_out,
        IFNULL(($sql_borrow_in),0) as stock_borrow_in,
        IFNULL(($sql_borrow_out),0) as stock_borrow_out,
        IFNULL(($sql_safety),0) as stock_safety,
        IFNULL(($sql_minimum),0) as stock_minimum 
        FROM tb_product as  tb
        WHERE product_status = 'Active' 
        ORDER BY CONCAT(product_code_first,product_code) 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
}
?>