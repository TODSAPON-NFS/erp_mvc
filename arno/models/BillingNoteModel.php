<?php

require_once("BaseModel.php");
class BillingNoteModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getBillingNoteBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(billing_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(billing_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(billing_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(billing_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        $sql = " SELECT billing_note_id, 
        billing_note_code, 
        billing_note_date, 
        billing_note_total,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        IFNULL(CONCAT(tb2.customer_name_en,' (',tb2.customer_name_th,')'),'-') as customer_name  
        FROM tb_billing_note 
        LEFT JOIN tb_user as tb1 ON tb_billing_note.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_billing_note.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  billing_note_code LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(billing_note_date,'%d-%m-%Y %H:%i:%s'),billing_note_code DESC 
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

    function getBillingNoteByID($id){
        $sql = " SELECT * 
        FROM tb_billing_note 
        LEFT JOIN tb_customer ON tb_billing_note.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_billing_note.employee_id = tb_user.user_id 
        WHERE billing_note_id = '$id' 
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

    function getBillingNoteViewByID($id){
        $sql = " SELECT *   
        FROM tb_billing_note 
        LEFT JOIN tb_user ON tb_billing_note.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user.user_position_id 
        LEFT JOIN tb_customer ON tb_billing_note.customer_id = tb_customer.customer_id 
        WHERE billing_note_id = '$id' 
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

    function getBillingNoteLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(billing_note_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  billing_note_lastcode 
        FROM tb_billing_note
        WHERE billing_note_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['billing_note_lastcode'];
        }

    }

    function getCustomerOrder(){

        $sql = "SELECT tb_customer.customer_id, customer_name_en , customer_name_th 
                FROM tb_customer 
                WHERE customer_id IN ( 
                    SELECT DISTINCT customer_id 
                    FROM tb_invoice_customer
                    WHERE invoice_customer_id NOT IN (
                        SELECT invoice_customer_id 
                        FROM tb_billing_note_list
                        GROUP BY invoice_customer_id 
                    ) 
                    GROUP BY customer_id 
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


    function generateBillingNoteListByCustomerId($customer_id, $data = [],$search=""){

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

        $sql_customer = "SELECT invoice_customer_id, 
        invoice_customer_code,
        '0' as billing_note_list_paid, 
        invoice_customer_net_price as billing_note_list_amount, 
        invoice_customer_date as billing_note_list_date, 
        invoice_customer_due as billing_note_list_due 
        FROM tb_invoice_customer 
        WHERE tb_invoice_customer.invoice_customer_id NOT IN ($str) 
        AND tb_invoice_customer.invoice_customer_id NOT IN (
            SELECT invoice_customer_id 
            FROM tb_billing_note_list 
            GROUP BY invoice_customer_id 
        ) 
        AND tb_invoice_customer.customer_id = '$customer_id' 
        ORDER BY  STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') ";

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

   
    function updateBillingNoteByID($id,$data = []){
        $sql = " UPDATE tb_billing_note SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        billing_note_code = '".$data['billing_note_code']."',
        billing_note_date = '".$data['billing_note_date']."', 
        billing_note_name = '".$data['billing_note_name']."', 
        billing_note_address = '".$data['billing_note_address']."', 
        billing_note_tax = '".$data['billing_note_tax']."', 
        billing_note_remark = '".$data['billing_note_remark']."', 
        billing_note_sent_name = '".$data['billing_note_sent_name']."', 
        billing_note_recieve_name = '".$data['billing_note_recieve_name']."', 
        billing_note_total = '".$data['billing_note_total']."', 
        billing_note_total_text = '".$data['billing_note_total_text']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE billing_note_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertBillingNote($data = []){
        $sql = " INSERT INTO tb_billing_note (
            customer_id,
            employee_id,
            billing_note_code,
            billing_note_date,
            billing_note_name,
            billing_note_address,
            billing_note_tax,
            billing_note_remark,
            billing_note_sent_name,
            billing_note_recieve_name,
            billing_note_total,
            billing_note_total_text,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['billing_note_code']."','".
        $data['billing_note_date']."','".
        $data['billing_note_name']."','".
        $data['billing_note_address']."','".
        $data['billing_note_tax']."','".
        $data['billing_note_remark']."','".
        $data['billing_note_sent_name']."','".
        $data['billing_note_recieve_name']."','".
        $data['billing_note_total']."','".
        $data['billing_note_total_text']."','".
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


    function deleteBillingNoteByID($id){

        $sql = " DELETE FROM tb_billing_note WHERE billing_note_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_billing_note_list WHERE billing_note_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>