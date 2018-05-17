<script>
    var product_data = [
    <?php for($i = 0 ; $i < count($products) ; $i++ ){?>
        {
            product_id:'<?php echo $products[$i]['product_id'];?>',
            product_code:'<?php echo $products[$i]['product_code'];?>',
            product_name:'<?php echo $products[$i]['product_name'];?>'
        },
    <?php }?>
    ];

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
       
        var p_id = $(id).closest('tr').children('td').children('div').children('select[name="product_id[]"]');

        if(p_id.length > 0){
            $.post( "controllers/getSupplierListByProductID.php", { 'product_id': $(p_id[0]).val()}, function( data ) {

                var modelsupp = $(id).closest('tr').children('td').children('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]')
                                .children('div').children('div').children('div').children('select[name="buy_supplier_id[]"]');
                if(modelsupp.length > 0){
                    var content = "<option value=''>Select Product</option>";
                    $.each(data, function (index, value) {
                        content += "<option value='" + value['supplier_id'] + "'>"+value['supplier_name_th']+"("+value['supplier_name_en']+")</option>";
                    });
                    $(modelsupp[0]).html(content);
                }

            });

            $.post( "controllers/getStockGroupByProductID.php", { 'product_id': $(p_id[0]).val()}, function( data ) {

                var modelhold = $(id).closest('tr').children('td').children('div[name="modalAdd"]').children('div').children('div').children('div[name="modelBody"]')
                                .children('div').children('div').children('div').children('select[name="stock_hold_id[]"]');
                if(modelhold.length > 0){
                    var content = "<option value=''>Select Product</option>";
                    $.each(data, function (index, value) {
                        content += "<option value='" + value['stock_group_id'] + "'>"+ value['stock_type_code'] + " "+ value['stock_type_name'] + " -> " + value['stock_group_name'] + "</option>";
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
     }



     function show_data(id){
        var product_name = "";
        var data = product_data.filter(val => val['product_id'] == $(id).val());
        if(data.length > 0){
            $(id).closest('tr').children('td').children('input[name="product_name[]"]').val( data[0]['product_name'] );
        }
        
     }



     function add_row(id){
         var index = 0;
         if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
            index = 1;
         }else{
            index = $(id).closest('table').children('tbody').children('tr').length + 1;
         }
        $(id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td>'+
                    '<input type="hidden" class="form-control" name="customer_purchase_order_list_id[]" value="0" readonly />'+
                    '<select class="form-control select" type="text" name="product_id[]" onchange="show_data(this);" data-live-search="true" ></select>'+
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
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').empty();
        var str = "<option value=''>Select Product</option>";
        $.each(product_data, function (index, value) {
            str += "<option value='" + value['product_id'] + "'>"+value['product_code']+"</option>";
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').selectpicker();
    }

     function get_customer_detail(){
        var customer_id = document.getElementById('customer_id').value;
        if(customer_id != ''){
            $.post( "controllers/getCustomerByID.php", { 'customer_id': customer_id }, function( data ) {
                document.getElementById('customer_code').value = data.customer_code;
                document.getElementById('customer_tax').value = data.customer_tax;
                document.getElementById('customer_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
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
               เพิ่มใบสั่งซื้อสินค้าของลูกค้า / Add Customer Order  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=customer_purchase_order&action=add" enctype="multipart/form-data">
                <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสลูกค้า / Customer Code <font color="#F00"></font></label>
                                        <input id="customer_code" name="customer_code" class="form-control" value="<? echo $customer['customer_code'];?>" readonly>
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
                                            <option <?php if($customers[$i]['customer_id'] == $customer_purchase_order['customer_id']){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> (<?php echo $customers[$i]['customer_name_th'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ / Address <font color="#F00"></font></label>
                                        <textarea  id="customer_address" name="customer_address" class="form-control" rows="7" readonly><? echo $customer['customer_address_1'] ."\n". $customer['customer_address_2'] ."\n". $customer['customer_address_3'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเลขผู้เสียภาษี / Tax. <font color="#F00"></font></label>
                                        <input id="customer_tax" name="customer_tax" class="form-control" value="<? echo $customer['customer_tax'];?>" readonly>
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
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">รหัสสินค้า <br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า <br>(Product Name)</th>
                                <th style="text-align:center;" width="96">จำนวน <br>(Qty)</th>
                                <th style="text-align:center;" width="96">ราคา <br>(@)</th>
                                <th style="text-align:center;" width="96">ราคารวม <br>(Amount)</th>
                                <th style="text-align:center;">การสั่งซื้อ<br>(From)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($customer_purchase_order_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="customer_purchase_order_list_id[]" value="<? echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_id'] ?>" />
                                    <select  class="form-control select" name="product_id[]" onchange="show_data(this);" data-live-search="true" >
                                        <option value="">Select</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($products) ; $ii++){
                                        ?>
                                        <option <?php if($products[$ii]['product_id'] == $customer_purchase_order_lists[$i]['product_id']){?> selected <?php }?> value="<?php echo $products[$ii]['product_id'] ?>"><?php echo $products[$ii]['product_code'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
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
                                                                <select  class="form-control " name="stock_hold_id[]" disabled  >
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
                                                            echo "ซื้อจาก ".$cpold[$ii]['supplier_name_th']." จำนวน ".$cpold[$ii]['qty']." (".$cpold[$ii]['stock_type_code']." ".$cpold[$ii]['stock_type_name']." -> ".$cpold[$ii]['stock_group_name'].")"; 
                                                       }?>
                                                </li>
                                        <?PHP }?>
                                    </ul>

                                    


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
                                <td>
                                    
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <a href="javascript:;" onclick="add_row(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </a>
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