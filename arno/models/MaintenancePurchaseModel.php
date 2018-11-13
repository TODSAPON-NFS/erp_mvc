<?php

require_once("BaseModel.php");

class MaintenancePurchaseModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function runMaintenance(){

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
                ORDER BY invoice_supplier_list_id ";
                $data_sub = []; 

                if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                        $data_sub[] = $row;
                    }
                    $result->close(); 
                }

               
                
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

                        //echo "<B> ".$data[$i]['invoice_supplier_code']."---->".($i_sup+1)."===>".$data_sub[$i_sup]['product_id']." </B> : ".$sql ."<br><br>";
                        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

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
            
                    //echo "<B> ".$data[$i]['invoice_supplier_code']." </B> : ".$sql ."<br><br>";
            
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

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
                        $data_sub[$i_sup]['invoice_supplier_list_price'] = $data_sub[$i_sup]['purchase_order_list_price'] * $exchange_rate; 
                        $cost_qty = $data_sub[$i_sup]['invoice_supplier_list_qty'];
                        $cost_price = $data_sub[$i_sup]['invoice_supplier_list_price'] ;
                        $cost_duty += $cost_qty * $cost_price;
                        $total += $cost_qty * $cost_price;
                        $data_sub[$i_sup]['invoice_supplier_list_total'] = round($cost_qty * $cost_price,2);
                    }
                    
                    for($i_sup = 0 ; $i_sup < count($data_sub); $i_sup ++ ){
                        $cost_qty = $data_sub[$i_sup]['invoice_supplier_list_qty']; 
                        $cost_price_ex = $data_sub[$i_sup]['invoice_supplier_list_price'];
 
                        $cost_price_ex_total = $cost_qty * $cost_price_ex;

                        if($cost_duty * $data[$i]['import_duty'] == 0){
                            $cost_price_duty = 0;
                        }else{
                            $cost_price_duty = $cost_price_ex_total / $cost_duty * $data[$i]['import_duty'];
                        }

                        if($cost_duty * $data[$i]['freight_in'] == 0){
                            $cost_price_f = 0;
                        }else{
                            $cost_price_f = $cost_price_ex_total / $cost_duty * $data[$i]['freight_in'];
                        } 

                        $cost_total = $cost_price_f + $cost_price_duty + $cost_price_ex_total; 

                        $data_sub[$i]['invoice_supplier_list_cost'] = round($cost_total,2);

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
            
                    //echo "<B> ".$data[$i]['invoice_supplier_code_gen']." </B> : ".$sql ."<br><br><br><br><br>";
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
                    

                }    
            }
        } 

    } 
}
?>