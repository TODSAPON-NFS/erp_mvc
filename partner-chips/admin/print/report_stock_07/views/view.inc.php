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
<table width="100%" cellspacing="0" style="" > 
    <thead>
        <tr>
            <td colspan="4">บริษัท อาร์โน (ประเทศไทย) จำกัด</td> 
            <td colspan="5" align="right" >เลขประจำตัวผู้เสียภาษี : 0105558002033</td>
        </tr> 
        <tr>
            <td colspan="9" align="center" style="font-size:14px;color:#00F;"><b>รายงานจุดสั่งซื้อ</b></td>  
        </tr>  
        <tr>
            <td colspan="1" ><b>รหัสสินค้าจาก </b></td>
            <td colspan="1" > '.$product_start.' </td>
            <td colspan="4" > ถึง&nbsp;&nbsp;&nbsp;&nbsp;'.$product_end.'</td> 
            <td colspan="3" align="right" ><b>หน้า</b> : {PAGENO}/{nbpg}</td>
        </tr>    
        <tr>
            <td colspan="1" ><b>ประเภท </b></td>
            <td colspan="8" > '.$product_type['product_type_name'].' </td> 
        </tr>  
        <tr>
            <td colspan="1" ><b>ผู้ขาย </b></td>
            <td colspan="8" > '.$suppliers['supplier_name_en'].' </td> 
        </tr>  
        <tr>
            <td colspan="1" ><b>จุดสั่งซื้อ </b></td>
            <td colspan="8" > '.$product_qty_text.' </td> 
        </tr>  
        <tr>
            <th width="10%" ></th>  
            <th width="15%" ></th>    
            <th align="15%"></th>
            <th width="25%" align=""></th>
            <th width="7%" align="center"></th>   
            <th width="7%" align="center"></th>   
            <th width="7%" align="center"></th>   
            <th width="7%" align="center"></th>   
            <th width="7%" align="center"></th>    
        </tr>
    </thead>
</table>  
';
$html_head_excel = ' 
<table width="100%" cellspacing="0" style="" > 
    <thead>
        <tr>
            <td colspan="4">บริษัท อาร์โน (ประเทศไทย) จำกัด</td> 
            <td colspan="5" align="right" >เลขประจำตัวผู้เสียภาษี : 0105558002033</td>
        </tr> 
        <tr>
            <td colspan="9" align="center" style="font-size:14px;color:#00F;"><b>รายงานจุดสั่งซื้อ</b></td>  
        </tr>  
        <tr>
            <td colspan="1" ><b>รหัสสินค้าจาก </b></td>
            <td colspan="1" > '.$product_start.' </td>
            <td colspan="7" > ถึง&nbsp;&nbsp;&nbsp;&nbsp;'.$product_end.'</td> 
        </tr>    
        <tr>
            <td colspan="1" ><b>ประเภท </b></td>
            <td colspan="8" > '.$product_type['product_type_name'].' </td> 
        </tr>  
        <tr>
            <td colspan="1" ><b>ผู้ขาย </b></td>
            <td colspan="8" > '.$suppliers['supplier_name_en'].' </td> 
        </tr>  
        <tr>
            <td colspan="1" ><b>จุดสั่งซื้อ </b></td>
            <td colspan="8" > '.$product_qty_text.' </td> 
        </tr>  
        <tr>
            <th width="8%" ></th>  
            <th width="15%" ></th>    
            <th align="17%"></th>
            <th width="25%" align=""></th>
            <th width="7%" align="center"></th>   
            <th width="7%" align="center"></th>   
            <th width="7%" align="center"></th>   
            <th width="7%" align="center"></th>   
            <th width="7%" align="center"></th>    
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
// while($i < count($stock_reports)){



    $html .= '
    <table class="table" width="100%" cellspacing="0" > 
        <thead> 
            <tr> 
                <th align="center" width="8%" >No.</th>  
                <th width="15%"  >รหัสสินค้า</th>     
                <th width="17%">ชื่อสินค้า </th>
                <th width="25%" align="">ผู้ขาย</th>
                <th width="7%" align="center">จุดต่ำสุด</th>   
                <th width="7%" align="center">จุดสั่งซื้อ</th>   
                <th width="7%" align="center">จุดสูงสุด</th>   
                <th width="7%" align="center">คงเหลือ</th>   
                <th width="7%" align="center">ต้องสั่งซื้อ</th>    
            </tr>
        </thead>
        <tbody>

    ';
    
        
    for(; $i < count($stock_reports); $i++){
        
       

   

        $html .= '
        <tr class="">
            <td align="center">'.($i+1).'</td> 
            <td>'.$stock_reports[$i]['product_code'].'</td> 
            <td>'.$stock_reports[$i]['product_name'].'</td> 
            <td>'.$stock_reports[$i]['supplier_name_en'].'</td> 
            <td align="right">'.number_format($stock_reports[$i]['minimum_stock'],0).'</td>
            <td align="right">'.number_format($stock_reports[$i]['safety_stock'],0).'</td>
            <td align="right">'.number_format($stock_reports[$i]['maximum_stock'],0).'</td>
            <td align="right">'.number_format($stock_reports[$i]['stock_report_qty'],0).'</td>
            <td align="right">'.number_format($stock_reports[$i]['product_buy'],0).'</td> 
        </tr>
        ';  
 
    }
 
    $html .= ' 
        </tbody>
        <tfoot> 
            <tr >  
                <td align="center" colspan="9" style="padding-top:15px;"><font color="black">********* จบรายงาน *********</font></td>  
            </tr>
        </tfoot>
    </table>
    ';
    

// } 

?>