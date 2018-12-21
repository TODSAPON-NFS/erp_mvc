<?php
require_once('../models/UserModel.php');
require_once('../models/UserPositionModel.php');
require_once('../models/UserStatusModel.php');
require_once('../models/LicenseModel.php');
require_once('../models/AddressModel.php');

$path = "modules/user_profile/views/";
$model = new UserModel;
$position = new UserPositionModel;
$status = new UserStatusModel;
$license = new LicenseModel;
$address = new AddressModel;
$target_dir = "../upload/user_profile/";

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


/* echo "<pre>";
print_r($user_admin);
echo"</pre>"; */

if(!isset($_GET['action'])){

    $user = $model->getUserBy($_GET['name'],$_GET['position'],$_GET['email']);
    $user_id = $admin_id;
    $user = $model->getUserByID($user_id);
    $user_license = $license->getLicenseBy();
    $user_position = $position->getUserPositionBy();
    $user_status = $status->getUserStatusBy();
    $add_province = $address->getProvinceByID();
    $add_amphur = $address->getAmphurByProviceID($user['user_province']);
    $add_district = $address->getDistricByAmphurID($user['user_amphur']);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'edit'){
    
    
        $data = [];
        $data['user_id'] = $admin_id;
        $data['user_code'] = $_POST['user_code'];
        $data['user_prefix'] = $_POST['user_prefix'];
        $data['user_name'] = $_POST['user_name'];
        $data['user_lastname'] = $_POST['user_lastname'];
        $data['user_mobile'] = $_POST['user_mobile'];
        $data['user_email'] = $_POST['user_email'];
        $data['user_username'] = $_POST['user_username'];
        $data['user_password'] = $_POST['user_password'];
        $data['user_address'] = $_POST['user_address'];
        $data['user_province'] = $_POST['user_province'];
        $data['user_amphur'] = $_POST['user_amphur'];
        $data['user_district'] = $_POST['user_district'];
        $data['user_zipcode'] = $_POST['user_zipcode'];
        $data['user_position_id'] = $_POST['user_position_id'];
        $data['license_id'] = $_POST['license_id'];
        $data['user_status_id'] = $_POST['user_status_id'];
        $data['user_image'] = trim($_POST['user_image']);


        $target_dir = "../upload/employee/";

        //-----------------ฟังก์ชั่นสุ่มตัวเลข----------------
        $numrand = (mt_rand());
        //-----------------------------------------------

        if($_FILES['user_image']['name'] == ""){
             $data['user_image'] = $_POST['user_image_o'];
        }else {

            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['user_image']['name'],".");
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
                $error_msg =  "ขอโทษด้วย. มีไฟล์นี้ในระบบแล้ว";
                $check = false;
            }else if ($_FILES["user_image"]["size"] > 5000000) {
                $error_msg = "ขอโทษด้วย. ไฟล์ของคุณต้องมีขนาดน้อยกว่า 5 MB.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "ขอโทษด้วย. ระบบสามารถอัพโหลดไฟล์นามสกุล JPG, JPEG, PNG & GIF เท่านั้น.";
                $check = false;
            }else if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $target_file)) {

                $data['user_image'] = $date.$newname;


                if( $_POST['user_image_o'] != null){

                    $target_file = $target_dir . $_POST['user_image_o'];
                
                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
    
                }

            } else {
                $error_msg =  "ขอโทษด้วย. ระบบไม่สามารถอัพโหลดไฟล์ได้.";
                $check = false;
            } 
        }
        if( $data['user_image'] == null){
            $user = $model->updateUserProfileNoIMGByID($admin_id,$data);
        } else{
            $user = $model->updateUserProfileByID($admin_id,$data);
        }
        
        if($user){
            $img = $_POST['hidden_data'];
            $data['user_signature'] = $img;
            $model->updateUserSignatureByID($admin_id,$data);

            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = $target_dir . $admin_id . ".png";
            $success = file_put_contents($file, $data);


?>
        <script>window.location="index.php?app=user_profile"</script>
<?php
        }else{
?>
        <script>window.location="index.php?app=user_profile"</script>
<?php
        }
 
        
        
    
}else{

    $user = $model->getUserBy($_GET['name'],$_GET['position'],$_GET['email']);
    require_once($path.'view.inc.php');

}





?>