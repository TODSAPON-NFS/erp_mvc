<?PHP 

session_start();

require_once('../models/StockReportModel.php'); 
require_once('../models/ProductTypeModel.php'); 
require_once('../models/SupplierModel.php');

date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s");  

$path = "print/report_stock_07/views/"; 

$stock_report_model = new StockReportModel; 
$product_type_model = new ProductTypeModel; 
$supplier_model = new SupplierModel;
 
$product_type_id = $_GET['product_type_id']; 

$supplier_id = $_GET['supplier_id']; 

$product_qty = $_GET['product_qty']; 
$product_qty_text = $_GET['product_qty_text']; 

$product_start = $_GET['product_start']; 

$product_end = $_GET['product_end'];

$product_type = $product_type_model->getProductTypeByID($product_type_id); 
$suppliers = $supplier_model->getSupplierByID($supplier_id);
$stock_reports = $stock_report_model->getStockReportMinPointBy($product_start,$product_end,$product_type_id,$supplier_id,$product_qty);
// echo '<pre>';
// print_r($stock_reports);
// echo '</pre>';
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
    
    header("Content-Disposition: attachment; filename=StockPoint $d1-$d2-$d3 $d4:$d5:$d6.xls");

    
        echo $html_head_excel.$html."<div> </div> <br>"; 
     
}
?>