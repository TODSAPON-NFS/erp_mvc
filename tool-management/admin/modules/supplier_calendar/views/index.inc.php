<?php
require_once('../models/UserModel.php');
require_once('../models/SupplierModel.php');

$path = "modules/supplier/views/";
$target_dir = "../upload/Supplier/";
$model_user = new UserModel;
$model_supplier = new SupplierModel;


if(!isset($_GET['action'])){

    $Supplier = $model_supplier->getSupplierBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){

    $user = $model_user->getUserBy();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){

    $supplier_id = $_GET['id'];
    $Supplier = $model_supplier->getSupplierByID($supplier_id);
    $user = $model_user->getUserByID($Supplier['user_id']);

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'){

    $Supplier = $model_supplier->getSupplierByID($_GET['id']);
    if(count($Supplier) > 0){
       
        $target_file = $target_dir . $Supplier["supplier_logo"];
        if (file_exists($target_file)) {
            unlink($target_file);
        }
        $user = $model_supplier->deleteSupplierById($_GET['id']);
    }
    
?>
    <script>window.location="index.php?app=supplier"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['supplier_code'])){
        $data = [];
        $data['supplier_code'] = $_POST['supplier_code'];
        $data['supplier_name_th'] = $_POST['supplier_name_th'];
        $data['supplier_name_en'] = $_POST['supplier_name_en'];
        $data['supplier_type'] = $_POST['supplier_type'];
        $data['supplier_tax'] = $_POST['supplier_tax'];
        $data['supplier_address_1'] = $_POST['supplier_address_1'];
        $data['supplier_address_2'] = $_POST['supplier_address_2'];
        $data['supplier_address_3'] = $_POST['supplier_address_3'];
        $data['supplier_zipcode'] = $_POST['supplier_zipcode'];
        $data['supplier_tel'] = $_POST['supplier_tel'];
        $data['supplier_fax'] = $_POST['supplier_fax'];
        $data['supplier_email'] = $_POST['supplier_email'];
        $data['supplier_domestic'] = $_POST['supplier_domestic'];
        $data['supplier_branch'] = $_POST['supplier_branch'];
        $data['supplier_remark'] = $_POST['supplier_remark'];
        $data['supplier_zone'] = $_POST['supplier_zone'];
        $data['credit_day'] = $_POST['credit_day'];
        $data['condition_pay'] = $_POST['condition_pay'];
        $data['pay_limit'] = $_POST['pay_limit'];

        $check = true;

        if($_FILES['supplier_logo']['name'] == ""){
            $data['supplier_logo'] = 'default.png';
        }else{
            
            $target_file = $target_dir . strtolower(basename($_FILES["supplier_logo"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["supplier_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["supplier_logo"]["tmp_name"], $target_file)) {
                $data['supplier_logo'] = strtolower($_FILES['supplier_logo']['name']);
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
            $id = $model_supplier->insertSupplier($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=supplier&action=update&id=<?php echo $id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=supplier&action=add"</script>
    <?php
            }
                    
        }
    }else{
        ?>
    <script>window.location="index.php?app=supplier"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['supplier_code'])){
        $data = [];
        $data['supplier_code'] = $_POST['supplier_code'];
        $data['supplier_name_th'] = $_POST['supplier_name_th'];
        $data['supplier_name_en'] = $_POST['supplier_name_en'];
        $data['supplier_type'] = $_POST['supplier_type'];
        $data['supplier_tax'] = $_POST['supplier_tax'];
        $data['supplier_address_1'] = $_POST['supplier_address_1'];
        $data['supplier_address_2'] = $_POST['supplier_address_2'];
        $data['supplier_address_3'] = $_POST['supplier_address_3'];
        $data['supplier_zipcode'] = $_POST['supplier_zipcode'];
        $data['supplier_tel'] = $_POST['supplier_tel'];
        $data['supplier_fax'] = $_POST['supplier_fax'];
        $data['supplier_email'] = $_POST['supplier_email'];
        $data['supplier_domestic'] = $_POST['supplier_domestic'];
        $data['supplier_branch'] = $_POST['supplier_branch'];
        $data['supplier_remark'] = $_POST['supplier_remark'];
        $data['supplier_zone'] = $_POST['supplier_zone'];
        $data['credit_day'] = $_POST['credit_day'];
        $data['condition_pay'] = $_POST['condition_pay'];
        $data['pay_limit'] = $_POST['pay_limit'];

        $check = true;

        if($_FILES['supplier_logo']['name'] == ""){
            $data['supplier_logo'] = $_POST['supplier_logo_o'];
        }else {
            
            $target_file = $target_dir . strtolower(basename($_FILES["supplier_logo"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["supplier_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["supplier_logo"]["tmp_name"], $target_file)) {
                $data['supplier_logo'] = strtolower($_FILES['supplier_logo']['name']);
                $target_file = $target_dir . $_POST["supplier_logo_o"];
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
            $id = $model_supplier->updateSupplierByID($_POST['supplier_id'],$data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=supplier&action=update&id=<?php echo $_POST['supplier_id'];?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=supplier&action=update&id=<?php echo $_POST['supplier_id'];?>"</script>
    <?php
            }
                    
        }
    }else{
        ?>
    <script>window.location="index.php?app=supplier"</script>
        <?php
    }
    
}else{

    $Supplier = $model_supplier->getSupplierBy();
    require_once($path.'view.inc.php');

}





?>