<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/BillingNoteModel.php');
$billing_note_list_id = json_decode($_POST['billing_note_list_id'],true);

$billing_note_model = new BillingNoteModel;
$billing_note = $billing_note_model->generateBillingNoteListByCustomerId($_POST['customer_id'],$billing_note_list_id ,$_POST['search'] );
echo json_encode($billing_note);

?>