<?php

require_once("BaseModel.php");
class RegrindSupplierModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getRegrindSupplierBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){
        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb_regrind_supplier.employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }

        $sql = " SELECT regrind_supplier_id, 
        tb_regrind_supplier.employee_id,
        regrind_supplier_code, 
        regrind_supplier_date, 
        regrind_supplier_file,
        contact_name,
        regrind_supplier_remark,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb2.supplier_name_en,' (',tb2.supplier_name_th,')'),'-') as supplier_name 
        FROM tb_regrind_supplier  
        LEFT JOIN tb_user as tb1 ON tb_regrind_supplier.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_regrind_supplier.supplier_id = tb2.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  contact_name LIKE ('%$keyword%') 
            OR  regrind_supplier_code LIKE ('%$keyword%') 
        ) 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') , regrind_supplier_code DESC 
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

    function getRegrindSupplierByID($id){
        $sql = " SELECT * 
        FROM tb_regrind_supplier 
        LEFT JOIN tb_supplier ON tb_regrind_supplier.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_regrind_supplier.employee_id = tb_user.user_id 
        WHERE regrind_supplier_id = '$id' 
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

    function getRegrindSupplierViewByID($id){
        $sql = " SELECT *   
        FROM tb_regrind_supplier 
        LEFT JOIN tb_user ON tb_regrind_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_regrind_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE regrind_supplier_id = '$id' 
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

    function getRegrindSupplierLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(regrind_supplier_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  regrind_supplier_lastcode 
        FROM tb_regrind_supplier 
        WHERE regrind_supplier_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['regrind_supplier_lastcode'];
        }

    }

   
    function updateRegrindSupplierByID($id,$data = []){
        $sql = " UPDATE tb_regrind_supplier SET 
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        regrind_supplier_code = '".$data['regrind_supplier_code']."', 
        regrind_supplier_date = '".$data['regrind_supplier_date']."', 
        regrind_supplier_remark = '".$data['regrind_supplier_remark']."', 
        regrind_supplier_file = '".$data['regrind_supplier_file']."', 
        contact_name = '".$data['contact_name']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE regrind_supplier_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateContactSignatureByID($id,$data = []){
        $sql = " UPDATE tb_regrind_supplier SET 
        contact_signature = '".$data['contact_signature']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE regrind_supplier_id = $id 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertRegrindSupplier($data = []){
        $sql = " INSERT INTO tb_regrind_supplier (
            supplier_id,
            employee_id,
            regrind_supplier_code,
            regrind_supplier_date,
            regrind_supplier_remark,
            regrind_supplier_file,
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
        $data['regrind_supplier_code']."','".
        $data['regrind_supplier_date']."','".
        $data['regrind_supplier_remark']."','".
        $data['regrind_supplier_file']."','".
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



    function deleteRegrindSupplierByID($id){

        $sql = " DELETE FROM tb_regrind_supplier WHERE regrind_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_regrind_supplier_list WHERE regrind_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>