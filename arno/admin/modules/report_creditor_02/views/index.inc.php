<?php
session_start();

require_once('../models/CreditorReportModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/InvoiceSupplierModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_creditor_02/views/";

$supplier_model = new SupplierModel;
$invoice_supplier_model = new InvoiceSupplierModel; 
$creditor_report_model = new CreditorReportModel; 



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

if(!isset($_GET['code_start'])){
    $code_start = $_SESSION['code_start'];
}else{
    $code_start = $_GET['code_start'];
    $_SESSION['code_start'] = $code_start;
}


if(!isset($_GET['code_end'])){
    $code_end = $_SESSION['code_end'];
}else{
    $code_end = $_GET['code_end'];
    $_SESSION['code_end'] = $code_end;
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

$tax_reports = $creditor_report_model->getInvoiceSupplierReportBy($date_start, $date_end, $code_start ,$code_end, $supplier_id, $keyword);

require_once($path.'view.inc.php');


?>