<?php
date_default_timezone_set('Asia/Bangkok');

require_once('../models/StockReportModel.php');
require_once('../models/StockGroupModel.php');

$path = "modules/search_product/views/";
$model_stock = new StockReportModel;
$model_group = new StockGroupModel;



$stock_group_id = $_GET['stock_group_id'];
$keyword = $_GET['keyword'];

if($_GET['page'] == '' || $_GET['page'] == '0'){
    $page = 0;
}else{
    $page = $_GET['page'] - 1;
}

$page_size = 100;

$stock_group = $model_group->getStockGroupBy();

if ($stock_group_id == ""){
    //$stock_group_id = $stock_group[0]["stock_group_id"];
}else{
    $stock_list = $model_stock->getStockReportListBy($stock_group_id, $keyword);
}
// echo "<pre>";
// print_r( $stock_list = $model_stock->getStockReportListBy($stock_group_id, $keyword));
// echo "</pre>"; 
$page_max = (int)(count($stock_list)/$page_size);
if(count($stock_list)%$page_size > 0){
    $page_max += 1;
}

require_once($path.'view.inc.php');
?>