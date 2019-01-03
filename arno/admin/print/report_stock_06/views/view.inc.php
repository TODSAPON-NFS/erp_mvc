<?PHP 

$product_list = 0;

$stock_report_list = 0;

$stock_report_in_qty = 0;
$stock_report_in_cost_avg = 0;
$stock_report_in_total = 0;

$stock_report_out_qty = 0;
$stock_report_out_cost_avg = 0;
$stock_report_out_total = 0;

$stock_report_balance_qty = 0;
$stock_report_balance_cost_avg = 0;
$stock_report_balance_total = 0; 

$stock_report_in_qty_sum = 0;
$stock_report_in_cost_avg_sum = 0;
$stock_report_in_total_sum = 0;

$stock_report_out_qty_sum = 0;
$stock_report_out_cost_avg_sum = 0;
$stock_report_out_total_sum = 0;

$stock_report_balance_qty_sum = 0;
$stock_report_balance_cost_avg_sum = 0;
$stock_report_balance_total_sum = 0;
 
$stock_sum = 0 ;
$count_product = 0;
$count_product_sum = 0;
$i = 0;  
$html_head_pdf = '   
<table  width="100%" cellspacing="0" style="" > 
    <thead>
        <tr>
            <td colspan="5">บริษัท อาร์โน (ประเทศไทย) จำกัด</td> 
            <td colspan="6" align="right" >เลขประจำตัวผู้เสียภาษี : 0105558002033</td>
        </tr> 
        <tr>
            <td colspan="11" align="center" style="font-size:14px;color:#00F;"><b>รายงานรายการประจำวันสินค้า</b></td>  
        </tr> 
        <tr>
            <td colspan="1" align="left"  ><b>วันที่ </b></td>
            <td colspan="1" > '.$date_target.' </td>
            <td colspan="1" align="center"> </td>
            <td colspan="1" > </td>
            <td colspan="1" ></td>
            <td colspan="6" align="right" ><b>หน้า</b> : {PAGENO}/{nbpg}</td>
        </tr> 
        <tr> 
            <td colspan="1" ><b>คลังสินค้าจาก </b></td>
            <td colspan="1" > '.$stock_start.' </td>
            <td colspan="1" align="center"> ถึง </td>
            <td colspan="1" > '.$stock_end.' </td>
            <td colspan="1" ></td>
            <td colspan="6" ></td>
        </tr>
        <tr>
            <td colspan="1" ><b>รหัสสินค้าจาก </b></td>
            <td colspan="1" > '.$product_start.' </td>
            <td colspan="1" align="center"> ถึง </td>
            <td colspan="1" > '.$product_end.' </td>
            <td colspan="1" ></td>
            <td colspan="6" ></td>
        </tr>   
        <tr>
            <td colspan="1" ><b>รหัสเอกสาร </b></td>
            <td colspan="1" > '.$paper_code.' </td>
            <td colspan="1" align="center"> </td>
            <td colspan="1" > </td>
            <td colspan="1" ></td>
            <td colspan="6" ></td>
        </tr>  
        <tr>
            <td colspan="1" ><b>ประเภท </b></td>
            <td colspan="1" > '.$table_name_text.' </td>
            <td colspan="1" align="center"> </td>
            <td colspan="1" > </td>
            <td colspan="1" ></td>
            <td colspan="6" ></td>
        </tr> 
        <tr>
            <td colspan="1" ><b>ประเภทรายงาน </b></td>
            <td colspan="1" > '.$group_by_text.' </td>
            <td colspan="1" align="center"> </td>
            <td colspan="1" > </td>
            <td colspan="1" ></td>
            <td colspan="6" ></td>
        </tr> 
        <tr>  
            <th width="8%"></th>    
            <th width="8%"></th>    
            <th width="8%"></th>   
            <th width="10%"></th>   
            <th width="10%"></th>  
            <th width="8%"></th>   
            <th width="10%"></th>   
            <th width="10%"></th>  
            <th width="8%"></th>   
            <th width="10%"></th>   
            <th width="10%"></th>  
        </tr>
    </thead>
</table>  
';
$html_head_excel = ' 
<table  width="100%" cellspacing="0" style="" > 
    <thead>
        <tr>
            <td colspan="5">บริษัท อาร์โน (ประเทศไทย) จำกัด</td> 
            <td colspan="6" align="right" >เลขประจำตัวผู้เสียภาษี : 0105558002033</td>
        </tr> 
        <tr>
            <td colspan="11" align="center" style="font-size:14px;color:#00F;"><b>รายงานรายการประจำวันสินค้า</b></td>  
        </tr> 
        <tr>
            <td colspan="1" align="left"  ><b>วันที่ </b></td>
            <td colspan="1" > '.$date_target.' </td>
            <td colspan="1" align="center"> </td>
            <td colspan="1" > </td>
            <td colspan="1" ></td>
            <td colspan="6" align="right" > </td>
        </tr> 
        <tr> 
            <td colspan="1" ><b>คลังสินค้าจาก </b></td>
            <td colspan="1" > '.$stock_start.' </td>
            <td colspan="1" align="center"> ถึง </td>
            <td colspan="1" > '.$stock_end.' </td>
            <td colspan="1" ></td>
            <td colspan="6" ></td>
        </tr>
        <tr>
            <td colspan="1" ><b>รหัสสินค้าจาก </b></td>
            <td colspan="1" > '.$product_start.' </td>
            <td colspan="1" align="center"> ถึง </td>
            <td colspan="1" > '.$product_end.' </td>
            <td colspan="1" ></td>
            <td colspan="6" ></td>
        </tr>  
        <tr>
            <td colspan="1" ><b>รหัสสินค้าจาก </b></td>
            <td colspan="1" > '.$product_start.' </td>
            <td colspan="1" align="center"> ถึง </td>
            <td colspan="1" > '.$product_end.' </td>
            <td colspan="1" ></td>
            <td colspan="6" ></td>
        </tr>  
        <tr>
            <td colspan="1" ><b>รหัสเอกสาร </b></td>
            <td colspan="1" > '.$paper_code.' </td>
            <td colspan="1" align="center"> </td>
            <td colspan="1" > </td>
            <td colspan="1" ></td>
            <td colspan="6" ></td>
        </tr>  
        <tr>
            <td colspan="1" ><b>ประเภท </b></td>
            <td colspan="1" > '.$table_name_text.' </td>
            <td colspan="1" align="center"> </td>
            <td colspan="1" > </td>
            <td colspan="1" ></td>
            <td colspan="6" ></td>
        </tr> 
        <tr>
            <td colspan="1" ><b>ประเภทรายงาน </b></td>
            <td colspan="1" > '.$group_by_text.' </td>
            <td colspan="1" align="center"> </td>
            <td colspan="1" > </td>
            <td colspan="1" ></td>
            <td colspan="6" ></td>
        </tr> 
        <tr>  
            <th width="8%"></th>    
            <th width="8%"></th>    
            <th width="8%"></th>   
            <th width="10%"></th>   
            <th width="10%"></th>  
            <th width="8%"></th>   
            <th width="10%"></th>   
            <th width="10%"></th>  
            <th width="8%"></th>   
            <th width="10%"></th>   
            <th width="10%"></th>  
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
while($i < count($stock_reports)){



    $html .= '
    <table class="table" width="100%" cellspacing="0" > 
        <thead>
            <tr>  
                <th width="16%" colspan="2" style="text-align:center;">ใบสำคัญ</th>     
                <th align="center" width="28%" colspan="3" style="text-align:center;">รายการรับ</th>    
                <th align="center" width="28%" colspan="3" style="text-align:center;">รายการจ่าย</th>    
                <th align="center" width="28%" colspan="3" style="text-align:center;">คงเหลือ</th>    
            </tr>
            <tr>  
                <th width="8%">วันที่<br></th>    
                <th width="8%">เลขที่<br></th>    
                <th width="8%">จำนวน</th>   
                <th width="10%">ราคาต่อหน่วย</th>   
                <th width="10%">มูลค่ารับ</th>  
                <th width="8%">จำนวน</th>   
                <th width="10%">ราคาต่อหน่วย</th>   
                <th width="10%">มูลค่าจ่าย</th>  
                <th width="8%">จำนวน</th>   
                <th width="10%">ราคาต่อหน่วย</th>   
                <th width="10%">มูลค่าคงเหลือ</th>  
            </tr>
        </thead>
        <tbody>

    ';
    
        
    for(; $i < count($stock_reports); $i++){
        
        if( $stock_reports[$i-1]['product_name'] != $stock_reports[$i]['product_name']){ 
            $product_list++;
            $html .= '
                
                <tr class="">
                    <td colspan="2" >
                        <b>'.$stock_reports[$i]['product_code'].'</b>
                    </td> 
                    <td colspan="9" >
                        <b style="color:blue;">'.$stock_reports[$i]['product_name'].'</b>
                    </td> 
                    
                </tr>  
            ';
        }
        if($stock_reports[$i-1]['stock_group_code'] != $stock_reports[$i]['stock_group_code']||($stock_reports[$i-1]['product_name'] != $stock_reports[$i]['product_name']&&$stock_reports[$i-1]['stock_group_code'] == $stock_reports[$i]['stock_group_code'])){ 
            $balance = $stock_report_model->getStockReportBalanceBy($stock_reports[$i]['product_id'],$stock_reports[$i]['table_name'],$date_start);
            if(count($balance)>0){  
            $html .= ' 
            <tr class="">
                <td colspan="1" align="center" >
                    <b>'.$stock_reports[$i]['stock_group_code'].'</b>
                </td> 
                <td colspan="7" >
                    <b><span style="color:blue;">'.$stock_reports[$i]['stock_group_name'].'</span></b>
                </td>   
                <td align="right" >'.number_format($balance['balance_qty'],0).'</td> 
                <td align="right" >'.number_format($balance['balance_stock_cost_avg'],2).'</td>
                <td align="right" >'.number_format($balance['balance_stock_cost_avg_total'],2).'</td> 
            </tr> 
            ';
            }else{
            $html .= ' 
            <tr class="">
                <td colspan="1" align="center">
                    <b>'.$stock_reports[$i]['stock_group_code'].'</b>
                </td> 
                <td colspan="7" >
                    <b><span style="color:blue;">'.$stock_reports[$i]['stock_group_name'].'</span></b>
                </td>   
                <td align="right">'.number_format(0,0).'</td> 
                <td align="right">'.number_format(0,2).'</td>
                <td align="right">'.number_format(0,2).'</td> 
            </tr> 
            ';
            }  
        }   

        
        $stock_report_list++;

        $stock_report_in_qty += $stock_reports[$i]['in_qty'];
        $stock_report_in_cost_avg += $stock_reports[$i]['in_stock_cost_avg'];
        $stock_report_in_total += $stock_reports[$i]['in_stock_cost_avg_total'];

        $stock_report_out_qty += $stock_reports[$i]['out_qty'];
        $stock_report_out_cost_avg += $stock_reports[$i]['out_stock_cost_avg'];
        $stock_report_out_total += $stock_reports[$i]['out_stock_cost_avg_total']; 

        $html .= '
        <tr class="">
            <td>'.$stock_reports[$i]['stock_date'].'</td>
            <td>'.$stock_reports[$i]['paper_code'].'</td>
            <td align="right">'; if($stock_reports[$i]['in_qty']>0){ $html .= number_format($stock_reports[$i]['in_qty'],0); } $html .= '</td> 
            <td align="right">'; if($stock_reports[$i]['in_qty']>0){ $html .= number_format($stock_reports[$i]['in_stock_cost_avg'],2);} $html .= '</td>
            <td align="right">'; if($stock_reports[$i]['in_qty']>0){ $html .= number_format($stock_reports[$i]['in_stock_cost_avg_total'],2);} $html .= '</td> 
            <td align="right">'; if($stock_reports[$i]['out_qty']>0){ $html .= number_format($stock_reports[$i]['out_qty'],0);} $html .= '</td> 
            <td align="right">'; if($stock_reports[$i]['out_qty']>0){ $html .= number_format($stock_reports[$i]['out_stock_cost_avg'],2);} $html .= '</td>
            <td align="right">'; if($stock_reports[$i]['out_qty']>0){ $html .= number_format($stock_reports[$i]['out_stock_cost_avg_total'],2);} $html .= '</td> 
            <td align="right">'.number_format($stock_reports[$i]['balance_qty'],0).'</td> 
            <td align="right">'.number_format($stock_reports[$i]['balance_stock_cost_avg'],2).'</td>
            <td align="right">'.number_format($stock_reports[$i]['balance_stock_cost_avg_total'],2).'</td> 
        </tr>
        ';  

        if($stock_reports[$i]['stock_group_code'] != $stock_reports[$i+1]['stock_group_code']||($stock_reports[$i+1]['product_name'] != $stock_reports[$i]['product_name']&&$stock_reports[$i+1]['stock_group_code'] == $stock_reports[$i]['stock_group_code'])){ 
  
            $stock_report_in_qty_sum += $stock_report_in_qty;
            $stock_report_in_cost_avg_sum += $stock_report_in_cost_avg;
            $stock_report_in_total_sum += $stock_report_in_total;

            $stock_report_out_qty_sum += $stock_report_out_qty;
            $stock_report_out_cost_avg_sum += $stock_report_out_cost_avg;
            $stock_report_out_total_sum += $stock_report_out_total;

            $stock_report_balance_qty_sum += $stock_reports[$i]['balance_qty'];
            $stock_report_balance_cost_avg_sum += $stock_reports[$i]['balance_stock_cost_avg'];
            $stock_report_balance_total_sum += $stock_reports[$i]['balance_stock_cost_avg_total'];
       
            $html .= '
            <tr class="">
                <td style="text-align:right;"><b><span>รวม</span></b></td>
                <td style="text-align:right;">'.number_format($stock_report_list,0).' รายการ</td>
                <td align="right">'; if($stock_report_in_qty>0){ $html .= number_format($stock_report_in_qty,0); } $html .= '</td> 
                <td align="right">'; if($stock_report_in_qty>0){ $html .= number_format($stock_report_in_cost_avg,2);} $html .= '</td>
                <td align="right">'; if($stock_report_in_qty>0){ $html .= number_format($stock_report_in_total,2);} $html .= '</td>  
                <td align="right">'; if($stock_report_out_qty>0){ $html .= number_format($stock_report_out_qty,0); } $html .= '</td> 
                <td align="right">'; if($stock_report_out_qty>0){ $html .= number_format($stock_report_out_cost_avg,2);} $html .= '</td>
                <td align="right">'; if($stock_report_out_qty>0){ $html .= number_format($stock_report_out_total,2);} $html .= '</td>  
                <td align="right" colspan="3"></td>  
            </tr>
            ';  

            $stock_report_list = 0;

            $stock_report_in_qty = 0;
            $stock_report_in_cost_avg = 0;
            $stock_report_in_total = 0;

            $stock_report_out_qty = 0;
            $stock_report_out_cost_avg = 0;
            $stock_report_out_total = 0;

            $stock_report_balance_qty = 0;
            $stock_report_balance_cost_avg = 0;
            $stock_report_balance_total = 0;
            
        } 
    }
 
        $html .= ' 
            </tbody>
            <tfoot> 
                <tr >  
                    <td align="center" colspan="11" style="padding-top:15px;"><font color="black">********* จบรายงาน *********</font></td>  
                </tr>
            </tfoot>
        </table>
        ';
    

} 

?>