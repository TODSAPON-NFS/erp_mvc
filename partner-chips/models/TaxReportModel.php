<?php

require_once("BaseModel.php");
class TaxReportModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPurchaseTaxReportBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }

        $sql = " SELECT invoice_supplier_id, 
        invoice_supplier_code, 
        invoice_supplier_code_gen, 
        invoice_supplier_date, 
        invoice_supplier_vat_price,
        invoice_supplier_total_price,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        invoice_supplier_name,
        invoice_supplier_tax,
        invoice_supplier_branch, 
        IFNULL(
            (
                SELECT GROUP_CONCAT(journal_cash_payment_code) 
                FROM tb_journal_cash_payment 
                LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment.journal_cash_payment_id = tb_journal_cash_payment_list.journal_cash_payment_id 
                WHERE tb_journal_cash_payment_list.	journal_invoice_supplier_id = tb.invoice_supplier_id
            )
            ,'-'
        )  as reference_code 
        FROM tb_invoice_supplier as tb
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  invoice_supplier_code LIKE ('%$keyword%') 
        ) 
        AND invoice_supplier_vat_price != 0
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(invoice_supplier_date,'%d-%m-%Y %H:%i:%s'),invoice_supplier_code DESC 
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

    function getSaleTaxReportBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        $sql = " SELECT invoice_customer_id, 
        invoice_customer_code,   
        invoice_customer_name,   
        invoice_customer_tax,   
        invoice_customer_branch,   
        invoice_customer_date, 
        invoice_customer_vat_price,
        invoice_customer_total_price,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        IFNULL(tb2.customer_name_th,tb2.customer_name_en) as customer_name  
        FROM tb_invoice_customer 
        LEFT JOIN tb_user as tb1 ON tb_invoice_customer.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_invoice_customer.customer_id = tb2.customer_id 
        WHERE   invoice_customer_code LIKE ('%$keyword%')  
        AND invoice_customer_vat_price != 0
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), invoice_customer_code  
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
}
?>