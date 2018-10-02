
<script>
    var customer_type = 0;
    var customer_data = [];
    var options = {
        url: function(keyword) {
            return "controllers/getProductByKeyword.php?keyword="+keyword;
        },
        
        list: {
            maxNumberOfElements: 10,
            match: {
                enabled: false
            }
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

    function check(){

        var customer_id = document.getElementById("customer_id").value;
        var customer_purchase_order_code = document.getElementById("customer_purchase_order_code").value;
        var customer_purchase_order_date = document.getElementById("customer_purchase_order_date").value;
        var customer_purchase_order_credit_term = document.getElementById("customer_purchase_order_credit_term").value;
        var employee_id = document.getElementById("employee_id").value;

        customer_id = $.trim(customer_id);
        customer_purchase_order_code = $.trim(customer_purchase_order_code);
        customer_purchase_order_date = $.trim(customer_purchase_order_date);
        customer_purchase_order_credit_term = $.trim(customer_purchase_order_credit_term);
        employee_id = $.trim(employee_id);

        if(customer_id.length == 0){
            alert("Please input Customer");
            document.getElementById("customer_id").focus();
            return false;
        }else if(customer_purchase_order_date.length == 0){
            alert("Please input purchase Order Date");
            document.getElementById("customer_purchase_order_date").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else{
            return true;
        }



    }


    function add_row_from(id,list_id){
        var hold = $(id).closest('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]').children('div').children('div').children('div').children('input[name="stock_hold[]"]');

        var stock_hold = $(id).closest('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]').children('div').children('div').children('div').children('select[name="stock_hold_id[]"]');
        var supplier = $(id).closest('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]').children('div').children('div').children('div').children('select[name="buy_supplier_id[]"]');
        var stock = $(id).closest('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]').children('div').children('div').children('div').children('select[name="stock_group_id[]"]');
        var qty = $(id).closest('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]').children('div').children('div').children('div').children('input[name="qty[]"]');

        var stock_hold_text = $(id).closest('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]').children('div').children('div').children('div').children('select[name="stock_hold_id[]"]').children("option:selected");
        var supplier_text = $(id).closest('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]').children('div').children('div').children('div').children('select[name="buy_supplier_id[]"]').children("option:selected");
        var stock_text = $(id).closest('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]').children('div').children('div').children('div').children('select[name="stock_group_id[]"]').children("option:selected");

        var detail = "";
        var content = "";
        if ($(hold[0]).prop('checked'))
        {
            if($(stock_hold[0]).val() == ""){
                alert("Please select stock hold.");
                $(stock_hold).focus();
                return false;
            }else if($(qty[0]).val() == ""){
                alert("Please input qty.");
                $(qty[0]).focus();
                return false;
            }else if(!$.isNumeric($(qty).val())){
                alert("Please input number of qty.");
                $(qty[0]).focus();
                return false;
            }else{

            
                detail = "คลังสินค้า "+$(stock_hold_text[0]).text()+" จำนวน "+$(qty[0]).val();
                content =   '<li class="list-group-item">'+
                                    '<input type="hidden" name="supplier_id_'+list_id+'[]" value="0" />'+
                                    '<input type="hidden" name="stock_group_id_'+list_id+'[]" value="0" />'+
                                    '<input type="hidden" name="stock_hold_id_'+list_id+'[]" value="'+$(stock_hold[0]).val()+'" />'+
                                    '<input type="hidden" name="qty_'+list_id+'[]" value="'+$(qty[0]).val()+'" />'+
                                    '<input type="hidden" name="customer_purchase_order_list_detail_id_'+list_id+'[]" value="0" />'+
                                    '<a href="javascript:;" class="close" onclick="delete_supplier(this)" >&times;</a>'+
                                    detail+
                            '</li>';
            }
        }else{
            if($(supplier[0]).val() == ""){
                alert("Please select supplier.");
                $(supplier).focus();
                return false;
            }else if($(stock[0]).val() == ""){
                alert("Please select stock.");
                $(stock).focus();
                return false;
            }else if($(qty[0]).val() == ""){
                alert("Please input qty.");
                $(qty[0]).focus();
                return false;
            }else if(!$.isNumeric($(qty).val())){
                alert("Please input number of qty.");
                $(qty[0]).focus();
                return false;
            }else{

            

                detail = "ซื้อจาก "+$(supplier_text[0]).text()+" จำนวน "+$(qty[0]).val()+' ('+$(stock_text[0]).text()+')';
                content =   '<li class="list-group-item">'+
                                    '<input type="hidden" name="supplier_id_'+list_id+'[]" value="'+$(supplier[0]).val()+'" />'+
                                    '<input type="hidden" name="stock_group_id_'+list_id+'[]" value="'+$(stock[0]).val()+'" />'+
                                    '<input type="hidden" name="stock_hold_id_'+list_id+'[]" value="0" />'+
                                    '<input type="hidden" name="qty_'+list_id+'[]" value="'+$(qty[0]).val()+'" />'+
                                    '<input type="hidden" name="customer_purchase_order_list_detail_id_'+list_id+'[]" value="0" />'+
                                    '<a href="javascript:;" class="close" onclick="delete_supplier(this)" >&times;</a>'+
                                    detail+
                            '</li>';
            }
        }

        

        $($(id).closest('td')[0]).append(content);

        var modal = $(id).closest('tr').children('td').children('div[name="modalAdd"]');
        if(modal.length > 0){
            $(modal[0]).modal('hide');
        }

    }

    function show_row_from(id){
       
        var p_id = $(id).closest('tr').children('td').children('input[name="product_id[]"]');

        if(p_id.length > 0){
            $.post( "controllers/getSupplierListByProductID.php", { 'product_id': $(p_id[0]).val()}, function( data ) {

                var modelsupp = $(id).closest('tr').children('td').children('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]')
                                .children('div').children('div').children('div').children('select[name="buy_supplier_id[]"]');
                if(modelsupp.length > 0){
                    var content = "<option value=''>Select Product</option>";
                    $.each(data, function (index, value) {
                        content += "<option value='" + value['supplier_id'] + "'>"+value['supplier_name_en']+"</option>";
                    });
                    $(modelsupp[0]).html(content);
                }

            });

            $.post( "controllers/getStockGroupByProductID.php", { 'product_id': $(p_id[0]).val()}, function( data ) {
                console.log("Product id : ",$(p_id[0]).val());
                console.log("Product id : ",data);
                var modelhold = $(id).closest('tr').children('td').children('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]')
                                .children('div').children('div').children('div').children('select[name="stock_hold_id[]"]');
                if(modelhold.length > 0){
                    var content = "<option value=''>Select Product</option>";
                    $.each(data, function (index, value) {
                        content += "<option value='" + value['stock_group_id'] + "'>"+ value['stock_type_code'] + " "+ value['stock_type_name'] + " -> " + value['stock_group_name'] + " ( Qty : "+ value['stock_report_qty']+" )</option>";
                    });
                    $(modelhold[0]).html(content);
                }

            });

            $.post( "controllers/getStockGroup.php", { 'product_id': $(p_id[0]).val()}, function( data ) {

                var modelstock = $(id).closest('tr').children('td').children('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]')
                                .children('div').children('div').children('div').children('select[name="stock_group_id[]"]');
                if(modelstock.length > 0){
                    var content = "<option value=''>Select Product</option>";
                    $.each(data, function (index, value) {
                        content += "<option value='" + value['stock_group_id'] + "'>"+ value['stock_type_code'] + " "+ value['stock_type_name'] + " -> " + value['stock_group_name'] + "</option>";
                    });
                    $(modelstock[0]).html(content);
                }

            });

            var modal = $(id).closest('tr').children('td').children('div[name="modalAdd"]');
            if(modal.length > 0){
                $(modal[0]).modal('show');
            }
        }
        

    } 

    function changeSupplier (id){
        
        var stock_hold = $(id).closest('div[name="modelBody"]').children('div').children('div').children('div').children('select[name="stock_hold_id[]"]');
        var supplier = $(id).closest('div[name="modelBody"]').children('div').children('div').children('div').children('select[name="buy_supplier_id[]"]');
        var stock = $(id).closest('div[name="modelBody"]').children('div').children('div').children('div').children('select[name="stock_group_id[]"]');

        if ($(id).prop('checked'))
        {
            $(stock_hold[0]).attr("disabled",false);
            $(supplier[0]).attr("disabled",true);
            $(stock[0]).attr("disabled",true);

        }else{

            $(stock_hold[0]).attr("disabled",true);
            $(supplier[0]).attr("disabled",false);
            $(stock[0]).attr("disabled",false);
        }
    }

    function delete_supplier(id){
        $(id).closest('li').remove();
    }

     function delete_row(id){
        $(id).closest('tr').remove();
        update_line(id);
     }


    function update_line(id){
        var td_number = $('table[name="tb_list"]').children('tbody').children('tr').children('td:first-child');
        for(var i = 0; i < td_number.length ;i++){
            td_number[i].innerHTML = (i+1);
        }
    }


     function show_data(id){
        var product_code = $(id).val();
        $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
            if(data != null){
                $(id).closest('tr').children('td').children('input[name="product_name[]"]').val(data.product_name);
                $(id).closest('tr').children('td').children('input[name="product_id[]"]').val(data.product_id);
                $(id).closest('tr').children('td').children('input[name="product_id[]"]').val(data.product_id);
                
                $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_qty[]"]').val('1');


                if(customer_type == 0){
                    $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price[]"]').val(data.product_price_7);
                }else if(customer_type == 1){
                    $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price[]"]').val(data.product_price_6);
                }else if(customer_type == 2){
                    $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price[]"]').val(data.product_price_5);
                }else if(customer_type == 3){
                    $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price[]"]').val(data.product_price_4);
                }else if(customer_type == 4){
                    $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price[]"]').val(data.product_price_3);
                }else if(customer_type == 5){
                    $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price[]"]').val(data.product_price_2);
                }else if(customer_type == 6){
                    $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price[]"]').val(data.product_price_1);
                }
                
                update_sum(id);
            }
        });
        
     }

    function set_data(id){
        var val = customer_data.filter(val => val.customer_code == $(id).val());
        if(val.length > 0){
            $(id).closest('tr').children('td').children('input[name="end_user_id[]"]').val(val[0].customer_id);
        }else{
            $(id).closest('tr').children('td').children('input[name="end_user_id[]"]').val(0);
        }
    }

     function get_customer_detail(){
        var customer_id = document.getElementById('customer_id').value;
        if(customer_id != ''){
            $.post( "controllers/getCustomerByID.php", { 'customer_id': customer_id }, function( data ) {
                customer_type = data.customer_type_id;
                document.getElementById('customer_code').value = data.customer_code;
                document.getElementById('customer_tax').value = data.customer_tax;
                document.getElementById('customer_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
               
            });

            $.post( "controllers/getEndUserByCustomerID.php", { 'customer_id': customer_id }, function( data ) {
                customer_data = data;
                var enduser_options = {
                    data:customer_data,
                    getValue: function(element) {
                        return element.customer_code ;
                    },
                    template: {
                        type: "description",
                        fields: {
                            description: "customer_name_en"
                        }
                    },
                    requestDelay: 400
                };

                $(".find-end-user").easyAutocomplete(enduser_options);
            });
        }
        
    }

    function update_sum(id){

        var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_qty[]"]').val(  ).replace(',',''));
        var price =  parseFloat($(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price[]"]').val( ).replace(',',''));
        var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price_sum[]"]').val( ).replace(',',''));

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

        $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price_sum[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();


    }


    
    function show_delivery_note(id){
        var customer_id = document.getElementById('customer_id').value;
        var val1 = document.getElementsByName('delivery_note_customer_list_id[]');

        var delivery_note_customer_list_id = []; 

        for(var i = 0 ; i < val1.length ; i++){
            delivery_note_customer_list_id.push(val1[i].value);
        }

        if(customer_id != ""){

            $.post( "controllers/getCustomerPurchaseOrderListByCustomerID.php", 
            { 
                'customer_id': customer_id,
                'delivery_note_customer_list_id': JSON.stringify(delivery_note_customer_list_id),
                search : $(id).val()   
             }, function( data ) {
                 
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
                                        '<br>Remark : '+
                                        data[i].customer_purchase_order_list_remark+
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].customer_purchase_order_list_qty +
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
        var customer_id = document.getElementById('customer_id').value;
        var val1 = document.getElementsByName('delivery_note_customer_list_id[]'); 

        var delivery_note_customer_list_id = []; 

        for(var i = 0 ; i < val1.length ; i++){
            delivery_note_customer_list_id.push(val1[i].value);
        }

        $.post( "controllers/getCustomerPurchaseOrderListByCustomerID.php", 
        { 
            'customer_id': customer_id,
            'delivery_note_customer_list_id': JSON.stringify(delivery_note_customer_list_id),
            search : $(id).val()  
        }, function( data ) {
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
                                        data[i].customer_purchase_order_list_remark+
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].customer_purchase_order_list_qty +
                                    '</td>'+ 
                                '</tr>';

                }
            }
            $('#bodyAdd').html(content);
        });
    }

    function add_row(id){
        var customer_id = document.getElementById('customer_id').value;
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
                var delivery_note_customer_list_id = 0; 

                if(data_buffer[i].delivery_note_customer_list_id !== undefined){
                    delivery_note_customer_list_id = data_buffer[i].delivery_note_customer_list_id;
                } 

                $(id).closest('table').children('tbody').append(
                    '<tr class="odd gradeX">'+
                        '<td style="text-align:center;width:80px;" ></td>'+
                        '<td>'+
                            '<input type="hidden" class="form-control" name="delivery_note_customer_list_id[]" value="'+delivery_note_customer_list_id+'" readonly />'+
                            '<input type="hidden" class="form-control" name="customer_purchase_order_list_id[]" value="0" readonly />'+
                            '<input type="hidden" name="product_id[]" class="form-control" />'+
                            '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" />'+ 
                        '</td>'+
                        '<td>'+
                            '<input type="text" class="form-control" name="product_name[]" readonly value="'+data_buffer[i].product_name+'" />'+
                            '<span>Name.</span>'+
                            '<input type="text" class="form-control" name="customer_purchase_order_product_name[]"  />'+
                            '<span>Description.</span>'+
                            '<input type="text" class="form-control" name="customer_purchase_order_product_detail[]"  />'+
                            '<span>Remark.</span>'+
                            '<input type="text" class="form-control" name="customer_purchase_order_list_remark[]" value="'+data_buffer[i].customer_purchase_order_list_remark+'" />'+
                        '</td>'+
                        '<td><input type="text" class="form-control" name="customer_purchase_order_list_qty[]" value="'+data_buffer[i].customer_purchase_order_list_qty+'" onchange="update_sum(this);" /></td>'+
                        '<td>'+
                            '<input type="text" class="form-control" name="customer_purchase_order_list_price[]" onchange="update_sum(this);" />'+
                        '</td>'+
                        '<td><input type="text" class="form-control" name="customer_purchase_order_list_price_sum[]" onchange="update_sum(this);" /></td>'+
                        '<td></td>'+
                        '<td>'+
                            '<input type="hidden" name="end_user_id[]" class="form-control" />'+
                            '<input class="find-end-user form-control" name="end_user_name[]" onchange="set_data(this);" placeholder="End user name." value=""  />'+
                        '</td>'+
                        '<td>'+
                            '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                '<i class="fa fa-times" aria-hidden="true"></i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );
                
                var enduser_options = {
                    data:customer_data,

                    getValue: function(element) {
                        return element.customer_code ;
                    },
                    template: {
                        type: "description",
                        fields: {
                            description: "customer_name_en"
                        }
                    },
                    requestDelay: 400
                };

                $(".find-end-user").easyAutocomplete(enduser_options);

                $(".example-ajax-post").easyAutocomplete(options);
                update_line(id);
                calculateAll();
            }
            
        }
    }



    function add_row_new(id){
        var index = 0;
        var customer_id = document.getElementById('customer_id').value;
         if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
            index = 1;
         }else{
            index = $(id).closest('table').children('tbody').children('tr').length + 1;
         }
        $(id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td style="text-align:center;width:80px;" ></td>'+
                '<td>'+
                    '<input type="hidden" class="form-control" name="delivery_note_customer_list_id[]" value="0" readonly />'+
                    '<input type="hidden" class="form-control" name="customer_purchase_order_list_id[]" value="0" readonly />'+
                    '<input type="hidden" name="product_id[]" class="form-control" />'+
                    '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" />'+ 
                '</td>'+
                '<td>'+
                    '<input type="text" class="form-control" name="product_name[]" readonly />'+
                    '<span>Name.</span>'+
                    '<input type="text" class="form-control" name="customer_purchase_order_product_name[]"  />'+
                    '<span>Description.</span>'+
                    '<input type="text" class="form-control" name="customer_purchase_order_product_detail[]"  />'+
                    '<span>Remark.</span>'+
                    '<input type="text" class="form-control" name="customer_purchase_order_list_remark[]" />'+
                '</td>'+
                '<td><input type="text" class="form-control" name="customer_purchase_order_list_qty[]" onchange="update_sum(this);" /></td>'+
                '<td>'+
                    '<input type="text" class="form-control" name="customer_purchase_order_list_price[]" onchange="update_sum(this);" />'+
                '</td>'+
                '<td><input type="text" class="form-control" name="customer_purchase_order_list_price_sum[]" onchange="update_sum(this);" /></td>'+
                '<td></td>'+
                '<td>'+
                    '<input type="hidden" name="end_user_id[]" class="form-control" />'+
                    '<input class="find-end-user form-control" name="end_user_name[]" onchange="set_data(this);" placeholder="End user name." value=""  />'+
                '</td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        var enduser_options = {
            data:customer_data,

            getValue: function(element) {
                return element.customer_code ;
            },
            template: {
                type: "description",
                fields: {
                    description: "customer_name_en"
                }
            },
            requestDelay: 400
        };

        $(".find-end-user").easyAutocomplete(enduser_options);
        $(".example-ajax-post").easyAutocomplete(options);
        update_line(id);
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

    function calculateAll(){

        var val = document.getElementsByName('customer_purchase_order_list_price_sum[]');
        var total = 0.0;

        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $('#customer_purchase_order_total').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        $('#customer_purchase_order_vat_price').val((total * ($('#customer_purchase_order_vat').val()/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#customer_purchase_order_vat_net').val((total * ($('#customer_purchase_order_vat').val()/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Customer Order Management</h1>
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
            แก้ไขใบสั่งซื้อสินค้าของลูกค้า /  Edit Customer Order
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=customer_purchase_order&action=edit&id=<?php echo $customer_purchase_order_id;?>" enctype="multipart/form-data">
                    <input type="hidden"  id="customer_purchase_order_id" name="customer_purchase_order_id" value="<?php echo $customer_purchase_order_id; ?>" />
                    <input type="hidden"  id="customer_purchase_order_date" name="customer_purchase_order_date" value="<?php echo $customer_purchase_order['customer_purchase_order_date']; ?>" />
                    <input type="hidden"  id="customer_purchase_order_file_o" name="customer_purchase_order_file_o" value="<?php echo $delivery_note_customer['customer_purchase_order_file_o']; ?>" /> 
                
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสลูกค้า / Customer Code <font color="#F00"></font></label>
                                        <input id="customer_code" name="customer_code" class="form-control" value="<?php echo $customer['customer_code'];?>" readonly>
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ลูกค้า / Customer  <font color="#F00"><b>*</b></font> </label>
                                        <select id="customer_id" name="customer_id" class="form-control select" onchange="get_customer_detail()" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($customers) ; $i++){
                                            ?>
                                            <option <?php if($customers[$i]['customer_id'] == $customer_purchase_order['customer_id']){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ / Address <font color="#F00"></font></label>
                                        <textarea  id="customer_address" name="customer_address" class="form-control" rows="7" readonly><?php echo $customer['customer_address_1'] ."\n". $customer['customer_address_2'] ."\n". $customer['customer_address_3'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเลขผู้เสียภาษี / Tax. <font color="#F00"></font></label>
                                        <input id="customer_tax" name="customer_tax" class="form-control" value="<?php echo $customer['customer_tax'];?>" readonly>
                                        <p class="help-block">Example : 0305559003597.</p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark <font color="#F00"></font></label>
                                        <textarea  id="customer_purchase_order_remark" name="customer_purchase_order_remark" class="form-control" rows="7" ><? echo $customer_purchase_order['customer_purchase_order_remark'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-2">
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขที่รับเข้าใบสั่งซื้อ / PO Recieve Code <font color="#F00"><b>*</b></font></label>
                                        <input id="customer_purchase_order_code_gen" name="customer_purchase_order_code_gen" class="form-control" value="<? echo $customer_purchase_order['customer_purchase_order_code_gen'];?>" readonly />
                                        <p class="help-block">Example : PO1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขที่ใบสั่งซื้อ / PO Code <font color="#F00"><b>*</b></font></label>
                                        <input id="customer_purchase_order_code" name="customer_purchase_order_code" class="form-control" value="<? echo $customer_purchase_order['customer_purchase_order_code'];?>" >
                                        <p class="help-block">Example : PO1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบสั่งซื้อ / PO Date</label>
                                        <input type="text" id="customer_purchase_order_date" name="customer_purchase_order_date" value="<? echo $customer_purchase_order['customer_purchase_order_date'];?>"  class="form-control calendar" readonly/>
                                        <p class="help-block">Example : 31-01-2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>จ่ายเงินภายใน (วัน) / Credit term (Day)</label>
                                        <input type="text" id="customer_purchase_order_credit_term" name="customer_purchase_order_credit_term" value="<? echo $customer_purchase_order['customer_purchase_order_credit_term'];?>" class="form-control"/>
                                        <p class="help-block">Example : 10 </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>พนักงานขาย / Sale  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true" >
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option <?php if($users[$i]['user_id'] == $customer_purchase_order['employee_id']){?> selected <?php }?> value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>จัดส่งโดย / Delivery by</label>
                                        <input type="text" id="customer_purchase_order_delivery_by" name="customer_purchase_order_delivery_by" value="<? echo $customer_purchase_order['customer_purchase_order_delivery_by'];?>"  class="form-control"/>
                                        <p class="help-block">Example : DHL </p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ไฟล์แนบ / File </label>
                                        <input accept=".pdf"   type="file" id="customer_purchase_order_file" name="customer_purchase_order_file" >
                                        <p class="help-block">Example : .pdf</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    <div>
                    Our reference :
                    </div>
                    <table width="100%" class="table table-striped table-bordered table-hover" name="tb_list" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">ลำดับ <br>(์No)</th>
                                <th style="text-align:center;">รหัสสินค้า <br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า <br>(Product Name)</th>
                                <th style="text-align:center;" width="96">จำนวน <br>(Qty)</th>
                                <th style="text-align:center;" width="96">ราคา <br>(@)</th>
                                <th style="text-align:center;" width="96">ราคารวม <br>(Amount)</th>
                                <th style="text-align:center;">การสั่งซื้อ<br>(From)</th>
                                <th style="text-align:center;">ขายให้<br>Sale to</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($customer_purchase_order_lists); $i++){
                                $total += $customer_purchase_order_lists[$i]['customer_purchase_order_list_price_sum'];
                                
                            ?>
                            <tr class="odd gradeX">
                                <td style="text-align:center;width:80px;" ><?PHP echo $i+1; ?></td>
                                <td>
                                    <input type="hidden" name="delivery_note_customer_list_id[]" value="<? echo $customer_purchase_order_lists[$i]['delivery_note_customer_list_id'] ?>" />
                                    <input type="hidden" name="customer_purchase_order_list_id[]" value="<? echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_id'] ?>" />
                                    <input type="hidden" name="product_id[]" class="form-control" value="<?php echo $customer_purchase_order_lists[$i]['product_id']; ?>" />
                                    <input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="<?php echo $customer_purchase_order_lists[$i]['product_code']; ?>"  readonly/>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $customer_purchase_order_lists[$i]['product_name']; ?>" />
                                    <span>Name.</span>
                                    <input type="text" class="form-control" name="customer_purchase_order_product_name[]"  value="<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_product_name']; ?>" />
                                    <span>Description.</span>
                                    <input type="text" class="form-control" name="customer_purchase_order_product_detail[]"  value="<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_product_detail']; ?>" />
                                    <span>Remark.</span>
                                    <input type="text" class="form-control" name="customer_purchase_order_list_remark[]" value="<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_remark']; ?>" />
                                </td>
                                <td><input type="text" class="form-control" onchange="update_sum(this);" name="customer_purchase_order_list_qty[]" value="<?php echo number_format($customer_purchase_order_lists[$i]['customer_purchase_order_list_qty'],2); ?>" /></td>
                                <td><input type="text" class="form-control" onchange="update_sum(this);" name="customer_purchase_order_list_price[]" value="<?php echo number_format($customer_purchase_order_lists[$i]['customer_purchase_order_list_price'],2); ?>" /></td>
                                <td><input type="text" class="form-control" onchange="update_sum(this);" name="customer_purchase_order_list_price_sum[]" value="<?php echo number_format($customer_purchase_order_lists[$i]['customer_purchase_order_list_price_sum'],2); ?>" /></td>
                                <td>
                                    <a href="javascript:;" onclick="show_row_from(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มการสั่งซื้อ</span>
                                    </a>

                                    <div name="modalAdd" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg " role="document">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title">เลือกการสั่งซื้อ / Choose from</h4>
                                                </div>

                                                <div  class="modal-body" name="modelBody">
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label>ใช้สินค้าจากคลัง / Stock hold  <font color="#F00"><b>*</b></font> </label>
                                                                <input type="checkbox" onclick="changeSupplier(this)"name="stock_hold[]"  value="1" class="form-group" /> 
                                                                <p class="help-block">Example : true is stock hold.</p>
                                                            </div>
                                                        </div>
                                                    </div>    
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <div class="form-group">
                                                                <label>ดึงจากคลังสินค้า / Hold Stock  <font color="#F00"><b>*</b></font> </label>
                                                                <select  class="form-control " name="stock_hold_id[]"  disabled  >
                                                                    <option value="">Select</option>
                                                                </select>
                                                                <p class="help-block">Example : Main stock.</p>
                                                            </div>
                                                        </div>
                                                    </div>    
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                                                                <select  class="form-control " name="buy_supplier_id[]"  >
                                                                    <option value="">Select</option>
                                                                </select>
                                                                <p class="help-block">Example : revel.</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label>คลังสินค้า / Stock  <font color="#F00"><b>*</b></font> </label>
                                                                <select  class="form-control " name="stock_group_id[]"   >
                                                                    <option value="">Select</option>
                                                                </select>
                                                                <p class="help-block">Example : Main stock.</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label>จำนวนสินค้า / Qty  <font color="#F00"><b>*</b></font> </label>
                                                                <input  class="form-control" name="qty[]" />
                                                                <p class="help-block">Example : 10.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary" onclick="add_row_from(this,'<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_id']; ?>');">Add</button>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->

                                    <ul class="list-group">
                                        <?PHP $cpold = $customer_purchase_order_list_detail_model->getCustomerPurchaseOrderListDetailBy($customer_purchase_order_lists[$i]['customer_purchase_order_list_id']);?>
                                        <?PHP for($ii=0; $ii < count($cpold); $ii++){?>
                                                <li class="list-group-item">
                                                        <input type="hidden" name="supplier_id_<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_id']; ?>[]" value="<?php echo $cpold[$ii]['supplier_id']; ?>" />
                                                        <input type="hidden" name="stock_group_id_<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_id']; ?>[]" value="<?php echo $cpold[$ii]['stock_group_id']; ?>" />
                                                        <input type="hidden" name="stock_hold_id_<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_id']; ?>[]" value="<?php echo $cpold[$ii]['stock_hold_id']; ?>" />
                                                        <input type="hidden" name="qty_<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_id']; ?>[]" value="<?php echo $cpold[$ii]['qty']; ?>" />
                                                        <input type="hidden" name="customer_purchase_order_list_detail_id_<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_id']; ?>[]" value="<?php echo $cpold[$ii]['customer_purchase_order_list_detail_id']; ?>" />
                                                        <a href="javascript:;" class="close" onclick="delete_supplier(this)" >&times;</a>
                                                       <?php if($cpold[$ii]['supplier_id'] == 0){
                                                            echo "คลังสินค้า ".$cpold[$ii]['stock_hold_name']." จำนวน ".$cpold[$ii]['qty'] ; 
                                                       }else{ 
                                                                $name = $cpold[$ii]['supplier_name_en']; 
                                                            echo "ซื้อจาก ".$name." จำนวน ".$cpold[$ii]['qty']." (".$cpold[$ii]['stock_type_code']." ".$cpold[$ii]['stock_type_name']." -> ".$cpold[$ii]['stock_group_name'].")"; 
                                                       }?>
                                                </li>
                                        <?PHP }?>
                                    </ul>

                                    


                                </td>
                                <td>
                                    <input type="hidden" name="end_user_id[]" class="form-control" value="<?php echo $customer_purchase_order_lists[$i]['end_user_id']; ?>"/> 
                                    <input class="find-end-user form-control" name="end_user_name[]" onchange="set_data(this);" placeholder="End user name." value="<?php echo $customer_purchase_order_lists[$i]['end_user_name']; ?>"  />
                                </td>
                                
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
                                <td colspan="9" align="center">
                                    <a href="javascript:;" onclick="show_delivery_note(this);" style="color:red;">
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
                                <td style="max-width:120px;">
                                    <input type="text" class="form-control" style="text-align: right;" id="customer_purchase_order_total" name="customer_purchase_order_total" value="<?PHP echo number_format($total,2) ;?>"  readonly/>
                                </td>
                                <td colspan="3">
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
                                                <input type="text" class="form-control" style="text-align: right;" onchange="calculateAll()" id="customer_purchase_order_vat" name="customer_purchase_order_vat" value="7" />
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td style="max-width:120px;">
                                    <input type="text" class="form-control" style="text-align: right;" id="customer_purchase_order_vat_price"  name="customer_purchase_order_vat_price" value="<?PHP echo number_format(($vat/100) * $total,2) ;?>"  readonly/>
                                </td>
                                <td colspan="3">
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td style="max-width:120px;">
                                    <input type="text" class="form-control" style="text-align: right;" id="customer_purchase_order_vat_net" name="customer_purchase_order_vat_net" value="<?PHP echo number_format(($vat/100) * $total + $total,2) ;?>" readonly/>
                                </td>
                                <td colspan="3"> 
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=customer_purchase_order" class="btn btn-default">Back</a>
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
    get_customer_detail();
    $(".example-ajax-post").easyAutocomplete(options);
</script>