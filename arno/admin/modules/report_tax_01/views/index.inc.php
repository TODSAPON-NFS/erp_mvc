<?php
session_start();

require_once('../models/TaxReportModel.php');
require_once('../models/SupplierModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_tax_01/views/";

$supplier_model = new SupplierModel;

$tax_report_model = new TaxReportModel;



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

$suppliers=$supplier_model->getSupplierBy();

$tax_reports = $tax_report_model->getPurchaseTaxReportBy($date_start,$date_end,$supplier_id,$keyword);
require_once($path.'view.inc.php');


?>