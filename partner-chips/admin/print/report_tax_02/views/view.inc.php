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
    <div><b>เดือน/ปีภาษี</b> '.$section_date.' (<b>เริ่มจาก</b> '.$date_start.' <b>ถึง</b> '.$date_end.') </div>
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานภาษีขาย</b></div>
    <table width="100%" border="0" cellspacing="0">
        <tr>
            <td align="left" width="120px" ><b>ชื่อสถานประกอบการ </b></td>
            <td> '.$company['company_name_th'].'</td>
            <td align="left"  align="left" width="120px" ><b>หน้า</b> '.($page_index + 1).' / '.$page_max.'</td>
        </tr>
        <tr>
            <td align="left" ><b>ที่อยู่สถานประกอบการ</b> </td>
            <td> '.$company['company_address_1'].' </td>
            <td ><b>สำนักงาน</b> '.$company['company_branch'].' </td>
        </tr>
        <tr>
            <td align="left" > </td>
            <td> '.$company['company_address_2'].' </td>
            <td ></td>
        </tr>
        <tr>
            <td align="left" > </td>
            <td> '.$company['company_address_3'].' </td>
            <td ></td>
        </tr>
        <tr>
            <td align="left" ><b>เลขประจำตัวผู้เสียภาษี</b> </td>
            <td> '.$company['company_tax'].' </td>
            <td >  </td>
        </tr>
    </table> 
    <br>

    ';

    $html[$page_index] .= '
    <table  width="100%" border="1" cellspacing="0" style="font-size:12px;">
        <thead>
            <tr>
                <th width="48" align="center"> ลำดับ <br>No.</th>
                <th width="80">วันที่ <br>Date</th>
                <th>เลขที่ <br>Code.</th>
                <th>เลขที่ออกใหม่ <br>New code.</th>
                <th>ลูกค้า<br>Customer</th>
                <th>ผู้ออก<br>Create by</th> 
                <th>ยอดเงิน<br>Net Price.</th>
                <th>ภาษีขาย<br>Vat.</th> 
            </tr>
        </thead>

        <tbody>

    ';
 
    
    //count($tax_reports)
    for($i=$page_index * $lines; $i < count($tax_reports) && $i < $page_index * $lines + $lines; $i++){
        $vat_total +=  $tax_reports[$i]['invoice_customer_vat_price'];
        $net_total +=  $tax_reports[$i]['invoice_customer_net_price'];

                $html[$page_index] .= ' 
                <tr>
                    <td align="center">'.($i + 1).'</td>
                    <td>'.$tax_reports[$i]['invoice_customer_date'].'</td>
                    <td>'.$tax_reports[$i]['invoice_customer_code'].'</td>
                    <td>'.$tax_reports[$i]['invoice_customer_code_gen'].'</td>
                    <td>'.$tax_reports[$i]['customer_name'].' </td>
                    <td>'.$tax_reports[$i]['employee_name'].'</td>
                    <td  align="right" >
                        '.number_format($tax_reports[$i]['invoice_customer_net_price'],2).'
                    </td>
                    <td  align="right" >'.number_format($tax_reports[$i]['invoice_customer_vat_price'],2).'</td>
                    
                </tr> 
                ';
    }

    $html[$page_index] .= ' 
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" align="center"> รวม </td>
                    <td  align="right" >'.number_format($net_total,2).'</td>
                    <td  align="right" >'.number_format($vat_total,2).'</td>
                </tr>
            </tfoot>
        </table>
    ';
}

?>