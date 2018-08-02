<?php

require_once("BaseModel.php");
class JournalCashPaymentListInvoiceModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJournalCashPaymentListInvoiceBy($journal_cash_payment_id){
        $sql = " SELECT *
        FROM tb_journal_cash_payment_list_invoice  
        WHERE journal_cash_payment_id = '$journal_cash_payment_id' 
        ORDER BY journal_cash_payment_list_invoice_id 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data ;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }


    function insertJournalCashPaymentListInvoice($data = []){
        $sql = " INSERT INTO tb_journal_cash_payment_list_invoice (
            journal_cash_payment_id,
            invoice_code,
            invoice_date,
            vat_section,
            vat_section_add,
            product_price,
            product_vat,
            product_price_non,
            product_vat_non,
            product_non
        ) VALUES (
            '".$data['journal_cash_payment_id']."', 
            '".$data['invoice_code']."', 
            '".$data['invoice_date']."', 
            '".$data['vat_section']."',
            '".$data['vat_section_add']."',
            '".$data['product_price']."',   
            '".$data['product_vat']."',  
            '".$data['product_price_non']."',  
            '".$data['product_vat_non']."',  
            '".$data['product_non']."'  
        ); 
        ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateJournalCashPaymentListInvoiceById($data,$id){

        $sql = " UPDATE tb_journal_cash_payment_list_invoice 
            SET invoice_code = '".$data['invoice_code']."', 
            invoice_date = '".$data['invoice_date']."',
            vat_section = '".$data['vat_section']."',
            vat_section_add = '".$data['vat_section_add']."',
            product_price = '".$data['product_price']."',
            product_vat = '".$data['product_vat']."',
            product_price_non = '".$data['product_price_non']."',
            product_vat_non = '".$data['product_vat_non']."',
            product_non = '".$data['product_non']."' 
            WHERE journal_cash_payment_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteJournalCashPaymentListInvoiceByID($id){
        $sql = "DELETE FROM tb_journal_cash_payment_list_invoice WHERE journal_cash_payment_list_invoice_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalCashPaymentListInvoiceByJournalCashPaymentID($id){
        $sql = "DELETE FROM tb_journal_cash_payment_list_invoice WHERE journal_cash_payment_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>