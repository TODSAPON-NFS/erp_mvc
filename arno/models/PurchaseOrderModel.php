<?php

require_once("BaseModel.php");
class PurchaseOrderModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getPurchaseOrderBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        $sql = " SELECT purchase_order_id, 
        purchase_order_type, 
        purchase_order_code, 
        purchase_order_date, 
        purchase_order_rewrite_id,
        IFNULL((
            SELECT COUNT(*) FROM tb_purchase_order WHERE purchase_order_rewrite_id = tb.purchase_order_id 
        ),0) as count_rewrite,
        purchase_order_rewrite_no,
        purchase_order_status,
        purchase_order_accept_status,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb3.user_name,' ',tb3.user_lastname),'-') as accept_name, 
        purchase_order_credit_term, 
        purchase_order_delivery_term, 
        purchase_order_cancelled,
        IFNULL(CONCAT(tb2.supplier_name_en,' (',tb2.supplier_name_th,')'),'-') as supplier_name, 
        purchase_order_delivery_by 
        FROM tb_purchase_order as tb
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb3 ON tb.purchase_order_accept_by = tb3.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  CONCAT(tb3.user_name,' ',tb3.user_lastname) LIKE ('%$keyword%') 
            OR  purchase_order_code LIKE ('%$keyword%') 
        ) 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s'),purchase_order_code DESC 
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
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
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

   
    function cancelPurchaseOrderByID($id){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_cancelled = '1', 
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

    function uncancelPurchaseOrderByID($id){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_cancelled = '0', 
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
        purchase_order_total = '".$data['purchase_order_total']."', 
        purchase_order_vat = '".$data['purchase_order_vat']."', 
        purchase_order_net = '".$data['purchase_order_net']."',
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
                    FROM tb_delivery_note_supplier_list 
                    WHERE purchase_order_list_id = 0 
                    UNION 
                    SELECT DISTINCT product_id 
                    FROM tb_purchase_request_list 
                    LEFT JOIN tb_purchase_request ON tb_purchase_request_list.purchase_request_id = tb_purchase_request.purchase_request_id
                    WHERE purchase_order_list_id = 0 
                    AND tb_purchase_request.purchase_request_cancelled = 0 
                    AND purchase_request_accept_status = 'Approve' 
                    UNION 
                    SELECT DISTINCT product_id 
                    FROM tb_customer_purchase_order_list 
                    LEFT JOIN tb_customer_purchase_order_list_detail 
                    ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_customer_purchase_order_list.customer_purchase_order_list_id
                    WHERE tb_customer_purchase_order_list_detail.purchase_order_list_id = 0   
                    AND tb_customer_purchase_order_list_detail.supplier_id != 0 
                    UNION 
                    SELECT DISTINCT product_id 
                    FROM tb_regrind_supplier_receive 
                    LEFT JOIN tb_regrind_supplier_receive_list 
                    ON  tb_regrind_supplier_receive.regrind_supplier_receive_id = tb_regrind_supplier_receive_list.regrind_supplier_receive_id
                    WHERE tb_regrind_supplier_receive_list.purchase_order_list_id = 0   
                    AND tb_regrind_supplier_receive.supplier_id != 0 
                )
                AND product_supplier_status = 'Active' 
                GROUP BY tb_product_supplier.supplier_id 
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

    function generatePurchaseOrderListBySupplierId($supplier_id, $data_pr = [], $data_cpo = [], $data_dn = [], $data_srr = [], $search = ""){

        $str_pr ='0';

        if(is_array($data_pr)){ 
            for($i=0; $i < count($data_pr) ;$i++){
                $str_pr .= $data_pr[$i];
                if($i + 1 < count($data_pr)){
                    $str_pr .= ',';
                }
            }
        }else if ($data_pr != ''){
            $str_pr = $data_pr;
        }else{
            $str_pr='0';
        }


        $str_cpo ='0';

        if(is_array($data_cpo)){ 
            for($i=0; $i < count($data_cpo) ;$i++){
                $str_cpo .= $data_cpo[$i];
                if($i + 1 < count($data_cpo)){
                    $str_cpo .= ',';
                }
            }
        }else if ($data_cpo != ''){
            $str_cpo = $data_cpo;
        }else{
            $str_cpo='0';
        }


        $str_dn ='0';

        if(is_array($data_dn)){ 
            for($i=0; $i < count($data_dn) ;$i++){
                $str_dn .= $data_dn[$i];
                if($i + 1 < count($data_dn)){
                    $str_dn .= ',';
                }
            }
        }else if ($data_dn != ''){
            $str_dn = $data_dn;
        }else{
            $str_dn='0';
        }


        $str_srr ='0';

        if(is_array($data_srr)){ 
            for($i=0; $i < count($data_srr) ;$i++){
                $str_srr .= $data_srr[$i];
                if($i + 1 < count($data_srr)){
                    $str_srr .= ',';
                }
            }
        }else if ($data_srr != ''){
            $str_srr = $data_srr;
        }else{
            $str_srr='0';
        }


        $sql_request = "SELECT tb_purchase_request_list.product_id, 
        purchase_request_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        purchase_request_list_qty as purchase_order_list_qty, 
        product_buyprice as purchase_order_list_price ,
        CONCAT('PR : ',purchase_request_code) as purchase_order_list_remark 
        FROM tb_purchase_request 
        LEFT JOIN tb_purchase_request_list ON tb_purchase_request.purchase_request_id = tb_purchase_request_list.purchase_request_id 
        LEFT JOIN tb_product ON tb_purchase_request_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_supplier ON tb_purchase_request_list.product_id = tb_product_supplier.product_id 
        WHERE supplier_id = '$supplier_id' 
        AND purchase_order_list_id = 0 
        AND purchase_request_list_id NOT IN ($str_pr) 
        AND purchase_request_code LIKE ('%$search%') 
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
        customer_purchase_order_list_detail_id,
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        (qty) as purchase_order_list_qty, 
        product_buyprice as purchase_order_list_price ,
        CONCAT('Customer ',customer_name_th,' PO : ',customer_purchase_order_code) as purchase_order_list_remark 
        FROM tb_customer_purchase_order 
        LEFT JOIN tb_customer ON tb_customer_purchase_order.customer_id = tb_customer.customer_id
        LEFT JOIN tb_customer_purchase_order_list ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id 
        LEFT JOIN tb_product ON tb_customer_purchase_order_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_supplier ON tb_customer_purchase_order_list.product_id = tb_product_supplier.product_id 
        LEFT JOIN tb_customer_purchase_order_list_detail ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_customer_purchase_order_list_detail.customer_purchase_order_list_id 
        WHERE tb_customer_purchase_order_list_detail.supplier_id = '$supplier_id' 
        AND tb_customer_purchase_order_list_detail.purchase_order_list_id = 0 
        AND customer_purchase_order_list_detail_id NOT IN ($str_cpo) 
        AND customer_purchase_order_code LIKE ('%$search%') 
        AND product_supplier_status = 'Active' ";

        //echo $sql_customer."<br><br>";
        if ($result = mysqli_query($this->db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        $sql_dn = "SELECT tb_delivery_note_supplier_list.product_id, 
        delivery_note_supplier_list_id,
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        (delivery_note_supplier_list_qty) as purchase_order_list_qty, 
        product_buyprice as purchase_order_list_price ,
        CONCAT('Supplier DN : ',delivery_note_supplier_code) as purchase_order_list_remark 
        FROM tb_delivery_note_supplier 
        LEFT JOIN tb_delivery_note_supplier_list ON tb_delivery_note_supplier.delivery_note_supplier_id = tb_delivery_note_supplier_list.delivery_note_supplier_id 
        LEFT JOIN tb_product ON tb_delivery_note_supplier_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_supplier ON tb_delivery_note_supplier_list.product_id = tb_product_supplier.product_id 
        WHERE tb_product_supplier.supplier_id = '$supplier_id' 
        AND purchase_order_list_id = 0 
        AND delivery_note_supplier_list_id NOT IN ($str_dn) 
        AND delivery_note_supplier_code LIKE ('%$search%');
        ";

        //echo $sql_dn."<br><br>";

        if ($result = mysqli_query($this->db,$sql_dn, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }


        
        $sql_customer = "SELECT tb_regrind_supplier_receive_list.product_id, 
        tb_regrind_supplier_receive_list.regrind_supplier_receive_list_id, 
        '' as regrind_supplier_receive_list_id,
        CONCAT(product_code_first,product_code) as product_code, 
        IFNULL(stock_group_id,(SELECT IFNULL(MIN(stock_group_id),0) FROM tb_stock_group WHERE 1)) as stock_group_id,
        product_name,  
        regrind_supplier_receive_list_qty as purchase_order_list_qty, 
        product_buyprice as purchase_order_list_price, 
        CONCAT('PO : ',regrind_supplier_receive_code) as purchase_order_list_remark 
        FROM tb_regrind_supplier_receive 
        LEFT JOIN tb_regrind_supplier_receive_list ON tb_regrind_supplier_receive.regrind_supplier_receive_id = tb_regrind_supplier_receive_list.regrind_supplier_receive_id
        LEFT JOIN tb_product ON tb_regrind_supplier_receive_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_supplier ON tb_regrind_supplier_receive_list.product_id = tb_product_supplier.product_id 
        WHERE tb_regrind_supplier_receive.supplier_id = '$supplier_id' 
        AND purchase_order_list_id = 0 
        AND regrind_supplier_receive_list_id NOT IN ($str_srr) 
        AND (product_name LIKE ('%$search%') OR regrind_supplier_receive_code LIKE ('%$search%')) ";
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
            purchase_order_rewrite_id,
            purchase_order_rewrite_no,
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
            purchase_order_total,
            purchase_order_vat,
            purchase_order_net,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['supplier_id']."','".
        $data['employee_id']."','".
        $data['purchase_order_rewrite_id']."','".
        $data['purchase_order_rewrite_no']."','".
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
        $data['purchase_order_total']."','".
        $data['purchase_order_vat']."','".
        $data['purchase_order_net']."','".
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

        $sql = " UPDATE tb_customer_purchase_order_list_detail SET purchase_order_list_id = '0' WHERE purchase_order_list_id (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_purchase_order WHERE purchase_order_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_purchase_order_list WHERE purchase_order_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>