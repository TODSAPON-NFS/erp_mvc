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


        var delivery_note_customer_code = document.getElementById("delivery_note_customer_code").value;
        var employee_id = document.getElementById("employee_id").value;
        var customer_id = document.getElementById("customer_id").value;
        var delivery_note_customer_date = document.getElementById("delivery_note_customer_date").value;
        var contact_name = document.getElementById("contact_name").value;
        
        delivery_note_customer_code = $.trim(delivery_note_customer_code);
        delivery_note_customer_date = $.trim(delivery_note_customer_date);
        employee_id = $.trim(employee_id);
        customer_id = $.trim(customer_id);
        contact_name = $.trim(contact_name);
        

        if(customer_id.length == 0){
            alert("Please input Customer");
            document.getElementById("customer_id").focus();
            return false;
        }else if(delivery_note_customer_code.length == 0){
            alert("Please input delivery note Customer code");
            document.getElementById("delivery_note_customer_code").focus();
            return false;
        }else if(delivery_note_customer_date.length == 0){
            alert("Please input delivery note Customer date");
            document.getElementById("delivery_note_customer_date").focus();
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



    function get_customer_detail(){
        var customer_id = document.getElementById('customer_id').value;
        $.post( "controllers/getCustomerByID.php", { 'customer_id': customer_id }, function( data ) {
            document.getElementById('customer_code').value = data.customer_code;
            document.getElementById('customer_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
        });
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
                    '<input type="hidden" name="customer_delivery_note_customer_list_id[]" value="0" />'+
                    '<input type="hidden" name="delivery_note_customer_list_id[]" value="0" />'+     
                    '<select class="form-control select" type="text" name="product_id[]" onchange="show_data(this);" data-live-search="true" ></select>'+
                '</td>'+
                '<td><input type="text" class="form-control" name="product_name[]" readonly /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="delivery_note_customer_list_qty[]"  /></td>'+
                '<td><input type="text" class="form-control" name="delivery_note_customer_list_remark[]" /></td>'+
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

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Delivery Note Customer  Management</h1>
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
            เพิ่มใบยืมสินค้าสำหรับลูกค้า /  Add delivery note Customer   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=delivery_note_customer&action=add" enctype="multipart/form-data">
                <div class="row">
                        <div class="col-lg-5">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label>รหัสลูกค้า / Customer Code <font color="#F00"><b>*</b></font></label>
                                        <input id="customer_code" name="customer_code" class="form-control" value="<? echo $customer['customer_code'];?>" readonly>
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <label>ลูกค้า / Customer  <font color="#F00"><b>*</b></font> </label>
                                        <select id="customer_id" name="customer_id" class="form-control select" onchange="get_customer_detail()" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($customers) ; $i++){
                                            ?>
                                            <option <?php if($customers[$i]['customer_id'] == $customer['customer_id']){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> (<?php echo $customers[$i]['customer_name_th'] ?>)</option>
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
                                        <textarea  id="customer_address" name="customer_address" class="form-control" rows="5" readonly><? echo $customer['customer_address_1'] ."\n". $customer['customer_address_2'] ."\n". $customer['customer_address_3'];?></textarea >
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
                                        <label>หมายเลขใบยืม / DNC Code <font color="#F00"><b>*</b></font></label>
                                        <input id="delivery_note_customer_code" name="delivery_note_customer_code" class="form-control" value="<?php echo $last_code;?>" readonly>
                                        <p class="help-block">Example : DNC1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบยืม / DNC Date</label>
                                        <input type="text" id="delivery_note_customer_date" name="delivery_note_customer_date"  class="form-control calendar" readonly/>
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
                                        <textarea id="delivery_note_customer_remark" name="delivery_note_customer_remark"  class="form-control"></textarea>
                                        <p class="help-block">DHL </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ไฟล์แนบ / File </label>
                                        <input accept=".pdf"   type="file" id="delivery_note_customer_file" name="delivery_note_customer_file" >
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
                            for($i=0; $i < count($delivery_note_customer_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <select  class="form-control select" name="product_id[]" onchange="show_data(this);" data-live-search="true" >
                                        <option value="">Select</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($products) ; $ii++){
                                        ?>
                                        <option <?php if($products[$ii]['product_id'] == $delivery_note_customer_lists[$i]['product_id']){?> selected <?php }?> value="<?php echo $products[$ii]['product_id'] ?>"><?php echo $products[$ii]['product_code'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $delivery_note_customer_lists[$i]['product_name']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  name="delivery_note_customer_list_qty[]" value="<?php echo $delivery_note_customer_lists[$i]['delivery_note_customer_list_qty']; ?>" /></td>
                                <td><input type="text" class="form-control" name="delivery_note_customer_list_remark[]" value="<?php echo $delivery_note_customer_lists[$i]['delivery_note_customer_list_remark']; ?>" /></td>
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
                            <a href="index.php?app=delivery_note_customer" class="btn btn-default">Back</a>
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