<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/CreditNoteModel.php');
require_once('../models/CustomerModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_debtor_07/views/";
$customer_model = new CustomerModel;

$credit_note_model = new CreditNoteModel;


    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();

    $credit_notes = $credit_note_model->getCreditNoteBy($date_start,$date_end,$customer_id,$keyword);
    require_once($path.'view.inc.php');
?>