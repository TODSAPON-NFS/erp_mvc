<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/CheckModel.php'); 
require_once('../models/CustomerModel.php');
require_once('../models/BankAccountModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/bank_check_in_deposit/views/"; 
$customer_model = new CustomerModel; 
$check_model = new CheckModel;
$account_model = new BankAccountModel;
$check_id = $_GET['id'];

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();
    $checks = $check_model->getCheckBy('',$date_start,$date_end,$customer_id,$keyword,'0','');
    $accounts=$account_model->getBankAccountBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'deposit'){
    $data = [];
    $data['bank_deposit_id'] = $_GET['bank_deposit_id'];
    $data['check_fee'] = $_GET['check_fee'];
    $data['check_date_deposit'] = $_GET['check_date_deposit'];
    $data['updateby'] = $admin_id;
    $check_model->updateCheckDepositByID($check_id,$data);
?>
    <script>window.location="index.php?app=bank_check_in_deposit"</script>
<?php

}else if ($_GET['action'] == 'undeposit'){
    $data = [];
    $data['bank_deposit_id'] = '0';
    $data['check_fee'] = '0';
    $data['check_date_deposit'] = '';
    $data['updateby'] = $admin_id;
    $check_model->updateCheckDepositByID($check_id,$data);
?>
    <script>window.location="index.php?app=bank_check_in_deposit"</script>
<?php

}else{
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();
    $checks = $check_model->getCheckBy('',$date_start,$date_end,$customer_id,$keyword,'0','');
    $accounts=$account_model->getBankAccountBy();
    require_once($path.'view.inc.php');

}





?>