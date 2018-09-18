<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/JournalCashPaymentModel.php');
require_once('../models/JournalCashPaymentListModel.php');
require_once('../models/JournalCashPaymentInvoiceModel.php');
require_once('../models/AccountModel.php');
require_once('../models/AccountSettingModel.php');
require_once('../models/BankAccountModel.php');
require_once('../models/UserModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/BankModel.php');

require_once('../models/CheckModel.php');
require_once('../models/CheckPayModel.php');
require_once('../models/InvoiceSupplierModel.php');
require_once('../models/InvoiceCustomerModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/journal_cash_payment/views/";
$account_model = new AccountModel;
$bank_account_model = new BankAccountModel;
$account_setting_model = new AccountSettingModel;
$journal_cash_payment_model = new JournalCashPaymentModel;
$journal_cash_payment_list_model = new JournalCashPaymentListModel;
$journal_cash_payment_invoice_model = new JournalCashPaymentInvoiceModel;
$user_model = new UserModel;
$supplier_model = new SupplierModel;
$customer_model = new CustomerModel;
$bank_model = new BankModel;


$check_model = new CheckModel;
$check_pay_model = new CheckPayModel;
$invoice_supplier_model = new InvoiceSupplierModel;
$invoice_customer_model = new InvoiceCustomerModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('30');

$account_setting['cheque_account'] = $account_setting_model->getAccountSettingByID(6); //ดึงข้อมูลเช็ครับล่วงหน้า
$account_setting['cheque_pay_account'] = $account_setting_model->getAccountSettingByID(13); //ดึงข้อมูลเช็คจ่ายล่วงหน้า
$account_setting['vat_purchase_account'] = $account_setting_model->getAccountSettingByID(9); //ดึงข้อมูลภาษีซื้อ
$account_setting['vat_sale_account'] = $account_setting_model->getAccountSettingByID(15); //ดึงข้อมูลภาษีขาย

$journal_cash_payment_id = $_GET['id'];
$target_dir = "../upload/journal_cash_payment/";

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword']; 
    $journal_cash_payments = $journal_cash_payment_model->getJournalCashPaymentBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $accounts=$account_model->getAccountAll();
    $suppliers=$supplier_model->getSupplierBy();
    $customers=$customer_model->getCustomerBy();
    $banks=$bank_model->getBankBy();
    $bank_accounts=$bank_account_model->getBankAccountBy();

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
            $last_code = $journal_cash_payment_model->getJournalCashPaymentLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
    

    
    $first_date = date("d")."-".date("m")."-".date("Y");   

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){

    $accounts=$account_model->getAccountAll();
    $suppliers=$supplier_model->getSupplierBy();
    $customers=$customer_model->getCustomerBy();
    $banks=$bank_model->getBankBy();
    $bank_accounts=$bank_account_model->getBankAccountBy();

    $checks = $check_model->getCheckViewListByjournalID($journal_cash_payment_id);
    $check_pays = $check_pay_model->getCheckPayViewListByjournalID($journal_cash_payment_id);
    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierViewListByjournalID($journal_cash_payment_id);
    $invoice_customers = $invoice_customer_model->getInvoiceCustomerViewListByjournalID($journal_cash_payment_id);

    $journal_cash_payment = $journal_cash_payment_model->getJournalCashPaymentByID($journal_cash_payment_id);
    $journal_cash_payment_lists = $journal_cash_payment_list_model->getJournalCashPaymentListBy($journal_cash_payment_id);
    $journal_cash_payment_invoices = $journal_cash_payment_invoice_model->getJournalCashPaymentInvoiceBy($journal_cash_payment_id);
 
    $supplier=$supplier_model->getSupplierByID($journal_cash_payment_invoices['supplier_id'] );
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $journal_cash_payment = $journal_cash_payment_model->getJournalCashPaymentViewByID($journal_cash_payment_id);
    $journal_cash_payment_lists = $journal_cash_payment_list_model->getJournalCashPaymentListBy($journal_cash_payment_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $journal_cash_payment = $journal_cash_payment_model->getJournalCashPaymentViewByID($journal_cash_payment_id);
    $journal_cash_payment_lists = $journal_cash_payment_list_model->getJournalCashPaymentListBy($journal_cash_payment_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){

    $journal_cash_payment_invoice_model->deleteJournalCashPaymentInvoiceByJournalCashPaymentID($journal_cash_payment_id);
    $journal_cash_payment_list_model->deleteJournalCashPaymentListByJournalCashPaymentID($journal_cash_payment_id);
    $journal_cash_payments = $journal_cash_payment_model->deleteJournalCashPaymentById($journal_cash_payment_id);
?>
    <script>window.location="index.php?app=journal_special_04"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['journal_cash_payment_code'])){

        $data = [];
        $data['journal_cash_payment_date'] = $_POST['journal_cash_payment_date'];
        $data['journal_cash_payment_code'] = $_POST['journal_cash_payment_code'];
        $data['journal_cash_payment_name'] = $_POST['journal_cash_payment_name'];
        $data['addby'] = $user[0][0];


            $journal_cash_payment_id = $journal_cash_payment_model->insertJournalCashPayment($data);

            if($journal_cash_payment_id > 0){

                $account_id = $_POST['account_id'];
                $journal_cash_payment_list_id = $_POST['journal_cash_payment_list_id'];
                $journal_cash_payment_list_name = $_POST['journal_cash_payment_list_name'];
                $journal_cash_payment_list_debit = $_POST['journal_cash_payment_list_debit'];
                $journal_cash_payment_list_credit = $_POST['journal_cash_payment_list_credit'];
                $journal_cheque_id = $_POST['journal_cheque_id'];
                $journal_cheque_pay_id = $_POST['journal_cheque_pay_id'];
                $journal_invoice_customer_id = $_POST['journal_invoice_customer_id'];
                $journal_invoice_supplier_id = $_POST['journal_invoice_supplier_id'];


                $journal_cash_payment_list_model->deleteJournalCashPaymentListByJournalCashPaymentIDNotIN($journal_cash_payment_id,$journal_cash_payment_list_id);

                if(is_array($account_id)){
                    for($i=0; $i < count($account_id) ; $i++){
                        $data = [];
                        $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                        $data['account_id'] = $account_id[$i];
                        $data['journal_cheque_id'] = $journal_cheque_id[$i];
                        $data['journal_cheque_pay_id'] = $journal_cheque_pay_id[$i];
                        $data['journal_invoice_customer_id'] = $journal_invoice_customer_id[$i];
                        $data['journal_invoice_supplier_id'] = $journal_invoice_supplier_id[$i];
                        $data['journal_cash_payment_list_name'] = $journal_cash_payment_list_name[$i];
                        $data['journal_cash_payment_list_debit'] = (float)filter_var($journal_cash_payment_list_debit[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data['journal_cash_payment_list_credit'] = (float)filter_var($journal_cash_payment_list_credit[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                    

                        if ($journal_cash_payment_list_id[$i] != "" && $journal_cash_payment_list_id[$i] != '0'){
                            $journal_cash_payment_list_model->updateJournalCashPaymentListById($data,$journal_cash_payment_list_id[$i]);
                        }else{
                            $out_id = $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
                         
                        }
                    }
                }else{
                    $data = [];
                    $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                    $data['account_id'] = $account_id;
                    $data['journal_cheque_id'] = $journal_cheque_id;
                    $data['journal_cheque_pay_id'] = $journal_cheque_pay_id;
                    $data['journal_invoice_customer_id'] = $journal_invoice_customer_id;
                    $data['journal_invoice_supplier_id'] = $journal_invoice_supplier_id;
                    $data['journal_cash_payment_list_name'] = $journal_cash_payment_list_name;
                    $data['journal_cash_payment_list_debit'] = (float)filter_var($journal_cash_payment_list_debit, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['journal_cash_payment_list_credit'] = (float)filter_var($journal_cash_payment_list_credit, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                    if ($journal_cash_payment_list_id != "" && $journal_cash_payment_list_id != '0'){
                        $journal_cash_payment_list_model->updateJournalCashPaymentListById($data,$journal_cash_payment_list_id);
                    }else{
                        $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
                    }
                    
                }

    ?>
            <script>window.location="index.php?app=journal_special_04&action=insert"</script>
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
    
    if(isset($_POST['journal_cash_payment_code'])){
        $data = [];
        $data['journal_cash_payment_date'] = $_POST['journal_cash_payment_date'];
        $data['journal_cash_payment_code'] = $_POST['journal_cash_payment_code'];
        $data['journal_cash_payment_name'] = $_POST['journal_cash_payment_name'];
        $data['updateby'] = $user[0][0];

        $output = $journal_cash_payment_model->updateJournalCashPaymentByID($journal_cash_payment_id,$data);

        
        $account_id = $_POST['account_id'];
        $journal_cash_payment_list_id = $_POST['journal_cash_payment_list_id'];
        $journal_cash_payment_list_name = $_POST['journal_cash_payment_list_name'];
        $journal_cash_payment_list_debit = $_POST['journal_cash_payment_list_debit'];
        $journal_cash_payment_list_credit = $_POST['journal_cash_payment_list_credit'];
        $journal_cheque_id = $_POST['journal_cheque_id'];
        $journal_cheque_pay_id = $_POST['journal_cheque_pay_id'];
        $journal_invoice_customer_id = $_POST['journal_invoice_customer_id'];
        $journal_invoice_supplier_id = $_POST['journal_invoice_supplier_id'];

        $journal_cash_payment_list_model->deleteJournalCashPaymentListByJournalCashPaymentIDNotIN($journal_cash_payment_id,$journal_cash_payment_list_id);

        if(is_array($account_id)){

            for($i=0; $i < count($account_id) ; $i++){
                $data = [];
                $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                $data['account_id'] = $account_id[$i];
                $data['journal_cheque_id'] = $journal_cheque_id[$i];
                $data['journal_cheque_pay_id'] = $journal_cheque_pay_id[$i];
                $data['journal_invoice_customer_id'] = $journal_invoice_customer_id[$i];
                $data['journal_invoice_supplier_id'] = $journal_invoice_supplier_id[$i];
                $data['journal_cash_payment_list_name'] = $journal_cash_payment_list_name[$i];
                $data['journal_cash_payment_list_debit'] = (float)filter_var($journal_cash_payment_list_debit[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['journal_cash_payment_list_credit'] = (float)filter_var($journal_cash_payment_list_credit[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                
                if ($journal_cash_payment_list_id[$i] != "" && $journal_cash_payment_list_id[$i] != '0'){
                    $journal_cash_payment_list_model->updateJournalCashPaymentListById($data,$journal_cash_payment_list_id[$i]);

                }else{
                    $out_id = $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
                }
            }
        }else{
            $data = [];
            $data['journal_cash_payment_id'] = $journal_cash_payment_id;
            $data['account_id'] = $account_id;
            $data['journal_cheque_id'] = $journal_cheque_id;
            $data['journal_cheque_pay_id'] = $journal_cheque_pay_id;
            $data['journal_invoice_customer_id'] = $journal_invoice_customer_id;
            $data['journal_invoice_supplier_id'] = $journal_invoice_supplier_id;
            $data['journal_cash_payment_list_name'] = $journal_cash_payment_list_name;
            $data['journal_cash_payment_list_debit'] = (float)filter_var($journal_cash_payment_list_debit, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['journal_cash_payment_list_credit'] = (float)filter_var($journal_cash_payment_list_credit, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            if ($journal_cash_payment_list_id != "" && $journal_cash_payment_list_id != '0'){
                $journal_cash_payment_list_model->updateJournalCashPaymentListById($data,$journal_cash_payment_list_id);
            }else{
                $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
            }
            
        }
        
        if($output){
    ?>
            <script>window.location="index.php?app=journal_special_04&action=insert"</script>
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
    $keyword = $_GET['keyword']; 
    $journal_cash_payments = $journal_cash_payment_model->getJournalCashPaymentBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');


}





?>