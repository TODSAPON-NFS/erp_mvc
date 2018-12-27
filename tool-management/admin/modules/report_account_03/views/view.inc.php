<script>
    function search(){ 
        var date_end = $("#date_end").val(); 
        var code_start = $("#code_start").val(); 
        var code_end = $("#code_end").val(); 
        var keyword = $("#keyword").val(); 

        window.location = "index.php?app=report_account_03&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&keyword="+keyword ;
    }
    function print(type){ 
        var date_end = $("#date_end").val(); 
        var code_start = $("#code_start").val(); 
        var code_end = $("#code_end").val(); 
        var keyword = $("#keyword").val(); 

        window.location = "print.php?app=report_account_03&action="+type+"&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&keyword="+keyword ;
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">งบเเสดงสถานะการเงิน</h1>
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
                        งบเเสดงสถานะการเงิน 
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-1">
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ถึงวันที่</label> 
                            <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/> 
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
                        <a href="index.php?app=report_account_04" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <div class="row">                  
                    <div align="center">
                        <label>สินทรัพย์</label>
                    </div>
                </div>

                <table width="80%"  align="center">
                    <thead>
                        <tr>                       
                            <th width="250" ></th>
                            <th width="100" ></th>
                            <th width="20" ></th>                              
                        </tr>
                    </thead>
                   
                     <tbody>

                    <tr class="odd gradeX">                     
                            <td align="right" > หมายเหตุ </td> 
                            <td align="right">  หน่วย:บาท</td>
                            <td align="right"></td>                            
                    </tr>
                   
                   
                        <?php 
                        $journal_debit_sum = 0;
                        $journal_credit_sum = 0;
                        $sumAll = 0 ;
                        $name_level2 = '' ;
                        $name_level3 = '' ;
                         $val = 0 ;
                        $text_tr = '' ;
                        $text_level2 = '' ;
                        $text_level3 = '' ;
                                            
                        $count = 0 ;

                        for($i=0; $i < count($journal_reports); $i++){
                            $journal_debit = 0;
                            $journal_credit = 0;  
                                 
                                if($journal_reports[$i]['account_value'] < 0){
                                     $journal_credit = abs($journal_reports[$i]['account_value']);
                                     $journal_credit_sum += $journal_credit;
                                }else{
                                     $journal_debit = abs($journal_reports[$i]['account_value']);
                                     $journal_debit_sum += $journal_debit;
                                }  
                            
                        // if($journal_reports[$i]['account_value'] != 0 || $journal_reports[$i]['account_level'] == '2'){                                                                    
                        
                           
                             if($journal_reports[$i]['account_level'] == '2'){ 
                                
                                $text_level2 =  ' <tr > <td > &emsp;<div><label> '.$journal_reports[$i]['account_name_th'].'</label></div> </td> </tr>';
                                                                                       

                            }   $val += $journal_reports[$i]['account_value'];    
                                
                            if( $journal_reports[$i]['account_level'] == '3'){ $name_level3 = $journal_reports[$i]['account_name_th'] ;  }                                                                            
                                                        
                            if( $journal_reports[$i+1]['account_level'] == '3' &&  $val != 0) {

                                $text_tr .= '
                                <tr class="odd gradeX">
                                <td >&emsp;&emsp;&emsp;'. $name_level3.'</td> 
                                <td align="right">'. number_format($val,2).'</td>
                                <td align="right"><'.$journal_reports[$i]['account_level'].'</td> 
                                </tr>' ;                                                                                      
                            
                                $val = 0 ;   
                                $journal_debit_sum = 0 ;       
                                $journal_credit_sum = 0 ;  

                            }
                            if( $journal_reports[$i+1]['account_level'] == '2' &&  $val != 0) { 

                                $arr [$count]['fulltable'] .= $text_level2.$text_tr;

                                $count++;
                                $text_tr='';
                            
                            }
                                            
                                                                                      
                             $sumAll +=  $journal_debit - $journal_credit ;                                                      
                       
                        } ?>       

                         <?  print_r($arr); ?>
                           
                         
                                          
                    </tbody>
                    <tfoot>
                        <tr>
                            <td  align="left"><div><label>รวมสินทรัพย์</label></div></td>
                            <td  align="right" ><?php echo number_format($sumAll, 2); ?></td>
                            <td  align="right" ><?php// echo number_format($sumAll,2); ?></td> 
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
            
            
