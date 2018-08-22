<?php

require_once("BaseModel.php");
class FinanceDebitPayModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getFinanceDebitPayBy($finance_debit_id){
        $sql = " SELECT 
        finance_debit_pay_id, 
        finance_debit_pay_by,
        finance_debit_pay_date,
        finance_debit_pay_bank,
        finance_debit_pay_value,
        finance_debit_pay_balance,
        finance_debit_pay_total
        FROM tb_finance_debit_pay 
        WHERE finance_debit_id = '$finance_debit_id' 
        ORDER BY finance_debit_pay_id 
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


    function insertFinanceDebitPay($data = []){
        $sql = " INSERT INTO tb_finance_debit_pay (
            finance_debit_id, 
            finance_debit_pay_by,
            finance_debit_pay_date,
            finance_debit_pay_bank,
            finance_debit_pay_value,
            finance_debit_pay_balance,
            finance_debit_pay_total,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['finance_debit_id']."', 
            '".$data['finance_debit_pay_by']."', 
            '".$data['finance_debit_pay_date']."', 
            '".$data['finance_debit_pay_bank']."', 
            '".$data['finance_debit_pay_value']."', 
            '".$data['finance_debit_pay_balance']."', 
            '".$data['finance_debit_pay_total']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql."<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateFinanceDebitPayById($data,$id){

        $sql = " UPDATE tb_finance_debit_pay 
            SET finance_debit_pay_by = '".$data['finance_debit_pay_by']."', 
            finance_debit_pay_date = '".$data['finance_debit_pay_date']."',
            finance_debit_pay_bank = '".$data['finance_debit_pay_bank']."',
            finance_debit_pay_value = '".$data['finance_debit_pay_value']."',
            finance_debit_pay_balance = '".$data['finance_debit_pay_balance']."',
            finance_debit_pay_total = '".$data['finance_debit_pay_total']."'     
            WHERE finance_debit_pay_id = '$id' 
        ";

        //echo $sql."<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteFinanceDebitPayByID($id){
        $sql = "DELETE FROM tb_finance_debit_pay WHERE finance_debit_pay_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteFinanceDebitPayByFinanceDebitID($id){
        $sql = "DELETE FROM tb_finance_debit_pay WHERE finance_debit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteFinanceDebitPayByFinanceDebitPayIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_finance_debit_pay WHERE finance_debit_id = '$id' AND finance_debit_pay_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>