<?PHP 


$journal_debit = 0;
$journal_credit = 0; 

$balance = 0;

$journal_debit_sum = 0;
$journal_credit_sum = 0; 

$i = 0;
$page_index=0;
$line = 0;

while($i < count($journal_reports)){

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
            padding:4px 4px;
            font-size:10px;
        }

    </style>';

    $html[$page_index] .= '
    <table width="100%">
        <tr>
            <td>
                <div><b>ถึง</b> '.$date_end.' </div>
            </td>
            <td align="left"  align="left" width="120px" >
                
            </td>
        </tr>
    </table>
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานบัญชีแยกประเภท</b></div>
    <table width="100%" border="0" cellspacing="0">
        <tr>
            <td align="left" width="140px" ><b>ชื่อสถานประกอบการ </b></td>
            <td> '.$company['company_name_th'].'</td>
            <td align="left"  align="left" width="120px" ><b>หน้า</b> : '.($page_index + 1).'</td>
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
    <table  width="100%" cellspacing="0" style="font-size:12px;">
        <thead>
            <tr>
                <th width="80" >วันที่</th> 
                <th width="80" >เลขที่เอกสาร</th>  
                <th >คำอธิบาย</th>
                <th width="80" >เดบิต</th>
                <th width="80" >เครดิต</th>  
                <th width="80" >คงเหลือ</th>  
            </tr>
        </thead>

        <tbody>

    ';
 
     
    for(; $i < count($journal_reports); $i++){

        if( $journal_reports[$i-1]['account_code'] != $journal_reports[$i]['account_code']){ 
            if($journal_reports[$i]['account_id'] != ""){
                $balance1 =  $journal_report_model->getJournalAcountBalanceBy($date_start,$journal_reports[$i]['account_id'] );
            }else{
                $balance1 = 0;
            }
            
            $balance = $balance1 + $journal_reports[$i]['account_debit_begin'] - $journal_reports[$i]['account_credit_begin'];
            
            $html[$page_index] .= '
                <tr class="">
                    <td colspan="6" >
                    </td>
                </tr>
                <tr class="">
                    <td colspan="4" >
                        <b>'.$journal_reports[$i]['account_code'].'</b> '.$journal_reports[$i]['account_name_th'].'
                    </td> 
                    <td align="right">
                        <b><font color="black">ยอดยกมา</font></b>
                    </td> 
                    <td align="right">
                        <font color="black"> '.number_format($balance,2).' </font>
                    </td>
                </tr>
            ';
            $line ++;
            if($line % $lines == 0){
                $i++;
                break;
            }
        }

       
        $index ++;
        $journal_debit +=  $journal_reports[$i]['journal_debit'];
        $journal_credit +=  $journal_reports[$i]['journal_credit'];
        $balance = $balance + ($journal_reports[$i]['journal_debit'] - $journal_reports[$i]['journal_credit']);


        $html[$page_index] .= ' 
        <tr>
            <td align="center" >'.$journal_reports[$i]['journal_date'].'</td>
            <td align="left" >'.$journal_reports[$i]['journal_code'].'</td>
            <td>'.$journal_reports[$i]['journal_list_name'].'</td> 
            <td  align="right" >
                '.number_format($journal_reports[$i]['journal_debit'],2).'
            </td> 
            <td  align="right" >
                '.number_format($journal_reports[$i]['journal_credit'],2).'
            </td>  
            <td  align="right" >
                '.number_format($balance,2).'
            </td>  
        </tr> 
        ';

        $line ++;
        if($line % $lines == 0){
            $i++;
            break;
        }

        if($journal_reports[$i]['account_code'] != $journal_reports[$i+1]['account_code']){ 

            $journal_debit_sum += $journal_debit;
            $journal_credit_sum +=  $journal_credit; 

            $html[$page_index] .= ' 
                <tr class="">
                    <td colspan="3" align="center" >
                    <b><font color="black"> ยอดคงเหลือ</font> </b>
                    </td> 
                    <td style="border-top: 1px dotted black;" align="right"><b><font color="black">'. number_format($journal_debit,2).' </font></b> </td>
                    <td style="border-top: 1px dotted black;" align="right"><b><font color="black">'. number_format($journal_credit,2).' </font></b> </td> 
                    <td style="border-top: 1px dotted black;" align="right" >
                    <b><font color="black"></font></b>
                    </td>
                </tr>
            ';

            $journal_debit = 0;
            $journal_credit = 0;

            $line ++;
            if($line % $lines == 0){
                $i++;
                break;
            }

            $html[$page_index] .= '<tr>
                <td colspan="6" align="center" ></td>
            </tr>'; 

            $line ++;
            if($line % $lines == 0){
                $i++;
                break;
            }
        } 
    }

    if($i < count($journal_reports)){ 
        $html[$page_index] .= ' 
                </tbody>
                <tfoot> 
                </tfoot>
            </table>
            ';
    }else if($page_index == 0){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>  
                    <tr>
                        <td colspan="3" align="center">รวม</td>
                        <td  align="right" >'. number_format($journal_debit_sum,2).'</td>
                        <td  align="right" >'. number_format($journal_credit_sum,2).'</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        ';
    }else{ 
        $html[$page_index] .= ' 
                </tbody>
                <tfoot> 
                    <tr>
                        <td></td>
                        <td colspan="2" align="left"><div><b>รวมทั้งสิ้น ถึง</b> '.$date_end.' </div> </td> 
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($total_total,2).'</td>  
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($paid_total,2).'</td>  
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($balance_total,2).'</td>  
                    </tr>
                </tfoot>
            </table>
        ';
    }

    $page_index++;

}

$page_max = $page_index;

?>