<?PHP 

session_start();
require_once('../models/CompanyModel.php');
require_once('../models/BillingNoteModel.php');
require_once('../models/BillingNoteListModel.php');
require_once('../functions/NumbertoTextFunction.func.php'); 

date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s"); 

$path = "print/billing_note/views/";

$number_2_text = new Number2Text;
$company_model = new CompanyModel;
$billing_note_model = new BillingNoteModel;
$billing_note_list_model = new BillingNoteListModel;

$billing_note_id = $_GET['id'];

$company=$company_model->getCompanyByID('1'); 

$billing_note = $billing_note_model->getBillingNoteViewByID($billing_note_id);
$billing_note_lists = $billing_note_list_model->getBillingNoteListBy($billing_note_id);


// for($i = 0 ; $i < 80; $i++){
//     $tax_reports[] = $tax_reports[0];
// }

$lines = 10;

$page_max = (int)(count($billing_note_lists) / $lines);
if(count($billing_note_lists) % $lines > 0){
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