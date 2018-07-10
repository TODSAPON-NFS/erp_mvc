<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/RegrindSupplierReceiveModel.php');
require_once('../models/RegrindSupplierReceiveListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/regrind_supplier_receive/views/";
$user_model = new UserModel;
$supplier_model = new SupplierModel;
$regrind_supplier_receive_model = new RegrindSupplierReceiveModel;
$regrind_supplier_receive_list_model = new RegrindSupplierReceiveListModel;
$product_model = new ProductModel;
$first_char = "RGR";
$regrind_supplier_receive_id = $_GET['id'];
$regrind_supplier_id = $_GET['regrind_supplier_id'];
$supplier_id = $_GET['supplier_id'];
$target_dir = "../upload/regrind_supplier_receive/";

$regrind_supplier_receive = $regrind_supplier_receive_model->getRegrindSupplierReceiveByID($regrind_supplier_receive_id);
$employee_id = $regrind_supplier_receive['employee_id'];


if(!isset($_GET['action']) && ($license_regrind_page == "Low" || $license_regrind_page == "Medium" || $license_regrind_page == "High" ) ){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();

    $supplier_receives = $regrind_supplier_receive_model->getSupplier($admin_id);
    $regrind_suppliers = $regrind_supplier_receive_model->getRegrindSupplier($admin_id);

    if($license_regrind_page == "Medium" || $license_regrind_page == "High" ){
        $regrind_supplier_receives = $regrind_supplier_receive_model->getRegrindSupplierReceiveBy($date_start,$date_end,$supplier_id,$keyword);
    }else{
        $regrind_supplier_receives = $regrind_supplier_receive_model->getRegrindSupplierReceiveBy($date_start,$date_end,$supplier_id,$keyword,$admin_id);
    }
    

    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && (($license_regrind_page == "Low" ) || $license_regrind_page == "Medium" || $license_regrind_page == "High" )){
    if($supplier_id > 0){
        $supplier=$supplier_model->getSupplierByID($supplier_id);
        $regrind_supplier_receive_lists = $regrind_supplier_receive_model->generateRegrindSupplierReceiveListBySupplierId($supplier_id,[] ,$_POST['search']);
    }
    $products=$product_model->getProductBy('','','','');
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $regrind_supplier_receive_model->getRegrindSupplierReceiveLastID($first_code,3);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && (($license_regrind_page == "Low" && $admin_id == $employee) || $license_regrind_page == "Medium" || $license_regrind_page == "High" )){
    $products=$product_model->getProductBy('','','','');
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $regrind_supplier_receive = $regrind_supplier_receive_model->getRegrindSupplierReceiveByID($regrind_supplier_receive_id);
    $supplier=$supplier_model->getSupplierByID($regrind_supplier_receive['supplier_id']);
    $regrind_supplier_receive_lists = $regrind_supplier_receive_list_model->getRegrindSupplierReceiveListBy($regrind_supplier_receive_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $regrind_supplier_receive = $regrind_supplier_receive_model->getRegrindSupplierReceiveViewByID($regrind_supplier_receive_id);
    $regrind_supplier_receive_lists = $regrind_supplier_receive_list_model->getRegrindSupplierReceiveListBy($regrind_supplier_receive_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $regrind_supplier_receive = $regrind_supplier_receive_model->getRegrindSupplierReceiveViewByID($regrind_supplier_receive_id);
    $regrind_supplier_receive_lists = $regrind_supplier_receive_list_model->getRegrindSupplierReceiveListBy($regrind_supplier_receive_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete' && (($license_regrind_page == "Low" && $admin_id == $employee) || $license_regrind_page == "High" )){

    $regrind_supplier_receive_list_model->deleteRegrindSupplierReceiveListByRegrindSupplierReceiveID($regrind_supplier_receive_id);
    $regrind_supplier_receives = $regrind_supplier_receive_model->deleteRegrindSupplierReceiveById($regrind_supplier_receive_id);
?>
    <script>window.location="index.php?app=regrind_supplier_receive"</script>
<?php

}else if ($_GET['action'] == 'add' && (($license_regrind_page == "Low") || $license_regrind_page == "Medium" || $license_regrind_page == "High" )){
    if(isset($_POST['regrind_supplier_receive_code'])){
  
        $check = true;

        $data = [];
        $data['regrind_supplier_receive_date'] = $_POST['regrind_supplier_receive_date'];
        $data['regrind_supplier_receive_code'] = $_POST['regrind_supplier_receive_code'];
        $data['contact_name'] = $_POST['contact_name'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['regrind_supplier_receive_remark'] = $_POST['regrind_supplier_receive_remark'];

        if($_FILES['regrind_supplier_receive_file']['name'] == ""){
            $data['regrind_supplier_receive_file'] = '';
        }else{
            
            $target_file = $target_dir .date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["regrind_supplier_receive_file"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["regrind_supplier_receive_file"]["size"] > 5000000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "pdf" ) {
                $error_msg = "Sorry, only PDF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["regrind_supplier_receive_file"]["tmp_name"], $target_file)) {
                $data['regrind_supplier_receive_file'] = date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["regrind_supplier_receive_file"]["name"]));
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

            $regrind_supplier_receive_id = $regrind_supplier_receive_model->insertRegrindSupplierReceive($data);

            if($regrind_supplier_receive_id > 0){

                $product_id = $_POST['product_id'];
                $regrind_supplier_list_id = $_POST['regrind_supplier_list_id'];
                $regrind_supplier_receive_list_id = $_POST['regrind_supplier_receive_list_id'];
                $regrind_supplier_receive_list_qty = $_POST['regrind_supplier_receive_list_qty'];
                $regrind_supplier_receive_list_remark = $_POST['regrind_supplier_receive_list_remark'];

                $regrind_supplier_receive_list_model->deleteRegrindSupplierReceiveListByRegrindSupplierReceiveIDNotIN($regrind_supplier_receive_id,$regrind_supplier_receive_list_id);

                if(is_array($product_id)){
                    for($i=0; $i < count($product_id) ; $i++){
                        $data = [];
                        $data['regrind_supplier_list_id'] = $regrind_supplier_list_id[$i];
                        $data['regrind_supplier_receive_id'] = $regrind_supplier_receive_id;
                        $data['product_id'] = $product_id[$i];
                        $data['regrind_supplier_receive_list_qty'] = $regrind_supplier_receive_list_qty[$i];
                        $data['regrind_supplier_receive_list_remark'] = $regrind_supplier_receive_list_remark[$i];

                        if ($regrind_supplier_receive_list_id[$i] != "" && $regrind_supplier_receive_list_id[$i] != '0'){
                            $regrind_supplier_receive_list_model->updateRegrindSupplierReceiveListById($data,$regrind_supplier_receive_list_id[$i]);
                        }else{
                            $regrind_supplier_receive_list_model->insertRegrindSupplierReceiveList($data);
                        }
                    }
                }else{
                    $data = [];
                    $data['regrind_supplier_list_id'] = $regrind_supplier_list_id;
                    $data['regrind_supplier_receive_id'] = $regrind_supplier_receive_id;
                    $data['product_id'] = $product_id;
                    $data['regrind_supplier_receive_list_qty'] = $regrind_supplier_receive_list_qty;
                    $data['regrind_supplier_receive_list_remark'] = $regrind_supplier_receive_list_remark;
                    if ($regrind_supplier_receive_list_id != "" && $regrind_supplier_receive_list_id != '0'){
                        $regrind_supplier_receive_list_model->updateRegrindSupplierReceiveListById($data,$regrind_supplier_receive_list_id);
                    }else{
                        $regrind_supplier_receive_list_model->insertRegrindSupplierReceiveList($data);
                    }
                    
                }

    ?>
            <script>window.location="index.php?app=regrind_supplier_receive&action=update&id=<?php echo $regrind_supplier_receive_id;?>"</script>
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
    
}else if ($_GET['action'] == 'edit' &&  (($license_regrind_page == "Low" && $admin_id == $employee) || $license_regrind_page == "Medium" || $license_regrind_page == "High" )){
    
    if(isset($_POST['regrind_supplier_receive_code'])){
        $data = [];
        $data['regrind_supplier_receive_date'] = $_POST['regrind_supplier_receive_date'];
        $data['regrind_supplier_receive_code'] = $_POST['regrind_supplier_receive_code'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['contact_name'] = $_POST['contact_name'];
        $data['regrind_supplier_receive_remark'] = $_POST['regrind_supplier_receive_remark'];

        $check = true;

        if($_FILES['regrind_supplier_receive_file']['name'] == ""){
            $data['regrind_supplier_receive_file'] = $_POST['regrind_supplier_receive_file_o'];
        }else {
            $target_file = $target_dir .date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["regrind_supplier_receive_file"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["regrind_supplier_receive_file"]["size"] > 5000000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "pdf" ) {
                $error_msg = "Sorry, only PDF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["regrind_supplier_receive_file"]["tmp_name"], $target_file)) {
                $data['regrind_supplier_receive_file'] = date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["regrind_supplier_receive_file"]["name"]));
                $target_file = $target_dir . $_POST["regrind_supplier_receive_file_o"];
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


            $output = $regrind_supplier_receive_model->updateRegrindSupplierReceiveByID($regrind_supplier_receive_id,$data);

            $product_id = $_POST['product_id'];
            $regrind_supplier_list_id = $_POST['regrind_supplier_list_id'];
            $regrind_supplier_receive_list_id = $_POST['regrind_supplier_receive_list_id'];
            $regrind_supplier_receive_list_qty = $_POST['regrind_supplier_receive_list_qty'];
            $regrind_supplier_receive_list_remark = $_POST['regrind_supplier_receive_list_remark'];

            $regrind_supplier_receive_list_model->deleteRegrindSupplierReceiveListByRegrindSupplierReceiveIDNotIN($regrind_supplier_receive_id,$regrind_supplier_receive_list_id);

            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data = [];

                    $data['regrind_supplier_list_id'] = $regrind_supplier_list_id;
                    $data['product_id'] = $product_id[$i];
                    $data['regrind_supplier_receive_list_qty'] = $regrind_supplier_receive_list_qty[$i];
                    $data['regrind_supplier_receive_list_remark'] = $regrind_supplier_receive_list_remark[$i];

                    if ($regrind_supplier_receive_list_id[$i] != "" && $regrind_supplier_receive_list_id[$i] != '0'){
                        $regrind_supplier_receive_list_model->updateRegrindSupplierReceiveListById($data,$regrind_supplier_receive_list_id[$i]);
                    }else{
                        $regrind_supplier_receive_list_model->insertRegrindSupplierReceiveList($data);
                    }
                }
            }else{
                $data = [];
                $data['regrind_supplier_list_id'] = $regrind_supplier_list_id;
                $data['product_id'] = $product_id;
                $data['regrind_supplier_receive_list_qty'] = $regrind_supplier_receive_list_qty;
                $data['regrind_supplier_receive_list_remark'] = $regrind_supplier_receive_list_remark;
                if ($regrind_supplier_receive_list_id != "" && $regrind_supplier_receive_list_id != '0'){
                    $regrind_supplier_receive_list_model->updateRegrindSupplierReceiveListById($data,$regrind_supplier_receive_list_id);
                }else{
                    $regrind_supplier_receive_list_model->insertRegrindSupplierReceiveList($data);
                }
                
            }
            
            if($output){
    ?>
            <script>window.location="index.php?app=regrind_supplier_receive"</script>
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
        
     
    
}else if($license_regrind_page == "Low" || $license_regrind_page == "Medium" || $license_regrind_page == "High" ){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();

    $supplier_receives = $regrind_supplier_receive_model->getSupplier($admin_id);
    $regrind_suppliers = $regrind_supplier_receive_model->getRegrindSupplier($admin_id);
    

    if($license_regrind_page == "Medium" || $license_regrind_page == "High" ){
        $regrind_supplier_receives = $regrind_supplier_receive_model->getRegrindSupplierReceiveBy($date_start,$date_end,$supplier_id,$keyword);
    }else{
        $regrind_supplier_receives = $regrind_supplier_receive_model->getRegrindSupplierReceiveBy($date_start,$date_end,$supplier_id,$keyword,$admin_id);
    }
    
    require_once($path.'view.inc.php');

}





?>