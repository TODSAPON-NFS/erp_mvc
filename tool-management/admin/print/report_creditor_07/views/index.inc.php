<?PHP 

session_start();
require_once('../models/CompanyModel.php');
require_once('../models/CreditorReportModel.php');
require_once('../models/SupplierModel.php');

date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s"); 

$path = "print/report_creditor_07/views/";

$company_model = new CompanyModel;
$supplier_model = new SupplierModel;
$creditor_report_model = new CreditorReportModel;


$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];

if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}

$val = explode("-",$date_start);

$section_date =  $val['1']."/".$val['2'];

$supplier_id = $_GET['supplier_id'];
$view_type = $_GET['view_type'];

$company=$company_model->getCompanyByID('1'); 


$lines = 24;
 
$page_max = 0;

if($view_type == 'full'){
    $creditor_reports = $creditor_report_model->getCreditorListDetailReportBy($date_end, $supplier_id, $keyword);
    require_once($path.'view-full.inc.php');
}else{
        
    $creditor_reports = $creditor_report_model->getAccountsPayableListReportBy($date_end, $supplier_id, $keyword);

    for($i = 0 ; $i < count($creditor_reports); $i++){
        $papers = $creditor_report_model->getCreditorListDetailReportBy('',$creditor_reports[$i]['supplier_id'],'');
        $creditor_reports[$i]['paper_number'] = count($papers);
        for($ii = 0; $ii < count($papers); $ii++){
            $creditor_reports[$i]['balance'] += $papers[$ii]['invoice_supplier_balance'];

            $val = explode("-",$date_end); 
            $current_date_str = $val[2]."-".$val[1]."-".$val[0];

            $val = explode("-",$papers[$ii]['invoice_supplier_due']); 
            $due_date_str = $val[2]."-".$val[1]."-".$val[0];

            $current_date = strtotime($current_date_str);
            $due_date = strtotime($due_date_str);

            $datediff =  $due_date - $current_date;

            $diff_day = round($datediff / (60 * 60 * 24)); 

            //echo $papers[$ii]['supplier_code'] . " ".$papers[$ii]['invoice_supplier_code']. " ". $current_date . " ". $papers[$ii]['invoice_supplier_due']." ".$diff_day."<br><br>";

            if($diff_day > 60){
                $creditor_reports[$i]['due_comming_more_than_60'] += $papers[$ii]['invoice_supplier_balance'];
            }else if($diff_day > 30){
                $creditor_reports[$i]['due_comming_in_60'] +=  $papers[$ii]['invoice_supplier_balance'];
            }else if($diff_day > -1){
                $creditor_reports[$i]['due_comming_in_30'] += $papers[$ii]['invoice_supplier_balance'];
            }else if($diff_day > -31){
                $creditor_reports[$i]['over_due_1_to_30'] += $papers[$ii]['invoice_supplier_balance'];
            }else if($diff_day > -61){
                $creditor_reports[$i]['over_due_31_to_60'] += $papers[$ii]['invoice_supplier_balance'];
            }else if($diff_day > -91){
                $creditor_reports[$i]['over_due_61_to_90'] += $papers[$ii]['invoice_supplier_balance'];
            }else{
                $creditor_reports[$i]['over_due_more_than_90'] += $papers[$ii]['invoice_supplier_balance'];
            } 
        } 
    }

    $page_max = (int)(count($creditor_reports) / $lines);
    if(count($creditor_reports) % $lines > 0){
        $page_max += 1;
    }
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
    
    for($page_index=0 ; $page_index < $page_max ; $page_index++){

        $mpdf->AddPage('L');
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
    
    header("Content-Disposition: attachment; filename=Sale_vat $d1-$d2-$d3 $d4:$d5:$d6.xls");

    for($page_index=0 ; $page_index < $page_max ; $page_index++){
        echo $html[$page_index] ."<div> </div> <br>"; 
    }
}
?>