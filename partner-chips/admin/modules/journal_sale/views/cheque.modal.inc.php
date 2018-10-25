<div id="modalCheque" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">สร้างเช็คจ่าย / Add Cheque Payment</h4>
        </div>

        <div  class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>วันที่เช็ค</label>
                                <input id="check_date_write" name="check_date_write" class="form-control calendar" type="text" value="" readonly />
                                <p class="help-block">01-06-2018 </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>เลขที่เช็ค <font color="#F00"><b>*</b></font></label>
                                <input id="check_code" name="check_code" class="form-control" type="text" value="QR" onchange="get_cheque_id(this)" >
                                <p class="help-block">Example : QR4411555.</p>
                            </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ผู้สั่งจ่าย <font color="#F00"><b>*</b></font> </label>
                                <select id="cheque_customer_id" name="cheque_customer_id" class="form-control select"  data-live-search="true">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($customers) ; $i++){
                                    ?>
                                    <option  value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ธนาคาร <font color="#F00"><b>*</b></font> </label>
                                <select id="bank_id" name="bank_id" class="form-control select" data-live-search="true">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($banks) ; $i++){
                                    ?>
                                    <option value="<?php echo $banks[$i]['bank_id'] ?>"><?php echo $banks[$i]['bank_name'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : BKK.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>สาขา</label>
                                <input type="text" id="bank_branch" name="bank_branch"  class="form-control" />
                                <p class="help-block">- </p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>วันรับที่เช็ค</label>
                                <input id="check_date_recieve" name="check_date_recieve" class="form-control calendar" value="" readonly>
                                <p class="help-block">01-06-2018 </p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>จำนวนเงิน</label>
                                <input id="check_total" name="check_total" class="form-control " value="" >
                                <p class="help-block">80000 </p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>หมายเหตุุ</label>
                                <input id="check_remark" name="check_remark" class="form-control" type="text" value="" />
                                <p class="help-block">- </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>

        <div class="modal-footer">
            <input type="hidden" id="check_id" name="check_id" value="" />
            <input type="hidden" id="cheque_action" name="cheque_action" value="" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="cheque_delete" class="btn btn-danger" onclick="delete_check();" >Delete Cheque</button>
            <button type="button" id="cheque_submit" class="btn btn-primary" onclick="check_post();" >Add Cheque</button>
        </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
var row_cheque_add_id;
var row_cheque_update_id;

var cheque_options = {
    url: function(keyword) {
        return "controllers/getChequeByKeyword.php?keyword="+keyword;
    },

    getValue: function(element) {
        return element.check_code ;
    },

    template: {
        type: "description",
        fields: {
            description: "bank_account_name"
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


function edit_cheque_row(id,journal_id){
    row_cheque_update_id = id;
    row_journal_id = journal_id;

    var check_id = $(id).closest('tr').children('td').children('input[name="check_id[]"]').val(); 
    $.post( "controllers/getChequeByChequeID.php", { 'check_id': check_id }, function( data ) { 
        if(data !== null){

            $(id).closest('tr').children('td').children('input[name="check_id[]"]').val(data.check_id);

            $('#check_code').val(data.check_code);  
            $('#check_id').val(data.check_id);
            $('#check_date_write').val(data.check_date_write); 
            $('#check_date_recieve').val(data.check_date_recieve); 
            
            $('#cheque_customer_id').val(data.customer_id);
            $('#bank_id').val(data.bank_id);
            $('#bank_branch').val(data.bank_branch);
            $('#check_date').val(data.check_date);
            $('#check_total').val(data.check_total);
            $('#check_remark').val(data.check_remark);

            $('#cheque_submit').html('Update Cheque');
            $('#cheque_action').val('edit');
            $('#cheque_delete').show();

            $('.select').selectpicker('refresh');
            $('#modalCheque').modal('show');

        }else{ 
            $('#check_id').val('0');
            $('#check_code').val('QR');  
            $('#check_date_write').val($('#journal_sale_date').val()); 
            $('#check_date_recieve').val($('#journal_sale_date').val());
            $('#cheque_customer_id').val($('#customer_id').val());
            $('#bank_id').val();
            $('#bank_branch').val();
            $('#check_date').val($('#journal_sale_date').val());
            $('#check_total').val(0);
            $('#check_remark').val($('#journal_sale_name').val());

            $('#check_submit').html('Add Cheque');
            $('#cheque_action').val('add');
            $('#cheque_delete').hide();

            $('.select').selectpicker('refresh');
            $('#modalCheque').modal('show');
        }
        
    }); 
}


function add_cheque_row(id,journal_id){
    
    $('#check_id').val('0');
    $('#check_code').val('QR');  
    $('#check_date_write').val($('#journal_sale_date').val()); 
    $('#check_date_recieve').val($('#journal_sale_date').val());
    $('#cheque_customer_id').val($('#customer_id').val());
    $('#bank_id').val();
    $('#bank_branch').val();
    $('#check_date').val($('#journal_sale_date').val());
    $('#check_total').val(0);
    $('#check_remark').val($('#journal_sale_name').val());

    $('#check_submit').html('Add Cheque');
    $('#cheque_action').val('add');
    $('#cheque_delete').hide();

    $('.select').selectpicker('refresh');
    $('#modalCheque').modal('show');
    $('#check_code').easyAutocomplete(cheque_options);
    row_cheque_add_id = id;

    row_journal_id = journal_id;

}

function get_cheque_id(id){ 
    get_cheque_data(id,$(id).val()); 
}



function get_cheque_data(id,code){ 
    $.post( "controllers/getChequeByCode.php", { 'check_code': code }, function( data ) { 
        if(data !== null){

            $(id).closest('tr').children('td').children('input[name="check_id[]"]').val(data.check_id);

            $('#check_code').val(data.check_code);  
            $('#check_id').val(data.check_id);
            $('#check_date_write').val(data.check_date_write); 
            $('#check_date_recieve').val(data.check_date_recieve); 
            
            $('#cheque_customer_id').val(data.customer_id);
            $('#bank_id').val(data.bank_id);
            $('#bank_branch').val(data.bank_branch);
            $('#check_date').val(data.check_date);
            $('#check_total').val(data.check_total);
            $('#check_remark').val(data.check_remark);

            $('#cheque_submit').html('Update Cheque');
            $('#cheque_action').val('edit');
            $('#cheque_delete').show();

            $('.select').selectpicker('refresh');
            $('#modalCheque').modal('show');

        }else{ 
            $('#check_id').val('0');
            $('#check_code').val(code);  
            $('#check_date_write').val($('#journal_sale_date').val()); 
            $('#check_date_recieve').val($('#check_date_recieve').val());
            $('#cheque_customer_id').val($('#customer_id').val());
            $('#bank_id').val();
            $('#bank_branch').val();
            $('#check_date').val($('#journal_sale_date').val());
            $('#check_total').val($('#finance_debit_total').val());
            $('#check_remark').val($('#journal_sale_name').val());

            $('#check_submit').html('Add Cheque');
            $('#cheque_action').val('add');
            $('#cheque_delete').hide();

            $('.select').selectpicker('refresh');
            $('#modalCheque').modal('show');
        }
        
    });
}

function delete_check(){
    var check_id = document.getElementById("check_id").value; 
    $.post( "controllers/deleteCheque.php", 
        { 
            'check_id':check_id 
        }, 
        function( data ) {
            console.log(data);
            if(data == true){
                var journal_cheque_id = $('#tb_journal').children('tbody').children('tr').children('td').children('input[name="journal_cheque_id[]"]');   
                for(var i = 0; i < journal_cheque_id.length ;i++){
                    if(journal_cheque_id[i].value == check_id){ 
                        $(journal_cheque_id[i]).closest('tr').remove();
                    }
                } 
                $(row_cheque_update_id).closest('tr').remove();
                $('#modalCheque').modal('hide');
            }else{
                alert("Can not delete check payment. Please contact administrator");
            }
            calculateChequeAll()
        }
    );
}

function check_post(){
    var check_code = document.getElementById("check_code").value;
    var check_date_write = document.getElementById("check_date_write").value;
    var check_date_recieve = document.getElementById("check_date_recieve").value;
    var bank_id = document.getElementById("bank_id").value;
    var bank_branch = document.getElementById("bank_branch").value;
    var customer_id = document.getElementById("cheque_customer_id").value;
    var check_remark = document.getElementById("check_remark").value;
    var check_total = document.getElementById("check_total").value; 
    var cheque_action = document.getElementById("cheque_action").value; 
    var check_id = document.getElementById("check_id").value; 
    var lastupdate = '<?PHP echo $admin_id; ?>';
    var addby = '<?PHP echo $admin_id; ?>';

    check_code = $.trim(check_code);
    check_date_write = $.trim(check_date_write);
    check_date_recieve = $.trim(check_date_recieve);
    bank_id = $.trim(bank_id);
    bank_branch = $.trim(bank_branch);
    customer_id = $.trim(customer_id);
    check_remark = $.trim(check_remark);
    check_total = $.trim(check_total);
    check_id = $.trim(check_id); 

    if(check_code.length == 0){
        alert("Please input cheque pay code");
        document.getElementById("check_code").focus();
        return false;
    }else if(bank_id.length == 0){
        alert("Please input bank");
        document.getElementById("bank_id").focus();
        return false;
    }else{ 
        if(cheque_action == 'edit'){
            $.post( "controllers/updateCheque.php", 
                    { 
                        'check_id':check_id,
                        'check_code': check_code ,
                        'check_date_write': check_date_write ,
                        'check_date_recieve': check_date_recieve ,
                        'bank_id': bank_id ,
                        'bank_branch': bank_branch ,
                        'customer_id': customer_id ,
                        'check_remark': check_remark ,
                        'check_total': check_total ,
                        'addby':addby
                    }, 
                    function( data ) {
                        if(data !== null){
                            set_cheque_row(data); 
                            $('.select').selectpicker('refresh');
                            $('#modalCheque').modal('hide');
                            
                        }else{
                            alert("Can not update check payment. Please contact administrator");
                        }
                    }
            );
        } else if (cheque_action == 'add') {
            $.post( "controllers/insertCheque.php", 
                    { 
                        'check_code': check_code ,
                        'check_date_write': check_date_write ,
                        'check_date_recieve': check_date_recieve ,
                        'bank_id': bank_id ,
                        'bank_branch': bank_branch ,
                        'customer_id': customer_id ,
                        'check_remark': check_remark ,
                        'check_total': check_total ,
                        'addby':addby
                    }, 
                    function( data ) {
                        console.log(data);
                        if(data !== null){
                            set_cheque_row(data);
                            $('.select').selectpicker('refresh');
                            $('#modalCheque').modal('hide');
                            
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


function set_cheque_row(data){
    var check_total = parseFloat(data.check_total);
    if(row_cheque_add_id != null){
        
        $(row_cheque_add_id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td>'+
                    '<input type="hidden" name="check_id[]" value="'+data.check_id+'" /> '+
                    '<span name="display_check_code">'+data.check_code+'</span>'+
                '</td> '+
                '<td >'+
                    '<span name="display_check_date_write">'+data.check_date_write+'</span>'+
                '</td> '+
                '<td >'+
                    '<span name="display_bank_name">'+data.bank_name+'</span>'+
                '</td>'+
                '<td align="right">'+
                    '<span name="display_check_total">'+ check_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'</span>'+
                '</td>'+
                '<td >'+
                    '<span name="display_check_remark">'+data.check_remark+'</span>'+
                '</td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="edit_cheque_row(this,null);" style="color:orange;">'+
                    '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'+
                    '</a>'+
                    '<a href="javascript:;" onclick="delete_cheque_row(this);" style="color:red;">'+
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
                            '<input type="hidden" name="journal_cheque_id[]" value="'+data.check_id+'" />'+  
                            '<input type="hidden" name="journal_cheque_pay_id[]" value="0" />'+  
                            '<input type="hidden" name="journal_invoice_customer_id[]" value="0" />'+  
                            '<input type="hidden" name="journal_invoice_supplier_id[]" value="0" />'+   
                            '<input type="hidden" name="journal_sale_list_id[]" value="0" />'+      
                            '<select class="form-control select" type="text" name="account_id[]" onchange="show_data(this);" data-live-search="true" disabled ></select>'+
                        '</td>'+
                        '<td><input type="text" class="form-control" name="journal_sale_list_name[]" value="' + data.check_remark + '" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" onclick="edit_credit(this);" onchange="val_format(this);" name="journal_sale_list_debit[]" value="'+ check_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'"  readonly/></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" onclick="edit_credit(this);" value="0" onchange="val_format(this);" name="journal_sale_list_credit[]" readonly/></td>'+
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
                    if(value['account_id'] != cheque_account){
                        str += "<option value='" + value['account_id'] + "'>["+value['account_code']+"] " +  value['account_name_th'] + "</option>";
                    }else{
                        str += "<option value='" + value['account_id'] + "' SELECTED >["+value['account_code']+"] " +  value['account_name_th'] + "</option>";
                    }
                    
                });
                $('#tb_journal').children('tbody').children('tr:last').children('td').children('select').html(str);

                $('#tb_journal').children('tbody').children('tr:last').children('td').children('select').selectpicker();
            }else{
                $(row_journal_id).prop("disabled","true");
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_debit[]"]').prop("readonly","true");
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_credit[]"]').prop("readonly","true");
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]').val(data.check_id);
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_name[]"]').val(data.check_remark);
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_debit[]"]').val(check_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_credit[]"]').val('0');
                $('.select').selectpicker();
            }
            
            

            calculateAll();
            row_cheque_add_id = null;
            row_journal_id = null;


    } else if (row_cheque_update_id != null){
        $(row_cheque_update_id).closest('tr').children('td').children('input[name="check_id[]"]').val(data.check_id);
        $(row_cheque_update_id).closest('tr').children('td').children('span[name="display_check_code"]').html(data.check_code);
        $(row_cheque_update_id).closest('tr').children('td').children('span[name="display_check_date_write"]').html(data.check_date_write); 
        $(row_cheque_update_id).closest('tr').children('td').children('span[name="display_bank_name"]').html(data.bank_name); 
        $(row_cheque_update_id).closest('tr').children('td').children('span[name="display_check_total"]').html(check_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")); 
        $(row_cheque_update_id).closest('tr').children('td').children('span[name="display_check_remark"]').html(data.check_remark);  

        if(row_journal_id != null){
            $(row_journal_id).prop("disabled","true");
            $(row_journal_id).prop("disabled","true");
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_debit[]"]').prop("readonly","true");
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_credit[]"]').prop("readonly","true");
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]').val(data.check_id);
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_name[]"]').val(data.check_remark);
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_debit[]"]').val(check_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_credit[]"]').val('0');
            $('.select').selectpicker();
        }else{
            var journal_cheque_id = $('#tb_journal').children('tbody').children('tr').children('td').children('input[name="journal_cheque_id[]"]');   
            for(var i = 0; i < journal_cheque_id.length ;i++){
                if(journal_cheque_id[i].value == data.check_id){ 
                    row_journal_id = $(journal_cheque_id[i]).closest('tr').children('td').children('div').children('select[name="account_id[]"]');
                    $(row_journal_id).prop("disabled","true");
                    $(row_journal_id).prop("disabled","true");
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_debit[]"]').prop("readonly","true");
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_credit[]"]').prop("readonly","true");
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]').val(data.check_id);
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_name[]"]').val(data.check_remark);
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_debit[]"]').val(check_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_sale_list_credit[]"]').val('0');
                    $('.select').selectpicker();
                }
            }

        }

        row_journal_id = null;

        row_cheque_update_id = null;
    }
    calculateChequeAll();
}

function delete_cheque_row(id){ 
    var check_id =  $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="check_id[]"]');
    var journal_cheque_id = $('#tb_journal').children('tbody').children('tr').children('td').children('input[name="journal_cheque_id[]"]');   
    for(var i = 0; i < journal_cheque_id.length ;i++){
        if(journal_cheque_id[i].value == check_id[0].value){ 
            $(journal_cheque_id[i]).closest('tr').remove();
        }
    }

    $(id).closest('tr').remove();
    calculateChequeAll();
}

function calculateChequeAll(){
    var display_check_total = document.getElementsByName('display_check_total'); 

    var total_1 = 0.0;  

    for(var i = 0 ; i < display_check_total.length ; i++){
        
        total_1 += parseFloat(display_check_total[i].innerText.replace(new RegExp(',', 'g'),''));
    } 
 
    $('#cheque_sum').html((total_1).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
    calculateAll();
}
</script>