<?php
session_start();
require_once('../models/NotificationModel.php');

$path = "modules/notification/views/";
$model_notification = new NotificationModel;
$user = $_SESSION['user'];
$type = $_GET['type'];
if($type==""){
    $notifications = $model_notification->getNotificationBy($user[0][0]);
}else{
    $notifications = $model_notification->getNotificationByType($user[0][0],$type);
}



require_once($path.'view.inc.php');

