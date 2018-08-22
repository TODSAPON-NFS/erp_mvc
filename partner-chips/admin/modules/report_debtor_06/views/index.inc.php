<?php
session_start();

require_once('../models/BillingNoteModel.php');
require_once('../models/CustomerModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_debtor_06/views/";

$customer_model = new CustomerModel;

$billing_note_model = new BillingNoteModel;



$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];
$customer_id = $_GET['customer_id'];
$keyword = $_GET['keyword'];

$customers=$customer_model->getCustomerBy();

$billing_notes = $billing_note_model->getBillingNoteBy($date_start,$date_end,$customer_id,$keyword);
$customer_orders = $billing_note_model->getCustomerOrder();
require_once($path.'view.inc.php');


?>