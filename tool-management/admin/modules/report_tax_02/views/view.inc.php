<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=report_tax_02&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword;
    }
    function print(type){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var keyword = $("#keyword").val();

        window.open("print.php?app=report_tax_02&action="+type+"&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานภาษีขาย</h1>
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
                        รายงานภาษีขาย 
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>งวดภาษีขาย</label>
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
                            <label>ลูกค้า </label>
                            <select id="customer_id" name="customer_id" class="form-control select"  data-live-search="true">
                                <option value="">ทั้งหมด</option>

                                <?php 
                                for($i =  0 ; $i < count($customers) ; $i++){
                                ?>
                                <option <?php if($customers[$i]['customer_id'] == $customer_id){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> (<?php echo $customers[$i]['customer_name_th'] ?>)</option>
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
                        <a href="index.php?app=report_tax_02" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th width="48"> ลำดับ <br>No.</th>
                            <th width="150">วันที่ <br>Date</th>
                            <th width="150">หมายเลขใบรับสินค้า <br>Invoice Code.</th>
                            <th>ลูกค้า <br>Customer</th>
                            <th width="150" > ผู้ออก<br>Create by</th> 
                            <th>ยอดเงิน<br>Net Price.</th>
                            <th>ภาษีขาย<br>Vat.</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $vat_total = 0;
                        $net_total = 0;
                        for($i=0; $i < count($tax_reports); $i++){
                            $vat_total +=  $tax_reports[$i]['invoice_customer_vat_price'];
                            $net_total +=  $tax_reports[$i]['invoice_customer_total_price'];
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $tax_reports[$i]['invoice_customer_date']; ?></td>
                            <td><?php echo $tax_reports[$i]['invoice_customer_code']; ?></td>
                            <td><?php echo $tax_reports[$i]['invoice_customer_name']; ?> </td>
                            <td><?php echo $tax_reports[$i]['employee_name']; ?></td>
                            <td  align="right" >
                                <?php echo number_format($tax_reports[$i]['invoice_customer_total_price'],2); ?>
                            </td>
                            <td  align="right" ><?php echo number_format($tax_reports[$i]['invoice_customer_vat_price'],2); ?></td>
                            
                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" align="center">รวม</td>
                            <td  align="right" ><?php echo number_format($net_total,2); ?></td>
                            <td  align="right" ><?php echo number_format($vat_total,2); ?></td>
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
            
            
