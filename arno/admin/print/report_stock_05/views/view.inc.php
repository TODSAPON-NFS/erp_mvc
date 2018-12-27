<?PHP 
  
$html_head_pdf = '    
<table  width="100%" cellspacing="0" style="" > 
    <thead>
        <tr>  
            <th width="8%" ></th>  
            <th width="22%" ></th>  
            <th width="6%" ></th>  
            <th width="22%" ></th>    
            <th width="7%" align=""></th>
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>    
            <th width="7%" align=""></th>   
        </tr>
        <tr>
            <td colspan="5">บริษัท อาร์โน (ประเทศไทย) จำกัด</td> 
            <td colspan="6" align="right" ></td>
        </tr> 
        <tr>
            <td colspan="11" align="center" style="font-size:14px;color:#00F;"><b>รายงานราคาขายสินค้า</b></td>  
        </tr> 
        <tr> 
            <td colspan="1" align="left"><b>รหัสสินค้าจาก </b></td>
            <td colspan="1" align="left"> '.$product_start.' </td>
            <td colspan="1" align="center"> ถึง </td>
            <td colspan="1" align="left"> '.$product_end.' </td> 
            <td colspan="7" align="right" ><b>หน้า</b> : {PAGENO}/{nbpg}</td>
        </tr> 
        <tr> 
            <td colspan="1" align="left" width="80px" ><b>ลักษณะ </b></td>
            <td colspan="1" > '.$product_category['product_category_name'].' </td>
            <td colspan="1" align="center"></td>
            <td colspan="1" ></td>
            <td colspan="7" ></td> 
        </tr>
        <tr> 
            <td colspan="1" align="left" width="80px" ><b>ประเภท </b></td>
            <td colspan="1" > '.$product_type['product_type_name'].' </td>
            <td colspan="1" align="center"></td>
            <td colspan="1" ></td>
            <td colspan="7" ></td> 
        </tr> 
        <tr>  
            <th class="head-style" >No.</th>  
            <th class="head-style" colspan="1">รหัสสินค้า</th>  
            <th class="head-style" colspan="2">ชื่อสินค้า </th>
            <th class="head-style" align="">พิเศษ</th>
            <th class="head-style" align="">ตัวแทน</th>   
            <th class="head-style" align="">ผู้จำหน่าย</th>   
            <th class="head-style" align="">องค์กร</th>   
            <th class="head-style" align="">ใหญ่</th>   
            <th class="head-style" align="">กลาง</th>   
            <th class="head-style" align="">เล็ก</th>    
        </tr>
    </thead>
</table> 
';
$html_head_excel = ' 
<table  width="100%" cellspacing="0" style="" > 
    <thead>
        <tr>  
            <th width="8%" ></th>  
            <th width="22%" ></th>  
            <th width="6%" ></th>  
            <th width="22%" ></th>    
            <th width="7%" align=""></th>
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>    
            <th width="7%" align=""></th>   
        </tr>
        <tr>
            <td colspan="5">บริษัท อาร์โน (ประเทศไทย) จำกัด</td> 
            <td colspan="6" align="right" ></td>
        </tr> 
        <tr>
            <td colspan="11" align="center" style="font-size:14px;color:#00F;"><b>รายงานราคาขายสินค้า</b></td>  
        </tr> 
        <tr> 
            <td colspan="1" align="left"><b>รหัสสินค้าจาก </b></td>
            <td colspan="1" align="left"> '.$product_start.' </td>
            <td colspan="1" align="center"> ถึง </td>
            <td colspan="1" align="left"> '.$product_end.' </td> 
            <td colspan="7" align="right" > </td>
        </tr> 
        <tr> 
            <td colspan="1" align="left" width="80px" ><b>ลักษณะ </b></td>
            <td colspan="1" > '.$product_category.' </td>
            <td colspan="1" align="center"></td>
            <td colspan="1" ></td>
            <td colspan="7" ></td> 
        </tr>
        <tr> 
            <td colspan="1" align="left" width="80px" ><b>ประเภท </b></td>
            <td colspan="1" > '.$product_type.' </td>
            <td colspan="1" align="center"></td>
            <td colspan="1" ></td>
            <td colspan="7" ></td> 
        </tr> 
        <tr>  
            <th class="head-style" >No.</th>  
            <th class="head-style" colspan="1">รหัสสินค้า</th>  
            <th class="head-style" colspan="2">ชื่อสินค้า </th>
            <th class="head-style" align="">พิเศษ</th>
            <th class="head-style" align="">ตัวแทน</th>   
            <th class="head-style" align="">ผู้จำหน่าย</th>   
            <th class="head-style" align="">องค์กร</th>   
            <th class="head-style" align="">ใหญ่</th>   
            <th class="head-style" align="">กลาง</th>   
            <th class="head-style" align="">เล็ก</th>    
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
        .head-style{
            padding-top:6px;
            padding-bottom:5px;  
            border-top: 1px dotted black;
            border-bottom: 1px dotted black;
        }
        .text-align-center{ 
            text-align:center;
        }
        .text-align-left{ 
            text-align:left;
        }
        .text-align-right{ 
            text-align:right;
        }  

    </style>';  

    

    $html .= '
    <table  width="100%" cellspacing="0" style="font-size:12px;"> 
        <thead>
            <tr>  
                <th width="8%" ></th>  
                <th width="22%" ></th>  
                <th width="6%" ></th>  
                <th width="22%" ></th>    
                <th width="7%" align=""></th>
                <th width="7%" align=""></th>   
                <th width="7%" align=""></th>   
                <th width="7%" align=""></th>   
                <th width="7%" align=""></th>   
                <th width="7%" align=""></th>    
                <th width="7%" align=""></th>   
            </tr>
        </thead>
        <tbody>

    ';
 
     
    for($i=0; $i < count($stock_reports); $i++){ 

        $html .= ' 
        <tr>  
            <td class="" align="center" >'.($i+1).'</th>  
            <td class="" align="left" >'.$stock_reports[$i]['product_code'].'</td>  
            <td class="" align="left" colspan="2">'.$stock_reports[$i]['product_name'].'</td>
            <td class="" align="right">'.number_format($stock_reports[$i]['product_price_1'],2).'</td>
            <td class="" align="right">'.number_format($stock_reports[$i]['product_price_2'],2).'</td>   
            <td class="" align="right">'.number_format($stock_reports[$i]['product_price_3'],2).'</td>   
            <td class="" align="right">'.number_format($stock_reports[$i]['product_price_4'],2).'</td>   
            <td class="" align="right">'.number_format($stock_reports[$i]['product_price_5'],2).'</td>   
            <td class="" align="right">'.number_format($stock_reports[$i]['product_price_6'],2).'</td>   
            <td class="" align="right">'.number_format($stock_reports[$i]['product_price_7'],2).'</td>    
        </tr> 
        '; 
        
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
   

?>