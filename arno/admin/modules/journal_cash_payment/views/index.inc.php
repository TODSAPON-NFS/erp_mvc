<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/JournalCashPaymentModel.php');
require_once('../models/JournalCashPaymentListModel.php');
require_once('../models/AccountModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/journal_cash_payment/views/";
$account_model = new AccountModel;
$journal_cash_payment_model = new JournalCashPaymentModel;
$journal_cash_payment_list_model = new JournalCashPaymentListModel;
$first_char = "JG";
$journal_cash_payment_id = $_GET['id'];
$target_dir = "../upload/journal_cash_payment/";

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword']; 
    $journal_cash_payments = $journal_cash_payment_model->getJournalCashPaymentBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $accounts=$account_model->getAccountAll();
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $journal_cash_payment_model->getJournalCashPaymentLastID($first_code,3);

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $accounts=$account_model->getAccountAll();
    $journal_cash_payment = $journal_cash_payment_model->getJournalCashPaymentByID($journal_cash_payment_id);
    $journal_cash_payment_lists = $journal_cash_payment_list_model->getJournalCashPaymentListBy($journal_cash_payment_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $journal_cash_payment = $journal_cash_payment_model->getJournalCashPaymentViewByID($journal_cash_payment_id);
    $journal_cash_payment_lists = $journal_cash_payment_list_model->getJournalCashPaymentListBy($journal_cash_payment_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $journal_cash_payment = $journal_cash_payment_model->getJournalCashPaymentViewByID($journal_cash_payment_id);
    $journal_cash_payment_lists = $journal_cash_payment_list_model->getJournalCashPaymentListBy($journal_cash_payment_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){

    $journal_cash_payment_list_model->deleteJournalCashPaymentListByJournalCashPaymentID($journal_cash_payment_id);
    $journal_cash_payments = $journal_cash_payment_model->deleteJournalCashPaymentById($journal_cash_payment_id);
?>
    <script>window.location="index.php?app=journal_special_04"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['journal_cash_payment_code'])){

        $data = [];
        $data['journal_cash_payment_date'] = $_POST['journal_cash_payment_date'];
        $data['journal_cash_payment_code'] = $_POST['journal_cash_payment_code'];
        $data['journal_cash_payment_name'] = $_POST['journal_cash_payment_name'];
        $data['addby'] = $user[0][0];


            $journal_cash_payment_id = $journal_cash_payment_model->insertJournalCashPayment($data);

            if($journal_cash_payment_id > 0){

                $account_id = $_POST['account_id'];
                $journal_cash_payment_list_id = $_POST['journal_cash_payment_list_id'];
                $journal_cash_payment_list_name = $_POST['journal_cash_payment_list_name'];
                $journal_cash_payment_list_debit = $_POST['journal_cash_payment_list_debit'];
                $journal_cash_payment_list_credit = $_POST['journal_cash_payment_list_credit'];

                $journal_cash_payment_list_model->deleteJournalCashPaymentListByJournalCashPaymentIDNotIN($journal_cash_payment_id,$journal_cash_payment_list_id);

                if(is_array($account_id)){
                    for($i=0; $i < count($account_id) ; $i++){
                        $data = [];
                        $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                        $data['account_id'] = $account_id[$i];
                        $data['journal_cash_payment_list_name'] = $journal_cash_payment_list_name[$i];
                        $data['journal_cash_payment_list_debit'] = $journal_cash_payment_list_debit[$i];
                        $data['journal_cash_payment_list_credit'] = $journal_cash_payment_list_credit[$i];

                        if ($journal_cash_payment_list_id[$i] != "" && $journal_cash_payment_list_id[$i] != '0'){
                            $journal_cash_payment_list_model->updateJournalCashPaymentListById($data,$journal_cash_payment_list_id[$i]);
                        }else{
                            $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
                        }
                    }
                }else{
                    $data = [];
                    $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                    $data['account_id'] = $account_id;
                    $data['journal_cash_payment_list_name'] = $journal_cash_payment_list_name;
                    $data['journal_cash_payment_list_debit'] = $journal_cash_payment_list_debit;
                    $data['journal_cash_payment_list_credit'] = $journal_cash_payment_list_credit;

                    if ($journal_cash_payment_list_id != "" && $journal_cash_payment_list_id != '0'){
                        $journal_cash_payment_list_model->updateJournalCashPaymentListById($data,$journal_cash_payment_list_id);
                    }else{
                        $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
                    }
                    
                }

    ?>
            <script>window.location="index.php?app=journal_special_04&action=update&id=<?php echo $journal_cash_payment_id;?>"</script>
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
    
    if(isset($_POST['journal_cash_payment_code'])){
        $data = [];
        $data['journal_cash_payment_date'] = $_POST['journal_cash_payment_date'];
        $data['journal_cash_payment_code'] = $_POST['journal_cash_payment_code'];
        $data['journal_cash_payment_name'] = $_POST['journal_cash_payment_name'];
        $data['updateby'] = $user[0][0];

        $output = $journal_cash_payment_model->updateJournalCashPaymentByID($journal_cash_payment_id,$data);

        $account_id = $_POST['account_id'];
        $journal_cash_payment_list_id = $_POST['journal_cash_payment_list_id'];
        $journal_cash_payment_list_name = $_POST['journal_cash_payment_list_name'];
        $journal_cash_payment_list_debit = $_POST['journal_cash_payment_list_debit'];
        $journal_cash_payment_list_credit = $_POST['journal_cash_payment_list_credit'];

        $journal_cash_payment_list_model->deleteJournalCashPaymentListByJournalCashPaymentIDNotIN($journal_cash_payment_id,$journal_cash_payment_list_id);

        if(is_array($account_id)){
            for($i=0; $i < count($account_id) ; $i++){
                $data = [];
                $data['journal_cash_payment_id'] = $journal_cash_payment_id;
                $data['account_id'] = $account_id[$i];
                $data['journal_cash_payment_list_name'] = $journal_cash_payment_list_name[$i];
                $data['journal_cash_payment_list_debit'] = $journal_cash_payment_list_debit[$i];
                $data['journal_cash_payment_list_credit'] = $journal_cash_payment_list_credit[$i];

                if ($journal_cash_payment_list_id[$i] != "" && $journal_cash_payment_list_id[$i] != '0'){
                    $journal_cash_payment_list_model->updateJournalCashPaymentListById($data,$journal_cash_payment_list_id[$i]);
                }else{
                    $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
                }
            }
        }else{
            $data = [];
            $data['journal_cash_payment_id'] = $journal_cash_payment_id;
            $data['account_id'] = $account_id;
            $data['journal_cash_payment_list_name'] = $journal_cash_payment_list_name;
            $data['journal_cash_payment_list_debit'] = $journal_cash_payment_list_debit;
            $data['journal_cash_payment_list_credit'] = $journal_cash_payment_list_credit;

            if ($journal_cash_payment_list_id != "" && $journal_cash_payment_list_id != '0'){
                $journal_cash_payment_list_model->updateJournalCashPaymentListById($data,$journal_cash_payment_list_id);
            }else{
                $journal_cash_payment_list_model->insertJournalCashPaymentList($data);
            }
            
        }
        
        if($output){
    ?>
            <script>window.location="index.php?app=journal_special_04"</script>
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
    $journal_cash_payments = $journal_cash_payment_model->getJournalCashPaymentBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');


}





?>