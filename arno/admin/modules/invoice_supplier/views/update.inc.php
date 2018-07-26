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

        var supplier_id = document.getElementById("supplier_id").value;
        var invoice_supplier_code = document.getElementById("invoice_supplier_code").value;
        var invoice_supplier_date = document.getElementById("invoice_supplier_date").value;
        var invoice_supplier_date_recieve = document.getElementById("invoice_supplier_date_recieve").value;
        var invoice_supplier_term = document.getElementById("invoice_supplier_term").value;
        var invoice_supplier_due = document.getElementById("invoice_supplier_due").value;
        var employee_id = document.getElementById("employee_id").value;

        
        supplier_id = $.trim(supplier_id);
        invoice_supplier_code = $.trim(invoice_supplier_code);
        invoice_supplier_date = $.trim(invoice_supplier_date);
        invoice_supplier_date_recieve = $.trim(invoice_supplier_date_recieve);
        invoice_supplier_term = $.trim(invoice_supplier_term);
        invoice_supplier_due = $.trim(invoice_supplier_due);
        employee_id = $.trim(employee_id);

        if(supplier_id.length == 0){
            alert("Please input iupplier.");
            document.getElementById("supplier_id").focus();
            return false;
        }else if(invoice_supplier_code.length == 0){
            alert("Please input invoice supplier date.");
            document.getElementById("invoice_supplier_code").focus();
            return false;
        }else if(invoice_supplier_date.length == 0){
            alert("Please input invoice supplier date.");
            document.getElementById("invoice_supplier_date").focus();
            return false;
        }else if(invoice_supplier_date_recieve.length == 0){
            alert("Please input invoice supplier date recieve.");
            document.getElementById("invoice_supplier_date_recieve").focus();
            return false;
        }else if(invoice_supplier_term.length == 0){
            alert("Please input invoice supplier term.");
            document.getElementById("invoice_supplier_term").focus();
            return false;
        }else if(invoice_supplier_due.length == 0){
            alert("Please input invoice supplier due");
            document.getElementById("invoice_supplier_due").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else{
            return true;
        }



    }

    function get_supplier_detail(){
        var supplier_id = document.getElementById('supplier_id').value;
        $.post( "controllers/getSupplierByID.php", { 'supplier_id': supplier_id }, function( data ) {
            document.getElementById('supplier_code').value = data.supplier_code;
            document.getElementById('invoice_supplier_name').value = data.supplier_name_en +' (' + data.supplier_name_th +')';
            document.getElementById('invoice_supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
            document.getElementById('invoice_supplier_tax').value = data.supplier_tax ;
        });

        $.post( "controllers/getInvoiceSupplierCodeByID.php", { 'supplier_id': supplier_id }, function( data ) {
            //document.getElementById('invoice_supplier_code_gen').value = data;
        });
    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
        calculateAll();
        calculateCost();
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

          var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_supplier_list_qty[]"]').val(  ).replace(',',''));
          var price =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_supplier_list_price[]"]').val( ).replace(',',''));
          var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_supplier_list_total[]"]').val( ).replace(',',''));

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

        $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_total[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();

        
    }



    function show_purchase_order(id){
        var supplier_id = document.getElementById('supplier_id').value;
        var val = document.getElementsByName('purchase_order_list_id[]');
        var purchase_order_list_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            purchase_order_list_id.push(val[i].value);
        }
        
        if(supplier_id != ""){

            $.post( "controllers/getInvoiceSupplierListBySupplierID.php", { 'supplier_id': supplier_id, 'purchase_order_list_id': JSON.stringify(purchase_order_list_id) }, function( data ) {
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
                                            data[i].invoice_supplier_list_qty +
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].invoice_supplier_list_price +
                                        '</td>'+
                                        '<td align="right">'+
                                            (data[i].invoice_supplier_list_qty * data[i].invoice_supplier_list_price) +
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
            alert("Please select supplier.");
        }
        
    } 

    function search_pop_like(id){
        var supplier_id = document.getElementById('supplier_id').value;
        var val = document.getElementsByName('purchase_order_list_id[]');
        var purchase_order_list_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            purchase_order_list_id.push(val[i].value);
        }

        $.post( "controllers/getInvoiceSupplierListBySupplierID.php", { 'supplier_id': supplier_id, 'purchase_order_list_id': JSON.stringify(purchase_order_list_id), search : $(id).val() }, function( data ) {
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
                                        '<br>Remark : '+
                                        data[i].invoice_supplier_list_remark+
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].invoice_supplier_list_qty +
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].invoice_supplier_list_price +
                                    '</td>'+
                                    '<td align="right">'+
                                        (data[i].invoice_supplier_list_qty * data[i].invoice_supplier_list_price) +
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
                            '<input type="hidden" name="purchase_order_list_id[]" value="'+ data_buffer[i].purchase_order_list_id +'" readonly />'+  
                            '<input type="hidden" name="stock_group_id[]" value="'+ data_buffer[i].stock_group_id +'" readonly />'+    
                            '<input type="hidden" name="invoice_supplier_list_id[]" value="0" />'+  
                            '<input type="hidden" name="invoice_supplier_list_cost[]" value="0" readonly />'+ 
                            '<input type="hidden" name="product_id[]" class="form-control" />'+
					        '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" />'+ 
                        '</td>'+
                        '<td>'+
                            '<input type="text" class="form-control" name="product_name[]" value="'+ data_buffer[i].product_name +'" readonly />'+
                            '<input type="text" class="form-control" name="invoice_supplier_list_product_name[]" placeholder="Product Name (Supplier)" />'+
                            '<input type="text" class="form-control" name="invoice_supplier_list_product_detail[]" placeholder="Product Detail (Supplier)" />'+
                            '<input type="text" class="form-control" name="invoice_supplier_list_remark[]" placeholder="Remark"/>'+
                        '</td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_qty[]"  onchange="update_sum(this);" value="'+ data_buffer[i].invoice_supplier_list_qty +'" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_price[]"  onchange="update_sum(this);" value="'+ data_buffer[i].invoice_supplier_list_price +'" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_total[]"  onchange="update_sum(this);"  value="'+ (data_buffer[i].invoice_supplier_list_qty * data_buffer[i].invoice_supplier_list_price) +'" readonly /></td>'+
                        '<td>'+
                            '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                '<i class="fa fa-times" aria-hidden="true"></i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );

                $(".example-ajax-post").easyAutocomplete(options);

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
                    '<input type="hidden" name="purchase_order_list_id[]" value="0" />'+    
                    '<input type="hidden" name="stock_group_id[]" value="<?PHP $stock_group_id?>" />'+  
                    '<input type="hidden" name="invoice_supplier_list_id[]" value="0" />'+  
                    '<input type="hidden" name="invoice_supplier_list_cost[]" value="0" readonly />'+ 
                    '<input type="hidden" name="old_cost[]" value="0" readonly />'+
                    '<input type="hidden" name="old_qty[]" value="0" readonly />'+
                    '<input type="hidden" name="product_id[]" class="form-control" value="'+ data_buffer[i].product_id +'" />'+
                    '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="'+ data_buffer[i].product_code +'" />'+ 
                '</td>'+
                '<td>'+
                    '<input type="text" class="form-control" name="product_name[]" readonly />'+
                    '<input type="text" class="form-control" name="invoice_supplier_list_product_name[]" placeholder="Product Name (Supplier)" />'+
                    '<input type="text" class="form-control" name="invoice_supplier_list_product_detail[]" placeholder="Product Detail (Supplier)" />'+
                    '<input type="text" class="form-control" name="invoice_supplier_list_remark[]" placeholder="Remark"/>'+
                '</td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" value="0" name="invoice_supplier_list_qty[]" onchange="update_sum(this);" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" value="0" name="invoice_supplier_list_price[]" onchange="update_sum(this);" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" value="0" name="invoice_supplier_list_total[]" onchange="update_sum(this);" readonly /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

       $(".example-ajax-post").easyAutocomplete(options);
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

        var val = document.getElementsByName('invoice_supplier_list_total[]');
        var total = 0.0;

        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $('#invoice_supplier_total_price').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_supplier_vat_price').val((total * ($('#invoice_supplier_vat').val()/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_supplier_net_price').val((total * ($('#invoice_supplier_vat').val()/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        calculateCost();
    }

    function calculateCost(){

        var invoice_supplier_list_cost = document.getElementsByName('invoice_supplier_list_cost[]');
        var invoice_supplier_list_total = document.getElementsByName('invoice_supplier_list_total[]');
        var invoice_supplier_list_qty = document.getElementsByName('invoice_supplier_list_qty[]');
        var exchange_rate_baht = parseFloat(document.getElementById('exchange_rate_baht').value.toString().replace(new RegExp(',', 'g'),''));
        var invoice_supplier_total_price = parseFloat(document.getElementById('invoice_supplier_total_price').value.toString().replace(new RegExp(',', 'g'),''));


        var invoice_supplier_total_price_ex = 0; 
        var import_duty = parseFloat(document.getElementById('import_duty').value.toString().replace(new RegExp(',', 'g'),''));
        var freight_in = parseFloat(document.getElementById('freight_in').value.toString().replace(new RegExp(',', 'g'),''));


        if(isNaN(exchange_rate_baht)){
            exchange_rate_baht = 0.0;
        }
        if(isNaN(invoice_supplier_total_price)){
            invoice_supplier_total_price = 0.0;
        }

        if(isNaN(import_duty)){
            import_duty = 0.0;
        }

        if(isNaN(freight_in)){
            freight_in = 0.0;
        }

        invoice_supplier_total_price_ex = invoice_supplier_total_price * exchange_rate_baht; 

        for(var i=0; i < invoice_supplier_list_cost.length; i++){

            var cost_price_total = parseFloat(invoice_supplier_list_total[i].value.toString().replace(new RegExp(',', 'g'),'')) * exchange_rate_baht ;
            var qty = parseFloat(invoice_supplier_list_qty[i].value.toString().replace(new RegExp(',', 'g'),''));
            var cost_price_duty = cost_price_total / invoice_supplier_total_price_ex * import_duty;
            var cost_price_f = cost_price_total / invoice_supplier_total_price_ex * freight_in;
            var cost_total = (cost_price_f + cost_price_duty + cost_price_total)/qty;

            if (invoice_supplier_total_price_ex > 0){
                invoice_supplier_list_cost[i].value = cost_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            }else{
                invoice_supplier_list_cost[i].value = 0;                
            }
        }
        
        document.getElementById('exchange_rate_baht').value = exchange_rate_baht.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        document.getElementById('invoice_supplier_total_price').value = invoice_supplier_total_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        document.getElementById('import_duty').value = import_duty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        document.getElementById('freight_in').value = freight_in.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");

    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Invoice Supplier Management</h1>
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
            แก้ไขใบกำกับภาษีรับเข้า / Edit Invoice Supplier 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=invoice_supplier&action=edit&id=<?php echo $invoice_supplier_id;?>" >
                    <input type="hidden"  id="invoice_supplier_id" name="invoice_supplier_id" value="<?php echo $invoice_supplier_id; ?>" />
                    <input type="hidden"  id="invoice_supplier_date" name="invoice_supplier_date" value="<?php echo $invoice_supplier['invoice_supplier_date']; ?>" />
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_code" name="supplier_code"  class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
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
                                            <option <?php if($suppliers[$i]['supplier_id'] == $supplier['supplier_id']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> (<?php echo $suppliers[$i]['supplier_name_th'] ?>)</option>
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
                                        <input  id="invoice_supplier_name" name="invoice_supplier_name" class="form-control" value="<?php echo $supplier['supplier_name_en'];?> (<?php echo $supplier['supplier_name_th'];?>)" >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="invoice_supplier_address" name="invoice_supplier_address" class="form-control" rows="5" ><?php echo $supplier['supplier_address_1'] ."\n". $supplier['supplier_address_2'] ."\n". $supplier['supplier_address_3'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_supplier_tax" name="invoice_supplier_tax" class="form-control" value="<?php echo $supplier['supplier_tax'];?>" >
                                        <p class="help-block">Example : 0305559003597.</p>
                                    </div>
                                </div>
                                <?PHP if($supplier['supplier_domestic'] == "ภายนอกประเทศ"){ ?>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Exchange rate Baht<font color="#F00"><b>*</b></font></label>
                                            <input  id="exchange_rate_baht" name="exchange_rate_baht" onchange="calculateCost();" class="form-control" value="<?php echo $exchange_rate_baht['exchange_rate_baht_value'];?>">
                                            <p class="help-block">Example : 0.</p>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Import duty<font color="#F00"><b>*</b></font></label>
                                            <input  id="import_duty" name="import_duty" onchange="calculateCost();" class="form-control" value="<?php echo $invoice_supplier['import_duty'];?>" >
                                            <p class="help-block">Example : 0.</p>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Freight in<font color="#F00"><b>*</b></font></label>
                                            <input  id="freight_in" name="freight_in" onchange="calculateCost();" class="form-control" value="<?php echo $invoice_supplier['freight_in'];?>" >
                                            <p class="help-block">Example : 0.</p>
                                        </div>
                                    </div>
                                <?PHP } ?>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="row">
                                    
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่รับสินค้า / Date recieve</label>
                                        <input type="text" id="invoice_supplier_date_recieve" name="invoice_supplier_date_recieve"  class="form-control calendar" value="<?PHP echo $invoice_supplier['invoice_supplier_date_recieve'];?>" readonly/>
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขรับใบกำกับภาษี / recieve code <font color="#F00"><b>*</b></font></label>
                                        <input id="invoice_supplier_code_gen" name="invoice_supplier_code_gen" class="form-control" value="<?PHP echo $invoice_supplier['invoice_supplier_code_gen'];?>" readonly>
                                        <p class="help-block">Example : RR1801001 OR RF1801001.</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกใบกำกับภาษี / Date</label>
                                        <input type="text" id="invoice_supplier_date" name="invoice_supplier_date" value="<?PHP echo $invoice_supplier['invoice_supplier_date'];?>"  class="form-control calendar" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        <input id="invoice_supplier_code" name="invoice_supplier_code" class="form-control" value="<?PHP echo $invoice_supplier['invoice_supplier_code'];?>" >
                                        <p class="help-block">Example : INV1801001.</p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        <input type="text" id="invoice_supplier_due" name="invoice_supplier_due"  class="form-control calendar" value="<?PHP echo $invoice_supplier['invoice_supplier_due'];?>" readonly/>
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / term </label>
                                        <input type="text" id="invoice_supplier_term" name="invoice_supplier_term"  class="form-control" value="<?PHP echo $invoice_supplier['invoice_supplier_term'];?>"  />
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div>
                                
                                

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้รับใบกำกับภาษี / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option value="<?php echo $users[$i]['user_id'] ?>" <?PHP if( $users[$i]['user_id'] == $invoice_supplier['employee_id']){ ?> SELECTED <?PHP }?> ><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
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
                            $cost_duty = 0;
                            $cost_price_total_s = 0;
                            $cost_price_ex_total_s = 0;
                            for($i=0; $i < count($invoice_supplier_lists); $i++){
                                $cost_qty = $invoice_supplier_lists[$i]['invoice_supplier_list_qty'];
                                $cost_price = $invoice_supplier_lists[$i]['invoice_supplier_list_price'] ;
                                $cost_duty += $cost_qty * $cost_price;
                            }


                            for($i=0; $i < count($invoice_supplier_lists); $i++){

                                $cost_qty = $invoice_supplier_lists[$i]['invoice_supplier_list_qty'];
                                $cost_price = $invoice_supplier_lists[$i]['invoice_supplier_list_price'] ;
                                $cost_price_ex = $invoice_supplier_lists[$i]['invoice_supplier_list_price'] * $exchange_rate_baht['exchange_rate_baht_value'];

                                $cost_price_total = $cost_qty * $cost_price;
                                $cost_price_ex_total = $cost_qty * $cost_price_ex;


                                $cost_price_duty = $cost_price_total / $cost_duty * $invoice_supplier['import_duty'];
                                $cost_price_f = $cost_price_total / $cost_duty * $invoice_supplier['freight_in'];
                                $cost_total = $cost_price_f + $cost_price_duty + $cost_price_ex_total;
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="purchase_order_list_id[]" value="<?PHP echo  $invoice_supplier_lists[$i]['purchase_order_list_id'];?>" />
                                    <input type="hidden" name="stock_group_id[]" value="<?PHP echo  $invoice_supplier_lists[$i]['stock_group_id'];?>" />
                                    <input type="hidden" name="invoice_supplier_list_id[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_id'];?>" />
                                    <input type="hidden" name="invoice_supplier_list_cost[]" value="<?PHP echo  $cost_total;?>" />
                                    <input type="hidden" name="old_cost[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_cost'];?>" />
                                    <input type="hidden" name="old_qty[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_qty'];?>" />
                                    <input type="hidden" name="product_id[]" class="form-control" value="<?php echo $invoice_supplier_lists[$i]['product_id']; ?>" />
                                    <input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="<?php echo $invoice_supplier_lists[$i]['product_code']; ?>"  readonly/>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $invoice_supplier_lists[$i]['product_name']; ?>" />
                                    <input type="text" class="form-control" name="invoice_supplier_list_product_name[]" value="<?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_product_name']; ?>" placeholder="Product Name (Supplier)"/>
                                    <input type="text" class="form-control" name="invoice_supplier_list_product_detail[]" value="<?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_product_detail']; ?>" placeholder="Product Detail (Supplier)" />
                                    <input type="text" class="form-control" name="invoice_supplier_list_remark[]"  placeholder="Remark" value="<?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_remark']; ?>" />
                                </td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="invoice_supplier_list_qty[]" value="<?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_qty']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="invoice_supplier_list_price[]" value="<?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_price'],2); ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" readonly onchange="update_sum(this);" name="invoice_supplier_list_total[]" value="<?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_qty'] * $invoice_supplier_lists[$i]['invoice_supplier_list_price'],2); ?>" /></td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $invoice_supplier_lists[$i]['invoice_supplier_list_qty'] * $invoice_supplier_lists[$i]['invoice_supplier_list_price'];
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
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_total_price" name="invoice_supplier_total_price" value="<?PHP echo number_format($total,2) ;?>"  readonly/>
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
                                                <input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_vat" name="invoice_supplier_vat" value="<?php echo $supplier['vat'];?>" />
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_vat_price"  name="invoice_supplier_vat_price" value="<?PHP echo number_format(($supplier['vat']/100) * $total,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_net_price" name="invoice_supplier_net_price" value="<?PHP echo number_format(($vat/100) * $total + $total,2) ;?>" readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=invoice_supplier" class="btn btn-default">Back</a>
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
$(".example-ajax-post").easyAutocomplete(options);
</script>