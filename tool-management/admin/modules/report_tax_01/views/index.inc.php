<?php
session_start();

require_once('../models/TaxReportModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/InvoiceSupplierModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_tax_01/views/";

$supplier_model = new SupplierModel;
$invoice_supplier_model = new InvoiceSupplierModel;

$tax_report_model = new TaxReportModel;
$supplier_replace_id = $_POST['supplier_replace_id'];
$change_id = $_POST['change_id'];

// echo "<pre>";
// print_r($change_id);
// echo "</pre>";
// echo "<pre>";
// print_r($supplier_replace_id);
// echo "</pre>";



if($supplier_replace_id != ""){
    $supplier = $supplier_model->getSupplierByID($supplier_replace_id);
    
    if($supplier['supplier_id'] > 0){
        
        for($i = 0; $i < count($change_id); $i++){
            $data = [];
            $data ['supplier_id'] = $supplier['supplier_id'];
            $data ['invoice_supplier_name'] = $supplier['supplier_name_en'];
            $data ['invoice_supplier_address'] = $supplier['supplier_address_1'].' '.$supplier['supplier_address_2'].' '.$supplier['supplier_address_3'];
            $data ['invoice_supplier_tax'] = $supplier['supplier_tax'];
            $data ['invoice_supplier_branch'] = $supplier['supplier_branch'];
            $data ['invoice_supplier_term'] = $supplier['condition_pay'];

            $invoice_supplier_model->updateSupplierByInvoiceID($change_id[$i],$data);
        }
    } 
}

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