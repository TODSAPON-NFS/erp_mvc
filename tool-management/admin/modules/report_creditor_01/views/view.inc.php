<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var code_start = $("#code_start").val();
        var code_end = $("#code_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=report_creditor_01&date_start="+date_start+"&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
    }
    function print(type){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var code_start = $("#code_start").val();
        var code_end = $("#code_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.open("print.php?app=report_creditor_01&action="+type+"&date_start="+date_start+"&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&supplier_id="+supplier_id+"&keyword="+keyword,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานใบสั่งซื้อสินค้า</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>


<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                    รายงานใบสั่งซื้อสินค้า 
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>งวดใบรับสินค้า</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start;?>"  class="form-control calendar" readonly/>
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/>
                                </div>
                            </div>
                            <p class="help-block">01-01-2018 - 31-12-2018</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>หมายเลขใบรับสินค้า</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="code_start" name=code_start" value="<?PHP echo $code_start;?>"  class="form-control " />
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="code_end" name="code_end" value="<?PHP echo $code_end;?>"  class="form-control " />
                                </div>
                            </div>
                            <p class="help-block">01-01-2018 - 31-12-2018</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ผู้ขาย </label>
                            <select id="supplier_id" name="supplier_id" class="form-control select"  data-live-search="true">
                                <option value="">ทั้งหมด</option>

                                <?php 
                                for($i =  0 ; $i < count($suppliers) ; $i++){
                                ?>
                                <option <?php if($suppliers[$i]['supplier_id'] == $supplier_id){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> (<?php echo $suppliers[$i]['supplier_name_th'] ?>)</option>
                                <?
                                }
                                ?>

                            </select>
                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>คำค้น <font color="#F00"><b>*</b></font></label>
                            <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                            <p class="help-block">Example : T001.</p>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="print('pdf');">PDF</button>
                        <button class="btn btn-success" style="float:right; margin:0px 4px;" onclick="print('excel');">Excel</button>
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                        <a href="index.php?app=report_creditor_01" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th width="48"> ลำดับ <br>No.</th>
                            <th width="150">วันที่ <br>Date</th>
                            <th >ใบสั่งซื้อสินค้า <br>Purchase Order</th>
                            <th >รหัสผู้ขาย <br>Supplier Code</th>
                            <th >ผู้ขาย <br>Supplier</th>
                            <th >ใบรับสินค้า<br>Invoice</th> 
                            <th >ยอดเงิน<br>Total Price</th>
                            <th >ภาษีขาย<br>Vat.</th>
                            <th >ยอดเงินสุทธิ<br>Net Price</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        $vat_total = 0;
                        $net_total = 0;
                        for($i=0; $i < count($creditor_reports); $i++){
                            $total +=  $creditor_reports[$i]['purchase_order_total_price'];
                            $vat_total +=  $creditor_reports[$i]['purchase_order_vat_price'];
                            $net_total +=  $creditor_reports[$i]['purchase_order_net_price'];
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $creditor_reports[$i]['purchase_order_date']; ?></td>
                            <td><?php echo $creditor_reports[$i]['purchase_order_code']; ?></td>
                            <td><?php echo $creditor_reports[$i]['supplier_code']; ?></td>
                            <td><?php echo $creditor_reports[$i]['supplier_name_en']; ?> </td>
                            <td><?php echo $creditor_reports[$i]['invoice_supplier_code_gen']; ?></td>
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['purchase_order_total_price'],2); ?>
                            </td>
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['purchase_order_vat_price'],2); ?>
                            </td> 
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['purchase_order_net_price'],2); ?>
                            </td> 
                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" align="center"><b>รวม</b></td>
                            <td  align="right" ><b><?php echo number_format($total,2); ?></b></td>
                            <td  align="right" ><b><?php echo number_format($vat_total,2); ?></b></td>
                            <td  align="right" ><b><?php echo number_format($net_total,2); ?></b></td>
                        </tr>
                    </tfoot>
                </table>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            