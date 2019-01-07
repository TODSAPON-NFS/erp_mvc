<?php
session_start(); 
date_default_timezone_set('asia/bangkok');
require_once('../models/CompanyModel.php'); 
$path = "modules/setting/views/";
$target_dir = "../upload/company/";
$company_model = new CompanyModel;




//-----------------ฟังก์ชั่นสุ่มตัวเลข----------------
$numrand = (mt_rand());
//-----------------------------------------------


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
        $data['company_address_en_1'] = $_POST['company_address_en_1']; 
        $data['company_address_en_2'] = $_POST['company_address_en_2']; 
        $data['company_address_en_3'] = $_POST['company_address_en_3']; 
        $data['company_tax'] = $_POST['company_tax']; 
        $data['company_tel'] = $_POST['company_tel']; 
        $data['company_fax'] = $_POST['company_fax']; 
        $data['company_email'] = $_POST['company_email']; 
        $data['company_email_smtp'] = $_POST['company_email_smtp']; 
        $data['company_email_port'] = $_POST['company_email_port']; 
        $data['company_email_user'] = $_POST['company_email_user']; 
        $data['company_email_password'] = $_POST['company_email_password']; 
        $data['company_branch'] = $_POST['company_branch'];  
        $data['company_branch_en'] = $_POST['company_branch_en'];  

        $data['company_vat_type'] = $_POST['company_vat_type']; 
        $data['updateby'] = $admin_id; 
        $check = true;
        $check_rectangle = true;

        if($_FILES['company_image']['name'] == ""  ){
            $data['company_image'] = $_POST['company_image_o'];
        }else  {

            //---------------------ฟังก์ชั่นวันที่------------------------------------ 
            $d1=date("d");
            $d2=date("m");
            $d3=date("Y");
            $d4=date("H");
            $d5=date("i");
            $d6=date("s");
            $date="$d1$d2$d3$d4$d5$d6";
            //---------------------------------------------------------------------

            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['company_image']['name'],".");
            //--------------------------------------------------
            
            //-----ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม---------
            $newname = $date.$numrand.$type;
            $path_copy=$path.$newname;
            $path_link=$target_dir.$newname;
            //-------------------------------------------------

            //-----------------ฟังก์ชั่นสุ่มตัวเลข----------------
            $numrand = (mt_rand());
            //-----------------------------------------------

            $target_file = $target_dir .$date.$newname;
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
                $data['company_image'] = $date.$newname;

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

            //---------------------ฟังก์ชั่นวันที่------------------------------------ 
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

            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['company_image_rectangle']['name'],".");
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
                $check_rectangle = false; 
            }else if ($_FILES["company_image_rectangle"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check_rectangle = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check_rectangle = false;
            }else if (move_uploaded_file($_FILES["company_image_rectangle"]["tmp_name"], $target_file)) {
                $data['company_image_rectangle'] = $date.$newname;

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