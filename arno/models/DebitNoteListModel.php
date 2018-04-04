<?php

require_once("BaseModel.php");
class DebitNoteListModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getDebitNoteListBy($debit_note_id){
        $sql = " SELECT tb_debit_note_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        debit_note_list_id,  
        debit_note_list_product_name, 
        debit_note_list_product_detail, 
        debit_note_list_qty, 
        debit_note_list_price, 
        debit_note_list_total, 
        debit_note_list_remark,
        invoice_customer_list_id,
        stock_group_id
        FROM tb_debit_note_list LEFT JOIN tb_product ON tb_debit_note_list.product_id = tb_product.product_id 
        WHERE debit_note_id = '$debit_note_id' 
        ORDER BY debit_note_list_id 
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


    function insertDebitNoteList($data = []){
        $sql = " INSERT INTO tb_debit_note_list (
            debit_note_id,
            product_id,
            debit_note_list_product_name,
            debit_note_list_product_detail,
            debit_note_list_qty,
            debit_note_list_price, 
            debit_note_list_total,
            debit_note_list_remark,
            invoice_customer_list_id,
            stock_group_id,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['debit_note_id']."', 
            '".$data['product_id']."', 
            '".$data['debit_note_list_product_name']."', 
            '".$data['debit_note_list_product_detail']."', 
            '".$data['debit_note_list_qty']."', 
            '".$data['debit_note_list_price']."', 
            '".$data['debit_note_list_total']."', 
            '".$data['debit_note_list_remark']."',
            '".$data['invoice_customer_list_id']."',
            '".$data['stock_group_id']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

       // echo $sql . "<br><br>";
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $id = mysqli_insert_id($this->db);
            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateDebitNoteListById($data,$id){

        $sql = " UPDATE tb_debit_note_list 
            SET product_id = '".$data['product_id']."', 
            debit_note_list_product_name = '".$data['debit_note_list_product_name']."', 
            debit_note_list_product_detail = '".$data['debit_note_list_product_detail']."',
            debit_note_list_qty = '".$data['debit_note_list_qty']."',
            debit_note_list_price = '".$data['debit_note_list_price']."', 
            debit_note_list_total = '".$data['debit_note_list_price_sum']."',
            debit_note_list_remark = '".$data['debit_note_list_remark']."', 
            invoice_customer_list_id = '".$data['invoice_customer_list_id']."', 
            stock_group_id = '".$data['stock_group_id']."'
            WHERE debit_note_list_id = '$id'
        ";
      // echo $sql . "<br><br>";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }




    function deleteDebitNoteListByID($id){
        $sql = "DELETE FROM tb_debit_note_list WHERE debit_note_list_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteDebitNoteListByDebitNoteID($id){

        $sql = "DELETE FROM tb_debit_note_list WHERE debit_note_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteDebitNoteListByDebitNoteIDNotIN($id,$data){
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


        $sql = "DELETE FROM tb_debit_note_list WHERE debit_note_id = '$id' AND debit_note_list_id NOT IN ($str) ";
     
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>