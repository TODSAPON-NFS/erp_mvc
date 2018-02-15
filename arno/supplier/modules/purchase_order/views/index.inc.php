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
$supplier_id = $_GET['supplier_id'];
if ($_GET['action'] == 'checking'){
    $products=$product_model->getProductBy();
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $purchase_order = $purchase_order_model->getPurchaseOrderByID($purchase_order_id);
    $supplier=$supplier_model->getSupplierByID($purchase_order['supplier_id']);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);
    require_once($path.'check.inc.php');

}else if ($_GET['action'] == 'confirm'){
    $products=$product_model->getProductBy();
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $purchase_order = $purchase_order_model->getPurchaseOrderByID($purchase_order_id);
    $supplier=$supplier_model->getSupplierByID($purchase_order['supplier_id']);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);
    require_once($path.'check.inc.php');

}else if ($_GET['action'] == 'update_checking'){
    
    if(isset($_POST['purchase_order_list_id'])){

        $purchase_order_list_id = $_POST['purchase_order_list_id'];
        $purchase_order_list_supplier_qty = $_POST['purchase_order_list_supplier_qty'];
        $purchase_order_list_supplier_delivery_min = $_POST['purchase_order_list_supplier_delivery_min'];
        $purchase_order_list_supplier_delivery_max = $_POST['purchase_order_list_supplier_delivery_max'];
        $purchase_order_list_supplier_remark = $_POST['purchase_order_list_supplier_remark'];

        if(is_array($purchase_order_list_id)){
            for($i=0; $i < count($purchase_order_list_id) ; $i++){
                $data_sub = [];
                $data_sub['purchase_order_list_supplier_qty'] = $purchase_order_list_supplier_qty[$i];
                $data_sub['purchase_order_list_supplier_delivery_min'] = $purchase_order_list_supplier_delivery_min[$i];
                $data_sub['purchase_order_list_supplier_delivery_max'] = $purchase_order_list_supplier_delivery_max[$i];
                $data_sub['purchase_order_list_supplier_remark'] = $purchase_order_list_supplier_remark[$i];
    
                $purchase_order_list_model->updatePurchaseOrderListById($data_sub,$purchase_order_list_id[$i]);
            }
        }else if($purchase_order_list_id != ""){
            $data_sub = [];
            $data_sub['purchase_order_list_supplier_qty'] = $purchase_order_list_supplier_qty;
            $data_sub['purchase_order_list_supplier_delivery_min'] = $purchase_order_list_supplier_delivery_min;
            $data_sub['purchase_order_list_supplier_delivery_max'] = $purchase_order_list_supplier_delivery_max;
            $data_sub['purchase_order_list_supplier_remark'] = $purchase_order_list_supplier_remark;

            $purchase_order_list_model->updatePurchaseOrderListById($data_sub,$purchase_order_list_id);
        }
        
        $data = [];
        $data['purchase_order_status'] = 'New';
        $output = $purchase_order_model->updatePurchaseOrderAcceptByID($purchase_order_id,$data);


        if($output){
            $notification_model->setNotification("Purchase Request","Purchase Request <br>No. ".$data['purchase_request_code']." ".$data['urgent_status'],"index.php?app=purchase_request&action=detail&id=$purchase_request_id","license_manager_page","'High'");
        
?>
        <script>
            alert("Update checking complete.");
            window.location="index.php?app=purchase_order&action=checking&id=<?php echo $purchase_order_id;?>"
        </script>
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
        if($_POST['purchase_order_accept_status'] == 'Approve'){
            $data['purchase_order_status'] = 'Approved';
        }else if($_POST['purchase_order_accept_status'] == 'Waitting'){
            $data['purchase_order_status'] = 'Request';
        }else {
            $data['purchase_order_status'] = 'New';
        }
        
        
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
        
        
    
}




?>