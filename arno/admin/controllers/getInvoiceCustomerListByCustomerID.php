<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceCustomerModel.php');
$purchase_order_list_id = json_decode($_POST['purchase_order_list_id'],true);

$invoice_customer_model = new InvoiceCustomerModel;
$customer = $invoice_customer_model->generateInvoiceCustomerListByCustomerId($_POST['customer_id'],$purchase_order_list_id,$purchase_order_list_id ,$_POST['search'] );
echo json_encode($customer);

?>