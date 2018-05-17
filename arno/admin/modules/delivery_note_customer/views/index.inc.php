<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/DeliveryNoteCustomerModel.php');
require_once('../models/DeliveryNoteCustomerListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/delivery_note_customer/views/";
$user_model = new UserModel;
$customer_model = new CustomerModel;
$delivery_note_customer_model = new DeliveryNoteCustomerModel;
$delivery_note_customer_list_model = new DeliveryNoteCustomerListModel;
$product_model = new ProductModel;
$first_char = "DNC";
$delivery_note_customer_id = $_GET['id'];
$target_dir = "../upload/delivery_note_customer/";

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();
    $delivery_note_customers = $delivery_note_customer_model->getDeliveryNoteCustomerBy($date_start,$date_end,$customer_id,$keyword);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $products=$product_model->getProductBy('','','','Active');
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $delivery_note_customer_model->getDeliveryNoteCustomerLastID($first_code,3);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $products=$product_model->getProductBy('','','','Active');
    $customers=$customer_model->getCustomerBy();
    $users=$user_model->getUserBy();
    $delivery_note_customer = $delivery_note_customer_model->getDeliveryNoteCustomerByID($delivery_note_customer_id);
    $customer=$customer_model->getCustomerByID($delivery_note_customer['customer_id']);
    $delivery_note_customer_lists = $delivery_note_customer_list_model->getDeliveryNoteCustomerListBy($delivery_note_customer_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $delivery_note_customer = $delivery_note_customer_model->getDeliveryNoteCustomerViewByID($delivery_note_customer_id);
    $delivery_note_customer_lists = $delivery_note_customer_list_model->getDeliveryNoteCustomerListBy($delivery_note_customer_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $delivery_note_customer = $delivery_note_customer_model->getDeliveryNoteCustomerViewByID($delivery_note_customer_id);
    $delivery_note_customer_lists = $delivery_note_customer_list_model->getDeliveryNoteCustomerListBy($delivery_note_customer_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){

    $delivery_note_customer_list_model->deleteDeliveryNoteCustomerListByDeliveryNoteCustomerID($delivery_note_customer_id);
    $delivery_note_customers = $delivery_note_customer_model->deleteDeliveryNoteCustomerById($delivery_note_customer_id);
?>
    <script>window.location="index.php?app=delivery_note_customer"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['delivery_note_customer_code'])){
  
        $check = true;

        $data = [];
        $data['delivery_note_customer_date'] = $_POST['delivery_note_customer_date'];
        $data['delivery_note_customer_code'] = $_POST['delivery_note_customer_code'];
        $data['contact_name'] = $_POST['contact_name'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['delivery_note_customer_remark'] = $_POST['delivery_note_customer_remark'];

        if($_FILES['delivery_note_customer_file']['name'] == ""){
            $data['delivery_note_customer_file'] = '';
        }else{
            
            $target_file = $target_dir .date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["delivery_note_customer_file"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["delivery_note_customer_file"]["size"] > 5000000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "pdf" ) {
                $error_msg = "Sorry, only PDF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["delivery_note_customer_file"]["tmp_name"], $target_file)) {
                $data['delivery_note_customer_file'] = date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["delivery_note_customer_file"]["name"]));
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

            $delivery_note_customer_id = $delivery_note_customer_model->insertDeliveryNoteCustomer($data);

            if($delivery_note_customer_id > 0){

                $product_id = $_POST['product_id'];
                $delivery_note_customer_list_id = $_POST['delivery_note_customer_list_id'];
                $delivery_note_customer_list_qty = $_POST['delivery_note_customer_list_qty'];
                $delivery_note_customer_list_remark = $_POST['delivery_note_customer_list_remark'];

                $delivery_note_customer_list_model->deleteDeliveryNoteCustomerListByDeliveryNoteCustomerIDNotIN($delivery_note_customer_id,$delivery_note_customer_list_id);

                if(is_array($product_id)){
                    for($i=0; $i < count($product_id) ; $i++){
                        $data = [];
                        $data['delivery_note_customer_id'] = $delivery_note_customer_id;
                        $data['product_id'] = $product_id[$i];
                        $data['delivery_note_customer_list_qty'] = $delivery_note_customer_list_qty[$i];
                        $data['delivery_note_customer_list_remark'] = $delivery_note_customer_list_remark[$i];

                        if ($delivery_note_customer_list_id[$i] != "" && $delivery_note_customer_list_id[$i] != '0'){
                            $delivery_note_customer_list_model->updateDeliveryNoteCustomerListById($data,$delivery_note_customer_list_id[$i]);
                        }else{
                            $delivery_note_customer_list_model->insertDeliveryNoteCustomerList($data);
                        }
                    }
                }else{
                    $data = [];
                    $data['delivery_note_customer_id'] = $delivery_note_customer_id;
                    $data['product_id'] = $product_id;
                    $data['delivery_note_customer_list_qty'] = $delivery_note_customer_list_qty;
                    $data['delivery_note_customer_list_remark'] = $delivery_note_customer_list_remark;
                    if ($delivery_note_customer_list_id != "" && $delivery_note_customer_list_id != '0'){
                        $delivery_note_customer_list_model->updateDeliveryNoteCustomerListById($data,$delivery_note_customer_list_id);
                    }else{
                        $delivery_note_customer_list_model->insertDeliveryNoteCustomerList($data);
                    }
                    
                }

    ?>
            <script>window.location="index.php?app=delivery_note_customer&action=update&id=<?php echo $delivery_note_customer_id;?>"</script>
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
    
    if(isset($_POST['delivery_note_customer_code'])){
        $data = [];
        $data['delivery_note_customer_date'] = $_POST['delivery_note_customer_date'];
        $data['delivery_note_customer_code'] = $_POST['delivery_note_customer_code'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['contact_name'] = $_POST['contact_name'];
        $data['delivery_note_customer_remark'] = $_POST['delivery_note_customer_remark'];

        $check = true;

        if($_FILES['delivery_note_customer_file']['name'] == ""){
            $data['delivery_note_customer_file'] = $_POST['delivery_note_customer_file_o'];
        }else {
            $target_file = $target_dir .date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["delivery_note_customer_file"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["delivery_note_customer_file"]["size"] > 5000000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "pdf" ) {
                $error_msg = "Sorry, only PDF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["delivery_note_customer_file"]["tmp_name"], $target_file)) {
                $data['delivery_note_customer_file'] = date("y").date("m").date("d").date("H").date("i"). date("s")."-". strtolower(basename($_FILES["delivery_note_customer_file"]["name"]));
                $target_file = $target_dir . $_POST["delivery_note_customer_file_o"];
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


            $output = $delivery_note_customer_model->updateDeliveryNoteCustomerByID($delivery_note_customer_id,$data);

            $product_id = $_POST['product_id'];
            $delivery_note_customer_list_id = $_POST['delivery_note_customer_list_id'];
            $delivery_note_customer_list_qty = $_POST['delivery_note_customer_list_qty'];
            $delivery_note_customer_list_remark = $_POST['delivery_note_customer_list_remark'];

            $delivery_note_customer_list_model->deleteDeliveryNoteCustomerListByDeliveryNoteCustomerIDNotIN($delivery_note_customer_id,$delivery_note_customer_list_id);

            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data = [];
                    $data['delivery_note_customer_id'] = $delivery_note_customer_id;
                    $data['product_id'] = $product_id[$i];
                    $data['delivery_note_customer_list_qty'] = $delivery_note_customer_list_qty[$i];
                    $data['delivery_note_customer_list_remark'] = $delivery_note_customer_list_remark[$i];

                    if ($delivery_note_customer_list_id[$i] != "" && $delivery_note_customer_list_id[$i] != '0'){
                        $delivery_note_customer_list_model->updateDeliveryNoteCustomerListById($data,$delivery_note_customer_list_id[$i]);
                    }else{
                        $delivery_note_customer_list_model->insertDeliveryNoteCustomerList($data);
                    }
                }
            }else{
                $data = [];
                $data['delivery_note_customer_id'] = $delivery_note_customer_id;
                $data['product_id'] = $product_id;
                $data['delivery_note_customer_list_qty'] = $delivery_note_customer_list_qty;
                $data['delivery_note_customer_list_remark'] = $delivery_note_customer_list_remark;
                if ($delivery_note_customer_list_id != "" && $delivery_note_customer_list_id != '0'){
                    $delivery_note_customer_list_model->updateDeliveryNoteCustomerListById($data,$delivery_note_customer_list_id);
                }else{
                    $delivery_note_customer_list_model->insertDeliveryNoteCustomerList($data);
                }
                
            }
            
            if($output){
    ?>
            <script>window.location="index.php?app=delivery_note_customer"</script>
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
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $customer_id = $_GET['customer_id'];
    $keyword = $_GET['keyword'];

    $customers=$customer_model->getCustomerBy();
    $delivery_note_customers = $delivery_note_customer_model->getDeliveryNoteCustomerBy($date_start,$date_end,$customer_id,$keyword);
    require_once($path.'view.inc.php');

}





?>