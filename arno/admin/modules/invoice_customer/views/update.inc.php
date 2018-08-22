<script>
    var options = {
        url: function(keyword) {
            return "controllers/getProductByKeyword.php?keyword="+keyword;
        },

        getValue: function(element) {
            return element.product_code ;
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

    var data_buffer = [];
    function check(){

        var customer_id = document.getElementById("customer_id").value;
        var invoice_customer_code = document.getElementById("invoice_customer_code").value;
        var invoice_customer_date = document.getElementById("invoice_customer_date").value;
        //var invoice_customer_date_recieve = document.getElementById("invoice_customer_date_recieve").value;
        var invoice_customer_term = document.getElementById("invoice_customer_term").value;
        var invoice_customer_due = document.getElementById("invoice_customer_due").value;
        var employee_id = document.getElementById("employee_id").value;

        
        customer_id = $.trim(customer_id);
        invoice_customer_code = $.trim(invoice_customer_code);
        invoice_customer_date = $.trim(invoice_customer_date);
        //invoice_customer_date_recieve = $.trim(invoice_customer_date_recieve);
        invoice_customer_term = $.trim(invoice_customer_term);
        invoice_customer_due = $.trim(invoice_customer_due);
        employee_id = $.trim(employee_id);

        if(customer_id.length == 0){
            alert("Please input iupplier.");
            document.getElementById("customer_id").focus();
            return false;
        }else if(invoice_customer_code.length == 0){
            alert("Please input invoice Customer date.");
            document.getElementById("invoice_customer_code").focus();
            return false;
        }else if(invoice_customer_date.length == 0){
            alert("Please input invoice Customer date.");
            document.getElementById("invoice_customer_date").focus();
            return false;
        }
        /*
        else if(invoice_customer_date_recieve.length == 0){
            alert("Please input invoice Customer date recieve.");
            document.getElementById("invoice_customer_date_recieve").focus();
            return false;
        }
        */
        else if(invoice_customer_term.length == 0){
            alert("Please input invoice Customer term.");
            document.getElementById("invoice_customer_term").focus();
            return false;
        }else if(invoice_customer_due.length == 0){
            alert("Please input invoice Customer due");
            document.getElementById("invoice_customer_due").focus();
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
            document.getElementById('invoice_customer_name').value = data.customer_name_en +' (' + data.customer_name_th +')';
            document.getElementById('invoice_customer_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
            document.getElementById('invoice_customer_tax').value = data.customer_tax ;
        });
    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
     }

     function show_data(id){
        var product_code = $(id).val();
        $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
            if(data != null){
                $(id).closest('tr').children('td').children('input[name="product_name[]"]').val(data.product_name)
                $(id).closest('tr').children('td').children('input[name="product_id[]"]').val(data.product_id)
            }
        });
        
     }

     function update_sum(id){

          var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_customer_list_qty[]"]').val(  ).replace(',',''));
          var price =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_customer_list_price[]"]').val( ).replace(',',''));
          var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_customer_list_total[]"]').val( ).replace(',',''));

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

        $(id).closest('tr').children('td').children('input[name="invoice_customer_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="invoice_customer_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="invoice_customer_list_total[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();

        
    }



    function show_purchase_order(id){
        var customer_id = document.getElementById('customer_id').value;
        var val = document.getElementsByName('customer_purchase_order_list_id[]');
        var customer_purchase_order_list_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            customer_purchase_order_list_id.push(val[i].value);
        }
        
        if(customer_id != ""){

            $.post( "controllers/getInvoiceCustomerListByCustomerID.php", { 'customer_id': customer_id, 'customer_purchase_order_list_id': JSON.stringify(customer_purchase_order_list_id) }, function( data ) {
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
                                            data[i].invoice_customer_list_qty +
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].invoice_customer_list_price +
                                        '</td>'+
                                        '<td align="right">'+
                                            (data[i].invoice_customer_list_qty * data[i].invoice_customer_list_price) +
                                        '</td>'+
                                    '</tr>';

                    }
                    $('#bodyAdd').html(content);
                    $('#modalAdd').modal('show');

                }else{
                    add_row_new(id);
                }
                
            });
        }else{
            alert("Please select Customer.");
        }
        
    } 

    function search_pop_like(id){
        var customer_id = document.getElementById('customer_id').value;
        var val = document.getElementsByName('customer_purchase_order_list_id[]');
        var customer_purchase_order_list_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            customer_purchase_order_list_id.push(val[i].value);
        }

        $.post( "controllers/getInvoiceCustomerListByCustomerID.php", { 'customer_id': customer_id, 'customer_purchase_order_list_id': JSON.stringify(customer_purchase_order_list_id), search : $(id).val() }, function( data ) {
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
                                            data[i].invoice_customer_list_qty +
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].invoice_customer_list_price +
                                        '</td>'+
                                        '<td align="right">'+
                                            (data[i].invoice_customer_list_qty * data[i].invoice_customer_list_price) +
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
                            '<input type="hidden" name="invoice_customer_list_id[]" value="0" />'+  
                            '<input type="hidden" name="customer_purchase_order_list_id[]" value="'+ data_buffer[i].customer_purchase_order_list_id +'" readonly />'+     
                            '<input type="hidden" name="product_id[]" class="form-control" value="'+ data_buffer[i].product_id +'" />'+
					        '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="'+ data_buffer[i].product_code +'" />'+ 
                        '</td>'+
                        '<td>'+
                            '<input type="text" class="form-control" name="product_name[]" value="'+ data_buffer[i].product_name +'" readonly />'+
                            '<input type="text" class="form-control" name="invoice_customer_list_product_name[]" placeholder="Product Name (Customer)" value="'+ data_buffer[i].invoice_customer_list_product_name +'"/>'+
                            '<input type="text" class="form-control" name="invoice_customer_list_product_detail[]" placeholder="Product Detail (Customer)" value="'+ data_buffer[i].invoice_customer_list_product_detail +'"/>'+
                            '<input type="text" class="form-control" name="invoice_customer_list_remark[]" placeholder="Remark" value="'+ data_buffer[i].invoice_customer_list_remark +'"/>'+
                        '</td>'+
                        '<td align="right">'+
                            //'<select class="form-control select" type="text" name="stock_group_id[]" onchange="show_data(this);" data-live-search="true" ></select>'+
                            '<input  class="form-control " type="hidden" name="stock_group_id[]" value="<?PHP echo $$stock_groups[0]['stock_group_id']; ?>" />'+
                            '<input type="text" class="form-control" style="text-align: right;" name="invoice_customer_list_qty[]" onchange="update_sum(this);" value="'+ data_buffer[i].invoice_customer_list_qty +'" />'+
                        '</td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_customer_list_price[]" onchange="update_sum(this);" value="'+ data_buffer[i].invoice_customer_list_price +'" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_customer_list_total[]" onchange="update_sum(this);"  value="'+ (data_buffer[i].invoice_customer_list_qty * data_buffer[i].invoice_customer_list_price) +'" readonly /></td>'+
                        '<td>'+
                            '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                '<i class="fa fa-times" aria-hidden="true"></i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );

                $(".example-ajax-post").easyAutocomplete(options);

                var str_stock = "<option value=''>Select Stock</option>";
                $.each(stock_data, function (index, value) {
                    str_stock += "<option value='" + value['stock_group_id'] + "'>"+value['stock_group_name']+"</option>";
                });
                $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').html(str_stock);

            }
            
        }
        calculateAll();
    }

    


    function add_row_new(id){
        $('#modalAdd').modal('hide');
        var index = 0;
        if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
            index = 1;
        }else{
            index = $(id).closest('table').children('tbody').children('tr').length + 1;
        }
        $(id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td>'+
                    '<input type="hidden" name="invoice_customer_list_id[]" value="0" />'+  
                    '<input type="hidden" name="customer_purchase_order_list_id[]" value="0" />'+     
                    '<input type="hidden" name="product_id[]" class="form-control" />'+
                    '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" />'+ 
                '</td>'+
                '<td>'+
                    '<input type="text" class="form-control" name="product_name[]" readonly />'+
                    '<input type="text" class="form-control" name="invoice_customer_list_product_name[]" placeholder="Product Name (Customer)" />'+
                    '<input type="text" class="form-control" name="invoice_customer_list_product_detail[]" placeholder="Product Detail (Customer)" />'+
                    '<input type="text" class="form-control" name="invoice_customer_list_remark[]" placeholder="Remark"/>'+
                '</td>'+
                '<td align="right">'+
                    //'<select class="form-control select" type="text" name="stock_group_id[]" onchange="show_data(this);" data-live-search="true" ></select>'+
                    '<input  class="form-control " type="hidden" name="stock_group_id[]" value="<?PHP echo $$stock_groups[0]['stock_group_id']; ?>" />'+
                    '<input type="text" class="form-control" style="text-align: right;" name="invoice_customer_list_qty[]" onchange="update_sum(this);" value="'+ data_buffer[i].invoice_customer_list_qty +'" />'+
                '</td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_customer_list_price[]" onchange="update_sum(this);" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_customer_list_total[]" onchange="update_sum(this);" readonly /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

         $(".example-ajax-post").easyAutocomplete(options);

        var str_stock = "<option value=''>Select Stock</option>";
        $.each(stock_data, function (index, value) {
            str_stock += "<option value='" + value['stock_group_id'] + "'>"+value['stock_group_name']+"</option>";
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').html(str_stock);
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


    function calculateAll(){

        var val = document.getElementsByName('invoice_customer_list_total[]');
        var total = 0.0;
        
        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $('#invoice_customer_total_price').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_customer_vat_price').val((total * ($('#invoice_customer_vat').val()/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_customer_net_price').val((total * ($('#invoice_customer_vat').val()/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }

    function generate_credit_date(){
        var day = parseInt($('#invoice_customer_due_day').val());
        var date = $('#invoice_customer_date').val();

        var current_date = new Date();
        var tomorrow = new Date();

        if(isNaN(day)){
            $('#invoice_customer_due_day').val(0);
            day = 0;
        }else if (date == ""){
            $('#invoice_customer_date').val(("0" + current_date.getDate() ) .slice(-2) + '-' + ("0" + current_date.getMonth() + 1).slice(-2) + '-' + current_date.getFullYear());
        }

        if (day > 0){
            $('#invoice_customer_term').val("เครดิต");
        }else{
            $('#invoice_customer_term').val("เงินสด");
        }

        tomorrow.setDate(current_date.getDate()+day);
        $('#invoice_customer_due').val(("0" + tomorrow.getDate() ) .slice(-2) + '-' + ("0" + (tomorrow.getMonth()+1) ).slice(-2) + '-' + tomorrow.getFullYear());
        

    }


generate_credit_date();
</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Invoice Customer Management</h1>
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
            แก้ไขใบกำกับภาษี / Edit Invoice Customer 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=invoice_customer&action=edit&id=<?php echo $invoice_customer_id;?>" >
                    <input type="hidden"  id="invoice_customer_id" name="invoice_customer_id" value="<?php echo $invoice_customer_id; ?>" />
                    <input type="hidden"  id="invoice_customer_date" name="invoice_customer_date" value="<?php echo $invoice_customer['invoice_customer_date']; ?>" />
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
                                        <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_customer_name" name="invoice_customer_name" class="form-control" value="<?php echo $customer['customer_name_en'];?> (<?php echo $customer['customer_name_th'];?>)" >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="invoice_customer_address" name="invoice_customer_address" class="form-control" rows="5" ><?php echo $customer['customer_address_1'] ."\n". $customer['customer_address_2'] ."\n". $customer['customer_address_3'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_customer_tax" name="invoice_customer_tax" class="form-control" value="<?php echo $customer['customer_tax'];?>" >
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
                                        <label>วันที่ออกใบกำกับภาษี / Date</label>
                                        <input type="text" id="invoice_customer_date" name="invoice_customer_date" value="<?PHP echo $invoice_customer['invoice_customer_date'];?>" onchange="generate_credit_date();"  class="form-control calendar" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        <input id="invoice_customer_code" name="invoice_customer_code" class="form-control" value="<?PHP echo $invoice_customer['invoice_customer_code'];?>" readonly>
                                        <p class="help-block">Example : INV1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>เครดิต / Credit Day </label>
                                        <input type="text" id="invoice_customer_due_day" name="invoice_customer_due_day"  class="form-control" value="<?php echo $customer['credit_day'];?>" onchange="generate_credit_date();"/>
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        <input type="text" id="invoice_customer_due" name="invoice_customer_due"  class="form-control calendar" value="<?PHP echo $invoice_customer['invoice_customer_due'];?>" onchange="generate_credit_date();" readonly/>
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / term </label>
                                        <input type="text" id="invoice_customer_term" name="invoice_customer_term"  class="form-control" value="<?PHP echo $invoice_customer['invoice_customer_term'];?>"  />
                                        <p class="help-block">Bank </p>
                                    </div>
                                </div>
                                

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>พนักงานขาย / Sale  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option value="<?php echo $users[$i]['user_id'] ?>" <?PHP if( $users[$i]['user_id'] == $invoice_customer['employee_id']){ ?> SELECTED <?PHP }?> ><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>สถานะใบสั่งซื้อ / Invoice status  <font color="#F00"><b>*</b></font> </label>
                                        <select id="invoice_customer_close" name="invoice_customer_close" class="form-control" >
                                            <option <?PHP if($invoice_customer['invoice_customer_close'] == "0"){ ?> selected <?PHP }?> value="0">ใช้งาน</option>
                                            <option <?PHP if($invoice_customer['invoice_customer_close'] == "1"){ ?> selected <?PHP }?> value="1">เลิกใช้งาน</option>
                                        </select>
                                        <p class="help-block">Example : ใช้งาน.</p>
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
                            for($i=0; $i < count($invoice_customer_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="customer_purchase_order_list_id[]" value="<?PHP echo  $invoice_customer_lists[$i]['customer_purchase_order_list_id'];?>" />
                                    <input type="hidden" name="invoice_customer_list_id[]" value="<?PHP echo  $invoice_customer_lists[$i]['invoice_customer_list_id'];?>" />
                                   
                                    <input type="hidden" name="product_id[]" class="form-control" value="<?php echo $invoice_customer_lists[$i]['product_id']; ?>" />
                                    <input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="<?php echo $invoice_customer_lists[$i]['product_code']; ?>"  readonly/>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $invoice_customer_lists[$i]['product_name']; ?>" />
                                    <input type="text" class="form-control" name="invoice_customer_list_product_name[]" value="<?php echo $invoice_customer_lists[$i]['invoice_customer_list_product_name']; ?>" placeholder="Product Name (Customer)"/>
                                    <input type="text" class="form-control" name="invoice_customer_list_product_detail[]" value="<?php echo $invoice_customer_lists[$i]['invoice_customer_list_product_detail']; ?>"  placeholder="Product Detail (Customer)" />
                                    <input type="text" class="form-control" name="invoice_customer_list_remark[]"  placeholder="Remark" value="<?php echo $invoice_customer_lists[$i]['invoice_customer_list_remark']; ?>" />
                                </td>
                                <td align="right">
                                    <!--
                                    <select  class="form-control " name="stock_group_id[]" onchange="show_qty(this);" >
                                        <option value="">Select</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($stock_groups) ; $ii++){
                                        ?>
                                        <option <?php if($stock_groups[$ii]['stock_group_id'] == $invoice_customer_lists[$i]['stock_group_id']){?> selected <?php }?> value="<?php echo $stock_groups[$ii]['stock_group_id'] ?>"><?php echo $stock_groups[$ii]['stock_group_name'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                    Qty in stock : <span>0</span> pcs<br>
                                    Qty sale : <span><?php echo $invoice_customer_lists[$i]['invoice_customer_list_qty']; ?></span> pcs<br>
                                    -->
                                    <input  class="form-control " type="hidden" name="stock_group_id[]" value="<?PHP echo $$stock_groups[0]['stock_group_id']; ?>" />
                                    <input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="invoice_customer_list_qty[]" value="<?php echo $invoice_customer_lists[$i]['invoice_customer_list_qty']; ?>" />
                                

                                    </td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="invoice_customer_list_price[]" value="<?php echo  number_format($invoice_customer_lists[$i]['invoice_customer_list_price'],2); ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" readonly onchange="update_sum(this);" name="invoice_customer_list_total[]" value="<?php echo  number_format($invoice_customer_lists[$i]['invoice_customer_list_qty'] * $invoice_customer_lists[$i]['invoice_customer_list_price'],2); ?>" /></td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $invoice_customer_lists[$i]['invoice_customer_list_qty'] * $invoice_customer_lists[$i]['invoice_customer_list_price'];
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="6" align="center">
                                    <a href="javascript:;" onclick="show_purchase_order(this);" style="color:red;">
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
                                                <button type="button" class="btn btn-primary" onclick="add_row_new(this);">New Row</button>
                                                <button type="button" class="btn btn-primary" onclick="add_row(this);">Add Product</button>
                                            </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->


                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" rowspan="3">
                                    
                                </td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_customer_total_price" name="invoice_customer_total_price" value="<?PHP echo number_format($total,2) ;?>"  readonly/>
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
                                                <input type="text" class="form-control" style="text-align: right;" onchange="calculateAll()" id="invoice_customer_vat" name="invoice_customer_vat" value="<?PHP echo $vat;?>" />
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_customer_vat_price"  name="invoice_customer_vat_price" value="<?PHP echo number_format(($vat/100) * $total,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_customer_net_price" name="invoice_customer_net_price" value="<?PHP echo number_format(($vat/100) * $total + $total,2) ;?>" readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=invoice_customer" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <a href="print.php?app=invoice_customer&action=pdf&id=<?PHP echo $invoice_customer_id?>" class="btn btn-danger">Print</a>
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
$(".example-ajax-post").easyAutocomplete(options);
</script>