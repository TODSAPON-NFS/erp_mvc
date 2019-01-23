<?php
require_once('../models/AssetAccountGroupModel.php');

$path = "modules/asset_account_group/views/";
$model = new AssetAccountGroupModel;
$target_dir = "../upload/asset_account_group/";


if(!isset($_GET['action'])){

    $asset = $model->getAssetAccountGroupByAll();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){

    // $asset_account_group_license = $license->getLicenseBy();
    // $asset_account_group_position = $position->getAsset_AccountGroupPositionBy();
    // $asset_account_group_status = $status->getAsset_AccountGroupStatusBy();
    // $add_province = $address->getProvinceByID();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    $asset_account_group_id = $_GET['id'];
    $asset = $model->getAssetAccountGroupByID($asset_account_group_id);
    // $asset_account_group_license = $license->getLicenseBy();
    // $asset_account_group_position = $position->getAsset_AccountGroupPositionBy();
    // $asset_account_group_status = $status->getAsset_AccountGroupStatusBy();
    // $add_province = $address->getProvinceByID();
    // $add_amphur = $address->getAmphurByProviceID($asset_account_group['asset_account_group_province']);
    // $add_district = $address->getDistricByAmphurID($asset_account_group['asset_account_group_amphur']);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_admin_page == 'High')){

    $asset = $model->deleteAssetAccountGroupById($_GET['id']);
?>
    <script>window.location="index.php?app=asset_account_group"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    if(isset($_POST['asset_account_group_code'])){
        $data = [];
        $data['asset_account_group_code'] = $_POST['asset_account_group_code'];
        $data['asset_account_group_name_th'] = $_POST['asset_account_group_name_th'];
        $data['asset_account_group_name_en'] = $_POST['asset_account_group_name_en'];

        $asset = $model->insertAssetAccountGroup($data);

        if($asset != ""){
            // $img = $_POST['hidden_data'];
            // $data['asset_account_group_signature'] = $img;
            // $model->updateAsset_AccountGroupSignatureByID($_POST['asset_account_group_id'],$data);
?>
        <script>window.location="index.php?app=asset_account_group&action=update&id=<?PHP echo $asset?>"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=asset_account_group"</script>
<?php
        }
    }else{
        ?>
    <script>window.location="index.php?app=asset_account_group"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    
    if(isset($_POST['asset_account_group_code'])){
        $data['asset_account_group_code'] = $_POST['asset_account_group_code'];
        $data['asset_account_group_name_th'] = $_POST['asset_account_group_name_th'];
        $data['asset_account_group_name_en'] = $_POST['asset_account_group_name_en'];

        $asset = $model->updateAssetAccountGroupByID($_POST['asset_account_group_id'],$data);

        if($asset){
            ?>
                    <script>window.location="index.php?app=asset_account_group"</script>
            <?php
        }else{
            ?>
                    <script>window.location="index.php?app=asset_account_group"</script>
            <?php
        }
        
    }else{
        ?>
         <script>window.location="index.php?app=asset_account_group"</script>
        <?php
    }
        
        
    
}else{

    $asset = $model->getAssetAccountGroupBy();
    require_once($path.'view.inc.php');

}





?>