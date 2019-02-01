<?php
session_start();

require_once('../models/StockReportModel.php'); 
require_once('../models/StockGroupModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_stock_11/views/";
 
$stock_report_model = new StockReportModel;
$model_group = new StockGroupModel;
 

$stock_group = $model_group->getStockGroupBy();

if(!isset($_GET['date_end'])){
    $date_end = $_SESSION['date_end'];
}else{
    $date_end = $_GET['date_end'];
    $_SESSION['date_end'] = $date_end;
}




    $stock_reports = $stock_report_model->getStockCostBy($date_end);

// echo '<pre>';
// print_r($stock_reports);
// echo '</pre>';

require_once($path.'view.inc.php');
  




?>