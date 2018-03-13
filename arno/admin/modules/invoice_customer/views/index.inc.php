<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/InvoiceCustomerModel.php');
require_once('../models/InvoiceCustomerListModel.php');
require_once('../models/CustomerPurchaseOrderListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/invoice_customer/views/";
$user_model = new UserModel;
$customer_model = new CustomerModel;
$notification_model = new NotificationModel;
$invoice_customer_model = new InvoiceCustomerModel;
$invoice_customer_list_model = new InvoiceCustomerListModel;
$customer_purchase_order_list_model = new CustomerPurchaseOrderListModel;
$product_model = new ProductModel;
$invoice_customer_id = $_GET['id'];
$notification_id = $_GET['notification'];
$customer_id = $_GET['customer_id'];
$vat = 7;
$first_char = "INV";

if(!isset($_GET['action'])){

    $invoice_customers = $invoice_customer_model->getInvoiceCustomerBy();
    $customer_orders = $invoice_customer_model->getCustomerOrder();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $first_code = $first_char.date("y").date("m");
    $last_code = $invoice_customer_model->getInvoiceCustomerLastID($first_code,3);

    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();
    
    if($customer_id > 0){
        $customer=$customer_model->getCustomerByID($customer_id);
        $invoice_customer_lists = $invoice_customer_model->generateInvoiceCustomerListByCustomerId($customer_id);
    }
   

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();

    $invoice_customer = $invoice_customer_model->getInvoiceCustomerByID($invoice_customer_id);

    $customer=$customer_model->getCustomerByID($invoice_customer['customer_id']);

    $invoice_customer_lists = $invoice_customer_list_model->getInvoiceCustomerListBy($invoice_customer_id);

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    
    $invoice_customer = $invoice_customer_model->getInvoiceCustomerViewByID($invoice_customer_id);
    //echo "<pre>";
    //print_r($invoice_customer);
    //echo "</pre>";
    $invoice_customer_lists = $invoice_customer_list_model->getInvoiceCustomerListBy($invoice_customer_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    
    $invoice_customer = $invoice_customer_model->getInvoiceCustomerViewByID($invoice_customer_id);
    //echo "<pre>";
    //print_r($invoice_customer);
    //echo "</pre>";
    $invoice_customer_lists = $invoice_customer_list_model->getInvoiceCustomerListBy($invoice_customer_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){
    $invoice_customer_model->deleteInvoiceCustomerById($invoice_customer_id);
?>
    <script>window.location="index.php?app=invoice_customer"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['invoice_customer_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['invoice_customer_code'] = $_POST['invoice_customer_code'];
        $data['invoice_customer_total_price'] = (float)filter_var($_POST['invoice_customer_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_vat'] = (float)filter_var($_POST['invoice_customer_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_vat_price'] =(float)filter_var( $_POST['invoice_customer_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_net_price'] = (float)filter_var($_POST['invoice_customer_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_date'] = $_POST['invoice_customer_date'];
        $data['invoice_customer_name'] = $_POST['invoice_customer_name'];
        $data['invoice_customer_address'] = $_POST['invoice_customer_address'];
        $data['invoice_customer_tax'] = $_POST['invoice_customer_tax'];
        $data['invoice_customer_term'] = $_POST['invoice_customer_term'];
        $data['invoice_customer_due'] = $_POST['invoice_customer_due'];
        $data['addby'] = $user[0][0];

        $output = $invoice_customer_model->insertInvoiceCustomer($data);

        

        if($output > 0){
            $data = [];
            $product_id = $_POST['product_id'];
            $customer_purchase_order_list_id = $_POST['customer_purchase_order_list_id'];
            $invoice_customer_list_product_name = $_POST['invoice_customer_list_product_name'];
            $invoice_customer_list_product_detail = $_POST['invoice_customer_list_product_detail'];
            $invoice_customer_list_qty = $_POST['invoice_customer_list_qty'];
            $invoice_customer_list_price = $_POST['invoice_customer_list_price'];
            $invoice_customer_list_total = $_POST['invoice_customer_list_total'];
            $invoice_customer_list_remark = $_POST['invoice_customer_list_remark'];

            
           
            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data_sub = [];
                    $data_sub['invoice_customer_id'] = $output;
                    $data_sub['product_id'] = $product_id[$i];
                    
                    $data_sub['invoice_customer_list_product_name'] = $invoice_customer_list_product_name[$i];
                    $data_sub['invoice_customer_list_product_detail'] = $invoice_customer_list_product_detail[$i];
                    $data_sub['invoice_customer_list_qty'] = (float)filter_var($invoice_customer_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_customer_list_price'] = (float)filter_var($invoice_customer_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_customer_list_total'] = (float)filter_var($invoice_customer_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_customer_list_remark'] = $invoice_customer_list_remark[$i];

                    $id = $invoice_customer_list_model->insertInvoiceCustomerList($data_sub);
                    if($id > 0){
                        if($customer_purchase_order_list_id[$i] > 0){
                            $invoice_customer_list_model->updateCustomerPurchaseOrderListID($customer_purchase_order_list_id[$i],$id);
                        }
                    }
                }
            }else if($product_id != ""){
                $data_sub = [];
                $data_sub['invoice_customer_id'] = $output;
                $data_sub['product_id'] = $product_id;
                
                $data_sub['invoice_customer_list_product_name'] = $invoice_customer_list_product_name;
                $data_sub['invoice_customer_list_product_detail'] = $invoice_customer_list_product_detail;
                $data_sub['invoice_customer_list_qty'] = (float)filter_var($invoice_customer_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_customer_list_price'] = (float)filter_var($invoice_customer_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_customer_list_total'] = (float)filter_var($invoice_customer_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_customer_list_remark'] = $invoice_customer_list_remark;
    
                $id = $invoice_customer_list_model->insertInvoiceCustomerList($data_sub);
                if($id > 0){
                    if($customer_purchase_order_list_id > 0){
                        $invoice_customer_list_model->updateCustomerPurchaseOrderListID($customer_purchase_order_list_id,$id);
                    }
                }
            }
            
?>
        <script>window.location="index.php?app=invoice_customer&action=update&id=<?php echo $output;?>"</script>
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
    
    if(isset($_POST['invoice_customer_code'])){
        
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['invoice_customer_code'] = $_POST['invoice_customer_code'];
        $data['invoice_customer_total_price'] = (double)filter_var($_POST['invoice_customer_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_vat'] = (double)filter_var($_POST['invoice_customer_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_vat_price'] =(double)filter_var( $_POST['invoice_customer_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_net_price'] = (double)filter_var($_POST['invoice_customer_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_date'] = $_POST['invoice_customer_date'];
        $data['invoice_customer_name'] = $_POST['invoice_customer_name'];
        $data['invoice_customer_address'] = $_POST['invoice_customer_address'];
        $data['invoice_customer_tax'] = $_POST['invoice_customer_tax'];
        $data['invoice_customer_term'] = $_POST['invoice_customer_term'];
        $data['invoice_customer_due'] = $_POST['invoice_customer_due'];
        $data['addby'] = $user[0][0];


        $product_id = $_POST['product_id'];
        $invoice_customer_list_id = $_POST['invoice_customer_list_id'];
        $customer_purchase_order_list_id = $_POST['customer_purchase_order_list_id'];
        $invoice_customer_list_product_name = $_POST['invoice_customer_list_product_name'];
        $invoice_customer_list_product_detail = $_POST['invoice_customer_list_product_detail'];
        $invoice_customer_list_qty = $_POST['invoice_customer_list_qty'];
        $invoice_customer_list_price = $_POST['invoice_customer_list_price'];
        $invoice_customer_list_total = $_POST['invoice_customer_list_total'];
        $invoice_customer_list_remark = $_POST['invoice_customer_list_remark'];

        
        $invoice_customer_list_model->deleteInvoiceCustomerListByInvoiceCustomerIDNotIN($invoice_customer_id,$invoice_customer_list_id);
        
        

        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data_sub = [];
                $data_sub['invoice_customer_id'] = $output;
                $data_sub['product_id'] = $product_id[$i];
                
                $data_sub['invoice_customer_list_product_name'] = $invoice_customer_list_product_name[$i];
                $data_sub['invoice_customer_list_product_detail'] = $invoice_customer_list_product_detail[$i];
                $data_sub['invoice_customer_list_qty'] = (float)filter_var($invoice_customer_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_customer_list_price'] = (float)filter_var($invoice_customer_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_customer_list_total'] = (float)filter_var($invoice_customer_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_customer_list_remark'] = $invoice_customer_list_remark[$i];

                if($invoice_customer_list_id[$i] > 0){
                    $invoice_customer_list_model->updateInvoiceCustomerListById($data_sub,$invoice_customer_list_id[$i]);
                    if($customer_purchase_order_list_id[$i] > 0){
                        $invoice_customer_list_model->updateCustomerPurchaseOrderListID($customer_purchase_order_list_id[$i],$invoice_customer_list_id[$i]);
                    }
                }else{
                    $id = $invoice_customer_list_model->insertInvoiceCustomerList($data_sub);
                    if($id > 0){
                        if($customer_purchase_order_list_id[$i] > 0){
                            $invoice_customer_list_model->updateCustomerPurchaseOrderListID($customer_purchase_order_list_id[$i],$id);
                        }
                    }
                }
                
            }
        }else if($product_id != ""){
            $data_sub = [];
            $data_sub['invoice_customer_id'] = $output;
            $data_sub['product_id'] = $product_id;
            
            $data_sub['invoice_customer_list_product_name'] = $invoice_customer_list_product_name;
            $data_sub['invoice_customer_list_product_detail'] = $invoice_customer_list_product_detail;
            $data_sub['invoice_customer_list_qty'] = (float)filter_var($invoice_customer_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_customer_list_price'] = (float)filter_var($invoice_customer_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_customer_list_total'] = (float)filter_var($invoice_customer_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_customer_list_remark'] = $invoice_customer_list_remark;

            if($invoice_customer_list_id > 0){
                $invoice_customer_list_model->updateInvoiceCustomerListById($data_sub);
                if($customer_purchase_order_list_id > 0){
                    $invoice_customer_list_model->updateCustomerPurchaseOrderListID($customer_purchase_order_list_id,$invoice_customer_list_id);
                }
            }else{
                $id = $invoice_customer_list_model->insertInvoiceCustomerList($data_sub,$invoice_customer_list_id);
                if($id > 0){
                    if($customer_purchase_order_list_id > 0){
                        $invoice_customer_list_model->updateCustomerPurchaseOrderListID($customer_purchase_order_list_id,$id);
                    }
                }
            }
        }

        $output = $invoice_customer_model->updateInvoiceCustomerByID($invoice_customer_id,$data);
        

        if($output){
        
?>
        <script>window.location="index.php?app=invoice_customer&action=update&id=<?php echo $invoice_customer_id;?>"</script>
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

    $invoice_customers = $invoice_customer_model->getInvoiceCustomerBy();
    $customer_orders = $invoice_customer_model->getCustomerOrder();
    require_once($path.'view.inc.php');

}





?>