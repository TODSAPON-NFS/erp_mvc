<?php

require_once("BaseModel.php");
class StockGroupModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getStockGroupBy(){
        $sql = "  SELECT * FROM tb_stock_group WHERE 1 ";
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getStockGroupByID($stock_group_id){
        $sql = "SELECT * FROM tb_stock_group WHERE stock_group_id = $stock_group_id ";
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }

    }


    function getQtyBy($stock_group_id,$product_id){
        $sql = "SELECT * FROM tb_stock_group WHERE stock_group_id = $stock_group_id ";
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();

            $sql_in = "SELECT SUM(qty) 
            FROM ".$data['table_name']."  
            WHERE ".$data['table_name'].".product_id = tb.product_id 
            AND stock_type = 'in' ";


            $sql_out = "SELECT SUM(qty) 
            FROM ".$data['table_name']."  
            WHERE ".$data['table_name'].".product_id = tb.product_id 
            AND stock_type = 'out' ";


    

            $sql = "SELECT product_id,  CONCAT(product_code_first,product_code) as product_code, 
            product_name, product_type, product_status ,
            (IFNULL(($sql_in),0) - IFNULL(($sql_out),0)) as stock_old 
            FROM tb_product as  tb
            WHERE product_status = 'Active' 
            AND product_id = '$product_id' 
            ORDER BY CONCAT(product_code_first,product_code) 
            ";

            //echo $sql;

            if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
               
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data = $row;
                }
                $result->close();
                return $data;
            }
        }

    }


    function updateTableName($stock_group_id,$table_name){
        $sql = " UPDATE tb_stock_group SET 
        table_name = '".$table_name."' 
        WHERE stock_group_id = $stock_group_id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function updateStockGroupByID($id,$data = []){
        $sql = " UPDATE tb_stock_group SET 
        stock_group_name = '".$data['stock_group_name']."' , 
        stock_group_detail = '".$data['stock_group_detail']."' , 
        stock_group_notification = '".$data['stock_group_notification']."' , 
        stock_group_day = '".$data['stock_group_day']."' , 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE stock_group_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertStockGroup($data = []){
        $sql = " INSERT INTO tb_stock_group (
            stock_group_name, 
            stock_group_detail, 
            stock_group_notification, 
            stock_group_day, 
            table_name, 
            addby,
            adddate
        ) VALUES (  
            '".$data['stock_group_name']."', 
            '".$data['stock_group_detail']."', 
            '".$data['stock_group_notification']."', 
            '".$data['stock_group_day']."', 
            '".$data['table_name']."', 
            '".$data['addby']."', 
            NOW()  
        ); 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }


    function deleteStockGroupByID($id){
        $sql = " DELETE FROM tb_stock_group WHERE stock_group_id = '$id' ";
        if(mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }
    }

}
?>