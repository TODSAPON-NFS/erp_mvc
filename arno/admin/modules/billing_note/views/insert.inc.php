<script>
    var total_old = 0.0;
    var data_buffer = [];
    function check(){

        var customer_id = document.getElementById("customer_id").value;
        var billing_note_code = document.getElementById("billing_note_code").value;
        var billing_note_date = document.getElementById("billing_note_date").value;
        var employee_id = document.getElementById("employee_id").value;

        
        customer_id = $.trim(customer_id);
        billing_note_code = $.trim(billing_note_code);
        billing_note_date = $.trim(billing_note_date);
        employee_id = $.trim(employee_id);

        if(customer_id.length == 0){
            alert("Please input iupplier.");
            document.getElementById("customer_id").focus();
            return false;
        }else if(billing_note_code.length == 0){
            alert("Please input Billing Note date.");
            document.getElementById("billing_note_code").focus();
            return false;
        }else if(billing_note_date.length == 0){
            alert("Please input Billing Note date.");
            document.getElementById("billing_note_date").focus();
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
            document.getElementById('billing_note_branch').value = data.customer_branch;
            document.getElementById('billing_note_name').value = data.customer_name_en;
            document.getElementById('billing_note_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
            document.getElementById('billing_note_tax').value = data.customer_tax ;
        });
    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
     }




    function show_invoice_customer(id){
        var customer_id = document.getElementById('customer_id').value;
        var val = document.getElementsByName('invoice_customer_id[]');
        var invoice_customer_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            invoice_customer_id.push(val[i].value);
        }
        
        if(customer_id != ""){

            $.post( "controllers/getBillingNoteListByCustomerID.php", { 'customer_id': customer_id, 'invoice_customer_id': JSON.stringify(invoice_customer_id) }, function( data ) {
               //alert(data);
                $('#bodyAdd').html(data);
                $('#modalAdd').modal('show');
               
                if(data.length > 0){
                    data_buffer = data;
                    var content = "";
                    for(var i = 0; i < data.length ; i++){

                        var billing_note_list_amount = parseFloat(data[i].billing_note_list_amount);
                        var billing_note_list_paid = parseFloat(data[i].billing_note_list_paid); 

                        content += '<tr class="odd gradeX">'+
                                    '<td>'+
                                        '<input type="checkbox" name="p_id" value="'+data[i].invoice_customer_id+'" />'+     
                                    '</td>'+
                                    '<td>'+
                                        data[i].invoice_customer_code+
                                    '</td>'+
                                    '<td>'+
                                        data[i].billing_note_list_date+
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].billing_note_list_due +
                                    '</td>'+
                                    '<td align="right">'+
                                         billing_note_list_amount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +
                                    '</td>'+
                                    '<td align="right">'+
                                         billing_note_list_paid.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +
                                    '</td>'+
                                    '<td align="right">'+
                                        ( billing_note_list_amount -  billing_note_list_paid).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +
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
        var val = document.getElementsByName('invoice_customer_id[]');
        var invoice_customer_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            invoice_customer_id.push(val[i].value);
        }

        $.post( "controllers/getBillingNoteListByCustomerID.php", { 'customer_id': customer_id, 'invoice_customer_id': JSON.stringify(invoice_customer_id), search : $(id).val() }, function( data ) {
            var content = "";
            if(data.length > 0){
                data_buffer = data;
                
                for(var i = 0; i < data.length ; i++){

                    var billing_note_list_amount = parseFloat(data[i].billing_note_list_amount);
                    var billing_note_list_paid = parseFloat(data[i].billing_note_list_paid); 

                    content += '<tr class="odd gradeX">'+
                                    '<td>'+
                                        '<input type="checkbox" name="p_id" value="'+data[i].invoice_customer_id+'" />'+     
                                    '</td>'+
                                    '<td>'+
                                        data[i].invoice_customer_code+
                                    '</td>'+
                                    '<td>'+
                                        data[i].billing_note_list_date+
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].billing_note_list_due +
                                    '</td>'+
                                    '<td align="right">'+
                                         billing_note_list_amount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +
                                    '</td>'+
                                    '<td align="right">'+
                                         billing_note_list_paid.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +
                                    '</td>'+
                                    '<td align="right">'+
                                        ( billing_note_list_amount -  billing_note_list_paid).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +
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

                var billing_note_list_amount = parseFloat(data_buffer[i].billing_note_list_amount);
                var billing_note_list_paid = parseFloat(data_buffer[i].billing_note_list_paid); 

                $(id).closest('table').children('tbody').append(
                    '<tr class="odd gradeX">'+
                        '<td>'+  
                            '<input type="hidden" name="billing_note_list_id[]" value="0" />'+
                            '<input type="hidden" name="invoice_customer_id[]" value="'+data_buffer[i].invoice_customer_id+'" />'+
                            data_buffer[i].invoice_customer_code +
                        '</td>'+
                        '<td>'+
                            data_buffer[i].billing_note_list_date + 
                        '</td>'+
                        '<td>'+
                            data_buffer[i].billing_note_list_due + 
                        '</td>'+
                        '<td align="right">'+
                            '<input type="text" class="form-control" name="billing_note_list_amount[]" style="text-align:right" onchange="update_sum(this);" value="'+billing_note_list_amount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'" />'+
                        '</td>'+
                        '<td align="right">'+
                        '<input type="text" class="form-control" name="billing_note_list_paid[]" style="text-align:right" onchange="update_sum(this);" value="'+billing_note_list_paid.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'" />'+
                        '</td>'+
                        '<td align="right">'+
                        '<input type="text" class="form-control" name="billing_note_list_balance[]" style="text-align:right" onchange="update_sum(this);" value="'+(billing_note_list_amount - billing_note_list_paid).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'" readonly />'+
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

        var amount =  parseFloat($(id).closest('tr').children('td').children('input[name="billing_note_list_amount[]"]').val(  ).replace(',',''));
        var paid =  parseFloat($(id).closest('tr').children('td').children('input[name="billing_note_list_paid[]"]').val( ).replace(',',''));
        var balance =  parseFloat($(id).closest('tr').children('td').children('input[name="billing_note_list_balance[]"]').val( ).replace(',',''));

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

        $(id).closest('tr').children('td').children('input[name="billing_note_list_amount[]"]').val( amount.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="billing_note_list_paid[]"]').val( paid.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="billing_note_list_balance[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();


    }

    function calculateAll(){

        var val = document.getElementsByName('billing_note_list_balance[]');
        var total = 0.0;
        
        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }
       
        $('#billing_note_total').val((total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
    }





</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Billing Note Management</h1>
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
            เพิ่มใบวางบิล / Add Billing Note  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=billing_note&action=add" >
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
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ชื่อตามใบวางบิล / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="billing_note_name" name="billing_note_name" class="form-control" value="<?php echo $customer['customer_name_en'];?>" >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>สาขา / Branch <font color="#F00"><b>*</b></font></label>
                                        <input  id="billing_note_branch" name="billing_note_branch" class="form-control" value="<?php echo $customer['customer_branch'];?>" >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="billing_note_address" name="billing_note_address" class="form-control" rows="5" ><?php echo $customer['customer_address_1'] ."\n". $customer['customer_address_2'] ."\n". $customer['customer_address_3'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <input  id="billing_note_tax" name="billing_note_tax" class="form-control" value="<?php echo $customer['customer_tax'];?>" >
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
                                        <label>วันที่ออกใบวางบิล / Date</label>
                                        <input type="text" id="billing_note_date" name="billing_note_date"  class="form-control calendar" value="<?PHP echo $first_date?>" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบวางบิล / CN code <font color="#F00"><b>*</b></font></label>
                                        <input id="billing_note_code" name="billing_note_code" class="form-control" value="<?php echo $last_code;?>" >
                                        <p class="help-block">Example : CN1801001.</p>
                                    </div>
                                </div>
                                

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ออกใบวางบิล / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option <?PHP if($admin_id == $users[$i]['user_id']){?> SELECTED <?PHP }?> value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>

                                 <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark </label>
                                        <textarea id="billing_note_remark" name="billing_note_remark" class="form-control" ><?php echo $billing_note['billing_note_remark'];?></textarea>
                                        <p class="help-block">Example : -.</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div> 

                     <div>
                    Our reference :
                    </div>
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">หมายใบกำกับภาษี <br> (Invoice Number)</th>
                                <th style="text-align:center;">วันที่ออก <br> (Date)</th>
                                <th style="text-align:center;" width="150">กำหนดชำระ <br> (Due Date)</th>
                                <th style="text-align:center;" width="150">จำนวนเงิน <br> (Amount) </th>
                                <th style="text-align:center;" width="150">ชำระแล้ว <br> (Paid)</th>
                                <th style="text-align:center;" width="150">ยอดชำระคงเหลือ <br> (Balance)</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($billing_note_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="billing_note_list_id[]" value="<?PHP echo  $billing_note_lists[$i]['billing_note_list_id'];?>" />
                                    <input type="hidden" name="invoice_customer_id[]" value="<?PHP echo  $billing_note_lists[$i]['invoice_customer_id'];?>" />
                                    <?PHP echo  $billing_note_lists[$i]['invoice_customer_code'];?>
                                </td>
                                <td>
                                    <?PHP echo  $billing_note_lists[$i]['invoice_customer_date'];?>
                                </td>
                                <td>
                                    <?PHP echo  $billing_note_lists[$i]['invoice_customer_due'];?>
                                </td>
                                <td align="right">
                                    <input type="text" class="form-control" name="billing_note_list_amount[]" style="text-align:right" onchange="update_sum(this);" value="<?PHP echo  number_format($billing_note_lists[$i]['billing_note_list_amount'],2);?>" />
                                </td>
                                <td  align="right">
                                    <input type="text" class="form-control" name="billing_note_list_paid[]" style="text-align:right" onchange="update_sum(this);" value="<?PHP echo  number_format($billing_note_lists[$i]['billing_note_list_paid'],2);?>" />
                                </td>
                                <td align="right">
                                    <input type="text" class="form-control" name="billing_note_list_balance[]" style="text-align:right" onchange="update_sum(this);" value="<?PHP echo  number_format($billing_note_lists[$i]['billing_note_list_amount'] - $billing_note_lists[$i]['billing_note_list_paid'],2);?>" readonly/>
                                </td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $billing_note_lists[$i]['billing_note_list_amount'] - $billing_note_lists[$i]['billing_note_list_paid'];
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="7" align="center">
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
                                <td colspan="3"></td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="billing_note_total" name="billing_note_total" value="<?PHP echo number_format($total,2) ;?>" readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=billing_note" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button type="button" onclick="check_login('form_target');" class="btn btn-success">Save</button>
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