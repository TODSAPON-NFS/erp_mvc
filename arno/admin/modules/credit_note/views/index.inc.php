<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/CreditNoteModel.php');
require_once('../models/CreditNoteListModel.php');
require_once('../models/CustomerPurchaseOrderListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/InvoiceCustomerModel.php');

require_once('../models/JournalSaleReturnModel.php');
require_once('../models/JournalSaleReturnListModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/credit_note/views/";
$user_model = new UserModel;
$customer_model = new CustomerModel;
$invoice_customer_model = new InvoiceCustomerModel;
$notification_model = new NotificationModel;
$credit_note_model = new CreditNoteModel;
$credit_note_list_model = new CreditNoteListModel;
$customer_purchase_order_list_model = new CustomerPurchaseOrderListModel;
$product_model = new ProductModel;
$journal_sale_return_model = new JournalSaleReturnModel;
$journal_sale_return_list_model = new JournalSaleReturnListModel;


$credit_note_id = $_GET['id'];
$notification_id = $_GET['notification'];
$customer_id = $_GET['customer_id'];
$vat = 7;
$first_char = "CN";

if(!isset($_GET['action']) && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();

    $credit_notes = $credit_note_model->getCreditNoteBy($date_start,$date_end,$customer_id,$keyword);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $credit_note_model->getCreditNoteLastID($first_code,3);

    $products=$product_model->getProductBy('','','','Active');
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();
   

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    $products=$product_model->getProductBy('','','','Active');
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();

    $credit_note = $credit_note_model->getCreditNoteByID($credit_note_id);

    $customer=$customer_model->getCustomerByID($credit_note['customer_id']);
    $invoice_customers=$invoice_customer_model->getInvoiceCustomerByCustomerID($credit_note['customer_id']);
    $invoice_customer=$invoice_customer_model->getInvoiceCustomerByID($credit_note['invoice_customer_id']);
    $credit_note_lists = $credit_note_list_model->getCreditNoteListBy($credit_note_id);

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    
    $credit_note = $credit_note_model->getCreditNoteViewByID($credit_note_id);
    $credit_note_lists = $credit_note_list_model->getCreditNoteListBy($credit_note_id);
    
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    
    $credit_note = $credit_note_model->getCreditNoteViewByID($credit_note_id);
    $credit_note_lists = $credit_note_list_model->getCreditNoteListBy($credit_note_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete' && ( $license_sale_page == "High" )){
    $credit_note_model->deleteCreditNoteById($credit_note_id);
    $journal_sale_return_model->deleteJournalSaleReturnByCreditNoteID($credit_note_id);
?>
    <script>window.location="index.php?app=credit_note"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_sale_page == "Medium" || $license_sale_page == "High" ) ){
    if(isset($_POST['credit_note_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['invoice_customer_id'] = $_POST['invoice_customer_id'];
        $data['credit_note_type_id'] = $_POST['credit_note_type_id'];
        $data['credit_note_code'] = $_POST['credit_note_code'];
        $data['credit_note_total_old'] = (float)filter_var($_POST['credit_note_total_old'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_note_total'] = (float)filter_var($_POST['credit_note_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_note_total_price'] = (float)filter_var($_POST['credit_note_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_note_vat'] = (float)filter_var($_POST['credit_note_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_note_vat_price'] =(float)filter_var( $_POST['credit_note_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_note_net_price'] = (float)filter_var($_POST['credit_note_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_note_date'] = $_POST['credit_note_date'];
        $data['credit_note_remark'] = $_POST['credit_note_remark'];
        $data['credit_note_name'] = $_POST['credit_note_name'];
        $data['credit_note_address'] = $_POST['credit_note_address'];
        $data['credit_note_tax'] = $_POST['credit_note_tax'];
        $data['credit_note_term'] = $_POST['credit_note_term'];
        $data['credit_note_due'] = $_POST['credit_note_due'];
        $data['addby'] = $user[0][0];

        $credit_note_id = $credit_note_model->insertCreditNote($data);

        
        if($credit_note_id > 0){

            $data = [];
            $first_code = "SR".date("y").date("m");
            $first_date = date("d")."-".date("m")."-".date("Y");
            $last_code = $journal_sale_return_model->getJournalSaleReturnLastID($first_code,3);
            $data['credit_note_id'] = $credit_note_id;
            $data['journal_sale_return_date'] = $_POST['credit_note_date'];
            $data['journal_sale_return_code'] = $last_code;
            $data['journal_sale_return_name'] = "รับคืนสินค้าจาก ".$_POST['credit_note_name']." [".$_POST['credit_note_code']."] ";
            $data['addby'] = $admin_id;
    
    
            $journal_sale_return_id = $journal_sale_return_model->insertJournalSaleReturn($data);

            if($journal_sale_return_id > 0){

                $customer=$customer_model->getCustomerByID($_POST['customer_id']);
                $data = [];
                $data['journal_sale_return_id'] = $journal_sale_return_id;
                $data['account_id'] = $customer['account_id'];
                $data['journal_sale_return_list_name'] = "รับคืนสินค้าจาก ".$_POST['credit_note_name']." [".$_POST['credit_note_code']."] ";
                $data['journal_sale_return_list_debit'] = 0;
                $data['journal_sale_return_list_credit'] = (float)filter_var( $_POST['credit_note_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $journal_sale_return_list_model->insertJournalSaleReturnList($data);

                if((float)filter_var( $_POST['credit_note_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) > 0){
                    $data = [];
                    $data['journal_sale_return_id'] = $journal_sale_return_id;
                    $data['account_id'] = '91';
                    $data['journal_sale_return_list_name'] = "รับคืนสินค้าจาก ".$_POST['credit_note_name']." [".$_POST['credit_note_code']."] ";
                    $data['journal_sale_return_list_debit'] = 0;
                    $data['journal_sale_return_list_credit'] = (float)filter_var( $_POST['credit_note_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $journal_sale_return_list_model->insertJournalSaleReturnList($data);

                }
            }

            $data = [];
            $product_id = $_POST['product_id'];
            $stock_group_id = $_POST['stock_group_id'];
            $invoice_customer_list_id = $_POST['invoice_customer_list_id'];
            $credit_note_list_product_name = $_POST['credit_note_list_product_name'];
            $credit_note_list_product_detail = $_POST['credit_note_list_product_detail'];
            $credit_note_list_qty = $_POST['credit_note_list_qty'];
            $credit_note_list_price = $_POST['credit_note_list_price'];
            $credit_note_list_total = $_POST['credit_note_list_total'];
            $credit_note_list_remark = $_POST['credit_note_list_remark'];

            
           
            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data_sub = [];
                    $data_sub['credit_note_id'] = $credit_note_id;
                    $data_sub['credit_note_type_id'] = $_POST['credit_note_type_id'];
                    $data_sub['stock_date'] = $_POST['credit_note_date'];
                    $data_sub['product_id'] = $product_id[$i];
                    $data_sub['stock_group_id'] = $stock_group_id[$i];
                    $data_sub['invoice_customer_list_id'] = $invoice_customer_list_id[$i];
                    $data_sub['credit_note_list_product_name'] = $credit_note_list_product_name[$i];
                    $data_sub['credit_note_list_product_detail'] = $credit_note_list_product_detail[$i];
                    $data_sub['credit_note_list_qty'] = (float)filter_var($credit_note_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['credit_note_list_price'] = (float)filter_var($credit_note_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['credit_note_list_total'] = (float)filter_var($credit_note_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['credit_note_list_remark'] = $credit_note_list_remark[$i];

                    $id = $credit_note_list_model->insertCreditNoteList($data_sub);
                }
            }else if($product_id != ""){
                $data_sub = [];
                $data_sub['credit_note_id'] = $credit_note_id;
                $data_sub['credit_note_type_id'] = $_POST['credit_note_type_id'];
                $data_sub['stock_date'] = $_POST['credit_note_date'];
                $data_sub['product_id'] = $product_id;
                $data_sub['stock_group_id'] = $stock_group_id;
                $data_sub['invoice_customer_list_id'] = $invoice_customer_list_id;
                $data_sub['credit_note_list_product_name'] = $credit_note_list_product_name;
                $data_sub['credit_note_list_product_detail'] = $credit_note_list_product_detail;
                $data_sub['credit_note_list_qty'] = (float)filter_var($credit_note_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['credit_note_list_price'] = (float)filter_var($credit_note_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['credit_note_list_total'] = (float)filter_var($credit_note_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['credit_note_list_remark'] = $credit_note_list_remark;
    
                $id = $credit_note_list_model->insertCreditNoteList($data_sub);
            }

?>
        <script>window.location="index.php?app=credit_note&action=update&id=<?php echo $credit_note_id;?>"</script>
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
    
    if(isset($_POST['credit_note_code'])){
        
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['invoice_customer_id'] = $_POST['invoice_customer_id'];
        $data['credit_note_type_id'] = $_POST['credit_note_type_id'];
        $data['credit_note_code'] = $_POST['credit_note_code'];
        $data['credit_note_total_old'] = (float)filter_var($_POST['credit_note_total_old'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_note_total'] = (float)filter_var($_POST['credit_note_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_note_total_price'] = (double)filter_var($_POST['credit_note_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_note_vat'] = (double)filter_var($_POST['credit_note_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_note_vat_price'] =(double)filter_var( $_POST['credit_note_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_note_net_price'] = (double)filter_var($_POST['credit_note_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['credit_note_date'] = $_POST['credit_note_date'];
        $data['credit_note_remark'] = $_POST['credit_note_remark'];
        $data['credit_note_name'] = $_POST['credit_note_name'];
        $data['credit_note_address'] = $_POST['credit_note_address'];
        $data['credit_note_tax'] = $_POST['credit_note_tax'];
        $data['credit_note_term'] = $_POST['credit_note_term'];
        $data['credit_note_due'] = $_POST['credit_note_due'];
        $data['addby'] = $user[0][0];


        $product_id = $_POST['product_id'];
        $credit_note_list_id = $_POST['credit_note_list_id'];
        $stock_group_id = $_POST['stock_group_id'];
        $invoice_customer_list_id = $_POST['invoice_customer_list_id'];
        $credit_note_list_product_name = $_POST['credit_note_list_product_name'];
        $credit_note_list_product_detail = $_POST['credit_note_list_product_detail'];
        $credit_note_list_qty = $_POST['credit_note_list_qty'];
        $credit_note_list_price = $_POST['credit_note_list_price'];
        $credit_note_list_total = $_POST['credit_note_list_total'];
        $credit_note_list_remark = $_POST['credit_note_list_remark'];

        
        $credit_note_list_model->deleteCreditNoteListByCreditNoteIDNotIN($credit_note_id,$credit_note_list_id);
        
        

        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data_sub = [];
                $data_sub['credit_note_id'] = $credit_note_id;
                $data_sub['credit_note_type_id'] = $_POST['credit_note_type_id'];
                $data_sub['stock_date'] = $_POST['credit_note_date'];
                $data_sub['product_id'] = $product_id[$i];
                $data_sub['stock_group_id'] = $stock_group_id[$i];
                $data_sub['invoice_customer_list_id'] = $invoice_customer_list_id[$i];
                $data_sub['credit_note_list_product_name'] = $credit_note_list_product_name[$i];
                $data_sub['credit_note_list_product_detail'] = $credit_note_list_product_detail[$i];
                $data_sub['credit_note_list_qty'] = (float)filter_var($credit_note_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['credit_note_list_price'] = (float)filter_var($credit_note_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['credit_note_list_total'] = (float)filter_var($credit_note_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['credit_note_list_remark'] = $credit_note_list_remark[$i];

                if($credit_note_list_id[$i] != '0'){
                    $credit_note_list_model->updateCreditNoteListById($data_sub,$credit_note_list_id[$i]);
                }else{
                    $id = $credit_note_list_model->insertCreditNoteList($data_sub);
                }
                
            }
        }else if($product_id != ""){
            $data_sub = [];
            $data_sub['credit_note_id'] = $credit_note_id;
            $data_sub['credit_note_type_id'] = $_POST['credit_note_type_id'];
            $data_sub['stock_date'] = $_POST['credit_note_date'];
            $data_sub['product_id'] = $product_id;
            $data_sub['stock_group_id'] = $stock_group_id;
            $data_sub['invoice_customer_list_id'] = $invoice_customer_list_id;
            $data_sub['credit_note_list_product_name'] = $credit_note_list_product_name;
            $data_sub['credit_note_list_product_detail'] = $credit_note_list_product_detail;
            $data_sub['credit_note_list_qty'] = (float)filter_var($credit_note_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['credit_note_list_price'] = (float)filter_var($credit_note_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['credit_note_list_total'] = (float)filter_var($credit_note_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['credit_note_list_remark'] = $credit_note_list_remark;

            if($credit_note_list_id != '0'){
                $credit_note_list_model->updateCreditNoteListById($data_sub,$credit_note_list_id);
            }else{
                $id = $credit_note_list_model->insertCreditNoteList($data_sub);
            }
        }

        $output = $credit_note_model->updateCreditNoteByID($credit_note_id,$data);
        

        if($output){
        
?>
        <script>window.location="index.php?app=credit_note&action=update&id=<?php echo $credit_note_id;?>"</script>
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

    $credit_notes = $credit_note_model->getCreditNoteBy($date_start,$date_end,$customer_id,$keyword);
    require_once($path.'view.inc.php');

}





?>