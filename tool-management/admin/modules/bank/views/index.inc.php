<?php
require_once('../models/BankModel.php');
$path = "modules/bank/views/";
$model_bank = new BankModel;
$bank_id = $_GET['id'];

if(!isset($_GET['action'])){
    $bank = $model_bank->getBankByID($bank_id);
    $banks = $model_bank->getBankBy();
    
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete'){

    $model_bank->deleteBankByID($bank_id);
?>
    <script>window.location="index.php?app=bank&action=view&id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['bank_name'])){
        $data = [];
        $data['bank_code'] = $_POST['bank_code'];
        $data['bank_name'] = $_POST['bank_name'];
        $data['bank_branch'] = $_POST['bank_branch'];
        $data['bank_number'] = $_POST['bank_number'];
        $data['bank_title'] = $_POST['bank_title'];
        $data['account_id'] = $_POST['account_id'];

            $id = $model_bank->insertBank($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=bank&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=bank&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['bank_name'])){
        $data = [];
        $data['bank_code'] = $_POST['bank_code'];
        $data['bank_name'] = $_POST['bank_name'];
        $data['bank_branch'] = $_POST['bank_branch'];
        $data['bank_number'] = $_POST['bank_number'];
        $data['bank_title'] = $_POST['bank_title'];
        $data['account_id'] = $_POST['account_id'];
            
        $id = $model_bank->updateBankByID($_POST['bank_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=bank&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=bank&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else{
        $bank = $model_bank->getBankByID($bank_id);
        $banks = $model_bank->getBankBy();
        
        require_once($path.'view.inc.php');

}





?>