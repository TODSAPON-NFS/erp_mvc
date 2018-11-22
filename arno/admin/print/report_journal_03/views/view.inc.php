<?PHP 
 
$i = 0;
$page_index=-1;
$line = 0;
$dr_total = 0;
$cr_total = 0;
 
 $row_max = count($journal_cash_receipt_lists) + count($check_pays) + count($checks) + count($invoice_suppliers) + count($invoice_customers);

 while($i <  $row_max ){
    $page_index ++;
    $html[$page_index] = '<style>
        div{
            font-size:16px;
        }

        .table, .table thead th, .table tbody td{
            border: 1px solid black;
            font-size:12px;
        }

        th , .table tfoot td{
            padding:8px 4px;
            font-size:14px;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }

        td{
            padding:4px 8px;
            font-size:12px;
        }

    </style>';

    $html[$page_index] .= '
    <div align="center">'.$company['company_name_en'].'</div>
    <div align="center">'.$company['company_name_th'].'</div>
    <table width="100%" border="0" cellspacing="0">
        <tr>
            <td align="left" > </td>
            <td colspan = "2" align="left" style="font-size:12px;"> RECEIPT VOUCHER </td> 
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
    <div style="font-size:13px;"><b>สมุดรายวันรับ</div>
    <div style="font-size:13px;"><b>รายละเอียด :</b> '.$journal_cash_receipt['journal_cash_receipt_name'].' </div>
 
    ';

    $html[$page_index] .= '
    <table  width="100%" cellspacing="0" style="font-size:13px;">
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
    for( ;$i < count($journal_cash_receipt_lists) ; $i++){
        $dr_page +=  $journal_cash_receipt_lists[$i]['journal_cash_receipt_list_debit'];
        $cr_page +=  $journal_cash_receipt_lists[$i]['journal_cash_receipt_list_credit'];
        $dr_total +=  $journal_cash_receipt_lists[$i]['journal_cash_receipt_list_debit'];
        $cr_total +=  $journal_cash_receipt_lists[$i]['journal_cash_receipt_list_credit'];

        if($journal_cash_receipt_lists[$i]['journal_cash_receipt_list_debit'] == 0){
            $journal_list_debit = "";
        }else{
            $journal_list_debit = number_format($journal_cash_receipt_lists[$i]['journal_cash_receipt_list_debit'],2);
        }

        if($journal_cash_receipt_lists[$i]['journal_cash_receipt_list_credit'] == 0){
            $journal_list_credit = "";
        }else{
            $journal_list_credit = number_format($journal_cash_receipt_lists[$i]['journal_cash_receipt_list_credit'],2);
        }

        $html[$page_index] .= ' 
        <tr>  
            <td  align="left" height="20px">'.$journal_cash_receipt_lists[$i]['account_code'].'</td>
            <td  align="left">'.$journal_cash_receipt_lists[$i]['account_name_th'].'</td> 
            <td  align="right" >
                '.$journal_list_debit.'
            </td>
            <td  align="right" >
                '. $journal_list_credit .' 
            </td> 
        </tr> 
        ';

        $line ++;
        if($line % $lines == 0){
            $i++;

            $html[$page_index] .= ' 
                    </tbody>
                    <tfoot>
                        <tr> 
                            <td colspan="2" align="left" height="20px"  > <b>รวมแต่ละหน้า</b> </td>
                            <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($dr_page,2).'</td>
                            <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($cr_page,2).'</td> 
                        </tr>
                    </tfoot>
                </table>
            ';

            break;
        }

    } 

    if(count($checks) > 0){
        $html[$page_index] .= ' 
            <tr> 
                <td width="120" align="center" height="20px"  > </td>
                <td width="120" align="center" > </td> 
                <td width="120" align="center" > </td> 
                <td width="120" align="center" > </td>  
            </tr> 
            <tr> 
                <td width="120" align="center"  height="20px" >Cheque Code .</td>
                <td width="120" align="center" >Cheque Date. </td> 
                <td width="120" align="center" >amount.</td> 
                <td width="120" align="center" >Remark.</td>  
            </tr> 
            <tr> 
                <td width="120" align="center" height="20px"  > </td>
                <td width="120" align="center" > </td> 
                <td width="120" align="center" > </td> 
                <td width="120" align="center" > </td>  
            </tr> 
        ';

        for(; $i < count($journal_cash_receipt_lists) + count($checks) ; $i++){
            $ii = $i - count($journal_cash_receipt_lists);
            $html[$page_index] .= ' 
            <tr>  
                <td  align="left" height="20px" >'.$checks[$ii]['check_code'].'</td>
                <td  align="left">'.$checks[$ii]['check_date'].'</td> 
                <td  align="right" >
                    '.number_format($checks[$ii]['check_total'],2).'
                </td>
                <td  align="right" >
                    '.$checks[$ii]['check_remark'] .' 
                </td> 
            </tr> 
            ';

            $line ++;
            if($line % $lines == 0){
                $i++;

                $html[$page_index] .= ' 
                        </tbody>
                        <tfoot>
                            <tr> 
                                <td colspan="2" align="left" height="20px"  > <b>รวมแต่ละหน้า</b> </td>
                                <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($dr_page,2).'</td>
                                <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($cr_page,2).'</td> 
                            </tr>
                        </tfoot>
                    </table>
                ';

                break;
            }
        } 
    }
     
    if(count($check_pays) > 0){
        $html[$page_index] .= ' 
            <tr> 
                <td width="120" align="center" height="20px"  > </td>
                <td width="120" align="center" > </td> 
                <td width="120" align="center" > </td> 
                <td width="120" align="center" > </td>  
            </tr> 
            <tr> 
                <td width="120" align="center" height="20px"  >Cheque Code .</td>
                <td width="120" align="center" >Cheque Date. </td> 
                <td width="120" align="center" >amount.</td> 
                <td width="120" align="center" >Remark.</td>  
            </tr> 
            <tr> 
                <td width="120" align="center" height="20px"  > </td>
                <td width="120" align="center" > </td> 
                <td width="120" align="center" > </td> 
                <td width="120" align="center" > </td>  
            </tr> 
        ';
        for(; $i < count($journal_cash_receipt_lists) + count($checks) + count($check_pays) ; $i++){
            $ii = $i - count($journal_cash_receipt_lists) + count($checks) ;
            $html[$page_index] .= ' 
            <tr>  
                <td  align="left" height="20px" >'.$check_pays[$ii]['check_pay_code'].'</td>
                <td  align="left">'.$check_pays[$ii]['check_pay_date'].'</td> 
                <td  align="right" >
                    '.number_format($check_pays[$ii]['check_pay_total'],2).'
                </td>
                <td  align="right" >
                    '.$check_pays[$ii]['check_pay_remark'] .' 
                </td> 
            </tr> 
            ';

            $line ++;
            if($line % $lines == 0){
                $i++;

                $html[$page_index] .= ' 
                        </tbody>
                        <tfoot>
                            <tr> 
                                <td colspan="2" align="left" height="20px"  > <b>รวมแต่ละหน้า</b> </td>
                                <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($dr_page,2).'</td>
                                <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($cr_page,2).'</td> 
                            </tr>
                        </tfoot>
                    </table>
                ';

                break;
            }
        } 
    }
     
    if(count($invoice_suppliers) > 0){
    
        $html[$page_index] .= ' 
        <tr> 
            <td width="120" align="center" height="20px"  > </td>
            <td width="120" align="center" >  </td> 
            <td width="120" align="center" >  </td> 
            <td width="120" align="center" > </td>  
        </tr> 
        <tr> 
            <td width="120" align="center" height="20px"  >Tax inv.no.</td>
            <td width="120" align="center" >Doc dd. </td> 
            <td width="120" align="center" >amount.</td> 
            <td width="120" align="center" >VAT amount.</td>  
        </tr> 
        <tr> 
            <td width="120" align="center" height="20px"  > </td>
            <td width="120" align="center" > </td> 
            <td width="120" align="center" > </td> 
            <td width="120" align="center" > </td>  
        </tr> 
        ';
        
    
        for(; $i < count($journal_cash_receipt_lists) + count($checks) + count($check_pays) + count($invoice_suppliers) ; $i++){
            $ii = $i - count($journal_cash_receipt_lists) + count($checks) + count($check_pays) ;
            $html[$page_index] .= ' 
            <tr>   
                <td  align="left" height="20px" >#ภาษีซื้อ '.$invoice_suppliers[$ii]['invoice_supplier_code'].'</td>
                <td  align="left">'.$invoice_suppliers[$ii]['invoice_supplier_date'].'</td> 
                <td  align="right" >
                    '.number_format($invoice_suppliers[$ii]['invoice_supplier_total_price'],2).'
                </td>
                <td  align="right" >
                    '.number_format($invoice_suppliers[$ii]['invoice_supplier_vat_price'],2).'
                </td> 
            </tr> 
            ';

            $line ++;
            if($line % $lines == 0){
                $i++;

                $html[$page_index] .= ' 
                        </tbody>
                        <tfoot>
                            <tr> 
                                <td colspan="2" align="left" height="20px"  > <b>รวมแต่ละหน้า</b> </td>
                                <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($dr_page,2).'</td>
                                <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($cr_page,2).'</td> 
                            </tr>
                        </tfoot>
                    </table>
                ';

                break;
            }
        }  
    }

    
    if(count($invoice_customers) > 0){
    
        $html[$page_index] .= ' 
        <tr> 
            <td width="120" align="center" height="20px"  > </td>
            <td width="120" align="center" >  </td> 
            <td width="120" align="center" >  </td> 
            <td width="120" align="center" > </td>  
        </tr>  
        <tr> 
            <td width="120" align="center" height="20px"  >Tax inv.no.</td>
            <td width="120" align="center" >Doc dd. </td> 
            <td width="120" align="center" >amount.</td> 
            <td width="120" align="center" >VAT amount.</td>  
        </tr> 
        <tr> 
            <td width="120" align="center" height="20px"  > </td>
            <td width="120" align="center" > </td> 
            <td width="120" align="center" > </td> 
            <td width="120" align="center" > </td>  
        </tr> 
        ';  
        for(; $i < count($journal_cash_receipt_lists) + count($checks) + count($check_pays) + count($invoice_suppliers) + count($invoice_customers) ; $i++){
            $ii = $i - count($journal_cash_receipt_lists) + count($checks) + count($check_pays) + count($invoice_suppliers) ;
            $html[$page_index] .= ' 
            <tr>   
                <td  align="left" height="20px" >#ภาษีซื้อ '.$invoice_customers[$i]['invoice_customer_code'].'</td>
                <td  align="left">'.$invoice_customers[$ii]['invoice_customer_date'].'</td> 
                <td  align="right" >
                    '.number_format($invoice_customers[$ii]['invoice_customer_total_price'],2).'
                </td>
                <td  align="right" >
                    '.number_format($invoice_customers[$ii]['invoice_customer_vat_price'],2).'
                </td> 
            </tr> 
            ';

            $line ++;
            if($line % $lines == 0){
                $i++;

                $html[$page_index] .= ' 
                        </tbody>
                        <tfoot>
                            <tr> 
                                <td colspan="2" align="left" height="20px"  > <b>รวมแต่ละหน้า</b> </td>
                                <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($dr_page,2).'</td>
                                <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($cr_page,2).'</td> 
                            </tr>
                        </tfoot>
                    </table>
                ';

                break;
            }
        } 
    } 

}

if($page_index == 0){
    $html[$page_index] .= ' 
    </tbody>
    <tfoot> 
        <tr>
            <td colspan="4" align="center" height="20px" > </td>
        </tr>
        <tr> 
            <td colspan="2" align="left" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" height="20px" ><b>รวมทั้งสิ้น </b> </td>
            <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($dr_total,2).'</td>
            <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($cr_total,2).'</td> 
        </tr>
    </tfoot>
    </table>
    ';
}else{
    $html[$page_index] .= ' 
    </tbody>
    <tfoot>
        <tr> 
            <td colspan="2" align="left"  height="20px"  > <b>รวมแต่ละหน้า</b> </td>
            <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($dr_page,2).'</td>
            <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($cr_page,2).'</td> 
        </tr>
        <tr>
            <td colspan="4" align="center" height="20px" > </td>
        </tr>
        <tr> 
            <td colspan="2" align="left" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" height="20px" ><b>รวมทั้งสิ้น </b> </td>
            <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($dr_total,2).'</td>
            <td  align="right" style="border-top: 1px solid black;border-bottom: 1px solid black;padding:8px 0px;" >'.number_format($cr_total,2).'</td> 
        </tr>
    </tfoot>
    </table>
    ';
}





$html[$page_index] .= '
<br>
<table width="100%">
    <tr> 
        <td align="right" >

        </td>
        <td align="center" >
            '.$user["user_name"].'
        </td>
        <td align="right" >

        </td>
        <td align="center" >
            '.$user["user_name"].'
        </td>
        <td></td>
    </tr>
    <tr> 
        <td align="right" style="width:120px;">
            Prepare by 
        </td>
        <td align="right" style="border-bottom: 1px solid black;width:120px;" >

        </td>
        <td align="right" >
            Approve by 
        </td>
        <td align="right" style="border-bottom: 1px solid black;width:120px;" >

        </td>
        <td>
        
        </td>
    </tr>
    <tr> 
        <td colspan="5" align="left" >
        <br>
        <br>
            <b>Print date : </b>  '.$d1.'/'.$d2.'/'.$d3.' '.$d4.':'.$d5.':'.$d6.'
        </td> 
    </tr>
</table>
';

$page_max = $page_index + 1;
 


?>