<?php
session_start();

require_once('../models/DebtorReportModel.php');
require_once('../models/CustomerModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_debtor_08/views/";

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
$view_type = $_GET['view_type'];

$customers=$customer_model->getCustomerBy();


if($view_type == 'full'){
    $debtor_reports = $debtor_report_model->getDebtorListDetailReportBy($date_end, $customer_id, $keyword);
    require_once($path.'view-full.inc.php');
}else{
        
    $debtor_reports = $debtor_report_model->getAccountsReceivableDebtorListReportBy($date_end, $customer_id, $keyword);

    for($i = 0 ; $i < count($debtor_reports); $i++){
        $papers = $debtor_report_model->getDebtorListDetailReportBy('',$debtor_reports[$i]['customer_id'],'');
        $debtor_reports[$i]['paper_number'] = count($papers);
        for($ii = 0; $ii < count($papers); $ii++){
            $debtor_reports[$i]['balance'] += $papers[$ii]['invoice_customer_balance'];

            $val = explode("-",$date_end); 
            $current_date_str = $val[2]."-".$val[1]."-".$val[0];

            $val = explode("-",$papers[$ii]['invoice_customer_due']); 
            $due_date_str = $val[2]."-".$val[1]."-".$val[0];

            $current_date = strtotime($current_date_str);
            $due_date = strtotime($due_date_str);

            $datediff =  $due_date - $current_date;

            $diff_day = round($datediff / (60 * 60 * 24)); 

            //echo $papers[$ii]['customer_code'] . " ".$papers[$ii]['invoice_customer_code']. " ". $current_date . " ". $papers[$ii]['invoice_customer_due']." ".$diff_day."<br><br>";

            if($diff_day > 60){
                $debtor_reports[$i]['due_comming_more_than_60'] += $papers[$ii]['invoice_customer_balance'];
            }else if($diff_day > 30){
                $debtor_reports[$i]['due_comming_in_60'] +=  $papers[$ii]['invoice_customer_balance'];
            }else if($diff_day > -1){
                $debtor_reports[$i]['due_comming_in_30'] += $papers[$ii]['invoice_customer_balance'];
            }else if($diff_day > -31){
                $debtor_reports[$i]['over_due_1_to_30'] += $papers[$ii]['invoice_customer_balance'];
            }else if($diff_day > -61){
                $debtor_reports[$i]['over_due_31_to_60'] += $papers[$ii]['invoice_customer_balance'];
            }else if($diff_day > -91){
                $debtor_reports[$i]['over_due_61_to_90'] += $papers[$ii]['invoice_customer_balance'];
            }else{
                $debtor_reports[$i]['over_due_more_than_90'] += $papers[$ii]['invoice_customer_balance'];
            } 
        } 
    }
    require_once($path.'view.inc.php');
}




?>