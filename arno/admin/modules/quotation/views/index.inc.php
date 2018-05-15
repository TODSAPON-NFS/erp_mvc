<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/QuotationModel.php');
require_once('../models/QuotationListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/quotation/views/";
$user_model = new UserModel;
$customer_model = new CustomerModel;
$notification_model = new NotificationModel;
$quotation_model = new QuotationModel;
$quotation_list_model = new QuotationListModel;
$product_model = new ProductModel;
$first_char = "";
$quotation_id = $_GET['id'];
$notification_id = $_GET['notification'];
$vat = 7;
if(!isset($_GET['action'])){

    $quotations = $quotation_model->getQuotationBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $user=$user_model->getUserByID($user[0][0]);
    $users=$user_model->getUserBy();

    $cus_firstcode = strtoupper($customers[0]['customer_code']);
    $emp_firstcode = strtoupper(substr($user['user_name'],0,2));
    $first_code = $cus_firstcode."-".$emp_firstcode."-".date("y").date("m");

    $first_date = date("d")."-".date("m")."-".date("Y");


    $last_code = $quotation_model->getQuotationLastID($first_code,3);
    
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    
    $users=$user_model->getUserBy();
    $quotation = $quotation_model->getQuotationByID($quotation_id);
    $quotation_lists = $quotation_list_model->getQuotationListBy($quotation_id);
    
    $customer=$customer_model->getCustomerByID($quotation['customer_id']);
    $vat = $quotation['quotation_vat'];
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $quotation = $quotation_model->getQuotationViewByID($quotation_id);
    $quotation_lists = $quotation_list_model->getQuotationListBy($quotation_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete'){
    $quotations = $quotation_model->deleteQuotationById($quotation_id);
?>
    <script>window.location="index.php?app=quotation"</script>
<?php

}else if ($_GET['action'] == 'cancelled'){
    $quotations = $quotation_model->cancelQuotationById($quotation_id);
?>
    <script>window.location="index.php?app=quotation"</script>
<?php

}else if ($_GET['action'] == 'uncancelled'){
    $quotations = $quotation_model->uncancelQuotationById($quotation_id);
?>
    <script>window.location="index.php?app=quotation"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['quotation_code'])){
        $data = [];
        $data['quotation_date'] = $_POST['quotation_date'];
        $data['quotation_rewrite_id'] = $_POST['quotation_rewrite_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['quotation_code'] = $_POST['quotation_code'];
        $data['quotation_contact_name'] = $_POST['quotation_contact_name'];
        $data['quotation_contact_tel'] = $_POST['quotation_contact_tel'];
        $data['quotation_contact_email'] = $_POST['quotation_contact_email'];
        $data['quotation_total'] = (float)filter_var($_POST['quotation_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['quotation_vat'] = (float)filter_var($_POST['quotation_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['quotation_vat_price'] = (float)filter_var($_POST['quotation_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['quotation_vat_net'] = (float)filter_var($_POST['quotation_vat_net'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['quotation_remark'] = $_POST['quotation_remark'];

        $quotation_id = $quotation_model->insertQuotation($data);

        $product_id = $_POST['product_id'];
        $quotation_list_id = $_POST['quotation_list_id'];
        $quotation_list_qty = $_POST['quotation_list_qty'];
        $quotation_list_price = $_POST['quotation_list_price'];
        $quotation_list_sum = $_POST['quotation_list_sum'];
        $quotation_list_discount = $_POST['quotation_list_discount'];
        $quotation_list_discount_type = $_POST['quotation_list_discount_type'];
        $quotation_list_total = $_POST['quotation_list_total'];
        $quotation_list_remark = $_POST['quotation_list_remark'];

        $quotation_list_model->deleteQuotationListByQuotationIDNotIN($quotation_id,$quotation_list_id);

        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data = [];
                $data['quotation_id'] = $quotation_id;
                $data['product_id'] = $product_id[$i];
                $data['quotation_list_qty'] = (float)filter_var($quotation_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['quotation_list_price'] = (float)filter_var($quotation_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['quotation_list_sum'] = (float)filter_var($quotation_list_sum[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['quotation_list_discount'] = (float)filter_var($quotation_list_discount[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['quotation_list_discount_type'] = $quotation_list_discount_type[$i];
                $data['quotation_list_total'] = (float)filter_var($quotation_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['quotation_list_remark'] = $quotation_list_remark[$i];
                if($quotation_list_id[$i] == 0){
                    $quotation_list_model->insertQuotationList($data);
                }else{
                    $quotation_list_model->updatePurchaseRquestListById($data,$quotation_list_id[$i]);
                }
            }
        }else{
            $data = [];
            $data['quotation_id'] = $quotation_id;
            $data['product_id'] = $product_id;
            $data['quotation_list_qty'] = (float)filter_var($quotation_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['quotation_list_price'] = (float)filter_var($quotation_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['quotation_list_sum'] = (float)filter_var($quotation_list_sum, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['quotation_list_discount'] = (float)filter_var($quotation_list_discount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['quotation_list_discount_type'] = $quotation_list_discount_type;
            $data['quotation_list_total'] = (float)filter_var($quotation_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['quotation_list_remark'] = $quotation_list_remark;

            if($quotation_list_id == 0){
                $quotation_list_model->insertQuotationList($data);
            }else{
                $quotation_list_model->updatePurchaseRquestListById($data,$quotation_list_id);
            }
        }

        if($quotation_id > 0){
?>
        <script>window.location="index.php?app=quotation&action=update&id=<?php echo $quotation_id;?>"</script>
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
    
    if(isset($_POST['quotation_code'])){
        $data = [];
        $data['quotation_date'] = $_POST['quotation_date'];
        $data['quotation_rewrite_id'] = $_POST['quotation_rewrite_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['quotation_code'] = $_POST['quotation_code'];
        $data['quotation_contact_name'] = $_POST['quotation_contact_name'];
        $data['quotation_contact_tel'] = $_POST['quotation_contact_tel'];
        $data['quotation_contact_email'] = $_POST['quotation_contact_email'];
        $data['quotation_total'] = (float)filter_var($_POST['quotation_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['quotation_vat'] = (float)filter_var($_POST['quotation_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['quotation_vat_price'] = (float)filter_var($_POST['quotation_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['quotation_vat_net'] = (float)filter_var($_POST['quotation_vat_net'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['quotation_remark'] = $_POST['quotation_remark'];

        $output = $quotation_model->updateQuotationByID($quotation_id,$data);

        $product_id = $_POST['product_id'];
        $quotation_list_id = $_POST['quotation_list_id'];
        $quotation_list_qty = $_POST['quotation_list_qty'];
        $quotation_list_price = $_POST['quotation_list_price'];
        $quotation_list_sum = $_POST['quotation_list_sum'];
        $quotation_list_discount = $_POST['quotation_list_discount'];
        $quotation_list_discount_type = $_POST['quotation_list_discount_type'];
        $quotation_list_total = $_POST['quotation_list_total'];
        $quotation_list_remark = $_POST['quotation_list_remark'];

        $quotation_list_model->deleteQuotationListByQuotationIDNotIN($quotation_id,$quotation_list_id);

        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data = [];
                $data['quotation_id'] = $quotation_id;
                $data['product_id'] = $product_id[$i];
                $data['quotation_list_qty'] = (float)filter_var($quotation_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['quotation_list_price'] = (float)filter_var($quotation_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['quotation_list_sum'] = (float)filter_var($quotation_list_sum[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['quotation_list_discount'] = (float)filter_var($quotation_list_discount[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['quotation_list_discount_type'] = $quotation_list_discount_type[$i];
                $data['quotation_list_total'] = (float)filter_var($quotation_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['quotation_list_remark'] = $quotation_list_remark[$i];
                if($quotation_list_id[$i] == 0){
                    $quotation_list_model->insertQuotationList($data);
                }else{
                    $quotation_list_model->updatePurchaseRquestListById($data,$quotation_list_id[$i]);
                }
            }
        }else{
            $data = [];
            $data['quotation_id'] = $quotation_id;
            $data['product_id'] = $product_id;
            $data['quotation_list_qty'] = (float)filter_var($quotation_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['quotation_list_price'] = (float)filter_var($quotation_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['quotation_list_sum'] = (float)filter_var($quotation_list_sum, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['quotation_list_discount'] = (float)filter_var($quotation_list_discount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['quotation_list_discount_type'] = $quotation_list_discount_type;
            $data['quotation_list_total'] = (float)filter_var($quotation_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['quotation_list_remark'] = $quotation_list_remark;

            if($quotation_list_id == 0){
                $quotation_list_model->insertQuotationList($data);
            }else{
                $quotation_list_model->updatePurchaseRquestListById($data,$quotation_list_id);
            }
        }
        
        if($output){
?>
        <script>window.location="index.php?app=quotation&action=update&id=<?PHP echo $quotation_id?>"</script>
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
        
    $quotation = $quotation_model->getQuotationByID($quotation_id);
    $quotation_lists = $quotation_list_model->getQuotationListBy($quotation_id);
    $quotations = $quotation_model->cancelQuotationById($quotation_id);
    
        $data = [];
        $data['quotation_date'] = $quotation['quotation_date'];
        $data['quotation_rewrite_id'] = $quotation_id;
        $data['quotation_rewrite_no'] = $quotation['quotation_rewrite_no'] + 1;
        $data['employee_id'] = $quotation['employee_id'];
        $data['customer_id'] = $quotation['customer_id'];
        $data['quotation_code'] = $quotation['quotation_code'];
        $data['quotation_contact_name'] = $quotation['quotation_contact_name'];
        $data['quotation_contact_tel'] = $quotation['quotation_contact_tel'];
        $data['quotation_contact_email'] = $quotation['quotation_contact_email'];
        $data['quotation_total'] = $quotation['quotation_total'];
        $data['quotation_vat'] = $quotation['quotation_vat'];
        $data['quotation_vat_price'] = $quotation['quotation_vat_price'];
        $data['quotation_vat_net'] = $quotation['quotation_vat_net'];
        $data['quotation_remark'] = $quotation['quotation_remark'];

        $quotation_id = $quotation_model->insertQuotation($data);

        for($i=0; $i < count($quotation_lists) ; $i++){
            $data = [];
            $data['quotation_id'] = $quotation_id;
            $data['product_id'] = $quotation_lists[$i]['product_id'];
            $data['quotation_list_qty'] = $quotation_lists[$i]['quotation_list_qty'];
            $data['quotation_list_price'] = $quotation_lists[$i]['quotation_list_price'];
            $data['quotation_list_sum'] = $quotation_lists[$i]['quotation_list_sum'];
            $data['quotation_list_discount'] = $quotation_lists[$i]['quotation_list_discount'];
            $data['quotation_list_discount_type'] = $quotation_lists[$i]['quotation_list_discount_type'];
            $data['quotation_list_total'] = $quotation_lists[$i]['quotation_list_total'];
            $data['quotation_list_remark'] = $quotation_lists[$i]['quotation_list_remark'];

            $quotation_list_model->insertQuotationList($data);

        }

        if($quotation_id > 0){
?>
        <script>window.location="index.php?app=quotation&action=update&id=<?php echo $quotation_id;?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
    
}else if ($_GET['action'] == 'approve'){
    
    if(isset($_POST['quotation_accept_status'])){
        $data = [];
        $data['quotation_accept_status'] = $_POST['quotation_accept_status'];
        $data['quotation_accept_by'] = $user[0][0];
        $data['quotation_status'] = 'Approved';
        $data['updateby'] = $user[0][0];

        $output = $quotation_model->updateQuotationAcceptByID($quotation_id,$data);


        if($output){
            $notification_model->setNotificationSeenByURL('action=detail&id='.$quotation_id);
        
?>
        <script>window.location="index.php?app=quotation"</script>
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

    $quotations = $quotation_model->getQuotationBy();
    require_once($path.'view.inc.php');

}





?>