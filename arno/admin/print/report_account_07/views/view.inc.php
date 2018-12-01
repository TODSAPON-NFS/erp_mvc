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
            padding:5px 6px;
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
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานเช็คจ่ายคงเหลือ</b></div>
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

    $html[$page_index] .= ' <br>
    <table width="100%"  cellspacing="0" >
        <thead>
             <tr>
                
                <th align="center" width="50" style="border-top:1px solid black;border-bottom: 1px solid black;">วันที่จ่าย</th>          
                <th align="center" width="50" style="border-top:1px solid black;border-bottom: 1px solid black;">ลวท.</th>                                   
                <th align="center" width="70" style="border-top:1px solid black;border-bottom: 1px solid black;">เลขที่เช็ค</th>
                <th align="center" width="70" style="border-top:1px solid black;border-bottom: 1px solid black;">เงินหน้าเช็ค</th> 
                <th width="170" style="border-top:1px solid black;border-bottom: 1px solid black;">หมายเหตุ</th> 
                <th align="center" width="65" style="border-top:1px solid black;border-bottom: 1px solid black;"> ใบสำคัญ </th>
    
            </tr>
        </thead>
        <tbody>

    ';

    
    //count($journal_reports)
    $total_page = 0;
    $total_credit_page = 0;
    $total_debit_page = 0;
    for($i=$page_index * $lines; $i < count($journal_reports) && $i < $page_index * $lines + $lines; $i++){
    
        
   
                $html[$page_index] .= ' 
                <tr >
                             
                            
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