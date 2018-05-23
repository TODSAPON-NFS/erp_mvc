<?php

require_once("BaseModel.php");
class CustomerModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getcustomerBy($customer_code = '',$customer_name_th = '',$customer_name_en = '', $customer_tax = '', $customer_email = '', $customer_tel  = ''){
        $sql = " SELECT customer_id, customer_code, customer_name_th, customer_name_en , customer_tax , customer_tel, customer_email   
        FROM tb_customer 
        WHERE customer_code LIKE ('%$customer_code%') 
        OR customer_name_th LIKE ('%$customer_name_th%') 
        OR customer_name_en LIKE ('%$customer_name_en%') 
        OR customer_tax LIKE ('%$customer_tax%') 
        OR customer_tel LIKE ('%$customer_tel%') 
        OR customer_email LIKE ('%$customer_email%') 
        ORDER BY customer_name_th  
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

    function getCustomerCodeIndexByChar($char){
        $sql = " SELECT IFNULL(MAX(CAST(RIGHT(customer_code,3) AS SIGNED )),0) as customer_code  
        FROM tb_customer 
        WHERE customer_code LIKE '$char%' 
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

    function getCustomerByID($id){
        $sql = " SELECT * 
        FROM tb_customer 
        WHERE customer_id = '$id' 
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


    function updateCustomerBillByID($id,$data = []){
        $sql = " UPDATE tb_customer SET 
        date_bill = '".$data['date_bill']."', 
        bill_shift = '".$data['bill_shift']."', 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE customer_id = $id 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
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
        WHERE customer_id = $id 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
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
        vat_type = '".$data['vat_type']."', 
        vat = '".$data['vat']."', 
        currency_id = '".$data['currency_id']."' , 
        customer_logo = '".$data['customer_logo']."' , 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE customer_id = $id 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
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
            '".$data['vat_type']."', 
            '".$data['vat']."',  
            '".$data['currency_id']."', 
            '".$data['customer_logo']."',    
            '".$data['addby']."', 
            NOW()  
        ); 
        ";

        //echo $sql;
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }


    function deleteCustomerByID($id){
        $sql = " DELETE FROM tb_customer WHERE customer_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

}
?>