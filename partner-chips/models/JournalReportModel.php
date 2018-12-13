<?php

require_once("BaseModel.php");
class JournalReportModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }


    //#####################################################################################################################
    //
    //
    //------------------------------------------ ดึงรายสมุดรายวัน แบบย่อ เรียงตามวัน --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getJournalReportBy($date_start = "", $date_end = "",$keyword = ""){


        //------------------------- General Journal -------------------------------------------------------------
        $str_general_date = "";

        if($date_start != "" && $date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_general = " SELECT
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE ( 
                journal_general_code LIKE ('%$keyword%') 
            OR  journal_general_name LIKE ('%$keyword%') 
        ) 
        $str_general_date 
        GROUP BY tb_journal_general.journal_general_id 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------



        //------------------------- Purchase Journal -------------------------------------------------------------
        $str_purchase_date = "";

        if($date_start != "" && $date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_purchase = " SELECT
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE ( 
                journal_purchase_code LIKE ('%$keyword%') 
            OR  journal_purchase_name LIKE ('%$keyword%') 
        ) 
        $str_purchase_date 
        GROUP BY tb_journal_purchase.journal_purchase_id 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------
        $str_sale_date = "";

        if($date_start != "" && $date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale = " SELECT
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE ( 
                journal_sale_code LIKE ('%$keyword%') 
            OR  journal_sale_name LIKE ('%$keyword%') 
        ) 
        $str_sale_date 
        GROUP BY tb_journal_sale.journal_sale_id 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
        $str_cash_payment_date = "";

        if($date_start != "" && $date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_payment = " SELECT
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE ( 
                journal_cash_payment_code LIKE ('%$keyword%') 
            OR  journal_cash_payment_name LIKE ('%$keyword%') 
        ) 
        $str_cash_payment_date 
        GROUP BY tb_journal_cash_payment.journal_cash_payment_id 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
        $str_cash_receipt_date = "";

        if($date_start != "" && $date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_receipt = " SELECT
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
        WHERE ( 
                journal_cash_receipt_code LIKE ('%$keyword%') 
            OR  journal_cash_receipt_name LIKE ('%$keyword%') 
        ) 
        $str_cash_receipt_date 
        GROUP BY tb_journal_cash_receipt.journal_cash_receipt_id 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


        $sql =" SELECT * 
                FROM (($sql_general) 
                UNION  ($sql_purchase)
                UNION  ($sql_sale)
                UNION  ($sql_cash_payment)
                UNION  ($sql_cash_receipt)) as tb_journal
                ORDER BY journal_date, journal_code ASC
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


    //#####################################################################################################################
    //
    //
    //--------------------------------------------- ดึงรายสมุดรายวัน แบบเต็ม เรียงตามวัน -----------------------------------------
    //
    //
    //#####################################################################################################################
    function getJournalFullReportBy($date_start = "", $date_end = "",$keyword = ""){


        //------------------------- General Journal -------------------------------------------------------------
        $str_general_date = "";

        if($date_start != "" && $date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_general = " SELECT
        CONCAT('general',journal_general_list_id) as journal_id,
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        IFNULL(account_code,'N/A') as account_code ,
        account_name_th as account_name ,
        journal_general_list_name as journal_list_name,
        IFNULL(journal_general_list_debit,0) as journal_debit,
        IFNULL(journal_general_list_credit,0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general.journal_general_id = tb_journal_general_list.journal_general_id  
        LEFT JOIN tb_account ON tb_journal_general_list.account_id = tb_account.account_id 
        WHERE ( 
                journal_general_code LIKE ('%$keyword%') 
            OR  journal_general_name LIKE ('%$keyword%') 
            OR  account_code LIKE ('%$keyword%') 
        ) 
        $str_general_date 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code , IFNULL(account_code,'N/A') DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------



        //------------------------- Purchase Journal -------------------------------------------------------------
        $str_purchase_date = "";

        if($date_start != "" && $date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_purchase = " SELECT
        CONCAT('purchase',journal_purchase_list_id) as journal_id,
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        IFNULL(account_code,'N/A') as account_code ,
        account_name_th as account_name ,
        journal_purchase_list_name as journal_list_name,
        IFNULL(journal_purchase_list_debit,0) as journal_debit,
        IFNULL(journal_purchase_list_credit,0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase.journal_purchase_id = tb_journal_purchase_list.journal_purchase_id  
        LEFT JOIN tb_account ON tb_journal_purchase_list.account_id = tb_account.account_id 
        WHERE ( 
                journal_purchase_code LIKE ('%$keyword%') 
            OR  journal_purchase_name LIKE ('%$keyword%') 
            OR  account_code LIKE ('%$keyword%') 
        ) 
        $str_purchase_date 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code , IFNULL(account_code,'N/A') DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------
        $str_sale_date = "";

        if($date_start != "" && $date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale = " SELECT
        CONCAT('sale',journal_sale_list_id) as journal_id,
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        IFNULL(account_code,'N/A') as account_code ,
        account_name_th as account_name ,
        journal_sale_list_name as journal_list_name,
        IFNULL(journal_sale_list_debit,0) as journal_debit,
        IFNULL(journal_sale_list_credit,0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale.journal_sale_id = tb_journal_sale_list.journal_sale_id  
        LEFT JOIN tb_account ON tb_journal_sale_list.account_id = tb_account.account_id 
        WHERE ( 
                journal_sale_code LIKE ('%$keyword%') 
            OR  journal_sale_name LIKE ('%$keyword%') 
            OR  account_code LIKE ('%$keyword%') 
        ) 
        $str_sale_date 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code , IFNULL(account_code,'N/A') DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
        $str_cash_payment_date = "";

        if($date_start != "" && $date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_payment = " SELECT 
        CONCAT('payment',journal_cash_payment_list_id) as journal_id,
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        IFNULL(account_code,'N/A') as account_code ,
        account_name_th as account_name ,
        journal_cash_payment_list_name as journal_list_name,
        IFNULL(journal_cash_payment_list_debit,0) as journal_debit,
        IFNULL(journal_cash_payment_list_credit,0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment.journal_cash_payment_id = tb_journal_cash_payment_list.journal_cash_payment_id  
        LEFT JOIN tb_account ON tb_journal_cash_payment_list.account_id = tb_account.account_id 
        WHERE ( 
                journal_cash_payment_code LIKE ('%$keyword%') 
            OR  journal_cash_payment_name LIKE ('%$keyword%') 
            OR  account_code LIKE ('%$keyword%') 
        ) 
        $str_cash_payment_date 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code , IFNULL(account_code,'N/A') DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
        $str_cash_receipt_date = "";

        if($date_start != "" && $date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_receipt = " SELECT
        CONCAT('receipt',journal_cash_receipt_list_id) as journal_id,
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        IFNULL(account_code,'N/A') as account_code ,
        account_name_th as account_name ,
        journal_cash_receipt_list_name as journal_list_name,
        IFNULL(journal_cash_receipt_list_debit,0) as journal_debit,
        IFNULL(journal_cash_receipt_list_credit,0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt.journal_cash_receipt_id = tb_journal_cash_receipt_list.journal_cash_receipt_id  
        LEFT JOIN tb_account ON tb_journal_cash_receipt_list.account_id = tb_account.account_id 
        WHERE ( 
                journal_cash_receipt_code LIKE ('%$keyword%') 
            OR  journal_cash_receipt_name LIKE ('%$keyword%') 
            OR  account_code LIKE ('%$keyword%') 
        ) 
        $str_cash_receipt_date 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code , IFNULL(account_code,'N/A') DESC
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


        $sql =" SELECT * 
                FROM (($sql_general) 
                UNION  ($sql_purchase)
                UNION  ($sql_sale)
                UNION  ($sql_cash_payment)
                UNION  ($sql_cash_receipt)) as tb_journal 
                ORDER BY journal_date, journal_code ASC
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




    //#####################################################################################################################
    //
    //
    //-------------------------------- ดึงรายสมุดรายวัน แบบย่อ รวมตามบัญชี เรียงตามบัญชี ------------------------------------------
    //
    //
    //#####################################################################################################################
    function getJournalAcountReportBy($date_end = "", $code_start = "", $code_end = "" ,$keyword = ""){


        //------------------------- General Journal -------------------------------------------------------------
        $str_general_date = "";

        if ($date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_general = " SELECT
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE ( 
                journal_general_code LIKE ('%$keyword%') 
            OR  journal_general_name LIKE ('%$keyword%') 
        ) 
        $str_general_date 
        GROUP BY tb_journal_general_list.journal_general_list_id 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------



        //------------------------- Purchase Journal -------------------------------------------------------------
        $str_purchase_date = "";

        if ($date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_purchase = " SELECT
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE ( 
                journal_purchase_code LIKE ('%$keyword%') 
            OR  journal_purchase_name LIKE ('%$keyword%') 
        ) 
        $str_purchase_date 
        GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------
        $str_sale_date = "";

        if ($date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale = " SELECT
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE ( 
                journal_sale_code LIKE ('%$keyword%') 
            OR  journal_sale_name LIKE ('%$keyword%') 
        ) 
        $str_sale_date 
        GROUP BY tb_journal_sale_list.journal_sale_list_id 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
        $str_cash_payment_date = "";

        if ($date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_payment = " SELECT
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE ( 
                journal_cash_payment_code LIKE ('%$keyword%') 
            OR  journal_cash_payment_name LIKE ('%$keyword%') 
        ) 
        $str_cash_payment_date 
        GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
        $str_cash_receipt_date = "";

        if ($date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_receipt = " SELECT
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
        WHERE ( 
                journal_cash_receipt_code LIKE ('%$keyword%') 
            OR  journal_cash_receipt_name LIKE ('%$keyword%') 
        ) 
        $str_cash_receipt_date 
        GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


        $sql =" SELECT account_code , account_name_th ,  MAX(IFNULL(account_debit_begin,0)) ,SUM(IFNULL(tb_journal.journal_debit,0)), MAX(IFNULL(account_credit_begin,0)) ,SUM(IFNULL(tb_journal.journal_credit,0)) , ( MAX(IFNULL(account_debit_begin,0)) + SUM(IFNULL(tb_journal.journal_debit,0)) ) - ( MAX(IFNULL(account_credit_begin,0)) + SUM(IFNULL(tb_journal.journal_credit,0)) ) as account_value
                FROM tb_account 
                LEFT JOIN  (($sql_general)  
                UNION   ALL  ($sql_purchase) 
                UNION   ALL  ($sql_sale) 
                UNION   ALL  ($sql_cash_payment) 
                UNION   ALL  ($sql_cash_receipt)) as tb_journal  
                ON tb_account.account_id = tb_journal.account_id  
                GROUP BY journal_code 
                HAVING  round(account_value,2)  != 0
                ORDER BY account_code ASC
        ";  
        //echo '<pre>'.$sql.'</pre>';
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    } 

    //#####################################################################################################################
    //
    //
    //-------------------------------- ดึงรายสมุดรายวัน แบบย่อ รวมตามบัญชี เรียงตามบัญชี (เเสดงทั้งหมด)------------------------------
    //
    //
    //#####################################################################################################################

    function getJournalAcountReportShowAllBy($date_end = "", $code_start = "", $code_end = "" ,$keyword = ""){
  //------------------------- General Journal -------------------------------------------------------------
        $str_general_date = "";

        if ($date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_general = " SELECT
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE ( 
                journal_general_code LIKE ('%$keyword%') 
            OR  journal_general_name LIKE ('%$keyword%') 
        ) 
        $str_general_date 
        GROUP BY tb_journal_general_list.journal_general_list_id 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------



        //------------------------- Purchase Journal -------------------------------------------------------------
        $str_purchase_date = "";

        if ($date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_purchase = " SELECT
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE ( 
                journal_purchase_code LIKE ('%$keyword%') 
            OR  journal_purchase_name LIKE ('%$keyword%') 
        ) 
        $str_purchase_date 
        GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------
        $str_sale_date = "";

        if ($date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale = " SELECT
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE ( 
                journal_sale_code LIKE ('%$keyword%') 
            OR  journal_sale_name LIKE ('%$keyword%') 
        ) 
        $str_sale_date 
        GROUP BY tb_journal_sale_list.journal_sale_list_id 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
        $str_cash_payment_date = "";

        if ($date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_payment = " SELECT
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE ( 
                journal_cash_payment_code LIKE ('%$keyword%') 
            OR  journal_cash_payment_name LIKE ('%$keyword%') 
        ) 
        $str_cash_payment_date 
        GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
        $str_cash_receipt_date = "";

        if ($date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_receipt = " SELECT
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  

        WHERE ( 
                journal_cash_receipt_code LIKE ('%$keyword%') 
            OR  journal_cash_receipt_name LIKE ('%$keyword%') 
        ) 
        $str_cash_receipt_date 
        GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


        $sql =" SELECT account_code , account_name_th ,  MAX(IFNULL(account_debit_begin,0)) ,SUM(IFNULL(tb_journal.journal_debit,0)), MAX(IFNULL(account_credit_begin,0)) ,SUM(IFNULL(tb_journal.journal_credit,0)) , ( MAX(IFNULL(account_debit_begin,0)) + SUM(IFNULL(tb_journal.journal_debit,0)) ) - ( MAX(IFNULL(account_credit_begin,0)) + SUM(IFNULL(tb_journal.journal_credit,0)) ) as account_value
                FROM tb_account 
                LEFT JOIN  (($sql_general)  
                UNION   ALL  ($sql_purchase) 
                UNION   ALL  ($sql_sale) 
                UNION   ALL  ($sql_cash_payment) 
                UNION   ALL  ($sql_cash_receipt)) as tb_journal  
                ON tb_account.account_id = tb_journal.account_id  
               
                ORDER BY account_code ASC
        ";  
        //echo '<pre>'.$sql.'</pre>';
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    } 





    //#####################################################################################################################
    //
    //
    //-------------------------------- ดึงรายสมุดรายวัน แบบเต็ม รวมตามบัญชี เรียงตามบัญชี ------------------------------------------
    //
    //
    //#####################################################################################################################
    function getJournalAcountFullReportBy($date_start="", $date_end = "", $code_start = "", $code_end = "" ,$keyword = ""){
        //------------------------- General Journal -------------------------------------------------------------
        $str_general_date = ""; 

        if($date_start != "" && $date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_general = " SELECT
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        account_id,
        journal_general_list_name as journal_list_name,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE ( 
                journal_general_code LIKE ('%$keyword%') 
            OR  journal_general_name LIKE ('%$keyword%') 
        )  
        $str_general_date 
        GROUP BY tb_journal_general_list.journal_general_list_id 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------



        //------------------------- Purchase Journal -------------------------------------------------------------
        $str_purchase_date = "";

        if($date_start != "" && $date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_purchase = " SELECT
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        account_id,
        journal_purchase_list_name as journal_list_name,
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE ( 
                journal_purchase_code LIKE ('%$keyword%') 
            OR  journal_purchase_name LIKE ('%$keyword%') 
        )  
        $str_purchase_date 
        GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------
        $str_sale_date = "";

        if($date_start != "" && $date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale = " SELECT
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        account_id,
        journal_sale_list_name as journal_list_name,
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE ( 
                journal_sale_code LIKE ('%$keyword%') 
            OR  journal_sale_name LIKE ('%$keyword%') 
        )  
        $str_sale_date 
        GROUP BY tb_journal_sale_list.journal_sale_list_id 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
        $str_cash_payment_date = "";

        if($date_start != "" && $date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_payment = " SELECT
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        account_id,
        journal_cash_payment_list_name as journal_list_name,
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE ( 
                journal_cash_payment_code LIKE ('%$keyword%') 
            OR  journal_cash_payment_name LIKE ('%$keyword%') 
        )  
        $str_cash_payment_date 
        GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
        $str_cash_receipt_date = "";

        if($date_start != "" && $date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_receipt = " SELECT
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        account_id,
        journal_cash_receipt_list_name as journal_list_name,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
        WHERE ( 
                journal_cash_receipt_code LIKE ('%$keyword%') 
            OR  journal_cash_receipt_name LIKE ('%$keyword%') 
        )  
        $str_cash_receipt_date 
        GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


            $sql =" SELECT *
                FROM tb_account 
                LEFT JOIN  (($sql_general)  
                UNION   ALL  ($sql_purchase) 
                UNION   ALL  ($sql_sale) 
                UNION   ALL  ($sql_cash_payment) 
                UNION   ALL  ($sql_cash_receipt)) as tb_journal   
                ON tb_account.account_id = tb_journal.account_id   
                ORDER BY account_code ASC , STR_TO_DATE(journal_date,'%d-%m-%Y %H:%i:%s') ASC ,journal_code ASC, journal_debit DESC 
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



    //#####################################################################################################################
    //
    //
    //-------------------------------- ดึงรายสมุดรายวัน แบบเต็ม รวมตามบัญชี เรียงตามบัญชี ------------------------------------------
    //
    //
    //#####################################################################################################################
    function getJournalAcountBalanceBy($date_start = "", $account_id = ""){

        if($account_id != ""){
            $account_str = " AND account_id = '$account_id' ";
        }

        //------------------------- General Journal -------------------------------------------------------------
        $sql_general = " SELECT
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        account_id,
        journal_general_list_name as journal_list_name,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE  1
        $account_str 
        AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s')
        GROUP BY tb_journal_general_list.journal_general_list_id 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------



        //------------------------- Purchase Journal -------------------------------------------------------------


        $sql_purchase = " SELECT
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        account_id,
        journal_purchase_list_name as journal_list_name,
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE  1
        $account_str 
        AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
        GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------

        $sql_sale = " SELECT
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        account_id,
        journal_sale_list_name as journal_list_name,
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE  1
        $account_str 
        AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s')
        GROUP BY tb_journal_sale_list.journal_sale_list_id 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
 
        $sql_cash_payment = " SELECT
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        account_id,
        journal_cash_payment_list_name as journal_list_name,
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE  1
        $account_str 
        AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s')
        GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
  
        $sql_cash_receipt = " SELECT
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        account_id,
        journal_cash_receipt_list_name as journal_list_name,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
        WHERE  1
        $account_str 
        AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s')
        GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------

            $sql =" SELECT SUM(IFNULL(journal_debit,'0')) - SUM(IFNULL(journal_credit,'0')) as journal_begin
                FROM  (($sql_general)  
                UNION   ALL  ($sql_purchase) 
                UNION   ALL  ($sql_sale) 
                UNION   ALL  ($sql_cash_payment) 
                UNION   ALL  ($sql_cash_receipt)) as tb_journal    
                GROUP BY account_id
        ";   
  

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['journal_begin'];
        }
    }

     //#####################################################################################################################
    //
    //
    //-------------------------------- ดึงรายงานเซ็ค ตามวันที่ เเละ บัญชี ------------------------------
    //
    //
    //#####################################################################################################################

    function getJournalAcountReportShowpayAllBy($date_end = "", $account_id ){
        //------------------------- General Journal -------------------------------------------------------------
              $str_general_date = "";
              
              if ($date_end != ""){
                  $str_general_date = " WHERE STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') = STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              }else{
                  $date_  = date('t-m-Y');
                  $str_general_date = " WHERE STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
              } 
              
              $sql_general = " SELECT
              journal_general_code as journal_code, 
              journal_general_date as journal_date,
              journal_general_name  as journal_name,
            
              check_pay_code  as cheque_code,
              check_pay_total  as cheque_total,
              check_pay_date_write,check_pay_date,
              account_id,
              IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_general_list_credit),0) as journal_credit
              FROM tb_journal_general 
              LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
              LEFT JOIN tb_check_pay ON tb_journal_general_list.journal_cheque_pay_id = tb_check_pay.check_pay_id  
              $str_general_date 
              GROUP BY tb_journal_general_list.journal_general_list_id 
              ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
              "; 
              //------------------------- End General Journal -------------------------------------------------------------
      
      
      
              //------------------------- Purchase Journal -------------------------------------------------------------
              $str_purchase_date = "";
      
              if ($date_end != ""){
                  $str_purchase_date = "WHERE STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') = STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              }else{
                $date_  = date('t-m-Y');
                $str_general_date = " WHERE STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
            }  
      
              $sql_purchase = " SELECT
              journal_purchase_code as journal_code, 
              journal_purchase_date as journal_date,
              journal_purchase_name  as journal_name,
             
              check_pay_code  as cheque_code,
              check_pay_total  as cheque_total,
              check_pay_date_write,check_pay_date,
              account_id,
              IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
              FROM tb_journal_purchase 
              LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id 
              LEFT JOIN tb_check_pay ON tb_journal_purchase_list.journal_cheque_pay_id = tb_check_pay.check_pay_id 
             
              $str_purchase_date 
              GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
              ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
              "; 
              //------------------------- End Purchase Journal -------------------------------------------------------------
      
      
      
              //------------------------- Sale Journal -------------------------------------------------------------
              $str_sale_date = "";
            
              if ($date_end != ""){
                  $str_sale_date = "WHERE STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') = STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              } else{
                $date_  = date('t-m-Y');
                $str_general_date = " WHERE STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
            } 
            
              $sql_sale = " SELECT
              journal_sale_code as journal_code, 
              journal_sale_date as journal_date,
              journal_sale_name  as journal_name,
             
              check_pay_code  as cheque_code,
              check_pay_total  as cheque_total,
              check_pay_date_write,check_pay_date,
              account_id,
              IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
              FROM tb_journal_sale 
              LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
              LEFT JOIN tb_check_pay ON tb_journal_sale_list.journal_cheque_pay_id = tb_check_pay.check_pay_id 
              
              $str_sale_date 
              GROUP BY tb_journal_sale_list.journal_sale_list_id 
              ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
              "; 
              //------------------------- End Sale Journal -------------------------------------------------------------
      
      
      
              //------------------------- Cash Payment Journal -------------------------------------------------------------
              $str_cash_payment_date = "";
      
              if ($date_end != ""){
                  $str_cash_payment_date = "WHERE STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') = STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              }else{
                $date_  = date('t-m-Y');
                $str_general_date = " WHERE STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
            }  
      
              $sql_cash_payment = " SELECT
              journal_cash_payment_code as journal_code, 
              journal_cash_payment_date as journal_date,
              journal_cash_payment_name  as journal_name,
              check_pay_code  as cheque_code,
              check_pay_total  as cheque_total,
              check_pay_date_write,check_pay_date,
              account_id,
              IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
              FROM tb_journal_cash_payment 
              LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
              LEFT JOIN tb_check_pay ON tb_journal_cash_payment_list.journal_cheque_pay_id = tb_check_pay.check_pay_id 
              
              $str_cash_payment_date 
              GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
              ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
              "; 
              //------------------------- End Cash Payment Journal -------------------------------------------------------------
      
      
      
              //------------------------- Cash Receipt Journal -------------------------------------------------------------
              $str_cash_receipt_date = "";
      
              if ($date_end != ""){
                  $str_cash_receipt_date = "WHERE STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') = STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              }else{
                $date_  = date('t-m-Y');
                $str_general_date = " WHERE STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
            }  
      
              $sql_cash_receipt = " SELECT
              journal_cash_receipt_code as journal_code, 
              journal_cash_receipt_date as journal_date,
              journal_cash_receipt_name  as journal_name,
              check_pay_code  as cheque_code,
              check_pay_total  as cheque_total,
              check_pay_date_write,check_pay_date,
              account_id,
              IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
              FROM tb_journal_cash_receipt 
              LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
              LEFT JOIN tb_check_pay ON tb_journal_cash_receipt_list.journal_cheque_pay_id = tb_check_pay.check_pay_id 
              
             
              $str_cash_receipt_date 
              GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
              ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
              "; 
              //------------------------- End Cash Receipt Journal -------------------------------------------------------------
      
      
              $sql =" SELECT account_code , account_name_th ,  MAX(IFNULL(account_debit_begin,0)) ,SUM(IFNULL(tb_journal.journal_debit,0)), MAX(IFNULL(account_credit_begin,0)) ,SUM(IFNULL(tb_journal.journal_credit,0)) , ( MAX(IFNULL(account_debit_begin,0)) + SUM(IFNULL(tb_journal.journal_debit,0)) ) - ( MAX(IFNULL(account_credit_begin,0)) + SUM(IFNULL(tb_journal.journal_credit,0)) ) as account_value,
                      cheque_code , cheque_total,journal_name, check_pay_date_write,journal_code,check_pay_date
                      FROM tb_account 
                      LEFT JOIN  (($sql_general)  
                      UNION   ALL  ($sql_purchase) 
                      UNION   ALL  ($sql_sale) 
                      UNION   ALL  ($sql_cash_payment) 
                      UNION   ALL  ($sql_cash_receipt)) as tb_journal  
                      ON tb_account.account_id = tb_journal.account_id  
                      where tb_account.account_id = '$account_id'  AND cheque_code IS NOT NULL
                      GROUP BY cheque_code     
                      ORDER BY cheque_code ASC
              ";  
            //echo '<pre>'.$sql.'</pre>';
              if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                  $data = [];
                  while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                      $data[] = $row;
                  }
                  $result->close();
                  return $data;
              }
      
          } 
    
    
}
?>