<?php
date_default_timezone_set('Asia/Bangkok');

require_once('../models/CurrencyModel.php');
require_once('../models/ExchangeRateBahtModel.php');

require_once('../functions/DateTimeFunction.func.php');

$path = "modules/exchange_rate_baht/views/";
$currencies_model = new CurrencyModel;
$date_time_function = new DateTimeFunction;
$exchange_rate_baht_model = new ExchangeRateBahtModel;


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
 
$start = $date_start; 
$end = $date_end;

$exchange_rate_baht_id = $_GET['id'];

if(!isset($_GET['action'])){
    $exchange_rate_bahts = $exchange_rate_baht_model->getExchangeRateBahtByDate($start, $end);
    $currencies = $currencies_model->getCurrencyBy();
    if($exchange_rate_baht_id != ""){
        $exchange_rate_baht = $exchange_rate_baht_model->getExchangeRateBahtById($exchange_rate_baht_id); 
    }
    require_once($path.'view.inc.php');

}else if($_GET['action'] == 'update'){
    $exchange_rate_bahts = $exchange_rate_baht_model->getExchangeRateBahtByDate($start, $end);
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

    $exchange_rate_bahts = $exchange_rate_baht_model->getExchangeRateBahtByDate($start, $end);
    $currencies = $currencies_model->getCurrencyBy();
    if($exchange_rate_baht_id != ""){
        $exchange_rate_baht = $exchange_rate_baht_model->getExchangeRateBahtById($exchange_rate_baht_id);
        $dt = explode(' ',$exchange_rate_baht['exchange_rate_baht_baht_date']);
        $dt = explode('-',$dt[0]);
        $exchange_rate_baht['exchange_rate_baht_baht_date'] =  $dt[2].'-'.$dt[1].'-'.$dt[0];
    }
    require_once($path.'view.inc.php');

}





?>