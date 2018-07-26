<?php
session_start();
require_once('../models/RequestStandardModel.php');
require_once('../models/RequestStandardListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/request_standard/views/";
$user_model = new UserModel;
$customer_model = new CustomerModel;
$supplier_model = new SupplierModel;
$notification_model = new NotificationModel;
$request_standard_model = new RequestStandardModel;
$request_standard_list_model = new RequestStandardListModel;
$product_model = new ProductModel;
$first_char = "STR";
$request_standard_id = $_GET['id']; 

$request_standard = $request_standard_model->getRequestStandardByID($request_standard_id);
$employee_id = $request_standard['employee_id'];

if(!isset($_GET['action'])){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword'];

    if($license_request_page == 'Medium' || $license_request_page == 'High'){ 
        $request_standards = $request_standard_model->getRequestStandardBy($date_start,$date_end,$keyword,'');
    }else{
        $request_standards = $request_standard_model->getRequestStandardBy($date_start,$date_end,$keyword,$admin_id);
    }

    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && (($license_request_page == 'Low') || $license_request_page == 'Medium' || $license_request_page == 'High' ) ){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $request_standard_model->getRequestStandardLastID($first_code,3);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && (($license_request_page == 'Low' && $admin_id == $employee_id ) || $license_request_page == 'Medium' || $license_request_page == 'High') ){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $request_standard = $request_standard_model->getRequestStandardByID($request_standard_id);
    $request_standard_lists = $request_standard_list_model->getRequestStandardListBy($request_standard_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){ 
    $request_standard = $request_standard_model->getRequestStandardViewByID($request_standard_id);
    $request_standard_lists = $request_standard_list_model->getRequestStandardListBy($request_standard_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete' && (($license_request_page == 'Low' && $admin_id == $employee_id ) || $license_request_page == 'High') ){
    $notification_model->deleteNotificationByTypeID('Standard Tool Request',$request_standard_id);
    $request_standard_list_model->deleteRequestStandardListByRequestStandardID($request_standard_id);
    $request_standards = $request_standard_model->deleteRequestStandardById($request_standard_id);
?>
    <script>window.location="index.php?app=request_standard"</script>
<?php

}else if ($_GET['action'] == 'cancelled' && (($license_request_page == 'Low' && $admin_id == $employee_id ) || $license_request_page == 'Medium' || $license_request_page == 'High') ){
    $request_standard_model->cancelRequestStandardById($request_standard_id);
?>
    <script>window.location="index.php?app=request_standard"</script>
<?php

}else if ($_GET['action'] == 'uncancelled' && (($license_request_page == 'Low' && $admin_id == $employee_id ) || $license_request_page == 'Medium' || $license_request_page == 'High') ){
    $request_standard_model->uncancelRequestStandardById($request_standard_id);
?>
    <script>window.location="index.php?app=request_standard"</script>
<?php

}else if ($_GET['action'] == 'add' && (($license_request_page == 'Low') || $license_request_page == 'Medium' || $license_request_page == 'High') ){
    if(isset($_POST['request_standard_code'])){
        $data = [];
        $data['request_standard_date'] = date("d")."-".date("m")."-".date("Y");
        $data['request_standard_code'] = $_POST['request_standard_code']; 
        $data['request_standard_accept_status'] = "Waiting";
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['request_standard_remark'] = $_POST['request_standard_remark'];
        $data['purchase_order_open'] = $_POST['purchase_order_open'];
        
        $request_standard_id = $request_standard_model->insertRequestStandard($data);

        if($request_standard_id > 0){
            $product_id = $_POST['product_id'];
            $request_standard_list_id = $_POST['request_standard_list_id'];
            $request_standard_list_qty = $_POST['request_standard_list_qty'];
            $request_standard_list_delivery_min = $_POST['request_standard_list_delivery'];
            $request_standard_list_remark = $_POST['request_standard_list_remark'];
            $tool_test_result = $_POST['tool_test_result'];

            $request_standard_list_model->deleteRequestStandardListByRequestStandardIDNotIN($request_standard_id,$request_standard_list_id);

            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data = [];
                    $data['request_standard_id'] = $request_standard_id;
                    $data['product_id'] = $product_id[$i];
                    $data['request_standard_list_qty'] = $request_standard_list_qty[$i];
                    $data['request_standard_list_delivery'] = $request_standard_list_delivery_min[$i];
                    $data['request_standard_list_remark'] = $request_standard_list_remark[$i];
                    $data['tool_test_result'] = $tool_test_result[$i];

                    if($request_standard_list_id[$i] == 0){
                        $request_standard_list_model->insertRequestStandardList($data);
                    }else{
                        $request_standard_list_model->updateRequestStandardListById($data,$request_standard_list_id[$i]);
                    }
                    
                    
                }
            }else{
                $data = [];
                $data['request_standard_id'] = $request_standard_id;
                $data['product_id'] = $product_id;
                $data['request_standard_list_qty'] = $request_standard_list_qty;
                $data['request_standard_list_delivery'] = $request_standard_list_delivery_min;
                $data['request_standard_list_remark'] = $request_standard_list_remark;
                $data['tool_test_result'] = $tool_test_result;
                
                if($request_standard_list_id == 0){
                    $request_standard_list_model->insertRequestStandardList($data);
                }else{
                    $request_standard_list_model->updateRequestStandardListById($data,$request_standard_list_id);
                }
            }
?>
        <script>window.location="index.php?app=request_standard&action=update&id=<?php echo $request_standard_id;?>"</script>
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
    
}else if ($_GET['action'] == 'edit' && (($license_request_page == 'Low' && $admin_id == $employee_id ) || $license_request_page == 'Medium' || $license_request_page == 'High') ){
    
    if(isset($_POST['request_standard_code'])){
        $data = [];
        $data['request_standard_date'] = $_POST['request_standard_date'];
        $data['request_standard_code'] = $_POST['request_standard_code']; 
        $data['request_standard_accept_status'] = "Waiting";
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['request_standard_remark'] = $_POST['request_standard_remark'];
        $data['purchase_order_open'] = $_POST['purchase_order_open'];

        $output = $request_standard_model->updateRequestStandardByID($request_standard_id,$data);

        $notification_model->setNotification("Standard Tool Request","Standard Tool Request  <br>No. ".$data['request_standard_code']." ".$data['urgent_status'],"index.php?app=request_standard&action=detail&id=$request_standard_id","license_manager_page","'High'");
        
        $product_id = $_POST['product_id'];
        $request_standard_list_id = $_POST['request_standard_list_id'];
        $request_standard_list_qty = $_POST['request_standard_list_qty'];
        $request_standard_list_delivery_min = $_POST['request_standard_list_delivery'];
        $request_standard_list_remark = $_POST['request_standard_list_remark'];
        $tool_test_result = $_POST['tool_test_result'];

        $request_standard_list_model->deleteRequestStandardListByRequestStandardIDNotIN($request_standard_id,$request_standard_list_id);

        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data = [];
                $data['request_standard_id'] = $request_standard_id;
                $data['product_id'] = $product_id[$i];
                $data['request_standard_list_qty'] = $request_standard_list_qty[$i];
                $data['request_standard_list_delivery'] = $request_standard_list_delivery_min[$i];
                $data['request_standard_list_remark'] = $request_standard_list_remark[$i];
                $data['tool_test_result'] = $tool_test_result[$i];

                if($request_standard_list_id[$i] == 0){
                    $request_standard_list_model->insertRequestStandardList($data);
                }else{
                    $request_standard_list_model->updateRequestStandardListById($data,$request_standard_list_id[$i]);
                }
                
                
            }
        }else{
            $data = [];
            $data['request_standard_id'] = $request_standard_id;
            $data['product_id'] = $product_id;
            $data['request_standard_list_qty'] = $request_standard_list_qty;
            $data['request_standard_list_delivery'] = $request_standard_list_delivery_min;
            $data['request_standard_list_remark'] = $request_standard_list_remark;
            $data['tool_test_result'] = $tool_test_result;

            if($request_standard_list_id == 0){
                $request_standard_list_model->insertRequestStandardList($data);
            }else{
                $request_standard_list_model->updateRequestStandardListById($data,$request_standard_list_id);
            }
        }

        if($output){
?>
        <script>window.location="index.php?app=request_standard"</script>
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
        
      
    
}else if ($_GET['action'] == 'rewrite' && (($license_request_page == 'Low' && $admin_id == $employee_id ) || $license_request_page == 'Medium' || $license_request_page == 'High') ){

        $request_standard = $request_standard_model->getRequestStandardByID($request_standard_id);
        $request_standard_lists = $request_standard_list_model->getRequestStandardListBy($request_standard_id);
        $request_standard_model->cancelRequestStandardById($request_standard_id);

        $data = [];
        $data['request_standard_date'] = $request_standard['request_standard_date'];
        $data['request_standard_code'] = $request_standard['request_standard_code']; 
        $data['request_standard_accept_status'] = "Waiting";
        $data['employee_id'] = $request_standard['employee_id'];
        $data['customer_id'] = $request_standard['customer_id'];
        $data['supplier_id'] = $request_standard['supplier_id'];
        $data['request_standard_rewrite_id'] = $request_standard_id;
        $data['request_standard_rewrite_no'] = $request_standard['request_standard_rewrite_no'] + 1;
        $data['request_standard_remark'] = $request_standard['request_standard_remark'];
        $data['purchase_order_open'] = $request_standard['purchase_order_open'];

        $request_standard_id = $request_standard_model->insertRequestStandard($data);

        if($request_standard_id > 0){
 
            if(count($request_standard_lists) > 0){
                for($i=0; $i < count($request_standard_lists) ; $i++){
                    $data = [];
                    $data['request_standard_id'] = $request_standard_id;
                    $data['product_id'] = $request_standard_lists[$i]['product_id'];
                    $data['request_standard_list_qty'] = $request_standard_lists[$i]['request_standard_list_qty'];
                    $data['request_standard_list_delivery'] = $request_standard_lists[$i]['request_standard_list_delivery_min'];
                    $data['request_standard_list_remark'] = $request_standard_lists[$i]['request_standard_list_remark'];
                    $data['tool_test_result'] = $request_standard_lists[$i]['tool_test_result'];
                    $data['request_test_list_id'] = $request_standard_lists[$i]['request_test_list_id'];
                    $request_standard_list_model->insertRequestStandardList($data); 
                }
            }
?>
        <script>window.location="index.php?app=request_standard&action=update&id=<?php echo $request_standard_id;?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }

}else if ($_GET['action'] == 'approve'){
    
    if(isset($_POST['request_standard_accept_status'])){
        $data = [];
        $data['request_standard_accept_status'] = $_POST['request_standard_accept_status'];
        $data['request_standard_accept_by'] = $user[0][0];
        $data['request_standard_status'] = 'Approved';
        $data['updateby'] = $user[0][0];

        $output = $request_standard_model->updateRequestStandardAcceptByID($request_standard_id,$data);


        if($output){
            $$notification_model->setNotificationSeenByTypeID('Standard Tool Request',$request_standard_id);
        
?>
        <script>window.location="index.php?app=request_standard"</script>
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
        
        
    
}else if($license_request_page == 'Low' || $license_request_page == 'Medium' || $license_request_page == 'High' ){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword'];
    if($license_request_page == 'Medium' || $license_request_page == 'High'){ 
        $request_standards = $request_standard_model->getRequestStandardBy($date_start,$date_end,$keyword,'');
    }else{
        $request_standards = $request_standard_model->getRequestStandardBy($date_start,$date_end,$keyword,$admin_id);
    }
    require_once($path.'view.inc.php');

}





?>