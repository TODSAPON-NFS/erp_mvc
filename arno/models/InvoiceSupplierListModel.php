<?php

require_once("BaseModel.php");
class InvoiceSupplierListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getInvoiceSupplierListBy($invoice_supplier_id){
        $sql = " SELECT tb_invoice_supplier_list.product_id, 
        invoice_supplier_list_id,
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        tb_invoice_supplier_list.purchase_order_list_id,
        purchase_order_list_price,
        tb_invoice_supplier_list.stock_group_id,
        invoice_supplier_list_product_name, 
        invoice_supplier_list_product_detail, 
        invoice_supplier_list_qty, 
        invoice_supplier_list_duty_percent, 
        invoice_supplier_list_price, 
        invoice_supplier_list_total, 
        invoice_supplier_list_cost, 
        invoice_supplier_list_remark 
        FROM tb_invoice_supplier_list 
        LEFT JOIN tb_product ON tb_invoice_supplier_list.product_id = tb_product.product_id 
        LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id 
        WHERE invoice_supplier_id = '$invoice_supplier_id' 
        ORDER BY invoice_supplier_list_id 
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


    function insertInvoiceSupplierList($data = []){
        $sql = " INSERT INTO tb_invoice_supplier_list (
            invoice_supplier_id,
            product_id,
            invoice_supplier_list_product_name,
            invoice_supplier_list_product_detail,
            invoice_supplier_list_duty_percent,
            invoice_supplier_list_qty,
            invoice_supplier_list_price, 
            invoice_supplier_list_total,
            invoice_supplier_list_remark,
            stock_group_id,
            purchase_order_list_id,
            invoice_supplier_list_cost,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['invoice_supplier_id']."', 
            '".$data['product_id']."', 
            '".$data['invoice_supplier_list_product_name']."', 
            '".$data['invoice_supplier_list_product_detail']."', 
            '".$data['invoice_supplier_list_duty_percent']."', 
            '".$data['invoice_supplier_list_qty']."', 
            '".$data['invoice_supplier_list_price']."', 
            '".$data['invoice_supplier_list_total']."', 
            '".$data['invoice_supplier_list_remark']."',
            '".$data['stock_group_id']."', 
            '".$data['purchase_order_list_id']."', 
            '".$data['invoice_supplier_list_cost']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            $id = mysqli_insert_id(static::$db);

            $sql = "
                CALL insert_stock_supplier('".
                $data['stock_group_id']."','".
                $id."','".
                $data['product_id']."','".
                $data['invoice_supplier_list_qty']."','".
                $data['stock_date']."','".
                $data['invoice_supplier_list_cost']."'".
                ");
            ";

            //echo $sql . "<br><br>";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateInvoiceSupplierListById($data,$id){

        $sql = " UPDATE tb_invoice_supplier_list 
            SET product_id = '".$data['product_id']."', 
            invoice_supplier_list_product_name = '".$data['invoice_supplier_list_product_name']."',  
            invoice_supplier_list_product_detail = '".$data['invoice_supplier_list_product_detail']."', 
            invoice_supplier_list_qty = '".$data['invoice_supplier_list_qty']."', 
            invoice_supplier_list_price = '".$data['invoice_supplier_list_price']."', 
            invoice_supplier_list_total = '".$data['invoice_supplier_list_price_sum']."', 
            invoice_supplier_list_remark = '".$data['invoice_supplier_list_remark']."', 
            stock_group_id = '".$data['stock_group_id']."', 
            invoice_supplier_list_cost = '".$data['invoice_supplier_list_cost']."', 
            purchase_order_list_id = '".$data['purchase_order_list_id']."' 
            WHERE invoice_supplier_list_id = '$id' 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $sql = "
                CALL update_stock_supplier('".
                $data['stock_group_id']."','".
                $id."','".
                $data['product_id']."','".
                $data['stock_date']."','".
                $data['old_qty']."','". 
                $data['old_cost']."','". 
                $data['invoice_supplier_list_qty']."','". 
                $data['invoice_supplier_list_cost']."'".
                ");
            ";

            //echo $sql . "<br><br>";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

           return true;
        }else {
            return false;
        }
    }


    function updateDutyPercentListById($invoice_supplier_list_duty_percent,$id){

        $sql = " UPDATE tb_invoice_supplier_list 
            SET invoice_supplier_list_duty_percent = '".$invoice_supplier_list_duty_percent."' 
            WHERE invoice_supplier_list_id = '$id' 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }




    function deleteInvoiceSupplierListByID($id){
        $sql = "DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceSupplierListByInvoiceSupplierID($id){


        $sql = "DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceSupplierListByInvoiceSupplierIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= $data[$i];
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        $sql = "    SELECT invoice_supplier_list_id, stock_group_id, product_id,  invoice_supplier_list_qty, invoice_supplier_list_cost
                    FROM  tb_invoice_supplier_list 
                    WHERE invoice_supplier_id = '$id' 
                    AND invoice_supplier_list_id NOT IN ($str) ";   
        //echo $sql . "<br><br>";
        $sql_delete=[];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $sql_delete [] = "
                    CALL delete_stock_supplier('".
                    $row['stock_group_id']."','".
                    $row['invoice_supplier_list_id']."','".
                    $row['product_id']."','".
                    $row['invoice_supplier_list_qty']."','".
                    $row['invoice_supplier_list_cost']."'".
                    ");
                ";
            
            }
            $result->close();
        }

        for($i = 0 ; $i < count($sql_delete); $i++){
            //echo $sql_delete[$i] . "<br><br>";
            mysqli_query(static::$db,$sql_delete[$i], MYSQLI_USE_RESULT);
        }



        $sql = "DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_id = '$id' AND invoice_supplier_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>