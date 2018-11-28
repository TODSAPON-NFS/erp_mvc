<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/ProductModel.php');
require_once('../models/CustomerPurchaseOrderModel.php');
require_once('../models/CustomerPurchaseOrderListModel.php');
require_once('../models/CustomerPurchaseOrderListDetailModel.php');
require_once('../models/QuotationModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');

require_once('../models/CustomerModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');


date_default_timezone_set('asia/bangkok');

$path = "modules/customer_purchase_order/views/";
$product_model = new ProductModel;
$user_model = new UserModel;
$customer_model = new CustomerModel;
$quotation_model = new QuotationModel;
$notification_model = new NotificationModel;
$customer_purchase_order_model = new CustomerPurchaseOrderModel;
$customer_purchase_order_list_model = new CustomerPurchaseOrderListModel;
$customer_purchase_order_list_detail_model = new CustomerPurchaseOrderListDetailModel;

$vat = 7;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('15');

$customer_purchase_order_id = $_GET['id'];
$quotation_id = $_GET['quotation_id'];
$customer_id = $_GET['customer_id'];

$notification_id = $_GET['notification'];
$target_dir = "../upload/customer_purchase_order/";

if(!isset($_GET['action']) && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){

    if(!isset($_GET['date_start'])){
        $date_start = $_SESSION['date_start'];
    }else{
        $date_start = $_GET['date_start'];
        $_SESSION['date_start'] = $date_start;
    }


    if(!isset($_GET['date_end'])){
        $date_end = $_SESSION['date_end'];
    }else{
        $date_end = $_GET['date_end'];
        $_SESSION['date_end'] = $date_end;
    }

    if(!isset($_GET['keyword'])){
        $keyword = $_SESSION['keyword'];
    }else{
        
        $keyword = $_GET['keyword']; 
        $_SESSION['keyword'] = $keyword;
    }
    
    
    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    }

    $customer_id = $_GET['customer_id'];
    $status = $_GET['status'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;

    $customers=$customer_model->getCustomerBy();

    $quotations=$quotation_model->getQuotationBy();
    $customer_purchase_orders = $customer_purchase_order_model->getCustomerPurchaseOrderBy($date_start,$date_end,$customer_id,$status,$keyword);

    
    $customer_orders = $customer_purchase_order_model->getCustomerOrder();
    

    $page_max = (int)(count($customer_purchase_orders)/$page_size);
    if(count($customer_purchase_orders)%$page_size > 0){
        $page_max += 1;
    }


    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    if($quotation_id > 0){
        $products=$product_model->getProduct( );
        $quotation = $quotation_model->getQuotationByID($quotation_id);
        $customer = $customer_model->getCustomerByID($quotation["customer_id"]);
        $customer_purchase_order = $customer_purchase_order_model->generateCustomerPurchaseOrderByID($quotation_id);
        $customer_purchase_order_lists = $customer_purchase_order_model->generateCustomerPurchaseOrderListBy($quotation_id);
    }

    if($customer_id > 0){
        $customer=$customer_model->getCustomerByID($customer_id);
        $customer_purchase_order_lists = $customer_purchase_order_model->generateCustomerPurchaseOrderListByCustomerId($customer_id);
    }

    $products=$product_model->getProduct( );
    $customers=$customer_model->getCustomerBy();

    $user=$user_model->getUserByID($admin_id);

    $data = [];
    $data['year'] = date("Y");
    $data['month'] = date("m");
    $data['number'] = "0000000000";
    $data['employee_name'] = $user["user_name"];
    $data['customer_code'] = $customers[0]['customer_code'];
    $data['customer_name'] = $customers[0]['customer_name_en'];

    $code = $code_generate->cut2Array($paper['paper_code'],$data);
    $last_code = "";
    for($i = 0 ; $i < count($code); $i++){
    
        if($code[$i]['type'] == "number"){
            $last_code = $customer_purchase_order_model->getCustomerPurchaseOrderLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    } 

    $first_date = date("d")."-".date("m")."-".date("Y");
   
    $users=$user_model->getUserBy();

    
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    $products=$product_model->getProduct( );
    $customers=$customer_model->getCustomerBy();
    
    $users=$user_model->getUserBy();
    $customer_purchase_order = $customer_purchase_order_model->getCustomerPurchaseOrderByID($customer_purchase_order_id);
    $customer=$customer_model->getCustomerByID($customer_purchase_order['customer_id']);
    $customer_purchase_order_lists = $customer_purchase_order_list_model->getCustomerPurchaseOrderListBy($customer_purchase_order_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    if($notification_id != ""){
        $notification_model->setNotificationSeenByID($notification_id);
    }
    $products=$product_model->getProduct( );
    $customers=$customer_model->getCustomerBy();
    
    $users=$user_model->getUserBy();
    $customer_purchase_order = $customer_purchase_order_model->getCustomerPurchaseOrderByID($customer_purchase_order_id);
    $customer=$customer_model->getCustomerByID($customer_purchase_order['customer_id']);
    $customer_purchase_order_lists = $customer_purchase_order_list_model->getCustomerPurchaseOrderListBy($customer_purchase_order_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete' && ( $license_sale_page == "High" ) ){
    $notification_model->deleteNotificationByTypeID('Customer Purchase Order',$customer_purchase_order_id);
    $customer_purchase_order_list_model->deleteCustomerPurchaseOrderListByCustomerPurchaseOrderID($customer_purchase_order_id);
    $customer_purchase_orders = $customer_purchase_order_model->deleteCustomerPurchaseOrderById($customer_purchase_order_id);
?>
    <script>window.location="index.php?app=customer_purchase_order"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    if(isset($_POST['customer_purchase_order_code'])){

        $customers=$customer_model->getCustomerBy();

        $user=$user_model->getUserByID($admin_id);

        $data = [];
        $data['year'] = date("Y");
        $data['month'] = date("m");
        $data['number'] = "0000000000";
        $data['employee_name'] = $user["user_name_en"];
        $data['customer_code'] = $customers[0]['customer_code'];

        $code = $code_generate->cut2Array($paper['paper_code'],$data);
        $last_code = "";
        for($i = 0 ; $i < count($code); $i++){
        
            if($code[$i]['type'] == "number"){
                $last_code = $customer_purchase_order_model->getCustomerPurchaseOrderLastID($last_code,$code[$i]['length']);
            }else{
                $last_code .= $code[$i]['value'];
            }   
        } 


        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_purchase_order_code'] = $_POST['customer_purchase_order_code'];
        $data['customer_purchase_order_code_gen'] = $last_code ;//$_POST['customer_purchase_order_code_gen'];
        $data['customer_purchase_order_date'] = $_POST['customer_purchase_order_date'];
        $data['customer_purchase_order_credit_term'] = $_POST['customer_purchase_order_credit_term'];
        $data['customer_purchase_order_delivery_term'] = $_POST['customer_purchase_order_delivery_term'];
        $data['customer_purchase_order_delivery_by'] = $_POST['customer_purchase_order_delivery_by'];
        $data['customer_purchase_order_status'] = 'Waiting';
        $data['customer_purchase_order_remark'] = $_POST['customer_purchase_order_remark'];

        $check = true;
        if($_FILES['customer_purchase_order_file']['name'] == ""){
            $data['customer_purchase_order_file'] = '';
        }else{
            
            $target_file = $target_dir .date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["customer_purchase_order_file"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["customer_purchase_order_file"]["size"] > 5000000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "pdf" ) {
                $error_msg = "Sorry, only PDF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["customer_purchase_order_file"]["tmp_name"], $target_file)) {
                $data['customer_purchase_order_file'] = date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["customer_purchase_order_file"]["name"]));
            } else {
                $error_msg =  "Sorry, there was an error uploading your file.";
                $check = false;
            } 
        }

        if($check == false){   

            ?>
                <script>
                    alert('<?php echo $error_msg; ?>');
                    window.history.back();
                </script>
            <?php
        }else{

            $customer_purchase_order_id = $customer_purchase_order_model->insertCustomerPurchaseOrder($data);

            if($customer_purchase_order_id > 0){

                $product_id = $_POST['product_id'];
                $end_user_id = $_POST['end_user_id'];
                $delivery_note_customer_list_id = $_POST['delivery_note_customer_list_id'];
                $customer_purchase_order_list_id = $_POST['customer_purchase_order_list_id'];
                $customer_purchase_order_product_name = $_POST['customer_purchase_order_product_name'];
                $customer_purchase_order_product_detail = $_POST['customer_purchase_order_product_detail'];
                $customer_purchase_order_list_qty = $_POST['customer_purchase_order_list_qty'];
                $customer_purchase_order_list_price = $_POST['customer_purchase_order_list_price'];
                $customer_purchase_order_list_price_sum = $_POST['customer_purchase_order_list_price_sum'];
                $customer_purchase_order_list_remark = $_POST['customer_purchase_order_list_remark'];
                $customer_purchase_order_list_hold = $_POST['customer_purchase_order_list_hold'];



                $customer_purchase_order_list_model->deleteCustomerPurchaseOrderListByCustomerPurchaseOrderIDNotIN($customer_purchase_order_id,$customer_purchase_order_list_id);
                if(is_array($product_id)){
                    for($i=0; $i < count($product_id) ; $i++){
                        $data = [];
                        $data['customer_purchase_order_id'] = $customer_purchase_order_id;
                        $data['product_id'] = $product_id[$i];
                        $data['end_user_id'] = $end_user_id[$i];
                        $data['delivery_note_customer_list_id'] = $delivery_note_customer_list_id[$i];
                        $data['customer_purchase_order_product_name'] = $customer_purchase_order_product_name[$i];
                        $data['customer_purchase_order_product_detail'] = $customer_purchase_order_product_detail[$i];
                        $data['customer_purchase_order_list_qty'] = (float)filter_var($customer_purchase_order_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data['customer_purchase_order_list_price'] = (float)filter_var($customer_purchase_order_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data['customer_purchase_order_list_price_sum'] = (float)filter_var($customer_purchase_order_list_price_sum[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data['customer_purchase_order_list_remark'] = $customer_purchase_order_list_remark[$i];
                        $data['customer_purchase_order_list_hold'] = $customer_purchase_order_list_hold[$i];

                        if($customer_purchase_order_list_id[$i] == "" || $customer_purchase_order_list_id[$i] == "0"){
                            $customer_purchase_order_list_model->insertCustomerPurchaseOrderList($data);
                        }else{
                            $customer_purchase_order_list_model->updateCustomerPurchaseOrderListById($data,$customer_purchase_order_list_id[$i]);

                            $supplier_id = $_POST['supplier_id_'.$customer_purchase_order_list_id[$i]];
                            $stock_hold_id = $_POST['stock_hold_id_'.$customer_purchase_order_list_id[$i]];
                            $stock_group_id = $_POST['stock_group_id_'.$customer_purchase_order_list_id[$i]];
                            $qty = $_POST['qty_'.$customer_purchase_order_list_id[$i]];
                            $customer_purchase_order_list_detail_id = $_POST['customer_purchase_order_list_detail_id_'.$customer_purchase_order_list_id[$i]];

                            $customer_purchase_order_list_detail_model->deleteCustomerPurchaseOrderListDetailByIDNotIN($customer_purchase_order_list_id[$i],$customer_purchase_order_list_detail_id);

                            if(is_array($supplier_id)){
                                for($ii=0; $ii < count($supplier_id) ; $ii++){
                                    $data = [];
                                    $data['supplier_id'] = $supplier_id[$ii];
                                    $data['stock_hold_id'] = $stock_hold_id[$ii];
                                    $data['stock_group_id'] = $stock_group_id[$ii];
                                    $data['qty'] = $qty[$ii];
                                    $data['customer_purchase_order_list_id'] = $customer_purchase_order_list_id[$i];
                                    $data['customer_purchase_order_list_detail_id'] = $customer_purchase_order_list_detail_id[$ii];
                                    
                                    if($customer_purchase_order_list_detail_id[$ii] == '0' || $customer_purchase_order_list_detail_id[$ii] == ''){
                                        $customer_purchase_order_list_detail_model->insertCustomerPurchaseOrderListDetail($data);
                                    }else{
                                        $customer_purchase_order_list_detail_model->updateCustomerPurchaseOrderListDetailByID($customer_purchase_order_list_detail_id[$ii], $data);
                                    }
                                }
                            }else if($supplier_id != ""){
                                $data = [];
                                $data['supplier_id'] = $supplier_id;
                                $data['stock_hold_id'] = $stock_hold_id;
                                $data['stock_group_id'] = $stock_group_id;
                                $data['qty'] = $qty;
                                $data['customer_purchase_order_list_id'] = $customer_purchase_order_list_id[$i];
                                $data['customer_purchase_order_list_detail_id'] = $customer_purchase_order_list_detail_id;
                                
                                if($customer_purchase_order_list_detail_id == '0' || $customer_purchase_order_list_detail_id == ''){
                                    $customer_purchase_order_list_detail_model->insertCustomerPurchaseOrderListDetail($data);
                                }else{
                                    $customer_purchase_order_list_detail_model->updateCustomerPurchaseOrderListDetailByID($customer_purchase_order_list_detail_id, $data);
                                }
                            }


                        }
                    }
                }else if($product_id != ""){
                    
                    $data = [];
                    $data['customer_purchase_order_id'] = $customer_purchase_order_id;
                    $data['product_id'] = $product_id;
                    $data['end_user_id'] = $end_user_id;
                    $data['delivery_note_customer_list_id'] = $delivery_note_customer_list_id;
                    $data['customer_purchase_order_product_name'] = $customer_purchase_order_product_name;
                    $data['customer_purchase_order_product_detail'] = $customer_purchase_order_product_detail;
                    $data['customer_purchase_order_list_qty'] = (float)filter_var($customer_purchase_order_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['customer_purchase_order_list_price'] = (float)filter_var($customer_purchase_order_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['customer_purchase_order_list_price_sum'] = (float)filter_var($customer_purchase_order_list_price_sum, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['customer_purchase_order_list_remark'] = $customer_purchase_order_list_remark;
                    $data['customer_purchase_order_list_hold'] = $customer_purchase_order_list_hold;

                    if($customer_purchase_order_list_id == "" || $customer_purchase_order_list_id == "0"){
                        $customer_purchase_order_list_model->insertCustomerPurchaseOrderList($data);
                    
                    }else{
                    
                            $customer_purchase_order_list_model->updateCustomerPurchaseOrderListById($data,$customer_purchase_order_list_id);

                            $supplier_id = $_POST['supplier_id_'.$customer_purchase_order_list_id];
                            $stock_hold_id = $_POST['stock_hold_id_'.$customer_purchase_order_list_id];
                            $stock_group_id = $_POST['stock_group_id_'.$customer_purchase_order_list_id];
                            $qty = $_POST['qty_'.$customer_purchase_order_list_id];
                            $customer_purchase_order_list_detail_id = $_POST['customer_purchase_order_list_detail_id_'.$customer_purchase_order_list_id];

                            $customer_purchase_order_list_detail_model->deleteCustomerPurchaseOrderListDetailByIDNotIN($customer_purchase_order_list_id,$customer_purchase_order_list_detail_id);

                            if(is_array($supplier_id)){
                                for($ii=0; $ii < count($supplier_id) ; $ii++){
                                    $data = [];
                                    $data['supplier_id'] = $supplier_id[$ii];
                                    $data['stock_hold_id'] = $stock_hold_id[$ii];
                                    $data['stock_group_id'] = $stock_group_id[$ii];
                                    $data['qty'] = $qty[$ii];
                                    $data['customer_purchase_order_list_id'] = $customer_purchase_order_list_id;
                                    $data['customer_purchase_order_list_detail_id'] = $customer_purchase_order_list_detail_id[$ii];
                                    
                                    if($customer_purchase_order_list_detail_id[$ii] == '0' || $customer_purchase_order_list_detail_id[$ii] == ''){
                                        
                                        $customer_purchase_order_list_detail_model->insertCustomerPurchaseOrderListDetail($data);
                                    }else{
                                        $customer_purchase_order_list_detail_model->updateCustomerPurchaseOrderListDetailByID($customer_purchase_order_list_detail_id[$ii], $data);
                                    }
                                }
                            }else if($supplier_id != ""){
                                $data = [];
                                $data['supplier_id'] = $supplier_id;
                                $data['stock_hold_id'] = $stock_hold_id;
                                $data['stock_group_id'] = $stock_group_id;
                                $data['qty'] = $qty;
                                $data['customer_purchase_order_list_id'] = $customer_purchase_order_list_id;
                                $data['customer_purchase_order_list_detail_id'] = $customer_purchase_order_list_detail_id;
                                
                                if($customer_purchase_order_list_detail_id == '0' || $customer_purchase_order_list_detail_id == ''){
                                    $customer_purchase_order_list_detail_model->insertCustomerPurchaseOrderListDetail($data);
                                }else{
                                    $customer_purchase_order_list_detail_model->updateCustomerPurchaseOrderListDetailByID($customer_purchase_order_list_detail_id, $data);
                                }
                            }
                    }
                    

                }

    ?>
            <script>window.location="index.php?app=customer_purchase_order&action=update&id=<?php echo $customer_purchase_order_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.history.back();</script>
    <?php
            }  
        } 
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    
    if(isset($_POST['customer_purchase_order_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id']; 
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_purchase_order_code'] = $_POST['customer_purchase_order_code'];
        $data['customer_purchase_order_code_gen'] = $_POST['customer_purchase_order_code_gen'];
        $data['customer_purchase_order_date'] = $_POST['customer_purchase_order_date'];
        $data['customer_purchase_order_credit_term'] = $_POST['customer_purchase_order_credit_term'];
        $data['customer_purchase_order_delivery_term'] = $_POST['customer_purchase_order_delivery_term'];
        $data['customer_purchase_order_delivery_by'] = $_POST['customer_purchase_order_delivery_by'];
        $data['customer_purchase_order_status'] = 'Waiting';
        $data['customer_purchase_order_remark'] = $_POST['customer_purchase_order_remark'];

        $check = true;

        if($_FILES['customer_purchase_order_file']['name'] == ""){
            $data['customer_purchase_order_file'] = $_POST['customer_purchase_order_file_o'];
        }else {
            $target_file = $target_dir .date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["customer_purchase_order_file"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["customer_purchase_order_file"]["size"] > 5000000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "pdf" ) {
                $error_msg = "Sorry, only PDF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["customer_purchase_order_file"]["tmp_name"], $target_file)) {
                $data['customer_purchase_order_file'] = date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["customer_purchase_order_file"]["name"]));
                $target_file = $target_dir . $_POST["customer_purchase_order_file_o"];
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
            } else {
                $error_msg =  "Sorry, there was an error uploading your file.";
                $check = false;
            } 
        }


        if($check == false){
            ?>
                <script>
                    alert('<?php echo $error_msg; ?>');
                    window.history.back();
                </script>
            <?php
        }else{

            $output = $customer_purchase_order_model->updateCustomerPurchaseOrderByID($customer_purchase_order_id,$data);

            $notification_model->setNotification("Customer Purchase Order","Customer Purchase Order <br>No. ".$data['customer_purchase_order_code']." ".$data['urgent_status'],"index.php?app=customer_purchase_order&action=detail&id=$customer_purchase_order_id","license_manager_page","'High'");
            
            
            $product_id = $_POST['product_id'];
            $end_user_id = $_POST['end_user_id'];
            $delivery_note_customer_list_id = $_POST['delivery_note_customer_list_id'];
            $customer_purchase_order_list_id = $_POST['customer_purchase_order_list_id'];
            $customer_purchase_order_product_name = $_POST['customer_purchase_order_product_name'];
            $customer_purchase_order_product_detail = $_POST['customer_purchase_order_product_detail'];
            $customer_purchase_order_list_qty = $_POST['customer_purchase_order_list_qty'];
            $customer_purchase_order_list_price = $_POST['customer_purchase_order_list_price'];
            $customer_purchase_order_list_price_sum = $_POST['customer_purchase_order_list_price_sum'];
            $customer_purchase_order_list_remark = $_POST['customer_purchase_order_list_remark'];
            $customer_purchase_order_list_hold = $_POST['customer_purchase_order_list_hold'];



            $customer_purchase_order_list_model->deleteCustomerPurchaseOrderListByCustomerPurchaseOrderIDNotIN($customer_purchase_order_id,$customer_purchase_order_list_id);
            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data = [];
                    $data['customer_purchase_order_id'] = $customer_purchase_order_id;
                    $data['product_id'] = $product_id[$i];
                    $data['end_user_id'] = $end_user_id[$i];
                    $data['delivery_note_customer_list_id'] = $delivery_note_customer_list_id[$i];
                    $data['customer_purchase_order_product_name'] = $customer_purchase_order_product_name[$i];
                    $data['customer_purchase_order_product_detail'] = $customer_purchase_order_product_detail[$i];
                    $data['customer_purchase_order_list_qty'] = (float)filter_var($customer_purchase_order_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['customer_purchase_order_list_price'] = (float)filter_var($customer_purchase_order_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['customer_purchase_order_list_price_sum'] = (float)filter_var($customer_purchase_order_list_price_sum[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['customer_purchase_order_list_remark'] = $customer_purchase_order_list_remark[$i];
                    $data['customer_purchase_order_list_hold'] = $customer_purchase_order_list_hold[$i];

                    if($customer_purchase_order_list_id[$i] == "" || $customer_purchase_order_list_id[$i] == "0"){
                        $customer_purchase_order_list_model->insertCustomerPurchaseOrderList($data);
                    }else{
                        $customer_purchase_order_list_model->updateCustomerPurchaseOrderListById($data,$customer_purchase_order_list_id[$i]);

                        $supplier_id = $_POST['supplier_id_'.$customer_purchase_order_list_id[$i]];
                        $stock_hold_id = $_POST['stock_hold_id_'.$customer_purchase_order_list_id[$i]];
                        $stock_group_id = $_POST['stock_group_id_'.$customer_purchase_order_list_id[$i]];
                        $qty = $_POST['qty_'.$customer_purchase_order_list_id[$i]];
                        $customer_purchase_order_list_detail_id = $_POST['customer_purchase_order_list_detail_id_'.$customer_purchase_order_list_id[$i]];

                        $customer_purchase_order_list_detail_model->deleteCustomerPurchaseOrderListDetailByIDNotIN($customer_purchase_order_list_id[$i],$customer_purchase_order_list_detail_id);

                        if(is_array($supplier_id)){
                            for($ii=0; $ii < count($supplier_id) ; $ii++){
                                $data = [];
                                $data['supplier_id'] = $supplier_id[$ii];
                                $data['stock_hold_id'] = $stock_hold_id[$ii];
                                $data['stock_group_id'] = $stock_group_id[$ii];
                                $data['qty'] = $qty[$ii];
                                $data['customer_purchase_order_list_id'] = $customer_purchase_order_list_id[$i];
                                $data['customer_purchase_order_list_detail_id'] = $customer_purchase_order_list_detail_id[$ii];
                                
                                if($customer_purchase_order_list_detail_id[$ii] == '0' || $customer_purchase_order_list_detail_id[$ii] == ''){
                                    $customer_purchase_order_list_detail_model->insertCustomerPurchaseOrderListDetail($data);
                                }else{
                                    $customer_purchase_order_list_detail_model->updateCustomerPurchaseOrderListDetailByID($customer_purchase_order_list_detail_id[$ii], $data);
                                }
                            }
                        }else if($supplier_id != ""){
                            $data = [];
                            $data['supplier_id'] = $supplier_id;
                            $data['stock_hold_id'] = $stock_hold_id;
                            $data['stock_group_id'] = $stock_group_id;
                            $data['qty'] = $qty;
                            $data['customer_purchase_order_list_id'] = $customer_purchase_order_list_id[$i];
                            $data['customer_purchase_order_list_detail_id'] = $customer_purchase_order_list_detail_id;
                            
                            if($customer_purchase_order_list_detail_id == '0' || $customer_purchase_order_list_detail_id == ''){
                                $customer_purchase_order_list_detail_model->insertCustomerPurchaseOrderListDetail($data);
                            }else{
                                $customer_purchase_order_list_detail_model->updateCustomerPurchaseOrderListDetailByID($customer_purchase_order_list_detail_id, $data);
                            }
                        }


                    }
                }
            }else if($product_id != ""){
                
                $data = [];
                $data['customer_purchase_order_id'] = $customer_purchase_order_id;
                $data['product_id'] = $product_id;
                $data['end_user_id'] = $end_user_id;
                $data['delivery_note_customer_list_id'] = $delivery_note_customer_list_id;
                $data['customer_purchase_order_product_name'] = $customer_purchase_order_product_name;
                $data['customer_purchase_order_product_detail'] = $customer_purchase_order_product_detail;
                $data['customer_purchase_order_list_qty'] = (float)filter_var($customer_purchase_order_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['customer_purchase_order_list_price'] = (float)filter_var($customer_purchase_order_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['customer_purchase_order_list_price_sum'] = (float)filter_var($customer_purchase_order_list_price_sum, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['customer_purchase_order_list_remark'] = $customer_purchase_order_list_remark;
                $data['customer_purchase_order_list_hold'] = $customer_purchase_order_list_hold;

                if($customer_purchase_order_list_id == "" || $customer_purchase_order_list_id == "0"){
                    $customer_purchase_order_list_model->insertCustomerPurchaseOrderList($data);
                }else{
                   
                        $customer_purchase_order_list_model->updateCustomerPurchaseOrderListById($data,$customer_purchase_order_list_id);

                        $supplier_id = $_POST['supplier_id_'.$customer_purchase_order_list_id];
                        $stock_hold_id = $_POST['stock_hold_id_'.$customer_purchase_order_list_id];
                        $stock_group_id = $_POST['stock_group_id_'.$customer_purchase_order_list_id];
                        $qty = $_POST['qty_'.$customer_purchase_order_list_id];
                        $customer_purchase_order_list_detail_id = $_POST['customer_purchase_order_list_detail_id_'.$customer_purchase_order_list_id];

                        $customer_purchase_order_list_detail_model->deleteCustomerPurchaseOrderListDetailByIDNotIN($customer_purchase_order_list_id,$customer_purchase_order_list_detail_id);

                        if(is_array($supplier_id)){
                            for($ii=0; $ii < count($supplier_id) ; $ii++){
                                $data = [];
                                $data['supplier_id'] = $supplier_id[$ii];
                                $data['stock_hold_id'] = $stock_hold_id[$ii];
                                $data['stock_group_id'] = $stock_group_id[$ii];
                                $data['qty'] = $qty[$ii];
                                $data['customer_purchase_order_list_id'] = $customer_purchase_order_list_id;
                                $data['customer_purchase_order_list_detail_id'] = $customer_purchase_order_list_detail_id[$ii];
                                
                                if($customer_purchase_order_list_detail_id[$ii] == '0' || $customer_purchase_order_list_detail_id[$ii] == ''){
                                    
                                    $customer_purchase_order_list_detail_model->insertCustomerPurchaseOrderListDetail($data);
                                }else{
                                    $customer_purchase_order_list_detail_model->updateCustomerPurchaseOrderListDetailByID($customer_purchase_order_list_detail_id[$ii], $data);
                                }
                            }
                        }else if($supplier_id != ""){
                            $data = [];
                            $data['supplier_id'] = $supplier_id;
                            $data['stock_hold_id'] = $stock_hold_id;
                            $data['stock_group_id'] = $stock_group_id;
                            $data['qty'] = $qty;
                            $data['customer_purchase_order_list_id'] = $customer_purchase_order_list_id;
                            $data['customer_purchase_order_list_detail_id'] = $customer_purchase_order_list_detail_id;
                            
                            if($customer_purchase_order_list_detail_id == '0' || $customer_purchase_order_list_detail_id == ''){
                                $customer_purchase_order_list_detail_model->insertCustomerPurchaseOrderListDetail($data);
                            }else{
                                $customer_purchase_order_list_detail_model->updateCustomerPurchaseOrderListDetailByID($customer_purchase_order_list_detail_id, $data);
                            }
                        }
                }
                

            }


            if($output){
    ?>
            <script>window.location="index.php?app=customer_purchase_order&action=update&id=<?PHP echo $customer_purchase_order_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.history.back();</script>
    <?php
            }
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
        
    
}else if ($_GET['action'] == 'approve'){
    
    if(isset($_POST['customer_purchase_order_accept_status'])){
        $data = [];
        $data['customer_purchase_order_accept_status'] = $_POST['customer_purchase_order_accept_status'];
        $data['customer_purchase_order_accept_by'] = $user[0][0];
        $data['customer_purchase_order_status'] = 'Approved';
        $data['updateby'] = $user[0][0];

        $output = $customer_purchase_order_model->updateCustomerPurchaseOrderAcceptByID($customer_purchase_order_id,$data);


        if($output){
            $notification_model->setNotificationSeenByTypeID('Customer Purchase Order',$customer_purchase_order_id);
        
?>
        <script>window.location="index.php?app=customer_purchase_order"</script>
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
        
        
    
}else if ($license_sale_page == "Medium" || $license_sale_page == "High" ){

    if(!isset($_GET['date_start'])){
        $date_start = $_SESSION['date_start'];
    }else{
        $date_start = $_GET['date_start'];
        $_SESSION['date_start'] = $date_start;
    }


    if(!isset($_GET['date_end'])){
        $date_end = $_SESSION['date_end'];
    }else{
        $date_end = $_GET['date_end'];
        $_SESSION['date_end'] = $date_end;
    }

    if(!isset($_GET['keyword'])){
        $keyword = $_SESSION['keyword'];
    }else{
        
        $keyword = $_GET['keyword']; 
        $_SESSION['keyword'] = $keyword;
    }

    
    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    }
    
    $customer_id = $_GET['customer_id'];
    $status = $_GET['status'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;

    $customers=$customer_model->getCustomerBy();

    $quotations=$quotation_model->getQuotationBy();
    $customer_purchase_orders = $customer_purchase_order_model->getCustomerPurchaseOrderBy($date_start,$date_end,$customer_id,$status,$keyword);

    
    $customer_orders = $customer_purchase_order_model->getCustomerOrder();
    
    $page_max = (int)(count($customer_purchase_orders)/$page_size);
    if(count($customer_purchase_orders)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view.inc.php');
}




?>