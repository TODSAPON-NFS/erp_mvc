<?php

require_once("BaseModel.php");
class FinanceCreditPayModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getFinanceCreditPayBy($finance_credit_id){
        $sql = " SELECT 
        finance_credit_pay_id, 
        finance_credit_pay_by,
        finance_credit_pay_date,
        finance_credit_pay_bank,
        finance_credit_pay_value,
        finance_credit_pay_balance,
        finance_credit_pay_total
        FROM tb_finance_credit_pay 
        WHERE finance_credit_id = '$finance_credit_id' 
        ORDER BY finance_credit_pay_id 
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


    function insertFinanceCreditPay($data = []){
        $sql = " INSERT INTO tb_finance_credit_pay (
            finance_credit_id, 
            finance_credit_pay_by,
            finance_credit_pay_date,
            finance_credit_pay_bank,
            finance_credit_pay_value,
            finance_credit_pay_balance,
            finance_credit_pay_total,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['finance_credit_id']."', 
            '".$data['finance_credit_pay_by']."', 
            '".$data['finance_credit_pay_date']."', 
            '".$data['finance_credit_pay_bank']."', 
            '".$data['finance_credit_pay_value']."', 
            '".$data['finance_credit_pay_balance']."', 
            '".$data['finance_credit_pay_total']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql."<br><br>";
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id($this->db);
        }else {
            return 0;
        }

    }

    function updateFinanceCreditPayById($data,$id){

        $sql = " UPDATE tb_finance_credit_pay 
            SET finance_credit_pay_by = '".$data['finance_credit_pay_by']."', 
            finance_credit_pay_date = '".$data['finance_credit_pay_date']."',
            finance_credit_pay_bank = '".$data['finance_credit_pay_bank']."',
            finance_credit_pay_value = '".$data['finance_credit_pay_value']."',
            finance_credit_pay_balance = '".$data['finance_credit_pay_balance']."',
            finance_credit_pay_total = '".$data['finance_credit_pay_total']."'     
            WHERE finance_credit_pay_id = '$id' 
        ";

        //echo $sql."<br><br>";
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteFinanceCreditPayByID($id){
        $sql = "DELETE FROM tb_finance_credit_pay WHERE finance_credit_pay_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteFinanceCreditPayByFinanceCreditID($id){
        $sql = "DELETE FROM tb_finance_credit_pay WHERE finance_credit_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteFinanceCreditPayByFinanceCreditPayIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                if($data[$i] != ""){
                    $str .= $data[$i];
                    if($i + 1 < count($data)){
                        $str .= ',';
                    }
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        if( $str==''){
            $str='0';
        }

        $sql = "DELETE FROM tb_finance_credit_pay WHERE finance_credit_id = '$id' AND finance_credit_pay_id NOT IN ($str) ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>