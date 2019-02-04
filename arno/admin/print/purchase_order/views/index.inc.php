<?PHP 

session_start();
require_once('../functions/NumbertoTextFunction.func.php');
require_once('../models/CompanyModel.php');
require_once('../models/PurchaseOrderModel.php'); 
require_once('../models/PurchaseOrderListModel.php');

date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s"); 

$path = "print/purchase_order/views/";

$number_2_text = new Number2Text;
$company_model = new CompanyModel;
$purchase_order_model = new PurchaseOrderModel; 
$purchase_order_list_model = new PurchaseOrderListModel; 


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





$html = '';
 

if($_GET['action'] == "pdf"){ 
    $purchase_order = $purchase_order_model->getPurchaseOrderViewByID($purchase_order_id);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);

    $lines = 6;

    $page_max = (int)(count($purchase_order_lists) / $lines);
    if(count($purchase_order_lists) % $lines > 0){
        $page_max += 1;
    }

    if($_GET['lan'] == "en"){ 
        include($path."view-pdf-en.inc.php");
    }else{
        include($path."view-pdf-th.inc.php");
    }

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

    $purchase_orders = $purchase_order_model->getPurchaseOrderExport($purchase_order_id,$supplier_id,$date_start,$date_end,$keyword);
    include($path."view-excel.inc.php");
    header("Content-type: application/vnd.ms-excel");
	// header('Content-type: application/csv'); //*** CSV ***//
    header("Content-Disposition: attachment; filename=purchase order export $d1-$d2-$d3 $d4:$d5:$d6.xls");
 
    echo $html;  
}
?>