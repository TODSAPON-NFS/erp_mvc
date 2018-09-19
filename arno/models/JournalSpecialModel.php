<?php

require_once("BaseModel.php");
class JournalSpecialModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJournalSpecialBy($date_start = "", $date_end = "",$keyword = "",$journal_id=""){


        $str_date = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(journal_special_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_special_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(journal_special_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(journal_special_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        $str_type = "";

        if($journal_id != ""){
            $str_type = " AND journal_id = '$journal_id' ";
        }


        $sql = " SELECT journal_special_id, 
        finance_credit_id,
        finance_debit_id,
        invoice_supplier_id,
        invoice_customer_id,
        debit_note_id,
        credit_note_id,
        journal_special_code, 
        journal_special_date,
        journal_special_name,
        addby,
        adddate,
        updateby,
        lastupdate,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as add_name, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as update_name 
        FROM tb_journal_special 
        LEFT JOIN tb_user as tb1 ON tb_journal_special.addby = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb_journal_special.updateby = tb2.user_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  CONCAT(tb2.user_name,' ',tb2.user_lastname) LIKE ('%$keyword%') 
            OR  journal_special_code LIKE ('%$keyword%') 
            OR  journal_special_name LIKE ('%$keyword%') 
        ) 
        $str_type 
        $str_date 
        ORDER BY STR_TO_DATE(journal_special_date,'%d-%m-%Y %H:%i:%s'), journal_special_code DESC 
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

    function getJournalSpecialByKeyword(){
       
        $sql = " SELECT journal_special_id, 
        journal_special_code,  
        journal_special_name 
        FROM tb_journal_special  
        WHERE journal_special_code LIKE ('%$keyword%')  OR  journal_special_name LIKE ('%$keyword%') 
        ORDER BY journal_special_code DESC 
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


    function getJournalSpecialByFinanceCreditID($id){
        $sql = " SELECT * 
        FROM tb_journal_special 
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


    function getJournalSpecialByID($id){
        $sql = " SELECT * 
        FROM tb_journal_special 
        WHERE journal_special_id = '$id' 
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

    function getJournalSpecialByCode($journal_special_code){
        $sql = " SELECT * 
        FROM tb_journal_special 
        WHERE journal_special_code = '$journal_special_code' 
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

    function getJournalSpecialViewByID($id){
        $sql = " SELECT journal_special_id, 
        journal_special_code, 
        journal_special_date,
        journal_special_name,
        addby,
        adddate,
        updateby,
        lastupdate,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as add_name, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as update_name 
        FROM tb_journal_special 
        LEFT JOIN tb_user as tb1 ON tb_journal_special.addby = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb_journal_special.updateby = tb2.user_id 
        WHERE journal_special_id = '$id' 
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

    function getJournalSpecialLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(journal_special_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  journal_special_lastcode 
        FROM tb_journal_special 
        WHERE journal_special_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['journal_special_lastcode'];
        }

    }

   
    function updateJournalSpecialByID($id,$data = []){
        $sql = " UPDATE tb_journal_special SET 
        journal_special_code = '".$data['journal_special_code']."', 
        journal_special_date = '".$data['journal_special_date']."', 
        journal_special_name = '".static::$db->real_escape_string($data['journal_special_name']). "', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE journal_special_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertJournalSpecial($data = []){
        $sql = " INSERT INTO tb_journal_special (
            finance_credit_id,
            finance_debit_id,
            invoice_supplier_id,
            invoice_customer_id,
            debit_note_id,
            credit_note_id,
            journal_special_code, 
            journal_special_date,
            journal_special_name,
            addby,
            adddate,
            updateby, 
            lastupdate) 
        VALUES ('".
        $data['finance_credit_id']."','".
        $data['finance_debit_id']."','".
        $data['invoice_supplier_id']."','".
        $data['invoice_customer_id']."','".
        $data['debit_note_id']."','".
        $data['credit_note_id']."','".
        $data['journal_special_code']."','".
        $data['journal_special_date']."','".
        static::$db->real_escape_string($data['journal_special_name'])."','".
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


    function deleteJournalSpecialByFinanceCreditID($finance_credit_id){
        
        $sql = " DELETE FROM tb_journal_special_list WHERE journal_special_id IN (SELECT journal_special_id FROM tb_journal_special WHERE finance_credit_id = '$finance_credit_id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_journal_special WHERE finance_credit_id = '$finance_credit_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        
    }

    function deleteJournalSpecialByID($id){
        $sql = " DELETE FROM tb_journal_special WHERE journal_special_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_journal_special_list WHERE journal_special_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }


}
?>