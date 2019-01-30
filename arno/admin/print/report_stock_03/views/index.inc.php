<?PHP 

session_start();
require_once('../models/StockReportModel.php'); 
require_once('../models/CompanyModel.php'); 
date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s");  

$path = "print/report_stock_03/views/";
 
$stock_report_model = new StockReportModel;
$company_model = new CompanyModel;

$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];
$stock_start = $_GET['stock_start'];
$stock_end = $_GET['stock_end'];
$product_start = $_GET['product_start'];
$product_end = $_GET['product_end'];    


$stock_reports = $stock_report_model->getStockReportProductMovementBy($date_start,$date_end,$stock_start,$stock_end,$product_start,$product_end);
 
$company=$company_model->getCompanyByID('1');
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
    $mpdf->mirrorMargins = true;
    $mpdf->SetHTMLHeader($html_head_pdf,'O');
    $mpdf->SetHTMLHeader($html_head_pdf,'E'); 

    $mpdf->AddPage('L', // L - landscape, P - portrait 
    '', '', '', '',
    10, // margin_left
    10, // margin right
    52, // margin top
    20, // margin bottom
    10, // margin header
    0); // margin footer  
    
    $mpdf->WriteHTML($html);  
    
    $mpdf->Output();

    //exit;
}else if ($_GET['action'] == "excel") {
    
    header("Content-type: application/vnd.ms-excel");
    // header('Content-type: application/csv'); //*** CSV ***//
    
    header("Content-Disposition: attachment; filename=StockMove $d1-$d2-$d3 $d4:$d5:$d6.xls");

    
        echo $html_head_excel.$html."<div> </div> <br>"; 
     
}
?>