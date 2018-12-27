<?PHP 


$total_total = 0;
//echo $page_max ;

for($page_index=0 ; $page_index < $page_max ; $page_index++){

    $html[$page_index] = '<style>
        div{
            font-size:10px;
        }
        .table, .table thead th, .table tbody td{
            border: 1px solid black;
        }

        th{
            padding:4px 4px;
            font-size:10px;
        }

        td{
            padding:2px 4px;
            font-size:10px;
        }

    </style>';

    $html[$page_index] .= '
    <table width="100%">
        <tr>
            <td>
                <div> </div>
            </td>
            <td align="left"  align="left" width="120px" >
                
            </td>
        </tr>
    </table>
    <div align="center" style="font-size:14px;color:#00F;"> <b>งบเเสดงสถานะการเงิน</b></div>
    <table width="100%" border="0" cellspacing="0">
        <tr>
            <td align="left" width="140px" ><b>ชื่อสถานประกอบการ </b></td>
            <td> '.$company['company_name_th'].'</td>
            <td align="left"  align="left" width="120px" ><b>หน้า</b> : '.($page_index + 1).' / '.$page_max.'</td>
        </tr>
        <tr>
            <td align="left" ><b>ที่อยู่สถานประกอบการ</b> </td>
            <td> '.$company['company_address_1'].' '.$company['company_address_2'].' '.$company['company_address_3'].'</td>
            <td ></td>
        </tr> 
        <tr>
            <td align="left" ><b>เลขประจำตัวผู้เสียภาษีอาการ</b> </td>
            <td> '.$company['company_tax'].' <b>สำนักงาน</b> '.$company['company_branch'].' </td>
            <td >  </td>
        </tr>
        <tr>
        <td align="left" ><b>ณ. วันที่</b> </td>
        <td> '.$date_end.' </td>
        <td >  </td>
    </tr>
    </table>  
    ';
    ////-----------------------------------------------------------------------------------------------สินทรัพย์------------------------------------------------------------//

    $html[$page_index] .= '
                <div><br></div>
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

    ';
  
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
                          }

                          for( $j = 0 ; $j<$count ; $j++ ){

                            $html[$page_index] .= $arr[$j]['fulltable']; 
                            $arr[$j]['fulltable'] = '';                           
                        } 
                        
                       
                        $count = 0 ;

                                    
                    $total_page = 0;
                  //  for($i=$page_index * $lines; $i < count($journal_reports) && $i < $page_index * $lines + $lines; $i++){}
                                                                            
                    $html[$page_index] .= ' 
                            </tbody>
                                    <tfoot>
                                        <tr style="width:200px" >
                                            <td  align="left" ><div><label>รวมสินทรัพย์</label></div></td>
                                            <td  align="right" align="right" style="border-top: 1px solid black; border-bottom: 3px double black ; ">'.number_format($sumAll, 2).'</td>
                                            <td  align="right" >'.number_format( ($sumAll/$acc_val)*100 ,2).'%</td> 
                                        </tr>
                                    </tfoot>
                                </table>
                                <div>
                                    <br>
                                    <br>
                                </div>' ;

 //-------------------------------------------------------------------------------------หนี้สินเเละผู้ถือหุ้น-------------------------------------------------------------------------------------------//


                    $html[$page_index] .= '
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
                                    </tr>'; 
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
                                        $html[$page_index] .= $arr[$j]['fulltable']; 
                                        $arr[$j]['fulltable'] = '';                           
                                    } 

                                    $html[$page_index] .=' 
                                    </tbody>
                                    <tfoot>  
                                    <tr>
                                    <td><div><br></div></td> 
                                    </tr>           
                                        <tr style="width:200px" >
                                            <td  align="left" ><div><label> รวมหนี้สินเเละส่วนของผู้ถือหุ้น </label></div></td>
                                            <td  align="right" align="right" style="border-top: 1px solid black; border-bottom: 3px double black ; ">'. number_format(abs($sumAll), 2).'</td>
                                            <td  align="right" >'.number_format(($sumAll/$acc_val)*100,2).'</td> 
                                         
                                        </tr>
                                    </tfoot>
                                </table>    
                            ';


}

?>