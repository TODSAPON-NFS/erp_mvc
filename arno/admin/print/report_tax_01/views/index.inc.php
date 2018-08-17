<?PHP 

session_start();
require_once('../models/CompanyModel.php');
require_once('../models/TaxReportModel.php');
require_once('../models/SupplierModel.php');

date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s"); 

$path = "print/report_tax_01/views/";

$company_model = new CompanyModel;
$supplier_model = new SupplierModel;
$tax_report_model = new TaxReportModel;


$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];

if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}

$val = explode("-",$date_start);

$section_date =  $val['1']."/".$val['2'];

$supplier_id = $_GET['supplier_id'];
$keyword = $_GET['keyword'];

$company=$company_model->getCompanyByID('1'); 
$tax_reports = $tax_report_model->getPurchaseTaxReportBy($date_start,$date_end,$supplier_id,$keyword);


// for($i = 0 ; $i < 80; $i++){
//     $tax_reports[] = $tax_reports[0];
// }

$lines = 30;

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