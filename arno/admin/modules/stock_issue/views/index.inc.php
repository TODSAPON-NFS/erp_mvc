<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/InvoiceCustomerModel.php');
require_once('../models/StockIssueModel.php');
require_once('../models/StockIssueListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/StockGroupModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/stock_issue/views/";

$invoice_customer_model = new InvoiceCustomerModel;
$user_model = new UserModel;
$stock_group_model = new StockGroupModel;
$stock_issue_model = new StockIssueModel;
$stock_issue_list_model = new StockIssueListModel;
$product_model = new ProductModel;
$first_char = "SM";
$stock_issue_id = $_GET['id'];
$target_dir = "../upload/stock_issue/";

if(!isset($_GET['action'])){

    $stock_issues = $stock_issue_model->getStockIssueBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $invoice_customers=$invoice_customer_model->getInvoiceCustomerBy();
    $products=$product_model->getProductBy('','','','Active');
    $stock_groups=$stock_group_model->getStockGroupBy();
    $users=$user_model->getUserBy();
    $first_code = $first_char.date("y").date("m");
    $last_code = $stock_issue_model->getStockIssueLastID($first_code,3);
    $first_date = date("d")."-".date("m")."-".date("Y");
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $invoice_customers=$invoice_customer_model->getInvoiceCustomerBy();
    $products=$product_model->getProductBy('','','','Active');
    $stock_groups=$stock_group_model->getStockGroupBy();
    $users=$user_model->getUserBy();
    $stock_issue = $stock_issue_model->getStockIssueByID($stock_issue_id);
    $stock_group=$stock_group_model->getStockGroupByID($stock_issue['stock_group_id']);
    $stock_issue_lists = $stock_issue_list_model->getStockIssueListBy($stock_issue_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $stock_issue = $stock_issue_model->getStockIssueViewByID($stock_issue_id);
    $stock_issue_lists = $stock_issue_list_model->getStockIssueListBy($stock_issue_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $stock_issue = $stock_issue_model->getStockIssueViewByID($stock_issue_id);
    $stock_issue_lists = $stock_issue_list_model->getStockIssueListBy($stock_issue_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){

    //$stock_issue_list_model->deleteStockIssueListByStockIssueID($stock_issue_id);
    $stock_issues = $stock_issue_model->deleteStockIssueById($stock_issue_id);
?>
    <script>window.location="index.php?app=stock_issue"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['stock_issue_code'])){
  
        $check = true;

        $data = [];
        $data['stock_issue_date'] = $_POST['stock_issue_date'];
        $data['stock_issue_code'] = $_POST['stock_issue_code'];
        $data['stock_group_id'] = $_POST['stock_group_id'];
        $data['invoice_customer_id'] = $_POST['invoice_customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['stock_issue_remark'] = $_POST['stock_issue_remark'];
        $data['stock_issue_total'] = (double)filter_var($_POST['stock_issue_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $stock_issue_id = $stock_issue_model->insertStockIssue($data);

        if($stock_issue_id > 0){

            $product_id = $_POST['product_id'];
            $stock_issue_list_id = $_POST['stock_issue_list_id'];
            $stock_issue_list_qty = $_POST['stock_issue_list_qty'];
            $stock_issue_list_price = $_POST['stock_issue_list_price'];
            $stock_issue_list_total = $_POST['stock_issue_list_total'];
            $stock_issue_list_remark = $_POST['stock_issue_list_remark'];

            $stock_issue_list_model->deleteStockIssueListByStockIssueIDNotIN($stock_issue_id,$stock_issue_list_id);

            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data = [];
                    $data['stock_group_id'] = $_POST['stock_group_id'];
                    $data['stock_date'] = $_POST['stock_issue_date'];
                    $data['stock_issue_id'] = $stock_issue_id;
                    $data['product_id'] = $product_id[$i];
                    $data['stock_issue_list_qty'] = $stock_issue_list_qty[$i];
                    $data['stock_issue_list_remark'] = $stock_issue_list_remark[$i];
                    $data['stock_issue_list_price'] = (double)filter_var($stock_issue_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['stock_issue_list_total'] = (double)filter_var($stock_issue_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if ($stock_issue_list_id[$i] != "" && $stock_issue_list_id[$i] != '0'){
                        $stock_issue_list_model->updateStockIssueListById($data,$stock_issue_list_id[$i]);
                    }else{
                        $stock_issue_list_model->insertStockIssueList($data);
                    }
                }
            }else{
                $data = [];
                $data['stock_group_id'] = $_POST['stock_group_id'];
                $data['stock_date'] = $_POST['stock_issue_date'];
                $data['stock_issue_id'] = $stock_issue_id;
                $data['product_id'] = $product_id;
                $data['stock_issue_list_qty'] = $stock_issue_list_qty;
                $data['stock_issue_list_remark'] = $stock_issue_list_remark;
                $data['stock_issue_list_price'] = (double)filter_var($stock_issue_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['stock_issue_list_total'] = (double)filter_var($stock_issue_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                if ($stock_issue_list_id != "" && $stock_issue_list_id != '0'){
                    $stock_issue_list_model->updateStockIssueListById($data,$stock_issue_list_id);
                }else{
                    $stock_issue_list_model->insertStockIssueList($data);
                }
                
            }

    ?>
            <script>window.location="index.php?app=stock_issue&action=update&id=<?php echo $stock_issue_id;?>"</script>
    <?php
        }
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['stock_issue_code'])){
        $data = [];
        $data['stock_issue_date'] = $_POST['stock_issue_date'];
        $data['stock_issue_code'] = $_POST['stock_issue_code'];
        $data['stock_group_id'] = $_POST['stock_group_id'];
        $data['invoice_customer_id'] = $_POST['invoice_customer_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['stock_issue_remark'] = $_POST['stock_issue_remark'];
        $data['stock_issue_total'] = (double)filter_var($_POST['stock_issue_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        

        $output = $stock_issue_model->updateStockIssueByID($stock_issue_id,$data);

        $product_id = $_POST['product_id'];
        $stock_issue_list_id = $_POST['stock_issue_list_id'];
        $stock_issue_list_qty = $_POST['stock_issue_list_qty'];
        $stock_issue_list_price = $_POST['stock_issue_list_price'];
        $stock_issue_list_total = $_POST['stock_issue_list_total'];
        $stock_issue_list_remark = $_POST['stock_issue_list_remark'];

        $stock_issue_list_model->deleteStockIssueListByStockIssueIDNotIN($stock_issue_id,$stock_issue_list_id);

        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data = [];
                $data['stock_group_id'] = $_POST['stock_group_id'];
                $data['stock_date'] = $_POST['stock_issue_date'];
                $data['stock_issue_id'] = $stock_issue_id;
                $data['product_id'] = $product_id[$i];
                $data['stock_issue_list_qty'] = $stock_issue_list_qty[$i];
                $data['stock_issue_list_price'] = (double)filter_var($stock_issue_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['stock_issue_list_total'] = (double)filter_var($stock_issue_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['stock_issue_list_remark'] = $stock_issue_list_remark[$i];

                if ($stock_issue_list_id[$i] != "" && $stock_issue_list_id[$i] != '0'){
                    $stock_issue_list_model->updateStockIssueListById($data,$stock_issue_list_id[$i]);
                }else{
                    $stock_issue_list_model->insertStockIssueList($data);
                }
            }
        }else{
            $data = [];
            $data['stock_group_id'] = $_POST['stock_group_id'];
            $data['stock_date'] = $_POST['stock_issue_date'];
            $data['stock_issue_id'] = $stock_issue_id;
            $data['product_id'] = $product_id;
            $data['stock_issue_list_qty'] = $stock_issue_list_qty;
            $data['stock_issue_list_price'] = (double)filter_var($stock_issue_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['stock_issue_list_total'] = (double)filter_var($stock_issue_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['stock_issue_list_remark'] = $stock_issue_list_remark;
            if ($stock_issue_list_id != "" && $stock_issue_list_id != '0'){
                $stock_issue_list_model->updateStockIssueListById($data,$stock_issue_list_id);
            }else{
                $stock_issue_list_model->insertStockIssueList($data);
            }
            
        }
        
        if($output){
    ?>
            <script>window.location="index.php?app=stock_issue"</script>
    <?php
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
     
    
}else{

    $stock_issues = $stock_issue_model->getStockIssueBy();
    require_once($path.'view.inc.php');

}





?>