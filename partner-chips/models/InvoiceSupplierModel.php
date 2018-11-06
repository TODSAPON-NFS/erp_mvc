<?php

require_once("BaseModel.php");
class InvoiceSupplierModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getInvoiceSupplierBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = "",$begin = '0'){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }

        
        $sql = " SELECT invoice_supplier_id, 
        invoice_supplier_code, 
        invoice_supplier_code_gen,
        invoice_supplier_date, 
        invoice_supplier_date_recieve,  
        invoice_supplier_total_price, 
        invoice_supplier_vat_price, 
        invoice_supplier_net_price,  
        import_duty, 
        freight_in, 
        supplier_domestic,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        invoice_supplier_term, 
        invoice_supplier_due, 
        invoice_supplier_name,
        IFNULL(tb2.supplier_name_en,'-') as supplier_name  
        FROM tb_invoice_supplier 
        LEFT JOIN tb_user as tb1 ON tb_invoice_supplier.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_invoice_supplier.supplier_id = tb2.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  invoice_supplier_code LIKE ('%$keyword%') 
            OR  invoice_supplier_code_gen LIKE ('%$keyword%') 
        ) 
        AND invoice_supplier_begin = '$begin' 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY  invoice_supplier_code_gen DESC 
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

    function getInvoiceSupplierByID($id){
        $sql = " SELECT * 
        FROM tb_invoice_supplier 
        WHERE invoice_supplier_id = '$id' 
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

    function getInvoiceSupplierByCode($invoice_supplier_code){
        $sql = " SELECT * 
        FROM tb_invoice_supplier  
        WHERE invoice_supplier_code_gen = '$invoice_supplier_code' 
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

    function getInvoiceSupplierViewByID($id){
        $sql = " SELECT *   
        FROM tb_invoice_supplier 
        LEFT JOIN tb_user ON tb_invoice_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE invoice_supplier_id = '$id' 
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

    function getInvoiceSupplierViewListByjournalPaymentID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_payment_list 
        LEFT JOIN tb_invoice_supplier ON tb_journal_cash_payment_list.journal_invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
        LEFT JOIN tb_user ON tb_invoice_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE journal_cash_payment_id = '$id' AND tb_journal_cash_payment_list.journal_invoice_supplier_id > 0
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

    function getInvoiceSupplierViewListByjournalReceiptID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_receipt_list 
        LEFT JOIN tb_invoice_supplier ON tb_journal_cash_receipt_list.journal_invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
        LEFT JOIN tb_user ON tb_invoice_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE journal_cash_receipt_id = '$id' AND tb_journal_cash_receipt_list.journal_invoice_supplier_id > 0
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


   
    function updateInvoiceSupplierByID($id,$data = []){
        $sql = " UPDATE tb_invoice_supplier SET 
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        invoice_supplier_code = '".static::$db->real_escape_string($data['invoice_supplier_code'])."', 
        invoice_supplier_code_gen = '".static::$db->real_escape_string($data['invoice_supplier_code_gen'])."', 
        invoice_supplier_total_price = '".$data['invoice_supplier_total_price']."', 
        invoice_supplier_vat = '".$data['invoice_supplier_vat']."', 
        invoice_supplier_vat_price = '".$data['invoice_supplier_vat_price']."', 
        invoice_supplier_net_price = '".$data['invoice_supplier_net_price']."', 
        invoice_supplier_date = '".static::$db->real_escape_string($data['invoice_supplier_date'])."', 
        invoice_supplier_date_recieve = '".static::$db->real_escape_string($data['invoice_supplier_date_recieve'])."', 
        invoice_supplier_name = '".static::$db->real_escape_string($data['invoice_supplier_name'])."', 
        invoice_supplier_address = '".static::$db->real_escape_string($data['invoice_supplier_address'])."', 
        invoice_supplier_tax = '".static::$db->real_escape_string($data['invoice_supplier_tax'])."', 
        invoice_supplier_branch = '".static::$db->real_escape_string($data['invoice_supplier_branch'])."', 
        invoice_supplier_term = '".static::$db->real_escape_string($data['invoice_supplier_term'])."', 
        invoice_supplier_due = '".static::$db->real_escape_string($data['invoice_supplier_due'])."',  
        invoice_supplier_begin = '".$data['invoice_supplier_begin']."', 
        import_duty = '".$data['import_duty']."', 
        freight_in = '".$data['freight_in']."', 
        vat_section = '".static::$db->real_escape_string($data['vat_section'])."', 
        vat_section_add = '".static::$db->real_escape_string($data['vat_section_add'])."', 
        invoice_supplier_total_price_non = '".$data['invoice_supplier_total_price_non']."', 
        invoice_supplier_vat_price_non = '".$data['invoice_supplier_vat_price_non']."', 
        invoice_supplier_total_non = '".$data['invoice_supplier_total_non']."', 
        invoice_supplier_description = '".static::$db->real_escape_string($data['invoice_supplier_description'])."', 
        invoice_supplier_remark = '".static::$db->real_escape_string($data['invoice_supplier_remark'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE invoice_supplier_id = $id 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function getPurchaseOrder($type = "ภายในประเทศ",$keyword = ""){

        $sql = "    SELECT tb_purchase_order.purchase_order_id , purchase_order_code, tb_purchase_order.supplier_id, supplier_name_en, supplier_name_th 
                    FROM tb_purchase_order 
                    LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_id = tb_purchase_order_list.purchase_order_id
                    WHERE purchase_order_list_id IN ( 
                        SELECT tb_purchase_order_list.purchase_order_list_id 
                        FROM tb_purchase_order_list  
                        LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id 
                        GROUP BY tb_purchase_order_list.purchase_order_list_id 
                        HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
                    ) 
                    AND purchase_order_status = 'Confirm' 
                    AND supplier_domestic = '$type' 
                    AND purchase_order_code LIKE('%$keyword%') 
                    GROUP BY tb_purchase_order.purchase_order_id 
                
        ";

        //echo $sql;
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }
        return $data;
    }


    function getPurchaseOrderByCode($type = "ภายในประเทศ",$keyword = ""){

        $sql = "    SELECT tb_purchase_order.purchase_order_id , purchase_order_code, tb_purchase_order.supplier_id, supplier_name_en, supplier_name_th 
                    FROM tb_purchase_order 
                    LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_id = tb_purchase_order_list.purchase_order_id
                    WHERE purchase_order_list_id IN ( 
                        SELECT tb_purchase_order_list.purchase_order_list_id 
                        FROM tb_purchase_order_list  
                        LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id 
                        GROUP BY tb_purchase_order_list.purchase_order_list_id 
                        HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
                    ) 
                    AND purchase_order_status = 'Confirm' 
                    AND supplier_domestic = '$type' 
                    AND purchase_order_code = '$keyword' 
                    GROUP BY tb_purchase_order.purchase_order_id 
                
        ";

        //echo $sql;
        $data ;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
        }
        return $data;
    }

    function getSupplierOrder($type = "ภายในประเทศ"){

        $sql = "SELECT tb_supplier.supplier_id, supplier_name_en , supplier_name_th 
                FROM tb_supplier 
                WHERE supplier_id IN ( 
                    SELECT DISTINCT supplier_id 
                    FROM tb_purchase_order 
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_id = tb_purchase_order_list.purchase_order_id
                    WHERE purchase_order_list_id IN ( 
                        SELECT tb_purchase_order_list.purchase_order_list_id 
                        FROM tb_purchase_order_list  
                        LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id 
                        GROUP BY tb_purchase_order_list.purchase_order_list_id 
                        HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
                    ) 
                    AND purchase_order_status = 'Confirm'
                ) 
                AND supplier_domestic = '$type' 
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


    function getInvoiceSupplierLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(invoice_supplier_code_gen,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  invoice_supplier_lastcode 
        FROM tb_invoice_supplier
        WHERE invoice_supplier_code_gen LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['invoice_supplier_lastcode'];
        }

    }

    function generateInvoiceSupplierListBySupplierId($supplier_id, $data = [], $search = "", $purchase_order_id=""){

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
        $str_po = "";

        if($purchase_order_id != ""){
            $str_po = "AND tb_purchase_order.purchase_order_id = '$purchase_order_id' ";
        }

        $sql_customer = "SELECT tb2.product_id, 
        tb2.purchase_order_list_id, 
        '' as regrind_supplier_list_id,
        CONCAT(product_code_first,product_code) as product_code, 
        IFNULL(tb_customer_purchase_order_list_detail.stock_group_id,(SELECT IFNULL(MIN(stock_group_id),0) FROM tb_stock_group WHERE 1)) as stock_group_id,
        product_name,  
        IFNULL(purchase_order_list_qty 
        - IFNULL((
            SELECT SUM(invoice_supplier_list_qty) 
            FROM tb_invoice_supplier_list 
            WHERE purchase_order_list_id = tb2.purchase_order_list_id 
        ),0) ,0) as invoice_supplier_list_qty, 
        purchase_order_list_price,
        '0' as invoice_supplier_list_price, 
        purchase_order_list_price_sum as invoice_supplier_list_total,
        '0' as invoice_supplier_list_cost, 
        CONCAT('PO : ',purchase_order_code) as invoice_supplier_list_remark 
        FROM tb_purchase_order 
        LEFT JOIN tb_purchase_order_list as tb2 ON tb_purchase_order.purchase_order_id = tb2.purchase_order_id 
        LEFT JOIN tb_customer_purchase_order_list_detail ON tb2.purchase_order_list_id = tb_customer_purchase_order_list_detail.purchase_order_list_id
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        WHERE tb_purchase_order.supplier_id = '$supplier_id' 
        $str_po 
        AND tb2.purchase_order_list_id NOT IN ($str) 
        AND tb2.purchase_order_list_id IN ( 
            SELECT tb_purchase_order_list.purchase_order_list_id 
            FROM tb_purchase_order_list  
            LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id 
            GROUP BY tb_purchase_order_list.purchase_order_list_id 
            HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
        ) 
        AND (product_name LIKE ('%$search%') OR purchase_order_code LIKE ('%$search%')) 
        AND purchase_order_status = 'Confirm' ";

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

    function insertInvoiceSupplier($data = []){
        $sql = " INSERT INTO tb_invoice_supplier (
            supplier_id,
            employee_id,
            invoice_supplier_code,
            invoice_supplier_code_gen,
            invoice_supplier_total_price,
            invoice_supplier_vat,
            invoice_supplier_vat_price,
            invoice_supplier_net_price,
            invoice_supplier_date,
            invoice_supplier_date_recieve,
            invoice_supplier_name,
            invoice_supplier_address,
            invoice_supplier_tax,
            invoice_supplier_branch,
            invoice_supplier_term,
            invoice_supplier_due, 
            invoice_supplier_begin,
            import_duty, 
            freight_in, 
            vat_section,
            vat_section_add,
            invoice_supplier_total_price_non,
            invoice_supplier_vat_price_non,
            invoice_supplier_total_non,
            invoice_supplier_description,
            invoice_supplier_remark,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['supplier_id']."','".
        $data['employee_id']."','".
        static::$db->real_escape_string($data['invoice_supplier_code'])."','".
        static::$db->real_escape_string($data['invoice_supplier_code_gen'])."','".
        $data['invoice_supplier_total_price']."','".
        $data['invoice_supplier_vat']."','".
        $data['invoice_supplier_vat_price']."','".
        $data['invoice_supplier_net_price']."','".
        static::$db->real_escape_string($data['invoice_supplier_date'])."','".
        static::$db->real_escape_string($data['invoice_supplier_date_recieve'])."','".
        static::$db->real_escape_string($data['invoice_supplier_name'])."','".
        static::$db->real_escape_string($data['invoice_supplier_address'])."','".
        static::$db->real_escape_string($data['invoice_supplier_tax'])."','".
        static::$db->real_escape_string($data['invoice_supplier_branch'])."','".
        static::$db->real_escape_string($data['invoice_supplier_term'])."','".
        static::$db->real_escape_string($data['invoice_supplier_due'])."','".  
        $data['invoice_supplier_begin']."','". 
        $data['import_duty']."','".
        $data['freight_in']."','".
        static::$db->real_escape_string($data['vat_section'])."','".
        static::$db->real_escape_string($data['vat_section_add'])."','".
        $data['invoice_supplier_total_price_non']."','".
        $data['invoice_supplier_vat_price_non']."','".
        $data['invoice_supplier_total_non']."','".
        static::$db->real_escape_string($data['invoice_supplier_description'])."','".
        static::$db->real_escape_string($data['invoice_supplier_remark'])."','".
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


    function updateSupplierByInvoiceID($id,$data = []){
        $sql = " UPDATE tb_invoice_supplier SET 
        supplier_id = '".$data['supplier_id']."',  
        invoice_supplier_name = '".static::$db->real_escape_string($data['invoice_supplier_name'])."', 
        invoice_supplier_address = '".static::$db->real_escape_string($data['invoice_supplier_address'])."', 
        invoice_supplier_tax = '".static::$db->real_escape_string($data['invoice_supplier_tax'])."', 
        invoice_supplier_branch = '".static::$db->real_escape_string($data['invoice_supplier_branch'])."', 
        invoice_supplier_term = '".static::$db->real_escape_string($data['invoice_supplier_term'])."',  
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE invoice_supplier_id = $id 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function deleteInvoiceSupplierByID($id){

        $sql = "    SELECT invoice_supplier_list_id, stock_group_id, product_id, invoice_supplier_list_qty, invoice_supplier_list_cost
                    FROM  tb_invoice_supplier_list 
                    WHERE invoice_supplier_id = '$id' ";   
                     
         $sql_delete=[];

         if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
             while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                 $sql_delete [] = "
                     CALL delete_stock_supplier('".
                     $row['stock_group_id']."','".
                     $row['invoice_supplier_list_id']."','".
                     $row['product_id']."','".
                     $row['invoice_supplier_list_qty']."','".
                     $row['invoice_supplier_list_cost']."'".
                     ");
                 ";
                
             }
             $result->close();
         }
 
         for($i = 0 ; $i < count($sql_delete); $i++){

            //echo $sql_delete[$i] . "<br><br>";
             mysqli_query(static::$db,$sql_delete[$i], MYSQLI_USE_RESULT);
         }
 

        $sql = " DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_invoice_supplier WHERE invoice_supplier_id = '$id' ";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }



    }


}
?>