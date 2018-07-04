<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/CheckModel.php');
require_once('../models/BankModel.php');
require_once('../models/BankAccountModel.php');
require_once('../models/CustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/bank_check_in/views/";
$bank_model = new BankModel;
$customer_model = new CustomerModel;
$account_model = new BankAccountModel;
$check_model = new CheckModel;
$check_id = $_GET['id'];

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();
    $checks = $check_model->getCheckBy($date_start,$date_end,$customer_id,$keyword);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){

    $accounts=$account_model->getBankAccountBy();
    $customers=$customer_model->getCustomerBy();
    $banks=$bank_model->getBankBy();
    
    $first_date = date("d")."-".date("m")."-".date("Y");

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){

    $accounts=$account_model->getBankAccountBy();
    $customers=$customer_model->getCustomerBy();
    $banks=$bank_model->getBankBy();

    $check = $check_model->getCheckByID($check_id);
    $customer=$customer_model->getCustomerByID($check['customer_id']);

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){

    $check = $check_model->getCheckViewByID($check_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete'){

    $checks = $check_model->deleteCheckById($check_id);
?>
    <script>window.location="index.php?app=check"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['check_code'])){
  
        $data = [];
        $data['check_code'] = $_POST['check_code'];
        $data['check_date_write'] = $_POST['check_date_write'];
        $data['check_date_recieve'] = $_POST['check_date_recieve'];
        $data['bank_id'] = $_POST['bank_id'];
        $data['bank_branch'] = $_POST['bank_branch'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['check_remark'] = $_POST['check_remark'];
        $data['check_total'] = $_POST['check_total'];
        $data['addby'] = $user[0][0];

       
        $check_id = $check_model->insertCheck($data);

    ?>
        <script>window.location="index.php?app=bank_check_in&action=update&id=<?php echo $check_id;?>"</script>
    <?php
    }else{
    ?>
    <script>window.history.back();</script>
    <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['check_code'])){
        $data = [];
        $data['check_code'] = $_POST['check_code'];
        $data['check_date_write'] = $_POST['check_date_write'];
        $data['check_date_recieve'] = $_POST['check_date_recieve'];
        $data['bank_id'] = $_POST['bank_id'];
        $data['bank_branch'] = $_POST['bank_branch'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['check_remark'] = $_POST['check_remark'];
        $data['check_total'] = $_POST['check_total'];
        $data['lastupdate'] = $user[0][0];

        $output = $check_model->updateCheckByID($check_id,$data);

?>
        <script>window.location="index.php?app=bank_check_in"</script>
<?php

    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }

}else{
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();
    $checks = $check_model->getCheckBy($date_start,$date_end,$customer_id,$keyword);
    require_once($path.'view.inc.php');

}





?>