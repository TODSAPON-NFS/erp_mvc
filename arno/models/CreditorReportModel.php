<?php

require_once("BaseModel.php");
class CreditorReportModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getInvoiceSupplierReportBy($date_start = "",$date_end = "",$code_start = "",$code_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_code = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($code_start != "" && $code_end != ""){
            $str_code = "AND invoice_supplier_code_gen >= '$code_start'  AND invoice_supplier_code_gen <=  '$code_end' ";
        }else if ($date_start != ""){
            $str_code = "AND invoice_supplier_code_gen >= '$code_start' ";    
        }else if ($date_end != ""){
            $str_code = "AND invoice_supplier_code_gen <= '$code_end'  ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }

        $sql = " SELECT invoice_supplier_id, 
        supplier_code,
        invoice_supplier_code, 
        invoice_supplier_code_gen, 
        invoice_supplier_date_recieve, 
        invoice_supplier_total_price,
        invoice_supplier_vat_price,
        invoice_supplier_net_price,  
        invoice_supplier_name,
        invoice_supplier_tax,
        invoice_supplier_due,
        IFNULL((
            SELECT GROUP_CONCAT(DISTINCT purchase_order_code )
            FROM tb_invoice_supplier_list 
            LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id 
            LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_id = tb_purchase_order.purchase_order_id 
            WHERE invoice_supplier_id = tb1.invoice_supplier_id 
            AND purchase_order_code != '' 
            GROUP BY tb_purchase_order.purchase_order_id 
        ),'-') as purchase_order_code  ,  
        IFNULL((
            SELECT SUM(finance_credit_list_amount) FROM tb_finance_credit_list WHERE invoice_supplier_id = tb1.invoice_supplier_id 
        ),'0') as payment 
        FROM tb_invoice_supplier as tb1 
        LEFT JOIN tb_supplier as tb2 ON tb1.supplier_id = tb2.supplier_id 
        WHERE  invoice_supplier_code LIKE ('%$keyword%')  
        AND tb1.invoice_supplier_begin = 0 
        $str_supplier 
        $str_date 
        $str_code 
        $str_user  
        ORDER BY invoice_supplier_code_gen ASC 
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