<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Credit Note Management</h1>
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
            แก้ไขใบลดหนี้ / Edit Credit Note  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ซื้อ / Customer Code <font color="#F00"><b>*</b></font></label> 
                                        <p class="help-block"><? echo $credit_note['customer_code'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อตามใบลดหนี้ / Full name <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $credit_note['credit_note_name'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $credit_note['credit_note_address']; ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $credit_note['customer_tax'];?></p>
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
                                        <label>วันที่ออกใบลดหนี้ / Date</label>
                                        <p class="help-block"><?php echo $credit_note['credit_note_date'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>เลขที่ใบลดหนี้ / CN code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $credit_note['credit_note_code'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>อ้างอิงใบกำกับภาษีหมายเลข / Inv Code  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $credit_note['invoice_customer_code'];?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        <p class="help-block"><?php echo $credit_note['credit_note_due'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / Term </label>
                                        <p class="help-block"><?php echo $credit_note['credit_note_term'];?></p>
                                    </div>
                                </div>
                                

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ออกใบลดหนี้ / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $credit_note['user_name'];?> <?php echo $credit_note['user_lastname'];?> (<?php echo $credit_note['user_position_name'];?>)</p>
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
                                <th style="text-align:center;">รหัสสินค้า <br> (Product Code)</th>
                                <th style="text-align:center;">รายละเอียดสินค้า <br> (Product Detail)</th>
                                <th style="text-align:center;" width="150">จำนวน <br> (Qty)</th>
                                <th style="text-align:center;" width="150">ราคาต่อหน่วย <br> (Unit price) </th>
                                <th style="text-align:center;" width="150">จำนวนเงิน <br> (Amount)</th>
                                <th width="24"></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($credit_note_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <?PHP echo  $credit_note_lists[$i]['product_code'];?>
                                </td>
                                <td>
                                    <span>Product name : </span><?php echo $credit_note_lists[$i]['credit_note_list_product_name']; ?><br>
                                    <span>Product detail : </span><?php echo $credit_note_lists[$i]['credit_note_list_product_detail']; ?><br>
                                    <span>Remark : </span><?php echo $credit_note_lists[$i]['credit_note_list_remark']; ?><br>
                                </td>
                                <td align="right"><?php echo $credit_note_lists[$i]['credit_note_list_qty']; ?></td>
                                <td align="right"><?php echo  number_format($credit_note_lists[$i]['credit_note_list_price'],2); ?></td>
                                <td align="right"><?php echo  number_format($credit_note_lists[$i]['credit_note_list_qty'] * $credit_note_lists[$i]['credit_note_list_price'],2); ?></td>
                                
                            </tr>
                            <?
                                $total += $credit_note_lists[$i]['credit_note_list_qty'] * $credit_note_lists[$i]['credit_note_list_price'];
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            
                            <tr class="odd gradeX">
                                <td colspan="2" rowspan="5">
                                    <b>Remark</b>
                                    <p><?php echo $credit_note['credit_note_remark'];?></p>
                                </td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>มูลค่าใบกำกับเดิม / Old total</span>
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format($credit_note['credit_note_total_old'],2) ;?>
                                </td>
                            </tr>

                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>มูลค่าที่ถูกต้อง / Total</span>
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format($credit_note['credit_note_total_old'] - $total ,2) ;?>
                                </td>
                            </tr>

                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>ผลต่าง / Sub total</span>
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format($total,2) ;?>
                                </td>
                            </tr>

                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <table>
                                        <tr>
                                            <td>
                                                <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;">
                                                <?PHP echo $vat;?>
                                            </td>
                                            <td>
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format(($vat/100) * $total,2) ;?>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format(($vat/100) * $total + $total,2) ;?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=credit_note" class="btn btn-default">Back</a>
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