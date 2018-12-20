<?php
session_start();
$user = $_SESSION['user'];
$purchase_order_id = $purchase_order[0][0];
require_once('../models/CreditPurchasingModel.php');
require_once('../models/CreditPurchasingListModel.php');
require_once('../models/PurchaseOrderModel.php');
require_once('../models/StockGroupModel.php');
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/credit_purchasing/views/";
$purchase_order_model = new PurchaseOrderModel;
$supplier_model = new SupplierModel;
$notification_model = new NotificationModel;
$credit_purchasing_model = new CreditPurchasingModel;
$credit_purchasing_list_model = new CreditPurchasingListModel;
$stock_group_model = new StockGroupModel;
$first_char = "RR";
$credit_purchasing_id = $_GET['id'];
$notification_id = $_GET['notification'];
$vat = 7;
if(!isset($_GET['action'])){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();
    $credit_purchasings = $credit_purchasing_model->getCreditPurchasingBy($date_start,$date_end,$supplier_id,$keyword,$purchase_order_id);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $stock_groups=$stock_group_model->getStockGroupBy();
    $suppliers=$supplier_model->getSupplierBy();

    $purchase_orders=$purchase_order_model->getPurchaseOrderBy();
    $first_code = $first_char.date("y").date("m");

    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $credit_purchasing_model->getCreditPurchasingLastID($first_code,3);
    
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $stock_groups=$stock_group_model->getStockGroupBy();
    $suppliers=$supplier_model->getSupplierBy();
    $purchase_orders=$purchase_order_model->getPurchaseOrderBy();

    $credit_purchasing = $credit_purchasing_model->getCreditPurchasingByID($credit_purchasing_id);
    $credit_purchasing_lists = $credit_purchasing_list_model->getCreditPurchasingListBy($credit_purchasing_id);
    
    $supplier=$supplier_model->getSupplierByID($credit_purchasing['supplier_id']);
    $vat = $credit_purchasing['credit_purchasing_vat'];
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $credit_purchasing = $credit_purchasing_model->getCreditPurchasingViewByID($credit_purchasing_id);
    $credit_purchasing_lists = $credit_purchasing_list_model->getCreditPurchasingListBy($credit_purchasing_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete'){
    $credit_purchasings = $credit_purchasing_model->deleteCreditPurchasingById($credit_purchasing_id);
?>
    <script>window.location="index.php?app=credit_purchasing"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['credit_purchasing_code'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['credit_purchasing_code'] = $_POST['credit_purchasing_code'];
        $data['credit_purchasing_date'] = $_POST['credit_purchasing_date'];
        $data['purchase_order_id'] = $_POST['purchase_order_id'];
        $data['credit_purchasing_credit_day'] = $_POST['credit_purchasing_credit_day'];
        $data['credit_purchasing_credit_date'] = $_POST['credit_purchasing_credit_date'];
        $data['credit_purchasing_delivery_by'] = $_POST['credit_purchasing_delivery_by'];
        $data['credit_purchasing_vat_type'] = $_POST['credit_purchasing_vat_type'];
        $data['credit_purchasing_total'] = (float)filter_var($_POST['credit_purchasing_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_purchasing_discount'] = (float)filter_var($_POST['credit_purchasing_discount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_purchasing_discount_type'] = $_POST['credit_purchasing_discount_type'];
        $data['credit_purchasing_vat'] = (float)filter_var($_POST['credit_purchasing_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_purchasing_vat_value'] = (float)filter_var($_POST['credit_purchasing_vat_value'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_purchasing_net'] = (float)filter_var($_POST['credit_purchasing_net'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_purchasing_remark'] = $_POST['credit_purchasing_remark'];
        $data['addby'] = $user[0][0];

        $credit_purchasing_id = $credit_purchasing_model->insertCreditPurchasing($data);

        $credit_purchasing_list_id = $_POST['credit_purchasing_list_id'];
        $credit_purchasing_list_code = $_POST['credit_purchasing_list_code'];
        $credit_purchasing_list_name = $_POST['credit_purchasing_list_name'];
        $stock_group_id = $_POST['stock_group_id'];
        $credit_purchasing_list_qty = $_POST['credit_purchasing_list_qty'];
        $credit_purchasing_list_unit = $_POST['credit_purchasing_list_unit'];
        $credit_purchasing_list_price = $_POST['credit_purchasing_list_price'];
        $credit_purchasing_list_discount = $_POST['credit_purchasing_list_discount'];
        $credit_purchasing_list_discount_type = $_POST['credit_purchasing_list_discount_type'];
        $credit_purchasing_list_total = $_POST['credit_purchasing_list_total'];

        $credit_purchasing_list_model->deleteCreditPurchasingListByCreditPurchasingListIDNotIN($credit_purchasing_id,$credit_purchasing_list_id);

        if(is_array($credit_purchasing_list_id)){
            for($i=0; $i < count($credit_purchasing_list_id) ; $i++){
                $data = [];
                $data['credit_purchasing_id'] = $credit_purchasing_id;
                $data['credit_purchasing_list_code'] = $credit_purchasing_list_code[$i];
                $data['credit_purchasing_list_name'] = $credit_purchasing_list_name[$i];
                $data['stock_group_id'] = $stock_group_id[$i];
                $data['credit_purchasing_list_qty'] = (float)filter_var($credit_purchasing_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['credit_purchasing_list_unit'] = $credit_purchasing_list_unit[$i];
                $data['credit_purchasing_list_price'] = (float)filter_var($credit_purchasing_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['credit_purchasing_list_discount'] = (float)filter_var($credit_purchasing_list_discount[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['credit_purchasing_list_discount_type'] = $credit_purchasing_list_discount_type[$i];
                $data['credit_purchasing_list_total'] = (float)filter_var($credit_purchasing_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['updateby'] = $user[0][0];

                if($credit_purchasing_list_id[$i] == 0){
                    $credit_purchasing_list_model->insertCreditPurchasingList($data);
                }else{
                    $credit_purchasing_list_model->updateCreditPurchasingListById($data,$credit_purchasing_list_id[$i]);
                }
            }
        }else{
            $data = [];
            $data['credit_purchasing_id'] = $credit_purchasing_id;
            $data['credit_purchasing_list_code'] = $credit_purchasing_list_code;
            $data['credit_purchasing_list_name'] = $credit_purchasing_list_name;
            $data['stock_group_id'] = $stock_group_id;
            $data['credit_purchasing_list_qty'] = (float)filter_var($credit_purchasing_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['credit_purchasing_list_unit'] = $credit_purchasing_list_unit;
            $data['credit_purchasing_list_price'] = (float)filter_var($credit_purchasing_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['credit_purchasing_list_discount'] = (float)filter_var($credit_purchasing_list_discount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['credit_purchasing_list_discount_type'] = $credit_purchasing_list_discount_type;
            $data['credit_purchasing_list_total'] = (float)filter_var($credit_purchasing_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['updateby'] = $user[0][0];

            if($credit_purchasing_list_id == 0){
                $credit_purchasing_list_model->insertCreditPurchasingList($data);
            }else{
                $credit_purchasing_list_model->updateCreditPurchasingListById($data,$credit_purchasing_list_id);
            }
        }

        if($credit_purchasing_id > 0){
?>
        <script>window.location="index.php?app=credit_purchasing&action=update&id=<?php echo $credit_purchasing_id;?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['credit_purchasing_code'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['credit_purchasing_code'] = $_POST['credit_purchasing_code'];
        $data['credit_purchasing_date'] = $_POST['credit_purchasing_date'];
        $data['purchase_order_id'] = $_POST['purchase_order_id'];
        $data['credit_purchasing_credit_day'] = $_POST['credit_purchasing_credit_day'];
        $data['credit_purchasing_credit_date'] = $_POST['credit_purchasing_credit_date'];
        $data['credit_purchasing_delivery_by'] = $_POST['credit_purchasing_delivery_by'];
        $data['credit_purchasing_vat_type'] = $_POST['credit_purchasing_vat_type'];
        $data['credit_purchasing_total'] = (float)filter_var($_POST['credit_purchasing_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_purchasing_discount'] = (float)filter_var($_POST['credit_purchasing_discount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_purchasing_discount_type'] = $_POST['credit_purchasing_discount_type'];
        $data['credit_purchasing_vat'] = (float)filter_var($_POST['credit_purchasing_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_purchasing_vat_value'] = (float)filter_var($_POST['credit_purchasing_vat_value'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_purchasing_net'] = (float)filter_var($_POST['credit_purchasing_net'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_purchasing_remark'] = $_POST['credit_purchasing_remark'];
        $data['updateby'] = $user[0][0];

        $output = $credit_purchasing_model->updateCreditPurchasingByID($credit_purchasing_id,$data);

        $credit_purchasing_list_id = $_POST['credit_purchasing_list_id'];
        $credit_purchasing_list_code = $_POST['credit_purchasing_list_code'];
        $credit_purchasing_list_name = $_POST['credit_purchasing_list_name'];
        $stock_group_id = $_POST['stock_group_id'];
        $credit_purchasing_list_qty = $_POST['credit_purchasing_list_qty'];
        $credit_purchasing_list_unit = $_POST['credit_purchasing_list_unit'];
        $credit_purchasing_list_price = $_POST['credit_purchasing_list_price'];
        $credit_purchasing_list_discount = $_POST['credit_purchasing_list_discount'];
        $credit_purchasing_list_discount_type = $_POST['credit_purchasing_list_discount_type'];
        $credit_purchasing_list_total = $_POST['credit_purchasing_list_total'];

        $credit_purchasing_list_model->deleteCreditPurchasingListByCreditPurchasingListIDNotIN($credit_purchasing_id,$credit_purchasing_list_id);

        if(is_array($credit_purchasing_list_id)){
            for($i=0; $i < count($credit_purchasing_list_id) ; $i++){
                $data = [];
                $data['credit_purchasing_id'] = $credit_purchasing_id;
                $data['credit_purchasing_list_code'] = $credit_purchasing_list_code[$i];
                $data['credit_purchasing_list_name'] = $credit_purchasing_list_name[$i];
                $data['stock_group_id'] = $stock_group_id[$i];
                $data['credit_purchasing_list_qty'] = (float)filter_var($credit_purchasing_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['credit_purchasing_list_unit'] = $credit_purchasing_list_unit[$i];
                $data['credit_purchasing_list_price'] = (float)filter_var($credit_purchasing_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['credit_purchasing_list_discount'] = (float)filter_var($credit_purchasing_list_discount[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['credit_purchasing_list_discount_type'] = $credit_purchasing_list_discount_type[$i];
                $data['credit_purchasing_list_total'] = (float)filter_var($credit_purchasing_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['updateby'] = $user[0][0];
                if($credit_purchasing_list_id[$i] == 0){
                    $credit_purchasing_list_model->insertCreditPurchasingList($data);
                }else{
                    $credit_purchasing_list_model->updateCreditPurchasingListById($data,$credit_purchasing_list_id[$i]);
                }
            }
        }else{
            $data = [];
            $data['credit_purchasing_id'] = $credit_purchasing_id;
            $data['credit_purchasing_list_code'] = $credit_purchasing_list_code;
            $data['credit_purchasing_list_name'] = $credit_purchasing_list_name;
            $data['stock_group_id'] = $stock_group_id;
            $data['credit_purchasing_list_qty'] = (float)filter_var($credit_purchasing_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['credit_purchasing_list_unit'] = $credit_purchasing_list_unit;
            $data['credit_purchasing_list_price'] = (float)filter_var($credit_purchasing_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['credit_purchasing_list_discount'] = (float)filter_var($credit_purchasing_list_discount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['credit_purchasing_list_discount_type'] = $credit_purchasing_list_discount_type;
            $data['credit_purchasing_list_total'] = (float)filter_var($credit_purchasing_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['updateby'] = $user[0][0];

            if($credit_purchasing_list_id == 0){
                $credit_purchasing_list_model->insertCreditPurchasingList($data);
            }else{
                $credit_purchasing_list_model->updateCreditPurchasingListById($data,$credit_purchasing_list_id);
            }
        }
        
        if($output){
?>
        <script>window.location="index.php?app=credit_purchasing&action=update&id=<?PHP echo $credit_purchasing_id?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
   
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
      
    
}else{

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();
    $credit_purchasings = $credit_purchasing_model->getCreditPurchasingBy($date_start,$date_end,$supplier_id,$keyword,$purchase_order_id);
    require_once($path.'view.inc.php');

}





?>