<?PHP 

if( (int)$invoice_customer['customer_branch'] * 1 == 0){
    $branch = " สำนักงานใหญ่";
} else {
    $branch =  "สาขา " . ((int)$invoice_customer['customer_branch'] * 1) ;
}

for($page_index=0 ; $page_index < $page_max ; $page_index++){

    $html[$page_index] = '
<style>
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
    <div style="font-size:10px;padding-left:32px;padding-top:180px;"></div>   
    <table width="100%" >
        <tr>
            <td style="padding:8px;width:120px;" align="center">
                '.$invoice_customer['customer_code'].'
            </td>
            <td style="font-size:14px;padding-right:100px;" align="right">
                <b>'.($page_index + 1).'/'.$page_max.'</b> 
            </td> 
    </table>
<div>
    <table width="100%" >
        <tr>
            <td style="padding:8px;">
                '.$invoice_customer['invoice_customer_name'].' '.$branch.'
                <br>
                '.$invoice_customer['invoice_customer_address'].'<br>
                เลขประจำตัวผู้เสียภาษี / Tax : '.$invoice_customer['invoice_customer_tax'].'
            </td>
            <td width="160">
                <table>
                    <tr>
                        <td width="60" height="32" valign="middle" align="left">
                        </td>
                        <td width="100" height="32" valign="middle" align="right">
                            '.$invoice_customer['invoice_customer_date'].'
                        </td>
                        <td width="60" height="32" valign="middle" align="left">
                        </td>
                        <td width="100" height="32" valign="middle" align="right">
                            '.$invoice_customer['invoice_customer_code'].'
                        </td>

                    </tr>

                    <tr>
                        <td width="60" height="32" valign="middle" align="left">
                        </td>

                        <td width="100" height="32" valign="middle" align="right">
                            '.$invoice_customer['invoice_customer_term'].'
                        </td>

                        <td width="60" height="32" valign="middle" align="left">
                        </td>

                        <td width="100" height="32" valign="middle" align="right">
                            -
                        </td>

                    </tr>

                    <tr>
                        <td width="60" height="32" valign="middle" align="left">
                        </td>
                        <td width="100" height="32" valign="middle" align="right">
                            '.$invoice_customer['invoice_customer_due'].'
                        </td>
                        <td width="60" height="32" valign="middle" align="left">
                        </td>
                        <td width="100" height="32" valign="middle" align="right">
                            '.$invoice_customer['user_name'] .' '.$invoice_customer['user_lastname'].'
                        </td>

                    </tr>
                </table>

            </td>
        </tr>
    </table>
</div>

<div style="height:64px;">

</div>

<div style="height:440px;">
    <table width="100%" >

        <tbody>
    ';
 
    
    //count($tax_reports)
    for($i=$page_index * $lines; $i < count($invoice_customer_lists) && $i < $page_index * $lines + $lines; $i++){ 
        $total += $invoice_customer_lists[$i]['invoice_customer_list_qty'] * $invoice_customer_lists[$i]['invoice_customer_list_price'];
                $html[$page_index] .= ' 
                <tr >
                    <td valign="top" width="20">
                        '.($i+1).'.
                    </td>

                    <td valign="top" width="280">
                        <b>'. $invoice_customer_lists[$i]['product_code'].'</b><br>
                        <span>Sub name : </span>'. $invoice_customer_lists[$i]['invoice_customer_list_product_name'].'<br>
                        <span>Detail : </span>'. $invoice_customer_lists[$i]['invoice_customer_list_product_detail'].'<br>
                        <span>Remark : </span>'. $invoice_customer_lists[$i]['invoice_customer_list_remark'].'<br>
                    </td>

                    <td valign="top" align="right" width="80">'. $invoice_customer_lists[$i]['invoice_customer_list_qty'].'</td>
                    <td valign="top" align="right" width="80">'.  number_format($invoice_customer_lists[$i]['invoice_customer_list_price'],2).'</td>
                    <td valign="top" align="right" width="80">'.  number_format($invoice_customer_lists[$i]['invoice_customer_list_qty'] * $invoice_customer_lists[$i]['invoice_customer_list_price'],2).'</td>
                    

                </tr>
                ';
    }

    if($page_index + 1 == $page_max){
       $str = $number_2_text->convert(number_format(($vat/100) * $total + $total,2));
       $vat = number_format($invoice_customer['invoice_customer_vat'],2);
       $vat_price = number_format(($vat/100) * $total,2);
       $net_price = number_format(($vat/100) * $total + $total,2);
       $total_price = number_format($total,2);
    }


    $html[$page_index] .= ' 
    </tbody>
    </table>
</div>
                    
                    
<table width="100%">
    <tr>
        <td width="20">
        </td>

        <td width="280">
            
        </td>


        <td width="80" align="right"></td>
        <td width="80" align="right"></td>
        <td width="80" align="right"></td>
        

    </tr>
        
    <tr class="odd gradeX">
        <td colspan="2" align="center">
        '.$str.'
        </td>
        <td colspan="2" align="left" style="vertical-align: middle;">
            
        </td>
        <td style="text-align: right;">
            '.$total_price.'
        </td>
        
    </tr>
    <tr class="odd gradeX">
        <td></td>
        <td></td>
        <td colspan="2" align="left" style="vertical-align: middle;">
            <table>
                <tr>
                    <td>
                        
                    </td>
                    <td style = "padding-left:8px;padding-right:8px;width:72px;">
                        
                    </td>
                    <td width="16">
                    
                    </td>
                </tr>
            </table>
            
        </td>
        <td style="text-align: right;">
            '.$vat_price.'
        </td>
        
    </tr>
    <tr class="odd gradeX">
        <td></td>
        <td></td>
        <td colspan="2" align="left" style="vertical-align: middle;">
            
        </td>
        <td style="text-align: right;">
            '.$net_price.'
        </td>
        
    </tr>
</table>   
    ';

}

?>