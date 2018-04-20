<?php

require_once("BaseModel.php");
class StockIssueModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getStockIssueBy($date_start  = '', $date_end  = ''){
        $sql = " SELECT stock_issue_id, 
        tb_stock_issue.invoice_customer_id,
        tb_stock_issue.stock_group_id,  
        invoice_customer_code,
        stock_issue_code, 
        stock_issue_date, 
        tb1.stock_group_name, 
        stock_issue_remark, 
        stock_issue_total, 
        invoice_customer_total_price,
        IFNULL(CONCAT(user_name,' ',user_lastname),'-') as employee_name 
        FROM tb_stock_issue 
        LEFT JOIN tb_user ON tb_stock_issue.employee_id = tb_user.user_id 
        LEFT JOIN tb_invoice_customer ON tb_stock_issue.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        LEFT JOIN tb_stock_group as tb1 ON tb_stock_issue.stock_group_id = tb1.stock_group_id 
        ORDER BY STR_TO_DATE(stock_issue_date,'%Y-%m-%d %H:%i:%s') DESC 
         ";

        //echo $sql ;
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getStockIssueByID($id){
        $sql = " SELECT tb_stock_issue.stock_issue_id, 
        tb_stock_issue.invoice_customer_id,
        tb_stock_issue.stock_group_id, 
        tb_stock_issue.employee_id,  
        invoice_customer_code,
        tb_stock_issue.stock_issue_code, 
        tb_stock_issue.stock_issue_date, 
        tb1.stock_group_name, 
        tb_stock_issue.stock_issue_remark, 
        stock_issue_total,
        invoice_customer_name, 
        invoice_customer_address,
        invoice_customer_tax,
        invoice_customer_total_price,
        IFNULL(CONCAT(tb_user.user_name,' ',tb_user.user_lastname),'-') as employee_name,
        IFNULL(CONCAT(tb.user_name,' ',tb.user_lastname),'-') as invoice_employee_name  
        FROM tb_stock_issue 
        LEFT JOIN tb_user ON tb_stock_issue.employee_id = tb_user.user_id 
        LEFT JOIN tb_invoice_customer ON tb_stock_issue.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        LEFT JOIN tb_user as tb ON tb_invoice_customer.employee_id = tb.user_id 
        LEFT JOIN tb_stock_group as tb1 ON tb_stock_issue.stock_group_id = tb1.stock_group_id 
        WHERE stock_issue_id = '$id' 
        ";

        //echo $sql;

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getStockIssueViewByID($id){
        $sql = " SELECT tb_stock_issue.stock_issue_id, 
        tb_stock_issue.invoice_customer_id,
        tb_stock_issue.stock_group_id, 
        tb_stock_issue.employee_id,  
        invoice_customer_code,
        tb_stock_issue.stock_issue_code, 
        tb_stock_issue.stock_issue_date, 
        tb1.stock_group_name, 
        tb_stock_issue.stock_issue_remark, 
        stock_issue_total,
        invoice_customer_name, 
        invoice_customer_address,
        invoice_customer_tax,
        invoice_customer_total_price,
        IFNULL(CONCAT(tb_user.user_name,' ',tb_user.user_lastname),'-') as employee_name,
        IFNULL(CONCAT(tb.user_name,' ',tb.user_lastname),'-') as invoice_employee_name  
        FROM tb_stock_issue 
        LEFT JOIN tb_user ON tb_stock_issue.employee_id = tb_user.user_id 
        LEFT JOIN tb_invoice_customer ON tb_stock_issue.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        LEFT JOIN tb_user as tb ON tb_invoice_customer.employee_id = tb.user_id 
        LEFT JOIN tb_stock_group as tb1 ON tb_stock_issue.stock_group_id = tb1.stock_group_id 
        WHERE stock_issue_id = '$id' 
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

    function getStockIssueLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(stock_issue_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  stock_issue_lastcode 
        FROM tb_stock_issue 
        WHERE stock_issue_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['stock_issue_lastcode'];
        }

    }

   
    function updateStockIssueByID($id,$data = []){
        $sql = " UPDATE tb_stock_issue SET 
        invoice_customer_id = '".$data['invoice_customer_id']."', 
        stock_group_id = '".$data['stock_group_id']."', 
        employee_id = '".$data['employee_id']."', 
        stock_issue_code = '".$data['stock_issue_code']."', 
        stock_issue_date = '".$data['stock_issue_date']."', 
        stock_issue_remark = '".$data['stock_issue_remark']."', 
        stock_issue_total = '".$data['stock_issue_total']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE stock_issue_id = $id 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertStockIssue($data = []){
        $sql = " INSERT INTO tb_stock_issue (
            invoice_customer_id, 
            stock_group_id, 
            employee_id,
            stock_issue_code,
            stock_issue_date,
            stock_issue_remark,
            stock_issue_total,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['invoice_customer_id']."','".
        $data['stock_group_id']."','".
        $data['employee_id']."','".
        $data['stock_issue_code']."','".
        $data['stock_issue_date']."','".
        $data['stock_issue_remark']."','".
        $data['stock_issue_total']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }



    function deleteStockIssueByID($id){

        $sql = "SELECT stock_issue_list_id,  stock_group_id
                FROM  tb_stock_issue 
                LEFT JOIN tb_stock_issue_list ON tb_stock_issue.stock_issue_id = tb_stock_issue_list.stock_issue_id
                WHERE tb_stock_issue_list.stock_issue_id = '$id' ";   
                     
         $sql_delete=[];
         if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
             while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                 $sql_delete [] = "
                    CALL delete_stock_issue('".
                    $row['stock_group_id']."','".
                    $row['stock_issue_list_id']."');
                 ";
                
             }
             $result->close();
         }
 
         for($i = 0 ; $i < count($sql_delete); $i++){
             mysqli_query($this->db,$sql_delete[$i], MYSQLI_USE_RESULT);
             //echo $sql_delete[$i]."<br><br>";
         }
 

        $sql = " DELETE FROM tb_stock_issue_list WHERE stock_issue_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_stock_issue WHERE stock_issue_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }


}
?>