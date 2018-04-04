<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/PurchaseOrderModel.php');
require_once('../models/PurchaseOrderListModel.php');
require_once('../models/CustomerPurchaseOrderListDetailModel.php');
require_once('../models/PurchaseRequestListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductSupplierModel.php');
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/purchase_order/views/";
$user_model = new UserModel;
$supplier_model = new SupplierModel;
$notification_model = new NotificationModel;
$purchase_order_model = new PurchaseOrderModel;
$purchase_order_list_model = new PurchaseOrderListModel;
$purchase_request_list_model = new PurchaseRequestListModel;
$customer_purchase_order_list_detail_model = new CustomerPurchaseOrderListDetailModel;
$product_supplier_model = new ProductSupplierModel;
$first_char = "PO";
$purchase_order_id = $_GET['id'];
$notification_id = $_GET['notification'];
$supplier_id = $_GET['supplier_id'];
if(!isset($_GET['action'])){

    $purchase_orders = $purchase_order_model->getPurchaseOrderBy();
    $supplier_orders = $purchase_order_model->getSupplierOrder();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();


    if($supplier_id > 0){
        $supplier=$supplier_model->getSupplierByID($supplier_id);
        $products=$product_supplier_model->getProductBySupplierID($supplier_id);
        
        if($supplier['supplier_domestic'] == "ภายในประเทศ"){
            $first_char = "LP";
        }else{
            $first_char = "PO";
        }
        $first_code = $first_char.date("y").date("m");
        $last_code = $purchase_order_model->getPurchaseOrderLastID($first_code,3);

        //$purchase_order_lists = $purchase_order_model->generatePurchaseOrderListBySupplierId($supplier_id);
    }
   

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $purchase_order = $purchase_order_model->getPurchaseOrderByID($purchase_order_id);
    $supplier=$supplier_model->getSupplierByID($purchase_order['supplier_id']);
    $products=$product_supplier_model->getProductBySupplierID($purchase_order['supplier_id']);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    if($notification_id != ""){
        $notification_model->setNotificationSeenByID($notification_id);
    }
    $purchase_order = $purchase_order_model->getPurchaseOrderViewByID($purchase_order_id);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete'){

    $purchase_order_list_model->deletePurchaseOrderListByPurchaseOrderID($purchase_order_id);
    $purchase_orders = $purchase_order_model->deletePurchaseOrderById($purchase_order_id);
?>
    <script>window.location="index.php?app=purchase_order"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['purchase_order_code'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['purchase_order_code'] = $_POST['purchase_order_code'];
        $data['purchase_order_date'] = $_POST['purchase_order_date'];
        $data['purchase_order_credit_term'] = $_POST['purchase_order_credit_term'];
        $data['purchase_order_accept_status'] = '';
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
                    }else if ($delivery_note_supplier_list_id[$i] > 0 ){
                        $delivery_note_supplier_list_model->updatePurchaseOrderId($delivery_note_supplier_list_id[$i],$id);
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
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['purchase_order_code'])){

        $data = [];
        
     
        $product_id = $_POST['product_id'];
        
        $purchase_request_list_id = $_POST['purchase_request_list_id'];
        $customer_purchase_order_list_detail_id = $_POST['customer_purchase_order_list_detail_id'];
        $delivery_note_supplier_list_id = $_POST['delivery_note_supplier_list_id'];

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
                    if($purchase_request_list_id > 0){
                        $purchase_request_list_model->updatePurchaseOrderId($purchase_request_list_id,$id);
                    }else if ($customer_purchase_order_list_detail_id > 0 ){
                        $customer_purchase_order_list_detail_model->updatePurchaseOrderId($customer_purchase_order_list_detail_id,$id);
                    }else if ($delivery_note_supplier_list_id[$i] > 0 ){
                        $delivery_note_supplier_list_model->updatePurchaseOrderId($delivery_note_supplier_list_id[$i],$id);
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
            $notification_model->setNotificationSeenByURL('action=detail&id='.$purchase_order_id);
        
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
        
        
    
}else if ($_GET['action'] == 'request'){
    
    if(isset($purchase_order_id)){
        $data = [];
        $data['purchase_order_accept_status'] = "Waitting";
        $data['purchase_order_accept_by'] = 0;

        $data['purchase_order_status'] = 'Request';
        
        $data['updateby'] = $user[0][0];
		$purchase_order = $purchase_order_model->getPurchaseOrderByID($purchase_order_id);

        $output = $purchase_order_model->updatePurchaseOrderRequestByID($purchase_order_id,$data);
        $notification_model->setNotification("Purchase Order","Purchase Order <br>No. ".$purchase_order['purchase_order_code']." ".$data['urgent_status'],"index.php?app=purchase_order&action=detail&id=$purchase_order_id","license_manager_page","'High'");

        if($output){
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
        
        
    
}else if ($_GET['action'] == 'checking'){
    
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
                At <a href="http://support.revelsoft.co.th/erp_mvc/arno/supplier/index.php?app=purchase_order&action=checking&id='.$purchase_order_id.'">Click</a> 
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
            $mail->Password = "support123456"; //  SMTP

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
        
        
    
}else if ($_GET['action'] == 'sending'){
    
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
                At <a href="http://support.revelsoft.co.th/erp_mvc/arno/supplier/index.php?app=purchase_order&action=sending&id='.$purchase_order_id.'">Click</a> 

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
            $mail->Password = "support123456"; //  SMTP

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
        
        
    
}else{

    $purchase_orders = $purchase_order_model->getPurchaseOrderBy();
    $supplier_orders = $purchase_order_model->getSupplierOrder();
    require_once($path.'view.inc.php');

}





?>