<?php
session_start();

require_once('../models/DebtorReportModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/UserModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_debtor_10/views/";

$customer_model = new CustomerModel;
$user_model = new UserModel;

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
$employee_id = $_GET['employee_id'];
$keyword = $_GET['keyword'];

$customers=$customer_model->getCustomerBy();
$employees=$user_model->getUserBy();

$debtor_reports = $debtor_report_model->getQuotationDebtorReportBy($date_start,$date_end,$customer_id,$keyword,$employee_id);

require_once($path.'view.inc.php');


?>