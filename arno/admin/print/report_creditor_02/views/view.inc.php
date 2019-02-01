<?PHP 


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
                <div><b>เดือน/ปีภาษี</b> '.$section_date.' (<b>เริ่มจาก</b> '.$date_start.' <b>ถึง</b> '.$date_end.') </div>
            </td>
            <td align="left"  align="left" width="120px" >
            <b>พิมพ์ : </b>  '.$d1.'-'.$d2.'-'.$d3.' '.$d4.':'.$d5.':'.$d6.'
                
            </td>
        </tr>
    </table>
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานใบรับสินค้า</b></div>
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
            <td> '.$company['company_tax'].'  '.$company['company_branch'].' </td>
            <td >  </td>
        </tr>
    </table>  
    ';

    $html[$page_index] .= '
    <table  width="100%" cellspacing="0" style="font-size:12px;">
        <thead>
            <tr> 
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">เลขที่ </th>
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">วันที่ </th>
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">ผู้จำหน่าย </th> 
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">เลขที่บิล</th>
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">มูลค่าสินค้า</th> 
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">VAT.</th>
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">รวมทั้งสิ้น</th> 
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">ครบกำหนด</th> 
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">ใบสั่งซื้อ</th> 
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">จ่ายเงิน</th> 
            </tr> 
        </thead>

        <tbody>

    ';
 
    
    //count($tax_reports)
    $invoice_supplier_total_price = 0;
    $invoice_supplier_vat_price = 0;
    $invoice_supplier_net_price = 0; 
    for($i=$page_index * $lines; $i < count($tax_reports) && $i < $page_index * $lines + $lines; $i++){
        if( $tax_reports[$i]['payment'] == $tax_reports[$i]['invoice_supplier_net_price']) { $payment = "Y"; } else { $payment =  "N"; }
        $invoice_supplier_total_price += $tax_reports[$i]['invoice_supplier_total_price'];
        $invoice_supplier_vat_price += $tax_reports[$i]['invoice_supplier_vat_price'];
        $invoice_supplier_net_price += $tax_reports[$i]['invoice_supplier_net_price']; 

                $html[$page_index] .= ' 
                <tr> 
                    <td align="center">'.$tax_reports[$i]['invoice_supplier_code_gen'].'</td>
                    <td align="center">'.$tax_reports[$i]['invoice_supplier_date_recieve'].'</td>
                    <td align="left">'.$tax_reports[$i]['invoice_supplier_name'].'</td>
                    <td align="left">'.$tax_reports[$i]['invoice_supplier_code'].'</td>
                    <td align="right">'.number_format($tax_reports[$i]['invoice_supplier_total_price'],2).'</td>
                    <td align="right">'.number_format($tax_reports[$i]['invoice_supplier_vat_price'],2).'</td>
                    <td align="right">'.number_format($tax_reports[$i]['invoice_supplier_net_price'],2).'</td>
                    <td align="center">'.$tax_reports[$i]['invoice_supplier_due'].'</td>
                    <td align="center">'.$tax_reports[$i]['purchase_order_code'].'</td>
                    <td align="center">'.$payment.'</td> 
                </tr>
                ';
    }

    if($page_index+1 < $page_max){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" align="center"> รวม '. number_format(count($tax_reports),0).' ใบ</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($invoice_supplier_total_price,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($invoice_supplier_vat_price,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($invoice_supplier_net_price,2).'</td>
                        <td  align="right" ></td>
                        <td  align="right" ></td>
                        <td  align="right" ></td>
                    </tr>
                </tfoot>
            </table>
        ';
    }else{
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" align="center"> รวม '. number_format(count($tax_reports),0).' ใบ</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($invoice_supplier_total_price,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($invoice_supplier_vat_price,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($invoice_supplier_net_price,2).'</td>
                        <td  align="right" ></td>
                        <td  align="right" ></td>
                        <td  align="right" ></td>
                    </tr>
                </tfoot>
            </table>
        ';
    }

    
}

?>