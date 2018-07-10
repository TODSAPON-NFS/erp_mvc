<?php
require_once('../models/ProductGroupModel.php');
$path = "modules/product_group/views/";
$model_product_group = new ProductGroupModel;
$product_group_id = $_GET['id'];

if(!isset($_GET['action'])){
    $product_group = $model_product_group->getProductGroupByID($product_group_id);
    $product_groups = $model_product_group->getProductGroupBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_admin_page == 'High') ){

    $model_product_group->deleteProductGroupByID($product_group_id);
?>
    <script>window.location="index.php?app=product_group&action=view&id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['product_group_name'])){
        $data = [];
        $data['product_group_name'] = $_POST['product_group_name'];
        $data['product_group_detail'] = $_POST['product_group_detail'];
       
            $id = $model_product_group->insertProductGroup($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=product_group&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product_group&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['product_group_name'])){
        $data = [];
        $data['product_group_name'] = $_POST['product_group_name'];
        $data['product_group_detail'] = $_POST['product_group_detail'];
            
        $id = $model_product_group->updateProductGroupByID($_POST['product_group_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=product_group&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product_group&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else{
    $product_group = $model_product_group->getProductGroupByID($product_group_id);
    $product_groups = $model_product_group->getProductGroupBy();
    require_once($path.'view.inc.php');

}





?>