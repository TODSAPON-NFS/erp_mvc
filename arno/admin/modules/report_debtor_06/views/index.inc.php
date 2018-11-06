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
$code_start = $_GET['code_start'];
$code_end = $_GET['code_end'];

if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}

$customer_id = $_GET['customer_id'];
$keyword = $_GET['keyword'];

$customers=$customer_model->getCustomerBy();

$debtor_reports = $debtor_report_model->getDebtorListReportBy($customer_id, $code_start, $code_end);

for($i = 0 ; $i < count($debtor_reports); $i++){
    //echo "<b>".$debtor_reports[$i]['customer_code']."</b><br>";
    $debtor_reports[$i]['debit_before'] = $debtor_report_model->getBeforeDebitReportBy($debtor_reports[$i]['customer_id'],$date_start);
    $debtor_reports[$i]['debit_invoice'] = $debtor_report_model->getInvoiceDebitReportBy($debtor_reports[$i]['customer_id'],$date_start,$date_end);
    $debtor_reports[$i]['debit_debit'] = $debtor_report_model->getDebitDebitReportBy($debtor_reports[$i]['customer_id'],$date_start,$date_end);
    $debtor_reports[$i]['debit_credit'] = $debtor_report_model->getCreditDebitReportBy($debtor_reports[$i]['customer_id'],$date_start,$date_end);
    $debtor_reports[$i]['debit_reciept'] = $debtor_report_model->getRecieveDebitReportBy($debtor_reports[$i]['customer_id'],$date_start,$date_end);
    $debtor_reports[$i]['debit_balance'] = $debtor_reports[$i]['debit_before'] +  $debtor_reports[$i]['debit_invoice'] + $debtor_reports[$i]['debit_debit'] - $debtor_reports[$i]['debit_credit'] - $debtor_reports[$i]['debit_reciept'];
}

require_once($path.'view.inc.php');


?>