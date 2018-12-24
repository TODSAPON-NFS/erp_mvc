<?PHP 


$total_total = 0;
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
            padding:2px 4px;
            font-size:10px;
        }

    </style>';

    $html[$page_index] .= '
    <table width="100%">
        <tr>
            <td>
                <div><b>เดือน/ปี</b> '.$section_date.' (<b>เริ่มจาก</b> '.$date_start.' <b>ถึง</b> '.$date_end.') </div>
            </td>
            <td align="left"  align="left" width="120px" >
                
            </td>
        </tr>
    </table>
    <div align="center" style="font-size:14px;color:#00F;"> <b>รายงานผังบัญชี</b></div>
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
    <table width="100%"  cellspacing="0" >
    <thead>
        <tr>
            <th width="48"  style="border-top:1px solid black;border-bottom: 1px solid black;">ลำดับ</th> 
            <th width="100" style="border-top:1px solid black;border-bottom: 1px solid black;">เลขที่บัญชี</th>
            <th width="150" style="border-top:1px solid black;border-bottom: 1px solid black;">ชื่อบัญชี</th>
            <th width="150" style="border-top:1px solid black;border-bottom: 1px solid black;">หมวดบัญชี</th>
            <th width="150" style="border-top:1px solid black;border-bottom: 1px solid black;">ประเภทบัญชี</th>
            
        </tr>
    </thead>
        <tbody>

    ';

    
    //count($journal_reports)
    $total_page = 0;
    for($i=$page_index * $lines; $i < count($journal_reports) && $i < $page_index * $lines + $lines; $i++){
    
        
        if ($journal_reports[$i]['account_type'] == 1) {
            $zc =  "บัญชีควบคุม";
        } else {
            $zc = "บัญชีย่อย";
        }
        
                $html[$page_index] .= ' 
                <tr >
                            <td align="center" >'.number_format($i + 1,0).'</td>
                            <td>'.$journal_reports[$i]['account_code'].'</td>
                            <td>'. $journal_reports[$i]['account_name_th'].'</td> 
                            <td>'. $journal_reports[$i]['account_group_name'].'</td> 
                            <td>'.$zc.'</td> 
                    
                        </tr>
                ';

                
    }

    if($page_index+1 < $page_max){
        $html[$page_index] .= ' 
                </tbody>
            </table>
        ';
    }else if($page_index == 0){
        $html[$page_index] .= ' 
                </tbody>
            </table>
        ';
    }else{
        $html[$page_index] .= ' 
                </tbody>
            </table>
        ';
    }

}

?>