<?PHP 

session_start();
require_once('../functions/NumbertoTextFunction.func.php');
require_once('../models/CompanyModel.php');
require_once('../models/PurchaseOrderModel.php'); 
require_once('../models/PurchaseOrderListModel.php');
require_once('../models/InvoiceSupplierListModel.php');
require_once('../models/InvoiceSupplierModel.php');
require_once('../models/InvoiceSupplierModel.php');
require_once('../models/ExchangeRateBahtModel.php');
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s"); 

$path = "print/invoice_supplier_abroad/views/";

$number_2_text = new Number2Text;
$company_model = new CompanyModel;
$invoice_supplier_abroad_model = new InvoiceSupplierModel;
$invoice_supplier_abroad_list_model = new InvoiceSupplierListModel; 
$purchaseOrder_model = new PurchaseOrderModel;
$supplier_model = new SupplierModel;
$exchange_rate_baht_model = new ExchangeRateBahtModel; 
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
$invoice_supplier_abroad_id = $_GET['id'];

$company=$company_model->getCompanyByID('1'); 





$html = '';
 

if($_GET['action'] == "pdf"){ 

    $invoice_supplier_abroad = $invoice_supplier_abroad_model->getInvoiceSupplierViewByID($invoice_supplier_abroad_id);       
    $invoice_supplier_abroad_lists = $invoice_supplier_abroad_list_model->getInvoiceSupplierListBy($invoice_supplier_abroad_id);
    $purchaseOrder_code = $purchaseOrder_model->getPurchaseOrderCodeByInvoiceSupplierID($invoice_supplier_abroad_id); 
    

    $supplier=$supplier_model->getSupplierByID($invoice_supplier_abroad['supplier_id']);
    $exchange_rate_baht = $exchange_rate_baht_model->getExchangeRateBahtByCurrncyID($invoice_supplier_abroad['invoice_supplier_date_recieve'],$supplier['currency_id']);
 
    $lines = 8;

    $page_max = (int)(count($invoice_supplier_abroad_lists) / $lines);
    if(count($invoice_supplier_abroad_lists) % $lines > 0){
        $page_max += 1;
    }

    if($_GET['type'] == "credit"){

        require_once($path."view-pdf-credit.inc.php");

    }else{

        require_once($path."view-pdf-receive.inc.php");

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
        //echo $html[$page_index];
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