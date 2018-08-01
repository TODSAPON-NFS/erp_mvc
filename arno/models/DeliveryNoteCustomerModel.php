<?php

require_once("BaseModel.php");
class DeliveryNoteCustomerModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getDeliveryNoteCustomerBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(delivery_note_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb_delivery_note_customer.employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }


        $sql = " SELECT delivery_note_customer_id, 
        tb_delivery_note_customer.employee_id ,
        delivery_note_customer_code, 
        delivery_note_customer_date, 
        delivery_note_customer_file,
        contact_name,
        delivery_note_customer_remark,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb2.customer_name_en,' (',tb2.customer_name_th,')'),'-') as customer_name 
        FROM tb_delivery_note_customer 
        LEFT JOIN tb_user as tb1 ON tb_delivery_note_customer.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_delivery_note_customer.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  contact_name LIKE ('%$keyword%') 
            OR  delivery_note_customer_code LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(delivery_note_customer_date,'%d-%m-%Y %H:%i:%s'), delivery_note_customer_code DESC 
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





    function getDeliveryNoteCustomerByID($id){
        $sql = " SELECT * 
        FROM tb_delivery_note_customer 
        LEFT JOIN tb_customer ON tb_delivery_note_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_delivery_note_customer.employee_id = tb_user.user_id 
        WHERE delivery_note_customer_id = '$id' 
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

    function getDeliveryNoteCustomerViewByID($id){
        $sql = " SELECT *   
        FROM tb_delivery_note_customer 
        LEFT JOIN tb_user ON tb_delivery_note_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_delivery_note_customer.customer_id = tb_customer.customer_id 
        WHERE delivery_note_customer_id = '$id' 
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

    function getDeliveryNoteCustomerLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(delivery_note_customer_code,".count($id).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  delivery_note_customer_lastcode 
        FROM tb_delivery_note_customer 
        WHERE delivery_note_customer_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['delivery_note_customer_lastcode'];
        }

    }

   
    function updateDeliveryNoteCustomerByID($id,$data = []){
        $sql = " UPDATE tb_delivery_note_customer SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        delivery_note_customer_date = '".$data['delivery_note_customer_date']."', 
        delivery_note_customer_remark = '".$data['delivery_note_customer_remark']."', 
        delivery_note_customer_file = '".$data['delivery_note_customer_file']."', 
        employee_signature = '".$data['employee_signature']."', 
        contact_name = '".$data['contact_name']."', 
        contact_signature = '".$data['contact_signature']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE delivery_note_customer_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertDeliveryNoteCustomer($data = []){
        $sql = " INSERT INTO tb_delivery_note_customer (
            customer_id,
            employee_id,
            delivery_note_customer_code,
            delivery_note_customer_date,
            delivery_note_customer_remark,
            delivery_note_customer_file,
            employee_signature,
            contact_name,
            contact_signature,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['delivery_note_customer_code']."','".
        $data['delivery_note_customer_date']."','".
        $data['delivery_note_customer_remark']."','".
        $data['delivery_note_customer_file']."','".
        $data['employee_signature']."','".
        $data['contact_name']."','".
        $data['contact_signature']."','".
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



    function deleteDeliveryNoteCustomerByID($id){
        $sql = " DELETE FROM tb_delivery_note_customer WHERE delivery_note_customer_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_delivery_note_customer_list WHERE delivery_note_customer_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }


}
?>