<?php
session_start();

require_once('../models/CreditorReportModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/InvoiceSupplierModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_creditor_04/views/";

$supplier_model = new SupplierModel;
$invoice_supplier_model = new InvoiceSupplierModel; 
$creditor_report_model = new CreditorReportModel; 


  

$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];

if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}

$supplier_id = $_GET['supplier_id'];
$keyword = $_GET['keyword'];
$code_start = $_GET['code_start'];
$code_end = $_GET['code_end'];

$suppliers=$supplier_model->getSupplierBy();

$tax_reports = $creditor_report_model->getInvoiceSupplierReportBy($date_start, $date_end, $code_start ,$code_end, $supplier_id, $keyword);

require_once($path.'view.inc.php');


?>