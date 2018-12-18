<?php
require_once('../models/FinanceDebitAccountModel.php');
require_once('../models/AccountModel.php');
require_once('../models/BankAccountModel.php');
$path = "modules/finance_debit_account/views/";
$model_finance_debit_account = new FinanceDebitAccountModel;
$account_model = new AccountModel;
$bank_account_model = new BankAccountModel;
$finance_debit_account_id = $_GET['id'];

if(!isset($_GET['action'])){
       
    $finance_debit_account = $model_finance_debit_account->getFinanceDebitAccountByID($finance_debit_account_id);
    $finance_debit_accounts = $model_finance_debit_account->getFinanceDebitAccountBy();
    
    $bank_account = $bank_account_model->getBankAccountBy();
    $account = $account_model->getAccountAll();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete'){

    $model_finance_debit_account->deleteFinanceDebitAccountByID($finance_debit_account_id);
?>
    <script>window.location="index.php?app=finance_debit_account&action=view&id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['finance_debit_account_name'])){
        $data = [];
        $data['finance_debit_account_code'] = $_POST['finance_debit_account_code'];
        $data['finance_debit_account_name'] = $_POST['finance_debit_account_name'];
        $data['finance_debit_account_cheque'] = $_POST['finance_debit_account_cheque'];
        $data['finance_debit_account_id'] = $_POST['finance_debit_account_id'];
        $data['account_id'] = $_POST['account_id']; 

            $id = $model_finance_debit_account->insertFinanceDebitAccount($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=finance_debit_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=finance_debit_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['finance_debit_account_name'])){
        $data = [];
        $data['finance_debit_account_code'] = $_POST['finance_debit_account_code'];
        $data['finance_debit_account_name'] = $_POST['finance_debit_account_name'];
        $data['finance_debit_account_cheque'] = $_POST['finance_debit_account_cheque'];
        $data['finance_debit_account_id'] = $_POST['finance_debit_account_id'];
        $data['account_id'] = $_POST['account_id']; 

            
        $id = $model_finance_debit_account->updateFinanceDebitAccountByID($_POST['finance_debit_account_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=finance_debit_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=finance_debit_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else{
        $finance_debit_account = $model_finance_debit_account->getFinanceDebitAccountByID($finance_debit_account_id);
        $finance_debit_accounts = $model_finance_debit_account->getFinanceDebitAccountBy();
        
        $bank_account = $bank_account_model->getBankAccountBy();
        $account = $account_model->getAccountAll();
        require_once($path.'view.inc.php');

}





?>