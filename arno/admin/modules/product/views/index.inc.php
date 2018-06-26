<?php
require_once('../models/ProductModel.php');
require_once('../models/ProductGroupModel.php');
require_once('../models/ProductTypeModel.php');
require_once('../models/ProductCategoryModel.php');
require_once('../models/ProductUnitModel.php');
require_once('../models/ProductCustomerModel.php');
require_once('../models/ProductSupplierModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/SupplierModel.php');

$path = "modules/product/views/";
$model_product = new ProductModel;
$model_product_group = new ProductGroupModel;
$model_product_type = new ProductTypeModel;
$model_product_category = new ProductCategoryModel;
$model_product_unit = new ProductUnitModel;
$model_product_customer = new ProductCustomerModel;
$model_product_supplier = new ProductSupplierModel;
$model_customer = new CustomerModel;
$model_supplier = new SupplierModel;

$target_dir = "../upload/product/";
$product_id = $_GET['id'];
$product_supplier_id = $_GET['product_supplier_id'];
$product_customer_id = $_GET['product_customer_id'];
if(!isset($_GET['action'])){

    $supplier_id = $_GET['supplier_id'];
    $product_category_id = $_GET['product_category_id'];
    $product_type_id = $_GET['product_type_id'];
    $keyword = $_GET['keyword'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100;
    
    $product_type = $model_product_type->getProductTypeBy();
    $product_category = $model_product_category->getProductCategoryBy();
    $suppliers = $model_supplier->getSupplierBy();
    $product = $model_product->getProductBy($supplier_id,$product_category_id,$product_type_id,$keyword );

    $page_max = (int)(count($product)/$page_size);
    if(count($product)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){

    $product_group = $model_product_group->getProductGroupBy();
    $product_type = $model_product_type->getProductTypeBy();
    $product_category = $model_product_category->getProductCategoryBy();
    $product_unit = $model_product_unit->getProductUnitBy();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){

    
    $product = $model_product->getProductByID($product_id);
    $product_group = $model_product_group->getProductGroupBy();
    $product_type = $model_product_type->getProductTypeBy();
    $product_category = $model_product_category->getProductCategoryBy();
    $product_unit = $model_product_unit->getProductUnitBy();

    $product_customers = $model_product_customer->getProductCustomerBy($product_id);
    $product_suppliers = $model_product_supplier->getProductSupplierBy($product_id);

    $customer = $model_customer->getCustomerBy();
    $supplier = $model_supplier->getSupplierBy();

    if($product_supplier_id != ''){
        $product_supplier = $model_product_supplier->getProductSupplierByID($product_supplier_id);
    }

    if($product_customer_id != ''){
        $product_customer = $model_product_customer->getProductCustomerByID($product_customer_id);
    }

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'){

    if($product_supplier_id != ''){
        $model_product_supplier->deleteProductSupplierById($product_supplier_id);    
        ?>
        <script>window.location="index.php?app=product&action=update&id=<?php echo $product_id;?>"</script>
        <?php
    } else if($product_customer_id != ''){
        $model_product_customer->deleteProductCustomerById($product_customer_id);   
        ?>
        <script>window.location="index.php?app=product&action=update&id=<?php echo $product_id;?>"</script>
        <?php
    }else{
        $model_product->deleteProductById($product_id);     
        ?>
        <script>window.location="index.php?app=product"</script>
        <?php
    }


}else if ($_GET['action'] == 'add'){
    if(isset($_POST['product_name'])){

        $data = [];
        $data['product_code_first'] = $_POST['product_code_first'];
        $data['product_code'] = $_POST['product_code'];
        $data['product_name'] = $_POST['product_name'];
        $data['product_group'] = $_POST['product_group'];
        $data['product_barcode'] = $_POST['product_barcode'];
        $data['product_description'] = $_POST['product_description'];
        $data['product_type'] = $_POST['product_type'];
        $data['product_unit'] = $_POST['product_unit'];
        $data['product_status'] = $_POST['product_status'];
        $data['product_category_id'] = $_POST['product_category_id'];
        $check = true;

        if($_FILES['product_drawing']['name'] == ""){
            $data['product_drawing'] = '';
        }else{
            
            $target_file = $target_dir . strtolower(basename($_FILES["product_drawing"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["product_drawing"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "pdf" ) {
                $error_msg = "Sorry, only PDF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["product_drawing"]["tmp_name"], $target_file)) {
                $data['product_drawing'] = strtolower($_FILES['product_drawing']['name']);
            } else {
                $error_msg =  "Sorry, there was an error uploading your file.";
                $check = false;
            } 
        }

        if($_FILES['product_logo']['name'] == ""){
            $data['product_logo'] = "default.png";
        }else {
            $target_file = $target_dir . strtolower(basename($_FILES["product_logo"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["product_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["product_logo"]["tmp_name"], $target_file)) {
                $data['product_logo'] = strtolower($_FILES['product_logo']['name']);
            } else {
                $error_msg =  "Sorry, there was an error uploading your file.";
                $check = false;
            } 
        }

        if($check == false){
    ?>
        <script>
            alert('<?php echo $error_msg; ?>');
            window.history.back();
        </script>
    <?php
        }else{
            $pro = $model_product->insertProduct($data);

            if($pro > 0){
    ?>
            <script>window.location="index.php?app=product&action=update&id=<?php echo $pro?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product"</script>
    <?php
            }
                    
        }
     
    }else{
        ?>
    <script>window.location="index.php?app=product"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['product_name'])){
        $data = [];
        $data['product_code_first'] = $_POST['product_code_first'];
        $data['product_code'] = $_POST['product_code'];
        $data['product_name'] = $_POST['product_name'];
        $data['product_group'] = $_POST['product_group'];
        $data['product_barcode'] = $_POST['product_barcode'];
        $data['product_description'] = $_POST['product_description'];
        $data['product_type'] = $_POST['product_type'];
        $data['product_unit'] = $_POST['product_unit'];
        $data['product_status'] = $_POST['product_status'];
        $data['product_category_id'] = $_POST['product_category_id'];


        $check = true;

        if($_FILES['product_drawing']['name'] == ""){
            $data['product_drawing'] = $_POST['product_drawing_o'];
        }else {
            $target_file = $target_dir . strtolower(basename($_FILES["product_drawing"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["product_drawing"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "pdf" ) {
                $error_msg = "Sorry, only PDF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["product_drawing"]["tmp_name"], $target_file)) {
                $data['product_drawing'] = strtolower($_FILES['product_drawing']['name']);
                $target_file = $target_dir . $_POST["product_drawing_o"];
                if($_POST["product_logo_o"] != 'default.png'){
                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
                }
            } else {
                $error_msg =  "Sorry, there was an error uploading your file.";
                $check = false;
            } 
        }

        
        if($_FILES['product_logo']['name'] == ""){
            $data['product_logo'] = $_POST['product_logo_o'];
        }else {
            $target_file = $target_dir . strtolower(basename($_FILES["product_logo"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["product_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["product_logo"]["tmp_name"], $target_file)) {
                $data['product_logo'] = strtolower($_FILES['product_logo']['name']);
                $target_file = $target_dir . $_POST["product_logo_o"];
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
            } else {
                $error_msg =  "Sorry, there was an error uploading your file.";
                $check = false;
            } 
        }

        if($check == false){
    ?>
        <script>
            alert('<?php echo $error_msg; ?>');
            window.history.back();
        </script>
    <?php
        }else{
            $user = $model_product->updateProductByID($_POST['product_id'],$data);
            if($user){
    ?>
            <script>window.location="index.php?app=product"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product"</script>
    <?php
            }
                    
        }

    }else{
        ?>
    <script>window.location="index.php?app=product"</script>
        <?php
    }
    
        
        
    
}

else if ($_GET['action'] == 'add_customer'){
    if(isset($_POST['customer_id'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['product_id'] = $product_id;
        $data['minimum_stock'] = $_POST['minimum_stock'];
        $data['safety_stock'] = $_POST['safety_stock'];
        $data['product_status'] = $_POST['product_status'];


        $model_product_customer->insertProductCustomer($data);
        
        ?>
            <script>window.location="index.php?app=product&action=update&id=<?php echo $product_id?>"</script>
        <?php
                
     
    }else{
        ?>
    <script>window.location="index.php?app=product&action=update&id=<?php echo $product_id?>"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit_customer'){
    
    if(isset($_POST['customer_id'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['product_id'] = $product_id;
        $data['minimum_stock'] = $_POST['minimum_stock'];
        $data['safety_stock'] = $_POST['safety_stock'];
        $data['product_status'] = $_POST['product_status'];


        $model_product_customer->updateProductCustomerByID($_POST['product_customer_id'],$data);
        
        ?>
            <script>window.location="index.php?app=product&action=update&id=<?php echo $product_id?>"</script>
        <?php
                
    }else{
        ?>
    <script>window.location="index.php?app=product?action=update&id=<?php echo $product_id?>"</script>
        <?php
    }
     
}

else if ($_GET['action'] == 'add_supplier'){
    if(isset($_POST['supplier_id'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['product_id'] = $product_id;
        $data['product_buyprice'] = $_POST['product_buyprice'];
        $data['lead_time'] = $_POST['lead_time'];
        $data['product_supplier_status'] = $_POST['product_supplier_status'];


        $model_product_supplier->insertProductSupplier($data);
        
        ?>
            <script>window.location="index.php?app=product&action=update&id=<?php echo $product_id?>"</script>
        <?php
                
     
    }else{
        ?>
    <script>window.location="index.php?app=product&action=update&id=<?php echo $product_id?>"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit_supplier'){
    
    if(isset($_POST['supplier_id'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['product_id'] = $product_id;
        $data['product_buyprice'] = $_POST['product_buyprice'];
        $data['lead_time'] = $_POST['lead_time'];
        $data['product_supplier_status'] = $_POST['product_supplier_status'];


        $model_product_supplier->updateProductSupplierByID($_POST['product_supplier_id'],$data);
        
        ?>
            <script>window.location="index.php?app=product&action=update&id=<?php echo $product_id?>"</script>
        <?php
                
    }else{
        ?>
    <script>window.location="index.php?app=product?action=update&id=<?php echo $product_id?>"</script>
        <?php
    }
     
}

else{

    
    $supplier_id = $_GET['supplier_id'];
    $product_category_id = $_GET['product_category_id'];
    $product_type_id = $_GET['product_type_id'];
    $keyword = $_GET['keyword'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100;
    
    $product_type = $model_product_type->getProductTypeBy();
    $product_category = $model_product_category->getProductCategoryBy();
    $suppliers = $model_supplier->getSupplierBy();
    $product = $model_product->getProductBy($supplier_id,$product_category_id,$product_type_id,$keyword );

    $page_max = (int)(count($product)/$page_size);
    if(count($product)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view.inc.php');

}





?>