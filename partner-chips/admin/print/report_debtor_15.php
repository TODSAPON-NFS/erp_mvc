<?
session_start();
require_once('../../models/QuotationModel.php');
date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s");
				
header("Content-type: application/vnd.ms-excel");
// header('Content-type: application/csv'); //*** CSV ***//

header("Content-Disposition: attachment; filename=quotation-report_$d1-$d2-$d3 $d4:$d5:$d6.xls");

$path = "modules/report_debtor_15/views/";
$quotation_model = new QuotationModel;


$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];
$customer_id = $_GET['customer_id'];
$user_id = $_GET['user_id'];
$keyword = $_GET['keyword'];

$quotations = $quotation_model->getQuotationBy($date_start,$date_end,$customer_id,$keyword,$user_id);

if($date_start == ""){$date_start = '-';}
if($date_end == ""){$date_end = '-';}
?>
<style>
th, td {
    padding: 8px 8px;
}
</style>
<div align="center" style="padding:8px;">
<h2 >รายงานใบเสนอราคาตั้งแต่วันที่ <?PHP echo $date_start; ?> ถึง <?PHP echo $date_end; ?></h2>
</div>
<br>
    <table width="100%" border="1" style="border-collapse: collapse;" >
        <thead>
            <tr>
                <th>ลำดับ<br>No.</th>
                <th>วันที่ออกใบเสนอราคา<br>Quotation Date</th>
                <th>หมายเลขใบเสนอราคา<br>Quotation No.</th>
                <th>ลูกค้า<br>Customer.</th>
                <th>ยอดเงิน<br>Net Price.</th>
                <th>ผู้ติดต่อ<br>Contact.</th>
                <th>ผู้ออกใบเสนอราคา<br>Create by.</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            for($i=0; $i < count($quotations); $i++){
            ?>
            <tr class="odd gradeX">
                <td><?php echo $i+1; ?></td>
                <td align="center" ><?php echo $quotations[$i]['quotation_date']; ?></td>
                <td  align="left" >
                    <?php echo $quotations[$i]['quotation_code']; ?>
                    <?php if($quotations[$i]['quotation_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $quotations[$i]['quotation_rewrite_no']; ?></font></b> <?PHP } ?> <?php if($quotations[$i]['quotation_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?>
                </td>
                <td><?php echo $quotations[$i]['customer_name']; ?></td>
                <td  align="right" >
                    <?php echo number_format($quotations[$i]['quotation_total'],2); ?>
                </td>
                <td><?php echo $quotations[$i]['quotation_contact_name']; ?></td>
                <td><?php echo $quotations[$i]['employee_name']; ?></td>
            </tr>
            <?
            }
            ?>
        </tbody>
    </table>
                    
            
            
