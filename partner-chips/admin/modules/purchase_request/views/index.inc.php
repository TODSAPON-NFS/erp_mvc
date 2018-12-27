<?php
session_start();
$user = $_SESSION['user'];
$user_id = $user[0][0];
require_once('../models/PurchaseRequestModel.php');
require_once('../models/PurchaseRequestListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/StockGroupModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');


date_default_timezone_set('asia/bangkok');

$path = "modules/purchase_request/views/";


$user_model = new UserModel;
$customer_model = new CustomerModel;
$notification_model = new NotificationModel;
$purchase_request_model = new PurchaseRequestModel;
$purchase_request_list_model = new PurchaseRequestListModel;
$product_model = new ProductModel;
$supplier_model = new SupplierModel; 
$stock_group_model = new StockGroupModel; 

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('9');

$purchase_request_id = $_GET['id'];
$type = strtoupper($_GET['type']);

$purchase_request = $purchase_request_model->getPurchaseRequestByID($purchase_request_id);

$employee_id = $purchase_request["employee_id"];
if(!isset($_GET['action']) && ($license_purchase_page == "Low" || $license_purchase_page == "Medium" || $license_purchase_page == "High" )){

    if(!isset($_GET['date_start'])){
        $date_start = $_SESSION['date_start'];
    }else{
        $date_start = $_GET['date_start'];
        $_SESSION['date_start'] = $date_start;
    }


    if(!isset($_GET['date_end'])){
        $date_end = $_SESSION['date_end'];
    }else{
        $date_end = $_GET['date_end'];
        $_SESSION['date_end'] = $date_end;
    }

    if(!isset($_GET['keyword'])){
        $keyword = $_SESSION['keyword'];
    }else{
        
        $keyword = $_GET['keyword']; 
        $_SESSION['keyword'] = $keyword;
    }

    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    }

    $customers=$customer_model->getCustomerBy();
    $suppliers=$supplier_model->getSupplierBy();

    if($license_purchase_page == "Medium" || $license_purchase_page == "High" ){
        $purchase_requests = $purchase_request_model->getPurchaseRequestBy($date_start,$date_end,$keyword);
    }else{
        $purchase_requests = $purchase_request_model->getPurchaseRequestBy($date_start,$date_end,$keyword,$user_id);
    }
    

    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_purchase_page == "Low" || $license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    if($type == ''){
        $type = 'STANDARD';
    }

    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $suppliers=$supplier_model->getSupplierBy();
    $stock_groups=$stock_group_model->getStockGroupBy();
    $users=$user_model->getUserBy();

    $user=$user_model->getUserByID($admin_id);

    $data = [];
    $data['year'] = date("Y");
    $data['month'] = date("m");
    $data['number'] = "0000000000";
    $data['employee_name'] = $user["user_name"];

    $code = $code_generate->cut2Array($paper['paper_code'],$data);
    $last_code = "";
    for($i = 0 ; $i < count($code); $i++){
    
        if($code[$i]['type'] == "number"){
            $last_code = $purchase_request_model->getPurchaseRequestLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
    $first_date = date("d")."-".date("m")."-".date("Y");

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && (($license_purchase_page == "Low" && $admin_id == $employee_id) || $license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $suppliers=$supplier_model->getSupplierBy();
    $stock_groups=$stock_group_model->getStockGroupBy();
    $users=$user_model->getUserBy();
    $purchase_request = $purchase_request_model->getPurchaseRequestByID($purchase_request_id);
    $purchase_request_lists = $purchase_request_list_model->getPurchaseRequestListBy($purchase_request_id);

    $first_date = date("d")."-".date("m")."-".date("Y");
    
    if($purchase_request['purchase_request_type'] == "Sale Blanked"){
        $type = 'BLANKED';
    }else{
        $type = 'STANDARD';
    }
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    
    $purchase_request = $purchase_request_model->getPurchaseRequestViewByID($purchase_request_id);
    $purchase_request_lists = $purchase_request_list_model->getPurchaseRequestListBy($purchase_request_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete'){
    $notification_model->deleteNotificationByTypeID('Purchase Request',$purchase_request_id);
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

}else if ($_GET['action'] == 'add' && ($license_purchase_page == "Low" || $license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    if(isset($_POST['purchase_request_code'])){
        $data = [];
        $data['purchase_request_id'] = $_POST['purchase_request_code'];
        $data['purchase_request_code'] = $_POST['purchase_request_code'];
        $data['purchase_request_date'] = $_POST['purchase_request_date'];
        $data['purchase_request_alert'] = $_POST['purchase_request_alert'];
        $data['purchase_request_type'] = $_POST['purchase_request_type'];
        $data['purchase_request_accept_status'] = "Waiting";
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['purchase_request_remark'] = $_POST['purchase_request_remark'];

        $purchase_request_id = $purchase_request_model->insertPurchaseRequest($data); 

        if($purchase_request_id != ''){

            $product_id = $_POST['product_id'];
            $supplier_id = $_POST['supplier_id'];
            $stock_group_id = $_POST['stock_group_id'];
            $purchase_request_list_id = $_POST['purchase_request_list_id'];
            $purchase_request_list_qty = $_POST['purchase_request_list_qty'];
            $purchase_request_list_delivery_min = $_POST['purchase_request_list_delivery'];
            $purchase_request_list_remark = $_POST['purchase_request_list_remark'];


            $purchase_request_list_model->deletePurchaseRequestListByPurchaseRequestIDNotIN($purchase_request_id,$purchase_request_list_id);

            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data = [];
                    $data['purchase_request_id'] = $purchase_request_id;
                    $data['purchase_request_list_no'] = $i;
                    $data['purchase_request_list_id'] = $purchase_request_id.date("YmdHisu").$i;
                    $data['product_id'] = $product_id[$i];
                    $data['supplier_id'] = $supplier_id[$i];
                    $data['stock_group_id'] = $stock_group_id[$i];
                    $data['purchase_request_list_qty'] = $purchase_request_list_qty[$i];
                    $data['purchase_request_list_delivery'] = $purchase_request_list_delivery_min[$i];
                    $data['purchase_request_list_remark'] = $purchase_request_list_remark[$i];
        
                    if($purchase_request_list_id[$i] == '0'){
                        $purchase_request_list_model->insertPurchaseRequestList($data);
                    }else{
                        $purchase_request_list_model->updatePurchaseRquestListById($data,$purchase_request_list_id[$i]);
                    }
                    
                    
                }
            }else{
                $data = [];
                $data['purchase_request_list_id'] = $purchase_request_id.date("YmdHisu").$i;
                $data['purchase_request_list_no'] = 0;
                $data['purchase_request_id'] = $purchase_request_id;
                $data['product_id'] = $product_id;
                $data['supplier_id'] = $supplier_id;
                $data['stock_group_id'] = $stock_group_id;
                $data['purchase_request_list_qty'] = $purchase_request_list_qty;
                $data['purchase_request_list_delivery'] = $purchase_request_list_delivery_min;
                $data['purchase_request_list_remark'] = $purchase_request_list_remark;

                if($purchase_request_list_id == '0'){
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
    
}else if ($_GET['action'] == 'edit' && (($license_purchase_page == "Low" && $admin_id == $employee_id) || $license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    
    if(isset($_POST['purchase_request_code'])){
        $data = [];
        $data['purchase_request_id'] = $_POST['purchase_request_code'];
        $data['purchase_request_code'] = $_POST['purchase_request_code'];
        $data['purchase_request_date'] = $_POST['purchase_request_date'];
        $data['purchase_request_alert'] = $_POST['purchase_request_alert'];
        $data['purchase_request_type'] = $_POST['purchase_request_type'];
        $data['purchase_request_accept_status'] = "Waiting";
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['purchase_request_remark'] = $_POST['purchase_request_remark'];

        $output = $purchase_request_model->updatePurchaseRequestByID($purchase_request_id,$data);

        $product_id = $_POST['product_id'];
        $supplier_id = $_POST['supplier_id'];
        $stock_group_id = $_POST['stock_group_id'];
        $purchase_request_list_id = $_POST['purchase_request_list_id'];
        $purchase_request_list_qty = $_POST['purchase_request_list_qty'];
        $purchase_request_list_delivery_min = $_POST['purchase_request_list_delivery'];
        $purchase_request_list_remark = $_POST['purchase_request_list_remark'];

        $purchase_request_list_model->deletePurchaseRequestListByPurchaseRequestIDNotIN($purchase_request_id,$purchase_request_list_id);

        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data = [];
                $data['purchase_request_list_id'] = $purchase_request_id.date("YmdHisu").$i;
                $data['purchase_request_id'] = $purchase_request_id;
                $data['product_id'] = $product_id[$i];
                $data['supplier_id'] = $supplier_id[$i];
                $data['stock_group_id'] = $stock_group_id[$i];
                $data['purchase_request_list_qty'] = $purchase_request_list_qty[$i];
                $data['purchase_request_list_delivery'] = $purchase_request_list_delivery_min[$i];
                $data['purchase_request_list_remark'] = $purchase_request_list_remark[$i];
    
                if($purchase_request_list_id[$i] == '0'){
                    $purchase_request_list_model->insertPurchaseRequestList($data);
                }else{
                    $purchase_request_list_model->updatePurchaseRquestListById($data,$purchase_request_list_id[$i]);
                }
            }
        }else{
            $data = [];
            $data['purchase_request_list_id'] = $purchase_request_id.date("YmdHisu").$i;
            $data['purchase_request_id'] = $purchase_request_id;
            $data['product_id'] = $product_id;
            $data['supplier_id'] = $supplier_id;
            $data['stock_group_id'] = $stock_group_id;
            $data['purchase_request_list_qty'] = $purchase_request_list_qty;
            $data['purchase_request_list_delivery'] = $purchase_request_list_delivery_min;
            $data['purchase_request_list_remark'] = $purchase_request_list_remark;

            if($purchase_request_list_id == '0'){
                $purchase_request_list_model->insertPurchaseRequestList($data);
            }else{
                $purchase_request_list_model->updatePurchaseRquestListById($data,$purchase_request_list_id);
            }
        }

        if($output){
            $notification_model->setNotification("Purchase Request",$purchase_request_id,"Purchase Request <br>No. ".$_POST['purchase_request_code']." ".$data['urgent_status'],"index.php?app=purchase_request&action=detail&id=$purchase_request_id","license_manager_page",'High');
        
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
        
      
    
}else if ($_GET['action'] == 'rewrite' && (($license_purchase_page == "Low" && $admin_id == $employee_id) || $license_purchase_page == "Medium" || $license_purchase_page == "High" )){

        $purchase_request = $purchase_request_model->getPurchaseRequestByID($purchase_request_id);
        $purchase_request_lists = $purchase_request_list_model->getPurchaseRequestListBy($purchase_request_id);
        $purchase_request_model->cancelPurchaseRequestById($purchase_request_id);

        $data = [];
        $data['purchase_request_rewrite_no'] = $purchase_request['purchase_request_rewrite_no'] + 1;
        $data['purchase_request_id'] = $purchase_request['purchase_request_code']."-REVISE-".$data['purchase_request_rewrite_no'];
        $data['purchase_request_code'] = $data['purchase_request_id'];
        $data['purchase_request_date'] = $purchase_request['purchase_request_date']; 
        $data['purchase_request_type'] = $purchase_request['purchase_request_type'];
        $data['purchase_request_accept_status'] = "Waiting";
        $data['employee_id'] = $purchase_request['employee_id'];
        $data['customer_id'] = $purchase_request['customer_id'];
        $data['purchase_request_rewrite_id'] = $purchase_request_id; 
        $data['purchase_request_remark'] = $purchase_request['purchase_request_remark'];

        $purchase_request_id = $purchase_request_model->insertPurchaseRequest($data);

        if($purchase_request_id != 0){
 
            if(count($purchase_request_lists) > 0){
                for($i=0; $i < count($purchase_request_lists) ; $i++){
                    $data = [];
                    $data['purchase_request_id'] = $purchase_request_id;
                    $data['purchase_request_list_id'] = $purchase_request_id.date("YmdHisu").$i;
                    $data['product_id'] = $purchase_request_lists[$i]['product_id'];
                    $data['supplier_id'] = $purchase_request_lists[$i]['supplier_id'];
                    $data['stock_group_id'] = $purchase_request_lists[$i]['stock_group_id'];
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
        $data['purchase_request_accept_by'] = $admin_id;
        $data['purchase_request_status'] = 'Approved';
        $data['updateby'] = $admin_id;

        $output = $purchase_request_model->updatePurchaseRequestAcceptByID($purchase_request_id,$data);


        if($output){
            $purchase_request = $purchase_request_model->getPurchaseRequestViewByID($purchase_request_id);
            $notification_model->setNotificationSeenByTypeID('Purchase Request',$purchase_request_id);

            $notification_model->setNotification("Purchase Request",$purchase_request_id,"Purchase Request <br>No. ".$purchase_request['purchase_request_code']." has ".$purchase_request['purchase_request_accept_status'],"index.php?app=purchase_request&action=detail&id=$purchase_request_id","license_purchase_page",'High');
            $notification_model->setNotification("Purchase Request",$purchase_request_id,"Purchase Request <br>No. ".$purchase_request['purchase_request_code']." has ".$purchase_request['purchase_request_accept_status'],"index.php?app=purchase_request&action=detail&id=$purchase_request_id","license_purchase_page",'Medium');
            $notification_model->setNotificationByUserID("Purchase Request",$purchase_request_id,"Purchase Request <br>No. ".$purchase_request['purchase_request_code']." has ".$purchase_request['purchase_request_accept_status'],"index.php?app=purchase_request&action=detail&id=$purchase_request_id",$purchase_request['employee_id']);
        
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
        
}else if($license_purchase_page == "Low" || $license_purchase_page == "Medium" || $license_purchase_page == "High" ){

    if(!isset($_GET['date_start'])){
        $date_start = $_SESSION['date_start'];
    }else{
        $date_start = $_GET['date_start'];
        $_SESSION['date_start'] = $date_start;
    }


    if(!isset($_GET['date_end'])){
        $date_end = $_SESSION['date_end'];
    }else{
        $date_end = $_GET['date_end'];
        $_SESSION['date_end'] = $date_end;
    }

    if(!isset($_GET['keyword'])){
        $keyword = $_SESSION['keyword'];
    }else{
        
        $keyword = $_GET['keyword']; 
        $_SESSION['keyword'] = $keyword;
    }

    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    }

    
    if($license_purchase_page == "Medium" || $license_purchase_page == "High" ){
        $purchase_requests = $purchase_request_model->getPurchaseRequestBy($date_start,$date_end,$keyword);
    }else{
        $purchase_requests = $purchase_request_model->getPurchaseRequestBy($date_start,$date_end,$keyword,$user_id);
    }
    require_once($path.'view.inc.php');

}





?>