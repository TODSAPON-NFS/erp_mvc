<?php

require_once("BaseModel.php");
class MaintenanceFinanceModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function runMaintenance(){
        //ดึงข้อมูลการรับชำระหนี้
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
        }

    } 
}
?>