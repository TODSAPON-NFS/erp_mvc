<?php

require_once("BaseModel.php");
class JournalReportModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

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
}
?>