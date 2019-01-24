<?php
require_once('../models/AssetModel.php');
require_once('../models/AssetAccountGroupModel.php');
require_once('../models/AssetCategoryModel.php');
require_once('../models/AssetDepartmentModel.php');

$path = "modules/asset/views/";
$model = new AssetModel;
$account_group_model = new AssetAccountGroupModel;
$category_model = new AssetCategoryModel;
$department_model = new AssetDepartmentModel;
$target_dir = "../upload/asset/";


if(!isset($_GET['action'])){

    $asset = $model->getAssetByAll();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    $account_group = $account_group_model->getAssetAccountGroupByAll();
    $category = $category_model->getAssetCategoryByAll();
    $department = $department_model->getAssetDepartmentByAll();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    $asset_id = $_GET['id'];
    $asset = $model->getAssetByID($asset_id);
    $account_group = $account_group_model->getAssetAccountGroupByAll();
    $category = $category_model->getAssetCategoryByAll();
    $department = $department_model->getAssetDepartmentByAll();
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_admin_page == 'High')){

    $asset = $model->deleteAssetById($_GET['id']);
?>
    <script>window.location="index.php?app=asset"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    if(isset($_POST['asset_code'])){
        $data = [];
        $data['asset_id'] = $_POST['asset_code'];
        $data['asset_code'] = $_POST['asset_code'];
        $data['asset_name_th'] = $_POST['asset_name_th'];
        $data['asset_name_en'] = $_POST['asset_name_en'];
        $data['asset_category_id'] = $_POST['asset_category_id'];
        $data['asset_registration_no'] = $_POST['asset_registration_no'];
        $data['asset_department_id'] = $_POST['asset_department_id'];
        $data['asset_depreciate'] = $_POST['asset_depreciate'];
        $data['asset_buy_date'] = $_POST['asset_buy_date'];
        $data['asset_use_date'] = $_POST['asset_use_date'];
        $data['asset_cost_price'] = $_POST['asset_cost_price'];
        $data['asset_expire'] = $_POST['asset_expire'];
        $data['asset_rate'] = $_POST['asset_rate'];
        $data['asset_depreciate_type'] = $_POST['asset_depreciate_type'];
        $data['asset_depreciate_transfer'] = $_POST['asset_depreciate_transfer'];
        $data['asset_depreciate_manual'] = $_POST['asset_depreciate_manual'];
        $data['asset_depreciate_initial'] = $_POST['asset_depreciate_initial'];
        $data['asset_manual_date'] = $_POST['asset_manual_date'];
        $data['asset_sale_date'] = $_POST['asset_sale_date'];
        $data['asset_price'] = $_POST['asset_price'];
        $data['asset_income'] = $_POST['asset_income'];
        $data['asset_remark'] = $_POST['asset_remark'];

        $asset = $model->insertAsset($data);

        if($asset != ""){
            // $img = $_POST['hidden_data'];
            // $data['asset_signature'] = $img;
            // $model->updateAssetSignatureByID($_POST['asset_id'],$data);
?>
        <script>window.location="index.php?app=asset&action=update&id=<?PHP echo $asset?>"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=asset"</script>
<?php
        }
    }else{
        ?>
    <script>window.location="index.php?app=asset"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    
    if(isset($_POST['asset_code'])){
        $data['asset_id'] = $_POST['asset_code'];
        $data['asset_code'] = $_POST['asset_code'];
        $data['asset_name_th'] = $_POST['asset_name_th'];
        $data['asset_name_en'] = $_POST['asset_name_en'];
        $data['asset_category_id'] = $_POST['asset_category_id'];
        $data['asset_registration_no'] = $_POST['asset_registration_no'];
        $data['asset_department_id'] = $_POST['asset_department_id'];
        $data['asset_depreciate'] = $_POST['asset_depreciate'];
        $data['asset_buy_date'] = $_POST['asset_buy_date'];
        $data['asset_use_date'] = $_POST['asset_use_date'];
        $data['asset_cost_price'] = $_POST['asset_cost_price'];
        $data['asset_expire'] = $_POST['asset_expire'];
        $data['asset_rate'] = $_POST['asset_rate'];
        $data['asset_depreciate_type'] = $_POST['asset_depreciate_type'];
        $data['asset_depreciate_transfer'] = $_POST['asset_depreciate_transfer'];
        $data['asset_depreciate_manual'] = $_POST['asset_depreciate_manual'];
        $data['asset_depreciate_initial'] = $_POST['asset_depreciate_initial'];
        $data['asset_manual_date'] = $_POST['asset_manual_date'];
        $data['asset_sale_date'] = $_POST['asset_sale_date'];
        $data['asset_price'] = $_POST['asset_price'];
        $data['asset_income'] = $_POST['asset_income'];
        $data['asset_remark'] = $_POST['asset_remark'];

        $asset = $model->updateAssetByID($_POST['asset_id'],$data);

        if($asset){
            // $img = $_POST['hidden_data'];
            // $data['asset_signature'] = $img;
            // $model->updateAssetSignatureByID($_POST['asset_id'],$data);
/*
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = $target_dir . $_POST['asset_id'] . ".png";
            $success = file_put_contents($file, $data);
*/
            ?>
                    <script>window.location="index.php?app=asset"</script>
            <?php
        }else{
            ?>
                    <script>window.location="index.php?app=asset"</script>
            <?php
        }
        
    }else{
        ?>
            <script>window.location="index.php?app=asset"</script>
        <?php
    }
        
        
    
}else{
    $asset = $model->getAssetBy($_GET['name'],$_GET['position'],$_GET['email']);
    require_once($path.'view.inc.php');

}





?>