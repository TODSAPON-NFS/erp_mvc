<?PHP 


$journal_debit = 0;
$journal_credit = 0; 
for($page_index=0 ; $page_index < $page_max ; $page_index++){

    $html[$page_index] = '<style>
        div{
            font-size:10px;
        }
        .table, .table thead th, .table tbody td{
            border: 1px solid black;
        }

        th{
            padding:8px 4px;
            font-size:10px;
            border-top: 1px dotted black;
            border-bottom: 1px dotted black;
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
                <div><b>เดือน/ปีภาษี</b> '.$section_date.' (<b>เริ่มจาก</b> '.$date_start.' <b>ถึง</b> '.$date_end.') </div>
            </td>
            <td align="left"  align="left" width="120px" >
                
            </td>
        </tr>
    </table>
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานสมุดรายวัน</b></div>
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
    <table  width="100%" cellspacing="0" style="font-size:12px;">
        <thead>
            <tr>
                <th width="100" >เลขที่บัญชี</th>
                <th >ชื่อบัญชี</th>
                <th >คำอธิบาย</th>
                <th width="150" >เดบิต</th>
                <th width="150" >เครดิต</th>  
                <th width="150" >สถานะ</th>
            </tr> 
        </thead>

        <tbody>

    ';
 
    for($i=$page_index * $lines; $i < count($journal_reports) && $i < $page_index * $lines + $lines; $i++){
        $journal_debit +=  $journal_reports[$i]['journal_debit'];
        $journal_credit +=  $journal_reports[$i]['journal_credit'];



        if( $journal_reports[$i-1]['journal_code'] != $journal_reports[$i]['journal_code']){                 
            $html[$page_index] .= ' 
            <tr>
                <td><b><font color="blue">'. $journal_reports[$i]['journal_date'].'</font></b></td>
                <td><b><font color="blue">'. $journal_reports[$i]['journal_code'].'</font></b></td>
                <td colspan="4" > '. $journal_reports[$i]['journal_name'] .' </td> 
            </tr>';
        } 
 
        $html[$page_index] .= ' 
        <tr>
            <td align="center">'. $journal_reports[$i]['account_code'].'</td>
            <td>'. $journal_reports[$i]['account_name'].'</td>
            <td>'. $journal_reports[$i]['journal_list_name'].'</td>
            <td align="right">'. number_format($journal_reports[$i]['journal_debit'],2).' </td>
            <td align="right">'. number_format($journal_reports[$i]['journal_credit'],2).'</td> 
            <td  align="center" ></td> 
        </tr> 
        '; 

        if($journal_reports[$i]['journal_code'] != $journal_reports[$i+1]['journal_code']){
        
            if(number_format($journal_debit,2) == number_format($journal_credit,2)){  
                $status = '<font color="green"><b>ยอดตรง</b></font>';
            } else {
                $status = '<font color="red"><b>ยอดไม่ตรง</b></font>';
            } 

            $html[$page_index] .= '
            <tr class="odd gradeX">
                <td colspan="3" align="right" >
                    <b> รวม </b>
                </td> 
                <td align="right" style="border-top: 1px dotted black;" ><b>'.number_format($journal_debit,2).'</b> </td>
                <td align="right" style="border-top: 1px dotted black;" ><b>'. number_format($journal_credit,2).'</b> </td> 
                <td  align="center" >'.$status.'</td>
            </tr>'; 
            $journal_debit = 0;
            $journal_credit = 0;
        }
    }

    $html[$page_index] .= ' 
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td colspan="3" align="left"> </td> 
                </tr>
            </tfoot>
        </table>
    ';
 

}

?>