<?php
require_once('../models/StockTypeModel.php');
require_once('../models/StockModel.php');
require_once('../models/StockGroupModel.php');

$path = "modules/stock_type/views/";
$model_stock_type = new StockTypeModel;
$model_stock = new StockModel;
$model_stock_group = new StockGroupModel;
$stock_type_id = $_GET['id'];

if(!isset($_GET['action'])){

    $stock_types = $model_stock_type->getStockTypeBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
   
    $stock_type = $model_stock_type->getStockTypeByID($stock_type_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'){

    if($model_stock_type->deleteStockTypeById($stock_type_id)){
    ?>
        <script>window.location="index.php?app=stock_type"</script>
    <?php
    }  else{

    }
 
}else if ($_GET['action'] == 'add'){
    if(isset($_POST['stock_type_code'])){

        $data = [];
        $data['stock_type_code'] = $_POST['stock_type_code'];
        $data['stock_type_name'] = $_POST['stock_type_name'];
        $data['stock_group_id'] = $_POST['stock_group_id'];

        $id = $model_stock_type->insertStockType($data);

        if($id > 0){
?>
        <script>window.location="index.php?app=stock_type&action=update&id=<?php echo $id?>"</script>
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
       
        $user = $model_stock_type->updateStockTypeByID($_POST['stock_type_id'],$data);
        if($user){
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