<?php
session_start(); 

require_once('../models/RequestTestModel.php');
require_once('../models/RequestTestListModel.php'); 
require_once('../models/RequestStandardListModel.php');
require_once('../models/RequestSpecialListModel.php');
require_once('../models/RequestRegrindListModel.php');
require_once('../models/UserModel.php'); 
require_once('../models/ProductSupplierModel.php');
require_once('../models/SupplierModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/request_test/views/";

$request_test_model = new RequestTestModel;
$request_test_list_model = new RequestTestListModel;
$request_standard_list_model = new RequestStandardListModel;
$request_special_list_model = new RequestSpecialListModel;
$request_regrind_list_model = new RequestRegrindListModel;
$user_model = new UserModel;
$product_supplier_model = new ProductSupplierModel;
$supplier_model = new SupplierModel; 

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('1');

$request_test_id = $_GET['id'];
$supplier_id = $_GET['supplier_id'];

if(!isset($_GET['action'])){

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

    $request_tests = $request_test_model->getRequestTestBy($date_start,$date_end,$supplier_id,$keyword);
    $supplier_orders = $request_test_model->getSupplierOrder();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_request_page == "Medium" || $license_request_page == "High") ){
    
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
            $last_code = $request_test_model->getRequestTestLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }

    if($supplier_id > 0){
        $supplier=$supplier_model->getSupplierByID($supplier_id);
        $products=$product_supplier_model->getProductBySupplierID($supplier_id);
        $request_test_lists = $request_test_model->generateRequestTestListBySupplierId($supplier_id);
    }
   
    $first_date = date("d")."-".date("m")."-".date("Y");

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_request_page == "Medium" || $license_request_page == "High") ){
    
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $request_test = $request_test_model->getRequestTestByID($request_test_id);
    $request_test_lists = $request_test_list_model->getRequestTestListBy($request_test_id);
    $supplier=$supplier_model->getSupplierByID($request_test['supplier_id']);
    $products=$product_supplier_model->getProductBySupplierID($request_test['supplier_id']);
   
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    if($notification_id != ""){
        $notification_model->setNotificationSeenByID($notification_id);
    }
    $request_test = $request_test_model->getRequestTestViewByID($request_test_id);
    $request_test_lists = $request_test_list_model->getRequestTestListBy($request_test_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_request_page == "High") ){

    $request_tests = $request_test_model->deleteRequestTestById($request_test_id);
?>
    <script>window.location="index.php?app=request_test"</script>
<?php

}else if ($_GET['action'] == 'cancelled'){
    $request_test_model->cancelRequestTestById($request_test_id);
?>
    <script>window.location="index.php?app=request_test"</script>
<?php

}else if ($_GET['action'] == 'uncancelled'){
    $request_test_model->uncancelRequestTestById($request_test_id);
?>
    <script>window.location="index.php?app=request_test"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_request_page == "Medium" || $license_request_page == "High") ){
    if(isset($_POST['request_test_code'])){
        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['request_test_code'] = $_POST['request_test_code'];
        $data['request_test_date'] = $_POST['request_test_date']; 
        $data['request_test_status'] = 'New'; 

        $request_test_id = $request_test_model->insertRequestTest($data);

        if($request_test_id > 0){
            $data = [];
            $product_id = $_POST['product_id'];

            $request_standard_list_id = $_POST['request_standard_list_id'];
            $request_special_list_id = $_POST['request_special_list_id'];
            $request_regrind_list_id = $_POST['request_regrind_list_id'];
            $request_test_list_id = $_POST['request_test_list_id'];
            $request_test_list_qty = $_POST['request_test_list_qty'];
            $request_test_list_delivery = $_POST['request_test_list_delivery'];
            $request_test_list_remark = $_POST['request_test_list_remark'];

           
            if(is_array($product_id)){
                for($i=0; $i < count($product_id) ; $i++){
                    $data_sub = [];
                    $data_sub['request_test_id'] = $request_test_id;
                    $data_sub['product_id'] = $product_id[$i];
                    
                    $data_sub['request_test_list_qty'] = (float)filter_var($request_test_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['request_test_list_delivery'] = $request_test_list_delivery[$i];
                    $data_sub['request_test_list_remark'] = $request_test_list_remark[$i];
        
                    $id = $request_test_list_model->insertRequestTestList($data_sub);
                    if($id > 0){
                        if($request_standard_list_id[$i] > 0){
                            $request_standard_list_model->updateRequestTestListId($request_standard_list_id[$i],$id);
                        }else if ($request_special_list_id[$i] > 0 ){
                            $request_special_list_model->updateRequestTestListId($request_special_list_id[$i],$id);
                        }else if ($request_regrind_list_id[$i] > 0 ){
                            $request_regrind_list_model->updateRequestTestListId($request_regrind_list_id[$i],$id);
                        }
                    }
                }
            }else if($product_id != ""){
                $data_sub = [];
                $data_sub['request_test_id'] = $request_test_id;
                $data_sub['product_id'] = $product_id;
                
                $data_sub['request_test_list_qty'] = (float)filter_var($request_test_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['request_test_list_delivery'] = $request_test_list_delivery;
                $data_sub['request_test_list_remark'] = $request_test_list_remark;
    
                $id = $request_test_list_model->insertRequestTestList($data_sub);
                if($id > 0){
                    if($request_standard_list_id > 0){
                        $request_standard_list_model->updateRequestTestListId($request_standard_list_id,$id);
                    }else if ($request_special_list_id > 0 ){
                        $request_special_list_model->updateRequestTestListId($request_special_list_id,$id);
                    }else if ($request_regrind_list_id > 0 ){
                        $request_regrind_list_model->updateRequestTestListId($request_regrind_list_id,$id);
                    }
                }
            }
?>
        <script>window.location="index.php?app=request_test&action=update&id=<?php echo $request_test_id;?>"</script>
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
    
}else if ($_GET['action'] == 'edit' && ($license_request_page == "Medium" || $license_request_page == "High") ){

    if(isset($_POST['request_test_code'])){

        $data = [];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['employee_id'] = $_POST['employee_id'];
        $data['request_test_code'] = $_POST['request_test_code'];
        $data['request_test_date'] = $_POST['request_test_date']; 
        $data['request_test_status'] = 'New'; 

        $output = $request_test_model->updateRequestTestByID($request_test_id , $data);

        $product_id = $_POST['product_id'];

        $request_standard_list_id = $_POST['request_standard_list_id'];
        $request_special_list_id = $_POST['request_special_list_id'];
        $request_regrind_list_id = $_POST['request_regrind_list_id'];
        $request_test_list_id = $_POST['request_test_list_id'];
        $request_test_list_qty = $_POST['request_test_list_qty'];
        $request_test_list_delivery = $_POST['request_test_list_delivery'];
        $request_test_list_remark = $_POST['request_test_list_remark'];

        $request_test_list_model->deleteRequestTestListByRequestTestIDNotIN($request_test_id,$request_test_list_id);
        
        if(is_array($product_id)){
            for($i=0; $i < count($product_id) ; $i++){
                $data_sub = [];
                $data_sub['request_test_id'] = $request_test_id;
                $data_sub['product_id'] = $product_id[$i];
                
                $data_sub['request_test_list_qty'] = (float)filter_var($request_test_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['request_test_list_delivery'] = $request_test_list_delivery[$i];
                $data_sub['request_test_list_remark'] = $request_test_list_remark[$i];
    
                if($request_test_list_id[$i] != '0' ){
                    $request_test_list_model->updateRequestTestListByIdAdmin($data_sub,$request_test_list_id[$i]);
                }else{
                    $id = $request_test_list_model->insertRequestTestList($data_sub);
                    if($id > 0){
                        if($request_standard_list_id[$i] > 0){
                            $request_standard_list_model->updateRequestTestListId($request_standard_list_id[$i],$id);
                        }else if ($request_special_list_id[$i] > 0 ){
                            $request_special_list_model->updateRequestTestListId($request_special_list_id[$i],$id);
                        }else if ($request_regrind_list_id[$i] > 0 ){
                            $request_regrind_list_model->updateRequestTestListId($request_regrind_list_id[$i],$id);
                        }
                    }
                }
                
            }
        }else if($product_id != ""){
            $data_sub = [];
            $data_sub['request_test_id'] = $request_test_id;
            $data_sub['product_id'] = $product_id;
            
            $data_sub['request_test_list_qty'] = (float)filter_var($request_test_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['request_test_list_delivery'] = $request_test_list_delivery;
            $data_sub['request_test_list_remark'] = $request_test_list_remark;
            
            if($request_test_list_id != '0'){
                $request_test_list_model->updateRequestTestListByIdAdmin($data_sub,$request_test_list_id);
            }else{
                $id = $request_test_list_model->insertRequestTestList($data_sub);

                if($id > 0){
                    if($request_standard_list_id > 0){
                        $request_standard_list_model->updateRequestTestListId($request_standard_list_id,$id);
                    }else if ($request_special_list_id > 0 ){
                        $request_special_list_model->updateRequestTestListId($request_special_list_id,$id);
                    }else if ($request_regrind_list_id > 0 ){
                        $request_regrind_list_model->updateRequestTestListId($request_regrind_list_id,$id);
                    }
                }
            }
        }

        if($output){
        
?>
        <script>window.location="index.php?app=request_test&action=update&id=<?php echo $request_test_id;?>"</script>
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
        
        
    
}else if ($_GET['action'] == 'rewrite' && ($license_request_page == "Medium" || $license_request_page == "High") ){
        
        if($request_test_id > 0){
            $request_test = $request_test_model->getRequestTestByID($request_test_id);
            $request_test_lists = $request_test_list_model->getRequestTestListBy($request_test_id);
            $data = [];
            $request_test_model->cancelRequestTestById($request_test_id);  

            $data['supplier_id'] = $request_test['supplier_id'];
            $data['employee_id'] = $request_test['employee_id'];
            $data['request_test_status'] = 'New';
            $data['request_test_code'] = $request_test['request_test_code'];
            $data['request_test_date'] = $request_test['request_test_date'];
            $data['request_test_rewrite_id'] = $request_test_id;
            $data['request_test_rewrite_no'] = $request_test['request_test_rewrite_no'] + 1;


            $request_test_id = $request_test_model->insertRequestTest($data);
               
        
            for($i=0; $i < count($request_test_lists) ; $i++){
                $data_sub = [];
                $data_sub['request_test_id'] = $request_test_id;
                $data_sub['product_id'] = $request_test_lists[$i]['product_id'];
                $data_sub['request_test_list_qty'] = $request_test_lists[$i]['request_test_list_qty'];
                $data_sub['request_test_list_delivery'] = $request_test_lists[$i]['request_test_list_delivery'];
                $data_sub['request_test_list_remark'] = $request_test_lists[$i]['request_test_list_remark'];

                $id = $request_test_list_model->insertRequestTestList($data_sub);

                if($id > 0){
                    if($request_test_lists[$i]['request_standard_list_id'] > 0){
                        $request_standard_list_model->updateRequestTestListId($request_test_lists[$i]['request_standard_list_id'],$id);
                    }else if ($request_test_lists[$i]['request_special_list_id'] > 0 ){
                        $request_special_list_model->updateRequestTestListId($request_test_lists[$i]['request_special_list_id'],$id);
                    }else if ($request_test_lists[$i]['request_regrind_list_id'] > 0 ){
                        $request_regrind_list_model->updateRequestTestListId($request_test_lists[$i]['request_regrind_list_id'],$id);
                    }
                }            

            }
        
?>
        <script>window.location="index.php?app=request_test&action=update&id=<?php echo $request_test_id;?>"</script>
<?php
        }else{
?>
        <script>window.history.back();</script>
<?php
        }
    
    
}else if ($_GET['action'] == 'sending' && ($license_request_page == "Medium" || $license_request_page == "High") ){
    
    if(isset($request_test_id)){
        $data = [];

        $data['request_test_status'] = 'Sending';
        
        $data['updateby'] = $user[0][0];

        
        $supplier=$supplier_model->getSupplierByID($supplier_id);

        if($supplier_id > 0){
            /******** setmail ********************************************/
            require("../controllers/mail/class.phpmailer.php");
            $mail = new PHPMailer();
            $body = '
                We are opened the request test.
                Can you confirm the request test details?. 
                At <a href="http://support.revelsoft.co.th/erp_mvc/arno/supplier/index.php?app=request_test&action=sending&id='.$request_test_id.'">Click</a> 

                <br>
                <br>
                <b> Best regards,</b><br><br>

                <b> '.$user[0][3].''.$user[0][4].'</b><br>
                <b> Head Office : </b> 2/27 Bangna Complex Office Tower, 7th Flr.,Soi Bangna-Trad 25, Bangna-Trad Rd.,<br>
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
            $mail->Subject = "Arno request test confirm to ".$supplier['supplier_name_en'];

            $mail->MsgHTML($body);

            $mail->AddAddress($supplier['supplier_email'], "Supplier Mail"); //
            //$mail->AddAddress($set1, $name); // 
            if(!$mail->Send()) {
                $result = "Mailer Error: " . $mail->ErrorInfo;
            }else{
                $output = $request_test_model->updateRequestTestStatusByID($request_test_id,$data);
                $result = "Send request test complete.";
            } 
?>
        <script>
            alert("<?php echo $result; ?>");
            window.history.back();
        </script>
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

    $request_tests = $request_test_model->getRequestTestBy($date_start,$date_end,$supplier_id,$keyword);
    $supplier_orders = $request_test_model->getSupplierOrder();
    require_once($path.'view.inc.php');

}





?>