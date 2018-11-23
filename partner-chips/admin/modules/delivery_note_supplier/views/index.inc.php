<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/DeliveryNoteSupplierModel.php');
require_once('../models/DeliveryNoteSupplierListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/ProductSupplierModel.php');
require_once('../models/SupplierModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');


date_default_timezone_set('asia/bangkok');

$path = "modules/delivery_note_supplier/views/";
$user_model = new UserModel;
$supplier_model = new SupplierModel;
$delivery_note_supplier_model = new DeliveryNoteSupplierModel;
$delivery_note_supplier_list_model = new DeliveryNoteSupplierListModel;
$product_model = new ProductModel;
$product_supplier_model = new ProductSupplierModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('5');

$delivery_note_supplier_id = $_GET['id'];
$supplier_id = $_GET['supplier_id'];

$target_dir = "../upload/delivery_note_supplier/";


$delivery_note_supplier = $delivery_note_supplier_model->getDeliveryNoteSupplierByID($delivery_note_customer_id);
$employee_id = $delivery_note_supplier['employee_id'];


if(!isset($_GET['action'])  && ($license_delivery_note_page == 'Low' || $license_delivery_note_page == 'Medium' || $license_delivery_note_page == 'High')){
    if(!isset($_GET['date_start'])){
        $date_start = $_SESSION['date_start'];
    }else{
        $date_start = $_GET['date_start'];
        $_SESSION['date_start'] = $date_start;
    }


    if(!isset($_GET['date_end'])){
        $date_end = $_SESSION['date_end'];
    }else{
        $date_end = $_GET['date_end'];
        $_SESSION['date_end'] = $date_end;
    }

    if(!isset($_GET['keyword'])){
        $keyword = $_SESSION['keyword'];
    }else{
        
        $keyword = $_GET['keyword']; 
        $_SESSION['keyword'] = $keyword;
    }

    $supplier_id = $_GET['supplier_id'];

    $suppliers=$supplier_model->getSupplierBy();
    $supplier_orders = $delivery_note_supplier_model->getSupplierOrder();

    if($license_delivery_note_page == 'Medium' || $license_delivery_note_page == 'High'){
        $delivery_note_suppliers = $delivery_note_supplier_model->getDeliveryNoteSupplierBy($date_start,$date_end,$supplier_id,$keyword);
    }else{
        $delivery_note_suppliers = $delivery_note_supplier_model->getDeliveryNoteSupplierBy($date_start,$date_end,$supplier_id,$keyword,$admin_id);
    }
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_delivery_note_page == 'Low' || $license_delivery_note_page == 'Medium' || $license_delivery_note_page == 'High') ){
    $products=$product_model->getProductBy('','','','');
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();

    $user=$user_model->getUserByID($admin_id);

    $data = [];
    $data['year'] = date("Y");
    $data['month'] = date("m");
    $data['number'] = "0000000000";
    $data['employee_name'] = $user["user_name_en"];

    $code = $code_generate->cut2Array($paper['paper_code'],$data);
    $last_code = "";
    for($i = 0 ; $i < count($code); $i++){
    
        if($code[$i]['type'] == "number"){
            $last_code = $delivery_note_supplier_model->getDeliveryNoteSupplierLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }  

    if($supplier_id > 0){
        $supplier=$supplier_model->getSupplierByID($supplier_id);
        //$products=$product_supplier_model->getProductBySupplierID($supplier_id);
        $delivery_note_supplier_lists = $delivery_note_supplier_model->generateDeliveryNoteSupplierListBySupplierId($supplier_id);
    }

    $first_date = date("d")."-".date("m")."-".date("Y");
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && (( $license_delivery_note_page == 'Low' && $employee_id == $admin_id ) || $license_delivery_note_page == 'Medium' || $license_delivery_note_page == 'High') ){
    $products=$product_model->getProductBy('','','','');
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $delivery_note_supplier = $delivery_note_supplier_model->getDeliveryNoteSupplierByID($delivery_note_supplier_id);
    $supplier=$supplier_model->getSupplierByID($delivery_note_supplier['supplier_id']);
    $delivery_note_supplier_lists = $delivery_note_supplier_list_model->getDeliveryNoteSupplierListBy($delivery_note_supplier_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $delivery_note_supplier = $delivery_note_supplier_model->getDeliveryNoteSupplierViewByID($delivery_note_supplier_id);
    $delivery_note_supplier_lists = $delivery_note_supplier_list_model->getDeliveryNoteSupplierListBy($delivery_note_supplier_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $delivery_note_supplier = $delivery_note_supplier_model->getDeliveryNoteSupplierViewByID($delivery_note_supplier_id);
    $delivery_note_supplier_lists = $delivery_note_supplier_list_model->getDeliveryNoteSupplierListBy($delivery_note_supplier_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete' && ( ( $license_delivery_note_page == 'Low' && $employee_id == $admin_id ) || $license_delivery_note_page == 'High') ){

    $delivery_note_supplier_list_model->deleteDeliveryNoteSupplierListByDeliveryNoteSupplierID($delivery_note_supplier_id);
    $delivery_note_suppliers = $delivery_note_supplier_model->deleteDeliveryNoteSupplierById($delivery_note_supplier_id);
?>
    <script>window.location="index.php?app=delivery_note_supplier"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_delivery_note_page == 'Low' || $license_delivery_note_page == 'Medium' || $license_delivery_note_page == 'High') ){
    if(isset($_POST['delivery_note_supplier_code'])){
  
        $check = true;

        $data = [];
        $data['delivery_note_supplier_date'] = $_POST['delivery_note_supplier_date'];
        $data['delivery_note_supplier_code'] = $_POST['delivery_note_supplier_code'];
        $data['contact_name'] = $_POST['contact_name'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['delivery_note_supplier_remark'] = $_POST['delivery_note_supplier_remark'];

        if($_FILES['delivery_note_supplier_file']['name'] == ""){
            $data['delivery_note_supplier_file'] = '';
        }else{
            
            $target_file = $target_dir .date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["delivery_note_supplier_file"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["delivery_note_supplier_file"]["size"] > 5000000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "pdf" ) {
                $error_msg = "Sorry, only PDF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["delivery_note_supplier_file"]["tmp_name"], $target_file)) {
                $data['delivery_note_supplier_file'] = date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["delivery_note_supplier_file"]["name"]));
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

            $delivery_note_supplier_id = $delivery_note_supplier_model->insertDeliveryNoteSupplier($data);

            if($delivery_note_supplier_id > 0){

                $product_id = $_POST['product_id'];
                $request_test_list_id = $_POST['request_test_list_id'];
                $delivery_note_supplier_list_id = $_POST['delivery_note_supplier_list_id'];
                $delivery_note_supplier_list_qty = $_POST['delivery_note_supplier_list_qty'];
                $delivery_note_supplier_list_remark = $_POST['delivery_note_supplier_list_remark'];

                $delivery_note_supplier_list_model->deleteDeliveryNoteSupplierListByDeliveryNoteSupplierIDNotIN($delivery_note_supplier_id,$delivery_note_supplier_list_id);

                if(is_array($product_id)){
                    for($i=0; $i < count($product_id) ; $i++){
                        $data = [];
                        $data['delivery_note_supplier_id'] = $delivery_note_supplier_id;
                        $data['request_test_list_id'] = $request_test_list_id[$i];
                        $data['product_id'] = $product_id[$i];
                        $data['delivery_note_supplier_list_qty'] = $delivery_note_supplier_list_qty[$i];
                        $data['delivery_note_supplier_list_remark'] = $delivery_note_supplier_list_remark[$i];

                        if ($delivery_note_supplier_list_id[$i] != "" && $delivery_note_supplier_list_id[$i] != '0'){
                            $delivery_note_supplier_list_model->updateDeliveryNoteSupplierListById($data,$delivery_note_supplier_list_id[$i]);
                        }else{
                            $delivery_note_supplier_list_model->insertDeliveryNoteSupplierList($data);
                        }
                    }
                }else{
                    $data = [];
                    $data['delivery_note_supplier_id'] = $delivery_note_supplier_id;
                    $data['request_test_list_id'] = $request_test_list_id;
                    $data['product_id'] = $product_id;
                    $data['delivery_note_supplier_list_qty'] = $delivery_note_supplier_list_qty;
                    $data['delivery_note_supplier_list_remark'] = $delivery_note_supplier_list_remark;
                    if ($delivery_note_supplier_list_id != "" && $delivery_note_supplier_list_id != '0'){
                        $delivery_note_supplier_list_model->updateDeliveryNoteSupplierListById($data,$delivery_note_supplier_list_id);
                    }else{
                        $delivery_note_supplier_list_model->insertDeliveryNoteSupplierList($data);
                    }
                    
                }

    ?>
            <script>window.location="index.php?app=delivery_note_supplier&action=update&id=<?php echo $delivery_note_supplier_id;?>"</script>
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
    
}else if ($_GET['action'] == 'edit' && (( $license_delivery_note_page == 'Low' && $employee_id == $admin_id ) || $license_delivery_note_page == 'Medium' || $license_delivery_note_page == 'High') ){
    
    if(isset($_POST['delivery_note_supplier_code'])){
        $data = [];
        $data['delivery_note_supplier_date'] = $_POST['delivery_note_supplier_date'];
        $data['delivery_note_supplier_code'] = $_POST['delivery_note_supplier_code'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['contact_name'] = $_POST['contact_name'];
        $data['delivery_note_supplier_remark'] = $_POST['delivery_note_supplier_remark'];

        $check = true;

        if($_FILES['delivery_note_supplier_file']['name'] == ""){
            $data['delivery_note_supplier_file'] = $_POST['delivery_note_supplier_file_o'];
        }else {
            $target_file = $target_dir .date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["delivery_note_supplier_file"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["delivery_note_supplier_file"]["size"] > 5000000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "pdf" ) {
                $error_msg = "Sorry, only PDF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["delivery_note_supplier_file"]["tmp_name"], $target_file)) {
                $data['delivery_note_supplier_file'] = date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["delivery_note_supplier_file"]["name"]));
                $target_file = $target_dir . $_POST["delivery_note_supplier_file_o"];
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


            $output = $delivery_note_supplier_model->updateDeliveryNoteSupplierByID($delivery_note_supplier_id,$data);

            $product_id = $_POST['product_id'];
            $request_test_list_id = $_POST['request_test_list_id'];
            $delivery_note_supplier_list_id = $_POST['delivery_note_supplier_list_id'];
            $delivery_note_supplier_list_qty = $_POST['delivery_note_supplier_list_qty'];
            $delivery_note_supplier_list_remark = $_POST['delivery_note_supplier_list_remark'];

            $delivery_note_supplier_list_model->deleteDeliveryNoteSupplierListByDeliveryNoteSupplierIDNotIN($delivery_note_supplier_id,$delivery_note_supplier_list_id);

            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data = [];
                    $data['delivery_note_supplier_id'] = $delivery_note_supplier_id;
                    $data['request_test_list_id'] = $request_test_list_id[$i];
                    $data['product_id'] = $product_id[$i];
                    $data['delivery_note_supplier_list_qty'] = $delivery_note_supplier_list_qty[$i];
                    $data['delivery_note_supplier_list_remark'] = $delivery_note_supplier_list_remark[$i];

                    if ($delivery_note_supplier_list_id[$i] != "" && $delivery_note_supplier_list_id[$i] != '0'){
                        $delivery_note_supplier_list_model->updateDeliveryNoteSupplierListById($data,$delivery_note_supplier_list_id[$i]);
                    }else{
                        $delivery_note_supplier_list_model->insertDeliveryNoteSupplierList($data);
                    }
                }
            }else{
                $data = [];
                $data['delivery_note_supplier_id'] = $delivery_note_supplier_id;
                $data['request_test_list_id'] = $request_test_list_id;
                $data['product_id'] = $product_id;
                $data['delivery_note_supplier_list_qty'] = $delivery_note_supplier_list_qty;
                $data['delivery_note_supplier_list_remark'] = $delivery_note_supplier_list_remark;
                if ($delivery_note_supplier_list_id != "" && $delivery_note_supplier_list_id != '0'){
                    $delivery_note_supplier_list_model->updateDeliveryNoteSupplierListById($data,$delivery_note_supplier_list_id);
                }else{
                    $delivery_note_supplier_list_model->insertDeliveryNoteSupplierList($data);
                }
                
            }
            
            if($output){
    ?>
            <script>window.location="index.php?app=delivery_note_supplier"</script>
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
        
     
    
}elseif($license_delivery_note_page == 'Low' || $license_delivery_note_page == 'Medium' || $license_delivery_note_page == 'High'){

    if(!isset($_GET['date_start'])){
        $date_start = $_SESSION['date_start'];
    }else{
        $date_start = $_GET['date_start'];
        $_SESSION['date_start'] = $date_start;
    }


    if(!isset($_GET['date_end'])){
        $date_end = $_SESSION['date_end'];
    }else{
        $date_end = $_GET['date_end'];
        $_SESSION['date_end'] = $date_end;
    }

    if(!isset($_GET['keyword'])){
        $keyword = $_SESSION['keyword'];
    }else{
        
        $keyword = $_GET['keyword']; 
        $_SESSION['keyword'] = $keyword;
    }
    
    $supplier_id = $_GET['supplier_id'];

    $suppliers=$supplier_model->getSupplierBy();
    $supplier_orders = $delivery_note_supplier_model->getSupplierOrder();
    if($license_delivery_note_page == 'Medium' || $license_delivery_note_page == 'High'){
        $delivery_note_suppliers = $delivery_note_supplier_model->getDeliveryNoteSupplierBy($date_start,$date_end,$supplier_id,$keyword);
    }else{
        $delivery_note_suppliers = $delivery_note_supplier_model->getDeliveryNoteSupplierBy($date_start,$date_end,$supplier_id,$keyword,$admin_id);
    }
    require_once($path.'view.inc.php');

}





?>