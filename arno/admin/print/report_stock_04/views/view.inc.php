<?PHP 
  
$html_head_pdf = '    
<table  width="100%" cellspacing="0" style="" > 
    <thead>
        <tr>  
            <th width="5%" ></th>  
            <th width="7%" ></th>  
            <th width="8%" ></th>  
            <th width="7%" ></th>  
            <th width="8%" ></th>  
            <th width="7%" align=""></th>
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>    
            <th width="16%" align=""></th>   
        </tr>
        <tr>
            <td colspan="6">บริษัท อาร์โน (ประเทศไทย) จำกัด</td> 
            <td colspan="7" align="right" ></td>
        </tr> 
        <tr>
            <td colspan="13" align="center" style="font-size:14px;color:#00F;"><b>รายงานรายละเอียดสินค้า</b></td>  
        </tr> 
        <tr> 
            <td colspan="2" align="left"><b>รหัสสินค้าจาก </b></td>
            <td colspan="3" align="left"> '.$product_start.' </td>
            <td colspan="1" align="center"> ถึง </td>
            <td colspan="3" align="left"> '.$product_end.' </td> 
            <td colspan="4" align="right" ><b>หน้า</b> : {PAGENO}/{nbpg}</td>
        </tr>  
        <tr>
            <td colspan="13" ></td>  
        </tr> 
        <tr>     
            <th class="head-style " style="text-align:center;" width="5%" >No.</th>  
            <th class="head-style " align="left" colspan="2" >รหัสสินค้า</th>  
            <th class="head-style " align="left" colspan="2" >ชื่อสินค้า </th>
            <th class="head-style " align="left">ลักษณะสินค้า</th>
            <th class="head-style " align="left">กลุ่มสินค้า</th>   
            <th class="head-style " align="left">ประเภทสินค้า</th>   
            <th class="head-style " align="left">บาร์โค๊ต</th>   
            <th class="head-style " align="left">หน่วยสินค้า</th>   
            <th class="head-style " align="left">บัญชีเมื่อซื้อ</th>   
            <th class="head-style " align="left">บัญชีเมื่อขาย</th>    
            <th class="head-style " align="left">รายละเอียดสินค้า</th>   
        </tr>
    </thead>
</table> 
';
$html_head_excel = ' 
<table  width="100%" cellspacing="0" style="" > 
    <thead>
        <tr>  
            <th width="5%" ></th>  
            <th width="7%" ></th>  
            <th width="8%" ></th>  
            <th width="7%" ></th>  
            <th width="8%" ></th>  
            <th width="7%" align=""></th>
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>   
            <th width="7%" align=""></th>    
            <th width="16%" align=""></th>   
        </tr>
        <tr>
            <td colspan="6">บริษัท อาร์โน (ประเทศไทย) จำกัด</td> 
            <td colspan="7" align="right" ></td>
        </tr> 
        <tr>
            <td colspan="13" align="center" style="font-size:14px;color:#00F;"><b>รายงานรายละเอียดสินค้า</b></td>  
        </tr> 
        <tr> 
            <td colspan="2" align="left"><b>รหัสสินค้าจาก </b></td>
            <td colspan="3" align="left"> '.$product_start.' </td>
            <td colspan="1" align="center"> ถึง </td>
            <td colspan="3" align="left"> '.$product_end.' </td> 
            <td colspan="4" align="right" > </td>
        </tr>  
        <tr>     
            <th class="head-style " style="text-align:center;" width="5%" >No.</th>  
            <th class="head-style " align="left" colspan="2" >รหัสสินค้า</th>  
            <th class="head-style " align="left" colspan="2" >ชื่อสินค้า </th>
            <th class="head-style " align="left">ลักษณะสินค้า</th>
            <th class="head-style " align="left">กลุ่มสินค้า</th>   
            <th class="head-style " align="left">ประเภทสินค้า</th>   
            <th class="head-style " align="left">บาร์โค๊ต</th>   
            <th class="head-style " align="left">หน่วยสินค้า</th>   
            <th class="head-style " align="left">บัญชีเมื่อซื้อ</th>   
            <th class="head-style " align="left">บัญชีเมื่อขาย</th>    
            <th class="head-style " align="left">รายละเอียดสินค้า</th>   
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
                <th width="5%" ></th>  
                <th width="7%" ></th>  
                <th width="8%" ></th>  
                <th width="7%" ></th>  
                <th width="8%" ></th>  
                <th width="7%" align=""></th>
                <th width="7%" align=""></th>   
                <th width="7%" align=""></th>   
                <th width="7%" align=""></th>   
                <th width="7%" align=""></th>   
                <th width="7%" align=""></th>   
                <th width="7%" align=""></th>    
                <th width="16%" align=""></th>   
            </tr>
        </thead>
        <tbody>

    ';
 
     
    for($i=0; $i < count($stock_reports); $i++){ 

        $html .= ' 
        <tr>  
            <td align="center">'. ($i+1).'</td> 
            <td align="left" colspan="2">'. $stock_reports[$i]['product_code_first'].$stock_reports[$i]['product_code'].'</td> 
            <td align="left" colspan="2">'. $stock_reports[$i]['product_name'].'</td> 
            <td align="left">'. $stock_reports[$i]['product_category_name'].'</td>
            <td align="left">'. $stock_reports[$i]['product_group_name'].'</td>
            <td align="left">'. $stock_reports[$i]['product_type_name'].'</td>
            <td align="left">'. $stock_reports[$i]['product_barcode'].'</td>
            <td align="left">'. $stock_reports[$i]['product_unit_name'].'</td>
            <td align="left">'. $stock_reports[$i]['buy_account_name'].'</td>
            <td align="left">'. $stock_reports[$i]['sale_account_name'].'</td> 
            <td align="left">'. $stock_reports[$i]['product_description'].'</td> 
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