<?PHP 

session_start();
require_once('../models/JournalCashPaymentModel.php');
require_once('../models/JournalCashPaymentListModel.php');

require_once('../models/CheckModel.php');
require_once('../models/CheckPayModel.php');
require_once('../models/InvoiceSupplierModel.php');
require_once('../models/InvoiceCustomerModel.php');

require_once('../models/CompanyModel.php');
date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s"); 

$path = "print/report_journal_04/views/";

$company_model = new CompanyModel;

$journal_cash_payment_model = new JournalCashPaymentModel;
$journal_cash_payment_list_model = new JournalCashPaymentListModel;

$check_model = new CheckModel;
$check_pay_model = new CheckPayModel;
$invoice_supplier_model = new InvoiceSupplierModel;
$invoice_customer_model = new InvoiceCustomerModel;

$lines = 28;

if($_GET['type'] == "id"){
    $journal_cash_payment_id = $_GET['id'];

    $company=$company_model->getCompanyByID('1'); 
    
    $journal_cash_payment = $journal_cash_payment_model->getJournalCashPaymentByID($journal_cash_payment_id);
    $journal_cash_payment_lists = $journal_cash_payment_list_model->getJournalCashPaymentListBy($journal_cash_payment_id);
    
    $checks = $check_model->getCheckViewListByjournalPaymentID($journal_cash_payment_id);
    $check_pays = $check_pay_model->getCheckPayViewListByjournalPaymentID($journal_cash_payment_id);
    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierViewListByjournalPaymentID($journal_cash_payment_id);
    $invoice_customers = $invoice_customer_model->getInvoiceCustomerViewListByjournalPaymentID($journal_cash_payment_id);
}





// for($i = 0 ; $i < 80; $i++){
//     $tax_reports[] = $tax_reports[0];
// }



$lines_max = count($journal_cash_payment_lists) + count($checks) + count($check_pays) + count($invoice_suppliers) + count($invoice_customers) ; 

$page_max = (int)($lines_max / $lines);
if($lines_max % $lines > 0){
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