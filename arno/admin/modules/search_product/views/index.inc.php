<?php
date_default_timezone_set('Asia/Bangkok');

require_once('../models/StockReportModel.php');
$path = "modules/search_product/views/";
$model_stock = new StockReportModel;

$stock_list = $model_stock->getStockReportListBy($start, $end);
require_once($path.'view.inc.php');

?>