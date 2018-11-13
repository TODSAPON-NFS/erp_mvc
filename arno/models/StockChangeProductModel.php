<?php

require_once("BaseModel.php");
class StockChangeProductModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockChangeProductBy($date_start  = '', $date_end  = ''){
        $sql = " SELECT stock_change_product_id, 
        tb_stock_change_product.stock_group_id,  
        stock_change_product_code, 
        stock_change_product_date, 
        stock_group_name, 
        stock_change_product_remark, 
        IFNULL(CONCAT(user_name,' ',user_lastname),'-') as employee_name 
        FROM tb_stock_change_product 
        LEFT JOIN tb_user ON tb_stock_change_product.employee_id = tb_user.user_id 
        LEFT JOIN tb_stock_group  ON tb_stock_change_product.stock_group_id = tb_stock_group.stock_group_id  
        ORDER BY STR_TO_DATE(stock_change_product_date,'%Y-%m-%d %H:%i:%s') DESC 
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

    function getStockChangeProductByID($id){
        $sql = " SELECT stock_change_product_id, 
        tb_stock_change_product.employee_id, 
        tb_stock_change_product.stock_group_id,  
        stock_group_name,  
        stock_change_product_code, 
        stock_change_product_date,  
        stock_change_product_remark, 
        IFNULL(CONCAT(user_name,' ',user_lastname),'-') as employee_name 
        FROM tb_stock_change_product 
        LEFT JOIN tb_user ON tb_stock_change_product.employee_id = tb_user.user_id  
        LEFT JOIN tb_stock_group  ON tb_stock_change_product.stock_group_id = tb_stock_group.stock_group_id 
        WHERE stock_change_product_id = '$id' 
        ";
 

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getStockChangeProductViewByID($id){
        $sql = " SELECT stock_change_product_id, 
        tb_stock_change_product.employee_id, 
        tb_stock_change_product.stock_group_id,  
        stock_change_product_code, 
        stock_change_product_date,
        stock_group_name, 
        stock_change_product_remark, 
        IFNULL(CONCAT(user_name,' ',user_lastname),'-') as employee_name 
        FROM tb_stock_change_product 
        LEFT JOIN tb_user ON tb_stock_change_product.employee_id = tb_user.user_id 
        LEFT JOIN tb_stock_group ON tb_stock_change_product.stock_group_id = tb_stock_group.stock_group_id  
        WHERE stock_change_product_id = '$id' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getStockChangeProductLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(stock_change_product_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  stock_change_product_lastcode 
        FROM tb_stock_change_product 
        WHERE stock_change_product_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['stock_change_product_lastcode'];
        }

    }

   
    function updateStockChangeProductByID($id,$data = []){
        $sql = " UPDATE tb_stock_change_product SET 
        stock_group_id = '".$data['stock_group_id']."',  
        employee_id = '".$data['employee_id']."', 
        stock_change_product_code = '".$data['stock_change_product_code']."', 
        stock_change_product_date = '".$data['stock_change_product_date']."', 
        stock_change_product_remark = '".$data['stock_change_product_remark']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE stock_change_product_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertStockChangeProduct($data = []){
        $sql = " INSERT INTO tb_stock_change_product (
            stock_group_id,  
            employee_id,
            stock_change_product_code,
            stock_change_product_date,
            stock_change_product_remark,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['stock_group_id']."','". 
        $data['employee_id']."','".
        $data['stock_change_product_code']."','".
        $data['stock_change_product_date']."','".
        $data['stock_change_product_remark']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        "; 
        
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }



    function deleteStockChangeProductByID($id){

        /*------------------------------ คำนวนต้นทุนสินค้าในกรณีลบ ---------------------------*/



        /*------------------------------ สิ้นสุด คำนวนต้นทุนสินค้าในกรณีลบ ----------------------*/
 

        $sql = " DELETE FROM tb_stock_change_product_list WHERE stock_change_product_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_stock_change_product WHERE stock_change_product_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>