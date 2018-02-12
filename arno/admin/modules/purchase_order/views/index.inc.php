<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/PurchaseOrderModel.php');
require_once('../models/PurchaseOrderListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/purchase_order/views/";
$user_model = new UserModel;
$supplier_model = new SupplierModel;
$notification_model = new NotificationModel;
$purchase_order_model = new PurchaseOrderModel;
$purchase_order_list_model = new PurchaseOrderListModel;
$product_model = new ProductModel;
$first_char = "PO";
$purchase_order_id = $_GET['id'];
$notification_id = $_GET['notification'];
if(!isset($_GET['action'])){

    $purchase_orders = $purchase_order_model->getPurchaseOrderBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $first_code = $first_char.date("y").date("m");
    $last_code = $purchase_order_model->getPurchaseOrderLastID($first_code,3);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $products=$product_model->getProductBy();
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $purchase_order = $purchase_order_model->getPurchaseOrderByID($purchase_order_id);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    if($notification_id != ""){
        $notification_model->setNotificationSeenByID($notification_id);
    }
    $purchase_order = $purchase_order_model->getPurchaseOrderViewByID($purchase_order_id);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete'){

    $purchase_order_list_model->deletePurchaseOrderListByPurchaseOrderID($purchase_order_id);
    $purchase_orders = $purchase_order_model->deletePurchaseOrderById($purchase_order_id);
?>
    <script>window.location="index.php?app=purchase_order"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['purchase_order_type'])){
        $data = [];
        $data['purchase_order_type'] = $_POST['purchase_order_type'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['purchase_order_code'] = $_POST['purchase_order_code'];
        $data['purchase_order_date'] = $_POST['purchase_order_date'];
        $data['purchase_order_credit_term'] = $_POST['purchase_order_credit_term'];
        $data['purchase_order_accept_status'] = 'Waiting';
        $data['purchase_order_status'] = 'Waiting';
        $data['purchase_order_delivery_by'] = $_POST['purchase_order_delivery_by'];
        $data['employee_id'] = $_POST['employee_id'];

        $output = $purchase_order_model->insertPurchaseOrder($data);

        if($output > 0){
?>
        <script>window.location="index.php?app=purchase_order&action=update&id=<?php echo $output;?>"</script>
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
    
    if(isset($_POST['purchase_order_type'])){
        $data = [];
        $data['purchase_order_type'] = $_POST['purchase_order_type'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['purchase_order_code'] = $_POST['purchase_order_code'];
        $data['purchase_order_date'] = $_POST['purchase_order_date'];
        $data['purchase_order_credit_term'] = $_POST['purchase_order_credit_term'];
        $data['purchase_order_accept_status'] = 'Waiting';
        $data['purchase_order_status'] = 'Waiting';
        $data['purchase_order_delivery_by'] = $_POST['purchase_order_delivery_by'];
        $data['employee_id'] = $_POST['employee_id'];

        $output = $purchase_order_model->updatePurchaseOrderByID($purchase_order_id,$data);

        $notification_model->setNotification("Purchase Order","Purchase Order <br>No. ".$data['purchase_order_code']." ".$data['urgent_status'],"index.php?app=purchase_order&action=detail&id=$purchase_order_id","license_manager_page","'High'");
        
        
        $product_id = $_POST['product_id'];
        $purchase_order_list_qty = $_POST['purchase_order_list_qty'];
        $purchase_order_list_price = $_POST['purchase_order_list_price'];
        $purchase_order_list_price_sum = $_POST['purchase_order_list_price_sum'];
        $purchase_order_list_delivery_min = $_POST['purchase_order_list_delivery_min'];
        $purchase_order_list_delivery_max = $_POST['purchase_order_list_delivery_max'];
        $purchase_order_list_remark = $_POST['purchase_order_list_remark'];

        $purchase_order_list_model->deletePurchaseOrderListByPurchaseOrderID($purchase_order_id);
        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data = [];
                $data['purchase_order_id'] = $purchase_order_id;
                $data['product_id'] = $product_id[$i];
                $data['purchase_order_list_qty'] = $purchase_order_list_qty[$i];
                $data['purchase_order_list_price'] = $purchase_order_list_price[$i];
                $data['purchase_order_list_price_sum'] = $purchase_order_list_price_sum[$i];
                $data['purchase_order_list_delivery_min'] = $purchase_order_list_delivery_min[$i];
                $data['purchase_order_list_delivery_max'] = $purchase_order_list_delivery_max[$i];
                $data['purchase_order_list_remark'] = $purchase_order_list_remark[$i];
    
                $purchase_order_list_model->insertPurchaseOrderList($data);
            }
        }else{
            $data = [];
            $data['purchase_order_id'] = $purchase_order_id;
            $data['product_id'] = $product_id;
            $data['purchase_order_list_qty'] = $purchase_order_list_qty;
            $data['purchase_order_list_price'] = $purchase_order_list_price;
            $data['purchase_order_list_price_sum'] = $purchase_order_list_price_sum;
            $data['purchase_order_list_delivery_min'] = $purchase_order_list_delivery_min;
            $data['purchase_order_list_delivery_max'] = $purchase_order_list_delivery_max;
            $data['purchase_order_list_remark'] = $purchase_order_list_remark;

            $purchase_order_list_model->insertPurchaseOrderList($data);
        }
        

        if($output){
?>
        <script>window.location="index.php?app=purchase_order"</script>
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
        
        
    
}else if ($_GET['action'] == 'approve'){
    
    if(isset($_POST['purchase_order_accept_status'])){
        $data = [];
        $data['purchase_order_accept_status'] = $_POST['purchase_order_accept_status'];
        $data['purchase_order_accept_by'] = $user[0][0];
        $data['purchase_order_status'] = 'Approved';
        $data['updateby'] = $user[0][0];

        $output = $purchase_order_model->updatePurchaseOrderAcceptByID($purchase_order_id,$data);


        if($output){
            $notification_model->setNotificationSeenByURL('action=detail&id='.$purchase_order_id);
        
?>
        <script>window.location="index.php?app=purchase_order"</script>
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

    $purchase_orders = $purchase_order_model->getPurchaseOrderBy();
    require_once($path.'view.inc.php');

}





?>