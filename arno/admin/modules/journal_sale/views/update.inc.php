<script>
    var row_journal_id = null;
    var cheque_account = '<?PHP echo $account_setting['cheque_account']['account_id']; ?>';
    var cheque_pay_account = '<?PHP echo $account_setting['cheque_pay_account']['account_id']; ?>';
    var vat_purchase_account = '<?PHP echo $account_setting['vat_purchase_account']['account_id']; ?>';
    var vat_sale_account = '<?PHP echo $account_setting['vat_sale_account']['account_id']; ?>';

    var options = {
        url: function(keyword) {
            return "controllers/getJournalSaleByKeyword.php?keyword="+keyword;
        },

        list: {
            maxNumberOfElements: 10,
            match: {
                enabled: true
            }
        },

        getValue: function(element) {
            return element.journal_sale_code ;
        },

        template: {
            type: "description",
            fields: {
                description: "journal_sale_name"
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

    var vat_id  ;

    function check(){

        var journal_sale_code = document.getElementById("journal_sale_code").value;
        var journal_sale_date = document.getElementById("journal_sale_date").value;
        var journal_sale_name = document.getElementById("journal_sale_name").value;
        
        var debit_total = parseFloat($('#journal_sale_list_debit').val( ).toString().replace(new RegExp(',', 'g'),''));
        var credit_total = parseFloat($('#journal_sale_list_credit').val( ).toString().replace(new RegExp(',', 'g'),''));

        journal_sale_code = $.trim(journal_sale_code);
        journal_sale_date = $.trim(journal_sale_date);
        journal_sale_name = $.trim(journal_sale_name);
        

        if(journal_sale_code.length == 0){
            alert("Please input Journal Sale code");
            document.getElementById("journal_sale_code").focus();
            return false;
        }else if(journal_sale_date.length == 0){
            alert("Please input Journal Sale date");
            document.getElementById("journal_sale_date").focus();
            return false;
        }else if(journal_sale_name.length == 0){
            alert("Please input journal_sale name");
            document.getElementById("journal_sale_name").focus();
            return false;
        }else if (debit_total != credit_total){
            alert("Can not save data. \nBecause credit value and debit value not match. "); 
            return false;
        }else{
            $('#tb_journal').children('tbody').children('tr').children('td').children('div').children('select[name="account_id[]"]').prop('disabled', false);
            return true;
        }

    }

    
    function delete_row(id){
        var journal_cheque_id = $(id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]');
        var journal_cheque_pay_id = $(id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]');
        var journal_invoice_customer_id = $(id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]');
        var journal_invoice_supplier_id = $(id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]');

        if(journal_cheque_id[0].value > 0){ 
            var check_id = $('#tb_cheque').children('tbody').children('tr').children('td').children('input[name="check_id[]"]');   
            for(var i = 0; i < check_id.length ;i++){
                if(check_id[i].value == journal_cheque_id[0].value){
                    $(check_id[i]).closest('tr').remove();
                    calculateChequeAll();
                }
            }
        }


        if(journal_cheque_pay_id[0].value > 0){
            var check_pay_id = $('#tb_cheque_pay').children('tbody').children('tr').children('td').children('input[name="check_pay_id[]"]');   
            for(var i = 0; i < check_pay_id.length ;i++){
                if(check_pay_id[i].value == journal_cheque_pay_id[0].value){
                    $(check_pay_id[i]).closest('tr').remove();
                    calculateChequePayAll();
                }
            }
        }

        if(journal_invoice_supplier_id[0].value > 0){
            var invoice_supplier_id = $('#tb_invoice_supplier').children('tbody').children('tr').children('td').children('input[name="invoice_supplier_id[]"]');   
            for(var i = 0; i < invoice_supplier_id.length ;i++){
                if(invoice_supplier_id[i].value == journal_invoice_supplier_id[0].value){
                    $(invoice_supplier_id[i]).closest('tr').remove();
                    calculateInvoiceSupplierAll();
                }
            }
        }

        if(journal_invoice_customer_id[0].value > 0){
            var invoice_customer_id = $('#tb_invoice_customer').children('tbody').children('tr').children('td').children('input[name="invoice_customer_id[]"]');   
            for(var i = 0; i < invoice_customer_id.length ;i++){
                if(invoice_customer_id[i].value == journal_invoice_customer_id[0].value){
                    $(invoice_customer_id[i]).closest('tr').remove();
                    calculateInvoiceCustomerAll();
                }
            }
        }

        $(id).closest('tr').remove();

        calculateAll();
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
                    '<input type="hidden" name="journal_cheque_id[]" value="0" />'+  
                    '<input type="hidden" name="journal_cheque_pay_id[]" value="0" />'+  
                    '<input type="hidden" name="journal_invoice_customer_id[]" value="0" />'+  
                    '<input type="hidden" name="journal_invoice_supplier_id[]" value="0" />'+  
                    '<input type="hidden" name="journal_sale_list_id[]" value="0" />'+      
                    '<select class="form-control select" type="text" name="account_id[]"  data-live-search="true" onchange="get_account_type(this);"  ></select>'+
                '</td>'+
                '<td><input type="text" class="form-control" name="journal_sale_list_name[]" value="' + document.getElementById("journal_sale_name").value + '" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;"  value="0" onchange="val_format(this);" name="journal_sale_list_debit[]" onclick="edit_credit(this)" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" value="0" onchange="val_format(this);" name="journal_sale_list_credit[]" onclick="edit_credit(this)" /></td>'+
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
            str += "<option value='" + value['account_id'] + "'>["+value['account_code']+"] " +  value['account_name_th'] + "</option>";
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
        var debit = document.getElementsByName('journal_sale_list_debit[]');
        var credit = document.getElementsByName('journal_sale_list_credit[]');
        var debit_total = 0.0;
        var credit_total = 0.0;

        for(var i = 0 ; i < debit.length ; i++){
            
            debit_total += parseFloat(debit[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        for(var i = 0 ; i < credit.length ; i++){
            
            credit_total += parseFloat(credit[i].value.toString().replace(new RegExp(',', 'g'),''));
        } 

        $('#journal_sale_list_debit').val((debit_total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#journal_sale_list_credit').val((credit_total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }

    function get_account_type(id){
        var account_id = $(id).val(); 
        console.log('cheque_account',cheque_account);
        console.log('cheque_pay_account',cheque_pay_account);
        console.log('vat_purchase_account',vat_purchase_account);
        console.log('vat_sale_account',vat_sale_account);
 
        if(account_id == cheque_account){
            var journal_cheque_id = $(id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]');
            if(journal_cheque_id[0].value == 0){ 
                add_cheque_row($('#add_cheque_row'),id);
            } 
        }else if (account_id == cheque_pay_account){
            var journal_cheque_pay_id = $(id).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]');
            if(journal_cheque_pay_id[0].value == 0){ 
                add_cheque_pay_row($('#add_cheque_pay_row'),id);
            } 
        }else if (account_id == vat_purchase_account){
            var journal_invoice_supplier_id = $(id).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]'); 
            if(journal_invoice_supplier_id[0].value == 0){ 
                add_invoice_supplier_row($('#add_invoice_supplier_row'),id);
            } 
        }else if (account_id == vat_sale_account){
            var journal_invoice_customer_id = $(id).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]'); 
            if(journal_invoice_customer_id[0].value == 0){ 
                add_invoice_customer_row($('#add_invoice_customer_row'),id);
            } 
        }else {

        }
    }

    function edit_credit(id){
        id =  $(id).closest('tr').children('td').children('div').children('select[name="account_id[]"]');
        console.log("edit_credit Acount id : ",id[0]);
        var account_id = $(id).val(); 
        if(account_id == cheque_account){
            var journal_cheque_id = $(id).closest('tr').children('td').children('input[name="journal_cheque_id[]"]');
            if(journal_cheque_id[0].value == 0){ 
                add_cheque_row($('#add_cheque_row'),id[0]);
            } else{
                var check_id = $('#tb_cheque').children('tbody').children('tr').children('td').children('input[name="check_id[]"]');   
                for(var i = 0; i < check_id.length ;i++){
                    if(check_id[i].value == journal_cheque_id[0].value){ 
                        edit_cheque_row(check_id[i],id[0]);
                    }
                }
            }
        }else if (account_id == cheque_pay_account){
            var journal_cheque_pay_id = $(id[0]).closest('tr').children('td').children('input[name="journal_cheque_pay_id[]"]');
            if(journal_cheque_pay_id[0].value == 0){ 
                add_cheque_pay_row($('#add_cheque_pay_row'),id[0]);
            } else{
                var check_pay_id = $('#tb_cheque_pay').children('tbody').children('tr').children('td').children('input[name="check_pay_id[]"]');   
                for(var i = 0; i < check_pay_id.length ;i++){
                    if(check_pay_id[i].value == journal_cheque_pay_id[0].value){
                        $(check_pay_id[i]).closest('tr').remove();
                        edit_cheque_pay_row(check_pay_id[i],id[0]);
                    }
                }
            }
        }else if (account_id == vat_purchase_account){
            var journal_invoice_supplier_id = $(id[0]).closest('tr').children('td').children('input[name="journal_invoice_supplier_id[]"]'); 
            if(journal_invoice_supplier_id[0].value == 0){ 
                add_invoice_supplier_row($('#add_invoice_supplier_row'),id[0]);
            } else{
                var invoice_supplier_id = $('#tb_invoice_supplier').children('tbody').children('tr').children('td').children('input[name="invoice_supplier_id[]"]');   
                for(var i = 0; i < invoice_supplier_id.length ;i++){
                    if(invoice_supplier_id[i].value == journal_invoice_supplier_id[0].value){
                        edit_invoice_supplier_row(invoice_supplier_id[i],id[0]);
                    }
                }
            }
        }else if (account_id == vat_sale_account){
            var journal_invoice_customer_id = $(id[0]).closest('tr').children('td').children('input[name="journal_invoice_customer_id[]"]'); 
            if(journal_invoice_customer_id[0].value == 0){ 
                add_invoice_customer_row($('#add_invoice_customer_row'),id[0]);
            } else{
                var invoice_customer_id = $('#tb_invoice_customer').children('tbody').children('tr').children('td').children('input[name="invoice_customer_id[]"]');   
                for(var i = 0; i < invoice_customer_id.length ;i++){
                    if(invoice_customer_id[i].value == journal_invoice_customer_id[0].value){
                        edit_invoice_customer_row(invoice_customer_id[i],id[0]);
                    }
                }
            }
        }else {

        }
    }
</script>


<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Journal Sale  Management</h1>
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
                <div class="row">
                    <div class="col-md-8">
                        เพิ่มสมุดรายวันขาย /  Add Journal Sale   
                    </div>
                    <div class="col-md-4" align="right">
                        <?PHP if($previous_id != ""){?>
                        <a class="btn btn-primary" href="?app=journal_sale&action=update&id=<?php echo $previous_id;?>" > <i class="fa fa-angle-double-left" aria-hidden="true"></i> <?php echo $previous_code;?> </a>
                        <?PHP } ?>

                        <?PHP if($next_id != ""){?>
                        <a class="btn btn-primary" href="?app=journal_sale&action=update&id=<?php echo $next_id;?>" >  <?php echo $next_code;?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> </a>
                        <?PHP } ?>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=journal_special_02&action=edit&id=<?PHP echo $journal_sale_id; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>หมายเลขสมุดรายวันขาย / Journal Sale Code <font color="#F00"><b>*</b></font></label>
                                <input id="journal_sale_code" name="journal_sale_code" class="form-control" value="<?php echo $journal_sale['journal_sale_code'];?>" >
                                <p class="help-block">Example : JG1801001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>วันที่ออกสมุดรายวันขาย / Journal Sale Date</label>
                                <input type="text" id="journal_sale_date" name="journal_sale_date"  class="form-control calendar" value="<?php echo $journal_sale['journal_sale_date'];?>" readonly/>
                                <p class="help-block">31/01/2018</p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>หัวข้อสมุดรายวันขาย / Journal Sale Name</label>
                                <input type="text" id="journal_sale_name" name="journal_sale_name"  class="form-control" value="<?php echo $journal_sale['journal_sale_name'];?>" />
                                <p class="help-block"></p>
                            </div>
                        </div>
    
                    </div>
                    <ul class="nav nav-tabs">
                        <li  class="active" ><a data-toggle="tab" class="tabs" href="#journal">รายการทีเดบิต / เครดิต</a></li>
                        <li  ><a data-toggle="tab" class="tabs" href="#cheque">เช็ครับ </a></li>
                        <li  ><a data-toggle="tab" class="tabs" href="#cheque_pay">เช็คจ่าย</a></li>
                        <li  ><a data-toggle="tab" class="tabs" href="#vat_purchase">ภาษีซื้อ</a></li>
                        <li  ><a data-toggle="tab" class="tabs" href="#vat_sale">ภาษีขาย</a></li>
                    </ul>

                    <div class="tab-content">

                        <div id="journal" class="tab-pane fade in active">
                            <h3>รายการทีเดบิต / เครดิต</h3>
                        
                            <table  id="tb_journal"  width="100%" class="table table-striped table-bordered table-hover" >
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
                                    $journal_sale_list_debit = 0;
                                    $journal_sale_list_credit = 0;
                                    for($i=0; $i < count($journal_sale_lists); $i++){
                                        $journal_sale_list_debit += $journal_sale_lists[$i]['journal_sale_list_debit'];
                                        $journal_sale_list_credit += $journal_sale_lists[$i]['journal_sale_list_credit'];
                                    ?>
                                    <tr class="odd gradeX">
                                        <td>

                                            <input type="hidden" name="journal_cheque_id[]" value="<?php echo $journal_sale_lists[$i]['journal_cheque_id']; ?>" /> 
                                            <input type="hidden" name="journal_cheque_pay_id[]" value="<?php echo $journal_sale_lists[$i]['journal_cheque_pay_id']; ?>" /> 
                                            <input type="hidden" name="journal_invoice_customer_id[]" value="<?php echo $journal_sale_lists[$i]['journal_invoice_customer_id']; ?>" />
                                            <input type="hidden" name="journal_invoice_supplier_id[]" value="<?php echo $journal_sale_lists[$i]['journal_invoice_supplier_id']; ?>" /> 
                                            <input type="hidden" name="journal_sale_list_id[]" value="<?php echo $journal_sale_lists[$i]['journal_sale_list_id']; ?>" />

                                            <select  class="form-control select" name="account_id[]" data-live-search="true"  onchange="get_account_type(this);" 
                                                <?PHP if(
                                                    $journal_sale_lists[$i]['journal_cheque_id'] > 0 ||
                                                    $journal_sale_lists[$i]['journal_cheque_pay_id'] > 0 ||
                                                    $journal_sale_lists[$i]['journal_invoice_customer_id'] > 0 ||
                                                    $journal_sale_lists[$i]['journal_invoice_supplier_id'] > 0 
                                                ){ ?>
                                                disabled
                                                <?PHP } ?>
                                             >
                                                <option value="">Select</option>
                                                <?php 
                                                for($ii =  0 ; $ii < count($accounts) ; $ii++){
                                                ?>
                                                <option <?php if($accounts[$ii]['account_id'] == $journal_sale_lists[$i]['account_id']){?> selected <?php }?> value="<?php echo $accounts[$ii]['account_id'] ?>">[<?php echo $accounts[$ii]['account_code'] ?>] <?php echo $accounts[$ii]['account_name_th'] ?></option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td align="right"><input type="text" class="form-control" name="journal_sale_list_name[]" value="<?php echo $journal_sale_lists[$i]['journal_sale_list_name']; ?>" /></td>
                                        <td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_sale_list_debit[]"  onchange="val_format(this);" value="<?php echo number_format($journal_sale_lists[$i]['journal_sale_list_debit'],2); ?>"  onclick="edit_credit(this)" 
                                            <?PHP if(
                                                    $journal_sale_lists[$i]['journal_cheque_id'] > 0 ||
                                                    $journal_sale_lists[$i]['journal_cheque_pay_id'] > 0 ||
                                                    $journal_sale_lists[$i]['journal_invoice_customer_id'] > 0 ||
                                                    $journal_sale_lists[$i]['journal_invoice_supplier_id'] > 0 
                                                ){ ?>
                                                readonly
                                            <?PHP } ?>
                                        /></td>
                                        <td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_sale_list_credit[]" onchange="val_format(this);" value="<?php echo number_format($journal_sale_lists[$i]['journal_sale_list_credit'],2); ?>"  onclick="edit_credit(this)" 
                                            <?PHP if(
                                                    $journal_sale_lists[$i]['journal_cheque_id'] > 0 ||
                                                    $journal_sale_lists[$i]['journal_cheque_pay_id'] > 0 ||
                                                    $journal_sale_lists[$i]['journal_invoice_customer_id'] > 0 ||
                                                    $journal_sale_lists[$i]['journal_invoice_supplier_id'] > 0 
                                                ){ ?>
                                                readonly
                                            <?PHP } ?>
                                        /></td>
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
                                            <input type="text" class="form-control" style="text-align: right;" id="journal_sale_list_debit" value="<?php echo number_format($journal_sale_list_debit,2); ?>" readonly />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" style="text-align: right;" id="journal_sale_list_credit" value="<?php echo number_format($journal_sale_list_credit,2); ?>" readonly />
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table> 
                        </div> 
                        
                        <div id="cheque" class="tab-pane fade">
                            <h3>เช็ครับ</h3>
                            <?PHP require_once($path.'cheque.inc.php'); ?>
                        </div>

                        <div id="cheque_pay" class="tab-pane fade">
                            <h3>เช็คจ่าย</h3>
                            <?PHP require_once($path.'cheque-pay.inc.php'); ?>  
                        </div>

                        <div id="vat_purchase" class="tab-pane fade">
                            <h3>ภาษีซื้อ</h3>
                            <?PHP require_once($path.'vat-purchase.inc.php'); ?>  
                        </div>

                        <div id="vat_sale" class="tab-pane fade">
                            <h3>ภาษีขาย</h3>
                            <?PHP require_once($path.'vat-sale.inc.php'); ?>
                        </div>

                    </div>


                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=journal_special_02" class="btn btn-default">Back</a>
                            <a href="index.php?app=journal_special_02&action=insert" class="btn btn-primary">Reset</a>
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


<!-- ************************************************** Cheque Modal  *********************************** -->
<?PHP require_once($path.'cheque.modal.inc.php'); ?>
<!-- ************************************************** End Cheque Modal  *********************************** -->


<!-- ************************************************** Cheque Modal  *********************************** -->
<?PHP require_once($path.'cheque-pay.modal.inc.php'); ?>
<!-- ************************************************** End Cheque Modal  *********************************** -->


<!-- ************************************************** Cheque Modal  *********************************** -->
<?PHP require_once($path.'vat-purchase.modal.inc.php'); ?>
<!-- ************************************************** End Cheque Modal  *********************************** -->

<!-- ************************************************** Cheque Modal  *********************************** -->
<?PHP require_once($path.'vat-sale.modal.inc.php'); ?>
<!-- ************************************************** End Cheque Modal  *********************************** -->

<script>
    $(".example-ajax-post").easyAutocomplete(options);
</script>