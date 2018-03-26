<?php

require_once("BaseModel.php");
class AccountTypeModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getAccountTypeBy($name = '',$code = ''){
        $sql = "SELECT * FROM tb_account_type WHERE  (account_type_name LIKE ('%$name%') OR account_type_code LIKE ('%$code%')) 
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

    function getAccountTypeByID($id){
        $sql = " SELECT * 
        FROM tb_account_type 
        WHERE account_type_id = '$id' 
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

    function updateAccountTypeByID($id,$data = []){
        $sql = " UPDATE tb_account_type SET 
        account_type_code = '".$data['account_type_code']."',
        account_type_name = '".$data['account_type_name']."'  
        WHERE account_type_id = $id 
        ";

        //echo $sql;

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertAccountType($data = []){
        $sql = " INSERT INTO tb_account_type (
            account_type_code , 
            account_type_name 
        ) VALUES (
            '".$data['account_type_code']."', 
            '".$data['account_type_name']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteAccountTypeByID($id){
        $sql = " DELETE FROM tb_account_type WHERE account_type_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>