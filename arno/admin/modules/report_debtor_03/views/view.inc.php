
<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=report_debtor_03&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword;
    }

    function print(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var keyword = $("#keyword").val();

        window.location = "print/report_debtor_03.php?date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword;
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานใบกำกับภาษี</h1>
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
                        รายงานใบกำกับภาษี
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>วันที่ออกใบกำกับภาษี</label>
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
                            <label>ผู้ซื้อ </label>
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
                        <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="print();">Print</button>
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                        <a href="index.php?app=report_debtor_03" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
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
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
