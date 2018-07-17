<?php

require_once("BaseModel.php");
class ProductModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProductBy($supplier_id = '', $product_category_id = '', $product_type_id = '', $keyword  = ''){
        
        if($supplier_id != ""){
            $supplier = "AND supplier_id = '$supplier_id' ";
        }


        
        if($product_type_id != ""){
            $product_type = "AND product_type_id = '$product_type_id' ";
        }



        if($product_type_id != ""){
            $product_category = "AND tb_product.product_category_id = '$product_category_id' ";
        }
        
        if($keyword != ""){
            $sts_keyword = " AND (product_name LIKE ('%$keyword%') OR product_code LIKE ('%$keyword%') ) ";
        }

        
        $sql = " SELECT product_id, CONCAT(product_code_first,product_code) as product_code, product_drawing, product_name, product_description , product_type, product_status   
        FROM tb_product 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id 
        WHERE 1 
        $sts_keyword
        $product_type 
        $product_category 
        $supplier 
        ORDER BY product_name  
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

    function getProduct(){
        
        
        $sql = " SELECT product_id, CONCAT(product_code_first,product_code) as product_code, product_drawing, product_name, product_description   
        FROM tb_product 
        ORDER BY product_name  
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

    function getProductByID($id){
        $sql = " SELECT * 
        FROM tb_product 
        LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        LEFT JOIN tb_product_group ON tb_product.product_group = tb_product_group.product_group_id 
        LEFT JOIN tb_product_unit ON tb_product.product_unit = tb_product_unit.product_unit_id 
        WHERE product_id = '$id' 
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

    function getProductDataByName($product_name){
        $sql = "SELECT * 
        FROM tb_product 
        WHERE product_name = '$product_name' 
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

    function getProductDataByID($product_id,$stock_group_id){
        $sql = "SELECT table_name FROM tb_stock_group WHERE tb_stock_group.stock_group_id = '$stock_group_id'";
        $table_name ="";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $table_name = $row['table_name'];
            }
            $result->close();
        }

        $sql_in = "SELECT SUM(qty) 
        FROM ".$table_name."  
        WHERE ".$table_name.".product_id = tb.product_id 
        AND stock_type = 'in' ";

        $sql_out = "SELECT SUM(qty) 
        FROM ".$table_name." 
        WHERE ".$table_name.".product_id = tb.product_id 
        AND stock_type = 'out' ";

        $sql = " SELECT product_name, product_buyprice as product_price, (IFNULL(($sql_in),0) - IFNULL(($sql_out),0)) as product_qty  
        FROM tb_product as tb 
        LEFT JOIN tb_product_supplier ON tb.product_id = tb_product_supplier.product_id 
        WHERE tb.product_id = '$product_id' 
        AND product_supplier_status = 'Active' 
        ";

        //echo $sql;
        

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }


    function getProductDataByCode($product_code,$stock_group_id,$qty){
        $sql = "SELECT table_name FROM tb_stock_group WHERE tb_stock_group.stock_group_id = '$stock_group_id'";
        $table_name ="";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $table_name = $row['table_name'];
            }
            $result->close();
        }

        $sql_in = "SELECT SUM(qty) 
        FROM ".$table_name."  
        WHERE ".$table_name.".product_id = tb.product_id 
        AND stock_type = 'in' ";

        $sql_out = "SELECT SUM(qty) 
        FROM ".$table_name." 
        WHERE ".$table_name.".product_id = tb.product_id 
        AND stock_type = 'out' ";

        $sql = " SELECT product_code, product_name, product_buyprice as product_price, $qty as product_qty  
        FROM tb_product as tb 
        LEFT JOIN tb_product_supplier ON tb.product_id = tb_product_supplier.product_id 
        WHERE tb.product_code = '$product_code' 
        AND product_supplier_status = 'Active' 
        ";

        //echo $sql;
        

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
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
        product_status = '".$data['product_status']."', 
        product_category_id = '".$data['product_category_id']."' 
        WHERE product_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
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
            product_status, 
            product_category_id
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
            '".$data['product_status']."', 
            '".$data['product_category_id']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }




    function deleteProductByID($id){
        $sql = " DELETE FROM tb_product WHERE product_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>