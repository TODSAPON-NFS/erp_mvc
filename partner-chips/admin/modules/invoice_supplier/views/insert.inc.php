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
            return "controllers/getPurchaseOrderByKeyword.php?type=<?PHP echo $sort; ?>&keyword="+keyword;
        },

        list: {
            maxNumberOfElements: 10,
            match: {
                enabled: true
            }
        },

        getValue: function(element) {
            return element.purchase_order_code ;
        },

        template: {
            type: "description",
            fields: {
                description: "supplier_name_en"
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
        $.post( "controllers/getInvoiceSupplierByCode.php", { 'invoice_supplier_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("invoice_supplier_code_gen").focus();
                $("#invoice_check").val(data.invoice_supplier_id);
                
            } else{
                $("#invoice_check").val("");
            }
        });
    }

    function check(){

        var supplier_id = document.getElementById("supplier_id").value;
        var invoice_supplier_code = document.getElementById("invoice_supplier_code").value;
        var invoice_supplier_date = document.getElementById("invoice_supplier_date").value;
        var invoice_supplier_date_recieve = document.getElementById("invoice_supplier_date_recieve").value;
        var invoice_supplier_term = document.getElementById("invoice_supplier_term").value;
        var invoice_supplier_due = document.getElementById("invoice_supplier_due").value;
        var employee_id = document.getElementById("employee_id").value;
        var invoice_check = document.getElementById("invoice_check").value;

        
        supplier_id = $.trim(supplier_id);
        invoice_supplier_code = $.trim(invoice_supplier_code);
        invoice_supplier_date = $.trim(invoice_supplier_date);
        invoice_supplier_date_recieve = $.trim(invoice_supplier_date_recieve);
        invoice_supplier_term = $.trim(invoice_supplier_term);
        invoice_supplier_due = $.trim(invoice_supplier_due);
        employee_id = $.trim(employee_id);

        if(invoice_check != ""){
            alert("This "+invoice_supplier_code_gen+" is already in the system.");
            document.getElementById("invoice_supplier_code_gen").focus();
            return false;
        }else if(supplier_id.length == 0){
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
        }


        /*
        else if(invoice_supplier_term.length == 0){
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
        }
        */

        else{
            $('select[name="stock_group_id[]"]').prop('disabled', false);
            return true;
        }



    }

    function get_supplier_detail(){
        var supplier_id = document.getElementById('supplier_id').value;
        var employee_id = document.getElementById("employee_id").value;
        var invoice_supplier_date_recieve = document.getElementById("invoice_supplier_date_recieve").value;
        $.post( "controllers/getSupplierByID.php", { 'supplier_id': supplier_id }, function( data ) {
            if(data != null){
                document.getElementById('supplier_code').value = data.supplier_code;
                document.getElementById('invoice_supplier_name').value = data.supplier_name_en;
                document.getElementById('invoice_supplier_branch').value = data.supplier_branch;
                document.getElementById('invoice_supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
                document.getElementById('invoice_supplier_tax').value = data.supplier_tax ;
                document.getElementById('invoice_supplier_day').value = data.credit_day ;
                document.getElementById('invoice_supplier_term').value = data.condition_pay ;
            }
        });

        <?PHP if($sort == "ภายนอกประเทศ"){ ?>
            $.post( "controllers/getExchangeRateByCurrencyID.php", { 'invoice_supplier_date_recieve':invoice_supplier_date_recieve, 'supplier_id': supplier_id }, function( data ) {
                if(data != null){
                    var val =  parseFloat(data.exchange_rate_baht_value);
                    document.getElementById('exchange_rate_baht').value =  numberWithCommas(val); 
                }else{
                    document.getElementById('exchange_rate_baht').value = 0;
                }
                calculateCost();
                //console.log(data);
            });
        <?PHP } ?>



        $.post( "controllers/getInvoiceSupplierCodeByID.php", { 'supplier_id': supplier_id, 'employee_id':employee_id  }, function( data ) {
            document.getElementById('invoice_supplier_code_gen').value = data;
        });
    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
        calculateAll();
        <?PHP if($sort == "ภายนอกประเทศ"){ ?>
        calculateCost();
        <?PHP } ?>
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


<?PHP if($sort == "ภายนอกประเทศ"){ ?>
    function update_sum(id){ 
    
        var qty = document.getElementsByName('invoice_supplier_list_qty[]'); 
        var purchase_price =  document.getElementsByName('purchase_order_list_price[]');
        var price =  document.getElementsByName('invoice_supplier_list_price[]');
        var sum = document.getElementsByName('invoice_supplier_list_total[]');
        var exchange_rate = parseFloat(document.getElementById('exchange_rate_baht').value.replace(',',''));
        console.log(purchase_price);
        console.log(qty);
        for(var i = 0 ; i < qty.length ; i++){  
            
            var val_qty =  parseFloat(qty[i].value.replace(',',''));
            var val_purchase_price =  parseFloat(purchase_price[i].value.replace(',',''));
            var val_price =  parseFloat(price[i].value.replace(',',''));
            var val_sum =  parseFloat(sum[i].value.replace(',',''));
            

            if(isNaN(val_qty)){
                val_qty = 0;
            }

            if(isNaN(val_purchase_price)){
                val_purchase_price = 0.0;
            }

            if(isNaN(val_price)){
                val_price = 0.0;
            }

            if(isNaN(val_sum)){
                val_sum = 0.0;
            }

            val_price =  val_purchase_price * exchange_rate;
            val_sum = val_qty*val_price;

            console.log("val_price",val_price);

            qty[i].value = val_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") ;
            price[i].value = numberWithCommas(val_price.toFixed(4)) ;
            sum[i].value = val_sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        }  
        calculateAll();


    }
<?PHP } else { ?>
     function update_sum(id){
        var val_qty = document.getElementsByName('invoice_supplier_list_qty[]');
        for(var i = 0 ; i < val_qty.length ; i++){ 
            id = val_qty[i];
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
            $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_cost[]"]').val( price.toFixed(2) );
            $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_total[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        }
        calculateAll();

        
    }

<?PHP } ?>

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

                        var invoice_supplier_list_qty = parseFloat( data[i].invoice_supplier_list_qty );
                        <?PHP if($sort != "ภายนอกประเทศ"){ ?>
                        var invoice_supplier_list_price = parseFloat( data[i].purchase_order_list_price );
                        <?PHP } else { ?>
                        var invoice_supplier_list_price = parseFloat( data[i].invoice_supplier_list_price );
                        <?PHP } ?>
                        var invoice_supplier_list_total = invoice_supplier_list_price * invoice_supplier_list_qty;

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
                                        data[i].invoice_supplier_list_remark+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="qty">' + invoice_supplier_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="qty" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_supplier_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" />'+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="price">' + invoice_supplier_list_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="price" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_supplier_list_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" />'+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="total">' + invoice_supplier_list_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="total" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_supplier_list_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" readonly />'+
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

                    var invoice_supplier_list_qty = parseFloat( data[i].invoice_supplier_list_qty );
                    <?PHP if($sort != "ภายนอกประเทศ"){ ?>
                    var invoice_supplier_list_price = parseFloat( data[i].purchase_order_list_price );
                    <?PHP } else { ?>
                    var invoice_supplier_list_price = parseFloat( data[i].invoice_supplier_list_price );
                    <?PHP } ?>

                    var invoice_supplier_list_total = invoice_supplier_list_price * invoice_supplier_list_qty;

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
                                        data[i].invoice_supplier_list_remark+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="qty">' + invoice_supplier_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="qty" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_supplier_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" />'+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="price">' + invoice_supplier_list_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="price" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_supplier_list_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" />'+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="total">' + invoice_supplier_list_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="total" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_supplier_list_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" readonly />'+
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
                <?PHP if($sort == "ภายนอกประเทศ"){ ?>
                var exchange_rate = parseFloat(document.getElementById('exchange_rate_baht').value.replace(',',''));
                var qty =  parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="qty"]').val(  ).replace(',',''));
                var purchase_price =  parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="price"]').val( ).replace(',',''));
                var price = purchase_price * exchange_rate;
                var sum =  parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="total"]').val( ).replace(',',''));
            <?PHP }else{ ?>
                var qty =  parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="qty"]').val(  ).replace(',',''));
                var price =  parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="price"]').val( ).replace(',',''));
                var sum =  parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="total"]').val( ).replace(',',''));
            <?PHP } ?>

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
                            '<input type="hidden" name="invoice_supplier_list_cost[]" value="'+price.toFixed(2)+'" readonly />'+     
                            '<input type="hidden" name="product_id[]" class="form-control" value="'+ data_buffer[i].product_id +'" />'+
					        '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="'+ data_buffer[i].product_code +'" />'+ 
                        '</td>'+
                        '<td>'+
                            '<input type="text" class="form-control" name="product_name[]" value="'+ data_buffer[i].product_name +'" readonly />'+
                            '<input type="text" class="form-control" name="invoice_supplier_list_product_name[]" placeholder="Product Name (Supplier)" />'+
                            '<input type="text" class="form-control" name="invoice_supplier_list_product_detail[]" placeholder="Product Detail (Supplier)" />'+
                            '<input type="text" class="form-control" name="invoice_supplier_list_remark[]" placeholder="Remark" value="'+ data_buffer[i].invoice_supplier_list_remark +'"/>'+
                        '</td>'+
                        '<td>'+
                            '<select  name="stock_group_id[]" class="form-control select" data-live-search="true">'+ 
                                '<option value="0">Select</option>'+ 
                            '</select>'+ 
                        '</td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_qty[]" onchange="update_sum(this);" value="'+ qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" /></td>'+<?PHP if($sort == "ภายนอกประเทศ"){ ?>
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="purchase_order_list_price[]" onchange="update_sum(this);" value="'+ purchase_price.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" /></td>'+
            <?PHP } ?>  '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_price[]" onchange="update_sum(this);" value="'+ price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_total[]" onchange="update_sum(this);"  value="'+ sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" readonly /></td>'+
                        '<td>'+
                            '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                '<i class="fa fa-times" aria-hidden="true"></i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );

                $(".example-ajax-post").easyAutocomplete(options);

                $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="stock_group_id[]"]').empty();
                var str = "<option value=''>เลือกคลังสินค้า</option>";
                $.each(stock_group_data, function (index, value) {
                    if(value['stock_group_id'] == data_buffer[i].stock_group_id ){
                        str += "<option value='" + value['stock_group_id'] + "' SELECTED >" +  value['stock_group_name'] + "</option>";
                    }else{
                        str += "<option value='" + value['stock_group_id'] + "'>" +  value['stock_group_name'] + "</option>";
                    }
                    
                });
                $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').html(str);

                $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').selectpicker();

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
                    '<input type="hidden" name="invoice_supplier_list_cost[]" value="0" readonly />'+
                    '<input type="hidden" name="old_cost[]" value="0" readonly />'+
                    '<input type="hidden" name="old_qty[]" value="0" readonly />'+
                    '<input type="hidden" name="product_id[]" class="form-control" value="0" />'+
                    '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="" />'+ 
                '</td>'+
                '<td>'+
                    '<input type="text" class="form-control" name="product_name[]" readonly />'+
                    '<input type="text" class="form-control" name="invoice_supplier_list_product_name[]" placeholder="Product Name (Supplier)" />'+
                    '<input type="text" class="form-control" name="invoice_supplier_list_product_detail[]" placeholder="Product Detail (Supplier)" />'+
                    '<input type="text" class="form-control" name="invoice_supplier_list_remark[]" placeholder="Remark"/>'+
                '</td>'+
                '<td>'+
                    '<select  name="stock_group_id[]" class="form-control select" data-live-search="true">'+ 
                        '<option value="0">Select</option>'+ 
                    '</select>'+ 
                '</td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_qty[]" value="0" onchange="update_sum(this);" /></td>'+<?PHP if($sort == "ภายนอกประเทศ"){ ?>
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="purchase_order_list_price[]" onchange="update_sum(this);" value="0" /></td>'+
    <?PHP } ?>  '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_price[]" value="0" onchange="update_sum(this);" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_total[]" value="0" onchange="update_sum(this);" readonly /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(".example-ajax-post").easyAutocomplete(options);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="stock_group_id[]"]').empty();
        var str = "<option value=''>เลือกคลังสินค้า</option>";
        $.each(stock_group_data, function (index, value) { 
            str += "<option value='" + value['stock_group_id'] + "'>" +  value['stock_group_name'] + "</option>"; 
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').selectpicker();
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
        
  
        var val = document.getElementsByName('invoice_supplier_list_total[]');
        var total = 0.0;

        
        
        for(var i = 0 ; i < val.length ; i++){ 
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $('#invoice_supplier_total_price').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_supplier_vat_price').val((total * ($('#invoice_supplier_vat').val()/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_supplier_net_price').val((total * ($('#invoice_supplier_vat').val()/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

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

        document.getElementById('exchange_rate_baht').value = numberWithCommas(exchange_rate_baht);
        document.getElementById('invoice_supplier_total_price').value = invoice_supplier_total_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        document.getElementById('import_duty').value = import_duty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        document.getElementById('freight_in').value = freight_in.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");

        update_sum(null);
    }




    function get_purchase(){
        var code = $('#purchase_order_code').val();
        $.post( "controllers/getPurchaseOrderByCode.php", { 'type':'<?PHP echo $sort;?>','purchase_order_code': code }, function( data ) {  
            if(data !== null){  
                window.location = "?app=invoice_supplier&action=insert&sort=<?PHP echo $sort; ?>&supplier_id="+data.supplier_id+"&purchase_order_id="+data.purchase_order_id; 
            }else{  
                alert("Can not find purchase order : "+ code );
            } 
        });
    } 

    function update_invoice_supplier_due(id){
        var day = parseInt($('#invoice_supplier_day').val());
        var date = $('#invoice_supplier_date').val();

        var current_date = new Date();
        var tomorrow = new Date();

        if(isNaN(day)){
            $('#invoice_supplier_term').val(0);
            day = 0;
        }else if (date == ""){
            $('#invoice_supplier_due').val(("0" + current_date.getDate() ) .slice(-2) + '-' + ("0" + current_date.getMonth() + 1).slice(-2) + '-' + current_date.getFullYear());
        } 

        tomorrow.setDate(current_date.getDate()+day);
        $('#invoice_supplier_due').val(("0" + tomorrow.getDate() ) .slice(-2) + '-' + ("0" + (tomorrow.getMonth()+1) ).slice(-2) + '-' + tomorrow.getFullYear());

        console.log($('#invoice_supplier_due').val());
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
                <div class="row">
                    <div class="col-md-6">
                        เพิ่มใบกำกับภาษีรับเข้า / Add Invoice Supplier
                    </div>
                    <div class="col-md-6">
                        <table width="100%">
                            <tr>
                                <td style="padding-left:4px;">
                                    <input class="purchase-ajax-post form-control" name="purchase_order_code" id="purchase_order_code" onchange=""/> 
                                </td>
                                <td style="padding-left:4px;width:100px;">
                                    <button class="btn btn-success " onclick="get_purchase();" ><i class="fa fa-plus" aria-hidden="true"></i> get purchase.</button>
                                </td> 
                            </tr>
                        </table> 
                    </div>
                </div> 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=invoice_supplier&action=add" >
                    <div class="row">
                        <div class="col-lg-7">
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
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_supplier_name" name="invoice_supplier_name" class="form-control" value="<?php echo $supplier['supplier_name_en'];?> " >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>สาขา / Branch <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_supplier_branch" name="invoice_supplier_branch" class="form-control" value="<?php echo $supplier['supplier_branch'];?>" >
                                        <p class="help-block">Example : 0000 </p>
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
                            <?PHP if($sort == "ภายนอกประเทศ"){ ?>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Exchange rate Baht<font color="#F00"><b>*</b></font></label>
                                        <input  id="exchange_rate_baht" name="exchange_rate_baht" onchange="calculateCost();" class="form-control" value="<?php echo number_format($exchange_rate_baht['exchange_rate_baht_value'],5);?>" onchange="calculateCost()" >
                                        <p class="help-block">Example : 0.</p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Import duty<font color="#F00"><b>*</b></font></label>
                                        <input  id="import_duty" name="import_duty" onchange="calculateCost();" class="form-control" value="<?php echo number_format($invoice_supplier['import_duty'],2);?>" onchange="calculateCost()" >
                                        <p class="help-block">Example : 0.</p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Freight in<font color="#F00"><b>*</b></font></label>
                                        <input  id="freight_in" name="freight_in" onchange="calculateCost();" class="form-control" value="<?php echo number_format($invoice_supplier['freight_in'],2);?>" onchange="calculateCost()" >
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
                                        <input type="text" id="invoice_supplier_date_recieve" name="invoice_supplier_date_recieve" value="<?PHP echo $first_date;?>"  class="form-control calendar" onchange="get_supplier_detail()" readonly/>
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขรับใบกำกับภาษี / recieve code <font color="#F00"><b>*</b></font></label>
                                        <input id="invoice_supplier_code_gen" name="invoice_supplier_code_gen" class="form-control" onchange="check_code(this)" value="<?php echo $last_code;?>" >
                                        <input id="invoice_check" type="hidden" value="" />
                                        <p class="help-block">Example : RR1801001 OR RF1801001.</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกใบกำกับภาษี / Date</label>
                                        <input type="text" id="invoice_supplier_date" name="invoice_supplier_date"  class="form-control calendar" onchange="update_invoice_supplier_due(this)" readonly/>
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        <input id="invoice_supplier_code" name="invoice_supplier_code" class="form-control" >
                                        <p class="help-block">Example : INV1801001.</p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12" style="display:none">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        <input type="text" id="invoice_supplier_due" name="invoice_supplier_due"  class="form-control calendar" value="" readonly/>
                                        <input type="hidden" id="invoice_supplier_day" name="invoice_supplier_day" value="<?PHP echo $supplier['credit_day']; ?>" />
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div>

                                <div class="col-lg-12" style="display:none">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / term </label>
                                        <input type="text" id="invoice_supplier_term" name="invoice_supplier_term"  class="form-control" value="<?PHP echo $supplier['condition_pay']; ?>" />
                                        <p class="help-block">Bank </p>
                                    </div>
                                </div>
                                
                               

                                <div class="col-lg-12" style="display:none">
                                    <div class="form-group">
                                        <label>ผู้รับใบกำกับภาษี / Employee  <font color="#F00"><b>*</b></font> </label>
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
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">รหัสสินค้า <br> (Product Code)</th>
                                <th style="text-align:center;">รายละเอียดสินค้า <br> (Product Detail)</th>
                                <th style="text-align:center;">คลังสินค้า <br> (Stock)</th>
                                <th style="text-align:center;" width="150">จำนวน <br> (Qty)</th>
                                <?PHP if($sort == "ภายนอกประเทศ"){ ?>
                                <th style="text-align:center;" width="150">ราคาต่อหน่วย <span nane="currency"><?PHP echo $supplier['currency_sign']; ?></span> <br> (Unit price <span nane="currency"><?PHP echo $supplier['currency_sign']; ?></span>) </th>
                                <?PHP } ?>
                                <th style="text-align:center;" width="150">ราคาต่อหน่วยบาท <br> (Unit price bath) </th>
                                <th style="text-align:center;" width="150">จำนวนเงินบาท <br> (Amount bath)</th>
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
                                if($sort != "ภายนอกประเทศ"){
                                    $invoice_supplier_lists[$i]['invoice_supplier_list_price'] = $invoice_supplier_lists[$i]['purchase_order_list_price'];
                                }

                                $cost_qty = $invoice_supplier_lists[$i]['invoice_supplier_list_qty'];
                                $cost_price = $invoice_supplier_lists[$i]['invoice_supplier_list_price'] ;
                                $cost_duty += $cost_qty * $cost_price;
                            }


                            for($i=0; $i < count($invoice_supplier_lists); $i++){

                                if($sort == "ภายนอกประเทศ"){
                                    $cost_qty = $invoice_supplier_lists[$i]['invoice_supplier_list_qty'];
                                    $cost_price = $invoice_supplier_lists[$i]['invoice_supplier_list_price'] ;
                                    $cost_price_ex = $invoice_supplier_lists[$i]['invoice_supplier_list_price'] * $exchange_rate_baht['exchange_rate_baht_value'];

                                    $cost_price_total = $cost_qty * $cost_price;
                                    $cost_price_ex_total = $cost_qty * $cost_price_ex;

                                    if($cost_duty * $invoice_supplier['import_duty'] == 0){
                                        $cost_price_duty = 0;
                                    }else{
                                        $cost_price_duty = $cost_price_total / $cost_duty * $invoice_supplier['import_duty'];
                                    }

                                    if($cost_duty * $invoice_supplier['freight_in'] == 0){
                                        $cost_price_f = 0;
                                    }else{
                                        $cost_price_f = $cost_price_total / $cost_duty * $invoice_supplier['freight_in'];
                                    } 

                                    $cost_total = $cost_price_f + $cost_price_duty + $cost_price_ex_total;
                                }else{
                                    $cost_total = $invoice_supplier_lists[$i]['invoice_supplier_list_price'] ;
                                }
                            ?>
                            <tr class="odd gradeX">
                                <td><input type="hidden" name="purchase_order_list_id[]" value="<?PHP echo  $invoice_supplier_lists[$i]['purchase_order_list_id'];?>" />
                                   
                                    <input type="hidden" name="invoice_supplier_list_cost[]" value="<?PHP echo  $cost_total;?>" />
                                    <input type="hidden" name="old_cost[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_cost'];?>" />
                                    <input type="hidden" name="old_qty[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_qty'];?>" />
                                    <input type="hidden" name="product_id[]" class="form-control" value="<?php echo $invoice_supplier_lists[$i]['product_id']; ?>" />
                                    <input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="<?php echo $invoice_supplier_lists[$i]['product_code']; ?>"  readonly/>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $invoice_supplier_lists[$i]['product_name']; ?>" />
                                    <input type="text" class="form-control" name="invoice_supplier_list_product_name[]"  placeholder="Product Name (Supplier)"/>
                                    <input type="text" class="form-control" name="invoice_supplier_list_product_detail[]"  placeholder="Product Detail (Supplier)" />
                                    <input type="text" class="form-control" name="invoice_supplier_list_remark[]"  placeholder="Remark" value="<?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_remark']; ?>" />
                                </td>
                                <td>
                                
                                    <select name="stock_group_id[]" class="form-control select" data-live-search="true" >
                                        <option value="">เลือกคลังสินค้า</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($stock_groups) ; $ii++){
                                        ?>
                                        <option value="<?php echo $stock_groups[$ii]['stock_group_id'] ?>" <?PHP if($stock_groups[$ii]['stock_group_id'] == $invoice_supplier_lists[$i]['stock_group_id']){  ?> SELECTED <?PHP } ?> ><?php echo $stock_groups[$ii]['stock_group_name'] ?> </option>
                                        <?
                                        }
                                        ?>
                                    </select>

                                </td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="invoice_supplier_list_qty[]" value="<?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_qty']; ?>" /></td>
                                
                                <?PHP if($sort == "ภายนอกประเทศ"){ ?>
                                    <td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="purchase_order_list_price[]" value="<?php echo $invoice_supplier_lists[$i]['purchase_order_list_price']; ?>" /></td>
                                <?PHP } ?>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="invoice_supplier_list_price[]" value="<?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_price'],4); ?>" /></td>
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
                                <td 
                                <?PHP if($sort == "ภายนอกประเทศ"){ ?>
                                colspan="8" 
                                <?PHP } else { ?>
                                colspan="7" 
                                <?PHP } ?>
                                align="center">
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
                                <td <?PHP if($sort == "ภายนอกประเทศ"){ ?>
                                colspan="3" 
                                <?PHP } else { ?>
                                colspan="2" 
                                <?PHP } ?> rowspan="3">
                                    
                                </td>
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td>
                                <?PHP
                                    if($supplier['vat_type'] == 1){
                                        $total_val = $total - (($supplier['vat']/( 100 + $supplier['vat'] )) * $total);
                                    } else if($supplier['vat_type'] == 2){
                                        $total_val = $total;
                                    } else {
                                        $total_val = $total;
                                    }
                                ?>
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_total_price" name="invoice_supplier_total_price" value="<?PHP echo number_format($total_val,2) ;?>"  readonly/>
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
                                                <input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_vat" name="invoice_supplier_vat" value="<?php echo $supplier['vat'];?>" onchange="calculateAll();"/>
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td>
                                    <?PHP 
                                    if($supplier['vat_type'] == 1){
                                        $vat_val = ($supplier['vat']/( 100 + $supplier['vat'] )) * $total;
                                    } else if($supplier['vat_type'] == 2){
                                        $vat_val = ($supplier['vat']/100) * $total;
                                    } else {
                                        $vat_val = 0.0;
                                    }
                                    ?>
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_vat_price"  name="invoice_supplier_vat_price" value="<?PHP echo number_format($vat_val,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td>
                                    <?PHP 
                                    if($supplier['vat_type'] == 1){
                                        $net_val =  $total;
                                    } else if($supplier['vat_type'] == 2){
                                        $net_val = ($supplier['vat']/100) * $total + $total;
                                    } else {
                                        $net_val = $total;
                                    }
                                    ?>
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_net_price" name="invoice_supplier_net_price" value="<?PHP echo number_format($net_val,2) ;?>" readonly/>
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

$(".purchase-ajax-post").easyAutocomplete(options_purchase);
</script>