<?php
session_start();

require_once('../models/JournalReportModel.php'); 

date_default_timezone_set('asia/bangkok');

$path = "modules/report_account_06/views/";
 
$journal_report_model = new JournalReportModel;



$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];
$code_start = $_GET['code_start'];
$code_end = $_GET['code_end'];  
$keyword = $_GET['keyword'];

if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}


$journal_reports = $journal_report_model->getJournalAcountFullReportBy($date_start,$date_end,$code_start,$code_end,$keyword);
require_once($path.'view.inc.php');
  




?>