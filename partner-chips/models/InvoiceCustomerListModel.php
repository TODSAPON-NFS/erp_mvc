<?php

require_once("BaseModel.php");
require_once("MaintenanceStockModel.php"); 
class InvoiceCustomerListModel extends BaseModel{

    private $maintenance_stock;

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }

        $this->maintenance_stock =  new MaintenanceStockModel;
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
        stock_event,
        stock_group_id
        FROM tb_invoice_customer_list 
        LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        WHERE invoice_customer_id = '$invoice_customer_id' 
        ORDER BY invoice_customer_list_no , invoice_customer_list_id 
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


    function getInvoiceCustomerListByID($id){
        $sql = " SELECT *
        FROM tb_invoice_customer_list 
        WHERE invoice_customer_list_id = '$id'  
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


    function insertInvoiceCustomerList($data = []){
        $sql = " INSERT INTO tb_invoice_customer_list (
            invoice_customer_id,
            invoice_customer_list_no,
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
            '".$data['invoice_customer_list_no']."', 
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
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $invoice_customer_list_id = mysqli_insert_id(static::$db);

            $sql = " SELECT stock_event 
                FROM tb_product 
                LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
                WHERE product_id = '".$data['product_id']."' ";
            
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                 $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                 $result->close();
            }

            if($row['stock_event'] != "None"){ 
                $this->maintenance_stock->addSaleStock($data['stock_date'], $data['stock_group_id'], $invoice_customer_list_id, $data['product_id'], $data['invoice_customer_list_qty']);
            } 

            return $invoice_customer_list_id; 
        }else {
            return 0;
        }

    }

    

    function updateInvoiceCustomerListById($data,$id){
        $data_old = $this->getInvoiceCustomerListByID($id);

        $sql = " UPDATE tb_invoice_customer_list 
            SET product_id = '".$data['product_id']."', 
            invoice_customer_list_no = '".$data['invoice_customer_list_no']."', 
            invoice_customer_list_product_name = '".$data['invoice_customer_list_product_name']."', 
            invoice_customer_list_product_detail = '".$data['invoice_customer_list_product_detail']."',
            invoice_customer_list_qty = '".$data['invoice_customer_list_qty']."',
            invoice_customer_list_price = '".$data['invoice_customer_list_price']."', 
            invoice_customer_list_total = '".$data['invoice_customer_list_total']."',
            invoice_customer_list_remark = '".$data['invoice_customer_list_remark']."', 
            customer_purchase_order_list_id = '".$data['customer_purchase_order_list_id']."',
            stock_group_id = '".$data['stock_group_id']."'
            WHERE invoice_customer_list_id = '$id'
        ";
       //echo $sql . "<br><br>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            
            $sql = " SELECT stock_event 
                FROM tb_product 
                LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
                WHERE product_id = '".$data['product_id']."' ";
            
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                 $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                 $result->close();
            }

            if($row['stock_event'] != "None"){
                $this->maintenance_stock->removeSaleStock($data_old['stock_group_id'],$data_old['invoice_customer_list_id'], $data_old['product_id'], $data_old['invoice_customer_list_qty'], $data_old['invoice_customer_list_cost']);
                
            } 
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


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function deleteInvoiceCustomerListByID($id){
        $sql = "DELETE FROM tb_invoice_customer_list WHERE invoice_customer_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceCustomerListByInvoiceCustomerID($id){

        $sql = "UPDATE  tb_customer_purchase_order_list SET invoice_customer_list_id = '0'  WHERE invoice_customer_list_id IN (SELECT invoice_customer_list_id FROM tb_invoice_customer_list WHERE invoice_customer_id = '$id') ";     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


        $sql = "DELETE FROM tb_invoice_customer_list WHERE invoice_customer_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

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

        $sql = "    SELECT * 
                    FROM  tb_invoice_customer_list 
                    LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id 
                    LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
                    WHERE invoice_customer_id = '$id' 
                    AND invoice_customer_list_id NOT IN ($str) 
                ";   

        $data_old=[];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){ 
                if($row['stock_event'] != "None"){
                    $data_old [] = $row; 
                } 
            }
            $result->close();
        }

        for($i = 0 ; $i < count($data_old); $i++){
            $this->maintenance_stock->removeSaleStock($data_old['stock_group_id'],$data_old['invoice_customer_list_id'], $data_old['product_id'], $data_old['invoice_customer_list_qty'], $data_old['invoice_customer_list_cost']);
                
        }

        $sql = "DELETE FROM tb_invoice_customer_list WHERE invoice_customer_id = '$id' AND invoice_customer_list_id NOT IN ($str) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>