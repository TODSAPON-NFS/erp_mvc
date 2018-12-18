




<table id="tb_cheque" width="100%" class="table table-striped table-bordered table-hover" >
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
        $cheque_sum = 0;
        for($i=0; $i < count($checks); $i++){
            $cheque_sum += $checks[$i]['check_total']; 
        ?>
        <tr class="odd gradeX">
            <td>
                <input type="hidden" name="check_id[]" value="<?php echo $checks[$i]['check_id']; ?>" /> 
                <span name="display_check_code" ><?php echo $checks[$i]['check_code']; ?></span>
            </td> 
            <td >
                <span name="display_check_date_write" ><?php echo $checks[$i]['check_date_write']; ?></span>
            </td> 
            <td >
                <span name="display_bank_name" ><?php echo $checks[$i]['bank_name']; ?></span>
            </td>
            <td align="right">
                <span name="display_check_total" ><?php echo number_format($checks[$i]['check_total'],2); ?></span>
            </td>
            <td >
                <span name="display_check_remark" ><?php echo $checks[$i]['check_remark']; ?></span>
            </td>
            <td>
                <a href="javascript:;" onclick="edit_cheque_row(this);" style="color:orange;">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
                <a href="javascript:;" onclick="delete_cheque_row(this);" style="color:red;">
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
                <a href="javascript:;" id="add_cheque_row" onclick="add_cheque_row(this,null);" style="color:red;">
                    <i class="fa fa-plus" aria-hidden="true"></i> 
                    <span>เพิ่มเช็ครับ</span>
                </a> 
            </td>
            <td align="right">
                <span id="cheque_sum"><?php echo number_format($cheque_sum,2); ?></span>
            </td> 
            <td colspan="2"> 
            </td>
        </tr>
    </tfoot>
</table>  

