<script>

   var options = {
        url: function(keyword) {
            return "controllers/getProductByKeyword.php?keyword="+keyword;
        },

        getValue: function(element) {
            return element.product_code ;
        },
        template: {
            type: "description",
            fields: {
                description: "product_name"
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

    var options_purchase = {
        url: function(keyword) {
            return "controllers/getCustomerPurchaseOrderByKeyword.php?keyword="+keyword;
        },

        list: {
            maxNumberOfElements: 10,
            match: {
                enabled: true
            }
        },

        getValue: function(element) {
            return element.customer_purchase_order_code ;
        },

        template: {
            type: "description",
            fields: {
                description: "customer_name_en"
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

    var stock_group_data = [
    <?php for($i = 0 ; $i < count($stock_groups) ; $i++ ){?>
        {
            stock_group_id:'<?php echo $stock_groups[$i]['stock_group_id'];?>',
            stock_group_name:'<?php echo $stock_groups[$i]['stock_group_name'];?>'
        },
    <?php }?>
    ];

    var data_buffer = [];

    function check_code(id){
        var code = $(id).val();
        $.post( "controllers/getInvoiceCustomerByCode.php", { 'invoice_customer_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("invoice_customer_code").focus();
                $("#invoice_check").val(data.invoice_customer_id);
                
            } else{
                $("#invoice_check").val("");
            }
        });
    }

    function check_date(id){
        var val_date = $(id).val();
        $.post( "controllers/checkPaperLockByDate.php", { 'date': val_date }, function( data ) {  
            if(data.result){ 
                alert("This "+val_date+" is locked in the system.");
                
                $("#date_check").val("1");
                //$("#invoice_customer_date").val(data.date_now);
                $( ".calendar" ).datepicker({ dateFormat: 'dd-mm-yy' });
                document.getElementById("invoice_customer_date").focus();
            } else{
                $("#date_check").val("0");
                generate_credit_date();
            }
        });
    }

    function check(){
        var customer_id = document.getElementById("customer_id").value;
        var invoice_customer_code = document.getElementById("invoice_customer_code").value;
        var invoice_customer_date = document.getElementById("invoice_customer_date").value;
        var invoice_customer_term = document.getElementById("invoice_customer_term").value;
        var invoice_customer_due = document.getElementById("invoice_customer_due").value;
        var employee_id = document.getElementById("employee_id").value;
        var invoice_check = document.getElementById("invoice_check").value;
        var date_check = document.getElementById("date_check").value;

        
        customer_id = $.trim(customer_id);
        invoice_customer_code = $.trim(invoice_customer_code);
        invoice_customer_date = $.trim(invoice_customer_date);
        invoice_customer_term = $.trim(invoice_customer_term);
        invoice_customer_due = $.trim(invoice_customer_due);
        employee_id = $.trim(employee_id);

        if(date_check == "1"){
            alert("This "+invoice_customer_date+" is locked in the system.");
            document.getElementById("invoice_customer_date").focus();
            return false;
        }else  if(invoice_check != ""){
            alert("This "+invoice_customer_code+" is already in the system.");
            document.getElementById("invoice_customer_code").focus();
            return false;
        }else if(customer_id.length == 0){
            alert("Please input Customer.");
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
        }else if(invoice_customer_term.length == 0){
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

            var stock_groupt_id = $('select[name="stock_group_id[]"]')
            for(var i = 0 ; i < stock_groupt_id.length; i++){
                if(stock_groupt_id[i].value == ""){
                    alert("กรุณาเลือกคลังสินค้า");
                    $(stock_groupt_id[i]).focus();
                    return false;
                }
            }

            return true;
        }
    }

    function get_customer_detail(){
        var customer_id = document.getElementById('customer_id').value;
        $.post( "controllers/getCustomerByID.php", { 'customer_id': customer_id }, function( data ) {
            document.getElementById('customer_code').value = data.customer_code;
            document.getElementById('invoice_customer_name').value = data.customer_name_en ;
            document.getElementById('invoice_customer_branch').value = data.customer_branch ;
            document.getElementById('invoice_customer_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
            document.getElementById('invoice_customer_tax').value = data.customer_tax ;
            document.getElementById('employee_id').value = data.sale_id ;
            console.log(data.sale_id);
            $('#employee_id').selectpicker('refresh');
            document.getElementById('invoice_customer_due_day').value = data.credit_day ;
            generate_credit_date();

        });
    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
        calculateAll();
        update_line();
     }

     function update_line(){
        var td_number = $('table[name="tb_list"]').children('tbody').children('tr').children('td:first-child');
        for(var i = 0; i < td_number.length ;i++){
            td_number[i].innerHTML = (i+1);
        }
    }

    function show_qty(id){
        var stock_group_id = $(id).closest('tr').children('td').children('select[name="stock_group_id[]"]').val();
        var product_id = $(id).closest('tr').children('td').children('div').children('select[name="product_id[]"]').val();

        $.post( "controllers/getQtyBy.php", { 'stock_group_id': stock_group_id,'product_id': product_id }, function( data ) {
            if (data != null){
                if( data.stock_report_qty == null){
                    $(id).closest('tr').children('td').children('input[name="invoice_customer_list_qty[]"]').attr( 'stock_report_qty', 0 );
                }else{
                    $(id).closest('tr').children('td').children('input[name="invoice_customer_list_qty[]"]').attr( 'stock_report_qty', data.stock_report_qty );
                }
            }
            
        });
    
    }

    function show_stock(id){ 
        var product_id = $(id).closest('tr').children('td').children('input[name="product_id[]"]').val();

        $.post( "controllers/getStockGroupByProductID.php", { 'product_id': product_id }, function( data ) {
                var str_stock = "";
                console.log(data);
                $.each(data, function (index, value) { 
                    if(index == 0){
                        $(id).closest('tr').children('td').children('input[name="invoice_customer_list_qty[]"]').attr( 'stock_report_qty' , value['stock_report_qty'] );
                    }
                    str_stock += "<option value='" + value['stock_group_id'] + "'>" +  value['stock_group_name'] + "["+value['stock_report_qty']+"]</option>"; 
                });
                $(id).closest('tr').children('td').children('div').children('select[name="stock_group_id[]"]').html(str_stock);
                $(id).closest('tr').children('td').children('div').children('select[name="stock_group_id[]"]').selectpicker('refresh');
        });
    
    } 

    function show_data (id){
        var product_code = $(id).val(); 
        $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
            if(data != null){
                $(id).closest('tr').children('td').children('input[name="product_name[]"]').val(data.product_name)
                $(id).closest('tr').children('td').children('input[name="product_id[]"]').val(data.product_id)  
                $(id).closest('tr').children('td').children('input[name="save_product_price[]"]').val(data.product_id)  
                
                show_stock(id);
                var customer_id = $('#customer_id').val(); 
                $.post( "controllers/getProductCustomerPriceByID.php", { 'product_id': $.trim(data.product_id),'customer_id': $.trim(customer_id)}, function( data ) { 
                    if (data != null){
                        if( data.product_id == null ){
                            $(id).closest('tr').children('td').children('input[name="product_name[]"]').attr('checked',true) ; 
                        }else{
                            var product_price = parseFloat(data.product_price);
                            $(id).closest('tr').children('td').children('input[name="invoice_customer_list_price[]"]').val( product_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
                        }
                    }
                    update_sum(id);
                });
            }
        });
    
    }

    function check_price (id){
        var customer_id = $('#customer_id').val(); 
        var product_id =  $(id).closest('tr').children('td').children('input[name="product_id[]"]').val();
        $.post( "controllers/getProductCustomerPriceByID.php", { 'product_id': $.trim(product_id),'customer_id': $.trim(customer_id)}, function( data ) {
            if (data != null){
                if( data.product_id == null ){
                    $(id).closest('tr').children('td').children('input[name="product_name[]"]').attr('checked',true) ; 
                }else{
                    $(id).closest('tr').children('td').children('input[name="product_name[]"]').attr('checked',false) ;
                }
            }
            update_sum(id);
        });
    
    }

    function check_qty(id){
        var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_customer_list_qty[]"]').val(  ).replace(',',''));
        var stock_qty =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_customer_list_qty[]"]').attr('stock_report_qty').replace(',',''));
        if(qty > stock_qty){
            alert("คลังสินค้านี้มีสินค้าเพียง " + stock_qty + " pcs. ");
            $(id).closest('tr').children('td').children('input[name="invoice_customer_list_qty[]"]').val( stock_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  )
        }
        update_sum(id);
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
                        var invoice_customer_list_qty = parseFloat( data[i].invoice_customer_list_qty );
                        var invoice_customer_list_price = parseFloat( data[i].invoice_customer_list_price );
                        var invoice_customer_list_total = invoice_customer_list_price * invoice_customer_list_qty;
                        content += '<tr class="odd gradeX">'+
                                        '<td>'+
                                            '<input type="checkbox" name="p_id" value="'+data[i].product_id+'" onchange="show_recieve(this);" />'+     
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_code+
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_name+
                                            '<br>Remark : '+
                                            data[i].invoice_customer_list_remark+
                                        '</td>'+
                                        '<td align="right">'+
                                            '<span name="qty">' + invoice_customer_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                            '<input name="qty" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_customer_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" />'+
                                        '</td>'+
                                        '<td align="right">'+
                                            '<span name="price">' + invoice_customer_list_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                            '<input name="price" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_customer_list_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" />'+
                                        '</td>'+
                                        '<td align="right">'+
                                            '<span name="total">' + invoice_customer_list_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                            '<input name="total" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_customer_list_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" readonly />'+
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
                                            '<input type="checkbox" name="p_id" value="'+data[i].product_id+'" onchange="show_recieve(this);" />'+     
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_code+
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_name+
                                            '<br>Remark : '+
                                            data[i].invoice_customer_list_remark+
                                        '</td>'+
                                        '<td align="right">'+
                                            '<span name="qty">' + invoice_customer_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                            '<input name="qty" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_customer_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" />'+
                                        '</td>'+
                                        '<td align="right">'+
                                            '<span name="price">' + invoice_customer_list_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                            '<input name="price" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_customer_list_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" />'+
                                        '</td>'+
                                        '<td align="right">'+
                                            '<span name="total">' + invoice_customer_list_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                            '<input name="total" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_customer_list_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" readonly />'+
                                        '</td>'+
                                    '</tr>';

                }
            }
            $('#bodyAdd').html(content);
            
        });
        
    }

    function show_recieve(checkbox){ 
        if (checkbox.checked == true){
            $(checkbox).closest('tr').children('td').children('input[name="qty"]').show();
            $(checkbox).closest('tr').children('td').children('span[name="qty"]').hide();

            $(checkbox).closest('tr').children('td').children('input[name="price"]').show();
            $(checkbox).closest('tr').children('td').children('span[name="price"]').hide();

            $(checkbox).closest('tr').children('td').children('input[name="total"]').show();
            $(checkbox).closest('tr').children('td').children('span[name="total"]').hide();


        }else{
            $(checkbox).closest('tr').children('td').children('input[name="qty"]').hide();
            $(checkbox).closest('tr').children('td').children('span[name="qty"]').show();

            $(checkbox).closest('tr').children('td').children('input[name="price"]').hide();
            $(checkbox).closest('tr').children('td').children('span[name="price"]').show();

            $(checkbox).closest('tr').children('td').children('input[name="total"]').hide();
            $(checkbox).closest('tr').children('td').children('span[name="total"]').show();
        }
    }


    function calculate_list(id){
        var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="qty"]').val(  ).replace(',',''));
        var price =  parseFloat($(id).closest('tr').children('td').children('input[name="price"]').val( ).replace(',',''));
        var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="total"]').val( ).replace(',',''));

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

        $(id).closest('tr').children('td').children('input[name="qty"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="price"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="total"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }

    function add_row(id){
        $('#modalAdd').modal('hide');
        var checkbox = document.getElementsByName('p_id');
        for(var i = 0 ; i < (checkbox.length); i++){
            if(checkbox[i].checked){

                var qty =  parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="qty"]').val(  ).replace(',',''));
                var price =  parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="price"]').val( ).replace(',',''));
                var sum =  parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="total"]').val( ).replace(',',''));

                var index = 0;
                if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
                    index = 1;
                }else{
                    index = $(id).closest('table').children('tbody').children('tr').length + 1;
                }

                $(id).closest('table').children('tbody').append(
                    '<tr class="odd gradeX">'+
                        '<td class="sorter">'+
                        '</td>'+ 
                        '<td>'+
                            '<input type="hidden" name="customer_purchase_order_list_id[]" value="'+ data_buffer[i].customer_purchase_order_list_id +'" readonly />'+     
                            '<input type="hidden" name="product_id[]" class="form-control" value="'+ data_buffer[i].product_id +'" />'+
					        '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code"  value="'+ data_buffer[i].product_code +'" readonly />'+ 
                            '<input type="text" class="form-control" name="product_name[]" value="'+ data_buffer[i].product_name +'" readonly />'+
                            '<input type="text" class="form-control" name="invoice_customer_list_product_name[]" placeholder="Product Name (Customer)" value="'+ data_buffer[i].invoice_customer_list_product_name +'"/>'+
                            '<input type="text" class="form-control" name="invoice_customer_list_product_detail[]" placeholder="Product Detail (Customer)" value="'+ data_buffer[i].invoice_customer_list_product_detail +'"/>'+
                            '<input type="text" class="form-control" name="invoice_customer_list_remark[]" placeholder="Remark" value="'+ data_buffer[i].invoice_customer_list_remark +'"/>'+
                        '</td>'+ 
                        '<td>'+
                            '<select  name="stock_group_id[]" onchange="show_qty(this)" class="form-control select" data-live-search="true">'+ 
                                '<option value="0">Select</option>'+ 
                            '</select>'+ 
                        '</td>'+
                        '<td align="right">'+  
                            '<input type="text" class="form-control" style="text-align: right;" name="invoice_customer_list_qty[]" autocomplete="off" onchange="check_qty(this);" value="'+ qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" />'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" class="form-control" style="text-align: right;" name="invoice_customer_list_price[]" autocomplete="off" onchange="check_price(this);" value="'+ price.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" />'+
                            '<input type="checkbox" name="save_product_price[]" value="'+ data_buffer[i].product_id +'" /> บันทึกราคาขาย'+ 
                        '</td>'+      
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_customer_list_total[]" autocomplete="off" onchange="update_sum(this);"  value="'+ sum.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" readonly /></td>'+
                        '<td>'+
                            '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                '<i class="fa fa-times" aria-hidden="true"></i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );

                $(".example-ajax-post").easyAutocomplete(options);

                var str_stock = "";
                $.each(stock_group_data, function (index, value) {
                    if(value['stock_group_id'] == data_buffer[i].stock_group_id ){
                        str_stock += "<option value='" + value['stock_group_id'] + "' SELECTED >" +  value['stock_group_name'] + "</option>";
                    }else{
                        str_stock += "<option value='" + value['stock_group_id'] + "'>" +  value['stock_group_name'] + "</option>";
                    }
                });
                $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').html(str_stock);
                $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').selectpicker();
                update_line();
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
                '<td class="sorter">'+
                '</td>'+ 
                '<td>'+
                    '<input type="hidden" name="customer_purchase_order_list_id[]" value="0" />'+     
                    '<input type="hidden" name="product_id[]" class="form-control" value="0" />'+
                    '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" />'+ 
                    '<input type="text" class="form-control" name="product_name[]" readonly />'+
                    '<input type="text" class="form-control" name="invoice_customer_list_product_name[]" placeholder="Product Name (Customer)" />'+
                    '<input type="text" class="form-control" name="invoice_customer_list_product_detail[]" placeholder="Product Detail (Customer)" />'+
                    '<input type="text" class="form-control" name="invoice_customer_list_remark[]" placeholder="Remark"/>'+
                '</td>'+ 
                '<td>'+
                    '<select  name="stock_group_id[]"  onchange="show_qty(this)" class="form-control select" data-live-search="true">'+ 
                        '<option value="0">Select</option>'+ 
                    '</select>'+ 
                '</td>'+
                '<td align="right">'+ 
                    '<input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="invoice_customer_list_qty[]" onchange="check_qty(this);" />'+
                '</td>'+
                '<td  >'+
                    '<input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="invoice_customer_list_price[]" onchange="check_price(this);" />'+
                    '<input type="checkbox" name="save_product_price[]" value="" /> บันทึกราคาขาย'+
                '</td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="invoice_customer_list_total[]" onchange="update_sum(this);" readonly /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(".example-ajax-post").easyAutocomplete(options);
        
        var str_stock = "";
        $.each(stock_group_data, function (index, value) {
            str_stock += "<option value='" + value['stock_group_id'] + "'>"+value['stock_group_name']+"</option>";
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').html(str_stock);
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').selectpicker();
        update_line();
    }


   function checkAll(id)
    {
        var checkbox = document.getElementById('check_all');
        if (checkbox.checked == true){
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', true);
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="qty"]').show();
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="price"]').show();
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="total"]').show();

            $(id).closest('table').children('tbody').children('tr').children('td').children('span[name="qty"]').hide();
            $(id).closest('table').children('tbody').children('tr').children('td').children('span[name="price"]').hide();
            $(id).closest('table').children('tbody').children('tr').children('td').children('span[name="total"]').hide();
        }else{
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', false);
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="qty"]').hide();
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="price"]').hide();
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="total"]').hide();

            $(id).closest('table').children('tbody').children('tr').children('td').children('span[name="qty"]').show();
            $(id).closest('table').children('tbody').children('tr').children('td').children('span[name="price"]').show();
            $(id).closest('table').children('tbody').children('tr').children('td').children('span[name="total"]').show();

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

    function get_customer_purchase(){
        var code = $('#customer_purchase_order_code').val();
        $.post( "controllers/getCustomerPurchaseOrderByCode.php", {'customer_purchase_order_code': code }, function( data ) {  
            if(data !== null){  
                window.location = "?app=invoice_customer&action=insert&customer_id="+data.customer_id+"&customer_purchase_order_id="+data.customer_purchase_order_id; 
            }else{  
                alert("Can not find purchase order : "+ code );
            } 
        });
    } 


    function generate_credit_date(){
        var day = parseInt($('#invoice_customer_due_day').val());
        var date = $('#invoice_customer_date').val(); 

        var current_date =new Date();
        var tomorrow = new Date();

        if(isNaN(day)){
            $('#invoice_customer_due_day').val(0);
            day = 0;
        }else if (date == ""){
            $('#invoice_customer_date').val(("0" + current_date.getDate() ) .slice(-2) + '-' + ("0" + current_date.getMonth() + 1).slice(-2) + '-' + current_date.getFullYear());
        }else{
            var date_arr = date.split('-'); 

            current_date = new Date(date_arr[2],date_arr[1] - 1,date_arr[0]);
            tomorrow = new Date(date_arr[2],date_arr[1] - 1,date_arr[0]);
        }


        if (day > 0){
            $('#invoice_customer_term').val("เครดิต");
        }else{
            $('#invoice_customer_term').val("เงินสด");
        }

        tomorrow.setDate(current_date.getDate()+day); 
        $('#invoice_customer_due').val(("0" + tomorrow.getDate() ) .slice(-2) + '-' + ("0" + (tomorrow.getMonth()+1) ).slice(-2) + '-' + tomorrow.getFullYear());
        

    }





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
                <div class="row">
                    <div class="col-md-6">
                        เพิ่มใบกำกับภาษี / Add Invoice Customer 
                    </div>
                    <div class="col-md-6">
                        <table width="100%">
                            <tr>
                                <td style="padding-left:4px;">
                                    <input class="purchase-ajax-post form-control" name="customer_purchase_order_code" id="customer_purchase_order_code" onchange=""/> 
                                </td>
                                <td style="padding-left:4px;width:100px;">
                                    <button class="btn btn-success " onclick="get_customer_purchase();" ><i class="fa fa-plus" aria-hidden="true"></i> get customer purchase.</button>
                                </td> 
                            </tr>
                        </table> 
                    </div>
                </div> 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=invoice_customer&action=add" >
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
                                            <option <?php if($customers[$i]['customer_id'] == $customer['customer_id']){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_customer_name" name="invoice_customer_name" class="form-control" value="<?php echo $customer['customer_name_en'];?>" >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>สาขา / Branch <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_customer_branch" name="invoice_customer_branch" class="form-control" value="<?php echo $customer['customer_branch'];?>" >
                                        <p class="help-block">Example : 0000 </p>
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
                                        <input type="text" id="invoice_customer_date" name="invoice_customer_date" value="<?PHP echo $first_date;?>" onchange="check_date(this);" class="form-control calendar" readonly/>
                                        <input id="date_check" type="hidden" value="" />
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        <input id="invoice_customer_code" name="invoice_customer_code" class="form-control" onchange="check_code(this)" value="<?php echo $last_code;?>" >
                                        <input id="invoice_check" type="hidden" value="" />
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
                                        <input type="text" id="invoice_customer_due" name="invoice_customer_due"  class="form-control calendar" onchange="generate_credit_date();" readonly/>
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / term </label>
                                        <input type="text" id="invoice_customer_term" name="invoice_customer_term"  class="form-control"  />
                                        <p class="help-block">Bank </p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ประเภทใบกำกับภาษี / Type </label>
                                        <select id="invoice_customer_begin" name="invoice_customer_begin" class="form-control">
                                            <option value="0">ขายสินค้า</option>
                                            <option value="3">รับเงินมัดจำ</option> 
                                        </select>
                                        <p class="help-block">ขายสินค้า </p>
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
                                            <option <?PHP if($admin_id == $users[$i]['user_id']){?> SELECTED <?PHP }?> value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
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
                    <table name="tb_list" width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;" width="60">ลำดับ </th> 
                                <th style="text-align:center;">รายละเอียดสินค้า </th>
                                <th style="text-align:center;" width="150">คลังสินค้า </th>
                                <th style="text-align:center;" width="150">จำนวน </th>
                                <th style="text-align:center;" width="150">ราคาต่อหน่วย </th>
                                <th style="text-align:center;" width="150">จำนวนเงิน </th>
                                <th width="24"></th>
                            </tr>
                        </thead>

                        <tbody class="sorted_table">
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($invoice_customer_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td class="sorter">
                                    <?PHP echo ($i + 1); ?>.
                                </td>
                                <td>
                                    <input type="hidden" name="customer_purchase_order_list_id[]" value="<?PHP echo  $invoice_customer_lists[$i]['customer_purchase_order_list_id'];?>" /> 
                                    <input type="hidden" name="product_id[]" class="form-control" value="<?php echo $invoice_customer_lists[$i]['product_id']; ?>" />
                                    <input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="<?php echo $invoice_customer_lists[$i]['product_code']; ?>"  readonly/>
                                    <input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $invoice_customer_lists[$i]['product_name']; ?>" />
                                    <input type="text" class="form-control" name="invoice_customer_list_product_name[]"  placeholder="Product Name (Customer)" value="<?PHP echo $invoice_customer_lists[$i]['invoice_customer_list_product_name'];?>"/>
                                    <input type="text" class="form-control" name="invoice_customer_list_product_detail[]"  placeholder="Product Detail (Customer)" value="<?PHP echo $invoice_customer_lists[$i]['invoice_customer_list_product_detail'];?>"/>
                                    <input type="text" class="form-control" name="invoice_customer_list_remark[]"  placeholder="Remark" value="<?php echo $invoice_customer_lists[$i]['invoice_customer_list_remark']; ?>" />
                                </td> 
                                <td>
                                    <select   name="stock_group_id[]"  onchange="show_qty(this)" class="form-control select" data-live-search="true" > 
                                        <?php 
                                        $stock_groups = $stock_group_model->getStockGroupByProductID($invoice_customer_lists[$i]['product_id']);
                                        $stock_report_qty = 0;
                                        for($ii =  0 ; $ii < count($stock_groups) ; $ii++){
                                            if($stock_groups[$ii]['stock_group_id'] == $invoice_customer_lists[$i]['stock_group_id'] || $ii ==  0){  
                                                $stock_report_qty = $stock_groups[$ii]['stock_report_qty'];
                                            }
                                        ?>
                                        <option <?php if($stock_groups[$ii]['stock_group_id'] == $invoice_customer_lists[$i]['stock_group_id']){?> selected <?php }?> value="<?php echo $stock_groups[$ii]['stock_group_id'] ?>"><?php echo $stock_groups[$ii]['stock_group_name'] ?> [<?php echo $stock_groups[$ii]['stock_report_qty'] ?>] </option>
                                        <?
                                        }
                                        ?>
                                    </select> 
                                </td>
                                <td align="right">  
                                    <input type="text" class="form-control" style="text-align: right;"  onchange="check_qty(this);" autocomplete="off" name="invoice_customer_list_qty[]" value="<?php if ($stock_report_qty >= $invoice_customer_lists[$i]['invoice_customer_list_qty']) { echo number_format($invoice_customer_lists[$i]['invoice_customer_list_qty'],0); } else { echo number_format($stock_report_qty,0); } ?>" stock_report_qty = "<?php echo $stock_report_qty; ?>" />
                                </td>
                                <td >
                                    <input type="text" class="form-control" style="text-align: right;"  onchange="check_price(this);" autocomplete="off" name="invoice_customer_list_price[]" value="<?php echo  number_format($invoice_customer_lists[$i]['invoice_customer_list_price'],2); ?>" />
                                    <input type="checkbox" name="save_product_price[]" value="<?php echo $invoice_customer_lists[$i]['product_id']; ?>"/> บันทึกราคาขาย
                                </td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" readonly onchange="update_sum(this);" autocomplete="off" name="invoice_customer_list_total[]" value="<?php echo  number_format($invoice_customer_lists[$i]['invoice_customer_list_qty'] * $invoice_customer_lists[$i]['invoice_customer_list_price'],2); ?>" /></td>
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
                                <td colspan="7" align="center">
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
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_customer_total_price" name="invoice_customer_total_price" value="<?PHP echo number_format($total,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <table>
                                        <tr>
                                            <td>
                                                <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;">
                                                <input type="text" class="form-control" style="text-align: right;" onchange="calculateAll()" id="invoice_customer_vat" name="invoice_customer_vat" value="<?PHP echo $vat;?>" />
                                            </td>
                                            <td>
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
                                <td colspan="3" align="left" style="vertical-align: middle;">
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
                            <a href="index.php?app=invoice_customer&action=insert" class="btn btn-primary">Reset</a>
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

<script>
$(".example-ajax-post").easyAutocomplete(options);
$(".purchase-ajax-post").easyAutocomplete(options_purchase);
generate_credit_date();
// Sortable rows
$('.sorted_table').sortable({
        handle: ".sorter" , 
        update: function( event, ui ) {
            update_line(); 
        }
    });
</script>
