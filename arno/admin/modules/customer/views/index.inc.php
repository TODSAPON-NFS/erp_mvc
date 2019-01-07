<?php
require_once('../models/UserModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/AccountModel.php');
require_once('../models/CurrencyModel.php');
require_once('../models/CustomerTypeModel.php');

$path = "modules/customer/views/";
$target_dir = "../upload/customer/";

$currency_model = new CurrencyModel;
$customer_type_model = new CustomerTypeModel;
$model_user = new UserModel;
$model_customer = new customerModel;
$account_model = new AccountModel;


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


    $keyword = $_GET['keyword'];
    $keyword_end = $_GET['keyword_end'];
    $customer_type = $_GET['customer_type'];
    $end_user = $_GET['end_user'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100;
    
    $customer = $model_customer->getCustomerBy($customer_type,$end_user,$keyword,$keyword_end);
    $customer_types = $customer_type_model->getCustomerTypeBy();
    $page_max = (int)(count($customer)/$page_size);
    if(count($customer)%$page_size > 0){
        $page_max += 1;
    }
    
    require_once($path.'view.inc.php');
    

}else if ($_GET['action'] == 'insert' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){

    $user = $model_user->getUserBy('','sale');
    $account = $account_model->getAccountAll();
    $currency = $currency_model->getCurrencyBy();
    $customer_types = $customer_type_model->getCustomerTypeBy();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){

    $customer_id = $_GET['id'];
    $customer = $model_customer->getCustomerByID($customer_id);
    $user = $model_user->getUserBy('','sale');
    $account = $account_model->getAccountAll();
    $currency = $currency_model->getCurrencyBy();
    $customer_types = $customer_type_model->getCustomerTypeBy();

   require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){   
    
    $customer = $model_customer->getCustomerByID($_GET['id']);

    $header_page = "รายละเอียดลูกค้า";
    $customer_code =  $customer['customer_code'];
    $customer_name_th =  $customer['customer_name_th'];
    $customer_name_en =  $customer['customer_name_en'];
    $customer_type =  $customer['customer_type'];
    $customer_tax =  $customer['customer_tax'];
    $customer_address_1 =  $customer['customer_address_1'];
    $customer_address_2 =  $customer['customer_address_2'];
    $customer_address_3 =  $customer['customer_address_3'];
    $customer_zipcode =  $customer['customer_zipcode'];
    $customer_tel =  $customer['customer_tel'];
    $customer_fax =  $customer['customer_fax'];
    $customer_email =  $customer['customer_email'];
    $customer_domestic =  $customer['customer_domestic'];
    $customer_remark =  $customer['customer_remark'];
    $customer_branch =  $customer['customer_branch'];
    $customer_zone =  $customer['customer_zone'];
    $credit_day =  $customer['credit_day'];
    $condition_pay =  $customer['condition_pay'];
    $pay_limit =  $customer['pay_limit'];
    $account_id =  $customer['account_id'];
    $vat_type =  $customer['vat_type'];
    $vat =  $customer['vat'];
    $currency_id =  $customer['currency_id'];
    $customer_logo =  $customer['customer_logo'];
    $bill_shift =  $customer['bill_shift'];
    $invoice_shift =  $customer['invoice_shift'];
    $date_bill =  $customer['date_bill'];
    $date_invoice =  $customer['date_invoice'];
    $customer_end_user =  $customer['customer_end_user'];
    $sale_id =  $customer['sale_id'];
    $customer_type_id =  $customer['customer_type_id'];
    $addby =  $customer['addby'];
    $adddate =  $customer['adddate'];
    $updateby =  $customer['updateby'];
    $lastupdate =  $customer['lastupdate'];





    // echo "<pre>";
    // print_r($customer);
    // echo"</pre>";
   require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_admin_page == 'High') ){

    $customer = $model_customer->getCustomerByID($_GET['id']);
    if(count($customer) > 0){
       
        $target_file = $target_dir . $customer["customer_logo"];
        if (file_exists($target_file)) {
            unlink($target_file);
        }
        $user = $model_customer->deleteCustomerById($_GET['id']);
    }
    
?>
    <script>window.location="index.php?app=customer"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['customer_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_code'];
        $data['customer_code'] = $_POST['customer_code'];
        $data['customer_name_th'] = $_POST['customer_name_th'];
        $data['customer_name_en'] = $_POST['customer_name_en'];
        $data['customer_type'] = $_POST['customer_type'];
        $data['customer_tax'] = $_POST['customer_tax'];
        $data['customer_address_1'] = $_POST['customer_address_1'];
        $data['customer_address_2'] = $_POST['customer_address_2'];
        $data['customer_address_3'] = $_POST['customer_address_3'];
        $data['customer_zipcode'] = $_POST['customer_zipcode'];
        $data['customer_tel'] = $_POST['customer_tel'];
        $data['customer_fax'] = $_POST['customer_fax'];
        $data['customer_email'] = $_POST['customer_email'];
        $data['customer_domestic'] = $_POST['customer_domestic'];
        $data['customer_branch'] = $_POST['customer_branch'];
        $data['customer_remark'] = $_POST['customer_remark'];
        $data['customer_zone'] = $_POST['customer_zone'];
        $data['credit_day'] = $_POST['credit_day'];
        $data['condition_pay'] = $_POST['condition_pay'];
        $data['pay_limit'] = $_POST['pay_limit'];
        $data['account_id'] = $_POST['account_id'];
        $data['sale_id'] = $_POST['sale_id'];
        $data['customer_type_id'] = $_POST['customer_type_id'];
        $data['vat_type'] = $_POST['vat_type'];
        $data['vat'] = $_POST['vat'];
        $data['currency_id'] = $_POST['currency_id'];

        $check = true;

        if($_FILES['customer_logo']['name'] == ""){
            $data['customer_logo'] = 'default.png';
        }else{
            
            
            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['customer_logo']['name'],".");
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
            }else if ($_FILES["customer_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["customer_logo"]["tmp_name"], $target_file)) {
                
                
                //-----------------------------------
                $data['customer_logo'] = $date.$newname;
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
            $id = $model_customer->insertCustomer($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=customer&action=update&id=<?php echo $id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=customer&action=add"</script>
    <?php
            }
                    
        }
    }else{
        ?>
    <script>window.location="index.php?app=customer"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['customer_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_code'];
        $data['customer_code'] = $_POST['customer_code'];
        $data['customer_name_th'] = $_POST['customer_name_th'];
        $data['customer_name_en'] = $_POST['customer_name_en'];
        $data['customer_type'] = $_POST['customer_type'];
        $data['customer_tax'] = $_POST['customer_tax'];
        $data['customer_address_1'] = $_POST['customer_address_1'];
        $data['customer_address_2'] = $_POST['customer_address_2'];
        $data['customer_address_3'] = $_POST['customer_address_3'];
        $data['customer_zipcode'] = $_POST['customer_zipcode'];
        $data['customer_tel'] = $_POST['customer_tel'];
        $data['customer_fax'] = $_POST['customer_fax'];
        $data['customer_email'] = $_POST['customer_email'];
        $data['customer_domestic'] = $_POST['customer_domestic'];
        $data['customer_branch'] = $_POST['customer_branch'];
        $data['customer_remark'] = $_POST['customer_remark'];
        $data['customer_zone'] = $_POST['customer_zone'];
        $data['credit_day'] = $_POST['credit_day'];
        $data['condition_pay'] = $_POST['condition_pay'];
        $data['pay_limit'] = $_POST['pay_limit'];
        $data['account_id'] = $_POST['account_id'];
        $data['sale_id'] = $_POST['sale_id'];
        $data['customer_type_id'] = $_POST['customer_type_id'];
        $data['vat_type'] = $_POST['vat_type'];
        $data['vat'] = $_POST['vat'];
        $data['currency_id'] = $_POST['currency_id'];

        $check = true;

        if($_FILES['customer_logo']['name'] == ""){
            $data['customer_logo'] = $_POST['customer_logo_o'];
        }else {
            

            
            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['customer_logo']['name'],".");
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
            }else if ($_FILES["customer_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["customer_logo"]["tmp_name"], $target_file)) {

                //-----------------------------------
                $data['customer_logo'] = $date.$newname;
                //-----------------------------------

                $target_file = $target_dir . $_POST["customer_logo_o"];
                if($_POST["customer_logo_o"] != 'default.png'){
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
            $id = $model_customer->updateCustomerByID($_POST['customer_id'],$data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=customer&action=update&id=<?php echo $_POST['customer_id'];?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=customer&action=update&id=<?php echo $_POST['customer_id'];?>"</script>
    <?php
            }
                    
        }
    }else{
        ?>
    <script>window.location="index.php?app=customer"</script>
        <?php
    }
    
}else{

    $keyword = $_GET['keyword'];
    $keyword_end = $_GET['keyword_end'];
    $customer_type = $_GET['customer_type'];
    $end_user = $_GET['end_user'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100;
    
    $customer = $model_customer->getCustomerBy($customer_type,$end_user,$keyword,$keyword_end);
    $customer_types = $customer_type_model->getCustomerTypeBy();
    $page_max = (int)(count($customer)/$page_size);
    if(count($customer)%$page_size > 0){
        $page_max += 1;
    }
    
    require_once($path.'view.inc.php');

}





?>