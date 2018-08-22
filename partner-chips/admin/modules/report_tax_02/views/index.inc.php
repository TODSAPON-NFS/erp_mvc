<?php
session_start();

require_once('../models/TaxReportModel.php');
require_once('../models/CustomerModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_tax_02/views/";

$customer_model = new CustomerModel;

$tax_report_model = new TaxReportModel;



$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];

if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}

$customer_id = $_GET['customer_id'];
$keyword = $_GET['keyword'];

$customers=$customer_model->getCustomerBy();

$tax_reports = $tax_report_model->getSaleTaxReportBy($date_start,$date_end,$customer_id,$keyword);

require_once($path.'view.inc.php');


?>