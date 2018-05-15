<?php
require_once('../models/UserModel.php');
require_once('../models/StockTypeUserModel.php');
require_once('../models/StockTypeModel.php');
require_once('../models/StockModel.php');
require_once('../models/StockGroupModel.php');

$path = "modules/stock_type/views/";
$user_model = new UserModel;
$model_stock_type_user = new StockTypeUserModel;
$model_stock_type = new StockTypeModel;
$model_stock = new StockModel;
$model_stock_group = new StockGroupModel;
$stock_type_id = $_GET['id'];

if(!isset($_GET['action'])){

    $stock_types = $model_stock_type->getStockTypeBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    
    $users=$user_model->getUserBy();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){ 
    $users=$user_model->getUserBy();
    $stock_type = $model_stock_type->getStockTypeByID($stock_type_id);
    $stock_type_users = $model_stock_type_user->getStockTypeUserBy($stock_type_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'){

    if($model_stock_type->deleteStockTypeById($stock_type_id)){
    ?>
        <script>window.location="index.php?app=stock_type"</script>
    <?php
    }  else{

    }
 
}else if ($_GET['action'] == 'set_primary'){

    if($model_stock_type->setPrimaryByID($stock_type_id)){
    ?>
        <script>window.location="index.php?app=stock_type"</script>
    <?php
    }  else{
        echo "-";
    }
 
}else if ($_GET['action'] == 'add'){
    if(isset($_POST['stock_type_code'])){

        $data = [];
        $data['stock_type_code'] = $_POST['stock_type_code'];
        $data['stock_type_name'] = $_POST['stock_type_name'];
        $data['stock_group_id'] = $_POST['stock_group_id'];

        $stock_type_id = $model_stock_type->insertStockType($data);

        
        if($stock_type_id > 0){
            $employee_id = $_POST['employee_id'];
            $stock_type_user_id = $_POST['stock_type_user_id'];

            $model_stock_type_user->deleteStockTypeUserByEmployeeIDNotIN($stock_type_id,$stock_type_user_id);

            if(is_array($employee_id)){
                for($i=0; $i < count($employee_id) ; $i++){
                    $data = [];
                    $data['stock_type_id'] = $stock_type_id;
                    $data['employee_id'] = $employee_id[$i];
                    if($stock_type_user_id[$i] == 0){
                        $model_stock_type_user->insertStockTypeUser($data);
                    }
                }
            }else{
                $data = [];
                $data['stock_type_id'] = $stock_type_id;
                $data['employee_id'] = $employee_id;
    
                if($stock_type_user_id == 0){
                    $model_stock_type_user->insertStockTypeUser($data);
                }
            }


?>
        <script>window.location="index.php?app=stock_type&action=update&id=<?php echo $stock_type_id?>"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=stock_type"</script>
<?php
        }
                    
     
    }else{
        ?>
    <script>window.location="index.php?app=stock_type"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['stock_type_code'])){
        $data = [];
        $data['stock_type_code'] = $_POST['stock_type_code'];
        $data['stock_type_name'] = $_POST['stock_type_name'];
        $data['stock_group_id'] = $_POST['stock_group_id'];
       
        $output = $model_stock_type->updateStockTypeByID($stock_type_id,$data);

        if($output){
            $employee_id = $_POST['employee_id'];
            $stock_type_user_id = $_POST['stock_type_user_id'];

            $model_stock_type_user->deleteStockTypeUserByEmployeeIDNotIN($stock_type_id,$stock_type_user_id);

            if(is_array($employee_id)){

                for($i=0; $i < count($employee_id) ; $i++){

                    $data = [];
                    $data['stock_type_id'] = $stock_type_id;
                    $data['employee_id'] = $employee_id[$i];

                    if($stock_type_user_id[$i] == 0){
                        $model_stock_type_user->insertStockTypeUser($data);
                    }

                }

            }else{

                $data = [];
                $data['stock_type_id'] = $stock_type_id;
                $data['employee_id'] = $employee_id;
    
                if($stock_type_user_id == 0){
                    $model_stock_type_user->insertStockTypeUser($data);
                }

            }
?>
        <script>window.location="index.php?app=stock_type"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=stock_type"</script>
<?php
        }
                    
    }else{
        ?>
    <script>window.location="index.php?app=stock_type"</script>
        <?php
    }
    
                 
} else {

    $stock_types = $model_stock_type->getStockTypeBy();
    require_once($path.'view.inc.php');

}





?>