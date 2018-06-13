<?php
session_start();
$user = $_SESSION['user'];
require_once('../models/OtherExpenseModel.php');
require_once('../models/OtherExpenseListModel.php');
require_once('../models/OtherExpensePayModel.php');
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/other_expense/views/"; 
$supplier_model = new SupplierModel;
$other_expense_model = new OtherExpenseModel;
$other_expense_list_model = new OtherExpenseListModel;
$other_expense_pay_model= new OtherExpensePayModel;
$first_char = "OE";
$other_expense_id = $_GET['id']; 
$vat = 7;
if(!isset($_GET['action'])){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();
    $other_expenses = $other_expense_model->getOtherExpenseBy($date_start,$date_end,$supplier_id,$keyword,$purchase_order_id);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){ 
    $suppliers=$supplier_model->getSupplierBy(); 
    $first_code = $first_char.date("y").date("m");

    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $other_expense_model->getOtherExpenseLastID($first_code,3);
    
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){ 
    $suppliers=$supplier_model->getSupplierBy(); 

    $other_expense = $other_expense_model->getOtherExpenseByID($other_expense_id);
    $other_expense_lists = $other_expense_list_model->getOtherExpenseListBy($other_expense_id);
    
    $other_expense_pays = $other_expense_pay_model->getOtherExpensePayBy($other_expense_id);
    
    $supplier=$supplier_model->getSupplierByID($other_expense['supplier_id']);
    $vat = $other_expense['other_expense_vat'];
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $other_expense = $other_expense_model->getOtherExpenseViewByID($other_expense_id);
    $other_expense_lists = $other_expense_list_model->getOtherExpenseListBy($other_expense_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete'){
    $other_expenses = $other_expense_model->deleteOtherExpenseById($other_expense_id);
?>
    <script>window.location="index.php?app=other_expense"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['other_expense_code'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['other_expense_code'] = $_POST['other_expense_code'];
        $data['other_expense_date'] = $_POST['other_expense_date'];
        $data['other_expense_vat_type'] = $_POST['other_expense_vat_type'];
        $data['other_expense_bill_code'] = $_POST['other_expense_bill_code'];
        $data['other_expense_bill_date'] = $_POST['other_expense_bill_date'];
        $data['other_expense_remark'] = $_POST['other_expense_remark'];
        $data['other_expense_total'] = (float)filter_var($_POST['other_expense_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_vat'] = (float)filter_var($_POST['other_expense_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_vat_value'] = (float)filter_var($_POST['other_expense_vat_value'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_net'] = (float)filter_var($_POST['other_expense_net'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_interest'] = (float)filter_var($_POST['other_expense_interest'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_cash'] = (float)filter_var($_POST['other_expense_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_other_pay'] = (float)filter_var($_POST['other_expense_other_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_vat_pay'] = (float)filter_var($_POST['other_expense_vat_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_discount_cash'] = (float)filter_var($_POST['other_expense_discount_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_pay'] = (float)filter_var($_POST['other_expense_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['addby'] = $user[0][0];

        $other_expense_id = $other_expense_model->insertOtherExpense($data);

        $other_expense_list_id = $_POST['other_expense_list_id'];
        $other_expense_list_code = $_POST['other_expense_list_code'];
        $other_expense_list_name = $_POST['other_expense_list_name'];
        $other_expense_list_total = $_POST['other_expense_list_total'];

        $other_expense_list_model->deleteOtherExpenseListByOtherExpenseListIDNotIN($other_expense_id,$other_expense_list_id);

        if(is_array($other_expense_list_id)){
            for($i=0; $i < count($other_expense_list_id) ; $i++){
                $data = [];
                $data['other_expense_id'] = $other_expense_id;
                $data['other_expense_list_code'] = $other_expense_list_code[$i];
                $data['other_expense_list_name'] = $other_expense_list_name[$i];
                $data['other_expense_list_total'] = (float)filter_var($other_expense_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['updateby'] = $user[0][0];

                if($other_expense_list_id[$i] == 0){
                    $other_expense_list_model->insertOtherExpenseList($data);
                }else{
                    $other_expense_list_model->updateOtherExpenseListById($data,$other_expense_list_id[$i]);
                }
            }
        }else{
            $data = [];
            $data['other_expense_id'] = $other_expense_id;
            $data['other_expense_list_code'] = $other_expense_list_code;
            $data['other_expense_list_name'] = $other_expense_list_name;
            $data['other_expense_list_total'] = (float)filter_var($other_expense_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['updateby'] = $user[0][0];

            if($other_expense_list_id == 0){
                $other_expense_list_model->insertOtherExpenseList($data);
            }else{
                $other_expense_list_model->updateOtherExpenseListById($data,$other_expense_list_id);
            }
        }


        $other_expense_pay_id = $_POST['other_expense_pay_id'];
        $other_expense_pay_by = $_POST['other_expense_pay_by'];
        $other_expense_pay_date = $_POST['other_expense_pay_date']; 
        $other_expense_pay_bank = $_POST['other_expense_pay_bank'];
        $other_expense_pay_value = $_POST['other_expense_pay_value'];
        $other_expense_pay_balance = $_POST['other_expense_pay_balance'];
        $other_expense_pay_total = $_POST['other_expense_pay_total'];



        $other_expense_pay_model->deleteOtherExpensePayByOtherExpensePayIDNotIN($other_expense_id,$other_expense_pay_id);

        if(is_array($other_expense_pay_id)){
            for($i=0; $i < count($other_expense_pay_id) ; $i++){
                $data = [];
                $data['other_expense_id'] = $other_expense_id;
                $data['other_expense_pay_by'] = $other_expense_pay_by[$i];
                $data['other_expense_pay_date'] = $other_expense_pay_date[$i];
                $data['other_expense_pay_bank'] = $other_expense_pay_bank[$i];
                $data['other_expense_pay_value'] = (float)filter_var($other_expense_pay_value[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['other_expense_pay_balance'] = (float)filter_var($other_expense_pay_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['other_expense_pay_total'] = (float)filter_var($other_expense_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['updateby'] = $user[0][0];
                if($other_expense_pay_id[$i] == 0){
                    $other_expense_pay_model->insertOtherExpensePay($data);
                }else{
                    $other_expense_pay_model->updateOtherExpensePayById($data,$other_expense_pay_id[$i]);
                }
            }
        }else{
            $data = [];
            $data['other_expense_id'] = $other_expense_id;
            $data['other_expense_pay_by'] = $other_expense_pay_by;
            $data['other_expense_pay_date'] = $other_expense_pay_date;
            $data['other_expense_pay_bank'] = $other_expense_pay_bank;
            $data['other_expense_pay_value'] = (float)filter_var($other_expense_pay_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['other_expense_pay_balance'] = (float)filter_var($other_expense_pay_balance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['other_expense_pay_total'] = (float)filter_var($other_expense_pay_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['updateby'] = $user[0][0];

            if($other_expense_pay_id == 0){
                $other_expense_pay_model->insertOtherExpensePay($data);
            }else{
                $other_expense_pay_model->updateOtherExpensePayById($data,$other_expense_pay_id);
            }
        }


        if($other_expense_id > 0){
?>
        <script>window.location="index.php?app=other_expense&action=update&id=<?php echo $other_expense_id;?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['other_expense_code'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['other_expense_code'] = $_POST['other_expense_code'];
        $data['other_expense_date'] = $_POST['other_expense_date'];
        $data['other_expense_vat_type'] = $_POST['other_expense_vat_type'];
        $data['other_expense_bill_code'] = $_POST['other_expense_bill_code'];
        $data['other_expense_bill_date'] = $_POST['other_expense_bill_date'];
        $data['other_expense_remark'] = $_POST['other_expense_remark'];
        $data['other_expense_total'] = (float)filter_var($_POST['other_expense_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_vat'] = (float)filter_var($_POST['other_expense_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_vat_value'] = (float)filter_var($_POST['other_expense_vat_value'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_net'] = (float)filter_var($_POST['other_expense_net'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_interest'] = (float)filter_var($_POST['other_expense_interest'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_cash'] = (float)filter_var($_POST['other_expense_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_other_pay'] = (float)filter_var($_POST['other_expense_other_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_vat_pay'] = (float)filter_var($_POST['other_expense_vat_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_discount_cash'] = (float)filter_var($_POST['other_expense_discount_cash'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['other_expense_pay'] = (float)filter_var($_POST['other_expense_pay'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['updateby'] = $user[0][0];

        $output = $other_expense_model->updateOtherExpenseByID($other_expense_id,$data);

        $other_expense_list_id = $_POST['other_expense_list_id'];
        $other_expense_list_code = $_POST['other_expense_list_code'];
        $other_expense_list_name = $_POST['other_expense_list_name']; 
        $other_expense_list_total = $_POST['other_expense_list_total'];

        $other_expense_list_model->deleteOtherExpenseListByOtherExpenseListIDNotIN($other_expense_id,$other_expense_list_id);

        if(is_array($other_expense_list_id)){
            for($i=0; $i < count($other_expense_list_id) ; $i++){
                $data = [];
                $data['other_expense_id'] = $other_expense_id;
                $data['other_expense_list_code'] = $other_expense_list_code[$i];
                $data['other_expense_list_name'] = $other_expense_list_name[$i];
                $data['other_expense_list_total'] = (float)filter_var($other_expense_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['updateby'] = $user[0][0];
                if($other_expense_list_id[$i] == 0){
                    $other_expense_list_model->insertOtherExpenseList($data);
                }else{
                    $other_expense_list_model->updateOtherExpenseListById($data,$other_expense_list_id[$i]);
                }
            }
        }else{
            $data = [];
            $data['other_expense_id'] = $other_expense_id;
            $data['other_expense_list_code'] = $other_expense_list_code;
            $data['other_expense_list_name'] = $other_expense_list_name; 
            $data['other_expense_list_total'] = (float)filter_var($other_expense_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['updateby'] = $user[0][0];

            if($other_expense_list_id == 0){
                $other_expense_list_model->insertOtherExpenseList($data);
            }else{
                $other_expense_list_model->updateOtherExpenseListById($data,$other_expense_list_id);
            }
        }


        $other_expense_pay_id = $_POST['other_expense_pay_id'];
        $other_expense_pay_by = $_POST['other_expense_pay_by'];
        $other_expense_pay_date = $_POST['other_expense_pay_date']; 
        $other_expense_pay_bank = $_POST['other_expense_pay_bank'];
        $other_expense_pay_value = $_POST['other_expense_pay_value'];
        $other_expense_pay_balance = $_POST['other_expense_pay_balance'];
        $other_expense_pay_total = $_POST['other_expense_pay_total'];

        $other_expense_pay_model->deleteOtherExpensePayByOtherExpensePayIDNotIN($other_expense_id,$other_expense_pay_id);

        if(is_array($other_expense_pay_id)){
            for($i=0; $i < count($other_expense_pay_id) ; $i++){
                $data = [];
                $data['other_expense_id'] = $other_expense_id;
                $data['other_expense_pay_by'] = $other_expense_pay_by[$i];
                $data['other_expense_pay_date'] = $other_expense_pay_date[$i];
                $data['other_expense_pay_bank'] = $other_expense_pay_bank[$i];
                $data['other_expense_pay_value'] = (float)filter_var($other_expense_pay_value[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['other_expense_pay_balance'] = (float)filter_var($other_expense_pay_balance[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['other_expense_pay_total'] = (float)filter_var($other_expense_pay_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['updateby'] = $user[0][0];
                if($other_expense_pay_id[$i] == 0){
                    $other_expense_pay_model->insertOtherExpensePay($data);
                }else{
                    $other_expense_pay_model->updateOtherExpensePayById($data,$other_expense_pay_id[$i]);
                }
            }
        }else{
            $data = [];
            $data['other_expense_id'] = $other_expense_id;
            $data['other_expense_pay_by'] = $other_expense_pay_by;
            $data['other_expense_pay_date'] = $other_expense_pay_date;
            $data['other_expense_pay_bank'] = $other_expense_pay_bank;
            $data['other_expense_pay_value'] = (float)filter_var($other_expense_pay_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['other_expense_pay_balance'] = (float)filter_var($other_expense_pay_balance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['other_expense_pay_total'] = (float)filter_var($other_expense_pay_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['updateby'] = $user[0][0];

            if($other_expense_pay_id == 0){
                $other_expense_pay_model->insertOtherExpensePay($data);
            }else{
                $other_expense_pay_model->updateOtherExpensePayById($data,$other_expense_pay_id);
            }
        }
        
        
        if($output){
?>
        <script>window.location="index.php?app=other_expense&action=update&id=<?PHP echo $other_expense_id?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
   
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
    $other_expenses = $other_expense_model->getOtherExpenseBy($date_start,$date_end,$supplier_id,$keyword,$purchase_order_id);
    require_once($path.'view.inc.php');

}





?>