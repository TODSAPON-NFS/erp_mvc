<?php
session_start();
$user = $_SESSION['user'];
$user_id = $user[0][0];
require_once('../models/RegrindSupplierModel.php');
require_once('../models/RegrindSupplierListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/SupplierModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/regrind_supplier/views/";
$user_model = new UserModel;
$supplier_model = new SupplierModel;
$regrind_supplier_model = new RegrindSupplierModel;
$regrind_supplier_list_model = new RegrindSupplierListModel;
$product_model = new ProductModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('7');

$regrind_supplier_id = $_GET['id'];
$target_dir = "../upload/regrind_supplier/";

$regrind_supplier = $regrind_supplier_model->getRegrindSupplierByID($regrind_supplier_id);
$employee_id = $regrind_supplier['employee_id'];

if(!isset($_GET['action']) && ($license_regrind_page == "Low" || $license_regrind_page == "Medium" || $license_regrind_page == "High" ) ){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();

    if($license_regrind_page == "Medium" || $license_regrind_page == "High" ){
        $regrind_suppliers = $regrind_supplier_model->getRegrindSupplierBy($date_start,$date_end,$supplier_id,$keyword);
    }else{
        $regrind_suppliers = $regrind_supplier_model->getRegrindSupplierBy($date_start,$date_end,$supplier_id,$keyword,$admin_id);
    }
   
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && (($license_regrind_page == "Low" ) || $license_regrind_page == "Medium" || $license_regrind_page == "High" )){
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
            $last_code = $regrind_supplier_model->getRegrindSupplierLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    } 
    $first_date = date("d")."-".date("m")."-".date("Y");
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && (($license_regrind_page == "Low" && $admin_id == $employee) || $license_regrind_page == "Medium" || $license_regrind_page == "High" )){
    $products=$product_model->getProductBy('','','','');
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

}else if ($_GET['action'] == 'delete' && (($license_regrind_page == "Low" && $admin_id == $employee) || $license_regrind_page == "High" )){

    $regrind_supplier_list_model->deleteRegrindSupplierListByRegrindSupplierID($regrind_supplier_id);
    $regrind_suppliers = $regrind_supplier_model->deleteRegrindSupplierById($regrind_supplier_id);
?>
    <script>window.location="index.php?app=regrind_supplier"</script>
<?php

}else if ($_GET['action'] == 'add' && (($license_regrind_page == "Low") || $license_regrind_page == "Medium" || $license_regrind_page == "High" )){
    if(isset($_POST['regrind_supplier_code'])){
  
        $check = true;

        $data = [];
        $data['regrind_supplier_date'] = $_POST['regrind_supplier_receive_date'];
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
    
}else if ($_GET['action'] == 'edit' &&  (($license_regrind_page == "Low" && $admin_id == $employee) || $license_regrind_page == "Medium" || $license_regrind_page == "High" )){
    
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
        
     
    
} else if($_GET['action'] == 'send' && (($license_regrind_page == "Low" && $admin_id == $employee) || $license_regrind_page == "Medium" || $license_regrind_page == "High" )){
    $img = $_POST['hidden_data'];
    $data['contact_signature'] = $img;

    $regrind_supplier_model->updateContactSignatureByID($regrind_supplier_id,$data);

    $regrind_supplier = $regrind_supplier_model->getRegrindSupplierViewByID($regrind_supplier_id);

    if($regrind_supplier_id > 0 ){
        /******** setmail ********************************************/
        require("../controllers/mail/class.phpmailer.php");
        $mail = new PHPMailer();
        $body = '
            We are sent the regrind order : '.$regrind_supplier['regrind_supplier_code'].' to '.$regrind_supplier['contact_name'].' <br>
            Can you see at <a href="http://localhost/erp_mvc/arno/admin/print/regrind_supplier.php?id='.$regrind_supplier_id.'">Click</a> 
            <br>
            <br>
            <b> Best regards,</b><br><br>

            <b> '.$regrind_supplier['user_name'].' '.$regrind_supplier['user_lastname'].' ('.$regrind_supplier['user_position_name'].')</b><br>
            <b> Head Office : </b> 2/27 Bangna Complex Office Tower,7th Flr.,Soi Bangna-Trad 25, Bangna-Trad Rd.,<br>
            Bangna, Bangna, Bangkok 10260, THAILAND, Tel : +662 399 2784  Fax : +662 399 2327 <br>
            <b> Tax ID :</b> 0105558002033 
            
        ';
        $mail->CharSet = "utf-8";
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->Host = "mail.revelsoft.co.th"; // SMTP server
        $mail->Port = 587; 
        $mail->Username = "support@revelsoft.co.th"; // account SMTP
        $mail->Password = "support123456"; //  SMTP

        $mail->SetFrom("support@revelsoft.co.th", "Revelsoft.co.th");
        $mail->AddReplyTo("support@revelsoft.co.th","Revelsoft.co.th");
        $mail->Subject = "Arno regrind order to ".$regrind_supplier['supplier_name_en'];

        $mail->MsgHTML($body);

        $mail->AddAddress($regrind_supplier['supplier_email'], "Supplier Mail"); //
        //$mail->AddAddress($set1, $name); // 
        if(!$mail->Send()) {
            $result = "Mailer Error: " . $mail->ErrorInfo;
        }else{
            $result = "Send regrind complete.";
        } 
?>
        <script>
            alert("<?php echo $result; ?>");
            window.location="index.php?app=regrind_supplier&action=print&id=<?php echo $regrind_supplier_id;?>";
        </script>
<?PHP
    }
    

?>
    <script>window.location="index.php?app=regrind_supplier&action=detail&id=<?php echo $regrind_supplier_id;?>"</script>
<?PHP
}else if($license_regrind_page == "Low" || $license_regrind_page == "Medium" || $license_regrind_page == "High" ) {

    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $supplier_id = $_GET['supplier_id'];
    $keyword = $_GET['keyword'];

    $suppliers=$supplier_model->getSupplierBy();
    if($license_regrind_page == "Medium" || $license_regrind_page == "High" ){
        $regrind_suppliers = $regrind_supplier_model->getRegrindSupplierBy($date_start,$date_end,$supplier_id,$keyword);
    }else{
        $regrind_suppliers = $regrind_supplier_model->getRegrindSupplierBy($date_start,$date_end,$supplier_id,$keyword,$admin_id);
    }
    require_once($path.'view.inc.php');

}





?>