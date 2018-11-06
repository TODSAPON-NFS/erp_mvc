<?PHP 

session_start();
require_once('../models/CompanyModel.php');
require_once('../models/DebtorReportModel.php');
require_once('../models/CustomerModel.php');

date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s"); 

$path = "print/report_debtor_06/views/";

$company_model = new CompanyModel;
$customer_model = new CustomerModel;
$debtor_report_model = new DebtorReportModel;


$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];
$code_start = $_GET['code_start'];
$code_end = $_GET['code_end'];

if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}

$val = explode("-",$date_start);

$section_date =  $val['1']."/".$val['2'];

$customer_id = $_GET['customer_id'];
$keyword = $_GET['keyword'];

$company=$company_model->getCompanyByID('1');

$debtor_reports = $debtor_report_model->getDebtorListReportBy($customer_id, $code_start, $code_end);

for($i = 0 ; $i < count($debtor_reports); $i++){
    //echo "<b>".$debtor_reports[$i]['customer_code']."</b><br>";
    $debtor_reports[$i]['debit_before'] = $debtor_report_model->getBeforeDebitReportBy($debtor_reports[$i]['customer_id'],$date_start);
    $debtor_reports[$i]['debit_invoice'] = $debtor_report_model->getInvoiceDebitReportBy($debtor_reports[$i]['customer_id'],$date_start,$date_end);
    $debtor_reports[$i]['debit_debit'] = $debtor_report_model->getDebitDebitReportBy($debtor_reports[$i]['customer_id'],$date_start,$date_end);
    $debtor_reports[$i]['debit_credit'] = $debtor_report_model->getCreditDebitReportBy($debtor_reports[$i]['customer_id'],$date_start,$date_end);
    $debtor_reports[$i]['debit_reciept'] = $debtor_report_model->getRecieveDebitReportBy($debtor_reports[$i]['customer_id'],$date_start,$date_end);
    $debtor_reports[$i]['debit_balance'] = $debtor_reports[$i]['debit_before'] +  $debtor_reports[$i]['debit_invoice'] + $debtor_reports[$i]['debit_debit'] - $debtor_reports[$i]['debit_credit'] - $debtor_reports[$i]['debit_reciept'];
}

$lines = 22;

$page_max = (int)(count($debtor_reports) / $lines);
if(count($debtor_reports) % $lines > 0){
    $page_max += 1;
}

include($path."view.inc.php");



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

        $mpdf->AddPage('L');
        $mpdf->mirrorMargins = true;
        
        $mpdf->SetDisplayMode('fullpage','two');
        

        //$html = ob_get_contents();  
        //ob_end_clean();

        $mpdf->WriteHTML($html[$page_index]);

    }
    
    
    $mpdf->Output();

    //exit;
}else if ($_GET['action'] == "excel") {
    
    header("Content-type: application/vnd.ms-excel");
    // header('Content-type: application/csv'); //*** CSV ***//
    
    header("Content-Disposition: attachment; filename=Sale_vat $d1-$d2-$d3 $d4:$d5:$d6.xls");

    for($page_index=0 ; $page_index < $page_max ; $page_index++){
        echo $html[$page_index] ."<div> </div> <br>"; 
    }
}
?>