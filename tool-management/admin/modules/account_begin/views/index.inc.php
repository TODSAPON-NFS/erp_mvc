<?php
require_once('../models/AccountModel.php');
$path = "modules/account_begin/views/";
$model_account = new AccountModel;

if(!isset($_GET['action'])){
    $accounts = $model_account->getAccountNode();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'edit'){

        $account_id = $_POST['account_id'];
        $account_credit_begin = $_POST['account_credit_begin'];
        $account_debit_begin = $_POST['account_debit_begin'];

        if(is_array($account_id)){
                for($i=0; $i < count($account_id) ; $i++){
                        $data = [];
                        $data['account_credit_begin'] = (float)filter_var($account_credit_begin[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data['account_debit_begin'] = (float)filter_var($account_debit_begin[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            
                        $id = $model_account->updateBeginByID($account_id[$i],$data);
                }
        }else{
                $data = [];
                $data['account_credit_begin'] = (float)filter_var($account_credit_begin, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['account_debit_begin'] = (float)filter_var($account_debit_begin, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                $id = $model_account->updateBeginByID($account_id,$data);
        }
        
    ?>
         <script>window.location="index.php?app=summit_account&action=view"</script>
    <?php
    
    
}else{
        $accounts = $model_account->getAccountNode();
        require_once($path.'view.inc.php');

}





?>