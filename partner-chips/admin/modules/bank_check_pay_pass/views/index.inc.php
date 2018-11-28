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


    $supplier_id = $_GET['supplier_id'];

    $suppliers=$supplier_model->getSupplierBy();
    $checks = $check_model->getCheckPayBy('',$date_start,$date_end,$supplier_id,$keyword,'',$lock_1,$lock_2);
    $cheque_journals = [];
    for($i=0; $i < count($checks); $i++){
        $cheque_journals[$checks[$i]['check_pay_id']] = $check_model->getJournalByChequePayID($checks[$i]['check_pay_id']);
    } 
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
    

    $supplier_id = $_GET['supplier_id'];

    $suppliers=$supplier_model->getSupplierBy();
    $checks = $check_model->getCheckPayBy('',$date_start,$date_end,$supplier_id,$keyword,'',$lock_1,$lock_2);
    $cheque_journals = [];
    for($i=0; $i < count($checks); $i++){
        $cheque_journals[$checks[$i]['check_pay_id']] = $check_model->getJournalByChequePayID($checks[$i]['check_pay_id']);
    } 
    require_once($path.'view.inc.php');

}





?>