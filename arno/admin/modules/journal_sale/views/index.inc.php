<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/JournalSaleModel.php');
require_once('../models/JournalSaleListModel.php'); 
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

$path = "modules/journal_sale/views/";
$account_model = new AccountModel;
$bank_account_model = new BankAccountModel;
$account_setting_model = new AccountSettingModel;
$journal_sale_model = new JournalSaleModel;
$journal_sale_list_model = new JournalSaleListModel; 
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

$journal_sale_id = $_GET['id'];
$target_dir = "../upload/journal_sale/";

if(!isset($_GET['action'])){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword']; 

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;

    $journal_sales = $journal_sale_model->getJournalSaleBy($date_start,$date_end,$keyword);

    $page_max = (int)(count($journal_sales)/$page_size);
    if(count($journal_sales)%$page_size > 0){
        $page_max += 1;
    }

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
            $last_code = $journal_sale_model->getJournalSaleLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
    $first_date = date("d")."-".date("m")."-".date("Y");   


    if($journal_sale_id > 0){
       
        $journal_sale = $journal_sale_model->getJournalSaleByID($journal_sale_id);
        $journal_sale_lists = $journal_sale_list_model->getJournalSaleListBy($journal_sale_id);
    }

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){

    $accounts=$account_model->getAccountAll();
    $suppliers=$supplier_model->getSupplierBy();
    $customers=$customer_model->getCustomerBy();
    $banks=$bank_model->getBankBy();
    $bank_accounts=$bank_account_model->getBankAccountBy();

    $checks = $check_model->getCheckViewListByjournalPaymentID($journal_sale_id);
    $check_pays = $check_pay_model->getCheckPayViewListByjournalPaymentID($journal_sale_id);
    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierViewListByjournalPaymentID($journal_sale_id);
    $invoice_customers = $invoice_customer_model->getInvoiceCustomerViewListByjournalPaymentID($journal_sale_id);

    $journal_sale = $journal_sale_model->getJournalSaleByID($journal_sale_id);
    $journal_sale_lists = $journal_sale_list_model->getJournalSaleListBy($journal_sale_id); 

    
    $journal_sales = $journal_sale_model->getJournalSaleBy();

    for($i = 0 ; $i < count($journal_sales) ; $i++){
        if($journal_sale_id == $journal_sales[$i]['journal_sale_id']){
            $previous_id = $journal_sales[$i-1]['journal_sale_id'];
            $previous_code = $journal_sales[$i-1]['journal_sale_code'];
            $next_id = $journal_sales[$i+1]['journal_sale_id'];
            $next_code = $journal_sales[$i+1]['journal_sale_code'];

        }
    }
    
 
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $journal_sale = $journal_sale_model->getJournalSaleViewByID($journal_sale_id);
    $journal_sale_lists = $journal_sale_list_model->getJournalSaleListBy($journal_sale_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $journal_sale = $journal_sale_model->getJournalSaleViewByID($journal_sale_id);
    $journal_sale_lists = $journal_sale_list_model->getJournalSaleListBy($journal_sale_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){
 
    $journal_sale_list_model->deleteJournalSaleListByJournalSaleID($journal_sale_id);
    $journal_sales = $journal_sale_model->deleteJournalSaleById($journal_sale_id);
?>
    <script>window.location="index.php?app=journal_special_02"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['journal_sale_code'])){

        $data = [];
        $data['journal_sale_date'] = $_POST['journal_sale_date'];
        $data['journal_sale_code'] = $_POST['journal_sale_code'];
        $data['journal_sale_name'] = $_POST['journal_sale_name'];
        $data['addby'] = $user[0][0];


            $journal_sale_id = $journal_sale_model->insertJournalSale($data);

            if($journal_sale_id > 0){

                $account_id = $_POST['account_id'];
                $journal_sale_list_id = $_POST['journal_sale_list_id'];
                $journal_sale_list_name = $_POST['journal_sale_list_name'];
                $journal_sale_list_debit = $_POST['journal_sale_list_debit'];
                $journal_sale_list_credit = $_POST['journal_sale_list_credit'];
                $journal_cheque_id = $_POST['journal_cheque_id'];
                $journal_cheque_pay_id = $_POST['journal_cheque_pay_id'];
                $journal_invoice_customer_id = $_POST['journal_invoice_customer_id'];
                $journal_invoice_supplier_id = $_POST['journal_invoice_supplier_id'];


                $journal_sale_list_model->deleteJournalSaleListByJournalSaleIDNotIN($journal_sale_id,$journal_sale_list_id);

                if(is_array($account_id)){
                    for($i=0; $i < count($account_id) ; $i++){
                        $data = [];
                        $data['journal_sale_id'] = $journal_sale_id;
                        $data['account_id'] = $account_id[$i];
                        $data['journal_cheque_id'] = $journal_cheque_id[$i];
                        $data['journal_cheque_pay_id'] = $journal_cheque_pay_id[$i];
                        $data['journal_invoice_customer_id'] = $journal_invoice_customer_id[$i];
                        $data['journal_invoice_supplier_id'] = $journal_invoice_supplier_id[$i];
                        $data['journal_sale_list_name'] = $journal_sale_list_name[$i];
                        $data['journal_sale_list_debit'] = (float)filter_var($journal_sale_list_debit[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data['journal_sale_list_credit'] = (float)filter_var($journal_sale_list_credit[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                    

                        if ($journal_sale_list_id[$i] != "" && $journal_sale_list_id[$i] != '0'){
                            $journal_sale_list_model->updateJournalSaleListById($data,$journal_sale_list_id[$i]);
                        }else{
                            $out_id = $journal_sale_list_model->insertJournalSaleList($data);
                         
                        }
                    }
                }else{
                    $data = [];
                    $data['journal_sale_id'] = $journal_sale_id;
                    $data['account_id'] = $account_id;
                    $data['journal_cheque_id'] = $journal_cheque_id;
                    $data['journal_cheque_pay_id'] = $journal_cheque_pay_id;
                    $data['journal_invoice_customer_id'] = $journal_invoice_customer_id;
                    $data['journal_invoice_supplier_id'] = $journal_invoice_supplier_id;
                    $data['journal_sale_list_name'] = $journal_sale_list_name;
                    $data['journal_sale_list_debit'] = (float)filter_var($journal_sale_list_debit, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['journal_sale_list_credit'] = (float)filter_var($journal_sale_list_credit, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                    if ($journal_sale_list_id != "" && $journal_sale_list_id != '0'){
                        $journal_sale_list_model->updateJournalSaleListById($data,$journal_sale_list_id);
                    }else{
                        $journal_sale_list_model->insertJournalSaleList($data);
                    }
                    
                }

    ?>
            <script>window.location="index.php?app=journal_special_02&action=insert"</script>
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
    
    if(isset($_POST['journal_sale_code'])){
        $data = [];
        $data['journal_sale_date'] = $_POST['journal_sale_date'];
        $data['journal_sale_code'] = $_POST['journal_sale_code'];
        $data['journal_sale_name'] = $_POST['journal_sale_name'];
        $data['updateby'] = $user[0][0];

        $output = $journal_sale_model->updateJournalSaleByID($journal_sale_id,$data);

        
        $account_id = $_POST['account_id'];
        $journal_sale_list_id = $_POST['journal_sale_list_id'];
        $journal_sale_list_name = $_POST['journal_sale_list_name'];
        $journal_sale_list_debit = $_POST['journal_sale_list_debit'];
        $journal_sale_list_credit = $_POST['journal_sale_list_credit'];
        $journal_cheque_id = $_POST['journal_cheque_id'];
        $journal_cheque_pay_id = $_POST['journal_cheque_pay_id'];
        $journal_invoice_customer_id = $_POST['journal_invoice_customer_id'];
        $journal_invoice_supplier_id = $_POST['journal_invoice_supplier_id'];

        $journal_sale_list_model->deleteJournalSaleListByJournalSaleIDNotIN($journal_sale_id,$journal_sale_list_id);

        if(is_array($account_id)){

            for($i=0; $i < count($account_id) ; $i++){
                $data = [];
                $data['journal_sale_id'] = $journal_sale_id;
                $data['account_id'] = $account_id[$i];
                $data['journal_cheque_id'] = $journal_cheque_id[$i];
                $data['journal_cheque_pay_id'] = $journal_cheque_pay_id[$i];
                $data['journal_invoice_customer_id'] = $journal_invoice_customer_id[$i];
                $data['journal_invoice_supplier_id'] = $journal_invoice_supplier_id[$i];
                $data['journal_sale_list_name'] = $journal_sale_list_name[$i];
                $data['journal_sale_list_debit'] = (float)filter_var($journal_sale_list_debit[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['journal_sale_list_credit'] = (float)filter_var($journal_sale_list_credit[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                
                if ($journal_sale_list_id[$i] != "" && $journal_sale_list_id[$i] != '0'){
                    $journal_sale_list_model->updateJournalSaleListById($data,$journal_sale_list_id[$i]);

                }else{
                    $out_id = $journal_sale_list_model->insertJournalSaleList($data);
                }
            }
        }else{
            $data = [];
            $data['journal_sale_id'] = $journal_sale_id;
            $data['account_id'] = $account_id;
            $data['journal_cheque_id'] = $journal_cheque_id;
            $data['journal_cheque_pay_id'] = $journal_cheque_pay_id;
            $data['journal_invoice_customer_id'] = $journal_invoice_customer_id;
            $data['journal_invoice_supplier_id'] = $journal_invoice_supplier_id;
            $data['journal_sale_list_name'] = $journal_sale_list_name;
            $data['journal_sale_list_debit'] = (float)filter_var($journal_sale_list_debit, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['journal_sale_list_credit'] = (float)filter_var($journal_sale_list_credit, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            if ($journal_sale_list_id != "" && $journal_sale_list_id != '0'){
                $journal_sale_list_model->updateJournalSaleListById($data,$journal_sale_list_id);
            }else{
                $journal_sale_list_model->insertJournalSaleList($data);
            }
            
        }
        
        if($output){
    ?>
            <script>window.location="index.php?app=journal_special_02&action=insert"</script>
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

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;

    $journal_sales = $journal_sale_model->getJournalSaleBy($date_start,$date_end,$keyword);

    $page_max = (int)(count($journal_sales)/$page_size);
    if(count($journal_sales)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view.inc.php');


}





?>