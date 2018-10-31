<?php

require_once("BaseModel.php");
class CheckPayModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCheckPayBy($check_pay_type = "",$date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$check_pay_status = ""){

        $str_supplier = "";
        $str_date = "";
        $str_type = "";
        $str_status = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(check_pay_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(check_pay_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(check_pay_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(check_pay_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }


        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }

        if($check_pay_type != ""){
            $str_type = "AND check_pay_type = '$check_pay_type' ";
        }

        if($check_pay_status != ""){
            $str_status = "AND check_pay_status = '$check_pay_status' ";
        }


        $sql = " SELECT *
        FROM tb_check_pay 
        LEFT JOIN tb_supplier as tb2 ON tb_check_pay.supplier_id = tb2.supplier_id 
        WHERE ( 
             check_pay_code LIKE ('%$keyword%') 
        ) 
        $str_type
        $str_status 
        $str_supplier 
        $str_date 
        ORDER BY STR_TO_DATE(check_pay_date,'%d-%m-%Y %H:%i:%s'), check_pay_code DESC 
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


    function getCheckPayPassViewByID($id){
        $sql = " SELECT *   
        FROM tb_check_pay 
        LEFT JOIN tb_bank_account ON tb_check_pay.bank_account_id = tb_bank_account.bank_account_id
        WHERE check_pay_id = '$id' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }


    function getCheckPayByID($id){
        $sql = " SELECT * 
        FROM tb_check_pay 
        WHERE check_pay_id = '$id' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCheckPayByCode($check_pay_code){
        $sql = " SELECT * 
        FROM tb_check_pay 
        WHERE check_pay_code = '$check_pay_code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCheckPayViewByID($id){
        $sql = " SELECT *   
        FROM tb_check_pay 
        LEFT JOIN tb_bank_account ON tb_check_pay.bank_account_id = tb_bank_account.bank_account_id  
        WHERE check_pay_id = '$id' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCheckPayViewListByjournalPaymentID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_payment_list 
        LEFT JOIN tb_check_pay ON tb_journal_cash_payment_list.journal_cheque_pay_id = tb_check_pay.check_pay_id
        LEFT JOIN tb_bank_account ON tb_check_pay.bank_account_id = tb_bank_account.bank_account_id
        WHERE journal_cash_payment_id = '$id' AND tb_journal_cash_payment_list.journal_cheque_pay_id > 0
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }

    }
    
    function getCheckPayViewListByjournalReceiptID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_receipt_list 
        LEFT JOIN tb_check_pay ON tb_journal_cash_receipt_list.journal_cheque_pay_id = tb_check_pay.check_pay_id
        LEFT JOIN tb_bank_account ON tb_check_pay.bank_account_id = tb_bank_account.bank_account_id
        WHERE journal_cash_receipt_id = '$id' AND tb_journal_cash_receipt_list.journal_cheque_pay_id > 0
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCheckPayLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(check_pay_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  check_pay_lastcode 
        FROM tb_check_pay 
        WHERE check_pay_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['check_pay_lastcode'];
        }

    }
 
    function updateCheckPayByID($id,$data = []){
        $sql = " UPDATE tb_check_pay SET 
        check_pay_code = '".$data['check_pay_code']."', 
        check_pay_date_write = '".$data['check_pay_date_write']."', 
        check_pay_date = '".$data['check_pay_date']."', 
        bank_account_id = '".$data['bank_account_id']."', 
        supplier_id = '".$data['supplier_id']."', 
        check_pay_remark = '".static::$db->real_escape_string($data['check_pay_remark'])."', 
        check_pay_total = '".$data['check_pay_total']."', 
        check_pay_type = '".$data['check_pay_type']."',  
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE check_pay_id = $id 
        "; 

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function updateCheckPayPassByID($id,$data = []){
        $sql = " UPDATE tb_check_pay SET 
        check_pay_status = '".$data['check_pay_status']."', 
        check_pay_date_pass = '".$data['check_pay_date_pass']."',
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE check_pay_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            if($data['check_pay_status'] == '1'){
                $cheque_pay = $this->getCheckPayPassViewByID($id);
                $journal = $this->getJournalListByChequePayID($id);
                for($i = 0 ; $i < count($journal) ; $i++){

                    $sql = " INSERT INTO tb_journal_".$journal[$i]['tb_name']."_list (
                        journal_".$journal[$i]['tb_name']."_id,
                        journal_cheque_id,
                        journal_cheque_pay_id,
                        journal_invoice_customer_id,
                        journal_invoice_supplier_id,
                        account_id,
                        journal_".$journal[$i]['tb_name']."_list_name,
                        journal_".$journal[$i]['tb_name']."_list_debit,
                        journal_".$journal[$i]['tb_name']."_list_credit,
                        addby,
                        adddate,
                        updateby,
                        lastupdate
                    ) VALUES (
                        '".$journal[$i]['journal_id']."',   
                        '0',
                        '".$cheque_pay['check_pay_id']."', 
                        '0', 
                        '0',  
                        (SELECT account_id FROM tb_account_setting WHERE tb_account_setting.account_setting_id = '13' LIMIT 0 , 1), 
                        '".$journal[$i]['journal_name']."', 
                        '".$journal[$i]['journal_credit']."',
                        '".$journal[$i]['journal_debit']."',
                        '".$data['addby']."', 
                        NOW(), 
                        '".$data['updateby']."', 
                        NOW() 
                    ); 
                    ";

                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
                    //echo $sql."<br><br>";

                    $sql = " INSERT INTO tb_journal_".$journal[$i]['tb_name']."_list (
                        journal_".$journal[$i]['tb_name']."_id,
                        journal_cheque_id,
                        journal_cheque_pay_id,
                        journal_invoice_customer_id,
                        journal_invoice_supplier_id,
                        account_id,
                        journal_".$journal[$i]['tb_name']."_list_name,
                        journal_".$journal[$i]['tb_name']."_list_debit,
                        journal_".$journal[$i]['tb_name']."_list_credit,
                        addby,
                        adddate,
                        updateby,
                        lastupdate
                    ) VALUES (
                        '".$journal[$i]['journal_id']."',   
                        '0',
                        '".$cheque_pay['check_pay_id']."', 
                        '0', 
                        '0',  
                        '".$cheque_pay['account_id']."', 
                        '".$journal[$i]['journal_name']."', 
                        '0',
                        '".$cheque_pay['check_pay_total']."',
                        '".$data['addby']."', 
                        NOW(), 
                        '".$data['updateby']."', 
                        NOW() 
                    ); 
                    ";

                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                    //echo $sql."<br><br>";

                }
            }else{

                $cheque_pay = $this->getCheckPayPassViewByID($id);
                $journal = $this->getJournalListByChequePayID($id);

                for($i = 0 ; $i < count($journal) ; $i++){
                    if($journal[$i-1]['journal_code'] != $journal[$i]['journal_code']){
                        $sql = " DELETE FROM tb_journal_".$journal[$i]['tb_name']."_list WHERE journal_cheque_pay_id = '".$cheque_pay['check_pay_id']."' AND journal_".$journal[$i]['tb_name']."_id = '".$journal[$i]['journal_id']."'";
                        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    
                        $sql = " INSERT INTO tb_journal_".$journal[$i]['tb_name']."_list (
                            journal_".$journal[$i]['tb_name']."_id,
                            journal_cheque_id,
                            journal_cheque_pay_id,
                            journal_invoice_customer_id,
                            journal_invoice_supplier_id,
                            account_id,
                            journal_".$journal[$i]['tb_name']."_list_name,
                            journal_".$journal[$i]['tb_name']."_list_debit,
                            journal_".$journal[$i]['tb_name']."_list_credit,
                            addby,
                            adddate,
                            updateby,
                            lastupdate
                        ) VALUES (
                            '".$journal[$i]['journal_id']."', 
                            '0',  
                            '".$cheque_pay['check_pay_id']."', 
                            '0', 
                            '0', 
                            (SELECT account_id FROM tb_account_setting WHERE tb_account_setting.account_setting_id = '13' LIMIT 0 , 1), 
                            '".$journal[$i]['journal_name']."', 
                            '0',
                            '".$cheque_pay['check_pay_total']."',
                            '".$data['addby']."', 
                            NOW(), 
                            '".$data['updateby']."', 
                            NOW() 
                        ); 
                        ";
    
                        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
                    }
                   
                    //echo $sql."<br><br>";

                }

            }
           return true;
        }else {
            return false;
        }

    }

    
    function getJournalByChequePayID($id){
        //------------------------- General Journal ------------------------------------------------------------- 
        $sql_general = " SELECT 
        tb_journal_general.journal_general_id as journal_id, 
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name 
        FROM tb_journal_general  
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE  journal_cheque_pay_id = '$id' 
        GROUP BY tb_journal_general.journal_general_id 
        ORDER BY journal_general_code DESC 
        "; 
        //------------------------- End General Journal ------------------------------------------------------------- 



        //------------------------- Purchase Journal -------------------------------------------------------------  
        $sql_purchase = " SELECT 
        tb_journal_purchase.journal_purchase_id as journal_id, 
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name  
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE  journal_cheque_pay_id = '$id' 
        GROUP BY tb_journal_purchase.journal_purchase_id 
        ORDER BY journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal ------------------------------------------------------------- 
        $sql_sale = " SELECT 
        tb_journal_sale.journal_sale_id as journal_id, 
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name 
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE  journal_cheque_pay_id = '$id' 
        GROUP BY tb_journal_sale.journal_sale_id  
        ORDER BY journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal ------------------------------------------------------------- 
        $sql_cash_payment = " SELECT 
        tb_journal_cash_payment.journal_cash_payment_id as journal_id, 
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name 
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE  journal_cheque_pay_id = '$id' 
        GROUP BY tb_journal_cash_payment.journal_cash_payment_id 
        ORDER BY journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal ------------------------------------------------------------- 
        $sql_cash_receipt = " SELECT
        tb_journal_cash_receipt.journal_cash_receipt_id as journal_id, 
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name 
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
        WHERE  journal_cheque_pay_id = '$id' 
        GROUP BY tb_journal_cash_receipt.journal_cash_receipt_id 
        ORDER BY journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


       $sql =" SELECT *
               FROM   (($sql_general)  
               UNION   ALL  ($sql_purchase) 
               UNION   ALL  ($sql_sale) 
               UNION   ALL  ($sql_cash_payment) 
               UNION   ALL  ($sql_cash_receipt)) as tb_journal    
               ORDER BY journal_code ASC
        ";  

       if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           $data = [];
           while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
               $data [] = $row;
           }
           $result->close();
           return $data;
       }
   }

    function getJournalListByChequePayID($id){
        //------------------------- General Journal ------------------------------------------------------------- 
        $sql_general = " SELECT 
        tb_journal_general.journal_general_id as journal_id,
        'general' as tb_name,
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        account_id,
        journal_general_list_name as journal_list_name,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit 
        FROM tb_journal_general  
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE  journal_cheque_pay_id = '$id' 
        GROUP BY tb_journal_general_list.journal_general_list_id 
        ORDER BY journal_general_code DESC 
        "; 
        //------------------------- End General Journal ------------------------------------------------------------- 



        //------------------------- Purchase Journal -------------------------------------------------------------  
        $sql_purchase = " SELECT 
        tb_journal_purchase.journal_purchase_id as journal_id,
        'purchase' as tb_name,
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        account_id,
        journal_purchase_list_name as journal_list_name,
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE  journal_cheque_pay_id = '$id' 
        GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
        ORDER BY journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal ------------------------------------------------------------- 
        $sql_sale = " SELECT 
        tb_journal_sale.journal_sale_id as journal_id,
        'sale' as tb_name,
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        account_id,
        journal_sale_list_name as journal_list_name,
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE  journal_cheque_pay_id = '$id' 
        GROUP BY tb_journal_sale_list.journal_sale_list_id 
        ORDER BY journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal ------------------------------------------------------------- 
        $sql_cash_payment = " SELECT 
        tb_journal_cash_payment.journal_cash_payment_id as journal_id,
        'cash_payment' as tb_name, 
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        account_id,
        journal_cash_payment_list_name as journal_list_name,
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE  journal_cheque_pay_id = '$id' 
        GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
        ORDER BY journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal ------------------------------------------------------------- 
        $sql_cash_receipt = " SELECT
        tb_journal_cash_receipt.journal_cash_receipt_id as journal_id,
        'cash_receipt' as tb_name, 
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        account_id,
        journal_cash_receipt_list_name as journal_list_name,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
        WHERE  journal_cheque_pay_id = '$id' 
        GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
        ORDER BY journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


       $sql =" SELECT *
               FROM   (($sql_general)  
               UNION   ALL  ($sql_purchase) 
               UNION   ALL  ($sql_sale) 
               UNION   ALL  ($sql_cash_payment) 
               UNION   ALL  ($sql_cash_receipt)) as tb_journal    
               ORDER BY journal_code ASC
        "; 

       //echo $sql."<br><br>";

       if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           $data = [];
           while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
               $data [] = $row;
           }
           $result->close();
           return $data;
       }
   }



    function insertCheckPay($data = []){
        $sql = " INSERT INTO tb_check_pay (
            check_pay_code,
            check_pay_date_write,
            check_pay_date,
            bank_account_id,
            supplier_id, 
            check_pay_remark,
            check_pay_total,
            check_pay_type,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['check_pay_code']."','".
        $data['check_pay_date_write']."','".
        $data['check_pay_date']."','".
        $data['bank_account_id']."','".
        $data['supplier_id']."','".
        static::$db->real_escape_string($data['check_pay_remark'])."','".
        $data['check_pay_total']."','".
        $data['check_pay_type']."','". 
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }



    function deleteCheckPayByID($id){
        $sql = " DELETE FROM tb_check_pay WHERE check_pay_id = '$id' ";
        if ( mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            $journal = $this->getJournalListByChequePayID($id);

            for($i = 0 ; $i < count($journal) ; $i++){
                if($journal[$i-1]['journal_code'] != $journal[$i]['journal_code']){
                    $sql = " DELETE FROM tb_journal_".$journal[$i]['tb_name']."_list WHERE journal_cheque_id = '".$id."' AND journal_".$journal[$i]['tb_name']."_id = '".$journal[$i]['journal_id']."'";
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
                }
            }
            return true;
        }else{
            return false;
        }
    }


}
?>