<?php

require_once("BaseModel.php");
class RegrindSupplierListModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getRegrindSupplierListBy($regrind_supplier_id){
        $sql = " SELECT tb_regrind_supplier_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        regrind_supplier_list_id, 
        regrind_supplier_list_qty,
        regrind_supplier_list_remark 
        FROM tb_regrind_supplier_list LEFT JOIN tb_product ON tb_regrind_supplier_list.product_id = tb_product.product_id 
        WHERE regrind_supplier_id = '$regrind_supplier_id' 
        ORDER BY regrind_supplier_list_id 
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


    function insertRegrindSupplierList($data = []){
        $sql = " INSERT INTO tb_regrind_supplier_list (
            regrind_supplier_id,
            product_id,
            regrind_supplier_list_qty,
            regrind_supplier_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['regrind_supplier_id']."', 
            '".$data['product_id']."', 
            '".$data['regrind_supplier_list_qty']."', 
            '".$data['regrind_supplier_list_remark']."',
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

    function updateRegrindSupplierListById($data,$id){

        $sql = " UPDATE tb_regrind_supplier_list 
            SET product_id = '".$data['product_id']."', 
            regrind_supplier_list_qty = '".$data['regrind_supplier_list_qty']."',
            regrind_supplier_list_remark = '".$data['regrind_supplier_list_remark']."' 
            WHERE regrind_supplier_list_id = '$id'
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updatePurchaseOrderId($regrind_supplier_list_id,$purchase_order_list_id){
        $sql = " UPDATE tb_regrind_supplier_list 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE regrind_supplier_list_id = '$regrind_supplier_list_id' 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteRegrindSupplierListByID($id){
        $sql = "DELETE FROM tb_regrind_supplier_list WHERE regrind_supplier_list_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteRegrindSupplierListByRegrindSupplierID($id){
        $sql = "DELETE FROM tb_regrind_supplier_list WHERE regrind_supplier_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteRegrindSupplierListByRegrindSupplierIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_regrind_supplier_list WHERE regrind_supplier_id = '$id' AND regrind_supplier_list_id NOT IN ($str) ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>