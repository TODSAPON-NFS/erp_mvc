<?php

require_once("BaseModel.php");
class InvoiceCustomerModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getInvoiceCustomerBy($date_start  = '', $date_end  = '', $status ="Waiting"){
        $sql = " SELECT invoice_customer_id, 
        invoice_customer_code, 
        invoice_customer_date, 
        invoice_customer_total_price,
        invoice_customer_vat_price,
        invoice_customer_net_price,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        invoice_customer_term, 
        invoice_customer_due, 
        IFNULL(CONCAT(tb2.customer_name_en,' (',tb2.customer_name_th,')'),'-') as customer_name  
        FROM tb_invoice_customer 
        LEFT JOIN tb_user as tb1 ON tb_invoice_customer.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_invoice_customer.customer_id = tb2.customer_id 
        ORDER BY STR_TO_DATE(invoice_customer_date,'%Y-%m-%d %H:%i:%s') DESC 
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

    function getInvoiceCustomerByID($id){
        $sql = " SELECT * 
        FROM tb_invoice_customer 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE invoice_customer_id = '$id' 
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

    function getInvoiceCustomerViewByID($id){
        $sql = " SELECT *   
        FROM tb_invoice_customer 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user.user_position_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE invoice_customer_id = '$id' 
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

    function getInvoiceCustomerLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(invoice_customer_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  invoice_customer_lastcode 
        FROM tb_invoice_customer
        WHERE invoice_customer_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['invoice_customer_lastcode'];
        }

    }


   
    function updateInvoiceCustomerByID($id,$data = []){
        $sql = " UPDATE tb_invoice_customer SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        invoice_customer_code = '".$data['invoice_customer_code']."', 
        invoice_customer_total_price = '".$data['invoice_customer_total_price']."', 
        invoice_customer_vat = '".$data['invoice_customer_vat']."', 
        invoice_customer_vat_price = '".$data['invoice_customer_vat_price']."', 
        invoice_customer_net_price = '".$data['invoice_customer_net_price']."', 
        invoice_customer_date = '".$data['invoice_customer_date']."', 
        invoice_customer_name = '".$data['invoice_customer_name']."', 
        invoice_customer_address = '".$data['invoice_customer_address']."', 
        invoice_customer_tax = '".$data['invoice_customer_tax']."', 
        invoice_customer_term = '".$data['invoice_customer_term']."', 
        invoice_customer_due = '".$data['invoice_customer_due']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE invoice_customer_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function getCustomerOrder(){

        $sql = "SELECT tb_customer.customer_id, customer_name_en , customer_name_th 
                FROM tb_customer 
                WHERE customer_id IN ( 
                    SELECT DISTINCT customer_id 
                    FROM tb_customer_purchase_order 
                    LEFT JOIN tb_customer_purchase_order_list ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id
                    WHERE invoice_customer_list_id = 0 
                ) 
        ";
        $data = [];
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }
        return $data;
    }

    function generateInvoiceCustomerListByCustomerId($customer_id, $data = []){

        $str ='0';

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

        $sql_customer = "SELECT tb_customer_purchase_order_list.product_id, 
        customer_purchase_order_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,  
        customer_purchase_order_list_qty as invoice_customer_list_qty, 
        customer_purchase_order_list_price as invoice_customer_list_price, 
        customer_purchase_order_list_price_sum as invoice_customer_list_total, 
        CONCAT('Order for customer purchase order ',customer_purchase_order_code) as invoice_customer_list_remark 
        FROM tb_customer_purchase_order 
        LEFT JOIN tb_customer_purchase_order_list ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id 
        LEFT JOIN tb_product ON tb_customer_purchase_order_list.product_id = tb_product.product_id 
        WHERE customer_id = '$customer_id' 
        AND customer_purchase_order_list_id NOT IN ($str) 
        AND invoice_customer_list_id = 0 ";


        $data = [];
        if ($result = mysqli_query($this->db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }

    function insertInvoiceCustomer($data = []){
        $sql = " INSERT INTO tb_invoice_customer (
            customer_id,
            employee_id,
            invoice_customer_code,
            invoice_customer_total_price,
            invoice_customer_vat,
            invoice_customer_vat_price,
            invoice_customer_net_price,
            invoice_customer_date,
            invoice_customer_name,
            invoice_customer_address,
            invoice_customer_tax,
            invoice_customer_term,
            invoice_customer_due,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['invoice_customer_code']."','".
        $data['invoice_customer_total_price']."','".
        $data['invoice_customer_vat']."','".
        $data['invoice_customer_vat_price']."','".
        $data['invoice_customer_net_price']."','".
        $data['invoice_customer_date']."','".
        $data['invoice_customer_name']."','".
        $data['invoice_customer_address']."','".
        $data['invoice_customer_tax']."','".
        $data['invoice_customer_term']."','".
        $data['invoice_customer_due']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";


        //echo $sql;
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }


    function deleteInvoiceCustomerByID($id){


        $sql = " UPDATE tb_customer_purchase_order_list SET invoice_customer_list_id = '0' WHERE invoice_customer_list_id IN (SELECT invoice_customer_list_id FROM tb_invoice_customer_list WHERE invoice_customer_id = '$id') ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_invoice_customer WHERE invoice_customer_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_invoice_customer_list WHERE invoice_customer_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }


}
?>