<?php
require_once('../models/BankAccountModel.php');
require_once('../models/AccountModel.php');
$path = "modules/bank_account/views/";
$model_bank_account = new BankAccountModel;
$account_model = new AccountModel;
$bank_account_id = $_GET['id'];

if(!isset($_GET['action'])){
    $bank_account = $model_bank_account->getBankAccountByID($bank_account_id);
    $bank_accounts = $model_bank_account->getBankAccountBy();
    
    $account = $account_model->getAccountAll();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete'){

    $model_bank_account->deleteBankAccountByID($bank_account_id);
?>
    <script>window.location="index.php?app=bank_account&action=view&id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['bank_account_name'])){
        $data = [];
        $data['bank_account_code'] = $_POST['bank_account_code'];
        $data['bank_account_name'] = $_POST['bank_account_name'];
        $data['bank_account_branch'] = $_POST['bank_account_branch'];
        $data['bank_account_number'] = $_POST['bank_account_number'];
        $data['bank_account_title'] = $_POST['bank_account_title'];
        $data['account_id'] = $_POST['account_id'];

            $id = $model_bank_account->insertBankAccount($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=bank_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=bank_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['bank_account_name'])){
        $data = [];
        $data['bank_account_code'] = $_POST['bank_account_code'];
        $data['bank_account_name'] = $_POST['bank_account_name'];
        $data['bank_account_branch'] = $_POST['bank_account_branch'];
        $data['bank_account_number'] = $_POST['bank_account_number'];
        $data['bank_account_title'] = $_POST['bank_account_title'];
        $data['account_id'] = $_POST['account_id'];
            
        $id = $model_bank_account->updateBankAccountByID($_POST['bank_account_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=bank_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=bank_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else{
        $bank_account = $model_bank_account->getBankAccountByID($bank_account_id);
        $bank_accounts = $model_bank_account->getBankAccountBy();
        
        $account = $account_model->getAccountAll();
        require_once($path.'view.inc.php');

}





?>