<?php
require_once('../models/UserModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/CustomerContactModel.php');
$path = "modules/customer_contact/views/";
$model_user = new UserModel;
$model_customer = new customerModel;
$model_customer_contact = new CustomerContactModel;
$customer_id = $_GET['id'];
$customer_contact_id = $_GET['subid'];

if(!isset($_GET['action'])){
    $customer = $model_customer->getCustomerByID($customer_id);
    $customer_contact = $model_customer_contact->getCustomerContactBy($customer_id);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $customer = $model_customer->getCustomerByID($customer_id);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $customer = $model_customer->getCustomerByID($customer_id);
    $customer_contact = $model_customer_contact->getCustomerContactByID($customer_contact_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'){

    $model_customer_contact->deleteCustomerContactByID($customer_contact_id);
?>
    <script>window.location="index.php?app=customer_contact&action=view&id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['customer_contact_name'])){
        $data = [];
        $data['customer_id'] = $customer_id;
        $data['customer_contact_name'] = $_POST['customer_contact_name'];
        $data['customer_contact_position'] = $_POST['customer_contact_position'];
        $data['customer_contact_tel'] = $_POST['customer_contact_tel'];
        $data['customer_contact_email'] = $_POST['customer_contact_email'];
        $data['customer_contact_detail'] = $_POST['customer_contact_detail'];
        $data['addby'] = '';
       
            $id = $model_customer_contact->insertCustomerContact($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=customer_contact&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=customer_contact&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['customer_contact_name'])){
        $data = [];
        $data['customer_id'] = $customer_id;
        $data['customer_contact_name'] = $_POST['customer_contact_name'];
        $data['customer_contact_position'] = $_POST['customer_contact_position'];
        $data['customer_contact_tel'] = $_POST['customer_contact_tel'];
        $data['customer_contact_email'] = $_POST['customer_contact_email'];
        $data['customer_contact_detail'] = $_POST['customer_contact_detail'];
        $data['updateby'] = '';
            
        $id = $model_customer_contact->updateCustomerContactByID($_POST['customer_contact_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=customer_contact&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=customer_contact&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else{

    $customer = $model_customer->getCustomerByID($customer_id);
    $customer_contact = $model_customer_contact->getCustomerContactBy($customer_id);
    require_once($path.'view.inc.php');

}





?>