<script>
    <?PHP if($debit_note['debit_note_total_old'] > 0){
    ?>
        var total_old = <?php echo $debit_note['debit_note_total_old'];?>;
    <?PHP
    }else{
    ?>
    var total_old = 0.0;
    <?
    }
    ?>
    var invoice_customer = [];
    var data_buffer = [];
    function check(){

        var customer_id = document.getElementById("customer_id").value;
        var debit_note_code = document.getElementById("debit_note_code").value;
        var debit_note_date = document.getElementById("debit_note_date").value;
        var debit_note_term = document.getElementById("debit_note_term").value;
        var debit_note_due = document.getElementById("debit_note_due").value;
        var employee_id = document.getElementById("employee_id").value;

        
        customer_id = $.trim(customer_id);
        debit_note_code = $.trim(debit_note_code);
        debit_note_date = $.trim(debit_note_date);
        debit_note_term = $.trim(debit_note_term);
        debit_note_due = $.trim(debit_note_due);
        employee_id = $.trim(employee_id);

        if(customer_id.length == 0){
            alert("Please input iupplier.");
            document.getElementById("customer_id").focus();
            return false;
        }else if(debit_note_code.length == 0){
            alert("Please input Debit Note date.");
            document.getElementById("debit_note_code").focus();
            return false;
        }else if(debit_note_date.length == 0){
            alert("Please input Debit Note date.");
            document.getElementById("debit_note_date").focus();
            return false;
        }

        else if(debit_note_term.length == 0){
            alert("Please input Debit Note term.");
            document.getElementById("debit_note_term").focus();
            return false;
        }else if(debit_note_due.length == 0){
            alert("Please input Debit Note due");
            document.getElementById("debit_note_due").focus();
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
            document.getElementById('debit_note_name').value = data.customer_name_en;
            document.getElementById('debit_note_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
            document.getElementById('debit_note_tax').value = data.customer_tax ;
        });

         $.post( "controllers/getInvoiceCustomerByCustomerID.php", { 'customer_id': customer_id }, function( data ) {
            invoice_customer = data;
            $('select[name="invoice_customer_id"]').empty();
            var str = "<option value=''>Select Invoice</option>";
            $.each(data, function (index, value) {
                str += "<option value='" + value['invoice_customer_id'] + "'>"+value['invoice_customer_code']+"</option>";
            });
            
            $('select[name="invoice_customer_id"]').html(str);
            $('select[name="invoice_customer_id"]').selectpicker('refresh');
         });
    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
     }


     function update_sum(id){

          var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="debit_note_list_qty[]"]').val(  ).replace(',',''));
          var price =  parseFloat($(id).closest('tr').children('td').children('input[name="debit_note_list_price[]"]').val( ).replace(',',''));
          var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="debit_note_list_total[]"]').val( ).replace(',',''));

        if(isNaN(qty)){
            qty = 0;
        }

        if(isNaN(price)){
            price = 0.0;
        }

        if(isNaN(sum)){
            sum = 0.0;
        }

        sum = qty*price;

        $(id).closest('tr').children('td').children('input[name="debit_note_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="debit_note_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="debit_note_list_total[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();

        
    }



    function show_invoice_customer(id){
        var invoice_customer_id = document.getElementById('invoice_customer_id').value;
        var val = document.getElementsByName('invoice_customer_list_id[]');
        var invoice_customer_list_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            invoice_customer_list_id.push(val[i].value);
        }
        
        if(invoice_customer_id != ""){

            $.post( "controllers/getDebitNoteListByInvoiceCustomerID.php", { 'invoice_customer_id': invoice_customer_id, 'invoice_customer_list_id': JSON.stringify(invoice_customer_list_id) }, function( data ) {
               //alert(data);
               //$('#bodyAdd').html(data);
               //     $('#modalAdd').modal('show');
               
                if(data.length > 0){
                    data_buffer = data;
                    var content = "";
                    for(var i = 0; i < data.length ; i++){

                        content += '<tr class="odd gradeX">'+
                                        '<td>'+
                                            '<input type="checkbox" name="p_id" value="'+data[i].product_id+'" />'+     
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_code+
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_name+
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].debit_note_list_qty +
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].debit_note_list_price +
                                        '</td>'+
                                        '<td align="right">'+
                                            (data[i].debit_note_list_qty * data[i].debit_note_list_price) +
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
        var invoice_customer_id = document.getElementById('invoice_customer_id').value;
        var val = document.getElementsByName('invoice_customer_list_id[]');
        var invoice_customer_list_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            invoice_customer_list_id.push(val[i].value);
        }

        $.post( "controllers/getDebitNoteListByInvoiceCustomerID.php", { 'invoice_customer_id': invoice_customer_id, 'invoice_customer_list_id': JSON.stringify(invoice_customer_list_id), search : $(id).val() }, function( data ) {
            var content = "";
                if(data.length > 0){
                    data_buffer = data;
                    
                    for(var i = 0; i < data.length ; i++){

                        content += '<tr class="odd gradeX">'+
                                        '<td>'+
                                            '<input type="checkbox" name="p_id" value="'+data[i].product_id+'" />'+     
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_code+
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_name+
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].debit_note_list_qty +
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].debit_note_list_price +
                                        '</td>'+
                                        '<td align="right">'+
                                            (data[i].debit_note_list_qty * data[i].debit_note_list_price) +
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
                            '<input type="hidden" name="debit_note_list_id[]" value="0" />'+
                            '<input type="hidden" name="invoice_customer_list_id[]" value="'+data_buffer[i].invoice_customer_list_id+'" />'+
                            '<input type="hidden" name="product_id[]" value="'+data_buffer[i].product_id+'" />'+
                            data_buffer[i].product_code +
                        '</td>'+
                        '<td>'+
                            'Product name : ' + data_buffer[i].debit_note_list_product_name + '<br>' +
                            'Product detail : ' + data_buffer[i].debit_note_list_product_detail + '<br>' +
                            'Remark : ' +

                            '<input type="hidden"  name="debit_note_list_product_name[]" value="'+ data_buffer[i].debit_note_list_product_name +'" />'+
                            '<input type="hidden"  name="debit_note_list_product_detail[]" value="'+ data_buffer[i].debit_note_list_product_detail +'" />'+
                            '<input type="text" class="form-control"  name="debit_note_list_remark[]" value="'+ data_buffer[i].debit_note_list_remark +'" />'+
                        '</td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="debit_note_list_qty[]" onchange="update_sum(this);" value="'+ data_buffer[i].debit_note_list_qty +'" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="debit_note_list_price[]" onchange="update_sum(this);" value="'+ data_buffer[i].debit_note_list_price +'" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="debit_note_list_total[]" onchange="update_sum(this);"  value="'+ (data_buffer[i].debit_note_list_qty * data_buffer[i].debit_note_list_price) +'" readonly /></td>'+
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

    function update_total_old(){
        var data = invoice_customer.filter(val => val['invoice_customer_id'] == $('#invoice_customer_id').val());
        if(data.length > 0){
            total_old =  data[0].invoice_customer_total_price * 1.00;
            calculateAll();
        }
    }


    function calculateAll(){

        var val = document.getElementsByName('debit_note_list_total[]');
        var total = 0.0;
        
        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $('#debit_note_total_old').val(total_old.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        $('#debit_note_total_price').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        $('#debit_note_total').val((total_old + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );                     
        $('#debit_note_vat_price').val((total * ($('#debit_note_vat').val()/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#debit_note_net_price').val((total * ($('#debit_note_vat').val()/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }





</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Debit Note Management</h1>
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
            แก้ไขใบเพิ่มหนี้ / Edit Debit Note  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=debit_note&action=edit&id=<?PHP echo $debit_note_id?>" >
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
                                            <option <?php if($customers[$i]['customer_id'] == $customer['customer_id']){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?></option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อตามใบเพิ่มหนี้ / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="debit_note_name" name="debit_note_name" class="form-control" value="<?php echo $debit_note['debit_note_name'];?>" >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="debit_note_address" name="debit_note_address" class="form-control" rows="5" ><?php echo $debit_note['debit_note_address']; ?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <input  id="debit_note_tax" name="debit_note_tax" class="form-control" value="<?php echo $debit_note['customer_tax'];?>" >
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
                                        <label>วันที่ออกใบเพิ่มหนี้ / Date</label>
                                        <input type="text" id="debit_note_date" name="debit_note_date"  class="form-control calendar" value="<?php echo $debit_note['debit_note_date'];?>" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบเพิ่มหนี้ / CN code <font color="#F00"><b>*</b></font></label>
                                        <input id="debit_note_code" name="debit_note_code" class="form-control" value="<?php echo $debit_note['debit_note_code'];?>" readonly >
                                        <p class="help-block">Example : CN1801001.</p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>อ้างอิงใบกำกับภาษีหมายเลข / Inv Code  <font color="#F00"><b>*</b></font> </label>
                                        <select id="invoice_customer_id" name="invoice_customer_id" class="form-control select" onchange="update_total_old();" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($invoice_customers) ; $i++){
                                            ?>
                                            <option <?PHP if($debit_note['invoice_customer_id'] == $invoice_customers[$i]['invoice_customer_id'] ){?> selected <?}?> value="<?php echo $invoice_customers[$i]['invoice_customer_id'] ?>" ><?php echo $invoice_customers[$i]['invoice_customer_code'] ?> </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : INV1802001.</p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        <input type="text" id="debit_note_due" name="debit_note_due"  class="form-control calendar" value="<?php echo $debit_note['debit_note_due'];?>" readonly/>
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / Term </label>
                                        <input type="text" id="debit_note_term" name="debit_note_term"  class="form-control" value="<?php echo $debit_note['debit_note_term'];?>"  />
                                        <p class="help-block">Bank </p>
                                    </div>
                                </div>
                                

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ออกใบเพิ่มหนี้ / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option <?PHP if($debit_note['employee_id'] == $users[$i]['user_id'] ){?> selected <?}?> value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark  <font color="#F00"><b>*</b></font> </label>
                                        <textarea id="debit_note_remark" name="debit_note_remark" class="form-control" ><?PHP echo $debit_note['debit_note_remark'];?></textarea>
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
                                <th style="text-align:center;">รหัสสินค้า <br> (Product Code)</th>
                                <th style="text-align:center;">รายละเอียดสินค้า <br> (Product Detail)</th>
                                <th style="text-align:center;" width="150">จำนวน <br> (Qty)</th>
                                <th style="text-align:center;" width="150">ราคาต่อหน่วย <br> (Unit price) </th>
                                <th style="text-align:center;" width="150">จำนวนเงิน <br> (Amount)</th>
                                <th width="24"></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($debit_note_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="debit_note_list_id[]" value="<?PHP echo  $debit_note_lists[$i]['debit_note_list_id'];?>" />
                                    <input type="hidden" name="invoice_customer_list_id[]" value="<?PHP echo  $debit_note_lists[$i]['invoice_customer_list_id'];?>" />
                                    <input type="hidden" name="product_id[]" value="<?PHP echo  $debit_note_lists[$i]['product_id'];?>" />
                                    <?PHP echo  $debit_note_lists[$i]['product_code'];?>
                                </td>
                                <td>
                                    <span>Product name : </span><?php echo $debit_note_lists[$i]['debit_note_list_product_name']; ?><br>
                                    <span>Product detail : </span><?php echo $debit_note_lists[$i]['debit_note_list_product_detail']; ?><br>
                                    <span>Remark : </span>

                                    <input type="hidden"  name="debit_note_list_product_name[]"  value="<?php echo $debit_note_lists[$i]['debit_note_list_product_name']; ?>"/>
                                    <input type="hidden"  name="debit_note_list_product_detail[]"  value="<?php echo $debit_note_lists[$i]['debit_note_list_product_detail']; ?>" />
                                    <input type="text" class="form-control"  name="debit_note_list_remark[]"  placeholder="Remark" value="<?php echo $debit_note_lists[$i]['debit_note_list_remark']; ?>" />
                                </td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="debit_note_list_qty[]" value="<?php echo $debit_note_lists[$i]['debit_note_list_qty']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="debit_note_list_price[]" value="<?php echo  number_format($debit_note_lists[$i]['debit_note_list_price'],2); ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" readonly onchange="update_sum(this);" name="debit_note_list_total[]" value="<?php echo  number_format($debit_note_lists[$i]['debit_note_list_qty'] * $debit_note_lists[$i]['debit_note_list_price'],2); ?>" /></td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $debit_note_lists[$i]['debit_note_list_qty'] * $debit_note_lists[$i]['debit_note_list_price'];
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="6" align="center">
                                    <a href="javascript:;" onclick="show_invoice_customer(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มสินค้า / Add product</span>
                                    </a>

                                    <div id="modalAdd" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg " role="document">
                                            <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">เลือกรายการสินค้า / Choose product</h4>
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
                                                        <th style="text-align:center;">รหัสสินค้า <br> (Product Code)</th>
                                                        <th style="text-align:center;">ชื่อสินค้า <br> (Product Detail)</th>
                                                        <th style="text-align:center;" width="150">จำนวน <br> (Qty)</th>
                                                        <th style="text-align:center;" width="150">ราคาต่อหน่วย <br> (Unit price) </th>
                                                        <th style="text-align:center;" width="150">จำนวนเงิน <br> (Amount)</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyAdd">

                                                </tbody>
                                            </table>
                                            </div>

                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" onclick="add_row(this);">Add Product</button>

                                            </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->


                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" rowspan="5">
                                    
                                </td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>มูลค่าใบกำกับเดิม / Old total</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="debit_note_total_old" name="debit_note_total_old" value="<?PHP echo number_format($invoice_customer['invoice_customer_total_price'],2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>

                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>มูลค่าที่ถูกต้อง / Total</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="debit_note_total" name="debit_note_total" value="<?PHP echo number_format($invoice_customer['invoice_customer_total_price'] - $total ,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>

                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>ผลต่าง / Sub total</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="debit_note_total_price" name="debit_note_total_price" value="<?PHP echo number_format($total,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>

                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <table>
                                        <tr>
                                            <td>
                                                <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;">
                                                <input type="text" class="form-control" style="text-align: right;" id="debit_note_vat" name="debit_note_vat" value="<?PHP echo $vat;?>" />
                                            </td>
                                            <td>
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="debit_note_vat_price"  name="debit_note_vat_price" value="<?PHP echo number_format(($vat/100) * $total,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="debit_note_net_price" name="debit_note_net_price" value="<?PHP echo number_format(($vat/100) * $total + $total,2) ;?>" readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=debit_note" class="btn btn-default">Back</a>
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