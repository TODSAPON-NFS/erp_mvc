<?php
require_once('../models/UserModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/SupplierAccountModel.php');
$path = "modules/supplier_account/views/";
$model_user = new UserModel;
$model_supplier = new SupplierModel;
$model_supplier_account = new SupplierAccountModel;
$supplier_id = $_GET['id'];
$supplier_account_id = $_GET['subid'];

if(!isset($_GET['action'])){
    $supplier = $model_supplier->getSupplierByID($supplier_id);
    $supplier_account = $model_supplier_account->getSupplierAccountBy($supplier_id);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $supplier = $model_supplier->getSupplierByID($supplier_id);
    require_once($path.'insert.inc.php');
    

}else if ($_GET['action'] == 'update'){
    $supplier = $model_supplier->getSupplierByID($supplier_id);
    $supplier_account = $model_supplier_account->getSupplierAccountByID($supplier_account_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'){

    $model_supplier_account->deleteSupplierAccountByID($supplier_account_id);
?>
    <script>window.location="index.php?app=supplier_account&action=view&id=<?php echo $supplier_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['supplier_account_no'])){
        $data = [];
        $data['supplier_id'] = $supplier_id;
        $data['supplier_account_no'] = $_POST['supplier_account_no'];
        $data['supplier_account_name'] = $_POST['supplier_account_name'];
        $data['supplier_account_bank'] = $_POST['supplier_account_bank'];
        $data['supplier_account_branch'] = $_POST['supplier_account_branch'];
        $data['supplier_account_detail'] = $_POST['supplier_account_detail'];
        $data['addby'] = '';
       
            $id = $model_supplier_account->insertSupplierAccount($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=supplier_account&action=view&id=<?php echo $supplier_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=supplier_account&action=view&id=<?php echo $supplier_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['supplier_account_no'])){
        $data = [];
        $data['supplier_id'] = $supplier_id;
        $data['supplier_account_no'] = $_POST['supplier_account_no'];
        $data['supplier_account_name'] = $_POST['supplier_account_name'];
        $data['supplier_account_bank'] = $_POST['supplier_account_bank'];
        $data['supplier_account_branch'] = $_POST['supplier_account_branch'];
        $data['supplier_account_detail'] = $_POST['supplier_account_detail'];
        $data['updateby'] = '';
            
        $id = $model_supplier_account->updateSupplierAccountByID($_POST['supplier_account_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=supplier_account&action=view&id=<?php echo $supplier_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=supplier_account&action=view&id=<?php echo $supplier_id;?>"</script>
    <?php
            }
                    
        }
    
}else{

    $supplier = $model_supplier->getSupplierByID($supplier_id);
    $supplier_account = $model_supplier_account->getSupplierAccountBy($supplier_id);
    require_once($path.'view.inc.php');

}





?>