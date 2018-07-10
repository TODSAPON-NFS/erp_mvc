<?php
session_start();
require_once('../models/RequestRegrindModel.php');
require_once('../models/RequestRegrindListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/request_regrind/views/";
$user_model = new UserModel;
$customer_model = new CustomerModel;
$supplier_model = new SupplierModel;
$notification_model = new NotificationModel;
$request_regrind_model = new RequestRegrindModel;
$request_regrind_list_model = new RequestRegrindListModel;
$product_model = new ProductModel;
$first_char = "RPTR";
$request_regrind_id = $_GET['id'];
$notification_id = $_GET['notification'];

$request_regrind = $request_regrind_model->getRequestRegrindByID($request_regrind_id);
$employee_id = $request_regrind['employee_id'];

if(!isset($_GET['action']) && (($license_request_page == 'Low') || $license_request_page == 'Medium' || $license_request_page == 'High' )){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword'];

    if($license_request_page == 'Medium' || $license_request_page == 'High'){
        $request_regrinds = $request_regrind_model->getRequestRegrindBy($date_start,$date_end,$keyword,'');
    }else{
        $request_regrinds = $request_regrind_model->getRequestRegrindBy($date_start,$date_end,$keyword,$admin_id);
    }
    

    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && (($license_request_page == 'Low') || $license_request_page == 'Medium' || $license_request_page == 'High' ) ){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $request_regrind_model->getRequestRegrindLastID($first_code,3);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && (($license_request_page == 'Low' && $admin_id == $employee_id ) || $license_request_page == 'Medium' || $license_request_page == 'High') ){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $request_regrind = $request_regrind_model->getRequestRegrindByID($request_regrind_id);
    $request_regrind_lists = $request_regrind_list_model->getRequestRegrindListBy($request_regrind_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    if($notification_id != ""){
        $notification_model->setNotificationSeenByID($notification_id);
    }
    $request_regrind = $request_regrind_model->getRequestRegrindViewByID($request_regrind_id);
    $request_regrind_lists = $request_regrind_list_model->getRequestRegrindListBy($request_regrind_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete' && (($license_request_page == 'Low' && $admin_id == $employee_id ) || $license_request_page == 'High') ){

    $request_regrind_list_model->deleteRequestRegrindListByRequestRegrindID($request_regrind_id);
    $request_regrinds = $request_regrind_model->deleteRequestRegrindById($request_regrind_id);
?>
    <script>window.location="index.php?app=request_regrind"</script>
<?php

}else if ($_GET['action'] == 'cancelled' && (($license_request_page == 'Low' && $admin_id == $employee_id ) || $license_request_page == 'Medium' || $license_request_page == 'High') ){
    $request_regrind_model->cancelRequestRegrindById($request_regrind_id);
?>
    <script>window.location="index.php?app=request_regrind"</script>
<?php

}else if ($_GET['action'] == 'uncancelled' && (($license_request_page == 'Low' && $admin_id == $employee_id ) || $license_request_page == 'Medium' || $license_request_page == 'High') ){
    $request_regrind_model->uncancelRequestRegrindById($request_regrind_id);
?>
    <script>window.location="index.php?app=request_regrind"</script>
<?php

}else if ($_GET['action'] == 'add' && (( $license_request_page == 'Low' ) || $license_request_page == 'Medium' || $license_request_page == 'High') ){
    if(isset($_POST['request_regrind_code'])){
        $data = [];
        $data['request_regrind_date'] = date("d")."-".date("m")."-".date("Y");
        $data['request_regrind_code'] = $_POST['request_regrind_code']; 
        $data['request_regrind_accept_status'] = "Waiting";
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['request_regrind_remark'] = $_POST['request_regrind_remark'];
        $data['purchase_order_open'] = $_POST['purchase_order_open'];
        
        $request_regrind_id = $request_regrind_model->insertRequestRegrind($data);

        if($request_regrind_id > 0){
            $product_id = $_POST['product_id'];
            $request_regrind_list_id = $_POST['request_regrind_list_id'];
            $request_regrind_list_qty = $_POST['request_regrind_list_qty'];
            $request_regrind_list_delivery_min = $_POST['request_regrind_list_delivery'];
            $request_regrind_list_remark = $_POST['request_regrind_list_remark'];
            $tool_test_result = $_POST['tool_test_result'];

            $request_regrind_list_model->deleteRequestRegrindListByRequestRegrindIDNotIN($request_regrind_id,$request_regrind_list_id);

            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data = [];
                    $data['request_regrind_id'] = $request_regrind_id;
                    $data['product_id'] = $product_id[$i];
                    $data['request_regrind_list_qty'] = $request_regrind_list_qty[$i];
                    $data['request_regrind_list_delivery'] = $request_regrind_list_delivery_min[$i];
                    $data['request_regrind_list_remark'] = $request_regrind_list_remark[$i];
                    $data['tool_test_result'] = $tool_test_result[$i];

                    if($request_regrind_list_id[$i] == 0){
                        $request_regrind_list_model->insertRequestRegrindList($data);
                    }else{
                        $request_regrind_list_model->updateRequestRegrindListById($data,$request_regrind_list_id[$i]);
                    }
                    
                    
                }
            }else{
                $data = [];
                $data['request_regrind_id'] = $request_regrind_id;
                $data['product_id'] = $product_id;
                $data['request_regrind_list_qty'] = $request_regrind_list_qty;
                $data['request_regrind_list_delivery'] = $request_regrind_list_delivery_min;
                $data['request_regrind_list_remark'] = $request_regrind_list_remark;
                $data['tool_test_result'] = $tool_test_result;
                
                if($request_regrind_list_id == 0){
                    $request_regrind_list_model->insertRequestRegrindList($data);
                }else{
                    $request_regrind_list_model->updateRequestRegrindListById($data,$request_regrind_list_id);
                }
            }
?>
        <script>window.location="index.php?app=request_regrind&action=update&id=<?php echo $request_regrind_id;?>"</script>
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
    
    if(isset($_POST['request_regrind_code'])){
        $data = [];
        $data['request_regrind_date'] = $_POST['request_regrind_date'];
        $data['request_regrind_code'] = $_POST['request_regrind_code']; 
        $data['request_regrind_accept_status'] = "Waiting";
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['request_regrind_remark'] = $_POST['request_regrind_remark'];
        $data['purchase_order_open'] = $_POST['purchase_order_open'];

        $output = $request_regrind_model->updateRequestRegrindByID($request_regrind_id,$data);

        $notification_model->setNotification("Regrind Tool Request ","Regrind Tool Request  <br>No. ".$data['request_regrind_code']." ".$data['urgent_status'],"index.php?app=request_regrind&action=detail&id=$request_regrind_id","license_manager_page","'High'");
        
        $product_id = $_POST['product_id'];
        $request_regrind_list_id = $_POST['request_regrind_list_id'];
        $request_regrind_list_qty = $_POST['request_regrind_list_qty'];
        $request_regrind_list_delivery_min = $_POST['request_regrind_list_delivery'];
        $request_regrind_list_remark = $_POST['request_regrind_list_remark'];
        $tool_test_result = $_POST['tool_test_result'];

        $request_regrind_list_model->deleteRequestRegrindListByRequestRegrindIDNotIN($request_regrind_id,$request_regrind_list_id);

        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data = [];
                $data['request_regrind_id'] = $request_regrind_id;
                $data['product_id'] = $product_id[$i];
                $data['request_regrind_list_qty'] = $request_regrind_list_qty[$i];
                $data['request_regrind_list_delivery'] = $request_regrind_list_delivery_min[$i];
                $data['request_regrind_list_remark'] = $request_regrind_list_remark[$i];
                $data['tool_test_result'] = $tool_test_result[$i];

                if($request_regrind_list_id[$i] == 0){
                    $request_regrind_list_model->insertRequestRegrindList($data);
                }else{
                    $request_regrind_list_model->updateRequestRegrindListById($data,$request_regrind_list_id[$i]);
                }
                
                
            }
        }else{
            $data = [];
            $data['request_regrind_id'] = $request_regrind_id;
            $data['product_id'] = $product_id;
            $data['request_regrind_list_qty'] = $request_regrind_list_qty;
            $data['request_regrind_list_delivery'] = $request_regrind_list_delivery_min;
            $data['request_regrind_list_remark'] = $request_regrind_list_remark;
            $data['tool_test_result'] = $tool_test_result;

            if($request_regrind_list_id == 0){
                $request_regrind_list_model->insertRequestRegrindList($data);
            }else{
                $request_regrind_list_model->updateRequestRegrindListById($data,$request_regrind_list_id);
            }
        }

        if($output){
?>
        <script>window.location="index.php?app=request_regrind"</script>
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

        $request_regrind = $request_regrind_model->getRequestRegrindByID($request_regrind_id);
        $request_regrind_lists = $request_regrind_list_model->getRequestRegrindListBy($request_regrind_id);
        $request_regrind_model->cancelRequestRegrindById($request_regrind_id);

        $data = [];
        $data['request_regrind_date'] = $request_regrind['request_regrind_date'];
        $data['request_regrind_code'] = $request_regrind['request_regrind_code']; 
        $data['request_regrind_accept_status'] = "Waiting";
        $data['employee_id'] = $request_regrind['employee_id'];
        $data['customer_id'] = $request_regrind['customer_id'];
        $data['supplier_id'] = $request_regrind['supplier_id'];
        $data['request_regrind_rewrite_id'] = $request_regrind_id;
        $data['request_regrind_rewrite_no'] = $request_regrind['request_regrind_rewrite_no'] + 1;
        $data['request_regrind_remark'] = $request_regrind['request_regrind_remark'];
        $data['purchase_order_open'] = $request_regrind['purchase_order_open'];

        $request_regrind_id = $request_regrind_model->insertRequestRegrind($data);

        if($request_regrind_id > 0){
 
            if(count($request_regrind_lists) > 0){
                for($i=0; $i < count($request_regrind_lists) ; $i++){
                    $data = [];
                    $data['request_regrind_id'] = $request_regrind_id;
                    $data['product_id'] = $request_regrind_lists[$i]['product_id'];
                    $data['request_regrind_list_qty'] = $request_regrind_lists[$i]['request_regrind_list_qty'];
                    $data['request_regrind_list_delivery'] = $request_regrind_lists[$i]['request_regrind_list_delivery_min'];
                    $data['request_regrind_list_remark'] = $request_regrind_lists[$i]['request_regrind_list_remark'];
                    $data['tool_test_result'] = $request_regrind_lists[$i]['tool_test_result'];
                    $data['request_test_list_id'] = $request_regrind_lists[$i]['request_test_list_id'];
                    $request_regrind_list_model->insertRequestRegrindList($data); 
                }
            }
?>
        <script>window.location="index.php?app=request_regrind&action=update&id=<?php echo $request_regrind_id;?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }

}else if ($_GET['action'] == 'approve'){
    
    if(isset($_POST['request_regrind_accept_status'])){
        $data = [];
        $data['request_regrind_accept_status'] = $_POST['request_regrind_accept_status'];
        $data['request_regrind_accept_by'] = $user[0][0];
        $data['request_regrind_status'] = 'Approved';
        $data['updateby'] = $user[0][0];

        $output = $request_regrind_model->updateRequestRegrindAcceptByID($request_regrind_id,$data);


        if($output){
            $notification_model->setNotificationSeenByURL('action=detail&id='.$request_regrind_id);
        
?>
        <script>window.location="index.php?app=request_regrind"</script>
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
        $request_regrinds = $request_regrind_model->getRequestRegrindBy($date_start,$date_end,$keyword,'');
    }else{
        $request_regrinds = $request_regrind_model->getRequestRegrindBy($date_start,$date_end,$keyword,$admin_id);
        
    }

    require_once($path.'view.inc.php');

}




?>