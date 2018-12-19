<?php 
require_once('../../models/CustomerPurchaseOrderModel.php');
require_once('../../models/CustomerModel.php');
require_once('../../functions/CodeGenerateFunction.func.php');
require_once('../../models/PaperModel.php');
require_once('../../models/UserModel.php');

$user_model = new UserModel;
$customer_model = new CustomerModel;
$customer_purchase_order_model = new CustomerPurchaseOrderModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('15');

$user=$user_model->getUserByID($_POST['employee_id']);
$customer=$customer_model->getCustomerByID($_POST['customer_id']); 

$data = [];
$data['year'] = date("Y");
$data['month'] = date("m");
$data['number'] = "0000000000";
$data['employee_name'] = $user["user_name"];
$data['customer_code'] = $customer['customer_code'];
$data['customer_name'] = $customer['customer_name_en'];

$code = $code_generate->cut2Array($paper['paper_code'],$data);
$last_code = "";
for($i = 0 ; $i < count($code); $i++){

    if($code[$i]['type'] == "number"){
        $last_code = $customer_purchase_order_model->getCustomerPurchaseOrderLastID($last_code,$code[$i]['length']);
    }else{
        $last_code .= $code[$i]['value'];
    }   
} 
echo $last_code;
?>