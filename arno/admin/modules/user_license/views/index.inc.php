<?php
require_once('../models/LicenseModel.php');
$path = "modules/user_license/views/";
$model_license = new LicenseModel;

if(!isset($_GET['action'])){

    $license = $model_license->getLicenseBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){

    $item_id = $_GET['id'];
    $license = $model_license->getLicenseByID($item_id);
    

    
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_admin_page == 'High')){

    $license = $model_license->deleteLicenseById($_GET['id']);
?>
    <script>window.location="index.php?app=employee_license"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    if(isset($_POST['license_name'])){
        $data = [];
        $data['license_name'] = $_POST['license_name'];
        $data['license_admin_page'] = $_POST['license_admin_page'];
        $data['license_sale_employee_page'] = $_POST['license_sale_employee_page'];
        $data['license_request_page'] = $_POST['license_request_page'];
        $data['license_delivery_note_page'] = $_POST['license_delivery_note_page'];
        $data['license_regrind_page'] = $_POST['license_regrind_page'];
        $data['license_purchase_page'] = $_POST['license_purchase_page'];
        $data['license_sale_page'] = $_POST['license_sale_page'];
        $data['license_sale_page'] = $_POST['license_sale_page'];
        $data['license_inventery_page'] = $_POST['license_inventery_page'];
        $data['license_account_page'] = $_POST['license_account_page'];
        $data['license_report_page'] = $_POST['license_report_page'];
        $data['license_manager_page'] = $_POST['license_manager_page'];
        
        $user = $model_license->insertLicense($data);

        if($user){
?>
        <script>window.location="index.php?app=employee_license"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=employee_license"</script>
<?php
        }     

    }else{
        ?>
    <script>window.location="index.php?app=employee_license"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    
    if(isset($_POST['license_name'])){
        $data = [];
        $data['license_name'] = $_POST['license_name'];
        $data['license_admin_page'] = $_POST['license_admin_page'];
        $data['license_sale_employee_page'] = $_POST['license_sale_employee_page'];
        $data['license_request_page'] = $_POST['license_request_page'];
        $data['license_delivery_note_page'] = $_POST['license_delivery_note_page'];
        $data['license_regrind_page'] = $_POST['license_regrind_page'];
        $data['license_purchase_page'] = $_POST['license_purchase_page'];
        $data['license_sale_page'] = $_POST['license_sale_page'];
        $data['license_sale_page'] = $_POST['license_sale_page'];
        $data['license_inventery_page'] = $_POST['license_inventery_page'];
        $data['license_account_page'] = $_POST['license_account_page'];
        $data['license_report_page'] = $_POST['license_report_page'];
        $data['license_manager_page'] = $_POST['license_manager_page'];
        
        $user = $model_license->updateLicenseByID($_POST['license_id'],$data);

        if($user){
?>
        <script>window.location="index.php?app=employee_license"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=employee_license"</script>
<?php
        }
        
    }else{
        ?>
    <script>window.location="index.php?app=employee_license"</script>
        <?php
    }
    
    
}else{

    $license = $model_license->getLicenseBy();
    require_once($path.'view.inc.php');

}





?>