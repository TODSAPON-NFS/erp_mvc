<?PHP 
 

    $html = '<style>
        div{
            font-size:10px;
        }
        .table, .table thead th, .table tbody td{
            border: 1px solid black;
        }

        th{
            padding:2px 4px;
            font-size:10px;
        }

        td{
            padding:2px 4px;
            font-size:10px;
        }
        body{
            font-family:  "tahoma";  
        }

    </style>'; 

    $html .= '
    <table  width="100%" cellspacing="0" style="font-size:12px;">
        <thead>
            <tr>
                <th colspan="8" > 
                    รายชื่อลูกค้าจากรหัส '.$code_start.' ถึง '.$code_end.' แบบย่อ
                </th>
            </tr>
            <tr>
                <th width="48" style="text-align: center;vertical-align: middle;"> ลำดับ</th>
                <th style="text-align: center;vertical-align: middle;" >รหัส</th> 
                <th style="text-align: center;vertical-align: middle;" >ชื่อภาษาไทย</th> 
                <th style="text-align: center;vertical-align: middle;" >ชื่อภาษาอังกฤษ</th> 
                <th style="text-align: center;vertical-align: middle;" >เลขผู้เสียภาษี</th>  
                <th style="text-align: center;vertical-align: middle;" >เบอร์โทรศัพท์</th> 
                <th style="text-align: center;vertical-align: middle;" >Fax.</th> 
                <th style="text-align: center;vertical-align: middle;" >อีเมล</th>   
            </tr>
        </thead>

        <tbody>

    ';
 
     
    for($i=0; $i < count($debtor_reports) ; $i++){
 

                $html .= ' 
                <tr>
                    <td align="center" >'.($i + 1).'</td>
                    <td>'.$debtor_reports[$i]['customer_code'].'</td> 
                    <td>'.$debtor_reports[$i]['customer_name_th'].'</td> 
                    <td>'.$debtor_reports[$i]['customer_name_en'].'</td> 
                    <td>'.$debtor_reports[$i]['customer_tax'].'</td> 
                    <td>'.$debtor_reports[$i]['customer_tel'].'</td> 
                    <td>'.$debtor_reports[$i]['customer_fax'].'</td> 
                    <td>'.$debtor_reports[$i]['customer_email'].'</td> 
                </tr> 
                ';
    }
 
    $html .= ' 
            </tbody> 
        </table>
    '; 

?>