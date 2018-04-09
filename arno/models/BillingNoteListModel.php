<?php

require_once("BaseModel.php");
class BillingNoteListModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getBillingNoteListBy($billing_note_id){
        $sql = " SELECT billing_note_id,
        billing_note_list_id,  
        tb_billing_note_list.invoice_customer_id,
        invoice_customer_code, 
        '0' as billing_note_list_paid, 
        invoice_customer_net_price as billing_note_list_net, 
        invoice_customer_date as billing_note_list_date, 
        invoice_customer_due as billing_note_list_due, 
        billing_note_list_remark 
        FROM tb_billing_note_list LEFT JOIN tb_invoice_customer ON tb_billing_note_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        WHERE billing_note_id = '$billing_note_id' 
        ORDER BY billing_note_list_id 
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


    function insertBillingNoteList($data = []){
        $sql = " INSERT INTO tb_billing_note_list (
            billing_note_id,
            invoice_customer_id,
            billing_note_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['billing_note_id']."', 
            '".$data['invoice_customer_id']."', 
            '".$data['billing_note_list_remark']."', 
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

    

    function updateBillingNoteListById($data,$id){

        $sql = " UPDATE tb_billing_note_list 
            SET invoice_customer_id = '".$data['invoice_customer_id']."', 
            billing_note_list_remark = '".$data['billing_note_list_remark']."' 
            WHERE billing_note_list_id = '$id'
        ";
      // echo $sql . "<br><br>";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }




    function deleteBillingNoteListByID($id){
        $sql = "DELETE FROM tb_billing_note_list WHERE billing_note_list_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteBillingNoteListByBillingNoteID($id){

        $sql = "DELETE FROM tb_billing_note_list WHERE billing_note_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteBillingNoteListByBillingNoteIDNotIN($id,$data){
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


        $sql = "DELETE FROM tb_billing_note_list WHERE billing_note_id = '$id' AND billing_note_list_id NOT IN ($str) ";
     
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>