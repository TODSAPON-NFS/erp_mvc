<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/InvoiceSupplierModel.php');
require_once('../models/InvoiceSupplierListModel.php');
require_once('../models/UserModel.php');
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/invoice_supplier_begin/views/";

$user_model = new UserModel;
$supplier_model = new SupplierModel;
$invoice_supplier_model = new InvoiceSupplierModel;
$invoice_supplier_list_model = new InvoiceSupplierListModel;
$invoice_supplier_id = $_GET['id'];
$vat = 7;
$first_char = "INV";

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();
    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierBy($date_start,$date_end,$supplier_id,$keyword,$user[0][0],'1');  
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $invoice_supplier_model->getInvoiceSupplierLastID($first_code,3);
 
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
   
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();

    $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierByID($invoice_supplier_id);

    $supplier=$supplier_model->getSupplierByID($invoice_supplier['supplier_id']); 
    $vat = $invoice_supplier['invoice_supplier_vat'];

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    
    $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierViewByID($invoice_supplier_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    
    $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierViewByID($invoice_supplier_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){
    $invoice_supplier_model->deleteInvoiceSupplierById($invoice_supplier_id);
?>
    <script>window.location="index.php?app=summit_credit"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['invoice_supplier_code'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['invoice_supplier_code'] = $_POST['invoice_supplier_code'];
        $data['invoice_supplier_code_gen'] = $_POST['invoice_supplier_code_gen'];
        $data['invoice_supplier_code'] = $_POST['invoice_supplier_code'];
        $data['invoice_supplier_date_recieve'] = $_POST['invoice_supplier_date_recieve'];
        $data['invoice_supplier_total_price'] = (float)filter_var($_POST['invoice_supplier_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_vat'] = (float)filter_var($_POST['invoice_supplier_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_vat_price'] =(float)filter_var( $_POST['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_net_price'] = (float)filter_var($_POST['invoice_supplier_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_date'] = $_POST['invoice_supplier_date'];
        $data['invoice_supplier_name'] = $_POST['invoice_supplier_name'];
        $data['invoice_supplier_address'] = $_POST['invoice_supplier_address'];
        $data['invoice_supplier_tax'] = $_POST['invoice_supplier_tax'];
        $data['invoice_supplier_term'] = $_POST['invoice_supplier_term'];
        $data['invoice_supplier_due'] = $_POST['invoice_supplier_due'];
        $data['invoice_supplier_begin'] = '1';
        $data['addby'] = $user[0][0];

        $output = $invoice_supplier_model->insertInvoiceSupplier($data);

        

        if($output > 0){
            
?>
        <script>window.location="index.php?app=summit_credit&action=update&id=<?php echo $output;?>"</script>
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
        $data['invoice_supplier_code'] = $_POST['invoice_supplier_code'];
        $data['invoice_supplier_date_recieve'] = $_POST['invoice_supplier_date_recieve'];
        $data['invoice_supplier_total_price'] = (double)filter_var($_POST['invoice_supplier_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_vat'] = (double)filter_var($_POST['invoice_supplier_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_vat_price'] =(double)filter_var( $_POST['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_net_price'] = (double)filter_var($_POST['invoice_supplier_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_date'] = $_POST['invoice_supplier_date'];
        $data['invoice_supplier_name'] = $_POST['invoice_supplier_name'];
        $data['invoice_supplier_address'] = $_POST['invoice_supplier_address'];
        $data['invoice_supplier_tax'] = $_POST['invoice_supplier_tax'];
        $data['invoice_supplier_term'] = $_POST['invoice_supplier_term'];
        $data['invoice_supplier_due'] = $_POST['invoice_supplier_due'];
        $data['invoice_supplier_begin'] = '1';
        $data['updateby'] = $user[0][0];
        
        

        $output = $invoice_supplier_model->updateInvoiceSupplierByID($invoice_supplier_id,$data);
        

        if($output){
        
?>
        <script>window.location="index.php?app=summit_credit&action=update&id=<?php echo $invoice_supplier_id;?>"</script>
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

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();
    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierBy($date_start,$date_end,$supplier_id,$keyword,$user[0][0],'1');  
    require_once($path.'view.inc.php');

}





?>