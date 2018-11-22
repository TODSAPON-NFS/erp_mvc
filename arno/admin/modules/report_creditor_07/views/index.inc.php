<?php
session_start();

require_once('../models/CreditorReportModel.php');
require_once('../models/SupplierModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_creditor_07/views/";

$supplier_model = new SupplierModel;

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
$view_type = $_GET['view_type'];

$suppliers=$supplier_model->getSupplierBy();


if($view_type == 'full'){
    $creditor_reports = $creditor_report_model->getCreditorListDetailReportBy($date_end, $supplier_id, $keyword);
    require_once($path.'view-full.inc.php');
}else{
        
    $creditor_reports = $creditor_report_model->getAccountsPayableListReportBy($date_end, $supplier_id, $keyword);

    for($i = 0 ; $i < count($creditor_reports); $i++){
        $papers = $creditor_report_model->getCreditorListDetailReportBy('',$creditor_reports[$i]['supplier_id'],'');
        $creditor_reports[$i]['paper_number'] = count($papers);
        for($ii = 0; $ii < count($papers); $ii++){
            $creditor_reports[$i]['balance'] += $papers[$ii]['invoice_supplier_balance'];

            $val = explode("-",$date_end); 
            $current_date_str = $val[2]."-".$val[1]."-".$val[0];

            $val = explode("-",$papers[$ii]['invoice_supplier_due']); 
            $due_date_str = $val[2]."-".$val[1]."-".$val[0];

            $current_date = strtotime($current_date_str);
            $due_date = strtotime($due_date_str);

            $datediff =  $due_date - $current_date;

            $diff_day = round($datediff / (60 * 60 * 24)); 

            //echo $papers[$ii]['supplier_code'] . " ".$papers[$ii]['invoice_supplier_code']. " ". $current_date . " ". $papers[$ii]['invoice_supplier_due']." ".$diff_day."<br><br>";

            if($diff_day > 60){
                $creditor_reports[$i]['due_comming_more_than_60'] += $papers[$ii]['invoice_supplier_balance'];
            }else if($diff_day > 30){
                $creditor_reports[$i]['due_comming_in_60'] +=  $papers[$ii]['invoice_supplier_balance'];
            }else if($diff_day > -1){
                $creditor_reports[$i]['due_comming_in_30'] += $papers[$ii]['invoice_supplier_balance'];
            }else if($diff_day > -31){
                $creditor_reports[$i]['over_due_1_to_30'] += $papers[$ii]['invoice_supplier_balance'];
            }else if($diff_day > -61){
                $creditor_reports[$i]['over_due_31_to_60'] += $papers[$ii]['invoice_supplier_balance'];
            }else if($diff_day > -91){
                $creditor_reports[$i]['over_due_61_to_90'] += $papers[$ii]['invoice_supplier_balance'];
            }else{
                $creditor_reports[$i]['over_due_more_than_90'] += $papers[$ii]['invoice_supplier_balance'];
            } 
        } 
    }
    require_once($path.'view.inc.php');
}




?>