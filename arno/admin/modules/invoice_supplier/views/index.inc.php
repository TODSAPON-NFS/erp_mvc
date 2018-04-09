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
$invoice_supplier_id = $_GET['id'];
$notification_id = $_GET['notification'];
$supplier_id = $_GET['supplier_id'];
$purchase_order_id = $_GET['purchase_order_id'];
$sort = $_GET['sort'];
$vat = 7;
$first_char = "RR";
$stock_group_id = 0;
if(!isset($_GET['action'])){

    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierBy();
    $supplier_orders_in = $invoice_supplier_model->getSupplierOrder("ภายในประเทศ");
    $supplier_orders_out = $invoice_supplier_model->getSupplierOrder("ภายนอกประเทศ");
    $purchase_orders_in = $invoice_supplier_model->getPurchaseOrder("ภายในประเทศ");
    $purchase_orders_out = $invoice_supplier_model->getPurchaseOrder("ภายนอกประเทศ");
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    
    $products=$product_model->getProductBy('','','','Active');
    $suppliers=$supplier_model->getSupplierBy($sort);
    $users=$user_model->getUserBy();

    if($sort == "ภายในประเทศ"){
        $first_char = "RR";
    }else{
        $first_char = "RF";
    }
    
    if($supplier_id > 0){
        $supplier=$supplier_model->getSupplierByID($supplier_id);
        $invoice_supplier_lists = $invoice_supplier_model->generateInvoiceSupplierListBySupplierId($supplier_id,"","",$purchase_order_id);
        $suppliers=$supplier_model->getSupplierBy($supplier['supplier_domestic']);
        if($supplier['supplier_domestic'] == "ภายในประเทศ"){
            $first_char = "RR";
        }else{
            $first_char = "RF";
        }
    }
    
    
    
    $first_code = $first_char.date("y").date("m");
    $last_code = $invoice_supplier_model->getInvoiceSupplierLastID($first_code,3);
   

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $products=$product_model->getProductBy('','','','Active');
    
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

}else if ($_GET['action'] == 'delete'){
    $invoice_supplier_model->deleteInvoiceSupplierById($invoice_supplier_id);
?>
    <script>window.location="index.php?app=invoice_supplier"</script>
<?php

}else if ($_GET['action'] == 'add'){
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
        $data['import_duty'] = $_POST['import_duty'];
        $data['freight_in'] = $_POST['freight_in'];
        $data['addby'] = $user[0][0];

        $output = $invoice_supplier_model->insertInvoiceSupplier($data);

        if($output > 0){
            $data = [];
            $product_id = $_POST['product_id'];
            $invoice_supplier_list_product_name = $_POST['invoice_supplier_list_product_name'];
            $invoice_supplier_list_product_detail = $_POST['invoice_supplier_list_product_detail'];
            $invoice_supplier_list_qty = $_POST['invoice_supplier_list_qty'];
            $invoice_supplier_list_price = $_POST['invoice_supplier_list_price'];
            $invoice_supplier_list_total = $_POST['invoice_supplier_list_total'];
            $invoice_supplier_list_remark = $_POST['invoice_supplier_list_remark'];
            $purchase_order_list_id = $_POST['purchase_order_list_id'];
            $stock_group_id = $_POST['stock_group_id'];
            
            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data_sub = [];
                    $data_sub['invoice_supplier_id'] = $output;
                    $data_sub['product_id'] = $product_id[$i];
                    $data_sub['stock_date'] = $data['invoice_supplier_date_recieve'];
                    
                    $data_sub['invoice_supplier_list_product_name'] = $invoice_supplier_list_product_name[$i];
                    $data_sub['invoice_supplier_list_product_detail'] = $invoice_supplier_list_product_detail[$i];
                    $data_sub['invoice_supplier_list_qty'] = (float)filter_var($invoice_supplier_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_price'] = (float)filter_var($invoice_supplier_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_total'] = (float)filter_var($invoice_supplier_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark[$i];
                    $data_sub['purchase_order_list_id'] = $purchase_order_list_id[$i];
                    $data_sub['stock_group_id'] = $stock_group_id[$i];

                    //echo "****";
                    $id = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);
                }
            }else if($product_id != ""){
                $data_sub = [];
                $data_sub['invoice_supplier_id'] = $output;
                $data_sub['product_id'] = $product_id;
                $data_sub['stock_date'] = $data['invoice_supplier_date_recieve'];
                
                $data_sub['invoice_supplier_list_product_name'] = $invoice_supplier_list_product_name;
                $data_sub['invoice_supplier_list_product_detail'] = $invoice_supplier_list_product_detail;
                $data_sub['invoice_supplier_list_qty'] = (float)filter_var($invoice_supplier_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_price'] = (float)filter_var($invoice_supplier_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_total'] = (float)filter_var($invoice_supplier_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark;
                $data_sub['purchase_order_list_id'] = $purchase_order_list_id;
                $data_sub['stock_group_id'] = $stock_group_id;
                //echo "----";
                $id = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);
               
            }
            
?>
        <script>window.location="index.php?app=invoice_supplier&action=update&id=<?php echo $output;?>"</script>
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
        $data['import_duty'] = $_POST['import_duty'];
        $data['freight_in'] = $_POST['freight_in'];
        $data['addby'] = $user[0][0];

       
       
        $product_id = $_POST['product_id'];
        $invoice_supplier_list_id = $_POST['invoice_supplier_list_id'];
        
        $invoice_supplier_list_product_name = $_POST['invoice_supplier_list_product_name'];
        $invoice_supplier_list_product_detail = $_POST['invoice_supplier_list_product_detail'];
        $invoice_supplier_list_qty = $_POST['invoice_supplier_list_qty'];
        $invoice_supplier_list_price = $_POST['invoice_supplier_list_price'];
        $invoice_supplier_list_total = $_POST['invoice_supplier_list_total'];
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
        
        
    
}else{

    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierBy();
    $supplier_orders_in = $invoice_supplier_model->getSupplierOrder("ภายในประเทศ");
    $supplier_orders_out = $invoice_supplier_model->getSupplierOrder("ภายนอกประเทศ");
    $purchase_orders_in = $invoice_supplier_model->getPurchaseOrder("ภายในประเทศ");
    $purchase_orders_out = $invoice_supplier_model->getPurchaseOrder("ภายนอกประเทศ");
    require_once($path.'view.inc.php');

}





?>