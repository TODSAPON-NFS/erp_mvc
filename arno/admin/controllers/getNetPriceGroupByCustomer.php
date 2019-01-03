<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/DashboardModel.php');
$invoice_customer_model = new DashboardModel;

$page_start = $_POST['limit']*5;
$page_end =5 ;

$invoice_customer = $invoice_customer_model->getNetPriceGroupByCustomerLimit($page_start,$page_end);

echo json_encode($invoice_customer);
?>