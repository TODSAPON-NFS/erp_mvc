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

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/official_receipt/views/";
$number_2_text = new Number2Text;
$user_model = new UserModel;
$customer_model = new CustomerModel;
$invoice_customer_model = new InvoiceCustomerModel;
$notification_model = new NotificationModel;
$official_receipt_model = new OfficialReceiptModel;
$official_receipt_list_model = new OfficialReceiptListModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('22');

$official_receipt_id = $_GET['id'];
$notification_id = $_GET['notification'];
$customer_id = $_GET['customer_id'];
$vat = 7; 

if($license_account_page == "Medium" || $license_account_page == "High"){
    $lock_1 = "1";
}else{
    $lock_1 = "0";
}

if($license_account_page == "Medium" || $license_account_page == "High"){
    $lock_2 = "1";
}else{
    $lock_2 = "0";
}

if(!isset($_GET['action']) && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){

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

    $customer_id = $_GET['customer_id'];
    $customers=$customer_model->getCustomerBy();

    $official_receipts = $official_receipt_model->getOfficialReceiptBy($date_start,$date_end,$customer_id,$keyword,$lock_1,$lock_2);
    $customer_orders = $official_receipt_model->getCustomerOrder();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){

    $customers=$customer_model->getCustomerBy();
    $customer=$customer_model->getCustomerByID($customer_id);
    $official_receipt_lists = $official_receipt_model->generateOfficialReceiptListByCustomerId($customer_id,$billing_note_list_id ,$_POST['search'],"" );
    $users=$user_model->getUserBy();

    $user=$user_model->getUserByID($admin_id);

    $data = [];
    $data['year'] = date("Y");
    $data['month'] = date("m");
    $data['number'] = "0000000000";
    $data['employee_name'] = $user["user_name_en"];
    $data['customer_code'] = $customer["customer_code"];

    $code = $code_generate->cut2Array($paper['paper_code'],$data);
    $last_code = "";
    for($i = 0 ; $i < count($code); $i++){
    
        if($code[$i]['type'] == "number"){
            $last_code = $official_receipt_model->getOfficialReceiptLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
    $first_date = date("d")."-".date("m")."-".date("Y");
   

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
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

}else if ($_GET['action'] == 'delete' && ( $license_sale_page == "High" ) ){
    $official_receipt_model->deleteOfficialReceiptById($official_receipt_id);
?>
    <script>window.location="index.php?app=official_receipt"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
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
    
}else if ($_GET['action'] == 'edit' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    
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
        
        
    
}else  if ($license_sale_page == "Medium" || $license_sale_page == "High" ) {

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
    
    $customer_id = $_GET['customer_id'];
    $customers=$customer_model->getCustomerBy();

    $official_receipts = $official_receipt_model->getOfficialReceiptBy($date_start,$date_end,$customer_id,$keyword,$lock_1,$lock_2);
    $customer_orders = $official_receipt_model->getCustomerOrder();
    require_once($path.'view.inc.php');

}





?>