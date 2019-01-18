<?PHP 

if( (int)$invoice_supplier_abroad['supplier_branch'] * 1 == 0){
    $branch = "";
} else {
    $branch =  "Branch " . ((int)$invoice_supplier_abroad['supplier_branch'] * 1) ;
} 

if($invoice_supplier_abroad['vat_type'] == '0'){
    $vat= '0';
}else{
    $vat = $invoice_supplier_abroad['vat'];
}

if($invoice_supplier_abroad['supplier_fax'] != ""){
    $fax = $invoice_supplier_abroad['supplier_fax'];
}else{
    $fax = "-";
}

if($invoice_supplier_abroad['supplier_tel'] != ""){
    $tel = $invoice_supplier_abroad['supplier_fax'];
}else{
    $tel = "-";
}

if($invoice_supplier_abroad['supplier_zipcode'] != ""){
    $zipcode = " ".$invoice_supplier_abroad['supplier_zipcode'];
}else{
    $zipcode = "";
}

if($invoice_supplier_abroad['invoice_supplier_tax'] != ""){
    $invoice_supplier_abroad_tax = " ".$invoice_supplier_abroad['invoice_supplier_tax'];
}else{
    $invoice_supplier_abroad_tax = "-";
}

$lastname =  substr($invoice_supplier_abroad['user_lastname'],0,1);

$$total = 0;
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
        border-top :1px solid black;  
        border-bottom :1px solid black;  
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
        <tr>
            <td width="120px">
                <img src="../upload/company/'.$company['company_image'].'" width="120px" />
            </td>
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
            

    <div style="line-height: 18px;" style="font-size:13px;" ><b>สำนักงานใหญ่ :</b> '.$company['company_address_1'].' '.$company['company_address_2'].' <br>'.$company['company_address_3'].' 
    Tel.'.$company['company_tel'].' Fax. '.$company['company_fax'].' Tax. '.$company['company_tax'].'</div>

 
    <table width="100%">
        <tr>
            <td './*style="border: 1px solid #000;border-radius: 8px;"*/'>
                <table width="100%">
                    <tr>
                        <td width="64px" valign="top" style="padding:4px;line-height: 18px;">
                        <b>รหัสผู้ขาย</b>  
                        </td>
                        <td colspan="3" valign="top" style="padding:4px;line-height: 18px;">
                        '.$invoice_supplier_abroad['supplier_code'].'
                        </td> 
                    </tr>
                    <tr>
                        <td width="64px" valign="top" style="padding:4px;line-height: 18px;">
                        <b>ชื่อผู้ขาย</b>  
                        </td>
                        <td colspan="3" valign="top" style="padding:4px;line-height: 18px;">
                        '.$invoice_supplier_abroad['supplier_name_en'].' '.$branch.'
                        </td> 
                    </tr> 
                    <tr>
                        <td width="64px" valign="top" style="padding:4px;line-height: 18px;">
                        <b>ที่อยู่</b> 
                        </td>
                        <td colspan="3" style="padding:4px;line-height: 18px;"> 
                            '. nl2br ( $invoice_supplier_abroad['supplier_address_1']).' <br> 
                            '. nl2br ( $invoice_supplier_abroad['supplier_address_2']).' <br> 
                            '. nl2br ( $invoice_supplier_abroad['supplier_address_3']).' '.$zipcode.'<br> 
                            Tax : '.$invoice_supplier_abroad_tax.'  
                        </td> 
                    </tr>
                    <tr>
                        <td width="64px" valign="top" style="padding:4px;line-height: 18px;">
                        <b>โทร.</b>  
                        </td>
                        <td  valign="top" style="padding:4px;line-height: 18px;">
                        '.$tel.'
                        </td> 
                        <td width="64px" valign="top" style="spadding:4px;line-height: 18px;">
                        <b>โทรสาร.</b>  
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
                        <b>เลขที่</b>
                        </td>
                        <td  valign="middle" align="left">
                        '.$invoice_supplier_abroad['invoice_supplier_stock'].'
                        </td>
                        
                    </tr>

                    
                    <tr>                      
                        <td width="64px"  valign="middle" align="left">
                        <b>วันที่</b>
                        </td>
                        <td  valign="middle" align="left">
                         '.$invoice_supplier_abroad['invoice_supplier_date_recieve'].'
                        </td>
                    </tr>
                  
                    <tr>
                        
                        <td width="64px"  valign="middle" align="left">
                        <b>เครดิต </b>
                        </td>
                        <td  valign="middle" align="left">
                         '.$invoice_supplier_abroad['invoice_supplier_due_day'].' วัน
                        </td>

                    </tr>

                    <tr>                      
                        <td width="64px"  valign="middle" align="left">
                        <b>ครบกำหนด</b>
                        </td>
                        <td  valign="middle" align="left">
                         '.$invoice_supplier_abroad['invoice_supplier_due'].'
                        </td>
                    </tr>
                    
                    
                    <tr>                  
                        <td width="110px"  valign="middle" align="left">
                        <b>ใบกำกับภาษี </b>
                        </td>
                        
                        <td   valign="middle" align="left">
                            '.$invoice_supplier_abroad['invoice_supplier_code'].'
                        </td>
                    </tr>
                      
                    <tr>                  
                        <td width="64px"  valign="middle" align="left">
                        <b>ใบสั่งซื้อ </b>
                        </td>
                        <td  valign="middle" align="left"> '.$purchaseOrder_code['purchase_order_code'].' </td>
                                      
                    </tr>
                    <tr>                  
                        <td width="64px"  valign="middle" align="left">
                        <b>ผู้นำเข้าข้อมูล </b>
                        </td>
                        <td  valign="middle" align="left">
                         '.$invoice_supplier_abroad['user_name'].' 
                        </td>
                    </tr>
                    <tr>                  
                        <td width="64px"  valign="middle" align="left">
                        <b>อัตราแลกเปลี่ยน </b>
                        </td>
                        <td  valign="middle" align="left">
                         '.number_format($exchange_rate_baht['exchange_rate_baht_value'],5).' 
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
                    <th style="text-align:center; padding:4px 0px;" >Price/Pcs '.$currency['currency_code'].'</th> 
                    <th style="text-align:center; padding:4px 0px;" >Price/Pcs Baht</th> 
                    <th style="text-align:center; padding:4px 0px;" >Amount </th> 
                    <th style="text-align:center; padding:4px 0px;" >Import duty</th>
                    <th style="text-align:center; padding:4px 0px;" >Freight in</th>
                    <th style="text-align:center; padding:4px 0px;" >Cost/Pcs </th> 
                    <th style="text-align:center; padding:4px 0px;" >Cost total </th> 
                </tr>
            </thead>

            <tbody  >
    ';
 
    
    //count($tax_reports)
    for($i=$page_index * $lines; $i < count($invoice_supplier_abroad_lists) && $i < $page_index * $lines + $lines; $i++){ 
        $invoice_supplier_list_cost_total =$invoice_supplier_abroad_lists[$i]['invoice_supplier_list_cost'] * $invoice_supplier_abroad_lists[$i]['invoice_supplier_list_qty'];
        $invoice_supplier_list_import_duty =$invoice_supplier_abroad_lists[$i]['invoice_supplier_list_import_duty'] * $invoice_supplier_abroad_lists[$i]['invoice_supplier_list_qty'];
        $invoice_supplier_list_freight_in =$invoice_supplier_abroad_lists[$i]['invoice_supplier_list_freight_in'] * $invoice_supplier_abroad_lists[$i]['invoice_supplier_list_qty'];

        $total += $invoice_supplier_list_cost_total;
        $import_duty_total += $invoice_supplier_list_import_duty;
        $freight_in_total += $invoice_supplier_list_freight_in;

                $html[$page_index] .= ' 
                <tr class="odd gradeX">
                <td align="center" valign="top" style="height:48px;width:48px;">
                    '.($i+1).'
                </td>
                <td align="left" valign="top" >
                   '. $invoice_supplier_abroad_lists[$i]['product_code'].' <br>
                   <span style="font-size:10px;"> '. $invoice_supplier_abroad_lists[$i]['product_name'].'</span>  
                </td> 
                <td align="center" valign="top" >
                    '. $invoice_supplier_abroad_lists[$i]['stock_group_code'].'
                </td> 
                <td align="right" valign="top">
                    '. number_format($invoice_supplier_abroad_lists[$i]['invoice_supplier_list_qty'],0).'
                </td> 
                <td align="right" valign="top" width="70px">
                    '. number_format($invoice_supplier_abroad_lists[$i]['invoice_supplier_list_currency_price'],2).'
                </td>
                <td align="right" valign="top" width="70px">
                    '. number_format($invoice_supplier_abroad_lists[$i]['invoice_supplier_list_price'],2).'
                </td>
                <td align="right" valign="top" >
                    '. number_format($invoice_supplier_abroad_lists[$i]['invoice_supplier_list_total'],2).'
                </td>
                <td align="right" valign="top" >
                    '. number_format($invoice_supplier_list_import_duty,2).'
                </td>
                <td align="right" valign="top" >
                    '. number_format($invoice_supplier_list_freight_in,2).'
                </td>
                <td align="right" valign="top" >
                    '. number_format($invoice_supplier_abroad_lists[$i]['invoice_supplier_list_cost'],2).'
                </td>
                <td align="right" valign="top" >
                    '. number_format($invoice_supplier_list_cost_total,2).'
                </td>                
            </tr>
        ';
    }

 
    if($page_index + 1 == $page_max){
        if(count($invoice_supplier_abroad_lists) % $lines > 0){
            for($i = count($invoice_supplier_abroad_lists) % $lines ; $i < $lines; $i++){
                $html[$page_index] .= ' 
                    <tr class="odd gradeX">
                            <td align="center" style="height:48px;"> 
                            
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
                            <td align="right">
                                 
                            </td>
                            <td align="right">
                                 
                            </td>
                            <td align="right">
                                 
                            </td>
                        </tr>
                    ';
            }
        }  
    
        $import_duty = number_format($import_duty_total,2);
        $freight_in = number_format($freight_in_total,2) ;
        $invoice_supplier_total_price = number_format($invoice_supplier_abroad['invoice_supplier_total_price'],2);
        $total_cost = number_format($total,2);
    }else{ 
        $import_duty = "-";
        $freight_in = "-";
        $invoice_supplier_total_price = "-";
        $total_cost = "-";
    }
   

    $html[$page_index] .= ' 
            </tbody>
            <tfoot>
                <tr class="odd gradeX" >
                    <td colspan="5" align="left" valign="top">
                        <b>Remark</b> 
                    </td>
                    <td align="left" colspan="2" >
                        <b>Summation </b> 
                    </td>
                    <td style="text-align: right;" >
                    '.  $invoice_supplier_total_price  .'
                    </td>
                    <td style="text-align: right;" >
                    '.  $import_duty  .'
                    </td>
                    <td style="text-align: right;" >
                    '. $freight_in .'
                    </td>
                    <td style="text-align: right;" >
                    '. $total_cost  .'
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
                        ผู้รับสินค้า
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
                        ผู้ตรวจสอบ
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