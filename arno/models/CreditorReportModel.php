<?php

require_once("BaseModel.php");
class CreditorReportModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }




    //#####################################################################################################################
    //
    //
    //-------------------------------------------- ดึงรายงานใบรับสินค้า (ซื้อเชื่อ) -----------------------------------------------
    //
    //
    //#####################################################################################################################
    function getInvoiceSupplierReportBy($date_start = "",$date_end = "",$code_start = "",$code_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_code = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($code_start != "" && $code_end != ""){
            $str_code = "AND invoice_supplier_code_gen >= '$code_start'  AND invoice_supplier_code_gen <=  '$code_end' ";
        }else if ($date_start != ""){
            $str_code = "AND invoice_supplier_code_gen >= '$code_start' ";    
        }else if ($date_end != ""){
            $str_code = "AND invoice_supplier_code_gen <= '$code_end'  ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }

        $sql = " SELECT invoice_supplier_id, 
        supplier_code,
        invoice_supplier_code, 
        invoice_supplier_code_gen, 
        invoice_supplier_date_recieve, 
        invoice_supplier_total_price,
        invoice_supplier_vat_price,
        invoice_supplier_net_price,  
        invoice_supplier_name,
        invoice_supplier_tax,
        invoice_supplier_due,
        IFNULL((
            SELECT GROUP_CONCAT(DISTINCT purchase_order_code )
            FROM tb_invoice_supplier_list 
            LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id 
            LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_id = tb_purchase_order.purchase_order_id 
            WHERE invoice_supplier_id = tb1.invoice_supplier_id 
            AND purchase_order_code != ''  
        ),'-') as purchase_order_code  ,  
        IFNULL((
            SELECT SUM(finance_credit_list_amount) FROM tb_finance_credit_list WHERE invoice_supplier_id = tb1.invoice_supplier_id 
        ),'0') as payment 
        FROM tb_invoice_supplier as tb1 
        LEFT JOIN tb_supplier as tb2 ON tb1.supplier_id = tb2.supplier_id 
        WHERE  invoice_supplier_code_gen LIKE ('%$keyword%')  
        AND tb1.invoice_supplier_begin = 0 
        $str_supplier 
        $str_date 
        $str_code 
        $str_user  
        ORDER BY invoice_supplier_code_gen ASC 
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




    //#####################################################################################################################
    //
    //
    //------------------------------------------------ ดึงรายงานใบสั่งซื้อ -----------------------------------------------------
    //
    //
    //#####################################################################################################################
    function getPurchaseOrderReportBy($date_start = "",$date_end = "",$code_start = "",$code_end = "",$supplier_id = "",$keyword = "",$user_id = ""){
        $str_supplier = "";
        $str_date = "";
        $str_code = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($code_start != "" && $code_end != ""){
            $str_code = "AND purchase_order_code >= '$code_start'  AND purchase_order_code <=  '$code_end' ";
        }else if ($date_start != ""){
            $str_code = "AND purchase_order_code >= '$code_start' ";    
        }else if ($date_end != ""){
            $str_code = "AND purchase_order_code <= '$code_end'  ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        $sql = " SELECT purchase_order_id, 
        purchase_order_type, 
        purchase_order_code, 
        purchase_order_date, 
        purchase_order_rewrite_id,
        IFNULL((
            SELECT COUNT(*) FROM tb_purchase_order WHERE purchase_order_rewrite_id = tb.purchase_order_id 
        ),0) as count_rewrite,
        purchase_order_rewrite_no,
        purchase_order_status,
        purchase_order_accept_status,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        purchase_order_credit_term, 
        purchase_order_delivery_term, 
        purchase_order_cancelled,
        tb2.supplier_code, 
        tb2.supplier_name_en, 
        purchase_order_delivery_by, 
        IFNULL((
            SELECT GROUP_CONCAT(DISTINCT invoice_supplier_code_gen )
            FROM tb_invoice_supplier_list 
            LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id 
            LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
            WHERE purchase_order_id = tb.purchase_order_id 
            AND invoice_supplier_code_gen != '' 
            GROUP BY tb_invoice_supplier.invoice_supplier_id 
        ),'-') as invoice_supplier_code_gen   
        FROM tb_purchase_order as tb
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id  
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  purchase_order_code LIKE ('%$keyword%') 
        ) 
        $str_supplier 
        $str_date 
        $str_code 
        $str_user 
        ORDER BY purchase_order_code DESC 
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
    //----------------------------------------------- ดึงรายงานใบจ่ายชำระเงิน --------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getFinanceCreditReportBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(finance_credit_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(finance_credit_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(finance_credit_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(finance_credit_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }

        $sql = " SELECT tb.finance_credit_id, 
        IFNULL (journal_cash_payment_code, '-') as journal_cash_payment_code,
        IFNULL (journal_cash_payment_id, '0') as journal_cash_payment_id,
        supplier_code,
        finance_credit_code, 
        finance_credit_date, 
        finance_credit_name,   
        finance_credit_tax,   
        finance_credit_branch, 
        finance_credit_date_pay, 
        finance_credit_total,
        finance_credit_pay, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        IFNULL( tb2.supplier_name_en ,'-') as supplier_name  
        FROM tb_finance_credit as tb
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id 
        LEFT JOIN tb_journal_cash_payment ON tb_journal_cash_payment.finance_credit_id = tb.finance_credit_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  finance_credit_code LIKE ('%$keyword%') 
        )  
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY finance_credit_code  
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
    //----------------------------------------------- ดึงรายงานสถานะเจ้าหนี้ --------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getCreditorListReportBy($supplier_id="",$code_start="",$code_end=""){

        $str_supplier ='';
        if($supplier_id != ""){
            $str_supplier =" AND supplier_id = '$supplier_id' ";
        }
        
        $str_code="";
        if($code_start != "" && $code_end != ""){
            $str_code = "AND supplier_code >=  '$code_start' AND supplier_code <=  '$code_end' ";
        }else if ($code_start != ""){
            $str_code = "AND supplier_code >=  '$code_start' ";    
        }else if ($code_end != ""){
            $str_code = "AND supplier_code <= '$code_end' ";  
        }

        $sql_supplier = "SELECT 
        tb_supplier.supplier_id,
        supplier_code,
        supplier_name_en  
        FROM tb_supplier  
        WHERE 1  
        $str_supplier
        $str_supplier
        ORDER BY supplier_code
        "; 


        $data = [];
        if ($result = mysqli_query(static::$db,$sql_supplier, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }

    function getBeforeCreditReportBy($supplier_id,$date_start){
        $sql ="SELECT 
            IFNULL( 
                ( 
                    SELECT SUM(invoice_supplier_net_price) 
                    FROM tb_invoice_supplier 
                    WHERE supplier_id = '$supplier_id' 
                    AND invoice_supplier_begin != '2' 
                    AND STR_TO_DATE(invoice_supplier_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
                ) 
            , 0) 
        -   IFNULL( 
                ( 
                    SELECT SUM(finance_credit_pay) 
                    FROM tb_finance_credit 
                    WHERE supplier_id = '$supplier_id' 
                    AND STR_TO_DATE(finance_credit_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s')  
                ) 
            , 0) as credit_before "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['credit_before'];
        }else{
            return 0;
        }

    }
    function getInvoiceCreditReportBy($supplier_id,$date_start,$date_end){
        $sql =" SELECT SUM(invoice_supplier_net_price) as credit_invoice 
                FROM tb_invoice_supplier 
                WHERE supplier_id = '$supplier_id' 
                AND invoice_supplier_begin != '2' 
                AND STR_TO_DATE(invoice_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
                AND STR_TO_DATE(invoice_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
            "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['credit_invoice'];
        }else{
            return 0;
        }

    }
    function getCreditDebitReportBy($supplier_id,$date_start,$date_end){
        $sql =" SELECT SUM(debit_note_net_price) as credit_debit 
                FROM tb_debit_note 
                WHERE supplier_id = '$supplier_id' 
                AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
                AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
            "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['credit_debit'];
        }else{
            return 0;
        }

    }
    function getCreditCreditReportBy($supplier_id,$date_start,$date_end){
        $sql =" SELECT SUM(credit_note_net_price) as credit_credit 
                FROM tb_credit_note 
                WHERE supplier_id = '$supplier_id' 
                AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
                AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
            "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['credit_credit'];
        }else{
            return 0;
        }

    }
    function getPaymentCreditReportBy($supplier_id,$date_start,$date_end){
        $sql =" SELECT SUM(finance_credit_pay) as credit_payment 
                FROM tb_finance_credit 
                WHERE supplier_id = '$supplier_id' 
                AND STR_TO_DATE(finance_credit_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
                AND STR_TO_DATE(finance_credit_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
            "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['credit_payment'];
        }else{
            return 0;
        }

    }




    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานเจ้าหนี้คงค้าง --------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getCreditorListDetailReportBy($date_end,$supplier_id,$keyword=""){

        $str_supplier ='';
        if($supplier_id != ""){
            $str_supplier =" AND tb1.supplier_id = '$supplier_id' ";
        }
        
        $str_date ='';
        if($date_end != ""){
            $str_date =" AND STR_TO_DATE(invoice_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s')  ";
        }

        $sql_supplier = "SELECT tb1.invoice_supplier_id, 
        supplier_code,
        IFNULL(currency_code,'-') as currency_code,
        IFNULL(
            (SELECT exchange_rate_baht_value 
            FROM tb_exchange_rate_baht   
            WHERE  currency_id = tb3.currency_id 
            AND  exchange_rate_baht_date = tb1.invoice_supplier_date LIMIT 0,1
            )
        , 1 ) as exchange_rate_baht_value,
        invoice_supplier_code, 
        invoice_supplier_code_gen, 
        invoice_supplier_name,   
        invoice_supplier_tax,   
        invoice_supplier_branch, 
        invoice_supplier_date, 
        invoice_supplier_due, 
        MAX(IFNULL(tb1.invoice_supplier_net_price,0)) as invoice_supplier_net_price,
        SUM(IFNULL(finance_credit_list_balance,0)) as finance_credit_list_paid,  
        MAX(IFNULL(tb1.invoice_supplier_net_price,0)) - SUM(IFNULL(finance_credit_list_balance,0)) as invoice_supplier_balance 
        FROM tb_invoice_supplier as tb1
        LEFT JOIN tb_finance_credit_list as tb2 ON tb1.invoice_supplier_id = tb2.invoice_supplier_id 
        LEFT JOIN tb_supplier as tb3 ON tb1.supplier_id = tb3.supplier_id        
        LEFT JOIN tb_currency as tb4 ON tb3.currency_id = tb4.currency_id 
        WHERE invoice_supplier_begin != '2'    
        $str_supplier 
        $str_date
        AND (
            invoice_supplier_code LIKE ('%$search%') OR 
            invoice_supplier_code_gen LIKE ('%$search%') 
        ) 
        GROUP BY tb1.invoice_supplier_id 
        HAVING MAX(IFNULL(tb1.invoice_supplier_net_price,0)) - SUM(IFNULL(finance_credit_list_balance,0)) != 0 
        ORDER BY  supplier_code , invoice_supplier_code "; 
       //  echo '<pre>'.$sql_supplier."</pre>";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql_supplier, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }






    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานวิเคราะห์อายุเจ้าหนี้ --------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getAccountsPayableListReportBy($date_end,$supplier_id,$keyword=""){

        $str_supplier ='';
        if($supplier_id != ""){
            $str_supplier =" AND tb_invoice_supplier.supplier_id = '$supplier_id' ";
        }
        
        $str_date ='';
        if($date_end != ""){
            $str_date =" AND STR_TO_DATE(invoice_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s')  ";
        }

        $sql_supplier = "SELECT 
        tb1.supplier_id, 
        supplier_code, 
        supplier_name_en, 
        supplier_tax,
        supplier_branch,
        '0' as paper_number, 
        '0' as balance, 
        '0' as due_comming_more_than_60, 
        '0' as due_comming_in_60, 
        '0' as due_comming_in_30, 
        '0' as over_due_1_to_30, 
        '0' as over_due_31_to_60, 
        '0' as over_due_61_to_90, 
        '0' as over_due_more_than_90 
        FROM tb_supplier as tb1
        WHERE supplier_id IN ( 
            SELECT DISTINCT supplier_id 
            FROM tb_invoice_supplier 
            LEFT JOIN tb_finance_credit_list ON tb_invoice_supplier.invoice_supplier_id = tb_finance_credit_list.invoice_supplier_id 
            WHERE invoice_supplier_begin != '2' 
            GROUP BY tb_invoice_supplier.invoice_supplier_id 
            HAVING MAX(IFNULL(tb_invoice_supplier.invoice_supplier_net_price,0)) > SUM(IFNULL(finance_credit_list_balance,0))
        ) 
        ORDER BY supplier_code
        ";

        //echo $sql_supplier;

        $data = [];
        if ($result = mysqli_query(static::$db,$sql_supplier, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    } 




    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานผู้ขาย ---------------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getSupplierListReportBy($code_start = '',$code_end = ''){

        $str_code="";
        if($code_start != "" && $code_end != ""){
            $str_code = "AND supplier_code >=  '$code_start' AND supplier_code <=  '$code_end' ";
        }else if ($code_start != ""){
            $str_code = "AND supplier_code >=  '$code_start' ";    
        }else if ($code_end != ""){
            $str_code = "AND supplier_code <= '$code_end' ";  
        }

        $sql = " SELECT * 
        FROM tb_supplier as tb1   
        WHERE 1 
        $str_code
        ORDER BY tb1.supplier_code  
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