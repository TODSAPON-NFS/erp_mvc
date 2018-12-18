<?
session_start();
require_once('../../models/BillingNoteModel.php');
date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s");
				
header("Content-type: application/vnd.ms-excel");
// header('Content-type: application/csv'); //*** CSV ***//

header("Content-Disposition: attachment; filename=billing-note-report_$d1-$d2-$d3 $d4:$d5:$d6.xls");

$path = "modules/report_debtor_06/views/";

$billing_note_model = new BillingNoteModel;



$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];
$customer_id = $_GET['customer_id'];
$keyword = $_GET['keyword'];


$billing_notes = $billing_note_model->getBillingNoteBy($date_start,$date_end,$customer_id,$keyword);


if($date_start == ""){$date_start = '-';}
if($date_end == ""){$date_end = '-';}
?>
<style>
th, td {
    padding: 8px 8px;
}
</style>
<div align="center" style="padding:8px;">
<h2 >รายงานใบวางบิลตั้งแต่วันที่ <?PHP echo $date_start; ?> ถึง <?PHP echo $date_end; ?></h2>
</div>
<br>
    <table width="100%" border="1" style="border-collapse: collapse;" >
        <thead>
            <tr>
                <th width="48"> ลำดับ <br>No.</th>
                <th width="150">วันที่ออกใบวางบิล <br>Billing Note Date</th>
                <th width="150">หมายเลขใบวางบิล <br>Billing Note Code.</th>
                <th>ลูกค้า <br>Customer</th>
                <th>ยอดเงิน<br>Net Price.</th>
                <th width="150" > ผู้ออก<br>Create by</th>
                <th>หมายเหตุ <br>Remark</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            for($i=0; $i < count($billing_notes); $i++){
            ?>
            <tr class="odd gradeX">
                <td><?php echo $i+1; ?></td>
                <td><?php echo $billing_notes[$i]['billing_note_date']; ?></td>
                <td><?php echo $billing_notes[$i]['billing_note_code']; ?></td>
                <td><?php echo $billing_notes[$i]['customer_name']; ?> </td>
                <td  align="right" >
                    <?php echo number_format($billing_notes[$i]['billing_note_total'],2); ?>
                </td>
                <td><?php echo $billing_notes[$i]['employee_name']; ?></td>
                <td><?php echo $billing_notes[$i]['billing_note_remark']; ?></td>
            </tr>
            <?
            }
            ?>
        </tbody>
    </table>
                    
            
            
