<?php

require_once("BaseModel.php");
class CustomerModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getcustomerBy($customer_type = '',$end_user = '',$keyword = '',$keyword_end = ''){

        if($customer_type != ""){
            $str_type = " AND tb1.customer_type_id = '$customer_type' ";
        }

        if($end_user == "0"){
            $str_end = "AND tb1.customer_end_user = '0'"; 
            
        }else if($end_user == "1"){
            $str_end = "AND tb1.customer_end_user > '0'"; 
            
        }

        $sql = " SELECT tb1.customer_id, tb1.customer_code, tb1.customer_name_th, tb1.customer_name_en , tb1.customer_tax , tb1.customer_tel, tb1.customer_email, customer_type_name,  tb2.customer_name_en as customer_end_user_name 
        FROM tb_customer as tb1 
        LEFT JOIN tb_customer as tb2 ON tb1.customer_end_user = tb2.customer_id 
        LEFT JOIN tb_customer_type ON tb1.customer_type_id = tb_customer_type.customer_type_id 
        WHERE ( 
            tb1.customer_code LIKE ('%$keyword%') 
            OR tb1.customer_name_th LIKE ('%$keyword%') 
            OR tb1.customer_name_en LIKE ('%$keyword%') 
            OR tb1.customer_tax LIKE ('%$keyword%') 
            OR tb1.customer_tel LIKE ('%$keyword%') 
            OR tb1.customer_email LIKE ('%$keyword%') 
        ) AND ( 
            tb2.customer_code LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_name_th,'') LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_name_en,'') LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_tax,'') LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_tel,'') LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_email,'') LIKE ('%$keyword_end%') 
        )
        $str_type 
        $str_end  
        ORDER BY tb1.customer_code  
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

    function getCustomerProductBy($customer_id){
        $sql = "SELECT * FROM `tb_customer_purchase_order` 
        LEFT JOIN tb_customer_purchase_order_list ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id
        LEFT JOIN tb_product ON tb_customer_purchase_order_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_customer_price ON tb_customer_purchase_order_list.product_id = tb_product_customer_price.product_id 
        LEFT JOIN tb_user ON tb_customer_purchase_order.employee_id = tb_user.user_id

        WHERE tb_customer_purchase_order.customer_id =  $customer_id

        ";
        // echo "<pre>";
        // print_r($sql);
        // echo"</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getCustomerProductInvoiceBy($customer_id){
        $sql = "SELECT * FROM `tb_customer_purchase_order` 
        LEFT JOIN tb_customer_purchase_order_list ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id
        LEFT JOIN tb_product ON tb_customer_purchase_order_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_customer_price ON tb_customer_purchase_order_list.product_id = tb_product_customer_price.product_id 
        LEFT JOIN tb_user ON tb_customer_purchase_order.employee_id = tb_user.user_id

        LEFT JOIN tb_invoice_customer ON tb_customer_purchase_order.customer_id = tb_invoice_customer.customer_id
        WHERE tb_customer_purchase_order.customer_id =  $customer_id

        ";
        // echo "<pre>";
        // print_r($sql);
        // echo"</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getCustomerProductQuoBy($customer_id){
        $sql = "SELECT * FROM `tb_customer_purchase_order` 
        LEFT JOIN tb_customer_purchase_order_list ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id
        LEFT JOIN tb_product ON tb_customer_purchase_order_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_customer_price ON tb_customer_purchase_order_list.product_id = tb_product_customer_price.product_id 
        LEFT JOIN tb_user ON tb_customer_purchase_order.employee_id = tb_user.user_id

        LEFT JOIN  tb_quotation ON tb_customer_purchase_order.customer_id = tb_quotation.customer_id
        WHERE tb_customer_purchase_order.customer_id =  $customer_id

        ";
        // echo "<pre>";
        // print_r($sql);
        // echo"</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getEndUserByCustomerID($customer_id){
        $sql = " SELECT customer_id, customer_code, customer_name_th, customer_name_en , customer_tax , customer_tel, customer_email ,customer_zipcode  
        FROM tb_customer as tb1
        WHERE customer_end_user = '$customer_id'
        ORDER BY customer_code  
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

    function getCustomerNotEndUserByID($customer_id){
        $sql = " SELECT customer_id, customer_code, customer_name_th, customer_name_en , customer_tax , customer_tel, customer_email   
        FROM tb_customer as tb1
        WHERE customer_end_user = '0' 
        AND customer_id != '$customer_id' 
        ORDER BY customer_code  
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



    function getCustomerBySaleID($sale_id){
        $sql = " SELECT *    
        FROM tb_customer as tb1
        WHERE sale_id = '$sale_id'  
        ORDER BY customer_code  
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


    function getCustomerCodeIndexByChar($char){
        $sql = " SELECT IFNULL(MAX(CAST(RIGHT(customer_code,3) AS SIGNED )),0) as customer_code  
        FROM tb_customer 
        WHERE customer_code LIKE '$char%' 
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

    function getCustomerByID($id){
        $sql = " SELECT * 
        FROM tb_customer 
        WHERE customer_id = '$id' 
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

    function getCustomerByCode($code){
        $sql = " SELECT * 
        FROM tb_customer 
        WHERE customer_code = '$code' 
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


    function deleteEndUserByID($id){
        $sql = " UPDATE tb_customer SET 
        customer_end_user = '0' 
        WHERE customer_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertEndUserByID($customer_id,$id){
        $sql = " UPDATE tb_customer SET 
        customer_end_user = '$customer_id' 
        WHERE customer_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function updateCustomerBillByID($id,$data = []){
        $sql = " UPDATE tb_customer SET 
        date_bill = '".$data['date_bill']."', 
        bill_shift = '".$data['bill_shift']."', 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE customer_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateCustomerInvoiceByID($id,$data = []){
        $sql = " UPDATE tb_customer SET 
        date_invoice = '".$data['date_invoice']."', 
        invoice_shift = '".$data['invoice_shift']."', 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE customer_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function updateSaleIDByID($id,$sale_id){
        $sql = " UPDATE tb_customer SET  
        sale_id = '".$sale_id."',   
        lastupdate = NOW() 
        WHERE customer_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }






    function updateCustomerByID($id,$data = []){
        $sql = " UPDATE tb_customer SET  
        customer_code = '".$data['customer_code']."', 
        customer_name_th = '".$data['customer_name_th']."', 
        customer_name_en = '".$data['customer_name_en']."', 
        customer_type = '".$data['customer_type']."', 
        customer_tax = '".$data['customer_tax']."', 
        customer_address_1 = '".$data['customer_address_1']."', 
        customer_address_2 = '".$data['customer_address_2']."', 
        customer_address_3 = '".$data['customer_address_3']."', 
        customer_zipcode = '".$data['customer_zipcode']."', 
        customer_tel = '".$data['customer_tel']."', 
        customer_fax = '".$data['customer_fax']."', 
        customer_email = '".$data['customer_email']."', 
        customer_domestic = '".$data['customer_domestic']."', 
        customer_remark = '".$data['customer_remark']."', 
        customer_branch = '".$data['customer_branch']."', 
        customer_zone = '".$data['customer_zone']."', 
        credit_day = '".$data['credit_day']."', 
        condition_pay = '".$data['condition_pay']."', 
        pay_limit = '".$data['pay_limit']."' , 
        account_id = '".$data['account_id']."', 
        sale_id = '".$data['sale_id']."', 
        customer_type_id = '".$data['customer_type_id']."', 
        vat_type = '".$data['vat_type']."', 
        vat = '".$data['vat']."', 
        currency_id = '".$data['currency_id']."' , 
        customer_logo = '".$data['customer_logo']."' , 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE customer_id = '$id' 
        "; 

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertCustomer($data = []){
        $sql = " INSERT INTO tb_customer ( 
            customer_code,
            customer_name_th,
            customer_name_en,
            customer_type,
            customer_tax,
            customer_address_1,
            customer_address_2,
            customer_address_3,
            customer_zipcode,
            customer_tel, 
            customer_fax, 
            customer_email, 
            customer_domestic, 
            customer_remark, 
            customer_branch, 
            customer_zone, 
            credit_day, 
            condition_pay,  
            pay_limit,
            account_id, 
            sale_id, 
            customer_type_id,
            vat_type, 
            vat,  
            currency_id,
            customer_logo,
            addby,
            adddate
        ) VALUES ( 
            '".$data['customer_code']."', 
            '".$data['customer_name_th']."', 
            '".$data['customer_name_en']."', 
            '".$data['customer_type']."', 
            '".$data['customer_tax']."', 
            '".$data['customer_address_1']."', 
            '".$data['customer_address_2']."', 
            '".$data['customer_address_3']."', 
            '".$data['customer_zipcode']."', 
            '".$data['customer_tel']."', 
            '".$data['customer_fax']."', 
            '".$data['customer_email']."', 
            '".$data['customer_domestic']."', 
            '".$data['customer_remark']."', 
            '".$data['customer_branch']."', 
            '".$data['customer_zone']."', 
            '".$data['credit_day']."', 
            '".$data['condition_pay']."',  
            '".$data['pay_limit']."', 
            '".$data['account_id']."', 
            '".$data['sale_id']."',
            '".$data['customer_type_id']."',
            '".$data['vat_type']."', 
            '".$data['vat']."',  
            '".$data['currency_id']."', 
            '".$data['customer_logo']."',    
            '".$data['addby']."', 
            NOW()  
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteCustomerByID($id){
        $sql = " DELETE FROM tb_customer WHERE customer_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

}
?>