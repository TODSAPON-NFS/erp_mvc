<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/DebitNoteModel.php');
require_once('../models/DebitNoteListModel.php');
require_once('../models/CustomerPurchaseOrderListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/InvoiceCustomerModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');


date_default_timezone_set('asia/bangkok');

$path = "modules/debit_note/views/";
$user_model = new UserModel;
$customer_model = new CustomerModel;
$invoice_customer_model = new InvoiceCustomerModel;
$notification_model = new NotificationModel;
$debit_note_model = new DebitNoteModel;
$debit_note_list_model = new DebitNoteListModel;
$customer_purchase_order_list_model = new CustomerPurchaseOrderListModel;
$product_model = new ProductModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('18');


$debit_note_id = $_GET['id'];
$notification_id = $_GET['notification'];
$customer_id = $_GET['customer_id'];
$vat = 7; 

if($license_account_page == "Medium" || $license_account_page == "High"){
    $lock_1 = "1";
}else{
    $lock_1 = "0";
}

if($license_account_page == "Medium" || $license_account_page == "High"){
    $lock_2 = "1";
}else{
    $lock_2 = "0";
}

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

    $customers=$customer_model->getCustomerBy();

    $debit_notes = $debit_note_model->getDebitNoteBy($date_start,$date_end,$customer_id,$keyword,'',$lock_1,$lock_2);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){ 

    $products=$product_model->getProductBy('','','','Active');
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();
   

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
            $last_code = $debit_note_model->getDebitNoteLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
    $first_date = date("d")."-".date("m")."-".date("Y");


    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    $products=$product_model->getProductBy('','','','Active');
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();

    $debit_note = $debit_note_model->getDebitNoteByID($debit_note_id);

    $customer=$customer_model->getCustomerByID($debit_note['customer_id']);
    $invoice_customers=$invoice_customer_model->getInvoiceCustomerByCustomerID($debit_note['customer_id']);
    $invoice_customer=$invoice_customer_model->getInvoiceCustomerByID($debit_note['invoice_customer_id']);
    $debit_note_lists = $debit_note_list_model->getDebitNoteListBy($debit_note_id);

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    
    $debit_note = $debit_note_model->getDebitNoteViewByID($debit_note_id);
    $debit_note_lists = $debit_note_list_model->getDebitNoteListBy($debit_note_id);
    
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    
    $debit_note = $debit_note_model->getDebitNoteViewByID($debit_note_id);
    $debit_note_lists = $debit_note_list_model->getDebitNoteListBy($debit_note_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete' && ( $license_sale_page == "High" ) ){
    $debit_note_model->deleteDebitNoteById($debit_note_id);
?>
    <script>window.location="index.php?app=debit_note"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    if(isset($_POST['debit_note_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['invoice_customer_id'] = $_POST['invoice_customer_id'];
        $data['debit_note_code'] = $_POST['debit_note_code'];
        $data['debit_note_total_old'] = (float)filter_var($_POST['debit_note_total_old'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['debit_note_total'] = (float)filter_var($_POST['debit_note_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['debit_note_total_price'] = (float)filter_var($_POST['debit_note_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['debit_note_vat'] = (float)filter_var($_POST['debit_note_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['debit_note_vat_price'] =(float)filter_var( $_POST['debit_note_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['debit_note_net_price'] = (float)filter_var($_POST['debit_note_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['debit_note_date'] = $_POST['debit_note_date'];
        $data['debit_note_remark'] = $_POST['debit_note_remark'];
        $data['debit_note_name'] = $_POST['debit_note_name'];
        $data['debit_note_address'] = $_POST['debit_note_address'];
        $data['debit_note_tax'] = $_POST['debit_note_tax'];
        $data['debit_note_branch'] = $_POST['debit_note_branch'];
        $data['debit_note_term'] = $_POST['debit_note_term'];
        $data['debit_note_due'] = $_POST['debit_note_due'];
        $data['addby'] = $user[0][0];

        $debit_note_id = $debit_note_model->insertDebitNote($data);

        
        if($debit_note_id > 0){
            $data = [];
            $product_id = $_POST['product_id'];
            $stock_group_id = $_POST['stock_group_id'];
            $invoice_customer_list_id = $_POST['invoice_customer_list_id'];
            $debit_note_list_product_name = $_POST['debit_note_list_product_name'];
            $debit_note_list_product_detail = $_POST['debit_note_list_product_detail'];
            $debit_note_list_qty = $_POST['debit_note_list_qty'];
            $debit_note_list_price = $_POST['debit_note_list_price'];
            $debit_note_list_total = $_POST['debit_note_list_total'];
            $debit_note_list_remark = $_POST['debit_note_list_remark'];

            
           
            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data_sub = [];
                    $data_sub['debit_note_id'] = $debit_note_id;
                    $data_sub['product_id'] = $product_id[$i];
                    $data_sub['stock_group_id'] = $stock_group_id[$i];
                    $data_sub['invoice_customer_list_id'] = $invoice_customer_list_id[$i];
                    $data_sub['debit_note_list_product_name'] = $debit_note_list_product_name[$i];
                    $data_sub['debit_note_list_product_detail'] = $debit_note_list_product_detail[$i];
                    $data_sub['debit_note_list_qty'] = (float)filter_var($debit_note_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['debit_note_list_price'] = (float)filter_var($debit_note_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['debit_note_list_total'] = (float)filter_var($debit_note_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['debit_note_list_remark'] = $debit_note_list_remark[$i];

                    $id = $debit_note_list_model->insertDebitNoteList($data_sub);
                }
            }else if($product_id != ""){
                $data_sub = [];
                $data_sub['debit_note_id'] = $debit_note_id;
                $data_sub['product_id'] = $product_id;
                $data_sub['stock_group_id'] = $stock_group_id;
                $data_sub['invoice_customer_list_id'] = $invoice_customer_list_id;
                $data_sub['debit_note_list_product_name'] = $debit_note_list_product_name;
                $data_sub['debit_note_list_product_detail'] = $debit_note_list_product_detail;
                $data_sub['debit_note_list_qty'] = (float)filter_var($debit_note_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['debit_note_list_price'] = (float)filter_var($debit_note_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['debit_note_list_total'] = (float)filter_var($debit_note_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['debit_note_list_remark'] = $debit_note_list_remark;
    
                $id = $debit_note_list_model->insertDebitNoteList($data_sub);
            }

?>
        <script>window.location="index.php?app=debit_note&action=update&id=<?php echo $debit_note_id;?>"</script>
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
    
}else if ($_GET['action'] == 'edit' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    
    if(isset($_POST['debit_note_code'])){
        
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['invoice_customer_id'] = $_POST['invoice_customer_id'];
        $data['debit_note_code'] = $_POST['debit_note_code'];
        $data['debit_note_total_old'] = (float)filter_var($_POST['debit_note_total_old'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['debit_note_total'] = (float)filter_var($_POST['debit_note_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['debit_note_total_price'] = (double)filter_var($_POST['debit_note_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['debit_note_vat'] = (double)filter_var($_POST['debit_note_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['debit_note_vat_price'] =(double)filter_var( $_POST['debit_note_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['debit_note_net_price'] = (double)filter_var($_POST['debit_note_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['debit_note_date'] = $_POST['debit_note_date'];
        $data['debit_note_remark'] = $_POST['debit_note_remark'];
        $data['debit_note_name'] = $_POST['debit_note_name'];
        $data['debit_note_address'] = $_POST['debit_note_address'];
        $data['debit_note_tax'] = $_POST['debit_note_tax'];
        $data['debit_note_branch'] = $_POST['debit_note_branch'];
        $data['debit_note_term'] = $_POST['debit_note_term'];
        $data['debit_note_due'] = $_POST['debit_note_due'];
        $data['addby'] = $user[0][0];


        $product_id = $_POST['product_id'];
        $debit_note_list_id = $_POST['debit_note_list_id'];
        $stock_group_id = $_POST['stock_group_id'];
        $invoice_customer_list_id = $_POST['invoice_customer_list_id'];
        $debit_note_list_product_name = $_POST['debit_note_list_product_name'];
        $debit_note_list_product_detail = $_POST['debit_note_list_product_detail'];
        $debit_note_list_qty = $_POST['debit_note_list_qty'];
        $debit_note_list_price = $_POST['debit_note_list_price'];
        $debit_note_list_total = $_POST['debit_note_list_total'];
        $debit_note_list_remark = $_POST['debit_note_list_remark'];

        
        $debit_note_list_model->deleteDebitNoteListByDebitNoteIDNotIN($debit_note_id,$debit_note_list_id);
        
        

        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data_sub = [];
                $data_sub['debit_note_id'] = $debit_note_id;
                $data_sub['product_id'] = $product_id[$i];
                $data_sub['stock_group_id'] = $stock_group_id[$i];
                $data_sub['invoice_customer_list_id'] = $invoice_customer_list_id[$i];
                $data_sub['debit_note_list_product_name'] = $debit_note_list_product_name[$i];
                $data_sub['debit_note_list_product_detail'] = $debit_note_list_product_detail[$i];
                $data_sub['debit_note_list_qty'] = (float)filter_var($debit_note_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['debit_note_list_price'] = (float)filter_var($debit_note_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['debit_note_list_total'] = (float)filter_var($debit_note_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['debit_note_list_remark'] = $debit_note_list_remark[$i];

                if($debit_note_list_id[$i] != '0'){
                    $debit_note_list_model->updateDebitNoteListById($data_sub,$debit_note_list_id[$i]);
                }else{
                    $id = $debit_note_list_model->insertDebitNoteList($data_sub);
                }
                
            }
        }else if($product_id != ""){
            $data_sub = [];
            $data_sub['debit_note_id'] = $debit_note_id;
            $data_sub['product_id'] = $product_id;
            $data_sub['stock_group_id'] = $stock_group_id;
            $data_sub['invoice_customer_list_id'] = $invoice_customer_list_id;
            $data_sub['debit_note_list_product_name'] = $debit_note_list_product_name;
            $data_sub['debit_note_list_product_detail'] = $debit_note_list_product_detail;
            $data_sub['debit_note_list_qty'] = (float)filter_var($debit_note_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['debit_note_list_price'] = (float)filter_var($debit_note_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['debit_note_list_total'] = (float)filter_var($debit_note_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['debit_note_list_remark'] = $debit_note_list_remark;

            if($debit_note_list_id != "0"){
                $debit_note_list_model->updateDebitNoteListById($data_sub,$debit_note_list_id);
            }else{
                $id = $debit_note_list_model->insertDebitNoteList($data_sub);
            }
        }

        $output = $debit_note_model->updateDebitNoteByID($debit_note_id,$data);
        

        if($output){
        
?>
        <script>window.location="index.php?app=debit_note&action=update&id=<?php echo $debit_note_id;?>"</script>
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
        
        
    
}else  if($license_sale_page == "Medium" || $license_sale_page == "High" ) {

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

    $debit_notes = $debit_note_model->getDebitNoteBy($date_start,$date_end,$customer_id,$keyword,'',$lock_1,$lock_2);
    require_once($path.'view.inc.php');

}





?>