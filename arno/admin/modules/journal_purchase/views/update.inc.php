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


        var journal_purchase_code = document.getElementById("journal_purchase_code").value;
        var journal_purchase_date = document.getElementById("journal_purchase_date").value;
        var journal_purchase_name = document.getElementById("journal_purchase_name").value;
        
        journal_purchase_code = $.trim(journal_purchase_code);
        journal_purchase_date = $.trim(journal_purchase_date);
        journal_purchase_name = $.trim(journal_purchase_name);
        

        if(journal_purchase_code.length == 0){
            alert("Please input Journal Purchase code");
            document.getElementById("journal_purchase_code").focus();
            return false;
        }else if(journal_purchase_date.length == 0){
            alert("Please input Journal Purchase date");
            document.getElementById("journal_purchase_date").focus();
            return false;
        }else if(journal_purchase_name.length == 0){
            alert("Please input journal_purchase name");
            document.getElementById("journal_purchase_name").focus();
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
                    '<input type="hidden" name="journal_purchase_list_id[]" value="0" />'+     
                    '<select class="form-control select" type="text" name="account_id[]" data-live-search="true" ></select>'+
                '</td>'+
                '<td><input type="text" class="form-control" name="journal_purchase_list_name[]" value="' + document.getElementById("journal_purchase_name").value + '" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_purchase_list_debit[]"  /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_purchase_list_credit[]" /></td>'+
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
        <h1 class="page-header">Journal Purchase  Management</h1>
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
            เพิ่มสมุดรายวันซื้อ /  Add Journal Purchase   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=journal_special_01&action=edit&id=<?PHP echo $journal_purchase_id; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>หมายเลขสมุดรายวันซื้อ / Journal Purchase Code <font color="#F00"><b>*</b></font></label>
                                <input id="journal_purchase_code" name="journal_purchase_code" class="form-control" value="<?php echo $journal_purchase['journal_purchase_code'];?>" readonly>
                                <p class="help-block">Example : JG1801001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>วันที่ออกสมุดรายวันซื้อ / Journal Purchase Date</label>
                                <input type="text" id="journal_purchase_date" name="journal_purchase_date"  class="form-control calendar" value="<?php echo $journal_purchase['journal_purchase_date'];?>" readonly/>
                                <p class="help-block">31/01/2018</p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>หัวข้อสมุดรายวันซื้อ / Journal Purchase Name</label>
                                <input type="text" id="journal_purchase_name" name="journal_purchase_name"  class="form-control" value="<?php echo $journal_purchase['journal_purchase_name'];?>" />
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
                            for($i=0; $i < count($journal_purchase_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="journal_purchase_list_id[]" value="0" />
                                    <select  class="form-control select" name="account_id[]" onchange="show_data(this);" data-live-search="true" >
                                        <option value="">Select</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($accounts) ; $ii++){
                                        ?>
                                        <option <?php if($accounts[$ii]['account_id'] == $journal_purchase_lists[$i]['account_id']){?> selected <?php }?> value="<?php echo $accounts[$ii]['account_id'] ?>">[<?php echo $accounts[$ii]['account_code'] ?>] <?php echo $accounts[$ii]['account_name_th'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_purchase_list_name[]" value="<?php echo $journal_purchase_lists[$i]['journal_purchase_list_name']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_purchase_list_debit[]" value="<?php echo $journal_purchase_lists[$i]['journal_purchase_list_debit']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_purchase_list_credit[]" value="<?php echo $journal_purchase_lists[$i]['journal_purchase_list_credit']; ?>" /></td>
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
                            <a href="index.php?app=journal_special_01" class="btn btn-default">Back</a>
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