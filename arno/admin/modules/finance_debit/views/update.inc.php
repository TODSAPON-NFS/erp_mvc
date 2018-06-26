<script>
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
            return true;
        }



    }

    function get_customer_detail(){
        var customer_id = document.getElementById('customer_id').value;
        $.post( "controllers/getCustomerByID.php", { 'customer_id': customer_id }, function( data ) {
            document.getElementById('customer_code').value = data.customer_code;
            document.getElementById('finance_debit_name').value = data.customer_name_en +' (' + data.customer_name_th +')';
            document.getElementById('finance_debit_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
            document.getElementById('finance_debit_tax').value = data.customer_tax ;
        });
    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
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
                            '<input type="text" class="form-control" value="'+data_buffer[i].invoice_customer_code+'" readonly />'+ 
                            '<input type="text" class="form-control" name="finance_debit_list_remark[]" />'+
                        '</td>'+
                        '<td align="center">'+
                            data_buffer[i].finance_debit_list_date + 
                        '</td>'+
                        '<td align="center">'+
                            data_buffer[i].finance_debit_list_due + 
                        '</td>'+
                        '<td align="right">'+
                            '<input type="text" class="form-control" name="finance_debit_list_billing[]" value="'+data_buffer[i].billing_note_code+'"/>'+
                        '</td>'+
                        '<td align="right">'+
                            '<input type="text" class="form-control" name="finance_debit_list_receipt[]" value="'+data_buffer[i].official_receipt_code+'"/>'+
                        '</td>'+
                        '<td align="right">'+
                            '<input type="text" class="form-control" name="finance_debit_list_amount[]" style="text-align:right" onchange="update_sum(this);" value="'+data_buffer[i].finance_debit_list_amount+'" />'+
                        '</td>'+
                        '<td align="right">'+
                        '<input type="text" class="form-control" name="finance_debit_list_paid[]" style="text-align:right" onchange="update_sum(this);" value="'+data_buffer[i].finance_debit_list_paid+'" />'+
                        '</td>'+
                        '<td align="right">'+
                        '<input type="text" class="form-control" name="finance_debit_list_balance[]" style="text-align:right" onchange="update_sum(this);" value="'+(data_buffer[i].finance_debit_list_amount - data_buffer[i].finance_debit_list_paid)+'" readonly />'+
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

        $(id).closest('tr').children('td').children('input[name="finance_debit_list_amount[]"]').val( amount.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="finance_debit_list_paid[]"]').val( paid.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="finance_debit_list_balance[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

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
                '<td style="max-width:150px;" >'+
                    '<input type="hidden" name="finance_debit_pay_id[]" value="0" />'+  
                    '<input type="text" class="form-control"  name="finance_debit_pay_by[]"  />'+
                '</td>'+ 
                '<td  style="max-width:150px;" >' +
                '<input type="text" class="form-control calendar"  name="finance_debit_pay_date[]"  readonly/>'+
                '</td>'+ 
                '<td  style="max-width:150px;" ><input type="text" class="form-control" name="finance_debit_pay_bank[]"   /></td>'+
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
                                            <option <?php if($customers[$i]['customer_id'] == $customer['customer_id']){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> (<?php echo $customers[$i]['customer_name_th'] ?>)</option>
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
                                        <input  id="finance_debit_name" name="finance_debit_name" class="form-control" value="<?php echo $customer['customer_name_en'];?> (<?php echo $customer['customer_name_th'];?>)" >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="finance_debit_address" name="finance_debit_address" class="form-control" rows="5" ><?php echo $customer['customer_address_1'] ."\n". $customer['customer_address_2'] ."\n". $customer['customer_address_3'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <input  id="finance_debit_tax" name="finance_debit_tax" class="form-control" value="<?php echo $customer['customer_tax'];?>" >
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
                                <th style="text-align:center;">หมายใบกำกับภาษี <br> (Invoice Number)</th>
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
                                    <input type="text" class="form-control" name="finance_debit_list_balance[]" style="text-align:right" onchange="update_sum(this);" value="<?PHP echo  number_format($finance_debit_lists[$i]['finance_debit_list_amount'] - $finance_debit_lists[$i]['finance_debit_list_paid'],2);?>" readonly/>
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
                                    <input type="text" class="form-control" name="finance_debit_pay_by[]" value="<?php echo $finance_debit_pays[$i]['finance_debit_pay_by']; ?>" />
                                </td>
                                <td>
                                    <input type="text" class="form-control calendar" name="finance_debit_pay_date[]" value="<?php echo $finance_debit_pays[$i]['finance_debit_pay_date']; ?>" readonly/>
                                </td> 
                                <td>
                                    <input type="text" class="form-control" name="finance_debit_pay_bank[]" value="<?php echo $finance_debit_pays[$i]['finance_debit_pay_bank']; ?>" />
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