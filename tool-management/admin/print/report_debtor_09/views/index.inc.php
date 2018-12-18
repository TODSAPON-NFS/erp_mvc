<?PHP 

session_start();
require_once('../models/CompanyModel.php');
require_once('../models/DebtorReportModel.php'); 
 

$path = "print/report_debtor_09/views/";

$company_model = new CompanyModel; 
$debtor_report_model = new DebtorReportModel;
$company=$company_model->getCompanyByID('1'); 



$code_start = $_GET['code_start'];
$code_end = $_GET['code_end'];  
$view_type = $_GET['view_type']; 

$debtor_reports = $debtor_report_model->getCustomerListReportBy($code_start, $code_end);


if($code_start == ""){
    $code_start = "-";
}

if($code_end == ""){
    $code_end = "-";
}

if($view_type == 'full'){
    
    require_once($path.'view-full.inc.php');
}else{
    
    require_once($path.'view.inc.php');
}



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

    $mpdf->AddPage('L');
    $mpdf->mirrorMargins = true;
    
    $mpdf->SetDisplayMode('fullpage','two');
    

    //$html = ob_get_contents();  
    //ob_end_clean();

    $mpdf->WriteHTML($html); 
    
    
    $mpdf->Output();

    //exit;
}else if ($_GET['action'] == "excel") {
    
    header("Content-type: application/vnd.ms-excel");
    // header('Content-type: application/csv'); //*** CSV ***//
    
    header("Content-Disposition: attachment; filename=Sale_vat $d1-$d2-$d3 $d4:$d5:$d6.xls");
 
    echo $html  ."<div> </div> <br>";  
}
?>