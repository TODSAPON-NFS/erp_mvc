<?php
require_once('../models/AssetModel.php');

$path = "modules/asset/views/";
$model = new AssetModel;
$target_dir = "../upload/asset/";


if(!isset($_GET['action'])){

    $asset = $model->getAssetByAll();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){

    // $asset_license = $license->getLicenseBy();
    // $asset_position = $position->getAssetPositionBy();
    // $asset_status = $status->getAssetStatusBy();
    // $add_province = $address->getProvinceByID();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    $asset_id = $_GET['id'];
    $asset = $model->getAssetByID($asset_id);
    $asset_license = $license->getLicenseBy();
    $asset_position = $position->getAssetPositionBy();
    $asset_status = $status->getAssetStatusBy();
    $add_province = $address->getProvinceByID();
    $add_amphur = $address->getAmphurByProviceID($asset['asset_province']);
    $add_district = $address->getDistricByAmphurID($asset['asset_amphur']);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_admin_page == 'High')){

    $asset = $model->deleteAssetById($_GET['id']);
?>
    <script>window.location="index.php?app=employee"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    if(isset($_POST['asset_code'])){
        $data = [];
        $data['asset_id'] = $_POST['asset_code'];
        $data['asset_code'] = $_POST['asset_code'];
        $data['asset_prefix'] = $_POST['asset_prefix'];
        $data['asset_name'] = $_POST['asset_name'];
        $data['asset_lastname'] = $_POST['asset_lastname'];
        $data['asset_mobile'] = $_POST['asset_mobile'];
        $data['asset_email'] = $_POST['asset_email'];
        $data['asset_assetname'] = $_POST['asset_assetname'];
        $data['asset_password'] = $_POST['asset_password'];
        $data['asset_address'] = $_POST['asset_address'];
        $data['asset_province'] = $_POST['asset_province'];
        $data['asset_amphur'] = $_POST['asset_amphur'];
        $data['asset_district'] = $_POST['asset_district'];
        $data['asset_zipcode'] = $_POST['asset_zipcode'];
        $data['asset_position_id'] = $_POST['asset_position_id'];
        $data['license_id'] = $_POST['license_id'];
        $data['asset_status_id'] = $_POST['asset_status_id'];

        $asset = $model->insertAsset($data);

        if($asset != ""){
            $img = $_POST['hidden_data'];
            $data['asset_signature'] = $img;
            $model->updateAssetSignatureByID($_POST['asset_id'],$data);
?>
        <script>window.location="index.php?app=employee&action=update&id=<?PHP echo $asset?>"</script>
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
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    
    if(isset($_POST['asset_code'])){
        $data = [];
        $data['asset_id'] = $_POST['asset_code'];
        $data['asset_code'] = $_POST['asset_code'];
        $data['asset_prefix'] = $_POST['asset_prefix'];
        $data['asset_name'] = $_POST['asset_name'];
        $data['asset_lastname'] = $_POST['asset_lastname'];
        $data['asset_mobile'] = $_POST['asset_mobile'];
        $data['asset_email'] = $_POST['asset_email'];
        $data['asset_assetname'] = $_POST['asset_assetname'];
        $data['asset_password'] = $_POST['asset_password'];
        $data['asset_address'] = $_POST['asset_address'];
        $data['asset_province'] = $_POST['asset_province'];
        $data['asset_amphur'] = $_POST['asset_amphur'];
        $data['asset_district'] = $_POST['asset_district'];
        $data['asset_zipcode'] = $_POST['asset_zipcode'];
        $data['asset_position_id'] = $_POST['asset_position_id'];
        $data['license_id'] = $_POST['license_id'];
        $data['asset_status_id'] = $_POST['asset_status_id'];

        $asset = $model->updateAssetByID($_POST['asset_id'],$data);

        if($asset){
            $img = $_POST['hidden_data'];
            $data['asset_signature'] = $img;
            $model->updateAssetSignatureByID($_POST['asset_id'],$data);
/*
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = $target_dir . $_POST['asset_id'] . ".png";
            $success = file_put_contents($file, $data);
*/
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

    $asset = $model->getAssetBy($_GET['name'],$_GET['position'],$_GET['email']);
    require_once($path.'view.inc.php');

}





?>