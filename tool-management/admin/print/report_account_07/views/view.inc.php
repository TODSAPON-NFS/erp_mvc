<?PHP 


$total_total = 0;
$total_credit = 0;
$cheque_total = 0;
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
                <div><b>เดือน/ปี</b> '.$section_date.' (<b>เริ่มจาก</b> '.$date_start.' <b>ถึง</b> '.$date_end.') </div>
            </td>
            <td align="left"  align="left" width="120px" >
                
            </td>
        </tr>
    </table>
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานเช็คจ่ายคงเหลือ เเยกตามเลขบัญชีเช็ค</b></div>
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
                
                <th align="center" width="35" style="border-top:1px dotted black;border-bottom: 1px dotted black;">วันที่จ่าย</th>          
                <th align="center" width="80" style="border-top:1px dotted black;border-bottom: 1px dotted black;">ลวท.</th>                                   
                <th align="center" width="60" style="border-top:1px dotted black;border-bottom: 1px dotted black;">เลขที่เช็ค</th>
                <th align="center" width="100" style="border-top:1px dotted black;border-bottom: 1px dotted black;">เงินหน้าเช็ค</th> 
                
                <th width="180" style="border-top:1px dotted black;border-bottom: 1px dotted black;">หมายเหตุ</th> 
                <th align="center" width="50" style="border-top:1px dotted black;border-bottom: 1px dotted black;"> ใบสำคัญ </th>
    
            </tr>
        </thead>
        <tbody>

    ';

    
    //count($journal_reports)
    $total_page = 0;
    $total_credit_page = 0;
    $cheque_total_page = 0;
    $count_pay = 0 ;
    for($i=$page_index * $lines; $i < count($journal_reports) && $i < $page_index * $lines + $lines; $i++){
    
        
   
                $html[$page_index] .= ' 
                <tr >
                    <td align="center">   '.$journal_reports[$i]['check_pay_date_write'].' </td>
                    <td align="center">   '.$journal_reports[$i]['check_pay_date'].' </td>
                    <td >   '. $journal_reports[$i]['cheque_code'].' </td>
                    <td align="right">   '. number_format($journal_reports[$i]['cheque_total'],2).' </td>
                    <td  style="padding-left: 50px" >   '.$journal_reports[$i]['journal_name'].' </td> 
                    <td >   '. $journal_reports[$i]['journal_code'].'</td>      
                                           
                </tr>
                ';
                $cheque_total_page += $journal_reports[$i]['cheque_total'];
                $cheque_total += $journal_reports[$i]['cheque_total'];
                $count_pay ++;
    }

    if($page_index+1 < $page_max){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                        <td><br></td><td></td><td></td><td></td><td></td><td></td>
                    </tr>
                </tr>
                    <tr>
                        <td colspan="11" align="center"> </td>
                    </tr>
                </tr>
                <tr>
                    <td  align="right" style="padding:20    px 20px;"><b>รวมบัญชี </d></td>
                    <td  align="left"  style="padding:20px 20px;" ><b>เช็คจ่ายล่วงหน้า </b></td>
                    <td  align="right"  ></td>             
                    <td  align="right" style="border-top: 2px  double ;border-bottom: 2px  double ;" >'.number_format($cheque_total_page,2).'</td> 
                    <td  ><div><b> ยอดเช็คลงบัญชีสุทธิ  &emsp; = </b>&emsp;'.number_format($cheque_total_page,2).'&emsp; ( จำนวนเช็ค </div></td> 
                    <td align="left"> '. count($count_pay) .'&nbsp; ใบ )  </td>
                </tr>
                </tfoot>
            </table>
        ';
    }else if($page_index == 0){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>  
                <tr>
                <td><br></td><td></td><td></td><td></td><td></td><td></td>
                </tr>             
                    <tr>
                        <td  align="right" style="padding:20    px 20px;"><b>รวมบัญชี </d></td>
                        <td  align="left"  style="padding:20px 20px;" ><b>เช็คจ่ายล่วงหน้า </b></td>
                        <td  align="right"  ></td>             
                        <td  align="right" style="border-top: 2px  double ;border-bottom: 2px  double ;" >'.number_format($cheque_total_page,2).'</td> 
                        <td  ><div><b> ยอดเช็คลงบัญชีสุทธิ  &emsp; = </b>&emsp;'.number_format($cheque_total_page,2).'&emsp; ( จำนวนเช็ค </div></td> 
                        <td align="left"> '. count($journal_reports) .'&nbsp; ใบ )  </td>
                    </tr>

                    <tr>
                        <td  align="right" style="padding:20    px 20px;"><b>รวมทั้งสิ้น </d></td>
                        <td  align="left"  style="padding:20px 20px;" ><b> </b></td>
                        <td  align="right"  ></td>             
                        <td  align="right" style="border-bottom: 2px  double ;" >'.number_format($cheque_total,2).'</td> 
                        <td  ><div><b> ยอดเช็คลงบัญชีสุทธิ  &emsp; = </b>&emsp;'.number_format($cheque_total,2).'&emsp; ( จำนวนเช็ค </div></td> 
                        <td align="left"> '. count($journal_reports) .'&nbsp; ใบ )  </td>
                    </tr>

                </tfoot>
            </table>
        ';
    }else{
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                    <td><br></td><td></td><td></td><td></td><td></td><td></td>
                    </tr>
                     <tr>
                        <td colspan="11" align="center"> </td>
                    </tr>
                    </tr>
                    <tr>
                        <td  align="right" style="padding:20    px 20px;"><b>รวมบัญชี </d></td>
                        <td  align="left"  style="padding:20px 20px;" ><b>เช็คจ่ายล่วงหน้า </b></td>
                        <td  align="right"  ></td>             
                        <td  align="right" style="border-top: 2px  double ;border-bottom: 2px  double ;" >'.number_format($cheque_total,2).'</td> 
                        <td  ><div><b> ยอดเช็คลงบัญชีสุทธิ  &emsp;&emsp; = </b>&emsp;'.number_format($cheque_total_page,2).'&emsp; (จำนวนเช็ค </div></td> 
                        <td align="left"> '. count($count_pay) .'&nbsp; ใบ)  </td>
                    </tr>

                    <tr>
                        <td colspan="11" align="center"> </td>
                    </tr>
                    </tr>
                    <tr>
                        <td  align="right" style="padding:20    px 20px;"><b>รวมบัญชี </d></td>
                        <td  align="left"  style="padding:20px 20px;" ><b>เช็คจ่ายล่วงหน้า </b></td>
                        <td  align="right"  ></td>             
                        <td  align="right" style="border-top: 2px  double ;border-bottom: 2px  double ;" >'.number_format($cheque_total,2).'</td> 
                        <td  ><div><b> ยอดเช็คลงบัญชีสุทธิ  &emsp;&emsp; = </b>&emsp;'.number_format($cheque_total_page,2).'&emsp; (จำนวนเช็ค </div></td> 
                        <td align="left"> '. count($journal_reports) .'&nbsp; ใบ)  </td>
                    </tr>
                </tfoot>
            </table>
        ';
    }

}

?>