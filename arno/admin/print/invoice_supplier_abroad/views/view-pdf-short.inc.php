<?PHP 

if( (int)$invoice_supplier_abroad['supplier_branch'] * 1 == 0){
    $branch = " สำนักงานใหญ่";
} else {
    $branch =  "สาขา " . ((int)$invoice_supplier_abroad['supplier_branch'] * 1) ;
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
    $tel = $invoice_supplier_abroad['supplier_tel'];
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



$total = 0;
$total_baht = 0;
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
                <div  style="font-size:22px;line-height:22px;padding:16px;"><b> ใบตั้งเจ้าหนี้ต่างประเทศ</b></div>
                <div  style="font-size:16px;line-height:16px;padding:16px;"><b> '.( $page_index + 1 ).'/'.$page_max.' หน้า</b></div>  
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
                        '.$invoice_supplier_abroad['invoice_supplier_code_gen'].'
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
                        <b>การจัดส่ง </b>
                        </td>
                        <td  valign="middle" align="left">
                         '.$invoice_supplier_abroad['purchase_order_delivery_by'].'
                        </td>

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
                    <th style="text-align:center; padding:4px 0px;" >ลำดับ</th>
                    <th style="text-align:center; padding:4px 0px;" >รายการ</th>
                    <th style="text-align:center; padding:4px 0px;" >ราคารวม <br>'.$invoice_supplier_abroad['currency_code'].' </th> 
                    <th style="text-align:center; padding:4px 0px;" >อัตราแลกเปลี่ยน<br>บาท</th>
                    <th style="text-align:center; padding:4px 0px;" >ราคารวม<br>บาท </th> 
                </tr>
            </thead>

            <tbody  >
    ';
    for($i=0;$i<count($invoice_supplier_abroad_lists);$i++){
        $total += $invoice_supplier_abroad_lists[$i]['invoice_supplier_list_currency_price']*$invoice_supplier_abroad_lists[$i]['invoice_supplier_list_qty'];
        $total_baht += $invoice_supplier_abroad_lists[$i]['invoice_supplier_list_total'];
    }
    $total_price = number_format($total,2); 
    $total_price_baht = number_format($total_baht,2); 
    $vat_price =  number_format($total * $vat/100,2);
    $vat_price_baht =  number_format($total_baht * $vat/100,2);
    $net_price = number_format($total+($total * $vat/100),2); 
    $net_price_baht = number_format($total_baht+($total_baht * $vat/100),2); 
    $str = $number_2_text->convert($net_price_baht);
    //count($tax_reports)
    for($i=$page_index * $lines; $i < 1 && $i < $page_index * $lines + $lines; $i++){ 

        
                $html[$page_index] .= ' 
                <tr  class="odd gradeX">
                        <td align="center" valign="top" style="height:48px;width:48px;">
                            '.($i+1).'
                        </td>
                        <td align="left" valign="top" >
                        จ่ายค่าสินค้าต่างประเทศ :
                        '.$invoice_supplier_abroad['invoice_supplier_code_gen'].' <br> 
                        </td> 
                        <td align="right" valign="top">
                            '. $total_price.'
                        </td> 
                        <td align="right" valign="top" >
                        '.number_format($exchange_rate_baht['exchange_rate_baht_value'],5).' 
                        </td>
                        <td align="right" valign="top" width="120">
                            '.$total_price_baht.'
                        </td>
                    </tr>
                ';
    }

 
    if($page_index + 1 == $page_max){
        if(1 % $lines > 0){
            for($i = 1 % $lines ; $i < $lines; $i++){
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
                            
                        </tr>
                    ';
            }
        } 
        
    
        
    }else{
        // $total_price = "-";
        // $total_price_baht = "-";
        // $vat_price = "-";
        // $vat_price_baht = "-";
        // $net_price  ="-";
        // $net_price_baht  ="-";
        // $str="";
    }
   

    $html[$page_index] .= ' 
            </tbody>
            <tfoot>
                <tr class="odd gradeX" > 
                    <td colspan="2" rowspan="2" align="left" valign="top">
                        <b>หมายเหตุ</b> 
                    </td>
                    <td align="left" colspan="2"> 
                        <b>รวมทั้งสิ้น </b>
                    </td>
                    <td style="text-align: right;" >
                    '. $total_price_baht .'
                    </td>
                </tr>    
                <tr class="odd gradeX" >
                    <td align="left" colspan="2">
                        <b>ภาษีมูลค่าเพิ่ม '.number_format($vat).' % </b>
                    </td>
                    <td style="text-align: right;" >
                    '. $vat_price_baht .'
                    </td>
                </tr>    
                <tr class="odd gradeX" >
                    <td colspan="2" align="center">
                        '. $str.'
                    </td>
                    <td align="left" colspan="2">
                        <b>จำนวนเงินทั้งสิ้น </b>
                    </td>
                    <td style="text-align: right;" >
                    '. $net_price_baht .'
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
                        <td style="height:40px;">
                        </td>
                    </tr>
                    <tr>
                        <td  align="center">
                            ผู้รับสินค้า
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                        วันที่ _____/________/_________
                        </td>
                    </tr>
                </table>
            </td>
            <td style="border: 1px solid #000;border-radius: 8px;">
                <table width="100%" >
                    <tr>
                        <td style="height:40px;">
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                        ผู้ตรวจสอบ
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                             วันที่ _____/________/_________
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