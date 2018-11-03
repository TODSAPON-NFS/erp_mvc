<?php
session_start();

require_once('../models/DebtorReportModel.php');
require_once('../models/CustomerModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_debtor_06/views/";

$customer_model = new CustomerModel;

$debtor_report_model = new DebtorReportModel;



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

$debtor_reports = $debtor_report_model->getDebtorListReportBy($date_end, $customer_id, $keyword);

require_once($path.'view.inc.php');


?>