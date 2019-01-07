<?PHP 


$total_total = 0;
//echo $page_max ;
$countcharges =0 ;

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
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานงบกำไรขาดทุน</b></div>
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
                <div class="row">                  
                    <div align="center">
                        <label>รายงานงบกำไรขาดทุน</label>
                    </div>
                </div>
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

    ';
                        if($page_index == 0 ){

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
                                $html[$page_index] .=  '
                                      <tr class="odd gradeX">
                                      <td >&emsp;&emsp;&emsp;'.$journal_reports_income[$i]['account_name_th'].' </td> 
                                      <td align="right"></td>
                                      <td align="right"></td> 
                                      <td align="right"> '.number_format(abs($value),2).'</td>
                                      <td align="right">'.(number_format(abs($value)/$sum *100,2)).'%; </td> 
                                   </tr> ';                                                               
                              }                                                                                                                                                                                                                                                     
                          }
                          if($sum!=0){ $getSum = number_format(abs($sum),2); }else{$getSum= number_format(0,2);}
                          if($sum!=0){ $getPer_Sum = number_format(abs($sum)/$sum*100,2); }else{$getPer_Sum= number_format(0,2);}
                          $html[$page_index] .=  ' 
                            <tr class="odd gradeX" style="height: 30px;">
                                <td align="left"  ><div> <label> รวมรายได้ </label> </div> </td>
                                <td align="right"></td>
                                <td align="right"></td> 
                                <td align="right" style="border-top:1px solid"> '.$getSum.' </td>
                                <td align="right" >  '.$getPer_Sum.' % </td> 
                            </tr>';
                                                                                        
                        }
 //-------------------------------------------------------------------------------------ค่าใช้จ่าย กำไร-------------------------------------------------------------------------------------------//

                $html[$page_index] .= '<tr class="odd gradeX" style="height: 40px;">
                <td align="left"><div><label>ค่าใช้าจ่าย</label></div></td>
                <td align="right"></td>
                <td align="right"></td> 
                <td align="right"></td>
                <td align="right"></td> 
                </tr>';

                $journal_credit = 0 ;
                    $journal_debit = 0;
                    //$sum = 0 ;
                    $sumAll = 0 ;
                    $sumCost = 0 ;
                    $value = 0 ;
                    $first = 0 ;
                    $sumexp = 0;
                    $countline = 0 ;
                    for($i=0; $i < count($journal_reports_charges); $i++){ 
                        $sumAll += $journal_reports_charges[$i]['journal_credit']- $journal_reports_charges[$i]['journal_debit'] ;
                    }
                    
                    for($i=0; $countcharges < count($journal_reports_charges) &&  ($countline + $countAss) <= $lines ; $i++){ 
                        
                        $journal_credit = $journal_reports_charges[$countcharges]['journal_credit'];
                        $journal_debit = $journal_reports_charges[$countcharges]['journal_debit'];
                        $value = ($journal_debit-$journal_credit);
                        $sumCost+=$value;
                        
                        if( $journal_reports_charges[$countcharges]['account_value'] != '0' && $value !=0 ){
                            if( $value > 0 ){ 
                                $html[$page_index] .='<tr class="odd gradeX">
                                <td >&emsp;&emsp;&emsp;'.$journal_reports_charges[$countcharges]['account_name_th'].' </td>                              
                                <td align="right">'.number_format( $value , 2).'</td>
                                <td align="right">'.(number_format($value/ abs($sum) *100 ,2)).'% </td> 
                                <td align="right"></td>
                                <td align="right"></td>
                             </tr> ';
                             $countline++;
                            }else{
                                $html[$page_index] .='<tr class="odd gradeX">
                                <td >&emsp;&emsp;&emsp;'.$journal_reports_charges[$countcharges]['account_name_th'].' </td>                              
                                <td align="right">'."(".number_format(abs($value),2).')</td>
                                <td align="right">'. (number_format($value/ abs($sum) *100 ,2)).'%</td> 
                                <td align="right"></td>
                                <td align="right"></td>
                             </tr> ';
                             $countline++;
                            }
                        }

                        if($journal_reports_charges[$countcharges]['account_level'] == '2'){ 
                            if($first==1 && $sum !=0) { 

                            
                                $html[$page_index] .='<tr class="odd gradeX" style="height: 30px;">
                                            <td align="left"  >&emsp;&emsp;</td>
                                            <td align="right"></td>
                                            <td align="right"></td> 
                                            <td align="right"> '.number_format(abs($sumCost),2).'  </td>
                                            <td align="right">'.number_format($sumCost/$sum*100,2).'  %</td> 
                                            
                                        </tr>

                                        <tr class="odd gradeX" style="height: 30px;">
                                            <td align="left"  >&emsp;&emsp;</td>
                                            <td align="right"></td>
                                            <td align="right"></td> 
                                            <td align="right" style="border-top:1px solid"> '.number_format(abs($sum-$sumCost),2).'</td>
                                            <td align="right" >'.number_format(($sum-$sumCost)/$sum*100,2).'%</td> 
                                            
                                        </tr>

                                        <tr class="odd gradeX" style="height: 40px;">
                                            <td align="left"><div><label>กำไรขั้นต้น</label></div></td>
                                            <td align="right"></td>
                                            <td align="right"></td> 
                                            <td align="right"></td>
                                            <td align="right"></td> 
                                        </tr>';

                                     $sumLucre = $sum-$sumCost; $sumCost = 0 ;
                                     $countline+=3;
                            }
                            $html[$page_index] .='<tr class="odd gradeX">
                                <td >&emsp;'.$journal_reports_charges[$countcharges]['account_name_th'].':</td> 
                                <td align="right"></td>
                                <td align="right"></td> 
                                <td align="right"></td>
                                <td align="right"></td> 
                            </tr> ';
                            $countline++;                          
                               
                       $first++;
                        }
                        $countcharges++;
                    }

                if(  $page_index+1 == $page_max ){
                    if($sum!=0){
                        $beforeCost = $sumLucre-$sumCost ; 
                        $cost = 0 ;
                        $before_income_tax  = $beforeCost - $cost ;
                        $income_tax = 0 ;
                        $net_profit = $before_income_tax-$income_tax ;

                    $html[$page_index] .= ' <tr class="odd gradeX">   
                                    <td align="left"  >&emsp;&emsp;&emsp;รวมค่าใช้จ่าย</td>  
                                    <td align="right"></td>
                                    <td align="right"></td>                                 
                                    <td align="right" style="border-top:1px solid">'.number_format(abs($sumCost),2).'</td>
                                    <td align="right" >'.number_format($sumCost/$sum*100,2).'%</td>                                                               
                                </tr>
                                <tr class="odd gradeX" >   
                                    <td align="left"  >&emsp;&emsp;กำไร (ขาดทุน) ก่อนต้นทุนทางการเงิน </td>  
                                    <td align="right"></td>
                                    <td align="right"></td>                                 
                                    <td align="right" style="border-top:1px solid">'.number_format(abs($beforeCost),2).'</td>
                                    <td align="right" > '.number_format(($beforeCost)/$sum*100,2).'%</td>                                                               
                                </tr>
                                <tr class="odd gradeX" >   
                                    <td align="left"  >&emsp;&emsp;ต้นทุนทางการเงิน </td>  
                                    <td align="right"></td>
                                    <td align="right"></td>                                 
                                    <td align="right"> '.number_format(abs($cost),2).' </td>
                                    <td align="right" >'.number_format(($cost)/$sum*100,2).'%</td>                                                               
                                </tr>
                                <tr class="odd gradeX" >   
                                    <td align="left"  >&emsp;&emsp;กำไร (ขาดทุน) ก่อนภาษีเงินได้ </td>  
                                    <td align="right"></td>
                                    <td align="right"></td>                                 
                                    <td align="right" style="border-top:1px solid"> '.number_format(abs($before_income_tax),2).'</td>
                                    <td align="right" > '.number_format(($before_income_tax)/$sum*100,2) .'%</td>                                                               
                                </tr>
                                <tr class="odd gradeX" >   
                                    <td align="left"  >&emsp;&emsp;ภาษีเงินได้ </td>  
                                    <td align="right"></td>
                                    <td align="right"></td>                                 
                                    <td align="right" style="border-top:1px solid"> '.number_format(abs($income_tax),2).' </td>
                                    <td align="right" > '.number_format(($income_tax)/$sum*100,2).'%</td>                                                               
                                </tr>


                                <tr class="odd gradeX" >   
                                    <td align="left"  >&emsp;&emsp;กำไร (ขาดทุน) สุทธิ </td>  
                                    <td align="right"></td>
                                    <td align="right"></td>                                 
                                    <td align="right" style="border-top:1px solid ;border-bottom: 3px double black ; ">  '.number_format(abs( $net_profit),2).'</td>
                                    <td align="right" > '.number_format(( $net_profit)/$sum*100,2) .'%</td>                                                               
                                </tr> ';
                    }                  
                
                }
                $html[$page_index] .= '<tbody>
                </table>';
}

?>