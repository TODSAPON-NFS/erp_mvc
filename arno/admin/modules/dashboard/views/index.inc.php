<?php
session_start();
require_once('../models/NotificationModel.php');
require_once('../models/DashboardModel.php');

$path = "modules/dashboard/views/";
$model_notification = new NotificationModel;
$dash_board_model = new DashboardModel;
$user = $_SESSION['user'];

//echo "<pre>";
//print_r($_SESSION['user']);
//echo "</pre>";
if($user['user_position_id']=='1' || $user['user_position_id']=='2'|| $user['user_position_id']=='4'){
    $customer = $dash_board_model->getNetPriceGroupByCustomer();
    
    require_once($path.'view.inc.php');
}
else{
    $customer = $dash_board_model->getNetPriceBySales($user['user_id']);
     require_once($path.'sales.inc.php');
}


