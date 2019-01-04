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
require_once('../models/ProductCustomerPriceModel.php');
require_once('../models/StockGroupModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/AccountSettingModel.php');

require_once('../models/JournalSaleModel.php');
require_once('../models/JournalSaleListModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');
require_once('../models/MaintenanceSaleModel.php');

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
$product_customer_price_model = new ProductCustomerPriceModel;
$stock_group_model = new StockGroupModel;
$journal_sale_model = new JournalSaleModel;
$journal_sale_list_model = new JournalSaleListModel;
$account_setting_model = new AccountSettingModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;
$maintenance_model = new MaintenanceSaleModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('16');

$invoice_customer_id = $_GET['id'];
$notification_id = $_GET['notification'];
$customer_id = $_GET['customer_id'];
$customer_purchase_order_id = $_GET['customer_purchase_order_id'];
$vat = 7;

if($license_sale_page == "Medium" || $license_sale_page == "High"){
    $lock_1 = "1";
}else{
    $lock_1 = "0";
}

if($license_account_page == "Medium" || $license_account_page == "High"){
    $lock_2 = "1";
}else{
    $lock_2 = "0";
}


if(!isset($_GET['action']) && ($license_sale_page == "Medium" || $license_sale_page == "High" )){
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


    $customers=$customer_model->getCustomerBy();

    $invoice_customers = $invoice_customer_model->getInvoiceCustomerBy($date_start,$date_end,$customer_id,$keyword,"","0",$lock_1,$lock_2);
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
        if($_GET['open-type'] == 'ready'){
            $invoice_customer_lists = $invoice_customer_model->getCustomerPurchaseOrderStock($customer_id,'','',$customer_purchase_order_id); 
        }else{
            $invoice_customer_lists = $invoice_customer_model->generateInvoiceCustomerListByCustomerId($customer_id,'','',$customer_purchase_order_id); 
        }
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

    $invoice_customers = $invoice_customer_model->getInvoiceCustomerBy("","","","","","0",$lock_1,$lock_2);

    for($i = 0 ; $i < count($invoice_customers) ; $i++){
        if($invoice_customer_id == $invoice_customers[$i]['invoice_customer_id']){ 
            $previous_id = $invoice_customers[$i-1]['invoice_customer_id'];
            $previous_code = $invoice_customers[$i-1]['invoice_customer_code'];
            $next_id = $invoice_customers[$i+1]['invoice_customer_id'];
            $next_code = $invoice_customers[$i+1]['invoice_customer_code'];

        }
    }

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    
    $invoice_customer = $invoice_customer_model->getInvoiceCustomerViewByID($invoice_customer_id);
    $customer=$customer_model->getCustomerByID($invoice_customer['customer_id']);
    $invoice_customer_lists = $invoice_customer_list_model->getInvoiceCustomerListBy($invoice_customer_id);
    require_once($path.'detail.inc.php');

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
        $data['invoice_customer_branch'] = $_POST['invoice_customer_branch'];
        $data['invoice_customer_address'] = $_POST['invoice_customer_address'];
        $data['invoice_customer_tax'] = $_POST['invoice_customer_tax'];
        $data['invoice_customer_term'] = $_POST['invoice_customer_term'];
        $data['invoice_customer_due'] = $_POST['invoice_customer_due'];
        $data['invoice_customer_due_day'] = $_POST['invoice_customer_due_day'];
        $data['invoice_customer_begin'] = $_POST['invoice_customer_begin'];
        $data['addby'] = $admin_id;
        $data['updateby'] = $admin_id;

        $invoice_customer_id = $invoice_customer_model->insertInvoiceCustomer($data);
        $invoice_customer = $data;
        $invoice_customer['invoice_customer_id'] = $invoice_customer_id;

        $journal_list = [];

        if($invoice_customer_id > 0){ 
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
                    $data_sub['invoice_customer_list_no'] = $i;
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


                    $product = $product_model->getProductByID( $product_id[$i] );

                    $has_account = false;
                    for($ii = 0 ; $ii < count($journal_list); $ii++){
                        if($journal_list[$ii]['account_id'] == $product['sale_account_id']){
                            $has_account = true;
                            $journal_list[$ii]['invoice_customer_list_total'] += $data_sub['invoice_customer_list_total'];
                            break;
                        }
                    }

                    if($has_account == false){
                        $journal_list[] = array (
                            "account_id"=>$product['sale_account_id'], 
                            "invoice_customer_list_total"=>$data_sub['invoice_customer_list_total'] 
                        ); 
                    } 
                }
            }else if($product_id != ""){
                $data_sub = [];
                $data_sub['invoice_customer_id'] = $invoice_customer_id;
                $data_sub['product_id'] = $product_id;
                $data_sub['invoice_customer_list_no'] = 0;
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

                $product = $product_model->getProductByID( $product_id );
                $journal_list[] = array (
                    "account_id"=>$product['sale_account_id'], 
                    "invoice_customer_list_total"=>$data_sub['invoice_customer_list_total'] 
                ); 
            }


            //account setting id = 15 ภาษีขาย --> [2135-00] ภาษีขาย
            $account_vat_sale = $account_setting_model->getAccountSettingByID(15);
                

            //account setting id = 19 ขายเชื่อ --> [4100-01] รายได้-ขายอะไหล่ชิ้นส่วน
            $account_sale = $account_setting_model->getAccountSettingByID(19);

            
            $customer=$customer_model->getCustomerByID($_POST['customer_id']);
            $account_customer = $customer['account_id'];

            $maintenance_model->updateJournal($invoice_customer,$journal_list, $account_customer, $account_vat_sale['account_id'],$account_sale['account_id']);


            $save_product_price = $_POST['save_product_price'];
            for($i=0; $i < count($save_product_price); $i++){
                $product_price = 0;
                for($j=0; $j < count($product_id); $j++){
                    if($product_id[$j] == $save_product_price[$i]){
                        $product_price = (float)filter_var($invoice_customer_list_price[$j], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    }
                }
                $product_customer_prices =  $product_customer_price_model->getProductCustomerPriceByID($save_product_price[$i],$_POST['customer_id']);

                $data = [];
                $data['product_id'] = $save_product_price[$i];
                $data['customer_id'] =$_POST['customer_id'];
                $data['product_price'] = $product_price;

                if(count($product_customer_prices) > 0){ 
                    $product_customer_price_model->updateProductCustomerPriceByID($data);
                }else{
                    $product_customer_price_model->insertProductCustomerPrice($data);
                }
            }
            

            
?>
        <script>
             
            window.location="index.php?app=invoice_customer&action=update&id=<?php echo $invoice_customer_id;?>";
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
        $data['invoice_customer_branch'] = $_POST['invoice_customer_branch'];
        $data['invoice_customer_address'] = $_POST['invoice_customer_address'];
        $data['invoice_customer_tax'] = $_POST['invoice_customer_tax'];
        $data['invoice_customer_term'] = $_POST['invoice_customer_term'];
        $data['invoice_customer_due'] = $_POST['invoice_customer_due'];
        $data['invoice_customer_due_day'] = $_POST['invoice_customer_due_day'];
        $data['invoice_customer_close'] = $_POST['invoice_customer_close'];
        $data['invoice_customer_begin'] = $_POST['invoice_customer_begin'];
        $data['addby'] = $admin_id;
        $data['updateby'] = $admin_id;

        $invoice_customer = $data;
        $invoice_customer['invoice_customer_id'] = $invoice_customer_id;


        $product_id = $_POST['product_id'];
        $invoice_customer_list_id = $_POST['invoice_customer_list_id'];
        $customer_purchase_order_list_id = $_POST['customer_purchase_order_list_id'];
        $stock_group_id = $_POST['stock_group_id'];
        $old_cost = $_POST['old_cost'];
        $old_qty = $_POST['old_qty'];
        $invoice_customer_list_product_name = $_POST['invoice_customer_list_product_name'];
        $invoice_customer_list_product_detail = $_POST['invoice_customer_list_product_detail'];
        $invoice_customer_list_qty = $_POST['invoice_customer_list_qty'];
        $invoice_customer_list_price = $_POST['invoice_customer_list_price'];
        $invoice_customer_list_total = $_POST['invoice_customer_list_total'];
        $invoice_customer_list_remark = $_POST['invoice_customer_list_remark'];

        $invoice_customer_list_model->deleteInvoiceCustomerListByInvoiceCustomerIDNotIN($invoice_customer_id,$invoice_customer_list_id);

        $journal_list = [];
        
        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data_sub = [];
                $data_sub['invoice_customer_id'] = $invoice_customer_id;
                $data_sub['invoice_customer_list_no'] = $i;
                $data_sub['product_id'] = $product_id[$i];
                $data_sub['customer_purchase_order_list_id'] = $customer_purchase_order_list_id[$i];
                $data_sub['stock_group_id'] = $stock_group_id[$i];
                $data_sub['stock_date'] = $_POST['invoice_customer_date'];
                $data_sub['old_cost'] = $old_cost[$i];
                $data_sub['old_qty'] = $old_qty[$i];
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

                $product = $product_model->getProductByID( $product_id[$i] );

                $has_account = false;
                for($ii = 0 ; $ii < count($journal_list); $ii++){
                    if($journal_list[$ii]['account_id'] == $product['sale_account_id']){
                        $has_account = true;
                        $journal_list[$ii]['invoice_customer_list_total'] += $data_sub['invoice_customer_list_total'];
                        break;
                    }
                }

                if($has_account == false){
                    $journal_list[] = array (
                        "account_id"=>$product['sale_account_id'], 
                        "invoice_customer_list_total"=>$data_sub['invoice_customer_list_total'] 
                    ); 
                } 
            
            }
        }else if($product_id != ""){
            $data_sub = [];
            $data_sub['invoice_customer_id'] = $invoice_customer_id;
            $data_sub['invoice_customer_list_no'] = 0;
            $data_sub['product_id'] = $product_id;
            $data_sub['customer_purchase_order_list_id'] = $customer_purchase_order_list_id;
            $data_sub['stock_group_id'] = $stock_group_id;
            $data_sub['stock_date'] = $_POST['invoice_customer_date'];
            $data_sub['old_cost'] = $old_cost;
            $data_sub['old_qty'] = $old_qty;
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

            $product = $product_model->getProductByID( $product_id );
            $journal_list[] = array (
                "account_id"=>$product['sale_account_id'], 
                "invoice_customer_list_total"=>$data_sub['invoice_customer_list_total'] 
            ); 
        }


        $output = $invoice_customer_model->updateInvoiceCustomerByID($invoice_customer_id,$data);
        

       //account setting id = 15 ภาษีขาย --> [2135-00] ภาษีขาย
       $account_vat_sale = $account_setting_model->getAccountSettingByID(15);
        
       if($data["invoice_customer_begin"] == "3"){
            //account setting id = 19 ขายเชื่อ --> [4100-01] รายได้-ขายอะไหล่ชิ้นส่วน
            $account_sale = $account_setting_model->getAccountSettingByID(19);
       }else{
            //account setting id = 11 ขายเชื่อ --> [4100-01] รายได้-ขายอะไหล่ชิ้นส่วน
            $account_sale = $account_setting_model->getAccountSettingByID(11);
       }
       

       $customer=$customer_model->getCustomerByID($_POST['customer_id']);
       $account_customer = $customer['account_id'];

       $maintenance_model->updateJournal($invoice_customer,$journal_list, $account_customer, $account_vat_sale['account_id'],$account_sale['account_id']);

        
       
       $save_product_price = $_POST['save_product_price'];
       for($i=0; $i < count($save_product_price); $i++){
           $product_price = 0;
           for($j=0; $j < count($product_id); $j++){
               if($product_id[$j] == $save_product_price[$i]){
                   $product_price = (float)filter_var($invoice_customer_list_price[$j], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
               }
           }
           $product_customer_prices =  $product_customer_price_model->getProductCustomerPriceByID($save_product_price[$i],$_POST['customer_id']);

           $data = [];
           $data['product_id'] = $save_product_price[$i];
           $data['customer_id'] =$_POST['customer_id'];
           $data['product_price'] = $product_price;

           if(count($product_customer_prices) > 0){ 
               $product_customer_price_model->updateProductCustomerPriceByID($data);
           }else{
               $product_customer_price_model->insertProductCustomerPrice($data);
           }
       }

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

    $customer_id = $_GET['customer_id'];
    
    $customers=$customer_model->getCustomerBy();

    $invoice_customers = $invoice_customer_model->getInvoiceCustomerBy($date_start,$date_end,$customer_id,$keyword,"","0",$lock_1,$lock_2);
    $customer_orders = $invoice_customer_model->getCustomerOrder();
    $customer_purchase_orders = $invoice_customer_model->getCustomerPurchaseOrder();
    require_once($path.'view.inc.php');

}





?>