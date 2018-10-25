

<!-- ***************************************************** Invoice Payment ************************************************************* -->
<div id="modalInvoiceCustomer" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">ป้อนรายละเอียดรายการภาษีซื้อ</h4>
        </div>

        <div  class="modal-body" align="left">

            <div class="row"> 
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>เลขที่ใบกำกับภาษี <font color="#F00"><b>*</b></font> </label>
                        <input id="invoice_customer_code" name="invoice_customer_code" class="form-control" value="<?php echo $invoice_customer['invoice_customer_code']; ?>" onchange="get_invoice_customer_id(this)" >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>วันที่ใบกำกับภาษี <font color="#F00"><b>*</b></font> </label>
                        <input id="invoice_customer_date" name="invoice_customer_date" class="form-control calendar" value="<?php echo $invoice_customer['invoice_customer_date']; ?>" readonly />
                        <p class="help-block">Example : -.</p>
                    </div>
                </div> 
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label>รหัสผู้ซื้อ / Customer Code <font color="#F00"><b>*</b></font></label>
                        <input id="customer_code" name="customer_code" class="form-control" value="<? echo $customer['customer_code'];?>" readonly>
                        <p class="help-block">Example : A0001.</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <label>ผู้ซื้อ / Customer  <font color="#F00"><b>*</b></font> </label>
                        <select id="vat_customer_id" name="vat_customer_id" class="form-control select" onchange="get_customer_invoice()" data-live-search="true">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($customers) ; $i++){
                            ?>
                            <option <?php if($customers[$i]['customer_id'] == $invoice_customer['customer_id']){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> </option>
                            <?
                            }
                            ?>
                        </select>
                        <input id="invoice_customer_name" name="invoice_customer_name" class="form-control" value="<?php echo $invoice_customer['invoice_customer_name']; ?>"/>
                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>เลขประจำตัวผู้เสียภาษี / Tax ID. <font color="#F00"><b>*</b></font></label>
                        <input id="invoice_customer_tax" name="invoice_customer_tax" class="form-control" value="<?php echo $invoice_customer['invoice_customer_tax']; ?>" />
                        <p class="help-block">Example : Somchai Wongnai.</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font></label>
                        <textarea  id="invoice_customer_address" name="invoice_customer_address" class="form-control" rows="5" ><?php echo $invoice_customer['invoice_customer_address']; ?></textarea >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>ยื่นภาษีรวมในงวด <font color="#F00"><b>*</b></font> </label>
                        <input id="customer_vat_section" name="customer_vat_section" class="form-control" value="<?php echo $invoice_customer['vat_section']; ?>" >
                        <p class="help-block">Example : 08/61.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>ยื่นเพิ่มเติม <font color="#F00"><b>*</b></font> </label>
                        <input id="customer_vat_section_add" name="customer_vat_section_add" class="form-control" value="<?php echo $invoice_customer['vat_section_add']; ?>" >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
            </div>  
            <table width="100%" class="table table-striped table-bordered table-hover" >
                <thead>
                    <tr>
                        <th style="text-align:center;" colspan="2">ภาษีซื้อขอคืนได้</th>
                        <th style="text-align:center;" colspan="2">ภาษีซื้อขอคืนไม่ได้</th>
                        <th style="text-align:center;" rowspan="2">มูลค่าสินค้าหรือบริการอัตราศูนย์</th>  
                    </tr>
                    <tr>
                        <th style="text-align:center;" >มูลค่าสินค้า</th>
                        <th style="text-align:center;" >จำนวนภาษี</th>
                        <th style="text-align:center;" >มูลค่าสินค้า</th>
                        <th style="text-align:center;" >จำนวนภาษี</th>
                    </tr>
                </thead>
                <tbody> 
                    <tr class="odd gradeX">
                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="invoice_customer_total_price" name="invoice_customer_total_price" onchange="update_sale_vat()" value="<?php echo $invoice_customer['invoice_customer_total_price']; ?>" /></td>
                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="invoice_customer_vat_price" name="invoice_customer_vat_price"  value="<?php echo $invoice_customer['invoice_customer_vat_price']; ?>" /></td>
                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="invoice_customer_total_price_non" name="invoice_customer_total_price_non" onchange="update_sale_vat_non()" value="<?php echo $invoice_customer['invoice_customer_total_price_non']; ?>" /></td>
                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="invoice_customer_vat_price_non" name="invoice_customer_vat_price_non"  value="<?php echo $invoice_customer['invoice_customer_vat_price_non']; ?>" /></td>
                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="invoice_customer_total_non"  name="invoice_customer_total_non"  value="<?php echo $invoice_customer['invoice_customer_total_non']; ?>" /></td>
                    </tr> 
                </tbody> 
            </table> 
            <div class="row"> 
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>รายละเอียด <font color="#F00"><b>*</b></font> </label>
                        <input id="invoice_customer_description" name="invoice_customer_description" class="form-control" value="<?php echo $invoice_customer['invoice_customer_description']; ?>" >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>หมายเหตุ <font color="#F00"><b>*</b></font> </label>
                        <input id="invoice_customer_remark" name="invoice_customer_remark" class="form-control" value="<?php echo $invoice_customer['invoice_customer_remark']; ?>" >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
            </div>
        </div>

        

        <div class="modal-footer">
            <input type="hidden" id="invoice_customer_id" name="invoice_customer_id" value="" />
            <input type="hidden" id="invoice_customer_action" name="invoice_customer_action" value="" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="invoice_customer_delete" class="btn btn-danger" onclick="delete_invoice_customer();" >Delete Invoice</button>
            <button type="button" id="invoice_customer_submit" class="btn btn-primary" onclick="invoice_customer_post();" >Add Invoice</button>
        </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- ****************************************************************************************************************** -->


<script>
var row_invoice_customer_add_id;
var row_invoice_customer_update_id;

var invoice_customer_options = {
    url: function(keyword) {
        return "controllers/getInvoiceCustomerByJournalKeyword.php?type=2&keyword="+keyword;
    },

    getValue: function(element) {
        return element.invoice_customer_code ;
    },

    template: {
        type: "description",
        fields: {
            description: "invoice_customer_name"
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


function edit_invoice_customer_row(id,journal_id){
    row_journal_id = journal_id;
    row_invoice_customer_update_id = id;
    var invoice_customer_id = $(id).closest('tr').children('td').children('input[name="invoice_customer_id[]"]').val();
    console.log(invoice_customer_id);
    $.post( "controllers/getInvoiceCustomerByID.php", { 'invoice_customer_id': invoice_customer_id }, function( data ) { 
        console.log(data);
        if(data !== null){

            $(id).closest('tr').children('td').children('input[name="invoice_customer_id[]"]').val(data.invoice_customer_id);

            $('#invoice_customer_id').val(data.invoice_customer_id); 
            $('#invoice_customer_code').val(data.invoice_customer_code); 
            $('#invoice_customer_date').val(data.invoice_customer_date);  
            $('#customer_code').val(data.customer_code); 
            $('#vat_customer_id').val(data.customer_id); 
            $('#invoice_customer_name').val(data.invoice_customer_name); 
            $('#invoice_customer_tax').val(data.invoice_customer_tax); 
            $('#invoice_customer_address').val(data.invoice_customer_address); 
            $('#customer_vat_section').val(data.vat_section); 
            $('#customer_vat_section_add').val(data.vat_section_add);
            $('#invoice_customer_total_price').val(data.invoice_customer_total_price);
            $('#invoice_customer_vat_price').val(data.invoice_customer_vat_price);
            $('#invoice_customer_total_price_non').val(data.invoice_customer_total_price_non); 
            $('#invoice_customer_vat_price_non').val(data.invoice_customer_vat_price_non);
            $('#invoice_customer_total_non').val(data.invoice_customer_total_non);
            $('#invoice_customer_description').val(data.invoice_customer_description);
            $('#invoice_customer_remark').val(data.invoice_customer_remark);

            $('#invoice_customer_submit').html('Update Invoice');
            $('#invoice_customer_action').val('edit');
            $('#invoice_customer_delete').show();

            $('.select').selectpicker('refresh');
            $('#modalInvoiceCustomer').modal('show');

        }else{ 
            $('#invoice_customer_id').val('0'); 
            $('#invoice_customer_code').val(''); 
            $('#invoice_customer_date').val($('#journal_cash_payment_date').val());  
            $('#customer_code').val(''); 
            $('#vat_customer_id').val('0'); 
            $('#invoice_customer_name').val(''); 
            $('#invoice_customer_tax').val(''); 
            $('#invoice_customer_address').val(''); 
            $('#customer_vat_section').val(''); 
            $('#customer_vat_section_add').val('');
            $('#invoice_customer_total_price').val('0');
            $('#invoice_customer_vat_price').val('0');
            $('#invoice_customer_total_price_non').val('0'); 
            $('#invoice_customer_vat_price_non').val('0');
            $('#invoice_customer_total_non').val('0');
            $('#invoice_customer_description').val($('#journal_cash_payment_name').val());
            $('#invoice_customer_remark').val('');

            $('#invoice_customer_submit').html('Add Invoice');
            $('#invoice_customer_action').val('add');
            $('#invoice_customer_delete').hide();

            $('.select').selectpicker('refresh');
            $('#modalInvoiceCustomer').modal('show');
        }
        
    }); 
}

function add_invoice_customer_row(id,journal_id){
    
    $('#invoice_customer_id').val('0'); 
    $('#invoice_customer_code').val(''); 
    $('#invoice_customer_date').val($('#journal_cash_payment_date').val());  
    $('#customer_code').val(''); 
    $('#vat_customer_id').val('0'); 
    $('#invoice_customer_name').val(''); 
    $('#invoice_customer_tax').val(''); 
    $('#invoice_customer_address').val(''); 
    $('#customer_vat_section').val(''); 
    $('#customer_vat_section_add').val('');
    $('#invoice_customer_total_price').val('0');
    $('#invoice_customer_vat_price').val('0');
    $('#invoice_customer_total_price_non').val('0'); 
    $('#invoice_customer_vat_price_non').val('0');
    $('#invoice_customer_total_non').val('0');
    $('#invoice_customer_description').val($('#journal_cash_payment_name').val());
    $('#invoice_customer_remark').val('');

    $('#invoice_customer_submit').html('Add Invoice');
    $('#invoice_customer_action').val('add');
    $('#invoice_customer_delete').hide();

    $('.select').selectpicker('refresh');
    $('#modalInvoiceCustomer').modal('show');
    $('#invoice_customer_code').easyAutocomplete(invoice_customer_options);
    row_invoice_customer_add_id = id;
    row_journal_id = journal_id;
}

function get_invoice_customer_id(id){ 
    get_invoice_customer_data(id,$(id).val()); 
}


function get_invoice_customer_data(id,code){ 
    $.post( "controllers/getInvoiceCustomerByCode.php", { 'invoice_customer_code': code }, function( data ) { 
        if(data !== null){

            $(id).closest('tr').children('td').children('input[name="invoice_customer_id[]"]').val(data.invoice_customer_id);

            $('#invoice_customer_id').val(data.invoice_customer_id); 
            $('#invoice_customer_code').val(data.invoice_customer_code); 
            $('#invoice_customer_date').val(data.invoice_customer_date);  
            $('#customer_code').val(data.customer_code); 
            $('#vat_customer_id').val(data.customer_id); 
            $('#invoice_customer_name').val(data.invoice_customer_name); 
            $('#invoice_customer_tax').val(data.invoice_customer_tax); 
            $('#invoice_customer_address').val(data.invoice_customer_address); 
            $('#customer_vat_section').val(data.vat_section); 
            $('#customer_vat_section_add').val(data.vat_section_add);
            $('#invoice_customer_total_price').val(data.invoice_customer_total_price);
            $('#invoice_customer_vat_price').val(data.invoice_customer_vat_price);
            $('#invoice_customer_total_price_non').val(data.invoice_customer_total_price_non); 
            $('#invoice_customer_vat_price_non').val(data.invoice_customer_vat_price_non);
            $('#invoice_customer_total_non').val(data.invoice_customer_total_non);
            $('#invoice_customer_description').val(data.invoice_customer_description);
            $('#invoice_customer_remark').val(data.invoice_customer_remark);

            $('#invoice_customer_submit').html('Update Invoice');
            $('#invoice_customer_action').val('edit');
            $('#invoice_customer_delete').show();

            $('.select').selectpicker('refresh');
            $('#modalInvoiceCustomer').modal('show');

        }else{ 
            $('#invoice_customer_id').val('0'); 
            $('#invoice_customer_code').val(code); 
            $('#invoice_customer_date').val($('#journal_cash_payment_date').val());  
            $('#customer_code').val(''); 
            $('#vat_customer_id').val('0'); 
            $('#invoice_customer_name').val(''); 
            $('#invoice_customer_tax').val(''); 
            $('#invoice_customer_address').val(''); 
            $('#customer_vat_section').val(''); 
            $('#customer_vat_section_add').val('');
            $('#invoice_customer_total_price').val('0');
            $('#invoice_customer_vat_price').val('0');
            $('#invoice_customer_total_price_non').val('0'); 
            $('#invoice_customer_vat_price_non').val('0');
            $('#invoice_customer_total_non').val('0');
            $('#invoice_customer_description').val($('#journal_cash_payment_name').val());
            $('#invoice_customer_remark').val('');

            $('#invoice_customer_submit').html('Add Invoice');
            $('#invoice_customer_action').val('add');
            $('#invoice_customer_delete').hide();

            $('.select').selectpicker('refresh');
            $('#modalInvoiceCustomer').modal('show');
        }
        
    });
}

function delete_invoice_customer(){
    var invoice_customer_id = document.getElementById("invoice_customer_id").value; 
    $.post( "controllers/deleteInvoiceCustomer.php", 
        { 
            'invoice_customer_id':invoice_customer_id 
        }, 
        function( data ) {
            console.log(data);
            if(data == true){
                var journal_invoice_customer_id = $('#tb_journal').children('tbody').children('tr').children('td').children('input[name="journal_invoice_customer_id[]"]');   
                for(var i = 0; i < journal_invoice_customer_id.length ;i++){
                    if(journal_invoice_customer_id[i].value == invoice_customer_id){
                        $(journal_invoice_customer_id[i]).closest('tr').remove();
                    }
                }
                $(row_invoice_customer_update_id).closest('tr').remove();
                $('#modalInvoiceCustomer').modal('hide');
                calculateInvoiceCustomerAll();
            }else{
                alert("Can not delete invoice Customer. Please contact administrator");
            }
        }
    );
}


function invoice_customer_post(){
    var invoice_customer_id = document.getElementById("invoice_customer_id").value;
    var invoice_customer_code = document.getElementById("invoice_customer_code").value;
    var invoice_customer_date = document.getElementById("invoice_customer_date").value; 
    var customer_code = document.getElementById("customer_code").value;
    var customer_id = document.getElementById("vat_customer_id").value;
    var invoice_customer_name = document.getElementById("invoice_customer_name").value; 
    var invoice_customer_tax = document.getElementById("invoice_customer_tax").value; 
    var invoice_customer_address = document.getElementById("invoice_customer_address").value; 
    var vat_section = document.getElementById("customer_vat_section").value; 
    var vat_section_add = document.getElementById("customer_vat_section_add").value; 
    var invoice_customer_total_price = document.getElementById("invoice_customer_total_price").value; 
    var invoice_customer_vat_price = document.getElementById("invoice_customer_vat_price").value; 
    var invoice_customer_total_price_non = document.getElementById("invoice_customer_total_price_non").value; 
    var invoice_customer_vat_price_non = document.getElementById("invoice_customer_vat_price_non").value; 
    var invoice_customer_total_non = document.getElementById("invoice_customer_total_non").value; 
    var invoice_customer_description = document.getElementById("invoice_customer_description").value; 
    var invoice_customer_remark = document.getElementById("invoice_customer_remark").value; 
    var invoice_customer_action = document.getElementById("invoice_customer_action").value; 
    var lastupdate = '<?PHP echo $admin_id; ?>';
    var addby = '<?PHP echo $admin_id; ?>';

    invoice_customer_code = $.trim(invoice_customer_code);
    invoice_customer_date = $.trim(invoice_customer_date); 
    customer_id = $.trim(customer_id);
    invoice_customer_name = $.trim(invoice_customer_name);
    invoice_customer_tax = $.trim(invoice_customer_tax);
    invoice_customer_address = $.trim(invoice_customer_address);
    vat_section = $.trim(vat_section);
    vat_section_add = $.trim(vat_section_add);
    invoice_customer_total_price = $.trim(invoice_customer_total_price);
    invoice_customer_vat_price = $.trim(invoice_customer_vat_price);
    invoice_customer_total_price_non = $.trim(invoice_customer_total_price_non);
    invoice_customer_vat_price_non = $.trim(invoice_customer_vat_price_non);
    invoice_customer_total_non = $.trim(invoice_customer_total_non);
    invoice_customer_description = $.trim(invoice_customer_description);
    invoice_customer_remark = $.trim(invoice_customer_remark);
    invoice_customer_action = $.trim(invoice_customer_action);

    

    if(invoice_customer_code.length == 0){
        alert("Please input invoice Customer code.");
        document.getElementById("invoice_customer_code").focus();
        return false;
    }else if(invoice_customer_date.length == 0){
        alert("Please input invoice Customer date.");
        document.getElementById("invoice_customer_date").focus();
        return false;
    }else{ 
        if(invoice_customer_action == 'edit'){
            $.post( "controllers/updateInvoiceCustomer.php", 
                    { 
                        'invoice_customer_id':invoice_customer_id, 
                        'invoice_customer_code':invoice_customer_code, 
                        'invoice_customer_date':invoice_customer_date,  
                        'customer_id':customer_id, 
                        'invoice_customer_name':invoice_customer_name, 
                        'invoice_customer_tax':invoice_customer_tax, 
                        'invoice_customer_address':invoice_customer_address, 
                        'vat_section':vat_section, 
                        'vat_section_add':vat_section_add, 
                        'invoice_customer_total_price':invoice_customer_total_price, 
                        'invoice_customer_vat_price':invoice_customer_vat_price, 
                        'invoice_customer_total_price_non':invoice_customer_total_price_non, 
                        'invoice_customer_vat_price_non':invoice_customer_vat_price_non, 
                        'invoice_customer_total_non':invoice_customer_total_non, 
                        'invoice_customer_description':invoice_customer_description, 
                        'invoice_customer_remark':invoice_customer_remark, 
                        'type':2,
                        'lastupdate':lastupdate
                    }, 
                    function( data ) {
                        if(data !== null){
                            set_invoice_customer_row(data); 
                            $('.select').selectpicker('refresh');
                            $('#modalInvoiceCustomer').modal('hide');
                            
                        }else{
                            alert("Can not update invoice Customer. Please contact administrator");
                        }
                    }
            );
        } else if (invoice_customer_action == 'add') {
            
            $.post( "controllers/insertInvoiceCustomer.php", 
                    {  
                        'invoice_customer_code':invoice_customer_code, 
                        'invoice_customer_date':invoice_customer_date,  
                        'customer_id':customer_id, 
                        'invoice_customer_name':invoice_customer_name, 
                        'invoice_customer_tax':invoice_customer_tax, 
                        'invoice_customer_address':invoice_customer_address, 
                        'vat_section':vat_section, 
                        'vat_section_add':vat_section_add, 
                        'invoice_customer_total_price':invoice_customer_total_price, 
                        'invoice_customer_vat_price':invoice_customer_vat_price, 
                        'invoice_customer_total_price_non':invoice_customer_total_price_non, 
                        'invoice_customer_vat_price_non':invoice_customer_vat_price_non, 
                        'invoice_customer_total_non':invoice_customer_total_non, 
                        'invoice_customer_description':invoice_customer_description, 
                        'invoice_customer_remark':invoice_customer_remark, 
                        'type':2,
                        'addby':addby
                    }, 
                    function( data ) { 
                        console.log(data);
                        if(data !== null){
                            set_invoice_customer_row(data);
                            $('.select').selectpicker('refresh');
                            $('#modalInvoiceCustomer').modal('hide');
                            
                        }else{
                            alert("Can not add invoice Customer. Please contact administrator");
                        }
                    }
            );
        }else{
            alert("System error. Please contact administrator");
        }

    }
}


function set_invoice_customer_row(data){

    var invoice_customer_total_price = parseFloat(data.invoice_customer_total_price);
    var invoice_customer_vat_price = parseFloat(data.invoice_customer_vat_price);
    var invoice_customer_total_price_non = parseFloat(data.invoice_customer_total_price_non);
    var invoice_customer_vat_price_non = parseFloat(data.invoice_customer_vat_price_non);

    var invoice_customer_vat_price_credit = 0 ;
    var invoice_customer_vat_price_debit = 0 ;

    if(invoice_customer_vat_price >= 0){
        invoice_customer_vat_price_debit = invoice_customer_vat_price
    }else{
        invoice_customer_vat_price_credit = Math.abs(invoice_customer_vat_price);
    }

    if(row_invoice_customer_add_id != null){
        

        $(row_invoice_customer_add_id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td>'+
                    '<input type="hidden" name="invoice_customer_id[]" value="'+data.invoice_customer_id+'" /> '+
                    '<span name="display_customer_vat_section" >'+data.vat_section+'</span>'+
                '</td> '+
                '<td >'+
                    '<span name="display_invoice_customer_date" >'+data.invoice_customer_date+'</span>'+
                '</td> '+
                '<td >'+
                    '<span name="display_invoice_customer_code" >'+data.invoice_customer_code+'</span>'+
                '</td>'+
                '<td>'+
                    '<span name="display_invoice_customer_description" >'+data.invoice_customer_description+'</span>'+
                '</td>'+
                '<td align="right">'+
                    '<span name="display_invoice_customer_total_price" >'+invoice_customer_total_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'</span>'+
                '</td>'+
                '<td align="right">'+
                    '<span name="display_invoice_customer_vat_price" >'+invoice_customer_vat_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'</span>'+
                '</td>'+
                '<td align="right">'+
                    '<span name="display_invoice_customer_total_price_non" >'+invoice_customer_total_price_non.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'</span>'+
                '</td>'+
                '<td align="right">'+
                    '<span name="display_invoice_customer_vat_price_non" >'+invoice_customer_vat_price_non.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'</span>'+
                '</td>'+
                '<td >'+
                    '<span name="display_invoice_customer_remark" >'+data.invoice_customer_remark+'</span>'+
                '</td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="edit_invoice_customer_row(this,null);" style="color:orange;">'+
                    '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'+
                    '</a>'+
                    '<a href="javascript:;" onclick="delete_invoice_customer_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
            );

            if(row_journal_id == null){
                
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
                            '<input type="hidden" name="journal_cheque_pay_id[]" value="0" />'+  
                            '<input type="hidden" name="journal_invoice_supplier_id[]" value="0" />'+  
                            '<input type="hidden" name="journal_invoice_customer_id[]" value="'+data.invoice_customer_id+'" />'+   
                            '<input type="hidden" name="journal_cash_payment_list_id[]" value="0" />'+      
                            '<select class="form-control select" type="text" name="account_id[]" onchange="show_data(this);" data-live-search="true" disabled ></select>'+
                        '</td>'+
                        '<td><input type="text" class="form-control" name="journal_cash_payment_list_name[]" value="' + data.invoice_customer_description + '" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" onclick="edit_credit(this);"  onchange="val_format(this);" name="journal_cash_payment_list_debit[]" value="'+ invoice_customer_vat_price_debit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'"  readonly/></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" onclick="edit_credit(this);"  onchange="val_format(this);" name="journal_cash_payment_list_credit[]" value="'+ invoice_customer_vat_price_credit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" readonly/></td>'+
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
                    if(value['account_id'] != vat_sale_account){
                        str += "<option value='" + value['account_id'] + "'>["+value['account_code']+"] " +  value['account_name_th'] + "</option>";
                    }else{
                        str += "<option value='" + value['account_id'] + "' SELECTED >["+value['account_code']+"] " +  value['account_name_th'] + "</option>";
                    }
                    
                });
                $('#tb_journal').children('tbody').children('tr:last').children('td').children('select').html(str);

                $('#tb_journal').children('tbody').children('tr:last').children('td').children('select').selectpicker();

            }else{
                $(row_journal_id).prop("disabled","true");
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_debit[]"]').prop("readonly","true");
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_credit[]"]').prop("readonly","true");
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]').val(data.invoice_customer_id);
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_name[]"]').val(data.invoice_customer_description);
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_debit[]"]').val(invoice_customer_vat_price_debit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_credit[]"]').val(invoice_customer_vat_price_credit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
                $('.select').selectpicker();
            }

            calculateAll();
            row_invoice_customer_add_id = null;
            row_journal_id = null;



    } else if (row_invoice_customer_update_id != null){
        console.log("Update Invoice ",data);
        $(row_invoice_customer_update_id).closest('tr').children('td').children('input[name="invoice_customer_id[]"]').val(data.invoice_customer_id); 
        $(row_invoice_customer_update_id).closest('tr').children('td').children('span[name="display_invoice_customer_date"]').html(data.invoice_customer_date); 
        $(row_invoice_customer_update_id).closest('tr').children('td').children('span[name="display_invoice_customer_code"]').html(data.invoice_customer_code); 
        $(row_invoice_customer_update_id).closest('tr').children('td').children('span[name="display_invoice_customer_description"]').html(data.invoice_customer_description); 
        $(row_invoice_customer_update_id).closest('tr').children('td').children('span[name="display_invoice_customer_total_price"]').html(invoice_customer_total_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")); 
        $(row_invoice_customer_update_id).closest('tr').children('td').children('span[name="display_invoice_customer_vat_price"]').html(invoice_customer_vat_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")); 
        $(row_invoice_customer_update_id).closest('tr').children('td').children('span[name="display_invoice_customer_total_price_non"]').html(invoice_customer_total_price_non.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")); 
        $(row_invoice_customer_update_id).closest('tr').children('td').children('span[name="display_invoice_customer_vat_price_non"]').html(invoice_customer_vat_price_non.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")); 
        $(row_invoice_customer_update_id).closest('tr').children('td').children('span[name="display_invoice_customer_remark"]').html(data.invoice_customer_remark); 
        console.log("Update",row_journal_id);
        if(row_journal_id != null){
            
            $(row_journal_id).prop("disabled","true");
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_debit[]"]').prop("readonly","true");
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_credit[]"]').prop("readonly","true");
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]').val(data.invoice_customer_id);
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_name[]"]').val(data.invoice_customer_description);
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_debit[]"]').val(invoice_customer_vat_price_debit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_credit[]"]').val(invoice_customer_vat_price_credit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
            $('.select').selectpicker();
        }else{
            var journal_invoice_customer_id = $('#tb_journal').children('tbody').children('tr').children('td').children('input[name="journal_invoice_customer_id[]"]');   
            for(var i = 0; i < journal_invoice_customer_id.length ;i++){
                if(journal_invoice_customer_id[i].value == data.invoice_customer_id){
                    row_journal_id = $(journal_invoice_customer_id[i]).closest('tr').children('td').children('div').children('select[name="account_id[]"]');
                    $(row_journal_id).prop("disabled","true");
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_debit[]"]').prop("readonly","true");
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_credit[]"]').prop("readonly","true");
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]').val(data.invoice_customer_id);
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_name[]"]').val(data.invoice_customer_description);
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_debit[]"]').val(invoice_customer_vat_price_debit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cash_payment_list_credit[]"]').val(invoice_customer_vat_price_credit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
                    $('.select').selectpicker();
                }
            }
            
        }

        row_journal_id = null;

        row_invoice_customer_update_id = null;
    }

    calculateInvoiceCustomerAll();
}

function delete_invoice_customer_row(id){ 
    var invoice_customer_id =  $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="invoice_customer_id[]"]');
    var journal_invoice_customer_id = $('#tb_journal').children('tbody').children('tr').children('td').children('input[name="journal_invoice_customer_id[]"]');   
    for(var i = 0; i < journal_invoice_customer_id.length ;i++){
        if(journal_invoice_customer_id[i].value == invoice_customer_id[0].value){
            $(journal_invoice_customer_id[i]).closest('tr').remove();
        }
    }

    $(id).closest('tr').remove();
    calculateInvoiceCustomerAll();

}


function update_sale_vat(){
    var invoice_customer_total_price =  parseFloat($("#invoice_customer_total_price").val().replace(',',''));
    var invoice_customer_vat_price =  parseFloat($("#invoice_customer_vat_price").val().replace(',',''));

    if(isNaN(invoice_customer_vat_price)){
        invoice_customer_vat_price = 0.0;
    }
    
    if(isNaN(invoice_customer_total_price)){
        invoice_customer_total_price = 0.0;
        $("#invoice_customer_vat_price").val(0.00);
    } else{
        $("#invoice_customer_vat_price").val( (invoice_customer_total_price * (7/100.0) ).toFixed(2) );
    }

}

function update_sale_vat_non(){
    var invoice_customer_total_price_non =  parseFloat($("#invoice_customer_total_price_non").val().replace(',',''));
    var invoice_customer_vat_price_non =  parseFloat($("#invoice_customer_vat_price_non").val().replace(',',''));

    if(isNaN(invoice_customer_vat_price_non)){
        invoice_customer_vat_price_non = 0.0;
    }
    
    if(isNaN(invoice_customer_total_price_non)){
        invoice_customer_total_price_non = 0.0;
        $("#invoice_customer_vat_price_non").val(0.00);
    } else{
        $("#invoice_customer_vat_price_non").val( (invoice_customer_total_price_non * (7/100.0) ).toFixed(2) );
    }
}


function get_customer_invoice(){
    var customer_id = document.getElementById('vat_customer_id').value;
    $.post( "controllers/getCustomerByID.php", { 'customer_id': customer_id }, function( data ) {
        document.getElementById('customer_code').value = data.customer_code;
        document.getElementById('invoice_customer_name').value = data.customer_name_en;
        document.getElementById('invoice_customer_tax').value = data.customer_tax;
        document.getElementById('invoice_customer_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
    });
}

function calculateInvoiceCustomerAll(){
        var display_invoice_customer_total_price = document.getElementsByName('display_invoice_customer_total_price');
        var display_invoice_customer_vat_price = document.getElementsByName('display_invoice_customer_vat_price');
        var display_invoice_customer_total_price_non = document.getElementsByName('display_invoice_customer_total_price_non');
        var display_invoice_customer_vat_price_non = document.getElementsByName('display_invoice_customer_vat_price_non');

        var total_1 = 0.0;
        var total_2 = 0.0;
        var total_3 = 0.0;
        var total_4 = 0.0;
        
        console.log(display_invoice_customer_total_price);
        for(var i = 0 ; i < display_invoice_customer_total_price.length ; i++){
            
            total_1 += parseFloat(display_invoice_customer_total_price[i].innerText.replace(new RegExp(',', 'g'),''));
        }

        for(var i = 0 ; i < display_invoice_customer_vat_price.length ; i++){
            
            total_2 += parseFloat(display_invoice_customer_vat_price[i].innerText.replace(new RegExp(',', 'g'),''));
        } 

        for(var i = 0 ; i < display_invoice_customer_total_price_non.length ; i++){
            
            total_3 += parseFloat(display_invoice_customer_total_price_non[i].innerText.replace(new RegExp(',', 'g'),''));
        }

        for(var i = 0 ; i < display_invoice_customer_vat_price_non.length ; i++){
            
            total_4 += parseFloat(display_invoice_customer_vat_price_non[i].innerText.replace(new RegExp(',', 'g'),''));
        } 

        $('#invoice_customer_sum').html((total_1).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_customer_sum_vat').html((total_2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_customer_sum_non').html((total_3).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_customer_sum_vat_non').html((total_4).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();

    }
</script>

