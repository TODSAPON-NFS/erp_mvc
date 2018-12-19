<?php
session_start();
require_once('../models/NotificationModel.php');

$path = "modules/notification/views/";
$model_notification = new NotificationModel;
$user = $_SESSION['user'];
$type = $_GET['type'];
$seen_type=$_GET['action'];
if($type==""){
    $notifications = $model_notification->getNotificationBy($user['user_id']);
}else{
    $notifications = $model_notification->getNotificationByType($user['user_id'],$type);
}


if($_GET['action']=='unseen'){
    $notifications = $model_notification->getNotificationByUnseen($user['user_id'],$type);
}
if($_GET['action']=='seen'){
    $notifications = $model_notification->getNotificationBySeen($user['user_id'],$type);
}
if($_GET['action']=='all'){
    $notifications = $model_notification->getNotificationBy($user['user_id'],$type);
}


require_once($path.'view.inc.php');

