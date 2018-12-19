<?PHP 

if( (int)$billing_note['customer_branch'] * 1 == 0){
    $branch = " สำนักงานใหญ่";
} else {
    $branch =  "สาขา " . ((int)$billing_note['customer_branch'] * 1) ;
} 


if($billing_note['customer_fax'] != ""){
    $fax = "Fax. ".$billing_note['customer_fax'];
}else{
    $fax = "";
}

if($billing_note['customer_tel'] != ""){
    $tel = "Tel. ".$billing_note['customer_fax'];
}else{
    $tel = "";
}

if($billing_note['customer_zipcode'] != ""){
    $zipcode = " ".$billing_note['customer_zipcode'];
}else{
    $zipcode = "";
}

$total = 0;
for($page_index=0 ; $page_index < $page_max ; $page_index++){

    $html[$page_index] = '
<style>
    .main{
        font-size:14px;
    }

    div{
        padding-left:8px;
        font-size:14px;
    }

    .table thead th , .table tfoot td{
        border-bottom:1px solid #000;
        border-top:1px solid #000;
        padding:16px 0px;
        border-collapse: collapse;
        height:16px;
        font-size:14px;
    }

    .table, .table tbody td{ 
        border-collapse: collapse;
        height:16px;
        font-size:14px;
    }


    tbody {
        display:block !important;
        height:325px;
    }
    th{
        padding:8px 4px;
        font-size:10px;
    }

    td{
        padding:4px;
        font-size:14px;
        height:14px;
        
    }
 

    

    @page *{
        margin-top: 2.54cm;
        margin-bottom: 2.54cm;
        margin-left: 3.175cm;
        margin-right: 3.175cm;
        margin-header: 5mm; /* <any of the usual CSS values for margins> */
	    margin-footer: 5mm; /* <any of the usual CSS values for margins> */
    }

</style>';

    $html[$page_index] .= '
<div class="main">
    <table width="100%">
        <tr> 
            <td>
                <div style="font-size:16px;">'.$company['company_name_en'].'</div>
                <div style="font-size:16px;">'.$company['company_name_th'].'</div>
            </td>
        </tr>
    </table>

    <div style="line-height: 18px;" style="font-size:13px;" >สำนักงานใหญ่ : '.$company['company_address_1'].' '.$company['company_address_2'].' <br>'.$company['company_address_3'].' 
    Tel.'.$company['company_tel'].' Fax. '.$company['company_fax'].' Tax. '.$company['company_tax'].'</div>

    <div align="center" style="font-size:20px;"><b>ใบวางบิล</b></div> 

    <div style="padding:4;">
        ลูกค้า : '. $billing_note['customer_code'].'
    </div>

    <div>
        <table width="100%" >
            <tr>
                <td style="line-height: 18px;">
                    <div style="padding:8px;">
                    '. $billing_note['billing_note_name'].'  '.$branch.'
                    </div>
                    <div style="padding:8px;line-height:2;"> 
                    '. nl2br ( $billing_note['billing_note_address'] ).' '.$zipcode.'
                        
                    </div>
                    <div style="padding:8px;">  
                    '.$tel.' '.$fax.' Tax : '. $billing_note['billing_note_tax'].'
                    </div>
                </td>
                <td width="200">
                    <table>
                        <tr>
                            <td width="40" height="32" valign="middle" align="left" style="font-size:14px;">
                            No.
                            </td>
                            <td width="140" height="32" valign="middle" align="left" style="font-size:14px;">
                               : '. $billing_note['billing_note_code'].'
                            </td>
                        </tr>

                       
                        <tr>
                            
                            <td width="40" height="32" valign="middle" align="left" style="font-size:14px;">
                            Date
                            </td>
                            <td width="140" height="32" valign="middle" align="left" style="font-size:14px;">
                            : '. $billing_note['billing_note_date'].'
                            </td>

                        </tr>
                    </table>

                </td>
            </tr>
        </table>
    </div> 
        <table width="100%" class="table" style="font-size:13px;">
            <thead>
                <tr >
                    <th style="text-align:center;">No.</th>
                    <th style="text-align:center;">Invoice Number</th>
                    <th style="text-align:center;">Date</th>
                    <th style="text-align:center;" width="100">Due Date</th>
                    <th style="text-align:center;" width="100">Amount</th>
                    <th style="text-align:center;" width="100">Paid</th>
                    <th style="text-align:center;" width="100">Balance</th>
                </tr>
            </thead>

            <tbody>
    ';
 
   
    //count($tax_reports)
    for($i=$page_index * $lines; $i < count($billing_note_lists) && $i < $page_index * $lines + $lines; $i++){ 

        $total += $billing_note_lists[$i]['billing_note_list_amount'] - $billing_note_lists[$i]['billing_note_list_paid'];
                $html[$page_index] .= ' 
                <tr class="odd gradeX">
                    <td align="center" style="height:20px;">
                        '. ($i+1) .'
                    </td>
                    <td align="center">
                        '.  $billing_note_lists[$i]['invoice_customer_code'].'
                    </td>
                    <td align="center">
                        '.  $billing_note_lists[$i]['billing_note_list_date'].'
                    </td>
                    <td align="center">
                        '.  $billing_note_lists[$i]['billing_note_list_due'].'
                    </td>
                    <td align="right">
                        '.  number_format($billing_note_lists[$i]['billing_note_list_amount'],2).'
                    </td>
                    <td align="right">
                        '.  number_format($billing_note_lists[$i]['billing_note_list_paid'],2).'
                    </td>
                    <td align="right">
                        '.  number_format($billing_note_lists[$i]['billing_note_list_amount'] - $billing_note_lists[$i]['billing_note_list_paid'],2).'
                    </td>
                </tr>
                ';
    }

 
    if($page_index + 1 == $page_max){
        if(count($billing_note_lists) % $lines > 0){
            for($i = count($billing_note_lists) % $lines ; $i < $lines; $i++){
                $html[$page_index] .= ' 
                    <tr class="odd gradeX">
                            <td align="center" style="height:20px;"> 

                            </td>
                            <td align="center">
                                 
                            </td>
                            <td align="center">
                                 
                            </td>
                            <td align="center">
                                
                            </td>
                            <td align="center">
                               
                            </td>
                            <td align="right">
                                 
                            </td>
                            <td align="right">
                                 
                            </td>
                        </tr>
                    ';
            }
        } 
        
    
       $total_price = number_format($total,2);
       $str = $number_2_text->convert($total_price);

    }
   

    $html[$page_index] .= ' 
    </tbody>
    <tfoot>
        <tr  >
            <td colspan="5" align="center" style="font-size:14px;">
                ('. $number_2_text->convert(number_format($total,2)).')
            </td>
            <td colspan="1" align="left" style="vertical-align: middle;">
                Total
            </td>
            <td style="text-align: right;">
                '. number_format($total,2) .'
            </td> 
        </tr>
    </tfoot>
    </table> 
                    
                    
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
    <tr >
        <td colspan="4" style="padding:16px;line-height: 24px;" > 
            <div ><b>Remark</b></div>
            <div >'.nl2br ( $billing_note['billing_note_remark']).'</div> 
            <br>
            <div >ชื่อผู้รับวางบิล   ______________________</div>
            <div style="padding:16px;" >วันที่รับ ____/____/________</div><br>
        </td>
        <td>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="padding:16px;line-height: 24px;vertical-align: top;" >
            <div style="padding:16px;" ><b>จ่ายโดย</b> </div>
            <div style="padding-left : 16px;">|_| การโอนเงิน วันที่ _______________</div>
            <div style="padding-left : 16px;">|_| เช็ค วันที่รับเช็ค _________________</div>
        </td>
        <td colspan="3" align="left" style="vertical-align: top;line-height: 24px;">
            
            <div>'.$company['company_name_th'].'</div>
            <br> 
            <div>ชื่อผู้วางบิล   ______________________</div>
        </td>
                   
    </tr>
    
</table>   

</div>
            
    ';

}

?>