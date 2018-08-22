<?php
session_start();
require_once('../models/NotificationModel.php');

$path = "modules/dashboard/views/";
$model_notification = new NotificationModel;
$user = $_SESSION['user'];

//echo "<pre>";
//print_r($_SESSION['user']);
//echo "</pre>";


require_once($path.'view.inc.php');

