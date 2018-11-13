<?php

require_once("BaseModel.php");
class MaintenanceSaleModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function runMaintenance(){
        //ดึงหัวเอกสารการรับสินค้าเข้า
        $sql = "    SELECT * 
                    FROM tb_invoice_customer 
                    LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
                    WHERE invoice_customer_begin = '0' 
                    ORDER BY STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') , invoice_customer_code 
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
                FROM tb_invoice_customer_list 
                LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id  
                WHERE invoice_customer_id = '".$data[$i]['invoice_customer_id']."' 
                ORDER BY invoice_customer_list_id ";
                $data_sub = []; 

                if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                        $data_sub[] = $row;
                    }
                    $result->close(); 
                }
 

                $total = 0;
                $vat_price = 0;
                $net_price = 0;

                //วนรอบอัพเดทรายการสินค้า ---------------------------------
                for($i_sup = 0 ; $i_sup < count($data_sub); $i_sup ++ ){
                    $data_sub[$i_sup]['invoice_customer_list_price'] = round($data_sub[$i_sup]['invoice_customer_list_price'],2); 
                    $data_sub[$i_sup]['invoice_customer_list_total'] = round($data_sub[$i_sup]['invoice_customer_list_qty'] * $data_sub[$i_sup]['invoice_customer_list_price'],2);
                    $total += $data_sub[$i_sup]['invoice_customer_list_total'];

                    $sql = " UPDATE tb_invoice_customer_list 
                    SET product_id = '".$data_sub[$i_sup]['product_id']."', 
                    invoice_customer_list_product_name = '".$data_sub[$i_sup]['invoice_customer_list_product_name']."', 
                    invoice_customer_list_product_detail = '".$data_sub[$i_sup]['invoice_customer_list_product_detail']."',
                    invoice_customer_list_qty = '".$data_sub[$i_sup]['invoice_customer_list_qty']."',
                    invoice_customer_list_price = '".$data_sub[$i_sup]['invoice_customer_list_price']."', 
                    invoice_customer_list_total = '".$data_sub[$i_sup]['invoice_customer_list_total']."',
                    invoice_customer_list_remark = '".$data_sub[$i_sup]['invoice_customer_list_remark']."', 
                    customer_purchase_order_list_id = '".$data_sub[$i_sup]['customer_purchase_order_list_id']."',
                    stock_group_id = '".$data_sub[$i_sup]['stock_group_id']."'
                    WHERE invoice_customer_list_id = '".$data_sub[$i_sup]['invoice_customer_list_id']."'
                    ";

                    //echo "<B> ".$data[$i]['invoice_customer_code']."---->".($i_sup+1)."===>".$data_sub[$i_sup]['product_id']." </B> : ".$sql ."<br><br>";
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                }


                //อัพเดทหัวข้อเอกสารรับสินค้าเข้า ----------------------------------------------------------------------
                $vat_price = $total * $data[$i]['invoice_customer_vat']/100;

                $net_price = $total + $vat_price;

                $data[$i]['invoice_customer_total_price'] = round($total,2);
                $data[$i]['invoice_customer_vat_price'] = round($vat_price,2);
                $data[$i]['invoice_customer_net_price'] = round($net_price,2);

                $sql = "UPDATE tb_invoice_customer SET 
                        customer_id = '".$data[$i]['customer_id']."', 
                        employee_id = '".$data[$i]['employee_id']."', 
                        invoice_customer_code = '".static::$db->real_escape_string($data[$i]['invoice_customer_code'])."', 
                        invoice_customer_total_price = '".$data[$i]['invoice_customer_total_price']."', 
                        invoice_customer_vat = '".$data[$i]['invoice_customer_vat']."', 
                        invoice_customer_vat_price = '".$data[$i]['invoice_customer_vat_price']."', 
                        invoice_customer_net_price = '".$data[$i]['invoice_customer_net_price']."', 
                        invoice_customer_date = '".static::$db->real_escape_string($data[$i]['invoice_customer_date'])."', 
                        invoice_customer_name = '".static::$db->real_escape_string($data[$i]['invoice_customer_name'])."', 
                        invoice_customer_address = '".static::$db->real_escape_string($data[$i]['invoice_customer_address'])."', 
                        invoice_customer_term = '".static::$db->real_escape_string($data[$i]['invoice_customer_term'])."', 
                        invoice_customer_tax = '".static::$db->real_escape_string($data[$i]['invoice_customer_tax'])."', 
                        invoice_customer_branch = '".static::$db->real_escape_string($data[$i]['invoice_customer_branch'])."', 
                        invoice_customer_due = '".static::$db->real_escape_string($data[$i]['invoice_customer_due'])."', 
                        invoice_customer_close = '".$data[$i]['invoice_customer_close']."', 
                        invoice_customer_begin = '".$data[$i]['invoice_customer_begin']."', 
                        vat_section = '".static::$db->real_escape_string($data[$i]['vat_section'])."', 
                        vat_section_add = '".static::$db->real_escape_string($data[$i]['vat_section_add'])."', 
                        invoice_customer_total_price_non = '".$data[$i]['invoice_customer_total_price_non']."', 
                        invoice_customer_vat_price_non = '".$data[$i]['invoice_customer_vat_price_non']."', 
                        invoice_customer_total_non = '".$data[$i]['invoice_customer_total_non']."', 
                        invoice_customer_description = '".static::$db->real_escape_string($data[$i]['invoice_customer_description'])."', 
                        invoice_customer_remark = '".static::$db->real_escape_string($data[$i]['invoice_customer_remark'])."', 
                        updateby = '".$data[$i]['updateby']."', 
                        lastupdate = '".$data[$i]['lastupdate']."' 
                        WHERE invoice_customer_id = '".$data[$i]['invoice_customer_id']."' 
                ";

                //echo "<B> ".$data[$i]['invoice_customer_code']." </B> : ".$sql ."<br><br>";

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            }
        }
    } 
}
?>