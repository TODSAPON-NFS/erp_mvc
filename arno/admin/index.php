<?php
session_start();

require_once('../models/NotificationModel.php');
$user = $_SESSION['user'];
//echo "<pre>";
//print_r($user);
//echo "</pre>";
$model_notification = new NotificationModel;
$notifications = $model_notification->getNotificationBy($user[0][0]);
$notifications_new = $model_notification->getNotificationBy($user[0][0],"1");

$notifications_pr = $model_notification->getNotificationByType($user[0][0],'Purchase Request',"1");
$notifications_po = $model_notification->getNotificationByType($user[0][0],'Purchase Order',"1");
$notifications_cpo = $model_notification->getNotificationByType($user[0][0],'Customer Order',"1");
$notifications_ns = $model_notification->getNotificationByType($user[0][0],'Supplier Approve',"1");

if(!isset($_SESSION['user'])){
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
