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
	
    function check(){


        var purchase_request_code = document.getElementById("purchase_request_code").value;
        var purchase_request_type = document.getElementById("purchase_request_type").value;
        var employee_id = document.getElementById("employee_id").value;
        var urgent_time = document.getElementById("urgent_time").value;
        var urgent_status = document.getElementById("urgent_status").value;

        
        purchase_request_code = $.trim(purchase_request_code);
        purchase_request_type = $.trim(purchase_request_type);
        employee_id = $.trim(employee_id);
        urgent_time = $.trim(urgent_time);
        urgent_status = $.trim(urgent_status);
        

        if(purchase_request_code.length == 0){
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

    function show_data(id){
        var product_code = $(id).val();
        $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
            if(data != null){
                $('#order_product_name').val(data.product_name)
                $('#order_product').val(data.product_id)
            }
        }); 
    }

    function delete_row(id){
        $(id).closest('tr').remove();
    }

    function add_row(id){

        
        if($('#order_product').val() == ""){
            alert("กรุณาเลือกสินค้า");
            document.getElementById("order_product").focus();
        }else{
            var product_id = $('#order_product').val();
            $.post( "controllers/getProductByID.php", { 'product_id': product_id }, function( data ) {
                
                <?PHP if($type == "STANDARD"){ ?>

                var index = 0;
                if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
                    index = 1;
                }else{
                    index = $(id).closest('table').children('tbody').children('tr').length + 1;
                }

                $(id).closest('table').children('tbody').append(
                    '<tr class="odd gradeX">'+
                        '<td>'+
                            '<input type="hidden" class="form-control" name="purchase_request_list_id[]" value="0" />'+
                            '<input type="hidden" class="form-control" name="product_id[]" value="'+data.product_id+'" />'+
                            '<span>'+data.product_code+'</span>'+
                        '</td>'+
                        '<td>'+
                            'Name : <span>'+data.product_name+'</span><br>'+
                            'Description : <span>'+data.product_description+'</span>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" class="form-control" style="text-align:right;" name="purchase_request_list_qty[]"  value="1"/>'+ 
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
                $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="purchase_request_list_delivery[]"]').datepicker({ dateFormat: 'dd-mm-yy' });
                $('#modalAdd').modal('hide');

                <?PHP } else if($type == "BLANKED") { ?>
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
                                '<td>'+
                                    '<input type="hidden" class="form-control" name="purchase_request_list_id[]" value="0" />'+
                                    '<input type="hidden" class="form-control" name="product_id[]" value="'+data.product_id+'" />'+
                                    '<span>'+data.product_code+'</span>'+
                                '</td>'+
                                '<td>'+
                                    'Name : <span>'+data.product_name+'</span><br>'+
                                    'Description : <span>'+data.product_description+'</span>'+
                                '</td>'+
                                '<td>'+
                                    '<input type="text" class="form-control" style="text-align:right;" name="purchase_request_list_qty[]"  value="'+ $('#order_qty').val() +'"/>'+ 
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
                        $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="purchase_request_list_delivery[]"]').datepicker({ dateFormat: 'dd-mm-yy' }).datepicker('setDate', startDate);

                        startDate.setDate(startDate.getDate() + parseInt($('#order_day').val()));
                        var newDate = startDate.toDateString(); 
                        startDate = new Date( Date.parse( newDate ) ); 

                    }
                    $('#modalAdd').modal('hide');
                }

                <?PHP } ?>
            });
        }
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
        $('employee_id').val($('employee_name').val());
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
            แก้ไขใบร้องขอสั่งซื้อสินค้า / Edit Purchase Request 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=purchase_request&action=edit&id=<?php echo $purchase_request_id;?>" >
                    <input type="hidden"  id="purchase_request_id" name="purchase_request_id" value="<?php echo $purchase_request_id; ?>" />
                    <input type="hidden"  id="purchase_request_date" name="purchase_request_date" value="<?php echo $purchase_request['purchase_request_date']; ?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>หมายเลขใบร้องขอสั่งซื้อสินค้า / PR Code <font color="#F00"><b>* </b></font> <?php if($purchase_request['purchase_request_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $purchase_request['purchase_request_rewrite_no']; ?></font></b> <?PHP } ?></label>
                                <input id="purchase_request_code" name="purchase_request_code" class="form-control"  value="<?PHP echo $purchase_request['purchase_request_code'];?>" readonly>
                                <p class="help-block">Example : PR1801001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ประเภทใบร้องขอสั่งซื้อสินค้า / PR Type <font color="#F00"><b>*</b></font></label>
                                <select id="purchase_request_type" name="purchase_request_type" class="form-control">
                                        <?PHP if($type == "STANDARD"){ ?>
                                        <option value="">Select</option>
                                        <option <?php if($purchase_request['purchase_request_type'] == "Sale"){?> selected <?php }?> >Sale</option>
                                        <option <?php if($purchase_request['purchase_request_type'] == "Use"){?> selected <?php }?> >Use</option>
                                        <?PHP } else if($type=="BLANKED"){ ?>
                                        <option <?php if($purchase_request['purchase_request_type'] == "Sale Blanked"){?> selected <?php }?> >Sale Blanked</option> 
                                        <?PHP } ?>
                                    </select>
                                <p class="help-block">Example : Low.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ผู้ร้องขอ / Request by  <font color="#F00"><b>*</b></font> </label>
                                <input id="employee_id" name="employee_id" type="hidden"  value="<?PHP echo $purchase_request['employee_id'];?>" />
                                        <select id="employee_name" name="employee_name" class="form-control select" onchange="set_employee();"  data-live-search="true" <?PHP if($license_purchase_page != "Medium" && $license_purchase_page != "High"){ ?> disabled <?PHP } ?> >
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($users) ; $i++){
                                    ?>
                                    <option <?php if($users[$i]['user_id'] == $purchase_request['employee_id']){?> selected <?php }?> value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>สำหรับลูกค้า / Customer </label>
                                <select id="customer_id" name="customer_id" class="form-control"  data-live-search="true">
                                    <option value="0">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($customers) ; $i++){
                                    ?>
                                    <option <?php if($customers[$i]['customer_id'] == $purchase_request['customer_id']){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_th'] ?>  </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด (Revel Soft co,ltd).</p>
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
                                    <option <?php if($suppliers[$i]['supplier_id'] == $purchase_request['supplier_id']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?>  </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>หมายเหตุ / Remark</label>
                                <input id="purchase_request_remark" name="purchase_request_remark" class="form-control" value="<? echo $purchase_request['purchase_request_remark'];?>"/>
                                <p class="help-block">Example : -.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>วันที่แจ้งเตือน  / Alert Date</label>
                                <input type="text" id="purchase_request_alert" name="purchase_request_alert" value="<?PHP echo $purchase_request['purchase_request_alert'];?>"  class="form-control calendar" readonly/>
                                <p class="help-block">01-03-2018</p>
                            </div>
                        </div>
                    </div>

                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">รหัสสินค้า <br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า<br>(Product Name)</th>
                                <th style="text-align:center;max-width:100px;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;">วันที่ใช้สินค้า<br>(Delivery Min)</th>
                                <th style="text-align:center;">หมายเหตุ<br>(Remark)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($purchase_request_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" class="form-control" name="purchase_request_list_id[]" value="<?php echo $purchase_request_lists[$i]['purchase_request_list_id']; ?>" />
                                    <input type="hidden" class="form-control" name="product_id[]" value="<?php echo $purchase_request_lists[$i]['product_id']; ?>" />
                                    <span><?php echo $purchase_request_lists[$i]['product_code']; ?></span>
                                </td>
                                <td>
                                    Name : <span><?php echo $purchase_request_lists[$i]['product_name']; ?></span><br>
                                    Description : <span><?php echo $purchase_request_lists[$i]['product_description']; ?></span>
                                </td>
                                <td> 
                                    <input type="text" class="form-control" style="text-align:right;" name="purchase_request_list_qty[]" value="<?php echo $purchase_request_lists[$i]['purchase_request_list_qty']; ?>" />
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
                                <td colspan="6" align="center">
                                    <a href="javascript:;" onclick="split_product(this);" style="color:red;">
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
																	<input class="example-ajax-post form-control" name="order_product_code" onchange="show_data(this);" placeholder="Product Code" />
																</div>
																<div class="col-lg-6">
																	<input class="form-control" id="order_product_name" name="order_product_name"  placeholder="Product Name" readonly />
																</div>
															</div>
															
                                                            <p class="help-block">Example : -.</p>
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