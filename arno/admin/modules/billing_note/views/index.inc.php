<?php
session_start();
$user = $_SESSION['user'];
require_once('../functions/NumbertoTextFunction.func.php');
require_once('../models/BillingNoteModel.php');
require_once('../models/BillingNoteListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/InvoiceCustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/billing_note/views/";
$number_2_text = new Number2Text;
$user_model = new UserModel;
$customer_model = new CustomerModel;
$invoice_customer_model = new InvoiceCustomerModel;
$notification_model = new NotificationModel;
$billing_note_model = new BillingNoteModel;
$billing_note_list_model = new BillingNoteListModel;
$billing_note_id = $_GET['id'];
$notification_id = $_GET['notification'];
$customer_id = $_GET['customer_id'];
$vat = 7;
$first_char = "BN";

if(!isset($_GET['action']) && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();

    $billing_notes = $billing_note_model->getBillingNoteBy($date_start,$date_end,$customer_id,$keyword);
    $customer_orders = $billing_note_model->getCustomerOrder();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $billing_note_model->getBillingNoteLastID($first_code,3);
    $customers=$customer_model->getCustomerBy();
    $customer=$customer_model->getCustomerByID($customer_id);
    $users=$user_model->getUserBy();
   

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();

    $billing_note = $billing_note_model->getBillingNoteByID($billing_note_id);

    $customer=$customer_model->getCustomerByID($billing_note['customer_id']);
    $invoice_customers=$invoice_customer_model->getInvoiceCustomerByCustomerID($billing_note['customer_id']);
    $invoice_customer=$invoice_customer_model->getInvoiceCustomerByID($billing_note['invoice_customer_id']);
    $billing_note_lists = $billing_note_list_model->getBillingNoteListBy($billing_note_id);

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    
    $billing_note = $billing_note_model->getBillingNoteViewByID($billing_note_id);
    $billing_note_lists = $billing_note_list_model->getBillingNoteListBy($billing_note_id);
    
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    
    $billing_note = $billing_note_model->getBillingNoteViewByID($billing_note_id);
    $billing_note_lists = $billing_note_list_model->getBillingNoteListBy($billing_note_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete' && ( $license_sale_page == "High" ) ){
    $billing_note_model->deleteBillingNoteById($billing_note_id);
?>
    <script>window.location="index.php?app=billing_note"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    if(isset($_POST['billing_note_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['billing_note_code'] = $_POST['billing_note_code'];
        $data['billing_note_date'] = $_POST['billing_note_date'];
        $data['billing_note_name'] = $_POST['billing_note_name'];
        $data['billing_note_address'] = $_POST['billing_note_address'];
        $data['billing_note_tax'] = $_POST['billing_note_tax'];
        $data['billing_note_remark'] = $_POST['billing_note_remark'];
        $data['billing_note_total'] = (float)filter_var($_POST['billing_note_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['billing_note_sent_name'] = $_POST['billing_note_sent_name'];
        $data['billing_note_recieve_name'] = $_POST['billing_note_recieve_name'];
        $data['addby'] = $user[0][0];

        $output = $billing_note_model->insertBillingNote($data);

        
        if($output > 0){
            $data = [];
            $invoice_customer_id = $_POST['invoice_customer_id'];
            $billing_note_list_amount = $_POST['billing_note_list_amount'];
            $billing_note_list_paid = $_POST['billing_note_list_paid'];
            $billing_note_list_balance = $_POST['billing_note_list_balance'];
            $billing_note_list_remark = $_POST['billing_note_list_remark'];
            
           
            if(is_array($invoice_customer_id)){
                for($i=0; $i < count($invoice_customer_id) ; $i++){
                    $data_sub = [];
                    $data_sub['billing_note_id'] = $output;
                    $data_sub['invoice_customer_id'] = $invoice_customer_id[$i];
                    $data_sub['billing_note_list_amount'] = $billing_note_list_amount[$i];
                    $data_sub['billing_note_list_paid'] = $billing_note_list_paid[$i];
                    $data_sub['billing_note_list_balance'] = $billing_note_list_balance[$i];
                    $data_sub['billing_note_list_remark'] = $billing_note_list_remark[$i];

                    $id = $billing_note_list_model->insertBillingNoteList($data_sub);
                }
            }else if($invoice_customer_id != ""){
                $data_sub = [];
                $data_sub['billing_note_id'] = $output;
                $data_sub['invoice_customer_id'] = $invoice_customer_id;
                $data_sub['billing_note_list_amount'] = $billing_note_list_amount;
                $data_sub['billing_note_list_paid'] = $billing_note_list_paid;
                $data_sub['billing_note_list_balance'] = $billing_note_list_balance;
                $data_sub['billing_note_list_remark'] = $billing_note_list_remark;
    
                $id = $billing_note_list_model->insertBillingNoteList($data_sub);
            }

?>
        <script>window.location="index.php?app=billing_note&action=update&id=<?php echo $output;?>"</script>
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
    
}else if ($_GET['action'] == 'edit' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    
    if(isset($_POST['billing_note_code'])){
        
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['billing_note_code'] = $_POST['billing_note_code'];
        $data['billing_note_date'] = $_POST['billing_note_date'];
        $data['billing_note_name'] = $_POST['billing_note_name'];
        $data['billing_note_address'] = $_POST['billing_note_address'];
        $data['billing_note_tax'] = $_POST['billing_note_tax'];
        $data['billing_note_remark'] = $_POST['billing_note_remark'];
        $data['billing_note_total_old'] = (float)filter_var($_POST['billing_note_total_old'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['billing_note_sent_name'] = $_POST['billing_note_sent_name'];
        $data['billing_note_recieve_name'] = $_POST['billing_note_recieve_name'];
        $data['updateby'] = $user[0][0];


        $invoice_customer_id = $_POST['invoice_customer_id'];
        $billing_note_list_amount = $_POST['billing_note_list_amount'];
        $billing_note_list_paid = $_POST['billing_note_list_paid'];
        $billing_note_list_balance = $_POST['billing_note_list_balance'];
        $billing_note_list_remark = $_POST['billing_note_list_remark'];

        
        $billing_note_list_model->deleteBillingNoteListByBillingNoteIDNotIN($billing_note_id,$billing_note_list_id);
        
        

        if(is_array($invoice_customer_id)){
            for($i=0; $i < count($invoice_customer_id) ; $i++){
                $data_sub = [];
                $data_sub['billing_note_id'] = $billing_note_id;
                $data_sub['invoice_customer_id'] = $invoice_customer_id[$i];
                $data_sub['billing_note_list_amount'] = $billing_note_list_amount[$i];
                $data_sub['billing_note_list_paid'] = $billing_note_list_paid[$i];
                $data_sub['billing_note_list_balance'] = $billing_note_list_balance[$i];
                $data_sub['billing_note_list_remark'] = $billing_note_list_remark[$i];

                if($billing_note_list_id[$i] != '0'){
                    $billing_note_list_model->updateBillingNoteListById($data_sub,$billing_note_list_id[$i]);
                }else{
                    $id = $billing_note_list_model->insertBillingNoteList($data_sub);
                }
                
            }
        }else if($invoice_customer_id != ""){
            $data_sub = [];
            $data_sub['billing_note_id'] = $billing_note_id;
            $data_sub['invoice_customer_id'] = $invoice_customer_id;
            $data_sub['billing_note_list_amount'] = $billing_note_list_amount;
            $data_sub['billing_note_list_paid'] = $billing_note_list_paid;
            $data_sub['billing_note_list_balance'] = $billing_note_list_balance;
            $data_sub['billing_note_list_remark'] = $billing_note_list_remark;

            if($billing_note_list_id != "0"){
                $billing_note_list_model->updateBillingNoteListById($data_sub,$billing_note_list_id);
            }else{
                $id = $billing_note_list_model->insertBillingNoteList($data_sub);
            }
        }

        $output = $billing_note_model->updateBillingNoteByID($billing_note_id,$data);
        

        if($output){
        
?>
        <script>window.location="index.php?app=billing_note&action=update&id=<?php echo $billing_note_id;?>"</script>
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
        
        
    
}else  if ($license_sale_page == "Medium" || $license_sale_page == "High" ) {

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();
    
    $billing_notes = $billing_note_model->getBillingNoteBy($date_start,$date_end,$customer_id,$keyword);
    $customer_orders = $billing_note_model->getCustomerOrder();
    require_once($path.'view.inc.php');

}





?>