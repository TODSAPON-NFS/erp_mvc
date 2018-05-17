<?php

require_once("BaseModel.php");
class DeliveryNoteSupplierModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getDeliveryNoteSupplierBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        $sql = " SELECT delivery_note_supplier_id, 
        delivery_note_supplier_code, 
        delivery_note_supplier_date, 
        delivery_note_supplier_file,
        contact_name,
        delivery_note_supplier_remark,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb2.supplier_name_en,' (',tb2.supplier_name_th,')'),'-') as supplier_name 
        FROM tb_delivery_note_supplier 
        LEFT JOIN tb_user as tb1 ON tb_delivery_note_supplier.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_delivery_note_supplier.supplier_id = tb2.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  contact_name LIKE ('%$keyword%') 
            OR  delivery_note_supplier_code LIKE ('%$keyword%') 
        ) 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') DESC 
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

    function getDeliveryNoteSupplierByID($id){
        $sql = " SELECT * 
        FROM tb_delivery_note_supplier 
        LEFT JOIN tb_supplier ON tb_delivery_note_supplier.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_delivery_note_supplier.employee_id = tb_user.user_id 
        WHERE delivery_note_supplier_id = '$id' 
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

    function getDeliveryNoteSupplierViewByID($id){
        $sql = " SELECT *   
        FROM tb_delivery_note_supplier 
        LEFT JOIN tb_user ON tb_delivery_note_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_delivery_note_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE delivery_note_supplier_id = '$id' 
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

    function getDeliveryNoteSupplierLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(delivery_note_supplier_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  delivery_note_supplier_lastcode 
        FROM tb_delivery_note_supplier 
        WHERE delivery_note_supplier_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['delivery_note_supplier_lastcode'];
        }

    }

   
    function updateDeliveryNoteSupplierByID($id,$data = []){
        $sql = " UPDATE tb_delivery_note_supplier SET 
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        
        delivery_note_supplier_date = '".$data['delivery_note_supplier_date']."', 
        delivery_note_supplier_remark = '".$data['delivery_note_supplier_remark']."', 
        delivery_note_supplier_file = '".$data['delivery_note_supplier_file']."', 
        employee_signature = '".$data['employee_signature']."', 
        contact_name = '".$data['contact_name']."', 
        contact_signature = '".$data['contact_signature']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE delivery_note_supplier_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertDeliveryNoteSupplier($data = []){
        $sql = " INSERT INTO tb_delivery_note_supplier (
            supplier_id,
            employee_id,
            delivery_note_supplier_code,
            delivery_note_supplier_date,
            delivery_note_supplier_remark,
            delivery_note_supplier_file,
            employee_signature,
            contact_name,
            contact_signature,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['supplier_id']."','".
        $data['employee_id']."','".
        $data['delivery_note_supplier_code']."','".
        $data['delivery_note_supplier_date']."','".
        $data['delivery_note_supplier_remark']."','".
        $data['delivery_note_supplier_file']."','".
        $data['employee_signature']."','".
        $data['contact_name']."','".
        $data['contact_signature']."','".
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



    function deleteDeliveryNoteSupplierByID($id){

        $sql = " DELETE FROM tb_delivery_note_supplier WHERE delivery_note_supplier_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_delivery_note_supplier_list WHERE delivery_note_supplier_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }


}
?>