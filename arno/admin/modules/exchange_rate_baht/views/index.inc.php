<?php
date_default_timezone_set('Asia/Bangkok');

require_once('../models/CurrencyModel.php');
require_once('../models/ExchangeRateBahtModel.php');

require_once('../functions/DateTimeFunction.func.php');

$path = "modules/exchange_rate_baht/views/";
$currencies_model = new CurrencyModel;
$date_time_function = new DateTimeFunction;
$exchange_rate_baht_model = new ExchangeRateBahtModel;

if($license_purchase_page == "Medium" || $license_purchase_page == "High"){
    $lock_1 = "1";
}else{
    $lock_1 = "0";
}

if($license_account_page == "Medium" || $license_account_page == "High"){
    $lock_2 = "1";
}else{
    $lock_2 = "0";
}

if(!isset($_GET['date_start'])){
    $date_start = $_SESSION['date_start'];
}else{
    $date_start = $_GET['date_start'];
    $_SESSION['date_start'] = $date_start;
}


if(!isset($_GET['date_end'])){
    $date_end = $_SESSION['date_end'];
}else{
    $date_end = $_GET['date_end'];
    $_SESSION['date_end'] = $date_end;
}

if(!isset($_GET['keyword'])){
    $keyword = $_SESSION['keyword'];
}else{
    
    $keyword = $_GET['keyword']; 
    $_SESSION['keyword'] = $keyword;
}
 
if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}

$exchange_rate_baht_id = $_GET['id'];

if(!isset($_GET['action'])){
    $exchange_rate_bahts = $exchange_rate_baht_model->getExchangeRateBahtByDate($date_start, $date_end,$lock_1,$lock_2);
    $currencies = $currencies_model->getCurrencyBy();
    if($exchange_rate_baht_id != ""){
        $exchange_rate_baht = $exchange_rate_baht_model->getExchangeRateBahtById($exchange_rate_baht_id); 
    }
    require_once($path.'view.inc.php');

}else if($_GET['action'] == 'update'){
    $exchange_rate_bahts = $exchange_rate_baht_model->getExchangeRateBahtByDate($date_start, $date_end,$lock_1,$lock_2);
    $currencies = $currencies_model->getCurrencyBy();
    if($exchange_rate_baht_id != ""){
        $exchange_rate_baht = $exchange_rate_baht_model->getExchangeRateBahtById($exchange_rate_baht_id); 
    }
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete'){

    $exchange_rate_baht_model->deleteExchangeRateBahtById($exchange_rate_baht_id);
    
?>
    <script>window.location="index.php?app=exchange_rate_baht"</script>
<?php

}else if ($_GET['action'] == 'add'){
    if(isset($_POST['currency_id'])){ 

        $data = [];
        $data['currency_id'] = $_POST['currency_id'];
        $data['exchange_rate_baht_value'] = $_POST['exchange_rate_baht_value'];
        $data['exchange_rate_baht_date'] = $_POST['exchange_rate_baht_date'];

        $id = $exchange_rate_baht_model->insertExchangeRateBaht($data);

        ?>
            <script>window.location="index.php?app=exchange_rate_baht&action=update&id=<?PHP echo $id;?>"</script>
        <?php

    }

    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['currency_id'])){ 

        $data = [];
        $data['currency_id'] = $_POST['currency_id'];
        $data['exchange_rate_baht_value'] = $_POST['exchange_rate_baht_value'];
        $data['exchange_rate_baht_date'] = $_POST['exchange_rate_baht_date'];

       
        $id = $exchange_rate_baht_model->updateExchangeRateBahtByID($exchange_rate_baht_id,$data);
        ?>
            <script>window.location="index.php?app=exchange_rate_baht"</script>
        <?php
        
    }

}else{

    $exchange_rate_bahts = $exchange_rate_baht_model->getExchangeRateBahtByDate($date_start, $date_end,$lock_1,$lock_2);
    $currencies = $currencies_model->getCurrencyBy();
    if($exchange_rate_baht_id != ""){
        $exchange_rate_baht = $exchange_rate_baht_model->getExchangeRateBahtById($exchange_rate_baht_id); 
    }
    require_once($path.'view.inc.php');

}





?>