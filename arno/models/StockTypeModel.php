<?php

require_once("BaseModel.php");
class StockTypeModel extends BaseModel{



    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }



    function getStockTypeBy(){
        $sql = "  SELECT * FROM tb_stock_type WHERE 1 ";
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    
    function getStockTypeByID($stock_type_id){
        $sql = "SELECT * FROM tb_stock_type WHERE stock_type_id = $stock_type_id ";
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }

    }



    function updateStockTypeByID($id,$data = []){
        $sql = " UPDATE tb_stock_type SET 
        stock_type_code = '".$data['stock_type_code']."' , 
        stock_type_name = '".$data['stock_type_name']."' , 
        stock_group_id = '".$data['stock_group_id']."'  
        WHERE stock_type_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function updateStockPrimaryByID($id,$stock_group_id){
        $sql = " UPDATE tb_stock_type SET 
        stock_group_id = '".$stock_group_id."'  
        WHERE stock_type_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function insertStockType($data = []){
        $sql = " INSERT INTO tb_stock_type (
            stock_type_code, 
            stock_type_name, 
            stock_group_id 
        ) VALUES (  
            '".$data['stock_type_code']."', 
            '".$data['stock_type_name']."', 
            '".$data['stock_group_id']."'  
        ); 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }



    function deleteStockTypeByID($id){
        $sql = " DELETE FROM tb_stock_type WHERE stock_type_id = '$id' ";
        if(mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }
    }

}
?>