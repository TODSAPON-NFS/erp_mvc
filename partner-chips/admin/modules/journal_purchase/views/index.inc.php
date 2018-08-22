<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/JournalPurchaseModel.php');
require_once('../models/JournalPurchaseListModel.php');
require_once('../models/AccountModel.php');
require_once('../models/UserModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/journal_purchase/views/";
$account_model = new AccountModel;
$journal_purchase_model = new JournalPurchaseModel;
$journal_purchase_list_model = new JournalPurchaseListModel;
$user_model = new UserModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('27');

$journal_purchase_id = $_GET['id'];
$target_dir = "../upload/journal_purchase/";

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword']; 
    $journal_purchases = $journal_purchase_model->getJournalPurchaseBy($date_start,$date_end,$keyword);

    $user=$user_model->getUserByID($admin_id);
    $data = [];
    $data['year'] = date("Y");
    $data['month'] = date("m");
    $data['number'] = "0000000000";
    $data['employee_name'] = $user["user_name_en"];
    $data['customer_code'] = $customer["customer_code"];

    $code = $code_generate->cut2Array($paper['paper_code'],$data);
    $last_code = "";
    for($i = 0 ; $i < count($code); $i++){
    
        if($code[$i]['type'] == "number"){
            $last_code =  $journal_purchase_model->getJournalPurchaseLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }

    
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $accounts=$account_model->getAccountAll();

    $user=$user_model->getUserByID($admin_id);
    $data = [];
    $data['year'] = date("Y");
    $data['month'] = date("m");
    $data['number'] = "0000000000";
    $data['employee_name'] = $user["user_name_en"];
    $data['customer_code'] = $customer["customer_code"];

    $code = $code_generate->cut2Array($paper['paper_code'],$data);
    $last_code = "";
    for($i = 0 ; $i < count($code); $i++){
    
        if($code[$i]['type'] == "number"){
            $last_code =  $journal_purchase_model->getJournalPurchaseLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
    $first_date = date("d")."-".date("m")."-".date("Y"); 

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $accounts=$account_model->getAccountAll();
    $journal_purchase = $journal_purchase_model->getJournalPurchaseByID($journal_purchase_id);
    $journal_purchase_lists = $journal_purchase_list_model->getJournalPurchaseListBy($journal_purchase_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $journal_purchase = $journal_purchase_model->getJournalPurchaseViewByID($journal_purchase_id);
    $journal_purchase_lists = $journal_purchase_list_model->getJournalPurchaseListBy($journal_purchase_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $journal_purchase = $journal_purchase_model->getJournalPurchaseViewByID($journal_purchase_id);
    $journal_purchase_lists = $journal_purchase_list_model->getJournalPurchaseListBy($journal_purchase_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){

    $journal_purchase_list_model->deleteJournalPurchaseListByJournalPurchaseID($journal_purchase_id);
    $journal_purchases = $journal_purchase_model->deleteJournalPurchaseById($journal_purchase_id);
?>
    <script>window.location="index.php?app=journal_special_01"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['journal_purchase_code'])){

        $data = [];
        $data['journal_purchase_date'] = $_POST['journal_purchase_date'];
        $data['journal_purchase_code'] = $_POST['journal_purchase_code'];
        $data['journal_purchase_name'] = $_POST['journal_purchase_name'];
        $data['addby'] = $admin_id;


            $journal_purchase_id = $journal_purchase_model->insertJournalPurchase($data);

            if($journal_purchase_id > 0){

                $account_id = $_POST['account_id'];
                $journal_purchase_list_id = $_POST['journal_purchase_list_id'];
                $journal_purchase_list_name = $_POST['journal_purchase_list_name'];
                $journal_purchase_list_debit = $_POST['journal_purchase_list_debit'];
                $journal_purchase_list_credit = $_POST['journal_purchase_list_credit'];

                $journal_purchase_list_model->deleteJournalPurchaseListByJournalPurchaseIDNotIN($journal_purchase_id,$journal_purchase_list_id);

                if(is_array($account_id)){
                    for($i=0; $i < count($account_id) ; $i++){
                        $data = [];
                        $data['journal_purchase_id'] = $journal_purchase_id;
                        $data['account_id'] = $account_id[$i];
                        $data['journal_purchase_list_name'] = $journal_purchase_list_name[$i];
                        $data['journal_purchase_list_debit'] = $journal_purchase_list_debit[$i];
                        $data['journal_purchase_list_credit'] = $journal_purchase_list_credit[$i];

                        if ($journal_purchase_list_id[$i] != "" && $journal_purchase_list_id[$i] != '0'){
                            $journal_purchase_list_model->updateJournalPurchaseListById($data,$journal_purchase_list_id[$i]);
                        }else{
                            $journal_purchase_list_model->insertJournalPurchaseList($data);
                        }
                    }
                }else{
                    $data = [];
                    $data['journal_purchase_id'] = $journal_purchase_id;
                    $data['account_id'] = $account_id;
                    $data['journal_purchase_list_name'] = $journal_purchase_list_name;
                    $data['journal_purchase_list_debit'] = $journal_purchase_list_debit;
                    $data['journal_purchase_list_credit'] = $journal_purchase_list_credit;

                    if ($journal_purchase_list_id != "" && $journal_purchase_list_id != '0'){
                        $journal_purchase_list_model->updateJournalPurchaseListById($data,$journal_purchase_list_id);
                    }else{
                        $journal_purchase_list_model->insertJournalPurchaseList($data);
                    }
                    
                }

    ?>
            <script>window.location="index.php?app=journal_special_01&action=update&id=<?php echo $journal_purchase_id;?>"</script>
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
    
    if(isset($_POST['journal_purchase_code'])){
        $data = [];
        $data['journal_purchase_date'] = $_POST['journal_purchase_date'];
        $data['journal_purchase_code'] = $_POST['journal_purchase_code'];
        $data['journal_purchase_name'] = $_POST['journal_purchase_name'];
        $data['updateby'] = $admin_id;

        $output = $journal_purchase_model->updateJournalPurchaseByID($journal_purchase_id,$data);

        $account_id = $_POST['account_id'];
        $journal_purchase_list_id = $_POST['journal_purchase_list_id'];
        $journal_purchase_list_name = $_POST['journal_purchase_list_name'];
        $journal_purchase_list_debit = $_POST['journal_purchase_list_debit'];
        $journal_purchase_list_credit = $_POST['journal_purchase_list_credit'];

        $journal_purchase_list_model->deleteJournalPurchaseListByJournalPurchaseIDNotIN($journal_purchase_id,$journal_purchase_list_id);

        if(is_array($account_id)){
            for($i=0; $i < count($account_id) ; $i++){
                $data = [];
                $data['journal_purchase_id'] = $journal_purchase_id;
                $data['account_id'] = $account_id[$i];
                $data['journal_purchase_list_name'] = $journal_purchase_list_name[$i];
                $data['journal_purchase_list_debit'] = $journal_purchase_list_debit[$i];
                $data['journal_purchase_list_credit'] = $journal_purchase_list_credit[$i];

                if ($journal_purchase_list_id[$i] != "" && $journal_purchase_list_id[$i] != '0'){
                    $journal_purchase_list_model->updateJournalPurchaseListById($data,$journal_purchase_list_id[$i]);
                }else{
                    $journal_purchase_list_model->insertJournalPurchaseList($data);
                }
            }
        }else{
            $data = [];
            $data['journal_purchase_id'] = $journal_purchase_id;
            $data['account_id'] = $account_id;
            $data['journal_purchase_list_name'] = $journal_purchase_list_name;
            $data['journal_purchase_list_debit'] = $journal_purchase_list_debit;
            $data['journal_purchase_list_credit'] = $journal_purchase_list_credit;

            if ($journal_purchase_list_id != "" && $journal_purchase_list_id != '0'){
                $journal_purchase_list_model->updateJournalPurchaseListById($data,$journal_purchase_list_id);
            }else{
                $journal_purchase_list_model->insertJournalPurchaseList($data);
            }
            
        }
        
        if($output){
    ?>
            <script>window.location="index.php?app=journal_special_01"</script>
    <?php
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
}else{
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword']; 
    $journal_purchases = $journal_purchase_model->getJournalPurchaseBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');


}





?>