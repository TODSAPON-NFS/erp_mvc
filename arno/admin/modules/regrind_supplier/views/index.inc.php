<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/RegrindSupplierModel.php');
require_once('../models/RegrindSupplierListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/regrind_supplier/views/";
$user_model = new UserModel;
$supplier_model = new SupplierModel;
$regrind_supplier_model = new RegrindSupplierModel;
$regrind_supplier_list_model = new RegrindSupplierListModel;
$product_model = new ProductModel;
$first_char = "RG";
$regrind_supplier_id = $_GET['id'];
$target_dir = "../upload/regrind_supplier/";

if(!isset($_GET['action'])){

    $regrind_suppliers = $regrind_supplier_model->getRegrindSupplierBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $products=$product_model->getProductBy('','','','Active');
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $first_code = $first_char.date("y").date("m");
    $last_code = $regrind_supplier_model->getRegrindSupplierLastID($first_code,3);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $products=$product_model->getProductBy('','','','Active');
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $regrind_supplier = $regrind_supplier_model->getRegrindSupplierByID($regrind_supplier_id);
    $supplier=$supplier_model->getSupplierByID($regrind_supplier['supplier_id']);
    $regrind_supplier_lists = $regrind_supplier_list_model->getRegrindSupplierListBy($regrind_supplier_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $regrind_supplier = $regrind_supplier_model->getRegrindSupplierViewByID($regrind_supplier_id);
    $regrind_supplier_lists = $regrind_supplier_list_model->getRegrindSupplierListBy($regrind_supplier_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $regrind_supplier = $regrind_supplier_model->getRegrindSupplierViewByID($regrind_supplier_id);
    $regrind_supplier_lists = $regrind_supplier_list_model->getRegrindSupplierListBy($regrind_supplier_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){

    $regrind_supplier_list_model->deleteRegrindSupplierListByRegrindSupplierID($regrind_supplier_id);
    $regrind_suppliers = $regrind_supplier_model->deleteRegrindSupplierById($regrind_supplier_id);
?>
    <script>window.location="index.php?app=regrind_supplier"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['regrind_supplier_code'])){
  
        $check = true;

        $data = [];
        $data['regrind_supplier_date'] = date("Y")."-".date("m")."-".date("d");
        $data['regrind_supplier_code'] = $_POST['regrind_supplier_code'];
        $data['contact_name'] = $_POST['contact_name'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['regrind_supplier_remark'] = $_POST['regrind_supplier_remark'];

        if($_FILES['regrind_supplier_file']['name'] == ""){
            $data['regrind_supplier_file'] = '';
        }else{
            
            $target_file = $target_dir .date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["regrind_supplier_file"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["regrind_supplier_file"]["size"] > 5000000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "pdf" ) {
                $error_msg = "Sorry, only PDF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["regrind_supplier_file"]["tmp_name"], $target_file)) {
                $data['regrind_supplier_file'] = date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["regrind_supplier_file"]["name"]));
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

            $regrind_supplier_id = $regrind_supplier_model->insertRegrindSupplier($data);

            if($regrind_supplier_id > 0){

                $product_id = $_POST['product_id'];
                $regrind_supplier_list_id = $_POST['regrind_supplier_list_id'];
                $regrind_supplier_list_qty = $_POST['regrind_supplier_list_qty'];
                $regrind_supplier_list_remark = $_POST['regrind_supplier_list_remark'];

                $regrind_supplier_list_model->deleteRegrindSupplierListByRegrindSupplierIDNotIN($regrind_supplier_id,$regrind_supplier_list_id);

                if(is_array($product_id)){
                    for($i=0; $i < count($product_id) ; $i++){
                        $data = [];
                        $data['regrind_supplier_id'] = $regrind_supplier_id;
                        $data['product_id'] = $product_id[$i];
                        $data['regrind_supplier_list_qty'] = $regrind_supplier_list_qty[$i];
                        $data['regrind_supplier_list_remark'] = $regrind_supplier_list_remark[$i];

                        if ($regrind_supplier_list_id[$i] != "" && $regrind_supplier_list_id[$i] != '0'){
                            $regrind_supplier_list_model->updateRegrindSupplierListById($data,$regrind_supplier_list_id[$i]);
                        }else{
                            $regrind_supplier_list_model->insertRegrindSupplierList($data);
                        }
                    }
                }else{
                    $data = [];
                    $data['regrind_supplier_id'] = $regrind_supplier_id;
                    $data['product_id'] = $product_id;
                    $data['regrind_supplier_list_qty'] = $regrind_supplier_list_qty;
                    $data['regrind_supplier_list_remark'] = $regrind_supplier_list_remark;
                    if ($regrind_supplier_list_id != "" && $regrind_supplier_list_id != '0'){
                        $regrind_supplier_list_model->updateRegrindSupplierListById($data,$regrind_supplier_list_id);
                    }else{
                        $regrind_supplier_list_model->insertRegrindSupplierList($data);
                    }
                    
                }

    ?>
            <script>window.location="index.php?app=regrind_supplier&action=update&id=<?php echo $regrind_supplier_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.history.back();</script>
    <?php
            }   
        }
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['regrind_supplier_code'])){
        $data = [];
        $data['regrind_supplier_date'] = $_POST['regrind_supplier_date'];
        $data['regrind_supplier_code'] = $_POST['regrind_supplier_code'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['contact_name'] = $_POST['contact_name'];
        $data['regrind_supplier_remark'] = $_POST['regrind_supplier_remark'];

        $check = true;

        if($_FILES['regrind_supplier_file']['name'] == ""){
            $data['regrind_supplier_file'] = $_POST['regrind_supplier_file_o'];
        }else {
            $target_file = $target_dir .date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["regrind_supplier_file"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["regrind_supplier_file"]["size"] > 5000000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "pdf" ) {
                $error_msg = "Sorry, only PDF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["regrind_supplier_file"]["tmp_name"], $target_file)) {
                $data['regrind_supplier_file'] = date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["regrind_supplier_file"]["name"]));
                $target_file = $target_dir . $_POST["regrind_supplier_file_o"];
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


            $output = $regrind_supplier_model->updateRegrindSupplierByID($regrind_supplier_id,$data);

            $product_id = $_POST['product_id'];
            $regrind_supplier_list_id = $_POST['regrind_supplier_list_id'];
            $regrind_supplier_list_qty = $_POST['regrind_supplier_list_qty'];
            $regrind_supplier_list_remark = $_POST['regrind_supplier_list_remark'];

            $regrind_supplier_list_model->deleteRegrindSupplierListByRegrindSupplierIDNotIN($regrind_supplier_id,$regrind_supplier_list_id);

            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data = [];
                    $data['regrind_supplier_id'] = $regrind_supplier_id;
                    $data['product_id'] = $product_id[$i];
                    $data['regrind_supplier_list_qty'] = $regrind_supplier_list_qty[$i];
                    $data['regrind_supplier_list_remark'] = $regrind_supplier_list_remark[$i];

                    if ($regrind_supplier_list_id[$i] != "" && $regrind_supplier_list_id[$i] != '0'){
                        $regrind_supplier_list_model->updateRegrindSupplierListById($data,$regrind_supplier_list_id[$i]);
                    }else{
                        $regrind_supplier_list_model->insertRegrindSupplierList($data);
                    }
                }
            }else{
                $data = [];
                $data['regrind_supplier_id'] = $regrind_supplier_id;
                $data['product_id'] = $product_id;
                $data['regrind_supplier_list_qty'] = $regrind_supplier_list_qty;
                $data['regrind_supplier_list_remark'] = $regrind_supplier_list_remark;
                if ($regrind_supplier_list_id != "" && $regrind_supplier_list_id != '0'){
                    $regrind_supplier_list_model->updateRegrindSupplierListById($data,$regrind_supplier_list_id);
                }else{
                    $regrind_supplier_list_model->insertRegrindSupplierList($data);
                }
                
            }
            
            if($output){
    ?>
            <script>window.location="index.php?app=regrind_supplier"</script>
    <?php
            }else{
    ?>
            <script>window.history.back();</script>
    <?php
            }
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
     
    
}else{

    $regrind_suppliers = $regrind_supplier_model->getRegrindSupplierBy();
    require_once($path.'view.inc.php');

}





?>