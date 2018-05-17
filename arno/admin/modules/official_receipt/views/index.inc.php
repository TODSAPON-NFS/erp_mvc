<?php
session_start();
$user = $_SESSION['user'];
require_once('../functions/NumbertoTextFunction.func.php');
require_once('../models/OfficialReceiptModel.php');
require_once('../models/OfficialReceiptListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/InvoiceCustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/official_receipt/views/";
$number_2_text = new Number2Text;
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

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();

    $official_receipts = $official_receipt_model->getOfficialReceiptBy($date_start,$date_end,$customer_id,$keyword);
    $customer_orders = $official_receipt_model->getCustomerOrder();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $official_receipt_model->getOfficialReceiptLastID($first_code,3);
    $customers=$customer_model->getCustomerBy();
    $customer=$customer_model->getCustomerByID($customer_id);
    $official_receipt_lists = $official_receipt_model->generateOfficialReceiptListByCustomerId($customer_id,$billing_note_list_id ,$_POST['search'],"" );
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
        $data['official_receipt_total'] = (float)filter_var($_POST['official_receipt_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['official_receipt_sent_name'] = $_POST['official_receipt_sent_name'];
        $data['official_receipt_recieve_name'] = $_POST['official_receipt_recieve_name'];
        $data['addby'] = $user[0][0];

        $official_receipt_id = $official_receipt_model->insertOfficialReceipt($data);

        
        if($official_receipt_id > 0){
            $data = [];
            $official_receipt_list_id = $_POST['official_receipt_list_id'];
            $billing_note_list_id = $_POST['billing_note_list_id'];
            $official_receipt_inv_amount = $_POST['official_receipt_inv_amount'];
            $official_receipt_bal_amount = $_POST['official_receipt_bal_amount'];
            $official_receipt_list_remark = $_POST['official_receipt_list_remark'];

           
            if(is_array($billing_note_list_id)){
                for($i=0; $i < count($billing_note_list_id) ; $i++){
                    $data_sub = [];
                    $data_sub['official_receipt_id'] = $official_receipt_id;
                    $data_sub['billing_note_list_id'] = $billing_note_list_id[$i];
                    $data_sub['official_receipt_inv_amount'] = $official_receipt_inv_amount[$i];
                    $data_sub['official_receipt_bal_amount'] = $official_receipt_bal_amount[$i];
                    $data_sub['official_receipt_list_remark'] = $official_receipt_list_remark[$i];

                    $id = $official_receipt_list_model->insertOfficialReceiptList($data_sub);
                }
            }else if($billing_note_list_id != ""){
                $data_sub = [];
                $data_sub['official_receipt_id'] = $official_receipt_id;
                $data_sub['billing_note_list_id'] = $billing_note_list_id;
                $data_sub['official_receipt_inv_amount'] = $official_receipt_inv_amount;
                $data_sub['official_receipt_bal_amount'] = $official_receipt_bal_amount;
                $data_sub['official_receipt_list_remark'] = $official_receipt_list_remark;
    
                $id = $official_receipt_list_model->insertOfficialReceiptList($data_sub);
            }

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
        $data['official_receipt_total'] = (float)filter_var($_POST['official_receipt_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['official_receipt_sent_name'] = $_POST['official_receipt_sent_name'];
        $data['official_receipt_recieve_name'] = $_POST['official_receipt_recieve_name'];
        $data['updateby'] = $user[0][0];

        $official_receipt_list_id = $_POST['official_receipt_list_id'];
        $billing_note_list_id = $_POST['billing_note_list_id'];
        $official_receipt_inv_amount = $_POST['official_receipt_inv_amount'];
        $official_receipt_bal_amount = $_POST['official_receipt_bal_amount'];
        $official_receipt_list_remark = $_POST['official_receipt_list_remark'];

        
        $official_receipt_list_model->deleteOfficialReceiptListByOfficialReceiptIDNotIN($official_receipt_id,$official_receipt_list_id);
        
        

        if(is_array($billing_note_list_id)){
           
            for($i=0; $i < count($billing_note_list_id) ; $i++){
                $data_sub = [];
                $data_sub['official_receipt_id'] = $official_receipt_id;
                $data_sub['billing_note_list_id'] = $billing_note_list_id[$i];
                $data_sub['official_receipt_inv_amount'] = $official_receipt_inv_amount[$i];
                $data_sub['official_receipt_bal_amount'] = $official_receipt_bal_amount[$i];
                $data_sub['official_receipt_list_remark'] = $official_receipt_list_remark[$i];

                if($official_receipt_list_id[$i] != '0'){
                    $official_receipt_list_model->updateOfficialReceiptListById($data_sub,$official_receipt_list_id[$i]);
                }else{
                    $id = $official_receipt_list_model->insertOfficialReceiptList($data_sub);
                }
                
            }
        }else if($billing_note_list_id != ""){
            $data_sub = [];
            $data_sub['official_receipt_id'] = $official_receipt_id;
            $data_sub['billing_note_list_id'] = $billing_note_list_id;
            $data_sub['official_receipt_inv_amount'] = $official_receipt_inv_amount;
            $data_sub['official_receipt_bal_amount'] = $official_receipt_bal_amount;
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

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();

    $official_receipts = $official_receipt_model->getOfficialReceiptBy($date_start,$date_end,$customer_id,$keyword);
    $customer_orders = $official_receipt_model->getCustomerOrder();
    require_once($path.'view.inc.php');

}





?>