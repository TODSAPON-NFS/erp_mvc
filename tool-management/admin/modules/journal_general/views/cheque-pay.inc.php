

<table id="tb_cheque_pay" width="100%" class="table table-striped table-bordered table-hover" >
    <thead>
        <tr>
            <th style="text-align:center;">เลขที่เช็ค</th>
            <th style="text-align:center;">ลงวันที่</th>
            <th style="text-align:center;">ธนาคาร</th>
            <th style="text-align:center;">จำนวนเงิน</th>
            <th style="text-align:center;">หมายเหตุ</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php  
        $cheque_pay_sum = 0;
        for($i=0; $i < count($check_pays); $i++){
            $cheque_pay_sum += $check_pays[$i]['check_pay_total']; 
        ?>
        <tr class="odd gradeX">
            <td>
                <input type="hidden" name="check_pay_id[]" value="<?php echo $check_pays[$i]['check_pay_id']; ?>" /> 
                <span name="display_check_pay_code" ><?php echo $check_pays[$i]['check_pay_code']; ?></span>
            </td> 
            <td >
                <span name="display_check_pay_date_write" ><?php echo $check_pays[$i]['check_pay_date_write']; ?></span>
            </td> 
            <td >
                <span name="display_bank_name" ><?php echo $check_pays[$i]['bank_account_name']; ?></span>
            </td>
            <td align="right">
                <span name="display_check_pay_total" ><?php echo number_format($check_pays[$i]['check_pay_total'],2); ?></span>
            </td>
            <td >
                <span name="display_check_pay_remark" ><?php echo $check_pays[$i]['check_pay_remark']; ?></span>
            </td>
            <td>
                <a href="javascript:;" onclick="edit_cheque_pay_row(this);" style="color:orange;">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
                <a href="javascript:;" onclick="delete_cheque_pay_row(this);" style="color:red;">
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
            <td colspan="3" align="center">
                <a href="javascript:;" id="add_cheque_pay_row" onclick="add_cheque_pay_row(this,null);" style="color:red;">
                    <i class="fa fa-plus" aria-hidden="true"></i> 
                    <span>เพิ่มเช็คจ่าย</span>
                </a> 
            </td>
            <td align="right">
                <span id="cheque_pay_sum"><?php echo number_format($cheque_pay_sum,2); ?></span>
            </td> 
            <td colspan="2"> 
            </td>
        </tr>
    </tfoot>
</table>  

