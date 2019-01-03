<?PHP 

session_start();
require_once('../models/CompanyModel.php');
require_once('../models/CreditorReportModel.php');
require_once('../models/CustomerModel.php');

date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s"); 

$path = "print/report_creditor_06/views/";

$company_model = new CompanyModel;
$customer_model = new CustomerModel;
$creditor_report_model = new CreditorReportModel;


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

$creditor_reports = $creditor_report_model->getDebtorListReportBy($customer_id, $code_start, $code_end);

for($i = 0 ; $i < count($creditor_reports); $i++){
    //echo "<b>".$creditor_reports[$i]['customer_code']."</b><br>";
    $creditor_reports[$i]['credit_before'] = $creditor_report_model->getBeforeCreditReportBy($creditor_reports[$i]['customer_id'],$date_start);
    $creditor_reports[$i]['credit_invoice'] = $creditor_report_model->getInvoiceCreditReportBy($creditor_reports[$i]['customer_id'],$date_start,$date_end);
    $creditor_reports[$i]['credit_debit'] = $creditor_report_model->getCreditDebitReportBy($creditor_reports[$i]['customer_id'],$date_start,$date_end);
    $creditor_reports[$i]['credit_credit'] = $creditor_report_model->getCreditCreditReportBy($creditor_reports[$i]['customer_id'],$date_start,$date_end);
    $creditor_reports[$i]['credit_payment'] = $creditor_report_model->getPaymentCreditReportBy($creditor_reports[$i]['customer_id'],$date_start,$date_end);
    $creditor_reports[$i]['credit_balance'] = $creditor_reports[$i]['credit_before'] +  $creditor_reports[$i]['credit_invoice'] + $creditor_reports[$i]['credit_debit'] - $creditor_reports[$i]['credit_credit'] - $creditor_reports[$i]['credit_payment'];
}

$lines = 22;

$page_max = (int)(count($creditor_reports) / $lines);
if(count($creditor_reports) % $lines > 0){
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