<?php
session_start();

require_once('../models/StockReportModel.php'); 

date_default_timezone_set('asia/bangkok');

$path = "modules/report_stock_02/views/";
 
$stock_report_model = new StockReportModel;
 

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

if($stock_start!=''||$stock_end!=''||$product_start!=''||$product_end!=''){

    $stock_reports = $stock_report_model->getStockReportBy($stock_start,$stock_end,$product_start,$product_end);
}

require_once($path.'view.inc.php');
  




?>