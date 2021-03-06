<?PHP 

if( (int)$invoice_supplier['supplier_branch'] * 1 == 0){
    $branch = "";
} else {
    $branch =  "Branch " . ((int)$invoice_supplier['supplier_branch'] * 1) ;
} 

if($invoice_supplier['vat_type'] == '0'){
    $vat= '0';
}else{
    $vat = $invoice_supplier['vat'];
}

if($invoice_supplier['supplier_fax'] != ""){
    $fax = $invoice_supplier['supplier_fax'];
}else{
    $fax = "-";
}

if($invoice_supplier['supplier_tel'] != ""){
    $tel = $invoice_supplier['supplier_fax'];
}else{
    $tel = "-";
}

if($invoice_supplier['supplier_zipcode'] != ""){
    $zipcode = " ".$invoice_supplier['supplier_zipcode'];
}else{
    $zipcode = "";
}

if($invoice_supplier['invoice_supplier_tax'] != ""){
    $invoice_supplier_tax = " ".$invoice_supplier['invoice_supplier_tax'];
}else{
    $invoice_supplier_tax = "-";
}

$lastname =  substr($invoice_supplier['user_lastname'],0,1);

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
        border-top :1px dashed   black;  
        border-bottom :1px dashed   black;  
        border-collapse: collapse;
        height:16px;
        font-size:12px;
    }

    .table, .table tbody td{ '.
        //border-left :1px solid black; 
        //border-right :1px solid black; 
        'border-collapse: collapse;
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
        padding-top:8px;
        font-size:12px;
        height:13px;
    }

    

    @page *{
        margin-top: 2.54cm;
        margin-bottom: 2.54cm;
        margin-left: 2.54cm;
        margin-right: 3.175cm;
        margin-header: 5mm; /* <any of the usual CSS values for margins> */
	    margin-footer: 5mm; /* <any of the usual CSS values for margins> */
    }

</style>';

/*

*/
    $html[$page_index] .= '
<div class="main" style="padding-left:8px;">
 
    <table width="100%">
        <tr>'./*
            <td width="120px">
                <img src="../upload/company/'.$company['company_image'].'" width="120px" />
            </td>*/'
            <td>
                <div style="font-size:18px;line-height:20px;">'.$company['company_name_en'].'</div>
                <div style="font-size:18px;line-height:20px;">'.$company['company_name_th'].'</div>
            </td>
            <td align="right"> 
                <div  style="font-size:22px;line-height:22px;padding:16px;"><b> ใบรับสินค้า</b></div>
                <div  style="font-size:16px;line-height:16px;padding:16px;"><b>  Page '.( $page_index + 1 ).'/'.$page_max.' </b></div>  
            </td>
        </tr>
    </table>
            

    <div style="line-height: 18px;" style="font-size:13px;" ><b>Head Office :</b> '.$company['company_address_en_1'].' '.$company['company_address_en_2'].' <br>'.$company['company_address_en_3'].' 
    Tel.'.$company['company_tel'].' Fax. '.$company['company_fax'].' Tax. '.$company['company_tax'].'</div>

 
    <table width="100%">
        <tr>
            <td './*style="border: 1px solid #000;border-radius: 8px;"*/'>
                <table width="100%">
                    <tr>
                        <td width="64px" valign="top" style="padding:4px;line-height: 18px;">
                        <b>Code</b>  
                        </td>
                        <td colspan="3" valign="top" style="padding:4px;line-height: 18px;">
                        '.$invoice_supplier['supplier_code'].'
                        </td> 
                    </tr>
                    <tr>
                        <td width="64px" valign="top" style="padding:4px;line-height: 18px;">
                        <b>Name</b>  
                        </td>
                        <td colspan="3" valign="top" style="padding:4px;line-height: 18px;">
                        '.$invoice_supplier['supplier_name_en'].' '.$branch.'
                        </td> 
                    </tr> 
                    <tr>
                        <td width="64px" valign="top" style="padding:4px;line-height: 18px;">
                        <b>Address</b> 
                        </td>
                        <td colspan="3" style="padding:4px;line-height: 18px;"> 
                            '. nl2br ( $invoice_supplier['supplier_address_1']).' <br> 
                            '. nl2br ( $invoice_supplier['supplier_address_2']).' <br> 
                            '. nl2br ( $invoice_supplier['supplier_address_3']).' '.$zipcode.'<br> 
                            Tax : '.$invoice_supplier_tax.'  
                        </td> 
                    </tr>
                    <tr>
                        <td width="64px" valign="top" style="padding:4px;line-height: 18px;">
                        <b>Tel.</b>  
                        </td>
                        <td  valign="top" style="padding:4px;line-height: 18px;">
                        '.$tel.'
                        </td> 
                        <td width="64px" valign="top" style="spadding:4px;line-height: 18px;">
                        <b>Fax.</b>  
                        </td>
                        <td  valign="top" style="padding:4px;line-height: 18px;">
                        '.$fax.'
                        </td> 
                    </tr> 
                </table> 
            </td>
            <td width="300" valign="top" './*style="border: 1px solid #000;border-radius: 8px;"*/'>
                <table width="100%">
                    <tr>
                        <td width="64px"  valign="middle" align="left">
                        <b>No.</b>
                        </td>
                        <td  valign="middle" align="left"> '.$invoice_supplier['invoice_supplier_code_gen'].'
                        
                        </td>
                    </tr>

                    
                    <tr>
                        
                        <td width="64px"  valign="middle" align="left">
                        <b>Date</b>
                        </td>
                        <td  valign="middle" align="left">
                         '.$invoice_supplier['invoice_supplier_date_recieve'].'
                        </td>

                    </tr>
                    <tr>                       
                        <td width="64px"  valign="middle" align="left">
                        <b>Credit </b>
                        </td>
                        <td  valign="middle" align="left">
                         '.$invoice_supplier['invoice_supplier_due_day'].' Day
                        </td>
                    </tr>

                    <tr>                       
                        <td width="64px"  valign="middle" align="left">
                        <b> Due </b>
                        </td>
                        <td  valign="middle" align="left">
                        '.$invoice_supplier['invoice_supplier_due'].'
                        </td>
                    </tr>

                    <tr>                       
                        <td width="100px"  valign="middle" align="left">
                        <b> Supplier Invoice </b>
                        </td>
                        <td   valign="middle" align="left">
                        '.$invoice_supplier['invoice_supplier_code'].'              
                        </td>
                     </tr>

                    <tr>                       
                        <td width="100px"  valign="middle" align="left">
                        <b> Purchase Order </b>
                        </td>
                        <td  valign="middle" align="left"> '.$purchaseOrder_code['purchase_order_code'].'                         
                        </td>
                    </tr>

                    <tr>
                        
                        <td width="64px"  valign="middle" align="left">
                        <b>Delivery  </b>
                        </td>
                        <td  valign="middle" align="left">
                        '.$invoice_supplier['invoice_supplier_delivery_by'].'
                        </td>

                    </tr> 
                    <tr>
                        
                        <td width="64px"  valign="middle" align="left">
                        <b>Prepared by </b>
                        </td>
                        <td  valign="middle" align="left">
                         '.$invoice_supplier['user_name'].' '.$lastname.'.
                        </td>
                        
                    </tr>
                </table>

            </td>
        </tr>
    </table> 

    <div>
        <table width="100%" class="table" celspacing="0">
            <thead>
                <tr style="border-bottom:1px dashed #000;border-top:1px dashed #000;">
                    <th style="text-align:center; padding:4px 0px;" >No.</th>
                    <th style="text-align:center; padding:4px 0px;" >Product name / Description</th>
                    <th style="text-align:center; padding:4px 0px;" >Stock</th>
                    <th style="text-align:center; padding:4px 0px;" >Qty</th>
                    <th style="text-align:center; padding:4px 0px;" >@</th>
                    <th style="text-align:center; padding:4px 0px;" >Amount </th> 
                </tr>
            </thead>

            <tbody  >
    ';
 
   
    //count($tax_reports)
    for($i=$page_index * $lines; $i < count($invoice_supplier_lists) && $i < $page_index * $lines + $lines; $i++){ 

        $total += $invoice_supplier_lists[$i]['invoice_supplier_list_total'];
                $html[$page_index] .= ' 
                <tr class="odd gradeX">
                <td align="center" valign="top" style="height:40px;width:48px;">
                    '.($i+1).'
                </td>
                <td align="left" valign="top" >
                   '. $invoice_supplier_lists[$i]['product_code'].' <br>
                   <span style="font-size:10px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $invoice_supplier_lists[$i]['product_name'].'</span>  
                </td> 
                <td align="center" valign="top" >
                   '. $invoice_supplier_lists[$i]['stock_group_code'].'  
                </td> 
                <td align="right" valign="top" width="80px">
                    '. number_format($invoice_supplier_lists[$i]['invoice_supplier_list_qty'],0).'
                </td> 
                <td align="right" valign="top" width="100px">
                    '. number_format($invoice_supplier_lists[$i]['invoice_supplier_list_price'],2).'
                </td>
                <td align="right" valign="top" width="120px">
                    '. number_format($invoice_supplier_lists[$i]['invoice_supplier_list_total'],2).'
                </td>
            </tr>
        ';
    }

 
    if($page_index + 1 == $page_max){
        if(count($invoice_supplier_lists) % $lines > 0){
            for($i = count($invoice_supplier_lists) % $lines ; $i < $lines; $i++){
                $html[$page_index] .= ' 
                    <tr class="odd gradeX">
                            <td align="center" style="height:40px;"> 
                            
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
        $vat_price =  number_format($total * $vat/100,2);
        $net_price = number_format($total+($total * $vat/100),2); 
        $str = '';//$number_2_text->convert_en($net_price);
    
        
    }else{
        $total_price = "-";
        $vat_price = "-";
        $net_price  ="-";
        $str="";
    }
   

    $html[$page_index] .= ' 
            </tbody>
            <tfoot>
                <tr class="odd gradeX" >
                    <td colspan="4" rowspan="2" align="left" valign="top">
                        <b>Remark</b> 
                    </td>
                    <td align="left">
                        <b>Total price</b>
                    </td>
                    <td style="text-align: right;" >
                    '.  $total_price .'
                    </td>
                </tr>    
                <tr class="odd gradeX" >
                    <td align="left"> 
                        <b>Vat '.number_format($vat).' % </b>
                    </td>
                    <td style="text-align: right;" >
                    '. $vat_price .'
                    </td>
                </tr>    
                <tr class="odd gradeX" >
                    <td colspan="4" align="center">
                        '. $str.'
                    </td>
                    <td align="left">
                        <b>Net price</b>
                    </td>
                    <td style="text-align: right;" >
                    '. $net_price .'
                    </td>
                </tr>    
            </tfoot>
        </table>
        
    </div>

    
    <table width="100%" >
        <tr>          
            <td style="border: 1px solid #000;border-radius: 8px;">
                <table width="100%" >
                    <tr>
                        <td style="height:50px;">
                        </td>
                    </tr>
                    <tr>
                        <td  align="center">
                        Receive by
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                        Date _____/________/_________
                        </td>
                    </tr>
                </table>
            </td>
            <td style="border: 1px solid #000;border-radius: 8px;">
                <table width="100%" >
                    <tr>
                        <td style="height:50px;">
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                        Authorized signature
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                             Date _____/________/_________
                        </td>
                    </tr>
                </table>
            </td>
        </tr> 
    </table>
    

                        
         
</div>
    ';

}

?>