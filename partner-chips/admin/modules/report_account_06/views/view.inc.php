<script>
    function search(){ 
        var date_end = $("#date_end").val(); 
        var code_start = $("#code_start").val(); 
        var code_end = $("#code_end").val(); 
        var keyword = $("#keyword").val(); 

        window.location = "index.php?app=report_account_06&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&keyword="+keyword ;
    }
    function print(type){ 
        var date_end = $("#date_end").val(); 
        var code_start = $("#code_start").val(); 
        var code_end = $("#code_end").val(); 
        var keyword = $("#keyword").val(); 

        window.location = "print.php?app=report_account_06&action="+type+"&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&keyword="+keyword ;
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานบัญชีแยกประเภท</h1>
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
                    รายงานบัญชีแยกประเภท 
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
                        <a href="index.php?app=report_account_04" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th width="100" >วันที่</th> 
                            <th width="120" >เลขที่เอกสาร</th> 
                            <th >ชื่อเอกสาร</th>
                            <th >คำอธิบาย</th>
                            <th width="150" >เดบิต</th>
                            <th width="150" >เครดิต</th>  
                            <th width="150" >คงเหลือ</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $journal_debit = 0;
                        $journal_credit = 0; 

                        $balance = 0;

                        $journal_debit_sum = 0;
                        $journal_credit_sum = 0; 

                        for($i=0; $i < count($journal_reports); $i++){ 
                            if( $journal_reports[$i-1]['account_code'] != $journal_reports[$i]['account_code']){ 
                                $balance = $journal_reports[$i]['account_debit_begin'] - $journal_reports[$i]['account_credit_begin'];
                        ?>
                        <tr class="">
                            <td colspan="7" >
                            </td>
                        </tr>
                        <tr class="">
                            <td colspan="3" >
                                <b><?php echo $journal_reports[$i]['account_code']; ?></b> <?php echo $journal_reports[$i]['account_name_th']; ?>
                            </td> 
                            <td align="right">
                                <b><font color="black">ยอดยกมา</font></b>
                            </td>
                            <td align="right">
                                <font color="black"> </font>
                            </td>
                            <td align="right">
                                <font color="black"> </font>
                            </td>
                            <td align="right">
                                <font color="black"> <?php echo number_format($balance,2); ?></font>
                            </td>
                        </tr>
                        
                        <?PHP
                            }

                            $journal_debit +=  $journal_reports[$i]['journal_debit'];
                            $journal_credit +=  $journal_reports[$i]['journal_credit'];
                            $balance = $balance + ($journal_reports[$i]['journal_debit'] - $journal_reports[$i]['journal_credit']);
                        ?>
                        <tr class="">
                            <td><?php echo $journal_reports[$i]['journal_date']; ?></td>
                            <td><?php echo $journal_reports[$i]['journal_code']; ?></td>
                            <td><?php echo $journal_reports[$i]['journal_name']; ?></td>
                            <td><?php echo $journal_reports[$i]['journal_list_name']; ?></td>
                            <td align="right" ><?php echo number_format($journal_reports[$i]['journal_debit'],2); ?> </td>
                            <td align="right" ><?php echo number_format($journal_reports[$i]['journal_credit'],2); ?></td> 
                            <td  align="right" ><?php echo number_format($balance,2); ?></td> 
                        </tr>
                        <?PHP
                            if($journal_reports[$i]['account_code'] != $journal_reports[$i+1]['account_code']){ 

                                $journal_debit_sum += $journal_debit;
                                $journal_credit_sum +=  $journal_credit; 
                        ?>
                        <tr class="">
                            <td colspan="4" align="center" >
                               <b><font color="black"> ยอดคงเหลือ</font> </b>
                            </td> 
                            <td align="right"><b><font color="black"><?php echo number_format($journal_debit,2); ?> </font></b> </td>
                            <td align="right"><b><font color="black"><?php echo number_format($journal_credit,2); ?> </font></b> </td> 
                            <td  align="right" >
                            <b><font color="black"></font></b>
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
                            <td colspan="4" align="center">รวม</td>
                            <td  align="right" ><?php echo number_format($journal_debit_sum,2); ?></td>
                            <td  align="right" ><?php echo number_format($journal_credit_sum,2); ?></td>
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
            
            
