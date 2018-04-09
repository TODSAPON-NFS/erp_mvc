<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/BillingNoteModel.php');
$invoice_customer_id = json_decode($_POST['invoice_customer_id'],true);

$billing_note_model = new BillingNoteModel;
$billing_note = $billing_note_model->generateBillingNoteListByCustomerId($_POST['customer_id'],$invoice_customer_id ,$_POST['search'] );
echo json_encode($billing_note);

?>