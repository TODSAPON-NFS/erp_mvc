<?php 

require_once('../../models/PurchaseOrderModel.php');
require_once('../../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$supplier_model = new SupplierModel;
$purchase_order_model = new PurchaseOrderModel;

$supplier=$supplier_model->getSupplierByID($_POST['supplier_id']);
if($supplier['supplier_domestic'] == "ภายในประเทศ"){
    $first_char = "LP";
}else{
    $first_char = "PO";
}

$first_code = $first_char.date("y").date("m");
$last_code = $purchase_order_model->getPurchaseOrderLastID($first_code,3);

echo $last_code;
?>