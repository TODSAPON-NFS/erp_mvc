<?php
session_start();

require_once('../models/StockReportModel.php'); 
require_once('../models/StockGroupModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_stock_10/views/";
 
$stock_report_model = new StockReportModel;
$model_group = new StockGroupModel;
 

$stock_group = $model_group->getStockGroupBy();


if(!isset($_GET['stock_group_id'])){
    $stock_group_id = $_SESSION['stock_group_id'];
}else{
    $stock_group_id = $_GET['stock_group_id'];
    $_SESSION['stock_group_id'] = $stock_group_id;
}


if(!isset($_GET['keyword'])){
    $keyword = $_SESSION['keyword'];
}else{
    $keyword = $_GET['keyword'];
    $_SESSION['keyword'] = $keyword;
}

 
if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}

if($stock_group_id!='' ){

    $stock_reports = $stock_report_model->getStockReportProblematicProductBy($stock_group_id,$keyword);

  // echo "<pre>";
  //  print_r($stock_reports);
 // echo "</pre>";
}

require_once($path.'view.inc.php');
  




?>