<?php
require_once('../models/UserModel.php'); 
require_once('../models/CustomerModel.php'); 

$path = "modules/sale_employee/views/";
$model = new UserModel; 
$customer_model = new CustomerModel; 

$customer_id = $_GET['customer_id'];
$sale_id = $_GET['sale_id'];



if(!isset($_GET['action'])){

    $user = $model->getUserBy('','sale','');
    $customers = $customer_model->getCustomerBySaleID('0');
    $user_customer = [];
    for($i = 0; $i < count($user); $i++){
        $user_customer[$user[$i]['user_id']] = $customer_model->getCustomerBySaleID($user[$i]['user_id']);
    }

    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'sale' && ($license_sale_page == 'High')){

    $user = $customer_model->updateSaleIDByID($customer_id,$sale_id);
?>
    <script>window.location="index.php?app=sale_employee"</script>
<?php

}else if ($_GET['action'] == 'unsale' && ($license_sale_page == 'High')){

    $user = $customer_model->updateSaleIDByID($customer_id,'0');
?>
    <script>window.location="index.php?app=sale_employee"</script>
<?php

} else{

    $user = $model->getUserBy('','sale','');
    $customers = $customer_model->getCustomerBySaleID('0');
    $user_customer = [];
    for($i = 0; $i < count($user); $i++){
        $user_customer[$user[$i]['user_id']] = $customer_model->getCustomerBySaleID($user[$i]['user_id']);
    }

    require_once($path.'view.inc.php');

}





?>