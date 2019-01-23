<?PHP 

 
$stock_sum = 0 ;
$count_product = 0;
$count_product_sum = 0;
$i = 0;  
$srt_stock_group = "" ;


if ($keyword == ""){
    $keyword = '-';
}

if($stock_group_id == 0){
    $srt_stock_group = "ทั้งหมด";
}else{
    $srt_stock_group = "[".$stock_group['stock_group_code']."] ".$stock_group['stock_group_name'];
}



$html_head_pdf = '  
<table width="100%" border="0" cellspacing="0">
    <tr>
        <td colspan="3">บริษัท อาร์โน (ประเทศไทย) จำกัด</td>  
    </tr> 
    <tr>
        <td colspan="6" align="center" style="font-size:14px;color:#00F;"><b> สินค้าเคลื่อนไหวที่มีปัญหา </b></td>  
    </tr> 
    <tr>
        <td align="left" width="80px" ><b>รหัสสินค้า</b></td>
        <td width="200" >'.$keyword.'</td>
        <td width="100" align="center"> <b></b>  </td>
        <td width="150">  </td>
        <td ></td>
        <td align="right" width="120px" > <b>หน้า</b> : {PAGENO}/{nbpg} </td>
    </tr> 
    <tr> 
        <td align="left" width="80px" ><b>คลังสินค้า </b></td>
        <td width="200" colspan="1" > '.$srt_stock_group.' </td> 
        <td width="100" align="center" colspan="1" ><b>ณ วันที่ </b></td>
        <td  colspan="1" > '.$date_.' </td>  
        <td ></td>
        <td  colspan="1" >   </td>  
    </tr>  
</table>

<table  width="100%" cellspacing="0" style="font-size:12px;margin-top:10px;border-top: 1px dotted black;border-bottom: 1px dotted black;padding-top:5px;padding-bottom:3px;" >
<thead>
    <tr> 
        <th width="5%">ลำดับ</th>
        <th width="20%" >คลังสินค้า</th> 
        <th width="10%" >วันที่</th> 
        <th width="30%">รหัสสินค้า</th>                     
        <th align="center">จำนวน</th>
        <th>ราคาต่อหน่วย</th> 
        <th>ราคารวม</th>  
                               
    </tr>
</thead>
</table> 
';
$html_head_excel = ' 
<div align="center" style="font-size:14px;color:#00F;"> <b>สินค้าเคลื่อนไหวที่มีปัญหา</b></div>
    <table width="100%" border="0" cellspacing="0">
        <tr>
             <td colspan="3">บริษัท อาร์โน (ประเทศไทย) จำกัด</td>  
        </tr> 
        <tr>
            <td colspan="6" align="center" style="font-size:14px;color:#00F;"><b> สินค้าเคลื่อนไหวที่มีปัญหา </b></td>  
        </tr> 
        <tr>
            <td align="left" width="80px" ><b>รหัสสินค้า</b></td>
            <td width="200" >'.$keyword.'</td>
            <td width="100" align="center"> <b></b>  </td>
            <td width="150">  </td>
            <td ></td>
            <td align="right" width="120px"></td>
        </tr> 
        <tr> 
            <td align="left" width="80px" ><b>คลังสินค้า </b></td>
            <td width="200" colspan="1" > '.$srt_stock_group.' </td> 
            <td width="100" align="center" colspan="1" ><b>ณ วันที่ </b></td>
            <td  colspan="1" > '.$date_.' </td>  
            <td ></td>
            <td  colspan="1" >   </td>  
        </tr>  
    </table>  
<table  width="100%" cellspacing="0" style="font-size:12px;margin-top:10px;border-top: 1px dotted black;border-bottom: 1px dotted black;padding-top:5px;padding-bottom:3px;" >
    thead>
        <tr> 
            <th width="5%">ลำดับ</th>
            <th width="20%" >คลังสินค้า</th> 
            <th width="10%" >วันที่</th> 
            <th width="30%">รหัสสินค้า</th>                     
            <th align="center">จำนวน</th>
            <th>ราคาต่อหน่วย</th> 
            <th>ราคารวม</th>                             
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
    $html .= '<table  width="100%" cellspacing="0" style="font-size:12px;">
    <tr>
        <th width="5%"></th>
        <th width="20%" ></th> 
        <th width="10%" ></th> 
        <th width="30%"></th>                     
        <th align="center"></th>
        <th></th> 
        <th></th>  
    </tr> 
        <tbody>
    
    ';
 
     
    for(; $i < count($stock_reports); $i++){
        

        $html .= ' 
        <tr> 
            <td align="left" >'.($i+1).'</td> 
            <td> '.$stock_reports[$i]['stock_group_name'].' </td>
            <td> '.$stock_reports[$i]['stock_date'] .'</td>
            <td> '.$stock_reports[$i]['product_code']." - ".$stock_reports[$i]['product_name'].'</td>
            <td align="right"> '.$stock_reports[$i]['balance_qty'].'</td>
            <td align="right"> '.number_format($stock_reports[$i]['balance_stock_cost_avg'],2).'</td>
            <td align="right"> '. number_format($stock_reports[$i]['balance_stock_cost_avg_total'],2).'</td> '.
            '
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
            </table>
            '.' 
        ';
    } 

} 

?>