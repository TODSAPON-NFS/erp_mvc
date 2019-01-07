<?PHP 

session_start();

require_once('../models/JournalReportModel.php'); 
require_once('../models/CompanyModel.php'); 

date_default_timezone_set('asia/bangkok');

$path = "print/report_account_03/views/";
 
$company_model = new CompanyModel;
$journal_report_model = new JournalReportModel;






if(!isset($_GET['date_start'])){
    $date_start = $_SESSION['date_start'];
}else{
    $date_start = $_GET['date_start'];
    $_SESSION['date_start'] = $date_start;
}


if(!isset($_GET['date_end'])){
    $date_end = $_SESSION['date_end'];
}else{
    $date_end = $_GET['date_end'];
    $_SESSION['date_end'] = $date_end;
}

if(!isset($_GET['code_start'])){
    $code_start = $_SESSION['code_start'];
}else{
    $code_start = $_GET['code_start'];
    $_SESSION['code_start'] = $code_start;
}


if(!isset($_GET['code_end'])){
    $code_end = $_SESSION['code_end'];
}else{
    $code_end = $_GET['code_end'];
    $_SESSION['code_end'] = $code_end;
}

if(!isset($_GET['keyword'])){
    $keyword = $_SESSION['keyword'];
}else{
    
    $keyword = $_GET['keyword']; 
    $_SESSION['keyword'] = $keyword;
} 


if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}
 
$type = $_GET['type'];




$company=$company_model->getCompanyByID('1');

$lines = 60;

$keyword = 1;
$journal_reports = $journal_report_model->getJournalAssetsReportBy($date_end,$code_start,$code_end, $keyword);

$keyword = 2;
$journal_reports_debit = $journal_report_model->getJournalAssetsReportBy($date_end,$code_start,$code_end, $keyword);

    $countAssets = 0 ; 

    for($i=0; $i < (int)(count($journal_reports)); $i++){ 
        if($journal_reports[$i]['account_level'] =='2' || $journal_reports[$i]['account_level'] =='3'){
            $countAssets++;
        }
    }
    $countDebit = 0;
    for($i=0; $i < count($journal_reports_debit); $i++){ 
        if($journal_reports_debit[$i]['account_level'] =='2' || $journal_reports_debit[$i]['account_level'] =='3'){
             $countAssets++;
        }
    }

    //echo  '<br>'.$countAssets;

 $page_max =(int) (   ($countAssets)  / $lines);
 


if( (int) ($countAssets)  % $lines > 0 )  { $page_max += 1;}
   


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
    

    for($page_index=0 ; $page_index < $page_max ; $page_index++){

        $mpdf->AddPage('P');
        $mpdf->mirrorMargins = true;
        
        $mpdf->SetDisplayMode('fullpage','two');
        

        ////$html = ob_get_contents();  
        ////ob_end_clean();

       $mpdf->WriteHTML($html[$page_index]);
        //echo $html[$page_index];
    }
    
    
    $mpdf->Output();

    //exit;
}else if ($_GET['action'] == "excel") {
    
    header("Content-type: application/vnd.ms-excel");
    //// header('Content-type: application/csv'); //*** CSV ***//
    
    header("Content-Disposition: attachment; filename=Financial_status$date_end.xls");

    for($page_index=0 ; $page_index < $page_max ; $page_index++){
        echo $html[$page_index] ."<div> </div> <br>"; 
    }
}
?>