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

    $customer_id = $_GET['customer_id'];

    $customers=$customer_model->getCustomerBy();
    $checks = $check_model->getCheckBy('',$date_start,$date_end,$customer_id,$keyword,'','1');
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

    $customer_id = $_GET['customer_id'];

    $customers=$customer_model->getCustomerBy();
    $checks = $check_model->getCheckBy('',$date_start,$date_end,$customer_id,$keyword,'','1');
    require_once($path.'view.inc.php');

}





?>