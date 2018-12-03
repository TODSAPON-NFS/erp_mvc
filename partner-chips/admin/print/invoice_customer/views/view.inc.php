<?PHP 

if( (int)$invoice_customer['customer_branch'] * 1 == 0){
    $branch = " สำนักงานใหญ่";
} else {
    $branch =  "สาขา " . ((int)$invoice_customer['customer_branch'] * 1) ;
}

$po = explode(":" , $invoice_customer_lists[0]['invoice_customer_list_remark']);

for($page_index=0 ; $page_index < $page_max ; $page_index++){

    $html[$page_index] = '
<style>
    div{
        font-size:12px;
    }
    .table, .table thead th, .table tbody td{
        border: 1px solid black;
    }

    th{
        padding:8px 4px;
        font-size:12px;
    }

    td{
        padding:4px;
        font-size:12px;
    }

    @page *{
        margin-top: 0cm;
        margin-bottom: 0cm;
        margin-left: 0cm;
        margin-right: 0cm;
    }

</style>';

    $html[$page_index] .= '
    <div style="font-size:12px;padding-left:32px;padding-top:134px;"></div>   
    <table width="100%" >
        <tr>
            <td style="padding-left:72px;width:120px;" align="center">
                '.$invoice_customer['customer_code'].'
            </td>
            <td style="font-size:14px;padding-right:60px;" align="right">
                <b> PAGE. '.($page_index + 1).'/'.$page_max.'</b> 
            </td> 
    </table>

<div>
    <table width="100%" >
        <tr>
            <td style="padding-left:84px;font-size:14px;" width = "580" valign="middle">
            <b>
                '.$invoice_customer['invoice_customer_name'].' '.$branch.'
                <br>
                '.$invoice_customer['invoice_customer_address'].'<br>
                เลขประจำตัวผู้เสียภาษี / Tax : '.$invoice_customer['invoice_customer_tax'].'
            </b>
            </td>
            <td width="140" >
                <table>
                    <tr>
                        <td width="80" height="48" valign="middle" align="left">
                        </td>
                        <td width="100" height="48" valign="middle" align="right">
                            '.$invoice_customer['invoice_customer_date'].'
                        </td>
                        <td width="80" height="48" valign="middle" align="left">
                        </td>
                        <td width="100" height="48" valign="middle" align="right">
                            '.$invoice_customer['invoice_customer_code'].'
                        </td>

                    </tr>

                    <tr>
                        <td width="80" height="48" valign="middle" align="left">
                        </td>

                        <td width="100" height="48" valign="middle" align="right">
                            '.$invoice_customer['invoice_customer_due_day'].' วัน
                        </td>

                        <td width="80" height="48" valign="middle" align="left">
                        </td>

                        <td width="100" height="48" valign="middle" align="right">
                            '.$po[1].'
                        </td>

                    </tr>

                    <tr>
                        <td width="80" height="48" valign="middle" align="left">
                        </td>
                        <td width="100" height="48" valign="middle" align="right" style="padding-top:8px;">
                            '.$invoice_customer['invoice_customer_due'].'
                        </td>
                        <td width="80" height="48" valign="middle" align="left">
                        </td>
                        <td width="100" height="48" valign="middle" align="right" style="padding-top:8px;">
                            '.$invoice_customer['user_name'] .' 
                        </td>

                    </tr>
                </table>

            </td>
        </tr>
    </table>
</div>

<div style="height:48px;">

</div>

<div style="height:390px;">
    <table width="100%" >

        <tbody>
    ';
 
    
    //count($tax_reports)
    for($i=$page_index * $lines; $i < count($invoice_customer_lists) && $i < $page_index * $lines + $lines; $i++){ 
        $total += $invoice_customer_lists[$i]['invoice_customer_list_qty'] * $invoice_customer_lists[$i]['invoice_customer_list_price'];
                $html[$page_index] .= ' 
                <tr >
                    <td valign="top" width="64" align="center">
                        '.($i+1).'.
                    </td>

                    <td valign="top" >
                        <b>['. $invoice_customer_lists[$i]['product_code'].'] '. $invoice_customer_lists[$i]['product_name'].'</b><br>
                        <span></span>'. $invoice_customer_lists[$i]['invoice_customer_list_product_name'].'<br>
                        <span></span>'. $invoice_customer_lists[$i]['invoice_customer_list_product_detail'].'<br>
                        
                    </td>

                    <td valign="top" align="right" width="100">'. $invoice_customer_lists[$i]['invoice_customer_list_qty'].'</td>
                    <td valign="top" align="right" width="100">'.  number_format($invoice_customer_lists[$i]['invoice_customer_list_price'],2).'</td>
                    <td valign="top" align="right" width="100">'.  number_format($invoice_customer_lists[$i]['invoice_customer_list_qty'] * $invoice_customer_lists[$i]['invoice_customer_list_price'],2).'</td>
                    

                </tr>
                ';
    }
//<span></span>'. $invoice_customer_lists[$i]['invoice_customer_list_remark'].'<br>
    if($page_index + 1 == $page_max){
       
       $vat = number_format($invoice_customer['invoice_customer_vat'],2);
       $vat_price = number_format(($vat/100) * $total,2);
       $net_price = number_format(($vat/100) * $total + $total,2);
       $total_price = number_format($total,2);
       $str = $number_2_text->convert($net_price);
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
        <td colspan="2" align="center" valign="middle">
        '.$str.'
        </td>
        <td colspan="2" align="left" style="vertical-align: middle;">
            
        </td>
        <td style="text-align: right;" valign="middle" height="30">
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
        <td style="text-align: right;" valign="middle" height="30">
            '.$vat_price.'
        </td>
        
    </tr>
    <tr class="odd gradeX">
        <td></td>
        <td></td>
        <td colspan="2" align="left" style="vertical-align: middle;" >
            
        </td>
        <td style="text-align: right;" valign="middle" height="30">
            '.$net_price.'
        </td>
        
    </tr>
</table>   
    ';

}

?>