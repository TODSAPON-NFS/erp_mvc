<?PHP 

$i = 0;  
$html_head_pdf = '   
<table  width="100%" cellspacing="0" style="" > 
    <thead>
        <tr>
            <td colspan="3"><b>'.$company['company_name_th'].'</b></td>  
        </tr> 
        <tr>
            <td colspan="3" align="center" style="font-size:14px;color:#00F;"><b>รายงานยอดขาย</b></td>  
        </tr> 
       <tr>
          <td>
            <b>ชื่อสถานประกอบการ</b>
          </td>  
            <td>
            '.$company['company_name_th'].'
            </td>
       </tr>
       <tr>
            <td align="left" >
                <b>
                    ที่อยู่สถานประกอบการ
                </b> 
            </td>
            <td> 
                '.$company['company_address_1'].' '.$company['company_address_2'].' '.$company['company_address_3'].'
            </td>
        </tr> 
        
        <tr>
            <td align="left" >
                <b>
                     เลขประจำตัวผู้เสียภาษีอาการ
                 </b> 
            </td>
            <td> 
                '.$company['company_tax'].' <b>สำนักงาน</b> '.$company['company_branch'].' 
            </td>
            <td >  
            
            </td>
        </tr>

        
    </thead>
</table>  
';
$html_head_excel = '  
<table  width="100%" cellspacing="0" style="" > 
    <thead>
        <tr>
            <td colspan="3" align="center" style="font-size:14px;color:#00F;"><b>รายงานยอดขาย</b></td>  
        </tr> 
       <tr>
          <td>
            <b>ชื่อสถานประกอบการ</b>
          </td>  
            <td>
            '.$company['company_name_th'].'
            </td>
       </tr>
       <tr>
            <td align="left"  >
                <b>
                    ที่อยู่สถานประกอบการ
                </b> 
            </td>
            <td colspan="2" > 
                '.$company['company_address_1'].' '.$company['company_address_2'].' '.$company['company_address_3'].'
            </td>
        </tr> 
        
        <tr>
            <td align="left" >
                <b>
                     เลขประจำตัวผู้เสียภาษีอาการ
                 </b> 
            </td>
            <td> 
                '.$company['company_tax'].' <b>สำนักงาน</b> '.$company['company_branch'].' 
            </td>
            <td >  
            
            </td>
        </tr>

        
    </thead>
</table>  
';
$html = '<style>
        div{
            font-size:10px;
        }
        .table, .table thead th, .table tbody td{
            border: 0.2px solid black;
        }

        th{
            padding:4px 4px;
            font-size:10px;
            padding-top:6px;
            padding-bottom:5px;
        }

        td{
            padding:4px 4px;
            font-size:10px;
        }

    </style>'; 
while($i < count($journal_reports )){



    $html .= '
    <table width="100%" cellspacing="0" > 
        <thead>
            <tr>  
                <th width="80" style="border-top:1px solid black;border-bottom: 1px solid black;">พนักงาน</th>     
                <th style="border-top:1px solid black;border-bottom: 1px solid black;">ชื่อลูกค้า</th>    
                <th style="border-top:1px solid black;border-bottom: 1px solid black;">จำนวนเงิน
                </th>  
            </tr>
            
        </thead>
        <tbody>

    ';
    
        
    for(; $i < count($journal_reports); $i++){
       
        if($journal_reports[$i]['user_username'] == $journal_reports[$i]['user_username'] && $journal_reports[$i]['user_username'] != null) {

            $sum +=  $journal_reports[$i]['invoice_customer_net_price'];
       
        $html .= '
        
        <tr>
        <td>
           '. 
             $journal_reports[$i]['user_username']
           .'
        </td>
        <td>
           '.
             $journal_reports[$i]['customer_name_en']
           .'
        </td>
        <td align="right">
           '.
             number_format ($journal_reports[$i]['invoice_customer_net_price'],2)
           .'
        </td>
    </tr>
    
    <tr>
        ';  

        
    }
 
    if($journal_reports[$i]['user_username'] != $journal_reports[$i+1]['user_username']  && $journal_reports[$i]['user_username'] != null) {
        $sum_sum = $sum;

        $html .= '
        
                            
        <tr >
            <td colspan="3" >

            </td>
        </tr>

        <tr>
            <td colspan="2"   style="border-top:1px solid black;border-bottom: 1px solid black;" align="center">
                <b> 
                    <font color="black">     
                        รวม
                    </font>
                </b>
            </td>
            <td align="right"  style="border-top:1px solid black;border-bottom: 1px solid black;">
            <b> 
                <font color="black">     
                '. number_format($sum_sum,2)
                .'
                </font>
            </b>
            </td>       
        </tr>
        
        <tr class="">
            <td colspan="3" >

            </td>
        </tr>
    </tr>
        ';


        $sum = 0;
                                        }

                        }

        $html .= ' 
        
            </tbody>
           
        </table>
        ';
    
        // $html .= ' 
        
        //     </tbody>
        //     <tfoot> 
        //         <tr >  
        //             <td align="center" colspan="3" style="padding-top:15px;"><font color="black">********* จบรายงาน *********</font></td>  
        //         </tr>
        //     </tfoot>
        // </table>
        // ';
    

} 

?>