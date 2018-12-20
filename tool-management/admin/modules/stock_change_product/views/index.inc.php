<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/StockChangeProductModel.php');
require_once('../models/StockChangeProductListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/StockGroupModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/stock_change_product/views/";
$user_model = new UserModel;
$stock_group_model = new StockGroupModel;
$stock_change_product_model = new StockChangeProductModel;
$stock_change_product_list_model = new StockChangeProductListModel;
$product_model = new ProductModel;
$first_char = "SPC";
$stock_change_product_id = $_GET['id'];
$target_dir = "../upload/stock_change_product/";

if(!isset($_GET['action'])){

    $stock_change_products = $stock_change_product_model->getStockChangeProductBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){ 
    $stock_groups=$stock_group_model->getStockGroupBy();
    $users=$user_model->getUserBy();
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $stock_change_product_model->getStockChangeProductLastID($first_code,3);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){ 
    $stock_groups=$stock_group_model->getStockGroupBy();
    $users=$user_model->getUserBy();
    $stock_change_product = $stock_change_product_model->getStockChangeProductByID($stock_change_product_id); 
    $stock_change_product_lists = $stock_change_product_list_model->getStockChangeProductListBy($stock_change_product_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $stock_change_product = $stock_change_product_model->getStockChangeProductViewByID($stock_change_product_id);
    $stock_change_product_lists = $stock_change_product_list_model->getStockChangeProductListBy($stock_change_product_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $stock_change_product = $stock_change_product_model->getStockChangeProductViewByID($stock_change_product_id);
    $stock_change_product_lists = $stock_change_product_list_model->getStockChangeProductListBy($stock_change_product_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){
 
    $stock_change_products = $stock_change_product_model->deleteStockChangeProductById($stock_change_product_id);
?>
    <script>window.location="index.php?app=stock_change_product"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['stock_change_product_code'])){
  
        $check = true;

        $data = [];
        $data['stock_change_product_date'] = $_POST['stock_change_product_date'];
        $data['stock_change_product_code'] = $_POST['stock_change_product_code'];
        $data['stock_group_id'] = $_POST['stock_group_id']; 
        $data['employee_id'] = $_POST['employee_id'];
        $data['stock_change_product_remark'] = $_POST['stock_change_product_remark'];

        $stock_change_product_id = $stock_change_product_model->insertStockChangeProduct($data);

        if($stock_change_product_id > 0){

            $product_id_new = $_POST['product_id_new'];
            $product_id_old = $_POST['product_id_old'];
            $stock_group_id_new = $_POST['stock_group_id_new'];
            $stock_group_id_old = $_POST['stock_group_id_old'];
            $stock_change_product_list_id = $_POST['stock_change_product_list_id'];
            $stock_change_product_list_qty = $_POST['stock_change_product_list_qty'];
            $stock_change_product_list_price = $_POST['stock_change_product_list_price'];
            $stock_change_product_list_total = $_POST['stock_change_product_list_total'];
            $stock_change_product_list_remark = $_POST['stock_change_product_list_remark'];

            $stock_change_product_list_model->deleteStockChangeProductListByStockChangeProductIDNotIN($stock_change_product_id,$stock_change_product_list_id);

            if(is_array($product_id_old)){
                for($i=0; $i < count($product_id_old) ; $i++){
                    $data = [];
                    $data['stock_group_id'] = $_POST['stock_group_id']; 
                    $data['stock_date'] = $_POST['stock_change_product_date'];
                    $data['stock_change_product_id'] = $stock_change_product_id;
                    $data['product_id_new'] = $product_id_new[$i];
                    $data['product_id_old'] = $product_id_old[$i];
                    $data['stock_group_id_new'] = $stock_group_id_new[$i];
                    $data['stock_group_id_old'] = $stock_group_id_old[$i];
                    $data['stock_change_product_list_qty'] = (float)filter_var($stock_change_product_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['stock_change_product_list_price'] = (float)filter_var($stock_change_product_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['stock_change_product_list_total'] = (float)filter_var($stock_change_product_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data['stock_change_product_list_remark'] = $stock_change_product_list_remark[$i];

                    if ($stock_change_product_list_id[$i] != "" && $stock_change_product_list_id[$i] != '0'){
                        $stock_change_product_list_model->updateStockChangeProductListById($data,$stock_change_product_list_id[$i]);
                    }else{
                        $stock_change_product_list_model->insertStockChangeProductList($data);
                    }
                }
            }else if($product_id_old != ""){
                $data = [];
                $data['stock_group_id'] = $_POST['stock_group_id']; 
                $data['stock_date'] = $_POST['stock_change_product_date'];
                $data['stock_change_product_id'] = $stock_change_product_id;
                $data['product_id_new'] = $product_id_new;
                $data['product_id_old'] = $product_id_old;
                $data['stock_group_id_new'] = $stock_group_id_new;
                $data['stock_group_id_old'] = $stock_group_id_old;
                $data['stock_change_product_list_qty'] = (float)filter_var($stock_change_product_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['stock_change_product_list_price'] = (float)filter_var($stock_change_product_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['stock_change_product_list_total'] = (float)filter_var($stock_change_product_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['stock_change_product_list_remark'] = $stock_change_product_list_remark;
                if ($stock_change_product_list_id != "" && $stock_change_product_list_id != '0'){
                    $stock_change_product_list_model->updateStockChangeProductListById($data,$stock_change_product_list_id);
                }else{
                    $stock_change_product_list_model->insertStockChangeProductList($data);
                }
                
            }

    ?>
            <script>window.location="index.php?app=stock_change_product&action=update&id=<?php echo $stock_change_product_id;?>"</script>
    <?php
        }
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['stock_change_product_code'])){
        $data = [];
        $data['stock_change_product_date'] = $_POST['stock_change_product_date'];
        $data['stock_change_product_code'] = $_POST['stock_change_product_code'];
        $data['stock_group_id'] = $_POST['stock_group_id']; 
        $data['employee_id'] = $_POST['employee_id'];
        $data['stock_change_product_remark'] = $_POST['stock_change_product_remark'];

        

        $output = $stock_change_product_model->updateStockChangeProductByID($stock_change_product_id,$data);

        $product_id_new = $_POST['product_id_new'];
        $product_id_old = $_POST['product_id_old'];
        $stock_group_id_new = $_POST['stock_group_id_new'];
        $stock_group_id_old = $_POST['stock_group_id_old'];
        $stock_change_product_list_id = $_POST['stock_change_product_list_id'];
        $stock_change_product_list_qty = $_POST['stock_change_product_list_qty'];
        $stock_change_product_list_price = $_POST['stock_change_product_list_price'];
        $stock_change_product_list_total = $_POST['stock_change_product_list_total'];
        $stock_change_product_list_remark = $_POST['stock_change_product_list_remark'];

        $stock_change_product_list_model->deleteStockChangeProductListByStockChangeProductIDNotIN($stock_change_product_id,$stock_change_product_list_id);

        if(is_array($product_id_old)){
            for($i=0; $i < count($product_id_old) ; $i++){
                $data = [];
                $data['stock_group_id'] = $_POST['stock_group_id']; 
                $data['stock_date'] = $_POST['stock_change_product_date'];
                $data['stock_change_product_id'] = $stock_change_product_id;
                $data['product_id_new'] = $product_id_new[$i];
                $data['product_id_old'] = $product_id_old[$i];
                $data['stock_group_id_new'] = $stock_group_id_new[$i];
                $data['stock_group_id_old'] = $stock_group_id_old[$i];
                $data['stock_change_product_list_qty'] = (float)filter_var($stock_change_product_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['stock_change_product_list_price'] = (float)filter_var($stock_change_product_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['stock_change_product_list_total'] = (float)filter_var($stock_change_product_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data['stock_change_product_list_remark'] = $stock_change_product_list_remark[$i];

                if ($stock_change_product_list_id[$i] != "" && $stock_change_product_list_id[$i] != '0'){
                    $stock_change_product_list_model->updateStockChangeProductListById($data,$stock_change_product_list_id[$i]);
                }else{
                    $stock_change_product_list_model->insertStockChangeProductList($data);
                }
            }
        }else if($product_id_old != ""){
            $data = [];
            $data['stock_group_id'] = $_POST['stock_group_id']; 
            $data['stock_date'] = $_POST['stock_change_product_date'];
            $data['stock_change_product_id'] = $stock_change_product_id;
            $data['product_id_new'] = $product_id_new;
            $data['product_id_old'] = $product_id_old;
            $data['stock_group_id_new'] = $stock_group_id_new;
            $data['stock_group_id_old'] = $stock_group_id_old;
            $data['stock_change_product_list_qty'] = (float)filter_var($stock_change_product_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['stock_change_product_list_price'] = (float)filter_var($stock_change_product_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['stock_change_product_list_total'] = (float)filter_var($stock_change_product_list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['stock_change_product_list_remark'] = $stock_change_product_list_remark;
            if ($stock_change_product_list_id != "" && $stock_change_product_list_id != '0'){
                $stock_change_product_list_model->updateStockChangeProductListById($data,$stock_change_product_list_id);
            }else{
                $stock_change_product_list_model->insertStockChangeProductList($data);
            }
            
        }
        
        if($output){
    ?>
            <script>window.location="index.php?app=stock_change_product"</script>
    <?php
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
     
    
}else{

    $stock_change_products = $stock_change_product_model->getStockChangeProductBy();
    require_once($path.'view.inc.php');

}





?>