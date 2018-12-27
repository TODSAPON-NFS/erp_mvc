<script>
    function search(){ 
        var date_end = $("#date_end").val(); 
        var code_start = $("#code_start").val(); 
        var code_end = $("#code_end").val(); 
        var keyword = $("#keyword").val(); 

        window.location = "index.php?app=report_account_01&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&keyword="+keyword ;
    }
    function print(type){ 
        var date_end = $("#date_end").val(); 
        var code_start = $("#code_start").val(); 
        var code_end = $("#code_end").val(); 
        var keyword = $("#keyword").val(); 

        window.location = "print.php?app=report_account_01&action="+type+"&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&keyword="+keyword ;
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานผังบัญชี</h1>
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
                            <label>ถึงวันที่</label> 
                            <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/> 
                            <p class="help-block">31-12-2018</p>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ช่วงเลขที่บัญชี</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="code_start" name="code_start" value="<?PHP echo $code_start;?>"  class="form-control" />
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="code_end" name="code_end" value="<?PHP echo $code_end;?>"  class="form-control" />
                                </div>
                            </div>
                            <p class="help-block">0000-00 - 9999-99</p>
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
                        <a href="index.php?app=report_account_01" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th width="48" >ลำดับ</th> 
                            <th width="100" >เลขที่บัญชี</th>
                            <th width="150" >ชื่อบัญชี</th>
                            <th width="150" >หมวดบัญชี</th>
                            <th width="150" >ประเภทบัญชี</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        
                        for($i=0; $i < count($journal_reports); $i++){
                            $journal_debit = 0;
                            $journal_credit = 0;
                            if($journal_reports[$i]['account_value'] < 0){
                                $journal_credit = abs($journal_reports[$i]['account_value']);
                            }else{
                                $journal_debit = abs($journal_reports[$i]['account_value']);
                            }
                             
                        ?>
                        <tr class="odd gradeX">
                            <td align="center" ><?PHP echo number_format($i + 1,0);?></td>
                            <td><?php echo $journal_reports[$i]['account_code']; ?></td>
                            <td><?php echo $journal_reports[$i]['account_name_th']; ?></td> 
                            <td><?php echo $journal_reports[$i]['account_group_name']; ?></td> 
                            <td><?php 
                                if ($journal_reports[$i]['account_type'] == 1) {
                                    echo "บัญชีควบคุม";
                                } else {
                                    echo "บัญชีย่อย";
                                }
                                ?>
                            </td> 
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
            
            
