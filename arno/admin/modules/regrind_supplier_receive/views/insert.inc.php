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
            data.keyword = $(".example-ajax-post:focus").val();
            return data;
        },

        requestDelay: 400
    };

    function check(){


        var regrind_supplier_receive_code = document.getElementById("regrind_supplier_receive_code").value;
        var employee_id = document.getElementById("employee_id").value;
        var supplier_id = document.getElementById("supplier_id").value;
        var regrind_supplier_receive_date = document.getElementById("regrind_supplier_receive_date").value;
        var contact_name = document.getElementById("contact_name").value;
        
        regrind_supplier_receive_code = $.trim(regrind_supplier_receive_code);
        regrind_supplier_receive_date = $.trim(regrind_supplier_receive_date);
        employee_id = $.trim(employee_id);
        supplier_id = $.trim(supplier_id);
        contact_name = $.trim(contact_name);
        

        if(supplier_id.length == 0){
            alert("Please input supplier");
            document.getElementById("supplier_id").focus();
            return false;
        }else if(regrind_supplier_receive_code.length == 0){
            alert("Please input Regrind supplier receive code");
            document.getElementById("regrind_supplier_receive_code").focus();
            return false;
        }else if(regrind_supplier_receive_date.length == 0){
            alert("Please input Regrind supplier receive date");
            document.getElementById("regrind_supplier_receive_date").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else if(contact_name.length == 0){
            alert("Please input contact name");
            document.getElementById("contact_name").focus();
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

     function show_data(id){
        var product_code = $(id).val();
        $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
            if(data != null){
                $(id).closest('tr').children('td').children('input[name="product_name[]"]').val(data.product_name)
                $(id).closest('tr').children('td').children('input[name="product_id[]"]').val(data.product_id)
            }
        });
     }



    function show_regrind (id){
        var supplier_id = document.getElementById('supplier_id').value;
        var val = document.getElementsByName('regrind_supplier_list_id[]');
        var regrind_supplier_list_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            regrind_supplier_list_id.push(val[i].value);
        }
        //console.log(supplier_id);
        //console.log(regrind_supplier_list_id);
        if(supplier_id != ""){

            $.post( "controllers/getRegrindSupplierListBySupplierID.php", { 'supplier_id': supplier_id, 'regrind_supplier_list_id': JSON.stringify(regrind_supplier_list_id) }, function( data ) {
                //console.log(data);
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
                                        data[i].regrind_supplier_list_qty +
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].regrind_supplier_list_remark+
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
        var val = document.getElementsByName('regrind_supplier_list_id[]');
        var regrind_supplier_list_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            regrind_supplier_list_id.push(val[i].value);
        }

        $.post( "controllers/getRegrindSupplierListBySupplierID.php", { 'supplier_id': supplier_id, 'regrind_supplier_list_id': JSON.stringify(regrind_supplier_list_id), search : $(id).val() }, function( data ) {
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
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].regrind_supplier_list_qty +
                                    '</td>'+
                                    '<td align="right">'+
                                        data[i].regrind_supplier_list_remark+
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
                            '<input type="hidden" name="regrind_supplier_list_id[]" value="'+data_buffer[i].regrind_supplier_list_id+'" />'+
                            '<input type="hidden" name="regrind_supplier_receive_list_id[]" value="0" />'+     
                            '<input type="hidden" name="product_id[]" class="form-control" />'+
							'<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" />'+ 
						'</td>'+
                        '<td><input type="text" class="form-control" name="product_name[]" value="'+ data_buffer[i].product_name+ '" readonly /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="regrind_supplier_receive_list_qty[]"  value="'+data_buffer[i].regrind_supplier_receive_list_qty+'" /></td>'+
                        '<td><input type="text" class="form-control" name="regrind_supplier_receive_list_remark[]" value="'+data_buffer[i].regrind_supplier_receive_list_remark+'"/></td>'+
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
                    '<input type="hidden" name="regrind_receive_list_id[]" value="0" />'+
                    '<input type="hidden" name="regrind_supplier_receive_list_id[]" value="0" />'+     
                    '<input type="hidden" name="product_id[]" class="form-control" />'+
					'<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" />'+ 
				'</td>'+
                '<td><input type="text" class="form-control" name="product_name[]" readonly /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="regrind_supplier_receive_list_qty[]"  /></td>'+
                '<td><input type="text" class="form-control" name="regrind_supplier_receive_list_remark[]" /></td>'+
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

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Regrind supplier receive  Management</h1>
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
            เพิ่มใบรีกายร์สินค้าจากผู้ขาย /  Add Regrind supplier receive   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=regrind_supplier_receive&action=add" enctype="multipart/form-data">
                <div class="row">
                        <div class="col-lg-5">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-7">
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
                                        <label>ชื่อผู้ติดต่อ / Contact name <font color="#F00"><b>*</b></font></label>
                                        <input id="contact_name" name="contact_name" class="form-control" >
                                        <p class="help-block">Example : Somchai Wongnai.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="supplier_address" name="supplier_address" class="form-control" rows="5" readonly><? echo $supplier['supplier_address_1'] ."\n". $supplier['supplier_address_2'] ."\n". $supplier['supplier_address_3'];?></textarea >
                                        <p class="help-block">Example : -.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                        </div>
                        <div class="col-lg-5">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเลขใบรีกายร์ / RG Code <font color="#F00"><b>*</b></font></label>
                                        <input id="regrind_supplier_receive_code" name="regrind_supplier_receive_code" class="form-control" value="<?php echo $last_code;?>" readonly>
                                        <p class="help-block">Example : RG1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบรีกายร์ / RG Date</label>
                                        <input type="text" id="regrind_supplier_receive_date" name="regrind_supplier_receive_date" value="<?PHP echo $first_date;?>"  class="form-control calendar" readonly/>
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้รับสินค้า / Employee  <font color="#F00"><b>*</b></font> </label>
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
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark</label>
                                        <textarea id="regrind_supplier_receive_remark" name="regrind_supplier_receive_remark"  class="form-control"></textarea>
                                        <p class="help-block">DHL </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ไฟล์แนบ / File </label>
                                        <input accept=".pdf"   type="file" id="regrind_supplier_receive_file" name="regrind_supplier_receive_file" >
                                        <p class="help-block">Example : .pdf</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">รหัสสินค้า<br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า<br>(Product Name)</th>
                                <th style="text-align:center;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;">หมายเหตุ<br>(Remark)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($regrind_supplier_receive_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="regrind_supplier_list_id[]" value="<?php echo $regrind_supplier_receive_lists[$i]['regrind_supplier_list_id']; ?>" />
                                    <input type="hidden" name="regrind_supplier_receive_list_id[]" value="0" />
                                    <input type="hidden" name="product_id[]" class="form-control" value="<?php echo $regrind_supplier_receive_lists[$i]['product_id']; ?>" />
                                    <input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="<?php echo $regrind_supplier_receive_lists[$i]['product_code']; ?>"  readonly/>
                                </td>
                                <td><input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $regrind_supplier_receive_lists[$i]['product_name']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  name="regrind_supplier_receive_list_qty[]" value="<?php echo $regrind_supplier_receive_lists[$i]['regrind_supplier_receive_list_qty']; ?>" /></td>
                                <td><input type="text" class="form-control" name="regrind_supplier_receive_list_remark[]" value="<?php echo $regrind_supplier_receive_lists[$i]['regrind_supplier_receive_list_remark']; ?>" /></td>
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
                                <td colspan="5" align="center">
                                    <a href="javascript:;" onclick="show_regrind(this);" style="color:red;">
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
                                                        <th style="text-align:center;" width="100">จำนวน <br> (Qty)</th>
                                                        <th style="text-align:center;" >หมายเหตุ <br> (Remark) </th>
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
                        </tfoot>
                    </table> 

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=regrind_supplier_receive" class="btn btn-default">Back</a>
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
</script>