<?php
session_start();
require_once('../models/RequestSpecialModel.php');
require_once('../models/RequestSpecialListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/request_special/views/";
$user_model = new UserModel;
$customer_model = new CustomerModel;
$supplier_model = new SupplierModel;
$notification_model = new NotificationModel;
$request_special_model = new RequestSpecialModel;
$request_special_list_model = new RequestSpecialListModel;
$product_model = new ProductModel;
$first_char = "SPTR";
$request_special_id = $_GET['id'];
$notification_id = $_GET['notification'];

$request_special = $request_special_model->getRequestSpecialByID($request_special_id);
$employee_id = $request_special['employee_id'];


if(!isset($_GET['action'])&& (($license_request_page == 'Low') || $license_request_page == 'Medium' || $license_request_page == 'High' )){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword'];
    $request_specials = $request_special_model->getRequestSpecialBy($date_start,$date_end,$keyword,$user_id);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'  && (($license_request_page == 'Low') || $license_request_page == 'Medium' || $license_request_page == 'High' ) ){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $request_special_model->getRequestSpecialLastID($first_code,3);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && (($license_request_page == 'Low' && $admin_id == $employee_id ) || $license_request_page == 'Medium' || $license_request_page == 'High') ){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $request_special = $request_special_model->getRequestSpecialByID($request_special_id);
    $request_special_lists = $request_special_list_model->getRequestSpecialListBy($request_special_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    if($notification_id != ""){
        $notification_model->setNotificationSeenByID($notification_id);
    }
    $request_special = $request_special_model->getRequestSpecialViewByID($request_special_id);
    $request_special_lists = $request_special_list_model->getRequestSpecialListBy($request_special_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete' && ( ($license_request_page == 'Low' && $admin_id == $employee_id ) || $license_request_page == 'High') ){

    $request_special_list_model->deleteRequestSpecialListByRequestSpecialID($request_special_id);
    $request_specials = $request_special_model->deleteRequestSpecialById($request_special_id);
?>
    <script>window.location="index.php?app=request_special"</script>
<?php

}else if ($_GET['action'] == 'cancelled' && (($license_request_page == 'Low' && $admin_id == $employee_id ) ||$license_request_page == 'Medium' || $license_request_page == 'High') ){
    $request_special_model->cancelRequestSpecialById($request_special_id);
?>
    <script>window.location="index.php?app=request_special"</script>
<?php

}else if ($_GET['action'] == 'uncancelled' && (($license_request_page == 'Low' && $admin_id == $employee_id ) ||$license_request_page == 'Medium' || $license_request_page == 'High') ){
    $request_special_model->uncancelRequestSpecialById($request_special_id);
?>
    <script>window.location="index.php?app=request_special"</script>
<?php

}else if ($_GET['action'] == 'add' && (( $license_request_page == 'Low' ) || $license_request_page == 'Medium' || $license_request_page == 'High') ){
    if(isset($_POST['request_special_code'])){
        $data = [];
        $data['request_special_date'] = date("d")."-".date("m")."-".date("Y");
        $data['request_special_code'] = $_POST['request_special_code']; 
        $data['request_special_accept_status'] = "Waiting";
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['request_special_remark'] = $_POST['request_special_remark'];
        $data['purchase_order_open'] = $_POST['purchase_order_open'];
        
        $request_special_id = $request_special_model->insertRequestSpecial($data);

        if($request_special_id > 0){
            $product_id = $_POST['product_id'];
            $request_special_list_id = $_POST['request_special_list_id'];
            $request_special_list_qty = $_POST['request_special_list_qty'];
            $request_special_list_delivery_min = $_POST['request_special_list_delivery'];
            $request_special_list_remark = $_POST['request_special_list_remark'];
            $tool_test_result = $_POST['tool_test_result'];

            $request_special_list_model->deleteRequestSpecialListByRequestSpecialIDNotIN($request_special_id,$request_special_list_id);

            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data = [];
                    $data['request_special_id'] = $request_special_id;
                    $data['product_id'] = $product_id[$i];
                    $data['request_special_list_qty'] = $request_special_list_qty[$i];
                    $data['request_special_list_delivery'] = $request_special_list_delivery_min[$i];
                    $data['request_special_list_remark'] = $request_special_list_remark[$i];
                    $data['tool_test_result'] = $tool_test_result[$i];

                    if($request_special_list_id[$i] == 0){
                        $request_special_list_model->insertRequestSpecialList($data);
                    }else{
                        $request_special_list_model->updateRequestSpecialListById($data,$request_special_list_id[$i]);
                    }
                    
                    
                }
            }else{
                $data = [];
                $data['request_special_id'] = $request_special_id;
                $data['product_id'] = $product_id;
                $data['request_special_list_qty'] = $request_special_list_qty;
                $data['request_special_list_delivery'] = $request_special_list_delivery_min;
                $data['request_special_list_remark'] = $request_special_list_remark;
                $data['tool_test_result'] = $tool_test_result;
                
                if($request_special_list_id == 0){
                    $request_special_list_model->insertRequestSpecialList($data);
                }else{
                    $request_special_list_model->updateRequestSpecialListById($data,$request_special_list_id);
                }
            }
?>
        <script>window.location="index.php?app=request_special&action=update&id=<?php echo $request_special_id;?>"</script>
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
    
    if(isset($_POST['request_special_code'])){
        $data = [];
        $data['request_special_date'] = $_POST['request_special_date'];
        $data['request_special_code'] = $_POST['request_special_code']; 
        $data['request_special_accept_status'] = "Waiting";
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['request_special_remark'] = $_POST['request_special_remark'];
        $data['purchase_order_open'] = $_POST['purchase_order_open'];

        $output = $request_special_model->updateRequestSpecialByID($request_special_id,$data);

        $notification_model->setNotification("Special Tool Request ","Special Tool Request  <br>No. ".$data['request_special_code']." ".$data['urgent_status'],"index.php?app=request_special&action=detail&id=$request_special_id","license_manager_page","'High'");
        
        $product_id = $_POST['product_id'];
        $request_special_list_id = $_POST['request_special_list_id'];
        $request_special_list_qty = $_POST['request_special_list_qty'];
        $request_special_list_delivery_min = $_POST['request_special_list_delivery'];
        $request_special_list_remark = $_POST['request_special_list_remark'];
        $tool_test_result = $_POST['tool_test_result'];

        $request_special_list_model->deleteRequestSpecialListByRequestSpecialIDNotIN($request_special_id,$request_special_list_id);

        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data = [];
                $data['request_special_id'] = $request_special_id;
                $data['product_id'] = $product_id[$i];
                $data['request_special_list_qty'] = $request_special_list_qty[$i];
                $data['request_special_list_delivery'] = $request_special_list_delivery_min[$i];
                $data['request_special_list_remark'] = $request_special_list_remark[$i];
                $data['tool_test_result'] = $tool_test_result[$i];

                if($request_special_list_id[$i] == 0){
                    $request_special_list_model->insertRequestSpecialList($data);
                }else{
                    $request_special_list_model->updateRequestSpecialListById($data,$request_special_list_id[$i]);
                }
                
                
            }
        }else{
            $data = [];
            $data['request_special_id'] = $request_special_id;
            $data['product_id'] = $product_id;
            $data['request_special_list_qty'] = $request_special_list_qty;
            $data['request_special_list_delivery'] = $request_special_list_delivery_min;
            $data['request_special_list_remark'] = $request_special_list_remark;
            $data['tool_test_result'] = $tool_test_result;

            if($request_special_list_id == 0){
                $request_special_list_model->insertRequestSpecialList($data);
            }else{
                $request_special_list_model->updateRequestSpecialListById($data,$request_special_list_id);
            }
        }

        if($output){
?>
        <script>window.location="index.php?app=request_special"</script>
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
        
      
    
}else if ($_GET['action'] == 'rewrite' && (($license_request_page == 'Low' && $admin_id == $employee_id ) ||$license_request_page == 'Medium' || $license_request_page == 'High') ){

        $request_special = $request_special_model->getRequestSpecialByID($request_special_id);
        $request_special_lists = $request_special_list_model->getRequestSpecialListBy($request_special_id);
        $request_special_model->cancelRequestSpecialById($request_special_id);

        $data = [];
        $data['request_special_date'] = $request_special['request_special_date'];
        $data['request_special_code'] = $request_special['request_special_code']; 
        $data['request_special_accept_status'] = "Waiting";
        $data['employee_id'] = $request_special['employee_id'];
        $data['customer_id'] = $request_special['customer_id'];
        $data['supplier_id'] = $request_special['supplier_id'];
        $data['request_special_rewrite_id'] = $request_special_id;
        $data['request_special_rewrite_no'] = $request_special['request_special_rewrite_no'] + 1;
        $data['request_special_remark'] = $request_special['request_special_remark'];
        $data['purchase_order_open'] = $request_special['purchase_order_open'];

        $request_special_id = $request_special_model->insertRequestSpecial($data);

        if($request_special_id > 0){
 
            if(count($request_special_lists) > 0){
                for($i=0; $i < count($request_special_lists) ; $i++){
                    $data = [];
                    $data['request_special_id'] = $request_special_id;
                    $data['product_id'] = $request_special_lists[$i]['product_id'];
                    $data['request_special_list_qty'] = $request_special_lists[$i]['request_special_list_qty'];
                    $data['request_special_list_delivery'] = $request_special_lists[$i]['request_special_list_delivery_min'];
                    $data['request_special_list_remark'] = $request_special_lists[$i]['request_special_list_remark'];
                    $data['tool_test_result'] = $request_special_lists[$i]['tool_test_result'];
                    $data['request_test_list_id'] = $request_special_lists[$i]['request_test_list_id'];
                    $request_special_list_model->insertRequestSpecialList($data); 
                }
            }
?>
        <script>window.location="index.php?app=request_special&action=update&id=<?php echo $request_special_id;?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }

}else if ($_GET['action'] == 'approve'){
    
    if(isset($_POST['request_special_accept_status'])){
        $data = [];
        $data['request_special_accept_status'] = $_POST['request_special_accept_status'];
        $data['request_special_accept_by'] = $user[0][0];
        $data['request_special_status'] = 'Approved';
        $data['updateby'] = $user[0][0];

        $output = $request_special_model->updateRequestSpecialAcceptByID($request_special_id,$data);


        if($output){
            $notification_model->setNotificationSeenByURL('action=detail&id='.$request_special_id);
        
?>
        <script>window.location="index.php?app=request_special"</script>
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
    $request_specials = $request_special_model->getRequestSpecialBy($date_start = "",$date_end = "",$keyword = "",$user_id = "");
    require_once($path.'view.inc.php');

}





?>