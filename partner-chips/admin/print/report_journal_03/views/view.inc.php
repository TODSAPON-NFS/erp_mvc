<?PHP 


$dr_total = 0;
$cr_total = 0;
for($page_index=0 ; $page_index < $page_max ; $page_index++){

    $html[$page_index] = '<style>
        div{
            font-size:14px;
        }

        .table, .table thead th, .table tbody td{
            border: 1px solid black;
            font-size:10px;
        }

        th{
            padding:8px 4px;
            font-size:12px;
            border-top: 1px dotted black;
            border-bottom: 1px dotted black;
        }

        td{
            padding:4px 8px;
            font-size:10px;
        }

    </style>';

    $html[$page_index] .= '
    <div align="center">'.$company['company_name_en'].'</div>
    <div align="center">'.$company['company_name_th'].'</div>
    <table width="100%" border="0" cellspacing="0">
        <tr>
            <td align="left" > </td>
            <td colspan = "2" align="left" style="font-size:12px;"> PAYMENT VOUCHER </td> 
        </tr>
        <tr>
            <td align="left" ></td>
            <td width="60px" align="left" ><b>เลขที่</b></td>
            <td width="120px" align="left">'.$journal_cash_receipt['journal_cash_receipt_code'].'</td>
        </tr>
        <tr>
            <td align="left" ></td>
            <td align="left" ><b>วันที่</b></td>
            <td align="left" >'.$journal_cash_receipt['journal_cash_receipt_date'].'</td>
        </tr> 
    </table>  
    <br>
    <div style="font-size:10px;"><b>รายละเอียด :</b> '.$journal_cash_receipt['journal_cash_receipt_name'].' </div>
 
    ';

    $html[$page_index] .= '
    <table  width="100%" cellspacing="0" style="font-size:12px;">
        <thead> 
            <tr>  
                <th width="120" align="center" >Account code</th>
                <th align="center" >Description </th> 
                <th width="120" align="center" >Dr.</th> 
                <th width="120" align="center" >Cr.</th>  
            </tr>
        </thead>

        <tbody>

    ';
 
     
    $dr_page = 0;
    $cr_page = 0;
    for($i=$page_index * $lines; $i < count($journal_cash_receipt_lists) && $i < $page_index * $lines + $lines; $i++){
        $dr_page +=  $journal_cash_receipt_lists[$i]['journal_cash_receipt_list_debit'];
        $cr_page +=  $journal_cash_receipt_lists[$i]['journal_cash_receipt_list_credit'];
        $dr_total +=  $journal_cash_receipt_lists[$i]['journal_cash_receipt_list_debit'];
        $cr_total +=  $journal_cash_receipt_lists[$i]['journal_cash_receipt_list_credit'];

                $html[$page_index] .= ' 
                <tr>  
                    <td  align="left">'.$journal_cash_receipt_lists[$i]['account_code'].'</td>
                    <td  align="left">'.$journal_cash_receipt_lists[$i]['account_name_th'].'</td> 
                    <td  align="right" >
                        '.number_format($journal_cash_receipt_lists[$i]['journal_cash_receipt_list_debit'],2).'
                    </td>
                    <td  align="right" >
                        '.number_format($journal_cash_receipt_lists[$i]['journal_cash_receipt_list_credit'],2).' 
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
                        <td colspan="2" align="left"> <b>รวมแต่ละหน้า</b> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($dr_page,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($cr_page,2).'</td> 
                    </tr>
                </tfoot>
            </table>
        ';
    }else{
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr> 
                        <td colspan="2" align="left"> <b>รวมแต่ละหน้า</b> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($dr_page,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($cr_page,2).'</td> 
                    </tr>
                    <tr>
                        <td colspan="4" align="center"> </td>
                    </tr>
                    <tr> 
                        <td colspan="2" align="left"><b>รวมทั้งสิ้น </b> </td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($dr_total,2).'</td>
                        <td  align="right" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >'.number_format($cr_total,2).'</td> 
                    </tr>
                </tfoot>
            </table>
        ';
    }   
}


if(count($checks) > 0){

    $html[$page_index-1] .= '
    <br>
    <table  width="100%" cellspacing="0" style="font-size:12px;"> 
        <tbody>
    ';
    
    for($i=0; $i < count($checks) ; $i++){
        $html[$page_index-1] .= ' 
        <tr>  
            <td  align="left">'.$checks[$i]['check_code'].'</td>
            <td  align="left">'.$checks[$i]['check_date'].'</td> 
            <td  align="right" >
                '.number_format($checks[$i]['check_total'],2).'
            </td>
            <td  align="right" >
                '.$checks[$i]['check_remark'] .' 
            </td> 
        </tr> 
        ';
    }
    
    $html[$page_index-1] .= ' 
        </tbody>
    </table>
    ';
    
}

if(count($check_pays) > 0){

    $html[$page_index-1] .= '
    <br>
    <table  width="100%" cellspacing="0" style="font-size:12px;"> 
        <tbody>
    ';
    
    for($i=0; $i < count($check_pays) ; $i++){
        $html[$page_index-1] .= ' 
        <tr>  
            <td  align="left">'.$check_pays[$i]['check_pay_code'].'</td>
            <td  align="left">'.$check_pays[$i]['check_pay_date'].'</td> 
            <td  align="right" >
                '.number_format($check_pays[$i]['check_pay_total'],2).'
            </td>
            <td  align="right" >
                '.$check_pays[$i]['check_pay_remark'] .' 
            </td> 
        </tr> 
        ';
    }
    
    $html[$page_index-1] .= ' 
        </tbody>
    </table>
    ';
}


if(count($invoice_suppliers) > 0){

    $html[$page_index-1] .= '
    <br>
    <table  width="100%" cellspacing="0" style="font-size:12px;"> 
        <thead> 
            <tr>
                <th></th>
                <th width="120" align="center" >Tax inv.no.</th>
                <th width="120" align="center" >Doc dd. </th> 
                <th width="120" align="center" >amount.</th> 
                <th width="120" align="center" >VAT amount.</th>  
            </tr>
        </thead>
        <tbody>
    ';
    
    
    for($i=0; $i < count($invoice_suppliers) ; $i++){
        $html[$page_index-1] .= ' 
        <tr>  
            <td align="left" > #ภาษีซื้อ </td>
            <td  align="left">'.$invoice_suppliers[$i]['invoice_supplier_code'].'</td>
            <td  align="left">'.$invoice_suppliers[$i]['invoice_supplier_date'].'</td> 
            <td  align="right" >
                '.number_format($invoice_suppliers[$i]['invoice_supplier_total_price'],2).'
            </td>
            <td  align="right" >
                '.number_format($invoice_suppliers[$i]['invoice_supplier_vat_price'],2).'
            </td> 
        </tr> 
        ';
    }
    
    $html[$page_index-1] .= ' 
        </tbody>
    </table>
    ';
}

if(count($invoice_customers) > 0){

    $html[$page_index-1] .= '
    <br>
    <table  width="100%" cellspacing="0" style="font-size:12px;"> 
        <thead> 
            <tr>
                <th></th>
                <th width="120" align="center" >Tax inv.no.</th>
                <th width="120" align="center" >Doc dd. </th> 
                <th width="120" align="center" >amount.</th> 
                <th width="120" align="center" >VAT amount.</th>  
            </tr>
        </thead>
        <tbody>
    ';
    
    
    for($i=0; $i < count($invoice_customers) ; $i++){
        $html[$page_index-1] .= ' 
        <tr>  
            <td align="left" > #ภาษีซื้อ </td>
            <td  align="left">'.$invoice_customers[$i]['invoice_customer_code'].'</td>
            <td  align="left">'.$invoice_customers[$i]['invoice_customer_date'].'</td> 
            <td  align="right" >
                '.number_format($invoice_customers[$i]['invoice_customer_total_price'],2).'
            </td>
            <td  align="right" >
                '.number_format($invoice_customers[$i]['invoice_customer_vat_price'],2).'
            </td> 
        </tr> 
        ';
    }
    
    $html[$page_index-1] .= ' 
        </tbody>
    </table>
    ';
}

?>