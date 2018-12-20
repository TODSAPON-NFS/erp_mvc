<?php
require_once('../models/UserModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/CustomerLogisticModel.php');
$path = "modules/customer_logistic/views/";
$model_user = new UserModel;
$model_customer = new customerModel;
$model_customer_logistic = new CustomerLogisticModel;
$customer_id = $_GET['id'];
$customer_logistic_id = $_GET['subid'];

if(!isset($_GET['action'])){
    $customer = $model_customer->getCustomerByID($customer_id);
    $customer_logistic = $model_customer_logistic->getCustomerLogisticBy($customer_id);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    $customer = $model_customer->getCustomerByID($customer_id);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    $customer = $model_customer->getCustomerByID($customer_id);
    $customer_logistic = $model_customer_logistic->getCustomerLogisticByID($customer_logistic_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'  && ( $license_admin_page == 'High') ){

    $model_customer_logistic->deleteCustomerLogisticByID($customer_logistic_id);
?>
    <script>window.location="index.php?app=customer_logistic&action=view&id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['customer_logistic_name'])){
        $data = [];
        $data['customer_id'] = $customer_id;
        $data['customer_logistic_name'] = $_POST['customer_logistic_name'];
        $data['customer_logistic_detail'] = $_POST['customer_logistic_detail'];
        $data['customer_logistic_lead_time'] = $_POST['customer_logistic_lead_time'];
        $data['addby'] = '';
       
            $id = $model_customer_logistic->insertCustomerLogistic($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=customer_logistic&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=customer_logistic&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['customer_logistic_name'])){
        $data = [];
        $data['customer_id'] = $customer_id;
        $data['customer_logistic_name'] = $_POST['customer_logistic_name'];
        $data['customer_logistic_detail'] = $_POST['customer_logistic_detail'];
        $data['customer_logistic_lead_time'] = $_POST['customer_logistic_lead_time'];
        $data['updateby'] = '';
            
        $id = $model_customer_logistic->updateCustomerLogisticByID($_POST['customer_logistic_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=customer_logistic&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=customer_logistic&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else{

    $customer = $model_customer->getCustomerByID($customer_id);
    $customer_logistic = $model_customer_logistic->getCustomerLogisticBy($customer_id);
    require_once($path.'view.inc.php');

}





?>