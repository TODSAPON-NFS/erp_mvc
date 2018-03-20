<?php

require_once("BaseModel.php");
class StockBorrowInModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getStockBorrowInByDate($date_start = '', $date_end = ''){
        $sql = "SELECT stock_log_id, stock_date, tb_stock_log.product_id,  CONCAT(product_code_first,product_code) as product_code, 
        product_name, product_type, product_status , qty , supplier_name_th, supplier_name_en 
        FROM tb_stock_log 
        LEFT JOIN tb_product ON tb_stock_log.product_id = tb_product.product_id 
        LEFT JOIN tb_supplier ON tb_stock_log.supplier_id = tb_supplier.supplier_id 
        WHERE stock_log_type = 'borrow_in' 
        AND STR_TO_DATE(stock_date,'%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('$date_start','%Y-%m-%d %H:%i:%s') 
        AND STR_TO_DATE(stock_date,'%Y-%m-%d %H:%i:%s') <= STR_TO_DATE('$date_end','%Y-%m-%d %H:%i:%s') 
        ORDER BY STR_TO_DATE(stock_date,'%Y-%m-%d %H:%i:%s') 
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


    function getStockBorrowInByID($id){
        $sql = " SELECT * 
        FROM tb_stock_log 
        WHERE stock_log_id = '$id' 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }


    function updateStockBorrowInByID($id,$data = []){
        $sql = " UPDATE tb_stock_log SET 
        stock_log_type = '".$data['stock_log_type']."',  
        product_id = '".$data['product_id']."', 
        stock_date = '".$data['stock_date']."', 
        po_code = '".$data['po_code']."', 
        invoice_code = '".$data['invoice_code']."', 
        borrow_code = '".$data['borrow_code']."', 
        recieve_code = '".$data['recieve_code']."', 
        supplier_id = '".$data['supplier_id']."', 
        customer_id = '".$data['customer_id']."', 
        qty = '".$data['qty']."', 
        borrow_status = '".$data['borrow_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE stock_log_id = $id 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }


    }



    function insertStockBorrowIn($data = []){
        $sql = " INSERT INTO tb_stock_log (
            stock_log_type,
            product_id,
            stock_date,
            po_code,
            invoice_code,
            borrow_code,
            recieve_code,
            supplier_id,
            customer_id,
            qty,
            borrow_status, 
            addby, 
            adddate 
        ) VALUES (
            '".$data['stock_log_type']."', 
            '".$data['product_id']."', 
            '".$data['stock_date']."', 
            '".$data['po_code']."', 
            '".$data['invoice_code']."', 
            '".$data['borrow_code']."', 
            '".$data['recieve_code']."', 
            '".$data['supplier_id']."', 
            '".$data['customer_id']."', 
            '".$data['qty']."', 
            '".$data['borrow_status']."', 
            '".$data['addby']."', 
            NOW()  
        ); 
        ";

        //echo $sql;
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }



    function deleteStockBorrowInByID($id){
        $sql = " DELETE FROM tb_stock_log WHERE stock_log_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    

}
?>