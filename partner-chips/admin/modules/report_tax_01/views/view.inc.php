<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=report_tax_01&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
    }
    function print(type){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "print.php?app=report_tax_01&action="+type+"&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
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

            $('#form_report').prop('action',"index.php?app=report_tax_01&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword);
            return true;
        }
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานภาษีซื้อ</h1>
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
                        รายงานภาษีซื้อ 
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>งวดภาษีซื้อ</label>
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
                        <a href="index.php?app=report_tax_01" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>
                <form id="form_report" role="form" method="post" onsubmit="return check();" action="?app=report_tax_01">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="tb_report" >
                        <thead>
                            <tr>
                                <th width="48"> ลำดับ <br>No.</th>
                                <th width="100">วันที่ <br>Date</th>
                                <th >เลขที่ <br>Code.</th>
                                <th >เลขที่อ้างอิง <br>Reference code.</th>
                                <th>
                                    <table width="100%">
                                        <tr>
                                            <td width="80">
                                                ผู้ขาย <br>Supplier
                                            </td>
                                            <td width="24">
                                                <input type="checkbox" value="all" id="check_all" onclick="checkAll(this)" />
                                            </td>
                                            <td> 
                                                <select id="supplier_replace_id" name="supplier_replace_id" class="form-control select" data-live-search="true">
                                                    <option value="">Select</option>
                                                    <?php 
                                                    for($i =  0 ; $i < count($suppliers) ; $i++){
                                                    ?>
                                                    <option <?php if($suppliers[$i]['supplier_id'] == $supplier_replace_id){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?>  </option>
                                                    <?
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td width="24"> 
                                                <button class="btn btn-warning" type="submit" style=" margin:4px 4px;" onclick="check();">Replace</button>
                                            </td>
                                        </tr>
                                    </table>
                                </th>
                                <th >เลขผู้เสียภาษี <br> Tax</th>
                                <th width="120" > ผู้ออก<br>Create by</th> 
                                <th>ยอดเงิน<br>Net Price.</th>
                                <th>ภาษีซื้อ<br>Vat.</th> 
                            </tr> 
                        </thead>
                        <tbody>
                            <?php 
                            $vat_total = 0;
                            $net_total = 0;
                            for($i=0; $i < count($tax_reports); $i++){
                                $vat_total +=  $tax_reports[$i]['invoice_supplier_vat_price'];
                                $net_total +=  $tax_reports[$i]['invoice_supplier_total_price'];
                            ?>
                            <tr class="odd gradeX">
                                <td><?php echo $i+1; ?></td>
                                <td><?php echo $tax_reports[$i]['invoice_supplier_date']; ?></td>
                                <td><?php echo $tax_reports[$i]['invoice_supplier_code']; ?></td>
                                <td><?php if($tax_reports[$i]['invoice_supplier_code_gen'] != ""){ echo $tax_reports[$i]['invoice_supplier_code_gen']; } else { echo $tax_reports[$i]['reference_code']; } ?></td>
                                <td><input type="checkbox" value="<?php echo $tax_reports[$i]['invoice_supplier_id']; ?>" name="change_id[]" /> <?php echo $tax_reports[$i]['invoice_supplier_name']; ?></td>
                                <td><?php echo $tax_reports[$i]['invoice_supplier_tax']; ?></td>
                                <td><?php echo $tax_reports[$i]['employee_name']; ?></td>
                                <td  align="right" >
                                    <?php echo number_format($tax_reports[$i]['invoice_supplier_total_price'],2); ?>
                                </td>
                                <td  align="right" ><?php echo number_format($tax_reports[$i]['invoice_supplier_vat_price'],2); ?></td> 
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7" align="center"> รวม </td>
                                <td  align="right" ><?php echo number_format($net_total,2); ?></td>
                                <td  align="right" ><?php echo number_format($vat_total,2); ?></td>
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
            
            
