<?php

require_once("BaseModel.php");
class CheckModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getCheckBy($date_start = "",$date_end = "",$customer_id = "",$keyword = ""){

        $str_customer = "";
        $str_date = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(check_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(check_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(check_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(check_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }


        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }


        $sql = " SELECT *
        FROM tb_check 
        LEFT JOIN tb_customer as tb2 ON tb_check.customer_id = tb2.customer_id 
        WHERE ( 
             check_code LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        ORDER BY STR_TO_DATE(check_date_recieve,'%d-%m-%Y %H:%i:%s'), check_code DESC 
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





    function getCheckByID($id){
        $sql = " SELECT * 
        FROM tb_check 
        WHERE check_id = '$id' 
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

    function getCheckViewByID($id){
        $sql = " SELECT *   
        FROM tb_check 
        WHERE check_id = '$id' 
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

    function getCheckLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(check_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  check_lastcode 
        FROM tb_check 
        WHERE check_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
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
        check_date = '".$data['check_date']."', 
        check_remark = '".$data['check_remark']."', 
        check_file = '".$data['check_file']."', 
        employee_signature = '".$data['employee_signature']."', 
        contact_name = '".$data['contact_name']."', 
        contact_signature = '".$data['contact_signature']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE check_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
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

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateCheckPassByID($id,$data = []){
        $sql = " UPDATE tb_check SET 
        check_status = '1', 
        check_date_pass = '".$data['check_date_pass']."',
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE check_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
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



    function deleteCheckByID($id){
        $sql = " DELETE FROM tb_check WHERE check_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);
    }


}
?>