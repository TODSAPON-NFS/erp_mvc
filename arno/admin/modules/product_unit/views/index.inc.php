<?php
require_once('../models/ProductUnitModel.php');
$path = "modules/product_unit/views/";
$model_product_unit = new ProductUnitModel;
$product_unit_id = $_GET['id'];

if(!isset($_GET['action'])){
    $product_unit = $model_product_unit->getProductUnitByID($product_unit_id);
    $product_units = $model_product_unit->getProductUnitBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete'){

    $model_product_unit->deleteProductUnitByID($product_unit_id);
?>
    <script>window.location="index.php?app=product_unit&action=view&id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['product_unit_name'])){
        $data = [];
        $data['product_unit_name'] = $_POST['product_unit_name'];
        $data['product_unit_detail'] = $_POST['product_unit_detail'];
       
            $id = $model_product_unit->insertProductUnit($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=product_unit&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product_unit&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['product_unit_name'])){
        $data = [];
        $data['product_unit_name'] = $_POST['product_unit_name'];
        $data['product_unit_detail'] = $_POST['product_unit_detail'];
            
        $id = $model_product_unit->updateProductUnitByID($_POST['product_unit_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=product_unit&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product_unit&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else{
    $product_unit = $model_product_unit->getProductUnitByID($product_unit_id);
    $product_units = $model_product_unit->getProductUnitBy();
    require_once($path.'view.inc.php');

}





?>