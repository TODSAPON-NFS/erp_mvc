<?php 

require_once('../../models/SupplierModel.php');
require_once('../../models/InvoiceSupplierModel.php');
date_default_timezone_set('asia/bangkok');

$supplier_model = new SupplierModel;
$invoice_supplier_model = new InvoiceSupplierModel;

$supplier=$supplier_model->getSupplierByID($_POST['supplier_id']);
if($supplier['supplier_domestic'] == "ภายในประเทศ"){
    $first_char = "RR";
}else{
    $first_char = "RF";
}

$first_code = $first_char.date("y").date("m");
$last_code = $invoice_supplier_model->getInvoiceSupplierLastID($first_code,3);

echo $last_code;
?>