<?PHP 

session_start();
require_once('../models/CompanyModel.php');
require_once('../models/PurchaseOrderModel.php'); 

date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s"); 

$path = "print/purchase_order/views/";

$company_model = new CompanyModel;
$purchase_order_model = new PurchaseOrderModel; 


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
$purchase_order_id = $_GET['id']; 

$company=$company_model->getCompanyByID('1'); 



$purchase_orders = $purchase_order_model->getPurchaseOrderExport($purchase_order_id,$supplier_id,$date_start,$date_end,$keyword);

$html = '';
 
include($path."view.inc.php");



if($_GET['action'] == "pdf"){ 
    include("../plugins/mpdf/mpdf.php");
    $mpdf=new mPDF('th', 'A4', '0', 'garuda');   

    $mpdf->AddPage('L');
    $mpdf->mirrorMargins = true;
    
    $mpdf->SetDisplayMode('fullpage','two');
    

    $mpdf->WriteHTML($html); 
    
    $mpdf->Output();

    //exit;
}else if ($_GET['action'] == "excel") {
    
    header("Content-type: application/vnd.ms-excel");
	// header('Content-type: application/csv'); //*** CSV ***//
    header("Content-Disposition: attachment; filename=purchase order export $d1-$d2-$d3 $d4:$d5:$d6.xls");
 
    echo $html;  
}
?>