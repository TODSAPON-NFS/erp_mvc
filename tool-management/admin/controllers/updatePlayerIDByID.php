<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/UserModel.php');
$user_model = new UserModel;
$product = $user_model->updatePlayerIDByID($_POST['user_id'],$_POST['user_player_id']);

echo json_encode($product);
?>