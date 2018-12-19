<?php
session_start();

require_once('../models/StockReportModel.php'); 
require_once('../models/ProductTypeModel.php');
require_once('../models/ProductCategoryModel.php'); 

date_default_timezone_set('asia/bangkok');

$path = "modules/report_stock_05/views/";
 
$stock_report_model = new StockReportModel;
$product_type_model = new ProductTypeModel;
$product_category_model = new ProductCategoryModel;
 

if(!isset($_GET['product_category_id'])){
    $product_category_id = $_SESSION['product_category_id'];
}else{
    $product_category_id = $_GET['product_category_id'];
    $_SESSION['product_category_id'] = $product_category_id;
}

if(!isset($_GET['product_type_id'])){
    $product_type_id = $_SESSION['product_type_id'];
}else{
    $product_type_id = $_GET['product_type_id'];
    $_SESSION['product_type_id'] = $product_type_id;
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

$product_type = $product_type_model->getProductTypeBy();
$product_category = $product_category_model->getProductCategoryBy();
if($product_start!=''||$product_category_id!=''||$product_type_id!=''){
    $stock_reports = $stock_report_model->getStockReportProductBy($product_category_id, $product_type_id,$product_start,$product_end);
    // echo '<pre>';
    // print_r($stock_reports);
    // echo '</pre>';
}

require_once($path.'view.inc.php');
  




?>