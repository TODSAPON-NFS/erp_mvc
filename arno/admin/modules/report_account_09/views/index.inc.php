<?php
session_start();

require_once('../models/JournalReportModel.php'); 

date_default_timezone_set('asia/bangkok');

$path = "modules/report_account_09/views/";
 
$journal_report_model = new JournalReportModel;



if(!isset($_GET['date_start'])){
    $date_start = $_SESSION['date_start'];
}else{
    $date_start = $_GET['date_start'];
    $_SESSION['date_start'] = $date_start;
}


if(!isset($_GET['date_end'])){
    $date_end = $_SESSION['date_end'];
}else{
    $date_end = $_GET['date_end'];
    $_SESSION['date_end'] = $date_end;
}


if($date_start == ""){
    $date_start = date('01-11-2018'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}


$journal_reports = $journal_report_model->getJournalSalesReportShowAllBy($date_start,$date_end);
require_once($path.'view.inc.php');
  




?>