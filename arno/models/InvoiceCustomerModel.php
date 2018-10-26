<?php

require_once("BaseModel.php");
class InvoiceCustomerModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getInvoiceCustomerBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = "",$begin = "0"){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        $sql = " SELECT invoice_customer_id, 
        invoice_customer_code, 
        invoice_customer_date, 
        invoice_customer_total_price,
        invoice_customer_vat,
        invoice_customer_vat_price,
        invoice_customer_net_price,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        invoice_customer_term, 
        invoice_customer_due, 
        invoice_customer_name,
        IFNULL(tb2.customer_name_en,'-') as customer_name  
        FROM tb_invoice_customer 
        LEFT JOIN tb_user as tb1 ON tb_invoice_customer.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_invoice_customer.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  invoice_customer_code LIKE ('%$keyword%') 
        ) 
        AND invoice_customer_begin = '$begin' 
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'),invoice_customer_code DESC 
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




    function getInvoiceCustomerByID($id){
        $sql = " SELECT * 
        FROM tb_invoice_customer 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE invoice_customer_id = '$id' 
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


    function getInvoiceCustomerByCode($invoice_customer_code){
        $sql = " SELECT * 
        FROM tb_invoice_customer 
        WHERE invoice_customer_code = '$invoice_customer_code' 
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

    function getInvoiceCustomerByCustomerID($id){
        $sql = " SELECT * 
        FROM tb_invoice_customer 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE tb_invoice_customer.customer_id = '$id' 
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

    function getInvoiceCustomerViewByID($id){
        $sql = " SELECT *   
        FROM tb_invoice_customer 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE invoice_customer_id = '$id' 
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


    function getInvoiceCustomerViewListByjournalPaymentID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_payment_list 
        LEFT JOIN tb_invoice_customer ON tb_journal_cash_payment_list.journal_invoice_customer_id = tb_invoice_customer.invoice_customer_id
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE journal_cash_payment_id = '$id' AND tb_journal_cash_payment_list.journal_invoice_customer_id > 0
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

    function getInvoiceCustomerViewListByjournalReceiptID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_receipt_list 
        LEFT JOIN tb_invoice_customer ON tb_journal_cash_receipt_list.journal_invoice_customer_id = tb_invoice_customer.invoice_customer_id
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE journal_cash_receipt_id = '$id' AND tb_journal_cash_receipt_list.journal_invoice_customer_id > 0
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

    function getInvoiceCustomerLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(invoice_customer_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  invoice_customer_lastcode 
        FROM tb_invoice_customer
        WHERE invoice_customer_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['invoice_customer_lastcode'];
        }

    }


   
    function updateInvoiceCustomerByID($id,$data = []){
        $sql = " UPDATE tb_invoice_customer SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        invoice_customer_code = '".static::$db->real_escape_string($data['invoice_customer_code'])."', 
        invoice_customer_total_price = '".$data['invoice_customer_total_price']."', 
        invoice_customer_vat = '".$data['invoice_customer_vat']."', 
        invoice_customer_vat_price = '".$data['invoice_customer_vat_price']."', 
        invoice_customer_net_price = '".$data['invoice_customer_net_price']."', 
        invoice_customer_date = '".static::$db->real_escape_string($data['invoice_customer_date'])."', 
        invoice_customer_name = '".static::$db->real_escape_string($data['invoice_customer_name'])."', 
        invoice_customer_address = '".static::$db->real_escape_string($data['invoice_customer_address'])."', 
        invoice_customer_term = '".static::$db->real_escape_string($data['invoice_customer_term'])."', 
        invoice_customer_tax = '".static::$db->real_escape_string($data['invoice_customer_tax'])."', 
        invoice_customer_branch = '".static::$db->real_escape_string($data['invoice_customer_branch'])."', 
        invoice_customer_due = '".static::$db->real_escape_string($data['invoice_customer_due'])."', 
        invoice_customer_close = '".$data['invoice_customer_close']."', 
        invoice_customer_begin = '".$data['invoice_customer_begin']."', 
        vat_section = '".static::$db->real_escape_string($data['vat_section'])."', 
        vat_section_add = '".static::$db->real_escape_string($data['vat_section_add'])."', 
        invoice_customer_total_price_non = '".$data['invoice_customer_total_price_non']."', 
        invoice_customer_vat_price_non = '".$data['invoice_customer_vat_price_non']."', 
        invoice_customer_total_non = '".$data['invoice_customer_total_non']."', 
        invoice_customer_description = '".static::$db->real_escape_string($data['invoice_customer_description'])."', 
        invoice_customer_remark = '".static::$db->real_escape_string($data['invoice_customer_remark'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE invoice_customer_id = $id 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function getCustomerPurchaseOrder($keyword = ""){

        $sql = "SELECT tb_customer_purchase_order.customer_purchase_order_id, customer_purchase_order_code, tb_customer.customer_id, customer_name_en , customer_name_th 
                FROM tb_customer_purchase_order 
                LEFT JOIN tb_customer 
                ON tb_customer_purchase_order.customer_id = tb_customer.customer_id 
                LEFT JOIN tb_customer_purchase_order_list 
                ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id 
                WHERE customer_purchase_order_list_id IN ( 
                    SELECT tb_customer_purchase_order_list.customer_purchase_order_list_id 
                    FROM tb_customer_purchase_order_list  
                    LEFT JOIN tb_invoice_customer_list ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id  
                    GROUP BY tb_customer_purchase_order_list.customer_purchase_order_list_id 
                    HAVING IFNULL(SUM(invoice_customer_list_qty),0) < AVG(customer_purchase_order_list_qty)  
                ) 
                AND customer_purchase_order_code LIKE ('%$keyword%')
                GROUP BY tb_customer_purchase_order.customer_purchase_order_id
        ";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }
        return $data;
    }


    function getCustomerPurchaseOrderByCode($customer_purchase_order_code = ""){

        $sql = "SELECT tb_customer_purchase_order.customer_purchase_order_id, customer_purchase_order_code, tb_customer.customer_id, customer_name_en , customer_name_th 
                FROM tb_customer_purchase_order 
                LEFT JOIN tb_customer 
                ON tb_customer_purchase_order.customer_id = tb_customer.customer_id 
                LEFT JOIN tb_customer_purchase_order_list 
                ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id 
                WHERE customer_purchase_order_list_id IN ( 
                    SELECT tb_customer_purchase_order_list.customer_purchase_order_list_id 
                    FROM tb_customer_purchase_order_list  
                    LEFT JOIN tb_invoice_customer_list ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id  
                    GROUP BY tb_customer_purchase_order_list.customer_purchase_order_list_id 
                    HAVING IFNULL(SUM(invoice_customer_list_qty),0) < AVG(customer_purchase_order_list_qty)  
                ) 
                AND customer_purchase_order_code = '$customer_purchase_order_code' 
                GROUP BY tb_customer_purchase_order.customer_purchase_order_id
        ";
        $data;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
        }
        return $data;
    }


    function getCustomerOrder(){

        $sql = "SELECT tb_customer.customer_id, customer_name_en , customer_name_th 
                FROM tb_customer 
                WHERE customer_id IN ( 
                    SELECT DISTINCT customer_id 
                    FROM tb_customer_purchase_order 
                    LEFT JOIN tb_customer_purchase_order_list 
                    ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id 
                    WHERE customer_purchase_order_list_id IN ( 
                        SELECT tb_customer_purchase_order_list.customer_purchase_order_list_id 
                        FROM tb_customer_purchase_order_list  
                        LEFT JOIN tb_invoice_customer_list ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id  
                        GROUP BY tb_customer_purchase_order_list.customer_purchase_order_list_id 
                        HAVING IFNULL(SUM(invoice_customer_list_qty),0) < AVG(customer_purchase_order_list_qty)  
                    ) 
                ) 
        ";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }
        return $data;
    }

    function generateInvoiceCustomerListByCustomerId($customer_id, $data = [],$search="",$customer_purchase_order_id=""){

        $str ='0';

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

        if($customer_purchase_order_id != ""){
            $str_po = "AND tb_customer_purchase_order.customer_purchase_order_id = '$customer_purchase_order_id' ";
        }

        $sql_customer = "SELECT tb2.product_id, 
        tb2.customer_purchase_order_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,  
        IFNULL(customer_purchase_order_list_qty 
        - IFNULL((
            SELECT SUM(invoice_customer_list_qty) 
            FROM tb_invoice_customer_list 
            WHERE customer_purchase_order_list_id = tb2.customer_purchase_order_list_id 
        ),0) ,0) as invoice_customer_list_qty,  
        customer_purchase_order_product_name as invoice_customer_list_product_name,
        customer_purchase_order_product_detail as invoice_customer_list_product_detail,
        customer_purchase_order_list_price as invoice_customer_list_price, 
        customer_purchase_order_list_price_sum as invoice_customer_list_total, 
        customer_purchase_order_list_price_sum as invoice_customer_list_cost,
        CONCAT('Order for customer PO : ',customer_purchase_order_code) as invoice_customer_list_remark 
        FROM tb_customer_purchase_order 
        LEFT JOIN tb_customer_purchase_order_list as tb2 ON tb_customer_purchase_order.customer_purchase_order_id = tb2.customer_purchase_order_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        WHERE tb_customer_purchase_order.customer_id = '$customer_id' 
        $str_po 
        AND tb2.customer_purchase_order_list_id NOT IN ($str) 
        AND tb2.customer_purchase_order_list_id IN (
            SELECT tb_customer_purchase_order_list.customer_purchase_order_list_id 
            FROM tb_customer_purchase_order_list  
            LEFT JOIN tb_invoice_customer_list ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id 
            GROUP BY tb_customer_purchase_order_list.customer_purchase_order_list_id 
            HAVING IFNULL(SUM(invoice_customer_list_qty),0) < AVG(customer_purchase_order_list_qty)  
        ) 
        AND (product_name LIKE ('%$search%') OR customer_purchase_order_code LIKE ('%$search%')) ";

        //echo $sql_customer;
        $data = [];
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }

    function insertInvoiceCustomer($data = []){
        $sql = " INSERT INTO tb_invoice_customer (
            customer_id,
            employee_id,
            invoice_customer_code,
            invoice_customer_total_price,
            invoice_customer_vat,
            invoice_customer_vat_price,
            invoice_customer_net_price,
            invoice_customer_date,
            invoice_customer_name,
            invoice_customer_address,
            invoice_customer_tax,
            invoice_customer_branch,
            invoice_customer_term,
            invoice_customer_due,
            invoice_customer_begin,  
            vat_section,
            vat_section_add,
            invoice_customer_total_price_non,
            invoice_customer_vat_price_non,
            invoice_customer_total_non,
            invoice_customer_description,
            invoice_customer_remark,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        static::$db->real_escape_string($data['invoice_customer_code'])."','".
        $data['invoice_customer_total_price']."','".
        $data['invoice_customer_vat']."','".
        $data['invoice_customer_vat_price']."','".
        $data['invoice_customer_net_price']."','".
        static::$db->real_escape_string($data['invoice_customer_date'])."','".
        static::$db->real_escape_string($data['invoice_customer_name'])."','".
        static::$db->real_escape_string($data['invoice_customer_address'])."','".
        static::$db->real_escape_string($data['invoice_customer_tax'])."','".
        static::$db->real_escape_string($data['invoice_customer_branch'])."','".
        static::$db->real_escape_string($data['invoice_customer_term'])."','".
        static::$db->real_escape_string($data['invoice_customer_due'])."','".
        $data['invoice_customer_begin']."','".
        static::$db->real_escape_string($data['vat_section'])."','".
        static::$db->real_escape_string($data['vat_section_add'])."','".
        static::$db->real_escape_string($data['invoice_customer_total_price_non'])."','".
        static::$db->real_escape_string($data['invoice_customer_vat_price_non'])."','".
        static::$db->real_escape_string($data['invoice_customer_total_non'])."','".
        static::$db->real_escape_string($data['invoice_customer_description'])."','".
        static::$db->real_escape_string($data['invoice_customer_remark'])."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";


        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteInvoiceCustomerByID($id){


        $sql = "    SELECT invoice_customer_list_id, stock_group_id, product_id, invoice_customer_list_qty, invoice_customer_list_cost,stock_event
        FROM  tb_invoice_customer_list 
        LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        WHERE invoice_customer_id = '$id' ";   
                
        $sql_delete=[];

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            if($row['stock_event'] != "None"){
                $sql_delete [] = "
                    CALL delete_stock_customer('".
                    $row['stock_group_id']."','".
                    $row['invoice_customer_list_id']."','".
                    $row['product_id']."','".
                    $row['invoice_customer_list_qty']."','".
                    $row['invoice_customer_list_cost']."'".
                    ");
                ";
            }
            
        }
        $result->close();
        }
 
         for($i = 0 ; $i < count($sql_delete); $i++){
             mysqli_query(static::$db,$sql_delete[$i], MYSQLI_USE_RESULT);
         }

        $sql = " DELETE FROM tb_invoice_customer_list WHERE invoice_customer_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_invoice_customer WHERE invoice_customer_id = '$id' ";
        
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }

        

    }


}
?>