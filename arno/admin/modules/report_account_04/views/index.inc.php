<?php
session_start();

require_once('../models/JournalReportModel.php'); 

date_default_timezone_set('asia/bangkok');

$path = "modules/report_account_04/views/";
 
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

if(!isset($_GET['keyword'])){
    $keyword = $_SESSION['keyword'];
}else{
    
    $keyword = $_GET['keyword']; 
    $_SESSION['keyword'] = $keyword;
}


if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}
 
$type = $_GET['type'];
$keyword = $_GET['keyword'];

if($type != "full"){
    $journal_reports = $journal_report_model->getJournalReportBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');
}else{
    $journal_reports = $journal_report_model->getJournalFullReportBy($date_start,$date_end,$keyword);
    require_once($path.'view-full.inc.php');
    
}





?>