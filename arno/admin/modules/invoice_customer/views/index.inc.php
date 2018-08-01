<?php
session_start();
$user = $_SESSION['user'];

require_once('../functions/NumbertoTextFunction.func.php');
require_once('../models/InvoiceCustomerModel.php');
require_once('../models/InvoiceCustomerListModel.php');
require_once('../models/CustomerPurchaseOrderListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/StockGroupModel.php');
require_once('../models/CustomerModel.php');

require_once('../models/JournalSaleModel.php');
require_once('../models/JournalSaleListModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/invoice_customer/views/";
$number_2_text = new Number2Text;
$user_model = new UserModel;
$customer_model = new CustomerModel;
$notification_model = new NotificationModel;
$invoice_customer_model = new InvoiceCustomerModel;
$invoice_customer_list_model = new InvoiceCustomerListModel;
$customer_purchase_order_list_model = new CustomerPurchaseOrderListModel;
$product_model = new ProductModel;
$stock_group_model = new StockGroupModel;
$journal_sale_model = new JournalSaleModel;
$journal_sale_list_model = new JournalSaleListModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('16');

$invoice_customer_id = $_GET['id'];
$notification_id = $_GET['notification'];
$customer_id = $_GET['customer_id'];
$customer_purchase_order_id = $_GET['customer_purchase_order_id'];
$vat = 7;


if(!isset($_GET['action']) && ($license_sale_page == "Medium" || $license_sale_page == "High" )){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();

    $invoice_customers = $invoice_customer_model->getInvoiceCustomerBy($date_start,$date_end,$customer_id,$keyword);
    $customer_orders = $invoice_customer_model->getCustomerOrder();
    $customer_purchase_orders = $invoice_customer_model->getCustomerPurchaseOrder();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_sale_page == "Medium" || $license_sale_page == "High" )){
    
    $products=$product_model->getProductBy('','','','');
    $stock_groups=$stock_group_model->getStockGroupBy();
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();
    $user=$user_model->getUserByID($admin_id);
    
    $first_date = date("d")."-".date("m")."-".date("Y");

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
            $last_code = $invoice_customer_model->getInvoiceCustomerLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }  

    
    
    if($customer_id > 0){
        $customer=$customer_model->getCustomerByID($customer_id);
        $invoice_customer_lists = $invoice_customer_model->generateInvoiceCustomerListByCustomerId($customer_id,'','',$customer_purchase_order_id);
    }
   

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_sale_page == "Medium" || $license_sale_page == "High" )){
    $products=$product_model->getProductBy('','','','');
    $stock_groups=$stock_group_model->getStockGroupBy();
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();

    $invoice_customer = $invoice_customer_model->getInvoiceCustomerByID($invoice_customer_id);

    $customer=$customer_model->getCustomerByID($invoice_customer['customer_id']);

    $invoice_customer_lists = $invoice_customer_list_model->getInvoiceCustomerListBy($invoice_customer_id);

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    
    $invoice_customer = $invoice_customer_model->getInvoiceCustomerViewByID($invoice_customer_id);
    $invoice_customer_lists = $invoice_customer_list_model->getInvoiceCustomerListBy($invoice_customer_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    
    $invoice_customer = $invoice_customer_model->getInvoiceCustomerViewByID($invoice_customer_id);
    $invoice_customer_lists = $invoice_customer_list_model->getInvoiceCustomerListBy($invoice_customer_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete' && ( $license_sale_page == "High" )){
    $invoice_customer_model->deleteInvoiceCustomerById($invoice_customer_id);
    $journal_sale_model->deleteJournalSaleByInvoiceCustomerID($invoice_customer_id);

?>
    <script>window.location="index.php?app=invoice_customer"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_sale_page == "Medium" || $license_sale_page == "High" )){
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

        $invoice_customer_id = $invoice_customer_model->insertInvoiceCustomer($data);

        

        if($invoice_customer_id > 0){


            $data = [];
            $first_code = "UV".date("y").date("m");
            $first_date = date("d")."-".date("m")."-".date("Y");
            $last_code = $journal_sale_model->getJournalSaleLastID($first_code,3);
            $data['invoice_customer_id'] = $invoice_customer_id;
            $data['journal_sale_date'] = $_POST['invoice_customer_date'];
            $data['journal_sale_code'] = $last_code;
            $data['journal_sale_name'] = "ขายเชื่อจาก ".$_POST['invoice_customer_name']." [".$_POST['invoice_customer_code']."] ";
            $data['addby'] = $admin_id;
    
    
            $journal_sale_id = $journal_sale_model->insertJournalSale($data);

            if($journal_sale_id > 0){

                $customer=$customer_model->getCustomerByID($_POST['customer_id']);
                $data = [];
                $data['journal_sale_id'] = $journal_sale_id;
                $data['account_id'] = $customer['account_id'];
                $data['journal_sale_list_name'] = "ขายเชื่อจาก ".$_POST['invoice_customer_name']." [".$_POST['invoice_customer_code']."] ";
                $data['journal_sale_list_debit'] = (float)filter_var( $_POST['invoice_customer_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['journal_sale_list_credit'] = 0;
                $journal_sale_list_model->insertJournalSaleList($data);

                if((float)filter_var( $_POST['invoice_customer_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) > 0){
                    $data = [];
                    $data['journal_sale_id'] = $journal_sale_id;
                    $data['account_id'] = '91';
                    $data['journal_sale_list_name'] = "ขายเชื่อจาก ".$_POST['invoice_customer_name']." [".$_POST['invoice_customer_code']."] ";
                    $data['journal_sale_list_debit'] = (float)filter_var( $_POST['invoice_customer_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['journal_sale_list_credit'] = 0;
                    $journal_sale_list_model->insertJournalSaleList($data);
               


                }
            }

            $data = [];
            $product_id = $_POST['product_id'];
            $customer_purchase_order_list_id = $_POST['customer_purchase_order_list_id'];
            $stock_group_id = $_POST['stock_group_id'];
            $invoice_customer_list_product_name = $_POST['invoice_customer_list_product_name'];
            $invoice_customer_list_product_detail = $_POST['invoice_customer_list_product_detail'];
            $invoice_customer_list_qty = $_POST['invoice_customer_list_qty'];
            $invoice_customer_list_price = $_POST['invoice_customer_list_price'];
            $invoice_customer_list_total = $_POST['invoice_customer_list_total'];
            $invoice_customer_list_remark = $_POST['invoice_customer_list_remark'];

            
           
            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data_sub = [];
                    $data_sub['invoice_customer_id'] = $invoice_customer_id;
                    $data_sub['product_id'] = $product_id[$i];
                    $data_sub['customer_purchase_order_list_id'] = $customer_purchase_order_list_id[$i];
                    $data_sub['stock_group_id'] = $stock_group_id[$i];
                    $data_sub['stock_date'] = $_POST['invoice_customer_date'];
                    $data_sub['invoice_customer_list_product_name'] = $invoice_customer_list_product_name[$i];
                    $data_sub['invoice_customer_list_product_detail'] = $invoice_customer_list_product_detail[$i];
                    $data_sub['invoice_customer_list_qty'] = (float)filter_var($invoice_customer_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_customer_list_price'] = (float)filter_var($invoice_customer_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_customer_list_total'] = (float)filter_var($invoice_customer_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_customer_list_remark'] = $invoice_customer_list_remark[$i];

                    $id = $invoice_customer_list_model->insertInvoiceCustomerList($data_sub);
                }
            }else if($product_id != ""){
                $data_sub = [];
                $data_sub['invoice_customer_id'] = $invoice_customer_id;
                $data_sub['product_id'] = $product_id;
                $data_sub['customer_purchase_order_list_id'] = $customer_purchase_order_list_id;
                $data_sub['stock_group_id'] = $stock_group_id;
                $data_sub['stock_date'] = $_POST['invoice_customer_date'];
                $data_sub['invoice_customer_list_product_name'] = $invoice_customer_list_product_name;
                $data_sub['invoice_customer_list_product_detail'] = $invoice_customer_list_product_detail;
                $data_sub['invoice_customer_list_qty'] = (float)filter_var($invoice_customer_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_customer_list_price'] = (float)filter_var($invoice_customer_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_customer_list_total'] = (float)filter_var($invoice_customer_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_customer_list_remark'] = $invoice_customer_list_remark;
    
                $id = $invoice_customer_list_model->insertInvoiceCustomerList($data_sub);
            }
            
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
    
}else if ($_GET['action'] == 'edit' && ($license_sale_page == "Medium" || $license_sale_page == "High" )){
    
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
        $data['invoice_customer_close'] = $_POST['invoice_customer_close'];
        $data['addby'] = $user[0][0];


        $product_id = $_POST['product_id'];
        $invoice_customer_list_id = $_POST['invoice_customer_list_id'];
        $customer_purchase_order_list_id = $_POST['customer_purchase_order_list_id'];
        $stock_group_id = $_POST['stock_group_id'];
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
                $data_sub['customer_purchase_order_list_id'] = $customer_purchase_order_list_id[$i];
                $data_sub['stock_group_id'] = $stock_group_id[$i];
                $data_sub['stock_date'] = $_POST['invoice_customer_date'];
                $data_sub['invoice_customer_list_product_name'] = $invoice_customer_list_product_name[$i];
                $data_sub['invoice_customer_list_product_detail'] = $invoice_customer_list_product_detail[$i];
                $data_sub['invoice_customer_list_qty'] = (float)filter_var($invoice_customer_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_customer_list_price'] = (float)filter_var($invoice_customer_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_customer_list_total'] = (float)filter_var($invoice_customer_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_customer_list_remark'] = $invoice_customer_list_remark[$i];

                if($invoice_customer_list_id[$i] > 0){
                    $invoice_customer_list_model->updateInvoiceCustomerListById($data_sub,$invoice_customer_list_id[$i]);
                }else{
                    $id = $invoice_customer_list_model->insertInvoiceCustomerList($data_sub);
                }
                
            }
        }else if($product_id != ""){
            $data_sub = [];
            $data_sub['invoice_customer_id'] = $output;
            $data_sub['product_id'] = $product_id;
            $data_sub['customer_purchase_order_list_id'] = $customer_purchase_order_list_id;
            $data_sub['stock_group_id'] = $stock_group_id;
            $data_sub['stock_date'] = $_POST['invoice_customer_date'];
            $data_sub['invoice_customer_list_product_name'] = $invoice_customer_list_product_name;
            $data_sub['invoice_customer_list_product_detail'] = $invoice_customer_list_product_detail;
            $data_sub['invoice_customer_list_qty'] = (float)filter_var($invoice_customer_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_customer_list_price'] = (float)filter_var($invoice_customer_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_customer_list_total'] = (float)filter_var($invoice_customer_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_customer_list_remark'] = $invoice_customer_list_remark;

            if($invoice_customer_list_id > 0){
                $invoice_customer_list_model->updateInvoiceCustomerListById($data_sub);
            }else{
                $id = $invoice_customer_list_model->insertInvoiceCustomerList($data_sub,$invoice_customer_list_id);
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
        
        
    
}else if($license_sale_page == "Medium" || $license_sale_page == "High" ){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();

    $invoice_customers = $invoice_customer_model->getInvoiceCustomerBy($date_start,$date_end,$customer_id,$keyword);
    $customer_orders = $invoice_customer_model->getCustomerOrder();
    $customer_purchase_orders = $invoice_customer_model->getCustomerPurchaseOrder();
    require_once($path.'view.inc.php');

}





?>