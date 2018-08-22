<?
session_start();
require_once('../../models/InvoiceCustomerModel.php');
date_default_timezone_set('asia/bangkok');
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("h");
$d5=date("i");
$d6=date("s");
				
header("Content-type: application/vnd.ms-excel");
// header('Content-type: application/csv'); //*** CSV ***//

header("Content-Disposition: attachment; filename=customer-invoice-report_$d1-$d2-$d3 $d4:$d5:$d6.xls");

$path = "modules/report_debtor_06/views/";

$invoice_customer_model = new InvoiceCustomerModel;



$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];
$customer_id = $_GET['customer_id'];
$keyword = $_GET['keyword'];


$invoice_customers = $invoice_customer_model->getInvoiceCustomerBy($date_start,$date_end,$customer_id,$keyword);


if($date_start == ""){$date_start = '-';}
if($date_end == ""){$date_end = '-';}
?>
<style>
th, td {
    padding: 8px 8px;
}
</style>
<div align="center" style="padding:8px;">
<h2 >รายงานใบกำกับภาษีตั้งแต่วันที่ <?PHP echo $date_start; ?> ถึง <?PHP echo $date_end; ?></h2>
</div>
<br>
    <table width="100%" border="1" style="border-collapse: collapse;" >
        <thead>
            <tr>
                <th width="48">ลำดับ <BR> No.</th>
                <th width="150">วันที่ออกใบกำกับภาษี <BR> Invoice Date</th>
                <th width="150">เลยใบกำกับภาษี <br> Invoice Code.</th>
                <th> ชื่อลูกค้า <br> Customer</th>
                <th >ผู้ออกใบกำกับภาษี <br> Employee</th>
                <th  >จำนวนเงิน <br> Total</th>
                <th  >ภาษี <br> Vat</th>
                <th  >มูลค่าภาษี <br> Vat Price</th>
                <th  >จำนวนเงินสุทธิ <br> Net Price</th>
                <th>หมายเหตุ <br> Remark</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            for($i=0; $i < count($invoice_customers); $i++){
            ?>
            <tr class="odd gradeX">
                <td><?php echo $i+1; ?></td>
                <td><?php echo $invoice_customers[$i]['invoice_customer_date']; ?></td>
                <td><?php echo $invoice_customers[$i]['invoice_customer_code']; ?></td>
                <td><?php echo $invoice_customers[$i]['customer_name']; ?> </td>
                <td><?php echo $invoice_customers[$i]['employee_name']; ?></td>
                <td align="right"><?php echo number_format($invoice_customers[$i]['invoice_customer_total_price'],2); ?> </td>
                <td align="right"><?php echo number_format($invoice_customers[$i]['invoice_customer_vat'],2); ?>% </td>
                <td align="right"><?php echo number_format($invoice_customers[$i]['invoice_customer_vat_price'],2); ?> </td>
                <td align="right"><?php echo number_format($invoice_customers[$i]['invoice_customer_net_price'],2); ?> </td>
                
                <td><?php echo $invoice_customers[$i]['invoice_customer_remark']; ?></td>
            </tr>
            <?
            }
            ?>
        </tbody>
    </table>
                    
            
            
