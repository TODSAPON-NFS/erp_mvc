<?php

require_once("BaseModel.php");
class StockReportModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockReportListBy($stock_group_id = '', $keyword = ''){

        if($stock_group_id != ""){
            $str_stock = " AND tb_stock_report.stock_group_id = '$stock_group_id' ";
        }
        $sql = "    SELECT * 
                    FROM tb_product 
                    LEFT JOIN tb_stock_report ON tb_product.product_id = tb_stock_report.product_id 
                    LEFT JOIN tb_stock_group ON tb_stock_report.stock_group_id = tb_stock_group.stock_group_id  
                    LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id  
                    WHERE ( product_name LIKE ('%$keyword%') OR product_description LIKE ('%$keyword%') OR CONCAT(product_code_first,product_code) LIKE ('%$keyword%') )
                    $str_stock 
                    GROUP BY  tb_product.product_id, tb_stock_report.stock_group_id 
                    ORDER BY  tb_product.product_id, tb_stock_report.stock_group_id ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getStockReportBalanceBy($product_id,$table_name,$stock_date){ 
         
        $sql = "SELECT balance_qty,balance_stock_cost_avg,balance_stock_cost_avg_total 
        FROM $table_name
        WHERE stock_id = (SELECT MAX(stock_id)  
        FROM $table_name
        WHERE product_id = '$product_id' 
        AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$stock_date','%d-%m-%Y %H:%i:%s') )"; 
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }
    }

    function updateCostWhenInsert($stock_group_id, $product_id, $qty, $cost){
        $stock_qty = 0;
        $stock_cost = 0.0;
        
        $new_qty = 0;
        $new_cost = 0.0;
        
        
        $str = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
            if(count($data) > 0){


                $new_qty = $data['stock_report_qty'] + $qty;
                $new_cost = (($data['stock_report_qty'] * $data['stock_report_cost_avg']) + ($qty * $cost))/$new_qty;
                 
    
                $str = "UPDATE tb_stock_report SET
                stock_report_qty = '$new_qty' , 
                stock_report_cost_avg = '$new_cost' 
                WHERE stock_group_id = '$stock_group_id' 
                AND product_id = '$product_id' ";
    
                if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    return true;
                }else {
                    return false;
                }
            }else{
                return false;
            }
        } 
    }

    function updateCostWhenUpdate($stock_group_id, $product_id, $qty_old, $cost_old, $qty_new, $cost_new  ){
        $stock_qty = 0;
        $stock_cost = 0.0;
        
        $new_qty = 0;
        $new_cost = 0.0;
        
        
        $str = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
            if(count($data) > 0){


                $new_qty_out = $data['stock_report_qty'] - $qty_old;
                $new_cost = (($data['stock_report_qty'] * $data['stock_report_cost_avg']) - ($qty_old * $cost_old))/$new_qty_out;
                 
                $new_qty = $new_qty_out + $qty_new;
                $new_cost = (($new_qty_out * $new_cost) + ($qty_new * $cost_new))/$new_qty;
    
                $str = "UPDATE tb_stock_report SET
                stock_report_qty = '$new_qty' , 
                stock_report_cost_avg = '$new_cost' 
                WHERE stock_group_id = '$stock_group_id' 
                AND product_id = '$product_id' ";
    
                if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    return true;
                }else {
                    return false;
                }
            }else{
                return false;
            }
        }    
    }

    function updateCostWhenDelete($stock_group_id, $product_id, $qty, $cost){
        $stock_qty = 0;
        $stock_cost = 0.0;
        
        $new_qty = 0;
        $new_cost = 0.0;
        
        
        $str = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
            if(count($data) > 0){
                $new_qty = $data['stock_report_qty'] - $qty;
                $new_cost = (($data['stock_report_qty'] * $data['stock_report_cost_avg']) - ($qty * $cost))/$new_qty;
                 
    
                $str = "UPDATE tb_stock_report SET
                stock_report_qty = '$new_qty' , 
                stock_report_cost_avg = '$new_cost' 
                WHERE stock_group_id = '$stock_group_id' 
                AND product_id = '$product_id' ";
    
                if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    return true;
                }else {
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    function insertStockReportRow($stock_group_id, $product_id){

        $str = "SELECT *  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            

            if(mysqli_num_rows($result) == 0){
                $sql = " INSERT INTO tb_stock_report (
                    stock_group_id,
                    product_id
                ) 
                VALUES ('$stock_group_id','$product_id'); 
                ";
        
                //echo $sql;
                if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    return mysqli_insert_id(static::$db);
                }else {
                    return 0;
                }
            }else{
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                return $row['stock_report_id'];
            }

        }

    }
    //#####################################################################################################################
    //
    //
    //------------------------------------------ รายงานจุดสั่งซื้อ --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportMinPointBy($product_start = "",$product_end = "",$product_type = "",$supplier_id = "",$product_qty = ""){
     
        $str_product = "";   
        $str_product_type = "";   
        $str_supplier_id = "";   
        $str_product_qty = "";   

        if($product_start != "" && $product_end != ""){
            $str_product = " AND tb_product.product_code >= '$product_start' AND tb_product.product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(tb_product.product_code_first,tb_product.product_code) LIKE ('%$product_start%') ";  
        } 

        if($product_type != ""){
            $str_product_type = " AND tb_product.product_type = '$product_type' "; 
        }  

        if($supplier_id != ""){
            $str_supplier_id = " AND tb_product_supplier.supplier_id = '$supplier_id' "; 
        }  
        if($product_qty != ""){
            if($product_qty = "low"){
                $str_product_qty = " AND (stock_report_qty - minimum_stock) < '0' "; 
            }else if($product_qty = "normal"){
                $str_product_qty = " AND (stock_report_qty - minimum_stock) = '0' "; 
            }else if($product_qty = "high"){
                $str_product_qty = " AND (stock_report_qty - minimum_stock) > '0' "; 
            }    
        }

        $sql =" SELECT CONCAT(product_code_first,product_code) AS product_code, 
                product_name , 
                (SELECT  GROUP_CONCAT(supplier_name_en SEPARATOR ', ') AS supplier_name_en   
                        FROM tb_product_supplier LEFT JOIN tb_supplier ON tb_product_supplier.supplier_id = tb_supplier.supplier_id 
                        WHERE product_id = tb_product.product_id  ) AS supplier_name_en, 
                minimum_stock ,
                safety_stock , 
                IFNULL(stock_report_qty,0) AS stock_report_qty ,
                IFNULL((minimum_stock - stock_report_qty),0) AS product_buy  
                FROM tb_product 
                LEFT JOIN tb_stock_report ON tb_product.product_id = tb_stock_report.product_id 
                LEFT JOIN tb_product_customer ON tb_product.product_id = tb_product_customer.product_id 
                LEFT JOIN tb_product_supplier ON tb_product.product_id = tb_product_supplier.product_id 
                WHERE 1 
                tb_product.product_id = '22492'   
                $str_product
                $str_product_type
                $str_supplier_id
                $str_product_qty
                GROUP BY product_id 
                ORDER BY product_code ASC    
        "; 
        echo $sql;
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
    //------------------------------------------ รายงานรายละเอียดสินค้า --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportProductDescriptionBy($product_start = "",$product_end = ""){
     
        $str_product = "";   

        if($product_start != "" && $product_end != ""){
            $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
        } 


        $sql =" SELECT product_code_first, product_code, product_name, product_barcode, product_description, product_category_name, product_group_name, product_type_name, product_unit_name,
                (SELECT account_name_th FROM tb_account WHERE account_id = tb_product.buy_account_id) AS buy_account_name ,
                (SELECT account_name_th FROM tb_account WHERE account_id = tb_product.sale_account_id) AS sale_account_name 
                FROM tb_product 
                LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
                LEFT JOIN tb_product_group ON tb_product.product_group = tb_product_group.product_group_id    
                LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id    
                LEFT JOIN tb_product_unit ON tb_product.product_unit = tb_product_unit.product_unit_id    
                LEFT JOIN tb_account ON tb_product.buy_account_id = tb_account.account_id   
                WHERE 1 
                $str_product
                ORDER BY product_code ASC    
        "; 
        // echo $sql;
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
    //------------------------------------------ รายงานสินค้าและวัตถุดิบ --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportBy($stock_start = "",$stock_end = "",$product_start = "",$product_end = ""){
      
        $str_stock = "";  
        $str_product = "";  

        if($stock_start != "" && $stock_end != ""){
            $str_stock = " AND CAST(stock_group_code AS UNSIGNED) >= '$stock_start' AND CAST(stock_group_code AS UNSIGNED) <=  '$stock_end' "; 
        }else if ($stock_start != "" && $stock_end == ""){
            $str_stock = " AND stock_group_code = '$stock_start' ";  
        } 

        if($product_start != "" && $product_end != ""){
            $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
        } 


        $sql =" SELECT stock_group_name ,CONCAT(product_code_first,product_code) as product_code,product_name ,stock_report_qty,stock_report_cost_avg, (stock_report_qty*stock_report_cost_avg) AS  stock_report_total 
                FROM tb_product 
                LEFT JOIN tb_stock_report ON tb_product.product_id = tb_stock_report.product_id 
                LEFT JOIN tb_stock_group ON tb_stock_report.stock_group_id = tb_stock_group.stock_group_id 
                WHERE tb_product.product_id IS NOT NULL 
                $str_stock
                $str_product
                ORDER BY stock_group_name,product_code ASC
        "; 
        // echo $sql;
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
    //------------------------------------------ รายงานสินค้าคงเหลือ --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportBalanceListBy($stock_start = "",$stock_end = "",$product_start = "",$product_end = ""){
      
        $str_stock = "";  
        $str_product = "";  

        if($stock_start != "" && $stock_end != ""){
            $str_stock = " AND CAST(stock_group_code AS UNSIGNED) >= '$stock_start' AND CAST(stock_group_code AS UNSIGNED) <=  '$stock_end' "; 
        }else if ($stock_start != "" && $stock_end == ""){
            $str_stock = " AND stock_group_code = '$stock_start' ";  
        } 

        if($product_start != "" && $product_end != ""){
            $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
        } 


        $sql =" SELECT stock_group_name ,CONCAT(product_code_first,product_code) as product_code,product_name ,stock_report_qty,stock_report_cost_avg, (stock_report_qty*stock_report_cost_avg) AS  stock_report_total 
                FROM tb_stock_report 
                LEFT JOIN tb_product ON tb_stock_report.product_id = tb_product.product_id 
                LEFT JOIN tb_stock_group ON tb_stock_report.stock_group_id = tb_stock_group.stock_group_id 
                WHERE tb_product.product_id IS NOT NULL 
                AND stock_report_qty != 0 
                $str_stock
                $str_product
                ORDER BY stock_group_name,product_code ASC
        "; 
        // echo $sql;
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
    //------------------------------------------ รายงานรายการประจำวันสินค้า --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportProductMovementDayBy($date_target = "",$stock_start = "",$stock_end = "",$product_start = "",$product_end = "",$table_name = "",$group_by = "",$paper_code = ""){

        $str_stock = "";   
        $str_product = "";   
        $str_table_name = "";   
        $str_group_by = "";   
        $str_paper = "";   

        if($stock_start != "" && $stock_end != ""){
            $str_stock = " AND CAST(stock_group_code AS UNSIGNED) >= '$stock_start' AND CAST(stock_group_code AS UNSIGNED) <=  '$stock_end' "; 
        }else if ($stock_start != "" && $stock_end == ""){
            $str_stock = " AND stock_group_code = '$stock_start' ";  
        } 

        if($product_start != "" && $product_end != ""){
            $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
        } 

        if($paper_code != ""){  
            $str_paper = " AND paper_code LIKE ('%$paper_code%')  ";  
        }
        
        if($group_by != ""){
            $str_group_by = " GROUP BY $group_by "; 
        }
        
        

        $sql ="SELECT table_name 
                    FROM tb_stock_group 
                    WHERE 1 
                    $str_stock
                    ";


        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close(); 
        }

      
        
        $sql = '';
        for($i = 0 ;$i<count($data)&&count($data)>0;$i++){

            $str_date_target = "";
            $str_table_name = "";
            $str_paper_code = "";

            if($date_target != ""){
                $str_date_target = " AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') = STR_TO_DATE('$date_target','%d-%m-%Y %H:%i:%s') "; 
            }

            if($table_name != ""){
                if($table_name =="invoice_supplier"){
                    $str_paper_code = " 
                    ".$table_name."_code_gen AS paper_code 
                    ";
                }else{
                    $str_paper_code = " 
                    ".$table_name."_code AS paper_code 
                    ";
                }
                
                $str_table_name = " 
                INNER JOIN tb_".$table_name."_list ON ".$data[$i]['table_name'].".".$table_name."_list_id = tb_".$table_name."_list.".$table_name."_list_id 
                INNER JOIN tb_".$table_name." ON tb_".$table_name."_list.".$table_name."_id = tb_".$table_name.".".$table_name."_id   
                "; 
            }else{
                $str_paper_code = " 
                IFNULL(
                        invoice_supplier_code_gen,
                    IFNULL(
                            invoice_customer_code,
                        IFNULL(
                                delivery_note_supplier_code,
                            IFNULL(
                                    delivery_note_customer_code,
                                IFNULL(
                                        stock_move_code,
                                    IFNULL(
                                            stock_issue_code,
                                        IFNULL(
                                                credit_note_code,
                                            IFNULL(
                                                    regrind_supplier_code,
                                                IFNULL(
                                                        stock_change_product_code,'initial' 
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                ) AS paper_code ";
                $str_table_name = " 
                LEFT JOIN tb_delivery_note_supplier_list ON ".$data[$i]['table_name'].".delivery_note_supplier_list_id = tb_delivery_note_supplier_list.delivery_note_supplier_list_id 
                LEFT JOIN tb_delivery_note_supplier ON tb_delivery_note_supplier_list.delivery_note_supplier_id = tb_delivery_note_supplier.delivery_note_supplier_id  
                
                LEFT JOIN tb_delivery_note_customer_list ON ".$data[$i]['table_name'].".delivery_note_customer_list_id = tb_delivery_note_customer_list.delivery_note_customer_list_id 
                LEFT JOIN tb_delivery_note_customer ON tb_delivery_note_customer_list.delivery_note_customer_id = tb_delivery_note_customer.delivery_note_customer_id 
                
                LEFT JOIN tb_invoice_supplier_list ON ".$data[$i]['table_name'].".invoice_supplier_list_id = tb_invoice_supplier_list.invoice_supplier_list_id 
                LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
                
                LEFT JOIN tb_invoice_customer_list ON ".$data[$i]['table_name'].".invoice_customer_list_id = tb_invoice_customer_list.invoice_customer_list_id 
                LEFT JOIN tb_invoice_customer ON tb_invoice_customer_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
                
                LEFT JOIN tb_stock_move_list ON ".$data[$i]['table_name'].".stock_move_list_id = tb_stock_move_list.stock_move_list_id 
                LEFT JOIN tb_stock_move ON tb_stock_move_list.stock_move_id = tb_stock_move.stock_move_id 
                
                LEFT JOIN tb_stock_issue_list ON ".$data[$i]['table_name'].".stock_issue_list_id = tb_stock_issue_list.stock_issue_list_id 
                LEFT JOIN tb_stock_issue ON tb_stock_issue_list.stock_issue_id = tb_stock_issue.stock_issue_id 
                
                LEFT JOIN tb_credit_note_list ON ".$data[$i]['table_name'].".credit_note_list_id = tb_credit_note_list.credit_note_list_id 
                LEFT JOIN tb_credit_note ON tb_credit_note_list.credit_note_id = tb_credit_note.credit_note_id 
                
                LEFT JOIN tb_regrind_supplier_list ON ".$data[$i]['table_name'].".regrind_supplier_list_id = tb_regrind_supplier_list.regrind_supplier_list_id 
                LEFT JOIN tb_regrind_supplier ON tb_regrind_supplier_list.regrind_supplier_id = tb_regrind_supplier.regrind_supplier_id 
                
                LEFT JOIN tb_stock_change_product_list ON ".$data[$i]['table_name'].".stock_change_product_list_id = tb_stock_change_product_list.stock_change_product_list_id 
                LEFT JOIN tb_stock_change_product ON tb_stock_change_product_list.stock_change_product_id = tb_stock_change_product.stock_change_product_id  
                "; 
            }

            if($i == 0){
                $sql .=" SELECT * FROM 
                ( 
                ";
            }
            $str_col ="";
            if($group_by == "product_code"){
                $str_col = "  
                CONCAT(product_code_first,product_code) as product_code ,
                product_name ,  
                $str_paper_code
                "; 
                $str_order_by = "ORDER BY product_code ASC ";
            }else if($group_by == "stock_group_code"){
                $str_col = " 
                (SELECT stock_group_name FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_name ,
                (SELECT stock_group_code FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_code ,
                $str_paper_code
                "; 
                $str_order_by = "ORDER BY stock_group_code ASC ";
            }else{
                $str_col = " 
                concat('".$data[$i]['table_name']."_',stock_id) AS from_stock ,
                ".$data[$i]['table_name'].".product_id ,
                CONCAT(product_code_first,product_code) as product_code ,
                product_name ,
                '".$data[$i]['table_name']."' AS table_name ,
                (SELECT stock_group_name FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_name ,
                (SELECT stock_group_code FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_code ,
                stock_type ,
                stock_date ,
                in_qty ,
                in_stock_cost_avg,
                in_stock_cost_avg_total,
                out_qty,
                out_stock_cost_avg,
                out_stock_cost_avg_total,
                balance_qty,
                balance_stock_cost_avg,
                balance_stock_cost_avg_total,
                $str_paper_code
                "; 
                $str_order_by = "ORDER BY  product_code,stock_group_code,STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s'),from_stock ASC ";
            }

            $sql .="(SELECT 
            $str_col
            FROM ".$data[$i]['table_name']." 
            LEFT JOIN tb_product ON ".$data[$i]['table_name'].".product_id = tb_product.product_id   
            $str_table_name
            WHERE 1 
            $str_date
            $str_date_target
            $str_product
            ) 
            ";
            if(($i+1)<count($data)){
                $sql .=" union";
            } 
        }  
        
        $sql .="  
        )
        AS tb_stock 
        WHERE 1 
        $str_paper
        $str_group_by
        $str_order_by
        "; 
        // echo $sql;
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
    //------------------------------------------ รายงานความเคลื่อนไหวสินค้า --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportProductMovementBy($date_start = "",$date_end = "",$stock_start = "",$stock_end = "",$product_start = "",$product_end = ""){

        $str_stock = "";   
        $str_product = "";   

        if($stock_start != "" && $stock_end != ""){
            $str_stock = " AND CAST(stock_group_code AS UNSIGNED) >= '$stock_start' AND CAST(stock_group_code AS UNSIGNED) <=  '$stock_end' "; 
        }else if ($stock_start != "" && $stock_end == ""){
            $str_stock = " AND stock_group_code = '$stock_start' ";  
        } 

        if($product_start != "" && $product_end != ""){
            $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
        } 

        $sql ="SELECT table_name 
                    FROM tb_stock_group 
                    WHERE 1 
                    $str_stock
                    ";


        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close(); 
        }

      
        
        $sql = '';
        for($i = 0 ;$i<count($data)&&count($data)>0;$i++){

            $str_date = "";

            if($date_start != "" && $date_end != ""){
                $str_date = " AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
            }else if ($date_start != ""){
                $str_date = " AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
            }else if ($date_end != ""){
                $str_date = " AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
            }

            if($i == 0){
                $sql .=" SELECT * FROM 
                ( 
                ";
            }
            $sql .="(SELECT concat('".$data[$i]['table_name']."_',stock_id) AS from_stock ,
            ".$data[$i]['table_name'].".product_id ,CONCAT(product_code_first,product_code) as product_code ,product_name ,
            '".$data[$i]['table_name']."' AS table_name ,
            (SELECT stock_group_name FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_name ,
            (SELECT stock_group_code FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_code ,
            stock_type ,
            stock_date ,
            in_qty ,
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty,
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty,
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            IFNULL(
                    invoice_supplier_code_gen,
                IFNULL(
                        invoice_customer_code,
                    IFNULL(
                            delivery_note_supplier_code,
                        IFNULL(
                                delivery_note_customer_code,
                            IFNULL(
                                    stock_move_code,
                                IFNULL(
                                        stock_issue_code,
                                    IFNULL(
                                            credit_note_code,
                                        IFNULL(
                                                regrind_supplier_code,
                                            IFNULL(
                                                    stock_change_product_code,'initial'
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            ) AS paper_code
            FROM ".$data[$i]['table_name']." 
            LEFT JOIN tb_product ON ".$data[$i]['table_name'].".product_id = tb_product.product_id  
            
            LEFT JOIN tb_delivery_note_supplier_list ON ".$data[$i]['table_name'].".delivery_note_supplier_list_id = tb_delivery_note_supplier_list.delivery_note_supplier_list_id 
            LEFT JOIN tb_delivery_note_supplier ON tb_delivery_note_supplier_list.delivery_note_supplier_id = tb_delivery_note_supplier.delivery_note_supplier_id  
            
            LEFT JOIN tb_delivery_note_customer_list ON ".$data[$i]['table_name'].".delivery_note_customer_list_id = tb_delivery_note_customer_list.delivery_note_customer_list_id 
            LEFT JOIN tb_delivery_note_customer ON tb_delivery_note_customer_list.delivery_note_customer_id = tb_delivery_note_customer.delivery_note_customer_id 
            
            LEFT JOIN tb_invoice_supplier_list ON ".$data[$i]['table_name'].".invoice_supplier_list_id = tb_invoice_supplier_list.invoice_supplier_list_id 
            LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
            
            LEFT JOIN tb_invoice_customer_list ON ".$data[$i]['table_name'].".invoice_customer_list_id = tb_invoice_customer_list.invoice_customer_list_id 
            LEFT JOIN tb_invoice_customer ON tb_invoice_customer_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
            
            LEFT JOIN tb_stock_move_list ON ".$data[$i]['table_name'].".stock_move_list_id = tb_stock_move_list.stock_move_list_id 
            LEFT JOIN tb_stock_move ON tb_stock_move_list.stock_move_id = tb_stock_move.stock_move_id 
            
            LEFT JOIN tb_stock_issue_list ON ".$data[$i]['table_name'].".stock_issue_list_id = tb_stock_issue_list.stock_issue_list_id 
            LEFT JOIN tb_stock_issue ON tb_stock_issue_list.stock_issue_id = tb_stock_issue.stock_issue_id 
            
            LEFT JOIN tb_credit_note_list ON ".$data[$i]['table_name'].".credit_note_list_id = tb_credit_note_list.credit_note_list_id 
            LEFT JOIN tb_credit_note ON tb_credit_note_list.credit_note_id = tb_credit_note.credit_note_id 
            
            LEFT JOIN tb_regrind_supplier_list ON ".$data[$i]['table_name'].".regrind_supplier_list_id = tb_regrind_supplier_list.regrind_supplier_list_id 
            LEFT JOIN tb_regrind_supplier ON tb_regrind_supplier_list.regrind_supplier_id = tb_regrind_supplier.regrind_supplier_id 
            
            LEFT JOIN tb_stock_change_product_list ON ".$data[$i]['table_name'].".stock_change_product_list_id = tb_stock_change_product_list.stock_change_product_list_id 
            LEFT JOIN tb_stock_change_product ON tb_stock_change_product_list.stock_change_product_id = tb_stock_change_product.stock_change_product_id  
            WHERE 1 
            $str_date
            $str_product
            ORDER BY ".$data[$i]['table_name'].".product_id ,STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s'),from_stock  ASC ) 
            ";
            if(($i+1)<count($data)){
                $sql .=" union";
            }
            
        }  
        
        $sql .="  
        )
        AS tb_stock
        ORDER BY  product_code,stock_group_code,STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s'),from_stock ASC
        "; 
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    } 

    function getStockReportProductBy($product_category_id = "", $product_type_id = "",$product_start = "",$product_end = ""){
          
        
        if($product_type_id != ""){
            $str_product_type = "AND tb_product.product_type = '$product_type_id' ";
        } 


        if($product_category_id != ""){
            $str_product_category = "AND tb_product.product_category_id = '$product_category_id' ";
        }  


        if($product_start != "" && $product_end != ""){
            $str_product = " AND tb_product.product_code  >= '$product_start' AND tb_product.product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(tb_product.product_code_first,tb_product.product_code) LIKE ('%$product_start%') ";   
        } 

        
        $sql = " SELECT tb_product.product_id, CONCAT(product_code_first,product_code) as product_code, product_name, product_type, 
        product_price_1, product_price_2, product_price_3, product_price_4, product_price_5, product_price_6, product_price_7 
        FROM tb_product 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id  
        WHERE 1 
        $str_product_type
        $str_product_category 
        $str_product  
        GROUP BY tb_product.product_id
        ORDER BY product_code  
        "; 
        // echo $sql;
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