<?php
require_once('../models/AccountSettingModel.php');
require_once('../models/AccountGroupModel.php');
require_once('../models/AccountModel.php');

$path = "modules/account_setting/views/";

$model_account_setting = new AccountSettingModel;
$model_account_group = new AccountGroupModel;
$account_model = new AccountModel;
$account_id = $_GET['id'];


if(!isset($_GET['action'])){     
        $account_groups = $model_account_group->getAccountGroupBy();

        $account_settings = [];
        for( $i=0; $i < count($account_groups); $i++ ){
                $account_settings[$account_groups[$i]['account_group_id']] = $model_account_setting->getAccountSettingByAccountGroupID($account_groups[$i]['account_group_id']);
        }
        

        $accounts = $account_model->getAccountAll();
        require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'edit'){
        if(isset($_POST['account_setting_id'])){
                $account_id = $_POST['account_id'];
                $account_setting_id = $_POST['account_setting_id'];
                for($i = 0 ; $i < count($account_setting_id) ; $i++ ){
                        $data = [];
                        $data['account_id'] = $account_id[$i]; 
                        $data['account_setting_id'] = $account_setting_id[$i]; 
                        $model_account_setting->updateAccountIDByID($account_setting_id[$i],$data); 
                }
        }
        ?>
        <script>window.location="index.php?app=account_setting&action=view"</script>
        <?php 
    
}else{
        $account_groups = $model_account_group->getAccountGroupBy();

        $account_settings = [];
        for( $i=0; $i < count($account_groups); $i++ ){
                $account_settings[$account_groups[$i]['account_group_id']] = $model_account_setting->getAccountSettingByAccountGroupID($account_groups[$i]['account_group_id']);
        }

        $accounts = $account_model->getAccountAll();
        require_once($path.'view.inc.php');

}





?>