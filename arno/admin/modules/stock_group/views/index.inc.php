<?php
require_once('../models/StockGroupModel.php');
require_once('../models/StockModel.php');

$path = "modules/stock_group/views/";
$model_stock_group = new StockGroupModel;
$model_stock = new StockModel;

$stock_group_id = $_GET['id'];

if(!isset($_GET['action'])){

    $stock_groups = $model_stock_group->getStockGroupBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
   
    $stock_group = $model_stock_group->getStockGroupByID($stock_group_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'){

    $stock_group = $model_stock_group->getStockGroupByID($stock_group_id);
    $model_stock->setTableName($stock_group['table_name']);
    if($model_stock_group->deleteStockGroupById($stock_group_id)){
        $model_stock->deleteStockTable();
    ?>
        <script>window.location="index.php?app=stock_group"</script>
    <?php
    }  else{

    }
 
}else if ($_GET['action'] == 'add'){
    if(isset($_POST['stock_group_name'])){

        $data = [];
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
        <script>window.location="index.php?app=stock_group&action=update&id=<?php echo $id?>"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=stock_group"</script>
<?php
        }
                    
     
    }else{
        ?>
    <script>window.location="index.php?app=stock_group"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['stock_group_name'])){
        $data = [];
        $data['stock_group_name'] = $_POST['stock_group_name'];
        $data['stock_group_detail'] = $_POST['stock_group_detail'];
        $data['stock_group_notification'] = $_POST['stock_group_notification'];
        $data['stock_group_day'] = $_POST['stock_group_day'];
        $data['table_name'] = $_POST['table_name'];
       
        $user = $model_stock_group->updateStockGroupByID($_POST['stock_group_id'],$data);
        if($user){
?>
        <script>window.location="index.php?app=stock_group"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=stock_group"</script>
<?php
        }
                    
    }else{
        ?>
    <script>window.location="index.php?app=stock_group"</script>
        <?php
    }
    
                 
} else {

    $stock_groups = $model_stock_group->getStockGroupBy();
    require_once($path.'view.inc.php');

}





?>