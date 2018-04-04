<?php

require_once("BaseModel.php");
class DebitNoteModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getDebitNoteBy($date_start  = '', $date_end  = ''){
        $sql = " SELECT debit_note_id, 
        debit_note_code, 
        debit_note_date, 
        debit_note_total_old,
        debit_note_total,
        debit_note_total_price,
        debit_note_vat_price,
        debit_note_net_price,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        debit_note_term, 
        debit_note_due, 
        IFNULL(CONCAT(tb2.customer_name_en,' (',tb2.customer_name_th,')'),'-') as customer_name  
        FROM tb_debit_note 
        LEFT JOIN tb_user as tb1 ON tb_debit_note.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_debit_note.customer_id = tb2.customer_id 
        ORDER BY STR_TO_DATE(debit_note_date,'%Y-%m-%d %H:%i:%s') DESC 
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

    function getDebitNoteByID($id){
        $sql = " SELECT * 
        FROM tb_debit_note 
        LEFT JOIN tb_customer ON tb_debit_note.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_debit_note.employee_id = tb_user.user_id 
        WHERE debit_note_id = '$id' 
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

    function getDebitNoteViewByID($id){
        $sql = " SELECT *   
        FROM tb_debit_note 
        LEFT JOIN tb_invoice_customer ON tb_debit_note.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        LEFT JOIN tb_user ON tb_debit_note.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user.user_position_id 
        LEFT JOIN tb_customer ON tb_debit_note.customer_id = tb_customer.customer_id 
        WHERE debit_note_id = '$id' 
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

    function getDebitNoteLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(debit_note_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  debit_note_lastcode 
        FROM tb_debit_note
        WHERE debit_note_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['debit_note_lastcode'];
        }

    }


    function generateDebitNoteListByInvoiceCustomerId($invoice_customer_id, $data = [],$search=""){

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
            SELECT SUM(debit_note_list_qty) 
            FROM tb_debit_note_list 
            WHERE invoice_customer_list_id = tb2.invoice_customer_list_id 
        ),0) ,0) as debit_note_list_qty,  
        invoice_customer_list_price as debit_note_list_price, 
        invoice_customer_list_total as debit_note_list_total, 
        invoice_customer_list_product_name as debit_note_list_product_name, 
        invoice_customer_list_product_detail as debit_note_list_product_detail, 
        invoice_customer_list_remark as debit_note_list_remark 
        FROM tb_invoice_customer 
        LEFT JOIN tb_invoice_customer_list as tb2 ON tb_invoice_customer.invoice_customer_id = tb2.invoice_customer_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        WHERE tb_invoice_customer.invoice_customer_id = '$invoice_customer_id' 
        AND tb2.invoice_customer_list_id NOT IN ($str) 
        AND tb2.invoice_customer_list_id IN (
            SELECT tb_invoice_customer_list.invoice_customer_list_id 
            FROM tb_invoice_customer_list  
            LEFT JOIN tb_debit_note_list ON  tb_invoice_customer_list.invoice_customer_list_id = tb_debit_note_list.invoice_customer_list_id 
            GROUP BY tb_invoice_customer_list.invoice_customer_list_id 
            HAVING IFNULL(SUM(debit_note_list_qty),0) < AVG(invoice_customer_list_qty)  
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

   
    function updateDebitNoteByID($id,$data = []){
        $sql = " UPDATE tb_debit_note SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        invoice_customer_id = '".$data['invoice_customer_id']."', 
        debit_note_code = '".$data['debit_note_code']."', 
        debit_note_total_old = '".$data['debit_note_total_old']."', 
        debit_note_total = '".$data['debit_note_total']."', 
        debit_note_total_price = '".$data['debit_note_total_price']."', 
        debit_note_vat = '".$data['debit_note_vat']."', 
        debit_note_vat_price = '".$data['debit_note_vat_price']."', 
        debit_note_net_price = '".$data['debit_note_net_price']."', 
        debit_note_date = '".$data['debit_note_date']."', 
        debit_note_remark = '".$data['debit_note_remark']."', 
        debit_note_name = '".$data['debit_note_name']."', 
        debit_note_address = '".$data['debit_note_address']."', 
        debit_note_tax = '".$data['debit_note_tax']."', 
        debit_note_term = '".$data['debit_note_term']."', 
        debit_note_due = '".$data['debit_note_due']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE debit_note_id = $id 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertDebitNote($data = []){
        $sql = " INSERT INTO tb_debit_note (
            customer_id,
            employee_id,
            invoice_customer_id,
            debit_note_code,
            debit_note_total_old,
            debit_note_total,
            debit_note_total_price,
            debit_note_vat,
            debit_note_vat_price,
            debit_note_net_price,
            debit_note_date,
            debit_note_remark,
            debit_note_name,
            debit_note_address,
            debit_note_tax,
            debit_note_term,
            debit_note_due,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['invoice_customer_id']."','".
        $data['debit_note_code']."','".
        $data['debit_note_total_old']."','".
        $data['debit_note_total']."','".
        $data['debit_note_total_price']."','".
        $data['debit_note_vat']."','".
        $data['debit_note_vat_price']."','".
        $data['debit_note_net_price']."','".
        $data['debit_note_date']."','".
        $data['debit_note_remark']."','".
        $data['debit_note_name']."','".
        $data['debit_note_address']."','".
        $data['debit_note_tax']."','".
        $data['debit_note_term']."','".
        $data['debit_note_due']."','".
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


    function deleteDebitNoteByID($id){

        $sql = " DELETE FROM tb_debit_note WHERE debit_note_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_debit_note_list WHERE debit_note_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }


}
?>