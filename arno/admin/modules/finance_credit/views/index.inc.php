<?php
session_start();
$user = $_SESSION['user'];
require_once('../functions/NumbertoTextFunction.func.php');
require_once('../models/FinanceCreditModel.php');
require_once('../models/FinanceCreditListModel.php');
require_once('../models/FinanceCreditPayModel.php');
require_once('../models/FinanceCreditAccountModel.php');

require_once('../models/BankAccountModel.php');
require_once('../models/BankModel.php');
require_once('../models/AccountModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/InvoiceSupplierModel.php');
require_once('../models/CheckPayModel.php');

require_once('../models/JournalCashPaymentModel.php');
require_once('../models/JournalCashPaymentListModel.php');

require_once('../models/AccountSettingModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/finance_credit/views/";
$number_2_text = new Number2Text;
$user_model = new UserModel;
$supplier_model = new SupplierModel;
$invoice_supplier_model = new InvoiceSupplierModel;
$notification_model = new NotificationModel;
$finance_credit_model = new FinanceCreditModel;
$finance_credit_list_model = new FinanceCreditListModel;
$finance_credit_pay_model = new FinanceCreditPayModel;
$finance_credit_account_model = new FinanceCreditAccountModel;
$bank_account_model = new BankAccountModel;
$bank_model = new BankModel;
$account_model = new AccountModel;
$check_model = new CheckPayModel;

$journal_cash_payment_model = new JournalCashPaymentModel;
$journal_cash_payment_list_model = new JournalCashPaymentListModel;

$account_setting_model = new AccountSettingModel;
$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('21');


$finance_credit_id = $_GET['id'];
$notification_id = $_GET['notification'];
$supplier_id = $_GET['supplier_id'];
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

    
    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    }


    $supplier_id = $_GET['supplier_id'];

    $url_search = "&date_start=$date_start&date_end=$date_end&supplier_id=$supplier_id&keyword=$keyword";


    $suppliers=$supplier_model->getSupplierBy();

    $finance_credits = $finance_credit_model->getFinanceCreditBy($date_start,$date_end,$supplier_id,$keyword,"",$lock_1,$lock_2);
    $supplier_orders = $finance_credit_model->getSupplierOrder();

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;
    $list_size = count($finance_credits);
    $page_max = (int)($list_size/$page_size);
    if($list_size%$page_size > 0){
        $page_max += 1;
    }
    
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){ 
    $suppliers=$supplier_model->getSupplierBy();
    $finance_credit_accounts=$finance_credit_account_model->getFinanceCreditAccountNoJoinBy();
    $bank_accounts=$bank_account_model->getBankAccountBy();
    $banks=$bank_model->getBankBy();
    $accounts=$account_model->getAccountAll();

    $supplier=$supplier_model->getSupplierByID($supplier_id);
    if($supplier_id != 0){
        $finance_credit_lists = $finance_credit_model->generateFinanceCreditListBySupplierId($supplier_id);
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
            $last_code = $finance_credit_model->getFinanceCreditLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
    $first_date = date("d")."-".date("m")."-".date("Y");
   

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){

    $suppliers=$supplier_model->getSupplierBy();
    $finance_credit_accounts=$finance_credit_account_model->getFinanceCreditAccountNoJoinBy();
    $bank_accounts=$bank_account_model->getBankAccountBy();
    $accounts=$account_model->getAccountAll();
    $users=$user_model->getUserBy();

    $finance_credit = $finance_credit_model->getFinanceCreditByID($finance_credit_id);

    $supplier=$supplier_model->getSupplierByID($finance_credit['supplier_id']);
    $finance_credit_lists = $finance_credit_list_model->getFinanceCreditListBy($finance_credit_id);
    $finance_credit_pays = $finance_credit_pay_model->getFinanceCreditPayBy($finance_credit_id);

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    
    $finance_credit = $finance_credit_model->getFinanceCreditViewByID($finance_credit_id);
    $finance_credit_lists = $finance_credit_list_model->getFinanceCreditListBy($finance_credit_id);
    
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    
    $finance_credit = $finance_credit_model->getFinanceCreditViewByID($finance_credit_id);
    $finance_credit_lists = $finance_credit_list_model->getFinanceCreditListBy($finance_credit_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){
    $journal_cash_payment_model->deleteJournalCashPaymentByFinanceCreditID($finance_credit_id);
    $finance_credit_model->deleteFinanceCreditById($finance_credit_id);
    
?>
    <script>window.location="index.php?app=finance_credit"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['finance_credit_code'])){
        $journal_cash_payment_list_debit = 0;
        $journal_cash_payment_list_credit = 0;
        /* -------------------------------- เพิ่มใบจ่ายชำระเงิน ---------------------------------------*/
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['finance_credit_code'] = $_POST['finance_credit_code'];
        $data['finance_credit_date'] = $_POST['finance_credit_date'];
        $data['finance_credit_name'] = $_POST['finance_credit_name'];
        $data['finance_credit_address'] = $_POST['finance_credit_address'];
        $data['finance_credit_tax'] = $_POST['finance_credit_tax'];
        $data['finance_credit_remark'] = $_POST['finance_credit_remark'];
        $data['finance_credit_total'] = (float)filter_var($_POST['finance_credit_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_interest'] = (float)filter_var($_POST['finance_credit_interest'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_cash'] = (float)filter_var($_POST['finance_credit_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_other_pay'] = (float)filter_var($_POST['finance_credit_other_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_tax_pay'] = (float)filter_var($_POST['finance_credit_tax_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_discount_cash'] = (float)filter_var($_POST['finance_credit_discount_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_pay'] = (float)filter_var($_POST['finance_credit_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_sent_name'] = $_POST['finance_credit_sent_name'];
        $data['finance_credit_recieve_name'] = $_POST['finance_credit_recieve_name'];
        $data['addby'] = $user[0][0];

        $finance_credit_id = $finance_credit_model->insertFinanceCredit($data);
        /* -------------------------------- สิ้นสุดเพิ่มใบจ่ายชำระเงิน ---------------------------------------*/

        
        if($finance_credit_id > 0){
            

            /* -------------------------------- เพิ่มรายการ Invoice ที่เกี่ยวข้องกับการจ่ายชำระเงิน ---------------------------------------*/
            $data = [];
            $invoice_supplier_id = $_POST['invoice_supplier_id'];
            $finance_credit_list_recieve = $_POST['finance_credit_list_recieve'];
            $finance_credit_list_receipt = $_POST['finance_credit_list_receipt'];
            $finance_credit_list_amount = $_POST['finance_credit_list_amount'];
            $finance_credit_list_paid = $_POST['finance_credit_list_paid'];
            $finance_credit_list_balance = $_POST['finance_credit_list_balance'];
            $finance_credit_list_remark = $_POST['finance_credit_list_remark'];
            
           
            if(is_array($invoice_supplier_id)){
                for($i=0; $i < count($invoice_supplier_id) ; $i++){
                    $data_sub = [];
                    $data_sub['finance_credit_id'] = $finance_credit_id;
                    $data_sub['invoice_supplier_id'] = $invoice_supplier_id[$i];
                    $data_sub['finance_credit_list_recieve'] = $finance_credit_list_recieve[$i];
                    $data_sub['finance_credit_list_receipt'] = $finance_credit_list_receipt[$i];

                    $data_sub['finance_credit_list_amount'] = (float)filter_var($finance_credit_list_amount[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['finance_credit_list_paid'] = (float)filter_var($finance_credit_list_paid[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['finance_credit_list_balance'] = (float)filter_var($finance_credit_list_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['finance_credit_list_remark'] = $finance_credit_list_remark[$i];

                    $id = $finance_credit_list_model->insertFinanceCreditList($data_sub);
                }
            } 

            /* -------------------------------- สิ้นสุดเพิ่มรายการ Invoice ที่เกี่ยวข้องกับการจ่ายชำระเงิน ---------------------------------------*/


            /* -------------------------------- สร้างสมุดรายวันจ่ายชำระเงิน ---------------------------------------*/
            $supplier=$supplier_model->getSupplierByID($_POST['supplier_id']);

            $data = [];
            $data['finance_credit_id'] = $finance_credit_id;
            $data['journal_cash_payment_date'] = $_POST['finance_credit_date'];
            $data['journal_cash_payment_code'] = $_POST['finance_credit_code'];
            $data['journal_cash_payment_name'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
            $data['addby'] = $user[0][0];

            $journal_cash_payment_id = $journal_cash_payment_model->insertJournalCashPayment($data);

            /* -------------------------------- สิ้นสุดสร้างสมุดรายวันจ่ายชำระเงิน ---------------------------------------*/


            /* -------------------------------------- Debit เจ้าหนี้การค้า ------------------------------------------*/
                $val = (float)filter_var($_POST['finance_credit_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                if($val >= 0){
                    $journal_cash_payment_list_debit = $val;
                    $journal_cash_payment_list_credit = 0;
                    
                }else{
                    $journal_cash_payment_list_debit = 0;
                    $journal_cash_payment_list_credit = abs($val);
                }
                $data = [];
                $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                $data['finance_credit_pay_id'] = -2;
                $data['account_id'] = $supplier['account_id'];
                $data['journal_cash_payment_list_name'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
                $data['journal_cash_payment_list_debit'] = $journal_cash_payment_list_debit;
                $data['journal_cash_payment_list_credit'] = $journal_cash_payment_list_credit;

                $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
            /* ----------------------------------- สิ้นสุด Credit เจ้าหนี้การค้า ---------------------------------------*/

            
            /* -------------------------------- เพิ่มรายการจ่ายชำระเงิน ---------------------------------------*/
            $finance_credit_pay_id = $_POST['finance_credit_pay_id'];
            $check_pay_id = $_POST['check_pay_id'];
            $finance_credit_account_id = $_POST['finance_credit_account_id'];
            $finance_credit_account_cheque = $_POST['finance_credit_account_cheque'];
            $finance_credit_pay_by = $_POST['finance_credit_pay_by'];
            $finance_credit_pay_date = $_POST['finance_credit_pay_date']; 
            $bank_account_id = $_POST['bank_account_id'];
            $finance_credit_pay_bank = $_POST['finance_credit_pay_bank'];
            $account_id = $_POST['account_id'];
            $finance_credit_pay_value = $_POST['finance_credit_pay_value'];
            $finance_credit_pay_balance = $_POST['finance_credit_pay_balance'];
            $finance_credit_pay_total = $_POST['finance_credit_pay_total'];


            if(is_array($finance_credit_pay_id)){

                



                for($i=0; $i < count($finance_credit_pay_id) ; $i++){

                    /* -------------------------------------- เพิ่ม Cheque จ่ายล่วงหน้า ------------------------------------------*/
                    //กรณีรายการนั้นเป็นการจ่ายด้ว Cheque ที่ไม่มีในรายการจ่ายเช็ค
                    if($finance_credit_account_cheque[$i] == 1  && $check_pay_id[$i] == 0){
                        $data = [];
                        $data['check_pay_code'] = $finance_credit_pay_by[$i];
                        $data['check_pay_date_write'] = $finance_credit_pay_date[$i];
                        $data['check_pay_date'] = $finance_credit_pay_date[$i];
                        $data['bank_account_id'] = $bank_account_id[$i];
                        $data['supplier_id'] = $_POST['supplier_id'];
                        $data['check_pay_remark'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
                        $data['check_pay_total'] = (float)filter_var($finance_credit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data['check_type'] = '0';
                        $data['addby'] = $admin_id;
                
                        $check_pay_id[$i] = $check_model->insertCheckPay($data);
                    }   
                    /* -------------------------------------- สิ้นสุดเพิ่ม Cheque จ่ายล่วงหน้า ------------------------------------------*/


                    /* -------------------------------- เพิ่มรายการจ่ายชำระเงิน ที่ละรายการ ---------------------------------------*/
                    $data = [];
                    $data['finance_credit_id'] = $finance_credit_id;
                    $data['finance_credit_account_id'] = $finance_credit_account_id[$i];
                    $data['finance_credit_pay_by'] = $finance_credit_pay_by[$i];
                    $data['finance_credit_pay_date'] = $finance_credit_pay_date[$i];
                    $data['check_pay_id'] = $check_pay_id[$i];
                    $data['bank_account_id'] = $bank_account_id[$i];
                    $data['finance_credit_pay_bank'] = $finance_credit_pay_bank[$i];
                    $data['account_id'] = $account_id[$i];
                    $data['finance_credit_pay_value'] = (float)filter_var($finance_credit_pay_value[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['finance_credit_pay_balance'] = (float)filter_var($finance_credit_pay_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['finance_credit_pay_total'] = (float)filter_var($finance_credit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['updateby'] = $user[0][0];
                    
                    $finance_credit_pay_id[$i] = $finance_credit_pay_model->insertFinanceCreditPay($data);
                    
                    /* -------------------------------- สิ้นสุดเพิ่มรายการจ่ายชำระเงิน ที่ละรายการ ---------------------------------------*/


                    /* -------------------------------------- Credit ในสมุดรายวันจ่ายชำระเงิน ------------------------------------------*/
                    $account_setting['cheque_pay_account'] = $account_setting_model->getAccountSettingByID(13); //ดึงข้อมูลเช็คจ่ายล่วงหน้า
                    $val = (float)filter_var($finance_credit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if($val >= 0){
                        $journal_cash_payment_list_debit = 0;
                        $journal_cash_payment_list_credit = $val;
                        
                    }else{
                        $journal_cash_payment_list_debit = abs($val);
                        $journal_cash_payment_list_credit = 0;
                    }

                    $data = [];
                    $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                    $data['finance_credit_pay_id'] = $finance_credit_pay_id[$i];
                    if($check_pay_id[$i] > 0){
                        $data['account_id'] = $account_setting['cheque_pay_account']['account_id'];
                    }else{
                        $data['account_id'] = $account_id[$i];
                    } 
                    $data['journal_cash_payment_list_name'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
                    $data['journal_cash_payment_list_debit'] = $journal_cash_payment_list_debit;
                    $data['journal_cash_payment_list_credit'] = $journal_cash_payment_list_credit;
                    $data['journal_cheque_pay_id'] = $check_pay_id[$i];
                    $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
                    /* ----------------------------------- สิ้นสุด Debit ในสมุดรายวันจ่ายชำระเงิน ---------------------------------------*/
 
                }


                /* -------------------------------------- Debit เงินสดในสมุดรายวันจ่ายชำระเงิน ------------------------------------------*/
                $cash = (float)filter_var($_POST['finance_credit_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                if($cash != 0){
                    //account setting id = 3 เงินสด 
                    $account_pays = $account_setting_model->getAccountSettingByID(3);
                    $val = $cash;
                    if($val >= 0){
                        $journal_cash_payment_list_debit = 0;
                        $journal_cash_payment_list_credit = $val;
                        
                    }else{
                        $journal_cash_payment_list_debit = abs($val);
                        $journal_cash_payment_list_credit = 0;
                    }
                    $data = [];
                    $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                    $data['finance_credit_pay_id'] = -1;
                    $data['account_id'] = $account_pays['account_id'];
                    $data['journal_cash_payment_list_name'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
                    $data['journal_cash_payment_list_debit'] = $journal_cash_payment_list_debit;
                    $data['journal_cash_payment_list_credit'] = $journal_cash_payment_list_credit;

                    $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
                }
 
                /* -------------------------------------- สิ้นสุด Debit เงินสดในสมุดรายวันจ่ายชำระเงิน ------------------------------------------*/



                /* -------------------------------- สิ้นสุดเพิ่มรายการจ่ายชำระเงิน ---------------------------------------*/
            } 


?>
        <script>window.location="index.php?app=finance_credit&action=insert"//window.location="index.php?app=finance_credit&action=update&id=<?php echo $finance_credit_id;?>"</script>
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
    
    if(isset($_POST['finance_credit_code'])){
        $journal_cash_payment_list_debit = 0;
        $journal_cash_payment_list_credit = 0;
        /* -------------------------------- อัพเดทใบจ่ายชำระเงิน ---------------------------------------*/
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['finance_credit_code'] = $_POST['finance_credit_code'];
        $data['finance_credit_date'] = $_POST['finance_credit_date'];
        $data['finance_credit_name'] = $_POST['finance_credit_name'];
        $data['finance_credit_address'] = $_POST['finance_credit_address'];
        $data['finance_credit_tax'] = $_POST['finance_credit_tax'];
        $data['finance_credit_remark'] = $_POST['finance_credit_remark'];
        $data['finance_credit_total'] = (float)filter_var($_POST['finance_credit_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_interest'] = (float)filter_var($_POST['finance_credit_interest'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_cash'] = (float)filter_var($_POST['finance_credit_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_other_pay'] = (float)filter_var($_POST['finance_credit_other_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_tax_pay'] = (float)filter_var($_POST['finance_credit_tax_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_discount_cash'] = (float)filter_var($_POST['finance_credit_discount_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_pay'] = (float)filter_var($_POST['finance_credit_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['finance_credit_sent_name'] = $_POST['finance_credit_sent_name'];
        $data['finance_credit_recieve_name'] = $_POST['finance_credit_recieve_name'];
        $data['updateby'] = $user[0][0];

        $output = $finance_credit_model->updateFinanceCreditByID($finance_credit_id,$data);
        /* -------------------------------- สิ้นสุด อัพเดทใบจ่ายชำระเงิน ---------------------------------------*/


        /* -------------------------------- เพิ่มหรืออัพเดทรายการ Invoice ที่เกี่ยวข้องกับการจ่ายชำระเงิน ---------------------------------------*/
        $finance_credit_list_id = $_POST['finance_credit_list_id'];
        $invoice_supplier_id = $_POST['invoice_supplier_id'];
        $finance_credit_list_recieve = $_POST['finance_credit_list_recieve'];
        $finance_credit_list_receipt = $_POST['finance_credit_list_receipt'];
        $finance_credit_list_amount = $_POST['finance_credit_list_amount'];
        $finance_credit_list_paid = $_POST['finance_credit_list_paid'];
        $finance_credit_list_balance = $_POST['finance_credit_list_balance'];
        $finance_credit_list_remark = $_POST['finance_credit_list_remark'];

        
        /* -------------------------------- ล้างรายการ Invoice ที่เกี่ยวข้องกับการจ่ายชำระเงิน ที่ถูกลบไป ---------------------------------------*/
        $finance_credit_list_model->deleteFinanceCreditListByFinanceCreditIDNotIN($finance_credit_id,$finance_credit_list_id);
        /* -------------------------------- สิ้นสุด ล้างรายการ Invoice ที่เกี่ยวข้องกับการจ่ายชำระเงิน ที่ถูกลบไป ---------------------------------------*/
        

        if(is_array($invoice_supplier_id)){
            for($i=0; $i < count($invoice_supplier_id) ; $i++){
                $data_sub = [];
                $data_sub['finance_credit_id'] = $finance_credit_id;
                $data_sub['invoice_supplier_id'] = $invoice_supplier_id[$i];
                $data_sub['finance_credit_list_recieve'] = $finance_credit_list_recieve[$i];
                $data_sub['finance_credit_list_receipt'] = $finance_credit_list_receipt[$i];
                $data_sub['finance_credit_list_amount'] = (float)filter_var($finance_credit_list_amount[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['finance_credit_list_paid'] = (float)filter_var($finance_credit_list_paid[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['finance_credit_list_balance'] = (float)filter_var($finance_credit_list_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['finance_credit_list_remark'] = $finance_credit_list_remark[$i];

                if($finance_credit_list_id[$i] != '0'){
                    $finance_credit_list_model->updateFinanceCreditListById($data_sub,$finance_credit_list_id[$i]);
                }else{
                    $id = $finance_credit_list_model->insertFinanceCreditList($data_sub);
                }
                
            }
        }

        /* -------------------------------- สิ้นสุด เพิ่มหรืออัพเดทรายการ Invoice ที่เกี่ยวข้องกับการจ่ายชำระเงิน ---------------------------------------*/

        /* -------------------------------- เพิ่มหรืออัพเดท สมุดรายวันจ่ายชำระเงิน ---------------------------------------*/
        $supplier=$supplier_model->getSupplierByID($_POST['supplier_id']);

        $journal_cash_payment = $journal_cash_payment_model->getJournalCashPaymentByFinanceCreditID($finance_credit_id);
        
        $data = [];
        $data['finance_credit_id'] = $finance_credit_id;
        $data['journal_cash_payment_date'] = $_POST['finance_credit_date'];
        $data['journal_cash_payment_code'] = $_POST['finance_credit_code'];
        $data['journal_cash_payment_name'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
        $data['addby'] = $admin_id;
        
        if($journal_cash_payment['journal_cash_payment_id'] == 0 || $journal_cash_payment['journal_cash_payment_id'] == ''){ 
            $journal_cash_payment_id = $journal_cash_payment_model->insertJournalCashPayment($data);
        }else{
            $journal_cash_payment_id = $journal_cash_payment['journal_cash_payment_id'];  
            $journal_cash_payment_model->updateJournalCashPaymentByID($journal_cash_payment_id,$data);
        }
         
        
        /* -------------------------------- สิ้นสุด เพิ่มหรืออัพเดท สมุดรายวันจ่ายชำระเงิน ---------------------------------------*/

        /* -------------------------------------- Debit เจ้าหนี้การค้า ------------------------------------------*/
            
        $journal_cash_payment_list_cash = $journal_cash_payment_list_model->getJournalCashPaymentListByFinanceCreditPayId($journal_cash_payment_id,-2);
        if($journal_cash_payment_list_cash['journal_cash_payment_list_id'] > 0){
            $val = (float)filter_var($_POST['finance_credit_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if($val >= 0){
                $journal_cash_payment_list_debit = $val;
                $journal_cash_payment_list_credit = 0;
                
            }else{
                $journal_cash_payment_list_debit = 0;
                $journal_cash_payment_list_credit = abs($val);
            }
            $data = [];
            $data['journal_cash_payment_id'] = $journal_cash_payment_id;
            $data['finance_credit_pay_id'] = $journal_cash_payment_list_cash['finance_credit_pay_id'];
            $data['account_id'] = $supplier['account_id'];
            $data['journal_cash_payment_list_name'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
            $data['journal_cash_payment_list_debit'] = $journal_cash_payment_list_debit;
            $data['journal_cash_payment_list_credit'] = $journal_cash_payment_list_credit;

            $journal_cash_payment_list_model->updateJournalCashPaymentListById($data,$journal_cash_payment_list_cash['journal_cash_payment_list_id']);
        }else{
            $val = (float)filter_var($_POST['finance_credit_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if($val >= 0){
                $journal_cash_payment_list_debit = $val;
                $journal_cash_payment_list_credit = 0;
                
            }else{
                $journal_cash_payment_list_debit = 0;
                $journal_cash_payment_list_credit = abs($val);
            }
            $data = [];
            $data['journal_cash_payment_id'] = $journal_cash_payment_id;
            $data['finance_credit_pay_id'] = -2;
            $data['account_id'] = $supplier['account_id'];
            $data['journal_cash_payment_list_name'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
            $data['journal_cash_payment_list_debit'] = $journal_cash_payment_list_debit;
            $data['journal_cash_payment_list_credit'] = $journal_cash_payment_list_credit;

            $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
        }
            
        /* ----------------------------------- สิ้นสุด Credit เจ้าหนี้การค้า ---------------------------------------*/

        /* -------------------------------- เพิ่มหรืออัพเดทรายการจ่ายชำระเงิน ---------------------------------------*/
          $finance_credit_pay_id = $_POST['finance_credit_pay_id'];
          $check_pay_id = $_POST['check_pay_id'];
          $finance_credit_account_id = $_POST['finance_credit_account_id'];
          $finance_credit_pay_by = $_POST['finance_credit_pay_by'];
          $finance_credit_pay_date = $_POST['finance_credit_pay_date']; 
          $bank_account_id = $_POST['bank_account_id'];
          $finance_credit_pay_bank = $_POST['finance_credit_pay_bank'];
          $account_id = $_POST['account_id'];
          $finance_credit_pay_value = $_POST['finance_credit_pay_value'];
          $finance_credit_pay_balance = $_POST['finance_credit_pay_balance'];
          $finance_credit_pay_total = $_POST['finance_credit_pay_total'];
  
  
          $finance_credit_pay_model->deleteFinanceCreditPayByFinanceCreditPayIDNotIN($journal_cash_payment_id,$finance_credit_pay_id);
          $journal_cash_payment_list_model->deleteJournalCashPaymentListByFinanceCreditListIDNotIn($finance_credit_id,$finance_credit_pay_id);
  
          if(is_array($finance_credit_pay_id)){
            

            for($i=0; $i < count($finance_credit_pay_id) ; $i++){

                
                

                if($finance_credit_pay_id[$i] == 0){

                    /* -------------------------------------- เพิ่ม Cheque จ่ายล่วงหน้า ------------------------------------------*/
                    //กรณีรายการนั้นเป็นการจ่ายด้ว Cheque ที่ไม่มีในรายการจ่ายเช็ค
                    if($finance_credit_account_cheque[$i] == 1 && $check_pay_id[$i] == 0){
                        $data = [];
                        $data['check_pay_code'] = $finance_credit_pay_by[$i];
                        $data['check_pay_date_write'] = $finance_credit_pay_date[$i];
                        $data['check_pay_date'] = $finance_credit_pay_date[$i];
                        $data['bank_account_id'] = $bank_account_id[$i];
                        $data['supplier_id'] = $_POST['supplier_id'];
                        $data['check_pay_remark'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
                        $data['check_pay_total'] = (float)filter_var($finance_credit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data['check_type'] = '0';
                        $data['addby'] = $admin_id;
                
                        $check_pay_id[$i] = $check_model->insertCheckPay($data);
                    }


                    /* -------------------------------------- สิ้นสุดเพิ่ม Cheque จ่ายล่วงหน้า ------------------------------------------*/


                    

                    /* -------------------------------- เพิ่มหรืออัพเดทรายการจ่ายชำระเงิน ที่ละรายการ ---------------------------------------*/
                    $data = [];
                    $data['finance_credit_id'] = $finance_credit_id;
                    $data['finance_credit_account_id'] = $finance_credit_account_id[$i];
                    $data['finance_credit_pay_by'] = $finance_credit_pay_by[$i];
                    $data['finance_credit_pay_date'] = $finance_credit_pay_date[$i];
                    $data['bank_account_id'] = $bank_account_id[$i];
                    $data['finance_credit_pay_bank'] = $finance_credit_pay_bank[$i];
                    $data['account_id'] = $account_id[$i];
                    $data['finance_credit_pay_value'] = (float)filter_var($finance_credit_pay_value[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['finance_credit_pay_balance'] = (float)filter_var($finance_credit_pay_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['finance_credit_pay_total'] = (float)filter_var($finance_credit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['updateby'] = $user[0][0];
                    $data['check_pay_id'] = $check_pay_id[$i];

                    $finance_credit_pay_id[$i] = $finance_credit_pay_model->insertFinanceCreditPay($data);

                    /* -------------------------------------- Credit ในสมุดรายวันจ่ายชำระเงิน ------------------------------------------*/
                    $account_setting['cheque_pay_account'] = $account_setting_model->getAccountSettingByID(13); //ดึงข้อมูลเช็คจ่ายล่วงหน้า
                    $val = (float)filter_var($finance_credit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if($val >= 0){
                        $journal_cash_payment_list_debit = 0;
                        $journal_cash_payment_list_credit = $val;
                        
                    }else{
                        $journal_cash_payment_list_debit = abs($val);
                        $journal_cash_payment_list_credit = 0;
                    }

                    $data = [];
                    $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                    $data['finance_credit_pay_id'] = $finance_credit_pay_id[$i];
                    if($check_pay_id[$i] > 0){
                        $data['account_id'] = $account_setting['cheque_pay_account']['account_id'];
                    }else{
                        $data['account_id'] = $account_id[$i];
                    } 
                    $data['journal_cash_payment_list_name'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
                    $data['journal_cash_payment_list_debit'] = $journal_cash_payment_list_debit;
                    $data['journal_cash_payment_list_credit'] = $journal_cash_payment_list_credit;
                    $data['journal_cheque_pay_id'] = $check_pay_id[$i];
                    $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
                    /* ----------------------------------- สิ้นสุด Credit ในสมุดรายวันจ่ายชำระเงิน ---------------------------------------*/

                     

                }else{

                    /* -------------------------------------- เพิ่ม Cheque จ่ายล่วงหน้า ------------------------------------------*/
                    //กรณีรายการนั้นเป็นการจ่ายด้ว Cheque ที่ไม่มีในรายการจ่ายเช็ค
                    if($finance_credit_account_cheque[$i] == 1 && $check_pay_id[$i] == 0){
                        $data = [];
                        $data['check_pay_code'] = $finance_credit_pay_by[$i];
                        $data['check_pay_date_write'] = $finance_credit_pay_date[$i];
                        $data['check_pay_date'] = $finance_credit_pay_date[$i];
                        $data['bank_account_id'] = $bank_account_id[$i];
                        
                        $data['supplier_id'] = $_POST['supplier_id'];
                        $data['check_pay_remark'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
                        $data['check_pay_total'] = (float)filter_var($finance_credit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data['check_type'] = '0';
                        $data['addby'] = $admin_id;
                
                        $check_pay_id[$i] = $check_model->insertCheckPay($data);
                    }


                    /* -------------------------------------- สิ้นสุดเพิ่ม Cheque จ่ายล่วงหน้า ------------------------------------------*/


                    /* -------------------------------- เพิ่มหรืออัพเดทรายการจ่ายชำระเงิน ที่ละรายการ ---------------------------------------*/
                    $data = [];
                    $data['finance_credit_id'] = $finance_credit_id;
                    $data['finance_credit_account_id'] = $finance_credit_account_id[$i];
                    $data['finance_credit_pay_by'] = $finance_credit_pay_by[$i];
                    $data['finance_credit_pay_date'] = $finance_credit_pay_date[$i];
                    $data['bank_account_id'] = $bank_account_id[$i];
                    $data['finance_credit_pay_bank'] = $finance_credit_pay_bank[$i];
                    $data['account_id'] = $account_id[$i];
                    $data['finance_credit_pay_value'] = (float)filter_var($finance_credit_pay_value[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['finance_credit_pay_balance'] = (float)filter_var($finance_credit_pay_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['finance_credit_pay_total'] = (float)filter_var($finance_credit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['updateby'] = $user[0][0];
                    $data['check_pay_id'] = $check_pay_id[$i]; 

                    $finance_credit_pay_model->updateFinanceCreditPayById($data,$finance_credit_pay_id[$i]);



                    /* -------------------------------------- Debit ในสมุดรายวันจ่ายชำระเงิน ------------------------------------------*/
                    $account_setting['cheque_pay_account'] = $account_setting_model->getAccountSettingByID(13); //ดึงข้อมูลเช็คจ่ายล่วงหน้า
                    $val = (float)filter_var($finance_credit_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if($val >= 0){
                        $journal_cash_payment_list_debit = 0;
                        $journal_cash_payment_list_credit = $val;
                        
                    }else{
                        $journal_cash_payment_list_debit = abs($val);
                        $journal_cash_payment_list_credit = 0;
                    }
                    $data = [];
                    $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                    $data['finance_credit_pay_id'] = $finance_credit_pay_id[$i];
                    if($check_pay_id[$i] > 0){
                        $data['account_id'] = $account_setting['cheque_pay_account']['account_id'];
                    }else{
                        $data['account_id'] = $account_id[$i];
                    } 
                    $data['journal_cash_payment_list_name'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
                    $data['journal_cash_payment_list_debit'] = $journal_cash_payment_list_debit;
                    $data['journal_cash_payment_list_credit'] = $journal_cash_payment_list_credit;
                    $data['journal_cheque_pay_id'] = $check_pay_id[$i];
                    $journal_cash_payment_list_cash = $journal_cash_payment_list_model->getJournalCashPaymentListByFinanceCreditPayId($journal_cash_payment_id, $finance_credit_pay_id[$i]);

                    if($journal_cash_payment_list_cash['journal_cash_payment_list_id'] > 0){
                        $journal_cash_payment_list_model->updateJournalCashPaymentListById($data,$journal_cash_payment_list_cash['journal_cash_payment_list_id'] );
                    }else{
                        $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
                    }
                    
                    /* ----------------------------------- สิ้นสุด Debit ในสมุดรายวันจ่ายชำระเงิน ---------------------------------------*/



                    
                }

                /* -------------------------------- สิ้นสุดเพิ่มหรืออัพเดทรายการจ่ายชำระเงิน ที่ละรายการ ---------------------------------------*/
            }
        } 

        /* -------------------------------- สิ้นสุด เพิ่มหรืออัพเดทรายการจ่ายชำระเงิน ---------------------------------------*/

        /* -------------------------------------- เพิ่มหรืออัพเดท Debit เงินสดในสมุดรายวันจ่ายชำระเงิน ------------------------------------------*/
        $cash = (float)filter_var($_POST['finance_credit_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        if($cash != 0){

                //account setting id = 3 เงินสด 
                $account_pays = $account_setting_model->getAccountSettingByID(3);
                $val = $cash;
                if($val >= 0){
                    $journal_cash_payment_list_debit = 0;
                    $journal_cash_payment_list_credit = $val;
                    
                }else{
                    $journal_cash_payment_list_debit = abs($val);
                    $journal_cash_payment_list_credit = 0;
                }
                $data = [];
                $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                $data['finance_credit_pay_id'] = -1;
                $data['account_id'] = $account_pays['account_id'];
                $data['journal_cash_payment_list_name'] = "จ่ายหนี้ให้ " . $_POST['finance_credit_name'];
                $data['journal_cash_payment_list_debit'] = $journal_cash_payment_list_debit;
                $data['journal_cash_payment_list_credit'] = $journal_cash_payment_list_credit;

                $journal_cash_payment_list_cash = $journal_cash_payment_list_model->getJournalCashPaymentListByFinanceCreditPayId($journal_cash_payment_id,-1);
                if($journal_cash_payment_list_cash['journal_cash_payment_list_id'] > 0){
                    $journal_cash_payment_list_model->updateJournalCashPaymentListById($data,$journal_cash_payment_list_cash['journal_cash_payment_list_id']);
                }else{
                    $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
                }
        }else{
            $journal_cash_payment_list_cash = $journal_cash_payment_list_model->getJournalCashPaymentListByFinanceCreditPayId($journal_cash_payment_id,-1);
            $journal_cash_payment_list_model->deleteJournalCashPaymentListByID($journal_cash_payment_list_cash['journal_cash_payment_list_id']);
        }
                

        /* -------------------------------------- สิ้นสุดเพิ่มหรืออัพเดท Debit เงินสดในสมุดรายวันจ่ายชำระเงิน ------------------------------------------*/
        

          
        if($output){
        
?>
        <script>window.location="index.php?app=finance_credit&action=update&id=<?php echo $finance_credit_id;?>"</script>
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


    
    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    }
    

    $supplier_id = $_GET['supplier_id'];
    $url_search = "&date_start=$date_start&date_end=$date_end&supplier_id=$supplier_id&keyword=$keyword";


    $suppliers=$supplier_model->getSupplierBy();

    $finance_credits = $finance_credit_model->getFinanceCreditBy($date_start,$date_end,$supplier_id,$keyword,"",$lock_1,$lock_2);
    $supplier_orders = $finance_credit_model->getSupplierOrder();

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;
    $list_size = count($finance_credits);
    $page_max = (int)($list_size/$page_size);
    if($list_size%$page_size > 0){
        $page_max += 1;
    }
    
    require_once($path.'view.inc.php');


}





?>