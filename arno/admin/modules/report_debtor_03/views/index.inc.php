<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/InvoiceCustomerModel.php');
require_once('../models/CustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/report_debtor_03/views/";
$customer_model = new CustomerModel;
$invoice_customer_model = new InvoiceCustomerModel;

 
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();
    $invoice_customers = $invoice_customer_model->getInvoiceCustomerBy($date_start,$date_end,$customer_id,$keyword);
    require_once($path.'view.inc.php');

?>