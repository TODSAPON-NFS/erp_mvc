<?php

require_once("BaseModel.php");
class LicenseModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getLicenseBy($name = ''){
        $sql = "SELECT * FROM tb_license WHERE  license_name LIKE ('%$name%') 
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

    function getLicenseByID($id){
        $sql = " SELECT * 
        FROM tb_license 
        WHERE license_id = '$id' 
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

    function updateLicenseByID($id,$data = []){
        $sql = " UPDATE tb_license SET 
        license_name = '".$data['license_name']."', 
        license_sale_page = '".$data['license_sale_page']."', 
        license_purchase_page = '".$data['license_purchase_page']."', 
        license_manager_page = '".$data['license_manager_page']."', 
        license_inventery_page = '".$data['license_inventery_page']."', 
        license_account_page = '".$data['license_account_page']."', 
        license_report_page = '".$data['license_report_page']."'  
        WHERE license_id = $id 
        ";

        //echo $sql;

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertLicense($data = []){
        $sql = " INSERT INTO tb_license (
            license_name,
            license_sale_page,
            license_purchase_page,
            license_manager_page,
            license_inventery_page,
            license_account_page,
            license_report_page
        ) VALUES (
            '".$data['license_name']."', 
            '".$data['license_sale_page']."', 
            '".$data['license_purchase_page']."', 
            '".$data['license_manager_page']."', 
            '".$data['license_inventery_page']."', 
            '".$data['license_account_page']."', 
            '".$data['license_report_page']."'
        ); 
        ";

        //echo $sql;
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteLicenseByID($id){
        $sql = " DELETE FROM tb_license WHERE license_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>