
<script>
    function search(){ 
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var code_start = $("#code_start").val();
        var code_end = $("#code_end").val();
        var supplier_id = $("#supplier_id").val(); 
        window.location = "index.php?app=report_creditor_06&date_start="+date_start+"&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&supplier_id="+supplier_id ;
    }
    function print(type){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var code_start = $("#code_start").val();
        var code_end = $("#code_end").val();
        var supplier_id = $("#supplier_id").val(); 

        window.open("print.php?app=report_creditor_06&action="+type+"&date_start="+date_start+"&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&supplier_id="+supplier_id ,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานสถานะเจ้าหนี้</h1>
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
                    รายงานสถานะเจ้าหนี้
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ช่วงวัน</label>
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
                            <label>รหัสผู้ขาย</label>
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
                            <p class="help-block">0A-001 - 0C-001</p>
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
                    </div >
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
                        <a href="index.php?app=report_creditor_06" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th  width="48" style="text-align: center;vertical-align: middle;"> ลำดับ</th> 
                            <th  style="text-align: center;vertical-align: middle;" >ผู้ขาย</th> 
                            <th  style="text-align: center;vertical-align: middle;" >ยอดหนี้ยกมา</th> 
                            <th  style="text-align: center;vertical-align: middle;" >ยอดซื้อ</th>  
                            <th  style="text-align: center;vertical-align: middle;" >เพิ่มหนี้</th>  
                            <th  style="text-align: center;vertical-align: middle;" >ลดหนี้/รับคืน</th>  
                            <th  style="text-align: center;vertical-align: middle;" >จ่ายชำระหนี้</th>  
                            <th  style="text-align: center;vertical-align: middle;" >ยอดหนี้ยกไป</th>  
                        </tr> 
                    </thead>
                    <tbody>

                        <?php 
                        $credit_before = 0; 
                        $credit_invoice = 0;  
                        $credit_credit = 0;  
                        $credit_credit = 0; 
                        $credit_payment = 0;  
                        $credit_balance = 0;  
                        for($i=0; $i < count($creditor_reports); $i++){
                            $credit_before +=  $creditor_reports[$i]['credit_before']; 
                            $credit_invoice +=  $creditor_reports[$i]['credit_invoice'];  
                            $credit_credit +=  $creditor_reports[$i]['credit_credit'];  
                            $credit_credit +=  $creditor_reports[$i]['credit_credit']; 
                            $credit_payment +=  $creditor_reports[$i]['credit_payment'];  
                            $credit_balance +=  $creditor_reports[$i]['credit_balance'];  
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td>[<?php echo $creditor_reports[$i]['supplier_code']; ?>] <?php echo $creditor_reports[$i]['supplier_name_en']; ?></td> 
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['credit_before'],2); ?>
                            </td> 
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['credit_invoice'],2); ?>
                            </td>  
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['credit_credit'],2); ?>
                            </td>  
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['credit_credit'],2); ?>
                            </td>  
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['credit_payment'],2); ?>
                            </td>  
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['credit_balance'],2); ?>
                            </td>  
                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" align="center"><b>รวม</b></td>
                            <td  align="right" ><b><?php echo number_format($credit_before,2); ?></b></td>  
                            <td  align="right" ><b><?php echo number_format($credit_invoice,2); ?></b></td>  
                            <td  align="right" ><b><?php echo number_format($credit_credit,2); ?></b></td>  
                            <td  align="right" ><b><?php echo number_format($credit_credit,2); ?></b></td>  
                            <td  align="right" ><b><?php echo number_format($credit_payment,2); ?></b></td>  
                            <td  align="right" ><b><?php echo number_format($credit_balance,2); ?></b></td>  
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
            
        