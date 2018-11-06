<?php
session_start();

require_once('../models/DebtorReportModel.php'); 

date_default_timezone_set('asia/bangkok');

$path = "modules/report_debtor_09/views/";
 

$debtor_report_model = new DebtorReportModel;



$code_start = $_GET['code_start'];
$code_end = $_GET['code_end'];  
$view_type = $_GET['view_type']; 

$debtor_reports = $debtor_report_model->getCustomerListReportBy($code_start, $code_end);

if($view_type == 'full'){
    
    require_once($path.'view-full.inc.php');
}else{
    
    require_once($path.'view.inc.php');
}




?>