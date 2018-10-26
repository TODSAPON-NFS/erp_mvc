<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val(); 
        var keyword = $("#keyword").val();
        var type_full = $("#type_full").val();

        window.location = "index.php?app=report_account_04&date_start="+date_start+"&date_end="+date_end+"&keyword="+keyword+"&type="+type_full;
    }
    function print(type){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val(); 
        var keyword = $("#keyword").val();
        var type_full = $("#type_full").val();

        window.location = "print.php?app=report_account_04&action="+type+"&date_start="+date_start+"&date_end="+date_end+"&keyword="+keyword+"&type="+type_full;
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานสมุดรายวัน</h1>
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
                        รายงานสมุดรายวัน 
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>งวดสมุดรายวัน</label>
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
                            <label>การแสดงผล <font color="#F00"><b>*</b></font></label>
                            <select class="form-control" id="type_full">
                                <option value="" >แสดงแบบย่อ</option>
                                <option value="full" <?PHP if($type=="full") { ?> SELECTED <?PHP } ?>>แสดงแบบเต็ม</option>
                            </select>
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
                        <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="print('pdf','');">PDF</button>
                        <button class="btn btn-success" style="float:right; margin:0px 4px;" onclick="print('excel','');">Excel</button>
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search('');">Search</button>
                        <a href="index.php?app=report_account_04" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th width="100" >เลขที่บัญชี</th>
                            <th >ชื่อบัญชี</th>
                            <th >คำอธิบาย</th>
                            <th width="150" >เดบิต</th>
                            <th width="150" >เครดิต</th>  
                            <th width="150" >สถานะ</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $journal_debit = 0;
                        $journal_credit = 0; 
                        for($i=0; $i < count($journal_reports); $i++){
                            $journal_debit +=  $journal_reports[$i]['journal_debit'];
                            $journal_credit +=  $journal_reports[$i]['journal_credit'];

                            if( $journal_reports[$i-1]['journal_code'] != $journal_reports[$i]['journal_code']){
                                
                        ?>
                        <tr class="odd gradeX">
                            <td colspan="6" >
                            <b><?php echo $journal_reports[$i]['journal_date']; ?></b> <b><?php echo $journal_reports[$i]['journal_code']; ?></b> <?php echo $journal_reports[$i]['journal_name']; ?>
                            </td> 
                        </tr>
                        
                        <?PHP
                            }
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $journal_reports[$i]['account_code']; ?></td>
                            <td><?php echo $journal_reports[$i]['account_name']; ?></td>
                            <td><?php echo $journal_reports[$i]['journal_list_name']; ?></td>
                            <td align="right"><?php echo number_format($journal_reports[$i]['journal_debit'],2); ?> </td>
                            <td align="right"><?php echo number_format($journal_reports[$i]['journal_credit'],2); ?></td> 
                            <td  align="center" ></td> 
                        </tr>
                        <?PHP
                            if($journal_reports[$i]['journal_code'] != $journal_reports[$i+1]['journal_code']){
                        ?>
                        <tr class="odd gradeX">
                            <td colspan="3" align="center" >
                               <b> รวม </b>
                            </td> 
                            <td align="right"><b><?php echo number_format($journal_debit,2); ?></b> </td>
                            <td align="right"><b><?php echo number_format($journal_credit,2); ?></b> </td> 
                            <td  align="center" >
                                <?PHP if(number_format($journal_debit,2) == number_format($journal_credit,2)){ ?>
                                    <font color="green"><b>ยอดตรง</b></font>
                                <?PHP } else { ?> 
                                    <font color="red"><b>ยอดไม่ตรง</b></font>
                                <?PHP } ?>
                            </td>
                        </tr>
                        <?PHP  
                                $journal_debit = 0;
                                $journal_credit = 0;
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" align="center">รวม</td>
                            <td  align="right" ><?php echo number_format($net_total,2); ?></td>
                            <td  align="right" ><?php echo number_format($vat_total,2); ?></td>
                            <td></td>
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
            
            
