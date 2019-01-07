<?PHP 

if( (int)$purchase_order['supplier_branch'] * 1 == 0){
    $branch = " สำนักงานใหญ่";
} else {
    $branch =  "สาขา " . ((int)$purchase_order['supplier_branch'] * 1) ;
} 

if($supplier['vat_type'] == '0'){
    $vat= '0';
}else{
    $vat = $purchase_order['vat'];
}

if($purchase_order['supplier_fax'] != ""){
    $fax = $purchase_order['supplier_fax'];
}else{
    $fax = "-";
}

if($purchase_order['supplier_tel'] != ""){
    $tel = $purchase_order['supplier_fax'];
}else{
    $tel = "-";
}

if($purchase_order['supplier_zipcode'] != ""){
    $zipcode = " ".$purchase_order['supplier_zipcode'];
}else{
    $zipcode = "";
}

if($purchase_order['purchase_order_tax'] != ""){
    $purchase_order_tax = " ".$purchase_order['purchase_order_tax'];
}else{
    $purchase_order_tax = "-";
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
        border-left :1px solid black; 
        border-right :1px solid black; 
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
                <div  style="font-size:16px;line-height:18px;padding:16px;"><b> ใบสั่งซื้อ </b></div> 
            </td>
        </tr>
    </table>
            

    <div style="line-height: 18px;" style="font-size:13px;" ><b>สำนักงานใหญ่ :</b> '.$company['company_address_1'].' '.$company['company_address_2'].' <br>'.$company['company_address_3'].' 
    Tel.'.$company['company_tel'].' Fax. '.$company['company_fax'].' Tax. '.$company['company_tax'].'</div>

 
    <table width="100%">
        <tr>
            <td style="border: 1px solid #000;border-radius: 8px;">
                <table width="100%">
                    <tr>
                        <td width="64px" valign="top" style="padding:4px;line-height: 18px;">
                        <b>รหัสผู้ขาย</b>  
                        </td>
                        <td colspan="3" valign="top" style="padding:4px;line-height: 18px;">
                        '.$purchase_order['supplier_code'].'
                        </td> 
                    </tr>
                    <tr>
                        <td width="64px" valign="top" style="padding:4px;line-height: 18px;">
                        <b>ชื่อผู้ขาย</b>  
                        </td>
                        <td colspan="3" valign="top" style="padding:4px;line-height: 18px;">
                        '.$purchase_order['supplier_name_en'].' '.$branch.'
                        </td> 
                    </tr> 
                    <tr>
                        <td width="64px" valign="top" style="padding:4px;line-height: 18px;">
                        <b>ที่อยู่</b> 
                        </td>
                        <td colspan="3" style="padding:4px;line-height: 18px;"> 
                            '. nl2br ( $purchase_order['supplier_address_1']).' <br> 
                            '. nl2br ( $purchase_order['supplier_address_2']).' <br> 
                            '. nl2br ( $purchase_order['supplier_address_3']).' '.$zipcode.'<br> 
                            Tax : '.$purchase_order_tax.'  
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
            <td width="240" valign="top" style="border: 1px solid #000;border-radius: 8px;">
                <table width="100%">
                    <tr>
                        <td width="64px"  valign="middle" align="left">
                        <b>เลขที่</b>
                        </td>
                        <td   valign="middle" align="left">
                            '.$purchase_order['purchase_order_code'].'
                        </td>
                    </tr>

                    
                    <tr>
                        
                        <td width="64px"  valign="middle" align="left">
                        <b>วันที่</b>
                        </td>
                        <td  valign="middle" align="left">
                         '.$purchase_order['purchase_order_date'].'
                        </td>

                    </tr>
                    <tr>
                        
                        <td width="64px"  valign="middle" align="left">
                        <b>เครดิต </b>
                        </td>
                        <td  valign="middle" align="left">
                         '.$purchase_order['purchase_order_credit_term'].' วัน
                        </td>

                    </tr>
                    <tr>
                        
                        <td width="64px"  valign="middle" align="left">
                        <b>การจัดส่ง </b>
                        </td>
                        <td  valign="middle" align="left">
                         '.$purchase_order['purchase_order_delivery_by'].'
                        </td>

                    </tr> 
                    <tr>
                        
                        <td width="64px"  valign="middle" align="left">
                        <b>พนักงาน </b>
                        </td>
                        <td  valign="middle" align="left">
                         '.$purchase_order['user_name'].' '.$purchase_order['user_lastname'].'
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
                    <th style="text-align:center; padding:4px 0px;" >ชื่อสินค้า / รายละเอียด</th>
                    <th style="text-align:center; padding:4px 0px;" >จำนวน</th>
                    <th style="text-align:center; padding:4px 0px;" >ราคา/หน่วย</th>
                    <th style="text-align:center; padding:4px 0px;" >ราคารวม </th> 
                </tr>
            </thead>

            <tbody  >
    ';
 
   
    //count($tax_reports)
    for($i=$page_index * $lines; $i < count($purchase_order_lists) && $i < $page_index * $lines + $lines; $i++){ 

        $total += $purchase_order_lists[$i]['purchase_order_list_price_sum'];
                $html[$page_index] .= ' 
                <tr class="odd gradeX">
                        <td align="center" valign="top" style="height:48px;width:48px;">
                            '.($i+1).'
                        </td>
                        <td align="left" valign="top" >
                           '. $purchase_order_lists[$i]['product_code'].' <br>
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $purchase_order_lists[$i]['product_name'].' <br>  
                        </td> 
                        <td align="right" valign="top" width="120px">
                            '. number_format($purchase_order_lists[$i]['purchase_order_list_qty'],0).'
                        </td> 
                        <td align="right" valign="top" width="120px">
                            '. number_format($purchase_order_lists[$i]['purchase_order_list_price'],2).'
                        </td>
                        <td align="right" valign="top" width="120px">
                            '. number_format($purchase_order_lists[$i]['purchase_order_list_price_sum'],2).'
                        </td>
                    </tr>
                ';
    }

 
    if($page_index + 1 == $page_max){
        if(count($purchase_order_lists) % $lines > 0){
            for($i = count($purchase_order_lists) % $lines ; $i < $lines; $i++){
                $html[$page_index] .= ' 
                    <tr class="odd gradeX">
                            <td align="center" style="height:48px;"> 
                            
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
        $str = $number_2_text->convert($net_price);
    
        
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
                    <td colspan="3" rowspan="2" align="left" valign="top">
                        <b>หมายเหตุ</b> 
                    </td>
                    <td align="left">
                        <b>รวมทั้งสิน</b>
                    </td>
                    <td style="text-align: right;" >
                    '.  $total_price .'
                    </td>
                </tr>    
                <tr class="odd gradeX" >
                    <td align="left"> 
                        <b>ภาษีมูลค่าเพิ่ม '.number_format($vat).' % </b>
                    </td>
                    <td style="text-align: right;" >
                    '. $vat_price .'
                    </td>
                </tr>    
                <tr class="odd gradeX" >
                    <td colspan="3" align="center">
                        '. $str.'
                    </td>
                    <td align="left">
                        <b>จำนวนเงินทั้งสิ้น</b>
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
            <td style="border: 1px solid #000;border-radius: 8px; " >
                <table width="100%" >
                    <tr>
                        <td style="height:64px;">
                        </td>
                    </tr>
                    <tr>
                        <td  align="center" >
                            ผู้รับคำสั่งซื้อ
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
                        <td style="height:64px;">
                        </td>
                    </tr>
                    <tr>
                        <td  align="center">
                            พนักงาน
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
                        <td style="height:64px;">
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                        ผู้อนุมัติ
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