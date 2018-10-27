<?php
session_start();
require_once('../models/MaintenanceModel.php');

$path = "modules/maintenance/views/";
$maintenance_model = new MaintenanceModel;

require_once($path.'view.inc.php');

