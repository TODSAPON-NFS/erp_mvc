<script>


    var data_buffer = [];
    function check(){


  
        var supplier_id = document.getElementById("supplier_id").value;
        var request_test_code = document.getElementById("request_test_code").value;
        var request_test_date = document.getElementById("request_test_date").value;
        var employee_id = document.getElementById("employee_id").value;
        
        supplier_id = $.trim(supplier_id);
        request_test_code = $.trim(request_test_code);
        request_test_date = $.trim(request_test_date);
        employee_id = $.trim(employee_id);

        if(supplier_id.length == 0){
            alert("Please input Supplier");
            document.getElementById("supplier_id").focus();
            return false;
        }else if(request_test_date.length == 0){
            alert("Please input Request Test Date");
            document.getElementById("request_test_date").focus();
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
            document.getElementById('supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
        });

    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
     }


    function show_request_test(id){
        var supplier_id = document.getElementById('supplier_id').value;
        var val1 = document.getElementsByName('request_standard_list_id[]');
        var val2 = document.getElementsByName('request_special_list_id[]');
        var val3 = document.getElementsByName('request_regrind_list_id[]');

        var request_standard_list_id = [];
        var request_special_list_id = [];
        var request_regrind_list_id = [];

        for(var i = 0 ; i < val1.length ; i++){
            request_standard_list_id.push(val1[i].value);
        }

        for(var i = 0 ; i < val2.length ; i++){
            request_special_list_id.push(val2[i].value);
        }

        for(var i = 0 ; i < val3.length ; i++){
            request_regrind_list_id.push(val3[i].value);
        }

        
        if(supplier_id != ""){

            $.post( "controllers/getRequestTestListBySupplierID.php", 
            { 
                'supplier_id': supplier_id,
                'request_standard_list_id': JSON.stringify(request_standard_list_id) ,
                'request_special_list_id': JSON.stringify(request_special_list_id) ,
                'request_regrind_list_id': JSON.stringify(request_regrind_list_id) ,
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
                                            data[i].request_test_list_remark+
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].request_test_list_qty +
                                        '</td>'+
                                    '</tr>';

                    }
                    
                    $('#bodyAdd').html(content);
                    $('#modalAdd').modal('show');

                }
                
            });
        }else{
            alert("Please select supplier.");
        }
        
    } 

    function search_pop_like(id){
        var supplier_id = document.getElementById('supplier_id').value;
        var val1 = document.getElementsByName('request_standard_list_id[]');
        var val2 = document.getElementsByName('request_special_list_id[]');
        var val3 = document.getElementsByName('request_regrind_list_id[]');

        var request_standard_list_id = [];
        var request_special_list_id = [];
        var request_regrind_list_id = [];

        for(var i = 0 ; i < val1.length ; i++){
            request_standard_list_id.push(val1[i].value);
        }

        for(var i = 0 ; i < val2.length ; i++){
            request_special_list_id.push(val2[i].value);
        }

        for(var i = 0 ; i < val3.length ; i++){
            request_regrind_list_id.push(val3[i].value);
        }
        
        $.post( "controllers/getRequestTestListBySupplierID.php", 
        { 
            'supplier_id': supplier_id,
            'request_standard_list_id': JSON.stringify(request_standard_list_id) ,
            'request_special_list_id': JSON.stringify(request_special_list_id) ,
            'request_regrind_list_id': JSON.stringify(request_regrind_list_id) ,
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
                                        data[i].request_test_list_remark+
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].request_test_list_qty +
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
                var request_standard_list_id = 0;
                var request_special_list_id = 0;
                var request_regrind_list_id = 0;

                if(data_buffer[i].request_standard_list_id !== undefined){
                    request_standard_list_id = data_buffer[i].request_standard_list_id;
                }

                if(data_buffer[i].request_special_list_id !== undefined){
                    request_special_list_id = data_buffer[i].request_special_list_id;
                }

                if(data_buffer[i].request_regrind_list_id !== undefined){
                    request_regrind_list_id = data_buffer[i].request_regrind_list_id;
                }

                if(data_buffer[i].regrind_supplier_receive_list_id !== undefined){
                    regrind_supplier_receive_list_id = data_buffer[i].regrind_supplier_receive_list_id;
                }

                $(id).closest('table').children('tbody').append(
                    '<tr class="odd gradeX">'+
                        '<td>'+
                            '<input type="hidden" name="product_id[]" value="'+data_buffer[i].product_id+'" />'+
                            '<input type="hidden" name="request_standard_list_id[]" value="'+request_standard_list_id+'" />'+
                            '<input type="hidden" name="request_special_list_id[]" value="'+request_special_list_id+'" />'+     
                            '<input type="hidden" name="request_regrind_list_id[]" value="'+request_regrind_list_id+'" />'+  
                            '<span>'+data_buffer[i].product_code+'</span>'+
                        '</td>'+
                        '<td>'+
                        '<span>Product name : </span>'+
                        '<span>'+data_buffer[i].product_code+'</span><br>'+
                        '<span>Remark : </span>'+
                        '<input type="text" class="form-control" name="request_test_list_remark[]" value="'+data_buffer[i].request_test_list_remark+'" />'+
                        '</td>'+                        
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="request_test_list_qty[]" value="'+data_buffer[i].request_test_list_qty+'"/></td>'+
                        '<td><input type="text" class="form-control calendar" name="request_test_list_delivery[]" readonly /></td>'+
                        '<td>'+
                            '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                '<i class="fa fa-times" aria-hidden="true"></i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );

                 $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="request_test_list_delivery[]"]').datepicker({ dateFormat: 'dd-mm-yy' });

            }
            
        }
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


</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Request Test Management</h1>
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
               เพิ่มใบสั่งสินค้าทดลองสินค้า / Add Request Test  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=request_test&action=add" >
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
                                        <label>รหัสใบสั่งสินค้าทดลองสินค้า / Request Test Code <font color="#F00"><b>*</b></font></label>
                                        <input id="request_test_code" name="request_test_code" class="form-control" value="<?php echo $request_test['request_test_code'];?>" readonly>
                                        <p class="help-block">Example : PO1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบสั่งสินค้าทดลองสินค้า / Request Test Date</label>
                                        <input type="text" id="request_test_date" name="request_test_date" value="<?PHP echo $request_test['request_test_date']; ?>" class="form-control calendar" readonly/>
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div> 
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ออกใบสั่งสินค้าทดลอง / Employee  <font color="#F00"><b>*</b></font> </label>
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
                                <th style="text-align:center;">ชื่อสินค้า/หมายเหตุ<br>(Product Name/Remark)</th>
                                <th style="text-align:center;max-width:100px;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;">วันที่ใช้สินค้า<br>(Delivery Min)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($request_test_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" class="form-control" name="product_id[]" value="<?php echo $request_test_lists[$i]['product_id']; ?>" />
                                    
                                    <input type="hidden" class="form-control" name="request_test_list_id[]" value="<?php echo $request_test_lists[$i]['request_test_list_id']; ?>" />
                                    <input type="hidden" class="form-control" name="request_standard_list_id[]" value="<?php echo $request_test_lists[$i]['request_standard_list_id']; ?>" />
                                    <input type="hidden" class="form-control" name="request_special_list_id[]" value="<?php echo $request_test_lists[$i]['request_special_list_id']; ?>" />
                                    <input type="hidden" class="form-control" name="request_regrind_list_id[]" value="<?php echo $request_test_lists[$i]['request_regrind_list_id']; ?>" />

                                    <span><?php echo $request_test_lists[$i]['product_code']; ?></span>
                                </td>
                                <td>
                                    <span>Product name : </span>
                                    <span><?php echo $request_test_lists[$i]['product_name']; ?></span><br>
                                    <span>Remark : </span>
                                    <input type="text" class="form-control" name="request_test_list_remark[]" value="<?php echo $request_test_lists[$i]['request_test_list_remark']; ?>" />
                                
                        
                                </td>
                                <td><input type="text" class="form-control" style="text-align:right;" name="request_test_list_qty[]" value="<?php echo $request_test_lists[$i]['request_test_list_qty']; ?>" /></td>
                                <td><input type="text" class="form-control calendar" name="request_test_list_delivery[]" readonly value="<?php echo $request_test_lists[$i]['request_test_list_delivery']; ?>" /></td>
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
                                    <a href="javascript:;" onclick="show_request_test(this);" style="color:red;">
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
                                                <button type="button" class="btn btn-primary" onclick="add_row(this);">Add Product</button>
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
                        <div class="col-lg-offset-6 col-lg-6" align="right">
                            <a href="index.php?app=request_test" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <a href="index.php?app=request_test&action=sending&id=<?php echo $request_test_id;?>&supplier_id=<?PHP echo $request_test['supplier_id']; ?>" class="btn btn-warning" >Send Request</a>
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