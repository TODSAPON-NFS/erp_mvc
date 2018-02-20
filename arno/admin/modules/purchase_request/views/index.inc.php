<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/PurchaseRequestModel.php');
require_once('../models/PurchaseRequestListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/purchase_request/views/";
$user_model = new UserModel;
$customer_model = new CustomerModel;
$notification_model = new NotificationModel;
$purchase_request_model = new PurchaseRequestModel;
$purchase_request_list_model = new PurchaseRequestListModel;
$product_model = new ProductModel;
$first_char = "PR";
$purchase_request_id = $_GET['id'];
$notification_id = $_GET['notification'];
if(!isset($_GET['action'])){

    $purchase_requests = $purchase_request_model->getPurchaseRequestBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();
    $first_code = $first_char.date("y").date("m");
    $last_code = $purchase_request_model->getPurchaseRequestLastID($first_code,3);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();
    $purchase_request = $purchase_request_model->getPurchaseRequestByID($purchase_request_id);
    $purchase_request_lists = $purchase_request_list_model->getPurchaseRequestListBy($purchase_request_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    if($notification_id != ""){
        $notification_model->setNotificationSeenByID($notification_id);
    }
    $purchase_request = $purchase_request_model->getPurchaseRequestViewByID($purchase_request_id);
    $purchase_request_lists = $purchase_request_list_model->getPurchaseRequestListBy($purchase_request_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete'){

    $purchase_request_list_model->deletePurchaseRequestListByPurchaseRequestID($purchase_request_id);
    $purchase_requests = $purchase_request_model->deletePurchaseRequestById($purchase_request_id);
?>
    <script>window.location="index.php?app=purchase_request"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['purchase_request_code'])){
        $data = [];
        $data['purchase_request_date'] = date("Y")."-".date("m")."-".date("d");
        $data['purchase_request_code'] = $_POST['purchase_request_code'];
        $data['purchase_request_type'] = $_POST['purchase_request_type'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['purchase_request_remark'] = $_POST['purchase_request_remark'];

        $output = $purchase_request_model->insertPurchaseRequest($data);

        if($output > 0){
?>
        <script>window.location="index.php?app=purchase_request&action=update&id=<?php echo $output;?>"</script>
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
    
    if(isset($_POST['purchase_request_code'])){
        $data = [];
        $data['purchase_request_date'] = $_POST['purchase_request_date'];
        $data['purchase_request_code'] = $_POST['purchase_request_code'];
        $data['purchase_request_type'] = $_POST['purchase_request_type'];
        $data['purchase_request_status'] = 'Waitting';
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['purchase_request_remark'] = $_POST['purchase_request_remark'];

        $output = $purchase_request_model->updatePurchaseRequestByID($purchase_request_id,$data);

        $notification_model->setNotification("Purchase Request","Purchase Request <br>No. ".$data['purchase_request_code']." ".$data['urgent_status'],"index.php?app=purchase_request&action=detail&id=$purchase_request_id","license_manager_page","'High'");
        
        $product_id = $_POST['product_id'];
        $purchase_request_list_id = $_POST['purchase_request_list_id'];
        $purchase_request_list_qty = $_POST['purchase_request_list_qty'];
        $purchase_request_list_delivery_min = $_POST['purchase_request_list_delivery'];
        $purchase_request_list_remark = $_POST['purchase_request_list_remark'];

        $purchase_request_list_model->deletePurchaseRequestListByPurchaseRequestIDNotIN($purchase_request_id,$purchase_request_list_id);

        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data = [];
                $data['purchase_request_id'] = $purchase_request_id;
                $data['product_id'] = $product_id[$i];
                $data['purchase_request_list_qty'] = $purchase_request_list_qty[$i];
                $data['purchase_request_list_delivery'] = $purchase_request_list_delivery_min[$i];
                $data['purchase_request_list_remark'] = $purchase_request_list_remark[$i];
    
                $purchase_request_list_model->updatePurchaseRquestListById($data,$purchase_request_list_id[$i]);
                echo $i;
            }
        }else{
            $data = [];
            $data['purchase_request_id'] = $purchase_request_id;
            $data['product_id'] = $product_id;
            $data['purchase_request_list_qty'] = $purchase_request_list_qty;
            $data['purchase_request_list_delivery'] = $purchase_request_list_delivery_min;
            $data['purchase_request_list_remark'] = $purchase_request_list_remark;
            echo "---";
            $purchase_request_list_model->updatePurchaseRquestListById($data,$purchase_request_list_id);
        }


        $m_product_id = $_POST['m_product_id'];
        $m_purchase_request_list_qty = $_POST['m_purchase_request_list_qty'];
        $m_purchase_request_list_delivery_min = $_POST['m_purchase_request_list_delivery'];
        $m_purchase_request_list_remark = $_POST['m_purchase_request_list_remark'];


        if(is_array($m_product_id)){
            for($i=0; $i < count($m_product_id) ; $i++){
                $data = [];
                $data['purchase_request_id'] = $purchase_request_id;
                $data['product_id'] = $m_product_id[$i];
                $data['purchase_request_list_qty'] = $m_purchase_request_list_qty[$i];
                $data['purchase_request_list_delivery'] = $m_purchase_request_list_delivery_min[$i];
                $data['purchase_request_list_remark'] = $m_purchase_request_list_remark[$i];
    
                $purchase_request_list_model->insertPurchaseRequestList($data);
            }
        }else if ($m_product_id != ''){
            $data = [];
            $data['purchase_request_id'] = $purchase_request_id;
            $data['product_id'] = $m_product_id;
            $data['purchase_request_list_qty'] = $m_purchase_request_list_qty;
            $data['purchase_request_list_delivery'] = $m_purchase_request_list_delivery_min;
            $data['purchase_request_list_remark'] = $m_purchase_request_list_remark;
            $purchase_request_list_model->insertPurchaseRequestList($data);
        }
        
        if($output){
?>
        <script>window.location="index.php?app=purchase_request"</script>
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
    
    if(isset($_POST['purchase_request_accept_status'])){
        $data = [];
        $data['purchase_request_accept_status'] = $_POST['purchase_request_accept_status'];
        $data['purchase_request_accept_by'] = $user[0][0];
        $data['purchase_request_status'] = 'Approved';
        $data['updateby'] = $user[0][0];

        $output = $purchase_request_model->updatePurchaseRequestAcceptByID($purchase_request_id,$data);


        if($output){
            $notification_model->setNotificationSeenByURL('action=detail&id='.$purchase_request_id);
        
?>
        <script>window.location="index.php?app=purchase_request"</script>
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

    $purchase_requests = $purchase_request_model->getPurchaseRequestBy();
    require_once($path.'view.inc.php');

}





?>