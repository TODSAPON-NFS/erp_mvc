<?php
require_once('../models/ProductCategoryModel.php');
$path = "modules/product_category/views/";
$model_product_category = new ProductCategoryModel;
$product_category_id = $_GET['id'];

if(!isset($_GET['action'])){
    $product_category = $model_product_category->getProductCategoryByID($product_category_id);
    $product_categorys = $model_product_category->getProductCategoryBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_admin_page == 'High') ){

    $model_product_category->deleteProductCategoryByID($product_category_id);
?>
    <script>window.location="index.php?app=product_category&action=view&id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['product_category_name'])){
        $data = [];
        $data['product_category_name'] = $_POST['product_category_name'];
        $data['stock_event'] = $_POST['stock_event'];
       
            $id = $model_product_category->insertProductCategory($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=product_category&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product_category&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit'  && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['product_category_name'])){
        $data = [];
        $data['product_category_name'] = $_POST['product_category_name'];
        $data['stock_event'] = $_POST['stock_event'];
            
        $id = $model_product_category->updateProductCategoryByID($_POST['product_category_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=product_category&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product_category&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else{
    $product_category = $model_product_category->getProductCategoryByID($product_category_id);
    $product_categorys = $model_product_category->getProductCategoryBy();
    require_once($path.'view.inc.php');

}





?>