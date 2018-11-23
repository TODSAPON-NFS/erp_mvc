<?php
session_start();

require_once('../models/NotificationModel.php');
$user_admin = $_SESSION['user'];
$notification_id = $_GET['notification'];
/*
echo "<pre>";
print_r($_SESSION['user']);
echo "</pre>";

echo "[".$admin_id."]"; 
*/
$admin_id = $user_admin['user_id'];
$license_admin_page =  $user_admin['license_admin_page'];
$license_sale_employee_page =  $user_admin['license_sale_employee_page']; 
$license_request_page =  $user_admin['license_request_page'];
$license_delivery_note_page  =  $user_admin['license_delivery_note_page'];
$license_regrind_page =  $user_admin['license_regrind_page'];
$license_purchase_page =  $user_admin['license_purchase_page'];
$license_sale_page =  $user_admin['license_sale_page'];
$license_inventery_page =  $user_admin['license_inventery_page'];
$license_account_page =  $user_admin['license_account_page'];
$license_report_page =  $user_admin['license_report_page'];
$license_manager_page   =  $user_admin['license_manager_page'];


$model_notification = new NotificationModel;

if($notification_id != ""){
    $model_notification->setNotificationSeenByID($notification_id);
}

$notifications = $model_notification->getNotificationBy($admin_id);
$notifications_new = $model_notification->getNotificationBy($admin_id,"1");

$notifications_pr = $model_notification->getNotificationByType($admin_id,'Purchase Request',"1");
$notifications_po = $model_notification->getNotificationByType($admin_id,'Purchase Order',"1");
$notifications_cpo = $model_notification->getNotificationByType($admin_id,'Customer Order',"1");
$notifications_ns = $model_notification->getNotificationByType($admin_id,'Supplier Approve',"1");

if($admin_id == ""){
?>
<script>window.location="../index.php";</script>
<?PHP 
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
