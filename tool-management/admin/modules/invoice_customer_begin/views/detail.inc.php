

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Invoice Customer Begin Management</h1>
    </div>
    <div class="col-lg-6" align="right">
       
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            แก้ไขใบกำกับภาษีลูกหนี้ยกยอดมา / Edit Invoice Customer Begin
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=invoice_customer&action=edit&id=<?php echo $invoice_customer_id;?>" >
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ซื้อ / Customer Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?PHP echo $invoice_customer['customer_code']; ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?PHP echo $invoice_customer['invoice_customer_name']; ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?PHP echo $invoice_customer['invoice_customer_address']; ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?PHP echo $invoice_customer['invoice_customer_tax']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1">
                        </div>
                        <div class="col-lg-5">
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกใบกำกับภาษี / Date</label>
                                        <p class="help-block"><?PHP echo $invoice_customer['invoice_customer_date']; ?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?PHP echo $invoice_customer['invoice_customer_code']; ?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        <p class="help-block"><?PHP echo $invoice_customer['invoice_customer_due']; ?></p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / term </label>
                                        <p class="help-block"><?PHP echo $invoice_customer['invoice_customer_term']; ?></p>
                                    </div>
                                </div>
                                

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>พนักงานขาย / Sale  <font color="#F00"><b>*</b></font> </label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_customer['user_name']; ?> <?PHP echo $invoice_customer['user_lastname']; ?></p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div> 

                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <tfoot>
                            
                            <tr class="odd gradeX">
                                <td colspan="3" rowspan="3">
                                    
                                </td>
                                <td width="300px" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td width="200px" style="text-align: right;">
                                <?PHP echo number_format($invoice_customer['invoice_customer_total'],2);?>
                                </td>
                                
                            </tr>
                            <tr class="odd gradeX">
                                <td width="300px" align="left" style="vertical-align: middle;">
                                    <table>
                                        <tr>
                                            <td>
                                                <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;">
                                            <?PHP echo number_format($invoice_customer['invoice_customer_vat'],2);?>
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td width="200px" style="text-align: right;">
                                <?PHP echo number_format($invoice_customer['invoice_customer_vat_price'],2);?>
                                </td>
                               
                            </tr>
                            <tr class="odd gradeX">
                                <td width="300px" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td width="200px" style="text-align: right;">
                                <?PHP echo number_format($invoice_customer['invoice_customer_net_price'],2);?>
                                </td>
                                
                            </tr>
                        </tfoot>
                    </table>   
                

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=summit_dedit" class="btn btn-default">Back</a>
                            <a href="index.php?app=summit_dedit&action=print&id=<?PHP echo $invoice_customer_id?>" class="btn btn-danger">Print</a>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>