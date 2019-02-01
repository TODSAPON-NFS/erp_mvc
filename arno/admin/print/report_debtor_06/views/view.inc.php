<?PHP 


$debit_before = 0; 
$debit_invoice = 0;  
$debit_debit = 0;  
$debit_credit = 0; 
$debit_reciept = 0;  
$debit_balance = 0;  

$i = 0;
$page_index=0;
$line = 0;

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
            padding:4px 4px;
            font-size:10px;
        }
        body{
            font-family:  "tahoma";  
        }

    </style>';

    $html[$page_index] .= '
    <table width="100%">
        <tr>
            <td>
                <div><b>ถึง</b> '.$date_end.' </div>
            </td>
            <td align="left"  align="left" width="120px" >
            <b>หน้า</b> : '.($page_index + 1).'
            </td>
        </tr>
    </table>
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานสถานะลูกหนี้ </b></div>
    <table width="100%" border="0" cellspacing="0">
        <tr>
            <td align="left" width="140px" ><b>ชื่อสถานประกอบการ </b></td>
            <td> '.$company['company_name_th'].'</td>
            <td align="left"  align="left" width="120px" > พิมพ์ '.$datePrint.'</td>
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
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" width="48" align="center" > ลำดับ </th>
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="center" >ลูกค้า </th> 
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="center" >ยอดหนี้ยกมา</th>  
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="center" >ยอดขาย</th>  
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="center" >เพิ่มหนี้</th>  
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="center" >ลดหนี้/รับคืน</th>  
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="center" >รับชำระหนี้</th>  
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="center" >ยอดหนี้ยกไป</th>  
            </tr> 
        </thead>

        <tbody>

    ';
 
    
    //count($debtor_reports)
    $debit_before_page = 0; 
    $debit_invoice_page = 0;  
    $debit_debit_page = 0;  
    $debit_credit_page = 0; 
    $debit_reciept_page = 0;  
    $debit_balance_page = 0;  
    for($i=$page_index * $lines; $i < count($debtor_reports) && $i < $page_index * $lines + $lines; $i++){
        $debit_before +=  $debtor_reports[$i]['debit_before']; 
        $debit_invoice +=  $debtor_reports[$i]['debit_invoice'];  
        $debit_debit +=  $debtor_reports[$i]['debit_debit'];  
        $debit_credit +=  $debtor_reports[$i]['debit_credit']; 
        $debit_reciept +=  $debtor_reports[$i]['debit_reciept'];  
        $debit_balance +=  $debtor_reports[$i]['debit_balance'];  


        $debit_before_page +=  $debtor_reports[$i]['debit_before']; 
        $debit_invoice_page +=  $debtor_reports[$i]['debit_invoice'];  
        $debit_debit_page +=  $debtor_reports[$i]['debit_debit'];  
        $debit_credit_page +=  $debtor_reports[$i]['debit_credit']; 
        $debit_reciept_page +=  $debtor_reports[$i]['debit_reciept'];  
        $debit_balance_page +=  $debtor_reports[$i]['debit_balance'];  


        $html[$page_index] .= ' 
        <tr>
            <td align="center" >'.($i + 1).'</td>
            <td align="left" >['.$debtor_reports[$i]['customer_code'].'] '.$debtor_reports[$i]['customer_name_en'].'</td> 
            <td  align="right" >
                '.number_format($debtor_reports[$i]['debit_before'],2).'
            </td> 
            <td  align="right" >
                '.number_format($debtor_reports[$i]['debit_invoice'],2).'
            </td>  
            <td  align="right" >
                '.number_format($debtor_reports[$i]['debit_debit'],2).'
            </td>  
            <td  align="right" >
                '.number_format($debtor_reports[$i]['debit_credit'],2).'
            </td>  
            <td  align="right" >
                '.number_format($debtor_reports[$i]['debit_reciept'],2).'
            </td>  
            <td  align="right" >
                '.number_format($debtor_reports[$i]['debit_balance'],2).'
            </td>  
        </tr> 
        '; 
    }

    if($page_index+1 < $page_max ){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td  align="left"> <b>รวมแต่ละหน้า</b> </td> 
                        <td style="border-top: 1px dotted black" align="right" ><b>'.number_format($debit_before_page,0).'</b></td> 
                        <td style="border-top: 1px dotted black" align="right" ><b>'.number_format($debit_invoice_page,2).'</b></td>  
                        <td style="border-top: 1px dotted black" align="right" ><b>'.number_format($debit_debit_page,2).'</b></td>  
                        <td style="border-top: 1px dotted black" align="right" ><b>'.number_format($debit_credit_page,2).'</b></td>  
                        <td style="border-top: 1px dotted black" align="right" ><b>'.number_format($debit_reciept_page,2).'</b></td>  
                        <td style="border-top: 1px dotted black" align="right" ><b>'.number_format($debit_balance_page,2).'</b></td>  
                    </tr>
                </tfoot>
            </table>
        ';
    }else if($page_index == 0){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>  
                    <tr>
                        <td></td>
                        <td  align="left"><div><b>รวมทั้งสิ้นจากวันที่</b> '.$date_end.' <b> ถึง</b> '.$date_end.' </div> </td>
                        <td style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right" ><b>'.number_format($debit_before,0).'</b></td> 
                        <td style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right" ><b>'.number_format($debit_invoice,2).'</b></td>  
                        <td style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right" ><b>'.number_format($debit_debit,2).'</b></td>  
                        <td style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right" ><b>'.number_format($debit_credit,2).'</b></td>  
                        <td style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right" ><b>'.number_format($debit_reciept,2).'</b></td>  
                        <td style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right" ><b>'.number_format($debit_balance,2).'</b></td>
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
                        <td  align="left"> <b>รวมแต่ละหน้า</b> </td> 
                        <td style="border-top: 1px dotted black" align="right" ><b>'.number_format($debit_before_page,0).'</b></td> 
                        <td style="border-top: 1px dotted black" align="right" ><b>'.number_format($debit_invoice_page,2).'</b></td>  
                        <td style="border-top: 1px dotted black" align="right" ><b>'.number_format($debit_debit_page,2).'</b></td>  
                        <td style="border-top: 1px dotted black" align="right" ><b>'.number_format($debit_credit_page,2).'</b></td>  
                        <td style="border-top: 1px dotted black" align="right" ><b>'.number_format($debit_reciept_page,2).'</b></td>  
                        <td style="border-top: 1px dotted black" align="right" ><b>'.number_format($debit_balance_page,2).'</b></td>
                    </tr>
                    <tr>
                        <td colspan="8" align="center"> </td>
                    </tr>
                    <tr> 
                        <td></td>
                        <td  align="left"><div><b>รวมทั้งสิ้นจากวันที่</b> '.$date_end.' <b> ถึง</b> '.$date_end.' </div> </td>
                        <td style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right" ><b>'.number_format($debit_before,0).'</b></td> 
                        <td style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right" ><b>'.number_format($debit_invoice,2).'</b></td>  
                        <td style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right" ><b>'.number_format($debit_debit,2).'</b></td>  
                        <td style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right" ><b>'.number_format($debit_credit,2).'</b></td>  
                        <td style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right" ><b>'.number_format($debit_reciept,2).'</b></td>  
                        <td style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right" ><b>'.number_format($debit_balance,2).'</b></td>
                    </tr>
                </tfoot>
            </table>
        ';
    } 
} 

?>