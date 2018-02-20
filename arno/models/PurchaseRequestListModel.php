<?php

require_once("BaseModel.php");
class PurchaseRequestListModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getPurchaseRequestListBy($purchase_request_id){
        $sql = " SELECT tb_purchase_request_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        purchase_request_list_id, 
        purchase_request_list_qty,
        purchase_request_list_delivery,
        purchase_request_list_remark 
        FROM tb_purchase_request_list LEFT JOIN tb_product ON tb_purchase_request_list.product_id = tb_product.product_id 
        WHERE purchase_request_id = '$purchase_request_id' 
        ORDER BY purchase_request_list_id 
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


    function insertPurchaseRequestList($data = []){
        $sql = " INSERT INTO tb_purchase_request_list (
            purchase_request_id,
            product_id,
            purchase_request_list_qty,
            purchase_request_list_delivery,
            purchase_request_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['purchase_request_id']."', 
            '".$data['product_id']."', 
            '".$data['purchase_request_list_qty']."', 
            '".$data['purchase_request_list_delivery']."', 
            '".$data['purchase_request_list_remark']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }

    function updatePurchaseRquestListById($data,$id){

        $sql = " UPDATE tb_purchase_request_list 
            SET product_id = '".$data['product_id']."', 
            purchase_request_list_qty = '".$data['purchase_request_list_qty']."',
            purchase_request_list_delivery = '".$data['purchase_request_list_delivery']."', 
            purchase_request_list_remark = '".$data['purchase_request_list_remark']."' 
            WHERE purchase_request_list_id = '$id'
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updatePurchaseOrderId($purchase_request_list_id,$purchase_order_list_id){
        $sql = " UPDATE tb_purchase_request_list 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE purchase_request_list_id = '$purchase_request_list_id' 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deletePurchaseRequestListByID($id){
        $sql = "DELETE FROM tb_purchase_request_list WHERE purchase_request_list_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deletePurchaseRequestListByPurchaseRequestID($id){
        $sql = "DELETE FROM tb_purchase_request_list WHERE purchase_request_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deletePurchaseRequestListByPurchaseRequestIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_purchase_request_list WHERE purchase_request_id = '$id' AND purchase_request_list_id NOT IN ($str) ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>