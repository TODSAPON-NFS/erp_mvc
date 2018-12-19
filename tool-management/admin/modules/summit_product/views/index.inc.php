<?php
require_once('../models/ProductModel.php');
require_once('../models/StockGroupModel.php');
require_once('../models/SummitProductModel.php');

$path = "modules/summit_product/views/";
$model_product = new ProductModel;
$model_stock_group = new StockGroupModel;
$model_summit_product = new SummitProductModel;

$product_id = $_GET['product_id'];
$stock_group_id = $_GET['stock_group_id'];
$summit_product_id = $_GET['summit_product_id'];

if(!isset($_GET['action']) || $_GET['action'] == 'view-product'){

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100;
    
    $stock_groups = $model_stock_group->getStockGroupBy();
    $products = $model_product->getProductBy();
    $product = $model_product->getProductByID($product_id);

    if($product_id != ''){
        $summit_products = $model_summit_product->getSummitProductBy($product_id,'');
    }

    $page_max = (int)(count($summit_products)/$page_size);
    if(count($summit_products)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view-product.inc.php');

} else if($_GET['action'] == 'add-product'){

    $data = [];
    $data['product_id']=$_POST['product_id'];
    $data['stock_group_id']=$_POST['stock_group_id'];
    $data['summit_product_qty']=(float)filter_var($_POST['summit_product_qty'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['summit_product_cost']=(float)filter_var($_POST['summit_product_cost'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['summit_product_total']=(float)filter_var($_POST['summit_product_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['addby']=$user[0][0];

    $model_summit_product->insertSummitProduct($data);
    ?>
    <script>window.location = "?app=summit_product&action=view-product&product_id=<?PHP echo $product_id?>";</script>
    <?PHP 

} else if($_GET['action'] == 'delete-product'){
    
    $model_summit_product->deleteSummitProductByID($summit_product_id);
    ?>
    <script>window.location = "?app=summit_product&action=view-product&product_id=<?PHP echo $product_id?>";</script>
    <?PHP 

}else if($_GET['action'] == 'add-stock'){

    $data = [];
    $data['product_id']=$_POST['product_id'];
    $data['stock_group_id']=$_POST['stock_group_id'];
    $data['summit_product_qty']=(float)filter_var($_POST['summit_product_qty'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['summit_product_cost']=(float)filter_var($_POST['summit_product_cost'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['summit_product_total']=(float)filter_var($_POST['summit_product_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['addby']=$user[0][0];

    $model_summit_product->insertSummitProduct($data);
    ?>
    <script>window.location = "?app=summit_product&action=view-stock&stock_group_id=<?PHP echo $stock_group_id?>";</script>
    <?PHP 

}else if($_GET['action'] == 'addgroup-stock'){

    $data = [];
    $product_id=$_POST['product_id']; 
    $product_qty= $_POST['product_qty'];
    $product_price= $_POST['product_price'];
    $product_price_total= $_POST['product_price_total'];

    for($i=0; $i < count($product_id); $i++){
        if($product_id[$i] > 0){
            $data = [];
            $data['product_id']=$product_id[$i];
            $data['stock_group_id']=$stock_group_id;
            $data['summit_product_qty']=(float)filter_var($product_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['summit_product_cost']=(float)filter_var($product_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['summit_product_total']=(float)filter_var($product_price_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['addby']=$admin_id;
            $model_summit_product->insertSummitProduct($data);
        }
        
    }
    
    ?>
    <script>window.location = "?app=summit_product&action=view-stock&stock_group_id=<?PHP echo $stock_group_id?>";</script>
    <?PHP 

} else if($_GET['action'] == 'delete-stock'){
    
    $model_summit_product->deleteSummitProductByID($summit_product_id);
    ?>
    <script>window.location = "?app=summit_product&action=view-stock&stock_group_id=<?PHP echo $stock_group_id?>";</script>
    <?PHP 

}else if($_GET['action'] == 'delete-all-stock'){ 
    $summit_product_id = $_POST['summit_product_id'];

    for($i=0; $i < count($summit_product_id) ; $i++){
        $model_summit_product->deleteSummitProductByID($summit_product_id[$i]);
    }
    ?>
    <script>window.location = "?app=summit_product&action=view-stock&stock_group_id=<?PHP echo $stock_group_id?>";</script>
    <?PHP 

}else{


    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100;
    
    $stock_groups = $model_stock_group->getStockGroupBy();
    $stock_group = $model_stock_group->getStockGroupByID($stock_group_id);
    $products = $model_product->getProductBy();

    if($stock_group_id != ''){
        $summit_products = $model_summit_product->getSummitProductBy('', $stock_group_id);
    }
    

    $page_max = (int)(count($summit_products)/$page_size);
    if(count($summit_products)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view-stock.inc.php');

}





?>