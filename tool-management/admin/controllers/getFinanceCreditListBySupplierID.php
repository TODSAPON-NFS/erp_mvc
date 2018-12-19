<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/FinanceCreditModel.php');
$invoice_supplier_id = json_decode($_POST['invoice_supplier_id'],true);

$finance_credit_model = new FinanceCreditModel;
$finance_credit = $finance_credit_model->generateFinanceCreditListBySupplierId($_POST['supplier_id'],$invoice_supplier_id ,$_POST['search'] );
echo json_encode($finance_credit);

?>