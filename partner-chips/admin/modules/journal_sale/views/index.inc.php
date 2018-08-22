<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/JournalSaleModel.php');
require_once('../models/JournalSaleListModel.php');
require_once('../models/AccountModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/PaperModel.php');
require_once('../models/UserModel.php');

date_default_timezone_set('asia/bangkok');

$path = "modules/journal_sale/views/";
$account_model = new AccountModel;
$journal_sale_model = new JournalSaleModel;
$journal_sale_list_model = new JournalSaleListModel;

$user_model = new UserModel;
$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('28');

$journal_sale_id = $_GET['id'];
$target_dir = "../upload/journal_sale/";

if(!isset($_GET['action'])){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $keyword = $_GET['keyword']; 
    $journal_sales = $journal_sale_model->getJournalSaleBy($date_start,$date_end,$keyword);
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
            $last_code =  $journal_sale_model->getJournalSaleLastID($last_code,$code[$i]['length']);
        }else{
            $last_code .= $code[$i]['value'];
        }   
    }
    $first_date = date("d")."-".date("m")."-".date("Y");  

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){
    $accounts=$account_model->getAccountAll();
    $journal_sale = $journal_sale_model->getJournalSaleByID($journal_sale_id);
    $journal_sale_lists = $journal_sale_list_model->getJournalSaleListBy($journal_sale_id);
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){
    $journal_sale = $journal_sale_model->getJournalSaleViewByID($journal_sale_id);
    $journal_sale_lists = $journal_sale_list_model->getJournalSaleListBy($journal_sale_id);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'print'){
    $journal_sale = $journal_sale_model->getJournalSaleViewByID($journal_sale_id);
    $journal_sale_lists = $journal_sale_list_model->getJournalSaleListBy($journal_sale_id);
    require_once($path.'print.inc.php');

}else if ($_GET['action'] == 'delete'){

    $journal_sale_list_model->deleteJournalSaleListByJournalSaleID($journal_sale_id);
    $journal_sales = $journal_sale_model->deleteJournalSaleById($journal_sale_id);
?>
    <script>window.location="index.php?app=journal_special_02"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['journal_sale_code'])){

        $data = [];
        $data['journal_sale_date'] = $_POST['journal_sale_date'];
        $data['journal_sale_code'] = $_POST['journal_sale_code'];
        $data['journal_sale_name'] = $_POST['journal_sale_name'];
        $data['addby'] = $user[0][0];


            $journal_sale_id = $journal_sale_model->insertJournalSale($data);

            if($journal_sale_id > 0){

                $account_id = $_POST['account_id'];
                $journal_sale_list_id = $_POST['journal_sale_list_id'];
                $journal_sale_list_name = $_POST['journal_sale_list_name'];
                $journal_sale_list_debit = $_POST['journal_sale_list_debit'];
                $journal_sale_list_credit = $_POST['journal_sale_list_credit'];

                $journal_sale_list_model->deleteJournalSaleListByJournalSaleIDNotIN($journal_sale_id,$journal_sale_list_id);

                if(is_array($account_id)){
                    for($i=0; $i < count($account_id) ; $i++){
                        $data = [];
                        $data['journal_sale_id'] = $journal_sale_id;
                        $data['account_id'] = $account_id[$i];
                        $data['journal_sale_list_name'] = $journal_sale_list_name[$i];
                        $data['journal_sale_list_debit'] = $journal_sale_list_debit[$i];
                        $data['journal_sale_list_credit'] = $journal_sale_list_credit[$i];

                        if ($journal_sale_list_id[$i] != "" && $journal_sale_list_id[$i] != '0'){
                            $journal_sale_list_model->updateJournalSaleListById($data,$journal_sale_list_id[$i]);
                        }else{
                            $journal_sale_list_model->insertJournalSaleList($data);
                        }
                    }
                }else{
                    $data = [];
                    $data['journal_sale_id'] = $journal_sale_id;
                    $data['account_id'] = $account_id;
                    $data['journal_sale_list_name'] = $journal_sale_list_name;
                    $data['journal_sale_list_debit'] = $journal_sale_list_debit;
                    $data['journal_sale_list_credit'] = $journal_sale_list_credit;

                    if ($journal_sale_list_id != "" && $journal_sale_list_id != '0'){
                        $journal_sale_list_model->updateJournalSaleListById($data,$journal_sale_list_id);
                    }else{
                        $journal_sale_list_model->insertJournalSaleList($data);
                    }
                    
                }

    ?>
            <script>window.location="index.php?app=journal_special_02&action=update&id=<?php echo $journal_sale_id;?>"</script>
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
    
    if(isset($_POST['journal_sale_code'])){
        $data = [];
        $data['journal_sale_date'] = $_POST['journal_sale_date'];
        $data['journal_sale_code'] = $_POST['journal_sale_code'];
        $data['journal_sale_name'] = $_POST['journal_sale_name'];
        $data['updateby'] = $user[0][0];

        $output = $journal_sale_model->updateJournalSaleByID($journal_sale_id,$data);

        $account_id = $_POST['account_id'];
        $journal_sale_list_id = $_POST['journal_sale_list_id'];
        $journal_sale_list_name = $_POST['journal_sale_list_name'];
        $journal_sale_list_debit = $_POST['journal_sale_list_debit'];
        $journal_sale_list_credit = $_POST['journal_sale_list_credit'];

        $journal_sale_list_model->deleteJournalSaleListByJournalSaleIDNotIN($journal_sale_id,$journal_sale_list_id);

        if(is_array($account_id)){
            for($i=0; $i < count($account_id) ; $i++){
                $data = [];
                $data['journal_sale_id'] = $journal_sale_id;
                $data['account_id'] = $account_id[$i];
                $data['journal_sale_list_name'] = $journal_sale_list_name[$i];
                $data['journal_sale_list_debit'] = $journal_sale_list_debit[$i];
                $data['journal_sale_list_credit'] = $journal_sale_list_credit[$i];

                if ($journal_sale_list_id[$i] != "" && $journal_sale_list_id[$i] != '0'){
                    $journal_sale_list_model->updateJournalSaleListById($data,$journal_sale_list_id[$i]);
                }else{
                    $journal_sale_list_model->insertJournalSaleList($data);
                }
            }
        }else{
            $data = [];
            $data['journal_sale_id'] = $journal_sale_id;
            $data['account_id'] = $account_id;
            $data['journal_sale_list_name'] = $journal_sale_list_name;
            $data['journal_sale_list_debit'] = $journal_sale_list_debit;
            $data['journal_sale_list_credit'] = $journal_sale_list_credit;

            if ($journal_sale_list_id != "" && $journal_sale_list_id != '0'){
                $journal_sale_list_model->updateJournalSaleListById($data,$journal_sale_list_id);
            }else{
                $journal_sale_list_model->insertJournalSaleList($data);
            }
            
        }
        
        if($output){
    ?>
            <script>window.location="index.php?app=journal_special_02"</script>
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
    $journal_sales = $journal_sale_model->getJournalSaleBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');


}





?>