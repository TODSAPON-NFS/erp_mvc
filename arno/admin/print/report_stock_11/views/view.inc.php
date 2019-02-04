<?PHP 

 
$stock_sum = 0 ;
$count_product = 0;
$count_product_sum = 0;
$i = 0;  
$srt_stock_group = "" ;


if ($keyword == ""){
    $keyword = '-';
}


    $srt_stock_group = "ทั้งหมด";




$html_head_pdf = '  
<table width="100%" border="0" cellspacing="0">
    <tr>
        <td colspan="3">'.$company['company_name_th'].'จำกัด</td>  
    </tr> 
    <tr>
        <td colspan="6" align="center" style="font-size:14px;color:#00F;"><b> มูลค่าตามคลังสินค้า </b></td>  
    </tr> 
    <tr>
    <td align="left" width="80px" ><b>คลังสินค้า </b></td>
    <td width="200" colspan="1" > '.$srt_stock_group.' </td> 
    <td width="100" align="center" colspan="1" ><b>ณ วันที่ </b></td>
    <td  colspan="1" > '.$date_.' </td>  
        <td ></td>
        <td align="right" width="120px" > <b>หน้า</b> : {PAGENO}/{nbpg} </td>
    </tr> 
</table>

<table  width="100%" cellspacing="0" style="font-size:12px;margin-top:10px;border-top: 1px solid black;border-bottom: 1px solid black;padding-top:5px;padding-bottom:3px;" >
        <thead>
            <tr> 
                <th width="10%">ลำดับ</th>
                <th width="20%">รหัสคลัง</th>
                <th width="40%">ชื่อคลังค้า</th> 
                <th>มูลค่า</th> 
                <th align="right"> % </th>
            </tr>
        </thead>
</table> 
';
$html_head_excel = ' 
<div align="center" style="font-size:14px;color:#00F;"> <b>มูลค่าตามคลังสินค้า</b></div>
    <table width="100%" border="0" cellspacing="0">
    <tr>
    <td colspan="3">'.$company['company_name_th'].'จำกัด</td>  
</tr> 
<tr>
    <td colspan="6" align="center" style="font-size:14px;color:#00F;"><b> มูลค่าตามคลังสินค้า </b></td>  
</tr> 
<tr>
<td align="left" width="80px" ><b>คลังสินค้า </b></td>
<td width="200" colspan="1" > '.$srt_stock_group.' </td> 
<td width="100" align="center" colspan="1" ><b>ณ วันที่ </b></td>
<td  colspan="1" > '.$date_.' </td>  
    <td ></td>
    <td align="right" width="120px" > </td>
</tr> 
    </table>  
<table  width="100%" cellspacing="0" style="font-size:12px;margin-top:10px;border-top: 1px solid black;border-bottom: 1px solid black;padding-top:5px;padding-bottom:3px;" >
    <thead>
        <tr> 
            <th width="10%">ลำดับ</th>
            <th width="20%">รหัสคลัง</th>
            <th width="40%">ชื่อคลังค้า</th> 
            <th>มูลค่า</th> 
            <th align="right"> % </th>
        </tr>
    </thead>
</table> 
';


$html = '<style>
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

    $sumQty = 0 ; 
    $sumCost_avg_total = 0 ; 
    $sumall = 0 ;

    for($i=0; $i < count($stock_reports); $i++){ 
        $sumall+=$stock_reports[$i]['stock_report_avg_total'] ;
    }

    $i=0;

    while($i < count($stock_reports)){ 
        $html .= '<table  width="100%"  style="font-size:12px;">
            <tbody>
        ';

     
    for(; $i < count($stock_reports); $i++){
        
        $sumQty += $stock_reports[$i]['stock_report_qty'];
        $sumCost_avg_total += $stock_reports[$i]['stock_report_avg_total'] ;

        $html .= ' 
            <tr >
                <td width="15%">'.number_format(($i + 1),0).'</td>
                <td width="15%">'.$stock_reports[$i]['stock_group_code'].'</td>
                <td width="25%">'.$stock_reports[$i]['stock_group_name'].'</td>
                <td align="right" width="10%">'.number_format($stock_reports[$i]['stock_report_avg_total'],2).'</td> 
                <td align="right" width="10%">'.number_format($stock_reports[$i]['stock_report_avg_total']/$sumall *100,2).' %</td>        
            </tr>
            ';  
    }

    if($i < count($stock_reports)){ 
        $html .= ' 
                </tbody>
                <tfoot> 
               
                </tfoot>
            </table>
            '; 
    }else{ 
        $html .= ' 
                </tbody> 
                    <tfoot> 
                        <tr>                          
                            <td align="center" colspan="3" style="padding-top:8px;border-top:1px solid black;border-bottom: 1px solid black;"> <b>รวมทั้งหมด </b></td>
                            <td align="right" style="padding-top:8px;border-top:1px solid black;border-bottom: 1px solid black;"><b>'.number_format($sumCost_avg_total,2).'</b></td>                        
                            <td align="right" style="padding-top:8px;border-top:1px solid black;border-bottom: 1px solid black;"><b>'.number_format($sumall/$sumall*100,2).'</b> %</td>              
                        </tr>
                    </tfoot>
            </table>
            '.' 
        ';
    } 

} 

?>