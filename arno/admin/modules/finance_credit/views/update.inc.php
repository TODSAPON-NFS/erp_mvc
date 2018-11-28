<script>
    var row_update_id ;
    var options = {
        url: function(keyword) {
            return "controllers/getChequePayByKeyword.php?keyword="+keyword;
        },

        getValue: function(element) {
            return element.check_pay_code ;
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

    var bank_account_data = [
    <?php for($i = 0 ; $i < count($bank_accounts) ; $i++ ){?>
        {
            bank_account_id:'<?php echo $bank_accounts[$i]['bank_account_id'];?>',
            bank_account_code:'<?php echo $bank_accounts[$i]['bank_account_code'];?>',
            bank_account_name:'<?php echo $bank_accounts[$i]['bank_account_name'];?>',
            account_id:'<?php echo $bank_accounts[$i]['account_id'];?>'
        },
    <?php }?>
    ];

    var finance_credit_account_data = [
    <?php for($i = 0 ; $i < count($finance_credit_accounts) ; $i++ ){?>
        {
            finance_credit_account_id:'<?php echo $finance_credit_accounts[$i]['finance_credit_account_id'];?>',
            finance_credit_account_code:'<?php echo $finance_credit_accounts[$i]['finance_credit_account_code'];?>',
            finance_credit_account_name:'<?php echo $finance_credit_accounts[$i]['finance_credit_account_name'];?>',
            finance_credit_account_cheque:'<?php echo $finance_credit_accounts[$i]['finance_credit_account_cheque'];?>',
            bank_account_id:'<?php echo $finance_credit_accounts[$i]['bank_account_id'];?>',
            account_id:'<?php echo $finance_credit_accounts[$i]['account_id'];?>'
        },
    <?php }?>
    ];

    var total_old = 0.0;
    var data_buffer = [];
    function check(){

        var supplier_id = document.getElementById("supplier_id").value;
        var finance_credit_code = document.getElementById("finance_credit_code").value;
        var finance_credit_date = document.getElementById("finance_credit_date").value;
        var employee_id = document.getElementById("employee_id").value;

        
        supplier_id = $.trim(supplier_id);
        finance_credit_code = $.trim(finance_credit_code);
        finance_credit_date = $.trim(finance_credit_date);
        employee_id = $.trim(employee_id);

        if(supplier_id.length == 0){
            alert("Please input iupplier.");
            document.getElementById("supplier_id").focus();
            return false;
        }else if(finance_credit_code.length == 0){
            alert("Please input Finance Credit date.");
            document.getElementById("finance_credit_code").focus();
            return false;
        }else if(finance_credit_date.length == 0){
            alert("Please input Finance Credit date.");
            document.getElementById("finance_credit_date").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else{
            var total = parseFloat($('#finance_credit_total').val().toString().replace(new RegExp(',', 'g'),''));
            var paid = parseFloat($('#finance_credit_pay').val().toString().replace(new RegExp(',', 'g'),''));

            if (total == paid){
                return true;
            }else if(confirm("เอกสารหมายเลข "+finance_credit_code+" ฉบับนี้ยังไม่สมบูรณ์เนื่องจากจำนวนเงินที่จ่ายจริง ไม่ตรงกับจำนวนเงินที่ต้องจ่าย")){
                return true;
            }else{
                return false;
            }
        }



    }


    function get_bank_account_name (id){ 
        var bank_account_id = $(id).val();
        var bank_account = bank_account_data.filter(val => val.bank_account_id == bank_account_id );

        
        var payment = $(id).closest('tr').children('td').children('div').children('div').children('select[name="finance_credit_account_id[]"]').val();
        var finance_credit_account = finance_credit_account_data.filter(val => val.finance_credit_account_id == payment );

        if(bank_account.length > 0){
            $(id).closest('tr').children('td').children('div').children('div').children('input[name="account_id[]"]').val(bank_account[0].account_id);
            $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_credit_pay_bank[]"]').val(bank_account[0].bank_account_name)
        }else{
            $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_credit_pay_bank[]"]').val('')
            $(id).closest('tr').children('td').children('div').children('div').children('input[name="account_id[]"]').val(bank_account[0].account_id);
        }
    }

    
    function generate_code(id){
        var payment = $(id).val();
        var finance_credit_account = finance_credit_account_data.filter(val => val.finance_credit_account_id == payment );

        
        if(finance_credit_account.length > 0){
            $(id).closest('tr').children('td').children('input[name="finance_credit_account_cheque[]"]').val(finance_credit_account[0].finance_credit_account_cheque);
            if(finance_credit_account[0].finance_credit_account_cheque == 1){
                // $.post( "controllers/getChequePayCodeIndex.php", {  }, function( data ) { 
                //     $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_credit_pay_by[]"]').val(data);
                //     $(id).closest('tr').children('td').children('div').children('div').children('div').children('input[name="finance_credit_pay_by[]"]').val(data);
                //     $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_credit_pay_by[]"]').easyAutocomplete(options);
                //     get_cheque_data(id,data);
                // }); 
                $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_credit_pay_by[]"]').val("QP");
                $(id).closest('tr').children('td').children('div').children('div').children('div').children('input[name="finance_credit_pay_by[]"]').val("QP");
                $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_credit_pay_by[]"]').easyAutocomplete(options);
            }else{ 
                $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_credit_pay_by[]"]').val(finance_credit_account[0].finance_credit_account_code); 
                $(id).closest('tr').children('td').children('div').children('div').children('div').children('input[name="finance_credit_pay_by[]"]').val(finance_credit_account[0].finance_credit_account_code); 
                
                $(id).closest('tr').children('td').children('div').children('div').children('div').children('select[name="bank_account_id[]"]').val(finance_credit_account[0].bank_account_id);
                
                $(id).closest('tr').children('td').children('div').children('div').children('input[name="account_id[]"]').val(finance_credit_account[0].account_id);
                $('.select').selectpicker('refresh');

                var bank_account = bank_account_data.filter(val => val.bank_account_id == finance_credit_account[0].bank_account_id );
                if(bank_account.length > 0){
                    $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_credit_pay_bank[]"]').val(bank_account[0].bank_account_name);
                }else{
                    $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_credit_pay_bank[]"]').val('')
                }

            }
        }

    }

    function get_supplier_detail(){
        var supplier_id = document.getElementById('supplier_id').value;
        $.post( "controllers/getSupplierByID.php", { 'supplier_id': supplier_id }, function( data ) {
            document.getElementById('supplier_code').value = data.supplier_code;
            document.getElementById('finance_credit_name').value = data.supplier_name_en ;
            document.getElementById('finance_credit_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3+' '+ data.customer_zipcode+ '\nTel.'+ data.customer_tel+' Fax. '+data.customer_fax;
            document.getElementById('finance_credit_tax').value = data.supplier_tax ;
        });
    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
        calculateAll();
        calculatePay();
     }




    function show_invoice_supplier(id){
        var supplier_id = document.getElementById('supplier_id').value;
        var val = document.getElementsByName('invoice_supplier_id[]');
        var invoice_supplier_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            invoice_supplier_id.push(val[i].value);
        }
        
        if(supplier_id != ""){

            $.post( "controllers/getFinanceCreditListBySupplierID.php", { 'supplier_id': supplier_id, 'invoice_supplier_id': JSON.stringify(invoice_supplier_id) }, function( data ) {
               
                if(data.length > 0){
                    document.getElementById("check_all").checked = false;
                    data_buffer = data;
                    var content = "";
                    $('#table_popup').DataTable().destroy();
                    for(var i = 0; i < data.length ; i++){
                       var finance_credit_list_amount = parseFloat(data[i].finance_credit_list_amount);
                       var finance_credit_list_paid = parseFloat(data[i].finance_credit_list_amount);
                       var finance_credit_list_balance = finance_credit_list_amount - finance_credit_list_paid;
                        content += '<tr class="odd gradeX">'+
                                        '<td>'+
                                            '<input type="checkbox" name="p_id" value="'+data[i].invoice_supplier_id+'" />'+     
                                        '</td>'+
                                        '<td>'+
                                            data[i].invoice_supplier_code +
                                        '</td>'+
                                        '<td>'+
                                            data[i].finance_credit_list_recieve +
                                        '</td>'+
                                        '<td>'+
                                            data[i].finance_credit_list_date +
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].finance_credit_list_due +
                                        '</td>'+
                                        '<td align="right">'+
                                            finance_credit_list_amount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +
                                        '</td>'+
                                        '<td align="right">'+
                                            finance_credit_list_paid.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +
                                        '</td>'+
                                        '<td align="right">'+
                                            finance_credit_list_balance.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + 
                                        '</td>'+
                                    '</tr>';

                    }
                    $('#bodyAdd').html(content); 
                    $('#modalAdd').modal('show');
                    $('#table_popup').DataTable({
                        "columnDefs": [ {
                            "targets": 'no-sort',
                            "orderable": false,
                        } ],
                        "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
                        "pageLength": 100,
                        responsive: true
                    });

                }
                
                
            });
        }else{
            alert("Please select Supplier.");
        }
        
    } 

    function search_pop_like(id){
        var supplier_id = document.getElementById('supplier_id').value;
        var val = document.getElementsByName('invoice_supplier_id[]');
        var invoice_supplier_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            invoice_supplier_id.push(val[i].value);
        }

        $.post( "controllers/getFinanceCreditListBySupplierID.php", { 'supplier_id': supplier_id, 'invoice_supplier_id': JSON.stringify(invoice_supplier_id), search : $(id).val() }, function( data ) {
            var content = "";
            document.getElementById("check_all").checked = false;
            console.log(data);
            if(data.length > 0){
                data_buffer = data;
                $('#table_popup').DataTable().destroy();
                for(var i = 0; i < data.length ; i++){
                       var finance_credit_list_amount = parseFloat(data[i].finance_credit_list_amount);
                       var finance_credit_list_paid = parseFloat(data[i].finance_credit_list_amount);
                       var finance_credit_list_balance = finance_credit_list_amount - finance_credit_list_paid;
                        content += '<tr class="odd gradeX">'+
                                        '<td>'+
                                            '<input type="checkbox" name="p_id" value="'+data[i].invoice_supplier_id+'" />'+     
                                        '</td>'+
                                        '<td>'+
                                            data[i].invoice_supplier_code +
                                        '</td>'+
                                        '<td>'+
                                            data[i].finance_credit_list_recieve +
                                        '</td>'+
                                        '<td>'+
                                            data[i].finance_credit_list_date +
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].finance_credit_list_due +
                                        '</td>'+
                                        '<td align="right">'+
                                            finance_credit_list_amount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +
                                        '</td>'+
                                        '<td align="right">'+
                                            finance_credit_list_paid.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +
                                        '</td>'+
                                        '<td align="right">'+
                                            finance_credit_list_balance.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + 
                                        '</td>'+
                                    '</tr>';

                    }
            }
            $('#bodyAdd').html(content);
            $('#table_popup').DataTable({
                "columnDefs": [ {
                    "targets": 'no-sort',
                    "orderable": false,
                } ],
                "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
                "pageLength": 100,
                responsive: true
            });

            
        });

        
    }

    function add_row(id){
        $('#modalAdd').modal('hide');
        var checkbox = document.getElementsByName('p_id');
        for(var i = 0 ; i < (checkbox.length); i++){
            if(checkbox[i].checked){

                var index = 0;
                if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
                    index = 1;
                }else{
                    index = $(id).closest('table').children('tbody').children('tr').length + 1;
                }

                $(id).closest('table').children('tbody').append(
                    '<tr class="odd gradeX">'+
                        '<td>'+  
                            '<input type="hidden" name="finance_credit_list_id[]" value="0" />'+
                            '<input type="hidden" name="invoice_supplier_id[]" value="'+data_buffer[i].invoice_supplier_id+'" />'+
                            '<input type="text" class="form-control"  value="'+data_buffer[i].invoice_supplier_code+'" readonly />'+ 
                            '<input type="text" class="form-control" name="finance_credit_list_remark[]" />'+
                        '</td>'+
                        '<td align="center">'+
                            data_buffer[i].finance_credit_list_date + 
                        '</td>'+
                        '<td align="center">'+
                            data_buffer[i].finance_credit_list_due + 
                        '</td>'+
                        '<td align="right">'+
                            '<input type="text" class="form-control" name="finance_credit_list_recieve[]"  value="'+data_buffer[i].finance_credit_list_recieve+'"  readonly />'+
                        '</td>'+
                        '<td align="right">'+
                            '<input type="text" class="form-control" name="finance_credit_list_receipt[]" />'+
                        '</td>'+
                        '<td align="right">'+
                            '<input type="text" class="form-control" name="finance_credit_list_amount[]" style="text-align:right" onchange="update_sum(this);" value="'+data_buffer[i].finance_credit_list_amount+'" />'+
                        '</td>'+
                        '<td align="right">'+
                        '<input type="text" class="form-control" name="finance_credit_list_paid[]" style="text-align:right" onchange="update_sum(this);" value="'+data_buffer[i].finance_credit_list_paid+'" readonly />'+
                        '</td>'+
                        '<td align="right">'+
                        '<input type="text" class="form-control" name="finance_credit_list_balance[]" style="text-align:right" onchange="update_sum(this);" value="'+(data_buffer[i].finance_credit_list_amount - data_buffer[i].finance_credit_list_paid)+'"  />'+
                        '</td>'+
                        '<td>'+
                            '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                '<i class="fa fa-times" aria-hidden="true"></i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );

            }
            
        }
        calculateAll();
    }

    


    function checkAll(id)
    {
        var checkbox = document.getElementById('check_all');
        if (checkbox.checked == true){
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', true);
        }else{
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', false);
        }
    }

    function update_sum(id){

        var amount =  parseFloat($(id).closest('tr').children('td').children('input[name="finance_credit_list_amount[]"]').val(  ).replace(',',''));
        var paid =  parseFloat($(id).closest('tr').children('td').children('input[name="finance_credit_list_paid[]"]').val( ).replace(',',''));
        var balance =  parseFloat($(id).closest('tr').children('td').children('input[name="finance_credit_list_balance[]"]').val( ).replace(',',''));

        if(isNaN(amount)){
            amount = 0;
        }

        if(isNaN(paid)){
            paid = 0.0;
        }

        if(isNaN(balance)){
            balance = 0.0;
        }

        balance = amount-paid;

        $(id).closest('tr').children('td').children('input[name="finance_credit_list_amount[]"]').val( amount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="finance_credit_list_paid[]"]').val( paid.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        //$(id).closest('tr').children('td').children('input[name="finance_credit_list_balance[]"]').val( balance.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();


    }

    function add_row_pay(id){
         var index = 0;
         if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
            index = 1;
         }else{
            index = $(id).closest('table').children('tbody').children('tr').length + 1;
         }
         $(id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td >'+
                    '<input type="hidden" class="form-control" name="finance_credit_pay_id[]" value="0" />'+ 
                    '<input type="hidden" class="form-control" name="check_pay_id[]" value="0" />'+ 
                    '<input type="hidden" class="form-control" name="finance_credit_account_cheque[]" value="0" />'+ 
                    '<div class="row">'+ 
                        '<div class="col-md-6">'+ 
                            '<select  name="finance_credit_account_id[]" onchange="generate_code(this);" class="form-control select" data-live-search="true">'+ 
                                '<option value="">Select</option>'+ 
                            '</select>'+ 
                        '</div>'+ 
                        '<div class="col-md-6">'+ 
                            '<input type="text" class="form-control" name="finance_credit_pay_by[]" value="" onchange="get_cheque_id(this)" />'+ 
                        '</div>'+ 
                    '</div> '+ 
                '</td>'+ 
                '<td  style="max-width:100px;" >' +
                '<input type="text" class="form-control calendar"  name="finance_credit_pay_date[]"  readonly/>'+
                '</td>'+ 
                '<td >'+
                    '<div class="row">'+
                        '<div class="col-md-6">'+
                            '<input type="hidden" name="account_id[]"  value="0" />'+
                            '<select  name="bank_account_id[]" onchange="get_bank_account_name(this);" class="form-control select" data-live-search="true">'+
                                '<option value="">Select</option> '+
                            '</select>'+
                        '</div>'+
                        '<div class="col-md-6">'+
                            '<input type="text" class="form-control" name="finance_credit_pay_bank[]" value="" />'+
                        '</div>'+
                    '</div>'+
                '</td>'+
                '<td  style="max-width:100px;" ><input type="text" style="text-align:right;" class="form-control" name="finance_credit_pay_value[]"   onchange="calculatePay()" /></td>'+
                '<td  style="max-width:100px;" ><input type="text" style="text-align:right;" class="form-control" name="finance_credit_pay_balance[]"  onchange="calculatePay()"  /></td>'+
                '<td  style="max-width:100px;" ><input type="text" style="text-align:right;" class="form-control" name="finance_credit_pay_total[]" onchange="calculatePay()"   /></td>'+
                
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="finance_credit_account_id[]"]').empty();
        var str = "<option value=''>เลือกวิธีการจ่ายเงิน</option>";
        $.each(finance_credit_account_data, function (index, value) {
            str += "<option value='" + value['finance_credit_account_id'] + "'>["+value['finance_credit_account_code']+"] " +  value['finance_credit_account_name'] + "</option>";
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="finance_credit_account_id[]"]').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="finance_credit_account_id[]"]').selectpicker();




        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="bank_account_id[]"]').empty();
        var str = "<option value=''>เลือกบัญชีธนาคาร</option>";
        $.each(bank_account_data, function (index, value) {
            str += "<option value='" + value['bank_account_id'] + "'>["+value['bank_account_code']+"] " +  value['bank_account_name'] + "</option>";
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="bank_account_id[]"]').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="bank_account_id[]"]').selectpicker();



        $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="finance_credit_pay_date[]"]').datepicker({ dateFormat: 'dd-mm-yy' });
                
    }

    function calculateAll(){

        var val = document.getElementsByName('finance_credit_list_balance[]');
        var total = 0.0;
        
        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }
       
        $('#finance_credit_total').val((total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
    }

    function calculatePay(){
        
        var value = document.getElementsByName('finance_credit_pay_value[]');
        var balance = document.getElementsByName('finance_credit_pay_balance[]');
        var total = document.getElementsByName('finance_credit_pay_total[]');

        var finance_credit_total = parseFloat(document.getElementById('finance_credit_total').value.toString().replace(new RegExp(',', 'g'),''));
        var cash = document.getElementById('finance_credit_cash');
        var interest = document.getElementById('finance_credit_interest');
        var tax_pay = document.getElementById('finance_credit_tax_pay');
        var discount_cash = document.getElementById('finance_credit_discount_cash');

        var sum_total = 0.0;

        var val_cash = parseFloat(cash.value.toString().replace(new RegExp(',', 'g'),''));
        var val_interest = parseFloat(interest.value.toString().replace(new RegExp(',', 'g'),''));
        var val_tax_pay = parseFloat(tax_pay.value.toString().replace(new RegExp(',', 'g'),''));
        var val_discount_cash = parseFloat(discount_cash.value.toString().replace(new RegExp(',', 'g'),''));


        if(isNaN(finance_credit_total)){    
            finance_credit_total = 0;
        }

        if(isNaN(val_cash)){
            val_cash = 0;
        }
    
        if(isNaN(val_interest)){
            val_interest = 0;
        }

        if(isNaN(val_tax_pay)){
            val_tax_pay = 0;
        }

        if(isNaN(val_discount_cash)){
            val_discount_cash = 0;
        }


        for(var i = 0 ; i < total.length ; i++){

            var val_value = parseFloat(value[i].value.toString().replace(new RegExp(',', 'g'),''));
            var val_balance = parseFloat(balance[i].value.toString().replace(new RegExp(',', 'g'),''));
            var val_total = parseFloat(total[i].value.toString().replace(new RegExp(',', 'g'),''));

            

            if(isNaN(val_total)){
                val_total = 0;
            }

            if(isNaN(val_value)){
                val_value = 0;
            }

            if(isNaN(val_balance)){  
                val_balance = 0;
            }

            if(val_value <= val_total){
                value[i].value = val_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                total[i].value = val_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                balance[i].value = "0.00";
            }else{
                val_balance = val_value - val_total;
                value[i].value = val_value.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                total[i].value = val_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                balance[i].value = val_balance.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            }

            sum_total += val_total;

            
        }

        
        document.getElementById('finance_credit_other_pay').value = sum_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") ;
        cash.value = val_cash.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        interest.value = val_interest.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        tax_pay.value = val_tax_pay.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        discount_cash.value = val_discount_cash.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");

        var finance_credit_pay =  val_cash + sum_total - val_interest + val_tax_pay + val_discount_cash;
        //console.log(finance_credit_pay);
        document.getElementById('finance_credit_pay').value = finance_credit_pay.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") ;

    }





</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Finance Credit Management</h1>
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
                        เพิ่มใบจ่ายชำระหนี้ / Add Finance Credit  
                    </div>
                    <div class="col-md-4" align="right">
                        <?PHP if($previous_id != ""){?>
                        <a class="btn btn-primary" href="?app=finance_credit&action=update&id=<?php echo $previous_id;?>" > <i class="fa fa-angle-double-left" aria-hidden="true"></i> <?php echo $previous_code;?> </a>
                        <?PHP } ?>

                        <a class="btn btn-danger" href="print.php?app=report_journal_04&type=id&action=pdf&id=<?php echo $journal_id;?>" target="_blank" > <i class="fa fa-print" aria-hidden="true"></i> พิมพ์ </a>
                        
                        <a class="btn btn-warning" href="?app=journal_special_04&action=update&id=<?php echo $journal_id;?>" target="_blank" > <i class="fa fa-folder-open" aria-hidden="true"></i> สมุดรายวันขาย </a>
                        
                        <?PHP if($next_id != ""){?>
                        <a class="btn btn-primary" href="?app=finance_credit&action=update&id=<?php echo $next_id;?>" >  <?php echo $next_code;?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> </a>
                        <?PHP } ?>
                    </div>
                </div> 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=finance_credit&action=edit&id=<?PHP echo $finance_credit_id;?>" >
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                                        <select id="supplier_id" name="supplier_id" class="form-control select" onchange="get_supplier_detail()" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($suppliers) ; $i++){
                                            ?>
                                            <option <?php if($suppliers[$i]['supplier_id'] == $supplier['supplier_id']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อตามใบจ่ายชำระหนี้ / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="finance_credit_name" name="finance_credit_name" class="form-control" value="<?php echo $finance_credit['finance_credit_name']; ?>" >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="finance_credit_address" name="finance_credit_address" class="form-control" rows="5" ><?php echo $finance_credit['finance_credit_address']; ?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <input  id="finance_credit_tax" name="finance_credit_tax" class="form-control" value="<?php echo $finance_credit['finance_credit_tax']; ?>" >
                                        <p class="help-block">Example : 0305559003597.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1">
                        </div>
                        <div class="col-lg-5">
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกใบจ่ายชำระหนี้ / Date</label>
                                        <input type="text" id="finance_credit_date" name="finance_credit_date"  class="form-control calendar" value="<?PHP echo $finance_credit['finance_credit_date']?>" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบจ่ายชำระหนี้ / CN code <font color="#F00"><b>*</b></font></label>
                                        <input id="finance_credit_code" name="finance_credit_code" class="form-control" value="<?PHP echo $finance_credit['finance_credit_code']?>" >
                                        <p class="help-block">Example : CN1801001.</p>
                                    </div>
                                </div>
                                

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ออกใบจ่ายชำระหนี้ / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option <?PHP if($finance_credit['employee_id'] == $users[$i]['user_id']){?> SELECTED <?PHP }?> value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div> 

                     <div>
                    <b>Our reference :</b>
                    </div>
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;" width="150" >หมายใบกำกับภาษี <br> (Invoice Number)</th>
                                <th style="text-align:center;" width="150">วันที่ออก <br> (Date)</th>
                                <th style="text-align:center;" width="150">กำหนดชำระ <br> (Due Date)</th>
                                <th style="text-align:center;" >ใบรับสินค้าเข้า <br> (RR/RF)</th>
                                <th style="text-align:center;" >เลขที่ใบเสร็จ <br> (Receipt)</th>
                                <th style="text-align:center;" width="150">จำนวนเงิน <br> (Amount) </th>
                                <th style="text-align:center;" width="150">ชำระแล้ว <br> (Paid)</th>
                                <th style="text-align:center;" width="150">ยอดชำระคงเหลือ <br> (Balance)</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($finance_credit_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="finance_credit_list_id[]" value="<?PHP echo  $finance_credit_lists[$i]['finance_credit_list_id'];?>" />
                                    <input type="hidden" name="invoice_supplier_id[]" value="<?PHP echo  $finance_credit_lists[$i]['invoice_supplier_id'];?>" />
                                    <input type="text" class="form-control" value="<?PHP echo  $finance_credit_lists[$i]['invoice_supplier_code'];?>" readonly />
                                    <input type="text" class="form-control" name="finance_credit_list_remark[]" />
                                </td>
                                <td align="center">
                                    <?PHP echo  $finance_credit_lists[$i]['finance_credit_list_date'];?>
                                </td>
                                <td align="center">
                                    <?PHP echo  $finance_credit_lists[$i]['finance_credit_list_due'];?>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="finance_credit_list_recieve[]" value="<?PHP echo  $finance_credit_lists[$i]['finance_credit_list_recieve'];?>" readonly />
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="finance_credit_list_receipt[]" />
                                </td>
                                <td align="right">
                                    <input type="text" class="form-control" name="finance_credit_list_amount[]" style="text-align:right" onchange="update_sum(this);" value="<?PHP echo  number_format($finance_credit_lists[$i]['finance_credit_list_amount'],2);?>" />
                                </td>
                                <td  align="right">
                                    <input type="text" class="form-control" name="finance_credit_list_paid[]" style="text-align:right" onchange="update_sum(this);" value="<?PHP echo  number_format($finance_credit_lists[$i]['finance_credit_list_paid'],2);?>" readonly />
                                </td>
                                <td align="right">
                                    <input type="text" class="form-control" name="finance_credit_list_balance[]" style="text-align:right" onchange="update_sum(this);" value="<?PHP echo  number_format($finance_credit_lists[$i]['finance_credit_list_balance'],2);?>" />
                                </td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $finance_credit_lists[$i]['finance_credit_list_balance'];
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="9" align="center">
                                    <a href="javascript:;" onclick="show_invoice_supplier(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มใบกำกับภาษี / Add Invoice</span>
                                    </a>

                                    <div id="modalAdd" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg " role="document">
                                            <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">เลือกรายการใบกำกับภาษี / Choose Invoice</h4>
                                            </div>

                                            <div  class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-offset-8 col-md-4" align="right">
                                                        <input type="text" class="form-control" name="search_pop" onchange="search_pop_like(this)" placeholder="Search"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div align="left">
                                                    <table width="100%" class="table table-striped table-bordered table-hover"  id="table_popup" >
                                                        <thead>
                                                            <tr>
                                                                <th width="24" class="no-sort" ><input type="checkbox" value="all" id="check_all" onclick="checkAll(this)" /></th>
                                                                <th style="text-align:center;" width="150">รหัสใบกำกับภาษี <br> (Invoice Number)</th>
                                                                <th style="text-align:center;" width="150">รหัสใบรับสินค้า <br> (RR / RF)</th>
                                                                <th style="text-align:center;" width="150">วันที่ออก <br> (Date)</th>
                                                                <th style="text-align:center;" width="150">กำหนดชำระ <br> (Due Date)</th>
                                                                <th style="text-align:center;" width="150">จำนวนเงิน <br> (Amount) </th>
                                                                <th style="text-align:center;" width="150">ชำระแล้ว <br> (Paid)</th>
                                                                <th style="text-align:center;" width="150">ยอดชำระคงเหลือ <br> (Balance)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="bodyAdd">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" onclick="add_row(this);">Add Invoice</button>

                                            </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->


                                </td>
                            </tr>

                            <tr class="odd gradeX">
                                <td colspan="5"></td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="finance_credit_total" name="finance_credit_total" value="<?PHP echo number_format($total,2) ;?>" readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table>   


                    <br>
                    <div>
                    <b>รายการชำระเงิน :</b>
                    </div>

                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">ชำระโดยอื่นๆ <br>(Pay by)</th>
                                <th style="text-align:center;">ลงวันที่<br>(Product Name)</th> 
                                <th style="text-align:center;max-width:120px;">ธนาคาร<br>(Bank)</th>
                                <th style="text-align:center;max-width:120px;">จำนวนเงิน<br>(Total)</th>
                                <th style="text-align:center;max-width:120px;">ยอดคงเหลือ<br>(Balance)</th>
                                <th style="text-align:center;max-width:120px;">ยอดชำระ<br>(Pay)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $total = 0;
                            for($i=0; $i < count($finance_credit_pays); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" class="form-control" name="finance_credit_pay_id[]" value="<?php echo $finance_credit_pays[$i]['finance_credit_pay_id']; ?>" />
                                    <input type="hidden" class="form-control" name="check_pay_id[]" value="<?php echo $finance_credit_pays[$i]['check_pay_id']; ?>" />
                                    <input type="hidden" class="form-control" name="finance_credit_account_cheque[]" value="<?php echo $finance_credit_pays[$i]['finance_credit_account_cheque']; ?>" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select  name="finance_credit_account_id[]" onchange="generate_code(this);" class="form-control select" data-live-search="true">
                                                <option value="">Select</option>
                                                <?php 
                                                for($ii =  0 ; $ii < count($finance_credit_accounts) ; $ii++){
                                                ?>
                                                <option <?PHP if($finance_credit_pays[$i]['finance_credit_account_id'] == $finance_credit_accounts[$ii]['finance_credit_account_id']){?> SELECTED <?PHP }?> value="<?php echo $finance_credit_accounts[$ii]['finance_credit_account_id'] ?>">[<?php echo $finance_credit_accounts[$ii]['finance_credit_account_code'] ?>] <?php echo $finance_credit_accounts[$ii]['finance_credit_account_name'] ?> </option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="finance_credit_pay_by[]" value="<?php echo $finance_credit_pays[$i]['finance_credit_pay_by']; ?>" onchange="get_cheque_id(this)" />
                                        </div>
                                    </div> 
                                </td>
                                <td>
                                    <input type="text" class="form-control calendar" name="finance_credit_pay_date[]" value="<?php echo $finance_credit_pays[$i]['finance_credit_pay_date']; ?>" readonly/>
                                </td> 
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" name="account_id[]"  value="<?php echo $finance_credit_pays[$i]['account_id']; ?>" />
                                            <select  name="bank_account_id[]" onchange="get_bank_account_name(this);" class="form-control select" data-live-search="true">
                                                <option value="">Select</option>
                                                <?php 
                                                for($ii =  0 ; $ii < count($bank_accounts) ; $ii++){
                                                ?>
                                                <option <?PHP if($finance_credit_pays[$i]['bank_account_id'] == $bank_accounts[$ii]['bank_account_id']){?> SELECTED <?PHP }?> value="<?php echo $bank_accounts[$ii]['bank_account_id'] ?>">[<?php echo $bank_accounts[$ii]['bank_account_code'] ?>] <?php echo $bank_accounts[$ii]['bank_account_name'] ?> </option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="finance_credit_pay_bank[]" value="<?php echo $finance_credit_pays[$i]['finance_credit_pay_bank']; ?>" />
                                        </div>
                                    </div>
                                </td> 
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="finance_credit_pay_value[]" value="<?php echo number_format($finance_credit_pays[$i]['finance_credit_pay_value'],2); ?>"  onchange="calculatePay()" /></td>
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="finance_credit_pay_balance[]" value="<?php echo number_format($finance_credit_pays[$i]['finance_credit_pay_balance'],2); ?>"  onchange="calculatePay()" /></td>
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="finance_credit_pay_total[]" value="<?php echo number_format($finance_credit_pays[$i]['finance_credit_pay_total'],2); ?>"  onchange="calculatePay()"  /></td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $finance_credit_pays[$i]['finance_credit_pay_total'];
                            }
                            

                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="7" align="center">
                                    <a href="javascript:;" onclick="add_row_pay(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มรายการจ่ายเงิน / Add pay list</span>
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <br>
                    <div class="row">
                        <div class="col-lg-2" style="display:none;">
                            <div class="form-group">
                                <label>ดอกเบี้ย</label>
                                <input id="finance_credit_interest" name="finance_credit_interest" style="text-align:right;" class="form-control" value="<?PHP echo number_format($finance_credit['finance_credit_interest'],2);?>" onchange="calculatePay()" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>  
                        <div class="col-lg-2" style="display:none;">
                            <div class="form-group">
                                <label>เงินสด</label>
                                <input id="finance_credit_cash" name="finance_credit_cash" style="text-align:right;" class="form-control" value="<?PHP echo number_format($finance_credit['finance_credit_cash'],2);?>" onchange="calculatePay()" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                        <div class="col-lg-2" style="display:none;">
                            <div class="form-group">
                                <label>ชำระโดย (ด้านบน)</label>
                                <input id="finance_credit_other_pay" name="finance_credit_other_pay" style="text-align:right;" class="form-control" value="<?PHP echo number_format($total,2);?>" readonly >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                        <div class="col-lg-2" style="display:none;">
                            <div class="form-group">
                                <label>ภาษีหัก ณ ที่จ่าย</label>
                                <input id="finance_credit_tax_pay" name="finance_credit_tax_pay"  style="text-align:right;" class="form-control" value="<?PHP echo number_format($finance_credit['finance_credit_tax_pay'],2);?>" onchange="calculatePay()" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                        <div class="col-lg-2" style="display:none;">
                            <div class="form-group">
                                <label>ส่วนลดเงินสด</label>
                                <input id="finance_credit_discount_cash" name="finance_credit_discount_cash" style="text-align:right;" class="form-control" value="<?PHP echo number_format($finance_credit['finance_credit_discount_cash'],2);?>" onchange="calculatePay()" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                        <div class="col-lg-offset-10 col-lg-2">
                            <div class="form-group">
                                <label>ยอดจ่ายจริง</label>
                                <input id="finance_credit_pay" name="finance_credit_pay" style="text-align:right;" class="form-control" value="<?PHP echo number_format($finance_credit['finance_credit_pay'],2);?>" readonly>
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                    </div>

                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=finance_credit" class="btn btn-default">Back</a>
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

<script>
    
    function get_cheque_id(id){
        
        get_cheque_data(id,$(id).val());
       
    }



    function get_cheque_data(id,code){
        row_update_id = id;
        $.post( "controllers/getChequePayByCode.php", { 'check_pay_code': code }, function( data ) {
            console.log(data);
            if(data !== null){

                $(id).closest('tr').children('td').children('input[name="check_pay_id[]"]').val(data.check_pay_id);
                
                $('#check_pay_id').val(data.check_pay_id);
                $('#check_pay_date_write').val(data.check_pay_date_write); 
                $('#check_pay_code').val(data.check_pay_code);  
                $('#cheque_supplier_id').val(data.supplier_id);
                $('#bank_account_id').val(data.bank_account_id);
                $('#check_pay_date').val(data.check_pay_date);
                $('#check_pay_total').val(data.check_pay_total);
                $('#check_pay_remark').val(data.check_pay_remark);

                $('#cheque_submit').html('Update Cheque');
                $('#action').val('edit');
                $('#cheque_delete').show();

                $('.select').selectpicker('refresh');
                $('#modalCheque').modal('show');

            }else{ 
                $('#check_pay_id').val('0');
                $('#check_pay_date_write').val($('#finance_credit_date').val()); 
                $('#check_pay_code').val(code);  
                $('#cheque_supplier_id').val($('#supplier_id').val());
                $('#bank_account_id').val();
                $('#check_pay_date').val($('#finance_credit_date').val());
                $('#check_pay_total').val($('#finance_credit_total').val());
                $('#check_pay_remark').val("จ่ายหนี้ให้ " + $('#finance_credit_name').val());

                $('#check_submit').html('Add Cheque');
                $('#action').val('add');
                $('#cheque_delete').hide();

                $('.select').selectpicker('refresh');
                $('#modalCheque').modal('show');
            }
            
        });
    }

    function delete_check(){
        var check_pay_id = document.getElementById("check_pay_id").value; 
        $.post( "controllers/deleteChequePay.php", 
            { 
                'check_pay_id':check_pay_id 
            }, 
            function( data ) {
                console.log(data);
                if(data == true){
                    $(row_update_id).closest('tr').children('td').children('input[name="check_pay_id[]"]').val(0);
                    $(row_update_id).closest('tr').children('td').children('input[name="finance_credit_pay_by[]"]').val('');
                    $('#modalCheque').modal('hide');
                }else{
                    alert("Can not delete check payment. Please contact administrator");
                }
            }
        );
    }

    function check_post(){
        var check_pay_code = document.getElementById("check_pay_code").value;
        var check_pay_date_write = document.getElementById("check_pay_date_write").value;
        var check_pay_date = document.getElementById("check_pay_date").value;
        var bank_account_id = document.getElementById("bank_account_id").value;
        var supplier_id = document.getElementById("cheque_supplier_id").value;
        var check_pay_remark = document.getElementById("check_pay_remark").value;
        var check_pay_total = document.getElementById("check_pay_total").value; 
        var action = document.getElementById("action").value; 
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
        }else if(supplier_id.length == 0){
            alert("Please input supplier");
            document.getElementById("supplier_id").focus();
            return false;
        }else{ 
            if(action == 'edit'){
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
                                $(row_update_id).closest('tr').children('td').children('input[name="check_pay_id[]"]').val(data.check_pay_id);
                                $(row_update_id).closest('tr').children('td').children('input[name="finance_credit_pay_total[]"]').val(data.check_pay_total);
                                $(row_update_id).closest('tr').children('td').children('input[name="finance_credit_pay_date[]"]').val(data.check_pay_date);
                                $(row_update_id).closest('tr').children('td').children('div').children('div').children('div').children('select[name="bank_account_id[]"]').val(data.bank_account_id);
                                var bank_account = bank_account_data.filter(val => val.bank_account_id == data.bank_account_id ); 

                                if(bank_account.length > 0){
                                    $(row_update_id).closest('tr').children('td').children('div').children('div').children('input[name="account_id[]"]').val(bank_account[0].account_id);
                                    $(row_update_id).closest('tr').children('td').children('div').children('div').children('input[name="finance_credit_pay_bank[]"]').val(bank_account[0].bank_account_name)
                                }

                                calculatePay();
                                $('.select').selectpicker('refresh');
                                $('#modalCheque').modal('hide');
                            }else{
                                alert("Can not update check payment. Please contact administrator");
                            }
                        }
                );
            } else if (action == 'add') {
                $.post( "controllers/insertChequePay.php", 
                        { 
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
                                console.log($(row_update_id).closest('tr').children('td').children('input[name="check_pay_id[]"]'));
                                $(row_update_id).closest('tr').children('td').children('input[name="check_pay_id[]"]').val(data.check_pay_id);
                                $(row_update_id).closest('tr').children('td').children('input[name="finance_credit_pay_total[]"]').val(data.check_pay_total);
                                $(row_update_id).closest('tr').children('td').children('input[name="finance_credit_pay_date[]"]').val(data.check_pay_date);
                                $(row_update_id).closest('tr').children('td').children('div').children('div').children('div').children('select[name="bank_account_id[]"]').val(data.bank_account_id); 
                                var bank_account = bank_account_data.filter(val => val.bank_account_id == data.bank_account_id ); 

                                if(bank_account.length > 0){
                                    $(row_update_id).closest('tr').children('td').children('div').children('div').children('input[name="account_id[]"]').val(bank_account[0].account_id);
                                    $(row_update_id).closest('tr').children('td').children('div').children('div').children('input[name="finance_credit_pay_bank[]"]').val(bank_account[0].bank_account_name)
                                }
                                calculatePay();
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
</script>

<form  >
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
                                    <input id="check_pay_date_write" name="check_pay_date_write" class="form-control calendar" type="text" value="" readonly />
                                    <p class="help-block">01-06-2018 </p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>เลขที่เช็ค <font color="#F00"><b>*</b></font></label>
                                    <input id="check_pay_code" name="check_pay_code" class="form-control" type="text" readonly />
                                    <p class="help-block">Example : QP4411555.</p>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>ผู้ขาย <font color="#F00"><b>*</b></font> </label>
                                    <select id="cheque_supplier_id" name="cheque_supplier_id" class="form-control select"  data-live-search="true">
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
                                        <option value="">Select</option>
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
                <input type="hidden" id="action" name="action" value="" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="cheque_delete" class="btn btn-danger" onclick="delete_check();" >Delete Cheque</button>
                <button type="button" id="cheque_submit" class="btn btn-primary" onclick="check_post();" >Add Cheque</button>
            </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>