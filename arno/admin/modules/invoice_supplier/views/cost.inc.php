
<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Invoice Supplier Management</h1>
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
            รายละเอียดใบกำกับภาษีรับเข้า / Invoice Supplier Detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=invoice_supplier&action=edit&id=<?php echo $invoice_supplier_id;?>" >
                    <input type="hidden"  id="invoice_supplier_id" name="invoice_supplier_id" value="<?php echo $invoice_supplier_id; ?>" />
                    <input type="hidden"  id="invoice_supplier_date" name="invoice_supplier_date" value="<?php echo $invoice_supplier['invoice_supplier_date']; ?>" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><? echo $invoice_supplier['supplier_code'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['supplier_name_en'] ?> (<?php echo $invoice_supplier['supplier_name_th'] ?>)</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['supplier_address_1'] ."\n". $invoice_supplier['supplier_address_2'] ."\n". $invoice_supplier['supplier_address_3'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['supplier_tax'];?></p>
                                    </div>
                                </div>
                            <?PHP if($invoice_supplier['supplier_domestic'] == "ภายนอกประเทศ"){ ?>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Exchange rate Baht<font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['exchange_rate_baht'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Import duty<font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['import_duty'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Freight in<font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['freight_in'];?></p>
                                    </div>
                                </div>
                            <?PHP } ?>
                            </div>
                        </div>
                        <div class="col-lg-1">
                        </div>
                        <div class="col-lg-5">
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกใบกำกับภาษี / Date</label>
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_date'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_code'];?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_due'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / term </label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_term'];?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่รับใบกำกับภาษี / Date recieve</label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_date_recieve'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขรับใบกำกับภาษี / recieve code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_code_gen'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้รับใบกำกับภาษี / Employee  <font color="#F00"><b>*</b></font> </label>
                                       
                                        <p class="help-block"><?PHP echo $invoice_supplier['user_name'];?> <?PHP echo $invoice_supplier['user_lastname'];?> (<?PHP echo $invoice_supplier['user_position_name'];?>)</p>
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
                                <th style="text-align:center;">ลำดับ <br> (์No.)</th>
                                <th style="text-align:center;" width="200" >รหัสสินค้า <br> (Product Code)</th>
                                <th style="text-align:center;" width="150">จำนวน <br> (Qty)</th>
                                <th style="text-align:center;" width="150">ราคาต่อหน่วย <br> (Unit price) </th>
                                <th style="text-align:center;" width="150">ราคาต่อหน่วย (บาท) <br> (Unit price baht) </th>
                                <th style="text-align:center;" width="150">จำนวนเงิน <br> (Amount)</th>
                                <th style="text-align:center;" width="150">จำนวนเงิน (บาท) <br> (Amount baht)</th>
                                <th style="text-align:center;" width="150">ภาษีนำเข้า (บาท) <br> (Import duty)</th>
                                <th style="text-align:center;" width="150">ค่าจัดส่ง (บาท) <br> (Freight in)</th>
                                <th style="text-align:center;" width="150">ราคารวมสุทธิ (บาท) <br> (Total)</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                            $total = 0;
                            $cost_duty = 0;
                            $cost_price_total_s = 0;
                            $cost_price_ex_total_s = 0;
                            for($i=0; $i < count($invoice_supplier_lists); $i++){
                                $cost_qty = $invoice_supplier_lists[$i]['invoice_supplier_list_qty'];
                                $cost_price = $invoice_supplier_lists[$i]['invoice_supplier_list_price'] ;
                                $cost_duty += $cost_price * $cost_price;
                            }

                            for($i=0; $i < count($invoice_supplier_lists); $i++){
                                $cost_qty = $invoice_supplier_lists[$i]['invoice_supplier_list_qty'];
                                $cost_price = $invoice_supplier_lists[$i]['invoice_supplier_list_price'] ;
                                $cost_price_ex = $invoice_supplier_lists[$i]['invoice_supplier_list_price'] * $invoice_supplier['exchange_rate_baht'];
                                $cost_price_total = $cost_qty * $cost_price;
                                $cost_price_ex_total = $cost_qty * $cost_price_ex;
                                $cost_price_duty = $cost_price_total / $cost_duty * $invoice_supplier['import_duty'];
                                $cost_price_f = $cost_price_total / $cost_duty * $invoice_supplier['freight_in'];
                                $cost_total = $cost_price_f + $cost_price_duty + $cost_price_ex_total;
                            ?>
                            <tr class="odd gradeX">
                                <td align="center">
                                    <?php echo $i+1; ?>.
                                </td>
                                
                                <td>
                                    <b><?php echo $invoice_supplier_lists[$i]['product_code']; ?></b><br>
                                    <?php echo $invoice_supplier_lists[$i]['product_name']; ?><br>
                                    <span>Sub name : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_product_name']; ?><br>
                                    <span>Detail : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_product_detail']; ?><br>
                                    <span>Remark : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_remark']; ?><br>
                                </td>
                                <td align="right"><?php echo  number_format($cost_qty,2); ?></td>
                                <td align="right"><?php echo  number_format($cost_price,2); ?></td>
                                <td align="right"><?php echo  number_format($cost_price_ex,2); ?></td>
                                <td align="right"><?php echo  number_format($cost_price_total,2); ?></td>
                                <td align="right"><?php echo  number_format($cost_price_ex_total,2); ?></td>
                                <td align="right"><?php echo  number_format($cost_price_duty,2); ?></td>
                                <td align="right"><?php echo  number_format($cost_price_f,2); ?></td>
                                <td align="right"><?php echo  number_format($cost_total,2); ?></td>
                            </tr>
                            <?
                                $total += $cost_total;
                                $cost_price_total_s += $cost_price_total;
                                $cost_price_ex_total_s += $cost_price_ex_total;
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="3" rowspan="3">
                                    
                                </td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format($cost_price_total_s,2) ;?>
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format($cost_price_ex_total_s,2) ;?>
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format($invoice_supplier['import_duty'],2) ;?>
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format($invoice_supplier['freight_in'],2) ;?>
                                </td>
                                <td style="text-align: right;">
                                <?PHP
                                    if($invoice_supplier['vat_type'] == 1){
                                        $total_val = $total - (($invoice_supplier['vat']/( 100 + $invoice_supplier['vat'] )) * $total);
                                    } else if($invoice_supplier['vat_type'] == 2){
                                        $total_val = $total;
                                    } else {
                                        $total_val = $total;
                                    }
                                ?>
                                    <?PHP echo number_format($total_val,2) ;?>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <table>
                                        <tr>
                                            <td>
                                                <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;text-align: right;">
                                                <?PHP echo $invoice_supplier['vat'];?>
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                    <?PHP 
                                    if($invoice_supplier['vat_type'] == 1){
                                        $vat_val = ($invoice_supplier['vat']/( 100 + $invoice_supplier['vat'] )) * $total;
                                    } else if($invoice_supplier['vat_type'] == 2){
                                        $vat_val = ($invoice_supplier['vat']/100) * $total;
                                    } else {
                                        $vat_val = 0.0;
                                    }
                                    ?>
                                    <?PHP echo number_format($vat_val,2) ;?>
                                </td>

                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                    <?PHP 
                                    if($invoice_supplier['vat_type'] == 1){
                                        $net_val =  $total;
                                    } else if($invoice_supplier['vat_type'] == 2){
                                        $net_val = ($invoice_supplier['vat']/100) * $total + $total;
                                    } else {
                                        $net_val = $total;
                                    }
                                    ?>
                                   <?PHP echo number_format($net_val,2) ;?>
                                </td>

                            </tr>
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=invoice_supplier" class="btn btn-default">Back</a>
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