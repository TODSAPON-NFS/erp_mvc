<?php
require_once('../models/StockGroupModel.php');
require_once('../models/StockModel.php');
require_once('../models/UserModel.php');
require_once('../models/StockTypeModel.php');


$path = "modules/stock_group/views/";
$model_stock_group = new StockGroupModel;
$model_stock = new StockModel;
$model_user = new UserModel;
$model_stock_type = new StockTypeModel;


$stock_group_id = $_GET['id'];
$stock_type_id = $_GET['stock_type_id'];


if(!isset($_GET['action']) && ($license_inventery_page == "Low" || $license_inventery_page == "Medium" || $license_inventery_page == "High" ) ){

    $stock_groups = $model_stock_group->getStockGroupBy($stock_type_id);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){

    $users = $model_user->getUserBy();
    $stock_types = $model_stock_type->getStockTypeBy();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_inventery_page == "Medium" || $license_inventery_page == "High" ) ){
   
    $stock_group = $model_stock_group->getStockGroupByID($stock_group_id);
    $users = $model_user->getUserBy();
    $stock_types = $model_stock_type->getStockTypeBy();
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_inventery_page == "High" )){

    $stock_group = $model_stock_group->getStockGroupByID($stock_group_id);
    $model_stock->setTableName($stock_group['table_name']);
    if($model_stock_group->deleteStockGroupById($stock_group_id)){
        $model_stock->deleteStockTable();
    ?>
        <script>window.location="index.php?app=stock_group&stock_type_id=<?php echo $stock_type_id;?>"</script>
    <?php
    }  else{

    }
 
}else if ($_GET['action'] == 'set_primary'){

    if($model_stock_group->setPrimaryByID($stock_type_id,$stock_group_id)){
    ?>
        <script>window.location="index.php?app=stock_group&stock_type_id=<?php echo $stock_type_id;?>"</script>
    <?php
    }  else{
        echo "-";
    }
 
}else if ($_GET['action'] == 'add' && ($license_inventery_page == "Medium" || $license_inventery_page == "High" ) ){
    if(isset($_POST['stock_group_name'])){

        $data = [];
        $data['stock_type_id'] = $_POST['stock_type_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['stock_group_name'] = $_POST['stock_group_name'];
        $data['stock_group_name'] = $_POST['stock_group_name'];
        $data['stock_group_detail'] = $_POST['stock_group_detail'];
        $data['stock_group_notification'] = $_POST['stock_group_notification'];
        $data['stock_group_day'] = $_POST['stock_group_day'];

        $id = $model_stock_group->insertStockGroup($data);

        if($id > 0){
            $table_name = "tb_stock_".$id;
            $model_stock_group->updateTableName($id,$table_name );
            $model_stock->setTableName($table_name);
            $model_stock->createStockTable();
?>
        <script>window.location="index.php?app=stock_group&action=update&stock_type_id=<?php echo $stock_type_id;?>&id=<?php echo $id?>"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=stock_group&stock_type_id=<?php echo $stock_type_id;?>"</script>
<?php
        }
                    
     
    }else{
        ?>
    <script>window.location="index.php?app=stock_group&stock_type_id=<?php echo $stock_type_id;?>"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit' && ($license_inventery_page == "Medium" || $license_inventery_page == "High" ) ){
    
    if(isset($_POST['stock_group_name'])){
        $data = [];
        $data['stock_type_id'] = $_POST['stock_type_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['stock_group_name'] = $_POST['stock_group_name'];
        $data['stock_group_detail'] = $_POST['stock_group_detail'];
        $data['stock_group_notification'] = $_POST['stock_group_notification'];
        $data['stock_group_day'] = $_POST['stock_group_day'];
        $data['table_name'] = $_POST['table_name'];
       
        $user = $model_stock_group->updateStockGroupByID($_POST['stock_group_id'],$data);
        if($user){
?>
        <script>window.location="index.php?app=stock_group&stock_type_id=<?php echo $stock_type_id;?>"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=stock_group&stock_type_id=<?php echo $stock_type_id;?>"</script>
<?php
        }
                    
    }else{
        ?>
    <script>window.location="index.php?app=stock_group&stock_type_id=<?php echo $stock_type_id;?>"</script>
        <?php
    }
    
                 
} else if ($license_inventery_page == "Low" || $license_inventery_page == "Medium" || $license_inventery_page == "High" ){

    $stock_groups = $model_stock_group->getStockGroupBy($stock_type_id);
    require_once($path.'view.inc.php');

}





?>