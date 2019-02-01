<?php
session_start();

require_once('../models/StockReportModel.php'); 
require_once('../models/StockGroupModel.php');
require_once('../models/CompanyModel.php'); 
date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s");
$date_  = date('d-m-Y');

$path = "print/report_stock_11/views/";
 
$stock_report_model = new StockReportModel;
$model_group = new StockGroupModel;
$company_model = new CompanyModel;





if(!isset($_GET['date_end'])){
    $date_end = $_SESSION['date_end'];
}else{
    $date_end = $_GET['date_end'];
    $_SESSION['date_end'] = $date_end;
}





$stock_reports = $stock_report_model->getStockCostBy($date_end);

//   echo "<pre>";
//    print_r($stock_reports);
//   echo "</pre>";

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

    $mpdf->AddPage('P', // L - landscape, P - portrait 
    '', '', '', '',
    10, // margin_left
    10, // margin right
    45, // margin top
    20, // margin bottom
    10, // margin header
    0); // margin footer  
    
    $mpdf->WriteHTML($html);  
    //echo $html ;
    $mpdf->Output();

    

    //exit;
}else if ($_GET['action'] == "excel") {
    
    header("Content-type: application/vnd.ms-excel");
    // header('Content-type: application/csv'); //*** CSV ***//
    
    header("Content-Disposition: attachment; filename=Stock $d1-$d2-$d3 $d4:$d5:$d6.xls");

    
        echo $html_head_excel.$html."<div> </div> <br>"; 
     
}
  




?>