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


        var request_special_code = document.getElementById("request_special_code").value;
        var request_special_type = document.getElementById("request_special_type").value;
        var employee_id = document.getElementById("employee_id").value;
        var urgent_time = document.getElementById("urgent_time").value;
        var urgent_status = document.getElementById("urgent_status").value;

        
        request_special_code = $.trim(request_special_code);
        request_special_type = $.trim(request_special_type);
        employee_id = $.trim(employee_id);
        urgent_time = $.trim(urgent_time);
        urgent_status = $.trim(urgent_status);
        

        if(request_special_code.length == 0){
            alert("Please input Special Tool Request  code");
            document.getElementById("request_special_code").focus();
            return false;
        }else if(request_special_type.length == 0){
            alert("Please input Special Tool Request  type");
            document.getElementById("request_special_type").focus();
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
                $(id).closest('tr').children('td').children('input[name="product_name[]"]').val(data.product_name)
                $(id).closest('tr').children('td').children('input[name="product_id[]"]').val(data.product_id)
            }
        });
        
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
                    '<input type="hidden" class="form-control" name="request_special_list_id[]" value="0" />'+
                     '<input type="hidden" name="product_id[]" class="form-control" />'+
					'<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" />'+ 
				'</td>'+
                '<td>'+
                '<input type="text" class="form-control" name="product_name[]" readonly />'+
                    '<div>หมายเหตุ.</div>'+
                    '<input type="text" class="form-control" name="request_special_list_remark[]" />'+
                '</td>'+
                '<td><input type="text" class="form-control" style="text-align:right;" name="request_special_list_qty[]"  value="1"/></td>'+
                '<td><input type="text" class="form-control" name="request_special_list_delivery[]" readonly /></td>'+
                '<td>'+
                    '<input type="checkbox" class="form-control" name="tool_test_result[]"  value="1" />'+
                '</td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(".example-ajax-post").easyAutocomplete(options);
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="request_special_list_delivery[]"]').datepicker({ dateFormat: 'dd-mm-yy' });
    }


</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Special Tool Request  Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            เพิ่มใบร้องขอสั่งซื้อสินค้าทดลองพิเศษ /  Add Special Tool Request   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=request_special&action=add" >
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ประเภทใบร้องขอสั่งซื้อสินค้าทดลองพิเศษ / SPTR Code <font color="#F00"><b>*</b></font></label>
                                <input id="request_special_code" name="request_special_code" class="form-control" value="<?php echo $last_code;?>" readonly>
                                <p class="help-block">Example : SPTR1801001.</p>
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ผู้ร้องขอ / Request by  <font color="#F00"><b>*</b></font> </label>
                                <select id="employee_id" name="employee_id" class="form-control" >
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($users) ; $i++){
                                    ?>
                                    <option value="<?php echo $users[$i]['user_id']; ?>" <?PHP if( $users[$i]['user_id']== $admin_id){?> SELECTED <?PHP } ?>><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>เปิดใบสั่งซื้อ / Open PO  <font color="#F00"><b>*</b></font> </label>
                                <input type="checkbox" class="form-control" name="purchase_order_open"  value="1" />
                                <p class="help-block">Example : true = เปิด PO, false = เปิด DN.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>สำหรับลูกค้า / Customer </label>
                                <select id="customer_id" name="customer_id" class="form-control" >
                                    <option value="0">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($customers) ; $i++){
                                    ?>
                                    <option value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด (Revel Soft co,ltd).</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ผู้ขาย / Supplier </label>
                                <select id="supplier_id" name="supplier_id" class="form-control"  data-live-search="true">
                                    <option value="0">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($suppliers) ; $i++){
                                    ?>
                                    <option <?php if($suppliers[$i]['supplier_id'] == $request_special['supplier_id']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด (Revel Soft co,ltd).</p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>หมายเหตุ / Remark</label>
                                <input id="request_special_remark" name="request_special_remark" class="form-control" />
                                <p class="help-block">Example : -.</p>
                            </div>
                        </div>
                    </div>

                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">รหัสสินค้า <br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า/หมายเหตุ<br>(Product Name/Remark)</th>
                                <th style="text-align:center;max-width:100px;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;">วันที่ใช้สินค้า<br>(Delivery Min)</th>
                                <th style="text-align:center;width:100px;">ผลทดสอบ<br>(Result)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($request_special_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" class="form-control" name="request_special_list_id[]" value="<?php echo $request_special_lists[$i]['request_special_list_id']; ?>" />
                                    <input type="hidden" name="product_id[]" class="form-control" value="<?php echo $request_special_lists[$i]['product_id']; ?>" />
                                    <input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="<?php echo $request_special_lists[$i]['product_code']; ?>"  readonly/>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $request_special_lists[$i]['product_name']; ?>" />
                                    <input type="text" class="form-control" name="request_special_list_remark[]" value="<?php echo $request_special_lists[$i]['request_special_list_remark']; ?>" />
                                </td>
                                <td><input type="text" class="form-control" style="text-align:right;" name="request_special_list_qty[]" value="<?php echo $request_special_lists[$i]['request_special_list_qty']; ?>" /></td>
                                <td><input type="text" class="form-control calendar" name="request_special_list_delivery[]" readonly value="<?php echo $request_special_lists[$i]['request_special_list_delivery']; ?>" /></td>
                                <td><input type="checkbox" class="form-control" name="tool_test_result[]"  value="1" <?php if($request_special_lists[$i]['tool_test_result'] == '1'){ echo "checked"; } ?> /> </td>
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
                                <td colspan="7" align="center">
                                    <a href="javascript:;" onclick="add_row(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มสินค้า / Add product</span>
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=request_special" class="btn btn-default">Back</a>
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