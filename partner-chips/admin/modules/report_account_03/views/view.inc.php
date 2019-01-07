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

                <table width="70%"  align="center">
                    <thead>
                        <tr>                       
                            <th width="300" ></th>
                            <th width="100" ></th>
                            <th width="15" ></th>                              
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
                        $acc_val = 0;
                        $sumlevel = 0 ;

                        for($i=0; $i < count($journal_reports); $i++){
                          $acc_val +=  $journal_reports[$i]['account_value'] ;
                        }

                        for($i=0; $i < count($journal_reports); $i++){
                                                               
                                $sumAll +=  $journal_reports[$i]['account_value'] ; 
                                $sumlevel += $journal_reports[$i]['account_value'] ;
                                                                                                                                      
                             if($journal_reports[$i]['account_level'] == '2'){ 
                                
                                $text_level2 =  ' <tr > <td > &emsp;<div><label> '.$journal_reports[$i]['account_name_th'].'</label></div> </td> </tr>';
                                $name_level2 =  $journal_reports[$i]['account_name_th'] ;                                       
                            }   $val += $journal_reports[$i]['account_value'];    
                                
                            if( $journal_reports[$i]['account_level'] == '3'){ $name_level3 = $journal_reports[$i]['account_name_th'] ;  }                                                                            
                                                        
                            if( ($journal_reports[$i+1]['account_level'] == '3' &&  $val != 0) || ($journal_reports[$i+1]['account_level'] == '2' &  $val != 0)   ){

                                if($val< 0 ){                                  
                                    $text_tr .= '
                                <tr class="odd gradeX">
                                <td >&emsp;&emsp;&emsp;'. $name_level3.'</td> 
                                <td align="right">('. number_format( abs($val),2).')</td>
                                <td align="right">'. number_format(($val/$acc_val)*100,2).'%</td> 
                                </tr>' ;

                                }else{
                                $text_tr .= '
                                <tr class="odd gradeX">
                                <td >&emsp;&emsp;&emsp;'. $name_level3.'</td> 
                                <td align="right">'. number_format($val,2).'</td>
                                <td align="right">'. number_format(($val/$acc_val)*100,2).'%</td> 
                                </tr>' ;                                                                                      
                                }
                                if( $journal_reports[$i+1]['account_level'] == '2' && $val != 0 ){ }else { $val = 0; }
                                                                
                            }

                            if( ($journal_reports[$i+1]['account_level'] == '2' &&  $val != 0) || $i+1 == count($journal_reports))  { 
                                $arr [$count]['fulltable'] .= $text_level2.$text_tr.'<tr style="width:100px"><td  align="left"  >&emsp;&emsp;&emsp;&emsp;&emsp;รวม'.$name_level2.'</td><td  align="right" style="border-top:1px solid" >'.number_format($sumlevel,2).'</td><td  align="right" >'. number_format(($sumlevel/$acc_val)*100,2).'%</td> 
                            </tr>
                            ';
                                $count++;
                                $text_tr='';  
                                $sumlevel = 0 ;   
                                $val = 0;                    
                            }                                                                                                                                                                                                                                      
                        } ?>  
                                                           
                         <? for( $j = 0 ; $j<$count ; $j++ ){
                             echo $arr[$j]['fulltable']; 
                             $arr[$j]['fulltable'] = '';                           
                         } 
                         
                        
                         $count = 0 ;
                        ?>
                                                                            
                    </tbody>
                    <tfoot>
                   
                        <tr style="width:200px" >
                            <td  align="left" ><div><label>รวมสินทรัพย์</label></div></td>
                            <td  align="right" align="right" style="border-top: 1px solid black; border-bottom: 3px double black ; "><?php echo number_format($sumAll, 2); ?></td>
                            <td  align="right" ><?php echo number_format( ($sumAll/$acc_val)*100 ,2).'%'; ?></td> 
                        </tr>
                    </tfoot>
                </table>
                <div>
                    <br>
                    <br>
                    <br>
                </div>

                <!--------------------------------------------------------------------------------------------หนี้สิน--------------------------------------------------------------------->

                <div class="row">                  
                    <div align="center">
                        <label>หนี้สินเเละส่วนของผู้ถือหุ้น</label>
                    </div>
                </div>

                <table width="70%"  align="center">
                    <thead>
                        <tr>                       
                            <th width="250" ></th>
                            <th width="100" ></th>
                            <th width="15" ></th>                              
                        </tr>
                    </thead>
                   
                     <tbody>

                    <tr class="odd gradeX">                     
                            <td align="right" > หมายเหตุ </td> 
                            <td align="right">  หน่วย:บาท</td>
                            <td align="right"></td>                            
                    </tr>
                    <?php
                    $first = true ;
                    $val = 0 ;
                    $sumAll=0;
                    $acc_val = 0;
                    for($i=0; $i < count($journal_reports_debit); $i++){
                          $acc_val +=  $journal_reports_debit[$i]['account_value'] ;
                        }

                        for($i=0; $i < count($journal_reports_debit); $i++){
                            if($journal_reports_debit[$i]['account_group']== '2'){
                                                               
                                $sumAll +=  $journal_reports_debit[$i]['account_value'] ; 
                                $sumlevel += $journal_reports_debit[$i]['account_value'] ;
                                $val += $journal_reports_debit[$i]['account_value'];                                                                                                         
                                if($journal_reports_debit[$i]['account_level'] == '2'){ 
                                    
                                    $text_level2 =  ' <tr > <td > &emsp;<div><label> '.$journal_reports_debit[$i]['account_name_th'].'</label></div> </td> </tr>';
                                    $name_level2 =  $journal_reports_debit[$i]['account_name_th'] ;                                       
                                }  
                              
                                if( $journal_reports_debit[$i]['account_level'] == '3'){ $name_level3 = $journal_reports_debit[$i]['account_name_th'] ;  }                                                                            
                                                            
                                if( ($journal_reports_debit[$i+1]['account_level'] == '3' &&  $val != 0) || ($journal_reports_debit[$i+1]['account_level'] == '2' &  $val != 0)   ){

                                        if($val< 0 ){
                                            //$val*=-1;
                                            $text_tr .= '
                                        <tr class="odd gradeX">
                                        <td >&emsp;&emsp;&emsp;'. $name_level3.'</td> 
                                        <td align="right">('. number_format(abs($val),2).')</td>
                                        <td align="right">'. number_format(($val/$acc_val )*100,2).'%</td> 
                                        </tr>' ;

                                        }else{
                                        $text_tr .= '
                                        <tr class="odd gradeX">
                                        <td >&emsp;&emsp;&emsp;'. $name_level3.'</td> 
                                        <td align="right">'. number_format($val,2).'</td>
                                        <td align="right">'. number_format(($val/$acc_val)*100,2).'%</td> 
                                        </tr>' ;                                                                                      
                                        }
                                        if( $journal_reports_debit[$i+1]['account_level'] == '2' && $val != 0 ){ }else { $val = 0; }

                                        if( ($journal_reports_debit[$i+1]['account_level'] == '2' &&  $val != 0) || ($i+1 == count($journal_reports_debit) && $val != 0)  )  { 
                                            $arr [$count]['fulltable'] .= $text_level2.$text_tr.'<tr style="width:100px"><td  align="left"  >&emsp;&emsp;&emsp;&emsp;&emsp;รวม'.$name_level2.'</td><td  align="right" style="border-top:1px solid" >'.number_format(abs($sumlevel),2).'</td><td  align="right" >'. number_format(abs(($sumlevel/$acc_val))*100,2).'%</td> 
                                        </tr>
                                        ';
                                        
                                            $count++;
                                            $text_tr='';  
                                            $sumlevel = 0 ;   
                                            $val = 0;                    
                                        }                                                                                                     
                                }

                            }else{

                                $val += $journal_reports_debit[$i]['account_value'];
                                $sumAll += $journal_reports_debit[$i]['account_value'];
                                $sumlevel += $val;
                                if( ($journal_reports_debit[$i]['account_level'] == '2' && $val)  ){
                                    //echo $sumlevel.'<br>';
                                    if($val<0){
                                        
                                        $text_tr .= '
                                        <tr class="odd gradeX">
                                        <td >&emsp;&emsp;&emsp;'.$journal_reports_debit[$i]['account_name_th'].'</td> 
                                        <td align="right">('. number_format( abs($val),2).')</td>
                                        <td align="right">'.number_format($val/$acc_val*100,2 ).'%</td> 
                                        </tr>' ; 
                                     }else{
                                        $text_tr .= '
                                        <tr class="odd gradeX">
                                        <td >&emsp;&emsp;&emsp;'.$journal_reports_debit[$i]['account_name_th'].'</td> 
                                        <td align="right">'. number_format($val,2).'</td>
                                        <td align="right">'. number_format(($val/$acc_val)*100,2).'%</td> 
                                        </tr>' ; 
                                     }
                                    $val=0;
                                }

                                if($first){

                                     $arr [$count]['fulltable'] .= 
                                     '<tr style="width:200px" >
                                        <td  align="left" ><div><label> รวมหนี้สิน </label></div></td>
                                        <td  align="right" align="right" style="border-top: 1px solid black; border-bottom: 1px solid black ; ">'. number_format(abs($sumAll), 2).'</td>
                                        <td  align="right" >'.number_format($sumAll/$acc_val*100 ,2).'%</td> 
                                     </tr><div></div>' ;
                                    $count++ ;
                                    $first = false ;
                                    $arr [$count]['fulltable'] .=' <tr > <td > &emsp;<div><label> ส่วนของผู้ถือหุ้น</label></div> </td> </tr>'; 
                                    $count++ ;
                                    
                                }

                                if( $i+1 == count($journal_reports_debit) ){
                                    $arr [$count]['fulltable'] .= $text_tr.'<tr style="width:100px"><td  align="left"  >&emsp;&emsp;&emsp;&emsp;&emsp;รวมส่วนของผู้ถือหุ้น</td><td  align="right" style="border-top:1px solid" >'.number_format(abs($sumlevel),2).'</td><td  align="right" >'.number_format($sumlevel/$acc_val*100,2).'%</td>';
                                    $count++ ;
                                    $text_tr = '' ;
                                }
                            }                                                             
                    } 

                     for( $j = 0 ; $j<$count ; $j++ ){
                        echo $arr[$j]['fulltable']; 
                        $arr[$j]['fulltable'] = '';                           
                    } 
      
                     ?>       
                    </tbody>
                    <tfoot>  
                    <tr>
                    <td><div><br></div></td> 
                    </tr>           
                        <tr style="width:200px" >
                            <td  align="left" ><div><label> รวมหนี้สินเเละส่วนของผู้ถือหุ้น </label></div></td>
                            <td  align="right" align="right" style="border-top: 1px solid black; border-bottom: 3px double black ; "><?php echo number_format(abs($sumAll), 2); ?></td>
                            <td  align="right" ><?php echo number_format(($sumAll/$acc_val)*100,2).'%'; ?></td> 
                         
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
            
            
