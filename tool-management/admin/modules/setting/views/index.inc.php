<?php
session_start(); 
date_default_timezone_set('asia/bangkok');
require_once('../models/CompanyModel.php'); 
$path = "modules/setting/views/";
$target_dir = "../upload/company/";
$company_model = new CompanyModel;

$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s"); 


if( (!isset($_GET['action']) || $_GET['action'] != 'edit')  && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){

    
    $company = $company_model->getCompanyByID('1'); 
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['company_name_th'])){
        $data = [];
        $data['company_name_th'] = $_POST['company_name_th'];
        $data['company_name_en'] = $_POST['company_name_en'];
        $data['company_address_1'] = $_POST['company_address_1']; 
        $data['company_address_2'] = $_POST['company_address_2']; 
        $data['company_address_3'] = $_POST['company_address_3']; 
        $data['company_tax'] = $_POST['company_tax']; 
        $data['company_tel'] = $_POST['company_tel']; 
        $data['company_fax'] = $_POST['company_fax']; 
        $data['company_email'] = $_POST['company_email']; 
        $data['company_branch'] = $_POST['company_branch'];  
        $data['company_vat_type'] = $_POST['company_vat_type']; 
        $data['updateby'] = $admin_id; 
        $check = true;
        $check_rectangle = true;

        if($_FILES['company_image']['name'] == ""  ){
            $data['company_image'] = $_POST['company_image_o'];
        }else  {
            
            $target_file = $target_dir . "$d1-$d2-$d3 $d4:$d5:$d6".strtolower(basename($_FILES["company_image"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false; 
            }else if ($_FILES["company_image"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["company_image"]["tmp_name"], $target_file)) {
                $data['company_image'] = "$d1-$d2-$d3 $d4:$d5:$d6".strtolower($_FILES['company_image']['name']);

                $target_file = $target_dir . $_POST["company_image_o"];
                if($_POST["company_image_o"] != 'default.png'){
                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
                }
            

            } else {
                $error_msg =  "Sorry, there was an error uploading your file.";
                $check = false;
            } 
        }


        if($_FILES['company_image_rectangle']['name'] == ""  ){
            $data['company_image_rectangle'] = $_POST['company_image_rectangle_o'];
        }else  {
            
            $target_file = $target_dir . "$d1-$d2-$d3 $d4:$d5:$d6".strtolower(basename($_FILES["company_image_rectangle"]["name"]));
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check_rectangle = false; 
            }else if ($_FILES["company_image_rectangle"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check_rectangle = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check_rectangle = false;
            }else if (move_uploaded_file($_FILES["company_image_rectangle"]["tmp_name"], $target_file)) {
                $data['company_image_rectangle'] = "$d1-$d2-$d3 $d4:$d5:$d6".strtolower($_FILES['company_image_rectangle']['name']);

                $target_file = $target_dir . $_POST["company_image_rectangle_o"];
                if($_POST["company_image_rectangle_o"] != 'default.png'){
                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
                }
            

            } else {
                $error_msg =  "Sorry, there was an error uploading your file.";
                $check_rectangle = false;
            } 
        }


        if($check == false){
    ?>
        <script>
            alert('<?php echo $error_msg; ?>');
            window.history.back();
        </script>
    <?php
        } else if($check_rectangle == false){
    ?>
        <script>
            alert('<?php echo $error_msg; ?>');
            window.history.back();
        </script>
    <?php
        }else{
            $id = $company_model->updateCompanyByID('1',$data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=setting&action=update"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=setting&action=update"</script>
    <?php
            }
                    
        }
    }else{
        ?>
    <script>window.location="index.php?app=setting"</script>
        <?php
    }
    
} 




?>