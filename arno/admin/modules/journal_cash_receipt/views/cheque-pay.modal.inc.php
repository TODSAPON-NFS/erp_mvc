
<div id="modalChequePay" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="row">
                <div class="col-md-6">
                    <h4 class="modal-title">สร้างเช็คจ่าย / Add Cheque Payment</h4>
                </div>
                <div class="col-md-4"> 
                    <input id="check_pay_code_search" place name="check_pay_code_search" placeholder="Search cheque" class="form-control" type="text"  value="QP"  />
                </div> 
                <div class="col-md-1" align="right"> 
                    <button class="btn btn-danger"  onclick="get_cheque_pay_id(this)" >Get cheque</button> 
                </div>
        </div>

        <div  class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>วันที่เช็ค</label>
                                <input id="check_pay_date_write" name="check_pay_date_write" class="form-control calendar" type="text" value="" readonly />
                                <p class="help-block">01-06-2018 </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>เลขที่เช็ค <font color="#F00"><b>*</b></font></label>
                                <input id="check_pay_code" name="check_pay_code" class="form-control" type="text"  value="QP"  />
                                <p class="help-block">Example : QP4411555.</p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ผู้ขาย <font color="#F00"><b>*</b></font> </label>
                                <select id="cheque_pay_supplier_id" name="cheque_pay_supplier_id" class="form-control select"  data-live-search="true">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($suppliers) ; $i++){
                                    ?>
                                    <option  value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> (<?php echo $suppliers[$i]['supplier_name_th'] ?>)</option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>จ่ายจากบัญชี <font color="#F00"><b>*</b></font> </label>
                                <select id="bank_account_id" name="bank_account_id" class="form-control select" data-live-search="true"> 
                                    <?php 
                                    for($i =  0 ; $i < count($bank_accounts) ; $i++){
                                    ?>
                                    <option value="<?php echo $bank_accounts[$i]['bank_account_id'] ?>"><?php echo $bank_accounts[$i]['bank_account_name'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : BKK.</p>
                            </div>
                        </div> 
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>วันจ่ายที่เช็ค</label>
                                <input id="check_pay_date" name="check_pay_date" class="form-control calendar" value="" readonly>
                                <p class="help-block">01-06-2018 </p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>จำนวนเงิน</label>
                                <input id="check_pay_total" name="check_pay_total" class="form-control " value="" >
                                <p class="help-block">80000 </p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>หมายเหตุุ</label>
                                <input id="check_pay_remark" name="check_pay_remark" class="form-control" type="text" value="" />
                                <p class="help-block">- </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>

        <div class="modal-footer">
            <input type="hidden" id="check_pay_id" name="check_pay_id" value="" />
            <input type="hidden" id="cheque_pay_action" name="cheque_pay_action" value="" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="cheque_pay_delete" class="btn btn-danger" onclick="delete_check_pay();" >Delete Cheque</button>
            <button type="button" id="cheque_pay_submit" class="btn btn-primary" onclick="check_pay_post();" >Add Cheque</button>
        </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<script>
var row_cheque_pay_add_id;
var row_cheque_pay_update_id;

var cheque_pay_options = {
        url: function(keyword) {
            return "controllers/getChequePayByKeyword.php?keyword="+keyword;
        },

        getValue: function(element) {
            return element.check_pay_code ;
        },

        template: {
            type: "description",
            fields: {
                description: "check_pay_remark"
            }
        },
        
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },

        preparePostData: function(data) {
            data.keyword = $(".example-ajax-post").val();
            return data;
        },

        requestDelay: 400
    };

$('#check_pay_code_search').easyAutocomplete(cheque_pay_options);

function edit_cheque_pay_row(id,journal_id){
    row_journal_id = journal_id;
    row_cheque_pay_update_id = id;
    var check_pay_id = $(id).closest('tr').children('td').children('input[name="check_pay_id[]"]').val();
    console.log(check_pay_id);
    $.post( "controllers/getChequePayByChequePayID.php", { 'check_pay_id': check_pay_id }, function( data ) { 
        console.log(data);
        if(data !== null){

            $(id).closest('tr').children('td').children('input[name="check_pay_id[]"]').val(data.check_pay_id);

            $('#check_pay_code').val(data.check_pay_code);  
            $('#check_pay_id').val(data.check_pay_id);
            $('#check_pay_date_write').val(data.check_pay_date_write); 
            $('#check_pay_date_recieve').val(data.check_pay_date_recieve); 
            
            $('#cheque_pay_supplier_id').val(data.supplier_id);
            $('#bank_account_id').val(data.bank_account_id);
            $('#bank_branch').val(data.bank_branch);
            $('#check_pay_date').val(data.check_pay_date);
            $('#check_pay_total').val(data.check_pay_total);
            $('#check_pay_remark').val(data.check_pay_remark);

            $('#cheque_pay_submit').html('Update Cheque');
            $('#cheque_pay_action').val('edit');
            $('#cheque_pay_delete').show();

            $('.select').selectpicker('refresh');
            $('#modalChequePay').modal('show');

        }else{ 
            $('#check_pay_id').val('0');
            $('#check_pay_code').val('QP');   
            $('#check_pay_date_write').val($('#journal_cash_receipt_date').val()); 
            $('#check_pay_date_recieve').val($('#journal_cash_receipt_date').val());
            $('#cheque_pay_supplier_id').val($('#supplier_id').val());
            $('#bank_account_id').val();
            $('#bank_branch').val();
            $('#check_pay_date').val($('#journal_cash_receipt_date').val());
            $('#check_pay_total').val(0);
            $('#check_pay_remark').val($('#journal_cash_receipt_name').val());

            $('#check_pay_submit').html('Add Cheque');
            $('#cheque_pay_action').val('add');
            $('#cheque_pay_delete').hide();

            $('.select').selectpicker('refresh');
            $('#modalChequePay').modal('show');
        }
        
    }); 
}


function add_cheque_pay_row(id,journal_id){
    
    $('#check_pay_id').val('0');
    $('#check_pay_code').val('QP');   
    $('#check_pay_date_write').val($('#journal_cash_receipt_date').val()); 
    $('#check_pay_date_recieve').val($('#journal_cash_receipt_date').val());
    $('#cheque_pay_supplier_id').val($('#supplier_id').val());
    $('#bank_account_id').val();
    $('#bank_branch').val();
    $('#check_pay_date').val($('#journal_cash_receipt_date').val());
    $('#check_pay_total').val(0);
    $('#check_pay_remark').val($('#journal_cash_receipt_name').val());

    $('#check_pay_submit').html('Add Cheque');
    $('#cheque_pay_action').val('add');
    $('#cheque_pay_delete').hide();

    $('.select').selectpicker('refresh');
    $('#modalChequePay').modal('show'); 
    row_cheque_pay_add_id = id;
    row_journal_id = journal_id;
}

function get_cheque_pay_id(id){ 
    get_cheque_pay_data(id,$("#check_pay_code_search").val()); 
}



function get_cheque_pay_data(id,code){ 
    $.post( "controllers/getChequePayByCode.php", { 'check_pay_code': code }, function( data ) { 
        if(data !== null){

            $(id).closest('tr').children('td').children('input[name="check_pay_id[]"]').val(data.check_pay_id);

            $('#check_pay_code').val(data.check_pay_code);  
            $('#check_pay_id').val(data.check_pay_id);
            $('#check_pay_date_write').val(data.check_pay_date_write);   
            $('#cheque_pay_supplier_id').val(data.supplier_id);
            $('#bank_account_id').val(data.bank_account_id); 
            $('#check_pay_date').val(data.check_pay_date);
            $('#check_pay_total').val(data.check_pay_total);
            $('#check_pay_remark').val(data.check_pay_remark);

            $('#cheque_pay_submit').html('Update Cheque');
            $('#cheque_pay_action').val('edit');
            $('#cheque_pay_delete').show();

            $('.select').selectpicker('refresh');
            $('#modalChequePay').modal('show');

        }else{ 
            $('#check_pay_id').val('0');
            $('#check_pay_code').val(code);  
            $('#check_pay_date_write').val($('#journal_cash_receipt_date').val());  
            $('#cheque_pay_supplier_id').val($('#supplier_id').val());
            $('#bank_account_id').val(); 
            $('#check_pay_date').val($('#journal_cash_receipt_date').val());
            $('#check_pay_total').val($('#finance_debit_total').val());
            $('#check_pay_remark').val($('#journal_cash_receipt_name').val());

            $('#check_pay_submit').html('Add Cheque');
            $('#cheque_pay_action').val('add');
            $('#cheque_pay_delete').hide();

            $('.select').selectpicker('refresh');
            $('#modalChequePay').modal('show');
        }
        
    });
}

function delete_check_pay(){
    var check_pay_id = document.getElementById("check_pay_id").value; 
    $.post( "controllers/deleteChequePay.php", 
        { 
            'check_pay_id':check_pay_id 
        }, 
        function( data ) {
            console.log(data);
            if(data == true){
                var journal_cheque_pay_id = $('#tb_journal').children('tbody').children('tr').children('td').children('input[name="journal_cheque_pay_id[]"]');   
                for(var i = 0; i < journal_cheque_pay_id.length ;i++){
                    if(journal_cheque_pay_id[i].value == check_pay_id){
                        $(journal_cheque_pay_id[i]).closest('tr').remove();
                    }
                }
                $(row_cheque_pay_update_id).closest('tr').remove();
                $('#modalChequePay').modal('hide');
            }else{
                alert("Can not delete check payment. Please contact administrator");
            }
            calculateChequePayAll()
        }
    );
}

function check_pay_post(){
    var check_pay_code = document.getElementById("check_pay_code").value;
        var check_pay_date_write = document.getElementById("check_pay_date_write").value;
        var check_pay_date = document.getElementById("check_pay_date").value;
        var bank_account_id = document.getElementById("bank_account_id").value;
        var supplier_id = document.getElementById("cheque_pay_supplier_id").value;
        var check_pay_remark = document.getElementById("check_pay_remark").value;
        var check_pay_total = document.getElementById("check_pay_total").value; 
        var cheque_pay_action = document.getElementById("cheque_pay_action").value; 
        var check_pay_id = document.getElementById("check_pay_id").value; 
        var lastupdate = '<?PHP echo $admin_id?>';
        var addby = '<?PHP echo $admin_id?>';

        check_pay_code = $.trim(check_pay_code);
        check_pay_date_write = $.trim(check_pay_date_write);
        check_pay_date = $.trim(check_pay_date);
        bank_account_id = $.trim(bank_account_id);
        supplier_id = $.trim(supplier_id);
        check_pay_remark = $.trim(check_pay_remark);
        check_pay_total = $.trim(check_pay_total);
        check_pay_id = $.trim(check_pay_id); 

        if(check_pay_code.length == 0){
            alert("Please input cheque pay code");
            document.getElementById("check_pay_code").focus();
            return false;
        }else if(bank_account_id.length == 0){
            alert("Please input bank account");
            document.getElementById("bank_account_id").focus();
            return false;
        }else{ 
            if(cheque_pay_action == 'edit'){
                $.post( "controllers/updateChequePay.php", 
                        { 
                            'check_pay_id':check_pay_id,
                            'check_pay_code': check_pay_code ,
                            'check_pay_date_write': check_pay_date_write ,
                            'check_pay_date': check_pay_date ,
                            'bank_account_id': bank_account_id ,
                            'supplier_id': supplier_id ,
                            'check_pay_remark': check_pay_remark ,
                            'check_pay_total': check_pay_total ,
                            'addby':addby
                        }, 
                    function( data ) {
                        if(data !== null){
                            set_cheque_pay_row(data); 
                            $('.select').selectpicker('refresh');
                            $('#modalChequePay').modal('hide');
                            
                        }else{
                            alert("Can not update check payment. Please contact administrator");
                        }
                    }
            );
        } else if (cheque_pay_action == 'add') {
            $.post( "controllers/insertChequePay.php", 
                    { 
                        'check_pay_id':check_pay_id,
                        'check_pay_code': check_pay_code ,
                        'check_pay_date_write': check_pay_date_write ,
                        'check_pay_date': check_pay_date ,
                        'bank_account_id': bank_account_id ,
                        'supplier_id': supplier_id ,
                        'check_pay_remark': check_pay_remark ,
                        'check_pay_total': check_pay_total ,
                        'addby':addby
                    }, 
                    function( data ) {
                        console.log(data);
                        if(data !== null){
                            set_cheque_pay_row(data);
                            $('.select').selectpicker('refresh');
                            $('#modalChequePay').modal('hide');
                            
                        }else{
                            alert("Can not add check payment. Please contact administrator");
                        }
                    }
            );
        }else{
            alert("System error. Please contact administrator");
        }

    }
}


function set_cheque_pay_row(data){
    var check_pay_total = parseFloat(data.check_pay_total);
    if(row_cheque_pay_add_id != null){
        
        $(row_cheque_pay_add_id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td>'+
                    '<input type="hidden" name="check_pay_id[]" value="'+data.check_pay_id+'" /> '+
                    '<span name="display_check_pay_code">'+data.check_pay_code+'</span>'+
                '</td> '+
                '<td >'+
                    '<span name="display_check_pay_date_write">'+data.check_pay_date_write+'</span>'+
                '</td> '+
                '<td >'+
                    '<span name="display_bank_name">'+data.bank_account_name+'</span>'+
                '</td>'+
                '<td align="right">'+
                    '<span name="display_check_pay_total">'+ check_pay_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'</span>'+
                '</td>'+
                '<td >'+
                    '<span name="display_check_pay_remark">'+data.check_pay_remark+'</span>'+
                '</td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="edit_cheque_pay_row(this,null);" style="color:orange;">'+
                    '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'+
                    '</a>'+
                    '<a href="javascript:;" onclick="delete_cheque_pay_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
            );

            if(row_journal_id == null ){ 
                var index = 0;
                if(isNaN($('#tb_journal').children('tbody').children('tr').length)){
                    index = 1;
                }else{
                    index = $('#tb_journal').children('tbody').children('tr').length + 1;
                }

                
                $('#tb_journal').children('tbody').append(
                    '<tr class="odd gradeX">'+
                        '<td>'+
                            '<input type="hidden" name="journal_cheque_id[]" value="0" />'+  
                            '<input type="hidden" name="journal_cheque_pay_id[]" value="'+data.check_pay_id+'" />'+  
                            '<input type="hidden" name="journal_invoice_customer_id[]" value="0" />'+  
                            '<input type="hidden" name="journal_invoice_supplier_id[]" value="0" />'+   
                            '<input type="hidden" name="journal_cash_receipt_list_id[]" value="0" />'+      
                            '<select class="form-control select" type="text" name="account_id[]" onchange="show_data(this);" data-live-search="true" disabled ></select>'+
                        '</td>'+
                        '<td><input type="text" class="form-control" name="journal_cash_receipt_list_name[]" value="' + data.check_pay_remark + '" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" onclick="edit_credit(this);" value="0" onchange="val_format(this);" name="journal_cash_receipt_list_debit[]"   readonly/></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" onclick="edit_credit(this);" value="'+ check_pay_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" onchange="val_format(this);" name="journal_cash_receipt_list_credit[]" readonly/></td>'+
                        '<td>'+
                            '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                '<i class="fa fa-times" aria-hidden="true"></i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );

                $('#tb_journal').children('tbody').children('tr:last').children('td').children('select').empty();
                var str = "<option value=''>Select account</option>";
                $.each(account_data, function (index, value) {
                    if(value['account_id'] != cheque_pay_account){
                        str += "<option value='" + value['account_id'] + "'>["+value['account_code']+"] " +  value['account_name_th'] + "</option>";
                    }else{
                        str += "<option value='" + value['account_id'] + "' SELECTED >["+value['account_code']+"] " +  value['account_name_th'] + "</option>";
                    }
                    
                });
                $('#tb_journal').children('tbody').children('tr:last').children('td').children('select').html(str);

                $('#tb_journal').children('tbody').children('tr:last').children('td').children('select').selectpicker();

            }else{
                $(row_journal_id).prop("disabled","true");
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_debit[]"]').prop("readonly","true");
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_credit[]"]').prop("readonly","true");
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]').val(data.check_pay_id);
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_name[]"]').val(data.check_pay_remark);
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_debit[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_credit[]"]').val(check_pay_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
                $('.select').selectpicker();
            }

            calculateAll();
            row_cheque_pay_add_id = null;
            row_journal_id = null;


    } else if (row_cheque_pay_update_id != null){
        $(row_cheque_pay_update_id).closest('tr').children('td').children('input[name="display_check_pay_id[]"]').val(data.check_pay_id);
        $(row_cheque_pay_update_id).closest('tr').children('td').children('span[name="display_check_pay_code"]').html(data.check_pay_code);
        $(row_cheque_pay_update_id).closest('tr').children('td').children('span[name="display_check_pay_date_write"]').html(data.check_pay_date_write); 
        $(row_cheque_pay_update_id).closest('tr').children('td').children('span[name="display_bank_name"]').html(data.bank_account_name); 
        $(row_cheque_pay_update_id).closest('tr').children('td').children('span[name="display_check_pay_total"]').html(check_pay_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")); 
        $(row_cheque_pay_update_id).closest('tr').children('td').children('span[name="display_check_pay_remark"]').html(data.check_pay_remark);  

        if(row_journal_id != null){
            $(row_journal_id).prop("disabled","true");
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_debit[]"]').prop("readonly","true");
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_credit[]"]').prop("readonly","true");
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]').val(data.check_pay_id);
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_name[]"]').val(data.check_pay_remark);
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_debit[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_credit[]"]').val(check_pay_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
            $('.select').selectpicker();
        }else{
            var journal_cheque_pay_id = $('#tb_journal').children('tbody').children('tr').children('td').children('input[name="journal_cheque_pay_id[]"]');   
            for(var i = 0; i < journal_cheque_pay_id.length ;i++){
                if(journal_cheque_pay_id[i].value == data.check_pay_id){
                    row_journal_id = $(journal_cheque_pay_id[i]).closest('tr').children('td').children('div').children('select[name="account_id[]"]');
                    $(row_journal_id).prop("disabled","true");
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_debit[]"]').prop("readonly","true");
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_credit[]"]').prop("readonly","true");
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]').val(data.check_pay_id);
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_name[]"]').val(data.check_pay_remark);
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_debit[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_receipt_list_credit[]"]').val(check_pay_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
                    $('.select').selectpicker();
                }
            }
        }

        row_journal_id = null;
        row_cheque_pay_update_id = null;
    }
    calculateChequePayAll()
}


function delete_cheque_pay_row(id){ 
    var check_pay_id =  $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="check_pay_id[]"]');
    var journal_cheque_pay_id = $('#tb_journal').children('tbody').children('tr').children('td').children('input[name="journal_cheque_pay_id[]"]');   
    for(var i = 0; i < journal_cheque_pay_id.length ;i++){
        if(journal_cheque_pay_id[i].value == check_pay_id[0].value){
            $(journal_cheque_pay_id[i]).closest('tr').remove();
        }
    }

    $(id).closest('tr').remove();
    calculateChequePayAll()

}


function calculateChequePayAll(){
    var display_check_pay_total = document.getElementsByName('display_check_pay_total'); 

    var total_1 = 0.0;  

    for(var i = 0 ; i < display_check_pay_total.length ; i++){
        
        total_1 += parseFloat(display_check_pay_total[i].innerText.replace(new RegExp(',', 'g'),''));
    } 
 
    $('#cheque_pay_sum').html((total_1).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
    calculateAll();
}
</script>