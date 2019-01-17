

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
            
            <div class="col-md-6">
            รายละเอียดใบกำกับภาษีรับเข้า / Invoice Supplier Detail 
                </div>
                <div class="col-md-6" align="right">
                    <?PHP if($previous_id != ""){?>
                    <a class="btn btn-primary" href="?app=invoice_supplier&action=detail&id=<?php echo $previous_id;?>" > <i class="fa fa-angle-double-left" aria-hidden="true"></i> <?php echo $previous_code;?> </a>
                    <?PHP } ?>

                    <a class="btn btn-success "  href="?app=invoice_supplier&action=insert&sort=<?php echo $sort;?>" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    
                    <?PHP if ($sort == "ภายนอกประเทศ") { ?>

                        <a class="btn btn-danger" href="print.php?app=invoice_supplier_abroad&action=pdf&type=credit&sort=<?php echo $sort;?>&id=<?php echo $invoice_supplier_id;?>" target="_blank" > <i class="fa fa-print" aria-hidden="true"></i> พิมพ์ใบตั้งเจ้าหนี้ต่างประเทศ </a>
                    
                        <a class="btn btn-danger" href="print.php?app=invoice_supplier_abroad&action=pdf&type=receive&sort=<?php echo $sort;?>&id=<?php echo $invoice_supplier_id;?>" target="_blank" > <i class="fa fa-print" aria-hidden="true"></i> พิมพ์ใบรับสินค้า </a>
                    
                    <?PHP } else { ?>

                    <a class="btn btn-danger" href="print.php?app=invoice_supplier&action=pdf&lan=th&sort=<?php echo $sort;?>&id=<?php echo $invoice_supplier_id;?>" target="_blank" > <i class="fa fa-print" aria-hidden="true"></i> พิมพ์ใบรับสินค้า </a>
                    
                    <?PHP } ?>

                    <?PHP if($next_id != ""){?>
                    <a class="btn btn-primary" href="?app=invoice_supplier&action=detail&sort=<?php echo $sort;?>&id=<?php echo $next_id;?>" >  <?php echo $next_code;?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> </a>
                    <?PHP } ?>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                
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
                                        <p class="help-block"><?php echo $invoice_supplier['supplier_name_en'] ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $supplier['supplier_address_1'] ."\n". $invoice_supplier['supplier_address_2'] ."\n". $invoice_supplier['supplier_address_3'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['supplier_tax'];?></p>
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
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_date'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_code'];?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>เครดิต / Credit Day </label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_due_day'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
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

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>รายการใบสั่งซื้อที่เกี่ยวข้อง </label>
                                        <table width="100%" class="table table-striped table-bordered table-hover" >
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center;">ลำดับ </th>
                                                    <th style="text-align:center;">หมายเลขใบสั่งซื้อ </th>
                                                    <th style="text-align:center;">สถานะ </th>
                                                   
                                                </tr>
                                            </thead>

                                            <tbody>
                                            <?php 
                                                        for($i=0; $i < count($purchase_orders); $i++){
                                                        ?>
                                                        <tr class="odd gradeX">
                                                        <td align="center">
                                                            <?php echo $i+1; ?>.
                                                        </td>
                                                        <td align="center">
                                                            <a target="_blank" href="?app=purchase_order&action=detail&id=<?php echo $purchase_orders[$i]['purchase_order_id']; ?>"><?php echo $purchase_orders[$i]['purchase_order_code']; ?></a>
                                                        </td>
                                                        <td align="center"> 
                                                        <?php 
                                                            if($invoice_supplier_model->checkPurchaseOrder($purchase_orders[$i]['purchase_order_id'])>0){ 
                                                                echo " <b class='text-danger'>ยังไม่ครบ</b> ";
                                                            }else{
                                                                echo "<b  class='text-success'>ครบ</b>";    
                                                            }
                                                            ?>

                                                        </td>   
                                                        </tr>
                                                        <?php  } ?>
                                            
                                            </tbody>
                                        </table>
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
                                <th style="text-align:center;">ลำดับ </th>
                                <th style="text-align:center;">รหัสสินค้า </th>
                                <th style="text-align:center;">รายละเอียดสินค้า </th>
                                <th style="text-align:center;" width="150">คลังสินค้า </th>
                                <th style="text-align:center;" width="150">จำนวน </th>
                                <th style="text-align:center;" width="150">ราคาต่อหน่วย </th>
                                <th style="text-align:center;" width="150">จำนวนเงิน </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($invoice_supplier_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td align="center">
                                    <?php echo $i+1; ?>.
                                </td>
                                
                                <td>
                                    <?php echo $invoice_supplier_lists[$i]['product_code']; ?>
                                </td>

                                <td>
                                    <b><?php echo $invoice_supplier_lists[$i]['product_name']; ?></b><br>
                                    <span>Sub name : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_product_name']; ?><br>
                                    <span>Detail : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_product_detail']; ?><br>
                                    <span>Remark : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_remark']; ?><br>
                                </td>

                                <td align="left"><?php echo $invoice_supplier_lists[$i]['stock_group_name']; ?></td>
                                <td align="right"><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_qty']; ?></td>
                                <td align="right"><?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_price'],2); ?></td>
                                <td align="right"><?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_qty'] * $invoice_supplier_lists[$i]['invoice_supplier_list_price'],2); ?></td>
                                
                            </tr>
                            <?
                                $total += $invoice_supplier_lists[$i]['invoice_supplier_list_qty'] * $invoice_supplier_lists[$i]['invoice_supplier_list_price'];
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="4" rowspan="3">
                                    
                                </td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
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