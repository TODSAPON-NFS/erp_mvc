<?PHP 

 
$stock_sum = 0 ;
$count_product = 0;
$count_product_sum = 0;
$i = 0;  
$html_head_pdf = '  
<table width="100%" border="0" cellspacing="0">
    <tr>
        <td colspan="3">บริษัท อาร์โน (ประเทศไทย) จำกัด</td>  
    </tr> 
    <tr>
        <td colspan="6" align="center" style="font-size:14px;color:#00F;"><b>สินค้าและวัตถุดิบ แยกตามคลังสินค้า</b></td>  
    </tr> 
    <tr>
        <td align="left" width="80px" ><b>รหัสสินค้าจาก </b></td>
        <td width="100" > '.$product_start.' </td>
        <td width="30" align="center"> ถึง </td>
        <td > '.$product_end.' </td>
        <td ></td>
        <td align="right" width="120px" ><b>หน้า</b> : {PAGENO}/{nbpg}</td>
    </tr> 
    <tr> 
        <td align="left" width="80px" ><b>คลังสินค้าจาก </b></td>
        <td width="100" > '.$stock_start.' </td>
        <td width="30" align="center"> ถึง </td>
        <td > '.$stock_end.' </td>
        <td ></td>
        <td ></td>
    </tr>
</table>  
<table  width="100%" cellspacing="0" style="font-size:12px;margin-top:10px;border-top: 1px dotted black;border-bottom: 1px dotted black;padding-top:5px;padding-bottom:3px;" >
    <thead>
        <tr > 
            <th align="left" style="">รหัส/ชื่อสินค้า</th>  
            <th align="left" style=""></th>  
            <th align="left" style=""></th>  
            <th width="60" style="">จำนวน</th>
            <th width="100" style="">ราคาต่อหน่วย</th>
            <th width="100" style="">มูลค่าคงเหลือ</th> 
        </tr>
    </thead>
</table> 
';
$html_head_excel = ' 
<div align="center" style="font-size:14px;color:#00F;"> <b>สินค้าและวัตถุดิบ แยกตามคลังสินค้า</b></div>
<table width="100%" border="0" cellspacing="0">
    <tr>
        <td align="left" width="80px" ><b>รหัสสินค้าจาก </b></td>
        <td width="100" > '.$product_start.' </td>
        <td width="30" align="center"> ถึง </td>
        <td > '.$product_end.' </td>
        <td ></td>
        <td align="right" width="120px" ></td>
    </tr> 
    <tr> 
        <td align="left" width="80px" ><b>คลังสินค้าจาก </b></td>
        <td width="100" > '.$stock_start.' </td>
        <td width="30" align="center"> ถึง </td>
        <td > '.$stock_end.' </td>
        <td ></td>
        <td ></td>
    </tr>
</table>  
<table  width="100%" cellspacing="0" style="font-size:12px;margin-top:10px;border-top: 1px dotted black;border-bottom: 1px dotted black;padding-top:5px;padding-bottom:3px;" >
    <thead>
        <tr > 
            <th align="left" style="">รหัส/ชื่อสินค้า</th>  
            <th align="left" style=""></th>  
            <th align="left" style=""></th>  
            <th width="60" style="">จำนวน</th>
            <th width="100" style="">ราคาต่อหน่วย</th>
            <th width="100" style="">มูลค่าคงเหลือ</th> 
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
while($i < count($stock_reports)){

    

    $html .= '
    <table  width="100%" cellspacing="0" style="font-size:12px;"> 
        <tbody>

    ';
 
     
    for(; $i < count($stock_reports); $i++){
        
        if($stock_reports[$i-1]['stock_group_name'] != $stock_reports[$i]['stock_group_name']){
            $stock_sum+=1;
            $html .= '
                 
                <tr class="">
                    <td colspan="6" color="blue">
                        <b>'.$stock_reports[$i]['stock_group_name'].'</b>
                    </td>  
                </tr> 
            ';
            $line ++;
            if($line % $lines == 0){
                $i++;
                break;
            }
        }

       
        $count_product+=1;
        $stock_report_qty +=  $stock_reports[$i]['stock_report_qty'];
        $stock_report_cost_avg +=  $stock_reports[$i]['stock_report_cost_avg'];
        $stock_report_total +=  $stock_reports[$i]['stock_report_total'];


        $html .= ' 
        <tr> 
            <td align="left" style="padding-left:15px;">'.$stock_reports[$i]['product_code'].' '.$stock_reports[$i]['product_name'].'</td> 
            <td></td>
            <td></td>
            <td  align="right" width="60" > '.number_format($stock_reports[$i]['stock_report_qty'],0).' Pc.</td> 
            <td  align="right" width="100" > '.number_format($stock_reports[$i]['stock_report_cost_avg'],2).' </td> 
            <td  align="right" width="100" > '.number_format($stock_reports[$i]['stock_report_total'],2).' </td>   
        </tr> 
        ';

        $line ++;
        if($line % $lines == 0){
            $i++;
            break;
        }

        if($stock_reports[$i]['stock_group_name'] != $stock_reports[$i+1]['stock_group_name']){ 
             
            $count_product_sum += $count_product;
            $stock_report_qty_sum += $stock_report_qty;
            $stock_report_cost_avg_sum +=  $stock_report_cost_avg; 
            $stock_report_total_sum +=  $stock_report_total; 

            $html .= ' </tbody> 
                    </table> 
                    <table  width="100%" cellspacing="0" style="font-size:12px;margin-top:10px;margin-bottom:10px;" >
                        <thead>
                            <tr >  
                                <td align="left" style="padding-left:15px;"><b><font color="black"> รวมคลัง '.$stock_reports[$i]['stock_group_name'].' </font></b></td> 
                                <td></td>
                                <td width="80" align="right" style="padding-right:15px;"><b>'.$count_product.' สินค้า</b></td> 
                                <td width="60" style="border-top: 1px dotted black;" align="right"><b><font color="blue">'. number_format($stock_report_qty,0).' </font></b> </td>
                                <td width="100" style="border-top: 1px dotted black;" align="right"><b><font color="blue">'. number_format($stock_report_cost_avg,2).' </font></b> </td> 
                                <td width="100" style="border-top: 1px dotted black;" align="right"><b><font color="blue">'. number_format($stock_report_total,2).' </font></b> </td>  
                            </tr>
                        </thead>
                    </table>  
                    <table  width="100%" cellspacing="0" style="font-size:12px;"> 
                        <tbody>
            ';

            $stock_report_qty = 0;
            $stock_report_cost_avg = 0;
            $stock_report_total = 0;
            $count_product = 0;   
      
        } 
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
            </table>
            <table  width="100%" cellspacing="0" style="font-size:12px;margin-top:10px;" >
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
            </table>  
        ';
    } 

} 

?>