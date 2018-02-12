<?php
require_once('../models/UserModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/SupplierLogisticModel.php');
$path = "modules/supplier_logistic/views/";
$model_user = new UserModel;
$model_supplier = new SupplierModel;
$model_supplier_logistic = new SupplierLogisticModel;
$supplier_id = $_GET['id'];
$supplier_logistic_id = $_GET['subid'];

if(!isset($_GET['action'])){
    $supplier = $model_supplier->getSupplierByID($supplier_id);
    $supplier_logistic = $model_supplier_logistic->getSupplierLogisticBy($supplier_id);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $supplier = $model_supplier->getSupplierByID($supplier_id);
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update'){
    $supplier = $model_supplier->getSupplierByID($supplier_id);
    $supplier_logistic = $model_supplier_logistic->getSupplierLogisticByID($supplier_logistic_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'){

    $model_supplier_logistic->deleteSupplierLogisticByID($supplier_logistic_id);
?>
    <script>window.location="index.php?app=supplier_logistic&action=view&id=<?php echo $supplier_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['supplier_logistic_name'])){
        $data = [];
        $data['supplier_id'] = $supplier_id;
        $data['supplier_logistic_name'] = $_POST['supplier_logistic_name'];
        $data['supplier_logistic_detail'] = $_POST['supplier_logistic_detail'];
        $data['supplier_logistic_lead_time'] = $_POST['supplier_logistic_lead_time'];
        $data['addby'] = '';
       
            $id = $model_supplier_logistic->insertSupplierLogistic($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=supplier_logistic&action=view&id=<?php echo $supplier_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=supplier_logistic&action=view&id=<?php echo $supplier_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['supplier_logistic_name'])){
        $data = [];
        $data['supplier_id'] = $supplier_id;
        $data['supplier_logistic_name'] = $_POST['supplier_logistic_name'];
        $data['supplier_logistic_detail'] = $_POST['supplier_logistic_detail'];
        $data['supplier_logistic_lead_time'] = $_POST['supplier_logistic_lead_time'];
        $data['updateby'] = '';
            
        $id = $model_supplier_logistic->updateSupplierLogisticByID($_POST['supplier_logistic_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=supplier_logistic&action=view&id=<?php echo $supplier_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=supplier_logistic&action=view&id=<?php echo $supplier_id;?>"</script>
    <?php
            }
                    
        }
    
}else{

    $supplier = $model_supplier->getSupplierByID($supplier_id);
    $supplier_logistic = $model_supplier_logistic->getSupplierLogisticBy($supplier_id);
    require_once($path.'view.inc.php');

}





?>