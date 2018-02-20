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
        purchase_order_status,
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
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_purchase_order.employee_id = tb_user.user_id 
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
        purchase_order_status = '".$data['purchase_order_status']."', 
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


    function updatePurchaseOrderRequestByID($id,$data = []){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_accept_status = '".$data['purchase_order_accept_status']."', 
        purchase_order_accept_by = '".$data['purchase_order_accept_by']."', 
        purchase_order_accept_date = '', 
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

    function updatePurchaseOrderStatusByID($id,$data = []){
        if ($data['updateby'] != ""){
            $str = "updateby = '".$data['updateby']."', ";
        }
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_status = '".$data['purchase_order_status']."', 
        $str 
        lastupdate = NOW() 
        WHERE purchase_order_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function getSupplierOrder(){

        $sql = "SELECT tb_product_supplier.supplier_id, supplier_name_en , supplier_name_th 
                FROM tb_product_supplier LEFT JOIN tb_supplier ON tb_product_supplier.supplier_id = tb_supplier.supplier_id 
                WHERE product_id IN ( 
                    SELECT DISTINCT product_id 
                    FROM tb_purchase_request_list 
                    WHERE purchase_order_list_id = 0 
                    UNION 
                    SELECT DISTINCT product_id 
                    FROM tb_customer_purchase_order_list 
                    WHERE purchase_order_list_id = 0 
                    AND customer_purchase_order_list_qty - customer_purchase_order_list_hold > 0   
                )
                AND product_supplier_status = 'Active' 
        ";
        $data = [];
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }
        return $data;
    }

    function generatePurchaseOrderListBySupplierId($supplier_id){

        $sql_request = "SELECT tb_purchase_request_list.product_id, 
        purchase_request_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        purchase_request_list_qty as purchase_order_list_qty, 
        product_buyprice as purchase_order_list_price ,
        CONCAT('Order for purchase request ',purchase_request_code) as purchase_order_list_remark 
        FROM tb_purchase_request 
        LEFT JOIN tb_purchase_request_list ON tb_purchase_request.purchase_request_id = tb_purchase_request_list.purchase_request_id 
        LEFT JOIN tb_product ON tb_purchase_request_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_supplier ON tb_purchase_request_list.product_id = tb_product_supplier.product_id 
        WHERE supplier_id = '$supplier_id' 
        AND purchase_order_list_id = 0 
        AND product_supplier_status = 'Active' ";

        $data = [];

        //echo $sql_request."<br><br>";

        if ($result = mysqli_query($this->db,$sql_request, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

       

        $sql_customer = "SELECT tb_customer_purchase_order_list.product_id, 
        customer_purchase_order_list_id,
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        (customer_purchase_order_list_qty - customer_purchase_order_list_hold) as purchase_order_list_qty, 
        product_buyprice as purchase_order_list_price ,
        CONCAT('Order for customer purchase order ',customer_purchase_order_code) as purchase_order_list_remark 
        FROM tb_customer_purchase_order 
        LEFT JOIN tb_customer_purchase_order_list ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id 
        LEFT JOIN tb_product ON tb_customer_purchase_order_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_supplier ON tb_customer_purchase_order_list.product_id = tb_product_supplier.product_id 
        WHERE supplier_id = '$supplier_id' 
        AND purchase_order_list_id = 0 
        AND customer_purchase_order_list_qty - customer_purchase_order_list_hold > 0
        AND product_supplier_status = 'Active' ";

        //echo $sql_customer."<br><br>";
        if ($result = mysqli_query($this->db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
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

        $sql = " UPDATE tb_purchase_request_list SET purchase_order_list_id = '0' WHERE purchase_order_list_id (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_customer_purchase_order_list SET purchase_order_list_id = '0' WHERE purchase_order_list_id (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_purchase_order WHERE purchase_order_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_purchase_order_list WHERE purchase_order_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>