<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/JournalPurchaseReturnModel.php');
require_once('../models/JournalPurchaseReturnListModel.php');
require_once('../models/AccountModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/journal_purchase_return/views/";
$account_model = new AccountModel;
$journal_purchase_return_model = new JournalPurchaseReturnModel;
$journal_purchase_return_list_model = new JournalPurchaseReturnListModel;
$first_char = "SR";
$journal_purchase_return_id = $_GET['id'];
$target_dir = "../upload/journal_purchase_return/";

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword']; 
    $journal_purchase_returns = $journal_purchase_return_model->getJournalPurchaseReturnBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $accounts=$account_model->getAccountAll();
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $journal_purchase_return_model->getJournalPurchaseReturnLastID($first_code,3);

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $accounts=$account_model->getAccountAll();
    $journal_purchase_return = $journal_purchase_return_model->getJournalPurchaseReturnByID($journal_purchase_return_id);
    $journal_purchase_return_lists = $journal_purchase_return_list_model->getJournalPurchaseReturnListBy($journal_purchase_return_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $journal_purchase_return = $journal_purchase_return_model->getJournalPurchaseReturnViewByID($journal_purchase_return_id);
    $journal_purchase_return_lists = $journal_purchase_return_list_model->getJournalPurchaseReturnListBy($journal_purchase_return_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $journal_purchase_return = $journal_purchase_return_model->getJournalPurchaseReturnViewByID($journal_purchase_return_id);
    $journal_purchase_return_lists = $journal_purchase_return_list_model->getJournalPurchaseReturnListBy($journal_purchase_return_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){

    $journal_purchase_return_list_model->deleteJournalPurchaseReturnListByJournalPurchaseReturnID($journal_purchase_return_id);
    $journal_purchase_returns = $journal_purchase_return_model->deleteJournalPurchaseReturnById($journal_purchase_return_id);
?>
    <script>window.location="index.php?app=journal_special_05"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['journal_purchase_return_code'])){

        $data = [];
        $data['journal_purchase_return_date'] = $_POST['journal_purchase_return_date'];
        $data['journal_purchase_return_code'] = $_POST['journal_purchase_return_code'];
        $data['journal_purchase_return_name'] = $_POST['journal_purchase_return_name'];
        $data['addby'] = $user[0][0];


            $journal_purchase_return_id = $journal_purchase_return_model->insertJournalPurchaseReturn($data);

            if($journal_purchase_return_id > 0){

                $account_id = $_POST['account_id'];
                $journal_purchase_return_list_id = $_POST['journal_purchase_return_list_id'];
                $journal_purchase_return_list_name = $_POST['journal_purchase_return_list_name'];
                $journal_purchase_return_list_debit = $_POST['journal_purchase_return_list_debit'];
                $journal_purchase_return_list_credit = $_POST['journal_purchase_return_list_credit'];

                $journal_purchase_return_list_model->deleteJournalPurchaseReturnListByJournalPurchaseReturnIDNotIN($journal_purchase_return_id,$journal_purchase_return_list_id);

                if(is_array($account_id)){
                    for($i=0; $i < count($account_id) ; $i++){
                        $data = [];
                        $data['journal_purchase_return_id'] = $journal_purchase_return_id;
                        $data['account_id'] = $account_id[$i];
                        $data['journal_purchase_return_list_name'] = $journal_purchase_return_list_name[$i];
                        $data['journal_purchase_return_list_debit'] = $journal_purchase_return_list_debit[$i];
                        $data['journal_purchase_return_list_credit'] = $journal_purchase_return_list_credit[$i];

                        if ($journal_purchase_return_list_id[$i] != "" && $journal_purchase_return_list_id[$i] != '0'){
                            $journal_purchase_return_list_model->updateJournalPurchaseReturnListById($data,$journal_purchase_return_list_id[$i]);
                        }else{
                            $journal_purchase_return_list_model->insertJournalPurchaseReturnList($data);
                        }
                    }
                }else{
                    $data = [];
                    $data['journal_purchase_return_id'] = $journal_purchase_return_id;
                    $data['account_id'] = $account_id;
                    $data['journal_purchase_return_list_name'] = $journal_purchase_return_list_name;
                    $data['journal_purchase_return_list_debit'] = $journal_purchase_return_list_debit;
                    $data['journal_purchase_return_list_credit'] = $journal_purchase_return_list_credit;

                    if ($journal_purchase_return_list_id != "" && $journal_purchase_return_list_id != '0'){
                        $journal_purchase_return_list_model->updateJournalPurchaseReturnListById($data,$journal_purchase_return_list_id);
                    }else{
                        $journal_purchase_return_list_model->insertJournalPurchaseReturnList($data);
                    }
                    
                }

    ?>
            <script>window.location="index.php?app=journal_special_05&action=update&id=<?php echo $journal_purchase_return_id;?>"</script>
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
    
    if(isset($_POST['journal_purchase_return_code'])){
        $data = [];
        $data['journal_purchase_return_date'] = $_POST['journal_purchase_return_date'];
        $data['journal_purchase_return_code'] = $_POST['journal_purchase_return_code'];
        $data['journal_purchase_return_name'] = $_POST['journal_purchase_return_name'];
        $data['updateby'] = $user[0][0];

        $output = $journal_purchase_return_model->updateJournalPurchaseReturnByID($journal_purchase_return_id,$data);

        $account_id = $_POST['account_id'];
        $journal_purchase_return_list_id = $_POST['journal_purchase_return_list_id'];
        $journal_purchase_return_list_name = $_POST['journal_purchase_return_list_name'];
        $journal_purchase_return_list_debit = $_POST['journal_purchase_return_list_debit'];
        $journal_purchase_return_list_credit = $_POST['journal_purchase_return_list_credit'];

        $journal_purchase_return_list_model->deleteJournalPurchaseReturnListByJournalPurchaseReturnIDNotIN($journal_purchase_return_id,$journal_purchase_return_list_id);

        if(is_array($account_id)){
            for($i=0; $i < count($account_id) ; $i++){
                $data = [];
                $data['journal_purchase_return_id'] = $journal_purchase_return_id;
                $data['account_id'] = $account_id[$i];
                $data['journal_purchase_return_list_name'] = $journal_purchase_return_list_name[$i];
                $data['journal_purchase_return_list_debit'] = $journal_purchase_return_list_debit[$i];
                $data['journal_purchase_return_list_credit'] = $journal_purchase_return_list_credit[$i];

                if ($journal_purchase_return_list_id[$i] != "" && $journal_purchase_return_list_id[$i] != '0'){
                    $journal_purchase_return_list_model->updateJournalPurchaseReturnListById($data,$journal_purchase_return_list_id[$i]);
                }else{
                    $journal_purchase_return_list_model->insertJournalPurchaseReturnList($data);
                }
            }
        }else{
            $data = [];
            $data['journal_purchase_return_id'] = $journal_purchase_return_id;
            $data['account_id'] = $account_id;
            $data['journal_purchase_return_list_name'] = $journal_purchase_return_list_name;
            $data['journal_purchase_return_list_debit'] = $journal_purchase_return_list_debit;
            $data['journal_purchase_return_list_credit'] = $journal_purchase_return_list_credit;

            if ($journal_purchase_return_list_id != "" && $journal_purchase_return_list_id != '0'){
                $journal_purchase_return_list_model->updateJournalPurchaseReturnListById($data,$journal_purchase_return_list_id);
            }else{
                $journal_purchase_return_list_model->insertJournalPurchaseReturnList($data);
            }
            
        }
        
        if($output){
    ?>
            <script>window.location="index.php?app=journal_special_05"</script>
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
    $journal_purchase_returns = $journal_purchase_return_model->getJournalPurchaseReturnBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');


}





?>