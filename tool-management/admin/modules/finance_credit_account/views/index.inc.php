<?php
require_once('../models/FinanceCreditAccountModel.php');
require_once('../models/AccountModel.php');
require_once('../models/BankAccountModel.php');
$path = "modules/finance_credit_account/views/";
$model_finance_credit_account = new FinanceCreditAccountModel;
$account_model = new AccountModel;
$bank_account_model = new BankAccountModel;
$finance_credit_account_id = $_GET['id'];

if(!isset($_GET['action'])){
       
    $finance_credit_account = $model_finance_credit_account->getFinanceCreditAccountByID($finance_credit_account_id);
    $finance_credit_accounts = $model_finance_credit_account->getFinanceCreditAccountBy();
    
    $bank_account = $bank_account_model->getBankAccountBy();
    $account = $account_model->getAccountAll();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete'){

    $model_finance_credit_account->deleteFinanceCreditAccountByID($finance_credit_account_id);
?>
    <script>window.location="index.php?app=finance_credit_account&action=view&id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['finance_credit_account_name'])){
        $data = [];
        $data['finance_credit_account_code'] = $_POST['finance_credit_account_code'];
        $data['finance_credit_account_name'] = $_POST['finance_credit_account_name'];
        $data['finance_credit_account_cheque'] = $_POST['finance_credit_account_cheque'];
        $data['finance_credit_account_id'] = $_POST['finance_credit_account_id'];
        $data['account_id'] = $_POST['account_id']; 
        $data['bank_account_id'] = $_POST['bank_account_id']; 
            $id = $model_finance_credit_account->insertFinanceCreditAccount($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=finance_credit_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=finance_credit_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['finance_credit_account_name'])){
        $data = [];
        $data['finance_credit_account_code'] = $_POST['finance_credit_account_code'];
        $data['finance_credit_account_name'] = $_POST['finance_credit_account_name'];
        $data['finance_credit_account_cheque'] = $_POST['finance_credit_account_cheque'];
        $data['finance_credit_account_id'] = $_POST['finance_credit_account_id'];
        $data['account_id'] = $_POST['account_id']; 
        $data['bank_account_id'] = $_POST['bank_account_id']; 
            
        $id = $model_finance_credit_account->updateFinanceCreditAccountByID($_POST['finance_credit_account_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=finance_credit_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=finance_credit_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else{
        $finance_credit_account = $model_finance_credit_account->getFinanceCreditAccountByID($finance_credit_account_id);
        $finance_credit_accounts = $model_finance_credit_account->getFinanceCreditAccountBy();
        
        $bank_account = $bank_account_model->getBankAccountBy();
        $account = $account_model->getAccountAll();
        require_once($path.'view.inc.php');

}





?>