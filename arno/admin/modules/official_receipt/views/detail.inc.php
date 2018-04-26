<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Official Receipt Management</h1>
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
            แก้ไขใบเสร็จ / Edit Official Receipt  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ซื้อ / Customer Code <font color="#F00"><b>*</b></font></label> 
                                        <p class="help-block"><? echo $official_receipt['customer_code'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อตามใบเสร็จ / Full name <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $official_receipt['official_receipt_name'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $official_receipt['official_receipt_address']; ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $official_receipt['customer_tax'];?></p>
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
                                        <label>วันที่ออกใบเสร็จ / Date</label>
                                        <p class="help-block"><?php echo $official_receipt['official_receipt_date'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบเสร็จ / BN code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $official_receipt['official_receipt_code'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ออกใบเสร็จ / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $official_receipt['user_name'];?> <?php echo $official_receipt['user_lastname'];?> (<?php echo $official_receipt['user_position_name'];?>)</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div> 

                     <div>
                    Our reference :
                    </div>
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">ลำดับ <br> (์NO)</th>
                                <th style="text-align:center;">หมายใบกำกับภาษี <br> (Invoice Number)</th>
                                <th style="text-align:center;">วันที่ออก <br> (Date)</th>
                                <th style="text-align:center;" width="150">กำหนดชำระ <br> (Due Date)</th>
                                <th style="text-align:center;" width="150">จำนวนเงิน <br> (Amount) </th>
                                <th style="text-align:center;" width="150">ชำระแล้ว <br> (Paid)</th>
                                <th style="text-align:center;" width="150">ยอดชำระคงเหลือ <br> (Balance)</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($official_receipt_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td align="center">
                                    <?PHP echo $i+1;?>
                                </td>
                                <td align="center">
                                    <?PHP echo  $official_receipt_lists[$i]['invoice_customer_code'];?>
                                </td>
                                <td align="center">
                                    <?PHP echo  $official_receipt_lists[$i]['official_receipt_list_date'];?>
                                </td>
                                <td align="center">
                                    <?PHP echo  $official_receipt_lists[$i]['official_receipt_list_due'];?>
                                </td>
                                <td align="right">
                                    <?PHP echo  number_format($official_receipt_lists[$i]['official_receipt_list_net'],2);?>
                                </td>
                                <td align="right">
                                    <?PHP echo  number_format($official_receipt_lists[$i]['official_receipt_list_paid'],2);?>
                                </td>
                                <td align="right">
                                    <?PHP echo  number_format($official_receipt_lists[$i]['official_receipt_list_net'] - $official_receipt_lists[$i]['official_receipt_list_paid'],2);?>
                                </td>
                            </tr>
                            <?
                                $total += $official_receipt_lists[$i]['official_receipt_list_net'] - $official_receipt_lists[$i]['official_receipt_list_paid'];
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            
                        <tr class="odd gradeX">
                                <td colspan="4"></td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td  align="right">
                                    <?PHP echo number_format($total,2) ;?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=official_receipt" class="btn btn-default">Back</a>
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