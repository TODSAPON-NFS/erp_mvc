<<<<<<< HEAD
<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/DashboardModel.php');
$net_price_model = new DashboardModel;
$net_price = $net_price_model->getNetPriceGroupBySales($_POST['user_id']);
echo json_encode($net_price);
=======
<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/DashboardModel.php');
$net_price_model = new DashboardModel;
$net_price = $net_price_model->getNetPriceGroupBySales($_POST['user_id']);
echo json_encode($net_price);
>>>>>>> bfe174f8f8a6ccd61604b3210c62329d9f03ccee
?>