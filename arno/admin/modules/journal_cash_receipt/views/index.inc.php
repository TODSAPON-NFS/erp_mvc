<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/JournalCashReceiptModel.php');
require_once('../models/JournalCashReceiptListModel.php');
require_once('../models/AccountModel.php');
require_once('../models/UserModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/journal_cash_receipt/views/";
$account_model = new AccountModel;
$journal_cash_receipt_model = new JournalCashReceiptModel;
$journal_cash_receipt_list_model = new JournalCashReceiptListModel;
$user_model = new UserModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('29');

$journal_cash_receipt_id = $_GET['id'];
$target_dir = "../upload/journal_cash_receipt/";

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword']; 
    $journal_cash_receipts = $journal_cash_receipt_model->getJournalCashReceiptBy($date_start,$date_end,$keyword);
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
            $last_code = $journal_cash_receipt_model->getJournalCashReceiptLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
    $first_date = date("d")."-".date("m")."-".date("Y");    
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $accounts=$account_model->getAccountAll();
    $journal_cash_receipt = $journal_cash_receipt_model->getJournalCashReceiptByID($journal_cash_receipt_id);
    $journal_cash_receipt_lists = $journal_cash_receipt_list_model->getJournalCashReceiptListBy($journal_cash_receipt_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $journal_cash_receipt = $journal_cash_receipt_model->getJournalCashReceiptViewByID($journal_cash_receipt_id);
    $journal_cash_receipt_lists = $journal_cash_receipt_list_model->getJournalCashReceiptListBy($journal_cash_receipt_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $journal_cash_receipt = $journal_cash_receipt_model->getJournalCashReceiptViewByID($journal_cash_receipt_id);
    $journal_cash_receipt_lists = $journal_cash_receipt_list_model->getJournalCashReceiptListBy($journal_cash_receipt_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){

    $journal_cash_receipt_list_model->deleteJournalCashReceiptListByJournalCashReceiptID($journal_cash_receipt_id);
    $journal_cash_receipts = $journal_cash_receipt_model->deleteJournalCashReceiptById($journal_cash_receipt_id);
?>
    <script>window.location="index.php?app=journal_special_03"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['journal_cash_receipt_code'])){

        $data = [];
        $data['journal_cash_receipt_date'] = $_POST['journal_cash_receipt_date'];
        $data['journal_cash_receipt_code'] = $_POST['journal_cash_receipt_code'];
        $data['journal_cash_receipt_name'] = $_POST['journal_cash_receipt_name'];
        $data['addby'] = $user[0][0];


            $journal_cash_receipt_id = $journal_cash_receipt_model->insertJournalCashReceipt($data);

            if($journal_cash_receipt_id > 0){

                $account_id = $_POST['account_id'];
                $journal_cash_receipt_list_id = $_POST['journal_cash_receipt_list_id'];
                $journal_cash_receipt_list_name = $_POST['journal_cash_receipt_list_name'];
                $journal_cash_receipt_list_debit = $_POST['journal_cash_receipt_list_debit'];
                $journal_cash_receipt_list_credit = $_POST['journal_cash_receipt_list_credit'];

                $journal_cash_receipt_list_model->deleteJournalCashReceiptListByJournalCashReceiptIDNotIN($journal_cash_receipt_id,$journal_cash_receipt_list_id);

                if(is_array($account_id)){
                    for($i=0; $i < count($account_id) ; $i++){
                        $data = [];
                        $data['journal_cash_receipt_id'] = $journal_cash_receipt_id;
                        $data['account_id'] = $account_id[$i];
                        $data['journal_cash_receipt_list_name'] = $journal_cash_receipt_list_name[$i];
                        $data['journal_cash_receipt_list_debit'] = $journal_cash_receipt_list_debit[$i];
                        $data['journal_cash_receipt_list_credit'] = $journal_cash_receipt_list_credit[$i];

                        if ($journal_cash_receipt_list_id[$i] != "" && $journal_cash_receipt_list_id[$i] != '0'){
                            $journal_cash_receipt_list_model->updateJournalCashReceiptListById($data,$journal_cash_receipt_list_id[$i]);
                        }else{
                            $journal_cash_receipt_list_model->insertJournalCashReceiptList($data);
                        }
                    }
                }else{
                    $data = [];
                    $data['journal_cash_receipt_id'] = $journal_cash_receipt_id;
                    $data['account_id'] = $account_id;
                    $data['journal_cash_receipt_list_name'] = $journal_cash_receipt_list_name;
                    $data['journal_cash_receipt_list_debit'] = $journal_cash_receipt_list_debit;
                    $data['journal_cash_receipt_list_credit'] = $journal_cash_receipt_list_credit;

                    if ($journal_cash_receipt_list_id != "" && $journal_cash_receipt_list_id != '0'){
                        $journal_cash_receipt_list_model->updateJournalCashReceiptListById($data,$journal_cash_receipt_list_id);
                    }else{
                        $journal_cash_receipt_list_model->insertJournalCashReceiptList($data);
                    }
                    
                }

    ?>
            <script>
            //window.location="index.php?app=journal_special_03&action=update&id=<?php echo $journal_cash_receipt_id;?>";
            window.location="index.php?app=journal_special_03&action=insert";
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
    
}else if ($_GET['action'] == 'edit'){
    
    if(isset($_POST['journal_cash_receipt_code'])){
        $data = [];
        $data['journal_cash_receipt_date'] = $_POST['journal_cash_receipt_date'];
        $data['journal_cash_receipt_code'] = $_POST['journal_cash_receipt_code'];
        $data['journal_cash_receipt_name'] = $_POST['journal_cash_receipt_name'];
        $data['updateby'] = $user[0][0];

        $output = $journal_cash_receipt_model->updateJournalCashReceiptByID($journal_cash_receipt_id,$data);

        $account_id = $_POST['account_id'];
        $journal_cash_receipt_list_id = $_POST['journal_cash_receipt_list_id'];
        $journal_cash_receipt_list_name = $_POST['journal_cash_receipt_list_name'];
        $journal_cash_receipt_list_debit = $_POST['journal_cash_receipt_list_debit'];
        $journal_cash_receipt_list_credit = $_POST['journal_cash_receipt_list_credit'];

        $journal_cash_receipt_list_model->deleteJournalCashReceiptListByJournalCashReceiptIDNotIN($journal_cash_receipt_id,$journal_cash_receipt_list_id);

        if(is_array($account_id)){
            for($i=0; $i < count($account_id) ; $i++){
                $data = [];
                $data['journal_cash_receipt_id'] = $journal_cash_receipt_id;
                $data['account_id'] = $account_id[$i];
                $data['journal_cash_receipt_list_name'] = $journal_cash_receipt_list_name[$i];
                $data['journal_cash_receipt_list_debit'] = $journal_cash_receipt_list_debit[$i];
                $data['journal_cash_receipt_list_credit'] = $journal_cash_receipt_list_credit[$i];

                if ($journal_cash_receipt_list_id[$i] != "" && $journal_cash_receipt_list_id[$i] != '0'){
                    $journal_cash_receipt_list_model->updateJournalCashReceiptListById($data,$journal_cash_receipt_list_id[$i]);
                }else{
                    $journal_cash_receipt_list_model->insertJournalCashReceiptList($data);
                }
            }
        }else{
            $data = [];
            $data['journal_cash_receipt_id'] = $journal_cash_receipt_id;
            $data['account_id'] = $account_id;
            $data['journal_cash_receipt_list_name'] = $journal_cash_receipt_list_name;
            $data['journal_cash_receipt_list_debit'] = $journal_cash_receipt_list_debit;
            $data['journal_cash_receipt_list_credit'] = $journal_cash_receipt_list_credit;

            if ($journal_cash_receipt_list_id != "" && $journal_cash_receipt_list_id != '0'){
                $journal_cash_receipt_list_model->updateJournalCashReceiptListById($data,$journal_cash_receipt_list_id);
            }else{
                $journal_cash_receipt_list_model->insertJournalCashReceiptList($data);
            }
            
        }
        
        if($output){
    ?>
            <script>window.location="index.php?app=journal_special_03"</script>
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
    $journal_cash_receipts = $journal_cash_receipt_model->getJournalCashReceiptBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');


}





?>