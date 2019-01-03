<?php
date_default_timezone_set('Asia/Bangkok');

require_once('../models/ProductModel.php');
require_once('../models/StockReportModel.php');

$path = "modules/product_detail/views/";

$product_model = new ProductModel;
$product_id = $_GET['product_id'];
$product = $product_model->getProductByID($product_id);
$header_page = "รายละเอียดสินค้า";
$product_ID = $product['product_id'];
$product_code = $product['product_code'];
$product_name = $product['product_name'];
$product_type_name = $product['product_type_name'];
$product_category_name = $product['product_category_name'];		
$product_group_name = $product['product_group_name'];		
$product_type_name = $product['product_type_name'];		
$product_barcode = $product['product_barcode'];		
$product_unit = $product['product_unit'];		
$product_status = $product['product_status'];		
$product_description = $product['product_description'];		


$stock_report_model = new StockReportModel; 
$stock_report = $stock_report_model->getStockReportProductByID($product_id);
$paper = $stock_report_model->getStockReportProductPaperByID($product_id);

require_once($path.'view.inc.php');

?>