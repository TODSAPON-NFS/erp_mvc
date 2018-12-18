<?PHP 

if( (int)$delivery_note_customer['customer_branch'] * 1 == 0){
    $branch = " สำนักงานใหญ่";
} else {
    $branch =  "สาขา " . ((int)$delivery_note_customer['customer_branch'] * 1) ;
} 


$total = 0;
for($page_index=0 ; $page_index < $page_max ; $page_index++){

    $html[$page_index] = '
<style>
    .main{
        font-size:10px;
    }

    div{
        display:block;
        padding:4px;
        font-size:10px;
    }

    .table thead th , .table tfoot td{
        border :1px solid black; 
        border-collapse: collapse;
        height:16px;
    }

    .table, .table tbody td{
        border-left:1px solid black;
        border-right:1px solid black;
        border-collapse: collapse;
        height:16px;
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
        font-size:10px;
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
            <td width="120px">
                <img src="../upload/company/'.$company['company_image'].'" width="120px" />
            </td>
            <td>
                <div style="font-size:12px;">'.$company['company_name_en'].'</div>
                <div style="font-size:12px;">'.$company['company_name_th'].'</div>
            </td>
        </tr>
    </table>

    <div>สำนักงานใหญ่ : '.$company['company_address_1'].' '.$company['company_address_2'].' <br>'.$company['company_address_3'].' 
    Tel.'.$company['company_tel'].' Fax. '.$company['company_fax'].'</div>
    <div align="center" style="font-size:12px;">ใบยืมสินค้าสำหรับลูกค้า </div>
    <div align="center" style="font-size:12px;">DERIVERY NOTE FOR CUSTOMER</div>

    <div>
        <table width="100%" heigth="180">
            <tr>
                <td style="padding:4px;">
                    <table width="100%">
                        <tr><td width="100" height="24" valign="middle" align="left">  '.$delivery_note_customer['contact_name'].' </td></tr>
                        <tr><td width="100" height="24" valign="middle" align="left">  '.$delivery_note_customer['customer_name_en'].'  '.$branch.' </td></tr>
                        <tr><td width="100" height="24" valign="middle" align="left">  '. $delivery_note_customer['customer_address_1'].' '. $delivery_note_customer['customer_address_2'].' '. $delivery_note_customer['customer_address_3'].'</td></tr>
                        <tr><td width="100" height="24" valign="middle" align="left">  เลขประจำตัวผู้เสียภาษี / Tax : '.$delivery_note_customer['customer_tax'].' </td></tr>
                    </table>
                </td>
                <td width="240">
                    <table width="100%">
                        <tr>
                            <td width="100" height="24" valign="middle" align="left">
                            No.
                            </td>
                            <td width="100" height="24" valign="middle" align="left">
                               : '.$delivery_note_customer['delivery_note_customer_code'].'
                            </td>
                        </tr>

                       
                        <tr>
                            
                            <td width="100" height="24" valign="middle" align="left">
                            Date
                            </td>
                            <td width="100" height="24" valign="middle" align="left">
                            : '.$delivery_note_customer['delivery_note_customer_date'].'
                            </td>

                        </tr>
                        <tr>
                            
                            <td width="100" height="24" valign="middle" align="left">
                            Customer code
                            </td>
                            <td width="140" height="24" valign="middle" align="left">
                            : '.$delivery_note_customer['customer_code'].'
                            </td>

                        </tr>
                        <tr>
                            
                            <td width="100" height="24" valign="middle" align="left">
                            Employee 
                            </td>
                            <td width="140" height="24" valign="middle" align="left">
                            : '.$delivery_note_customer['user_name'].' '.$delivery_note_customer['user_lastname'].'
                            </td>

                        </tr>
                    </table>

                </td>
            </tr>
        </table>
    </div>

    <div>
        <table width="100%" class="table" celspacing="0">
            <thead>
                <tr>
                    <th style="text-align:center;" width="48">ลำดับ<br>(No.)</th>
                    <th style="text-align:center;">รหัสสินค้า<br>(Product Code)</th>
                    <th style="text-align:center;">ชื่อสินค้า<br>(Product Name)</th>
                    <th style="text-align:center;">จำนวน<br>(Qty)</th>
                    <th style="text-align:center;">หมายเหตุ<br>(Remark)</th>
                </tr>
            </thead> 
            <tbody  >
    ';
 
   
    //count($tax_reports)
    for($i=$page_index * $lines; $i < count($delivery_note_customer_lists) && $i < $page_index * $lines + $lines; $i++){ 

        $total += $delivery_note_customer_lists[$i]['delivery_note_customer_bal_amount'];
                $html[$page_index] .= ' 
                <tr class="odd gradeX">
                    <td style="text-align:center;" >
                        '.($i+1).'
                    </td>
                    <td>
                        '.$delivery_note_customer_lists[$i]['product_code'].'
                    </td>
                    <td>'.$delivery_note_customer_lists[$i]['product_name'].'</td>
                    <td align="right">'.number_format($delivery_note_customer_lists[$i]['delivery_note_customer_list_qty'],2).'</td>
                    <td >'.$delivery_note_customer_lists[$i]['delivery_note_customer_list_remark'].'</td>
                </tr>
                ';
    }

 
    if($page_index + 1 == $page_max){
        if(count($delivery_note_customer_lists) % $lines > 0){
            for($i = count($delivery_note_customer_lists) % $lines ; $i < $lines; $i++){
                $html[$page_index] .= ' 
                    <tr class="odd gradeX">
                            <td align="center"> 

                            </td>
                            <td align="center">
                                 
                            </td>
                            <td align="center">
                                 
                            </td>
                            <td align="center">
                                
                            </td>
                            <td align="center">
                               
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
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div style="margin-top:64px;">
        <table width="100%" >
            <tr>
                <td align="center" valign="bottom" > ................................................ </td>
                <td align="center" valign="bottom" > </td>
                <td align="center" valign="bottom" > <img src="'.$delivery_note_customer['user_signature'].'" height="96" /><br> ................................................ </td>
            </tr>
            <tr>
                <td align="center"> Contact </td>
                <td align="center"> </td>
                <td align="center"> Authorized Signature </td>
            </tr>
        </table>
    </div>
</div>
    ';

}

?>