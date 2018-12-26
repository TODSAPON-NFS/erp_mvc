<script>
    function search(){ 
        var date_start = $("#date_start").val(); 
        var date_end = $("#date_end").val(); 

        window.location = "index.php?app=report_account_09&date_start="+date_start+"&date_end="+date_end ;
    }
    function print(type){ 
        var date_start = $("#date_start").val(); 
        var date_end = $("#date_end").val(); 

        window.open("print.php?app=report_account_09&action="+type+"&date_start="+date_start+"&date_end="+date_end,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานยอดขาย</h1>
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
                    รายงานยอดขาย
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ถึงวันที่</label> 
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
                            <p class="help-block">31-12-2018</p>
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
                        <a href="index.php?app=report_account_09" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th width="100" >ชื่อพนักงาน</th> 
                            <th width="120" >ลูกค้า</th>  
                            <th >จำนวนเงิน</th>
                        </tr>
                    </thead>

                    <tbody>
                       
                    <?PHP
                        for($i=0; $i < count($journal_reports); $i++){


                            if($journal_reports[$i]['user_username'] == $journal_reports[$i]['user_username'] && $journal_reports[$i]['user_username'] != null) {

                                $sum +=  $journal_reports[$i]['invoice_customer_net_price'];
                           
                    ?>

                                <tr>
                                    <td>
                                        <?php echo $journal_reports[$i]['user_username']; ?>
                                    </td>
                                    <td>
                                        <?php echo $journal_reports[$i]['customer_name_en']; ?>
                                    </td>
                                    <td align="right">
                                        <?php echo number_format ($journal_reports[$i]['invoice_customer_net_price'],2); ?>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <?PHP
                                        }
                                        if($journal_reports[$i]['user_username'] != $journal_reports[$i+1]['user_username']  && $journal_reports[$i]['user_username'] != null) {
                                            $sum_sum = $sum;

                                    ?>
                                                  
                     <tr class="">
                            <td colspan="3" >
                            </td>
                        </tr>
                                            <td colspan="2"  align="center">
                                                รวม
                                            </td>
                                            <td align="right">
                                                <?PHP
                                                    echo number_format($sum_sum,2);
                                                ?>
                                            </td>
                                                          
                     <tr class="">
                            <td colspan="3" >
                            </td>
                        </tr>
                                        </tr>
                          
                    <?PHP 
                                            $sum = 0;
                                        }

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
            
            
