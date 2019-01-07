<<<<<<< HEAD
<?php
session_start();
require_once('../models/MaintenanceStockModel.php');
require_once('../models/MaintenancePurchaseModel.php');
require_once('../models/MaintenanceSaleModel.php');
require_once('../models/MaintenanceFinanceModel.php');

$path = "modules/maintenance_stock/views/";
$maintenance_model = new MaintenanceStockModel;
$maintenance_purchase_model = new MaintenancePurchaseModel;
$maintenance_sale_model = new MaintenanceSaleModel;
$maintenance_finance_model = new MaintenanceFinanceModel;

//$maintenance_model->runMaintenance();
//$maintenance_purchase_model->runMaintenance();
//$maintenance_sale_model->runMaintenance();
//$maintenance_finance_model->runMaintenance();

require_once($path.'view.inc.php');

=======
<?php
session_start();
require_once('../models/MaintenanceStockModel.php');
require_once('../models/MaintenancePurchaseModel.php');
require_once('../models/MaintenanceSaleModel.php');
require_once('../models/MaintenanceFinanceModel.php');

$path = "modules/maintenance_stock/views/";
$maintenance_model = new MaintenanceStockModel;
$maintenance_purchase_model = new MaintenancePurchaseModel;
$maintenance_sale_model = new MaintenanceSaleModel;
$maintenance_finance_model = new MaintenanceFinanceModel;

//$maintenance_model->runMaintenance();
//$maintenance_purchase_model->runMaintenance();
//$maintenance_sale_model->runMaintenance();
//$maintenance_finance_model->runMaintenance();

require_once($path.'view.inc.php');

>>>>>>> bfe174f8f8a6ccd61604b3210c62329d9f03ccee
