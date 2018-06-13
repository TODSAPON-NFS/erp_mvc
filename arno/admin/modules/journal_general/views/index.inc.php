<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/JournalGeneralModel.php');
require_once('../models/JournalGeneralListModel.php');
require_once('../models/AccountModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/journal_general/views/";
$account_model = new AccountModel;
$journal_general_model = new JournalGeneralModel;
$journal_general_list_model = new JournalGeneralListModel;
$first_char = "JG";
$journal_general_id = $_GET['id'];
$target_dir = "../upload/journal_general/";

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword']; 
    $journal_generals = $journal_general_model->getJournalGeneralBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'insert'){
    $accounts=$account_model->getAccountAll();
    $first_code = $first_char.date("y").date("m");
    $first_date = date("d")."-".date("m")."-".date("Y");
    $last_code = $journal_general_model->getJournalGeneralLastID($first_code,3);

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $accounts=$account_model->getAccountAll();
    $journal_general = $journal_general_model->getJournalGeneralByID($journal_general_id);
    $journal_general_lists = $journal_general_list_model->getJournalGeneralListBy($journal_general_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $journal_general = $journal_general_model->getJournalGeneralViewByID($journal_general_id);
    $journal_general_lists = $journal_general_list_model->getJournalGeneralListBy($journal_general_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $journal_general = $journal_general_model->getJournalGeneralViewByID($journal_general_id);
    $journal_general_lists = $journal_general_list_model->getJournalGeneralListBy($journal_general_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){

    $journal_general_list_model->deleteJournalGeneralListByJournalGeneralID($journal_general_id);
    $journal_generals = $journal_general_model->deleteJournalGeneralById($journal_general_id);
?>
    <script>window.location="index.php?app=journal_general"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['journal_general_code'])){

        $data = [];
        $data['journal_general_date'] = $_POST['journal_general_date'];
        $data['journal_general_code'] = $_POST['journal_general_code'];
        $data['journal_general_name'] = $_POST['journal_general_name'];
        $data['addby'] = $user[0][0];


            $journal_general_id = $journal_general_model->insertJournalGeneral($data);

            if($journal_general_id > 0){

                $account_id = $_POST['account_id'];
                $journal_general_list_id = $_POST['journal_general_list_id'];
                $journal_general_list_name = $_POST['journal_general_list_name'];
                $journal_general_list_debit = $_POST['journal_general_list_debit'];
                $journal_general_list_credit = $_POST['journal_general_list_credit'];

                $journal_general_list_model->deleteJournalGeneralListByJournalGeneralIDNotIN($journal_general_id,$journal_general_list_id);

                if(is_array($account_id)){
                    for($i=0; $i < count($account_id) ; $i++){
                        $data = [];
                        $data['journal_general_id'] = $journal_general_id;
                        $data['account_id'] = $account_id[$i];
                        $data['journal_general_list_name'] = $journal_general_list_name[$i];
                        $data['journal_general_list_debit'] = $journal_general_list_debit[$i];
                        $data['journal_general_list_credit'] = $journal_general_list_credit[$i];

                        if ($journal_general_list_id[$i] != "" && $journal_general_list_id[$i] != '0'){
                            $journal_general_list_model->updateJournalGeneralListById($data,$journal_general_list_id[$i]);
                        }else{
                            $journal_general_list_model->insertJournalGeneralList($data);
                        }
                    }
                }else{
                    $data = [];
                    $data['journal_general_id'] = $journal_general_id;
                    $data['account_id'] = $account_id;
                    $data['journal_general_list_name'] = $journal_general_list_name;
                    $data['journal_general_list_debit'] = $journal_general_list_debit;
                    $data['journal_general_list_credit'] = $journal_general_list_credit;

                    if ($journal_general_list_id != "" && $journal_general_list_id != '0'){
                        $journal_general_list_model->updateJournalGeneralListById($data,$journal_general_list_id);
                    }else{
                        $journal_general_list_model->insertJournalGeneralList($data);
                    }
                    
                }

    ?>
            <script>window.location="index.php?app=journal_general&action=update&id=<?php echo $journal_general_id;?>"</script>
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
    
    if(isset($_POST['journal_general_code'])){
        $data = [];
        $data['journal_general_date'] = $_POST['journal_general_date'];
        $data['journal_general_code'] = $_POST['journal_general_code'];
        $data['journal_general_name'] = $_POST['journal_general_name'];
        $data['updateby'] = $user[0][0];

        $output = $journal_general_model->updateJournalGeneralByID($journal_general_id,$data);

        $account_id = $_POST['account_id'];
        $journal_general_list_id = $_POST['journal_general_list_id'];
        $journal_general_list_name = $_POST['journal_general_list_name'];
        $journal_general_list_debit = $_POST['journal_general_list_debit'];
        $journal_general_list_credit = $_POST['journal_general_list_credit'];

        $journal_general_list_model->deleteJournalGeneralListByJournalGeneralIDNotIN($journal_general_id,$journal_general_list_id);

        if(is_array($account_id)){
            for($i=0; $i < count($account_id) ; $i++){
                $data = [];
                $data['journal_general_id'] = $journal_general_id;
                $data['account_id'] = $account_id[$i];
                $data['journal_general_list_name'] = $journal_general_list_name[$i];
                $data['journal_general_list_debit'] = $journal_general_list_debit[$i];
                $data['journal_general_list_credit'] = $journal_general_list_credit[$i];

                if ($journal_general_list_id[$i] != "" && $journal_general_list_id[$i] != '0'){
                    $journal_general_list_model->updateJournalGeneralListById($data,$journal_general_list_id[$i]);
                }else{
                    $journal_general_list_model->insertJournalGeneralList($data);
                }
            }
        }else{
            $data = [];
            $data['journal_general_id'] = $journal_general_id;
            $data['account_id'] = $account_id;
            $data['journal_general_list_name'] = $journal_general_list_name;
            $data['journal_general_list_debit'] = $journal_general_list_debit;
            $data['journal_general_list_credit'] = $journal_general_list_credit;

            if ($journal_general_list_id != "" && $journal_general_list_id != '0'){
                $journal_general_list_model->updateJournalGeneralListById($data,$journal_general_list_id);
            }else{
                $journal_general_list_model->insertJournalGeneralList($data);
            }
            
        }
        
        if($output){
    ?>
            <script>window.location="index.php?app=journal_general"</script>
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
    $journal_generals = $journal_general_model->getJournalGeneralBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');


}





?>