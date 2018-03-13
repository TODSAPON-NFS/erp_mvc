<?php

require_once("BaseModel.php");
class DeliveryNoteCustomerListModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getDeliveryNoteCustomerListBy($delivery_note_customer_id){
        $sql = " SELECT tb_delivery_note_customer_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        delivery_note_customer_list_id, 
        delivery_note_customer_list_qty,
        delivery_note_customer_list_remark 
        FROM tb_delivery_note_customer_list LEFT JOIN tb_product ON tb_delivery_note_customer_list.product_id = tb_product.product_id 
        WHERE delivery_note_customer_id = '$delivery_note_customer_id' 
        ORDER BY delivery_note_customer_list_id 
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


    function insertDeliveryNoteCustomerList($data = []){
        $sql = " INSERT INTO tb_delivery_note_customer_list (
            delivery_note_customer_id,
            product_id,
            delivery_note_customer_list_qty,
            delivery_note_customer_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['delivery_note_customer_id']."', 
            '".$data['product_id']."', 
            '".$data['delivery_note_customer_list_qty']."', 
            '".$data['delivery_note_customer_list_remark']."',
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

    function updateDeliveryNoteCustomerListById($data,$id){

        $sql = " UPDATE tb_delivery_note_customer_list 
            SET product_id = '".$data['product_id']."', 
            delivery_note_customer_list_qty = '".$data['delivery_note_customer_list_qty']."',
            delivery_note_customer_list_remark = '".$data['delivery_note_customer_list_remark']."' 
            WHERE delivery_note_customer_list_id = '$id'
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updatePurchaseOrderId($delivery_note_customer_list_id,$purchase_order_list_id){
        $sql = " UPDATE tb_delivery_note_customer_list 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE delivery_note_customer_list_id = '$delivery_note_customer_list_id' 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteDeliveryNoteCustomerListByID($id){
        $sql = "DELETE FROM tb_delivery_note_customer_list WHERE delivery_note_customer_list_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteDeliveryNoteCustomerListByDeliveryNoteCustomerID($id){
        $sql = "DELETE FROM tb_delivery_note_customer_list WHERE delivery_note_customer_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteDeliveryNoteCustomerListByDeliveryNoteCustomerIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                if($data[$i] != ""){
                    $str .= $data[$i];
                    if($i + 1 < count($data)){
                        $str .= ',';
                    }
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        if( $str==''){
            $str='0';
        }

        $sql = "DELETE FROM tb_delivery_note_customer_list WHERE delivery_note_customer_id = '$id' AND delivery_note_customer_list_id NOT IN ($str) ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>