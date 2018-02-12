<?php

require_once("BaseModel.php");
class PurchaseOrderModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getPurchaseOrderBy($date_start  = '', $date_end  = '', $status ="Waiting"){
        $sql = " SELECT purchase_order_id, 
        purchase_order_type, 
        purchase_order_code, 
        purchase_order_date, 
        purchase_order_accept_status,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb3.user_name,' ',tb3.user_lastname),'-') as accept_name, 
        purchase_order_credit_term, 
        purchase_order_delivery_term, 
        IFNULL(CONCAT(tb2.supplier_name_en,' (',tb2.supplier_name_th,')'),'-') as supplier_name, 
        purchase_order_delivery_by 
        FROM tb_purchase_order 
        LEFT JOIN tb_user as tb1 ON tb_purchase_order.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb3 ON tb_purchase_order.purchase_order_accept_by = tb3.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_purchase_order.supplier_id = tb2.supplier_id 
        ORDER BY STR_TO_DATE(purchase_order_date,'%Y-%m-%d %H:%i:%s') DESC 
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

    function getPurchaseOrderByID($id){
        $sql = " SELECT * 
        FROM tb_purchase_order 
        WHERE purchase_order_id = '$id' 
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

    function getPurchaseOrderViewByID($id){
        $sql = " SELECT *   
        FROM tb_purchase_order 
        LEFT JOIN tb_user ON tb_purchase_order.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user.user_position_id 
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id 
        WHERE purchase_order_id = '$id' 
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

    function getPurchaseOrderLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(purchase_order_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  purchase_order_lastcode 
        FROM tb_purchase_order 
        WHERE purchase_order_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['purchase_order_lastcode'];
        }

    }

   
    function updatePurchaseOrderByID($id,$data = []){
        $sql = " UPDATE tb_purchase_order SET 
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        purchase_order_type = '".$data['purchase_order_type']."', 
        purchase_order_code = '".$data['purchase_order_code']."', 
        purchase_order_credit_term = '".$data['purchase_order_credit_term']."', 
        purchase_order_delivery_term = '".$data['purchase_order_delivery_term']."', 
        purchase_order_delivery_by = '".$data['purchase_order_delivery_by']."', 
        purchase_order_date = '".$data['purchase_order_date']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE purchase_order_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updatePurchaseOrderAcceptByID($id,$data = []){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_accept_status = '".$data['purchase_order_accept_status']."', 
        purchase_order_accept_by = '".$data['purchase_order_accept_by']."', 
        purchase_order_accept_date = NOW(), 
        purchase_order_status = '".$data['purchase_order_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE purchase_order_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertPurchaseOrder($data = []){
        $sql = " INSERT INTO tb_purchase_order (
            supplier_id,
            employee_id,
            purchase_order_accept_status,
            purchase_order_accept_by,
            purchase_order_accept_date,
            purchase_order_status,
            purchase_order_type,
            purchase_order_code,
            purchase_order_credit_term,
            purchase_order_delivery_term,
            purchase_order_delivery_by,
            purchase_order_date,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['supplier_id']."','".
        $data['employee_id']."','".
        $data['purchase_order_accept_status']."','".
        $data['purchase_order_accept_by']."','".
        $data['purchase_order_accept_date']."','".
        $data['purchase_order_status']."','".
        $data['purchase_order_type']."','".
        $data['purchase_order_code']."','".
        $data['purchase_order_credit_term']."','".
        $data['purchase_order_delivery_term']."','".
        $data['purchase_order_delivery_by']."','".
        $data['purchase_order_date']."','".
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


    function deletePurchaseOrderByID($id){
        $sql = " DELETE FROM tb_purchase_order WHERE purchase_order_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_purchase_order_list WHERE purchase_order_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>