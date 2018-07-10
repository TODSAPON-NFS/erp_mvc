<?php
require_once('../models/ProductTypeModel.php');
$path = "modules/product_type/views/";
$model_product_type = new ProductTypeModel;
$product_type_id = $_GET['id'];

if(!isset($_GET['action'])){
    $product_type = $model_product_type->getProductTypeByID($product_type_id);
    $product_types = $model_product_type->getProductTypeBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_admin_page == 'High') ){

    $model_product_type->deleteProductTypeByID($product_type_id);
?>
    <script>window.location="index.php?app=product_type&action=view&id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['product_type_name'])){
        $data = [];
        $data['product_type_name'] = $_POST['product_type_name'];
        $data['product_type_first_char'] = $_POST['product_type_first_char'];
        $data['product_type_auto'] = $_POST['product_type_auto'];
        $data['product_type_digit'] = $_POST['product_type_digit'];
        $data['product_type_detail'] = $_POST['product_type_detail'];
       
            $id = $model_product_type->insertProductType($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=product_type&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product_type&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['product_type_name'])){
        $data = [];
        $data['product_type_name'] = $_POST['product_type_name'];
        $data['product_type_first_char'] = $_POST['product_type_first_char'];
        $data['product_type_auto'] = $_POST['product_type_auto'];
        $data['product_type_digit'] = $_POST['product_type_digit'];
        $data['product_type_detail'] = $_POST['product_type_detail'];
            
        $id = $model_product_type->updateProductTypeByID($_POST['product_type_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=product_type&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product_type&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else{
    $product_type = $model_product_type->getProductTypeByID($product_type_id);
    $product_types = $model_product_type->getProductTypeBy();
    require_once($path.'view.inc.php');

}





?>