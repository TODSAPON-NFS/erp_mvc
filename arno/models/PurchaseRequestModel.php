<?php

require_once("BaseModel.php");
class PurchaseRequestModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getPurchaseRequestBy($date_start  = '', $date_end  = '', $status ="Waiting"){
        $sql = " 
        SELECT purchase_request_id, 
        purchase_request_date, 
        purchase_request_code, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as request_name, 
        purchase_request_type, 
        purchase_request_accept_status, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as accept_name, 
        purchase_request_remark 
        FROM tb_purchase_request LEFT JOIN tb_user as tb1 ON tb_purchase_request.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb_purchase_request.purchase_request_accept_by = tb2.user_id 
        ORDER BY STR_TO_DATE(purchase_request_date,'%Y-%m-%d %H:%i:%s') DESC 
         ";
         /*WHERE STR_TO_DATE(purchase_request_date,'%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('$date_start','%Y-%m-%d %H:%i:%s') 
        AND STR_TO_DATE(purchase_request_date,'%Y-%m-%d %H:%i:%s') <= STR_TO_DATE('$date_end','%Y-%m-%d %H:%i:%s')  */
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getPurchaseRequestByID($id){
        $sql = " SELECT * 
        FROM tb_purchase_request 
        WHERE purchase_request_id = '$id' 
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

    function getPurchaseRequestViewByID($id){
        $sql = " SELECT *   
        FROM tb_purchase_request 
        LEFT JOIN tb_user ON tb_purchase_request.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user.user_position_id 
        LEFT JOIN tb_customer ON tb_purchase_request.customer_id = tb_customer.customer_id 
        WHERE purchase_request_id = '$id' 
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

    function getPurchaseRequestLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(purchase_request_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  purchase_request_lastcode 
        FROM tb_purchase_request 
        WHERE purchase_request_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['purchase_request_lastcode'];
        }

    }

   
    function updatePurchaseRequestByID($id,$data = []){
        $sql = " UPDATE tb_purchase_request SET 
        purchase_request_code = '".$data['purchase_request_code']."', 
        purchase_request_type = '".$data['purchase_request_type']."', 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        purchase_request_date = '".$data['purchase_request_date']."', 
        urgent_status = '".$data['urgent_status']."', 
        urgent_time = '".$data['urgent_time']."', 
        purchase_request_accept_status = 'Waiting', 
        purchase_request_remark = '".$data['purchase_request_remark']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE purchase_request_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updatePurchaseRequestAcceptByID($id,$data = []){
        $sql = " UPDATE tb_purchase_request SET 
        purchase_request_accept_status = '".$data['purchase_request_accept_status']."', 
        purchase_request_accept_by = '".$data['purchase_request_accept_by']."', 
        purchase_request_accept_date = NOW(), 
        purchase_request_status = '".$data['purchase_request_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE purchase_request_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertPurchaseRequest($data = []){
        $sql = " INSERT INTO tb_purchase_request (purchase_request_code,purchase_request_type,customer_id,employee_id,purchase_request_date,urgent_status,urgent_time,purchase_request_remark,addby,adddate) 
        VALUES ('".$data['purchase_request_code']."','".$data['purchase_request_type']."','".$data['customer_id']."','".$data['employee_id']."','".$data['purchase_request_date']."','".$data['urgent_status']."','".$data['urgent_time']."','".$data['purchase_request_remark']."','".$data['addby']."',NOW()); 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }


    function deletePurchaseRequestByID($id){
        $sql = " DELETE FROM tb_purchase_request WHERE purchase_request_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_purchase_request_list WHERE purchase_request_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>