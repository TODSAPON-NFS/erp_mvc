<?php
session_start();
$user = $_SESSION['user'];
require_once('../functions/NumbertoTextFunction.func.php');
require_once('../models/FinanceDebitModel.php');
require_once('../models/FinanceDebitListModel.php');
require_once('../models/FinanceDebitPayModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/InvoiceCustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/finance_debit/views/";
$number_2_text = new Number2Text;
$user_model = new UserModel;
$customer_model = new CustomerModel;
$invoice_customer_model = new InvoiceCustomerModel;
$notification_model = new NotificationModel;
$finance_debit_model = new FinanceDebitModel;
$finance_debit_list_model = new FinanceDebitListModel;
$finance_debit_pay_model = new FinanceDebitPayModel;
$finance_debit_id = $_GET['id'];
$notification_id = $_GET['notification'];
$customer_id = $_GET['customer_id'];
$vat = 7;
$first_char = "PS";

if(!isset($_GET['action'])){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();

    $finance_debits = $finance_debit_model->getFinanceDebitBy($date_start,$date_end,$customer_id,$keyword);
    $customer_orders = $finance_debit_model->getCustomerOrder();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $finance_debit_model->getFinanceDebitLastID($first_code,3);
    $customers=$customer_model->getCustomerBy();
    $customer=$customer_model->getCustomerByID($customer_id);
    $finance_debit_lists = $finance_debit_model->generateFinanceDebitListByCustomerId($customer_id);
    $users=$user_model->getUserBy();
   

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){

    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();

    $finance_debit = $finance_debit_model->getFinanceDebitByID($finance_debit_id);

    $customer=$customer_model->getCustomerByID($finance_debit['customer_id']);
    $finance_debit_lists = $finance_debit_list_model->getFinanceDebitListBy($finance_debit_id);
    $finance_debit_pays = $finance_debit_pay_model->getFinanceDebitPayBy($finance_debit_id);

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    
    $finance_debit = $finance_debit_model->getFinanceDebitViewByID($finance_debit_id);
    $finance_debit_lists = $finance_debit_list_model->getFinanceDebitListBy($finance_debit_id);
    
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    
    $finance_debit = $finance_debit_model->getFinanceDebitViewByID($finance_debit_id);
    $finance_debit_lists = $finance_debit_list_model->getFinanceDebitListBy($finance_debit_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){
    $finance_debit_model->deleteFinanceDebitById($finance_debit_id);
?>
    <script>window.location="index.php?app=finance_debit"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['finance_debit_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['finance_debit_code'] = $_POST['finance_debit_code'];
        $data['finance_debit_date'] = $_POST['finance_debit_date'];
        $data['finance_debit_name'] = $_POST['finance_debit_name'];
        $data['finance_debit_address'] = $_POST['finance_debit_address'];
        $data['finance_debit_tax'] = $_POST['finance_debit_tax'];
        $data['finance_debit_remark'] = $_POST['finance_debit_remark'];
        $data['finance_debit_total'] = (float)filter_var($_POST['finance_debit_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_interest'] = (float)filter_var($_POST['finance_debit_interest'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_cash'] = (float)filter_var($_POST['finance_debit_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_other_pay'] = (float)filter_var($_POST['finance_debit_other_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_tax_pay'] = (float)filter_var($_POST['finance_debit_tax_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_discount_cash'] = (float)filter_var($_POST['finance_debit_discount_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_pay'] = (float)filter_var($_POST['finance_debit_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_sent_name'] = $_POST['finance_debit_sent_name'];
        $data['finance_debit_recieve_name'] = $_POST['finance_debit_recieve_name'];
        $data['addby'] = $user[0][0];

        $finance_debit_id = $finance_debit_model->insertFinanceDebit($data);

        
        if($finance_debit_id > 0){
            $data = [];
            $billing_note_list_id = $_POST['billing_note_list_id'];
            $finance_debit_list_amount = $_POST['finance_debit_list_amount'];
            $finance_debit_list_paid = $_POST['finance_debit_list_paid'];
            $finance_debit_list_balance = $_POST['finance_debit_list_balance'];
            $finance_debit_list_remark = $_POST['finance_debit_list_remark'];
            
           
            if(is_array($billing_note_list_id)){
                for($i=0; $i < count($billing_note_list_id) ; $i++){
                    $data_sub = [];
                    $data_sub['finance_debit_id'] = $finance_debit_id;
                    $data_sub['billing_note_list_id'] = $billing_note_list_id[$i];
                    $data_sub['finance_debit_list_amount'] = (float)filter_var($finance_debit_list_amount[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['finance_debit_list_paid'] = (float)filter_var($finance_debit_list_paid[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['finance_debit_list_balance'] = (float)filter_var($finance_debit_list_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['finance_debit_list_remark'] = $finance_debit_list_remark[$i];

                    $id = $finance_debit_list_model->insertFinanceDebitList($data_sub);
                }
            }else if($billing_note_list_id != ""){
                $data_sub = [];
                $data_sub['finance_debit_id'] = $finance_debit_id;
                $data_sub['billing_note_list_id'] = $billing_note_list_id;
                $data_sub['finance_debit_list_amount'] = (float)filter_var($finance_debit_list_amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['finance_debit_list_paid'] = (float)filter_var($finance_debit_list_paid, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['finance_debit_list_balance'] = (float)filter_var($finance_debit_list_balance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['finance_debit_list_remark'] = $finance_debit_list_remark;
    
                $id = $finance_debit_list_model->insertFinanceDebitList($data_sub);
            }

            $finance_debit_pay_id = $_POST['finance_debit_pay_id'];
            $finance_debit_pay_by = $_POST['finance_debit_pay_by'];
            $finance_debit_pay_date = $_POST['finance_debit_pay_date']; 
            $finance_debit_pay_bank = $_POST['finance_debit_pay_bank'];
            $finance_debit_pay_value = $_POST['finance_debit_pay_value'];
            $finance_debit_pay_balance = $_POST['finance_debit_pay_balance'];
            $finance_debit_pay_total = $_POST['finance_debit_pay_total'];



            $finance_debit_pay_model->deleteFinanceDebitPayByFinanceDebitPayIDNotIN($finance_debit_id,$finance_debit_pay_id);

            if(is_array($finance_debit_pay_id)){
                for($i=0; $i < count($finance_debit_pay_id) ; $i++){
                    $data = [];
                    $data['finance_debit_id'] = $finance_debit_id;
                    $data['finance_debit_pay_by'] = $finance_debit_pay_by[$i];
                    $data['finance_debit_pay_date'] = $finance_debit_pay_date[$i];
                    $data['finance_debit_pay_bank'] = $finance_debit_pay_bank[$i];
                    $data['finance_debit_pay_value'] = (float)filter_var($finance_debit_pay_value[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['finance_debit_pay_balance'] = (float)filter_var($finance_debit_pay_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['finance_debit_pay_total'] = (float)filter_var($finance_debit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['updateby'] = $user[0][0];
                    if($finance_debit_pay_id[$i] == 0){
                        $finance_debit_pay_model->insertFinanceDebitPay($data);
                    }else{
                        $finance_debit_pay_model->updateFinanceDebitPayById($data,$finance_debit_pay_id[$i]);
                    }
                }
            }else{
                $data = [];
                $data['finance_debit_id'] = $finance_debit_id;
                $data['finance_debit_pay_by'] = $finance_debit_pay_by;
                $data['finance_debit_pay_date'] = $finance_debit_pay_date;
                $data['finance_debit_pay_bank'] = $finance_debit_pay_bank;
                $data['finance_debit_pay_value'] = (float)filter_var($finance_debit_pay_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['finance_debit_pay_balance'] = (float)filter_var($finance_debit_pay_balance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['finance_debit_pay_total'] = (float)filter_var($finance_debit_pay_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['updateby'] = $user[0][0];

                if($finance_debit_pay_id == 0){
                    $finance_debit_pay_model->insertFinanceDebitPay($data);
                }else{
                    $finance_debit_pay_model->updateFinanceDebitPayById($data,$finance_debit_pay_id);
                }
            }


?>
        <script>window.location="index.php?app=finance_debit&action=update&id=<?php echo $finance_debit_id;?>"</script>
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
    
    if(isset($_POST['finance_debit_code'])){
        
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['finance_debit_code'] = $_POST['finance_debit_code'];
        $data['finance_debit_date'] = $_POST['finance_debit_date'];
        $data['finance_debit_name'] = $_POST['finance_debit_name'];
        $data['finance_debit_address'] = $_POST['finance_debit_address'];
        $data['finance_debit_tax'] = $_POST['finance_debit_tax'];
        $data['finance_debit_remark'] = $_POST['finance_debit_remark'];
        $data['finance_debit_total'] = (float)filter_var($_POST['finance_debit_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_interest'] = (float)filter_var($_POST['finance_debit_interest'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_cash'] = (float)filter_var($_POST['finance_debit_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_other_pay'] = (float)filter_var($_POST['finance_debit_other_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_tax_pay'] = (float)filter_var($_POST['finance_debit_tax_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_discount_cash'] = (float)filter_var($_POST['finance_debit_discount_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_pay'] = (float)filter_var($_POST['finance_debit_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_debit_sent_name'] = $_POST['finance_debit_sent_name'];
        $data['finance_debit_recieve_name'] = $_POST['finance_debit_recieve_name'];
        $data['updateby'] = $user[0][0];


        $billing_note_list_id = $_POST['billing_note_list_id'];
        $finance_debit_list_amount = $_POST['finance_debit_list_amount'];
        $finance_debit_list_paid = $_POST['finance_debit_list_paid'];
        $finance_debit_list_balance = $_POST['finance_debit_list_balance'];
        $finance_debit_list_remark = $_POST['finance_debit_list_remark'];

        
        $finance_debit_list_model->deleteFinanceDebitListByFinanceDebitIDNotIN($finance_debit_id,$finance_debit_list_id);
        
        

        if(is_array($billing_note_list_id)){
            for($i=0; $i < count($billing_note_list_id) ; $i++){
                $data_sub = [];
                $data_sub['finance_debit_id'] = $finance_debit_id;
                $data_sub['billing_note_list_id'] = $billing_note_list_id[$i];
                $data_sub['finance_debit_list_amount'] = (float)filter_var($finance_debit_list_amount[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['finance_debit_list_paid'] = (float)filter_var($finance_debit_list_paid[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['finance_debit_list_balance'] = (float)filter_var($finance_debit_list_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['finance_debit_list_remark'] = $finance_debit_list_remark[$i];

                if($finance_debit_list_id[$i] != '0'){
                    $finance_debit_list_model->updateFinanceDebitListById($data_sub,$finance_debit_list_id[$i]);
                }else{
                    $id = $finance_debit_list_model->insertFinanceDebitList($data_sub);
                }
                
            }
        }else if($billing_note_list_id != ""){
            $data_sub = [];
            $data_sub['finance_debit_id'] = $finance_debit_id;
            $data_sub['billing_note_list_id'] = $billing_note_list_id;
            $data_sub['finance_debit_list_amount'] = (float)filter_var($finance_debit_list_amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['finance_debit_list_paid'] = (float)filter_var($finance_debit_list_paid, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['finance_debit_list_balance'] = (float)filter_var($finance_debit_list_balance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['finance_debit_list_remark'] = $finance_debit_list_remark;

            if($finance_debit_list_id != "0"){
                $finance_debit_list_model->updateFinanceDebitListById($data_sub,$finance_debit_list_id);
            }else{
                $id = $finance_debit_list_model->insertFinanceDebitList($data_sub);
            }
        }

        $finance_debit_pay_id = $_POST['finance_debit_pay_id'];
        $finance_debit_pay_by = $_POST['finance_debit_pay_by'];
        $finance_debit_pay_date = $_POST['finance_debit_pay_date']; 
        $finance_debit_pay_bank = $_POST['finance_debit_pay_bank'];
        $finance_debit_pay_value = $_POST['finance_debit_pay_value'];
        $finance_debit_pay_balance = $_POST['finance_debit_pay_balance'];
        $finance_debit_pay_total = $_POST['finance_debit_pay_total'];



        $finance_debit_pay_model->deleteFinanceDebitPayByFinanceDebitPayIDNotIN($finance_debit_id,$finance_debit_pay_id);

        if(is_array($finance_debit_pay_id)){
            for($i=0; $i < count($finance_debit_pay_id) ; $i++){
                $data = [];
                $data['finance_debit_id'] = $finance_debit_id;
                $data['finance_debit_pay_by'] = $finance_debit_pay_by[$i];
                $data['finance_debit_pay_date'] = $finance_debit_pay_date[$i];
                $data['finance_debit_pay_bank'] = $finance_debit_pay_bank[$i];
                $data['finance_debit_pay_value'] = (float)filter_var($finance_debit_pay_value[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['finance_debit_pay_balance'] = (float)filter_var($finance_debit_pay_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['finance_debit_pay_total'] = (float)filter_var($finance_debit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['updateby'] = $user[0][0];
                if($finance_debit_pay_id[$i] == 0){
                    $finance_debit_pay_model->insertFinanceDebitPay($data);
                }else{
                    $finance_debit_pay_model->updateFinanceDebitPayById($data,$finance_debit_pay_id[$i]);
                }
            }
        }else{
            $data = [];
            $data['finance_debit_id'] = $finance_debit_id;
            $data['finance_debit_pay_by'] = $finance_debit_pay_by;
            $data['finance_debit_pay_date'] = $finance_debit_pay_date;
            $data['finance_debit_pay_bank'] = $finance_debit_pay_bank;
            $data['finance_debit_pay_value'] = (float)filter_var($finance_debit_pay_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['finance_debit_pay_balance'] = (float)filter_var($finance_debit_pay_balance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['finance_debit_pay_total'] = (float)filter_var($finance_debit_pay_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['updateby'] = $user[0][0];

            if($finance_debit_pay_id == 0){
                $finance_debit_pay_model->insertFinanceDebitPay($data);
            }else{
                $finance_debit_pay_model->updateFinanceDebitPayById($data,$finance_debit_pay_id);
            }
        }

        $output = $finance_debit_model->updateFinanceDebitByID($finance_debit_id,$data);
        

        if($output){
        
?>
        <script>window.location="index.php?app=finance_debit&action=update&id=<?php echo $finance_debit_id;?>"</script>
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
    
    $finance_debits = $finance_debit_model->getFinanceDebitBy($date_start,$date_end,$customer_id,$keyword);
    $customer_orders = $finance_debit_model->getCustomerOrder();
    require_once($path.'view.inc.php');

}





?>