<?php

require_once("BaseModel.php");
class CustomerPurchaseOrderListModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getCustomerPurchaseOrderListBy($customer_purchase_order_id){
        $sql = " SELECT tb_customer_purchase_order_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        customer_purchase_order_product_name, 
        customer_purchase_order_product_detail, 
        customer_purchase_order_list_id,  
        customer_purchase_order_list_qty, 
        customer_purchase_order_list_price, 
        customer_purchase_order_list_price_sum, 
        customer_purchase_order_list_delivery_min,  
        customer_purchase_order_list_delivery_max, 
        customer_purchase_order_list_remark, 
        customer_purchase_order_list_hold  
        FROM tb_customer_purchase_order_list LEFT JOIN tb_product ON tb_customer_purchase_order_list.product_id = tb_product.product_id 
        WHERE customer_purchase_order_id = '$customer_purchase_order_id' 
        ORDER BY customer_purchase_order_list_id 
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


    function insertCustomerPurchaseOrderList($data = []){
        $sql = " INSERT INTO tb_customer_purchase_order_list (
            customer_purchase_order_id,
            product_id,
            customer_purchase_order_product_name, 
            customer_purchase_order_product_detail, 
            customer_purchase_order_list_qty,
            customer_purchase_order_list_price, 
            customer_purchase_order_list_price_sum,
            customer_purchase_order_list_delivery_min, 
            customer_purchase_order_list_delivery_max,
            customer_purchase_order_list_remark,
            customer_purchase_order_list_hold,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['customer_purchase_order_id']."', 
            '".$data['product_id']."', 
            '".$data['customer_purchase_order_product_name']."', 
            '".$data['customer_purchase_order_product_detail']."', 
            '".$data['customer_purchase_order_list_qty']."', 
            '".$data['customer_purchase_order_list_price']."', 
            '".$data['customer_purchase_order_list_price_sum']."', 
            '".$data['customer_purchase_order_list_delivery_min']."', 
            '".$data['customer_purchase_order_list_delivery_max']."', 
            '".$data['customer_purchase_order_list_remark']."',
            '".$data['customer_purchase_order_list_hold']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }

    function updateCustomerPurchaseOrderListById($data,$id){

        $sql = " UPDATE tb_customer_purchase_order_list 
            SET product_id = '".$data['product_id']."', 
            customer_purchase_order_product_name = '".$data['customer_purchase_order_product_name']."', 
            customer_purchase_order_product_detail = '".$data['customer_purchase_order_product_detail']."', 
            customer_purchase_order_list_qty = '".$data['customer_purchase_order_list_qty']."', 
            customer_purchase_order_list_price = '".$data['customer_purchase_order_list_price']."', 
            customer_purchase_order_list_price_sum = '".$data['customer_purchase_order_list_price_sum']."', 
            customer_purchase_order_list_hold = '".$data['customer_purchase_order_list_hold']."', 
            customer_purchase_order_list_delivery_min = '".$data['customer_purchase_order_list_delivery_min']."', 
            customer_purchase_order_list_delivery_max = '".$data['customer_purchase_order_list_delivery_max']."', 
            customer_purchase_order_list_remark = '".$data['customer_purchase_order_list_remark']."', 
            purchase_order_list_id = '".$data['purchase_order_list_id']."'
            WHERE customer_purchase_order_list_id = '$id'
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updatePurchaseOrderId($customer_purchase_order_list_id,$purchase_order_list_id){
        $sql = " UPDATE tb_customer_purchase_order_list 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE customer_purchase_order_list_id = '$customer_purchase_order_list_id' 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteCustomerPurchaseOrderListByID($id){
        $sql = "DELETE FROM tb_customer_purchase_order_list WHERE customer_purchase_order_list_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteCustomerPurchaseOrderListByCustomerPurchaseOrderID($id){
        $sql = "DELETE FROM tb_customer_purchase_order_list WHERE customer_purchase_order_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteCustomerPurchaseOrderListByCustomerPurchaseOrderIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= $data[$i];
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        $sql = "DELETE FROM tb_customer_purchase_order_list WHERE customer_purchase_order_id = '$id' AND customer_purchase_order_list_id NOT IN ($str) ";
     
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>