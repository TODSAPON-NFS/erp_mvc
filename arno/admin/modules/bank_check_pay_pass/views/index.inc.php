<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/CheckPayModel.php'); 
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/bank_check_pay_pass/views/"; 
$supplier_model = new SupplierModel; 
$check_model = new CheckPayModel;
$check_pay_id = $_GET['id'];

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();
    $checks = $check_model->getCheckPayBy('',$date_start,$date_end,$supplier_id,$keyword,'0');
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'pass'){
    $data = [];
    $data['check_pay_status'] = '1';
    $data['check_pay_date_pass'] = $_POST['check_pay_date_pass'];
    $data['updateby'] = $admin_id;
    $check_model->updateCheckPayPassByID($check_pay_id,$data);
?>
    <script>window.location="index.php?app=bank_check_pay_pass"</script>
<?php

}else if ($_GET['action'] == 'unpass'){
    $data = [];
    $data['check_pay_status'] = '0';
    $data['check_pay_date_pass'] = '';
    $data['updateby'] = $admin_id;
    $check_model->updateCheckPayPassByID($check_pay_id,$data);
?>
    <script>window.location="index.php?app=bank_check_pay_pass"</script>
<?php

}else{
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();
    $checks = $check_model->getCheckPayBy('',$date_start,$date_end,$supplier_id,$keyword,'0');
    require_once($path.'view.inc.php');

}





?>