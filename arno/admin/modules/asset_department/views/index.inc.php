<?php
require_once('../models/AssetDepartmentModel.php');

$path = "modules/asset_department/views/";
$model = new AssetDepartmentModel;
$target_dir = "../upload/asset_department/";


if(!isset($_GET['action'])){

    $asset = $model->getAssetDepartmentByAll();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){

    // $asset_department_license = $license->getLicenseBy();
    // $asset_department_position = $position->getAsset_DepartmentPositionBy();
    // $asset_department_status = $status->getAsset_DepartmentStatusBy();
    // $add_province = $address->getProvinceByID();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    $asset_department_id = $_GET['id'];
    $asset = $model->getAssetDepartmentByID($asset_department_id);
    // $asset_department_license = $license->getLicenseBy();
    // $asset_department_position = $position->getAsset_DepartmentPositionBy();
    // $asset_department_status = $status->getAsset_DepartmentStatusBy();
    // $add_province = $address->getProvinceByID();
    // $add_amphur = $address->getAmphurByProviceID($asset_department['asset_department_province']);
    // $add_district = $address->getDistricByAmphurID($asset_department['asset_department_amphur']);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_admin_page == 'High')){

    $asset = $model->deleteAssetDepartmentById($_GET['id']);
?>
    <script>window.location="index.php?app=asset_department"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    if(isset($_POST['asset_department_code'])){
        $data = [];
        $data['asset_department_code'] = $_POST['asset_department_code'];
        $data['asset_department_name_th'] = $_POST['asset_department_name_th'];
        $data['asset_department_name_en'] = $_POST['asset_department_name_en'];

        $asset = $model->insertAssetDepartment($data);

        if($asset != ""){
            // $img = $_POST['hidden_data'];
            // $data['asset_department_signature'] = $img;
            // $model->updateAsset_DepartmentSignatureByID($_POST['asset_department_id'],$data);
?>
        <script>window.location="index.php?app=asset_department&action=update&id=<?PHP echo $asset?>"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=asset_department"</script>
<?php
        }
    }else{
        ?>
    <script>window.location="index.php?app=asset_department"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    
    if(isset($_POST['asset_department_code'])){
        $data['asset_department_code'] = $_POST['asset_department_code'];
        $data['asset_department_name_th'] = $_POST['asset_department_name_th'];
        $data['asset_department_name_en'] = $_POST['asset_department_name_en'];

        $asset = $model->updateAssetDepartmentByID($_POST['asset_department_id'],$data);

        if($asset){
            ?>
                    <script>window.location="index.php?app=asset_department"</script>
            <?php
        }else{
            ?>
                    <script>window.location="index.php?app=asset_department"</script>
            <?php
        }
        
    }else{
        ?>
         <script>window.location="index.php?app=asset_department"</script>
        <?php
    }
        
        
    
}else{

    $asset = $model->getAssetDepartmentBy();
    require_once($path.'view.inc.php');

}





?>