<?php

require_once("BaseModel.php");
class ExchangeRateBahtModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getExchangeRateBahtByDate($date_start = '', $date_end = ''){
        $sql = "SELECT * 
        FROM tb_exchange_rate_baht 
        LEFT JOIN tb_currency ON tb_exchange_rate_baht.currency_id = tb_currency.currency_id  
        WHERE   STR_TO_DATE(exchange_rate_baht_date,'%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('$date_start','%Y-%m-%d %H:%i:%s') 
        AND STR_TO_DATE(exchange_rate_baht_date,'%Y-%m-%d %H:%i:%s') <= STR_TO_DATE('$date_end','%Y-%m-%d %H:%i:%s') 
        ORDER BY STR_TO_DATE(exchange_rate_baht_date,'%Y-%m-%d %H:%i:%s')  
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


    function getExchangeRateBahtByID($id){
        $sql = " SELECT * 
        FROM tb_exchange_rate_baht 
        LEFT JOIN tb_currency ON tb_exchange_rate_baht.currency_id = tb_currency.currency_id  
        WHERE exchange_rate_baht_id = '$id' 
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

    function getExchangeRateBahtByCurrncyID($date,$currency_id){
        $sql = " SELECT * 
        FROM tb_exchange_rate_baht 
        LEFT JOIN tb_currency ON tb_exchange_rate_baht.currency_id = tb_currency.currency_id  
        WHERE tb_exchange_rate_baht.currency_id = '$currency_id' 
        AND STR_TO_DATE(tb_exchange_rate_baht.exchange_rate_baht_date,'%Y-%m-%d %H:%i:%s') = STR_TO_DATE('$date 00:00:00','%Y-%m-%d %H:%i:%s') 
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


    function updateExchangeRateBahtByID($id,$data = []){
        $sql = " UPDATE tb_exchange_rate_baht SET 
        currency_id = '".$data['currency_id']."',  
        exchange_rate_baht_date = '".$data['exchange_rate_baht_date']."', 
        exchange_rate_baht_value = '".$data['exchange_rate_baht_value']."' 
        WHERE exchange_rate_baht_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }


    }



    function insertExchangeRateBaht($data = []){
        $sql = " INSERT INTO tb_exchange_rate_baht (
            currency_id,
            exchange_rate_baht_date,
            exchange_rate_baht_value  
        ) VALUES (
            '".$data['currency_id']."', 
            '".$data['exchange_rate_baht_date']."', 
            '".$data['exchange_rate_baht_value']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }



    function deleteExchangeRateBahtByID($id){
        $sql = " DELETE FROM tb_exchange_rate_baht WHERE exchange_rate_baht_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    

}
?>