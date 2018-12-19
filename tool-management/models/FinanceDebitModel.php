<?php

require_once("BaseModel.php");
class FinanceDebitModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getFinanceDebitBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = "", $lock_1 = "0", $lock_2 = "0" ){

        $str_customer = "";
        $str_date = "";
        $str_user = "";
        $str_lock = "";

        if($lock_1 == "1" && $lock_2 == "1"){
            $str_lock = "AND (paper_lock_1 = '0' OR paper_lock_2 = '0')";
        }else if ($lock_1 == "1") {
            $str_lock = "AND paper_lock_1 = '0' ";
        }else if($lock_2 == "1"){
            $str_lock = "AND paper_lock_2 = '0' ";
        }


        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(finance_debit_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(finance_debit_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(finance_debit_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(finance_debit_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        $sql = " SELECT tb.finance_debit_id, 
        IFNULL (journal_cash_receipt_code, '-') as journal_cash_receipt_code,
        IFNULL (journal_cash_receipt_id, '0') as journal_cash_receipt_id,
        finance_debit_code, 
        finance_debit_date, 
        finance_debit_date_pay, 
        finance_debit_total,
        finance_debit_pay, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        IFNULL( tb2.customer_name_en ,'-') as customer_name  
        FROM tb_finance_debit as tb
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb.customer_id = tb2.customer_id 
        LEFT JOIN tb_journal_cash_receipt ON tb_journal_cash_receipt.finance_debit_id = tb.finance_debit_id 
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb.finance_debit_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  finance_debit_code LIKE ('%$keyword%') 
        ) 
        $str_lock 
        $str_customer 
        $str_date 
        $str_user  
        GROUP BY tb.finance_debit_id
        ORDER BY finance_debit_code ASC 
         ";

         //echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getFinanceDebitByID($id){
        $sql = " SELECT * 
        FROM tb_finance_debit 
        LEFT JOIN tb_customer ON tb_finance_debit.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_finance_debit.employee_id = tb_user.user_id 
        WHERE finance_debit_id = '$id' 
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


    function getFinanceDebitByCode($code){
        $sql = " SELECT * 
        FROM tb_finance_debit 
        LEFT JOIN tb_customer ON tb_finance_debit.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_finance_debit.employee_id = tb_user.user_id 
        WHERE finance_debit_code = '$code' 
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

    function getFinanceDebitViewByID($id){
        $sql = " SELECT *   
        FROM tb_finance_debit 
        LEFT JOIN tb_user ON tb_finance_debit.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user.user_position_id 
        LEFT JOIN tb_customer ON tb_finance_debit.customer_id = tb_customer.customer_id 
        WHERE finance_debit_id = '$id' 
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

    function getFinanceDebitLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(finance_debit_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  finance_debit_lastcode 
        FROM tb_finance_debit
        WHERE finance_debit_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['finance_debit_lastcode'];
        }

    }

    function getCustomerOrder(){

        $sql = "SELECT tb_customer.customer_id, customer_name_en , customer_name_th 
                FROM tb_customer 
                WHERE customer_id IN ( 
                    SELECT DISTINCT customer_id 
                    FROM tb_billing_note_list 
                    LEFT JOIN tb_finance_debit_list ON tb_billing_note_list.billing_note_list_id = tb_finance_debit_list.billing_note_list_id
                    LEFT JOIN tb_billing_note ON tb_billing_note_list.billing_note_id = tb_billing_note.billing_note_id
                    GROUP BY tb_billing_note_list.billing_note_list_id 
                    HAVING MAX(IFNULL(tb_billing_note_list.billing_note_list_balance,0)) > SUM(IFNULL(finance_debit_list_balance,0))
                ) 
                ORDER BY customer_name_en
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


    function generateFinanceDebitListByCustomerId($customer_id, $data = [],$search=""){

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

        $sql_customer = "SELECT tb_billing_note_list.billing_note_list_id, 
        IFNULL(billing_note_code,'-') as billing_note_code, 
        IFNULL(official_receipt_code,'-') as official_receipt_code, 
        invoice_customer_code, 
        '0' as finance_debit_list_paid, 
        '' as finance_debit_list_billing, 
        '' as finance_debit_list_receipt, 
        MAX(IFNULL(tb_billing_note_list.billing_note_list_balance,0)) as finance_debit_list_amount, 
        SUM(IFNULL(finance_debit_list_balance,0)) as finance_debit_list_paid, 
        invoice_customer_date as finance_debit_list_date, 
        invoice_customer_due as finance_debit_list_due 
        FROM tb_billing_note_list 
        LEFT JOIN tb_invoice_customer ON tb_billing_note_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        LEFT JOIN tb_billing_note ON tb_billing_note_list.billing_note_id = tb_billing_note.billing_note_id 
        LEFT JOIN tb_finance_debit_list ON tb_billing_note_list.billing_note_list_id = tb_finance_debit_list.billing_note_list_id 
        LEFT JOIN tb_finance_debit ON tb_finance_debit_list.finance_debit_id = tb_finance_debit.finance_debit_id 
        LEFT JOIN tb_official_receipt_list ON tb_billing_note_list.billing_note_list_id = tb_official_receipt_list.billing_note_list_id 
        LEFT JOIN tb_official_receipt ON tb_official_receipt_list.official_receipt_id = tb_official_receipt.official_receipt_id 
        WHERE tb_billing_note_list.billing_note_list_id NOT IN ($str)  
        AND tb_invoice_customer.customer_id = '$customer_id' 
        AND (
            invoice_customer_date LIKE ('%$search%') OR
            invoice_customer_due LIKE ('%$search%') OR 
            invoice_customer_code LIKE ('%$search%') 
        ) 
        GROUP BY tb_billing_note_list.billing_note_list_id 
        HAVING MAX(IFNULL(tb_billing_note_list.billing_note_list_balance,0)) -  SUM(IFNULL(finance_debit_list_balance,0)) != 0
        ORDER BY  invoice_customer_code ";

        //echo $sql_customer;

        $data = [];
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }

   
    function updateFinanceDebitByID($id,$data = []){
        $sql = " UPDATE tb_finance_debit SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        finance_debit_code = '".$data['finance_debit_code']."',
        finance_debit_date = '".$data['finance_debit_date']."', 
        finance_debit_name = '".$data['finance_debit_name']."', 
        finance_debit_address = '".$data['finance_debit_address']."', 
        finance_debit_tax = '".$data['finance_debit_tax']."', 
        finance_debit_branch = '".$data['finance_debit_branch']."', 
        finance_debit_remark = '".$data['finance_debit_remark']."', 
        finance_debit_sent_name = '".$data['finance_debit_sent_name']."', 
        finance_debit_recieve_name = '".$data['finance_debit_recieve_name']."', 
        finance_debit_total = '".$data['finance_debit_total']."', 
        finance_debit_interest = '".$data['finance_debit_interest']."', 
        finance_debit_cash = '".$data['finance_debit_cash']."', 
        finance_debit_other_pay = '".$data['finance_debit_other_pay']."', 
        finance_debit_tax_pay = '".$data['finance_debit_tax_pay']."', 
        finance_debit_discount_cash = '".$data['finance_debit_discount_cash']."', 
        finance_debit_pay = '".$data['finance_debit_pay']."', 
        finance_debit_total_text = '".$data['finance_debit_total_text']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE finance_debit_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertFinanceDebit($data = []){
        $sql = " INSERT INTO tb_finance_debit (
            customer_id,
            employee_id,
            finance_debit_code,
            finance_debit_date,
            finance_debit_name,
            finance_debit_address,
            finance_debit_tax,
            finance_debit_branch,
            finance_debit_remark,
            finance_debit_sent_name,
            finance_debit_recieve_name,
            finance_debit_total,
            finance_debit_interest,
            finance_debit_cash,
            finance_debit_other_pay,
            finance_debit_tax_pay,
            finance_debit_discount_cash,
            finance_debit_pay,
            finance_debit_total_text,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['finance_debit_code']."','".
        $data['finance_debit_date']."','".
        $data['finance_debit_name']."','".
        $data['finance_debit_address']."','".
        $data['finance_debit_tax']."','".
        $data['finance_debit_branch']."','".
        $data['finance_debit_remark']."','".
        $data['finance_debit_sent_name']."','".
        $data['finance_debit_recieve_name']."','".
        $data['finance_debit_total']."','".
        $data['finance_debit_interest']."','".
        $data['finance_debit_cash']."','".
        $data['finance_debit_other_pay']."','".
        $data['finance_debit_tax_pay']."','".
        $data['finance_debit_discount_cash']."','".
        $data['finance_debit_pay']."','".
        $data['finance_debit_total_text']."','".
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


    function deleteFinanceDebitByID($id){

        $sql = " DELETE FROM tb_finance_debit WHERE finance_debit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_finance_debit_list WHERE finance_debit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_finance_debit_pay WHERE finance_debit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>