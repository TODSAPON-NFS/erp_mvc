<?php
require_once('../models/UserModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/AccountModel.php');
require_once('../models/CurrencyModel.php');

$path = "modules/customer/views/";
$target_dir = "../upload/customer/";

$currency_model = new CurrencyModel;
$model_user = new UserModel;
$model_customer = new customerModel;
$account_model = new AccountModel;

if(!isset($_GET['action'])){


    $keyword = $_GET['keyword'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100;
    
    $customer = $model_customer->getCustomerBy($keyword);

    $page_max = (int)(count($customer)/$page_size);
    if(count($customer)%$page_size > 0){
        $page_max += 1;
    }
    
    require_once($path.'view.inc.php');
    

}else if ($_GET['action'] == 'insert'){

    $user = $model_user->getUserBy();
    $account = $account_model->getAccountAll();
    $currency = $currency_model->getCurrencyBy();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){

    $customer_id = $_GET['id'];
    $customer = $model_customer->getCustomerByID($customer_id);
    $user = $model_user->getUserByID($customer['user_id']);
    $account = $account_model->getAccountAll();
    $currency = $currency_model->getCurrencyBy();

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'){

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

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['customer_code'])){
        $data = [];
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
        $data['vat_type'] = $_POST['vat_type'];
        $data['vat'] = $_POST['vat'];
        $data['currency_id'] = $_POST['currency_id'];

        $check = true;

        if($_FILES['customer_logo']['name'] == ""){
            $data['customer_logo'] = 'default.png';
        }else{
            
            $target_file = $target_dir . strtolower(basename($_FILES["customer_logo"]["name"]));
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
                $data['customer_logo'] = strtolower($_FILES['customer_logo']['name']);
                
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
    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['customer_code'])){
        $data = [];
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
        $data['vat_type'] = $_POST['vat_type'];
        $data['vat'] = $_POST['vat'];
        $data['currency_id'] = $_POST['currency_id'];

        $check = true;

        if($_FILES['customer_logo']['name'] == ""){
            $data['customer_logo'] = $_POST['customer_logo_o'];
        }else {
            
            $target_file = $target_dir . strtolower(basename($_FILES["customer_logo"]["name"]));
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
                $data['customer_logo'] = strtolower($_FILES['customer_logo']['name']);
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

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100;
    
    $customer = $model_customer->getCustomerBy($keyword);

    $page_max = (int)(count($customer)/$page_size);
    if(count($customer)%$page_size > 0){
        $page_max += 1;
    }
    
    require_once($path.'view.inc.php');

}





?>