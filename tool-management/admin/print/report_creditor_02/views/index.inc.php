<?PHP 

session_start();
require_once('../models/CreditorReportModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/InvoiceSupplierModel.php');
require_once('../models/CompanyModel.php');

date_default_timezone_set('asia/bangkok'); 
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s"); 

$path = "print/report_creditor_02/views/";

$company_model = new CompanyModel;
$supplier_model = new SupplierModel;
$invoice_supplier_model = new InvoiceSupplierModel; 
$creditor_report_model = new CreditorReportModel; 


  

$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];

if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}

$supplier_id = $_GET['supplier_id'];
$keyword = $_GET['keyword'];
$code_start = $_GET['code_start'];
$code_end = $_GET['code_end'];

$suppliers=$supplier_model->getSupplierBy();

$tax_reports = $creditor_report_model->getInvoiceSupplierReportBy($date_start, $date_end, $code_start ,$code_end, $supplier_id, $keyword); 

$company=$company_model->getCompanyByID('1'); 
// for($i = 0 ; $i < 80; $i++){
//     $tax_reports[] = $tax_reports[0];
// }

$lines = 28;

$page_max = (int)(count($tax_reports) / $lines);
if(count($tax_reports) % $lines > 0){
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

        $mpdf->AddPage('P');
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
    header("Content-Disposition: attachment; filename=purchase_vat $d1-$d2-$d3 $d4:$d5:$d6.xls");

    for($page_index=0 ; $page_index < $page_max ; $page_index++){
        echo $html[$page_index] ."<div> </div> <br>"; 
    }
}
?>