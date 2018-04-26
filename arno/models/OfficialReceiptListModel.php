<?php

require_once("BaseModel.php");
class OfficialReceiptListModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getOfficialReceiptListBy($official_receipt_id){
        $sql = " SELECT official_receipt_id,
        official_receipt_list_id,  
        tb_official_receipt_list.invoice_customer_id,
        invoice_customer_code, 
        '0' as official_receipt_list_paid, 
        invoice_customer_net_price as official_receipt_list_net, 
        invoice_customer_date as official_receipt_list_date, 
        invoice_customer_due as official_receipt_list_due, 
        official_receipt_list_remark 
        FROM tb_official_receipt_list LEFT JOIN tb_invoice_customer ON tb_official_receipt_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        WHERE official_receipt_id = '$official_receipt_id' 
        ORDER BY official_receipt_list_id 
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


    function insertOfficialReceiptList($data = []){
        $sql = " INSERT INTO tb_official_receipt_list (
            official_receipt_id,
            invoice_customer_id,
            official_receipt_inv_amount,
            official_receipt_bal_amount,
            official_receipt_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['official_receipt_id']."', 
            '".$data['invoice_customer_id']."',
            '".$data['official_receipt_inv_amount']."',
            '".$data['official_receipt_bal_amount']."',
            '".$data['official_receipt_list_remark']."', 
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

    

    function updateOfficialReceiptListById($data,$id){

        $sql = " UPDATE tb_official_receipt_list 
            SET invoice_customer_id = '".$data['invoice_customer_id']."', 
            official_receipt_inv_amount = '".$data['official_receipt_inv_amount']."' 
            official_receipt_bal_amount = '".$data['official_receipt_bal_amount']."' 
            official_receipt_list_remark = '".$data['official_receipt_list_remark']."' 
            WHERE official_receipt_list_id = '$id'
        ";
      // echo $sql . "<br><br>";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }




    function deleteOfficialReceiptListByID($id){
        $sql = "DELETE FROM tb_official_receipt_list WHERE official_receipt_list_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteOfficialReceiptListByOfficialReceiptID($id){

        $sql = "DELETE FROM tb_official_receipt_list WHERE official_receipt_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteOfficialReceiptListByOfficialReceiptIDNotIN($id,$data){
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


        $sql = "DELETE FROM tb_official_receipt_list WHERE official_receipt_id = '$id' AND official_receipt_list_id NOT IN ($str) ";
     
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>