<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/PurchaseOrderModel.php');
$purchase_request_list_id = json_decode($_POST['purchase_request_list_id'],true);
$customer_purchase_order_list_detail_id = json_decode($_POST['customer_purchase_order_list_detail_id'],true);
$delivery_note_supplier_list_id = json_decode($_POST['delivery_note_supplier_list_id'],true);

$purchase_order_model = new PurchaseOrderModel;
$supplier = $purchase_order_model->generatePurchaseOrderListBySupplierId($_POST['supplier_id'],$purchase_request_list_id , $customer_purchase_order_list_detail_id, $delivery_note_supplier_list_id ,$_POST['search']);
echo json_encode($supplier);

?>