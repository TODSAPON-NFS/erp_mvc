<?php
session_start();
$user = $_SESSION['user'];
require_once('../functions/NumbertoTextFunction.func.php');
require_once('../models/FinanceCreditModel.php');
require_once('../models/FinanceCreditListModel.php');
require_once('../models/FinanceCreditPayModel.php');
require_once('../models/FinanceCreditAccountModel.php');
require_once('../models/BankAccountModel.php');
require_once('../models/AccountModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/InvoiceSupplierModel.php');

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
$account_model = new AccountModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('21');


$finance_credit_id = $_GET['id'];
$notification_id = $_GET['notification'];
$supplier_id = $_GET['supplier_id'];
$vat = 7; 

if(!isset($_GET['action'])){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();

    $finance_credits = $finance_credit_model->getFinanceCreditBy($date_start,$date_end,$supplier_id,$keyword);
    $supplier_orders = $finance_credit_model->getSupplierOrder();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){ 
    $suppliers=$supplier_model->getSupplierBy();
    $finance_credit_accounts=$finance_credit_account_model->getFinanceCreditAccountNoJoinBy();
    $bank_accounts=$bank_account_model->getBankAccountBy();
    $accounts=$account_model->getAccountAll();

    $supplier=$supplier_model->getSupplierByID($supplier_id);
    $finance_credit_lists = $finance_credit_model->generateFinanceCreditListBySupplierId($supplier_id);
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
    $finance_credit_model->deleteFinanceCreditById($finance_credit_id);
?>
    <script>window.location="index.php?app=finance_credit"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['finance_credit_code'])){
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

        
        if($finance_credit_id > 0){
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
            }else if($invoice_supplier_id != ""){
                $data_sub = [];
                $data_sub['finance_credit_id'] = $finance_credit_id;
                $data_sub['invoice_supplier_id'] = $invoice_supplier_id;
                $data_sub['finance_credit_list_recieve'] = $finance_credit_list_recieve;
                $data_sub['finance_credit_list_receipt'] = $finance_credit_list_receipt;
                $data_sub['finance_credit_list_amount'] = (float)filter_var($finance_credit_list_amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['finance_credit_list_paid'] = (float)filter_var($finance_credit_list_paid, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['finance_credit_list_balance'] = (float)filter_var($finance_credit_list_balance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['finance_credit_list_remark'] = $finance_credit_list_remark;
    
                $id = $finance_credit_list_model->insertFinanceCreditList($data_sub);
            }

            $finance_credit_pay_id = $_POST['finance_credit_pay_id'];
            $finance_credit_account_id = $_POST['finance_credit_account_id'];
            $finance_credit_pay_by = $_POST['finance_credit_pay_by'];
            $finance_credit_pay_date = $_POST['finance_credit_pay_date']; 
            $bank_account_id = $_POST['bank_account_id'];
            $finance_credit_pay_bank = $_POST['finance_credit_pay_bank'];
            $account_id = $_POST['account_id'];
            $finance_credit_pay_value = $_POST['finance_credit_pay_value'];
            $finance_credit_pay_balance = $_POST['finance_credit_pay_balance'];
            $finance_credit_pay_total = $_POST['finance_credit_pay_total'];



            $finance_credit_pay_model->deleteFinanceCreditPayByFinanceCreditPayIDNotIN($finance_credit_id,$finance_credit_pay_id);

            if(is_array($finance_credit_pay_id)){
                for($i=0; $i < count($finance_credit_pay_id) ; $i++){
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
                    if($finance_credit_pay_id[$i] == 0){
                        $finance_credit_pay_model->insertFinanceCreditPay($data);
                    }else{
                        $finance_credit_pay_model->updateFinanceCreditPayById($data,$finance_credit_pay_id[$i]);
                    }
                }
            }else{
                $data = [];
                $data['finance_credit_id'] = $finance_credit_id;
                $data['finance_credit_account_id'] = $finance_credit_account_id;
                $data['finance_credit_pay_by'] = $finance_credit_pay_by;
                $data['finance_credit_pay_date'] = $finance_credit_pay_date;
                $data['bank_account_id'] = $bank_account_id;
                $data['finance_credit_pay_bank'] = $finance_credit_pay_bank;
                $data['account_id'] = $account_id;
                $data['finance_credit_pay_value'] = (float)filter_var($finance_credit_pay_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['finance_credit_pay_balance'] = (float)filter_var($finance_credit_pay_balance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['finance_credit_pay_total'] = (float)filter_var($finance_credit_pay_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['updateby'] = $user[0][0];

                if($finance_credit_pay_id == 0){
                    $finance_credit_pay_model->insertFinanceCreditPay($data);
                }else{
                    $finance_credit_pay_model->updateFinanceCreditPayById($data,$finance_credit_pay_id);
                }
            }


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
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['finance_credit_code'])){
        
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


        $invoice_supplier_id = $_POST['invoice_supplier_id'];
        $finance_credit_list_recieve = $_POST['finance_credit_list_recieve'];
        $finance_credit_list_receipt = $_POST['finance_credit_list_receipt'];
        $finance_credit_list_amount = $_POST['finance_credit_list_amount'];
        $finance_credit_list_paid = $_POST['finance_credit_list_paid'];
        $finance_credit_list_balance = $_POST['finance_credit_list_balance'];
        $finance_credit_list_remark = $_POST['finance_credit_list_remark'];

        
        $finance_credit_list_model->deleteFinanceCreditListByFinanceCreditIDNotIN($finance_credit_id,$finance_credit_list_id);
        
        

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
        }else if($invoice_supplier_id != ""){
            $data_sub = [];
            $data_sub['finance_credit_id'] = $finance_credit_id;
            $data_sub['invoice_supplier_id'] = $invoice_supplier_id;
            $data_sub['finance_credit_list_recieve'] = $finance_credit_list_recieve;
            $data_sub['finance_credit_list_receipt'] = $finance_credit_list_receipt;
            $data_sub['finance_credit_list_amount'] = (float)filter_var($finance_credit_list_amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['finance_credit_list_paid'] = (float)filter_var($finance_credit_list_paid, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['finance_credit_list_balance'] = (float)filter_var($finance_credit_list_balance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['finance_credit_list_remark'] = $finance_credit_list_remark;

            if($finance_credit_list_id != "0"){
                $finance_credit_list_model->updateFinanceCreditListById($data_sub,$finance_credit_list_id);
            }else{
                $id = $finance_credit_list_model->insertFinanceCreditList($data_sub);
            }
        }

        $finance_credit_pay_id = $_POST['finance_credit_pay_id'];
        $finance_credit_account_id = $_POST['finance_credit_account_id'];
        $finance_credit_pay_by = $_POST['finance_credit_pay_by'];
        $finance_credit_pay_date = $_POST['finance_credit_pay_date']; 
        $bank_account_id = $_POST['bank_account_id'];
        $finance_credit_pay_bank = $_POST['finance_credit_pay_bank'];
        $account_id = $_POST['account_id'];
        $finance_credit_pay_value = $_POST['finance_credit_pay_value'];
        $finance_credit_pay_balance = $_POST['finance_credit_pay_balance'];
        $finance_credit_pay_total = $_POST['finance_credit_pay_total'];



        $finance_credit_pay_model->deleteFinanceCreditPayByFinanceCreditPayIDNotIN($finance_credit_id,$finance_credit_pay_id);

        if(is_array($finance_credit_pay_id)){
            for($i=0; $i < count($finance_credit_pay_id) ; $i++){
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
                if($finance_credit_pay_id[$i] == 0){
                    $finance_credit_pay_model->insertFinanceCreditPay($data);
                }else{
                    $finance_credit_pay_model->updateFinanceCreditPayById($data,$finance_credit_pay_id[$i]);
                }
            }
        }else{
            $data = [];
            $data['finance_credit_id'] = $finance_credit_id;
            $data['finance_credit_account_id'] = $finance_credit_account_id;
            $data['finance_credit_pay_by'] = $finance_credit_pay_by;
            $data['finance_credit_pay_date'] = $finance_credit_pay_date;
            $data['bank_account_id'] = $bank_account_id;
            $data['finance_credit_pay_bank'] = $finance_credit_pay_bank;
            $data['account_id'] = $account_id;
            $data['finance_credit_pay_value'] = (float)filter_var($finance_credit_pay_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['finance_credit_pay_balance'] = (float)filter_var($finance_credit_pay_balance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['finance_credit_pay_total'] = (float)filter_var($finance_credit_pay_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['updateby'] = $user[0][0];

            if($finance_credit_pay_id == 0){
                $finance_credit_pay_model->insertFinanceCreditPay($data);
            }else{
                $finance_credit_pay_model->updateFinanceCreditPayById($data,$finance_credit_pay_id);
            }
        }

        $output = $finance_credit_model->updateFinanceCreditByID($finance_credit_id,$data);
        

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

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();
    
    $finance_credits = $finance_credit_model->getFinanceCreditBy($date_start,$date_end,$supplier_id,$keyword);
    $supplier_orders = $finance_credit_model->getSupplierOrder();
    require_once($path.'view.inc.php');

}





?>