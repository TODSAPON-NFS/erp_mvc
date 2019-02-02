<?php

require_once("BaseModel.php");

class MaintenancePurchaseModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
 


    function runMaintenance(){

        $sql = "TRUNCATE TABLE tb_journal_purchase ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "TRUNCATE TABLE tb_journal_purchase_list ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        //ดึงหัวเอกสารการรับสินค้าเข้า
        $sql = "    SELECT * 
                    FROM tb_invoice_supplier 
                    LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
                    WHERE invoice_supplier_begin = '0' 
                    ORDER BY STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') , invoice_supplier_code_gen 
        ";
        $data = [];
        
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close(); 

            for($i = 0 ; $i < count($data) ; $i++){
                // ดึงรายการรับสินค้าในเอกสารนั้น -----------------------------------------------------------------
                $sql = "SELECT * 
                FROM tb_invoice_supplier_list 
                LEFT JOIN tb_product ON tb_invoice_supplier_list.product_id = tb_product.product_id 
                LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id 
                WHERE invoice_supplier_id = '".$data[$i]['invoice_supplier_id']."' 
                ORDER BY invoice_supplier_list_no ";
                $data_sub = []; 

                if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                        $data_sub[] = $row;
                    }
                    $result->close(); 
                }

                /*
                if($data[$i]['invoice_supplier_id'] == 276){
                    echo "<pre>";
                    print_r($data_sub);
                    echo "</pre>";

                }
                */

               
                $journal_list = [];
               //คำนวนต้นทุนในกรณีเป็นบริษัทภายในประเทศ ----------------------------------------------------------
                if( $data[$i]['supplier_domestic'] == "ภายในประเทศ"){
                    $total = 0;
                    $vat_price = 0;
                    $net_price = 0;

                    //วนรอบอัพเดทรายการสินค้า ---------------------------------
                    for($i_sup = 0 ; $i_sup < count($data_sub); $i_sup ++ ){
                        $data_sub[$i_sup]['invoice_supplier_list_price'] = round($data_sub[$i_sup]['invoice_supplier_list_price'],2);
                        $data_sub[$i_sup]['invoice_supplier_list_cost'] = $data_sub[$i_sup]['invoice_supplier_list_price'];
                        $data_sub[$i_sup]['invoice_supplier_list_total'] = round($data_sub[$i_sup]['invoice_supplier_list_qty'] * $data_sub[$i_sup]['invoice_supplier_list_price'],2);
                        $total += $data_sub[$i_sup]['invoice_supplier_list_total'];

                        $sql = " UPDATE tb_invoice_supplier_list 
                                SET product_id = '".$data_sub[$i_sup]['product_id']."', 
                                invoice_supplier_list_product_name = '".$data_sub[$i_sup]['invoice_supplier_list_product_name']."',  
                                invoice_supplier_list_product_detail = '".$data_sub[$i_sup]['invoice_supplier_list_product_detail']."', 
                                invoice_supplier_list_qty = '".$data_sub[$i_sup]['invoice_supplier_list_qty']."', 
                                invoice_supplier_list_price = '".$data_sub[$i_sup]['invoice_supplier_list_price']."', 
                                invoice_supplier_list_total = '".$data_sub[$i_sup]['invoice_supplier_list_total']."', 
                                invoice_supplier_list_remark = '".$data_sub[$i_sup]['invoice_supplier_list_remark']."', 
                                stock_group_id = '".$data_sub[$i_sup]['stock_group_id']."', 
                                invoice_supplier_list_cost = '".$data_sub[$i_sup]['invoice_supplier_list_cost']."', 
                                purchase_order_list_id = '".$data_sub[$i_sup]['purchase_order_list_id']."' 
                                WHERE invoice_supplier_list_id = '".$data_sub[$i_sup]['invoice_supplier_list_id']."' 
                        "; 

                        //echo "<B> ".$data[$i]['invoice_supplier_code_gen']."---->".($i_sup+1)."===>".$data_sub[$i_sup]['product_id']." </B> : ".$sql ."<br><br>";
                        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
                        $has_account = false;
                        for($ii = 0 ; $ii < count($journal_list); $ii++){
                            if($journal_list[$ii]['account_id'] == $data_sub[$i_sup]['buy_account_id']){
                                $has_account = true;
                                $journal_list[$ii]['invoice_supplier_list_total'] += $data_sub[$i_sup]['invoice_supplier_list_total'];
                                break;
                            }
                        }

                        if($has_account == false){
                            $journal_list[] = array (
                                "account_id"=>$data_sub[$i_sup]['buy_account_id'], 
                                "invoice_supplier_list_total"=>$data_sub[$i_sup]['invoice_supplier_list_total'] 
                            ); 
                        } 

                    }
                

                    //อัพเดทหัวข้อเอกสารรับสินค้าเข้า ----------------------------------------------------------------------
                    $vat_price = $total * $data[$i]['invoice_supplier_vat']/100;

                    $net_price = $total + $vat_price;

                    $data[$i]['invoice_supplier_total_price'] = round($total,2);
                    $data[$i]['invoice_supplier_vat_price'] = round($vat_price,2);
                    $data[$i]['invoice_supplier_net_price'] = round($net_price,2);

                    $sql = "    UPDATE tb_invoice_supplier SET 
                                invoice_supplier_id = '".$data[$i]['invoice_supplier_id']."', 
                                supplier_id = '".$data[$i]['supplier_id']."', 
                                employee_id = '".$data[$i]['employee_id']."', 
                                invoice_supplier_code = '".static::$db->real_escape_string($data[$i]['invoice_supplier_code'])."', 
                                invoice_supplier_code_gen = '".static::$db->real_escape_string($data[$i]['invoice_supplier_code_gen'])."', 
                                invoice_supplier_total_price = '".$data[$i]['invoice_supplier_total_price']."', 
                                invoice_supplier_vat = '".$data[$i]['invoice_supplier_vat']."', 
                                invoice_supplier_vat_price = '".$data[$i]['invoice_supplier_vat_price']."', 
                                invoice_supplier_net_price = '".$data[$i]['invoice_supplier_net_price']."', 
                                invoice_supplier_date = '".static::$db->real_escape_string($data[$i]['invoice_supplier_date'])."', 
                                invoice_supplier_date_recieve = '".static::$db->real_escape_string($data[$i]['invoice_supplier_date_recieve'])."', 
                                invoice_supplier_name = '".static::$db->real_escape_string($data[$i]['invoice_supplier_name'])."', 
                                invoice_supplier_address = '".static::$db->real_escape_string($data[$i]['invoice_supplier_address'])."', 
                                invoice_supplier_tax = '".static::$db->real_escape_string($data[$i]['invoice_supplier_tax'])."', 
                                invoice_supplier_branch = '".static::$db->real_escape_string($data[$i]['invoice_supplier_branch'])."', 
                                invoice_supplier_term = '".static::$db->real_escape_string($data[$i]['invoice_supplier_term'])."', 
                                invoice_supplier_due = '".static::$db->real_escape_string($data[$i]['invoice_supplier_due'])."',  
                                invoice_supplier_begin = '".$data[$i]['invoice_supplier_begin']."', 
                                import_duty = '".$data[$i]['import_duty']."', 
                                freight_in = '".$data[$i]['freight_in']."', 
                                vat_section = '".static::$db->real_escape_string($data[$i]['vat_section'])."', 
                                vat_section_add = '".static::$db->real_escape_string($data[$i]['vat_section_add'])."', 
                                invoice_supplier_total_price_non = '".$data[$i]['invoice_supplier_total_price_non']."', 
                                invoice_supplier_vat_price_non = '".$data[$i]['invoice_supplier_vat_price_non']."', 
                                invoice_supplier_total_non = '".$data[$i]['invoice_supplier_total_non']."', 
                                invoice_supplier_description = '".static::$db->real_escape_string($data[$i]['invoice_supplier_description'])."', 
                                invoice_supplier_remark = '".static::$db->real_escape_string($data[$i]['invoice_supplier_remark'])."', 
                                updateby = '".$data[$i]['updateby']."', 
                                lastupdate = '".$data[$i]['lastupdate']."' 
                                WHERE invoice_supplier_id = '".$data[$i]['invoice_supplier_id']."' 
                    ";
            
                    // echo "<B> ".$data[$i]['invoice_supplier_code_gen']." </B> : ".$sql ."<br><br>"; 
                    // echo "<pre>";
                    // print_r($sql);
                    // echo "</pre>"; 

            
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                    //account setting id = 9 ภาษีซื้อ  --> [1154-00] ภาษีซื้อ
                    $sql = " SELECT *
                    FROM tb_account_setting 
                    LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
                    LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
                    WHERE tb_account_setting.account_setting_id = '9' 
                    ";

                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $account_vat_purchase ;
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $account_vat_purchase  = $row;
                        }
                        $result->close();
                    } 
                        
                    //account setting id = 26 ซื้อสินค้า --> [5130-01] ซื้อ
                    $sql = " SELECT *
                    FROM tb_account_setting 
                    LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
                    LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
                    WHERE tb_account_setting.account_setting_id = '26' 
                    ";

                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $account_purchase ;
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $account_purchase  = $row;
                        }
                        $result->close();
                    }  
/*
                    if($data[$i]['invoice_supplier_id'] == 276){
                        echo "<pre>";
                        print_r($data_sub);
                        echo "</pre>";
    
                        echo "<pre>";
                        print_r($journal_list);
                        echo "</pre>";
    
                    }
*/
                    $account_supplier = $data[$i]['account_id'];

                    $this->updateJournal($data[$i],$journal_list, $account_supplier, $account_vat_purchase['account_id'],$account_purchase['account_id']);

                }else{

                    $sql = " SELECT *  FROM tb_exchange_rate_baht 
                    LEFT JOIN tb_currency ON tb_exchange_rate_baht.currency_id = tb_currency.currency_id  
                    WHERE tb_exchange_rate_baht.currency_id = '".$data[$i]['currency_id'] ."' 
                    AND tb_exchange_rate_baht.exchange_rate_baht_date ='". $data[$i]['invoice_supplier_date_recieve'] ."'  
                    "; 

                    
            
                    $total = 0;
                    $vat_price = 0;
                    $net_price = 0;
                    $cost_duty = 0;
                    $cost_price_total_s = 0;
                    $cost_price_ex_total_s = 0;

            
                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $exchange = mysqli_fetch_array($result,MYSQLI_ASSOC);
                        $result->close();
                        $exchange_rate = round($exchange['exchange_rate_baht_value'],5);
                    }else{
                        $exchange_rate = 1;
                    }
 

                    //echo "<b>".$sql." ===> ".$exchange_rate."</b><br><br>";

                     for($i_sup = 0 ; $i_sup < count($data_sub); $i_sup ++ ){ 
                        $data_sub[$i_sup]['invoice_supplier_list_price'] = $data_sub[$i_sup]['invoice_supplier_list_currency_price'] * $exchange_rate; 
                        $cost_qty = $data_sub[$i_sup]['invoice_supplier_list_qty'];
                        $cost_price = $data_sub[$i_sup]['invoice_supplier_list_price'] ;
                        $cost_duty += $cost_qty * $cost_price;
                        $total += $cost_qty * $cost_price;
                        $data_sub[$i_sup]['invoice_supplier_list_total'] = round($cost_qty * $cost_price,2);
                        
                        $has_account = false;
                        for($ii = 0 ; $ii < count($journal_list); $ii++){
                            if($journal_list[$ii]['account_id'] == $data_sub[$i_sup]['buy_account_id']){
                                $has_account = true;
                                $journal_list[$ii]['invoice_supplier_list_total'] +=  $cost_qty * $cost_price;
                                break;
                            }
                        }

                        if($has_account == false){
                            $journal_list[] = array (
                                "account_id"=>$data_sub[$i_sup]['buy_account_id'], 
                                "invoice_supplier_list_total"=> $cost_qty * $cost_price 
                            ); 
                        } 

                    }


                    //คำนวนตามค่าที่ Fix เอาไว้
                    $import_duty_amount = $data[$i]['import_duty'];
                    $invoice_supplier_total_price_ex_use = 0;
                    // if($data[$i]['invoice_supplier_id'] == 163){
                    //     echo "<pre>";
                    //     print_r($import_duty_amount);
                    //     echo "</pre>";
                    // }
                    for($i_sup = 0 ; $i_sup < count($data_sub); $i_sup ++ ){
                        $cost_qty = $data_sub[$i_sup]['invoice_supplier_list_qty']; 
                        $cost_price_ex = $data_sub[$i_sup]['invoice_supplier_list_price'];
                        $cost_price_ex_total = $cost_qty * $cost_price_ex;

                        $val_duty = 0;
                        if($data_sub[$i_sup]['invoice_supplier_list_fix_type'] == 'percent-fix' || $data_sub[$i_sup]['invoice_supplier_list_fix_type'] == 'price-fix'){
                            if($data_sub[$i_sup]['invoice_supplier_list_fix_type'] == 'percent-fix'){

                                $val_duty = ($data_sub[$i_sup]['invoice_supplier_list_duty']/ 100  * $cost_price_ex_total) ;   
                            }else if($data_sub[$i_sup]['invoice_supplier_list_fix_type'] == 'price-fix'){
                
                                $val_duty = $data_sub[$i_sup]['invoice_supplier_list_duty'] ;   
                                
                            }else{
                                $val_duty = 0;
                                $cost_price_ex_total = 0;
                            }

                            

                            if($import_duty_amount - $val_duty < 0){
                                $val_duty = $import_duty_amount;
                                $import_duty_amount = 0;
                            }else{
                                $import_duty_amount = $import_duty_amount - $val_duty;
                            }
                            
                            $data_sub[$i_sup]['invoice_supplier_list_import_duty'] = $val_duty / $data_sub[$i_sup]['invoice_supplier_list_qty'] ;
                            // if($data[$i]['invoice_supplier_id'] == 163){
                            //     echo "<pre>";
                            //     print_r( "Price : " . $data_sub[$i_sup]['invoice_supplier_list_import_duty'] );
                            //     echo "</pre>";
                            // }
                            $invoice_supplier_total_price_ex_use += $cost_price_ex_total;

                        }
 



                        if($cost_duty * $data[$i]['freight_in'] == 0){
                            $cost_price_f = 0;
                        }else{
                            $cost_price_f = $cost_price_ex_total / $cost_duty * $data[$i]['freight_in'];
                        }  

                        $data_sub[$i_sup]['invoice_supplier_list_freight_in'] = $cost_price_f / $data_sub[$i_sup]['invoice_supplier_list_qty'];

                        $cost_total = $data_sub[$i_sup]['invoice_supplier_list_import_duty'] + $data_sub[$i_sup]['invoice_supplier_list_freight_in'] + $data_sub[$i_sup]['invoice_supplier_list_price']; 
                        $data_sub[$i_sup]['invoice_supplier_list_cost'] = round($cost_total,2);

                        $sql = " UPDATE tb_invoice_supplier_list 
                                SET product_id = '".$data_sub[$i_sup]['product_id']."', 
                                invoice_supplier_list_product_name = '".$data_sub[$i_sup]['invoice_supplier_list_product_name']."',  
                                invoice_supplier_list_product_detail = '".$data_sub[$i_sup]['invoice_supplier_list_product_detail']."', 
                                invoice_supplier_list_qty = '".$data_sub[$i_sup]['invoice_supplier_list_qty']."', 
                                invoice_supplier_list_price = '".$data_sub[$i_sup]['invoice_supplier_list_price']."', 
                                invoice_supplier_list_total = '".$data_sub[$i_sup]['invoice_supplier_list_total']."', 
                                invoice_supplier_list_remark = '".$data_sub[$i_sup]['invoice_supplier_list_remark']."', 
                                stock_group_id = '".$data_sub[$i_sup]['stock_group_id']."', 
                                invoice_supplier_list_freight_in = '".$data_sub[$i_sup]['invoice_supplier_list_freight_in']."', 
                                invoice_supplier_list_import_duty = '".$data_sub[$i_sup]['invoice_supplier_list_import_duty']."', 
                                invoice_supplier_list_cost = '".$data_sub[$i_sup]['invoice_supplier_list_cost']."', 
                                purchase_order_list_id = '".$data_sub[$i_sup]['purchase_order_list_id']."' 
                                WHERE invoice_supplier_list_id = '".$data_sub[$i_sup]['invoice_supplier_list_id']."' 
                        "; 

                        //echo "<B> ".$data[$i]['invoice_supplier_code_gen']."---->".($i_sup+1)."===>".$data_sub[$i_sup]['product_id']." </B> : ".$sql ."<br><br>";
                        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                       

                    }

                    $invoice_supplier_total_price_ex = $total - $invoice_supplier_total_price_ex_use;

                    // if($data[$i]['invoice_supplier_id'] == 163){
                    //     echo "<pre>";
                    //     print_r($import_duty_amount);
                    //     echo "</pre>";
                    // }


                    for($i_sup = 0 ; $i_sup < count($data_sub); $i_sup ++ ){
                        $cost_qty = $data_sub[$i_sup]['invoice_supplier_list_qty']; 
                        $cost_price_ex = $data_sub[$i_sup]['invoice_supplier_list_price'];
                        $cost_price_ex_total = $cost_qty * $cost_price_ex;
                        $val_duty = 0;

                        if($data_sub[$i_sup]['invoice_supplier_list_fix_type'] == 'no-fix' || $data_sub[$i_sup]['invoice_supplier_list_fix_type'] == "" ){
                            $data_sub[$i_sup]['invoice_supplier_list_fix_type'] = 'no-fix';

                            if($invoice_supplier_total_price_ex > 0){
                                $val_duty = $cost_price_ex_total / $invoice_supplier_total_price_ex * $data[$i]['import_duty'];
                            }else{
                                $val_duty = 0;
                            }

                            
                            
                            if($import_duty_amount - $val_duty < 0){
                                $val_duty = $import_duty_amount;
                                $import_duty_amount = 0;
                            }else{
                                $import_duty_amount = $import_duty_amount - $val_duty;
                            }

                            $data_sub[$i_sup]['invoice_supplier_list_import_duty'] = $val_duty / $data_sub[$i_sup]['invoice_supplier_list_qty'] ;

                        }



                        
                         
 



                        if($cost_duty * $data[$i]['freight_in'] == 0){
                            $cost_price_f = 0;
                        }else{
                            $cost_price_f = $cost_price_ex_total / $cost_duty * $data[$i]['freight_in'];
                        }  

                        $data_sub[$i_sup]['invoice_supplier_list_freight_in'] = $cost_price_f / $data_sub[$i_sup]['invoice_supplier_list_qty'];

                        $cost_total = $data_sub[$i_sup]['invoice_supplier_list_import_duty'] + $data_sub[$i_sup]['invoice_supplier_list_freight_in'] + $data_sub[$i_sup]['invoice_supplier_list_price']; 
                        $data_sub[$i_sup]['invoice_supplier_list_cost'] = round($cost_total,2);

                        $sql = " UPDATE tb_invoice_supplier_list 
                                SET product_id = '".$data_sub[$i_sup]['product_id']."', 
                                invoice_supplier_list_product_name = '".$data_sub[$i_sup]['invoice_supplier_list_product_name']."',  
                                invoice_supplier_list_product_detail = '".$data_sub[$i_sup]['invoice_supplier_list_product_detail']."', 
                                invoice_supplier_list_qty = '".$data_sub[$i_sup]['invoice_supplier_list_qty']."', 
                                invoice_supplier_list_price = '".$data_sub[$i_sup]['invoice_supplier_list_price']."', 
                                invoice_supplier_list_total = '".$data_sub[$i_sup]['invoice_supplier_list_total']."', 
                                invoice_supplier_list_remark = '".$data_sub[$i_sup]['invoice_supplier_list_remark']."', 
                                stock_group_id = '".$data_sub[$i_sup]['stock_group_id']."', 
                                invoice_supplier_list_freight_in = '".$data_sub[$i_sup]['invoice_supplier_list_freight_in']."', 
                                invoice_supplier_list_import_duty = '".$data_sub[$i_sup]['invoice_supplier_list_import_duty']."', 
                                invoice_supplier_list_cost = '".$data_sub[$i_sup]['invoice_supplier_list_cost']."', 
                                purchase_order_list_id = '".$data_sub[$i_sup]['purchase_order_list_id']."' 
                                WHERE invoice_supplier_list_id = '".$data_sub[$i_sup]['invoice_supplier_list_id']."' 
                        "; 

                        //echo "<B> ".$data[$i]['invoice_supplier_code_gen']."---->".($i_sup+1)."===>".$data_sub[$i_sup]['product_id']." </B> : ".$sql ."<br><br>";
                        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                       

                    }
// if($data[$i]['invoice_supplier_id'] == 163){
//                         echo "<pre>";
//                         print_r($import_duty_amount);
//                         echo "</pre>";
//                     }

                    




                    //อัพเดทหัวข้อเอกสารรับสินค้าเข้า ----------------------------------------------------------------------
                    $vat_price = $total * $data[$i]['invoice_supplier_vat']/100;

                    $net_price = $total + $vat_price;

                    $data[$i]['invoice_supplier_total_price'] = round($total,2);
                    $data[$i]['invoice_supplier_vat_price'] = round($vat_price,2);
                    $data[$i]['invoice_supplier_net_price'] = round($net_price,2);

                    $sql = "    UPDATE tb_invoice_supplier SET 
                                invoice_supplier_id = '".$data[$i]['invoice_supplier_id']."', 
                                supplier_id = '".$data[$i]['supplier_id']."', 
                                employee_id = '".$data[$i]['employee_id']."', 
                                invoice_supplier_code = '".static::$db->real_escape_string($data[$i]['invoice_supplier_code'])."', 
                                invoice_supplier_code_gen = '".static::$db->real_escape_string($data[$i]['invoice_supplier_code_gen'])."', 
                                invoice_supplier_total_price = '".$data[$i]['invoice_supplier_total_price']."', 
                                invoice_supplier_vat = '".$data[$i]['invoice_supplier_vat']."', 
                                invoice_supplier_vat_price = '".$data[$i]['invoice_supplier_vat_price']."', 
                                invoice_supplier_net_price = '".$data[$i]['invoice_supplier_net_price']."', 
                                invoice_supplier_date = '".static::$db->real_escape_string($data[$i]['invoice_supplier_date'])."', 
                                invoice_supplier_date_recieve = '".static::$db->real_escape_string($data[$i]['invoice_supplier_date_recieve'])."', 
                                invoice_supplier_name = '".static::$db->real_escape_string($data[$i]['invoice_supplier_name'])."', 
                                invoice_supplier_address = '".static::$db->real_escape_string($data[$i]['invoice_supplier_address'])."', 
                                invoice_supplier_tax = '".static::$db->real_escape_string($data[$i]['invoice_supplier_tax'])."', 
                                invoice_supplier_branch = '".static::$db->real_escape_string($data[$i]['invoice_supplier_branch'])."', 
                                invoice_supplier_term = '".static::$db->real_escape_string($data[$i]['invoice_supplier_term'])."', 
                                invoice_supplier_due = '".static::$db->real_escape_string($data[$i]['invoice_supplier_due'])."',  
                                invoice_supplier_begin = '".$data[$i]['invoice_supplier_begin']."', 
                                import_duty = '".$data[$i]['import_duty']."', 
                                freight_in = '".$data[$i]['freight_in']."', 
                                vat_section = '".static::$db->real_escape_string($data[$i]['vat_section'])."', 
                                vat_section_add = '".static::$db->real_escape_string($data[$i]['vat_section_add'])."', 
                                invoice_supplier_total_price_non = '".$data[$i]['invoice_supplier_total_price_non']."', 
                                invoice_supplier_vat_price_non = '".$data[$i]['invoice_supplier_vat_price_non']."', 
                                invoice_supplier_total_non = '".$data[$i]['invoice_supplier_total_non']."', 
                                invoice_supplier_description = '".static::$db->real_escape_string($data[$i]['invoice_supplier_description'])."', 
                                invoice_supplier_remark = '".static::$db->real_escape_string($data[$i]['invoice_supplier_remark'])."', 
                                updateby = '".$data[$i]['updateby']."', 
                                lastupdate = '".$data[$i]['lastupdate']."' 
                                WHERE invoice_supplier_id = '".$data[$i]['invoice_supplier_id']."' 
                    ";
            
                    //echo "<B> ".$data[$i]['invoice_supplier_code_gen']." </B> : ".$sql ."<br><br><br><br><br>";
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                    //account setting id = 9 ภาษีซื้อ  --> [1154-00] ภาษีซื้อ
                    $sql = " SELECT *
                    FROM tb_account_setting 
                    LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
                    LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
                    WHERE tb_account_setting.account_setting_id = '9' 
                    ";

                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $account_vat_purchase ;
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $account_vat_purchase  = $row;
                        }
                        $result->close();
                    } 
                        
                    //account setting id = 26 ซื้อสินค้า --> [5130-01] ซื้อ
                    $sql = " SELECT *
                    FROM tb_account_setting 
                    LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
                    LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
                    WHERE tb_account_setting.account_setting_id = '26' 
                    ";

                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $account_purchase ;
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $account_purchase  = $row;
                        }
                        $result->close();
                    }  
    
                    $account_supplier = $data[$i]['account_id'];
/*
                    if($data[$i]['invoice_supplier_id'] == 276){
                        echo "<pre>";
                        print_r($data_sub);
                        echo "</pre>";
    
                        echo "<pre>";
                        print_r($journal_list);
                        echo "</pre>";
    
                    }
*/
                    $this->updateJournal($data[$i],$journal_list, $account_supplier, $account_vat_purchase['account_id'],$account_purchase['account_id']);
                    

                }    
            }
        } 

    } 

    function updateJournal($data,$journal_list, $account_supplier, $account_vat_purchase,$account_purchase){
        //----------------------------- สร้างสมุดรายวันซื้อ ----------------------------------------  
        $journal_purchase_name = "ซื้อเชื่อจาก ".$data['invoice_supplier_name']." [".$data['invoice_supplier_code_gen']."] "; 

        $sql = " SELECT * 
        FROM tb_journal_purchase 
        WHERE invoice_supplier_id = '".$data['invoice_supplier_id']."' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $journal;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $journal = $row;
            }
            $result->close();
        }


        if($journal['journal_purchase_id'] != ""){
            $journal_purchase_id = $journal['journal_purchase_id'];

            $sql = " UPDATE tb_journal_purchase SET 
            journal_purchase_code = '".$data['invoice_supplier_code_gen']."', 
            journal_purchase_date = '".$data['invoice_supplier_date_recieve']."', 
            journal_purchase_name = '".$journal_purchase_name."', 
            updateby = '".$data['updateby']."', 
            lastupdate = NOW() 
            WHERE journal_purchase_id = '".$journal_purchase_id."' 
            ";

            //echo $sql."<br><br>";
    
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            $sql = " DELETE FROM tb_journal_purchase_list WHERE journal_purchase_id = '$journal_purchase_id' ";
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        }else{
            $sql = " INSERT INTO tb_journal_purchase (
                invoice_supplier_id,
                journal_purchase_code, 
                journal_purchase_date,
                journal_purchase_name,
                addby,
                adddate,
                updateby, 
                lastupdate) 
            VALUES ('".
            $data['invoice_supplier_id']."','".
            $data['invoice_supplier_code_gen']."','".
            $data['invoice_supplier_date_recieve']."','".
            $journal_purchase_name."','".
            $data['addby']."',".
            "NOW(),'".
            $data['addby'].
            "',NOW()); 
            ";
    
            //echo $sql."<br><br>";
    
            if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $journal_purchase_id = mysqli_insert_id(static::$db);
            }
        }

       



        //----------------------------- สิ้นสุด สร้างสมุดรายวันซื้อ ----------------------------------------

        if($journal_purchase_id != ""){ 

            //---------------------------- เพิ่มรายการเจ้าหนี้ --------------------------------------------
            $journal_purchase_list_debit = 0;
            $journal_purchase_list_credit = 0;

            if((float)filter_var( $data['invoice_supplier_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) < 0){
                $journal_purchase_list_debit = (float)filter_var( $data['invoice_supplier_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $journal_purchase_list_credit = 0;
            }else{
                $journal_purchase_list_debit = 0;
                $journal_purchase_list_credit = (float)filter_var( $data['invoice_supplier_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            } 

            $sql = " INSERT INTO tb_journal_purchase_list (
                journal_purchase_id,
                journal_cheque_id,
                journal_cheque_pay_id,
                journal_invoice_customer_id,
                journal_invoice_supplier_id,
                account_id,
                journal_purchase_list_name,
                journal_purchase_list_debit,
                journal_purchase_list_credit,
                addby,
                adddate,
                updateby,
                lastupdate
            ) VALUES (
                '".$journal_purchase_id."',  
                '0', 
                '0', 
                '0', 
                '0', 
                '".$account_supplier."', 
                '".$journal_purchase_name."', 
                '".$journal_purchase_list_debit."',
                '".$journal_purchase_list_credit."',
                '".$data['addby']."', 
                NOW(), 
                '".$data['updateby']."', 
                NOW() 
            ); 
            ";

            //echo $sql."<br><br>";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            
            //---------------------------- สิ้นสุด เพิ่มรายการเจ้าหนี้ --------------------------------------------
            

            //---------------------------- เพิ่มรายการซื้อเชื่อ --------------------------------------------
            for($i = 0; $i < count($journal_list) ; $i++){
                $journal_purchase_list_debit = 0;
                $journal_purchase_list_credit = 0;
                
                if($journal_list[$i]['account_id'] == 0){
                    $account_id = $account_purchase;
                }else{
                    $account_id = $journal_list[$i]['account_id'];
                }
                



                if((float)filter_var( $journal_list[$i]['invoice_supplier_list_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) > 0){
                    $journal_purchase_list_debit = round((float)filter_var( $journal_list[$i]['invoice_supplier_list_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),2);
                    $journal_purchase_list_credit = 0;
                }else{
                    $journal_purchase_list_debit = 0;
                    $journal_purchase_list_credit = round((float)filter_var( $journal_list[$i]['invoice_supplier_list_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),2);
                } 

                $sql = " INSERT INTO tb_journal_purchase_list (
                    journal_purchase_id,
                    journal_cheque_id,
                    journal_cheque_pay_id,
                    journal_invoice_customer_id,
                    journal_invoice_supplier_id,
                    account_id,
                    journal_purchase_list_name,
                    journal_purchase_list_debit,
                    journal_purchase_list_credit,
                    addby,
                    adddate,
                    updateby,
                    lastupdate
                ) VALUES (
                    '".$journal_purchase_id."',  
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '".$account_id."', 
                    '".$journal_purchase_name."', 
                    '".$journal_purchase_list_debit."',
                    '".$journal_purchase_list_credit."',
                    '".$data['addby']."', 
                    NOW(), 
                    '".$data['updateby']."', 
                    NOW() 
                ); 
                ";

                //echo $sql."<br><br>";

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            } 
            //---------------------------- สิ้นสุด เพิ่มรายการซื้อเชื่อ --------------------------------------------


            //---------------------------- เพิ่มรายการภาษีซื้อ --------------------------------------------
            if((float)filter_var( $data['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) != 0.0){
                $journal_purchase_list_debit = 0;
                $journal_purchase_list_credit = 0;

                if((float)filter_var( $data['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) < 0){
                    $journal_purchase_list_debit = 0;
                    $journal_purchase_list_credit = (float)filter_var( $data['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }else{
                    $journal_purchase_list_debit = (float)filter_var( $data['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $journal_purchase_list_credit = 0;
                }


                $sql = " INSERT INTO tb_journal_purchase_list (
                    journal_purchase_id,
                    journal_cheque_id,
                    journal_cheque_pay_id,
                    journal_invoice_customer_id,
                    journal_invoice_supplier_id,
                    account_id,
                    journal_purchase_list_name,
                    journal_purchase_list_debit,
                    journal_purchase_list_credit,
                    addby,
                    adddate,
                    updateby,
                    lastupdate
                ) VALUES (
                    '".$journal_purchase_id."',  
                    '0', 
                    '0', 
                    '0', 
                    '". $data['invoice_supplier_id']."', 
                    '".$account_vat_purchase."', 
                    '".$journal_purchase_name."', 
                    '".$journal_purchase_list_debit."',
                    '".$journal_purchase_list_credit."',
                    '".$data['addby']."', 
                    NOW(), 
                    '".$data['updateby']."', 
                    NOW() 
                ); 
                ";

                //echo $sql."<br><br><hr>";

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            } 
            //---------------------------- สิ้นสุด เพิ่มรายการภาษีซื้อ --------------------------------------------

        }
    }
}
?>