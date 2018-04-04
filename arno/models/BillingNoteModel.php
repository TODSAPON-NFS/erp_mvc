<?php

require_once("BaseModel.php");
class BillingNoteModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getBillingNoteBy($date_start  = '', $date_end  = ''){
        $sql = " SELECT billing_note_id, 
        billing_note_code, 
        billing_note_date, 
        billing_note_total,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        billing_note_term, 
        billing_note_due, 
        IFNULL(CONCAT(tb2.customer_name_en,' (',tb2.customer_name_th,')'),'-') as customer_name  
        FROM tb_billing_note 
        LEFT JOIN tb_user as tb1 ON tb_billing_note.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_billing_note.customer_id = tb2.customer_id 
        ORDER BY STR_TO_DATE(billing_note_date,'%Y-%m-%d %H:%i:%s') DESC 
         ";
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getBillingNoteByID($id){
        $sql = " SELECT * 
        FROM tb_billing_note 
        LEFT JOIN tb_customer ON tb_billing_note.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_billing_note.employee_id = tb_user.user_id 
        WHERE billing_note_id = '$id' 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getBillingNoteViewByID($id){
        $sql = " SELECT *   
        FROM tb_billing_note 
        LEFT JOIN tb_invoice_customer ON tb_billing_note.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        LEFT JOIN tb_user ON tb_billing_note.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user.user_position_id 
        LEFT JOIN tb_customer ON tb_billing_note.customer_id = tb_customer.customer_id 
        WHERE billing_note_id = '$id' 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getBillingNoteLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(billing_note_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  billing_note_lastcode 
        FROM tb_billing_note
        WHERE billing_note_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['billing_note_lastcode'];
        }

    }


    function generateBillingNoteListByInvoiceCustomerId($invoice_customer_id, $data = [],$search=""){

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

        $sql_customer = "SELECT tb2.product_id, 
        tb2.invoice_customer_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,  
        IFNULL(invoice_customer_list_qty 
        - IFNULL((
            SELECT SUM(billing_note_list_qty) 
            FROM tb_billing_note_list 
            WHERE invoice_customer_list_id = tb2.invoice_customer_list_id 
        ),0) ,0) as billing_note_list_qty,  
        invoice_customer_list_price as billing_note_list_price, 
        invoice_customer_list_total as billing_note_list_total, 
        invoice_customer_list_product_name as billing_note_list_product_name, 
        invoice_customer_list_product_detail as billing_note_list_product_detail, 
        invoice_customer_list_remark as billing_note_list_remark 
        FROM tb_invoice_customer 
        LEFT JOIN tb_invoice_customer_list as tb2 ON tb_invoice_customer.invoice_customer_id = tb2.invoice_customer_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        WHERE tb_invoice_customer.invoice_customer_id = '$invoice_customer_id' 
        AND tb2.invoice_customer_list_id NOT IN ($str) 
        AND tb2.invoice_customer_list_id IN (
            SELECT tb_invoice_customer_list.invoice_customer_list_id 
            FROM tb_invoice_customer_list  
            LEFT JOIN tb_billing_note_list ON  tb_invoice_customer_list.invoice_customer_list_id = tb_billing_note_list.invoice_customer_list_id 
            GROUP BY tb_invoice_customer_list.invoice_customer_list_id 
            HAVING IFNULL(SUM(billing_note_list_qty),0) < AVG(invoice_customer_list_qty)  
        ) 
        AND (product_name LIKE ('%$search%') OR invoice_customer_code LIKE ('%$search%')) ";

        //echo $sql_customer;

        $data = [];
        if ($result = mysqli_query($this->db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }

   
    function updateBillingNoteByID($id,$data = []){
        $sql = " UPDATE tb_billing_note SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        invoice_customer_id = '".$data['invoice_customer_id']."', 
        billing_note_code = '".$data['billing_note_code']."', 
        billing_note_total_old = '".$data['billing_note_total_old']."', 
        billing_note_total = '".$data['billing_note_total']."', 
        billing_note_total_price = '".$data['billing_note_total_price']."', 
        billing_note_vat = '".$data['billing_note_vat']."', 
        billing_note_vat_price = '".$data['billing_note_vat_price']."', 
        billing_note_net_price = '".$data['billing_note_net_price']."', 
        billing_note_date = '".$data['billing_note_date']."', 
        billing_note_remark = '".$data['billing_note_remark']."', 
        billing_note_name = '".$data['billing_note_name']."', 
        billing_note_address = '".$data['billing_note_address']."', 
        billing_note_tax = '".$data['billing_note_tax']."', 
        billing_note_term = '".$data['billing_note_term']."', 
        billing_note_due = '".$data['billing_note_due']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE billing_note_id = $id 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertBillingNote($data = []){
        $sql = " INSERT INTO tb_billing_note (
            customer_id,
            employee_id,
            invoice_customer_id,
            billing_note_code,
            billing_note_total_old,
            billing_note_total,
            billing_note_total_price,
            billing_note_vat,
            billing_note_vat_price,
            billing_note_net_price,
            billing_note_date,
            billing_note_remark,
            billing_note_name,
            billing_note_address,
            billing_note_tax,
            billing_note_term,
            billing_note_due,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['invoice_customer_id']."','".
        $data['billing_note_code']."','".
        $data['billing_note_total_old']."','".
        $data['billing_note_total']."','".
        $data['billing_note_total_price']."','".
        $data['billing_note_vat']."','".
        $data['billing_note_vat_price']."','".
        $data['billing_note_net_price']."','".
        $data['billing_note_date']."','".
        $data['billing_note_remark']."','".
        $data['billing_note_name']."','".
        $data['billing_note_address']."','".
        $data['billing_note_tax']."','".
        $data['billing_note_term']."','".
        $data['billing_note_due']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";


        //echo $sql;
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }


    function deleteBillingNoteByID($id){

        $sql = " DELETE FROM tb_billing_note WHERE billing_note_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_billing_note_list WHERE billing_note_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }


}
?>