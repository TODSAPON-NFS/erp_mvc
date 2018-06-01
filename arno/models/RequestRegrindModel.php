<?php

require_once("BaseModel.php");
class RequestRegrindModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getRequestRegrindBy($date_start = "",$date_end = "",$keyword = "",$user_id = ""){

        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(request_regrind_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(request_regrind_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(request_regrind_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(request_regrind_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }


        $sql = " SELECT request_regrind_id, 
        request_regrind_date, 
        request_regrind_rewrite_id,
        IFNULL((
            SELECT COUNT(*) FROM tb_request_regrind WHERE request_regrind_rewrite_id = tb.request_regrind_id 
        ),0) as count_rewrite,
        request_regrind_rewrite_no,
        request_regrind_code, 
        purchase_order_open, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as request_name, 
        request_regrind_accept_status, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as accept_name, 
        request_regrind_cancelled, 
        request_regrind_remark 
        FROM tb_request_regrind as tb 
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb.request_regrind_accept_by = tb2.user_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  CONCAT(tb2.user_name,' ',tb2.user_lastname) LIKE ('%$keyword%') 
            OR  request_regrind_code LIKE ('%$keyword%') 
        ) 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(request_regrind_date,'%d-%m-%Y %H:%i:%s') DESC 
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

    function getRequestRegrindByID($id){
        $sql = " SELECT * 
        FROM tb_request_regrind 
        WHERE request_regrind_id = '$id' 
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

    function getRequestRegrindViewByID($id){
        $sql = " SELECT *   
        FROM tb_request_regrind 
        LEFT JOIN tb_user ON tb_request_regrind.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_request_regrind.customer_id = tb_customer.customer_id 
        WHERE request_regrind_id = '$id' 
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

    function getRequestRegrindLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(request_regrind_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  request_regrind_lastcode 
        FROM tb_request_regrind 
        WHERE request_regrind_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['request_regrind_lastcode'];
        }

    }

    function cancelRequestRegrindByID($id){
        $sql = " UPDATE tb_request_regrind SET 
        request_regrind_cancelled = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE request_regrind_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelRequestRegrindByID($id){
        $sql = " UPDATE tb_request_regrind SET 
        request_regrind_cancelled = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE request_regrind_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

   
    function updateRequestRegrindByID($id,$data = []){
        $sql = " UPDATE tb_request_regrind SET 
        request_regrind_code = '".$data['request_regrind_code']."',  
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        request_regrind_date = '".$data['request_regrind_date']."', 
        request_regrind_accept_status = 'Waiting', 
        request_regrind_remark = '".$data['request_regrind_remark']."', 
        purchase_order_open = '".$data['purchase_order_open']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE request_regrind_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateRequestRegrindAcceptByID($id,$data = []){
        $sql = " UPDATE tb_request_regrind SET 
        request_regrind_accept_status = '".$data['request_regrind_accept_status']."', 
        request_regrind_accept_by = '".$data['request_regrind_accept_by']."', 
        request_regrind_accept_date = NOW(), 
        request_regrind_status = '".$data['request_regrind_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE request_regrind_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    

    function insertRequestRegrind($data = []){
        $sql = " INSERT INTO tb_request_regrind (
            request_regrind_rewrite_id,
            request_regrind_rewrite_no,
            request_regrind_code, 
            customer_id,
            employee_id,
            request_regrind_date,
            request_regrind_remark,
            purchase_order_open,
            request_regrind_accept_status,
            addby,
            adddate) 
        VALUES ('".
        $data['request_regrind_rewrite_id']."','".
        $data['request_regrind_rewrite_no']."','".
        $data['request_regrind_code']."','". 
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['request_regrind_date']."','".
        $data['request_regrind_remark']."','".
        $data['purchase_order_open']."','".
        $data['request_regrind_accept_status']."','".
        $data['addby']."',".
        "NOW()); 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }


    function deleteRequestRegrindByID($id){
        $sql = " DELETE FROM tb_request_regrind WHERE request_regrind_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_request_regrind_list WHERE request_regrind_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>