<?php
session_start();

require_once('../models/JournalReportModel.php'); 
require_once('../models/AccountModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/report_account_08/views/";
 
$journal_report_model = new JournalReportModel;
$account_model = new AccountModel;





if(!isset($_GET['date_end'])){
    $date_end = $_SESSION['date_end'];
}else{
    $date_end = $_GET['date_end'];
    $_SESSION['date_end'] = $date_end;
}


if(!isset($_GET['account_id'])){
    $account_id = $_SESSION['account_id'];
}else{
    
    $account_id= $_GET['account_id']; 
    $_SESSION['account_id'] = $account_id;
}


$account = $account_model->getAccountAll();

$journal_reports = $journal_report_model->getJournalAcountReportShowRceiptsAllBy($date_end,$account_id);


require_once($path.'view.inc.php');





?>