<?PHP 

session_start();

require_once('../models/JournalReportModel.php'); 
require_once('../models/CompanyModel.php'); 
require_once('../models/AccountModel.php');
date_default_timezone_set('asia/bangkok');

$path = "print/report_account_07/views/";
 
$company_model = new CompanyModel;
$journal_report_model = new JournalReportModel;
$account_model = new AccountModel;






if(!isset($_GET['date_end'])){
    $date_end = $_SESSION['date_end'];
}else{
    $date_end = $_GET['date_end'];
    $_SESSION['date_end'] = $date_end;
}



if(!isset($_GET['account_id']) || $_GET['account_id'] =='' ){
    $account_id = $_SESSION['account_id'];
    echo 'dsad';
}else{  
    echo 'dsadsssssss';
    $account_id= $_GET['account_id']; 
    $_SESSION['account_id'] = $account_id;
}




$type = $_GET['type'];


echo $date_end.'<br>';
echo $account_id.'<br>';

$company=$company_model->getCompanyByID('1');

$lines = 52;
$account = $account_model->getAccountAll();

$journal_reports = $journal_report_model->getJournalAcountReportShowpayAllBy($date_end,$account_id);

//print_r($journal_reports);

$page_max = (int)(count($journal_reports) / $lines);
if(count($journal_reports) % $lines > 0){
    $page_max += 1;
}
//echo '<pre>';
//print_r($journal_reports);
//echo '</pre>';

require_once($path.'view.inc.php');



if($_GET['action'] == "pdf"){
    /*############################### FPDF ##############################*/
    // require('../plugins/fpdf/fpdf.php');
	// $pdf=new FPDF();
	// $pdf->AddPage();
    // $pdf->SetFont('Times','B',16); 
    // $pdf->Output();
    /*############################### FPDF ##############################*/

    include("../plugins/mpdf/mpdf.php");
    $mpdf=new mPDF('th', 'A4', '0', 'garuda');  
    
    for($page_index=0 ; $page_index < $page_max ; $page_index++){

        $mpdf->AddPage('P');
        $mpdf->mirrorMargins = true;
        
        $mpdf->SetDisplayMode('fullpage','two');
        

        ////$html = ob_get_contents();  
       // //ob_end_clean();

        $mpdf->WriteHTML($html[$page_index]);
        echo $html[$page_index];
    }
    
    
    //$mpdf->Output();

    //exit;
}else if ($_GET['action'] == "excel") {
    
    header("Content-type: application/vnd.ms-excel");
    //// header('Content-type: application/csv'); //*** CSV ***//
    
    header("Content-Disposition: attachment; filename=Sale_vat $d1-$d2-$d3 $d4:$d5:$d6.xls");

    for($page_index=0 ; $page_index < $page_max ; $page_index++){
        echo $html[$page_index] ."<div> </div> <br>"; 
    }
}
?>