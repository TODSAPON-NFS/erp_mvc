<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/OfficialReceiptModel.php');
require_once('../models/OfficialReceiptListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/InvoiceCustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/official_receipt/views/";
$user_model = new UserModel;
$customer_model = new CustomerModel;
$invoice_customer_model = new InvoiceCustomerModel;
$notification_model = new NotificationModel;
$official_receipt_model = new OfficialReceiptModel;
$official_receipt_list_model = new OfficialReceiptListModel;
$official_receipt_id = $_GET['id'];
$notification_id = $_GET['notification'];
$customer_id = $_GET['customer_id'];
$vat = 7;
$first_char = "RE";

if(!isset($_GET['action'])){

    $official_receipts = $official_receipt_model->getOfficialReceiptBy();
    $customer_orders = $official_receipt_model->getCustomerOrder();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $first_code = $first_char.date("y").date("m");
    $last_code = $official_receipt_model->getOfficialReceiptLastID($first_code,3);
    $customers=$customer_model->getCustomerBy();
    $customer=$customer_model->getCustomerByID($customer_id);
    $users=$user_model->getUserBy();
   

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();

    $official_receipt = $official_receipt_model->getOfficialReceiptByID($official_receipt_id);

    $customer=$customer_model->getCustomerByID($official_receipt['customer_id']);
    $invoice_customers=$invoice_customer_model->getInvoiceCustomerByCustomerID($official_receipt['customer_id']);
    $invoice_customer=$invoice_customer_model->getInvoiceCustomerByID($official_receipt['invoice_customer_id']);
    $official_receipt_lists = $official_receipt_list_model->getOfficialReceiptListBy($official_receipt_id);

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    
    $official_receipt = $official_receipt_model->getOfficialReceiptViewByID($official_receipt_id);
    $official_receipt_lists = $official_receipt_list_model->getOfficialReceiptListBy($official_receipt_id);
    
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    
    $official_receipt = $official_receipt_model->getOfficialReceiptViewByID($official_receipt_id);
    $official_receipt_lists = $official_receipt_list_model->getOfficialReceiptListBy($official_receipt_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){
    $official_receipt_model->deleteOfficialReceiptById($official_receipt_id);
?>
    <script>window.location="index.php?app=official_receipt"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['official_receipt_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['official_receipt_code'] = $_POST['official_receipt_code'];
        $data['official_receipt_date'] = $_POST['official_receipt_date'];
        $data['official_receipt_name'] = $_POST['official_receipt_name'];
        $data['official_receipt_address'] = $_POST['official_receipt_address'];
        $data['official_receipt_tax'] = $_POST['official_receipt_tax'];
        $data['official_receipt_remark'] = $_POST['official_receipt_remark'];
        $data['official_receipt_total_old'] = (float)filter_var($_POST['official_receipt_total_old'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['official_receipt_sent_name'] = $_POST['official_receipt_sent_name'];
        $data['official_receipt_recieve_name'] = $_POST['official_receipt_recieve_name'];
        $data['addby'] = $user[0][0];

        $output = $official_receipt_model->insertOfficialReceipt($data);

        
        if($output > 0){
            $data = [];
            $invoice_customer_id = $_POST['invoice_customer_id'];
            $official_receipt_list_remark = $_POST['official_receipt_list_remark'];

            
           
            if(is_array($invoice_customer_id)){
                for($i=0; $i < count($invoice_customer_id) ; $i++){
                    $data_sub = [];
                    $data_sub['official_receipt_id'] = $output;
                    $data_sub['invoice_customer_id'] = $invoice_customer_id[$i];
                    $data_sub['official_receipt_list_remark'] = $official_receipt_list_remark[$i];

                    $id = $official_receipt_list_model->insertOfficialReceiptList($data_sub);
                }
            }else if($invoice_customer_id != ""){
                $data_sub = [];
                $data_sub['official_receipt_id'] = $output;
                $data_sub['invoice_customer_id'] = $invoice_customer_id;
                $data_sub['official_receipt_list_remark'] = $official_receipt_list_remark;
    
                $id = $official_receipt_list_model->insertOfficialReceiptList($data_sub);
            }

?>
        <script>window.location="index.php?app=official_receipt&action=update&id=<?php echo $output;?>"</script>
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
    
    if(isset($_POST['official_receipt_code'])){
        
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['official_receipt_code'] = $_POST['official_receipt_code'];
        $data['official_receipt_date'] = $_POST['official_receipt_date'];
        $data['official_receipt_name'] = $_POST['official_receipt_name'];
        $data['official_receipt_address'] = $_POST['official_receipt_address'];
        $data['official_receipt_tax'] = $_POST['official_receipt_tax'];
        $data['official_receipt_remark'] = $_POST['official_receipt_remark'];
        $data['official_receipt_total_old'] = (float)filter_var($_POST['official_receipt_total_old'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['official_receipt_sent_name'] = $_POST['official_receipt_sent_name'];
        $data['official_receipt_recieve_name'] = $_POST['official_receipt_recieve_name'];
        $data['updateby'] = $user[0][0];


        $invoice_customer_id = $_POST['invoice_customer_id'];
        $official_receipt_list_id = $_POST['official_receipt_list_id'];
         $official_receipt_list_remark = $_POST['official_receipt_list_remark'];

        
        $official_receipt_list_model->deleteOfficialReceiptListByOfficialReceiptIDNotIN($official_receipt_id,$official_receipt_list_id);
        
        

        if(is_array($invoice_customer_id)){
            for($i=0; $i < count($invoice_customer_id) ; $i++){
                $data_sub = [];
                $data_sub['official_receipt_id'] = $official_receipt_id;
                $data_sub['invoice_customer_id'] = $invoice_customer_id[$i];
                $data_sub['official_receipt_list_remark'] = $official_receipt_list_remark[$i];

                if($official_receipt_list_id[$i] != '0'){
                    $official_receipt_list_model->updateOfficialReceiptListById($data_sub,$official_receipt_list_id[$i]);
                }else{
                    $id = $official_receipt_list_model->insertOfficialReceiptList($data_sub);
                }
                
            }
        }else if($invoice_customer_id != ""){
            $data_sub = [];
            $data_sub['official_receipt_id'] = $official_receipt_id;
            $data_sub['invoice_customer_id'] = $invoice_customer_id;
            $data_sub['official_receipt_list_remark'] = $official_receipt_list_remark;

            if($official_receipt_list_id != "0"){
                $official_receipt_list_model->updateOfficialReceiptListById($data_sub,$official_receipt_list_id);
            }else{
                $id = $official_receipt_list_model->insertOfficialReceiptList($data_sub);
            }
        }

        $output = $official_receipt_model->updateOfficialReceiptByID($official_receipt_id,$data);
        

        if($output){
        
?>
        <script>window.location="index.php?app=official_receipt&action=update&id=<?php echo $official_receipt_id;?>"</script>
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

    $official_receipts = $official_receipt_model->getOfficialReceiptBy();
    $customer_orders = $official_receipt_model->getCustomerOrder();
    require_once($path.'view.inc.php');

}





?>