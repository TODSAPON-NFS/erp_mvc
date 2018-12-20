<?php
session_start();

require_once('../models/StockReportModel.php'); 

date_default_timezone_set('asia/bangkok');

$path = "modules/report_stock_06/views/";
 
$stock_report_model = new StockReportModel;
 

if(!isset($_GET['date_target'])){
    $date_target = $_SESSION['date_target'];
}else{
    $date_target = $_GET['date_target'];
    $_SESSION['date_target'] = $date_target;
}

if(!isset($_GET['table_name'])){
    $table_name = $_SESSION['table_name'];
}else{
    $table_name = $_GET['table_name'];
    $_SESSION['table_name'] = $table_name;
}
 

if(!isset($_GET['stock_start'])){
    $stock_start = $_SESSION['stock_start'];
}else{
    $stock_start = $_GET['stock_start'];
    $_SESSION['stock_start'] = $stock_start;
}

if(!isset($_GET['stock_end'])){
    $stock_end = $_SESSION['stock_end'];
}else{
    $stock_end = $_GET['stock_end'];
    $_SESSION['stock_end'] = $stock_end;
}


if(!isset($_GET['product_start'])){
    $product_start = $_SESSION['product_start'];
}else{
    $product_start = $_GET['product_start'];
    $_SESSION['product_start'] = $product_start;
}


if(!isset($_GET['product_end'])){
    $product_end = $_SESSION['product_end'];
}else{
    $product_end = $_GET['product_end'];
    $_SESSION['product_end'] = $product_end;
}

if(!isset($_GET['group_by'])){
    $group_by = $_SESSION['group_by'];
}else{
    $group_by = $_GET['group_by'];
    $_SESSION['group_by'] = $group_by;
}

if(!isset($_GET['paper_code'])){
    $paper_code = $_SESSION['paper_code'];
}else{
    $paper_code = $_GET['paper_code'];
    $_SESSION['paper_code'] = $paper_code;
}

// echo '<pre>';
// print_r($_GET);
// echo '</pre>';

 

if($group_by == "product_code"){
    if($date_target!=''){  
        $stock_reports = $stock_report_model->getStockReportProductMovementDayBy($date_target,$stock_start,$stock_end,$product_start,$product_end,$table_name,$group_by,$paper_code); 
    } 
    require_once($path.'view-product.inc.php');
}else if($group_by == "stock_group_code"){
    if($date_target!=''){  
        $stock_reports = $stock_report_model->getStockReportProductMovementDayBy($date_target,$stock_start,$stock_end,$product_start,$product_end,$table_name,$group_by,$paper_code); 
    } 
    require_once($path.'view-stock.inc.php');
    
}else{
    if($date_target!=''){  
        $stock_reports = $stock_report_model->getStockReportProductMovementDayBy($date_target,$stock_start,$stock_end,$product_start,$product_end,$table_name,$group_by,$paper_code); 
    } 
    require_once($path.'view.inc.php');
}


  




?>