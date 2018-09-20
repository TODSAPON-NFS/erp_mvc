<?php

require_once("BaseModel.php");
class CheckModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCheckBy($check_type = "",$date_start = "",$date_end = "",$customer_id = "",$keyword = "",$check_status="",$check_date_deposit = ""){

        $str_customer = "";
        $str_date = "";
        $str_type = "";
        $str_status = "";
        $str_deposit = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(check_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(check_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(check_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(check_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }


        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        if($check_type != ""){
            $str_type = "AND check_type = '$check_type' ";
        }

        if($check_status != ""){
            $str_status  = "AND check_status = '$check_status' ";
        }

        if($check_date_deposit == "0"){
            $str_deposit  = "AND check_date_deposit = '' ";
        } else if($check_date_deposit == "1"){
            $str_deposit  = "AND check_date_deposit != '' ";
        }

        $sql = " SELECT *
        FROM tb_check 
        LEFT JOIN tb_customer as tb2 ON tb_check.customer_id = tb2.customer_id 
        WHERE ( 
             check_code LIKE ('%$keyword%') 
        ) 
        $str_type
        $str_status
        $str_deposit 
        $str_customer 
        $str_date 
        ORDER BY STR_TO_DATE(check_date_recieve,'%d-%m-%Y %H:%i:%s'), check_code DESC 
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





    function getCheckByID($id){
        $sql = " SELECT * 
        FROM tb_check 
        WHERE check_id = '$id' 
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

    function getCheckViewByID($id){
        $sql = " SELECT *   
        FROM tb_check 
        WHERE check_id = '$id' 
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

    function getCheckLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(check_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  check_lastcode 
        FROM tb_check 
        WHERE check_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['check_lastcode'];
        }

    }

    function updateCheckByID($id,$data = []){
        $sql = " UPDATE tb_check SET 
        check_code = '".$data['check_code']."', 
        check_date_write = '".$data['check_date_write']."', 
        check_date_recieve = '".$data['check_date_recieve']."', 
        bank_id = '".$data['bank_id']."', 
        bank_branch = '".$data['bank_branch']."', 
        customer_id = '".$data['customer_id']."', 
        check_remark = '".$data['check_remark']."', 
        check_total = '".$data['check_total']."', 
        check_type = '".$data['check_type']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE check_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateCheckDepositByID($id,$data = []){
        $sql = " UPDATE tb_check SET 
        check_date_deposit = '".$data['check_date_deposit']."', 
        check_fee = '".$data['check_fee']."', 
        bank_deposit_id = '".$data['bank_deposit_id']."',
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE check_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateCheckPassByID($id,$data = []){
        $sql = " UPDATE tb_check SET 
        check_status = '".$data['check_status']."', 
        check_date_pass = '".$data['check_date_pass']."',
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE check_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertCheck($data = []){
        $sql = " INSERT INTO tb_check (
            check_code,
            check_date_write,
            check_date_recieve,
            bank_id,
            bank_branch,
            customer_id,
            check_remark,
            check_total,
            check_type,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['check_code']."','".
        $data['check_date_write']."','".
        $data['check_date_recieve']."','".
        $data['bank_id']."','".
        $data['bank_branch']."','".
        $data['customer_id']."','".
        $data['check_remark']."','".
        $data['check_total']."','".
        $data['check_type']."','".
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



    function deleteCheckByID($id){
        $sql = " DELETE FROM tb_check WHERE check_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }


}
?>