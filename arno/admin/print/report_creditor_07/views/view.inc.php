<?PHP 


$paper_number_total = 0;   
$due_comming_more_than_60_total = 0;  
$due_comming_in_60_total = 0;  
$due_comming_in_30_total = 0;  
$over_due_1_to_30_total = 0;  
$over_due_31_to_60_total = 0;  
$over_due_61_to_90_total = 0;  
$over_due_more_than_90_total = 0;  
$balance_total = 0; 
for($page_index=0 ; $page_index < $page_max ; $page_index++){

    $html[$page_index] = '<style>
        div{
            font-size:10px;
        }
        .table, .table thead th, .table tbody td{
            border: 1px solid black;
        }

        th{
            padding:4px 4px;
            font-size:10px;
        }

        td{
            padding:4px 4px;
            font-size:10px;
        }

    </style>';

    $html[$page_index] .= '
    <table width="100%">
        <tr>
            <td>
                <div><b>เดือน/ปีภาษี</b> '.$section_date.' (<b>เริ่มจาก</b> '.$date_start.' <b>ถึง</b> '.$date_end.') </div>
            </td>
            <td align="left"  align="left" width="120px" >
                
            </td>
        </tr>
    </table>
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานวิเคราะห์อายุเจ้าหนี้ แบบย่อ </b></div>
    <table width="100%" border="0" cellspacing="0">
        <tr>
            <td align="left" width="140px" ><b>ชื่อสถานประกอบการ </b></td>
            <td> '.$company['company_name_th'].'</td>
            <td align="left"  align="left" width="120px" ><b>หน้า</b> : '.($page_index + 1).' / '.$page_max.'</td>
        </tr>
        <tr>
            <td align="left" ><b>ที่อยู่สถานประกอบการ</b> </td>
            <td> '.$company['company_address_1'].' '.$company['company_address_2'].' '.$company['company_address_3'].'</td>
            <td ></td>
        </tr> 
        <tr>
            <td align="left" ><b>เลขประจำตัวผู้เสียภาษีอาการ</b> </td>
            <td> '.$company['company_tax'].' <b>สำนักงาน</b> '.$company['company_branch'].' </td>
            <td >  </td>
        </tr>
    </table>  
    ';

    $html[$page_index] .= '
    <table  width="100%" cellspacing="0" style="font-size:12px;">
        <thead>
            <tr>
                <th style="border-top: 1px dotted black;" width="48" align="center" > ลำดับ </th> 
                <th style="border-top: 1px dotted black;" align="center" >ชื่อผู้ซื้อสินค้า/ผู้รับบริการ</th> 
                <th style="border-top: 1px dotted black;" align="center" >จำนวนเอกสาร</th> 
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" colspan="3" align="center" >จะครบกำหนด</th>  
                <th style="border-top: 1px dotted black;border-bottom: 1px dotted black;" colspan="4" align="center" >เกินกำหนด</th>
                <th style="border-top: 1px dotted black;" align="center" >ยอดคงค้าง</th>  
            </tr>
            <tr> 
                <th style="border-bottom: 1px dotted black;" ></th> 
                <th style="border-bottom: 1px dotted black;" ></th>
                <th style="border-bottom: 1px dotted black;" align="center" ></th>   
                <th style="border-bottom: 1px dotted black;" align="center" >เกิน 60 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" >ภายใน 60 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" >ภายใน 30 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" >1 - 30 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" >31 - 60 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" >61 - 90 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" >เกิน 90 วัน</th>  
                <th style="border-bottom: 1px dotted black;" align="center" ></th>  
            </tr>
        </thead>

        <tbody>

    ';
 
    
    $paper_number = 0;   
    $due_comming_more_than_60 = 0;  
    $due_comming_in_60 = 0;  
    $due_comming_in_30 = 0;  
    $over_due_1_to_30 = 0;  
    $over_due_31_to_60 = 0;  
    $over_due_61_to_90 = 0;  
    $over_due_more_than_90 = 0;  
    $balance = 0; 

    for($i=$page_index * $lines; $i < count($creditor_reports) && $i < $page_index * $lines + $lines; $i++){
        $branch = (int)$creditor_reports[$i]['supplier_branch'];
        if($branch == 0){
            $branch_main = "/";
            $branch_sub = "";
        }else{
            $branch_main = "";
            $branch_sub = $branch;
        }
        $paper_number +=  $creditor_reports[$i]['paper_number']; 
        $due_comming_more_than_60 +=  $creditor_reports[$i]['due_comming_more_than_60'];  
        $due_comming_in_60 +=  $creditor_reports[$i]['due_comming_in_60'];  
        $due_comming_in_30 +=  $creditor_reports[$i]['due_comming_in_30'];  
        $over_due_1_to_30 +=  $creditor_reports[$i]['over_due_1_to_30'];  
        $over_due_31_to_60 +=  $creditor_reports[$i]['over_due_31_to_60'];  
        $over_due_61_to_90 +=  $creditor_reports[$i]['over_due_61_to_90'];  
        $over_due_more_than_90 +=  $creditor_reports[$i]['over_due_more_than_90'];  
        $balance +=  $creditor_reports[$i]['balance'];  
        
        $paper_number_total +=  $creditor_reports[$i]['paper_number']; 
        $due_comming_more_than_60_total +=  $creditor_reports[$i]['due_comming_more_than_60'];  
        $due_comming_in_60_total +=  $creditor_reports[$i]['due_comming_in_60'];  
        $due_comming_in_30_total +=  $creditor_reports[$i]['due_comming_in_30'];  
        $over_due_1_to_30_total +=  $creditor_reports[$i]['over_due_1_to_30'];  
        $over_due_31_to_60_total +=  $creditor_reports[$i]['over_due_31_to_60'];  
        $over_due_61_to_90_total +=  $creditor_reports[$i]['over_due_61_to_90'];  
        $over_due_more_than_90_total +=  $creditor_reports[$i]['over_due_more_than_90'];  
        $balance_total +=  $creditor_reports[$i]['balance'];  


                $html[$page_index] .= ' 
                <tr >
                    <td>'. ($i+1).'</td>
                    <td>'. $creditor_reports[$i]['supplier_name_en'].' </td>  
                    <td  align="right" >
                        '. number_format($creditor_reports[$i]['paper_number'],0).'
                    </td>
                    <td  align="right" >
                        '. number_format($creditor_reports[$i]['due_comming_more_than_60'],2).'
                    </td> 
                    <td  align="right" >
                        '. number_format($creditor_reports[$i]['due_comming_in_60'],2).'
                    </td>  
                    <td  align="right" >
                        '. number_format($creditor_reports[$i]['due_comming_in_30'],2).'
                    </td>  
                    <td  align="right" >
                        '. number_format($creditor_reports[$i]['over_due_1_to_30'],2).'
                    </td>  
                    <td  align="right" >
                        '. number_format($creditor_reports[$i]['over_due_31_to_60'],2).'
                    </td>  
                    <td  align="right" >
                        '. number_format($creditor_reports[$i]['over_due_61_to_90'],2).'
                    </td> 
                    <td  align="right" >
                        '. number_format($creditor_reports[$i]['over_due_more_than_90'],2).'
                    </td> 
                    <td  align="right" >
                        '. number_format($creditor_reports[$i]['balance'],0).'
                    </td>  
                </tr>
                ';
    }

    if($page_index+1 < $page_max ){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td  align="left"> <b>รวมแต่ละหน้า</b> </td> 
                        <td  align="right" ><b>'.number_format($paper_number,0).'</b></td> 
                        <td  align="right" ><b>'.number_format($due_comming_more_than_60,2).'</b></td>  
                        <td  align="right" ><b>'.number_format($due_comming_in_60,2).'</b></td>  
                        <td  align="right" ><b>'.number_format($due_comming_in_30,2).'</b></td>  
                        <td  align="right" ><b>'.number_format($over_due_1_to_30,2).'</b></td>  
                        <td  align="right" ><b>'.number_format($over_due_31_to_60,2).'</b></td>  
                        <td  align="right" ><b>'.number_format($over_due_61_to_90,2).'</b></td>  
                        <td  align="right" ><b>'.number_format($over_due_more_than_90,2).'</b></td>
                        <td  align="right" ><b>'.number_format($balance,2).'</b></td> 
                    </tr>
                </tfoot>
            </table>
        ';
    }else if($page_index == 0){
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>  
                    <tr>
                        <td></td>
                        <td  align="left"><div><b>รวมทั้งสิ้น ถึง</b> '.$date_end.' </div> </td>
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($paper_number_total,0).'</b></td> 
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($due_comming_more_than_60_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($due_comming_in_60_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($due_comming_in_30_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($over_due_1_to_30_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($over_due_31_to_60_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($over_due_61_to_90_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($over_due_more_than_90_total,2).'</b></td>
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($balance_total,2).'</b></td>  
                    </tr>
                </tfoot>
            </table>
        ';
    }else{
        $html[$page_index] .= ' 
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td  align="left"> <b>รวมแต่ละหน้า</b> </td> 
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($paper_number,0).'</b></td> 
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($due_comming_more_than_60,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($due_comming_in_60,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($due_comming_in_30,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($over_due_1_to_30,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($over_due_31_to_60,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($over_due_61_to_90,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($over_due_more_than_90,2).'</b></td>
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($balance,2).'</b></td> 
                    </tr>
                    <tr>
                        <td colspan="9" align="center"> </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td  align="left"><div><b>รวมทั้งสิ้น ถึง</b> '.$date_end.' </div> </td>
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($paper_number_total,0).'</b></td> 
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($due_comming_more_than_60_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($due_comming_in_60_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($due_comming_in_30_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($over_due_1_to_30_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($over_due_31_to_60_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($over_due_61_to_90_total,2).'</b></td>  
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($over_due_more_than_90_total,2).'</b></td>
                        <td style="border-top: 1px dotted black; " align="right" ><b>'.number_format($balance_total,2).'</b></td> 
                    </tr>
                </tfoot>
            </table>
        ';
    }

}

?>