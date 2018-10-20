<?PHP 


$vat_total = 0;
$net_total = 0;
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
        }

        td{
            padding:4px;
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
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานภาษีซื้อ</b></div>
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
                <th width="48" align="center" > ลำดับ </th>
                <th colspan="2" align="center" style="border-bottom: 1px dotted black;" >ใบกำกับภาษี</th>
                <th align="center" >ชื่อผู้ขายสินค้า/ผู้ให้บริการ</th>
                <th align="center" >เลขประจำตัว</th>
                <th colspan="2" align="center" style="border-bottom: 1px dotted black;" >สถานประกอบการ</th>
                <th align="center" >มูลค่าสินค้า</th>
                <th align="center" >จำนวนเงิน</th>
                <th align="center" >หมายเหตุ</th>
            </tr>
            <tr> 
                <th></th>
                <th width="80" align="center" >วัน/เดือน/ปี</th>
                <th align="center" >เลขที่ </th> 
                <th></th>
                <th align="center" >ผู้เสียภาษี</th> 
                <th align="center" >สนญ.</th>
                <th align="center" >สาขาที่</th> 
                <th align="center" >หรือบริการ</th> 
                <th align="center" >ภาษีมูลค่าเพิ่ม</th> 
                <th></th>
            </tr>
        </thead>

        <tbody>

    ';
 
    
    //count($tax_reports)
    $vat_page = 0;
    $net_page = 0;
    for($i=$page_index * $lines; $i < count($tax_reports) && $i < $page_index * $lines + $lines; $i++){
        $vat_page +=  $tax_reports[$i]['invoice_customer_vat_price'];
        $net_page +=  $tax_reports[$i]['invoice_customer_total_price'];
        $vat_total +=  $tax_reports[$i]['invoice_customer_vat_price'];
        $net_total +=  $tax_reports[$i]['invoice_customer_total_price'];

                $html[$page_index] .= ' 
                <tr>
                    <td align="center" >'.($i + 1).'</td>
                    <td align="center" >'.$tax_reports[$i]['invoice_customer_date'].'</td>
                    <td>'.$tax_reports[$i]['invoice_customer_code'].'</td> 
                    <td>'.$tax_reports[$i]['invoice_customer_name'].' </td>
                    <td>'.$tax_reports[$i]['invoice_customer_tax'].' </td>
                    <td>' .'</td>
                    <td>' .'</td>
                    <td  align="right" >
                        '.number_format($tax_reports[$i]['invoice_customer_total_price'],2).'
                    </td>
                    <td  align="right" >'.number_format($tax_reports[$i]['invoice_customer_vat_price'],2).'</td>
                    <td>
                        '.$tax_reports[$i]['invoice_customer_remark'].'
                    </td>
                </tr> 
                ';
    }

    if($page_index+1 < $page_max){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td colspan="6" align="left"> <b>รวมแต่ละหน้า</b> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($net_page,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($vat_page,2).'</td>
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
                        <td colspan="6" align="left"> <b>รวมแต่ละหน้า</b> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($net_page,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($vat_page,2).'</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="11" align="center"> </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="6" align="left"><div><b>รวมทั้งสิ้น งวด </b> '.$section_date.' (<b>เริ่มจาก</b> '.$date_start.' <b>ถึง</b> '.$date_end.') </div> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($net_total,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($vat_total,2).'</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        ';
    }

}

?>