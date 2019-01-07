<script>

    var data_buffer = [];

    function check_code(){
        var code = $('#purchase_order_code').val();
        $.post( "controllers/getPurchaseOrderByCodeCheck.php", { 'purchase_order_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("purchase_order_code").focus();
                $("#purchase_check").val(data.purchase_order_id);
                
            } else{
                $("#purchase_check").val("");
            }
        });
    }

    function check_date(id){
        var val_date = $(id).val();
        $.post( "controllers/checkPaperLockByDate.php", { 'date': val_date }, function( data ) {  
            if(data.result){ 
                alert("This "+val_date+" is locked in the system.");
                
                $("#date_check").val("1");
                //$("#purchase_order_date").val(data.date_now);
                $( ".calendar" ).datepicker({ dateFormat: 'dd-mm-yy' });
                document.getElementById("purchase_order_date").focus();
            } else{
                $("#date_check").val("0");
                //generate_credit_date();
            }
        });
    }


    function check(){


  
        var supplier_id = document.getElementById("supplier_id").value;
        var purchase_order_code = document.getElementById("purchase_order_code").value;
        var purchase_order_date = document.getElementById("purchase_order_date").value;
        var purchase_order_credit_term = document.getElementById("purchase_order_credit_term").value;
        var employee_id = document.getElementById("employee_id").value;
        var purchase_check = document.getElementById("purchase_check").value;
        var date_check = document.getElementById("date_check").value;

        supplier_id = $.trim(supplier_id);
        purchase_order_code = $.trim(purchase_order_code);
        purchase_order_date = $.trim(purchase_order_date);
        purchase_order_credit_term = $.trim(purchase_order_credit_term);
        employee_id = $.trim(employee_id);

        if(date_check == "1"){
            alert("This "+purchase_order_date+" is locked in the system.");
            document.getElementById("purchase_order_date").focus();
            return false;
        }else if(purchase_check != ""){
            alert("This "+purchase_order_code+" is already in the system.");
            document.getElementById("purchase_order_code").focus();
            return false;
        }else if(purchase_order_code == ""){
            alert("Please input purchase order code .");
            document.getElementById("purchase_order_code").focus();
            return false;
        }else if(supplier_id.length == 0){
            alert("Please input Supplier");
            document.getElementById("supplier_id").focus();
            return false;
        }else if(purchase_order_date.length == 0){
            alert("Please input purchase order Date");
            document.getElementById("purchase_order_date").focus();
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
        var supplier_id = document.getElementById('supplier_select').value;
        var employee_id = document.getElementById('employee_id').value;
        document.getElementById('supplier_id').value = supplier_id;
        $.post( "controllers/getSupplierByID.php", { 'supplier_id': supplier_id}, function( data ) {
            document.getElementById('supplier_code').value = data.supplier_code;
            document.getElementById('purchase_order_credit_term').value = data.credit_day;
            document.getElementById('supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
        });

        $.post( "controllers/getPurchaseOrderCodeByID.php", { 'supplier_id': supplier_id, 'employee_id':employee_id  }, function( data ) {
            document.getElementById('purchase_order_code').value = data;
            check_code();

        });

        $.post( "controllers/getProductBySupplierID.php", { 'supplier_id': supplier_id }, function( data ) {
            product_data = data;
        });
    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
        update_line();
        calculateAll();
     }

     function update_line(){
        var td_number = $('table[name="tb_list"]').children('tbody').children('tr').children('td:first-child');
        for(var i = 0; i < td_number.length ;i++){
            td_number[i].innerHTML = (i+1);
        }
    }


     
     function show_data(id){
        var product_name = "";
        var data = product_data.filter(val => val['product_id'] == $(id).val());
        if(data.length > 0){
            $(id).closest('tr').children('td').children('input[name="product_id[]"]').val( data[0]['product_id'] );
            $(id).closest('tr').children('td').children('span[name="product_name[]"]').html( data[0]['product_name'] );
            $(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val( data[0]['product_buyprice'] );
            update_sum(id);
        }
        
     }

     function update_sum(id){

          var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="purchase_order_list_qty[]"]').val(  ).replace(',',''));
          var price =  parseFloat($(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val( ).replace(',',''));
          var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="purchase_order_list_price_sum[]"]').val( ).replace(',',''));

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

        $(id).closest('tr').children('td').children('input[name="purchase_order_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="purchase_order_list_price_sum[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();

        
    }



    function show_purchase_order(id){
        var supplier_id = document.getElementById('supplier_id').value;
        var val1 = document.getElementsByName('purchase_request_list_id[]');
        var val2 = document.getElementsByName('customer_purchase_order_list_detail_id[]');
        var val3 = document.getElementsByName('delivery_note_supplier_list_id[]');
        var val4 = document.getElementsByName('regrind_supplier_receive_list_id[]');

        var val5 = document.getElementsByName('request_standard_list_id[]');
        var val6 = document.getElementsByName('request_special_list_id[]');
        var val7 = document.getElementsByName('request_regrind_list_id[]');

        var purchase_request_list_id = [];
        var customer_purchase_order_list_detail_id = [];
        var delivery_note_supplier_list_id = [];
        var regrind_supplier_receive_list_id = [];
        var request_standard_list_id = [];
        var request_special_list_id = [];
        var request_regrind_list_id = [];

        for(var i = 0 ; i < val1.length ; i++){
            purchase_request_list_id.push(val1[i].value);
        }

        for(var i = 0 ; i < val2.length ; i++){
            customer_purchase_order_list_detail_id.push(val2[i].value);
        }

        for(var i = 0 ; i < val3.length ; i++){
            delivery_note_supplier_list_id.push(val3[i].value);
        }

        for(var i = 0 ; i < val4.length ; i++){
            regrind_supplier_receive_list_id.push(val4[i].value);
        }

        for(var i = 0 ; i < val5.length ; i++){
            request_standard_list_id.push(val5[i].value);
        }

        for(var i = 0 ; i < val6.length ; i++){
            request_special_list_id.push(val6[i].value);
        }

        for(var i = 0 ; i < val7.length ; i++){
            request_regrind_list_id.push(val7[i].value);
        }
        
        if(supplier_id != ""){

            $.post( "controllers/getPurchaseOrderListBySupplierID.php", 
            { 
                'type':'<?PHP echo $type;?>',
                'supplier_id': supplier_id,
                'purchase_request_id':'<?PHP echo $purchase_request_id;?>',
                'purchase_request_list_id': JSON.stringify(purchase_request_list_id) ,
                'customer_purchase_order_list_detail_id': JSON.stringify(customer_purchase_order_list_detail_id) ,
                'delivery_note_supplier_list_id': JSON.stringify(delivery_note_supplier_list_id), 
                'regrind_supplier_receive_list_id': JSON.stringify(regrind_supplier_receive_list_id),
                'request_standard_list_id': JSON.stringify(request_standard_list_id),
                'request_special_list_id': JSON.stringify(request_special_list_id),
                'request_regrind_list_id': JSON.stringify(request_regrind_list_id)
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
                                            data[i].purchase_order_list_remark+
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].purchase_order_list_qty +
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].purchase_order_list_price +
                                        '</td>'+
                                        '<td align="right">'+
                                            (data[i].purchase_order_list_qty * data[i].purchase_order_list_price) +
                                        '</td>'+
                                    '</tr>';

                    }
                    
                    $('#bodyAdd').html(content);
                    $('#modalAdd').modal('show');

                }else{
                    //add_row_new(id);
                    alert("ไม่มีรายการสินค้าที่สามารถเปิดใบสั่งซื้อได้");
                }
                
            });
        }else{
            alert("Please select supplier.");
        }
        
    } 

    function search_pop_like(id){
        var supplier_id = document.getElementById('supplier_id').value;
        var val1 = document.getElementsByName('purchase_request_list_id[]');
        var val2 = document.getElementsByName('customer_purchase_order_list_detail_id[]');
        var val3 = document.getElementsByName('delivery_note_supplier_list_id[]');
        var val4 = document.getElementsByName('regrind_supplier_receive_list_id[]');

        var val5 = document.getElementsByName('request_standard_list_id[]');
        var val6 = document.getElementsByName('request_special_list_id[]');
        var val7 = document.getElementsByName('request_regrind_list_id[]');

        var purchase_request_list_id = [];
        var customer_purchase_order_list_detail_id = [];
        var delivery_note_supplier_list_id = [];
        var regrind_supplier_receive_list_id = [];
        var request_standard_list_id = [];
        var request_special_list_id = [];
        var request_regrind_list_id = [];

        for(var i = 0 ; i < val1.length ; i++){
            purchase_request_list_id.push(val1[i].value);
        }

        for(var i = 0 ; i < val2.length ; i++){
            customer_purchase_order_list_detail_id.push(val2[i].value);
        }

        for(var i = 0 ; i < val3.length ; i++){
            delivery_note_supplier_list_id.push(val3[i].value);
        }

        for(var i = 0 ; i < val4.length ; i++){
            regrind_supplier_receive_list_id.push(val4[i].value);
        }

        for(var i = 0 ; i < val5.length ; i++){
            request_standard_list_id.push(val5[i].value);
        }

        for(var i = 0 ; i < val6.length ; i++){
            request_special_list_id.push(val6[i].value);
        }

        for(var i = 0 ; i < val7.length ; i++){
            request_regrind_list_id.push(val7[i].value);
        }
        

        $.post( "controllers/getPurchaseOrderListBySupplierID.php", 
        { 
            'type':'<?PHP echo $type;?>',
            'purchase_request_id':'<?PHP echo $purchase_request_id;?>',
            'supplier_id': supplier_id,
            'purchase_request_list_id': JSON.stringify(purchase_request_list_id) ,
            'customer_purchase_order_list_detail_id': JSON.stringify(customer_purchase_order_list_detail_id) ,
            'delivery_note_supplier_list_id': JSON.stringify(delivery_note_supplier_list_id), 
            'regrind_supplier_receive_list_id': JSON.stringify(regrind_supplier_receive_list_id),
            'request_standard_list_id': JSON.stringify(request_standard_list_id),
            'request_special_list_id': JSON.stringify(request_special_list_id),
            'request_regrind_list_id': JSON.stringify(request_regrind_list_id),
            'search':$(id).val() 
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
                                        data[i].purchase_order_list_remark+
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].purchase_order_list_qty +
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].purchase_order_list_price +
                                    '</td>'+
                                    '<td align="right">'+
                                        (data[i].purchase_order_list_qty * data[i].purchase_order_list_price) +
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
                var customer_purchase_order_list_detail_id = 0;
                var purchase_request_list_id = 0;
                var delivery_note_supplier_list_id = 0;
                var regrind_supplier_receive_list_id = 0;
                var request_standard_list_id = 0;
                var request_special_list_id = 0;
                var request_regrind_list_id = 0;

                if(data_buffer[i].customer_purchase_order_list_detail_id !== undefined){
                    customer_purchase_order_list_detail_id = data_buffer[i].customer_purchase_order_list_detail_id;
                }

                if(data_buffer[i].purchase_request_list_id !== undefined){
                    purchase_request_list_id = data_buffer[i].purchase_request_list_id;
                }

                if(data_buffer[i].delivery_note_supplier_list_id !== undefined){
                    delivery_note_supplier_list_id = data_buffer[i].delivery_note_supplier_list_id;
                }

                if(data_buffer[i].regrind_supplier_receive_list_id !== undefined){
                    regrind_supplier_receive_list_id = data_buffer[i].regrind_supplier_receive_list_id;
                }

                if(data_buffer[i].request_standard_list_id !== undefined){
                    request_standard_list_id = data_buffer[i].request_standard_list_id;
                }

                if(data_buffer[i].request_special_list_id !== undefined){
                    request_special_list_id = data_buffer[i].request_special_list_id;
                }

                if(data_buffer[i].request_regrind_list_id !== undefined){
                    request_regrind_list_id = data_buffer[i].request_regrind_list_id;
                }

                $(id).closest('table').children('tbody').append(
                    '<tr class="odd gradeX">'+
                        '<td class="sorter">'+
                        index+
                        '.</td>'+
                        '<td>'+
                            '<input type="hidden" name="purchase_order_list_id[]" value="0" />'+ 
                            '<input type="hidden" name="product_id[]" value="'+data_buffer[i].product_id+'" />'+
                            '<input type="hidden" name="stock_group_id[]" value="'+data_buffer[i].stock_group_id+'" />'+
                            '<input type="hidden" name="customer_purchase_order_list_detail_id[]" value="'+customer_purchase_order_list_detail_id+'" />'+
                            '<input type="hidden" name="purchase_request_list_id[]" value="'+purchase_request_list_id+'" />'+     
                            '<input type="hidden" name="delivery_note_supplier_list_id[]" value="'+delivery_note_supplier_list_id+'" />'+  
                            '<input type="hidden" name="regrind_supplier_receive_list_id[]" value="'+regrind_supplier_receive_list_id+'" />'+ 
                            '<input type="hidden" name="request_standard_list_id[]" value="'+request_standard_list_id+'" />'+ 
                            '<input type="hidden" name="request_special_list_id[]" value="'+request_special_list_id+'" />'+ 
                            '<input type="hidden" name="request_regrind_list_id[]" value="'+request_regrind_list_id+'" />'+ 
                            '<span>'+data_buffer[i].product_code+'</span>'+
                        '</td>'+
                        '<td>'+
                        '<span>Product name : </span>'+
                        '<span>'+data_buffer[i].product_name+'</span><br>'+
                        '<span>Remark : </span>'+
                        '<input type="text" class="form-control" name="purchase_order_list_remark[]" value="'+data_buffer[i].purchase_order_list_remark+'" />'+
                        '</td>'+
                        '<td><input type="text" class="form-control" name="purchase_order_list_delivery_min[]" readonly /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_qty[]" onchange="update_sum(this);"  value="'+data_buffer[i].purchase_order_list_qty+'"/></td>'+
                        '<td >'+
                            '<input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_price[]" onchange="update_sum(this);" value="'+data_buffer[i].purchase_order_list_price+'"/>'+
                            '<input type="checkbox" name="save_product_price[]" value="'+ data_buffer[i].product_id +'" /> บันทึกราคาซื้อ'+ 
                        '</td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_price_sum[]" onchange="update_sum(this);" value="'+(data_buffer[i].purchase_order_list_qty * data_buffer[i].purchase_order_list_price)+'"/></td>'+
                        
                        '<td>'+
                            '<a href="javascript:;" onclick="product_detail_blank(this);">'+
                                '<i class="fa fa-file-text-o" aria-hidden="true"></i>'+
                            '</a> '+
                            '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                '<i class="fa fa-times" aria-hidden="true"></i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );

                $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="purchase_order_list_delivery_min[]"]').datepicker({ dateFormat: 'dd-mm-yy' });

            }
            
        }
        update_line();
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
                index+
                '.</td>'+
                '<td>'+
                '<input type="hidden" name="purchase_order_list_id[]" value="0" />'+ 
                    '<input type="hidden" name="product_id[]" value="0" />'+ 
                    '<input type="hidden" name="stock_group_id[]" value="0" />'+ 
                    '<input type="hidden" name="customer_purchase_order_list_detail_id[]" value="0" />'+ 
                    '<input type="hidden" name="purchase_request_list_id[]" value="0" />'+     
                    '<input type="hidden" name="delivery_note_supplier_list_id[]" value="0" />'+   
                    '<input type="hidden" name="regrind_supplier_receive_list_id[]" value="0" />'+   
                    '<input type="hidden" name="request_standard_list_id[]" value="0" />'+ 
                    '<input type="hidden" name="request_special_list_id[]" value="0" />'+ 
                    '<input type="hidden" name="request_regrind_list_id[]" value="0" />'+ 
                    '<input type="hidden" name="request_regrind_list_id[]" value="0" />'+ 
                    '<select class="form-control select" onchange="show_data(this);" data-live-search="true" ></select>'+
                '</td>'+
                '<td>'+
                '<span>Product name : </span>'+
                        '<span name="product_name[]" ></span><br>'+
                '<span>Remark</span><br>'+
                '<input type="text" class="form-control" name="purchase_order_list_remark[]" />'+
                '</td>'+
                '<td><input type="text" class="form-control" name="purchase_order_list_delivery_min[]" readonly /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_qty[]"  onchange="update_sum(this);" value="1"/></td>'+
                '<td >'+
                    '<input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_price[]" onchange="update_sum(this);" />'+ 
                    '<input type="checkbox" name="save_product_price[]" value="" /> บันทึกราคาซื้อ'+
                '</td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_price_sum[]" onchange="update_sum(this);" /></td>'+
                
                '<td>'+
                    '<a href="javascript:;" onclick="product_detail_blank(this);">'+
                        '<i class="fa fa-file-text-o" aria-hidden="true"></i>'+
                    '</a> '+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="purchase_order_list_delivery_min[]"]').datepicker({ dateFormat: 'dd-mm-yy' });
        update_line();
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

        var val = document.getElementsByName('purchase_order_list_price_sum[]');
        var total = 0.0;
        
        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $('#purchase_order_total_price').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#purchase_order_vat_price').val((total * ($('#purchase_order_vat').val()/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#purchase_order_net_price').val((total * ($('#purchase_order_vat').val()/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }

    function product_detail_blank(id){
        var product_id = $(id).closest('tr').children('td').children('input[name="product_id[]"]').val();
        if(product_id == ''){
            alert('ไม่มีข้อมูลสินค้านี้');
            $(id).closest('tr').children('td').children('input[name="product_code[]"]').focus();
        }else{
            window.open("?app=product_detail&product_id="+product_id);
        }
    }


</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Purchase Order Management <b style="color:red;">[<?PHP echo $type;?>]</b></h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
               เพิ่มใบสั่งซื้อสินค้า / Add Purchase Order  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=purchase_order&action=add&type=<?PHP echo $type; ?>" >
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                                        <input type="hidden" id="supplier_id" name="supplier_id" value="<?PHP echo $supplier_id; ?>"/>
                                            <select id="supplier_select" name="supplier_select" class="form-control select" onchange="get_supplier_detail()" data-live-search="true" <?PHP if($type == "BLANKED"){?> DISABLED <?}?>>
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($suppliers) ; $i++){
                                            ?>
                                            <option <?php if($suppliers[$i]['supplier_id'] == $supplier['supplier_id']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?>  </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="supplier_address" name="supplier_address" class="form-control" rows="5" readonly><? echo $supplier['supplier_address_1'] ."\n". $supplier['supplier_address_2'] ."\n". $supplier['supplier_address_3'];?></textarea >
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
                                        <label>รหัสใบสั่งซื้อสินค้า / Purchase Order Code <font color="#F00"><b>*</b></font></label>
                                        <input id="purchase_order_code" name="purchase_order_code" class="form-control"  onchange="check_code()" value="<?php echo $last_code;?>" >
                                        <input id="purchase_check" type="hidden" value="" />
                                        <p class="help-block">Example : PO1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบสั่งซื้อสินค้า / Purchase Order Date</label>
                                        <input type="text" id="purchase_order_date" name="purchase_order_date" value="<?PHP echo $first_date; ?>" class="form-control calendar"  onchange="check_date(this);" readonly/>
                                        <input id="date_check" type="hidden" value="" />
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เครดิต (วัน) / Credit term (Day)</label>
                                        <input type="text" id="purchase_order_credit_term" name="purchase_order_credit_term"  class="form-control" value="<?PHP echo $supplier['credit_day'];?>"/>
                                        <p class="help-block">10 </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ออกใบสั่งซื้อ / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option <?PHP if($user[0][0] == $users[$i]['user_id']){?> SELECTED <?PHP }?> value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>จัดส่งโดย / Delivery by</label>
                                        <input type="text" id="purchase_order_delivery_by" name="purchase_order_delivery_by" value="<?PHP echo $supplier['delivery_by'] ?>"  class="form-control"/>
                                        <p class="help-block">DHL </p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่จัดส่ง / Delivery Date</label>
                                        <input type="text" id="purchase_order_delivery_term" name="purchase_order_delivery_term" value="<? echo $purchase_order['purchase_order_delivery_term'];?>"  class="form-control calendar"   onchange="check_date(this);" readonly/>
                                        <input id="date_check" type="hidden" value="" />
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 

                     <div>
                    Our reference :
                    </div>
                    <table width="100%" name="tb_list" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;" width="60">ลำดับ </th>
                                <th style="text-align:center;" width="150">รหัสสินค้า </th>
                                <th style="text-align:center;" >ชื่อสินค้า / หมายเหตุ  </th>
                                <th style="text-align:center;" width="120">วันที่จัดส่ง  </th>
                                <th style="text-align:center;" width="120">จำนวน  </th>
                                <th style="text-align:center;" width="120">ราคาต่หน่วย </th>
                                <th style="text-align:center;" width="120">จำนวนเงิน </th>
                                <th width="24"></th>
                            </tr>
                        </thead>
                        <tbody  class="sorted_table">
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($purchase_order_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td class="sorter">
                                    <?PHP echo ($i + 1); ?>.
                                </td>
                                <td>
                                    <input type="hidden" name="purchase_order_list_id[]" value="0"/>
                                    
                                    <input type="hidden" name="customer_purchase_order_list_detail_id[]" value="<?PHP echo  $purchase_order_lists[$i]['customer_purchase_order_list_detail_id'];?>" />
                                    <input type="hidden" name="purchase_request_list_id[]" value="<?PHP echo  $purchase_order_lists[$i]['purchase_request_list_id'];?>" />
                                    <input type="hidden" name="delivery_note_supplier_list_id[]" value="<?PHP echo  $purchase_order_lists[$i]['delivery_note_supplier_list_id'];?>" />
                                    <input type="hidden" name="regrind_supplier_receive_list_id[]" value="<?PHP echo  $purchase_order_lists[$i]['regrind_supplier_receive_list_id'];?>" />
                                    <input type="hidden" name="request_standard_list_id[]" value="<?PHP echo  $purchase_order_lists[$i]['request_standard_list_id'];?>" />
                                    <input type="hidden" name="request_special_list_id[]" value="<?PHP echo  $purchase_order_lists[$i]['request_special_list_id'];?>" />
                                    <input type="hidden" name="request_regrind_list_id[]" value="<?PHP echo  $purchase_order_lists[$i]['request_regrind_list_id'];?>" />
                                    <input type="hidden" name="stock_group_id[]" value="<?PHP echo  $purchase_order_lists[$i]['stock_group_id'];?>" />
                                    <input type="hidden" name="product_id[]" value="<?PHP echo  $purchase_order_lists[$i]['product_id'];?>" />

                                    <span><?PHP echo  $purchase_order_lists[$i]['product_code'];?></span>
                                </td>
                                <td>
                                    <span>Product name : </span>
                                    <span><?PHP echo  $purchase_order_lists[$i]['product_name'];?></span><br>
                                    <span>Remark.</span>
                                    <input type="text" class="form-control" name="purchase_order_list_remark[]" value="<?php echo $purchase_order_lists[$i]['purchase_order_list_remark']; ?>" />
                                </td>
                                <td>
                                    <input type="text" class="form-control calendar" name="purchase_order_list_delivery_min[]" readonly value="<?php echo $purchase_order_lists[$i]['purchase_order_list_delivery_min']; ?>" />
                                </td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off"  onchange="update_sum(this);" name="purchase_order_list_qty[]" value="<?php echo $purchase_order_lists[$i]['purchase_order_list_qty']; ?>" /></td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" autocomplete="off"  onchange="update_sum(this);" name="purchase_order_list_price[]" value="<?php echo number_format($purchase_order_lists[$i]['purchase_order_list_price'],2); ?>" />
                                    <input type="checkbox" name="save_product_price[]" value="<?php echo $invoice_customer_lists[$i]['product_id']; ?>"/> บันทึกราคาซื้อ
                                </td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" readonly onchange="update_sum(this);" name="purchase_order_list_price_sum[]" value="<?php echo number_format($purchase_order_lists[$i]['purchase_order_list_qty'] * $purchase_order_lists[$i]['purchase_order_list_price'],2); ?>" /></td>
                                
                                <td>
                                    <a href="javascript:;" onclick="product_detail_blank(this);">
                                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                    </a> 
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $purchase_order_lists[$i]['purchase_order_list_qty'] * $purchase_order_lists[$i]['purchase_order_list_price'];
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="8" align="center">
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
                                                        <th style="text-align:center;">รหัสสินค้า  </th>
                                                        <th style="text-align:center;">ชื่อสินค้า  </th>
                                                        <th style="text-align:center;" width="150">จำนวน  </th>
                                                        <th style="text-align:center;" width="150">ราคาต่อหน่วย  </th>
                                                        <th style="text-align:center;" width="150">จำนวนเงิน  </th>
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
                                <td colspan="4" rowspan="3">
                                    
                                </td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
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
                                    <input type="text" class="form-control" style="text-align: right;" id="purchase_order_total_price" name="purchase_order_total_price" value="<?PHP echo number_format($total_val,2) ;?>"  readonly/>
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
                                                <input type="text" class="form-control" style="text-align: right;" id="purchase_order_vat" name="purchase_order_vat" value="<?php echo $supplier['vat'];?>" onchange="calculateAll();" />
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
                                    <input type="text" class="form-control" style="text-align: right;" id="purchase_order_vat_price"  name="purchase_order_vat_price" value="<?PHP echo number_format($vat_val,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
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
                                    <input type="text" class="form-control" style="text-align: right;" id="purchase_order_net_price" name="purchase_order_net_price" value="<?PHP echo number_format($net_val,2) ;?>" readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=purchase_order" class="btn btn-default">Back</a>
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
    $('.sorted_table').sortable({
        handle: ".sorter" , 
        update: function( event, ui ) {
            update_line(); 
        }
    });
</script>