<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var code_start = $("#code_start").val();
        var code_end = $("#code_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=report_creditor_02&date_start="+date_start+"&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
    }
    function print(type){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var code_start = $("#code_start").val();
        var code_end = $("#code_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.open("print.php?app=report_creditor_02&action="+type+"&date_start="+date_start+"&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&supplier_id="+supplier_id+"&keyword="+keyword,'_blank');
    }

    function checkAll(id)
    {
        var checkbox = document.getElementById('check_all'); 
        if (checkbox.checked == true){
            $('#tb_report').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', true);
        }else{
            $('#tb_report').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', false);
        }
    }

    function check(){
        
        var supplier_replace_id = document.getElementById("supplier_replace_id").value; 
        
        supplier_replace_id = $.trim(supplier_replace_id); 

        if(supplier_replace_id.length == 0){
            alert("Please select supplier for replace");
            document.getElementById("supplier_replace_id").focus();
            return false;
        }else{
            var date_start = $("#date_start").val();
            var date_end = $("#date_end").val();
            var supplier_id = $("#supplier_id").val();
            var keyword = $("#keyword").val();

            $('#form_report').prop('action',"index.php?app=report_creditor_02&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword);
            return true;
        }
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานใบรับสินค้า</h1>
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
                        รายงานใบรับสินค้า 
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
                        <a href="index.php?app=report_creditor_02" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>
                <form id="form_report" role="form" method="post" onsubmit="return check();" action="?app=report_creditor_02">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="tb_report" >
                        <thead>
                            <tr> 
                                <th align="center">เลขที่ </th>
                                <th align="center">วันที่ </th>
                                <th align="center">ผู้จำหน่าย </th> 
                                <th align="center">เลขที่บิล</th>
                                <th align="center">มูลค่าสินค้า</th> 
                                <th align="center">VAT.</th>
                                <th align="center">รวมทั้งสิ้น</th> 
                                <th align="center">ครบกำหนด</th> 
                                <th align="center">ใบสั่งซื้อ</th> 
                                <th align="center">จ่ายเงิน</th> 
                            </tr> 
                        </thead>
                        <tbody>
                            <?php  
                            $invoice_supplier_total_price = 0;
                            $invoice_supplier_vat_price = 0;
                            $invoice_supplier_net_price = 0;
                            $payment = 0;
                            for($i=0; $i < count($tax_reports); $i++){ 
                                $invoice_supplier_total_price += $tax_reports[$i]['invoice_supplier_total_price'];
                                $invoice_supplier_vat_price += $tax_reports[$i]['invoice_supplier_vat_price'];
                                $invoice_supplier_net_price += $tax_reports[$i]['invoice_supplier_net_price'];
                                $payment += $tax_reports[$i]['payment'];
                            ?>
                            <tr class="odd gradeX"> 
                                <td align="center"><?php echo $tax_reports[$i]['invoice_supplier_code_gen']; ?></td>
                                <td align="center"><?php echo $tax_reports[$i]['invoice_supplier_date_recieve']; ?></td>
                                <td align="left"><?php echo $tax_reports[$i]['invoice_supplier_name']; ?></td>
                                <td align="left"><?php echo $tax_reports[$i]['invoice_supplier_code']; ?></td>
                                <td align="right"><?php echo number_format($tax_reports[$i]['invoice_supplier_total_price'],2); ?></td>
                                <td align="right"><?php echo number_format($tax_reports[$i]['invoice_supplier_vat_price'],2); ?></td>
                                <td align="right"><?php echo number_format($tax_reports[$i]['invoice_supplier_net_price'],2); ?></td>
                                <td align="center"><?php echo $tax_reports[$i]['invoice_supplier_due']; ?></td>
                                <td align="center"><?php echo $tax_reports[$i]['purchase_order_code']; ?></td>
                                <td align="center"><?php if( $tax_reports[$i]['payment'] == $tax_reports[$i]['invoice_supplier_net_price']) { echo "Y"; } else { echo "N"; } ?></td> 
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" align="center"> รวม <?PHP echo number_format(count($tax_reports),0); ?> ใบ</td>
                                <td  align="right" ><?php echo number_format($invoice_supplier_total_price,2); ?></td>
                                <td  align="right" ><?php echo number_format($invoice_supplier_vat_price,2); ?></td>
                                <td  align="right" ><?php echo number_format($invoice_supplier_net_price,2); ?></td>
                                <td  align="right" ></td>
                                <td  align="right" ></td>
                                <td  align="right" ></td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
