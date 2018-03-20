<?php
date_default_timezone_set('Asia/Bangkok');

require_once('../models/StockBorrowOutModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');

$path = "modules/stock_borrow_out/views/";
$model_stock_borrow_out = new StockBorrowOutModel;
$model_product = new ProductModel;
$model_customer = new CustomerModel;
$date_start = $_POST['date_start'];
$date_end = $_POST['date_end'];
$stock_log_id = $_GET['id'];

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
    $stock_borrow_outs = $model_stock_borrow_out->getStockBorrowOutByDate($start, $end);
    $products = $model_product->getProductBy();
    $customers = $model_customer->getCustomerBy();
    if($stock_log_id != ""){
        $stock_borrow_out = $model_stock_borrow_out->getStockBorrowOutById($stock_log_id);
    }
    require_once($path.'view.inc.php');

}else if($_GET['action'] == 'update'){
    $stock_borrow_outs = $model_stock_borrow_out->getStockBorrowOutByDate($start, $end);
    $products = $model_product->getProductBy();
    $customers = $model_customer->getCustomerBy();
    if($stock_log_id != ""){
        $stock_borrow_out = $model_stock_borrow_out->getStockBorrowOutById($stock_log_id);
        $dt = explode(' ',$stock_borrow_out['stock_date']);
        $dt = explode('-',$dt[0]);
        $stock_borrow_out['stock_date'] =  $dt[2].'-'.$dt[1].'-'.$dt[0];
    }
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete'){

    $model_stock_borrow_out->deleteStockBorrowOutById($stock_log_id);
    
?>
    <script>window.location="index.php?app=stock_borrow_out"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['product_id'])){
        $ds = explode('-', $_POST['stock_date']);
        $stock_date = $ds[2].'-'.$ds[1].'-'.$ds[0].' 00:00:00';

        $data = [];
        $data['product_id'] = $_POST['product_id'];
        $data['borrow_code'] = $_POST['borrow_code'];
        $data['stock_log_type'] = 'borrow_out';
        $data['customer_id'] = $_POST['customer_id'];
        $data['stock_date'] = $stock_date;
        $data['qty'] = $_POST['qty'];

        $model_stock_borrow_out->insertStockBorrowOut($data);
        ?>
            <script>window.location="index.php?app=stock_borrow_out"</script>
        <?php
    }

    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['product_id'])){

        $ds = explode('-', $_POST['stock_date']);
        $stock_date = $ds[2].'-'.$ds[1].'-'.$ds[0].' 00:00:00';

        $data = [];
        $data['product_id'] = $_POST['product_id'];
        $data['borrow_code'] = $_POST['borrow_code'];
        $data['stock_log_type'] = 'borrow_out';
        $data['customer_id'] = $_POST['customer_id'];
        $data['stock_date'] = $stock_date;
        $data['qty'] = $_POST['qty'];

       
        $id = $model_stock_borrow_out->updateStockBorrowOutByID($stock_log_id,$data);
        ?>
            <script>window.location="index.php?app=stock_borrow_out"</script>
        <?php
        
    }

}else{

    $stock_borrow_out = $model_stock_borrow_out->getStockBorrowOutByDate($start, $end);
    require_once($path.'view.inc.php');

}





?>