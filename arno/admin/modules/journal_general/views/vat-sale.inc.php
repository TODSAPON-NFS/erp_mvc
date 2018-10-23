


<table id="tb_invoice_customer" width="100%" class="table table-striped table-bordered table-hover" >
    <thead>
        <tr>
            <th style="text-align:center;" rowspan="2">ยื่นรวม<br>ในงวด</th>
            <th style="text-align:center;" colspan="2">ใบกำกับภาษี</th>
            <th style="text-align:center;" rowspan="2">รายการ</th>
            <th style="text-align:center;" colspan="2">ภาษีของคืนได้</th>
            <th style="text-align:center;" colspan="2">ภาษีของคืนไม่ได้</th>
            <th style="text-align:center;" rowspan="2">หมายเหตุ</th>
            <th rowspan="2"></th>
        </tr>
        <tr>
            <th style="text-align:center;">วันที่</th>
            <th style="text-align:center;">เลขที่</th>
            <th style="text-align:center;">มูลค่า</th>
            <th style="text-align:center;">ภาษี</th>
            <th style="text-align:center;">มูลค่า</th>
            <th style="text-align:center;">ภาษี</th>  
        </tr>
    </thead>
    <tbody>
    <?php  
        $invoice_customer_sum = 0;
        $invoice_customer_sum_vat = 0;
        $invoice_customer_sum_non = 0;
        $invoice_customer_sum_vat_non = 0;
        for($i=0; $i < count($invoice_customers); $i++){
            $invoice_customer_sum += $invoice_customer[$i]['invoice_customer_total_price']; 
            $invoice_customer_sum_vat += $invoice_customer[$i]['invoice_customer_vat_price']; 
            $invoice_customer_sum_non += $invoice_customer[$i]['invoice_customer_total_price_non']; 
            $invoice_customer_sum_vat_non += $invoice_customer[$i]['invoice_customer_vat_price_non']; 
        ?>
        <tr class="odd gradeX">
            <td>
                <input type="hidden" name="invoice_customer_id[]" value="<?php echo $invoice_customers[$i]['invoice_customer_id']; ?>" /> 
                <span name="display_customer_vat_section" ><?php echo $invoice_customers[$i]['vat_section']; ?></span>
            </td> 
            <td >
                <span name="display_invoice_customer_date" ><?php echo $invoice_customers[$i]['invoice_customer_date']; ?></span>
            </td> 
            <td >
                <span name="display_invoice_customer_code" ><?php echo $invoice_customers[$i]['invoice_customer_code']; ?></span>
            </td>
            <td>
                <span name="display_invoice_customer_description" ><?php echo $invoice_customers[$i]['invoice_customer_description']; ?></span>
            </td>
            <td align="right">
                <span name="display_invoice_customer_total_price" ><?php echo number_format($invoice_customers[$i]['invoice_customer_total_price'],2); ?></span>
            </td>
            <td align="right">
                <span name="display_invoice_customer_vat_price" ><?php echo number_format($invoice_customers[$i]['invoice_customer_vat_price'],2); ?></span>
            </td>
            <td align="right">
                <span name="display_invoice_customer_total_price_non" ><?php echo number_format($invoice_customers[$i]['invoice_customer_total_price_non'],2); ?></span>
            </td>
            <td align="right">
                <span name="display_invoice_customer_vat_price_non" ><?php echo number_format($invoice_customers[$i]['invoice_customer_vat_price_non'],2); ?></span>
            </td>
            <td >
                <span name="display_invoice_customer_remark" ><?php echo $invoice_customers[$i]['invoice_customer_remark']; ?></span>
            </td>
            <td>
                <a href="javascript:;" onclick="edit_invoice_customer_row(this);" style="color:orange;">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
                <a href="javascript:;" onclick="delete_invoice_customer_row(this);" style="color:red;">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
            </td>
        </tr>
        <?
        }
        ?>
    </tbody>

    </tbody>
    <tfoot>
        <tr class="odd gradeX">
            <td colspan="4" align="center">
                <a href="javascript:;" id="add_invoice_customer_row" onclick="add_invoice_customer_row(this,null);" style="color:red;">
                    <i class="fa fa-plus" aria-hidden="true"></i> 
                    <span>เพิ่มใบกำกับภาษี</span>
                </a> 
            </td>
            <td align="right">
                <span id="invoice_customer_sum" ><?php echo number_format($invoice_customer_sum,2); ?></span> 
            </td> 
            <td align="right">
                <span id="invoice_customer_sum_vat" ><?php echo number_format($invoice_customer_sum_vat,2); ?> </span> 
            </td> 
            <td align="right">
                <span id="invoice_customer_sum_non" ><?php echo number_format($invoice_customer_sum_non,2); ?> </span> 
            </td> 
            <td align="right">
                <span id="invoice_customer_sum_vat_non" ><?php echo number_format($invoice_customer_sum_vat_non,2); ?> </span> 
            </td> 
            <td colspan="2"> 
            </td>
        </tr>
    </tfoot>
</table>  

