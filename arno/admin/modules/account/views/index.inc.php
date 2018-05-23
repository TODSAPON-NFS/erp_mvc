<?php
require_once('../models/AccountModel.php');
$path = "modules/account/views/";
$model_account = new AccountModel;
$account_id = $_GET['id'];


function generateTree($data){
        $model_account = new AccountModel;
        echo '<ul style="list-style-image: url(\'sqpurple.gif\');">';
        for($i=0; $i < count($data); $i++){ 
                $val = $model_account->getAccountBy($data[$i]['account_id']);

                echo '<li style="padding:4px;">';
                echo '  <a title="Update data" href="?app=account&action=update&id='.$data[$i]['account_id'].'">';
                if(count($account) > 0){echo '<b>';}
                echo $data[$i]['account_code'].' '.$data[$i]['account_name_th']." ";
                if(count($account) > 0){echo '</b>';}
                echo '  </a>'; 
                echo '  <a  href="?app=account&action=view&id='.$data[$i]['account_id'].'" >';
                echo '          <i class="fa fa-plus" aria-hidden="true"></i>';
                echo '  </a>';
                echo '  <a title="Delete data" href="?app=account&action=delete&id='.$data[$i]['account_id'].
                        '" onclick="return confirm(\'You want to delete account : '.$data[$i]['account_name_th'].'\');" style="color:red;">';
                echo '          <i class="fa fa-times" aria-hidden="true"></i>';
                echo '  </a>';
                
                generateTree($val);
                echo "</li>";
        }
        echo "</ul>";
        return true;
}


if(!isset($_GET['action'])){    
    $account = $model_account->getAccountByID($account_id);
    $accounts = $model_account->getAccountBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete'){

    $model_account->deleteAccountByID($account_id);
?>
    <script>window.location="index.php?app=account&action=view"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['account_code'])){
        $data = [];
        $data['account_code'] = $_POST['account_code'];
        $data['account_name_th'] = $_POST['account_name_th'];
        $data['account_name_en'] = $_POST['account_name_en'];
        $data['account_control'] = $_POST['account_control'];
        $data['account_level'] = $_POST['account_level'];
        $data['account_group'] = $_POST['account_group'];
        $data['account_type'] = $_POST['account_type'];
       
            $account_id = $model_account->insertAccount($data);
            if($account_id > 0){
    ?>
            <script>window.location="index.php?app=account&action=view"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=account&action=view"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['account_code'])){
        $data = [];
        $data['account_code'] = $_POST['account_code'];
        $data['account_name_th'] = $_POST['account_name_th'];
        $data['account_name_en'] = $_POST['account_name_en'];
        $data['account_control'] = $_POST['account_control'];
        $data['account_level'] = $_POST['account_level'];
        $data['account_group'] = $_POST['account_group'];
        $data['account_type'] = $_POST['account_type'];
            
        $id = $model_account->updateAccountByID($account_id,$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=account&action=view&id=<?php echo $account_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=account&action=view&id=<?php echo $account_id;?>"</script>
    <?php
            }
                    
        }
    
}else{
    $account = $model_account->getAccountByID($account_id);
    $accounts = $model_account->getAccountBy();
    require_once($path.'view.inc.php');

}





?>