<?PHP 


$total_total = 0;
$total_credit = 0;
$total_debit = 0;
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
                <div><b>เดือน/ปี</b> '.$section_date.' (<b>เริ่มจาก</b> '.$date_start.' <b>ถึง</b> '.$date_end.') </div>
            </td>
            <td align="left"  align="left" width="120px" >
                
            </td>
        </tr>
    </table>
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานงบทดลอง</b></div>
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
    </table>  
    ';

    $html[$page_index] .= '
    <table width="100%"  cellspacing="0" >
    <thead>
        <tr>
            <th width="48"  style="border-top:1px solid black;border-bottom: 1px solid black;">ลำดับ</th> 
            <th width="100" style="border-top:1px solid black;border-bottom: 1px solid black;">เลขที่บัญชี</th>
            <th width="150" style="border-top:1px solid black;border-bottom: 1px solid black;">ชื่อบัญชี</th>
            <th width="150" style="border-top:1px solid black;border-bottom: 1px solid black;">เดบิต</th>
            <th width="150" style="border-top:1px solid black;border-bottom: 1px solid black;">เครดิต</th>   
            
        </tr>
    </thead>
        <tbody>

    ';

    
    //count($journal_reports)
    $total_page = 0;
    $total_credit_page = 0;
    $total_debit_page = 0;
    for($i=$page_index * $lines; $i < count($journal_reports) && $i < $page_index * $lines + $lines; $i++){
    
        
        

    $journal_debit = 0;
    $journal_credit = 0;
    $debit='';
    $credit='';
            if($journal_reports[$i]['account_value'] < 0){
                $journal_credit = abs($journal_reports[$i]['account_value']);
                $journal_credit_sum += $journal_credit;
               
            }else{
                 $journal_debit = abs($journal_reports[$i]['account_value']);
                 $journal_debit_sum += $journal_debit;
                 
            }

        $total_credit_page += $journal_credit;    
        $total_debit_page +=  $journal_debit;
        


        $total_credit = $journal_credit_sum ;    
        $total_debit =  $journal_debit_sum; 

        
      

            if (number_format($journal_debit,2) != 0 ){$debit=number_format($journal_debit,2);}           
            if (number_format($journal_credit,2) != 0 ){$credit= number_format($journal_credit,2);}
                $html[$page_index] .= ' 
                <tr >
                            <td align="center" >'.number_format($i + 1,0).'</td>
                            <td>'.$journal_reports[$i]['account_code'].'</td>
                            <td>'. $journal_reports[$i]['account_name_th'].'</td> 
                            <td align="right"> '.$debit.' </td>
                            <td align="right">'.$credit.'</td> 
                            
                        </tr>
                ';

                
    }

    if($page_index+1 < $page_max){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                        <td   style="border-top:1px solid black;border-bottom: 1px solid black;"></td>
                        <td  align="left"  style="border-top:1px solid black;border-bottom: 1px solid black;"> <b>รวมแต่ละหน้า</b> </td>
                        <td  align="right"  style="border-top:1px solid black;border-bottom: 1px solid black;" ></td>                     
                        <td  align="right"  style="border-top:1px solid black;border-bottom: 1px solid black;" >'.number_format($total_debit_page,2).'</td> 
                        <td  align="right"  style="border-top:1px solid black;border-bottom: 1px solid black;">'.number_format($total_credit_page,2).'</td>
                    </tr>
                </tfoot>
            </table>
        ';
    }else if($page_index == 0){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>  
                    <tr>
                        <td style="border-top:1px solid black;border-bottom: 1px solid black;"></td>
                        <td  align="left"><div><b>รวมทั้งสิ้น งวด </b> '.$section_date.' (<b>เริ่มจาก</b> '.$date_start.' <b>ถึง</b> '.$date_end.') </div> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" ></td>
                        
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($total_debit,2).'</td> 
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($total_credit,2).'</td>
                    </tr>
                </tfoot>
            </table>
        ';
    }else{
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                        <td  style="border-top:1px solid black;border-bottom: 1px dotted black;"></td>
                        <td  align="left"   style="border-top:1px solid black;border-bottom: 1px dotted black;"><b>รวมแต่ละหน้า</b> </td>
                        <td  align="right"  style="border-top:1px solid black;border-bottom: 1px dotted black;" ></td>
                       
                        <td  align="right"  style="border-top:1px solid black;border-bottom: 1px dotted black;" >'.number_format($total_debit_page,2).'</td> 
                        <td  align="right"  style="border-top:1px solid black;border-bottom: 1px dotted black;" >'.number_format($total_credit_page,2).'</td>
                    </tr>
                    <tr>
                        <td colspan="11" align="center"> </td>
                    </tr>
                    <tr>
                        <td align="right"  style="border-bottom: 1px solid black;" > </td>
                        <td align="left" style="border-bottom: 1px solid black;" ><div><b>รวมทั้งหมด </b></div> </td>
                        <td style="border-bottom: 1px solid black;"  ></td>
                        
                        <td  align="right" style="border-bottom: 1px solid black;" >'.number_format($total_debit,2).'</td> 
                        <td  align="right" style="border-bottom: 1px solid black;" >'.number_format($total_credit,2).'</td>
                    </tr>
                </tfoot>
            </table>
        ';
    }

}

?>