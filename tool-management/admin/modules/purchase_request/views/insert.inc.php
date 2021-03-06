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

    var stock_group_data = [
    <?php for($i = 0 ; $i < count($stock_groups) ; $i++ ){?>
        {
            stock_group_id:'<?php echo $stock_groups[$i]['stock_group_id'];?>',
            stock_group_name:'<?php echo $stock_groups[$i]['stock_group_name'];?>' 
        },
    <?php }?>
    ];


    var supplier_data = [
    <?php for($i = 0 ; $i < count($suppliers) ; $i++ ){?>
        {
            supplier_id:'<?php echo $suppliers[$i]['supplier_id'];?>',
            supplier_name_th:'<?php echo $suppliers[$i]['supplier_name_th'];?>',
            supplier_name_en:'<?php echo $suppliers[$i]['supplier_name_en'];?>' 
        },
    <?php }?>
    ];
	

    function check_code(id){
        var code = $(id).val();
        $.post( "controllers/getPurchaseRequestByCode.php", { 'purchase_request_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("purchase_request_code").focus();
                $("#purchase_check").val(data.purchase_request_id);
                
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
                //$("#purchase_request_date").val(data.date_now);
                $( ".calendar" ).datepicker({ dateFormat: 'dd-mm-yy' });
                document.getElementById("purchase_request_date").focus();
            } else{
                $("#date_check").val("0");
                //generate_credit_date();
            }
        });
    }
	
    function check(){


        var purchase_request_code = document.getElementById("purchase_request_code").value;
        var purchase_request_date = document.getElementById("purchase_request_date").value;
        var purchase_request_type = document.getElementById("purchase_request_type").value;
        var employee_id = document.getElementById("employee_id").value; 
        var purchase_check = document.getElementById("purchase_check").value;
        var date_check = document.getElementById("date_check").value;

        
        purchase_request_code = $.trim(purchase_request_code);
        purchase_request_type = $.trim(purchase_request_type);
        employee_id = $.trim(employee_id); 
        
        
        if(date_check == "1"){
            alert("This "+purchase_request_date+" is locked in the system.");
            document.getElementById("purchase_request_date").focus();
            return false;
        }else if(purchase_check != ""){
            alert("This "+purchase_request_code+" is already in the system.");
            document.getElementById("purchase_request_code").focus();
            return false;
        }else if(purchase_request_code.length == 0){
            alert("Please input purchase request code");
            document.getElementById("purchase_request_code").focus();
            return false;
        }else if(purchase_request_type.length == 0){
            alert("Please input purchase request type");
            document.getElementById("purchase_request_type").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else{
            return true;
        }



    }

    function show_data_blanked(id){
        var product_code = $(id).val();
        $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
            if(data != null){
                $('#order_product_name').val(data.product_name)
                $('#order_product').val(data.product_id)
            }
        }); 
    }

    function show_data(id){
        var product_code = $(id).val();
        $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
            if(data != null){
                $(id).closest('tr').children('td').children('span[name="product_description[]"]').html(data.product_description)
                $(id).closest('tr').children('td').children('span[name="product_name[]"]').html(data.product_name)
                $(id).closest('tr').children('td').children('input[name="product_id[]"]').val(data.product_id)
            }
        });
    }

    function delete_row(id){
        $(id).closest('tr').remove();
        update_line();
    }

    function update_line(){
        var td_number = $('table[name="tb_list"]').children('tbody').children('tr').children('td:first-child');
        for(var i = 0; i < td_number.length ;i++){
            td_number[i].innerHTML = (i+1);
        }
    }

    function add_row(id){ 
        <?PHP if($type == "STANDARD"){ ?>

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
                    '<input type="hidden" class="form-control" name="purchase_request_list_id[]" value="0" />'+
                    '<input type="hidden" class="form-control" name="product_id[]" value="0" />'+
                    '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="" />'+
                    'Name : <span name="product_name[]"></span><br>'+
                    'Description : <span name="product_description[]"></span>'+
                '</td>'+ 
                '<td>'+
                    '<select  name="supplier_id[]" class="form-control select" data-live-search="true">'+  
                    '</select>'+ 
                '</td>'+
                '<td>'+
                    '<select  name="stock_group_id[]" class="form-control select" data-live-search="true">'+  
                    '</select>'+ 
                '</td>'+
                '<td>'+
                    '<input type="text" class="form-control" style="text-align:right;"  autocomplete="off" name="purchase_request_list_qty[]"  value="1"/>'+ 
                '</td>'+
                '<td><input type="text" class="form-control" name="purchase_request_list_delivery[]" readonly /></td>'+
                '<td><input type="text" class="form-control" name="purchase_request_list_remark[]" /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(".example-ajax-post").easyAutocomplete(options);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="supplier_id[]"]').empty();
        var str = "<option value='0'>ไม่ระบุ</option>";
        $.each(supplier_data, function (index, value) { 
            str += "<option value='" + value['supplier_id'] + "'>" +  value['supplier_name_en'] + "</option>";  
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="supplier_id[]"]').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="supplier_id[]"]').selectpicker();


        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="stock_group_id[]"]').empty();
        var str = "";
        $.each(stock_group_data, function (index, value) { 
            str += "<option value='" + value['stock_group_id'] + "'>" +  value['stock_group_name'] + "</option>"; 
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').selectpicker();


        $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="purchase_request_list_delivery[]"]').datepicker({ dateFormat: 'dd-mm-yy' });
         
        <?PHP } else if($type == "BLANKED") { ?>

        if($('#order_product').val() == ""){
            alert("กรุณาเลือกสินค้า");
            document.getElementById("order_product").focus();
        }else{
            var product_id = $('#order_product').val();
            $.post( "controllers/getProductByID.php", { 'product_id': product_id }, function( data ) {

                if(isNaN($("#order_number").val())){
                    alert("กรุณากรอกจำนวนครั้งการสั่งสินค้า");
                    document.getElementById("order_number").focus();
                }else if (parseInt($("#order_number").val()) <= 0){
                    alert("กรุณากรอกจำนวนครั้งการสั่งสินค้าเป็นตัวเลขที่มากกว่า 0");
                    document.getElementById("order_number").focus();
                }else if(isNaN($("#order_qty").val())){
                    alert("กรุณากรอกจำนวนสินค้าต่อครั้ง");
                    document.getElementById("order_qty").focus();
                }else if (parseInt($("#order_qty").val()) == 0){
                    alert("กรุณากรอกจำนวนสินค้าต่อครั้งเป็นตัวเลขที่มากกว่า 0");
                    document.getElementById("order_qty").focus();
                }else if ($("#order_date").val() == ''){
                    alert("กรุณากรอกวันที่เริ่มต้นรับสินค้า");
                    document.getElementById("order_date").focus();
                }else{
                    var d = $("#order_date").val().split('-');
                    var startDate = new Date(d[2], d[1], d[0]);
                    $(id).closest('table').children('tbody').html('');


                    for(var i = 0 ; i < $("#order_number").val() ; i++){
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
                                    '<input type="hidden" class="form-control" name="purchase_request_list_id[]" value="0" />'+
                                    '<input type="hidden" class="form-control" name="product_id[]" value="'+data.product_id+'" />'+
                                    '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="'+data.product_code+'" />'+
                                    'Name : <span name="product_name[]" >'+data.product_name+'</span><br>'+
                                    'Description : <span name="product_description[]" >'+data.product_description+'</span>'+
                                '</td>'+ 
                                '<td>'+
                                    '<select  name="supplier_id[]" class="form-control select" data-live-search="true">'+  
                                    '</select>'+ 
                                '</td>'+
                                '<td>'+
                                    '<select  name="stock_group_id[]" class="form-control select" data-live-search="true">'+  
                                    '</select>'+ 
                                '</td>'+
                                '<td>'+
                                    '<input type="text" class="form-control" style="text-align:right;"  autocomplete="off" name="purchase_request_list_qty[]"  value="'+ $('#order_qty').val() +'"/>'+ 
                                '</td>'+
                                '<td><input type="text" class="form-control" name="purchase_request_list_delivery[]" readonly /></td>'+
                                '<td><input type="text" class="form-control" name="purchase_request_list_remark[]" /></td>'+
                                '<td>'+
                                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                                    '</a>'+
                                '</td>'+
                            '</tr>'
                        );
                        
                        $(".example-ajax-post").easyAutocomplete(options);

                        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="supplier_id[]"]').empty();
                        var str = "<option value='0'>ไม่ระบุ</option>";
                        $.each(supplier_data, function (index, value) { 
                            str += "<option value='" + value['supplier_id'] + "'>" +  value['supplier_name_en'] + "</option>";  
                        });
                        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="supplier_id[]"]').html(str);

                        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="supplier_id[]"]').selectpicker();


                        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="stock_group_id[]"]').empty();
                        var str = "";
                        $.each(stock_group_data, function (index, value) { 
                            str += "<option value='" + value['stock_group_id'] + "'>" +  value['stock_group_name'] + "</option>"; 
                        });
                        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').html(str);

                        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id[]"]').selectpicker();
 
                        $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="purchase_request_list_delivery[]"]').datepicker({ dateFormat: 'dd-mm-yy' }).datepicker('setDate', startDate);

                        startDate.setDate(startDate.getDate() + parseInt($('#order_day').val()));
                        var newDate = startDate.toDateString(); 
                        startDate = new Date( Date.parse( newDate ) ); 

                    }
                    $('#modalAdd').modal('hide');
                }
            });
        }
        <?PHP } ?>
           
    }

    function split_product(id){
        $('#order_number').val('1');
        $('#order_day').val('30');
        $('#order_qty').val('1');
        $('#order_date').val('<?PHP echo $first_date; ?>');
        $('#order_product').val('');
        $('#modalAdd').modal('show');
    }


    function set_employee(){
        $('#employee_id').val($('#employee_name').val());
        getNewCode();
    }

    function getNewCode(){
        var employee_id = document.getElementById('employee_id').value;  
        $.post( "controllers/getPurchaseRequestCodeIndex.php", { 'employee_id':employee_id }, function( data ) {
            console.log(data);
            document.getElementById('purchase_request_code').value = data;
        });

    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Request Management</h1>
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
            เพิ่มใบร้องขอสั่งซื้อสินค้า /  Add Purchase Request  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=purchase_request&action=add" >
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>รหัสใบร้องขอสั่งซื้อสินค้า <font color="#F00"><b>*</b></font></label>
                                <input id="purchase_request_code" name="purchase_request_code" class="form-control"   onchange="check_code(this)" value="<?php echo $last_code;?>" >
                                <input id="purchase_check" type="hidden" value="" />
                                <p class="help-block">Example : PR1801001.</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>วันที่ร้องขอ</label>
                                <input type="text" id="purchase_request_date" name="purchase_request_date" value="<?PHP echo $purchase_request['purchase_request_date'];?>"  class="form-control calendar" onchange="check_date(this);" readonly/>
                                <input id="date_check" type="hidden" value="" />
                                <p class="help-block">01-03-2018</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ประเภทใบร้องขอสั่งซื้อสินค้า <font color="#F00"><b>*</b></font></label>
                                <select id="purchase_request_type" name="purchase_request_type" class="form-control">
                                        <?PHP if($type == "STANDARD"){ ?>
                                        <option value="">Select</option>
                                        <option>Sale</option>
                                        <option>Use</option>
                                        <?PHP } else if($type=="BLANKED"){ ?>
                                        <option>Sale Blanked</option> 
                                        <?PHP } ?>
                                    </select>
                                <p class="help-block">Example : Low.</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ผู้ร้องขอ  <font color="#F00"><b>*</b></font> </label>
                                <input id="employee_id" name="employee_id" type="hidden"  value="<?PHP echo $admin_id;?>" />
                                <select id="employee_name" name="employee_name" onchange="set_employee();" class="form-control" <?PHP if($license_purchase_page != "Medium" && $license_purchase_page != "High"){ ?> disabled <?PHP } ?> >
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($users) ; $i++){
                                    ?>
                                    <option <?php if($users[$i]['user_id'] == $admin_id){?> selected <?php }?> value="<?php echo $users[$i]['user_id'] ?>" ><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>สำหรับลูกค้า </label>
                                <select id="customer_id" name="customer_id" class="form-control" >
                                    <option value="0">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($customers) ; $i++){
                                    ?>
                                    <option  value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?>  </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด (Revel Soft co,ltd).</p>
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>หมายเหตุ</label>
                                <input id="purchase_request_remark" name="purchase_request_remark" class="form-control" />
                                <p class="help-block">Example : -.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>วันที่แจ้งเตือน </label>
                                <input type="text" id="purchase_request_alert" name="purchase_request_alert" value="<?PHP echo $purchase_request['purchase_request_alert'];?>"  class="form-control calendar" readonly/>
                                <p class="help-block">01-03-2018</p>
                            </div>
                        </div>
                    </div>

                    <table name="tb_list" width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;" width="60">ลำดับ </th>
                                <th style="text-align:center;">รายละเอียดสินค้า  </th> 
                                <th style="text-align:center;">ผู้ขาย </th>
                                <th style="text-align:center;">คลังสินค้า </th>
                                <th style="text-align:center;max-width:80px;">จำนวน </th>
                                <th style="text-align:center;max-width:80px;">วันที่ใช้สินค้า </th>
                                <th style="text-align:center;">หมายเหตุ </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody  class="sorted_table">
                            <?php 
                            for($i=0; $i < count($purchase_request_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td class="sorter">
                                    <?PHP echo ($i + 1); ?>.
                                </td>
                                <td>
                                    <input type="hidden" class="form-control" name="purchase_request_list_id[]" value="<?php echo $purchase_request_lists[$i]['purchase_request_list_id']; ?>" />
                                    <input type="hidden" class="form-control" name="product_id[]" value="<?php echo $purchase_request_lists[$i]['product_id']; ?>" />
                                    <input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="<?php echo $purchase_request_lists[$i]['product_code']; ?>" />
                                    Name : <span name="product_name[]"><?php echo $purchase_request_lists[$i]['product_name']; ?></span><br>
                                    Description : <span name="product_description[]" ><?php echo $purchase_request_lists[$i]['product_description']; ?></span>
                                </td> 
                                <td> 
                                    <select  name="supplier_id[]" class="form-control select" data-live-search="true"> 
                                        <option value='0'>ไม่ระบุ</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($suppliers) ; $ii++){
                                        ?>
                                        <option <?PHP if($suppliers[$ii]['supplier_id'] == $purchase_request_lists[$i]['supplier_id'] ){ ?> SELECTED <?PHP } ?> value="<?php echo $suppliers[$ii]['supplier_id'] ?>"><?php echo $suppliers[$ii]['supplier_name_en'] ?>  </option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td> 
                                    <select  name="stock_group_id[]" class="form-control select" data-live-search="true"> 
                                        <?php 
                                        for($ii =  0 ; $ii < count($stock_groups) ; $ii++){
                                        ?>
                                        <option  <?PHP if($stock_groups[$ii]['stock_group_id'] == $purchase_request_lists[$i]['stock_group_id'] ){ ?> SELECTED <?PHP } ?> value="<?php echo $stock_groups[$ii]['stock_group_id'] ?>"><?php echo $stock_groups[$ii]['stock_group_name'] ?>  </option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td> 
                                <td> 
                                    <input type="text" class="form-control" style="text-align:right;" name="purchase_request_list_qty[]" autocomplete="off" value="<?php echo $purchase_request_lists[$i]['purchase_request_list_qty']; ?>" />
                                </td>
                                <td><input type="text" class="form-control calendar" name="purchase_request_list_delivery[]" readonly value="<?php echo $purchase_request_lists[$i]['purchase_request_list_delivery']; ?>" /></td>
                                <td><input type="text" class="form-control" name="purchase_request_list_remark[]" value="<?php echo $purchase_request_lists[$i]['purchase_request_list_remark']; ?>" /></td>
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
                                <td colspan="8" align="center">
                        <a href="javascript:;" <?PHP if($type == "STANDARD"){ ?> onclick="add_row(this);" <?PHP } else if($type=="BLANKED"){ ?> onclick="split_product(this);" <?PHP } ?> style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <span>เพิ่มสินค้า / Add product</span>
                                    </a>

                                    <div id="modalAdd" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg " role="document">
                                            <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">กำหนดรอบการส่งสินค้า </h4>
                                            </div>

                                            <div  class="modal-body">
                                                <div class="row">
                                                    <div class="col-lg-12" align="left">
                                                        <div class="form-group">
                                                            <label>สินค้า / Product</label>
															<input type="hidden" id="order_product" name="order_product" class="form-control" value="0" />
															<div class="row">
																<div class="col-lg-6">
																	<input class="example-ajax-post form-control" name="order_product_code" onchange="show_data_blanked(this);" placeholder="Product Code" />
																</div>
																<div class="col-lg-6">
																	<input class="form-control" id="order_product_name" name="order_product_name"  placeholder="Product Name" readonly />
																</div>
															</div>
                                
                                                            <p class="help-block">Example : 10.</p>
                                                        </div>
                                                    </div>
                                                    <?PHP if($type == "BLANKED"){ ?>
                                                    <div class="col-lg-6" align="left">
                                                        <div class="form-group">
                                                            <label>จำนวนครั้ง / Number</label>
                                                            <input id="order_number" name="order_number" class="form-control"  value="0" >
                                                            <p class="help-block">Example : 10.</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6" align="left">
                                                        <div class="form-group">
                                                            <label>จำนวนสินค้าต่อครั้ง / Qty per order</label>
                                                            <input id="order_qty" name="order_qty" class="form-control"  value="0" >
                                                            <p class="help-block">Example : 10.</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6" align="left">
                                                        <div class="form-group">
                                                            <label>วันที่รับสินค้า / Date Order</label>
                                                            <input id="order_date" name="order_date" class="form-control calendar"  value="" readonly>
                                                            <p class="help-block">Example : 10.</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6"  align="left">
                                                        <div class="form-group">
                                                            <label>ระยะห่างระหว่างการสั่ง (วัน) / Distance between orders (Day)</label>
                                                            <input id="order_day" name="order_day" class="form-control"  value="0" >
                                                            <p class="help-block">Example : 30.</p>
                                                        </div>
                                                    </div>
                                                    <?PHP } ?>
                                                </div> 
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" onclick="add_row(this);">Add</button>
                                            </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->

                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=purchase_request" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button  type="button" onclick="check_login('form_target');" class="btn btn-success">Save</button>
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
    // Sortable rows
    $('.sorted_table').sortable({
        handle: ".sorter" , 
        update: function( event, ui ) {
            update_line(); 
        }
    });
</script>