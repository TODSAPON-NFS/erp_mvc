<?php
require_once('../models/UserModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/CustomerAccountModel.php');
$path = "modules/customer_account/views/";
$model_user = new UserModel;
$model_customer = new customerModel;
$model_customer_account = new CustomerAccountModel;
$customer_id = $_GET['id'];
$customer_account_id = $_GET['subid'];

if(!isset($_GET['action'])){
    $customer = $model_customer->getCustomerByID($customer_id);
    $customer_account = $model_customer_account->getCustomerAccountBy($customer_id);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'&& ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    $customer = $model_customer->getCustomerByID($customer_id);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'&& ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    $customer = $model_customer->getCustomerByID($customer_id);
    $customer_account = $model_customer_account->getCustomerAccountByID($customer_account_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'&& ($license_admin_page == 'High')){

    $model_customer_account->deleteCustomerAccountByID($customer_account_id);
?>
    <script>window.location="index.php?app=customer_account&action=view&id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add'&& ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    if(isset($_POST['customer_account_no'])){
        $data = [];
        $data['customer_id'] = $customer_id;
        $data['customer_account_no'] = $_POST['customer_account_no'];
        $data['customer_account_name'] = $_POST['customer_account_name'];
        $data['customer_account_bank'] = $_POST['customer_account_bank'];
        $data['customer_account_branch'] = $_POST['customer_account_branch'];
        $data['customer_account_detail'] = $_POST['customer_account_detail'];
        $data['addby'] = '';
       
            $id = $model_customer_account->insertCustomerAccount($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=customer_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=customer_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit'&& ($license_admin_page == 'Medium' || $license_admin_page == 'High')){
    if(isset($_POST['customer_account_no'])){
        $data = [];
        $data['customer_id'] = $customer_id;
        $data['customer_account_no'] = $_POST['customer_account_no'];
        $data['customer_account_name'] = $_POST['customer_account_name'];
        $data['customer_account_bank'] = $_POST['customer_account_bank'];
        $data['customer_account_branch'] = $_POST['customer_account_branch'];
        $data['customer_account_detail'] = $_POST['customer_account_detail'];
        $data['updateby'] = '';
            
        $id = $model_customer_account->updateCustomerAccountByID($_POST['customer_account_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=customer_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=customer_account&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else{

    $customer = $model_customer->getCustomerByID($customer_id);
    $customer_account = $model_customer_account->getCustomerAccountBy($customer_id);
    require_once($path.'view.inc.php');

}





?>