<?PHP 

if( (int)$official_receipt['customer_branch'] * 1 == 0){
    $branch = " สำนักงานใหญ่";
} else {
    $branch =  "สาขา " . ((int)$official_receipt['customer_branch'] * 1) ;
} 

if($official_receipt['customer_fax'] != ""){
    $fax = "Fax. ".$official_receipt['customer_fax'];
}else{
    $fax = "";
}

if($official_receipt['customer_tel'] != ""){
    $tel = "Tel. ".$official_receipt['customer_fax'];
}else{
    $tel = "";
}

if($official_receipt['customer_zipcode'] != ""){
    $zipcode = " ".$official_receipt['customer_zipcode'];
}else{
    $zipcode = "";
}


$total = 0;
for($page_index=0 ; $page_index < $page_max ; $page_index++){

    $html[$page_index] = '
<style>
    .main{
        font-size:13px;
    }

    div{
        display:block;
        padding:4px;
        font-size:13px;
        line-height:20px;
    }

    .table thead th , .table tfoot td{
        border :1px solid black; 
        border-collapse: collapse;
        height:16px;
        font-size:12px;
    }

    .table, .table tbody td{
        border-left:1px solid black;
        border-right:1px solid black;
        border-collapse: collapse;
        height:16px;
        font-size:12px;
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
        font-size:12px;
        height:13px;
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

/*
<td width="120px">
                <img src="../upload/company/'.$company['company_image'].'" width="120px" />
            </td>
*/
    $html[$page_index] .= '
<div class="main" style="padding-left:32px;">
 
    <div style="font-size:16px;line-height:20px;">'.$company['company_name_en'].'</div>
    <div style="font-size:16px;line-height:20px;">'.$company['company_name_th'].'</div>
            

    <div style="line-height: 18px;" style="font-size:13px;" >สำนักงานใหญ่ : '.$company['company_address_1'].' '.$company['company_address_2'].' <br>'.$company['company_address_3'].' 
    Tel.'.$company['company_tel'].' Fax. '.$company['company_fax'].' Tax. '.$company['company_tax'].'</div>
    <div align="center" style="font-size:14px;line-height:18px;"><b>ใบเสร็จรับเงิน</b></div>
    <div align="center" style="font-size:14px;line-height:18px;"><b>OFFICIAL RECEIPT</b></div>

    <div style="padding:4px;line-height:20px;">
        Receipt by thanks from : -
    </div>
 
    <table width="100%">
        <tr>
            <td style="padding:4px;line-height: 18px;">
                <div style="padding:8px;font-size:12px;"> '.$official_receipt['official_receipt_name'].'  '.$branch.' <br> 
                 '. nl2br ( $official_receipt['official_receipt_address']).' '.$zipcode.'<br> 
                 '.$tel.' '.$fax.' Tax : '.$official_receipt['official_receipt_tax'].' 
                </div>
            </td>
            <td width="240">
                <table>
                    <tr>
                        <td width="100"  valign="middle" align="left">
                        No.
                        </td>
                        <td width="100"  valign="middle" align="left">
                            : '.$official_receipt['official_receipt_code'].'
                        </td>
                    </tr>

                    
                    <tr>
                        
                        <td width="100"  valign="middle" align="left">
                        Date
                        </td>
                        <td width="100"  valign="middle" align="left">
                        : '.$official_receipt['official_receipt_date'].'
                        </td>

                    </tr>
                    <tr>
                        
                        <td width="100" valign="middle" align="left">
                        Customer code
                        </td>
                        <td width="140"  valign="middle" align="left">
                        : '.$official_receipt['customer_code'].'
                        </td>

                    </tr>
                </table>

            </td>
        </tr>
    </table> 

    <div style="padding:4px;line-height:22px;" >
        Begin payment for the followings invoice : - 
    </div>

    <div>
        <table width="100%" class="table" celspacing="0">
            <thead>
                <tr style="border-bottom:1px dashed #000;border-top:1px dashed #000;">
                    <th style="text-align:center; padding:4px 0px;" >SQ.</th>
                    <th style="text-align:center; padding:4px 0px;" >INV.No</th>
                    <th style="text-align:center; padding:4px 0px;" >INV.DD.</th>
                    <th style="text-align:center; padding:4px 0px;" >DUE DD.</th>
                    <th style="text-align:center; padding:4px 0px;" >BILLING NO. </th>
                    <th style="text-align:center; padding:4px 0px;" >INV. AMOUNT</th>
                    <th style="text-align:center; padding:4px 0px;" >BAL. AMOUNT</th>
                </tr>
            </thead>

            <tbody  >
    ';
 
   
    //count($tax_reports)
    for($i=$page_index * $lines; $i < count($official_receipt_lists) && $i < $page_index * $lines + $lines; $i++){ 

        $total += $official_receipt_lists[$i]['official_receipt_bal_amount'];
                $html[$page_index] .= ' 
                <tr class="odd gradeX">
                        <td align="center" style="height:20px;">
                            '.($i+1).'
                        </td>
                        <td align="center">
                            '. $official_receipt_lists[$i]['invoice_customer_code'].'
                        </td>
                        <td align="center">
                            '. $official_receipt_lists[$i]['official_receipt_list_date'].'
                        </td>
                        <td align="center">
                            '. $official_receipt_lists[$i]['official_receipt_list_due'].'
                        </td>
                        <td align="center">
                            '. $official_receipt_lists[$i]['billing_note_code'].'
                        </td>
                        <td align="right">
                            '. number_format($official_receipt_lists[$i]['official_receipt_inv_amount'],2).'
                        </td>
                        <td align="right">
                            '. number_format($official_receipt_lists[$i]['official_receipt_bal_amount'],2).'
                        </td>
                    </tr>
                ';
    }

 
    if($page_index + 1 == $page_max){
        if(count($official_receipt_lists) % $lines > 0){
            for($i = count($official_receipt_lists) % $lines ; $i < $lines; $i++){
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
                <tr class="odd gradeX" >
                    <td colspan="6" align="center">
                        ('. $str.')
                    </td>
                    <td style="text-align: right;" >
                    '.  $total_price .'
                    </td>
                </tr>    
            </tfoot>
        </table>
    </div>
                        
                    
    <table width="100%">
        <tr>
            <td colspan="4" style="padding:8px 0px;">Payment in form of </b></td>
        <tr>
        <tr>
            <td>[ ]</td>
            <td>CASH  </td>
            <td>date ___________________</td>
            <td>Baht _________________</td>
        </tr>
        <tr>
            <td>[ ]</td>
            <td>TRANSFER  </td>
            <td>date ___________________</td>
            <td>Baht _________________</td>
        </tr>
        <tr>
            <td>[ ]</td>
            <td>CHEQUE no.__________________</td>
            <td>chq.dd._________________</td>
            <td>Baht _________________</td>
        </tr>
        <tr>
            <td></td>
            <td>Bank_________________________</td>
            <td>Branch_________________</td>
            <td>Baht _________________</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:8px 0px;">Deduction </b></td>
        <tr>
            <td>[ ]</td>
            <td>Withholding tax (if any)</td>
            <td></td>
            <td>Baht _________________</td>
        </tr>
        <tr>
            <td>[ ]</td>
            <td>[ ] Bank charges (if any)</td>
            <td></td>
            <td>Baht _________________</td>
        </tr>
    </table>
    <div>
    For payment for cheque, please make a crossed cheque payable to "'.$company['company_name_en'].'."
    This Official Receipt is valid only after the cheque is honoured by the bank or transferred to '.$company['company_name_en'].' account. 
    </div>
    <br>
    <table width="100%">
        <tr>
            <td align="center"> .............................. </td>
            <td align="center"> .............................. </td>
            <td align="center"> .............................. </td>
        </tr>
        <tr>
            <td align="center"> Bill collector </td>
            <td align="center"> Date </td>
            <td align="center"> Authorized Signature </td>
        </tr>
    </table>
</div>
    ';

}

?>