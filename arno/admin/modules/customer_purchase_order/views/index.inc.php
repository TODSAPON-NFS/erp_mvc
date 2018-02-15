<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/CustomerPurchaseOrderModel.php');
require_once('../models/CustomerPurchaseOrderListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/customer_purchase_order/views/";
$user_model = new UserModel;
$customer_model = new CustomerModel;
$notification_model = new NotificationModel;
$customer_purchase_order_model = new CustomerPurchaseOrderModel;
$customer_purchase_order_list_model = new CustomerPurchaseOrderListModel;
$product_model = new ProductModel;
$first_char = "PO";
$customer_purchase_order_id = $_GET['id'];
$notification_id = $_GET['notification'];
if(!isset($_GET['action'])){

    $customer_purchase_orders = $customer_purchase_order_model->getCustomerPurchaseOrderBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    
    $users=$user_model->getUserBy();
    $customer_purchase_order = $customer_purchase_order_model->getCustomerPurchaseOrderByID($customer_purchase_order_id);
    $customer=$customer_model->getCustomerByID($customer_purchase_order['customer_id']);
    $customer_purchase_order_lists = $customer_purchase_order_list_model->getCustomerPurchaseOrderListBy($customer_purchase_order_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    if($notification_id != ""){
        $notification_model->setNotificationSeenByID($notification_id);
    }
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    
    $users=$user_model->getUserBy();
    $customer_purchase_order = $customer_purchase_order_model->getCustomerPurchaseOrderByID($customer_purchase_order_id);
    $customer=$customer_model->getCustomerByID($customer_purchase_order['customer_id']);
    $customer_purchase_order_lists = $customer_purchase_order_list_model->getCustomerPurchaseOrderListBy($customer_purchase_order_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete'){

    $customer_purchase_order_list_model->deleteCustomerPurchaseOrderListByCustomerPurchaseOrderID($customer_purchase_order_id);
    $customer_purchase_orders = $customer_purchase_order_model->deleteCustomerPurchaseOrderById($customer_purchase_order_id);
?>
    <script>window.location="index.php?app=customer_purchase_order"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['customer_purchase_order_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_purchase_order_code'] = $_POST['customer_purchase_order_code'];
        $data['customer_purchase_order_date'] = $_POST['customer_purchase_order_date'];
        $data['customer_purchase_order_credit_term'] = $_POST['customer_purchase_order_credit_term'];
        $data['customer_purchase_order_delivery_term'] = $_POST['customer_purchase_order_delivery_term'];
        $data['customer_purchase_order_delivery_by'] = $_POST['customer_purchase_order_delivery_by'];
        $data['customer_purchase_order_status'] = 'Waiting';
        $data['customer_purchase_order_remark'] = $_POST['customer_purchase_order_remark'];

        $output = $customer_purchase_order_model->insertCustomerPurchaseOrder($data);

        if($output > 0){
?>
        <script>window.location="index.php?app=customer_purchase_order&action=update&id=<?php echo $output;?>"</script>
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
    
    if(isset($_POST['customer_purchase_order_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_purchase_order_code'] = $_POST['customer_purchase_order_code'];
        $data['customer_purchase_order_date'] = $_POST['customer_purchase_order_date'];
        $data['customer_purchase_order_credit_term'] = $_POST['customer_purchase_order_credit_term'];
        $data['customer_purchase_order_delivery_term'] = $_POST['customer_purchase_order_delivery_term'];
        $data['customer_purchase_order_delivery_by'] = $_POST['customer_purchase_order_delivery_by'];
        $data['customer_purchase_order_status'] = 'Waiting';
        $data['customer_purchase_order_remark'] = $_POST['customer_purchase_order_remark'];

        $output = $customer_purchase_order_model->updateCustomerPurchaseOrderByID($customer_purchase_order_id,$data);

        $notification_model->setNotification("Customer Purchase Order","Customer Purchase Order <br>No. ".$data['customer_purchase_order_code']." ".$data['urgent_status'],"index.php?app=customer_purchase_order&action=detail&id=$customer_purchase_order_id","license_manager_page","'High'");
        
        
        $product_id = $_POST['product_id'];
        $customer_purchase_order_product_name = $_POST['customer_purchase_order_product_name'];
        $customer_purchase_order_product_detail = $_POST['customer_purchase_order_product_detail'];
        $customer_purchase_order_list_qty = $_POST['customer_purchase_order_list_qty'];
        $customer_purchase_order_list_price = $_POST['customer_purchase_order_list_price'];
        $customer_purchase_order_list_price_sum = $_POST['customer_purchase_order_list_price_sum'];
        $customer_purchase_order_list_delivery_min = $_POST['customer_purchase_order_list_delivery_min'];
        $customer_purchase_order_list_delivery_max = $_POST['customer_purchase_order_list_delivery_max'];
        $customer_purchase_order_list_remark = $_POST['customer_purchase_order_list_remark'];
        $customer_purchase_order_list_hold = $_POST['customer_purchase_order_list_hold'];



        $customer_purchase_order_list_model->deleteCustomerPurchaseOrderListByCustomerPurchaseOrderID($customer_purchase_order_id);
        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data = [];
                $data['customer_purchase_order_id'] = $customer_purchase_order_id;
                $data['product_id'] = $product_id[$i];
                $data['customer_purchase_order_product_name'] = $customer_purchase_order_product_name[$i];
                $data['customer_purchase_order_product_detail'] = $customer_purchase_order_product_detail[$i];
                $data['customer_purchase_order_list_qty'] = $customer_purchase_order_list_qty[$i];
                $data['customer_purchase_order_list_price'] = $customer_purchase_order_list_price[$i];
                $data['customer_purchase_order_list_price_sum'] = $customer_purchase_order_list_price_sum[$i];
                $data['customer_purchase_order_list_delivery_min'] = $customer_purchase_order_list_delivery_min[$i];
                $data['customer_purchase_order_list_delivery_max'] = $customer_purchase_order_list_delivery_max[$i];
                $data['customer_purchase_order_list_remark'] = $customer_purchase_order_list_remark[$i];
                $data['customer_purchase_order_list_hold'] = $customer_purchase_order_list_hold[$i];

                $customer_purchase_order_list_model->insertCustomerPurchaseOrderList($data);
            }
        }else{
            $data = [];
            $data['customer_purchase_order_id'] = $customer_purchase_order_id;
            $data['product_id'] = $product_id;
            $data['customer_purchase_order_product_name'] = $customer_purchase_order_product_name;
            $data['customer_purchase_order_product_detail'] = $customer_purchase_order_product_detail;
            $data['customer_purchase_order_list_qty'] = $customer_purchase_order_list_qty;
            $data['customer_purchase_order_list_price'] = $customer_purchase_order_list_price;
            $data['customer_purchase_order_list_price_sum'] = $customer_purchase_order_list_price_sum;
            $data['customer_purchase_order_list_delivery_min'] = $customer_purchase_order_list_delivery_min;
            $data['customer_purchase_order_list_delivery_max'] = $customer_purchase_order_list_delivery_max;
            $data['customer_purchase_order_list_remark'] = $customer_purchase_order_list_remark;
            $data['customer_purchase_order_list_hold'] = $customer_purchase_order_list_hold;

            $customer_purchase_order_list_model->insertCustomerPurchaseOrderList($data);
        }
        

        if($output){
?>
        <script>window.location="index.php?app=customer_purchase_order"</script>
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
    
    if(isset($_POST['customer_purchase_order_accept_status'])){
        $data = [];
        $data['customer_purchase_order_accept_status'] = $_POST['customer_purchase_order_accept_status'];
        $data['customer_purchase_order_accept_by'] = $user[0][0];
        $data['customer_purchase_order_status'] = 'Approved';
        $data['updateby'] = $user[0][0];

        $output = $customer_purchase_order_model->updateCustomerPurchaseOrderAcceptByID($customer_purchase_order_id,$data);


        if($output){
            $notification_model->setNotificationSeenByURL('action=detail&id='.$customer_purchase_order_id);
        
?>
        <script>window.location="index.php?app=customer_purchase_order"</script>
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

    $customer_purchase_orders = $customer_purchase_order_model->getCustomerPurchaseOrderBy();
    require_once($path.'view.inc.php');

}





?>