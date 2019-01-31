<?PHP 

 
$stock_sum = 0 ;
$count_product = 0;
$count_product_sum = 0;
$i = 0;  

$str_head = "" ;

if($status_qty == 1){

    $str_head ="สินค้าคงเหลือ แยกตามคลังสินค้า (สินค้าที่ไม่ติดลบ)";

}elseif($status_qty == 2){

    $str_head ="สินค้าคงเหลือ แยกตามคลังสินค้า (สินค้าที่ติดลบ)";

}else{

    $str_head ="สินค้าคงเหลือ แยกตามคลังสินค้า (สินค้าทั้งหมด)";
}

if ($product_start == ""){
    $product_start = '-';
}

if ($product_end == ""){
    $product_end = '-';
}

$html_head_pdf = '  
<table width="100%" border="0" cellspacing="0">
    <tr>
        <td colspan="3">'.$company['company_name_th'].'</td>  
    </tr> 
    <tr>
        <td colspan="6" align="center" style="font-size:14px;color:#00F;"><b>'.$str_head.'</b></td>  
    </tr> 
    <tr>
        <td align="left" width="80px" ><b>รหัสสินค้าจาก </b></td>
        <td width="150" > '.$product_start.' </td>
        <td width="100" align="center"> <b>ถึง</b>  </td>
        <td width="150"> '.$product_end.' </td>
        <td ></td>
        <td align="right" width="120px" ><b>หน้า</b> : {PAGENO}/{nbpg}</td>
    </tr> 
    <tr> 
        <td align="left" width="80px" ><b>คลังสินค้า </b></td>
        <td width="150" colspan="1" > ['.$stock_group['stock_group_code'].'] '.$stock_group['stock_group_name'].' </td> 
        <td width="100" align="center" colspan="1" ><b>ณ วันที่ </b></td>
        <td  colspan="1" > '.$date_end.' </td>  
        <td ></td>
        <td  colspan="1" >   </td>  
    </tr>  
</table>  
<table  width="100%" cellspacing="0" style="font-size:12px;margin-top:10px;border-top: 1px solid black;border-bottom: 1px solid black;padding-top:5px;padding-bottom:3px;" >
    <thead>
        <tr > 
            <th align="left" width="40">ลำดับ</th>  
            <th align="left" width="70">รหัสสินค้า</th>  
            <th align="center" width="250">ชื่อสินค้า</th>  
            <th width="50">จำนวน</th>
            <th width="50">ต้นทุนเฉลี่ยต่อชิ้น</th> 
            <th width="50">ต้นทุนเฉลี่ยรวม</th>
        </tr>
    </thead>
</table> 
';
$html_head_excel = ' 
<div align="center" style="font-size:14px;color:#00F;"> <b>'.$str_head.'</b></div>
<table width="100%" border="0" cellspacing="0">
    <tr>
        <td colspan="3">บริษัท อาร์โน (ประเทศไทย) จำกัด</td>  
    </tr> 
    <tr>
        <td colspan="6" align="center" style="font-size:14px;color:#00F;"><b>'.$str_head.'</b></td>  
    </tr> 
    <tr>
        <td align="left" width="80px" ><b>รหัสสินค้าจาก </b></td>
        <td width="150" > '.$product_start.' </td>
        <td width="100" align="center"> <b>ถึง</b> </td>
        <td width="150"> '.$product_end.' </td>
        <td ></td>
        <td align="right" width="120px" ><b>หน้า</b> : {PAGENO}/{nbpg}</td>
    </tr> 
    <tr> 
        <td align="left" width="80px" ><b>คลังสินค้า </b></td>
        <td width="150" colspan="1" > ['.$stock_group['stock_group_code'].'] '.$stock_group['stock_group_name'].' </td> 
        <td width="100" align="center" colspan="1" ><b>ณ วันที่ </b></td>
        <td  colspan="1" > '.$date_end.' </td>  
        <td ></td>
        <td  colspan="1" >   </td>  
    </tr> 
</table>  
<table  width="100%" cellspacing="0" style="font-size:12px;margin-top:10px;border-top: 1px solid black;border-bottom: 1px solid black;padding-top:5px;padding-bottom:3px;" >
    <thead>
    <tr > 
        <th align="left" width="40">ลำดับ</th>  
        <th align="left" width="70">รหัสสินค้า</th>  
        <th align="center" width="250">ชื่อสินค้า</th>  
        <th width="50">จำนวน</th>
        <th width="50">ต้นทุนเฉลี่ยต่อชิ้น</th> 
        <th width="50">ต้นทุนเฉลี่ยรวม</th>
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
    $sumCost_avg_total= 0;
while($i < count($stock_reports)){ 
    $html .= '
    <table  width="100%" cellspacing="0" style="font-size:12px;">
    <tr > 
        <th width="10%"></th>  
        <th width="20%"></th>  
        <th width="27%"></th>  
        <th width="10%"></th>
        <th width="10%"></th> 
        <th width="15%"></th>
    </tr> 
        <tbody>

    ';
 
     
    for(; $i < count($stock_reports); $i++){
        
        $sumQty +=  $stock_reports[$i]['stock_report_qty'] ;
        $sumCost_avg_total += $stock_reports[$i]['stock_report_avg_total'] ;
       
        $count_product+=1;
        $stock_report_qty +=  $stock_reports[$i]['stock_report_qty'];
        $stock_report_cost_avg +=  $stock_reports[$i]['stock_report_cost_avg'];
        $stock_report_total +=  $stock_reports[$i]['stock_report_total'];


        $html .= ' 
        <tr> 
            <td align="left"  >'.($i+1).'</td> 
            <td align="left" >'.$stock_reports[$i]['product_code'].'</td>
            <td align="left"  >'.$stock_reports[$i]['product_name'].'</td>
            <td align="right"> '.number_format($stock_reports[$i]['stock_report_qty'],0).' Pc.</td> 
            <td align="right"> '.number_format($stock_reports[$i]['stock_report_cost_avg'],2).' </td> 
            <td align="right"> '.number_format($stock_reports[$i]['stock_report_avg_total'],2).' </td> 
            
        </tr> 
        ';  
    }

    if($i < count($stock_reports)){ 
        // $html .= ' 
        //         </tbody>
        //         <tfoot> 
        //         </tfoot>
        //     </table>
        //     '; 
    }else{ 
        $html .= ' 
               
            './*<table  width="100%" cellspacing="0" style="font-size:12px;margin-top:10px;" >
                <thead>
                    <tr >  
                        <td style="padding-top:10px;padding-bottom:6px;" align="left" ><b><font color="black">รวมทั้งสิ้น</font></b></td>  
                        <td style="padding-top:10px;padding-bottom:6px;" width="120" align="center" ><b>'.$stock_sum.' คลัง</b></td> 
                        <td style="padding-top:10px;padding-bottom:6px;" width="120" align="right" style="padding-right:15px;"><b>'.$count_product_sum.' สินค้า</b></td> 
                        <td style="padding-top:10px;padding-bottom:6px;border-top: 1px dotted black;border-bottom: 1px dotted black;color:blue;" width="60" align="right" ><b>'.number_format($stock_report_qty_sum,0).'</b></td>  
                        <td style="padding-top:10px;padding-bottom:6px;border-top: 1px dotted black;border-bottom: 1px dotted black;color:blue;" width="100" align="right" ><b>'.number_format($stock_report_cost_avg_sum,2).'</b></td>  
                        <td style="padding-top:10px;padding-bottom:6px;border-top: 1px dotted black;border-bottom: 1px dotted black;color:blue;" width="100" align="right" ><b>'.number_format($stock_report_total_sum,2).'</b></td>  
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot> 
                    <tr >  
                        <td align="center" colspan="6" style="padding-top:15px;"><font color="black">********* จบรายงาน *********</font></td>  
                    </tr>
                </tfoot>
            </table> */' 
        ';
        
     $html .= '
            <tfoot style="margin-top:12px;">
                <tr>                           
                    <td align="center" colspan="3" style="padding-top:8;border-top:1px solid black;border-bottom: 1px solid black;"><font color="black"> <b>รวมทั้งหมด</b></td>
                    <td align="right" style="padding-top:8px;border-top:1px solid black;border-bottom: 1px solid black;"> <b>'.number_format($sumQty ,0).' Pc.</b></td>                        
                    <td align="right" style="padding-top:8;border-top:1px solid black;border-bottom: 1px solid black;"><b> - </b></td>
                    <td align="right" style="padding-top:8;border-top:1px solid black;border-bottom: 1px solid black;"><b>'.number_format($sumCost_avg_total,2).'</b></td> 
                </tr>
            </tfoot>  
            </table>';


    } 
} 

        
?>