<?php
session_start();
require_once('../models/NotificationModel.php');

$path = "modules/notification/views/";
$model_notification = new NotificationModel;
$user = $_SESSION['user'];
$type = $_GET['type'];
$seen_type=$_GET['action'];
$type_color = "primary";


if($_GET['action-set']=='setseen'){
    $notification_id = $_POST['notification_id'];
    for($i=0;$i<count($notification_id);$i++){
        $model_notification->setNotificationSeenByID($notification_id[$i]);
    }
}
else if($_GET['action-set']=='setunseen'){
    $notification_id = $_POST['notification_id'];
    for($i=0;$i<count($notification_id);$i++){
        $model_notification->setNotificationUnSeenByID($notification_id[$i]);
    }
}
else if($_GET['action-set']=='setdelete'){
    $notification_id = $_POST['notification_id'];
    // print_r($notification_id);
    for($i=0;$i<count($notification_id);$i++){
        $model_notification->deleteNotificationByID($notification_id[$i]);
    }
}

if($type==""){
    $notifications = $model_notification->getNotificationBy($user['user_id']);
}else{
    if($type=="Purchase Request"){
        $type_color = "success";
    }
    else if($type=="Purchase Order"){
        $type_color = "warning";
    }
    else if($type=="Customer Order"){
        $type_color = "info";
    }
    else{
        $type_color = "primary";
    }
    $notifications = $model_notification->getNotificationByType($user['user_id'],$type);
}


if($_GET['action']=='unseen'){
    $notifications = $model_notification->getNotificationByUnseen($user['user_id'],$type);
}
else if($_GET['action']=='seen'){
    $notifications = $model_notification->getNotificationBySeen($user['user_id'],$type);
}
else if($_GET['action']=='all'){
    $notifications = $model_notification->getNotificationBy($user['user_id'],$type);
}


require_once($path.'view.inc.php');

