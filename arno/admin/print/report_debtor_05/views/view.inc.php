<?PHP 


$total_total = 0;
$paid_total = 0;
$balance_total = 0;

$i = 0;
$page_index=0;
$line = 0;

while($i < count($debtor_reports)){

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
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานลูกหนี้คงค้าง </b></div>
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
                <th style="border-top: 1px dotted black;" width="48" align="center" > ลำดับ </th>
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" colspan="2" align="center" >ใบกำกับภาษี </th> 
                <th style="border-top: 1px dotted black;" align="center" >จำนวนเงินรวม</th>  
                <th style="border-top: 1px dotted black;" align="center" >ยอดรับจริง</th>  
                <th style="border-top: 1px dotted black;" align="center" >ยอดหนี้คงเหลือ</th>  
            </tr>
            <tr> 
                <th style="border-bottom: 1px dotted black;" ></th>
                <th style="border-bottom: 1px dotted black;" width="80" align="center" >วัน/เดือน/ปี</th>
                <th style="border-bottom: 1px dotted black;" align="center" >เลขที่ </th>  
                <th style="border-bottom: 1px dotted black;" align="center" ></th>  
                <th style="border-bottom: 1px dotted black;" align="center" ></th>  
                <th style="border-bottom: 1px dotted black;" align="center" ></th>  
            </tr>
        </thead>

        <tbody>

    ';
 
    
    //count($debtor_reports)
    $total_page = 0; 
    $paid_page = 0;
    $balance_page = 0;
    for(; $i < count($debtor_reports); $i++){

        if( $debtor_reports[$i-1]['customer_code'] != $debtor_reports[$i]['customer_code']){ 
            $index = 0;
            $invoice_customer_net_price = 0; 
            $finance_debit_list_paid = 0;  
            $invoice_customer_balance = 0;
            $branch = (int)$debtor_reports[$i]['billing_note_branch'];
            if($branch == 0){
                $branch_main = "/";
                $branch_sub = "";
            }else{
                $branch_main = "";
                $branch_sub = $branch;
            }
            $html[$page_index] .= '
                <tr class="">
                    <td colspan="6" >
                        <b>['. $debtor_reports[$i]['customer_code'].'] '.$debtor_reports[$i]['billing_note_name'].'</b>
                    </td> 
                </tr>
            ';
            $line ++;
            if($line % $lines == 0){
                $i++;
                break;
            }
        }

        $invoice_customer_net_price +=  $debtor_reports[$i]['invoice_customer_net_price']; 
        $finance_debit_list_paid +=  $debtor_reports[$i]['finance_debit_list_paid'];  
        $invoice_customer_balance +=  $debtor_reports[$i]['invoice_customer_balance'];  

        $index ++;

        $total_page +=  $debtor_reports[$i]['invoice_customer_net_price'];  
        $paid_page +=  $debtor_reports[$i]['finance_debit_list_paid']; 
        $balance_page +=  $debtor_reports[$i]['invoice_customer_balance']; 

        $total_total +=  $debtor_reports[$i]['invoice_customer_net_price'];  
        $paid_total +=  $debtor_reports[$i]['finance_debit_list_paid']; 
        $balance_total +=  $debtor_reports[$i]['invoice_customer_balance']; 


        $html[$page_index] .= ' 
        <tr>
            <td align="center" >'.($index).'</td>
            <td align="left" >'.$debtor_reports[$i]['invoice_customer_date'].'</td>
            <td>'.$debtor_reports[$i]['invoice_customer_code'].'</td> 
            <td  align="right" >
                '.number_format($debtor_reports[$i]['invoice_customer_net_price'],2).'
            </td> 
            <td  align="right" >
                '.number_format($debtor_reports[$i]['finance_debit_list_paid'],2).'
            </td>  
            <td  align="right" >
                '.number_format($debtor_reports[$i]['invoice_customer_balance'],2).'
            </td>  
        </tr> 
        ';

        $line ++;
        if($line % $lines == 0){
            $i++;
            break;
        }

        if($debtor_reports[$i]['customer_code'] != $debtor_reports[$i+1]['customer_code']){  
            $html[$page_index] .= ' <tr class="">
                <td></td>
                <td colspan="4" align="left" >
                    <b><font color="black"> ยอดรวมของ '. $debtor_reports[$i]['billing_note_name'].' จำนวน '. number_format($index,0) .' ใบ</font> </b>
                </td>  
                <td  align="right" style="border-top: 1px dotted black; " >
                    <b>'. number_format($invoice_customer_balance,2).'</b>
                </td>  
            </tr>';

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

    if($i < count($debtor_reports)){
        /*
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td colspan="2" align="left"> <b>รวมแต่ละหน้า</b> </td> 
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($total_page,2).'</td> 
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($paid_page,2).'</td> 
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($balance_page,2).'</td> 
                    </tr>
                </tfoot>
            </table>
        ';
        */
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
                        <td></td>
                        <td colspan="2" align="left"><div><b>รวมทั้งสิ้น ถึง</b> '.$date_end.' </div> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($total_total,2).'</td>  
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($paid_total,2).'</td>   
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($balance_total,2).'</td>   
                    </tr>
                </tfoot>
            </table>
        ';
    }else{
        /*
        <tr>
            <td></td>
            <td colspan="2" align="left"> <b>รวมแต่ละหน้า</b> </td>
            <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($total_page,2).'</td>  
            <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($paid_page,2).'</td> 
            <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($balancec_page,2).'</td> 
        </tr>
        <tr>
            <td colspan="6" align="center"> </td>
        </tr>
        */
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