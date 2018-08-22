<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/CheckModel.php'); 
require_once('../models/CustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/bank_check_in_pass/views/"; 
$customer_model = new CustomerModel; 
$check_model = new CheckModel;
$check_id = $_GET['id'];

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();
    $checks = $check_model->getCheckBy('',$date_start,$date_end,$customer_id,$keyword,'0','1');
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'pass'){
    $data = [];
    $data['check_status'] = '1';
    $data['check_date_pass'] = $_POST['check_date_pass'];
    $data['updateby'] = $admin_id;
    $check_model->updateCheckPassByID($check_id,$data);
?>
    <script>window.location="index.php?app=bank_check_in_pass"</script>
<?php

}else if ($_GET['action'] == 'unpass'){
    $data = [];
    $data['check_status'] = '0';
    $data['check_date_pass'] = '';
    $data['updateby'] = $admin_id;
    $check_model->updateCheckPassByID($check_id,$data);
?>
    <script>window.location="index.php?app=bank_check_in_pass"</script>
<?php

}else{
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();
    $checks = $check_model->getCheckBy('',$date_start,$date_end,$customer_id,$keyword,'0','1');
    require_once($path.'view.inc.php');

}





?>