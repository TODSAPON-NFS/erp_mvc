<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/CheckModel.php');
require_once('../models/BankModel.php');
require_once('../models/BankAccountModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/UserModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/bank_check_in/views/";
$bank_model = new BankModel;
$customer_model = new CustomerModel;
$account_model = new BankAccountModel;
$check_model = new CheckModel;
$user_model = new UserModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('23');

$check_id = $_GET['id'];

if($license_account_page == "Medium" || $license_account_page == "High"){
    $lock_1 = "1";
}else{
    $lock_1 = "0";
}

if($license_account_page == "Medium" || $license_account_page == "High"){
    $lock_2 = "1";
}else{
    $lock_2 = "0";
}

if(!isset($_GET['action'])){
    if(!isset($_GET['date_start'])){
        $date_start = $_SESSION['date_start'];
    }else{
        $date_start = $_GET['date_start'];
        $_SESSION['date_start'] = $date_start;
    }


    if(!isset($_GET['date_end'])){
        $date_end = $_SESSION['date_end'];
    }else{
        $date_end = $_GET['date_end'];
        $_SESSION['date_end'] = $date_end;
    }

    if(!isset($_GET['keyword'])){
        $keyword = $_SESSION['keyword'];
    }else{
        
        $keyword = $_GET['keyword']; 
        $_SESSION['keyword'] = $keyword;
    }

    
    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    }

    $customer_id = $_GET['customer_id'];
    
    $customers=$customer_model->getCustomerBy();
    $checks = $check_model->getCheckBy('0',$date_start,$date_end,$customer_id,$keyword,'','',$lock_1,$lock_2);
    $cheque_journals = [];
    for($i=0; $i < count($checks); $i++){
        $cheque_journals[$checks[$i]['check_id']] = $check_model->getJournalByChequeID($checks[$i]['check_id']);
    } 
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){

    $accounts=$account_model->getBankAccountBy();
    $customers=$customer_model->getCustomerBy();
    $banks=$bank_model->getBankBy();

    $user=$user_model->getUserByID($admin_id);

    $data = [];
    $data['year'] = date("Y");
    $data['month'] = date("m");
    $data['number'] = "0000000000";
    $data['employee_name'] = $user["user_name_en"];
    $data['customer_code'] = $customer["customer_code"];

    $code = $code_generate->cut2Array($paper['paper_code'],$data);
    $last_code = "";
    for($i = 0 ; $i < count($code); $i++){
    
        if($code[$i]['type'] == "number"){
            $last_code =  $check_model->getCheckLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
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
    <script>window.location="index.php?app=bank_check_in"</script>
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
        $data['check_total'] = (float)filter_var($_POST['check_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['check_type'] = '0';
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
        $data['check_total'] = (float)filter_var($_POST['check_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['check_type'] = '0';
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
    if(!isset($_GET['date_start'])){
        $date_start = $_SESSION['date_start'];
    }else{
        $date_start = $_GET['date_start'];
        $_SESSION['date_start'] = $date_start;
    }


    if(!isset($_GET['date_end'])){
        $date_end = $_SESSION['date_end'];
    }else{
        $date_end = $_GET['date_end'];
        $_SESSION['date_end'] = $date_end;
    }

    if(!isset($_GET['keyword'])){
        $keyword = $_SESSION['keyword'];
    }else{
        
        $keyword = $_GET['keyword']; 
        $_SESSION['keyword'] = $keyword;
    }

    
    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    }

    $customer_id = $_GET['customer_id'];
    
    $customers=$customer_model->getCustomerBy();
    $checks = $check_model->getCheckBy('0',$date_start,$date_end,$customer_id,$keyword,'','',$lock_1,$lock_2);
    $cheque_journals = [];
    for($i=0; $i < count($checks); $i++){
        $cheque_journals[$checks[$i]['check_id']] = $check_model->getJournalByChequeID($checks[$i]['check_id']);
    }
 
    require_once($path.'view.inc.php');

}





?>