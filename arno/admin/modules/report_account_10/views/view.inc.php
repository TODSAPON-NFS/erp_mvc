<script>
    function search(){ 
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val(); 
        var code_start = $("#code_start").val(); 
        var code_end = $("#code_end").val(); 
        var keyword = $("#keyword").val(); 

        window.location = "index.php?app=report_account_10&date_start="+date_start+"&date_end="+date_end;
    }
    function print(type){ 
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val(); 
        var code_start = $("#code_start").val(); 
        var code_end = $("#code_end").val(); 
        var keyword = $("#keyword").val(); 

        window.location = "print.php?app=report_account_10&action="+type+"&date_end="+date_end+"&code_start="+code_start+"&code_end="+code_end+"&keyword="+keyword ;
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">งบกำไรขาดทุน</h1>
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
                        รายงานงบกำไรขาดทุน 
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
                            <label>จากวันที่</label> 
                            <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start;?>"  class="form-control calendar" readonly/> 
                            <p class="help-block">01-12-2018</p>

                        </div>

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
                        <label>รายงานงบกำไรขาดทุน</label>
                    </div>
                </div>

                <?php 
                $thai_month_arr_short=array(   
                    "0"=>"",   
                    "1"=>"ม.ค.",   
                    "2"=>"ก.พ.",   
                    "3"=>"มี.ค.",   
                    "4"=>"เม.ย.",   
                    "5"=>"พ.ค.",   
                    "6"=>"มิ.ย.",    
                    "7"=>"ก.ค.",   
                    "8"=>"ส.ค.",   
                    "9"=>"ก.ย.",   
                    "10"=>"ต.ค.",   
                    "11"=>"พ.ย.",   
                    "12"=>"ธ.ค."                    
                );   
                ///echo strtotime($date_start).'<br>';
               /// echo date('Y',$date_start) ;
                ?>

                <table width="80%"  align="center">
                    <thead>
                        <tr>                       
                            <th width="300" ></th>
                            <th width="100" ></th>
                            <th width="20" ></th>
                            <th width="100" ></th>
                            <th width="20" ></th>                               
                        </tr>
                    </thead>
                  
                     <tbody>

                     <tr class="odd gradeX">  
                            <td align="right"></td> 
                            <td align="right"></td>
                            <td align="right"></td> 
                            <td align="right"> <?PHP// echo $date_start;?> <?PHP //echo $date_end;?> </td>
                            <td align="right"></td>     
                     </tr>

                    <tr class="odd gradeX">                     
                            <td align="right" >  </td> 
                            <td align="right"></td>
                            <td align="right"></td> 
                            <td align="right">  หน่วย:บาท</td>
                            <td align="right">  % </td>                            
                    </tr>
                    
                    <tr class="odd gradeX">
                            <td  align="left" ><div><label>รายได้</label></div></td>
                            <td align="right">   </td>
                            <td align="right">   </td> 
                    </tr>
                    <?php  
                    $sumLucre = 0 ;
                    $journal_credit = 0 ;
                    $journal_debit = 0;
                    $sum = 0 ;
                    $value = 0 ;

                    for($i=0; $i < count($journal_reports_income); $i++){ 
                        $sum += $journal_reports_income[$i]['journal_credit']- $journal_reports_income[$i]['journal_debit'] ;
                    }
                    for($i=0; $i < count($journal_reports_income); $i++){ 

                        $journal_credit = $journal_reports_income[$i]['journal_credit'];
                        $journal_debit = $journal_reports_income[$i]['journal_debit'];
                        $value = ($journal_debit-$journal_credit);
                         
                        if( $journal_reports_income[$i]['account_value'] != '0' && $value !=0 ){
                        ?>                      
                        <tr class="odd gradeX">
                            <td >&emsp;&emsp;&emsp;<?php  echo $journal_reports_income[$i]['account_name_th']; ?> </td> 
                            <td align="right"></td>
                            <td align="right"></td> 
                            <td align="right"><?php echo number_format(abs($value),2); ?> </td>
                            <td align="right"><?php echo (number_format(abs($value)/$sum *100,2)).'%';?> </td> 
                         </tr>                   
                        <?php }
                   } ?>

                   
                   <tr class="odd gradeX" style="height: 30px;">
                            <td align="left"  ><div> <label> รวมรายได้ </label> </div> </td>
                            <td align="right"></td>
                            <td align="right"></td> 
                            <td align="right" style="border-top:1px solid">  <?php if($sum!=0){ echo number_format(abs($sum),2); }else{echo number_format(0,2);}?>  </td>
                            <td align="right" >  <?php if($sum!=0){ echo number_format($sum/$sum*100,2) ;}else{echo number_format(0,2);}?> %</td> 
                    </tr>

     <!-------------------------------------------------------------------- ค่าใช้าจ่าย ------------------------------------------------------------------------------------------------>               
                  
                    <tr class="odd gradeX" style="height: 40px;">
                            <td align="left"><div><label>ค่าใช้าจ่าย</label></div></td>
                            <td align="right"></td>
                            <td align="right"></td> 
                            <td align="right"></td>
                            <td align="right"></td> 
                    </tr>

                    <?php  
                    
                    $journal_credit = 0 ;
                    $journal_debit = 0;
                    //$sum = 0 ;
                    $sumAll = 0 ;
                    $sumCost = 0 ;
                    $value = 0 ;
                    $first = 0 ;
                    $sumexp = 0;
                    for($i=0; $i < count($journal_reports_charges); $i++){ 
                        $sumAll += $journal_reports_charges[$i]['journal_credit']- $journal_reports_charges[$i]['journal_debit'] ;
                    }

                    for($i=0; $i < count($journal_reports_charges); $i++){ 
                        
                        $journal_credit = $journal_reports_charges[$i]['journal_credit'];
                        $journal_debit = $journal_reports_charges[$i]['journal_debit'];
                        $value = ($journal_debit-$journal_credit);
                        $sumCost+=$value;
                        
                        if( $journal_reports_charges[$i]['account_value'] != '0' && $value !=0 ){
                            ?>                      
                            <tr class="odd gradeX">
                                <td >&emsp;&emsp;&emsp;<?php  echo $journal_reports_charges[$i]['account_name_th']; ?> </td>                              
                                <td align="right"><?php if( $value > 0 ){ echo number_format( $value , 2); }else{ echo "(".number_format(abs($value),2).")";}?> </td>
                                <td align="right"><?php echo (number_format($value/ abs($sum) *100 ,2)).'%';?> </td> 
                                <td align="right"></td>
                                <td align="right"></td>
                             </tr> 
                        
                   <?php } 
                                               
                          if($journal_reports_charges[$i]['account_level'] == '2'){ ?> 
                                    <? if($first==1) { ?>
                                        <tr class="odd gradeX" style="height: 30px;">
                                            <td align="left"  >&emsp;&emsp;</td>
                                            <td align="right"></td>
                                            <td align="right"></td> 
                                            <td align="right">   <?php if($sum!=0){ echo number_format(abs($sumCost),2);} else{echo number_format(0,2);}?>  </td>
                                            <td align="right" >  <?php if($sum!=0){ echo number_format($sumCost/$sum*100,2) ;}else{echo number_format(0,2);}?> %</td> 
                                            
                                        </tr>

                                        <tr class="odd gradeX" style="height: 30px;">
                                            <td align="left"  >&emsp;&emsp;</td>
                                            <td align="right"></td>
                                            <td align="right"></td> 
                                            <td align="right" style="border-top:1px solid">  <?php if($sum!=0){ echo number_format(abs($sum-$sumCost),2);} else{echo number_format(0,2);} ?>  </td>
                                            <td align="right" >  <?php if($sum!=0){  echo number_format(($sum-$sumCost)/$sum*100,2) ;}else{echo number_format(0,2);}?> %</td> 
                                            
                                        </tr>

                                        <tr class="odd gradeX" style="height: 40px;">
                                            <td align="left"><div><label>กำไรขั้นต้น</label></div></td>
                                            <td align="right"></td>
                                            <td align="right"></td> 
                                            <td align="right"></td>
                                            <td align="right"></td> 
                                        </tr>

                                    <?php $sumLucre = $sum-$sumCost; $sumCost = 0 ;}?>

                            <tr class="odd gradeX">
                                <td >&emsp;<?php echo $journal_reports_charges[$i]['account_name_th']; ?> :</td> 
                                <td align="right"></td>
                                <td align="right"></td> 
                                <td align="right"></td>
                                <td align="right"></td> 
                            </tr>                           
                               
                    <?php   $first++;}
                    }
                   ?>
                                <?php $beforeCost = $sumLucre-$sumCost ; 
                                      $cost = 0 ;
                                      $before_income_tax  = $beforeCost - $cost ;
                                      $income_tax = 0 ;
                                      $net_profit = $before_income_tax-$income_tax ;
                                ?>
                                <tr class="odd gradeX">   
                                    <td align="left"  >&emsp;&emsp;&emsp;รวมค่าใช้จ่าย</td>  
                                    <td align="right"></td>
                                    <td align="right"></td>                                 
                                    <td align="right" style="border-top:1px solid">  <?php  if($sum!=0){ echo number_format(abs($sumCost),2); } else{echo number_format(0,2);}?>  </td>
                                    <td align="right" >  <?php if($sum!=0){ echo number_format($sumCost/$sum*100,2) ;}else{echo number_format(0,2);}?> %</td>                                                               
                                </tr>
                                <tr class="odd gradeX" >   
                                    <td align="left"  >&emsp;&emsp;กำไร (ขาดทุน) ก่อนต้นทุนทางการเงิน </td>  
                                    <td align="right"></td>
                                    <td align="right"></td>                                 
                                    <td align="right" style="border-top:1px solid">  <?php if($sum!=0){ echo number_format(abs($beforeCost),2); } else{echo number_format(0,2);}?>  </td>
                                    <td align="right" >  <?php if($sum!=0){ echo number_format(($beforeCost)/$sum*100,2) ;}else{echo number_format(0,2);}?> %</td>                                                               
                                </tr>
                                <tr class="odd gradeX" >   
                                    <td align="left"  >&emsp;&emsp;ต้นทุนทางการเงิน </td>  
                                    <td align="right"></td>
                                    <td align="right"></td>                                 
                                    <td align="right">  <?php if($sum!=0){ echo number_format(abs($cost),2); } else{echo number_format(0,2);}?>  </td>
                                    <td align="right" >  <?php if($sum!=0){ echo number_format(($cost)/$sum*100,2) ;}else{echo number_format(0,2);}?> %</td>                                                               
                                </tr>
                                <tr class="odd gradeX" >   
                                    <td align="left"  >&emsp;&emsp;กำไร (ขาดทุน) ก่อนภาษีเงินได้ </td>  
                                    <td align="right"></td>
                                    <td align="right"></td>                                 
                                    <td align="right" style="border-top:1px solid">  <?php if($sum!=0){ echo number_format(abs($before_income_tax),2); } else{echo number_format(0,2);}?>  </td>
                                    <td align="right" >  <?php if($sum!=0){ echo number_format(($before_income_tax)/$sum*100,2) ;}else{echo number_format(0,2);}?> %</td>                                                               
                                </tr>
                                <tr class="odd gradeX" >   
                                    <td align="left"  >&emsp;&emsp;ภาษีเงินได้ </td>  
                                    <td align="right"></td>
                                    <td align="right"></td>                                 
                                    <td align="right" style="border-top:1px solid">  <?php if($sum!=0){ echo number_format(abs($income_tax),2); } else{echo number_format(0,2);}?>  </td>
                                    <td align="right" >  <?php if($sum!=0){ echo number_format(($income_tax)/$sum*100,2) ;}else{echo number_format(0,2);}?> %</td>                                                               
                                </tr>


                                <tr class="odd gradeX" >   
                                    <td align="left"  >&emsp;&emsp;กำไร (ขาดทุน) สุทธิ </td>  
                                    <td align="right"></td>
                                    <td align="right"></td>                                 
                                    <td align="right" style="border-top:1px solid ; border-bottom: 3px double black ; ">  <?php if($sum!=0){ echo number_format(abs( $net_profit),2); } else{echo number_format(0,2);}?>  </td>
                                    <td align="right" >  <?php if($sum!=0){ echo number_format(( $net_profit)/$sum*100,2) ;}else{echo number_format(0,2);}?> %</td>                                                               
                                </tr>
                                


          
                    <tbody>
                </table>
               
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
