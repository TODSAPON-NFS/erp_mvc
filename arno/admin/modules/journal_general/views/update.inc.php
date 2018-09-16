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


        var journal_general_code = document.getElementById("journal_general_code").value;
        var journal_general_date = document.getElementById("journal_general_date").value;
        var journal_general_name = document.getElementById("journal_general_name").value;
        var debit_total = parseFloat($('#journal_general_list_debit').val( ).toString().replace(new RegExp(',', 'g'),''));
        var credit_total = parseFloat($('#journal_general_list_credit').val( ).toString().replace(new RegExp(',', 'g'),''));

        journal_general_code = $.trim(journal_general_code);
        journal_general_date = $.trim(journal_general_date);
        journal_general_name = $.trim(journal_general_name);
        

        if(journal_general_code.length == 0){
            alert("Please input Journal General code");
            document.getElementById("journal_general_code").focus();
            return false;
        }else if(journal_general_date.length == 0){
            alert("Please input Journal General date");
            document.getElementById("journal_general_date").focus();
            return false;
        }else if(journal_general_name.length == 0){
            alert("Please input journal_general name");
            document.getElementById("journal_general_name").focus();
            return false;
        }else if (debit_total != credit_total){
            alert("Can not save data. \nBecause credit value and debit value not match. "); 
            return false;
        } else{
            return true;
        }



    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
     }


     function add_row(id){
        var journal_general_name = document.getElementById("journal_general_name").value;
         var index = 0;
         if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
            index = 1;
         }else{
            index = $(id).closest('table').children('tbody').children('tr').length + 1;
         }
        $(id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td>'+
                    '<input type="hidden" name="journal_general_list_id[]" value="0" />'+     
                    '<select class="form-control select" type="text" name="account_id[]" data-live-search="true" ></select>'+
                '</td>'+
                '<td><input type="text" class="form-control" name="journal_general_list_name[]" value="'+ journal_general_name +'" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_general_list_debit[]" onchange="val_format(this);" value="0" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_general_list_credit[]" onchange="val_format(this);" value="0" /></td>'+
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
            str += "<option value='" + value['account_id'] + "'>"+value['account_code']+" - " + value['account_name_th'] + "</option>";
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').selectpicker();
    }

    function val_format(id){
        var val =  parseFloat($(id).val().replace(',',''));  
        if(isNaN(val)){
            val = 0;
        }
        $(id).val( val.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") ); 
        calculateAll();
    }

    function calculateAll(){
        var debit = document.getElementsByName('journal_general_list_debit[]');
        var credit = document.getElementsByName('journal_general_list_credit[]');
        var debit_total = 0.0;
        var credit_total = 0.0;

        for(var i = 0 ; i < debit.length ; i++){
            
            debit_total += parseFloat(debit[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        for(var i = 0 ; i < credit.length ; i++){
            
            credit_total += parseFloat(credit[i].value.toString().replace(new RegExp(',', 'g'),''));
        } 

        $('#journal_general_list_debit').val((debit_total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#journal_general_list_credit').val((credit_total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Journal General  Management</h1>
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
            เพิ่มสมุดรายวันทั่วไป /  Add Journal General   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=journal_general&action=edit&id=<?PHP echo $journal_general_id; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>หมายเลขสมุดรายวันทั่วไป / Journal General Code <font color="#F00"><b>*</b></font></label>
                                <input id="journal_general_code" name="journal_general_code" class="form-control" value="<?php echo $journal_general['journal_general_code'];?>" >
                                <p class="help-block">Example : JG1801001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>วันที่ออกสมุดรายวันทั่วไป / Journal General Date</label>
                                <input type="text" id="journal_general_date" name="journal_general_date"  class="form-control calendar" value="<?php echo $journal_general['journal_general_date'];?>" readonly/>
                                <p class="help-block">31/01/2018</p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>หัวข้อสมุดรายวันทั่วไป / Journal General Name</label>
                                <input type="text" id="journal_general_name" name="journal_general_name"  class="form-control" value="<?php echo $journal_general['journal_general_name'];?>" />
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
                            $journal_general_list_debit = 0;
                            $journal_general_list_credit = 0;
                            for($i=0; $i < count($journal_general_lists); $i++){
                                $journal_general_list_debit += $journal_general_lists[$i]['journal_general_list_debit'];
                                $journal_general_list_credit += $journal_general_lists[$i]['journal_general_list_credit'];
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="journal_general_list_id[]" value="0" />
                                    <select  class="form-control select" name="account_id[]" onchange="show_data(this);" data-live-search="true" >
                                        <option value="">Select</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($accounts) ; $ii++){
                                        ?>
                                        <option <?php if($accounts[$ii]['account_id'] == $journal_general_lists[$i]['account_id']){?> selected <?php }?> value="<?php echo $accounts[$ii]['account_id'] ?>">[<?php echo $accounts[$ii]['account_code'] ?>] <?php echo $accounts[$ii]['account_name_th'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td align="right"><input type="text" class="form-control" name="journal_general_list_name[]" value="<?php echo $journal_general_lists[$i]['journal_general_list_name']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_general_list_debit[]" onchange="val_format(this);" value="<?php echo $journal_general_lists[$i]['journal_general_list_debit']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_general_list_credit[]" onchange="val_format(this);" value="<?php echo $journal_general_lists[$i]['journal_general_list_credit']; ?>" /></td>
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
                                <td colspan="2" align="center">
                                    <a href="javascript:;" onclick="add_row(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มบัญชี / Add account</span>
                                    </a>
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="journal_general_list_debit" value="<?php echo number_format($journal_general_list_debit,2); ?>" readonly />
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="journal_general_list_credit" value="<?php echo number_format($journal_general_list_credit,2); ?>" readonly />
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table> 

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=journal_general" class="btn btn-default">Back</a>
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