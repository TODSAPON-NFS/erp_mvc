<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/PurchaseOrderModel.php');
require_once('../models/PurchaseOrderListModel.php');
require_once('../models/CustomerPurchaseOrderListDetailModel.php');
require_once('../models/PurchaseRequestListModel.php');
require_once('../models/RegrindSupplierReceiveListModel.php');
require_once('../models/RequestStandardListModel.php');
require_once('../models/RequestSpecialListModel.php');
require_once('../models/RequestRegrindListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/ProductSupplierModel.php');
require_once('../models/SupplierModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/purchase_order/views/";
$user_model = new UserModel;
$product_model = new ProductModel;
$supplier_model = new SupplierModel;
$notification_model = new NotificationModel;
$purchase_order_model = new PurchaseOrderModel;
$purchase_order_list_model = new PurchaseOrderListModel;
$purchase_request_list_model = new PurchaseRequestListModel;
$regrind_supplier_receive_list_model = new RegrindSupplierReceiveListModel;
$request_standard_list_model = new RequestStandardListModel;
$request_special_list_model = new RequestSpecialListModel;
$request_regrind_list_model = new RequestRegrindListModel;
$customer_purchase_order_list_detail_model = new CustomerPurchaseOrderListDetailModel;
$product_supplier_model = new ProductSupplierModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('10');



$purchase_order_id = $_GET['id'];
$purchase_order_list_id = $_GET['purchase_order_list_id']; 
$supplier_id = $_GET['supplier_id'];
$purchase_request_id = $_GET['purchase_request_id'];
$type = strtoupper($_GET['type']);


if(!isset($_GET['action'])){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();

    $purchase_orders = $purchase_order_model->getPurchaseOrderBy($date_start,$date_end,$supplier_id,$keyword);
    $supplier_orders = $purchase_order_model->getSupplierOrder();
    $supplier_tests = $purchase_order_model->getSupplierTestOrder();
    $supplier_blankeds = $purchase_order_model->getSupplierBlankedOrder();
    $supplier_regrinds = $purchase_order_model->getSupplierRegrind();

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;
    $list_size = count($purchase_orders);
    $page_max = (int)($list_size/$page_size);
    if($list_size%$page_size > 0){
        $page_max += 1;
    }


    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    
    if($type != "STANDARD" && $type != "TEST" && $type != "BLANKED" && $type != "REGRIND"){
        $type = "STANDARD";
    } 

    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy(); 
    if($purchase_request_id > 0){
        $type = "BLANKED";
    }


    if($supplier_id > 0){
        $supplier=$supplier_model->getSupplierByID($supplier_id);
        if($supplier['vat_type'] == '0'){
            $vat= '0';
        }else{
            $vat = $invoice_supplier['vat'];
        }
        //$products=$product_supplier_model->getProductBySupplierID(/*$supplier_id*/);
        $products=$product_model->getProductBy('','','','Active');

        if($supplier['supplier_domestic'] == "ภายในประเทศ"){
            $paper = $paper_model->getPaperByID('11');
        }else{
            $paper = $paper_model->getPaperByID('10');
        }

        $user=$user_model->getUserByID($admin_id);
        
        $data = [];
        $data['year'] = date("Y");
        $data['month'] = date("m");
        $data['number'] = "0000000000";
        $data['employee_name'] = $user["user_name_en"];

        $code = $code_generate->cut2Array($paper['paper_code'],$data);
        $last_code = "";
        for($i = 0 ; $i < count($code); $i++){
        
            if($code[$i]['type'] == "number"){
                $last_code = $purchase_order_model->getPurchaseOrderLastID($last_code,$code[$i]['length']);
            }else{
                $last_code .= $code[$i]['value'];
            }   
        } 

        $purchase_order_lists = $purchase_order_model->generatePurchaseOrderListBySupplierId($supplier_id,$purchase_request_id,$type);
        
    }
    $first_date = date("d")."-".date("m")."-".date("Y");



    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $purchase_order = $purchase_order_model->getPurchaseOrderByID($purchase_order_id);
    $type=$purchase_order["purchase_order_type"];
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);
    $supplier=$supplier_model->getSupplierByID($purchase_order['supplier_id']);
    if($supplier['vat_type'] == '0'){
        $vat= '0';
    }else{
        $vat = $invoice_supplier['vat'];
    }
    //$products=$product_supplier_model->getProductBySupplierID($purchase_order['supplier_id']);
    $products=$product_model->getProductBy('','','','Active');
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){ 
    $purchase_order = $purchase_order_model->getPurchaseOrderViewByID($purchase_order_id);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);
    if($supplier['vat_type'] == '0'){
        $vat= '0';
    }else{
        $vat = $invoice_supplier['vat'];
    }
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete' && ( $license_purchase_page == "High" )){

    $notification_model->deleteNotificationByTypeID('Purchase Order',$purchase_order_id);
    $purchase_order_list_model->deletePurchaseOrderListByPurchaseOrderID($purchase_order_id);
    $purchase_orders = $purchase_order_model->deletePurchaseOrderById($purchase_order_id);
?>
    <script>window.location="index.php?app=purchase_order"</script>
<?php

}else if ($_GET['action'] == 'cancelled' && ($license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    $purchase_order_model->cancelPurchaseOrderById($purchase_order_id);
?>
    <script>window.location="index.php?app=purchase_order"</script>
<?php

}else if ($_GET['action'] == 'uncancelled' && ($license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    $purchase_order_model->uncancelPurchaseOrderById($purchase_order_id);
?>
    <script>window.location="index.php?app=purchase_order"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    if(isset($_POST['purchase_order_code'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['purchase_order_code'] = $_POST['purchase_order_code'];
        $data['purchase_order_date'] = $_POST['purchase_order_date'];
        $data['purchase_order_credit_term'] = $_POST['purchase_order_credit_term'];
        $data['purchase_order_accept_status'] = '';
        $data['purchase_order_type'] = $type;
        $data['purchase_order_status'] = '';
        $data['purchase_order_delivery_by'] = $_POST['purchase_order_delivery_by'];
        $data['purchase_order_total'] = (float)filter_var($purchase_order_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_vat'] = (float)filter_var($purchase_order_vat, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_net'] = (float)filter_var($purchase_order_net, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['employee_id'] = $_POST['employee_id'];

        $output = $purchase_order_model->insertPurchaseOrder($data);

        if($output > 0){
            $data = [];
            $product_id = $_POST['product_id'];

            $purchase_request_list_id = $_POST['purchase_request_list_id'];
            $customer_purchase_order_list_detail_id = $_POST['customer_purchase_order_list_detail_id'];
            $delivery_note_supplier_list_id = $_POST['delivery_note_supplier_list_id'];
            $regrind_supplier_receive_list_id = $_POST['regrind_supplier_receive_list_id'];
            $request_standard_list_id = $_POST['request_standard_list_id'];
            $request_special_list_id = $_POST['request_special_list_id'];
            $request_regrind_list_id = $_POST['request_regrind_list_id'];

            $purchase_order_list_qty = $_POST['purchase_order_list_qty'];
            $purchase_order_list_price = $_POST['purchase_order_list_price'];
            $purchase_order_list_price_sum = $_POST['purchase_order_list_price_sum'];
            $purchase_order_list_delivery_min = $_POST['purchase_order_list_delivery_min'];
            $purchase_order_list_delivery_max = $_POST['purchase_order_list_delivery_max'];
            $purchase_order_list_remark = $_POST['purchase_order_list_remark'];

           
            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data_sub = [];
                    $data_sub['purchase_order_id'] = $output;
                    $data_sub['product_id'] = $product_id[$i];
                    
                    $data_sub['purchase_order_list_qty'] = (float)filter_var($purchase_order_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['purchase_order_list_price'] = (float)filter_var($purchase_order_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['purchase_order_list_price_sum'] = (float)filter_var($purchase_order_list_price_sum[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['purchase_order_list_delivery_min'] = $purchase_order_list_delivery_min[$i];
                    $data_sub['purchase_order_list_delivery_max'] = $purchase_order_list_delivery_max[$i];
                    $data_sub['purchase_order_list_remark'] = $purchase_order_list_remark[$i];
        
                    $id = $purchase_order_list_model->insertPurchaseOrderList($data_sub);
                    if($id > 0){
                        if($purchase_request_list_id[$i] > 0){
                            $purchase_request_list_model->updatePurchaseOrderId($purchase_request_list_id[$i],$id);
                        }else if ($customer_purchase_order_list_detail_id[$i] > 0 ){
                            $customer_purchase_order_list_detail_model->updatePurchaseOrderId($customer_purchase_order_list_detail_id[$i],$id);
                        }else if ($delivery_note_supplier_list_id[$i] > 0 ){
                            $delivery_note_supplier_list_model->updatePurchaseOrderId($delivery_note_supplier_list_id[$i],$id);
                        }else if ($regrind_supplier_receive_list_id[$i] > 0 ){
                            $regrind_supplier_receive_list_model->updatePurchaseOrderId($regrind_supplier_receive_list_id[$i],$id);
                        }else if ($request_standard_list_id[$i] > 0 ){
                            $request_standard_list_model->updatePurchaseOrderListId($request_standard_list_id[$i],$id);
                        }else if ($request_special_list_id[$i] > 0 ){
                            $request_special_list_model->updatePurchaseOrderListId($request_special_list_id[$i],$id);
                        }else if ($request_regrind_list_id[$i] > 0 ){
                            $request_regrind_list_model->updatePurchaseOrderListId($request_regrind_list_id[$i],$id);
                        }
                    }
                }
                $data['purchase_order_status'] = 'New';
            }else if($product_id != ""){
                $data_sub = [];
                $data_sub['purchase_order_id'] = $output;
                $data_sub['product_id'] = $product_id;
                $data_sub['purchase_order_list_qty'] = (float)filter_var($purchase_order_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['purchase_order_list_price'] = (float)filter_var($purchase_order_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['purchase_order_list_price_sum'] = (float)filter_var($purchase_order_list_price_sum, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['purchase_order_list_delivery_min'] = $purchase_order_list_delivery_min;
                $data_sub['purchase_order_list_delivery_max'] = $purchase_order_list_delivery_max;
                $data_sub['purchase_order_list_remark'] = $purchase_order_list_remark;

                $id = $purchase_order_list_model->insertPurchaseOrderList($data_sub);
                if($id > 0){
                    if($purchase_request_list_id > 0){
                        $purchase_request_list_model->updatePurchaseOrderId($purchase_request_list_id,$id);
                    }else if ($customer_purchase_order_list_detail_id > 0 ){
                        $customer_purchase_order_list_detail_model->updatePurchaseOrderId($customer_purchase_order_list_detail_id,$id);
                    }else if ($delivery_note_supplier_list_id > 0 ){
                        $delivery_note_supplier_list_model->updatePurchaseOrderId($delivery_note_supplier_list_id,$id);
                    }else if ($regrind_supplier_receive_list_id > 0 ){
                        $regrind_supplier_receive_list_model->updatePurchaseOrderId($regrind_supplier_receive_list_id,$id);
                    }else if ($request_standard_list_id > 0 ){
                        $request_standard_list_model->updatePurchaseOrderListId($request_standard_list_id,$id);
                    }else if ($request_special_list_id > 0 ){
                        $request_special_list_model->updatePurchaseOrderListId($request_special_list_id,$id);
                    }else if ($request_regrind_list_id > 0 ){
                        $request_regrind_list_model->updatePurchaseOrderListId($request_regrind_list_id,$id);
                    }
                }
                $data['purchase_order_status'] = 'New';
            }else{
                $data['purchase_order_status'] = '';
            }

            
            $data['supplier_id'] = $_POST['supplier_id'];
            $data['purchase_order_code'] = $_POST['purchase_order_code'];
            $data['purchase_order_date'] = $_POST['purchase_order_date'];
            $data['purchase_order_credit_term'] = $_POST['purchase_order_credit_term'];
            $data['purchase_order_accept_status'] = '';
            $data['purchase_order_delivery_by'] = $_POST['purchase_order_delivery_by'];
            $data['purchase_order_total'] = (float)filter_var($purchase_order_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['purchase_order_vat'] = (float)filter_var($purchase_order_vat, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['purchase_order_net'] = (float)filter_var($purchase_order_net, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['employee_id'] = $_POST['employee_id'];

            $purchase_order_model->updatePurchaseOrderByID($output,$data);
?>
        <script>window.location="index.php?app=purchase_order&action=update&id=<?php echo $output;?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit' && ($license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    
    if(isset($_POST['purchase_order_code'])){

        $data = [];
        
     
        $product_id = $_POST['product_id'];
        
        $purchase_request_list_id = $_POST['purchase_request_list_id'];
        $customer_purchase_order_list_detail_id = $_POST['customer_purchase_order_list_detail_id'];
        $delivery_note_supplier_list_id = $_POST['delivery_note_supplier_list_id'];
        $regrind_supplier_receive_list_id = $_POST['regrind_supplier_receive_list_id'];
        $request_standard_list_id = $_POST['request_standard_list_id'];
        $request_special_list_id = $_POST['request_special_list_id'];
        $request_regrind_list_id = $_POST['request_regrind_list_id'];

        $purchase_order_list_id = $_POST['purchase_order_list_id'];
        $purchase_order_list_qty = $_POST['purchase_order_list_qty'];
        $purchase_order_list_price = $_POST['purchase_order_list_price'];
        $purchase_order_list_price_sum = $_POST['purchase_order_list_price_sum'];
        $purchase_order_list_delivery_min = $_POST['purchase_order_list_delivery_min'];
        $purchase_order_list_delivery_max = $_POST['purchase_order_list_delivery_max'];
        $purchase_order_list_remark = $_POST['purchase_order_list_remark'];

        $purchase_order_list_model->deletePurchaseOrderListByPurchaseOrderIDNotIN($purchase_order_id,$purchase_order_list_id);
        
        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data_sub = [];
                $data_sub['purchase_order_id'] = $purchase_order_id;
                $data_sub['product_id'] = $product_id[$i];
                
                $data_sub['purchase_order_list_qty'] = (float)filter_var($purchase_order_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['purchase_order_list_price'] = (float)filter_var($purchase_order_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['purchase_order_list_price_sum'] = (float)filter_var($purchase_order_list_price_sum[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['purchase_order_list_delivery_min'] = $purchase_order_list_delivery_min[$i];
                $data_sub['purchase_order_list_delivery_max'] = $purchase_order_list_delivery_max[$i];
                $data_sub['purchase_order_list_remark'] = $purchase_order_list_remark[$i];
    
                if($purchase_order_list_id[$i] != '0' ){
                    $purchase_order_list_model->updatePurchaseOrderListByIdAdmin($data_sub,$purchase_order_list_id[$i]);
                }else{
                    $id = $purchase_order_list_model->insertPurchaseOrderList($data_sub);
                    if($id > 0){
                        if($purchase_request_list_id[$i] > 0){
                            $purchase_request_list_model->updatePurchaseOrderId($purchase_request_list_id[$i],$id);
                        }else if ($customer_purchase_order_list_detail_id[$i] > 0 ){
                            $customer_purchase_order_list_detail_model->updatePurchaseOrderId($customer_purchase_order_list_detail_id[$i],$id);
                        }else if ($delivery_note_supplier_list_id[$i] > 0 ){
                            $delivery_note_supplier_list_model->updatePurchaseOrderId($delivery_note_supplier_list_id[$i],$id);
                        }else if ($regrind_supplier_receive_list_id[$i] > 0 ){
                            $regrind_supplier_receive_list_model->updatePurchaseOrderId($regrind_supplier_receive_list_id[$i],$id);
                        }else if ($request_standard_list_id[$i] > 0 ){
                            $request_standard_list_model->updatePurchaseOrderListId($request_standard_list_id[$i],$id);
                        }else if ($request_special_list_id[$i] > 0 ){
                            $request_special_list_model->updatePurchaseOrderListId($request_special_list_id[$i],$id);
                        }else if ($request_regrind_list_id[$i] > 0 ){
                            $request_regrind_list_model->updatePurchaseOrderListId($request_regrind_list_id[$i],$id);
                        }
                    }
                }
                
            }
            $data['purchase_order_status'] = 'New';
        }else if($product_id != ""){
            $data_sub = [];
            $data_sub['purchase_order_id'] = $purchase_order_id;
            $data_sub['product_id'] = $product_id;
            $data_sub['purchase_order_list_qty'] = (float)filter_var($purchase_order_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['purchase_order_list_price'] = (float)filter_var($purchase_order_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['purchase_order_list_price_sum'] = (float)filter_var($purchase_order_list_price_sum, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['purchase_order_list_delivery_min'] = $purchase_order_list_delivery_min;
            $data_sub['purchase_order_list_delivery_max'] = $purchase_order_list_delivery_max;
            $data_sub['purchase_order_list_remark'] = $purchase_order_list_remark;
            
            if($purchase_order_list_id != '0'){
                $purchase_order_list_model->updatePurchaseOrderListByIdAdmin($data_sub,$purchase_order_list_id);
            }else{
                $id = $purchase_order_list_model->insertPurchaseOrderList($data_sub);

                if($id > 0){
                    if($id > 0){
                        if($purchase_request_list_id > 0){
                            $purchase_request_list_model->updatePurchaseOrderId($purchase_request_list_id,$id);
                        }else if ($customer_purchase_order_list_detail_id > 0 ){
                            $customer_purchase_order_list_detail_model->updatePurchaseOrderId($customer_purchase_order_list_detail_id,$id);
                        }else if ($delivery_note_supplier_list_id > 0 ){
                            $delivery_note_supplier_list_model->updatePurchaseOrderId($delivery_note_supplier_list_id,$id);
                        }else if ($regrind_supplier_receive_list_id > 0 ){
                            $regrind_supplier_receive_list_model->updatePurchaseOrderId($regrind_supplier_receive_list_id,$id);
                        }else if ($request_standard_list_id > 0 ){
                            $request_standard_list_model->updatePurchaseOrderListId($request_standard_list_id,$id);
                        }else if ($request_special_list_id > 0 ){
                            $request_special_list_model->updatePurchaseOrderListId($request_special_list_id,$id);
                        }else if ($request_regrind_list_id > 0 ){
                            $request_regrind_list_model->updatePurchaseOrderListId($request_regrind_list_id,$id);
                        }
                    }
                }
            }
            $data['purchase_order_status'] = 'New';
        }else{
            $data['purchase_order_status'] = '';
        }


        $data['supplier_id'] = $_POST['supplier_id'];
        $data['purchase_order_code'] = $_POST['purchase_order_code'];
        $data['purchase_order_date'] = $_POST['purchase_order_date'];
        $data['purchase_order_credit_term'] = $_POST['purchase_order_credit_term'];
        $data['purchase_order_accept_status'] = '';
        $data['purchase_order_delivery_by'] = $_POST['purchase_order_delivery_by'];
        $data['purchase_order_total'] = (float)filter_var($purchase_order_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_vat'] = (float)filter_var($purchase_order_vat, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_net'] = (float)filter_var($purchase_order_net, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['employee_id'] = $_POST['employee_id'];

        $output = $purchase_order_model->updatePurchaseOrderByID($purchase_order_id , $data);
        

        if($output){
        
?>
        <script>window.location="index.php?app=purchase_order&action=update&id=<?php echo $purchase_order_id;?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
        
    
}else if ($_GET['action'] == 'rewrite' && ($license_purchase_page == "Medium" || $license_purchase_page == "High" )){
        
        if($purchase_order_id > 0){
            $purchase_order = $purchase_order_model->getPurchaseOrderByID($purchase_order_id);
            $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);
            $data = [];
            $purchase_order_model->cancelPurchaseOrderById($purchase_order_id);  

            $data['supplier_id'] = $purchase_order['supplier_id'];
            $data['employee_id'] = $purchase_order['employee_id'];
            $data['purchase_order_status'] = 'New';
            $data['purchase_order_type'] = $purchase_order['purchase_order_type'];
            $data['purchase_order_code'] = $purchase_order['purchase_order_code'];
            $data['purchase_order_date'] = $purchase_order['purchase_order_date'];
            $data['purchase_order_rewrite_id'] = $purchase_order_id;
            $data['purchase_order_rewrite_no'] = $purchase_order['purchase_order_rewrite_no'] + 1;
            $data['purchase_order_credit_term'] = $purchase_order['purchase_order_credit_term'];
            $data['purchase_order_accept_status'] = '';
            $data['purchase_order_delivery_by'] = $purchase_order['purchase_order_delivery_by'];
            $data['purchase_order_total'] = $purchase_order['purchase_order_total'];
            $data['purchase_order_vat'] = $purchase_order['purchase_order_vat'];
            $data['purchase_order_net'] = $purchase_order['purchase_order_net'];


            $purchase_order_id = $purchase_order_model->insertPurchaseOrder($data);
               
        
            for($i=0; $i < count($purchase_order_lists) ; $i++){
                $data_sub = [];
                $data_sub['purchase_order_id'] = $purchase_order_id;
                $data_sub['product_id'] = $purchase_order_lists[$i]['product_id'];
                $data_sub['purchase_order_list_qty'] = $purchase_order_lists[$i]['purchase_order_list_qty'];
                $data_sub['purchase_order_list_price'] = $purchase_order_lists[$i]['purchase_order_list_price'];
                $data_sub['purchase_order_list_price_sum'] = $purchase_order_lists[$i]['purchase_order_list_price_sum'];
                $data_sub['purchase_order_list_delivery_min'] = $purchase_order_lists[$i]['purchase_order_list_delivery_min'];
                $data_sub['purchase_order_list_delivery_max'] = $purchase_order_lists[$i]['purchase_order_list_delivery_max'];
                $data_sub['purchase_order_list_remark'] = $purchase_order_lists[$i]['purchase_order_list_remark'];

                $id = $purchase_order_list_model->insertPurchaseOrderList($data_sub);

                if($id > 0){
                    if($purchase_order_lists[$i]['purchase_request_list_id'] > 0){
                        $purchase_request_list_model->updatePurchaseOrderId($purchase_order_lists[$i]['purchase_request_list_id'],$id);
                    }else if ($purchase_order_lists[$i]['customer_purchase_order_list_detail_id'] > 0 ){
                        $customer_purchase_order_list_detail_model->updatePurchaseOrderId($purchase_order_lists[$i]['customer_purchase_order_list_detail_id'],$id);
                    }else if ($purchase_order_lists[$i]['delivery_note_supplier_list_id'] > 0 ){
                        $delivery_note_supplier_list_model->updatePurchaseOrderId($purchase_order_lists[$i]['delivery_note_supplier_list_id'],$id);
                    }else if ($purchase_order_lists[$i]['regrind_supplier_receive_list_id'] > 0 ){
                        $regrind_supplier_receive_list_model->updatePurchaseOrderId($purchase_order_lists[$i]['regrind_supplier_receive_list_id'],$id);
                    }else if ($purchase_order_lists[$i]['request_standard_list_id'] > 0 ){
                        $request_standard_list_model->updatePurchaseOrderListId($purchase_order_lists[$i]['request_standard_list_id'],$id);
                    }else if ($purchase_order_lists[$i]['request_special_list_id'] > 0 ){
                        $request_special_list_model->updatePurchaseOrderListId($purchase_order_lists[$i]['request_special_list_id'],$id);
                    }else if ($purchase_order_lists[$i]['request_regrind_list_id'] > 0 ){
                        $request_regrind_list_model->updatePurchaseOrderListId($purchase_order_lists[$i]['request_regrind_list_id'],$id);
                    }
                }            

            }
        
?>
        <script>window.location="index.php?app=purchase_order&action=update&id=<?php echo $purchase_order_id;?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
    
    
}else if ($_GET['action'] == 'approve'){
    
    if(isset($_POST['purchase_order_accept_status'])){
        $data = [];
        $data['purchase_order_accept_status'] = $_POST['purchase_order_accept_status'];
        $data['purchase_order_accept_by'] = $user[0][0];
        if($_POST['purchase_order_accept_status'] == 'Approve'){
            $data['purchase_order_status'] = 'Approved';
        }else if($_POST['purchase_order_accept_status'] == 'Waitting'){
            $data['purchase_order_status'] = 'Request';
        }else {
            $data['purchase_order_status'] = 'New';
        }
        
        
        $data['updateby'] = $user[0][0];

        $output = $purchase_order_model->updatePurchaseOrderAcceptByID($purchase_order_id,$data);


        if($output){

            $purchase_order = $purchase_order_model->getPurchaseOrderByID($purchase_order_id);

            $notification_model->setNotificationSeenByTypeID('Purchase Order',$purchase_order_id);
            $notification_model->setNotificationByUserID("Purchase Order",$purchase_order_id,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been request","index.php?app=purchase_order&action=detail&id=$purchase_order_id",$purchase_order['employee_id']);
            $notification_model->setNotification("Purchase Order",$purchase_order_id,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been request","index.php?app=purchase_order&action=detail&id=$purchase_order_id","license_purchase_page",'Medium');
            $notification_model->setNotification("Purchase Order",$purchase_order_id,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been request","index.php?app=purchase_order&action=detail&id=$purchase_order_id","license_purchase_page",'High');  
        
        
?>
        <script>window.location="index.php?app=purchase_order&action=detail&id=<?php echo $purchase_order_id;?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
        
    
}else if ($_GET['action'] == 'balance' && ($license_purchase_page == "Medium" || $license_purchase_page == "High" )){

    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);

    for($i=0; $i < count($purchase_order_lists) ; $i++){
        if($purchase_order_list_id > 0 ){
            if($purchase_order_list_id == $purchase_order_lists[$i]['purchase_order_list_id'] ){
                $data_sub = [];
                $data_sub['purchase_order_id'] = $purchase_order_id;
                $data_sub['product_id'] = $purchase_order_lists[$i]['product_id'];
                $data_sub['purchase_order_list_qty'] = $purchase_order_lists[$i]['purchase_order_list_qty_recieve'];
                $data_sub['purchase_order_list_price'] = $purchase_order_lists[$i]['purchase_order_list_price'];
                $data_sub['purchase_order_list_price_sum'] = $purchase_order_lists[$i]['purchase_order_list_price_sum'];
                $data_sub['purchase_order_list_delivery_min'] = $purchase_order_lists[$i]['purchase_order_list_delivery_min'];
                $data_sub['purchase_order_list_delivery_max'] = $purchase_order_lists[$i]['purchase_order_list_delivery_max'];
                $data_sub['purchase_order_list_remark'] = $purchase_order_lists[$i]['purchase_order_list_remark'];
                $purchase_order_list_model->updatePurchaseOrderListByIdAdmin($data_sub,$purchase_order_lists[$i]['purchase_order_list_id']);
            }
        }else{
            $data_sub = [];
            $data_sub['purchase_order_id'] = $purchase_order_id;
            $data_sub['product_id'] = $purchase_order_lists[$i]['product_id'];
            $data_sub['purchase_order_list_qty'] = $purchase_order_lists[$i]['purchase_order_list_qty_recieve'];
            $data_sub['purchase_order_list_price'] = $purchase_order_lists[$i]['purchase_order_list_price'];
            $data_sub['purchase_order_list_price_sum'] = $purchase_order_lists[$i]['purchase_order_list_price_sum'];
            $data_sub['purchase_order_list_delivery_min'] = $purchase_order_lists[$i]['purchase_order_list_delivery_min'];
            $data_sub['purchase_order_list_delivery_max'] = $purchase_order_lists[$i]['purchase_order_list_delivery_max'];
            $data_sub['purchase_order_list_remark'] = $purchase_order_lists[$i]['purchase_order_list_remark'];
            $purchase_order_list_model->updatePurchaseOrderListByIdAdmin($data_sub,$purchase_order_lists[$i]['purchase_order_list_id']);
        }
        
    } 
    
?>
    <script>window.location="index.php?app=purchase_order&action=detail&id=<?php echo $purchase_order_id;?>"</script>
<?php 
        
        
    
}else if ($_GET['action'] == 'request' && ($license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    
    if(isset($purchase_order_id)){
        $data = [];
        $data['purchase_order_accept_status'] = "Waitting";
        $data['purchase_order_accept_by'] = 0;

        $data['purchase_order_status'] = 'Request';
        
        $data['updateby'] = $user[0][0];

		$purchase_order = $purchase_order_model->getPurchaseOrderByID($purchase_order_id);

        $output = $purchase_order_model->updatePurchaseOrderRequestByID($purchase_order_id,$data);

        if($output){

            $notification_model->setNotification("Purchase Order",$purchase_order_id,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been request","index.php?app=purchase_order&action=detail&id=$purchase_order_id","license_manager_page",'High');
            $notification_model->setNotification("Purchase Order",$purchase_order_id,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been request","index.php?app=purchase_order&action=detail&id=$purchase_order_id","license_purchase_page",'High');  
        
?>
        <script>
            alert("Send request complete.");
            window.history.back();
        </script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
        
    
}else if ($_GET['action'] == 'checking' && ($license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    
    if(isset($purchase_order_id)){
        $data = [];

        $data['purchase_order_status'] = 'Checking';
        
        $data['updateby'] = $user[0][0];

        
        $supplier=$supplier_model->getSupplierByID($supplier_id);
        //echo "<pre>";
        //print_r($supplier);
        //echo "</pre>";
        if($supplier_id > 0){
            /******** setmail ********************************************/
            require("../controllers/mail/class.phpmailer.php");
            $mail = new PHPMailer();
            $body = '
                We are opening the purchase order.
                Can you please confirm the order details?. 
                At <a href="http://arno-thailand.revelsoft.co.th/partner-chips/supplier/index.php?app=purchase_order&action=checking&id='.$purchase_order_id.'">Click</a> 
                Before I send you a purchase order.
                <br>
                <br>
                <b> Best regards,</b><br><br>

                <b> Vittawat Bussara</b><br>
                <b> Head Office : </b> 2/27 Bangna Complex Office Tower,7th Flr.,Soi Bangna-Trad 25, Bangna-Trad Rd.,<br>
                Bangna, Bangna, Bangkok 10260, THAILAND, Tel : +662 399 2784  Fax : +662 399 2327 <br>
                <b> Tax ID :</b> 0105558002033 
                
            ';
            $mail->CharSet = "utf-8";
            $mail->IsSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->Host = "mail.revelsoft.co.th"; // SMTP server
            $mail->Port = 587; 
            $mail->Username = "support@revelsoft.co.th"; // account SMTP
            $mail->Password = "revelsoft1234@"; //  SMTP

            $mail->SetFrom("support@revelsoft.co.th", "Revelsoft.co.th");
            $mail->AddReplyTo("support@revelsoft.co.th","Revelsoft.co.th");
            $mail->Subject = "Arno order recheck to ".$supplier['supplier_name_en'];

            $mail->MsgHTML($body);

            $mail->AddAddress($supplier['supplier_email'], "Supplier Mail"); //
            //$mail->AddAddress($set1, $name); // 
            if(!$mail->Send()) {
                $result = "Mailer Error: " . $mail->ErrorInfo;
            }else{
                $output = $purchase_order_model->updatePurchaseOrderStatusByID($purchase_order_id,$data);
                $result = "Send checking complete.";
            } 
?>
        <script>
            alert("<?php echo $result; ?>");
            window.history.back();
        </script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
        
    
}else if ($_GET['action'] == 'sending' && ($license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    
    if(isset($purchase_order_id)){
        $data = [];

        $data['purchase_order_status'] = 'Sending';
        
        $data['updateby'] = $user[0][0];

        
        $supplier=$supplier_model->getSupplierByID($supplier_id);
        //echo "<pre>";
        //print_r($supplier);
        //echo "</pre>";
        if($supplier_id > 0){
            /******** setmail ********************************************/
            require("../controllers/mail/class.phpmailer.php");
            $mail = new PHPMailer();
            $body = '
                We are opened the purchase order.
                Can you confirm the order details?. 
                At <a href="http://arno-thailand.revelsoft.co.th/partner-chips/supplier/index.php?app=purchase_order&action=sending&id='.$purchase_order_id.'">Click</a> 

                <br>
                <br>
                <b> Best regards,</b><br><br>

                <b> Vittawat Bussara</b><br>
                <b> Head Office : </b> 2/27 Bangna Complex Office Tower,7th Flr.,Soi Bangna-Trad 25, Bangna-Trad Rd.,<br>
                Bangna, Bangna, Bangkok 10260, THAILAND, Tel : +662 399 2784  Fax : +662 399 2327 <br>
                <b> Tax ID :</b> 0105558002033 
                
            ';
            $mail->CharSet = "utf-8";
            $mail->IsSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->Host = "mail.revelsoft.co.th"; // SMTP server
            $mail->Port = 587; 
            $mail->Username = "support@revelsoft.co.th"; // account SMTP
            $mail->Password = "revelsoft1234@"; //  SMTP

            $mail->SetFrom("support@revelsoft.co.th", "Revelsoft.co.th");
            $mail->AddReplyTo("support@revelsoft.co.th","Revelsoft.co.th");
            $mail->Subject = "Arno order confirm to ".$supplier['supplier_name_en'];

            $mail->MsgHTML($body);

            $mail->AddAddress($supplier['supplier_email'], "Supplier Mail"); //
            //$mail->AddAddress($set1, $name); // 
            if(!$mail->Send()) {
                $result = "Mailer Error: " . $mail->ErrorInfo;
            }else{
                $output = $purchase_order_model->updatePurchaseOrderStatusByID($purchase_order_id,$data);
                $result = "Send purchase order complete.";
            } 
?>
        <script>
            alert("<?php echo $result; ?>");
            window.history.back();
        </script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
        
    
}else if ($license_purchase_page == "Medium" || $license_purchase_page == "High" ){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();

    $purchase_orders = $purchase_order_model->getPurchaseOrderBy($date_start,$date_end,$supplier_id,$keyword);
    $supplier_orders = $purchase_order_model->getSupplierOrder();
    $supplier_tests = $purchase_order_model->getSupplierTestOrder();
    $supplier_blankeds = $purchase_order_model->getSupplierBlankedOrder();
    $supplier_regrinds = $purchase_order_model->getSupplierRegrind();

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;
    $list_size = count($purchase_orders);
    $page_max = (int)($list_size/$page_size);
    if($list_size%$page_size > 0){
        $page_max += 1;
    }
    
    require_once($path.'view.inc.php');

}





?>