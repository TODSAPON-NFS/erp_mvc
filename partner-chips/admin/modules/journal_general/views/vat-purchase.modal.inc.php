

<!-- ***************************************************** Invoice Payment ************************************************************* -->
<div id="modalInvoiceSupplier" class="modal fade" tabindex="-1" role="dialog">
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
                        <input id="invoice_supplier_code" name="invoice_supplier_code" class="form-control" value="<?php echo $invoice_supplier['invoice_supplier_code']; ?>" onchange="get_invoice_supplier_id(this)" >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>วันที่ใบกำกับภาษี <font color="#F00"><b>*</b></font> </label>
                        <input id="invoice_supplier_date" name="invoice_supplier_date" class="form-control calendar" value="<?php echo $invoice_supplier['invoice_supplier_date']; ?>" readonly />
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>เลขที่ออกใหม่ <font color="#F00"><b>*</b></font> </label>
                        <input id="invoice_supplier_code_gen" name="invoice_supplier_code_gen" class="form-control" value="<?php echo $invoice_supplier['invoice_supplier_code_gen']; ?>" >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>วันที่รับเอกสาร <font color="#F00"><b>*</b></font> </label>
                        <input id="invoice_supplier_date_recieve" name="invoice_supplier_date_recieve" class="form-control calendar" value="<?php echo $invoice_supplier['invoice_supplier_date_recieve']; ?>" readonly />
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                        <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
                        <p class="help-block">Example : A0001.</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                        <select id="vat_supplier_id" name="vat_supplier_id" class="form-control select" onchange="get_supplier_invoice()" data-live-search="true">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($suppliers) ; $i++){
                            ?>
                            <option <?php if($suppliers[$i]['supplier_id'] == $invoice_supplier['supplier_id']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
                            <?
                            }
                            ?>
                        </select>
                        <input id="invoice_supplier_name" name="invoice_supplier_name" class="form-control" value="<?php echo $invoice_supplier['invoice_supplier_name']; ?>"/>
                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>เลขประจำตัวผู้เสียภาษี / Tax ID. <font color="#F00"><b>*</b></font></label>
                        <input id="invoice_supplier_tax" name="invoice_supplier_tax" class="form-control" value="<?php echo $invoice_supplier['invoice_supplier_tax']; ?>" />
                        <p class="help-block">Example : Somchai Wongnai.</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font></label>
                        <textarea  id="invoice_supplier_address" name="invoice_supplier_address" class="form-control" rows="5" ><?php echo $invoice_supplier['invoice_supplier_address']; ?></textarea >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>ยื่นภาษีรวมในงวด <font color="#F00"><b>*</b></font> </label>
                        <input id="vat_section" name="vat_section" class="form-control" value="<?php echo $invoice_supplier['vat_section']; ?>" >
                        <p class="help-block">Example : 08/61.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>ยื่นเพิ่มเติม <font color="#F00"><b>*</b></font> </label>
                        <input id="vat_section_add" name="vat_section_add" class="form-control" value="<?php echo $invoice_supplier['vat_section_add']; ?>" >
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
                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_total_price" name="invoice_supplier_total_price" onchange="update_vat()" value="<?php echo $invoice_supplier['invoice_supplier_total_price']; ?>" /></td>
                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_vat_price" name="invoice_supplier_vat_price"  value="<?php echo $invoice_supplier['invoice_supplier_vat_price']; ?>" /></td>
                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_total_price_non" name="invoice_supplier_total_price_non" onchange="update_vat_non()" value="<?php echo $invoice_supplier['invoice_supplier_total_price_non']; ?>" /></td>
                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_vat_price_non" name="invoice_supplier_vat_price_non"  value="<?php echo $invoice_supplier['invoice_supplier_vat_price_non']; ?>" /></td>
                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_total_non"  name="invoice_supplier_total_non"  value="<?php echo $invoice_supplier['invoice_supplier_total_non']; ?>" /></td>
                    </tr> 
                </tbody> 
            </table> 
            <div class="row"> 
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>รายละเอียด <font color="#F00"><b>*</b></font> </label>
                        <input id="invoice_supplier_description" name="invoice_supplier_description" class="form-control" value="<?php echo $invoice_supplier['invoice_supplier_description']; ?>" >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>หมายเหตุ <font color="#F00"><b>*</b></font> </label>
                        <input id="invoice_supplier_remark" name="invoice_supplier_remark" class="form-control" value="<?php echo $invoice_supplier['invoice_supplier_remark']; ?>" >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
            </div>
        </div>

        

        <div class="modal-footer">
            <input type="hidden" id="invoice_supplier_id" name="invoice_supplier_id" value="" />
            <input type="hidden" id="invoice_supplier_action" name="invoice_supplier_action" value="" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="invoice_supplier_delete" class="btn btn-danger" onclick="delete_invoice_supplier();" >Delete Invoice</button>
            <button type="button" id="invoice_supplier_submit" class="btn btn-primary" onclick="invoice_supplier_post();" >Add Invoice</button>
        </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- ****************************************************************************************************************** -->


<script>
var row_invoice_supplier_add_id;
var row_invoice_supplier_update_id;

var invoice_supplier_options = {
    url: function(keyword) {
        return "controllers/getInvoiceSupplierByJournalKeyword.php?type=2&keyword="+keyword;
    },

    getValue: function(element) {
        return element.invoice_supplier_code ;
    },

    template: {
        type: "description",
        fields: {
            description: "invoice_supplier_name"
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


function edit_invoice_supplier_row(id,journal_id){
    row_journal_id = journal_id;
    row_invoice_supplier_update_id = id;
    var invoice_supplier_id = $(id).closest('tr').children('td').children('input[name="invoice_supplier_id[]"]').val();
    console.log(invoice_supplier_id);
    $.post( "controllers/getInvoiceSupplierByID.php", { 'invoice_supplier_id': invoice_supplier_id }, function( data ) { 
        console.log(data);
        if(data !== null){

            $(id).closest('tr').children('td').children('input[name="invoice_supplier_id[]"]').val(data.invoice_supplier_id);

            $('#invoice_supplier_id').val(data.invoice_supplier_id); 
            $('#invoice_supplier_code').val(data.invoice_supplier_code); 
            $('#invoice_supplier_date').val(data.invoice_supplier_date); 
            $('#invoice_supplier_code_gen').val(data.invoice_supplier_code_gen); 
            $('#invoice_supplier_date_recieve').val(data.invoice_supplier_date_recieve); 
            $('#supplier_code').val(data.supplier_code); 
            $('#vat_supplier_id').val(data.supplier_id); 
            $('#invoice_supplier_name').val(data.invoice_supplier_name); 
            $('#invoice_supplier_tax').val(data.invoice_supplier_tax); 
            $('#invoice_supplier_address').val(data.invoice_supplier_address); 
            $('#vat_section').val(data.vat_section); 
            $('#vat_section_add').val(data.vat_section_add);
            $('#invoice_supplier_total_price').val(data.invoice_supplier_total_price);
            $('#invoice_supplier_vat_price').val(data.invoice_supplier_vat_price);
            $('#invoice_supplier_total_price_non').val(data.invoice_supplier_total_price_non); 
            $('#invoice_supplier_vat_price_non').val(data.invoice_supplier_vat_price_non);
            $('#invoice_supplier_total_non').val(data.invoice_supplier_total_non);
            $('#invoice_supplier_description').val(data.invoice_supplier_description);
            $('#invoice_supplier_remark').val(data.invoice_supplier_remark);

            $('#invoice_supplier_submit').html('Update Invoice');
            $('#invoice_supplier_action').val('edit');
            $('#invoice_supplier_delete').show();

            $('.select').selectpicker('refresh');
            $('#modalInvoiceSupplier').modal('show');

        }else{ 
            $('#invoice_supplier_id').val('0'); 
            $('#invoice_supplier_code').val(''); 
            $('#invoice_supplier_date').val($('#journal_general_date').val()); 
            $('#invoice_supplier_code_gen').val($('#journal_general_code').val()); 
            $('#invoice_supplier_date_recieve').val($('#journal_general_date').val()); 
            $('#supplier_code').val(''); 
            $('#vat_supplier_id').val('0'); 
            $('#invoice_supplier_name').val(''); 
            $('#invoice_supplier_tax').val(''); 
            $('#invoice_supplier_address').val(''); 
            $('#vat_section').val(''); 
            $('#vat_section_add').val('');
            $('#invoice_supplier_total_price').val('0');
            $('#invoice_supplier_vat_price').val('0');
            $('#invoice_supplier_total_price_non').val('0'); 
            $('#invoice_supplier_vat_price_non').val('0');
            $('#invoice_supplier_total_non').val('0');
            $('#invoice_supplier_description').val($('#journal_general_name').val());
            $('#invoice_supplier_remark').val('');

            $('#invoice_supplier_submit').html('Add Invoice');
            $('#invoice_supplier_action').val('add');
            $('#invoice_supplier_delete').hide();

            $('.select').selectpicker('refresh');
            $('#modalInvoiceSupplier').modal('show');
        }
        
    }); 
}

function add_invoice_supplier_row(id,journal_id){
    
    $('#invoice_supplier_id').val('0'); 
    $('#invoice_supplier_code').val(''); 
    $('#invoice_supplier_date').val($('#journal_general_date').val()); 
    $('#invoice_supplier_code_gen').val($('#journal_general_code').val()); 
    $('#invoice_supplier_date_recieve').val($('#journal_general_date').val()); 
    $('#supplier_code').val(''); 
    $('#vat_supplier_id').val('0'); 
    $('#invoice_supplier_name').val(''); 
    $('#invoice_supplier_tax').val(''); 
    $('#invoice_supplier_address').val(''); 
    $('#vat_section').val(''); 
    $('#vat_section_add').val('');
    $('#invoice_supplier_total_price').val('0');
    $('#invoice_supplier_vat_price').val('0');
    $('#invoice_supplier_total_price_non').val('0'); 
    $('#invoice_supplier_vat_price_non').val('0');
    $('#invoice_supplier_total_non').val('0');
    $('#invoice_supplier_description').val($('#journal_general_name').val());
    $('#invoice_supplier_remark').val('');

    $('#invoice_supplier_submit').html('Add Invoice');
    $('#invoice_supplier_action').val('add');
    $('#invoice_supplier_delete').hide();

    $('.select').selectpicker('refresh');
    $('#modalInvoiceSupplier').modal('show');
    $('#invoice_supplier_code').easyAutocomplete(invoice_supplier_options);
    row_invoice_supplier_add_id = id;
    row_journal_id = journal_id;
}

function get_invoice_supplier_id(id){ 
    get_invoice_supplier_data(id,$(id).val()); 
}


function get_invoice_supplier_data(id,code){ 
    $.post( "controllers/getInvoiceSupplierByCode.php", { 'invoice_supplier_code': code }, function( data ) { 
        if(data !== null){

            $(id).closest('tr').children('td').children('input[name="invoice_supplier_id[]"]').val(data.invoice_supplier_id);

            $('#invoice_supplier_id').val(data.invoice_supplier_id); 
            $('#invoice_supplier_code').val(data.invoice_supplier_code); 
            $('#invoice_supplier_date').val(data.invoice_supplier_date); 
            $('#invoice_supplier_code_gen').val(data.invoice_supplier_code_gen); 
            $('#invoice_supplier_date_recieve').val(data.invoice_supplier_date_recieve); 
            $('#supplier_code').val(data.supplier_code); 
            $('#vat_supplier_id').val(data.supplier_id); 
            $('#invoice_supplier_name').val(data.invoice_supplier_name); 
            $('#invoice_supplier_tax').val(data.invoice_supplier_tax); 
            $('#invoice_supplier_address').val(data.invoice_supplier_address); 
            $('#vat_section').val(data.vat_section); 
            $('#vat_section_add').val(data.vat_section_add);
            $('#invoice_supplier_total_price').val(data.invoice_supplier_total_price);
            $('#invoice_supplier_vat_price').val(data.invoice_supplier_vat_price);
            $('#invoice_supplier_total_price_non').val(data.invoice_supplier_total_price_non); 
            $('#invoice_supplier_vat_price_non').val(data.invoice_supplier_vat_price_non);
            $('#invoice_supplier_total_non').val(data.invoice_supplier_total_non);
            $('#invoice_supplier_description').val(data.invoice_supplier_description);
            $('#invoice_supplier_remark').val(data.invoice_supplier_remark);

            $('#invoice_supplier_submit').html('Update Invoice');
            $('#invoice_supplier_action').val('edit');
            $('#invoice_supplier_delete').show();

            $('.select').selectpicker('refresh');
            $('#modalInvoiceSupplier').modal('show');

        }else{ 
            $('#invoice_supplier_id').val('0'); 
            $('#invoice_supplier_code').val(code); 
            $('#invoice_supplier_date').val($('#journal_general_date').val()); 
            $('#invoice_supplier_code_gen').val($('#journal_general_code').val()); 
            $('#invoice_supplier_date_recieve').val($('#journal_general_date').val()); 
            $('#supplier_code').val(''); 
            $('#vat_supplier_id').val('0'); 
            $('#invoice_supplier_name').val(''); 
            $('#invoice_supplier_tax').val(''); 
            $('#invoice_supplier_address').val(''); 
            $('#vat_section').val(''); 
            $('#vat_section_add').val('');
            $('#invoice_supplier_total_price').val('0');
            $('#invoice_supplier_vat_price').val('0');
            $('#invoice_supplier_total_price_non').val('0'); 
            $('#invoice_supplier_vat_price_non').val('0');
            $('#invoice_supplier_total_non').val('0');
            $('#invoice_supplier_description').val($('#journal_general_name').val());
            $('#invoice_supplier_remark').val('');

            $('#invoice_supplier_submit').html('Add Invoice');
            $('#invoice_supplier_action').val('add');
            $('#invoice_supplier_delete').hide();

            $('.select').selectpicker('refresh');
            $('#modalInvoiceSupplier').modal('show');
        }
        
    });
}

function delete_invoice_supplier(){
    var invoice_supplier_id = document.getElementById("invoice_supplier_id").value; 
    $.post( "controllers/deleteInvoiceSupplier.php", 
        { 
            'invoice_supplier_id':invoice_supplier_id 
        }, 
        function( data ) {
            console.log(data);
            if(data == true){
                var journal_invoice_supplier_id = $('#tb_journal').children('tbody').children('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]');   
                for(var i = 0; i < journal_invoice_supplier_id.length ;i++){
                    if(journal_invoice_supplier_id[i].value == invoice_supplier_id){
                        $(journal_invoice_supplier_id[i]).closest('tr').remove();
                    }
                }
                $(row_invoice_supplier_update_id).closest('tr').remove();
                $('#modalInvoiceSupplier').modal('hide');
                calculateInvoiceSupplierAll();
            }else{
                alert("Can not delete invoice supplier. Please contact administrator");
            }
        }
    );
}


function invoice_supplier_post(){
    var invoice_supplier_id = document.getElementById("invoice_supplier_id").value;
    var invoice_supplier_code = document.getElementById("invoice_supplier_code").value;
    var invoice_supplier_date = document.getElementById("invoice_supplier_date").value;
    var invoice_supplier_code_gen = document.getElementById("invoice_supplier_code_gen").value;
    var invoice_supplier_date_recieve = document.getElementById("invoice_supplier_date_recieve").value;
    var supplier_code = document.getElementById("supplier_code").value;
    var supplier_id = document.getElementById("vat_supplier_id").value;
    var invoice_supplier_name = document.getElementById("invoice_supplier_name").value; 
    var invoice_supplier_tax = document.getElementById("invoice_supplier_tax").value; 
    var invoice_supplier_address = document.getElementById("invoice_supplier_address").value; 
    var vat_section = document.getElementById("vat_section").value; 
    var vat_section_add = document.getElementById("vat_section_add").value; 
    var invoice_supplier_total_price = document.getElementById("invoice_supplier_total_price").value; 
    var invoice_supplier_vat_price = document.getElementById("invoice_supplier_vat_price").value; 
    var invoice_supplier_total_price_non = document.getElementById("invoice_supplier_total_price_non").value; 
    var invoice_supplier_vat_price_non = document.getElementById("invoice_supplier_vat_price_non").value; 
    var invoice_supplier_total_non = document.getElementById("invoice_supplier_total_non").value; 
    var invoice_supplier_description = document.getElementById("invoice_supplier_description").value; 
    var invoice_supplier_remark = document.getElementById("invoice_supplier_remark").value; 
    var invoice_supplier_action = document.getElementById("invoice_supplier_action").value; 
    var lastupdate = '<?PHP echo $admin_id; ?>';
    var addby = '<?PHP echo $admin_id; ?>';

    invoice_supplier_code = $.trim(invoice_supplier_code);
    invoice_supplier_date = $.trim(invoice_supplier_date);
    invoice_supplier_code_gen = $.trim(invoice_supplier_code_gen);
    invoice_supplier_date_recieve = $.trim(invoice_supplier_date_recieve);
    supplier_id = $.trim(supplier_id);
    invoice_supplier_name = $.trim(invoice_supplier_name);
    invoice_supplier_tax = $.trim(invoice_supplier_tax);
    invoice_supplier_address = $.trim(invoice_supplier_address);
    vat_section = $.trim(vat_section);
    vat_section_add = $.trim(vat_section_add);
    invoice_supplier_total_price = $.trim(invoice_supplier_total_price);
    invoice_supplier_vat_price = $.trim(invoice_supplier_vat_price);
    invoice_supplier_total_price_non = $.trim(invoice_supplier_total_price_non);
    invoice_supplier_vat_price_non = $.trim(invoice_supplier_vat_price_non);
    invoice_supplier_total_non = $.trim(invoice_supplier_total_non);
    invoice_supplier_description = $.trim(invoice_supplier_description);
    invoice_supplier_remark = $.trim(invoice_supplier_remark);
    invoice_supplier_action = $.trim(invoice_supplier_action);

    

    if(invoice_supplier_code.length == 0){
        alert("Please input invoice supplier code.");
        document.getElementById("invoice_supplier_code").focus();
        return false;
    }else if(invoice_supplier_date.length == 0){
        alert("Please input invoice supplier date.");
        document.getElementById("invoice_supplier_date").focus();
        return false;
    }else{ 
        if(invoice_supplier_action == 'edit'){
            $.post( "controllers/updateInvoiceSupplier.php", 
                    { 
                        'invoice_supplier_id':invoice_supplier_id, 
                        'invoice_supplier_code':invoice_supplier_code, 
                        'invoice_supplier_date':invoice_supplier_date, 
                        'invoice_supplier_code_gen':invoice_supplier_code_gen, 
                        'invoice_supplier_date_recieve':invoice_supplier_date_recieve, 
                        'supplier_id':supplier_id, 
                        'invoice_supplier_name':invoice_supplier_name, 
                        'invoice_supplier_tax':invoice_supplier_tax, 
                        'invoice_supplier_address':invoice_supplier_address, 
                        'vat_section':vat_section, 
                        'vat_section_add':vat_section_add, 
                        'invoice_supplier_total_price':invoice_supplier_total_price, 
                        'invoice_supplier_vat_price':invoice_supplier_vat_price, 
                        'invoice_supplier_total_price_non':invoice_supplier_total_price_non, 
                        'invoice_supplier_vat_price_non':invoice_supplier_vat_price_non, 
                        'invoice_supplier_total_non':invoice_supplier_total_non, 
                        'invoice_supplier_description':invoice_supplier_description, 
                        'invoice_supplier_remark':invoice_supplier_remark, 
                        'type':2,
                        'lastupdate':lastupdate
                    }, 
                    function( data ) {
                        if(data !== null){
                            set_invoice_supplier_row(data); 
                            $('.select').selectpicker('refresh');
                            $('#modalInvoiceSupplier').modal('hide');
                            
                        }else{
                            alert("Can not update invoice supplier. Please contact administrator");
                        }
                    }
            );
        } else if (invoice_supplier_action == 'add') {
            
            $.post( "controllers/insertInvoiceSupplier.php", 
                    {  
                        'invoice_supplier_code':invoice_supplier_code, 
                        'invoice_supplier_date':invoice_supplier_date, 
                        'invoice_supplier_code_gen':invoice_supplier_code_gen, 
                        'invoice_supplier_date_recieve':invoice_supplier_date_recieve, 
                        'supplier_id':supplier_id, 
                        'invoice_supplier_name':invoice_supplier_name, 
                        'invoice_supplier_tax':invoice_supplier_tax, 
                        'invoice_supplier_address':invoice_supplier_address, 
                        'vat_section':vat_section, 
                        'vat_section_add':vat_section_add, 
                        'invoice_supplier_total_price':invoice_supplier_total_price, 
                        'invoice_supplier_vat_price':invoice_supplier_vat_price, 
                        'invoice_supplier_total_price_non':invoice_supplier_total_price_non, 
                        'invoice_supplier_vat_price_non':invoice_supplier_vat_price_non, 
                        'invoice_supplier_total_non':invoice_supplier_total_non, 
                        'invoice_supplier_description':invoice_supplier_description, 
                        'invoice_supplier_remark':invoice_supplier_remark, 
                        'type':2,
                        'addby':addby
                    }, 
                    function( data ) { 
                        console.log(data);
                        if(data !== null){
                            set_invoice_supplier_row(data);
                            $('.select').selectpicker('refresh');
                            $('#modalInvoiceSupplier').modal('hide');
                            
                        }else{
                            alert("Can not add invoice supplier. Please contact administrator");
                        }
                    }
            );
        }else{
            alert("System error. Please contact administrator");
        }

    }
}


function set_invoice_supplier_row(data){

    var invoice_supplier_total_price = parseFloat(data.invoice_supplier_total_price);
    var invoice_supplier_vat_price = parseFloat(data.invoice_supplier_vat_price);
    var invoice_supplier_total_price_non = parseFloat(data.invoice_supplier_total_price_non);
    var invoice_supplier_vat_price_non = parseFloat(data.invoice_supplier_vat_price_non);

    var invoice_supplier_vat_price_credit = 0 ;
    var invoice_supplier_vat_price_debit = 0 ;

    if(invoice_supplier_vat_price >= 0){
        invoice_supplier_vat_price_debit = invoice_supplier_vat_price
    }else{
        invoice_supplier_vat_price_credit = Math.abs(invoice_supplier_vat_price);
    }

    if(row_invoice_supplier_add_id != null){
        

        $(row_invoice_supplier_add_id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td>'+
                    '<input type="hidden" name="invoice_supplier_id[]" value="'+data.invoice_supplier_id+'" /> '+
                    '<span name="display_vat_section" >'+data.vat_section+'</span>'+
                '</td> '+
                '<td >'+
                    '<span name="display_invoice_supplier_date" >'+data.invoice_supplier_date+'</span>'+
                '</td> '+
                '<td >'+
                    '<span name="display_invoice_supplier_code" >'+data.invoice_supplier_code+'</span>'+
                '</td>'+
                '<td>'+
                    '<span name="display_invoice_supplier_description" >'+data.invoice_supplier_description+'</span>'+
                '</td>'+
                '<td align="right">'+
                    '<span name="display_invoice_supplier_total_price" >'+invoice_supplier_total_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'</span>'+
                '</td>'+
                '<td align="right">'+
                    '<span name="display_invoice_supplier_vat_price" >'+invoice_supplier_vat_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'</span>'+
                '</td>'+
                '<td align="right">'+
                    '<span name="display_invoice_supplier_total_price_non" >'+invoice_supplier_total_price_non.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'</span>'+
                '</td>'+
                '<td align="right">'+
                    '<span name="display_invoice_supplier_vat_price_non" >'+invoice_supplier_vat_price_non.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'</span>'+
                '</td>'+
                '<td >'+
                    '<span name="display_invoice_supplier_remark" >'+data.invoice_supplier_remark+'</span>'+
                '</td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="edit_invoice_supplier_row(this,null);" style="color:orange;">'+
                    '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'+
                    '</a>'+
                    '<a href="javascript:;" onclick="delete_invoice_supplier_row(this);" style="color:red;">'+
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
                            '<input type="hidden" name="journal_invoice_customer_id[]" value="0" />'+  
                            '<input type="hidden" name="journal_invoice_supplier_id[]" value="'+data.invoice_supplier_id+'" />'+   
                            '<input type="hidden" name="journal_general_list_id[]" value="0" />'+      
                            '<select class="form-control select" type="text" name="account_id[]" onchange="show_data(this);" data-live-search="true" disabled ></select>'+
                        '</td>'+
                        '<td><input type="text" class="form-control" name="journal_general_list_name[]" value="' + data.invoice_supplier_description + '" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" onclick="edit_credit(this);"  onchange="val_format(this);" name="journal_general_list_debit[]" value="'+ invoice_supplier_vat_price_debit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'"  readonly/></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" onclick="edit_credit(this);"  onchange="val_format(this);" name="journal_general_list_credit[]" value="'+ invoice_supplier_vat_price_credit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" readonly/></td>'+
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
                    if(value['account_id'] != vat_purchase_account){
                        str += "<option value='" + value['account_id'] + "'>["+value['account_code']+"] " +  value['account_name_th'] + "</option>";
                    }else{
                        str += "<option value='" + value['account_id'] + "' SELECTED >["+value['account_code']+"] " +  value['account_name_th'] + "</option>";
                    }
                    
                });
                $('#tb_journal').children('tbody').children('tr:last').children('td').children('select').html(str);

                $('#tb_journal').children('tbody').children('tr:last').children('td').children('select').selectpicker();

            }else{
                $(row_journal_id).prop("disabled","true");
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_debit[]"]').prop("readonly","true");
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_credit[]"]').prop("readonly","true");
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]').val('0');
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]').val(data.invoice_supplier_id);
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_name[]"]').val(data.invoice_supplier_description);
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_debit[]"]').val(invoice_supplier_vat_price_debit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
                $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_credit[]"]').val(invoice_supplier_vat_price_credit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
                $('.select').selectpicker();
            }

            calculateAll();
            row_invoice_supplier_add_id = null;
            row_journal_id = null;



    } else if (row_invoice_supplier_update_id != null){
        console.log("Update Invoice ",data);
        $(row_invoice_supplier_update_id).closest('tr').children('td').children('input[name="invoice_supplier_id[]"]').val(data.invoice_supplier_id); 
        $(row_invoice_supplier_update_id).closest('tr').children('td').children('span[name="display_invoice_supplier_date"]').html(data.invoice_supplier_date); 
        $(row_invoice_supplier_update_id).closest('tr').children('td').children('span[name="display_invoice_supplier_code"]').html(data.invoice_supplier_code); 
        $(row_invoice_supplier_update_id).closest('tr').children('td').children('span[name="display_invoice_supplier_description"]').html(data.invoice_supplier_description); 
        $(row_invoice_supplier_update_id).closest('tr').children('td').children('span[name="display_invoice_supplier_total_price"]').html(invoice_supplier_total_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")); 
        $(row_invoice_supplier_update_id).closest('tr').children('td').children('span[name="display_invoice_supplier_vat_price"]').html(invoice_supplier_vat_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")); 
        $(row_invoice_supplier_update_id).closest('tr').children('td').children('span[name="display_invoice_supplier_total_price_non"]').html(invoice_supplier_total_price_non.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")); 
        $(row_invoice_supplier_update_id).closest('tr').children('td').children('span[name="display_invoice_supplier_vat_price_non"]').html(invoice_supplier_vat_price_non.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")); 
        $(row_invoice_supplier_update_id).closest('tr').children('td').children('span[name="display_invoice_supplier_remark"]').html(data.invoice_supplier_remark); 
        console.log("Update",row_journal_id);
        if(row_journal_id != null){
            
            $(row_journal_id).prop("disabled","true");
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_debit[]"]').prop("readonly","true");
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_credit[]"]').prop("readonly","true");
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]').val('0');
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]').val(data.invoice_supplier_id);
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_name[]"]').val(data.invoice_supplier_description);
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_debit[]"]').val(invoice_supplier_vat_price_debit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
            $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_credit[]"]').val(invoice_supplier_vat_price_credit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
            $('.select').selectpicker();
        }else{
            var journal_invoice_supplier_id = $('#tb_journal').children('tbody').children('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]');   
            for(var i = 0; i < journal_invoice_supplier_id.length ;i++){
                if(journal_invoice_supplier_id[i].value == data.invoice_supplier_id){
                    row_journal_id = $(journal_invoice_supplier_id[i]).closest('tr').children('td').children('div').children('select[name="account_id[]"]');
                    $(row_journal_id).prop("disabled","true");
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_debit[]"]').prop("readonly","true");
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_credit[]"]').prop("readonly","true");
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]').val('0');
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]').val(data.invoice_supplier_id);
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_name[]"]').val(data.invoice_supplier_description);
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_debit[]"]').val(invoice_supplier_vat_price_debit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
                    $(row_journal_id).closest('tr').children('td').children('input[name="journal_general_list_credit[]"]').val(invoice_supplier_vat_price_credit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
                    $('.select').selectpicker();
                }
            }
            
        }

        row_journal_id = null;

        row_invoice_supplier_update_id = null;
    }

    calculateInvoiceSupplierAll();
}

function delete_invoice_supplier_row(id){ 
    var invoice_supplier_id =  $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="invoice_supplier_id[]"]');
    var journal_invoice_supplier_id = $('#tb_journal').children('tbody').children('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]');   
    for(var i = 0; i < journal_invoice_supplier_id.length ;i++){
        if(journal_invoice_supplier_id[i].value == invoice_supplier_id[0].value){
            $(journal_invoice_supplier_id[i]).closest('tr').remove();
        }
    }

    $(id).closest('tr').remove();
    calculateInvoiceSupplierAll();

}


function update_vat(){
    var invoice_supplier_total_price =  parseFloat($("#invoice_supplier_total_price").val().replace(',',''));
    var invoice_supplier_vat_price =  parseFloat($("#invoice_supplier_vat_price").val().replace(',',''));

    if(isNaN(invoice_supplier_vat_price)){
        invoice_supplier_vat_price = 0.0;
    }
    
    if(isNaN(invoice_supplier_total_price)){
        invoice_supplier_total_price = 0.0;
        $("#invoice_supplier_vat_price").val(0.00);
    } else{
        $("#invoice_supplier_vat_price").val( (invoice_supplier_total_price * (7/100.0) ).toFixed(2) );
    }

}

function update_vat_non(){
    var invoice_supplier_total_price_non =  parseFloat($("#invoice_supplier_total_price_non").val().replace(',',''));
    var invoice_supplier_vat_price_non =  parseFloat($("#invoice_supplier_vat_price_non").val().replace(',',''));

    if(isNaN(invoice_supplier_vat_price_non)){
        invoice_supplier_vat_price_non = 0.0;
    }
    
    if(isNaN(invoice_supplier_total_price_non)){
        invoice_supplier_total_price_non = 0.0;
        $("#invoice_supplier_vat_price_non").val(0.00);
    } else{
        $("#invoice_supplier_vat_price_non").val( (invoice_supplier_total_price_non * (7/100.0) ).toFixed(2) );
    }
}


function get_supplier_invoice(){
    var supplier_id = document.getElementById('vat_supplier_id').value;
    $.post( "controllers/getSupplierByID.php", { 'supplier_id': supplier_id }, function( data ) {
        document.getElementById('supplier_code').value = data.supplier_code;
        document.getElementById('invoice_supplier_name').value = data.supplier_name_en;
        document.getElementById('invoice_supplier_tax').value = data.supplier_tax;
        document.getElementById('invoice_supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
    });
}

function calculateInvoiceSupplierAll(){
        var display_invoice_supplier_total_price = document.getElementsByName('display_invoice_supplier_total_price');
        var display_invoice_supplier_vat_price = document.getElementsByName('display_invoice_supplier_vat_price');
        var display_invoice_supplier_total_price_non = document.getElementsByName('display_invoice_supplier_total_price_non');
        var display_invoice_supplier_vat_price_non = document.getElementsByName('display_invoice_supplier_vat_price_non');

        var total_1 = 0.0;
        var total_2 = 0.0;
        var total_3 = 0.0;
        var total_4 = 0.0;
        
        console.log(display_invoice_supplier_total_price);
        for(var i = 0 ; i < display_invoice_supplier_total_price.length ; i++){
            
            total_1 += parseFloat(display_invoice_supplier_total_price[i].innerText.replace(new RegExp(',', 'g'),''));
        }

        for(var i = 0 ; i < display_invoice_supplier_vat_price.length ; i++){
            
            total_2 += parseFloat(display_invoice_supplier_vat_price[i].innerText.replace(new RegExp(',', 'g'),''));
        } 

        for(var i = 0 ; i < display_invoice_supplier_total_price_non.length ; i++){
            
            total_3 += parseFloat(display_invoice_supplier_total_price_non[i].innerText.replace(new RegExp(',', 'g'),''));
        }

        for(var i = 0 ; i < display_invoice_supplier_vat_price_non.length ; i++){
            
            total_4 += parseFloat(display_invoice_supplier_vat_price_non[i].innerText.replace(new RegExp(',', 'g'),''));
        } 

        $('#invoice_supplier_sum').html((total_1).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_supplier_sum_vat').html((total_2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_supplier_sum_non').html((total_3).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_supplier_sum_vat_non').html((total_4).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();

    }
</script>

