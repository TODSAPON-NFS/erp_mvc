<?php

require_once("BaseModel.php");
class JournalSpecialListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJournalSpecialListBy($journal_special_id){
        $sql = " SELECT 
        journal_special_list_id, 
        journal_special_list_name,
        journal_special_list_debit,
        journal_special_list_credit, 
        tb_journal_special_list.account_id, 
        tb_journal_special_list.journal_cheque_id, 
        tb_journal_special_list.journal_cheque_pay_id, 
        tb_journal_special_list.journal_invoice_customer_id, 
        tb_journal_special_list.journal_invoice_supplier_id, 
        account_name_th,  
        account_name_en 
        FROM tb_journal_special_list LEFT JOIN tb_account ON tb_journal_special_list.account_id = tb_account.account_id 
        WHERE journal_special_id = '$journal_special_id' 
        ORDER BY journal_special_list_id 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getJournalSpecialListByFinanceCreditPayId($journal_special_id,$finance_credit_pay_id){
        $sql = " SELECT 
        journal_special_list_id, 
        journal_special_list_name,
        journal_special_list_debit,
        journal_special_list_credit, 
        tb_journal_special_list.account_id, 
        tb_journal_special_list.journal_cheque_id, 
        tb_journal_special_list.journal_cheque_pay_id, 
        tb_journal_special_list.journal_invoice_customer_id, 
        tb_journal_special_list.journal_invoice_supplier_id, 
        finance_credit_pay_id,
        account_name_th,  
        account_name_en 
        FROM tb_journal_special_list LEFT JOIN tb_account ON tb_journal_special_list.account_id = tb_account.account_id 
        WHERE journal_special_id = '$journal_special_id' AND tb_journal_special_list.finance_credit_pay_id = '$finance_credit_pay_id' 
        ORDER BY journal_special_list_id 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data ;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data  = $row;
            }
            $result->close();
            return $data;
        }

    }

    function insertJournalSpecialList($data = []){

        $sql = " INSERT INTO tb_journal_special_list (
            journal_special_id,
            finance_credit_pay_id,
            finance_debiit_pay_id,
            journal_cheque_id,
            journal_cheque_pay_id,
            journal_invoice_customer_id,
            journal_invoice_supplier_id,
            account_id,
            journal_special_list_name,
            journal_special_list_debit,
            journal_special_list_credit,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['journal_special_id']."', 
            '".$data['finance_credit_pay_id']."', 
            '".$data['finance_debiit_pay_id']."', 
            '".$data['journal_cheque_id']."', 
            '".$data['journal_cheque_pay_id']."', 
            '".$data['journal_invoice_customer_id']."', 
            '".$data['journal_invoice_supplier_id']."', 
            '".$data['account_id']."', 
            '".static::$db->real_escape_string($data['journal_special_list_name'])."', 
            '".$data['journal_special_list_debit']."',
            '".$data['journal_special_list_credit']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateJournalSpecialListById($data,$id){

        $sql = " UPDATE tb_journal_special_list 
            SET account_id = '".$data['account_id']."',  
            journal_cheque_id = '".$data['journal_cheque_id']."',
            journal_cheque_pay_id = '".$data['journal_cheque_pay_id']."',
            journal_invoice_customer_id = '".$data['journal_invoice_customer_id']."',
            journal_invoice_supplier_id = '".$data['journal_invoice_supplier_id']."',
            journal_special_list_name = '".static::$db->real_escape_string($data['journal_special_list_name'])."',
            journal_special_list_debit = '".$data['journal_special_list_debit']."',
            journal_special_list_credit = '".$data['journal_special_list_credit']."' 
            WHERE journal_special_list_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function deleteJournalSpecialListByID($id){
        $sql = "DELETE FROM tb_journal_special_list WHERE journal_special_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalSpecialListByJournalSpecialID($id){
        $sql = "DELETE FROM tb_journal_special_list WHERE journal_special_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalSpecialListByFinanceCreditListIDNotIn($journal_special_id,$data){
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

        $sql = "DELETE FROM tb_journal_special_list 
                WHERE journal_special_id = '$journal_special_id' AND finance_credit_pay_id NOT IN ( $str ) AND finance_credit_pay_id NOT IN ('-1','-2') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deleteJournalSpecialListByJournalSpecialIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_journal_special_list WHERE journal_special_id = '$id' AND journal_special_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>