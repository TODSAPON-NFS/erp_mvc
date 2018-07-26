<?php
session_start();

require_once('../models/NotificationModel.php');
$user_admin = $_SESSION['user'];
$notification_id = $_GET['notification'];

$admin_id = $user_admin[0][0];
$license_admin_page =  $user_admin[0][26];
$license_sale_employee_page =  $user_admin[0][27]; 
$license_request_page =  $user_admin[0][28];
$license_delivery_note_page  =  $user_admin[0][29];
$license_regrind_page =  $user_admin[0][30];
$license_purchase_page =  $user_admin[0][31];
$license_sale_page =  $user_admin[0][32];
$license_inventery_page =  $user_admin[0][33];
$license_account_page =  $user_admin[0][34];
$license_report_page =  $user_admin[0][35];
$license_manager_page   =  $user_admin[0][36];


$model_notification = new NotificationModel;

if($notification_id != ""){
    $model_notification->setNotificationSeenByID($notification_id);
}

$notifications = $model_notification->getNotificationBy($user_admin[0][0]);
$notifications_new = $model_notification->getNotificationBy($user_admin[0][0],"1");

$notifications_pr = $model_notification->getNotificationByType($user_admin[0][0],'Purchase Request',"1");
$notifications_po = $model_notification->getNotificationByType($user_admin[0][0],'Purchase Order',"1");
$notifications_cpo = $model_notification->getNotificationByType($user_admin[0][0],'Customer Order',"1");
$notifications_ns = $model_notification->getNotificationByType($user_admin[0][0],'Supplier Approve',"1");

if($user_admin[0][0] === ""){
header('Location ../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php require_once('views/header.inc.php') ?>

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <?php require_once("views/menu.inc.php"); ?>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
           <?php require_once("views/body.inc.php"); ?>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <?php require_once('views/footer.inc.php'); ?>

</body>

</html>
