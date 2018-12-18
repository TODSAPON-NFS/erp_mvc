<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/ProductModel.php');
$model_product = new ProductModel;
$product = $model_product->getProductDataByCode($_POST['product_code'],$_POST['stock_group_id'],$_POST['qty']);

echo json_encode($product);
?>