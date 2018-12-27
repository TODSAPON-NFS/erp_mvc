<?php
date_default_timezone_set('Asia/Bangkok');

require_once('../models/StockInModel.php');
require_once('../models/ProductModel.php');
require_once('../models/SupplierModel.php');

$path = "modules/stock_in/views/";
$model_stock_in = new StockInModel;
$model_product = new ProductModel;
$model_supplier = new SupplierModel;
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
    $stock_ins = $model_stock_in->getStockInByDate($start, $end);
    $products = $model_product->getProductBy();
    $suppliers = $model_supplier->getSupplierBy();
    if($stock_log_id != ""){
        $stock_in = $model_stock_in->getStockInById($stock_log_id);
    }
    require_once($path.'view.inc.php');

}else if($_GET['action'] == 'update'){
    $stock_ins = $model_stock_in->getStockInByDate($start, $end);
    $products = $model_product->getProductBy();
    $suppliers = $model_supplier->getSupplierBy();
    if($stock_log_id != ""){
        $stock_in = $model_stock_in->getStockInById($stock_log_id);
        $dt = explode(' ',$stock_in['stock_date']);
        $dt = explode('-',$dt[0]);
        $stock_in['stock_date'] =  $dt[2].'-'.$dt[1].'-'.$dt[0];
    }
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete'){

    $model_stock_in->deleteStockInById($stock_log_id);
    
?>
    <script>window.location="index.php?app=stock_in"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['product_id'])){
        $ds = explode('-', $_POST['stock_date']);
        $stock_date = $ds[2].'-'.$ds[1].'-'.$ds[0].' 00:00:00';

        $data = [];
        $data['product_id'] = $_POST['product_id'];
        $data['po_code'] = $_POST['po_code'];
        $data['recieve_code'] = $_POST['recieve_code'];
        $data['stock_log_type'] = 'in';
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['stock_date'] = $stock_date;
        $data['qty'] = $_POST['qty'];

        $model_stock_in->insertStockIn($data);
        ?>
            <script>window.location="index.php?app=stock_in"</script>
        <?php
    }

    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['product_id'])){

        $ds = explode('-', $_POST['stock_date']);
        $stock_date = $ds[2].'-'.$ds[1].'-'.$ds[0].' 00:00:00';

        $data = [];
        $data['product_id'] = $_POST['product_id'];
        $data['po_code'] = $_POST['po_code'];
        $data['recieve_code'] = $_POST['recieve_code'];
        $data['stock_log_type'] = 'in';
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['stock_date'] = $stock_date;
        $data['qty'] = $_POST['qty'];

       
        $id = $model_stock_in->updateStockInByID($stock_log_id,$data);
        ?>
            <script>window.location="index.php?app=stock_in"</script>
        <?php
        
    }

}else{

    $stock_in = $model_stock_in->getStockInByDate($start, $end);
    require_once($path.'view.inc.php');

}





?>