<?php

require_once("BaseModel.php");
class InvoiceCustomerListModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getInvoiceCustomerListBy($invoice_customer_id){
        $sql = " SELECT tb_invoice_customer_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        invoice_customer_list_id,  
        invoice_customer_list_product_name, 
        invoice_customer_list_product_detail, 
        invoice_customer_list_qty, 
        invoice_customer_list_price, 
        invoice_customer_list_total, 
        invoice_customer_list_remark,
        customer_purchase_order_list_id,
        stock_group_id
        FROM tb_invoice_customer_list LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id 
        WHERE invoice_customer_id = '$invoice_customer_id' 
        ORDER BY invoice_customer_list_id 
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


    function insertInvoiceCustomerList($data = []){
        $sql = " INSERT INTO tb_invoice_customer_list (
            invoice_customer_id,
            product_id,
            invoice_customer_list_product_name,
            invoice_customer_list_product_detail,
            invoice_customer_list_qty,
            invoice_customer_list_price, 
            invoice_customer_list_total,
            invoice_customer_list_remark,
            customer_purchase_order_list_id,
            stock_group_id,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['invoice_customer_id']."', 
            '".$data['product_id']."', 
            '".$data['invoice_customer_list_product_name']."', 
            '".$data['invoice_customer_list_product_detail']."', 
            '".$data['invoice_customer_list_qty']."', 
            '".$data['invoice_customer_list_price']."', 
            '".$data['invoice_customer_list_total']."', 
            '".$data['invoice_customer_list_remark']."',
            '".$data['customer_purchase_order_list_id']."', 
            '".$data['stock_group_id']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $id = mysqli_insert_id($this->db);

            $sql = "
                CALL insert_stock('".
                $data['stock_group_id']."','".
                $id."','".
                $data['product_id']."','".
                $data['invoice_customer_list_qty']."','".
                $data['stock_date']."','out');
            ";

            //echo $sql . "<br><br>";

            mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateInvoiceCustomerListById($data,$id){

        $sql = " UPDATE tb_invoice_customer_list 
            SET product_id = '".$data['product_id']."', 
            invoice_customer_list_product_name = '".$data['invoice_customer_list_product_name']."', 
            invoice_customer_list_product_detail = '".$data['invoice_customer_list_product_detail']."',
            invoice_customer_list_qty = '".$data['invoice_customer_list_qty']."',
            invoice_customer_list_price = '".$data['invoice_customer_list_price']."', 
            invoice_customer_list_total = '".$data['invoice_customer_list_price_sum']."',
            invoice_customer_list_remark = '".$data['invoice_customer_list_remark']."', 
            customer_purchase_order_list_id = '".$data['customer_purchase_order_list_id']."',
            stock_group_id = '".$data['stock_group_id']."'
            WHERE invoice_customer_list_id = '$id'
        ";
       //echo $sql . "<br><br>";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $sql = "
                CALL update_stock('".
                $data['stock_group_id']."','".
                $id."','".
                $data['product_id']."','".
                $data['invoice_customer_list_qty']."','".
                $data['stock_date']."','out');
            ";

            //echo $sql . "<br><br>";

            mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);
           return true;
        }else {
            return false;
        }
    }

    function updateCustomerPurchaseOrderListID($customer_purchase_order_list_id,$invoice_customer_list_id){
        $sql = " UPDATE tb_customer_purchase_order_list 
            SET invoice_customer_list_id = '$invoice_customer_list_id' 
            WHERE customer_purchase_order_list_id = '$customer_purchase_order_list_id' 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function deleteInvoiceCustomerListByID($id){
        $sql = "DELETE FROM tb_invoice_customer_list WHERE invoice_customer_list_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceCustomerListByInvoiceCustomerID($id){

        $sql = "UPDATE  tb_customer_purchase_order_list SET invoice_customer_list_id = '0'  WHERE invoice_customer_list_id IN (SELECT invoice_customer_list_id FROM tb_invoice_customer_list WHERE invoice_customer_id = '$id') ";     
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);


        $sql = "DELETE FROM tb_invoice_customer_list WHERE invoice_customer_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceCustomerListByInvoiceCustomerIDNotIN($id,$data){
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

        $sql = "    SELECT invoice_customer_list_id, stock_group_id 
                    FROM  tb_invoice_customer_list 
                    WHERE invoice_customer_id = '$id' 
                    AND invoice_customer_list_id NOT IN ($str) ";   

        $sql_delete=[];
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $sql_delete [] = "
                    CALL delete_stock('".
                    $row['stock_group_id']."','".
                    $row['invoice_customer_list_id']."','out');
                ";
               
            }
            $result->close();
        }

        for($i = 0 ; $i < count($sql_delete); $i++){
            mysqli_query($this->db,$sql_delete[$i], MYSQLI_USE_RESULT);
        }

        $sql = "DELETE FROM tb_invoice_customer_list WHERE invoice_customer_id = '$id' AND invoice_customer_list_id NOT IN ($str) ";
     
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>