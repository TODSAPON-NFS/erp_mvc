<script>
    var row_update_id ;
    var options = {
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

    var finance_debit_account_data = [
    <?php for($i = 0 ; $i < count($finance_debit_accounts) ; $i++ ){?>
        {
            finance_debit_account_id:'<?php echo $finance_debit_accounts[$i]['finance_debit_account_id'];?>',
            finance_debit_account_code:'<?php echo $finance_debit_accounts[$i]['finance_debit_account_code'];?>',
            finance_debit_account_name:'<?php echo $finance_debit_accounts[$i]['finance_debit_account_name'];?>',
            finance_debit_account_cheque:'<?php echo $finance_debit_accounts[$i]['finance_debit_account_cheque'];?>',
            bank_account_id:'<?php echo $finance_debit_accounts[$i]['bank_account_id'];?>',
            account_id:'<?php echo $finance_debit_accounts[$i]['account_id'];?>'
        },
    <?php }?>
    ];
    
    var total_old = 0.0;
    var data_buffer = [];
    function check(){

        var customer_id = document.getElementById("customer_id").value;
        var finance_debit_code = document.getElementById("finance_debit_code").value;
        var finance_debit_date = document.getElementById("finance_debit_date").value;
        var employee_id = document.getElementById("employee_id").value;

        
        customer_id = $.trim(customer_id);
        finance_debit_code = $.trim(finance_debit_code);
        finance_debit_date = $.trim(finance_debit_date);
        employee_id = $.trim(employee_id);

        if(customer_id.length == 0){
            alert("Please input iupplier.");
            document.getElementById("customer_id").focus();
            return false;
        }else if(finance_debit_code.length == 0){
            alert("Please input Finance Debit date.");
            document.getElementById("finance_debit_code").focus();
            return false;
        }else if(finance_debit_date.length == 0){
            alert("Please input Finance Debit date.");
            document.getElementById("finance_debit_date").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else{
            var total = parseFloat($('#finance_debit_total').val().toString().replace(new RegExp(',', 'g'),''));
            var paid = parseFloat($('#finance_debit_pay').val().toString().replace(new RegExp(',', 'g'),''));

            if (total == paid){
                return true;
            }else if(confirm("เอกสารหมายเลข "+finance_debit_code+" ฉบับนี้ยังไม่สมบูรณ์เนื่องจากจำนวนเงินที่รับจริง ไม่ตรงกับจำนวนเงินที่ต้องรับ")){
                return true;
            }else{
                return false;
            }
        }



    }

    function generate_code(id){
        var payment = $(id).val();
        var finance_debit_account = finance_debit_account_data.filter(val => val.finance_debit_account_id == payment );

        
        if(finance_debit_account.length > 0){
            $(id).closest('tr').children('td').children('input[name="finance_debit_account_cheque[]"]').val(finance_debit_account[0].finance_debit_account_cheque);
            if(finance_debit_account[0].finance_debit_account_cheque == 1){
                $.post( "controllers/getChequeCodeIndex.php", {  }, function( data ) { 
                    $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_debit_pay_by[]"]').val(data);
                    $(id).closest('tr').children('td').children('div').children('div').children('div').children('input[name="finance_debit_pay_by[]"]').val(data);
                    $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_debit_pay_by[]"]').easyAutocomplete(options);
                    get_cheque_data(id,data);
                }); 
            }else{ 
                $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_debit_pay_by[]"]').val(finance_debit_account[0].finance_debit_account_code); 
                $(id).closest('tr').children('td').children('div').children('div').children('div').children('input[name="finance_debit_pay_by[]"]').val(finance_debit_account[0].finance_debit_account_code); 
                
                $(id).closest('tr').children('td').children('div').children('div').children('div').children('select[name="bank_account_id[]"]').val(finance_debit_account[0].bank_account_id);
                
                $(id).closest('tr').children('td').children('div').children('div').children('input[name="account_id[]"]').val(finance_debit_account[0].account_id);
                $('.select').selectpicker('refresh');

                var bank_account = bank_account_data.filter(val => val.bank_account_id == finance_debit_account[0].bank_account_id );
                if(bank_account.length > 0){
                    $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_debit_pay_bank[]"]').val(bank_account[0].bank_account_name);
                }else{
                    $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_debit_pay_bank[]"]').val('')
                }

            }
        }

    }

    function get_bank_account_name (id){ 
        var bank_account_id = $(id).val();
        var bank_account = bank_account_data.filter(val => val.bank_account_id == bank_account_id );

        
        var payment = $(id).closest('tr').children('td').children('div').children('div').children('select[name="finance_debit_account_id[]"]').val();
        var finance_debit_account = finance_debit_account_data.filter(val => val.finance_debit_account_id == payment );

        if(bank_account.length > 0){
            $(id).closest('tr').children('td').children('div').children('div').children('input[name="account_id[]"]').val(bank_account[0].account_id);
            $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_debit_pay_bank[]"]').val(bank_account[0].bank_account_name)
        }else{
            $(id).closest('tr').children('td').children('div').children('div').children('input[name="finance_debit_pay_bank[]"]').val('')
            $(id).closest('tr').children('td').children('div').children('div').children('input[name="account_id[]"]').val(bank_account[0].account_id);
        }
    }


    function get_customer_detail(){
        var customer_id = document.getElementById('customer_id').value;
        $.post( "controllers/getCustomerByID.php", { 'customer_id': customer_id }, function( data ) {
            document.getElementById('customer_code').value = data.customer_code;
            document.getElementById('finance_debit_name').value = data.customer_name_en  ;
            document.getElementById('finance_debit_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3 +' '+ data.customer_zipcode + '\nTel.'+ data.customer_tel+' Fax. '+data.customer_fax;
            document.getElementById('finance_debit_tax').value = data.customer_tax ;
        });
    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
        calculateAll();
        calculatePay();
     }




    function show_invoice_customer(id){
        var customer_id = document.getElementById('customer_id').value;
        var val = document.getElementsByName('billing_note_list_id[]');
        var billing_note_list_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            billing_note_list_id.push(val[i].value);
        }
        
        if(customer_id != ""){

            $.post( "controllers/getFinanceDebitListByCustomerID.php", { 'customer_id': customer_id, 'billing_note_list_id': JSON.stringify(billing_note_list_id) }, function( data ) {
               
                if(data.length > 0){
                    document.getElementById("check_all").checked = false;
                    data_buffer = data;
                    var content = "";
                    for(var i = 0; i < data.length ; i++){

                        content += '<tr class="odd gradeX">'+
                                        '<td>'+
                                            '<input type="checkbox" name="p_id" value="'+data[i].billing_note_list_id+'" />'+     
                                        '</td>'+
                                        '<td>'+
                                            data[i].invoice_customer_code+
                                        '</td>'+
                                        '<td>'+
                                            data[i].finance_debit_list_date+
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].finance_debit_list_due +
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].finance_debit_list_amount +
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].finance_debit_list_paid +
                                        '</td>'+
                                        '<td align="right">'+
                                            (data[i].finance_debit_list_amount - data[i].finance_debit_list_paid) +
                                        '</td>'+
                                    '</tr>';

                    }
                    $('#bodyAdd').html(content);
                    $('#modalAdd').modal('show');

                }
                
                
            });
        }else{
            alert("Please select Customer.");
        }
        
    } 

    function search_pop_like(id){
        var customer_id = document.getElementById('customer_id').value;
        var val = document.getElementsByName('billing_note_list_id[]');
        var billing_note_list_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            billing_note_list_id.push(val[i].value);
        }

        $.post( "controllers/getFinanceDebitListByCustomerID.php", { 'customer_id': customer_id, 'billing_note_list_id': JSON.stringify(billing_note_list_id), search : $(id).val() }, function( data ) {
            var content = "";
            document.getElementById("check_all").checked = false;
            console.log(data);
            if(data.length > 0){
                data_buffer = data;
                
                for(var i = 0; i < data.length ; i++){

                    content += '<tr class="odd gradeX">'+
                                    '<td>'+
                                        '<input type="checkbox" name="p_id" value="'+data[i].billing_note_list_id+'" />'+     
                                    '</td>'+
                                    '<td>'+
                                        data[i].invoice_customer_code+
                                    '</td>'+
                                    '<td>'+
                                        data[i].finance_debit_list_date+
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].finance_debit_list_due +
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].finance_debit_list_amount +
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].finance_debit_list_paid +
                                    '</td>'+
                                    '<td align="right">'+
                                        (data[i].finance_debit_list_amount - data[i].finance_debit_list_paid) +
                                    '</td>'+
                                '</tr>';

                }
            }
            $('#bodyAdd').html(content);
            
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
                            '<input type="hidden" name="finance_debit_list_id[]" value="0" />'+
                            '<input type="hidden" name="billing_note_list_id[]" value="'+data_buffer[i].billing_note_list_id+'" />'+
                            '<input type="text" class="form-control"  value="'+data_buffer[i].invoice_customer_code+'" readonly />'+ 
                            '<input type="text" class="form-control" name="finance_debit_list_remark[]" />'+
                        '</td>'+
                        '<td align="center">'+
                            data_buffer[i].finance_debit_list_date + 
                        '</td>'+
                        '<td align="center">'+
                            data_buffer[i].finance_debit_list_due + 
                        '</td>'+
                        '<td align="right">'+
                            '<input type="text" class="form-control" name="finance_debit_list_billing[]" value="'+data_buffer[i].billing_note_code+'" />'+
                        '</td>'+
                        '<td align="right">'+
                            '<input type="text" class="form-control" name="finance_debit_list_receipt[]" value="'+data_buffer[i].official_receipt_code+'" />'+
                        '</td>'+
                        '<td align="right">'+
                            '<input type="text" class="form-control" name="finance_debit_list_amount[]" style="text-align:right" onchange="update_sum(this);" value="'+data_buffer[i].finance_debit_list_amount+'" />'+
                        '</td>'+
                        '<td align="right">'+
                        '<input type="text" class="form-control" name="finance_debit_list_paid[]" style="text-align:right" onchange="update_sum(this);" value="'+data_buffer[i].finance_debit_list_paid+'" />'+
                        '</td>'+
                        '<td align="right">'+
                        '<input type="text" class="form-control" name="finance_debit_list_balance[]" style="text-align:right" onchange="update_sum(this);" value="'+(data_buffer[i].finance_debit_list_amount - data_buffer[i].finance_debit_list_paid)+'"  />'+
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

        var amount =  parseFloat($(id).closest('tr').children('td').children('input[name="finance_debit_list_amount[]"]').val(  ).replace(',',''));
        var paid =  parseFloat($(id).closest('tr').children('td').children('input[name="finance_debit_list_paid[]"]').val( ).replace(',',''));
        var balance =  parseFloat($(id).closest('tr').children('td').children('input[name="finance_debit_list_balance[]"]').val( ).replace(',',''));

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

        $(id).closest('tr').children('td').children('input[name="finance_debit_list_amount[]"]').val( amount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="finance_debit_list_paid[]"]').val( paid.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        //$(id).closest('tr').children('td').children('input[name="finance_debit_list_balance[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

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
                    '<input type="hidden" class="form-control" name="finance_debit_pay_id[]" value="0" />'+ 
                    '<input type="hidden" class="form-control" name="check_id[]" value="0" />'+ 
                    '<input type="hidden" class="form-control" name="finance_debit_account_cheque[]" value="0" />'+ 
                    '<div class="row">'+ 
                        '<div class="col-md-6">'+ 
                            '<select  name="finance_debit_account_id[]" onchange="generate_code(this);" class="form-control select" data-live-search="true">'+ 
                                '<option value="">Select</option>'+ 
                            '</select>'+ 
                        '</div>'+ 
                        '<div class="col-md-6">'+ 
                            '<input type="text" class="form-control" name="finance_debit_pay_by[]" value="" onchange="get_cheque_id(this)" />'+ 
                        '</div>'+ 
                    '</div> '+ 
                '</td>'+ 
                '<td  style="max-width:100px;" >' +
                '<input type="text" class="form-control calendar"  name="finance_debit_pay_date[]"  readonly/>'+
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
                            '<input type="text" class="form-control" name="finance_debit_pay_bank[]" value="" />'+
                        '</div>'+
                    '</div>'+
                '</td>'+
                '<td  style="max-width:100px;" ><input type="text" style="text-align:right;" class="form-control" name="finance_debit_pay_value[]"   onchange="calculatePay()" /></td>'+
                '<td  style="max-width:100px;" ><input type="text" style="text-align:right;" class="form-control" name="finance_debit_pay_balance[]"  onchange="calculatePay()"  /></td>'+
                '<td  style="max-width:100px;" ><input type="text" style="text-align:right;" class="form-control" name="finance_debit_pay_total[]" onchange="calculatePay()"   /></td>'+
                
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="finance_debit_account_id[]"]').empty();
        var str = "<option value=''>เลือกวิธีการจ่ายเงิน</option>";
        $.each(finance_debit_account_data, function (index, value) {
            str += "<option value='" + value['finance_debit_account_id'] + "'>["+value['finance_debit_account_code']+"] " +  value['finance_debit_account_name'] + "</option>";
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="finance_debit_account_id[]"]').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="finance_debit_account_id[]"]').selectpicker();




        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="bank_account_id[]"]').empty();
        var str = "<option value=''>เลือกบัญชีธนาคาร</option>";
        $.each(bank_account_data, function (index, value) {
            str += "<option value='" + value['bank_account_id'] + "'>["+value['bank_account_code']+"] " +  value['bank_account_name'] + "</option>";
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="bank_account_id[]"]').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="bank_account_id[]"]').selectpicker();



        $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="finance_debit_pay_date[]"]').datepicker({ dateFormat: 'dd-mm-yy' });
                
                

    }

    function calculateAll(){

        var val = document.getElementsByName('finance_debit_list_balance[]');
        var total = 0.0;
        
        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }
       
        $('#finance_debit_total').val((total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
    }

    function calculatePay(){
        
        var value = document.getElementsByName('finance_debit_pay_value[]');
        var balance = document.getElementsByName('finance_debit_pay_balance[]');
        var total = document.getElementsByName('finance_debit_pay_total[]');

        var finance_debit_total = parseFloat(document.getElementById('finance_debit_total').value.toString().replace(new RegExp(',', 'g'),''));
        var cash = document.getElementById('finance_debit_cash');
        var interest = document.getElementById('finance_debit_interest');
        var tax_pay = document.getElementById('finance_debit_tax_pay');
        var discount_cash = document.getElementById('finance_debit_discount_cash');

        var sum_total = 0.0;

        var val_cash = parseFloat(cash.value.toString().replace(new RegExp(',', 'g'),''));
        var val_interest = parseFloat(interest.value.toString().replace(new RegExp(',', 'g'),''));
        var val_tax_pay = parseFloat(tax_pay.value.toString().replace(new RegExp(',', 'g'),''));
        var val_discount_cash = parseFloat(discount_cash.value.toString().replace(new RegExp(',', 'g'),''));


        if(isNaN(finance_debit_total)){    
            finance_debit_total = 0;
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

        
        document.getElementById('finance_debit_other_pay').value = sum_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") ;
        cash.value = val_cash.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        interest.value = val_interest.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        tax_pay.value = val_tax_pay.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        discount_cash.value = val_discount_cash.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");

        var finance_debit_pay =  val_cash + sum_total - val_interest + val_tax_pay + val_discount_cash;
        //console.log(finance_debit_pay);
        document.getElementById('finance_debit_pay').value = finance_debit_pay.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") ;

    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Finance Debit Management</h1>
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
            เพิ่มใบรับชำระหนี้ / Add Finance Debit  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=finance_debit&action=edit&id=<?PHP echo $finance_debit_id;?>" >
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ซื้อ / Customer Code <font color="#F00"><b>*</b></font></label>
                                        <input id="customer_code" name="customer_code" class="form-control" value="<? echo $customer['customer_code'];?>" readonly>
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ผู้ซื้อ / Customer  <font color="#F00"><b>*</b></font> </label>
                                        <select id="customer_id" name="customer_id" class="form-control select" onchange="get_customer_detail()" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($customers) ; $i++){
                                            ?>
                                            <option <?php if($customers[$i]['customer_id'] == $customer['customer_id']){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?>  </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อตามใบรับชำระหนี้ / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="finance_debit_name" name="finance_debit_name" class="form-control" value="<?php echo $finance_debit['finance_debit_name'];?>  " >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="finance_debit_address" name="finance_debit_address" class="form-control" rows="5" ><?php echo $finance_debit['finance_debit_address'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <input  id="finance_debit_tax" name="finance_debit_tax" class="form-control" value="<?php echo $finance_debit['finance_debit_tax'];?>" >
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
                                        <label>วันที่ออกใบรับชำระหนี้ / Date</label>
                                        <input type="text" id="finance_debit_date" name="finance_debit_date"  class="form-control calendar" value="<?PHP echo $finance_debit['finance_debit_date']?>" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบรับชำระหนี้ / CN code <font color="#F00"><b>*</b></font></label>
                                        <input id="finance_debit_code" name="finance_debit_code" class="form-control" value="<?PHP echo $finance_debit['finance_debit_code']?>" >
                                        <p class="help-block">Example : CN1801001.</p>
                                    </div>
                                </div>
                                

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ออกใบรับชำระหนี้ / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option <?PHP if($finance_debit['employee_id'] == $users[$i]['user_id']){?> SELECTED <?PHP }?> value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
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
                                <th style="text-align:center;"  width="150">หมายใบกำกับภาษี <br> (Invoice Number)</th>
                                <th style="text-align:center;" width="150">วันที่ออก <br> (Date)</th>
                                <th style="text-align:center;" width="150">กำหนดชำระ <br> (Due Date)</th>
                                <th style="text-align:center;" >ใบรับวางบิล <br> (Billing Note)</th>
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
                            for($i=0; $i < count($finance_debit_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="finance_debit_list_id[]" value="<?PHP echo  $finance_debit_lists[$i]['finance_debit_list_id'];?>" />
                                    <input type="hidden" name="billing_note_list_id[]" value="<?PHP echo  $finance_debit_lists[$i]['billing_note_list_id'];?>" />
                                    <input type="text" class="form-control" value="<?PHP echo  $finance_debit_lists[$i]['invoice_customer_code'];?>" readonly />
                                    <input type="text" class="form-control" name="finance_debit_list_remark[]" />
                                </td>
                                <td align="center">
                                    <?PHP echo  $finance_debit_lists[$i]['finance_debit_list_date'];?>
                                </td>
                                <td align="center">
                                    <?PHP echo  $finance_debit_lists[$i]['finance_debit_list_due'];?>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="finance_debit_list_billing[]" value="<?PHP echo $finance_debit_lists[$i]['billing_note_code']; ?>" />
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="finance_debit_list_receipt[]" value="<?PHP echo $finance_debit_lists[$i]['official_receipt_code']; ?>" />
                                </td>
                                <td align="right">
                                    <input type="text" class="form-control" name="finance_debit_list_amount[]" style="text-align:right" onchange="update_sum(this);" value="<?PHP echo  number_format($finance_debit_lists[$i]['finance_debit_list_amount'],2);?>" />
                                </td>
                                <td  align="right">
                                    <input type="text" class="form-control" name="finance_debit_list_paid[]" style="text-align:right" onchange="update_sum(this);" value="<?PHP echo  number_format($finance_debit_lists[$i]['finance_debit_list_paid'],2);?>" />
                                </td>
                                <td align="right">
                                    <input type="text" class="form-control" name="finance_debit_list_balance[]" style="text-align:right" onchange="update_sum(this);" value="<?PHP echo  number_format($finance_debit_lists[$i]['finance_debit_list_amount'] - $finance_debit_lists[$i]['finance_debit_list_paid'],2);?>" />
                                </td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $finance_debit_lists[$i]['finance_debit_list_amount'] - $finance_debit_lists[$i]['finance_debit_list_paid'];
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="9" align="center">
                                    <a href="javascript:;" onclick="show_invoice_customer(this);" style="color:red;">
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
                                            <table width="100%" class="table table-striped table-bordered table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th width="24"><input type="checkbox" value="all" id="check_all" onclick="checkAll(this)" /></th>
                                                        <th style="text-align:center;">รหัสใบกำกับภาษี <br> (Invoice Number)</th>
                                                        <th style="text-align:center;">วันที่ออก <br> (Date)</th>
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
                                    <input type="text" class="form-control" style="text-align: right;" id="finance_debit_total" name="finance_debit_total" value="<?PHP echo number_format($total,2) ;?>" readonly/>
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
                            for($i=0; $i < count($finance_debit_pays); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" class="form-control" name="finance_debit_pay_id[]" value="<?php echo $finance_debit_pays[$i]['finance_debit_pay_id']; ?>" />
                                    <input type="hidden" class="form-control" name="check_id[]" value="<?php echo $finance_debit_pays[$i]['check_id']; ?>" />
                                    <input type="hidden" class="form-control" name="finance_debit_account_cheque[]" value="<?php echo $finance_debit_pays[$i]['finance_debit_account_cheque']; ?>" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select  name="finance_debit_account_id[]" onchange="generate_code(this);" class="form-control select" data-live-search="true">
                                                <option value="">Select</option>
                                                <?php 
                                                for($ii =  0 ; $ii < count($finance_debit_accounts) ; $ii++){
                                                ?>
                                                <option <?PHP if($finance_debit_pays[$i]['finance_debit_account_id'] == $finance_debit_accounts[$ii]['finance_debit_account_id']){?> SELECTED <?PHP }?> value="<?php echo $finance_debit_accounts[$ii]['finance_debit_account_id'] ?>">[<?php echo $finance_debit_accounts[$ii]['finance_debit_account_code'] ?>] <?php echo $finance_debit_accounts[$ii]['finance_debit_account_name'] ?> </option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="finance_debit_pay_by[]" value="<?php echo $finance_debit_pays[$i]['finance_debit_pay_by']; ?>"   onchange="get_cheque_id(this)" />
                                        </div>
                                    </div> 
                                </td>
                                <td>
                                    <input type="text" class="form-control calendar" name="finance_debit_pay_date[]" value="<?php echo $finance_debit_pays[$i]['finance_debit_pay_date']; ?>" readonly/>
                                </td> 
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" name="account_id[]"  value="<?php echo $finance_debit_pays[$i]['account_id']; ?>" />
                                            <select  name="bank_account_id[]" onchange="get_bank_account_name(this);" class="form-control select" data-live-search="true">
                                                <option value="">Select</option>
                                                <?php 
                                                for($ii =  0 ; $ii < count($bank_accounts) ; $ii++){
                                                ?>
                                                <option <?PHP if($finance_debit_pays[$i]['bank_account_id'] == $bank_accounts[$ii]['bank_account_id']){?> SELECTED <?PHP }?> value="<?php echo $bank_accounts[$ii]['bank_account_id'] ?>">[<?php echo $bank_accounts[$ii]['bank_account_code'] ?>] <?php echo $bank_accounts[$ii]['bank_account_name'] ?> </option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="finance_debit_pay_bank[]" value="<?php echo $finance_debit_pays[$i]['finance_debit_pay_bank']; ?>" />
                                        </div>
                                    </div>
                                </td> 
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="finance_debit_pay_value[]" value="<?php echo number_format($finance_debit_pays[$i]['finance_debit_pay_value'],2); ?>"  onchange="calculatePay()" /></td>
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="finance_debit_pay_balance[]" value="<?php echo number_format($finance_debit_pays[$i]['finance_debit_pay_balance'],2); ?>"  onchange="calculatePay()" /></td>
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="finance_debit_pay_total[]" value="<?php echo number_format($finance_debit_pays[$i]['finance_debit_pay_total'],2); ?>"  onchange="calculatePay()"  /></td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $finance_debit_pays[$i]['finance_debit_pay_total'];
                            }
                            

                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="7" align="center">
                                    <a href="javascript:;" onclick="add_row_pay(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มรายการรับเงิน / Add pay list</span>
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <br>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ดอกเบี้ย</label>
                                <input id="finance_debit_interest" name="finance_debit_interest" style="text-align:right;" class="form-control" value="<?PHP echo number_format($finance_debit['finance_debit_interest'],2);?>" onchange="calculatePay()" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>  
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>เงินสด</label>
                                <input id="finance_debit_cash" name="finance_debit_cash" style="text-align:right;" class="form-control" value="<?PHP echo number_format($finance_debit['finance_debit_cash'],2);?>" onchange="calculatePay()" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ชำระโดย (ด้านบน)</label>
                                <input id="finance_debit_other_pay" name="finance_debit_other_pay" style="text-align:right;" class="form-control" value="<?PHP echo number_format($total,2);?>" readonly >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ภาษีหัก ณ ที่รับ</label>
                                <input id="finance_debit_tax_pay" name="finance_debit_tax_pay"  style="text-align:right;" class="form-control" value="<?PHP echo number_format($finance_debit['finance_debit_tax_pay'],2);?>" onchange="calculatePay()" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ส่วนลดเงินสด</label>
                                <input id="finance_debit_discount_cash" name="finance_debit_discount_cash" style="text-align:right;" class="form-control" value="<?PHP echo number_format($finance_debit['finance_debit_discount_cash'],2);?>" onchange="calculatePay()" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ยอดรับจริง</label>
                                <input id="finance_debit_pay" name="finance_debit_pay" style="text-align:right;" class="form-control" value="<?PHP echo number_format($finance_debit['finance_debit_pay'],2);?>" readonly>
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                    </div>

                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=finance_debit" class="btn btn-default">Back</a>
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
    $.post( "controllers/getChequeByCode.php", { 'check_code': code }, function( data ) {
        console.log(data);
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
            $('#action').val('edit');
            $('#cheque_delete').show();

            $('.select').selectpicker('refresh');
            $('#modalCheque').modal('show');

        }else{ 
            $('#check_id').val('0');
            $('#check_code').val(code);  
            $('#check_date_write').val($('#finance_debit_date').val()); 
            $('#check_date_recieve').val($('#check_date_recieve').val());
            $('#cheque_customer_id').val($('#customer_id').val());
            $('#bank_id').val();
            $('#bank_branch').val();
            $('#check_date').val($('#finance_debit_date').val());
            $('#check_total').val($('#finance_debit_total').val());
            $('#check_remark').val("จ่ายหนี้ให้ " + $('#finance_debit_name').val());

            $('#check_submit').html('Add Cheque');
            $('#action').val('add');
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
                $(row_update_id).closest('tr').children('td').children('input[name="check_id[]"]').val(0);
                $(row_update_id).closest('tr').children('td').children('input[name="finance_debit_pay_by[]"]').val('');
                $('#modalCheque').modal('hide');
            }else{
                alert("Can not delete check payment. Please contact administrator");
            }
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
    var action = document.getElementById("action").value; 
    var check_id = document.getElementById("check_id").value; 
    var lastupdate = '<?PHP echo $admin_id?>';
    var addby = '<?PHP echo $admin_id?>';

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
    }else if(customer_id.length == 0){
        alert("Please input customer");
        document.getElementById("customer_id").focus();
        return false;
    }else{ 
        if(action == 'edit'){
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
                            $(row_update_id).closest('tr').children('td').children('input[name="check_id[]"]').val(data.check_id);
                            $(row_update_id).closest('tr').children('td').children('input[name="finance_debit_pay_total[]"]').val(data.check_total);
                            $(row_update_id).closest('tr').children('td').children('input[name="finance_debit_pay_date[]"]').val(data.check_date_recieve);
                            $(row_update_id).closest('tr').children('td').children('div').children('div').children('div').children('select[name="bank_account_id[]"]').val(data.bank_account_id);
                            var bank_account = bank_account_data.filter(val => val.bank_account_id == data.bank_account_id ); 

                            if(bank_account.length > 0){
                                $(row_update_id).closest('tr').children('td').children('div').children('div').children('input[name="account_id[]"]').val(bank_account[0].account_id);
                                $(row_update_id).closest('tr').children('td').children('div').children('div').children('input[name="finance_debit_pay_bank[]"]').val(bank_account[0].bank_account_name)
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
                            console.log($(row_update_id).closest('tr').children('td').children('input[name="check_id[]"]'));
                            $(row_update_id).closest('tr').children('td').children('input[name="check_id[]"]').val(data.check_id);
                            $(row_update_id).closest('tr').children('td').children('input[name="finance_debit_pay_total[]"]').val(data.check_total);
                            $(row_update_id).closest('tr').children('td').children('input[name="finance_debit_pay_date[]"]').val(data.check_date_recieve);
                            $(row_update_id).closest('tr').children('td').children('div').children('div').children('div').children('select[name="bank_account_id[]"]').val(data.bank_account_id); 
                            var bank_account = bank_account_data.filter(val => val.bank_account_id == data.bank_account_id ); 

                            if(bank_account.length > 0){
                                $(row_update_id).closest('tr').children('td').children('div').children('div').children('input[name="account_id[]"]').val(bank_account[0].account_id);
                                $(row_update_id).closest('tr').children('td').children('div').children('div').children('input[name="finance_debit_pay_bank[]"]').val(bank_account[0].bank_account_name)
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
                                <input id="check_date_write" name="check_date_write" class="form-control calendar" type="text" value="" readonly />
                                <p class="help-block">01-06-2018 </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>เลขที่เช็ค <font color="#F00"><b>*</b></font></label>
                                <input id="check_code" name="check_code" class="form-control" type="text" value="<?php echo $last_code;?>" >
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
                                    <option  value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> (<?php echo $customers[$i]['customer_name_th'] ?>)</option>
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
            <input type="hidden" id="action" name="action" value="" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="cheque_delete" class="btn btn-danger" onclick="delete_check();" >Delete Cheque</button>
            <button type="button" id="cheque_submit" class="btn btn-primary" onclick="check_post();" >Add Cheque</button>
        </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>