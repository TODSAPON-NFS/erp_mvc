<?PHP 


$total_total = 0;
$vat_total = 0;
$net_total = 0;
for($page_index=0 ; $page_index < $page_max ; $page_index++){

    $html[$page_index] = '<style>
        div{
            font-size:10px;
        }
        body{
            font-family:  "tahoma";  
        }
        .table, .table thead th, .table tbody td{
            border: 1px solid black;
        }

        th{
            padding:2px 4px;
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
                <div><b>เดือน/ปีภาษี</b> '.$section_date.' (<b>เริ่มจาก</b> '.$date_start.' <b>ถึง</b> '.$date_end.') </div>
            </td>
            <td align="left"  align="left" width="120px" >
                
            </td>
        </tr>
    </table>
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานใบวางบิล</b></div>
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
            <td align="left"  align="left" width="120px" > พิมพ์ '.$datePrint.'</td>
        </tr>
    </table>  
    ';

    $html[$page_index] .= '
    <table  width="100%" cellspacing="0" style="font-size:12px;">
        <thead>
            <tr>
                <th width="48" align="center" > ลำดับ </th>
                <th colspan="2" align="center" style="border-bottom: 1px dotted black;" >ใบวางบิล</th>
                <th align="center" >ชื่อผู้ซื้อสินค้า/ผู้รับบริการ</th>
                <th align="center" >เลขประจำตัว</th>
                <th colspan="2" align="center" style="border-bottom: 1px dotted black;" >สถานประกอบการ</th>
                <th align="center" >มูลค่าสินค้า</th>  
            </tr>
            <tr> 
                <th></th>
                <th width="80" align="center" >วัน/เดือน/ปี</th>
                <th align="center" >เลขที่ </th> 
                <th></th>
                <th align="center" >ผู้เสียภาษี</th> 
                <th align="center" width="80" >สนญ.</th>
                <th align="center" width="80" >สาขาที่</th> 
                <th align="center" >หรือบริการ</th>  
            </tr>
        </thead>

        <tbody>

    ';
 
    
    //count($debtor_reports)
    $total_page = 0;
    $vat_page = 0;
    $net_page = 0;
    for($i=$page_index * $lines; $i < count($debtor_reports) && $i < $page_index * $lines + $lines; $i++){
        $branch = (int)$debtor_reports[$i]['billing_note_branch'];
        if($branch == 0){
            $branch_main = "/";
            $branch_sub = "";
        }else{
            $branch_main = "";
            $branch_sub = $branch;
        }
        $total_page +=  $debtor_reports[$i]['billing_note_total']; 

        $total_total +=  $debtor_reports[$i]['billing_note_total']; 

                $html[$page_index] .= ' 
                <tr>
                    <td align="center" >'.($i + 1).'</td>
                    <td align="center" >'.$debtor_reports[$i]['billing_note_date'].'</td>
                    <td>'.$debtor_reports[$i]['billing_note_code'].'</td> 
                    <td>['.$debtor_reports[$i]['customer_code'].'] '.$debtor_reports[$i]['billing_note_name'].' </td>
                    <td>'.$debtor_reports[$i]['billing_note_tax'].' </td>
                    <td align="center" >' .$branch_main.'</td>
                    <td align="center" >' .$branch_sub.'</td>
                    <td  align="right" >
                        '.number_format($debtor_reports[$i]['billing_note_total'],2).'
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
                        <td colspan="6" align="left"> <b>รวมแต่ละหน้า</b> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($total_page,2).'</td> 
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
                        <td colspan="6" align="left"><div><b>รวมทั้งสิ้น งวด </b> '.$section_date.' (<b>เริ่มจาก</b> '.$date_start.' <b>ถึง</b> '.$date_end.') </div> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($total_total,2).'</td>  
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
                        <td colspan="6" align="left"> <b>รวมแต่ละหน้า</b> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($total_page,2).'</td>  
                    </tr>
                    <tr>
                        <td colspan="11" align="center"> </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="6" align="left"><div><b>รวมทั้งสิ้น งวด </b> '.$section_date.' (<b>เริ่มจาก</b> '.$date_start.' <b>ถึง</b> '.$date_end.') </div> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($total_total,2).'</td>  
                    </tr>
                </tfoot>
            </table>
        ';
    }

}

?>