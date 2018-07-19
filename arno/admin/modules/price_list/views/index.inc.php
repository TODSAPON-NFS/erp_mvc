<?php
require_once('../models/ProductModel.php');
require_once('../models/ProductTypeModel.php');
require_once('../models/ProductCategoryModel.php'); 
require_once('../models/SupplierModel.php');

$path = "modules/price_list/views/";
$model_product = new ProductModel; 
$model_product_type = new ProductTypeModel;
$model_product_category = new ProductCategoryModel;
$model_supplier = new SupplierModel;

if(!isset($_GET['action']) || $_GET['action'] != 'update'){

    $supplier_id = $_GET['supplier_id'];
    $product_category_id = $_GET['product_category_id'];
    $product_type_id = $_GET['product_type_id'];
    $keyword = $_GET['keyword'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100;
    
    $product_type = $model_product_type->getProductTypeBy();
    $product_category = $model_product_category->getProductCategoryBy();
    $suppliers = $model_supplier->getSupplierBy();
    $product = $model_product->getProductBy($supplier_id,$product_category_id,$product_type_id,$keyword );

    $page_max = (int)(count($product)/$page_size);
    if(count($product)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view.inc.php');

} else if ($_GET['action'] == 'update' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){

    $product_id = $_POST['product_id'];
    $product_price_1 = $_POST['product_price_1'];
    $product_price_2 = $_POST['product_price_2'];
    $product_price_3 = $_POST['product_price_3'];
    $product_price_4 = $_POST['product_price_4'];
    $product_price_5 = $_POST['product_price_5'];
    
    for($i = 0; $i < count($product_id); $i++){
        $data = [];
        $data['product_id'] = $product_id[$i];
        $data['product_price_1'] = $product_price_1[$i];
        $data['product_price_2'] = $product_price_2[$i];
        $data['product_price_3'] = $product_price_3[$i];
        $data['product_price_4'] = $product_price_4[$i];
        $data['product_price_5'] = $product_price_5[$i];
    
    
        $model_product->updateProductPriceByID($product_id[$i],$data);
    }
    

    ?>
    <script>window.location="index.php?app=price_list"</script>
    <?php

}





?>