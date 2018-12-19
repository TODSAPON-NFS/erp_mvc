<?php
session_start();
require_once('../models/PaperLockModel.php'); 

$path = "modules/paper_lock/views/";
$paper_lock_model = new PaperLockModel; 
 
date_default_timezone_set('asia/bangkok');


if($_GET['action'] == "generate"){
    if(isset($_POST['date_start'])){
        $date_start = $_POST['date_start'];
    }else{
        $date_start = "01-01-2018";
    }
    $paper_lock_model->generatePaperLock($date_start);
?>
   <script> window.location="index.php?app=paper_lock";</script>
<?PHP 
} else if($_GET['action'] == "update"){
    $paper_lock_1 = $_POST["paper_lock_1"];
    $paper_lock_2 = $_POST["paper_lock_2"];
    $paper_lock_model->clearPaperLock1();
    $paper_lock_model->clearPaperLock2();
    for($i=0; $i < count($paper_lock_1);$i++){
        $paper_lock_model->setPaperLock1($paper_lock_1[$i]);
    }
    for($i=0; $i < count($paper_lock_2);$i++){
        $paper_lock_model->setPaperLock2($paper_lock_2[$i]);
    } 
?>
   <script> window.location="index.php?app=paper_lock";</script>
<?PHP 
}

$paper_locks = $paper_lock_model->getPaperLock();

if(count($paper_locks) > 0){
    $date_start = $paper_locks [0]['paper_lock_date'];
}else{
    $date_start = date("t-m-Y");
}

require_once($path.'view.inc.php');

