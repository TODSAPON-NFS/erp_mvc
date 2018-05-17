<?php

require_once("BaseModel.php");
class CustomerPurchaseOrderModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getCustomerPurchaseOrderBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        $sql = " SELECT customer_purchase_order_id, 
        customer_purchase_order_code, 
        customer_purchase_order_date, 
        customer_purchase_order_status, 
        customer_purchase_order_file, 
        customer_purchase_order_remark, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        user_position_name,
        customer_purchase_order_credit_term, 
        customer_purchase_order_delivery_term, 
        IFNULL(CONCAT(tb2.customer_name_en,' (',tb2.customer_name_th,')'),'-') as customer_name, 
        customer_purchase_order_delivery_by 
        FROM tb_customer_purchase_order 
        LEFT JOIN tb_user as tb1 ON tb_customer_purchase_order.employee_id = tb1.user_id 
        LEFT JOIN tb_user_position  ON tb1.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer as tb2 ON tb_customer_purchase_order.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  customer_purchase_order_code LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(customer_purchase_order_date,'%Y-%m-%d %H:%i:%s'),customer_purchase_order_code DESC 
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

    function getCustomerPurchaseOrderByID($id){
        $sql = " SELECT * 
        FROM tb_customer_purchase_order 
        LEFT JOIN tb_customer ON tb_customer_purchase_order.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_customer_purchase_order.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position  ON tb_user.user_position_id = tb_user_position.user_position_id 
        WHERE customer_purchase_order_id = '$id' 
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

    function getCustomerPurchaseOrderViewByID($id){
        $sql = " SELECT *   
        FROM tb_customer_purchase_order 
        LEFT JOIN tb_user ON tb_customer_purchase_order.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_customer_purchase_order.customer_id = tb_customer.customer_id 
        WHERE customer_purchase_order_id = '$id' 
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

    function generateCustomerPurchaseOrderByID($id){
        $sql = " SELECT * 
        FROM tb_quotation 
        LEFT JOIN tb_customer ON tb_quotation.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_quotation.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position  ON tb_user.user_position_id = tb_user_position.user_position_id 
        WHERE quotation_id = '$id' 
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

    function generateCustomerPurchaseOrderListBy($quotation_id){
        $sql = " SELECT tb_quotation_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        '' as customer_purchase_order_product_name, 
        '' as customer_purchase_order_product_detail, 
        '0' as customer_purchase_order_list_id,  
        quotation_list_qty as customer_purchase_order_list_qty, 
        quotation_list_price as customer_purchase_order_list_price, 
        quotation_list_sum as customer_purchase_order_list_price_sum, 
        '' as customer_purchase_order_list_delivery_min,  
        '' as customer_purchase_order_list_delivery_max, 
        quotation_list_remark as customer_purchase_order_list_remark 
        FROM tb_quotation_list LEFT JOIN tb_product ON tb_quotation_list.product_id = tb_product.product_id 
        WHERE quotation_id = '$quotation_id' 
        ORDER BY quotation_list_id 
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

   
    function updateCustomerPurchaseOrderByID($id,$data = []){
        $sql = " UPDATE tb_customer_purchase_order SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        customer_purchase_order_code = '".$data['customer_purchase_order_code']."', 
        customer_purchase_order_file = '".$data['customer_purchase_order_file']."', 
        customer_purchase_order_credit_term = '".$data['customer_purchase_order_credit_term']."', 
        customer_purchase_order_delivery_term = '".$data['customer_purchase_order_delivery_term']."', 
        customer_purchase_order_delivery_by = '".$data['customer_purchase_order_delivery_by']."', 
        customer_purchase_order_date = '".$data['customer_purchase_order_date']."', 
        customer_purchase_order_remark = '".$data['customer_purchase_order_remark']."', 
        customer_purchase_order_status = '".$data['customer_purchase_order_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE customer_purchase_order_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertCustomerPurchaseOrder($data = []){
        $sql = " INSERT INTO tb_customer_purchase_order (
            customer_id,
            employee_id,           
            customer_purchase_order_code,
            customer_purchase_order_file,
            customer_purchase_order_credit_term,
            customer_purchase_order_delivery_term,
            customer_purchase_order_delivery_by,
            customer_purchase_order_date,
            customer_purchase_order_remark,
            customer_purchase_order_status,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['customer_purchase_order_code']."','".
        $data['customer_purchase_order_file']."','".
        $data['customer_purchase_order_credit_term']."','".
        $data['customer_purchase_order_delivery_term']."','".
        $data['customer_purchase_order_delivery_by']."','".
        $data['customer_purchase_order_date']."','".
        $data['customer_purchase_order_remark']."','".
        $data['customer_purchase_order_status']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }


    function deleteCustomerPurchaseOrderByID($id){
        $sql = " DELETE FROM tb_customer_purchase_order WHERE customer_purchase_order_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_customer_purchase_order_list WHERE customer_purchase_order_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>