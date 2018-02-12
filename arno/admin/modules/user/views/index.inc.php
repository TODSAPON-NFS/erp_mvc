<?php
require_once('../models/UserModel.php');
require_once('../models/UserPositionModel.php');
require_once('../models/UserStatusModel.php');
require_once('../models/LicenseModel.php');
require_once('../models/AddressModel.php');

$path = "modules/user/views/";
$model = new UserModel;
$position = new UserPositionModel;
$status = new UserStatusModel;
$license = new LicenseModel;
$address = new AddressModel;

if(!isset($_GET['action'])){

    $user = $model->getUserBy($_GET['name'],$_GET['position'],$_GET['email']);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){

    $user_license = $license->getLicenseBy();
    $user_position = $position->getUserPositionBy();
    $user_status = $status->getUserStatusBy();
    $add_province = $address->getProvinceByID();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $user_id = $_GET['id'];
    $user = $model->getUserByID($user_id);
    $user_license = $license->getLicenseBy();
    $user_position = $position->getUserPositionBy();
    $user_status = $status->getUserStatusBy();
    $add_province = $address->getProvinceByID();
    $add_amphur = $address->getAmphurByProviceID($user['user_province']);
    $add_district = $address->getDistricByAmphurID($user['user_amphur']);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'){

    $user = $model->deleteUserById($_GET['id']);
?>
    <script>window.location="index.php?app=employee"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['user_code'])){
        $data = [];
        $data['user_code'] = $_POST['user_code'];
        $data['user_prefix'] = $_POST['user_prefix'];
        $data['user_name'] = $_POST['user_name'];
        $data['user_lastname'] = $_POST['user_lastname'];
        $data['user_mobile'] = $_POST['user_mobile'];
        $data['user_email'] = $_POST['user_email'];
        $data['user_username'] = $_POST['user_username'];
        $data['user_password'] = $_POST['user_password'];
        $data['user_address'] = $_POST['user_address'];
        $data['user_province'] = $_POST['user_province'];
        $data['user_amphur'] = $_POST['user_amphur'];
        $data['user_district'] = $_POST['user_district'];
        $data['user_zipcode'] = $_POST['user_zipcode'];
        $data['user_position_id'] = $_POST['user_position_id'];
        $data['license_id'] = $_POST['license_id'];
        $data['user_status_id'] = $_POST['user_status_id'];

        $user = $model->insertUser($data);

        if($user){
?>
        <script>window.location="index.php?app=employee"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=employee"</script>
<?php
        }
    }else{
        ?>
    <script>window.location="index.php?app=employee"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['user_code'])){
        $data = [];
        $data['user_code'] = $_POST['user_code'];
        $data['user_prefix'] = $_POST['user_prefix'];
        $data['user_name'] = $_POST['user_name'];
        $data['user_lastname'] = $_POST['user_lastname'];
        $data['user_mobile'] = $_POST['user_mobile'];
        $data['user_email'] = $_POST['user_email'];
        $data['user_username'] = $_POST['user_username'];
        $data['user_password'] = $_POST['user_password'];
        $data['user_address'] = $_POST['user_address'];
        $data['user_province'] = $_POST['user_province'];
        $data['user_amphur'] = $_POST['user_amphur'];
        $data['user_district'] = $_POST['user_district'];
        $data['user_zipcode'] = $_POST['user_zipcode'];
        $data['user_position_id'] = $_POST['user_position_id'];
        $data['license_id'] = $_POST['license_id'];
        $data['user_status_id'] = $_POST['user_status_id'];

        $user = $model->updateUserByID($_POST['user_id'],$data);

        if($user){
?>
        <script>window.location="index.php?app=employee"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=employee"</script>
<?php
        }
        
    }else{
        ?>
    <script>window.location="index.php?app=employee"</script>
        <?php
    }
        
        
    
}else{

    $user = $model->getUserBy($_GET['name'],$_GET['position'],$_GET['email']);
    require_once($path.'view.inc.php');

}





?>