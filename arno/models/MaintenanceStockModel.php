<?php

require_once("BaseModel.php");

class MaintenanceStockModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    //***************************************************************************************************************** */
    //
    //
    //  Function ใช้ภายในระบบ Summit Porduct, Invoice Supplier, Stock Move
    //
    //
    //***************************************************************************************************************** */


    function runMaintenance(){

        
        // ล้างข้อมูลทั้งหมดในตาราง รายงานคลังสินค้า
        $sql = "TRUNCATE TABLE tb_stock_report";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        // ค้นหาคลังสินค้าทั้งหมด แล้วล้างประวัติทั้งหมด
        $sql = "SELECT * FROM tb_stock_group ";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close(); 
            //วนรอบล้างประวัติคลังสินค้า

            for($i = 0; $i < count($data); $i++){
                $sql = "TRUNCATE TABLE ".$data[$i]['table_name'];
                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            }
        }

        // ทำการนำสินค้าตั้งต้น เข้าสู่คลังสินค้าต่างๆ 
        $sql = "SELECT * FROM tb_summit_product ORDER BY stock_group_id , product_id";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();

            



            //วนรอบปรับต้นทุนต่างๆ ในคลังสินค้า
            for($i = 0; $i < count($data); $i++){

                // echo "<pre>";
                // print_r($data[$i]);
                // echo "</pre>";

                $this->addSummitProduct('', $data[$i]['stock_group_id'], $data[$i]['summit_product_id'], $data[$i]['product_id'], $data[$i]['summit_product_qty'], $data[$i]['summit_product_cost']);
            }
        }



        
        //ดึงข้อมูลการรับสินค้าเข้าเรียงตามวันที่
        $sql_purchase="SELECT 
        invoice_supplier_code_gen as transaction_code,  
        invoice_supplier_date_recieve as stock_date, 
        '1_purchase' as transaction_type, 
        stock_group_id,   
        '0' as stock_group_id_out, 
        '0' as stock_group_id_in, 
        invoice_supplier_list_id,  
        '0' as stock_move_list_id, 
        '0' as product_rename_list_id, 
        '0' as invoice_customer_list_id,   
        '0' as stock_issue_list_id,  
        product_id, 
        invoice_supplier_list_qty as qty, 
        invoice_supplier_list_cost as cost 
        FROM tb_invoice_supplier 
        LEFT JOIN tb_invoice_supplier_list ON tb_invoice_supplier.invoice_supplier_id = tb_invoice_supplier_list.invoice_supplier_id 
        WHERE invoice_supplier_begin = 0 
        ORDER BY STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') , invoice_supplier_code_gen  ";



        //ดึงข้อมูลการย้ายคลังสินค้าเข้าเรียงตามวันที่
        $sql_move="SELECT 
        stock_move_code as transaction_code,  
        stock_move_date as stock_date,  
        '2_move' as transaction_type, 
        '0' as stock_group_id,   
        stock_group_id_out, 
        stock_group_id_in, 
        '0' as invoice_supplier_list_id, 
        stock_move_list_id, 
        '0' as product_rename_list_id, 
        '0' as invoice_customer_list_id,   
        '0' as stock_issue_list_id,  
        product_id, 
        stock_move_list_qty as qty, 
        '0' as cost 
        FROM tb_stock_move 
        LEFT JOIN tb_stock_move_list ON tb_stock_move.stock_move_id = tb_stock_move_list.stock_move_id 
        ORDER BY STR_TO_DATE(stock_move_date,'%d-%m-%Y %H:%i:%s') , stock_move_code ";



        //ดึงข้อมูลการย้ายจำนวนจากสินค้าชื่อนึง ไป ยังสินค้าชื่อนึง เข้าเรียงตามวันที่
        $sql_rename = "";



        //ดึงข้อมูลการรับสินค้าเข้าเรียงตามวันที่
        $sql_sale="SELECT 
        invoice_customer_code as transaction_code,  
        invoice_customer_date as stock_date, 
        '4_sale' as transaction_type, 
        stock_group_id,  
        '0' as stock_group_id_out, 
        '0' as stock_group_id_in, 
        '0' as invoice_supplier_list_id, 
        '0' as stock_move_list_id, 
        '0' as product_rename_list_id, 
        invoice_customer_list_id,   
        '0' as stock_issue_list_id,  
        product_id, 
        invoice_customer_list_qty as qty, 
        invoice_customer_list_price as cost 
        FROM tb_invoice_customer 
        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
        WHERE invoice_customer_begin = 0 
        ORDER BY STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') , invoice_customer_code  ";


        //ดึงข้อมูลการรับสินค้าเข้าเรียงตามวันที่
        $sql_issue="SELECT 
        stock_issue_code as transaction_code, 
        stock_issue_date as stock_date, 
        '5_issue' as transaction_type, 
        stock_group_id,  
        '0' as stock_group_id_out, 
        '0' as stock_group_id_in, 
        '0' as invoice_supplier_list_id, 
        '0' as stock_move_list_id, 
        '0' as product_rename_list_id, 
        invoice_customer_list_id,   
        '0' as stock_issue_list_id,  
        product_id, 
        stock_issue_list_qty as qty, 
        '0' as cost 
        FROM tb_stock_issue 
        LEFT JOIN tb_stock_issue_list ON tb_stock_issue.stock_issue_id = tb_stock_issue_list.stock_issue_id 
        ORDER BY STR_TO_DATE(stock_issue_date,'%d-%m-%Y %H:%i:%s') , stock_issue_code  ";


        // ดึงข้อมูล Transaction รวมทั้งหมด
        $sql = "SELECT 
        transaction_code, 
        stock_date, 
        transaction_type,
        stock_group_id, 
        stock_group_id_out, 
        stock_group_id_in, 
        invoice_supplier_list_id, 
        stock_move_list_id,
        invoice_customer_list_id,
        product_rename_list_id,
        invoice_customer_list_id,
        stock_issue_list_id,
        product_id, 
        qty, 
        cost 
        FROM    (
                    ($sql_purchase) 
                    UNION  ($sql_move) 
                    UNION  ($sql_sale) 
                ) as tb_transaction  
        ORDER BY STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s'), transaction_type ASC
        "; 
        
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            for($i = 0 ; $i < count($data) ; $i++){
                if($data[$i]['transaction_type'] == '1_purchase'){ // คำนวนคลังสินค้าในรูปแบบของการซื้อ
                    $this->addPurchase($data[$i]['stock_date'], $data[$i]['stock_group_id'], $data[$i]['invoice_supplier_list_id'], $data[$i]['product_id'], $data[$i]['qty'], $data[$i]['cost']);
                }else if($data[$i]['transaction_type'] == '2_move'){// คำนวนคลังสินค้าในรูปแบบของการย้ายคลังสินค้า
                    $this->addMoveStock($data[$i]['stock_date'], $data[$i]['stock_group_id_out'],$data[$i]['stock_group_id_in'],$data[$i]['stock_move_list_id'], $data[$i]['product_id'], $data[$i]['qty']);
                }else if($data[$i]['transaction_type'] == '3_rename'){// คำนวนคลังสินค้าในรูปแบบของการย้ายสินค้าไปยังสินค้าชื่ออื่น

                }else if($data[$i]['transaction_type'] == '4_sale'){// คำนวนคลังสินค้าในรูปแบบของการขาย
                    $this->addSaleStock($data[$i]['stock_date'], $data[$i]['stock_group_id'], $data[$i]['invoice_customer_list_id'], $data[$i]['product_id'], $data[$i]['qty']);
                }else if($data[$i]['transaction_type'] == '5_issue'){// คำนวนคลังสินค้าในรูปแบบของการตัดสินค้า Tool Management

                }
            }
        }
        

        // ทำการดึงข้อมูล การซื้อ การย้ายคลังสินค้า การขาย 
        
        
        

        

    }

    
    //##########################################################################################################
    //
    //####################################### ตรวจสอบและสร้างรายการรายงานคลังสินค้า #################################
    //
    //##########################################################################################################

    function createRowStockReport($stock_group_id,$product_id){
        $sql = "SELECT COUNT(*) as check_row 
        FROM tb_stock_report 
        WHERE tb_stock_report.stock_group_id = '$stock_group_id' 
        AND tb_stock_report.product_id = '$product_id' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            
            if($stock_report['check_row'] == 0){
                $sql = "INSERT INTO tb_stock_report (
                    stock_group_id,
                    product_id
                ) VALUES ('".
                $stock_group_id . "','".
                $product_id . "'".
                "); "; 

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            }
        }

    }






    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือเพิ่มรายรับสินค้าเข้า ###############################
    //
    //##########################################################################################################

    function calculatePurchaseCostIn($stock_group_id, $product_id, $qty, $cost){
        $sql = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock_report['stock_report_qty'];
            $stock_cost = $stock_report['stock_report_cost_avg'];

            $new_qty = $stock_qty + $qty;
            $new_cost = (($stock_qty * $stock_cost) + ($qty * $cost))/$new_qty;
 
            $sql = "UPDATE tb_stock_report 
                    SET stock_report_qty = '$new_qty', 
                        stock_report_cost_avg = '$new_cost' 
                    WHERE stock_group_id = '$stock_group_id' 
                    AND product_id = '$product_id' ; "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

            $stock_report['stock_report_qty'] = $new_qty;
            $stock_report['stock_report_cost_avg'] = $new_cost;
            return $stock_report;
        }
    }






    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือลบรายรับสินค้าเข้า ###############################
    //
    //##########################################################################################################

    function calculatePurchaseCostOut($stock_group_id, $product_id, $qty, $cost){
        $sql = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock_report['stock_report_qty'];
            $stock_cost = $stock_report['stock_report_cost_avg'];

            $new_qty = $stock_qty - $qty;
            $new_cost = (($stock_qty * $stock_cost) - ($qty * $cost))/$new_qty;
 
            $sql = "UPDATE tb_stock_report 
                    SET stock_report_qty = '$new_qty', 
                        stock_report_cost_avg = '$new_cost' 
                    WHERE stock_group_id = '$stock_group_id' 
                    AND product_id = '$product_id' ; "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
            
        }
    }






    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือแก้ไขรายรับสินค้าเข้า ###############################
    //
    //##########################################################################################################

    function calculatePurchaseCostUpdate($stock_group_id, $product_id, $qty_old, $cost_old, $qty, $cost){

        $sql = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock_report['stock_report_qty'];
            $stock_cost = $stock_report['stock_report_cost_avg'];

            $new_qty = $stock_qty - $qty_old;
            $new_cost = (($stock_qty * $stock_cost) - ($qty_old * $cost_old))/$new_qty;


            $new_qty = $new_qty + $qty;
            $new_cost = (($stock_qty * $stock_cost) - ($qty * $cost))/$new_qty;

 
            $sql = "UPDATE tb_stock_report 
                    SET stock_report_qty = '$new_qty', 
                        stock_report_cost_avg = '$new_cost' 
                    WHERE stock_group_id = '$stock_group_id' 
                    AND product_id = '$product_id' ; "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
        }
    }






    //##########################################################################################################
    //
    //############################ คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือเพิ่มรายการขายสินค้า ###############################
    //
    //##########################################################################################################

    function calculateSaleCostIn($stock_group_id, $product_id, $qty){
        $sql = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock_report['stock_report_qty'];
            $stock_cost = $stock_report['stock_report_cost_avg'];

            $new_qty = $stock_qty - $qty; 
            $new_cost = $stock_cost ;
            $sql = "UPDATE tb_stock_report 
                    SET stock_report_qty = '$new_qty', 
                        stock_report_cost_avg = '$new_cost' 
                    WHERE stock_group_id = '$stock_group_id' 
                    AND product_id = '$product_id' ; "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

            $stock_report['stock_report_qty'] = $new_qty;
            $stock_report['stock_report_cost_avg'] = $new_cost; 

            return $stock_report;
        }else{
            return 0;
        }
    }





    //##########################################################################################################
    //
    //############################ คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือลบรายการขายสินค้า ###############################
    //
    //##########################################################################################################

    function calculateSaleCostOut($stock_group_id, $product_id, $qty, $cost){
        $sql = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock_report['stock_report_qty'];
            $stock_cost = $stock_report['stock_report_cost_avg'];

            $new_qty = $stock_qty + $qty;
            $new_cost = (($stock_qty * $stock_cost) + ($qty * $cost))/$new_qty;
 
            $sql = "UPDATE tb_stock_report 
                    SET stock_report_qty = '$new_qty', 
                        stock_report_cost_avg = '$new_cost' 
                    WHERE stock_group_id = '$stock_group_id' 
                    AND product_id = '$product_id' ; "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
        }
    }





    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือแก้ไขรายรับสินค้าเข้า ###############################
    //
    //##########################################################################################################

    function calculateSaleCostUpdate($stock_group_id, $product_id, $qty_old, $cost_old, $qty){

        $sql = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock_report['stock_report_qty'];
            $stock_cost = $stock_report['stock_report_cost_avg'];

            $new_qty = $stock_qty + $qty_old;
            $new_cost = (($stock_qty * $stock_cost) + ($qty_old * $cost_old))/$new_qty;


            $new_qty = $new_qty - $qty; 

 
            $sql = "UPDATE tb_stock_report 
                    SET stock_report_qty = '$new_qty', 
                        stock_report_cost_avg = '$new_cost' 
                    WHERE stock_group_id = '$stock_group_id' 
                    AND product_id = '$product_id' ; "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
            return $new_cost;
        }else{
            return 0;
        }
    }






    //##########################################################################################################
    //
    //##################################### ดึงข้อมูลคลังสินค้าจาก stock_group_id ###################################
    //
    //##########################################################################################################

    function getStockGroupTable($stock_group_id){
        if($stock_group_id != 0){
            $sql ="SELECT `table_name`,`stock_group_id`  
            FROM tb_stock_group 
            WHERE tb_stock_group.stock_group_id = '$stock_group_id' ";
        }else{
            $sql ="SELECT `table_name`,`stock_group_id`  
            FROM tb_stock_group 
            WHERE tb_stock_group.stock_group_primary = '1' ";
        }

        $stock = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            return $stock;
        }else{

        }
    }






    //##########################################################################################################
    //
    //################################## ทำการคำณวนต้นทุนเมื่อเพิ่มรายการสินค้ายกยอดมา ################################
    //
    //##########################################################################################################

    function addSummitProduct($stock_date, $stock_group_id = 0, $summit_product_id, $product_id, $qty, $cost){

        $stock = $this->getStockGroupTable($stock_group_id);

        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostIn($stock['stock_group_id'], $product_id, $qty, $cost);
 
        $sql = "INSERT INTO ". $stock['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            summit_product_id
        ) VALUE ('".
        "in"."','".
        $product_id."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        ($qty * $cost)."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_qty'] * $stock_report['stock_report_cost_avg'])."','".
        $summit_product_id."'); "; 
        //echo $sql."<br><br>";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }  







    //##########################################################################################################
    //
    //################################## ทำการคำณวนต้นทุนเมื่อลบรายการสินค้ายกยอดมา ################################
    //
    //##########################################################################################################

    function removeSummitProduct($stock_group_id, $summit_product_id, $product_id, $qty, $cost){
        $stock = $this->getStockGroupTable($stock_group_id); 

        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostOut($stock['stock_group_id'], $product_id, $qty, $cost);

        $sql = "DELETE FROM ".$stock['table_name']." WHERE summit_product_id ='".$summit_product_id."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
    }  







    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการรับสินค้า #################################
    //
    //##########################################################################################################

    function addPurchase($stock_date, $stock_group_id = 0, $invoice_supplier_list_id, $product_id, $qty, $cost){

        $stock = $this->getStockGroupTable($stock_group_id);

        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostIn($stock['stock_group_id'], $product_id, $qty, $cost);
 
        $sql = "INSERT INTO ". $stock['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            invoice_supplier_list_id
            ) VALUE ('".
        "in"."','".
        $product_id."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        ($qty * $cost)."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_qty'] * $stock_report['stock_report_cost_avg'])."','".
        $invoice_supplier_list_id."'); "; 

        

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
 
    }   







    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อลบรายการรับสินค้า #################################
    //
    //##########################################################################################################

    function removePurchase($stock_group_id, $invoice_supplier_list_id, $product_id, $qty, $cost){
        $stock = $this->getStockGroupTable($stock_group_id); 

        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostOut($stock['stock_group_id'], $product_id, $qty, $cost);

        $sql = "DELETE FROM ".$stock['table_name']." WHERE invoice_supplier_list_id ='".$invoice_supplier_list_id."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);  

    } 







    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการขายสินค้า #################################
    //
    //##########################################################################################################

    function addSaleStock($stock_date, $stock_group_id, $invoice_customer_list_id, $product_id, $qty){ 
        $stock = $this->getStockGroupTable($stock_group_id); 
        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculateSaleCostIn($stock['stock_group_id'],$product_id,$qty); 
 
        $sql = "INSERT INTO ". $stock['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            invoice_customer_list_id
            ) VALUE ('".
        "out"."','".
        $product_id."','".
        $stock_date."','".
        (0)."','".
        (0)."','".
        (0)."','".
        $qty."','".
        $stock_report['stock_report_cost_avg']."','".
        ($qty * $stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_qty'] * $stock_report['stock_report_cost_avg'])."','".
        $invoice_customer_list_id."'); "; 
        echo $sql."<br><br>";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    } 

 






    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อลบรายการขายสินค้า #################################
    //
    //##########################################################################################################

    function removeSaleStock($stock_group_id,$invoice_customer_list_id, $product_id, $qty, $cost){
        $stock = $this->getStockGroupTable($stock_group_id); 

        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculateSaleCostOut($stock['stock_group_id'], $product_id, $qty, $cost);

        $sql = "DELETE FROM ".$stock['table_name']." WHERE invoice_customer_list_id ='".$invoice_customer_list_id."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);  

    }








    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการย้ายสินค้า #################################
    //
    //##########################################################################################################

    function addMoveStock($stock_date,$stock_group_id_out,$stock_group_id_in,$stock_move_list_id, $product_id, $qty){


        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาออก -------------------------------------------
        $stock_out = $this->getStockGroupTable($stock_group_id_out);

        $this->createRowStockReport($stock_out['stock_group_id'],$product_id);

        $stock_report = $this->calculateSaleCostIn($stock['stock_group_id'],$product_id,$qty); 
 
        $sql = "INSERT INTO ". $stock_out['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            stock_move_list_id
            ) VALUE ('".
        "out"."','".
        $product_id."','".
        $stock_date."','".
        (0)."','".
        (0)."','".
        (0)."','".
        $qty."','".
        $stock_report['stock_report_cost_avg']."','".
        ($qty * $stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_qty'] * $stock_report['stock_report_cost_avg'])."','".
        $stock_move_list_id."'); "; 

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาเข้า -------------------------------------------
        $cost = $stock_report['stock_report_cost_avg'];
        $stock_in = $this->getStockGroupTable($stock_group_id_in);
        $this->createRowStockReport($stock_in['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostIn($stock_in['stock_group_id'], $product_id, $qty, $cost);
 
        $sql = "INSERT INTO ". $stock_in['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            stock_move_list_id
            ) VALUE ('".
        "in"."','".
        $product_id."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        ($qty * $cost)."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_qty'] * $stock_report['stock_report_cost_avg'])."','".
        $stock_move_list_id."'); "; 

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


    } 








    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการย้ายสินค้า #################################
    //
    //##########################################################################################################

    function removeMoveStock($stock_group_id_out,$stock_group_id_in,$stock_move_list_id, $product_id, $qty, $cost){
        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาออก -------------------------------------------
        $stock_out = $this->getStockGroupTable($stock_group_id_out); 

        $this->createRowStockReport($stock_out['stock_group_id'],$product_id);
        $stock_report = $this->calculateSaleCostOut($stock['stock_group_id'], $product_id, $qty, $cost);

        $sql = "DELETE FROM ".$stock_out['table_name']." WHERE stock_move_list_id ='".$stock_move_list_id."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 


        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาเข้า -------------------------------------------
        $stock_in = $this->getStockGroupTable($stock_group_id_in); 

        $this->createRowStockReport($stock_in['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostOut($stock_in['stock_group_id'], $product_id, $qty, $cost);

        $sql = "DELETE FROM ".$stock_in['table_name']." WHERE invoice_supplier_list_id ='".$invoice_supplier_list_id."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);  



    }



}
?>