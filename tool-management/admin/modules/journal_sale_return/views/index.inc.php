<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/JournalSaleReturnModel.php');
require_once('../models/JournalSaleReturnListModel.php');
require_once('../models/AccountModel.php');
require_once('../models/UserModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/journal_sale_return/views/";
$account_model = new AccountModel;
$journal_sale_return_model = new JournalSaleReturnModel;
$journal_sale_return_list_model = new JournalSaleReturnListModel;
$user_model = new UserModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('32');

$journal_sale_return_id = $_GET['id'];
$target_dir = "../upload/journal_sale_return/";

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword']; 
    $journal_sale_returns = $journal_sale_return_model->getJournalSaleReturnBy($date_start,$date_end,$keyword);
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
            $last_code = $journal_sale_return_model->getJournalSaleReturnLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
    $first_date = date("d")."-".date("m")."-".date("Y");   

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $accounts=$account_model->getAccountAll();
    $journal_sale_return = $journal_sale_return_model->getJournalSaleReturnByID($journal_sale_return_id);
    $journal_sale_return_lists = $journal_sale_return_list_model->getJournalSaleReturnListBy($journal_sale_return_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $journal_sale_return = $journal_sale_return_model->getJournalSaleReturnViewByID($journal_sale_return_id);
    $journal_sale_return_lists = $journal_sale_return_list_model->getJournalSaleReturnListBy($journal_sale_return_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $journal_sale_return = $journal_sale_return_model->getJournalSaleReturnViewByID($journal_sale_return_id);
    $journal_sale_return_lists = $journal_sale_return_list_model->getJournalSaleReturnListBy($journal_sale_return_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){

    $journal_sale_return_list_model->deleteJournalSaleReturnListByJournalSaleReturnID($journal_sale_return_id);
    $journal_sale_returns = $journal_sale_return_model->deleteJournalSaleReturnById($journal_sale_return_id);
?>
    <script>window.location="index.php?app=journal_special_06"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['journal_sale_return_code'])){

        $data = [];
        $data['journal_sale_return_date'] = $_POST['journal_sale_return_date'];
        $data['journal_sale_return_code'] = $_POST['journal_sale_return_code'];
        $data['journal_sale_return_name'] = $_POST['journal_sale_return_name'];
        $data['addby'] = $user[0][0];


            $journal_sale_return_id = $journal_sale_return_model->insertJournalSaleReturn($data);

            if($journal_sale_return_id > 0){

                $account_id = $_POST['account_id'];
                $journal_sale_return_list_id = $_POST['journal_sale_return_list_id'];
                $journal_sale_return_list_name = $_POST['journal_sale_return_list_name'];
                $journal_sale_return_list_debit = $_POST['journal_sale_return_list_debit'];
                $journal_sale_return_list_credit = $_POST['journal_sale_return_list_credit'];

                $journal_sale_return_list_model->deleteJournalSaleReturnListByJournalSaleReturnIDNotIN($journal_sale_return_id,$journal_sale_return_list_id);

                if(is_array($account_id)){
                    for($i=0; $i < count($account_id) ; $i++){
                        $data = [];
                        $data['journal_sale_return_id'] = $journal_sale_return_id;
                        $data['account_id'] = $account_id[$i];
                        $data['journal_sale_return_list_name'] = $journal_sale_return_list_name[$i];
                        $data['journal_sale_return_list_debit'] = $journal_sale_return_list_debit[$i];
                        $data['journal_sale_return_list_credit'] = $journal_sale_return_list_credit[$i];

                        if ($journal_sale_return_list_id[$i] != "" && $journal_sale_return_list_id[$i] != '0'){
                            $journal_sale_return_list_model->updateJournalSaleReturnListById($data,$journal_sale_return_list_id[$i]);
                        }else{
                            $journal_sale_return_list_model->insertJournalSaleReturnList($data);
                        }
                    }
                }else{
                    $data = [];
                    $data['journal_sale_return_id'] = $journal_sale_return_id;
                    $data['account_id'] = $account_id;
                    $data['journal_sale_return_list_name'] = $journal_sale_return_list_name;
                    $data['journal_sale_return_list_debit'] = $journal_sale_return_list_debit;
                    $data['journal_sale_return_list_credit'] = $journal_sale_return_list_credit;

                    if ($journal_sale_return_list_id != "" && $journal_sale_return_list_id != '0'){
                        $journal_sale_return_list_model->updateJournalSaleReturnListById($data,$journal_sale_return_list_id);
                    }else{
                        $journal_sale_return_list_model->insertJournalSaleReturnList($data);
                    }
                    
                }

    ?>
            <script>window.location="index.php?app=journal_special_06&action=update&id=<?php echo $journal_sale_return_id;?>"</script>
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
    
    if(isset($_POST['journal_sale_return_code'])){
        $data = [];
        $data['journal_sale_return_date'] = $_POST['journal_sale_return_date'];
        $data['journal_sale_return_code'] = $_POST['journal_sale_return_code'];
        $data['journal_sale_return_name'] = $_POST['journal_sale_return_name'];
        $data['updateby'] = $user[0][0];

        $output = $journal_sale_return_model->updateJournalSaleReturnByID($journal_sale_return_id,$data);

        $account_id = $_POST['account_id'];
        $journal_sale_return_list_id = $_POST['journal_sale_return_list_id'];
        $journal_sale_return_list_name = $_POST['journal_sale_return_list_name'];
        $journal_sale_return_list_debit = $_POST['journal_sale_return_list_debit'];
        $journal_sale_return_list_credit = $_POST['journal_sale_return_list_credit'];

        $journal_sale_return_list_model->deleteJournalSaleReturnListByJournalSaleReturnIDNotIN($journal_sale_return_id,$journal_sale_return_list_id);

        if(is_array($account_id)){
            for($i=0; $i < count($account_id) ; $i++){
                $data = [];
                $data['journal_sale_return_id'] = $journal_sale_return_id;
                $data['account_id'] = $account_id[$i];
                $data['journal_sale_return_list_name'] = $journal_sale_return_list_name[$i];
                $data['journal_sale_return_list_debit'] = $journal_sale_return_list_debit[$i];
                $data['journal_sale_return_list_credit'] = $journal_sale_return_list_credit[$i];

                if ($journal_sale_return_list_id[$i] != "" && $journal_sale_return_list_id[$i] != '0'){
                    $journal_sale_return_list_model->updateJournalSaleReturnListById($data,$journal_sale_return_list_id[$i]);
                }else{
                    $journal_sale_return_list_model->insertJournalSaleReturnList($data);
                }
            }
        }else{
            $data = [];
            $data['journal_sale_return_id'] = $journal_sale_return_id;
            $data['account_id'] = $account_id;
            $data['journal_sale_return_list_name'] = $journal_sale_return_list_name;
            $data['journal_sale_return_list_debit'] = $journal_sale_return_list_debit;
            $data['journal_sale_return_list_credit'] = $journal_sale_return_list_credit;

            if ($journal_sale_return_list_id != "" && $journal_sale_return_list_id != '0'){
                $journal_sale_return_list_model->updateJournalSaleReturnListById($data,$journal_sale_return_list_id);
            }else{
                $journal_sale_return_list_model->insertJournalSaleReturnList($data);
            }
            
        }
        
        if($output){
    ?>
            <script>window.location="index.php?app=journal_special_06"</script>
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
    $journal_sale_returns = $journal_sale_return_model->getJournalSaleReturnBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');


}





?>