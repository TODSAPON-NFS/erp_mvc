<?PHP 


$due_comming_more_than_60_page = 0;  
$due_comming_in_60_page = 0;  
$due_comming_in_30_page = 0;  
$over_due_1_to_30_page = 0;  
$over_due_31_to_60_page = 0;  
$over_due_61_to_90_page = 0;  
$over_due_more_than_90_page = 0;  
$balance_page = 0;  


$paper_number_total = 0;  
$due_comming_more_than_60_total = 0;  
$due_comming_in_60_total = 0;  
$due_comming_in_30_total = 0;  
$over_due_1_to_30_total = 0;  
$over_due_31_to_60_total = 0;  
$over_due_61_to_90_total = 0;  
$over_due_more_than_90_total = 0;  
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
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานวิเคราะห์อายุลูกหนี้ แบบละเอียด</b></div>
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
                <th style="border-top: 1px dotted black;" width="48" align="center" > ลำดับ </th>
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" colspan="4" align="center" >ใบกำกับภาษี </th> 
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="center" colspan="3" >จะครบกำหนด</th>  
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="center" colspan="4" >เกินกำหนด</th>  
                <th style="border-top: 1px dotted black;" align="center" >ยอดคงค้าง</th>  
            </tr>
            <tr> 
                <th style="border-bottom: 1px dotted black;" ></th>
                <th style="border-bottom: 1px dotted black;" width="80" align="center" >วัน/เดือน/ปี</th>
                <th style="border-bottom: 1px dotted black;" align="center" >เลขที่ </th>  
                <th style="border-bottom: 1px dotted black;" align="center" >วันครบกำหนด </th>  
                <th style="border-bottom: 1px dotted black;" align="center" >จำนวนวัน </th>  
                <th style="border-bottom: 1px dotted black;" align="center" >เกิน 60 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" >ภายใน 60 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" >ภายใน 30 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" >1 - 30 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" >31 - 60 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" >61 - 90 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" >เกิน 90 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" ></th>  
            </tr>
        </thead>

        <tbody>

    ';
 
     
    
    for(; $i < count($debtor_reports); $i++){

        if( $debtor_reports[$i-1]['customer_code'] != $debtor_reports[$i]['customer_code']){ 
            $due_comming_more_than_60_page = 0;  
            $due_comming_in_60_page = 0;  
            $due_comming_in_30_page = 0;  
            $over_due_1_to_30_page = 0;  
            $over_due_31_to_60_page = 0;  
            $over_due_61_to_90_page = 0;  
            $over_due_more_than_90_page = 0;  
            $balance_page = 0;  
            $index = 0;
             
            $html[$page_index] .= '
                <tr class="">
                    <td colspan="13" >
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

        $val = explode("-",$date_end); 
        $current_date_str = $val[2]."-".$val[1]."-".$val[0];

        $val = explode("-",$debtor_reports[$i]['invoice_customer_due']); 
        $due_date_str = $val[2]."-".$val[1]."-".$val[0];

        $current_date = strtotime($current_date_str);
        $due_date = strtotime($due_date_str);

        $datediff =  $due_date - $current_date;

        $diff_day = round($datediff / (60 * 60 * 24)); 

        //echo $papers[$ii]['customer_code'] . " ".$papers[$ii]['invoice_customer_code']. " ". $current_date . " ". $papers[$ii]['invoice_customer_due']." ".$diff_day."<br><br>";

        $due_comming_more_than_60  = 0;  
        $due_comming_in_60  = 0;  
        $due_comming_in_30  = 0;  
        $over_due_1_to_30  = 0;  
        $over_due_31_to_60  = 0;  
        $over_due_61_to_90  = 0;  
        $over_due_more_than_90  = 0;  

        if($diff_day > 60){
            $due_comming_more_than_60_page =  $debtor_reports[$i]['invoice_customer_balance']; 
            $due_comming_more_than_60_total +=  $debtor_reports[$i]['invoice_customer_balance'];  
        }else if($diff_day > 30){
            $due_comming_in_60 =  $debtor_reports[$i]['invoice_customer_balance'];  
            $due_comming_in_60_page +=  $debtor_reports[$i]['invoice_customer_balance'];  
            $due_comming_in_60_total +=  $debtor_reports[$i]['invoice_customer_balance'];
        }else if($diff_day > -1){
            $due_comming_in_30 =  $debtor_reports[$i]['invoice_customer_balance']; 
            $due_comming_in_30_page +=  $debtor_reports[$i]['invoice_customer_balance']; 
            $due_comming_in_30_total +=  $debtor_reports[$i]['invoice_customer_balance'];  
        }else if($diff_day > -31){
            $over_due_1_to_30 =  $debtor_reports[$i]['invoice_customer_balance'];  
            $over_due_1_to_30_page +=  $debtor_reports[$i]['invoice_customer_balance'];  
            $over_due_1_to_30_total +=  $debtor_reports[$i]['invoice_customer_balance'];  
        }else if($diff_day > -61){
            $over_due_31_to_60 =  $debtor_reports[$i]['invoice_customer_balance'];  
            $over_due_31_to_60_page +=  $debtor_reports[$i]['invoice_customer_balance'];  
            $over_due_31_to_60_total +=  $debtor_reports[$i]['invoice_customer_balance'];  
        }else if($diff_day > -91){
            $over_due_61_to_90 =  $debtor_reports[$i]['invoice_customer_balance']; 
            $over_due_61_to_90_page +=  $debtor_reports[$i]['invoice_customer_balance']; 
            $over_due_61_to_90_total +=  $debtor_reports[$i]['invoice_customer_balance'];
        }else{
            $over_due_more_than_90 =  $debtor_reports[$i]['invoice_customer_balance'];
            $over_due_more_than_90_page +=  $debtor_reports[$i]['invoice_customer_balance'];
            $over_due_more_than_90_total +=  $debtor_reports[$i]['invoice_customer_balance']; 
        }  

        $balance_page +=  $debtor_reports[$i]['invoice_customer_balance'];  
        $balance_total +=  $debtor_reports[$i]['invoice_customer_balance'];  
        $index ++; 
        $paper_number_total ++;

        $html[$page_index] .= ' 
        <tr >
            <td> '.$index.'</td>
            <td> '.$debtor_reports[$i]['invoice_customer_date'].'</td>
            <td> '.$debtor_reports[$i]['invoice_customer_code'].'</td>
            <td> '.$debtor_reports[$i]['invoice_customer_due'].'</td>
            <td  align="right" >
                 '.number_format($diff_day,0).'
            </td>
            <td  align="right" >
                 '.number_format($due_comming_more_than_60,2).'
            </td> 
            <td  align="right" >
                 '.number_format($due_comming_in_60,2).'
            </td>  
            <td  align="right" >
                 '.number_format($due_comming_in_30 ,2).'
            </td>  
            <td  align="right" >
                 '.number_format($over_due_1_to_30 ,2).'
            </td>  
            <td  align="right" >
                 '.number_format($over_due_31_to_60 ,2).'
            </td>  
            <td  align="right" >
                 '.number_format($over_due_61_to_90 ,2).'
            </td> 
            <td  align="right" >
                 '.number_format($over_due_more_than_90 ,2).'
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
                <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($due_comming_more_than_60_page,2).'</b></td>  
                <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($due_comming_in_60_page,2).'</b></td>  
                <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($due_comming_in_30_page,2).'</b></td>  
                <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($over_due_1_to_30_page,2).'</b></td>  
                <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($over_due_31_to_60_page,2).'</b></td>  
                <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($over_due_61_to_90_page,2).'</b></td>  
                <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($over_due_more_than_90_page,2).'</b></td>
                <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($balance_page,2).'</b></td> 
            </tr>';

            $line ++;
            if($line % $lines == 0){
                $i++;
                break;
            }

            $html[$page_index] .= '<tr>
                <td colspan="13" align="center" ></td>
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
                        <td colspan="4" align="left"><div><b>รวมทั้งสิ้น ถึง</b> '.$date_end.' </div> </td>
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($due_comming_more_than_60_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($due_comming_in_60_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($due_comming_in_30_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($over_due_1_to_30_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($over_due_31_to_60_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($over_due_61_to_90_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($over_due_more_than_90_total,2).'</b></td>
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($balance_total,2).'</b></td> 
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
                        <td colspan="4" align="left"><div><b>รวมทั้งสิ้น ถึง</b> '.$date_end.' </div> </td> 
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($due_comming_more_than_60_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($due_comming_in_60_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($due_comming_in_30_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($over_due_1_to_30_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($over_due_31_to_60_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($over_due_61_to_90_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($over_due_more_than_90_total,2).'</b></td>
                        <td style="border-top: 1px dotted black; "  align="right" ><b>'.number_format($balance_total,2).'</b></td> 
                    </tr>
                </tfoot>
            </table>
        ';
    }

    $page_index++;

}

$page_max = $page_index;

?>