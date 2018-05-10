<?php

require_once("BaseModel.php");
class RegrindSupplierReceiveListModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getRegrindSupplierReceiveListBy($regrind_supplier_receive_id){
        $sql = " SELECT tb_regrind_supplier_receive_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        regrind_supplier_list_id,   
        regrind_supplier_receive_list_id, 
        regrind_supplier_list_id, 
        regrind_supplier_receive_list_qty,
        regrind_supplier_receive_list_remark 
        FROM tb_regrind_supplier_receive_list LEFT JOIN tb_product ON tb_regrind_supplier_receive_list.product_id = tb_product.product_id 
        WHERE regrind_supplier_receive_id = '$regrind_supplier_receive_id' 
        ORDER BY regrind_supplier_receive_list_id 
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


    function insertRegrindSupplierReceiveList($data = []){
        $sql = " INSERT INTO tb_regrind_supplier_receive_list (
            regrind_supplier_receive_id,
            product_id,
            regrind_supplier_receive_list_qty,
            regrind_supplier_receive_list_remark,
            regrind_supplier_list_id,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['regrind_supplier_receive_id']."', 
            '".$data['product_id']."', 
            '".$data['regrind_supplier_receive_list_qty']."', 
            '".$data['regrind_supplier_receive_list_remark']."',
            '".$data['regrind_supplier_list_id']."',
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

    function updateRegrindSupplierReceiveListById($data,$id){

        $sql = " UPDATE tb_regrind_supplier_receive_list 
            SET product_id = '".$data['product_id']."', 
            regrind_supplier_receive_list_qty = '".$data['regrind_supplier_receive_list_qty']."',
            regrind_supplier_receive_list_remark = '".$data['regrind_supplier_receive_list_remark']."' 
            WHERE regrind_supplier_receive_list_id = '$id'
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updatePurchaseOrderId($regrind_supplier_receive_list_id,$regrind_supplier_list_id){
        $sql = " UPDATE tb_regrind_supplier_receive_list 
            SET regrind_supplier_list_id = '$regrind_supplier_list_id' 
            WHERE regrind_supplier_receive_list_id = '$regrind_supplier_receive_list_id' 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteRegrindSupplierReceiveListByID($id){
        $sql = "DELETE FROM tb_regrind_supplier_receive_list WHERE regrind_supplier_receive_list_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteRegrindSupplierReceiveListByRegrindSupplierReceiveID($id){
        $sql = "DELETE FROM tb_regrind_supplier_receive_list WHERE regrind_supplier_receive_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteRegrindSupplierReceiveListByRegrindSupplierReceiveIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_regrind_supplier_receive_list WHERE regrind_supplier_receive_id = '$id' AND regrind_supplier_receive_list_id NOT IN ($str) ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>