<?php
session_start();
require_once('../models/QuotationModel.php');
require_once('../models/UserModel.php');
require_once('../models/CustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/report_debtor_15/views/";
$customer_model = new CustomerModel;
$user_model = new UserModel;
$quotation_model = new QuotationModel;


$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];
$customer_id = $_GET['customer_id'];
$user_id = $_GET['user_id'];
$keyword = $_GET['keyword'];

$customers=$customer_model->getCustomerBy();
$users=$user_model->getUserBy();
$quotations = $quotation_model->getQuotationBy($date_start,$date_end,$customer_id,$keyword,$user_id);
require_once($path.'view.inc.php');


?>