<?php
require_once('../models/UserModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/SupplierContactModel.php');
$path = "modules/supplier_contact/views/";
$model_user = new UserModel;
$model_supplier = new SupplierModel;
$model_supplier_contact = new SupplierContactModel;
$supplier_id = $_GET['id'];
$supplier_contact_id = $_GET['subid'];

if(!isset($_GET['action'])){
    $supplier = $model_supplier->getSupplierByID($supplier_id);
    $supplier_contact = $model_supplier_contact->getSupplierContactBy($supplier_id);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    $supplier = $model_supplier->getSupplierByID($supplier_id);
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    $supplier = $model_supplier->getSupplierByID($supplier_id);
    $supplier_contact = $model_supplier_contact->getSupplierContactByID($supplier_contact_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && ( $license_admin_page == 'High') ){

    $model_supplier_contact->deleteSupplierContactByID($supplier_contact_id);
?>
    <script>window.location="index.php?app=supplier_contact&action=view&id=<?php echo $supplier_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['supplier_contact_name'])){
        $data = [];
        $data['supplier_id'] = $supplier_id;
        $data['supplier_contact_name'] = $_POST['supplier_contact_name'];
        $data['supplier_contact_position'] = $_POST['supplier_contact_position'];
        $data['supplier_contact_tel'] = $_POST['supplier_contact_tel'];
        $data['supplier_contact_email'] = $_POST['supplier_contact_email'];
        $data['supplier_contact_detail'] = $_POST['supplier_contact_detail'];
        $data['addby'] = '';
       
            $id = $model_supplier_contact->insertSupplierContact($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=supplier_contact&action=view&id=<?php echo $supplier_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=supplier_contact&action=view&id=<?php echo $supplier_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['supplier_contact_name'])){
        $data = [];
        $data['supplier_id'] = $supplier_id;
        $data['supplier_contact_name'] = $_POST['supplier_contact_name'];
        $data['supplier_contact_position'] = $_POST['supplier_contact_position'];
        $data['supplier_contact_tel'] = $_POST['supplier_contact_tel'];
        $data['supplier_contact_email'] = $_POST['supplier_contact_email'];
        $data['supplier_contact_detail'] = $_POST['supplier_contact_detail'];
        $data['updateby'] = '';
            
        $id = $model_supplier_contact->updateSupplierContactByID($_POST['supplier_contact_id'],$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=supplier_contact&action=view&id=<?php echo $supplier_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=supplier_contact&action=view&id=<?php echo $supplier_id;?>"</script>
    <?php
            }
                    
        }
    
}else{

    $supplier = $model_supplier->getSupplierByID($supplier_id);
    $supplier_contact = $model_supplier_contact->getSupplierContactBy($supplier_id);
    require_once($path.'view.inc.php');

}





?>