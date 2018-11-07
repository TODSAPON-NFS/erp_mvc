
<?php
session_start();

require_once('../models/CreditorReportModel.php');
require_once('../models/SupplierModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_creditor_06/views/";

$supplier_model = new SupplierModel;

$creditor_report_model = new CreditorReportModel;



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

$supplier_id = $_GET['supplier_id'];
$keyword = $_GET['keyword'];

$suppliers=$supplier_model->getSupplierBy();

$creditor_reports = $creditor_report_model->getCreditorListReportBy($supplier_id, $code_start, $code_end);

for($i = 0 ; $i < count($creditor_reports); $i++){
    //echo "<b>".$creditor_reports[$i]['supplier_code']."</b><br>";
    $creditor_reports[$i]['credit_before'] = $creditor_report_model->getBeforeCreditReportBy($creditor_reports[$i]['supplier_id'],$date_start);
    $creditor_reports[$i]['credit_invoice'] = $creditor_report_model->getInvoiCecreditReportBy($creditor_reports[$i]['supplier_id'],$date_start,$date_end);
    $creditor_reports[$i]['credit_debit'] = $creditor_report_model->getCreditDebitReportBy($creditor_reports[$i]['supplier_id'],$date_start,$date_end);
    $creditor_reports[$i]['credit_credit'] = $creditor_report_model->getCreditCreditReportBy($creditor_reports[$i]['supplier_id'],$date_start,$date_end);
    $creditor_reports[$i]['credit_payment'] = $creditor_report_model->getPaymentCreditReportBy($creditor_reports[$i]['supplier_id'],$date_start,$date_end);
    $creditor_reports[$i]['credit_balance'] = $creditor_reports[$i]['credit_before'] +  $creditor_reports[$i]['credit_invoice'] + $creditor_reports[$i]['credit_credit'] - $creditor_reports[$i]['credit_credit'] - $creditor_reports[$i]['credit_payment'];
}

require_once($path.'view.inc.php');


?>