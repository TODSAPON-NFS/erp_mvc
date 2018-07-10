<?php

require_once("BaseModel.php");
class StockReportModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getStockReportListBy($stock_group_id = '', $keyword = ''){

        if($stock_group_id != ""){
            $str_stock = " AND tb_stock_report.stock_group_id = '$stock_group_id' ";
        }
        $sql = "    SELECT * 
                    FROM tb_product 
                    LEFT JOIN tb_stock_report ON tb_product.product_id = tb_stock_report.product_id 
                    LEFT JOIN tb_stock_group ON tb_stock_report.stock_group_id = tb_stock_group.stock_group_id  
                    LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id  
                    WHERE ( product_name LIKE ('%$keyword%') OR product_description LIKE ('%$keyword%') OR CONCAT(product_code_first,product_code) LIKE ('%$keyword%') )
                    $str_stock 
                    GROUP BY  tb_product.product_id, tb_stock_report.stock_group_id 
                    ORDER BY  tb_product.product_id, tb_stock_report.stock_group_id ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function updateCostWhenInsert($stock_group_id, $product_id, $qty, $cost){
        $stock_qty = 0;
        $stock_cost = 0.0;
        
        $new_qty = 0;
        $new_cost = 0.0;
        
        
        $str = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
            if(count($data) > 0){


                $new_qty = $data['stock_report_qty'] + $qty;
                $new_cost = (($data['stock_report_qty'] * $data['stock_report_cost_avg']) + ($qty * $cost))/$new_qty;
                 
    
                $str = "UPDATE tb_stock_report SET
                stock_report_qty = '$new_qty' , 
                stock_report_cost_avg = '$new_cost' 
                WHERE stock_group_id = '$stock_group_id' 
                AND product_id = '$product_id' ";
    
                if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
                    return true;
                }else {
                    return false;
                }
            }else{
                return false;
            }
        } 
    }

    function updateCostWhenUpdate($stock_group_id, $product_id, $qty_old, $cost_old, $qty_new, $cost_new  ){
        $stock_qty = 0;
        $stock_cost = 0.0;
        
        $new_qty = 0;
        $new_cost = 0.0;
        
        
        $str = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
            if(count($data) > 0){


                $new_qty_out = $data['stock_report_qty'] - $qty_old;
                $new_cost = (($data['stock_report_qty'] * $data['stock_report_cost_avg']) - ($qty_old * $cost_old))/$new_qty_out;
                 
                $new_qty = $new_qty_out + $qty_new;
                $new_cost = (($new_qty_out * $new_cost) + ($qty_new * $cost_new))/$new_qty;
    
                $str = "UPDATE tb_stock_report SET
                stock_report_qty = '$new_qty' , 
                stock_report_cost_avg = '$new_cost' 
                WHERE stock_group_id = '$stock_group_id' 
                AND product_id = '$product_id' ";
    
                if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
                    return true;
                }else {
                    return false;
                }
            }else{
                return false;
            }
        }    
    }

    function updateCostWhenDelete($stock_group_id, $product_id, $qty, $cost){
        $stock_qty = 0;
        $stock_cost = 0.0;
        
        $new_qty = 0;
        $new_cost = 0.0;
        
        
        $str = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
            if(count($data) > 0){
                $new_qty = $data['stock_report_qty'] - $qty;
                $new_cost = (($data['stock_report_qty'] * $data['stock_report_cost_avg']) - ($qty * $cost))/$new_qty;
                 
    
                $str = "UPDATE tb_stock_report SET
                stock_report_qty = '$new_qty' , 
                stock_report_cost_avg = '$new_cost' 
                WHERE stock_group_id = '$stock_group_id' 
                AND product_id = '$product_id' ";
    
                if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
                    return true;
                }else {
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    function insertStockReportRow($stock_group_id, $product_id){

        $str = "SELECT *  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {

            

            if(mysqli_num_rows($result) == 0){
                $sql = " INSERT INTO tb_stock_report (
                    stock_group_id,
                    product_id
                ) 
                VALUES ('$stock_group_id','$product_id'); 
                ";
        
                //echo $sql;
                if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
                    return mysqli_insert_id($this->db);
                }else {
                    return 0;
                }
            }else{
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                return $row['stock_report_id'];
            }

        }

    }



}
?>