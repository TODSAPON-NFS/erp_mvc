<?PHP 
  
    $html  = '
    <table  width="100%" border="1" cellspacing="0" style="font-size:12px;">
        <thead>
            <tr>
                <th style="background:#c5c1c1" >invoice</th>
                <th style="background:#c5c1c1" >order</th>
                <th style="background:#c5c1c1" >pos</th>
                <th >poCode</th>
                <th >poNo</th>
                <th >edp</th>
                <th >item</th>
                <th >qty</th>
                <th >priceNet</th>
                <th >total</th>
                <th style="background:#c5c1c1" >posWert</th>
            </tr> 
        </thead>

        <tbody>

    '; 

    $index = 0;
    for($i=0; $i < count($purchase_orders); $i++){ 
        if($purchase_orders[$i]['product_code'] != $purchase_orders[$i-1]['product_code']){
            $index = 0;
        }
        $index ++;
        $html .= ' 
        <tr>  
            <td style="background:#c5c1c1" ></td> 
            <td style="background:#c5c1c1" ></td> 
            <td style="background:#c5c1c1" ></td> 
            <td>'.$purchase_orders[$i]['purchase_order_code'].' </td> 
            <td>'.$index.' </td> 
            <td>'.$purchase_orders[$i]['product_code'].' </td> 
            <td>'.$purchase_orders[$i]['product_name'].' </td> 
            <td>'.$purchase_orders[$i]['purchase_order_list_qty'].' </td> 
            <td>'.$purchase_orders[$i]['purchase_order_list_price'].' </td> 
            <td>'.$purchase_orders[$i]['purchase_order_list_price_sum'].' </td> 
            <td style="background:#c5c1c1" ></td> 
        </tr> 
        ';

    }

    $html .= ' 
        </tbody>
    </table>
    ';


?>