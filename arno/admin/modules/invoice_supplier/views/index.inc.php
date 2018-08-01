<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/InvoiceSupplierModel.php');
require_once('../models/InvoiceSupplierListModel.php');
require_once('../models/PurchaseOrderListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/ExchangeRateBahtModel.php');
require_once('../functions/DateTimeFunction.func.php');
require_once('../models/JournalPurchaseModel.php');
require_once('../models/JournalPurchaseListModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/invoice_supplier/views/";
$user_model = new UserModel;
$supplier_model = new SupplierModel;
$notification_model = new NotificationModel;
$invoice_supplier_model = new InvoiceSupplierModel;
$invoice_supplier_list_model = new InvoiceSupplierListModel;
$purchase_order_list_model = new PurchaseOrderListModel;
$product_model = new ProductModel;
$exchange_rate_baht_model = new ExchangeRateBahtModel;
$date_time_function_model = new DateTimeFunction;
$journal_purchase_model = new JournalPurchaseModel;
$journal_purchase_list_model = new JournalPurchaseListModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('13');

$invoice_supplier_id = $_GET['id'];
$notification_id = $_GET['notification'];
$supplier_id = $_GET['supplier_id'];
$purchase_order_id = $_GET['purchase_order_id'];
$sort = $_GET['sort'];
$vat = 7;
$first_char = "RR";
$stock_group_id = 0;
if(!isset($_GET['action']) && ( $license_purchase_page == "Medium" || $license_purchase_page == "High" )){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();

    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierBy($date_start,$date_end,$supplier_id,$keyword);
    $supplier_orders_in = $invoice_supplier_model->getSupplierOrder("ภายในประเทศ");
    $supplier_orders_out = $invoice_supplier_model->getSupplierOrder("ภายนอกประเทศ");
    $purchase_orders_in = $invoice_supplier_model->getPurchaseOrder("ภายในประเทศ");
    $purchase_orders_out = $invoice_supplier_model->getPurchaseOrder("ภายนอกประเทศ");
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ( $license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    
    $products=$product_model->getProductBy('','','','');
    $suppliers=$supplier_model->getSupplierBy($sort);
    $users=$user_model->getUserBy();

    if($sort == "ภายในประเทศ"){
        $paper = $paper_model->getPaperByID('12');
    }else{
        $paper = $paper_model->getPaperByID('13');
    }
    
    if($supplier_id > 0){
        $supplier=$supplier_model->getSupplierByID($supplier_id);
        $invoice_supplier_lists = $invoice_supplier_model->generateInvoiceSupplierListBySupplierId($supplier_id,"","",$purchase_order_id);
        $suppliers=$supplier_model->getSupplierBy($supplier['supplier_domestic']);

        if($supplier['supplier_domestic'] == "ภายในประเทศ"){
            $paper = $paper_model->getPaperByID('12');
        }else{
            $paper = $paper_model->getPaperByID('13');
        }
    }
    
    $user=$user_model->getUserByID($admin_id);

    $data = [];
    $data['year'] = date("Y");
    $data['month'] = date("m");
    $data['number'] = "0000000000";
    $data['employee_name'] = $user["user_name_en"];
    $data['supplier_code'] = $supplier["supplier_code"];
    
    $code = $code_generate->cut2Array($paper['paper_code'],$data);
    $last_code = "";
    for($i = 0 ; $i < count($code); $i++){
    
        if($code[$i]['type'] == "number"){
            $last_code = $invoice_supplier_model->getInvoiceSupplierLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    } 
   

    $exchange_rate_baht = $exchange_rate_baht_model->getExchangeRateBahtByCurrncyID($date_time_function_model->changeDateFormat($invoice_supplier['invoice_supplier_date_recieve']),$supplier['currency_id']);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ( $license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    $products=$product_model->getProductBy('','','','');
    
    $users=$user_model->getUserBy();

    $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierByID($invoice_supplier_id);

    $supplier=$supplier_model->getSupplierByID($invoice_supplier['supplier_id']);
    $suppliers=$supplier_model->getSupplierBy($supplier['supplier_domestic']);
    $invoice_supplier_lists = $invoice_supplier_list_model->getInvoiceSupplierListBy($invoice_supplier_id);

    $exchange_rate_baht = $exchange_rate_baht_model->getExchangeRateBahtByCurrncyID($date_time_function_model->changeDateFormat($invoice_supplier['invoice_supplier_date_recieve']),$supplier['currency_id']);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierViewByID($invoice_supplier_id);
    $invoice_supplier_lists = $invoice_supplier_list_model->getInvoiceSupplierListBy($invoice_supplier_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'cost'){
    $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierViewByID($invoice_supplier_id);
    $invoice_supplier_lists = $invoice_supplier_list_model->getInvoiceSupplierListBy($invoice_supplier_id);
    $exchange_rate_baht = $exchange_rate_baht_model->getExchangeRateBahtByCurrncyID($date_time_function_model->changeDateFormat($invoice_supplier['invoice_supplier_date_recieve']),$invoice_supplier['currency_id']);
    
    require_once($path.'cost.inc.php');

}else if ($_GET['action'] == 'delete' && ( $license_purchase_page == "High" )){
    $invoice_supplier_model->deleteInvoiceSupplierById($invoice_supplier_id);

    $journal_purchases = $journal_purchase_model->deleteJournalPurchaseByInvoiceSupplierID($invoice_supplier_id);

?>
    <script>window.location="index.php?app=invoice_supplier"</script>
<?php

}else if ($_GET['action'] == 'add' && ( $license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    if(isset($_POST['invoice_supplier_code'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['invoice_supplier_code'] = $_POST['invoice_supplier_code'];
        $data['invoice_supplier_code_gen'] = $_POST['invoice_supplier_code_gen'];

        $data['invoice_supplier_total_price'] = (float)filter_var($_POST['invoice_supplier_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_vat'] = (float)filter_var($_POST['invoice_supplier_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_vat_price'] =(float)filter_var( $_POST['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_net_price'] = (float)filter_var($_POST['invoice_supplier_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);


        $data['invoice_supplier_date'] = $_POST['invoice_supplier_date'];
        $data['invoice_supplier_date_recieve'] = $_POST['invoice_supplier_date_recieve'];
        $data['invoice_supplier_name'] = $_POST['invoice_supplier_name'];
        $data['invoice_supplier_address'] = $_POST['invoice_supplier_address'];
        $data['invoice_supplier_tax'] = $_POST['invoice_supplier_tax'];
        $data['invoice_supplier_term'] = $_POST['invoice_supplier_term'];
        $data['invoice_supplier_due'] = $_POST['invoice_supplier_due']; 
        $data['import_duty'] = (float)filter_var($_POST['import_duty'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['freight_in'] = (float)filter_var($_POST['freight_in'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['addby'] = $admin_id;

        $invoice_supplier_id = $invoice_supplier_model->insertInvoiceSupplier($data);

        if($invoice_supplier_id > 0){

            $data = [];
            $first_code = "HP".date("y").date("m");
            $first_date = date("d")."-".date("m")."-".date("Y");
            $last_code = $journal_purchase_model->getJournalPurchaseLastID($first_code,3);
            $data['invoice_supplier_id'] = $invoice_supplier_id;
            $data['journal_purchase_date'] = $_POST['invoice_supplier_date_recieve'];
            $data['journal_purchase_code'] = $last_code;
            $data['journal_purchase_name'] = "ซื้อเชื่อจาก ".$_POST['invoice_supplier_name']." [".$_POST['invoice_supplier_code']."] ";
            $data['addby'] = $admin_id;
    
    
            $journal_purchase_id = $journal_purchase_model->insertJournalPurchase($data);

            if($journal_purchase_id > 0){

                $supplier=$supplier_model->getSupplierByID($_POST['supplier_id']);
                $data = [];
                $data['journal_purchase_id'] = $journal_purchase_id;
                $data['account_id'] = $supplier['account_id'];
                $data['journal_purchase_list_name'] = "ซื้อเชื่อจาก ".$_POST['invoice_supplier_name']." [".$_POST['invoice_supplier_code']."] ";
                $data['journal_purchase_list_debit'] = (float)filter_var( $_POST['invoice_supplier_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['journal_purchase_list_credit'] = 0;
                $journal_purchase_list_model->insertJournalPurchaseList($data);

                if((float)filter_var( $_POST['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) > 0){
                    $data = [];
                    $data['journal_purchase_id'] = $journal_purchase_id;
                    $data['account_id'] = '42';
                    $data['journal_purchase_list_name'] = "ซื้อเชื่อจาก ".$_POST['invoice_supplier_name']." [".$_POST['invoice_supplier_code']."] ";
                    $data['journal_purchase_list_debit'] = (float)filter_var( $_POST['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['journal_purchase_list_credit'] = 0;
                    $journal_purchase_list_model->insertJournalPurchaseList($data);
                }
            }

            $data = [];
            $product_id = $_POST['product_id'];
            $invoice_supplier_list_product_name = $_POST['invoice_supplier_list_product_name'];
            $invoice_supplier_list_product_detail = $_POST['invoice_supplier_list_product_detail'];
            $invoice_supplier_list_qty = $_POST['invoice_supplier_list_qty'];
            $invoice_supplier_list_cost = $_POST['invoice_supplier_list_cost'];
            $invoice_supplier_list_price = $_POST['invoice_supplier_list_price'];
            $invoice_supplier_list_total = $_POST['invoice_supplier_list_total'];
            $invoice_supplier_list_remark = $_POST['invoice_supplier_list_remark'];
            $purchase_order_list_id = $_POST['purchase_order_list_id'];
            $stock_group_id = $_POST['stock_group_id'];
            
            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data_sub = [];
                    $data_sub['invoice_supplier_id'] = $invoice_supplier_id;
                    $data_sub['product_id'] = $product_id[$i];
                    $data_sub['stock_date'] = $data['invoice_supplier_date_recieve'];
                    
                    $data_sub['invoice_supplier_list_product_name'] = $invoice_supplier_list_product_name[$i];
                    $data_sub['invoice_supplier_list_product_detail'] = $invoice_supplier_list_product_detail[$i];
                    $data_sub['invoice_supplier_list_qty'] = (float)filter_var($invoice_supplier_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_price'] = (float)filter_var($invoice_supplier_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_total'] = (float)filter_var($invoice_supplier_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_cost'] = (float)filter_var($invoice_supplier_list_cost[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark[$i];
                    $data_sub['purchase_order_list_id'] = $purchase_order_list_id[$i];

                    $data_sub['stock_group_id'] = $stock_group_id[$i];

                    //echo "****";
                    $id = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);
                }
            }else if($product_id != ""){
                $data_sub = [];
                $data_sub['invoice_supplier_id'] = $invoice_supplier_id;
                $data_sub['product_id'] = $product_id;
                $data_sub['stock_date'] = $data['invoice_supplier_date_recieve'];
                
                $data_sub['invoice_supplier_list_product_name'] = $invoice_supplier_list_product_name;
                $data_sub['invoice_supplier_list_product_detail'] = $invoice_supplier_list_product_detail;
                $data_sub['invoice_supplier_list_qty'] = (float)filter_var($invoice_supplier_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_price'] = (float)filter_var($invoice_supplier_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_total'] = (float)filter_var($invoice_supplier_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_cost'] = (float)filter_var($invoice_supplier_list_cost, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark;
                $data_sub['purchase_order_list_id'] = $purchase_order_list_id;
                $data_sub['stock_group_id'] = $stock_group_id;
                //echo "----";
                $id = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);
               
            }
            
?>
        <script>window.location="index.php?app=invoice_supplier&action=update&id=<?php echo $invoice_supplier_id;?>"</script>
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
    
}else if ($_GET['action'] == 'edit' && ( $license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    
    if(isset($_POST['invoice_supplier_code'])){
        
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['invoice_supplier_code'] = $_POST['invoice_supplier_code'];
        $data['invoice_supplier_code_gen'] = $_POST['invoice_supplier_code_gen'];
        $data['invoice_supplier_total_price'] = (float)filter_var($_POST['invoice_supplier_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_vat'] = (float)filter_var($_POST['invoice_supplier_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_vat_price'] =(float)filter_var( $_POST['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_net_price'] = (float)filter_var($_POST['invoice_supplier_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_date'] = $_POST['invoice_supplier_date'];
        $data['invoice_supplier_date_recieve'] = $_POST['invoice_supplier_date_recieve'];
        $data['invoice_supplier_name'] = $_POST['invoice_supplier_name'];
        $data['invoice_supplier_address'] = $_POST['invoice_supplier_address'];
        $data['invoice_supplier_tax'] = $_POST['invoice_supplier_tax'];
        $data['invoice_supplier_term'] = $_POST['invoice_supplier_term'];
        $data['invoice_supplier_due'] = $_POST['invoice_supplier_due'];
        $data['import_duty'] = (float)filter_var($_POST['import_duty'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['freight_in'] = (float)filter_var($_POST['freight_in'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['addby'] = $admin_id;

       
       
        $product_id = $_POST['product_id'];
        $invoice_supplier_list_id = $_POST['invoice_supplier_list_id'];
        
        $invoice_supplier_list_product_name = $_POST['invoice_supplier_list_product_name'];
        $invoice_supplier_list_product_detail = $_POST['invoice_supplier_list_product_detail'];
        $invoice_supplier_list_qty = $_POST['invoice_supplier_list_qty'];
        $invoice_supplier_list_price = $_POST['invoice_supplier_list_price'];
        $invoice_supplier_list_total = $_POST['invoice_supplier_list_total'];
        $invoice_supplier_list_cost = $_POST['invoice_supplier_list_cost'];
        $invoice_supplier_list_remark = $_POST['invoice_supplier_list_remark'];
        $purchase_order_list_id = $_POST['purchase_order_list_id'];
        $stock_group_id = $_POST['stock_group_id'];
        
        $invoice_supplier_list_model->deleteInvoiceSupplierListByInvoiceSupplierIDNotIN($invoice_supplier_id,$invoice_supplier_list_id);
        

       
        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data_sub = [];
                $data_sub['invoice_supplier_id'] = $invoice_supplier_id;
                $data_sub['product_id'] = $product_id[$i];
                $data_sub['stock_date'] = $data['invoice_supplier_date_recieve'];
                
                $data_sub['invoice_supplier_list_product_name'] = $invoice_supplier_list_product_name[$i];
                $data_sub['invoice_supplier_list_product_detail'] = $invoice_supplier_list_product_detail[$i];
                $data_sub['invoice_supplier_list_qty'] = (float)filter_var($invoice_supplier_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_price'] = (float)filter_var($invoice_supplier_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_total'] = (float)filter_var($invoice_supplier_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_cost'] = (float)filter_var($invoice_supplier_list_cost[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark[$i];

                $data_sub['stock_group_id'] = $stock_group_id[$i];
                $data_sub['purchase_order_list_id'] = $purchase_order_list_id[$i];
    
                if($invoice_supplier_list_id[$i] != '0' && $invoice_supplier_list_id[$i] != ''){
                    $invoice_supplier_list_model->updateInvoiceSupplierListById($data_sub,$invoice_supplier_list_id[$i]);
                }else{
                    $id = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);
                }
                
            }
        }else if($product_id != ""){
            $data_sub = [];
            $data_sub['invoice_supplier_id'] = $invoice_supplier_id;
            $data_sub['product_id'] = $product_id;
            $data_sub['stock_date'] = $data['invoice_supplier_date_recieve'];

            $data_sub['invoice_supplier_list_product_name'] = $invoice_supplier_list_product_name;
            $data_sub['invoice_supplier_list_product_detail'] = $invoice_supplier_list_product_detail;
            $data_sub['invoice_supplier_list_qty'] = (float)filter_var($invoice_supplier_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_price'] = (float)filter_var($invoice_supplier_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_total'] = (float)filter_var($invoice_supplier_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_cost'] = (float)filter_var($invoice_supplier_list_cost, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark;

            $data_sub['stock_group_id'] = $stock_group_id;
            $data_sub['purchase_order_list_id'] = $purchase_order_list_id;

            if($invoice_supplier_list_id != '0' && $invoice_supplier_list_id != ''){
                $invoice_supplier_list_model->updateInvoiceSupplierListById($data_sub,$invoice_supplier_list_id);
            }else{
                $id = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);
            }
        }

        $output = $invoice_supplier_model->updateInvoiceSupplierByID($invoice_supplier_id,$data);
        

        if($output){
        
?>
        <script>window.location="index.php?app=invoice_supplier&action=update&id=<?php echo $invoice_supplier_id;?>"</script>
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
        
        
    
}else if ($_GET['action'] == 'edit_cost' && ( $license_purchase_page == "Medium" || $license_purchase_page == "High" )){
    

    $invoice_supplier_list_id = $_POST['invoice_supplier_list_id'];
    $invoice_supplier_list_duty_percent = $_POST['invoice_supplier_list_duty_percent'];
    
    if(is_array($invoice_supplier_list_id)){
        for($i=0; $i < count($invoice_supplier_list_id) ; $i++){
            $invoice_supplier_list_model->updateDutyPercentListById($invoice_supplier_list_duty_percent[$i],$invoice_supplier_list_id[$i]); 
        }
    }else if($invoice_supplier_list_id != ""){
        
            $invoice_supplier_list_model->updateDutyPercentListById($invoice_supplier_list_duty_percent,$invoice_supplier_list_id);
        
    }
?>
        <script>window.location="index.php?app=invoice_supplier&action=cost&id=<?php echo $invoice_supplier_id;?>"</script>
<?php

    
}else if ( $license_purchase_page == "Medium" || $license_purchase_page == "High" ){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();

    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierBy($date_start,$date_end,$supplier_id,$keyword);
    $supplier_orders_in = $invoice_supplier_model->getSupplierOrder("ภายในประเทศ");
    $supplier_orders_out = $invoice_supplier_model->getSupplierOrder("ภายนอกประเทศ");
    $purchase_orders_in = $invoice_supplier_model->getPurchaseOrder("ภายในประเทศ");
    $purchase_orders_out = $invoice_supplier_model->getPurchaseOrder("ภายนอกประเทศ");
    require_once($path.'view.inc.php');

}





?>