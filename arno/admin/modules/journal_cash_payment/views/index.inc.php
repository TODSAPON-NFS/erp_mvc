<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/JournalCashPaymentModel.php');
require_once('../models/JournalCashPaymentListModel.php');
require_once('../models/JournalCashPaymentInvoiceModel.php');
require_once('../models/AccountModel.php');
require_once('../models/UserModel.php');
require_once('../models/SupplierModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/journal_cash_payment/views/";
$account_model = new AccountModel;
$journal_cash_payment_model = new JournalCashPaymentModel;
$journal_cash_payment_list_model = new JournalCashPaymentListModel;
$journal_cash_payment_invoice_model = new JournalCashPaymentInvoiceModel;
$user_model = new UserModel;
$supplier_model = new SupplierModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('30');

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

                $data = [];
                $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                $data['supplier_id'] = $_POST['supplier_id'];
                $data['supplier_name'] = $_POST['supplier_name'];
                $data['invoice_code'] = $_POST['invoice_code'];
                $data['invoice_date'] = $_POST['invoice_date'];
                $data['vat_section'] = (float)filter_var($_POST['vat_section'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['vat_section_add'] = (float)filter_var($_POST['vat_section_add'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['product_price'] = (float)filter_var($_POST['product_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['product_vat'] = (float)filter_var($_POST['product_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['product_price_non'] = (float)filter_var($_POST['product_price_non'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['product_vat_non'] = (float)filter_var($_POST['product_vat_non'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['product_non'] = (float)filter_var($_POST['product_non'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $journal_cash_payment_invoice_model->insertJournalCashPaymentInvoice($data);


                $account_id = $_POST['account_id'];
                $journal_cash_payment_list_id = $_POST['journal_cash_payment_list_id'];
                $journal_cash_payment_list_name = $_POST['journal_cash_payment_list_name'];
                $journal_cash_payment_list_debit = $_POST['journal_cash_payment_list_debit'];
                $journal_cash_payment_list_credit = $_POST['journal_cash_payment_list_credit'];

                $journal_cash_payment_list_model->deleteJournalCashPaymentListByJournalCashPaymentIDNotIN($journal_cash_payment_id,$journal_cash_payment_list_id);

                if(is_array($account_id)){
                    for($i=0; $i < count($account_id) ; $i++){
                        $data = [];
                        $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                        $data['account_id'] = $account_id[$i];
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
            <script>window.location="index.php?app=journal_special_04&action=update&id=<?php echo $journal_cash_payment_id;?>"</script>
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


        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['supplier_name'] = $_POST['supplier_name'];
        $data['invoice_code'] = $_POST['invoice_code'];
        $data['invoice_date'] = $_POST['invoice_date'];
        $data['vat_section'] = (float)filter_var($_POST['vat_section'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['vat_section_add'] = (float)filter_var($_POST['vat_section_add'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['product_price'] = (float)filter_var($_POST['product_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['product_vat'] = (float)filter_var($_POST['product_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['product_price_non'] = (float)filter_var($_POST['product_price_non'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['product_vat_non'] = (float)filter_var($_POST['product_vat_non'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['product_non'] = (float)filter_var($_POST['product_non'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $journal_cash_payment_invoice_model->updateJournalCashPaymentInvoiceById($data,$journal_cash_payment_id);

        
        $account_id = $_POST['account_id'];
        $journal_cash_payment_list_id = $_POST['journal_cash_payment_list_id'];
        $journal_cash_payment_list_name = $_POST['journal_cash_payment_list_name'];
        $journal_cash_payment_list_debit = $_POST['journal_cash_payment_list_debit'];
        $journal_cash_payment_list_credit = $_POST['journal_cash_payment_list_credit'];

        $journal_cash_payment_list_model->deleteJournalCashPaymentListByJournalCashPaymentIDNotIN($journal_cash_payment_id,$journal_cash_payment_list_id);

        if(is_array($account_id)){

            for($i=0; $i < count($account_id) ; $i++){
                $data = [];
                $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                $data['account_id'] = $account_id[$i];
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
            <script>window.location="index.php?app=journal_special_04"</script>
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