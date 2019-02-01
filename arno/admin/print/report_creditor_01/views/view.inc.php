<?PHP 


$purchase_order_total_prices = 0;
$purchase_order_vat_prices = 0;
$purchase_order_net_prices = 0; 
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
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานใบสั่งซื้อสินค้า</b></div>
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
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">ลำดับ </th>
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">วันที่ </th>
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">ใบสั่งซื้อสินค้า </th> 
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">รหัสผู้ขาย</th>
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">ผู้ขาย</th> 
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">ใบรับสินค้า</th>
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">ยอดเงิน</th> 
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">ภาษีขาย</th> 
                <th align="center" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">ยอดเงินสุทธิ</th>  
            </tr> 
        </thead>

        <tbody>

    ';
 
    
    //count($creditor_reports)
    $purchase_order_total_price = 0;
    $purchase_order_vat_price = 0;
    $purchase_order_net_price = 0; 
    for($i=$page_index * $lines; $i < count($creditor_reports) && $i < $page_index * $lines + $lines; $i++){
        if( $creditor_reports[$i]['payment'] == $creditor_reports[$i]['purchase_order_net_price']) { $payment = "Y"; } else { $payment =  "N"; }
        $purchase_order_total_price += $creditor_reports[$i]['purchase_order_total_price'];
        $purchase_order_vat_price += $creditor_reports[$i]['purchase_order_vat_price'];
        $purchase_order_net_price += $creditor_reports[$i]['purchase_order_net_price']; 
        
        $purchase_order_total_prices += $creditor_reports[$i]['purchase_order_total_price'];
        $purchase_order_vat_prices += $creditor_reports[$i]['purchase_order_vat_price'];
        $purchase_order_net_prices += $creditor_reports[$i]['purchase_order_net_price']; 
                $html[$page_index] .= ' 
                <tr> 
                    <td align="center">'.($i+1).'</td>
                    <td align="center">'.$creditor_reports[$i]['purchase_order_date'].'</td>
                    <td align="center">'.$creditor_reports[$i]['purchase_order_code'].'</td>
                    <td align="left">'.$creditor_reports[$i]['supplier_code'].'</td>
                    <td align="left">'.$creditor_reports[$i]['supplier_name_en'].'</td>
                    <td align="left">'.$creditor_reports[$i]['invoice_supplier_code_gen'].'</td>
                    <td align="right">'.number_format($creditor_reports[$i]['purchase_order_total_price'],2).'</td>
                    <td align="right">'.number_format($creditor_reports[$i]['purchase_order_vat_price'],2).'</td>
                    <td align="right">'.number_format($creditor_reports[$i]['purchase_order_net_price'],2).'</td> 
                </tr>
                ';
    }

    if($page_index+1 < $page_max){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" align="center"> <b>รวมในหน้า</b></td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($purchase_order_total_price,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($purchase_order_vat_price,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($purchase_order_net_price,2).'</td> 
                    </tr>
                </tfoot>
            </table>
        ';
    }else if ($page_index == 0){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot> 
                    <tr>
                        <td colspan="6" align="center"> <b>รวมทั้งหมด</b> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($purchase_order_total_prices,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($purchase_order_vat_prices,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($purchase_order_net_prices,2).'</td> 
                    </tr>
                </tfoot>
            </table>
        ';
    }else{
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" align="center"> <b>รวมในหน้า</b> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($purchase_order_total_price,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($purchase_order_vat_price,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($purchase_order_net_price,2).'</td> 
                    </tr>
                    <tr>
                        <td colspan="6" align="center"> <b>รวมทั้งหมด</b> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($purchase_order_total_prices,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($purchase_order_vat_prices,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;">'. number_format($purchase_order_net_prices,2).'</td> 
                    </tr>
                </tfoot>
            </table>
        ';
    }

    
}

?>