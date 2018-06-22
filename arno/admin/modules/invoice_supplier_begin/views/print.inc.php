<div style="font-size:10px;padding-left:32px;padding-top:220px;">
    <div style="display:block;padding:8;">
        <?PHP echo $invoice_supplier['supplier_code']; ?>
    </div>
    <div>
        <table width="595" heigth="320">
            <tr>
                <td style="padding:8px;">
                    <?PHP echo $invoice_supplier['invoice_supplier_name']; 
                        if( (int)$invoice_supplier['supplier_branch'] * 1 == 0){
                            echo " สำนักงานใหญ่";
                        } else {
                            echo "สาขา " . ((int)$invoice_supplier['supplier_branch'] * 1) ;
                        }
                    ?>  <br>
                    <?PHP echo $invoice_supplier['invoice_supplier_address']; ?><br>
                    เลขประจำตัวผู้เสียภาษี / Tax : <?PHP echo $invoice_supplier['invoice_supplier_tax']; ?>
                </td>
                <td width="200">
                    <table>
                        <tr>
                            <td width="60" height="32" valign="middle" align="left">
                            </td>
                            <td width="140" height="32" valign="middle" align="left">
                                <?PHP echo $invoice_supplier['invoice_supplier_date']; ?>
                            </td>
                            <td width="60" height="32" valign="middle" align="left">
                            </td>
                            <td width="140" height="32" valign="middle" align="left">
                                <?PHP echo $invoice_supplier['invoice_supplier_code']; ?>
                            </td>

                        </tr>

                        <tr>
                            <td width="60" height="32" valign="middle" align="left">
                            </td>

                            <td width="140" height="32" valign="middle" align="left">
                                <?PHP echo $invoice_supplier['invoice_supplier_term']; ?>
                            </td>

                            <td width="60" height="32" valign="middle" align="left">
                            </td>

                            <td width="140" height="32" valign="middle" align="left">
                                -
                            </td>

                        </tr>

                        <tr>
                            <td width="60" height="32" valign="middle" align="left">
                            </td>
                            <td width="140" height="32" valign="middle" align="left">
                                <?PHP echo $invoice_supplier['invoice_supplier_due']; ?>
                            </td>
                            <td width="60" height="32" valign="middle" align="left">
                            </td>
                            <td width="140" height="32" valign="middle" align="left">
                                <?PHP echo $invoice_supplier['user_name']; ?> <?PHP echo $invoice_supplier['user_lastname']; ?>
                            </td>

                        </tr>
                    </table>

                </td>
            </tr>
        </table>
    </div>

    <div style="height:64px;">

    </div>

    <div style="height:440px;">
        <table width="595" >

            <tbody>
                <?php 
                $total = 0;
                for($i=0; $i < count($invoice_supplier_lists); $i++){
                ?>
                <tr >
                    <td valign="top" width="20">
                        <?php echo $i+1; ?>.
                    </td>

                    <td valign="top" width="280">
                        <b><?php echo $invoice_supplier_lists[$i]['product_code']; ?></b><br>
                        <span>Sub name : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_product_name']; ?><br>
                        <span>Detail : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_product_detail']; ?><br>
                        <span>Remark : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_remark']; ?><br>
                    </td>

                    <td valign="top" align="right" width="80"><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_qty']; ?></td>
                    <td valign="top" align="right" width="80"><?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_price'],2); ?></td>
                    <td valign="top" align="right" width="80"><?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_qty'] * $invoice_supplier_lists[$i]['invoice_supplier_list_price'],2); ?></td>
                    

                </tr>
                <?
                    $total += $invoice_supplier_lists[$i]['invoice_supplier_list_qty'] * $invoice_supplier_lists[$i]['invoice_supplier_list_price'];
                }
                ?>
            </tbody>
        </table>
    </div>
                        
                        
    <table width="595">
        <tr>
            <td width="20">
            </td>

            <td width="280">
                
            </td>


            <td width="80" align="right"></td>
            <td width="80" align="right"></td>
            <td width="80" align="right"></td>
            

        </tr>
            
        <tr class="odd gradeX">
            <td colspan="2" rowspan="3">
                
            </td>
            <td colspan="2" align="left" style="vertical-align: middle;">
                
            </td>
            <td style="text-align: right;">
                <?PHP echo number_format($total,2) ;?>
            </td>
            
        </tr>
        <tr class="odd gradeX">
            <td colspan="2" align="left" style="vertical-align: middle;">
                <table>
                    <tr>
                        <td>
                            
                        </td>
                        <td style = "padding-left:8px;padding-right:8px;width:72px;">
                            <?PHP echo $vat;?>
                        </td>
                        <td width="16">
                        
                        </td>
                    </tr>
                </table>
                
            </td>
            <td style="text-align: right;">
                <?PHP echo number_format(($vat/100) * $total,2) ;?>
            </td>
            
        </tr>
        <tr class="odd gradeX">
            <td colspan="2" align="left" style="vertical-align: middle;">
                
            </td>
            <td style="text-align: right;">
                <?PHP echo number_format(($vat/100) * $total + $total,2) ;?>
            </td>
            
        </tr>
    </table>   

</div>

<script>//window.print();</script>
                