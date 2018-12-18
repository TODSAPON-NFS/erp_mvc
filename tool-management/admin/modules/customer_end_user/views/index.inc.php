<?php
require_once('../models/CustomerModel.php');
$path = "modules/customer_end_user/views/";
$model_customer = new CustomerModel;
$customer_id = $_GET['customer_id'];
$end_user_id = $_GET['id'];

if(!isset($_GET['action'])){

    $customer = $model_customer->getCustomerByID($customer_id);
    $customers = $model_customer->getCustomerNotEndUserByID($customer_id);
    $customer_end_users = $model_customer->getEndUserByCustomerID($customer_id);

    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_admin_page == 'High') ){

    $model_customer->deleteEndUserByID($end_user_id);
?>
    <script>window.location="index.php?app=customer_end_users&action=view&customer_id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
        $end_user_id = $_POST['end_user_id'];
        $model_customer->insertEndUserByID($customer_id,$end_user_id);
    ?>
            <script>window.location="index.php?app=customer_end_users&action=view&customer_id=<?php echo $customer_id;?>"</script>
    <?php
           
}else{

        if($_GET['page'] == '' || $_GET['page'] == '0'){
                $page = 0;
        }else{
                $page = $_GET['page'] - 1;
        }

        $page_size = 100;

        $customer = $model_customer->getCustomerByID($customer_id);
        $customers = $model_customer->getCustomerNotEndUserByID($customer_id);
        $customer_end_users = $model_customer->getEndUserByCustomerID($customer_id);

        $page_max = (int)(count($customer_end_users)/$page_size);
        if(count($customer_end_users)%$page_size > 0){
                $page_max += 1;
        }

        

        require_once($path.'view.inc.php');

}





?>