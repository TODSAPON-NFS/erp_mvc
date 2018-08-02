<?php

require_once("BaseModel.php");
class FinanceCreditModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getFinanceCreditBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(finance_credit_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(finance_credit_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(finance_credit_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(finance_credit_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }

        $sql = " SELECT finance_credit_id, 
        finance_credit_code, 
        finance_credit_date, 
        finance_credit_date_pay, 
        finance_credit_total,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        IFNULL(CONCAT(tb2.supplier_name_en,' (',tb2.supplier_name_th,')'),'-') as supplier_name  
        FROM tb_finance_credit 
        LEFT JOIN tb_user as tb1 ON tb_finance_credit.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_finance_credit.supplier_id = tb2.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  finance_credit_code LIKE ('%$keyword%') 
        ) 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(finance_credit_date,'%d-%m-%Y %H:%i:%s'),finance_credit_code DESC 
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

    function getFinanceCreditByID($id){
        $sql = " SELECT * 
        FROM tb_finance_credit 
        LEFT JOIN tb_supplier ON tb_finance_credit.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_finance_credit.employee_id = tb_user.user_id 
        WHERE finance_credit_id = '$id' 
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

    function getFinanceCreditViewByID($id){
        $sql = " SELECT *   
        FROM tb_finance_credit 
        LEFT JOIN tb_user ON tb_finance_credit.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user.user_position_id 
        LEFT JOIN tb_supplier ON tb_finance_credit.supplier_id = tb_supplier.supplier_id 
        WHERE finance_credit_id = '$id' 
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

    function getFinanceCreditLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(finance_credit_code,".count($id).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  finance_credit_lastcode 
        FROM tb_finance_credit
        WHERE finance_credit_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['finance_credit_lastcode'];
        }

    }

    function getSupplierOrder(){

        $sql = "SELECT tb_supplier.supplier_id, supplier_name_en , supplier_name_th 
                FROM tb_supplier 
                WHERE supplier_id IN ( 
                    SELECT DISTINCT supplier_id 
                    FROM tb_invoice_supplier
                    LEFT JOIN tb_finance_credit_list ON tb_invoice_supplier.invoice_supplier_id = tb_finance_credit_list.invoice_supplier_id 
                    WHERE supplier_id = '$supplier_id' 
                    GROUP BY tb_invoice_supplier.invoice_supplier_id 
                    HAVING MAX(IFNULL(tb_invoice_supplier.invoice_supplier_net_price,0)) > SUM(IFNULL(finance_credit_list_balance,0)) 
                ) 
        "; 
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }

            $result->close();
            
        }
        return $data;
    }


    function generateFinanceCreditListBySupplierId($supplier_id, $data = [],$search=""){

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

        $sql_supplier = "SELECT invoice_supplier_id, 
        invoice_supplier_code,
        '0' as finance_credit_list_paid, 
        '' as finance_credit_list_billing,
        '' as finance_credit_list_receipt,
        invoice_supplier_net_price as finance_credit_list_amount, 
        invoice_supplier_date as finance_credit_list_date, 
        invoice_supplier_due as finance_credit_list_due 
        FROM tb_invoice_supplier 
        WHERE tb_invoice_supplier.invoice_supplier_id NOT IN ($str) 
        AND tb_invoice_supplier.invoice_supplier_id  IN (
            SELECT DISTINCT tb_invoice_supplier.invoice_supplier_id 
            FROM tb_invoice_supplier
            LEFT JOIN tb_finance_credit_list ON tb_invoice_supplier.invoice_supplier_id = tb_finance_credit_list.invoice_supplier_id 
            WHERE supplier_id = '$supplier_id' 
            GROUP BY tb_invoice_supplier.invoice_supplier_id 
            HAVING MAX(IFNULL(tb_invoice_supplier.invoice_supplier_net_price,0)) > SUM(IFNULL(finance_credit_list_balance,0)) 
        ) 
        AND supplier_id = '$supplier_id' 
        AND (
            invoice_supplier_date LIKE ('%$search%') OR
            invoice_supplier_due LIKE ('%$search%') OR 
            invoice_supplier_code LIKE ('%$search%') 
        ) 
        ORDER BY  STR_TO_DATE(invoice_supplier_date,'%d-%m-%Y %H:%i:%s') ";

        //echo $sql_supplier;

        $data = [];
        if ($result = mysqli_query(static::$db,$sql_supplier, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }

   
    function updateFinanceCreditByID($id,$data = []){
        $sql = " UPDATE tb_finance_credit SET 
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        finance_credit_code = '".$data['finance_credit_code']."',
        finance_credit_date = '".$data['finance_credit_date']."', 
        finance_credit_name = '".$data['finance_credit_name']."', 
        finance_credit_address = '".$data['finance_credit_address']."', 
        finance_credit_tax = '".$data['finance_credit_tax']."', 
        finance_credit_remark = '".$data['finance_credit_remark']."', 
        finance_credit_sent_name = '".$data['finance_credit_sent_name']."', 
        finance_credit_recieve_name = '".$data['finance_credit_recieve_name']."', 
        finance_credit_total = '".$data['finance_credit_total']."', 
        finance_credit_interest = '".$data['finance_credit_interest']."', 
        finance_credit_cash = '".$data['finance_credit_cash']."', 
        finance_credit_other_pay = '".$data['finance_credit_other_pay']."', 
        finance_credit_tax_pay = '".$data['finance_credit_tax_pay']."', 
        finance_credit_discount_cash = '".$data['finance_credit_discount_cash']."', 
        finance_credit_pay = '".$data['finance_credit_pay']."', 
        finance_credit_total_text = '".$data['finance_credit_total_text']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE finance_credit_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertFinanceCredit($data = []){
        $sql = " INSERT INTO tb_finance_credit (
            supplier_id,
            employee_id,
            finance_credit_code,
            finance_credit_date,
            finance_credit_name,
            finance_credit_address,
            finance_credit_tax,
            finance_credit_remark,
            finance_credit_sent_name,
            finance_credit_recieve_name,
            finance_credit_total,
            finance_credit_interest,
            finance_credit_cash,
            finance_credit_other_pay,
            finance_credit_tax_pay,
            finance_credit_discount_cash,
            finance_credit_pay,
            finance_credit_total_text,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['supplier_id']."','".
        $data['employee_id']."','".
        $data['finance_credit_code']."','".
        $data['finance_credit_date']."','".
        $data['finance_credit_name']."','".
        $data['finance_credit_address']."','".
        $data['finance_credit_tax']."','".
        $data['finance_credit_remark']."','".
        $data['finance_credit_sent_name']."','".
        $data['finance_credit_recieve_name']."','".
        $data['finance_credit_total']."','".
        $data['finance_credit_interest']."','".
        $data['finance_credit_cash']."','".
        $data['finance_credit_other_pay']."','".
        $data['finance_credit_tax_pay']."','".
        $data['finance_credit_discount_cash']."','".
        $data['finance_credit_pay']."','".
        $data['finance_credit_total_text']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";


        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteFinanceCreditByID($id){

        $sql = " DELETE FROM tb_finance_credit WHERE finance_credit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_finance_credit_list WHERE finance_credit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_finance_credit_pay WHERE finance_credit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>