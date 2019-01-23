<?php
require_once('../models/AssetCategoryModel.php');

$path = "modules/asset_category/views/";
$model = new AssetCategoryModel;
$target_dir = "../upload/asset_category/";


if(!isset($_GET['action'])){

    $asset = $model->getAssetCategoryByAll();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){

    // $asset_category_license = $license->getLicenseBy();
    // $asset_category_position = $position->getAsset_CategoryPositionBy();
    // $asset_category_status = $status->getAsset_CategoryStatusBy();
    // $add_province = $address->getProvinceByID();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    $asset_category_id = $_GET['id'];
    $asset = $model->getAssetCategoryByID($asset_category_id);
    $code = $asset['asset_category_code'];
    // $asset_category_license = $license->getLicenseBy();
    // $asset_category_position = $position->getAsset_CategoryPositionBy();
    // $asset_category_status = $status->getAsset_CategoryStatusBy();
    // $add_province = $address->getProvinceByID();
    // $add_amphur = $address->getAmphurByProviceID($asset_category['asset_category_province']);
    // $add_district = $address->getDistricByAmphurID($asset_category['asset_category_amphur']);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_admin_page == 'High')){

    $asset = $model->deleteAssetCategoryById($_GET['id']);
?>
    <script>window.location="index.php?app=asset_category"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    if(isset($_POST['asset_category_code'])){
        $data = [];
        $data['asset_category_code'] = $_POST['asset_category_code'];
        $data['asset_category_name_th'] = $_POST['asset_category_name_th'];
        $data['asset_category_name_en'] = $_POST['asset_category_name_en'];

        $asset = $model->insertAssetCategory($data);

        if($asset != ""){
            // $img = $_POST['hidden_data'];
            // $data['asset_category_signature'] = $img;
            // $model->updateAsset_CategorySignatureByID($_POST['asset_category_id'],$data);
?>
        <script>window.location="index.php?app=asset_category&action=update&id=<?PHP echo $asset?>"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=asset_category"</script>
<?php
        }
    }else{
        ?>
    <script>window.location="index.php?app=asset_category"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    
    if(isset($_POST['asset_category_id'])){
        $data['asset_category_code'] = $_POST['asset_category_code'];
        $data['asset_category_name_th'] = $_POST['asset_category_name_th'];
        $data['asset_category_name_en'] = $_POST['asset_category_name_en'];

        $asset = $model->updateAssetCategoryByID($_POST['asset_category_id'],$data);

        if($asset){
            ?>
            <script>window.location="index.php?app=asset_category&action=update&id=<?PHP echo $_POST['asset_category_id'];?>"</script>
            <?php
        }else{
            ?>
                <script>window.location="index.php?app=asset_category"</script>
            <?php
        }
        
    }else{
        ?>
            <script>window.location="index.php?app=asset_category"</script>
        <?php
    }
        
        
    
}else{

    $asset = $model->getAssetCategoryBy();
    require_once($path.'view.inc.php');

}





?>