<?PHP 

session_start();
require_once('../models/StockReportModel.php'); 
require_once('../models/ProductTypeModel.php');
require_once('../models/ProductCategoryModel.php'); 

date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s");  

$path = "print/report_stock_05/views/";
 
$stock_report_model = new StockReportModel; 
$product_type_model = new ProductTypeModel;
$product_category_model = new ProductCategoryModel;

$product_category_id = $_GET['product_category_id'];
$product_type_id = $_GET['product_type_id'];
$product_start = $_GET['product_start'];
$product_end = $_GET['product_end'];    

$product_type = $product_type_model->getProductTypeByID($product_type_id);
$product_category = $product_category_model->getProductCategoryByID($product_category_id);
$stock_reports = $stock_report_model->getStockReportProductBy($product_category_id, $product_type_id,$product_start,$product_end);
 

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
    43, // margin top
    10, // margin bottom
    10, // margin header
    0); // margin footer  
    
    $mpdf->WriteHTML($html);  
    
    $mpdf->Output();

    //exit;
}else if ($_GET['action'] == "excel") {
    
    header("Content-type: application/vnd.ms-excel");
    // header('Content-type: application/csv'); //*** CSV ***//
    
    header("Content-Disposition: attachment; filename=ProductPrice $d1-$d2-$d3 $d4:$d5:$d6.xls");

    
        echo $html_head_excel.$html."<div> </div> <br>"; 
     
}
?>