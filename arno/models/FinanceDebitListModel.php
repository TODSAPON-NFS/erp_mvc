<?php

require_once("BaseModel.php");
class FinanceDebitListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getFinanceDebitListBy($finance_debit_id){
        $sql = " SELECT finance_debit_id,
        finance_debit_list_id,  
        tb_finance_debit_list.billing_note_list_id,
        invoice_customer_code, 
        finance_debit_list_billing, 
        finance_debit_list_receipt, 
        '0' as finance_debit_list_paid, 
        invoice_customer_net_price as finance_debit_list_amount, 
        invoice_customer_date as finance_debit_list_date, 
        invoice_customer_due as finance_debit_list_due,  
        finance_debit_list_amount,
        finance_debit_list_paid,
        finance_debit_list_balance,
        finance_debit_list_remark 
        FROM tb_finance_debit_list 
        LEFT JOIN tb_billing_note_list ON tb_finance_debit_list.billing_note_list_id = tb_billing_note_list.billing_note_list_id 
        LEFT JOIN tb_invoice_customer ON tb_billing_note_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        WHERE finance_debit_id = '$finance_debit_id' 
        ORDER BY invoice_customer_code 
        ";

        //echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    function insertFinanceDebitList($data = []){
        $sql = " INSERT INTO tb_finance_debit_list (
            finance_debit_id,
            billing_note_list_id,
            finance_debit_list_billing,
            finance_debit_list_receipt,
            finance_debit_list_amount,
            finance_debit_list_paid,
            finance_debit_list_balance,
            finance_debit_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['finance_debit_id']."', 
            '".$data['billing_note_list_id']."', 
            '".$data['finance_debit_list_billing']."',
            '".$data['finance_debit_list_receipt']."',
            '".$data['finance_debit_list_amount']."',
            '".$data['finance_debit_list_paid']."',
            '".$data['finance_debit_list_balance']."',
            '".$data['finance_debit_list_remark']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $id = mysqli_insert_id(static::$db);
            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateFinanceDebitListById($data,$id){

        $sql = " UPDATE tb_finance_debit_list 
            SET billing_note_list_id = '".$data['billing_note_list_id']."', 
            finance_debit_list_billing = '".$data['finance_debit_list_billing']."', 
            finance_debit_list_receipt = '".$data['finance_debit_list_receipt']."', 
            finance_debit_list_amount = '".$data['finance_debit_list_amount']."', 
            finance_debit_list_paid = '".$data['finance_debit_list_paid']."',
            finance_debit_list_balance = '".$data['finance_debit_list_balance']."',  
            finance_debit_list_remark = '".$data['finance_debit_list_remark']."' 
            WHERE finance_debit_list_id = '$id'
        ";
      // echo $sql . "<br><br>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }




    function deleteFinanceDebitListByID($id){
        $sql = "DELETE FROM tb_finance_debit_list WHERE finance_debit_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteFinanceDebitListByFinanceDebitID($id){

        $sql = "DELETE FROM tb_finance_debit_list WHERE finance_debit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteFinanceDebitListByFinanceDebitIDNotIN($id,$data){
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


        $sql = "DELETE FROM tb_finance_debit_list WHERE finance_debit_id = '$id' AND finance_debit_list_id NOT IN ($str) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>