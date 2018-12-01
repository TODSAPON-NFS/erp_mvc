<script>
    function search(){ 
      
        var date_end = $("#date_end").val();    
        var account_ = $("#account_id").val();

      
            window.location = "index.php?app=report_account_07&date_end="+date_end+"&account_id="+account_ ;
      
    }
    function print(type){ 
        var date_start = $("#date_start").val(); 
        var date_end = $("#date_end").val(); 
        var code_start = $("#code_start").val(); 
        var code_end = $("#code_end").val(); 
         
        var account_ = $("#account_id").val();
        window.open("print.php?app=report_account_07&action="+type+"&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&account_id="+account_,'_blank');
    }
</script>


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานเช็คจ่ายคงเหลือ</h1>
    </div>

            <div class="panel-body">
                  <div class="col-lg-6">
                        <div class="form-group">
                            <label>ถึงวันที่</label> 
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_start;?>"  class="form-control calendar" readonly/>
                                </div>
                            </div>
                            <p class="help-block">31-12-2018</p>
                        </div>
                     </div>


                <div class="col-lg-3">
                    <div class="form-group">
                        <label>ประเภทบัญชี  </label>
                            <select id="account_id" name="account_id"  class="form-control select" data-live-search="true" >
                                <option value="">เลือก / Select</option>
                                <?PHP 
                                    for($i=0; $i < count($account) ; $i++){
                                ?>
                                <option value="<?PHP echo $account[$i]['account_id'];?>" <?PHP if($account[$i]['account_id'] == $finance_credit_account['account_id'] ){ ?> SELECTED <? } ?> ><?PHP echo $account[$i]['account_code'];?> <?PHP echo $account[$i]['account_name_th'];?></option>
                                 <?PHP
                                   }
                                ?>
                            </select>
                                <p class="help-block">Example : 2120-01.</p>
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
                        <a href="index.php?app=report_account_07" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

              <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th align="center" width="30" >ลำดับ</th> 
                            <th align="center" width="70" >เลขที่บัญชี</th>
                            <th align="center" width="90" >ชื่อบัญชี</th>
                            <th align="center" width="80" >วันที่จ่าย</th>                                           
                            <th align="center" width="70" >เลขที่เช็ค</th>
                            <th align="center" width="70" >เงินหน้าเช็ค</th>   
                            <th align="center" width="65" > ใบสำคัญ </th>
                            <th width="165" >หมายเหตุ</th>
                        </tr>
                    </thead>

                    <?php 
                        $journal_debit_sum = 0;
                        $journal_credit_sum = 0;
                        
                        for($i=0; $i < count($journal_reports); $i++){
                    ?>
                      <tr class="odd gradeX">
                      <td align="center" ><?PHP echo number_format($i + 1,0);?></td>
                      <td align="center" ><?PHP echo $journal_reports[$i]['account_code'];?></td> 
                      <td align="center" ><?PHP echo $journal_reports[$i]['account_name_th'];?></td> 
                      <td align="center" ><?PHP echo $journal_reports[$i]['check_pay_date_write'];?></td> 
                      <td align="center" ><?PHP echo $journal_reports[$i]['cheque_code'];?></td> 
                      <td align="right"  ><?PHP echo number_format($journal_reports[$i]['cheque_total'],2);?></td> 
                      <td align="center" ><?PHP echo $journal_reports[$i]['journal_code'];?> </td>                     
                      <td ><?PHP echo $journal_reports[$i]['journal_name'];?></td>
                      </tr>
                    <?
                        }                  
                    ?>


                </table>
            

    <!-- /.col-lg-12 -->
</div>

