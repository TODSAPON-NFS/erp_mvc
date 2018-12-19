<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/JobModel.php');
require_once('../models/JobOperationModel.php');
require_once('../models/JobOperationProcessModel.php');
require_once('../models/JobOperationProcessToolModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/job/views/";
$customer_model = new CustomerModel;
$job_model = new JobModel;
$job_operation_model = new JobOperationModel;
$job_operation_process_model = new JobOperationProcessModel;
$job_operation_process_tool_model = new JobOperationProcessToolModel;
$product_model = new ProductModel;

$first_char = "";
$job_id = $_GET['id'];


if(!isset($_GET['action'])){

    $jobs = $job_model->getJobBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $products=$product_model->getProductBy();
    $customers=$customer_model->getCustomerBy();
    $job = $job_model->getJobByID($job_id);
    $job_operations = $job_operation_model->getJobOperationBy($job_id);
    $job_operation_processes = $job_operation_process_model->getJobOperationProcessBy($job_id);
    $job_operation_process_tools = $job_operation_process_tool_model->getJobOperationProcessToolBy($job_id);
    $customer=$customer_model->getCustomerByID($Job['customer_id']);

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $job = $job_model->getJobViewByID($job_id);
    $job_operations = $job_operation_model->getJobOperationBy($job_id);
    $job_operation_processes = $job_operation_process_model->getJobOperationProcessBy($job_id);
    $job_operation_process_tools = $job_operation_process_tool_model->getJobOperationProcessToolBy($job_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete'){
    $jobs = $job_model->deleteJobById($job_id);
?>
    <script>window.location="index.php?app=job"</script>
<?php

}else if ($_GET['action'] == 'active'){
    $Jobs = $job_model->activeJobById($job_id);
?>
    <script>window.location="index.php?app=job"</script>
<?php

}else if ($_GET['action'] == 'inactive'){
    $Jobs = $job_model->inactiveJobById($job_id);
?>
    <script>window.location="index.php?app=job"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['customer_id'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['job_code'] = $_POST['job_code'];
        $data['job_name'] = $_POST['job_name'];
        $data['job_cost'] = $_POST['job_cost'];
        $data['job_price'] = $_POST['job_price'];
        $data['job_production'] = $_POST['job_production'];
        $data['job_remark'] = $_POST['job_remark'];
        $data['job_drawing'] = $_POST['job_drawing'];
        $data['job_start'] = $_POST['job_start'];
        $data['job_end'] = $_POST['job_end'];
        $data['job_active'] = '1';

        $job_id = $job_model->insertJob($data);


        if($job_id > 0){
?>
        <script>window.location="index.php?app=Job&action=update&id=<?php echo $job_id;?>"</script>
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
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['job_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_id'];
        $data['job_code'] = $_POST['job_code'];
        $data['job_name'] = $_POST['job_name'];
        $data['job_cost'] = $_POST['job_cost'];
        $data['job_price'] = $_POST['job_price'];
        $data['job_production'] = $_POST['job_production'];
        $data['job_remark'] = $_POST['job_remark'];
        $data['job_drawing'] = $_POST['job_drawing'];
        $data['job_start'] = $_POST['job_start'];
        $data['job_end'] = $_POST['job_end'];
        $data['job_active'] = '1';

        $output = $job_model->updateJobByID($job_id,$data);

        if($output){
?>
        <script>window.location="index.php?app=Job&action=update&id=<?PHP echo $job_id?>"</script>
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

    $Jobs = $job_model->getJobBy();
    require_once($path.'view.inc.php');

}

?>