<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/InvoiceCustomerModel.php');
require_once('../models/InvoiceCustomerListModel.php');
require_once('../models/UserModel.php');
require_once('../models/CustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/invoice_customer_begin/views/";

$user_model = new UserModel;
$customer_model = new CustomerModel;
$invoice_customer_model = new InvoiceCustomerModel;
$invoice_customer_list_model = new InvoiceCustomerListModel;
$invoice_customer_id = $_GET['id'];
$vat = 7;
$first_char = "INV";

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;

    $customers=$customer_model->getCustomerBy();
    $invoice_customers = $invoice_customer_model->getInvoiceCustomerBy($date_start,$date_end,$customer_id,$keyword,'','1');  

    $page_max = (int)(count($invoice_customers)/$page_size);
    if(count($invoice_customers)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $invoice_customer_model->getInvoiceCustomerLastID($first_code,3);
 
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();
   
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();

    $invoice_customer = $invoice_customer_model->getInvoiceCustomerByID($invoice_customer_id);

    $customer=$customer_model->getCustomerByID($invoice_customer['customer_id']); 
    $vat = $invoice_customer['invoice_customer_vat'];

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    
    $invoice_customer = $invoice_customer_model->getInvoiceCustomerViewByID($invoice_customer_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    
    $invoice_customer = $invoice_customer_model->getInvoiceCustomerViewByID($invoice_customer_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){
    $invoice_customer_model->deleteInvoiceCustomerById($invoice_customer_id);
?>
    <script>window.location="index.php?app=summit_dedit"</script>
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
        $data['invoice_customer_begin'] = '1';
        $data['addby'] = $user[0][0];

        $output = $invoice_customer_model->insertInvoiceCustomer($data);

        

        if($output > 0){
            
?>
        <script>window.location="index.php?app=summit_dedit&action=update&id=<?php echo $output;?>"</script>
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
        $data['invoice_customer_begin'] = '1';
        $data['updateby'] = $user[0][0];
        
        

        $output = $invoice_customer_model->updateInvoiceCustomerByID($invoice_customer_id,$data);
        

        if($output){
        
?>
        <script>window.location="index.php?app=summit_dedit&action=update&id=<?php echo $invoice_customer_id;?>"</script>
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
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;

    $customers=$customer_model->getCustomerBy();
    $invoice_customers = $invoice_customer_model->getInvoiceCustomerBy($date_start,$date_end,$customer_id,$keyword,'','1');  

    $page_max = (int)(count($invoice_customers)/$page_size);
    if(count($invoice_customers)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view.inc.php');

}





?>