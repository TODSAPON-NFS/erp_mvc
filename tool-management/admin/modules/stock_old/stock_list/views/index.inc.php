<?php
date_default_timezone_set('Asia/Bangkok');

require_once('../models/StockListModel.php');
$path = "modules/stock_list/views/";
$model_stock_list = new StockListModel;
$date_start = $_POST['date_start'];
$date_end = $_POST['date_end'];


if($date_start == ""){
    $date_start  = date('1-m-Y');  
}
$ds = explode('-', $date_start);
$start = $ds[2].'-'.$ds[1].'-'.$ds[0].' 00:00:00';



if($date_end == ""){
    $date_end  = date('t-m-Y');
}

$de = explode('-', $date_end);
$end = $de[2].'-'.$de[1].'-'.$de[0].' 23:59:59';


if(!isset($_GET['action'])){
    $stock_list = $model_stock_list->getStockLogListByDate($start, $end);
    require_once($path.'view.inc.php');

}else{

    $stock_list = $model_stock_list->getStockLogListByDate($start, $end);
    require_once($path.'view.inc.php');

}





?>