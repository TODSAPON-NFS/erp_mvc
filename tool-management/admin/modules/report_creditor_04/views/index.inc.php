<?php
session_start();

require_once('../models/CreditorReportModel.php');
require_once('../models/SupplierModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_creditor_04/views/";

$supplier_model = new SupplierModel;

$credit_report_model = new CreditorReportModel;


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

$credit_reports = $credit_report_model->getFinanceCreditReportBy($date_start,$date_end,$supplier_id,$keyword);

require_once($path.'view.inc.php');


?>