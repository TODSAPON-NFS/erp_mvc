<?php
session_start();
$user = $_SESSION['user'];
require_once('../models/UserModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/CurrencyModel.php');
require_once('../models/AccountModel.php');
$path = "modules/supplier/views/";
$target_dir = "../upload/supplier/";
$model_user = new UserModel;
$model_supplier = new SupplierModel;

$notification_model = new NotificationModel;
$currency_model = new CurrencyModel;
$account_model = new AccountModel;
$supplier_id = $_GET['id'];
$notification_id = $_GET['notification'];




        //---------------------ฟังก์ชั่นวันที่------------------------------------
        date_default_timezone_set("Asia/Bangkok");
        $d1=date("d");
        $d2=date("m");
        $d3=date("Y");
        $d4=date("H");
        $d5=date("i");
        $d6=date("s");
        $date="$d1$d2$d3$d4$d5$d6";
        //---------------------------------------------------------------------


        //-----------------ฟังก์ชั่นสุ่มตัวเลข----------------
        $numrand = (mt_rand());
        //-----------------------------------------------





if(!isset($_GET['action'])){

    $supplier = $model_supplier->getSupplierBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'detail'){
    if($notification_id != ""){
        $notification_model->setNotificationSeenByID($notification_id);
    }
    
    $supplier = $model_supplier->getSupplierByID($supplier_id);
    $supplier_code = $supplier['supplier_code'];
    $supplier_name_th = $supplier['supplier_name_th'];
    $supplier_name_en = $supplier['supplier_name_en'];
    $supplier_type = $supplier['supplier_type'];
    $supplier_tax = $supplier['supplier_tax'];
    $supplier_address_1 = $supplier['supplier_address_1'];
    $supplier_address_2 = $supplier['supplier_address_2'];
    $supplier_address_3 = $supplier['supplier_address_3'];
    $supplier_zipcode = $supplier['supplier_zipcode'];
    $supplier_tel = $supplier['supplier_tel'];
    $supplier_fax = $supplier['supplier_fax'];
    $supplier_email = $supplier['supplier_email'];
    $supplier_domestic = $supplier['supplier_domestic'];
    $supplier_remark = $supplier['supplier_remark'];
    $supplier_branch = $supplier['supplier_branch'];
    $supplier_zone = $supplier['supplier_zone'];
    $credit_day = $supplier['credit_day'];
    $condition_pay = $supplier['condition_pay'];
    $pay_limit = $supplier['pay_limit'];
    $account_id = $supplier['account_id'];
    $vat_type = $supplier['vat_type'];
    $vat = $supplier['vat'];
    $currency_id = $supplier['currency_id'];
    $addby = $supplier['addby'];
    $adddate = $supplier['adddate'];
    $updateby = $supplier['updateby'];
    $lastupdate = $supplier['lastupdate'];
    $supplier_accept_by = $supplier['supplier_accept_by'];
    $supplier_accept_date = $supplier['supplier_accept_date'];
    $supplier_accept_status = $supplier['supplier_accept_status'];
    $currency_country = $supplier['currency_country'];
    $currency_name = $supplier['currency_name'];
    $currency_code = $supplier['currency_code'];
    $currency_sign = $supplier['currency_sign'];
    $currency_thousand = $supplier['currency_thousand'];
    $currency_decimal = $supplier['currency_decimal'];
    $product = $model_supplier->getSupplierProductBy($supplier_id);

    
    // echo "<pre>";
    // print_r($product);
    // echo"</pre>";

    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'insert'  && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){

    $user = $model_user->getUserBy();
    $currency = $currency_model->getCurrencyBy();
    $account = $account_model->getAccountAll();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'  && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){

    
    $supplier = $model_supplier->getSupplierByID($supplier_id);
    $user = $model_user->getUserByID($supplier['user_id']);
    $currency = $currency_model->getCurrencyBy();
    $account = $account_model->getAccountAll();
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'  && ( $license_admin_page == 'High') ){

    $supplier = $model_supplier->getSupplierByID($_GET['id']);
    if(count($supplier) > 0){
       
        $target_file = $target_dir . $supplier["supplier_logo"];
        if (file_exists($target_file)) {
            unlink($target_file);
        }
        $user = $model_supplier->deleteSupplierById($_GET['id']);
    }
    
?>
    <script>window.location="index.php?app=supplier"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['supplier_code'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_code'];
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
        $data['account_id'] = $_POST['account_id'];
        $data['vat_type'] = $_POST['vat_type'];
        $data['vat'] = $_POST['vat'];
        $data['currency_id'] = $_POST['currency_id'];

        $check = true;

        if($_FILES['supplier_logo']['name'] == ""){
            $data['supplier_logo'] = 'default.png';
        }else{
            

            
            
            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['supplier_logo']['name'],".");
            //--------------------------------------------------
            
            //-----ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม---------
            $newname = $date.$numrand.$type;
            $path_copy=$path.$newname;
            $path_link=$target_dir.$newname;
            //-------------------------------------------------

            $target_file = $target_dir .$date.$newname;



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
                
                
                //-----------------------------------
                $data['supplier_logo'] = $date.$newname;
                //-----------------------------------



                
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
                $notification_model->setNotification("Supplier Approve","Supplier Approve <br>Name. ".$data['supplier_name_en']."","index.php?app=supplier&action=detail&id=$id","license_manager_page","'High'");
        
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
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['supplier_code'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_code'];
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
        $data['account_id'] = $_POST['account_id'];
        $data['vat_type'] = $_POST['vat_type'];
        $data['vat'] = $_POST['vat'];
        $data['currency_id'] = $_POST['currency_id'];

        $check = true;

        if($_FILES['supplier_logo']['name'] == ""  ){
            $data['supplier_logo'] = $_POST['supplier_logo_o'];
        }else  {
            

            
            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['supplier_logo']['name'],".");
            //--------------------------------------------------
            
            //-----ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม---------
            $newname = $date.$numrand.$type;
            $path_copy=$path.$newname;
            $path_link=$target_dir.$newname;
            //-------------------------------------------------

            $target_file = $target_dir .$date.$newname;
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

                //-----------------------------------
                $data['supplier_logo'] = $date.$newname;
                //-----------------------------------

                $target_file = $target_dir . $_POST["supplier_logo_o"];
                if($_POST["supplier_logo_o"] != 'default.png'){
                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
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
    
}else if ($_GET['action'] == 'approve'){
    
    if(isset($_POST['supplier_accept_status'])){
        $data = [];
        $data['supplier_accept_status'] = $_POST['supplier_accept_status'];
        $data['supplier_accept_by'] = $user[0][0];
        $data['updateby'] = $user[0][0];

        $output = $model_supplier->updateSupplierAcceptByID($supplier_id,$data);


        if($output){
            $notification_model->setNotificationSeenByURL('app=supplier&action=detail&id='.$supplier_id);
        
?>
        <script>window.location="index.php?app=supplier"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
        
    
}else{

    $supplier = $model_supplier->getSupplierBy();
    require_once($path.'view.inc.php');

}





?>