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
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
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

}else if ($_GET['action'] == 'cancelled'){
    $purchase_request_model->cancelPurchaseRequestById($purchase_request_id);
?>
    <script>window.location="index.php?app=purchase_request"</script>
<?php

}else if ($_GET['action'] == 'uncancelled'){
    $purchase_request_model->uncancelPurchaseRequestById($purchase_request_id);
?>
    <script>window.location="index.php?app=purchase_request"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['purchase_request_code'])){
        $data = [];
        $data['purchase_request_date'] = date("Y")."-".date("m")."-".date("d");
        $data['purchase_request_code'] = $_POST['purchase_request_code'];
        $data['purchase_request_type'] = $_POST['purchase_request_type'];
        $data['purchase_request_accept_status'] = "Waiting";
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['purchase_request_remark'] = $_POST['purchase_request_remark'];

        $purchase_request_id = $purchase_request_model->insertPurchaseRequest($data);

        if($purchase_request_id > 0){
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
        
                    if($purchase_request_list_id[$i] == 0){
                        $purchase_request_list_model->insertPurchaseRequestList($data);
                    }else{
                        $purchase_request_list_model->updatePurchaseRquestListById($data,$purchase_request_list_id[$i]);
                    }
                    
                    
                }
            }else{
                $data = [];
                $data['purchase_request_id'] = $purchase_request_id;
                $data['product_id'] = $product_id;
                $data['purchase_request_list_qty'] = $purchase_request_list_qty;
                $data['purchase_request_list_delivery'] = $purchase_request_list_delivery_min;
                $data['purchase_request_list_remark'] = $purchase_request_list_remark;

                if($purchase_request_list_id == 0){
                    $purchase_request_list_model->insertPurchaseRequestList($data);
                }else{
                    $purchase_request_list_model->updatePurchaseRquestListById($data,$purchase_request_list_id);
                }
            }
?>
        <script>window.location="index.php?app=purchase_request&action=update&id=<?php echo $purchase_request_id;?>"</script>
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
        $data['purchase_request_accept_status'] = "Waiting";
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
    
                if($purchase_request_list_id[$i] == 0){
                    $purchase_request_list_model->insertPurchaseRequestList($data);
                }else{
                    $purchase_request_list_model->updatePurchaseRquestListById($data,$purchase_request_list_id[$i]);
                }
                
                
            }
        }else{
            $data = [];
            $data['purchase_request_id'] = $purchase_request_id;
            $data['product_id'] = $product_id;
            $data['purchase_request_list_qty'] = $purchase_request_list_qty;
            $data['purchase_request_list_delivery'] = $purchase_request_list_delivery_min;
            $data['purchase_request_list_remark'] = $purchase_request_list_remark;

            if($purchase_request_list_id == 0){
                $purchase_request_list_model->insertPurchaseRequestList($data);
            }else{
                $purchase_request_list_model->updatePurchaseRquestListById($data,$purchase_request_list_id);
            }
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
        
      
    
}else if ($_GET['action'] == 'rewrite'){

        $purchase_request = $purchase_request_model->getPurchaseRequestByID($purchase_request_id);
        $purchase_request_lists = $purchase_request_list_model->getPurchaseRequestListBy($purchase_request_id);
        $purchase_request_model->cancelPurchaseRequestById($purchase_request_id);

        $data = [];
        $data['purchase_request_date'] = $purchase_request['purchase_request_date'];
        $data['purchase_request_code'] = $purchase_request['purchase_request_code'];
        $data['purchase_request_type'] = $purchase_request['purchase_request_type'];
        $data['purchase_request_accept_status'] = "Waiting";
        $data['employee_id'] = $purchase_request['employee_id'];
        $data['customer_id'] = $purchase_request['customer_id'];
        $data['purchase_request_rewrite_id'] = $purchase_request_id;
        $data['purchase_request_rewrite_no'] = $purchase_request['purchase_request_rewrite_no'] + 1;
        $data['purchase_request_remark'] = $purchase_request['purchase_request_remark'];

        $purchase_request_id = $purchase_request_model->insertPurchaseRequest($data);

        if($purchase_request_id > 0){
 
            if(count($purchase_request_lists) > 0){
                for($i=0; $i < count($purchase_request_lists) ; $i++){
                    $data = [];
                    $data['purchase_request_id'] = $purchase_request_id;
                    $data['product_id'] = $purchase_request_lists[$i]['product_id'];
                    $data['purchase_request_list_qty'] = $purchase_request_lists[$i]['purchase_request_list_qty'];
                    $data['purchase_request_list_delivery'] = $purchase_request_lists[$i]['purchase_request_list_delivery_min'];
                    $data['purchase_request_list_remark'] = $purchase_request_lists[$i]['purchase_request_list_remark'];
                    $purchase_request_list_model->insertPurchaseRequestList($data); 
                }
            }
?>
        <script>window.location="index.php?app=purchase_request&action=update&id=<?php echo $purchase_request_id;?>"</script>
<?php
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