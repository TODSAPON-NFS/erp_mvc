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

if($license_account_page == "Medium" || $license_account_page == "High"){
    $lock_1 = "1";
}else{
    $lock_1 = "0";
}

if($license_account_page == "Medium" || $license_account_page == "High"){
    $lock_2 = "1";
}else{
    $lock_2 = "0";
}

if(!isset($_GET['action'])){
    if(!isset($_GET['date_start'])){
        $date_start = $_SESSION['date_start'];
    }else{
        $date_start = $_GET['date_start'];
        $_SESSION['date_start'] = $date_start;
    }


    if(!isset($_GET['date_end'])){
        $date_end = $_SESSION['date_end'];
    }else{
        $date_end = $_GET['date_end'];
        $_SESSION['date_end'] = $date_end;
    }

    if(!isset($_GET['keyword'])){
        $keyword = $_SESSION['keyword'];
    }else{
        
        $keyword = $_GET['keyword']; 
        $_SESSION['keyword'] = $keyword;
    }

    
    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    }


    $customer_id = $_GET['customer_id'];
    $customers=$customer_model->getCustomerBy();
    $checks = $check_model->getCheckBy('',$date_start,$date_end,$customer_id,$keyword,'0','',$lock_1,$lock_2);
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
    if(!isset($_GET['date_start'])){
        $date_start = $_SESSION['date_start'];
    }else{
        $date_start = $_GET['date_start'];
        $_SESSION['date_start'] = $date_start;
    }


    if(!isset($_GET['date_end'])){
        $date_end = $_SESSION['date_end'];
    }else{
        $date_end = $_GET['date_end'];
        $_SESSION['date_end'] = $date_end;
    }

    if(!isset($_GET['keyword'])){
        $keyword = $_SESSION['keyword'];
    }else{
        
        $keyword = $_GET['keyword']; 
        $_SESSION['keyword'] = $keyword;
    }

    
    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    }

    $customer_id = $_GET['customer_id'];
    $customers=$customer_model->getCustomerBy();
    $checks = $check_model->getCheckBy('',$date_start,$date_end,$customer_id,$keyword,'0','',$lock_1,$lock_2);
    $accounts=$account_model->getBankAccountBy();
    require_once($path.'view.inc.php');

}





?>