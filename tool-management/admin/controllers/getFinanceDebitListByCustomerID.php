<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/FinanceDebitModel.php');
$billing_note_list_id = json_decode($_POST['billing_note_list_id'],true);

$finance_debit_model = new FinanceDebitModel;
$finance_debit = $finance_debit_model->generateFinanceDebitListByCustomerId($_POST['customer_id'],$billing_note_list_id ,$_POST['search'] );
echo json_encode($finance_debit);

?>