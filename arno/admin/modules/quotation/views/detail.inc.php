

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Quotation Management</h1>
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
               รายละเอียดใบเสนอราคาสินค้า / Quotation Detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสผู้ซื้อ / Customer Code </label>
                                        <p class="help-block"><? echo $quotation['customer_code'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax</label>
                                        <p class="help-block"><?php echo $quotation['customer_tax'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ชื่อตามใบเสนอราคา / Full name</label>
                                        <p class="help-block"><?php echo $quotation['customer_name_en'];?> (<?php echo $quotation['customer_name_th'];?>)</p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address</label>
                                        <p class="help-block"><?php echo $quotation['customer_address_1'] ."\n". $quotation['customer_address_2'] ."\n". $quotation['customer_address_3'];?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ชื่อผู้ติดต่อ / Contact name</label>
                                        <p class="help-block"><?php echo $quotation['quotation_contact_name'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>เบอร์โทรผู้ติดต่อ / Contact telephone</label>
                                        <p class="help-block"><?php echo $quotation['quotation_contact_tel'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>อีเมล์ผู้ติดต่อ / Contact email</label>
                                        <p class="help-block"><?php echo $quotation['quotation_contact_email'];?></p>
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
                                        <label>วันที่ออกใบเสนอราคา / Quotation Date</label>
                                        <p class="help-block"><?PHP echo $quotation['quotation_date'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบเสนอราคา / Quotation code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block">
                                            <?php echo $quotation['quotation_code'];?>
                                            <?php if($quotation['quotation_cancelled'] != 0){ ?>
                                                <span><font color="#F00">(Cancelled)</font></span>
                                            <?PHP } ?>
                                        </p>
                                    </div>
                                </div>                              

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>พนักงานขาย / Sale  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $quotation['user_name'];?>  <?php echo $quotation['user_lastname'];?> (<?php echo $quotation['user_position_name'] ?>)</p>
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
                                <th style="text-align:center;max-width:32px;">ลำดับ <br>(์No.)</th>
                                <th style="text-align:center;">รหัสสินค้า <br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า/หมายเหตุ<br>(Product Name/Remark)</th>
                                <th style="text-align:center;max-width:100px;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;max-width:100px;">ราคาต่อชิ้น<br>(Price)</th>
                                <th style="text-align:center;">ราคารวม<br>(Total price)</th>
                                <th style="text-align:center;" colspan="2">ส่วนลด<br>(Discount)</th>
                                <th style="text-align:center;">ราคาสุทธิ<br>(Net price)</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($quotation_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td align="center">
                                    <?php echo $i+1; ?>
                                </td>
                                <td>
                                    <?php echo $quotation_lists[$i]['product_code']; ?>
                                </td>
                                <td>
                                    <div><?php echo $quotation_lists[$i]['product_name']; ?></div>
                                    <div>หมายเหตุ.</div>
                                    <?php echo $quotation_lists[$i]['quotation_list_remark']; ?>
                                </td>
                                <td  style="max-width:100px;text-align:right;"><?php echo number_format($quotation_lists[$i]['quotation_list_qty'],2); ?></td>
                                <td  style="max-width:100px;text-align:right;"><?php echo number_format($quotation_lists[$i]['quotation_list_price'],2); ?></td>
                                <td  style="max-width:120px;text-align:right;"><?php echo number_format($quotation_lists[$i]['quotation_list_sum'],2);  ?></td>
                                <td width="100px" align="right"><?php echo number_format($quotation_lists[$i]['quotation_list_discount'],2); ?></td>
                                <td width="80px" align="center">
                                    <?PHP if($quotation_lists[$i]['quotation_list_discount_type'] == 0){?> % <?PHP } else{?> - <?PHP } ?>
                                </td>
                                <td  style="max-width:120px;text-align:right;"><?php echo number_format($quotation_lists[$i]['quotation_list_total'],2); ?></td>
                            </tr>
                            <?
                                $total += $quotation_lists[$i]['quotation_list_total'];
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="5" rowspan="3">
                                    <b>หมายเหตุ</b>
                                    <p class="help-block"><?php echo $quotation['quotation_remark'];?></p>
                                </td>
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td align="right">
                                    <?PHP echo number_format($total,2) ;?>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <table>
                                        <tr>
                                            <td>
                                                <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;">
                                                <?PHP echo number_format($vat,2);?>
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td align="right">
                                    <?PHP echo number_format(($vat/100) * $total,2) ;?>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td align="right">
                                    <?PHP echo number_format(($vat/100) * $total + $total,2) ;?>
                                </td>
                             
                            </tr>
                        </tfoot>
                    </table>

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-12" align="right">
                            <a href="index.php?app=quotation" class="btn btn-default">Back</a>
                        </div>
                    </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>