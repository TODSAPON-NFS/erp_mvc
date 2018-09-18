<?php

require_once("BaseModel.php");
class CheckPayModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCheckPayBy($check_pay_type = "",$date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$check_pay_status = ""){

        $str_supplier = "";
        $str_date = "";
        $str_type = "";
        $str_status = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(check_pay_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(check_pay_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(check_pay_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(check_pay_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }


        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }

        if($check_pay_type != ""){
            $str_type = "AND check_pay_type = '$check_pay_type' ";
        }

        if($check_pay_status != ""){
            $str_status = "AND check_pay_status = '$check_pay_status' ";
        }


        $sql = " SELECT *
        FROM tb_check_pay 
        LEFT JOIN tb_supplier as tb2 ON tb_check_pay.supplier_id = tb2.supplier_id 
        WHERE ( 
             check_pay_code LIKE ('%$keyword%') 
        ) 
        $str_type
        $str_status 
        $str_supplier 
        $str_date 
        ORDER BY STR_TO_DATE(check_pay_date,'%d-%m-%Y %H:%i:%s'), check_pay_code DESC 
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





    function getCheckPayByID($id){
        $sql = " SELECT * 
        FROM tb_check_pay 
        WHERE check_pay_id = '$id' 
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

    function getCheckPayByCode($check_pay_code){
        $sql = " SELECT * 
        FROM tb_check_pay 
        WHERE check_pay_code = '$check_pay_code' 
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

    function getCheckPayViewByID($id){
        $sql = " SELECT *   
        FROM tb_check_pay 
        LEFT JOIN tb_bank_account ON tb_check_pay.bank_account_id = tb_bank_account.bank_account_id  
        WHERE check_pay_id = '$id' 
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

    function getCheckPayViewListByjournalID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_payment_list 
        LEFT JOIN tb_check_pay ON tb_journal_cash_payment_list.journal_cheque_pay_id = tb_check_pay.check_pay_id
        LEFT JOIN tb_bank_account ON tb_check_pay.bank_account_id = tb_bank_account.bank_account_id
        WHERE journal_cash_payment_id = '$id' AND tb_journal_cash_payment_list.journal_cheque_pay_id > 0
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCheckPayLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(check_pay_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  check_pay_lastcode 
        FROM tb_check_pay 
        WHERE check_pay_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['check_pay_lastcode'];
        }

    }
 
    function updateCheckPayByID($id,$data = []){
        $sql = " UPDATE tb_check_pay SET 
        check_pay_code = '".$data['check_pay_code']."', 
        check_pay_date_write = '".$data['check_pay_date_write']."', 
        check_pay_date = '".$data['check_pay_date']."', 
        bank_account_id = '".$data['bank_account_id']."', 
        supplier_id = '".$data['supplier_id']."', 
        check_pay_remark = '".$data['check_pay_remark']."', 
        check_pay_total = '".$data['check_pay_total']."', 
        check_pay_type = '".$data['check_pay_type']."',  
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE check_pay_id = $id 
        "; 

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function updateCheckPayPassByID($id,$data = []){
        $sql = " UPDATE tb_check_pay SET 
        check_pay_status = '".$data['check_pay_status']."', 
        check_pay_date_pass = '".$data['check_pay_date_pass']."',
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE check_pay_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertCheckPay($data = []){
        $sql = " INSERT INTO tb_check_pay (
            check_pay_code,
            check_pay_date_write,
            check_pay_date,
            bank_account_id,
            supplier_id, 
            check_pay_remark,
            check_pay_total,
            check_pay_type,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['check_pay_code']."','".
        $data['check_pay_date_write']."','".
        $data['check_pay_date']."','".
        $data['bank_account_id']."','".
        $data['supplier_id']."','".
        $data['check_pay_remark']."','".
        $data['check_pay_total']."','".
        $data['check_pay_type']."','". 
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }



    function deleteCheckPayByID($id){
        $sql = " DELETE FROM tb_check_pay WHERE check_pay_id = '$id' ";
        if ( mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }
    }


}
?>