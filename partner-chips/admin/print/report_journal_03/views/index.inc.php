<?PHP 

session_start();
require_once('../models/JournalCashReceiptModel.php');
require_once('../models/JournalCashReceiptListModel.php');

require_once('../models/UserModel.php');
require_once('../models/CheckModel.php');
require_once('../models/CheckPayModel.php');
require_once('../models/InvoiceSupplierModel.php');
require_once('../models/InvoiceCustomerModel.php');

require_once('../models/CompanyModel.php');
date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s"); 

$path = "print/report_journal_03/views/";

$company_model = new CompanyModel;
$user_model = new UserModel;

$journal_cash_receipt_model = new JournalCashReceiptModel;
$journal_cash_receipt_list_model = new JournalCashReceiptListModel;

$check_model = new CheckModel;
$check_pay_model = new CheckPayModel;
$invoice_supplier_model = new InvoiceSupplierModel;
$invoice_customer_model = new InvoiceCustomerModel;
 
$user = $user_model->getUserByID($admin_id);
if($_GET['type'] == "id"){
    $journal_cash_receipt_id = $_GET['id'];

    $company=$company_model->getCompanyByID('1'); 
    
    $journal_cash_receipt = $journal_cash_receipt_model->getJournalCashReceiptByID($journal_cash_receipt_id);
    $journal_cash_receipt_lists = $journal_cash_receipt_list_model->getJournalCashReceiptListBy($journal_cash_receipt_id);
    
    $checks = $check_model->getCheckViewListByjournalReceiptID($journal_cash_receipt_id);
    $check_pays = $check_pay_model->getCheckPayViewListByjournalReceiptID($journal_cash_receipt_id);
    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierViewListByjournalReceiptID($journal_cash_receipt_id);
    $invoice_customers = $invoice_customer_model->getInvoiceCustomerViewListByjournalReceiptID($journal_cash_receipt_id);
}

$lines = 23;
 
$page_max = 0;
 
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