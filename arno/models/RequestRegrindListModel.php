<?php

require_once("BaseModel.php");
class RequestRegrindListModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getRequestRegrindListBy($request_regrind_id){
        $sql = " SELECT tb_request_regrind_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        request_regrind_list_id, 
        request_regrind_list_qty,
        request_regrind_list_delivery,
        request_regrind_list_remark, 
        tool_test_result,
        request_test_list_id
        FROM tb_request_regrind_list LEFT JOIN tb_product ON tb_request_regrind_list.product_id = tb_product.product_id 
        WHERE request_regrind_id = '$request_regrind_id' 
        ORDER BY request_regrind_list_id 
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


    function insertRequestRegrindList($data = []){
        $sql = " INSERT INTO tb_request_regrind_list (
            request_regrind_id,
            product_id,
            request_regrind_list_qty,
            request_regrind_list_delivery,
            request_regrind_list_remark, 
            tool_test_result,
            request_test_list_id,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['request_regrind_id']."', 
            '".$data['product_id']."', 
            '".$data['request_regrind_list_qty']."', 
            '".$data['request_regrind_list_delivery']."', 
            '".$data['request_regrind_list_remark']."', 
            '".$data['tool_test_result']."',
            '".$data['request_test_list_id']."',
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

        $sql = " UPDATE tb_request_regrind_list 
            SET product_id = '".$data['product_id']."', 
            request_regrind_list_qty = '".$data['request_regrind_list_qty']."', 
            request_regrind_list_delivery = '".$data['request_regrind_list_delivery']."', 
            request_regrind_list_remark = '".$data['request_regrind_list_remark']."', 
            tool_test_result = '".$data['tool_test_result']."' , 
            request_test_list_id = '".$data['request_test_list_id']."'  
            WHERE request_regrind_list_id = '$id' 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updateRequestTestListId($request_regrind_list_id,$request_test_list_id){
        $sql = " UPDATE tb_request_regrind_list 
            SET request_test_list_id = '$request_test_list_id' 
            WHERE request_regrind_list_id = '$request_regrind_list_id' 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteRequestRegrindListByID($id){
        $sql = "DELETE FROM tb_request_regrind_list WHERE request_regrind_list_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteRequestRegrindListByRequestRegrindID($id){
        $sql = "DELETE FROM tb_request_regrind_list WHERE request_regrind_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteRequestRegrindListByRequestRegrindIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_request_regrind_list WHERE request_regrind_id = '$id' AND request_regrind_list_id NOT IN ($str) ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>