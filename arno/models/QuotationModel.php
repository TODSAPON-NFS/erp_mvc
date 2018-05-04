<?php

require_once("BaseModel.php");
class QuotationModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getQuotationBy(){
        $sql = " 
        SELECT quotation_id, 
        quotation_date, 
        quotation_code, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        quotation_total,
        IFNULL(CONCAT(tb2.customer_name_th,' (',tb2.customer_name_th,')' ) ,'-') as customer_name, 
        quotation_contact_name,
        quotation_cancelled,
        quotation_remark 
        FROM tb_quotation LEFT JOIN tb_user as tb1 ON tb_quotation.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_quotation.customer_id = tb2.customer_id 
        ORDER BY STR_TO_DATE(quotation_date,'%Y-%m-%d %H:%i:%s') DESC 
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

    function getQuotationByID($id){
        $sql = " SELECT * 
        FROM tb_quotation 
        WHERE quotation_id = '$id' 
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

    function getQuotationViewByID($id){
        $sql = " SELECT *   
        FROM tb_quotation 
        LEFT JOIN tb_user ON tb_quotation.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_quotation.customer_id = tb_customer.customer_id 
        WHERE quotation_id = '$id' 
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

    function getQuotationLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(quotation_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  quotation_lastcode 
        FROM tb_quotation 
        WHERE quotation_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['quotation_lastcode'];
        }

    }

   
    function updateQuotationByID($id,$data = []){
        $sql = " UPDATE tb_quotation SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        quotation_code = '".$data['quotation_code']."', 
        quotation_date = '".$data['quotation_date']."', 
        quotation_contact_name = '".$data['quotation_contact_name']."', 
        quotation_contact_tel = '".$data['quotation_contact_tel']."', 
        quotation_contact_email = '".$data['quotation_contact_email']."', 
        quotation_total = '".$data['quotation_total']."', 
        quotation_vat = '".$data['quotation_vat']."', 
        quotation_vat_price = '".$data['quotation_vat_price']."', 
        quotation_vat_net = '".$data['quotation_vat_net']."', 
        quotation_remark = '".$data['quotation_remark']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE quotation_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function cancelQuotationByID($id){
        $sql = " UPDATE tb_quotation SET 
        quotation_cancelled = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE quotation_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelQuotationByID($id){
        $sql = " UPDATE tb_quotation SET 
        quotation_cancelled = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE quotation_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    

    function insertQuotation($data = []){
        $sql = " INSERT INTO tb_quotation (
            quotation_rewrite_id,
            customer_id,
            employee_id,
            quotation_code,
            quotation_date,
            quotation_contact_name,
            quotation_contact_tel,
            quotation_contact_email,
            quotation_total,
            quotation_vat,
            quotation_vat_price,
            quotation_vat_net,
            quotation_remark,
            quotation_cancelled,
            addby,
            adddate
        ) VALUES ('".
        $data['quotation_rewrite_id']."','".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['quotation_code']."','".
        $data['quotation_date']."','".
        $data['quotation_contact_name']."','".
        $data['quotation_contact_tel']."','".
        $data['quotation_contact_email']."','".
        $data['quotation_total']."','".
        $data['quotation_vat']."','".
        $data['quotation_vat_price']."','".
        $data['quotation_vat_net']."','".
        $data['quotation_remark']."','".
        "0','".
        $data['addby']."',".
        "NOW()); 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }


    function deleteQuotationByID($id){
        $sql = " DELETE FROM tb_quotation WHERE quotation_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_quotation_list WHERE quotation_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>