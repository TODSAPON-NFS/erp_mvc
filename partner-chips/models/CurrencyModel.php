<?php

require_once("BaseModel.php");
class CurrencyModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCurrencyBy($name_th = '',$name_en = ''){
        $sql = "SELECT * FROM tb_currency WHERE  (currency_name_th LIKE ('%$name_th%') OR currency_name_en LIKE ('%$name_en%')) 
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

    function getCurrencyByID($id){
        $sql = " SELECT * 
        FROM tb_currency 
        WHERE currency_id = '$id' 
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

    function updateCurrencyByID($id,$data = []){
        $sql = " UPDATE tb_currency SET 
        currency_name_en = '".$data['currency_name_en']."' ,  
        currency_name_th = '".$data['currency_name_th']."' ,  
        currency_sign = '".$data['currency_sign']."' ,  
        WHERE currency_id = $id 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertCurrency($data = []){
        $sql = " INSERT INTO tb_currency (
            currency_name_en , 
            currency_name_th , 
            currency_sign 
        ) VALUES (
            '".$data['currency_name_en']."', 
            '".$data['currency_name_th']."', 
            '".$data['currency_sign']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteCurrencyByID($id){
        $sql = " DELETE FROM tb_currency WHERE currency_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>