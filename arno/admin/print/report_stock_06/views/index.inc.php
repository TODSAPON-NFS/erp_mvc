<?PHP 

session_start();
require_once('../models/StockReportModel.php'); 

date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s");  

$path = "print/report_stock_06/views/";
 
$stock_report_model = new StockReportModel;


$date_target = $_GET['date_target'];
$table_name = $_GET['table_name'];
$table_name_text = $_GET['table_name_text'];
$group_by = $_GET['group_by'];
$group_by_text = $_GET['group_by_text'];
$paper_code = $_GET['paper_code'];
$stock_start = $_GET['stock_start'];
$stock_end = $_GET['stock_end'];
$product_start = $_GET['product_start'];
$product_end = $_GET['product_end'];       

if($group_by == "product_code"){ 

    $stock_reports = $stock_report_model->getStockReportProductMovementDayBy($date_target,$stock_start,$stock_end,$product_start,$product_end,$table_name,$group_by,$paper_code);  
    require_once($path.'view-product.inc.php');
}else if($group_by == "stock_group_code"){ 

    $stock_reports = $stock_report_model->getStockReportProductMovementDayBy($date_target,$stock_start,$stock_end,$product_start,$product_end,$table_name,$group_by,$paper_code);  
    require_once($path.'view-stock.inc.php');
    
}else{ 

    $stock_reports = $stock_report_model->getStockReportProductMovementDayBy($date_target,$stock_start,$stock_end,$product_start,$product_end,$table_name,$group_by,$paper_code);  
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
    $mpdf->mirrorMargins = true;
    $mpdf->SetHTMLHeader($html_head_pdf,'O');
    $mpdf->SetHTMLHeader($html_head_pdf,'E'); 

    $mpdf->AddPage('L', // L - landscape, P - portrait 
    '', '', '', '',
    10, // margin_left
    10, // margin right
    55, // margin top
    10, // margin bottom
    10, // margin header
    0); // margin footer  
    
    $mpdf->WriteHTML($html);  
    
    $mpdf->Output();

    //exit;
}else if ($_GET['action'] == "excel") {
    
    header("Content-type: application/vnd.ms-excel");
    // header('Content-type: application/csv'); //*** CSV ***//
    
    header("Content-Disposition: attachment; filename=StockMoveDay $d1-$d2-$d3 $d4:$d5:$d6.xls");

    
        echo $html_head_excel.$html."<div> </div> <br>"; 
     
}
?>