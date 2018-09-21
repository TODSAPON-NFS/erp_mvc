<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/CheckPayModel.php');
require_once('../models/BankModel.php');
require_once('../models/BankAccountModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/UserModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/bank_check_pay/views/"; 
$supplier_model = new SupplierModel;
$account_model = new BankAccountModel;
$check_model = new CheckPayModel;
$user_model = new UserModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('24');

$check_pay_id = $_GET['id'];

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();
    $checks = $check_model->getCheckPayBy('0',$date_start,$date_end,$supplier_id,$keyword);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){

    $accounts=$account_model->getBankAccountBy();
    $suppliers=$supplier_model->getSupplierBy(); 
    
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
            $last_code =  $check_model->getCheckPayLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
    $first_date = date("d")."-".date("m")."-".date("Y"); 

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){

    $accounts=$account_model->getBankAccountBy();
    $suppliers=$supplier_model->getSupplierBy(); 

    $check = $check_model->getCheckPayByID($check_pay_id);
    $supplier=$supplier_model->getSupplierByID($check['supplier_id']);

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){

    $check = $check_model->getCheckPayViewByID($check_pay_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete'){

    $checks = $check_model->deleteCheckPayById($check_pay_id);
?>
    <script>window.location="index.php?app=bank_check_pay"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['check_pay_code'])){
  
        $data = [];
        $data['check_pay_code'] = $_POST['check_pay_code'];
        $data['check_pay_date_write'] = $_POST['check_pay_date_write'];
        $data['check_pay_date'] = $_POST['check_pay_date'];
        $data['bank_account_id'] = $_POST['bank_account_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['check_pay_remark'] = $_POST['check_pay_remark'];
        $data['check_pay_total'] = (float)filter_var($_POST['check_pay_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 
        $data['check_type'] = '0';
        $data['addby'] = $admin_id;

       
        $check_pay_id = $check_model->insertCheckPay($data);

    ?>
        <script>window.location="index.php?app=bank_check_pay&action=update&id=<?php echo $check_pay_id;?>"</script>
    <?php
    }else{
    ?>
    <script>window.history.back();</script>
    <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['check_pay_code'])){
        $data = [];
        $data['check_pay_code'] = $_POST['check_pay_code'];
        $data['check_pay_date_write'] = $_POST['check_pay_date_write'];
        $data['check_pay_date'] = $_POST['check_pay_date'];
        $data['bank_account_id'] = $_POST['bank_account_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['check_pay_remark'] = $_POST['check_pay_remark'];
        $data['check_pay_total'] =(float)filter_var($_POST['check_pay_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 
        $data['check_type'] = '0';
        $data['lastupdate'] = $admin_id;

        $output = $check_model->updateCheckPayByID($check_pay_id,$data);

?>
        <script>window.location="index.php?app=bank_check_pay"</script>
<?php

    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }

}else{
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();
    $checks = $check_model->getCheckPayBy('0',$date_start,$date_end,$supplier_id,$keyword);
    require_once($path.'view.inc.php');

}





?>