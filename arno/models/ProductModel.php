<?php

require_once("BaseModel.php");
class ProductModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getProductBy($product_name = '', $product_code = '', $product_type = '', $product_status  = ''){
        $sql = " SELECT product_id, CONCAT(product_code_first,product_code) as product_code, product_drawing, product_name, product_description , product_type, product_status   
        FROM tb_product 
        WHERE product_name LIKE ('%$product_name%') 
        OR product_code LIKE ('%$product_code%') 
        OR product_type LIKE ('%$product_type%') 
        OR product_status LIKE ('%$product_status%') 
        ORDER BY product_name  
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

    function getProductByID($id){
        $sql = " SELECT * 
        FROM tb_product 
        WHERE product_id = '$id' 
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

    
    

    function updateProductByID($id,$data = []){
        $sql = " UPDATE tb_product SET 
        product_code_first = '".$data['product_code_first']."', 
        product_code = '".$data['product_code']."', 
        product_name = '".$data['product_name']."', 
        product_group = '".$data['product_group']."', 
        product_barcode = '".$data['product_barcode']."', 
        product_description = '".$data['product_description']."', 
        product_type = '".$data['product_type']."', 
        product_unit = '".$data['product_unit']."', 
        product_drawing = '".$data['product_drawing']."', 
        product_logo = '".$data['product_logo']."',
        product_status = '".$data['product_status']."'  
        WHERE product_id = $id 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertProduct($data = []){
        $sql = " INSERT INTO tb_product (
            product_code_first, 
            product_code,
            product_name,
            product_group,
            product_barcode,
            product_description,
            product_type,
            product_unit,
            product_drawing,
            product_logo,
            product_status 
        ) VALUES (
            '".$data['product_code_first']."', 
            '".$data['product_code']."', 
            '".$data['product_name']."', 
            '".$data['product_group']."', 
            '".$data['product_barcode']."', 
            '".$data['product_description']."', 
            '".$data['product_type']."', 
            '".$data['product_unit']."', 
            '".$data['product_drawing']."', 
            '".$data['product_logo']."', 
            '".$data['product_status']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }




    function deleteProductByID($id){
        $sql = " DELETE FROM tb_product WHERE product_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>