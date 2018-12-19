<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('asia/bangkok');

require_once('../../models/PaperLockModel.php');
$paper_lock_model = new PaperLockModel;
$result['result'] = $paper_lock_model->checkPaperLockByDate($_POST['date'],"1","1");

if($result['result']){
    $result['date_now'] = date("d")."-".date("m")."-".date("Y");
}else{

}
echo json_encode($result);

?>