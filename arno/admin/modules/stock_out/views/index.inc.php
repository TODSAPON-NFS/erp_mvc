<?php
date_default_timezone_set('Asia/Bangkok');

require_once('../models/StockModel.php');
require_once('../models/StockGroupModel.php');
require_once('../models/ProductModel.php');
require_once('../models/SupplierModel.php');

$path = "modules/stock_out/views/";
$model_stock = new StockModel;
$model_stock_group = new StockGroupModel;
$model_product = new ProductModel;
$model_supplier = new SupplierModel;
$stock_group_id = $_GET['id'];
$stock_group = $model_stock_group->getStockGroupByID($stock_group_id);
$table_name = $stock_group['table_name'];
$model_stock->setTableName($table_name);

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
    $stock_outs = $model_stock->getStockOutByDate($start, $end);
    require_once($path.'view.inc.php');

}else{

    $stock_outs = $model_stock->getStockOutByDate($start, $end);
    require_once($path.'view.inc.php');

}





?>