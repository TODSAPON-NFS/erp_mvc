<?php
session_start();

require_once('../models/StockReportModel.php'); 
require_once('../models/StockGroupModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_stock_02/views/";
 
$stock_report_model = new StockReportModel;
$model_group = new StockGroupModel;
 

$stock_group = $model_group->getStockGroupBy();

if(!isset($_GET['date_end'])){
    $date_end = $_SESSION['date_end'];
}else{
    $date_end = $_GET['date_end'];
    $_SESSION['date_end'] = $date_end;
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


if(!isset($_GET['stock_group_id'])){
    $stock_group_id = $_SESSION['stock_group_id'];
}else{
    $stock_group_id = $_GET['stock_group_id'];
    $_SESSION['stock_group_id'] = $stock_group_id;
}

if(!isset($_GET['status_qty'])){
    $status_qty = $_SESSION['status_qty'];
}else{
    $status_qty = $_GET['status_qty'];
    $_SESSION['status_qty'] = $status_qty;
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


 
if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}

if($stock_group_id!='' ||$product_start!=''||$product_end!=''){

    $stock_reports = $stock_report_model->getStockReportBalanceListBy($date_end, $stock_group_id , $product_start, $product_end,$status_qty);
}

require_once($path.'view.inc.php');
  




?>