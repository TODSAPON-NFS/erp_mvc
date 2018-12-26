<?PHP 


$total_total = 0;
for($page_index=0 ; $page_index < $page_max ; $page_index++){

    $html[$page_index] = '<style>
        div{
            font-size:10px;
        }
        .table, .table thead th, .table tbody td{
            border: 1px solid black;
        }

        th{
            padding:10px 9px;
            font-size:10px;
        }

        td{
            padding:5px 6px;
            font-size:10px;
        }

    </style>';

    $html[$page_index] .= '
    <table width="100%">
        <tr>
            <td>
                
            </td>
            <td align="left"  align="left" width="120px" >
                
            </td>
        </tr>
    </table>
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานยอดขาย</b></div>
    <table width="100%" border="0" cellspacing="0">
        <tr>
            <td align="left" width="160px" ><b>ชื่อสถานประกอบการ </b></td>
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
            <td align="left" ><b> เลขที่บัญชี </b> </td>
            <td>'.$journal_reports[0]['account_code'].'&nbsp;&nbsp;'.$journal_reports[0]['account_name_th'].' </td>
            <td >  </td>
        </tr>
        <tr>
            <td align="left" ><b>ณ. วันที่</b> </td>
            <td> '.$date_end.' </td>
            <td >  </td>
        </tr>


    </table>  
   
    ';

    $html[$page_index] .= ' 
    <table width="100%"  cellspacing="0" >
        <thead>
             <tr>
                
             <th width="100" >ชื่อพนักงาน</th> 
             <th width="120" >ลูกค้า</th>  
             <th >จำนวนเงิน</th>

            </tr>
        </thead>
        <tbody>

    ';

    for($i=0; $i < count($journal_reports); $i++){


        if($journal_reports[$i]['user_username'] == $journal_reports[$i]['user_username'] && $journal_reports[$i]['user_username'] != null) {

            $sum +=  $journal_reports[$i]['invoice_customer_net_price'];
       
   
                $html[$page_index] .= ' 
                
                <tr>
                <td>   '.$journal_reports[$i]['user_username'].' </td>
                <td>'. $journal_reports[$i]['customer_name_en'].' </td>
                <td align="right">   '.  number_format ($journal_reports[$i]['invoice_customer_net_price'],2).' </td>
                </tr>
                                
                <tr>
                ';
                $line ++;
                if($line % $lines == 0){
                    $i++;
                    break;
                }
        


        
        $index ++;

        
        if($journal_reports[$i]['user_username'] != $journal_reports[$i+1]['user_username']  && $journal_reports[$i]['user_username'] != null) {
            $sum_sum = $sum;



        $html[$page_index] .= ' 
                       
                     <tr class="">
                            <td colspan="3" >
                            </td>
                        </tr>
                                            <td colspan="2"  align="center">
                                                รวม
                                            </td>
                                            <td align="right">'.  number_format($sum_sum,2) .'
                                            </td>
                                                          
                                            <tr class="">
                                                   <td colspan="3" >
                                                   </td>
                                               </tr>
                                                               </tr>
                                                 
                                            ';
                                            $sum = 0;
                                        }

                        }

        $line ++;
        if($line % $lines == 0){
            $i++;
            break;
        }

}
    $page_index++;

    }

$page_max = $page_index;

?>