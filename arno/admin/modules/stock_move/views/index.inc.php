<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/StockMoveModel.php');
require_once('../models/StockMoveListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/StockGroupModel.php');
require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');
require_once('../models/MaintenanceStockModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/stock_move/views/";
$user_model = new UserModel;
$stock_group_model = new StockGroupModel;
$stock_move_model = new StockMoveModel;
$stock_move_list_model = new StockMoveListModel;
$product_model = new ProductModel;
$code_generate = new CodeGenerate;
$paper_model = new PaperModel;
$maintenance_stock_model = new MaintenanceStockModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('34');
 
$stock_move_id = $_GET['id'];
$target_dir = "../upload/stock_move/";


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

    // if(!isset($_GET['keyword'])){
    //     $keyword = $_SESSION['keyword'];
    // }else{
        
    //     $keyword = $_GET['keyword']; 
    //     $_SESSION['keyword'] = $keyword;
    // }
    $keyword = $_GET['keyword'];

if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}



if(!isset($_GET['action'])){

    

    $stock_moves = $stock_move_model->getStockMoveBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $products=$product_model->getProductBy('','','','');
    $stock_groups=$stock_group_model->getStockGroupBy();
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
            $last_code = $stock_move_model->getStockMoveLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }  

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $products=$product_model->getProductBy('','','','');
    $stock_groups=$stock_group_model->getStockGroupBy();
    $users=$user_model->getUserBy();
    $stock_move = $stock_move_model->getStockMoveByID($stock_move_id);
    $stock_group=$stock_group_model->getStockGroupByID($stock_move['stock_group_id']);
    $stock_move_lists = $stock_move_list_model->getStockMoveListBy($stock_move_id);

    $stock_moves = $stock_move_model->getStockMoveBy($date_start,$date_end,$keyword);

    for($i = 0 ; $i < count($stock_moves) ; $i++){
        if($stock_move_id == $stock_moves[$i]['stock_move_id']){ 
            $previous_id = $stock_moves[$i-1]['stock_move_id'];
            $previous_code = $stock_moves[$i-1]['stock_move_code'];
            $next_id = $stock_moves[$i+1]['stock_move_id'];
            $next_code = $stock_moves[$i+1]['stock_move_code'];

        }
    }

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $stock_move = $stock_move_model->getStockMoveViewByID($stock_move_id);
    $stock_move_lists = $stock_move_list_model->getStockMoveListBy($stock_move_id);

    $stock_moves = $stock_move_model->getStockMoveBy($date_start,$date_end,$keyword);

    for($i = 0 ; $i < count($stock_moves) ; $i++){
        if($stock_move_id == $stock_moves[$i]['stock_move_id']){ 
            $previous_id = $stock_moves[$i-1]['stock_move_id'];
            $previous_code = $stock_moves[$i-1]['stock_move_code'];
            $next_id = $stock_moves[$i+1]['stock_move_id'];
            $next_code = $stock_moves[$i+1]['stock_move_code'];

        }
    }
    
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $stock_move = $stock_move_model->getStockMoveViewByID($stock_move_id);
    $stock_move_lists = $stock_move_list_model->getStockMoveListBy($stock_move_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){

    //$stock_move_list_model->deleteStockMoveListByStockMoveID($stock_move_id);
    
    $stock_move = $stock_move_model->getStockMoveByID($stock_move_id);
    $stock_moves = $stock_move_model->deleteStockMoveById($stock_move_id);

    $maintenance_stock_model->runMaintenance($stock_move['stock_move_date']);

?>
    <script>window.location="index.php?app=stock_move"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['stock_move_code'])){
  
        $check = true;

        $data = [];
        $data['stock_move_date'] = $_POST['stock_move_date'];
        $data['stock_move_code'] = $_POST['stock_move_code'];
        $data['stock_group_id_out'] = $_POST['stock_group_id_out'];
        $data['stock_group_id_in'] = $_POST['stock_group_id_in'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['stock_move_remark'] = $_POST['stock_move_remark'];

        $stock_move_id = $stock_move_model->insertStockMove($data);

        if($stock_move_id > 0){

            $product_id = $_POST['product_id'];
            $stock_move_list_id = $_POST['stock_move_list_id'];
            $stock_move_list_qty = $_POST['stock_move_list_qty'];
            $stock_move_list_remark = $_POST['stock_move_list_remark'];

            $stock_move_list_model->deleteStockMoveListByStockMoveIDNotIN($stock_move_id,$stock_move_list_id);

            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data = [];
                    $data['stock_group_id_out'] = $_POST['stock_group_id_out'];
                    $data['stock_group_id_in'] = $_POST['stock_group_id_in'];
                    $data['stock_date'] = $_POST['stock_move_date'];
                    $data['stock_move_id'] = $stock_move_id;
                    $data['product_id'] = $product_id[$i];
                    $data['stock_move_list_qty'] = $stock_move_list_qty[$i];
                    $data['stock_move_list_remark'] = $stock_move_list_remark[$i];

                    if ($stock_move_list_id[$i] != "" && $stock_move_list_id[$i] != '0'){
                        $stock_move_list_model->updateStockMoveListById($data,$stock_move_list_id[$i]);
                    }else{
                        $stock_move_list_model->insertStockMoveList($data);
                    }
                }
            }else{
                $data = [];
                $data['stock_group_id_out'] = $_POST['stock_group_id_out'];
                $data['stock_group_id_in'] = $_POST['stock_group_id_in'];
                $data['stock_date'] = $_POST['stock_move_date'];
                $data['stock_move_id'] = $stock_move_id;
                $data['product_id'] = $product_id;
                $data['stock_move_list_qty'] = $stock_move_list_qty;
                $data['stock_move_list_remark'] = $stock_move_list_remark;
                if ($stock_move_list_id != "" && $stock_move_list_id != '0'){
                    $stock_move_list_model->updateStockMoveListById($data,$stock_move_list_id);
                }else{
                    $stock_move_list_model->insertStockMoveList($data);
                }
                
            }


            $maintenance_stock_model->runMaintenance($_POST['stock_move_date']);



    ?>
            <script>window.location="index.php?app=stock_move&action=update&id=<?php echo $stock_move_id;?>"</script>
    <?php
        }
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['stock_move_code'])){

        $stock_move = $stock_move_model->getStockMoveByID($stock_move_id);

        $data = [];
        $data['stock_move_date'] = $_POST['stock_move_date'];
        $data['stock_move_code'] = $_POST['stock_move_code'];
        $data['stock_group_id_out'] = $_POST['stock_group_id_out'];
        $data['stock_group_id_in'] = $_POST['stock_group_id_in'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['stock_move_remark'] = $_POST['stock_move_remark'];

        

        $output = $stock_move_model->updateStockMoveByID($stock_move_id,$data);

        $product_id = $_POST['product_id'];
        $stock_move_list_id = $_POST['stock_move_list_id'];
        $stock_move_list_qty = $_POST['stock_move_list_qty'];
        $stock_move_list_remark = $_POST['stock_move_list_remark'];

        $stock_move_list_model->deleteStockMoveListByStockMoveIDNotIN($stock_move_id,$stock_move_list_id);

        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data = [];
                $data['stock_group_id_out'] = $_POST['stock_group_id_out'];
                $data['stock_group_id_in'] = $_POST['stock_group_id_in'];
                $data['stock_date'] = $_POST['stock_move_date'];
                $data['stock_move_id'] = $stock_move_id;
                $data['product_id'] = $product_id[$i];
                $data['stock_move_list_qty'] = $stock_move_list_qty[$i];
                $data['stock_move_list_remark'] = $stock_move_list_remark[$i];

                if ($stock_move_list_id[$i] != "" && $stock_move_list_id[$i] != '0'){
                    $stock_move_list_model->updateStockMoveListById($data,$stock_move_list_id[$i]);
                }else{
                    $stock_move_list_model->insertStockMoveList($data);
                }
            }
        }else{
            $data = [];
            $data['stock_group_id_out'] = $_POST['stock_group_id_out'];
            $data['stock_group_id_in'] = $_POST['stock_group_id_in'];
            $data['stock_date'] = $_POST['stock_move_date'];
            $data['stock_move_id'] = $stock_move_id;
            $data['product_id'] = $product_id;
            $data['stock_move_list_qty'] = $stock_move_list_qty;
            $data['stock_move_list_remark'] = $stock_move_list_remark;
            if ($stock_move_list_id != "" && $stock_move_list_id != '0'){
                $stock_move_list_model->updateStockMoveListById($data,$stock_move_list_id);
            }else{
                $stock_move_list_model->insertStockMoveList($data);
            }
            
        }
        
        if($output){

            $old_date = DateTime::createFromFormat('d-m-Y',$stock_move['stock_move_date']);
            $new_date =  DateTime::createFromFormat('d-m-Y',$_POST['stock_move_date']);

            if($old_date < $new_date){
                $maintenance_stock_model->runMaintenance($stock_move['stock_move_date']);
            }else{
                $maintenance_stock_model->runMaintenance($_POST['stock_move_date']);
            }
            
    ?>
            <script>window.location="index.php?app=stock_move"</script>
    <?php
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
     
    
}else{


    $stock_moves = $stock_move_model->getStockMoveBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');

}





?>