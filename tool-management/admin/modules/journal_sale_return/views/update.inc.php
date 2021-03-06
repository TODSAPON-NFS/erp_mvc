<script>

    var account_data = [
    <?php for($i = 0 ; $i < count($accounts) ; $i++ ){?>
        {
            account_id:'<?php echo $accounts[$i]['account_id'];?>',
            account_code:'<?php echo $accounts[$i]['account_code'];?>',
            account_name_th:'<?php echo $accounts[$i]['account_name_th'];?>',
            account_name_en:'<?php echo $accounts[$i]['account_name_en'];?>'
        },
    <?php }?>
    ];

    function check(){


        var journal_sale_return_code = document.getElementById("journal_sale_return_code").value;
        var journal_sale_return_date = document.getElementById("journal_sale_return_date").value;
        var journal_sale_return_name = document.getElementById("journal_sale_return_name").value;
        
        journal_sale_return_code = $.trim(journal_sale_return_code);
        journal_sale_return_date = $.trim(journal_sale_return_date);
        journal_sale_return_name = $.trim(journal_sale_return_name);
        

        if(journal_sale_return_code.length == 0){
            alert("Please input Journal Sale Return code");
            document.getElementById("journal_sale_return_code").focus();
            return false;
        }else if(journal_sale_return_date.length == 0){
            alert("Please input Journal Sale Return date");
            document.getElementById("journal_sale_return_date").focus();
            return false;
        }else if(journal_sale_return_name.length == 0){
            alert("Please input Journal Sale Return name");
            document.getElementById("journal_sale_return_name").focus();
            return false;
        }else{
            return true;
        }



    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
     }


     function add_row(id){
         var index = 0;
         if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
            index = 1;
         }else{
            index = $(id).closest('table').children('tbody').children('tr').length + 1;
         }
        $(id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td>'+
                    '<input type="hidden" name="journal_sale_return_list_id[]" value="0" />'+     
                    '<select class="form-control select" type="text" name="account_id[]" data-live-search="true" ></select>'+
                '</td>'+
                '<td><input type="text" class="form-control" name="journal_sale_return_list_name[]" value="' + document.getElementById("journal_sale_return_name").value + '" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_sale_return_list_debit[]"  /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_sale_return_list_credit[]" /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').empty();
        var str = "<option value=''>Select account</option>";
        $.each(account_data, function (index, value) {
            str += "<option value='" + value['account_id'] + "'>["+value['account_code']+"] " + value['account_name_th'] + "</option>";
        });

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').selectpicker();
        
    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Journal Sale Return  Management</h1>
    </div>
    <div class="col-lg-6" align="right">
       
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            เพิ่มสมุดรายวันรับคืนสินค้า /  Add Journal Sale Return   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=journal_special_06&action=edit&id=<?PHP echo $journal_sale_return_id; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>หมายเลขสมุดรายวันรับคืนสินค้า / Journal Sale Return Code <font color="#F00"><b>*</b></font></label>
                                <input id="journal_sale_return_code" name="journal_sale_return_code" class="form-control" value="<?php echo $journal_sale_return['journal_sale_return_code'];?>" readonly>
                                <p class="help-block">Example : JG1801001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>วันที่ออกสมุดรายวันรับคืนสินค้า / Journal Sale Return Date</label>
                                <input type="text" id="journal_sale_return_date" name="journal_sale_return_date"  class="form-control calendar" value="<?php echo $journal_sale_return['journal_sale_return_date'];?>" readonly/>
                                <p class="help-block">31/01/2018</p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>หัวข้อสมุดรายวันรับคืนสินค้า / Journal Sale Return Name</label>
                                <input type="text" id="journal_sale_return_name" name="journal_sale_return_name"  class="form-control" value="<?php echo $journal_sale_return['journal_sale_return_name'];?>" />
                                <p class="help-block"></p>
                            </div>
                        </div>
    
                    </div>
                        
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">บัญชี<br>(Account)</th>
                                <th style="text-align:center;">รายละเอียด<br>(Description)</th>
                                <th style="text-align:center;">เดบิต<br>(Debit)</th>
                                <th style="text-align:center;">เครดิต<br>(Credit)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($journal_sale_return_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="journal_sale_return_list_id[]" value="0" />
                                    <select  class="form-control select" name="account_id[]" onchange="show_data(this);" data-live-search="true" >
                                        <option value="">Select</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($accounts) ; $ii++){
                                        ?>
                                        <option <?php if($accounts[$ii]['account_id'] == $journal_sale_return_lists[$i]['account_id']){?> selected <?php }?> value="<?php echo $accounts[$ii]['account_id'] ?>">[<?php echo $accounts[$ii]['account_code'] ?>] <?php echo $accounts[$ii]['account_name_th'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_sale_return_list_name[]" value="<?php echo $journal_sale_return_lists[$i]['journal_sale_return_list_name']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_sale_return_list_debit[]" value="<?php echo $journal_sale_return_lists[$i]['journal_sale_return_list_debit']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_sale_return_list_credit[]" value="<?php echo $journal_sale_return_lists[$i]['journal_sale_return_list_credit']; ?>" /></td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="5" align="center">
                                    <a href="javascript:;" onclick="add_row(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มบัญชี / Add account</span>
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table> 

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=journal_special_06" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>