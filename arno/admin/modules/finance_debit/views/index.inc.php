<?php
session_start();
$user = $_SESSION['user'];
require_once('../functions/NumbertoTextFunction.func.php');
require_once('../models/FinanceDebitModel.php');
require_once('../models/FinanceDebitListModel.php');
require_once('../models/FinanceDebitPayModel.php');
require_once('../models/FinanceDebitAccountModel.php');
require_once('../models/BankAccountModel.php');
require_once('../models/BankModel.php');
require_once('../models/AccountModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/InvoiceCustomerModel.php');

require_once('../models/CheckModel.php');

require_once('../models/JournalCashReceiptModel.php');
require_once('../models/JournalCashReceiptListModel.php');

require_once('../models/AccountSettingModel.php');


require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

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
$finance_debit_account_model = new FinanceDebitAccountModel;
$bank_account_model = new BankAccountModel;
$bank_model = new BankModel;
$account_model = new AccountModel;
$check_model = new CheckModel;

$journal_cash_receipt_model = new JournalCashReceiptModel;
$journal_cash_receipt_list_model = new JournalCashReceiptListModel;

$account_setting_model = new AccountSettingModel;
$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('20');


$finance_debit_id = $_GET['id'];
$notification_id = $_GET['notification'];
$customer_id = $_GET['customer_id'];
$vat = 7; 

if(!isset($_GET['action'])){

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

    $customer_id = $_GET['customer_id'];

    $url_search = "&date_start=$date_start&date_end=$date_end&customer_id=$customer_id&keyword=$keyword";

    $customers=$customer_model->getCustomerBy();

    $finance_debits = $finance_debit_model->getFinanceDebitBy($date_start,$date_end,$customer_id,$keyword);
    $customer_orders = $finance_debit_model->getCustomerOrder();

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;
    $list_size = count($finance_debits);
    $page_max = (int)($list_size/$page_size);
    if($list_size%$page_size > 0){
        $page_max += 1;
    }
    
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){ 

    $customers=$customer_model->getCustomerBy();

    $finance_debit_accounts=$finance_debit_account_model->getFinanceDebitAccountNoJoinBy();
    $bank_accounts=$bank_account_model->getBankAccountBy();
    $banks=$bank_model->getBankBy();
    $accounts=$account_model->getAccountAll();

    $customer=$customer_model->getCustomerByID($customer_id);
    if($customer_id != 0){
        $finance_debit_lists = $finance_debit_model->generateFinanceDebitListByCustomerId($customer_id);
    }
    
    $users=$user_model->getUserBy();
   
    $user=$user_model->getUserByID($admin_id);

    $data = [];
    $data['year'] = date("Y");
    $data['month'] = date("m");
    $data['number'] = "0000000000";
    $data['employee_name'] = $user["user_name_en"];

    $code = $code_generate->cut2Array($paper['paper_code'],$data);
    $last_code = "";
    for($i = 0 ; $i < count($code); $i++){
    
        if($code[$i]['type'] == "number"){
            $last_code = $finance_debit_model->getFinanceDebitLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
    $first_date = date("d")."-".date("m")."-".date("Y");

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){

    $customers=$customer_model->getCustomerBy();
    $finance_debit_accounts=$finance_debit_account_model->getFinanceDebitAccountNoJoinBy();
    $bank_accounts=$bank_account_model->getBankAccountBy();
    $banks=$bank_model->getBankBy();
    $accounts=$account_model->getAccountAll();
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
    $journal_cash_receipt_model->deleteJournalCashReceiptByFinanceDebitID($finance_debit_id);
    $finance_debit_model->deleteFinanceDebitById($finance_debit_id);
    
?>
    <script>window.location="index.php?app=finance_debit"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['finance_debit_code'])){

        $journal_cash_receipt_list_debit = 0;
        $journal_cash_receipt_list_credit = 0;

        /* -------------------------------- เพิ่มใบรับชำระเงิน ---------------------------------------*/
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
        /* -------------------------------- สิ้นสุดเพิ่มใบรับชำระเงิน ---------------------------------------*/

        
        if($finance_debit_id > 0){
            

            /* -------------------------------- เพิ่มรายการ Invoice ที่เกี่ยวข้องกับการรับชำระเงิน ---------------------------------------*/
            $data = [];
            $billing_note_list_id = $_POST['billing_note_list_id'];
            $finance_debit_list_recieve = $_POST['finance_debit_list_recieve'];
            $finance_debit_list_receipt = $_POST['finance_debit_list_receipt'];
            $finance_debit_list_amount = $_POST['finance_debit_list_amount'];
            $finance_debit_list_paid = $_POST['finance_debit_list_paid'];
            $finance_debit_list_balance = $_POST['finance_debit_list_balance'];
            $finance_debit_list_remark = $_POST['finance_debit_list_remark'];
            
           
            if(is_array($billing_note_list_id)){
                for($i=0; $i < count($billing_note_list_id) ; $i++){
                    $data_sub = [];
                    $data_sub['finance_debit_id'] = $finance_debit_id;
                    $data_sub['billing_note_list_id'] = $billing_note_list_id[$i];
                    $data_sub['finance_debit_list_recieve'] = $finance_debit_list_recieve[$i];
                    $data_sub['finance_debit_list_receipt'] = $finance_debit_list_receipt[$i];

                    $data_sub['finance_debit_list_amount'] = (float)filter_var($finance_debit_list_amount[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['finance_debit_list_paid'] = (float)filter_var($finance_debit_list_paid[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['finance_debit_list_balance'] = (float)filter_var($finance_debit_list_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['finance_debit_list_remark'] = $finance_debit_list_remark[$i];

                    $id = $finance_debit_list_model->insertFinanceDebitList($data_sub);
                }
            } 

            /* -------------------------------- สิ้นสุดเพิ่มรายการ Invoice ที่เกี่ยวข้องกับการรับชำระเงิน ---------------------------------------*/



            /* -------------------------------- สร้างสมุดรายวันรับชำระเงิน ---------------------------------------*/
            $data = [];
            $data['finance_debit_id'] = $finance_debit_id;
            $data['journal_cash_receipt_date'] = $_POST['finance_debit_date'];
            $data['journal_cash_receipt_code'] = $_POST['finance_debit_code'];
            $data['journal_cash_receipt_name'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
            $data['addby'] = $admin_id;

            $journal_cash_receipt_id = $journal_cash_receipt_model->insertJournalCashReceipt($data);

            /* -------------------------------- สิ้นสุดสร้างสมุดรายวันรับชำระเงิน ---------------------------------------*/

            /* -------------------------------------- Credit เจ้าหนี้การค้า ------------------------------------------*/
            $val = (float)filter_var($_POST['finance_debit_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if($val >= 0){
                $journal_cash_receipt_list_debit = 0;
                $journal_cash_receipt_list_credit = $val;
                
            }else{
                $journal_cash_receipt_list_debit = abs($val);
                $journal_cash_receipt_list_credit = 0;
            }

            $data = [];
            $data['journal_cash_receipt_id'] = $journal_cash_receipt_id;
            $data['finance_debit_pay_id'] = -2;
            $data['account_id'] = $customer['account_id'];
            $data['journal_cash_receipt_list_name'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
            $data['journal_cash_receipt_list_debit'] = $journal_cash_receipt_list_debit;
            $data['journal_cash_receipt_list_credit'] = $journal_cash_receipt_list_credit;

            $journal_cash_receipt_list_model->insertJournalCashReceiptList($data);
            /* ----------------------------------- สิ้นสุด Credit เจ้าหนี้การค้า ---------------------------------------*/

            
            /* -------------------------------- เพิ่มรายการรับชำระเงิน ---------------------------------------*/
            $finance_debit_pay_id = $_POST['finance_debit_pay_id'];
            $check_id = $_POST['check_id'];
            $finance_debit_account_id = $_POST['finance_debit_account_id'];
            $finance_debit_account_cheque = $_POST['finance_debit_account_cheque'];
            $finance_debit_pay_by = $_POST['finance_debit_pay_by'];
            $finance_debit_pay_date = $_POST['finance_debit_pay_date']; 
            $bank_account_id = $_POST['bank_account_id'];
            $finance_debit_pay_bank = $_POST['finance_debit_pay_bank'];
            $account_id = $_POST['account_id'];
            $finance_debit_pay_value = $_POST['finance_debit_pay_value'];
            $finance_debit_pay_balance = $_POST['finance_debit_pay_balance'];
            $finance_debit_pay_total = $_POST['finance_debit_pay_total'];


            if(is_array($finance_debit_pay_id)){

 
                for($i=0; $i < count($finance_debit_pay_id) ; $i++){

                    /* -------------------------------------- เพิ่ม Cheque รับล่วงหน้า ------------------------------------------*/
                    //กรณีรายการนั้นเป็นการรับด้ว Cheque ที่ไม่มีในรายการรับเช็ค
                    if($finance_debit_account_cheque[$i] == 1  && $check_id[$i] == 0){
                        $data = [];
                        $data['check_code'] = $finance_debit_pay_by[$i];
                        $data['check_date_write'] = $finance_debit_pay_date[$i];
                        $data['check_date'] = $finance_debit_pay_date[$i];
                        $data['bank_account_id'] = $bank_account_id[$i];
                        $data['customer_id'] = $_POST['customer_id'];
                        $data['check_remark'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
                        $data['check_total'] = (float)filter_var($finance_debit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data['check_type'] = '0';
                        $data['addby'] = $admin_id;
                
                        $check_id[$i] = $check_model->insertCheck($data);
                    }   
                    /* -------------------------------------- สิ้นสุดเพิ่ม Cheque รับล่วงหน้า ------------------------------------------*/


                    /* -------------------------------- เพิ่มรายการรับชำระเงิน ที่ละรายการ ---------------------------------------*/
                    $data = [];
                    $data['finance_debit_id'] = $finance_debit_id;
                    $data['finance_debit_account_id'] = $finance_debit_account_id[$i];
                    $data['finance_debit_pay_by'] = $finance_debit_pay_by[$i];
                    $data['finance_debit_pay_date'] = $finance_debit_pay_date[$i];
                    $data['check_id'] = $check_id[$i];
                    $data['bank_account_id'] = $bank_account_id[$i];
                    $data['finance_debit_pay_bank'] = $finance_debit_pay_bank[$i];
                    $data['account_id'] = $account_id[$i];
                    $data['finance_debit_pay_value'] = (float)filter_var($finance_debit_pay_value[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['finance_debit_pay_balance'] = (float)filter_var($finance_debit_pay_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['finance_debit_pay_total'] = (float)filter_var($finance_debit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['updateby'] = $user[0][0];
                    
                    $finance_debit_pay_id[$i] = $finance_debit_pay_model->insertFinanceDebitPay($data);
                    
                    /* -------------------------------- สิ้นสุดเพิ่มรายการรับชำระเงิน ที่ละรายการ ---------------------------------------*/


                    /* -------------------------------------- Debit ในสมุดรายวันรับชำระเงิน ------------------------------------------*/
                    $account_setting['cheque_account'] = $account_setting_model->getAccountSettingByID(6); //ดึงข้อมูลเช็ครับล่วงหน้า
                    $val = (float)filter_var($finance_debit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if($val >= 0){
                        $journal_cash_receipt_list_debit = $val;
                        $journal_cash_receipt_list_credit = 0;
                        
                    }else{
                        $journal_cash_receipt_list_debit = 0;
                        $journal_cash_receipt_list_credit = abs($val);
                    }

                    $data = [];
                    $data['journal_cash_receipt_id'] = $journal_cash_receipt_id;
                    $data['finance_debit_pay_id'] = $finance_debit_pay_id[$i];
                    if($check_id[$i] > 0){
                        $data['account_id'] = $account_setting['cheque_account']['account_id'];
                    }else{
                        $data['account_id'] = $account_id[$i];
                    } 
                    $data['journal_cash_receipt_list_name'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
                    $data['journal_cash_receipt_list_debit'] = $journal_cash_receipt_list_debit;
                    $data['journal_cash_receipt_list_credit'] = $journal_cash_receipt_list_credit;
                    $data['journal_cheque_id'] = $check_id[$i];

                    $journal_cash_receipt_list_model->insertJournalCashReceiptList($data);
                    /* ----------------------------------- สิ้นสุด Debit ในสมุดรายวันรับชำระเงิน ---------------------------------------*/
 
                }


                /* -------------------------------------- Debit เงินสดในสมุดรายวันรับชำระเงิน ------------------------------------------*/
                $cash = (float)filter_var($_POST['finance_debit_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                if($cash != 0){
                    //account setting id = 3 เงินสด 
                    $val = $cash;
                    if($val >= 0){
                        $journal_cash_receipt_list_debit = $val;
                        $journal_cash_receipt_list_credit = 0;
                        
                    }else{
                        $journal_cash_receipt_list_debit = 0;
                        $journal_cash_receipt_list_credit = abs($val);
                    }

                    $account_pays = $account_setting_model->getAccountSettingByID(3);
                    $data = [];
                    $data['journal_cash_receipt_id'] = $journal_cash_receipt_id;
                    $data['finance_debit_pay_id'] = -1;
                    $data['account_id'] = $account_pays['account_id'];
                    $data['journal_cash_receipt_list_name'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
                    $data['journal_cash_receipt_list_debit'] = $journal_cash_receipt_list_debit;
                    $data['journal_cash_receipt_list_credit'] = $journal_cash_receipt_list_credit;

                    $journal_cash_receipt_list_model->insertJournalCashReceiptList($data);
                }
 
                /* -------------------------------------- สิ้นสุด Debit เงินสดในสมุดรายวันรับชำระเงิน ------------------------------------------*/



                /* -------------------------------- สิ้นสุดเพิ่มรายการรับชำระเงิน ---------------------------------------*/
            } 


?>
        <script>window.location="index.php?app=finance_debit&action=insert";//window.location="index.php?app=finance_debit&action=update&id=<?php echo $finance_debit_id;?>"</script>
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
        
        $journal_cash_receipt_list_debit = 0;
        $journal_cash_receipt_list_credit = 0;

        /* -------------------------------- อัพเดทใบรับชำระเงิน ---------------------------------------*/
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

        $output = $finance_debit_model->updateFinanceDebitByID($finance_debit_id,$data);
        /* -------------------------------- สิ้นสุด อัพเดทใบรับชำระเงิน ---------------------------------------*/


        /* -------------------------------- เพิ่มหรืออัพเดทรายการ Invoice ที่เกี่ยวข้องกับการรับชำระเงิน ---------------------------------------*/
        $finance_debit_list_id = $_POST['finance_debit_list_id'];
        $billing_note_list_id = $_POST['billing_note_list_id'];
        $finance_debit_list_recieve = $_POST['finance_debit_list_recieve'];
        $finance_debit_list_receipt = $_POST['finance_debit_list_receipt'];
        $finance_debit_list_amount = $_POST['finance_debit_list_amount'];
        $finance_debit_list_paid = $_POST['finance_debit_list_paid'];
        $finance_debit_list_balance = $_POST['finance_debit_list_balance'];
        $finance_debit_list_remark = $_POST['finance_debit_list_remark'];

        
        /* -------------------------------- ล้างรายการ Invoice ที่เกี่ยวข้องกับการรับชำระเงิน ที่ถูกลบไป ---------------------------------------*/
        $finance_debit_list_model->deleteFinanceDebitListByFinanceDebitIDNotIN($finance_debit_id,$finance_debit_list_id);
        /* -------------------------------- สิ้นสุด ล้างรายการ Invoice ที่เกี่ยวข้องกับการรับชำระเงิน ที่ถูกลบไป ---------------------------------------*/
        

        if(is_array($billing_note_list_id)){
            for($i=0; $i < count($billing_note_list_id) ; $i++){
                $data_sub = [];
                $data_sub['finance_debit_id'] = $finance_debit_id;
                $data_sub['billing_note_list_id'] = $billing_note_list_id[$i];
                $data_sub['finance_debit_list_recieve'] = $finance_debit_list_recieve[$i];
                $data_sub['finance_debit_list_receipt'] = $finance_debit_list_receipt[$i];
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
        }

        /* -------------------------------- สิ้นสุด เพิ่มหรืออัพเดทรายการ Invoice ที่เกี่ยวข้องกับการรับชำระเงิน ---------------------------------------*/

        /* -------------------------------- เพิ่มหรืออัพเดท สมุดรายวันรับชำระเงิน ---------------------------------------*/
        $customer=$customer_model->getCustomerByID($_POST['customer_id']);

        $journal_cash_receipt = $journal_cash_receipt_model->getJournalCashReceiptByFinanceDebitID($finance_debit_id);

        
        if($journal_cash_receipt['journal_cash_receipt_id'] == 0){  

            $data = [];
            $data['finance_debit_id'] = $finance_debit_id;
            $data['journal_cash_receipt_date'] = $_POST['finance_debit_date'];
            $data['journal_cash_receipt_code'] = $_POST['finance_debit_code'];
            $data['journal_cash_receipt_name'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
            $data['addby'] = $admin_id;

            $journal_cash_receipt_id = $journal_cash_receipt_model->insertJournalCashReceipt($data);
        }else{
            $journal_cash_receipt_id = $journal_cash_receipt['journal_cash_receipt_id'];  
            $data['finance_debit_id'] = $finance_debit_id;
            $data['journal_cash_receipt_date'] = $_POST['finance_debit_date'];
            $data['journal_cash_receipt_code'] = $_POST['finance_debit_code'];
            $data['journal_cash_receipt_name'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
            $data['updateby'] = $admin_id;

            $journal_cash_receipt_model->updateJournalCashReceiptByID($journal_cash_receipt_id,$data);

        }
         
        
        /* -------------------------------- สิ้นสุด เพิ่มหรืออัพเดท สมุดรายวันรับชำระเงิน ---------------------------------------*/

        /* -------------------------------------- Credit เจ้าหนี้การค้า ------------------------------------------*/
            
        $journal_cash_receipt_list_cash = $journal_cash_receipt_list_model->getJournalCashReceiptListByFinanceDebitPayId($journal_cash_receipt_id,-2);
        if($journal_cash_receipt_list_cash['journal_cash_receipt_list_id'] > 0){
            $val = (float)filter_var($_POST['finance_debit_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if($val >= 0){
                $journal_cash_receipt_list_debit = 0;
                $journal_cash_receipt_list_credit = $val;
                
            }else{
                $journal_cash_receipt_list_debit = abs($val);
                $journal_cash_receipt_list_credit = 0;
            }
            $data = [];
            $data['journal_cash_receipt_id'] = $journal_cash_receipt_id;
            $data['finance_debit_pay_id'] = $journal_cash_receipt_list_cash['finance_debit_pay_id'];
            $data['account_id'] = $customer['account_id'];
            $data['journal_cash_receipt_list_name'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
            $data['journal_cash_receipt_list_debit'] = $journal_cash_receipt_list_debit ;
            $data['journal_cash_receipt_list_credit'] = $journal_cash_receipt_list_credit;

            $journal_cash_receipt_list_model->updateJournalCashReceiptListById($data,$journal_cash_receipt_list_cash['journal_cash_receipt_list_id']);
        }else{
            $val = (float)filter_var($_POST['finance_debit_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if($val >= 0){
                $journal_cash_receipt_list_debit = 0;
                $journal_cash_receipt_list_credit = $val;
                
            }else{
                $journal_cash_receipt_list_debit = abs($val);
                $journal_cash_receipt_list_credit = 0;
            }
            $data = [];
            $data['journal_cash_receipt_id'] = $journal_cash_receipt_id;
            $data['finance_debit_pay_id'] = -2;
            $data['account_id'] = $customer['account_id'];
            $data['journal_cash_receipt_list_name'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
            $data['journal_cash_receipt_list_debit'] =  $journal_cash_receipt_list_debit ;
            $data['journal_cash_receipt_list_credit'] =$journal_cash_receipt_list_credit;

            $journal_cash_receipt_list_model->insertJournalCashReceiptList($data);
        }
            
        /* ----------------------------------- สิ้นสุด Credit เจ้าหนี้การค้า ---------------------------------------*/

          /* -------------------------------- เพิ่มหรืออัพเดทรายการรับชำระเงิน ---------------------------------------*/
          $finance_debit_pay_id = $_POST['finance_debit_pay_id'];
          $check_id = $_POST['check_id'];
          $finance_debit_account_id = $_POST['finance_debit_account_id'];
          $finance_debit_pay_by = $_POST['finance_debit_pay_by'];
          $finance_debit_pay_date = $_POST['finance_debit_pay_date']; 
          $bank_account_id = $_POST['bank_account_id'];
          $finance_debit_pay_bank = $_POST['finance_debit_pay_bank'];
          $account_id = $_POST['account_id'];
          $finance_debit_pay_value = $_POST['finance_debit_pay_value'];
          $finance_debit_pay_balance = $_POST['finance_debit_pay_balance'];
          $finance_debit_pay_total = $_POST['finance_debit_pay_total'];
  
  
          $journal_cash_receipt_list_model->deleteJournalCashReceiptListByFinanceDebitListIDNotIn($journal_cash_receipt_id,$finance_debit_pay_id);
          $finance_debit_pay_model->deleteFinanceDebitPayByFinanceDebitPayIDNotIN($finance_debit_id,$finance_debit_pay_id);
  
          if(is_array($finance_debit_pay_id)){
 


            for($i=0; $i < count($finance_debit_pay_id) ; $i++){


                

                if($finance_debit_pay_id[$i] == 0){

                    /* -------------------------------------- เพิ่ม Cheque รับล่วงหน้า ------------------------------------------*/
                    //กรณีรายการนั้นเป็นการรับด้ว Cheque ที่ไม่มีในรายการรับเช็ค
                    if($finance_debit_account_cheque[$i] == 1 && $check_id[$i] == 0){
                        $data = [];
                        $data['check_code'] = $finance_debit_pay_by[$i];
                        $data['check_date_write'] = $finance_debit_pay_date[$i];
                        $data['check_date'] = $finance_debit_pay_date[$i];
                        $data['bank_account_id'] = $bank_account_id[$i];
                        $data['customer_id'] = $_POST['customer_id'];
                        $data['check_remark'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
                        $data['check_total'] = (float)filter_var($finance_debit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data['check_type'] = '0';
                        $data['addby'] = $admin_id;
                
                        $check_id[$i] = $check_model->insertCheck($data);
                    }


                    /* -------------------------------------- สิ้นสุดเพิ่ม Cheque รับล่วงหน้า ------------------------------------------*/

                     /* -------------------------------- เพิ่มหรืออัพเดทรายการรับชำระเงิน ที่ละรายการ ---------------------------------------*/
                     $data = [];
                     $data['finance_debit_id'] = $finance_debit_id;
                     $data['finance_debit_account_id'] = $finance_debit_account_id[$i];
                     $data['finance_debit_pay_by'] = $finance_debit_pay_by[$i];
                     $data['finance_debit_pay_date'] = $finance_debit_pay_date[$i];
                     $data['bank_account_id'] = $bank_account_id[$i];
                     $data['finance_debit_pay_bank'] = $finance_debit_pay_bank[$i];
                     $data['account_id'] = $account_id[$i];
                     $data['finance_debit_pay_value'] = (float)filter_var($finance_debit_pay_value[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                     $data['finance_debit_pay_balance'] = (float)filter_var($finance_debit_pay_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                     $data['finance_debit_pay_total'] = (float)filter_var($finance_debit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                     $data['updateby'] = $user[0][0];
                     $data['check_id'] = $check_id[$i];
 
                     $finance_debit_pay_id[$i] = $finance_debit_pay_model->insertFinanceDebitPay($data);

                    /* -------------------------------------- Debit ในสมุดรายวันรับชำระเงิน ------------------------------------------*/
                    $account_setting['cheque_account'] = $account_setting_model->getAccountSettingByID(6); //ดึงข้อมูลเช็ครับล่วงหน้า
                    $val = (float)filter_var($finance_debit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if($val >= 0){
                        $journal_cash_receipt_list_debit = $val;
                        $journal_cash_receipt_list_credit = 0;
                        
                    }else{
                        $journal_cash_receipt_list_debit = 0;
                        $journal_cash_receipt_list_credit = abs($val);
                    }

                    $data = [];
                    $data['journal_cash_receipt_id'] = $journal_cash_receipt_id;
                    $data['finance_debit_pay_id'] = $finance_debit_pay_id[$i];
                    if($check_id[$i] > 0){
                        $data['account_id'] = $account_setting['cheque_account']['account_id'];
                    }else{
                        $data['account_id'] = $account_id[$i];
                    } 
                    $data['journal_cash_receipt_list_name'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
                    $data['journal_cash_receipt_list_debit'] = $journal_cash_receipt_list_debit ;
                    $data['journal_cash_receipt_list_credit'] = $journal_cash_receipt_list_credit;
                    $data['journal_cheque_id'] = $check_id[$i];
                    $journal_cash_receipt_list_model->insertJournalCashReceiptList($data);
                    /* ----------------------------------- สิ้นสุด Debit ในสมุดรายวันรับชำระเงิน ---------------------------------------*/


                   

                     

                }else{

                    /* -------------------------------------- เพิ่ม Cheque รับล่วงหน้า ------------------------------------------*/
                    //กรณีรายการนั้นเป็นการรับด้ว Cheque ที่ไม่มีในรายการรับเช็ค
                    if($finance_debit_account_cheque[$i] == 1 && $check_id[$i] == 0){
                        $data = [];
                        $data['check_code'] = $finance_debit_pay_by[$i];
                        $data['check_date_write'] = $finance_debit_pay_date[$i];
                        $data['check_date'] = $finance_debit_pay_date[$i];
                        $data['bank_account_id'] = $bank_account_id[$i];
                        $data['customer_id'] = $_POST['customer_id'];
                        $data['check_remark'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
                        $data['check_total'] = (float)filter_var($finance_debit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data['check_type'] = '0';
                        $data['addby'] = $admin_id;
                
                        $check_id[$i] = $check_model->insertCheck($data);
                    }


                    /* -------------------------------------- สิ้นสุดเพิ่ม Cheque รับล่วงหน้า ------------------------------------------*/

                    /* -------------------------------------- Debit ในสมุดรายวันรับชำระเงิน ------------------------------------------*/
                    $account_setting['cheque_account'] = $account_setting_model->getAccountSettingByID(6); //ดึงข้อมูลเช็ครับล่วงหน้า
                    $val = (float)filter_var($finance_debit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if($val >= 0){
                        $journal_cash_receipt_list_debit = $val;
                        $journal_cash_receipt_list_credit =0;
                        
                    }else{
                        $journal_cash_receipt_list_debit =  0;
                        $journal_cash_receipt_list_credit = abs($val);
                    }

                    $data = [];
                    $data['journal_cash_receipt_id'] = $journal_cash_receipt_id;
                    $data['finance_debit_pay_id'] = $finance_debit_pay_id[$i];
                    if($check_id[$i] > 0){
                        $data['account_id'] = $account_setting['cheque_account']['account_id'];
                    }else{
                        $data['account_id'] = $account_id[$i];
                    } 
                    $data['journal_cash_receipt_list_name'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
                    $data['journal_cash_receipt_list_debit'] = $journal_cash_receipt_list_debit;
                    $data['journal_cash_receipt_list_credit'] = $journal_cash_receipt_list_credit;
                    $data['journal_cheque_id'] = $check_id[$i];
                    $journal_cash_receipt_list_cash = $journal_cash_receipt_list_model->getJournalCashReceiptListByFinanceDebitPayId($journal_cash_receipt_id, $finance_debit_pay_id[$i]);

                    if($journal_cash_receipt_list_cash['journal_cash_receipt_list_id'] > 0){
                        $journal_cash_receipt_list_model->updateJournalCashReceiptListById($data,$journal_cash_receipt_list_cash['journal_cash_receipt_list_id'] );
                    }else{
                        $journal_cash_receipt_list_model->insertJournalCashReceiptList($data);
                    }
                    
                    /* ----------------------------------- สิ้นสุด Debit ในสมุดรายวันรับชำระเงิน ---------------------------------------*/

                    /* -------------------------------- เพิ่มหรืออัพเดทรายการรับชำระเงิน ที่ละรายการ ---------------------------------------*/
                    $data = [];
                    $data['finance_debit_id'] = $finance_debit_id;
                    $data['finance_debit_account_id'] = $finance_debit_account_id[$i];
                    $data['finance_debit_pay_by'] = $finance_debit_pay_by[$i];
                    $data['finance_debit_pay_date'] = $finance_debit_pay_date[$i];
                    $data['bank_account_id'] = $bank_account_id[$i];
                    $data['finance_debit_pay_bank'] = $finance_debit_pay_bank[$i];
                    $data['account_id'] = $account_id[$i];
                    $data['finance_debit_pay_value'] = (float)filter_var($finance_debit_pay_value[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['finance_debit_pay_balance'] = (float)filter_var($finance_debit_pay_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['finance_debit_pay_total'] = (float)filter_var($finance_debit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['updateby'] = $user[0][0];
                    $data['check_id'] = $check_id[$i]; 

                    $finance_debit_pay_model->updateFinanceDebitPayById($data,$finance_debit_pay_id[$i]);
                }

                /* -------------------------------- สิ้นสุดเพิ่มหรืออัพเดทรายการรับชำระเงิน ที่ละรายการ ---------------------------------------*/
            }
        } 

        /* -------------------------------- สิ้นสุด เพิ่มหรืออัพเดทรายการรับชำระเงิน ---------------------------------------*/

        /* -------------------------------------- เพิ่มหรืออัพเดท Debit เงินสดในสมุดรายวันรับชำระเงิน ------------------------------------------*/
        $cash = (float)filter_var($_POST['finance_debit_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        if($cash != 0){

                //account setting id = 3 เงินสด 
                $account_pays = $account_setting_model->getAccountSettingByID(3);
                $val = $cash;

                if($val >= 0){
                    $journal_cash_receipt_list_debit = $val;
                    $journal_cash_receipt_list_credit =0;
                    
                }else{
                    $journal_cash_receipt_list_debit =  0;
                    $journal_cash_receipt_list_credit = abs($val);
                }

                $data = [];
                $data['journal_cash_receipt_id'] = $journal_cash_receipt_id;
                $data['finance_debit_pay_id'] = -1;
                $data['account_id'] = $account_pays['account_id'];
                $data['journal_cash_receipt_list_name'] = "รับชำระหนี้ให้ " . $_POST['finance_debit_name'];
                $data['journal_cash_receipt_list_debit'] = $journal_cash_receipt_list_debit;
                $data['journal_cash_receipt_list_credit'] = $journal_cash_receipt_list_credit;

                $journal_cash_receipt_list_cash = $journal_cash_receipt_list_model->getJournalCashReceiptListByFinanceDebitPayId($journal_cash_receipt_id,-1);
                if($journal_cash_receipt_list_cash['journal_cash_receipt_list_id'] > 0){
                    $journal_cash_receipt_list_model->updateJournalCashReceiptListById($data,$journal_cash_receipt_list_cash['journal_cash_receipt_list_id']);
                }else{
                    $journal_cash_receipt_list_model->insertJournalCashReceiptList($data);
                }
        }else{
            $journal_cash_receipt_list_cash = $journal_cash_receipt_list_model->getJournalCashReceiptListByFinanceDebitPayId($journal_cash_receipt_id,-1);
            $journal_cash_receipt_list_model->deleteJournalCashReceiptListByID($journal_cash_receipt_list_cash['journal_cash_receipt_list_id']);
        }
                

        /* -------------------------------------- สิ้นสุดเพิ่มหรืออัพเดท Debit เงินสดในสมุดรายวันรับชำระเงิน ------------------------------------------*/
        

          
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

    $customer_id = $_GET['customer_id'];
    $url_search = "&date_start=$date_start&date_end=$date_end&customer_id=$customer_id&keyword=$keyword";

    

    $customers=$customer_model->getCustomerBy();

    $finance_debits = $finance_debit_model->getFinanceDebitBy($date_start,$date_end,$customer_id,$keyword);
    $customer_orders = $finance_debit_model->getCustomerOrder();

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;
    $list_size = count($finance_debits);
    $page_max = (int)($list_size/$page_size);
    if($list_size%$page_size > 0){
        $page_max += 1;
    }
    
    require_once($path.'view.inc.php');

}





?>