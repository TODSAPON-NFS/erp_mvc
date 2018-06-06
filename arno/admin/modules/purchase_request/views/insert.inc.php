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
                    '<input type="hidden" class="form-control" name="purchase_request_list_id[]" value="0" />'+
                    '<select class="form-control select" type="text" name="product_id[]" onchange="show_data(this);" data-live-search="true" ></select>'+
                '</td>'+
                '<td><input type="text" class="form-control" name="product_name[]" readonly /></td>'+
                '<td><input type="text" class="form-control" style="text-align:right;" name="purchase_request_list_qty[]"  value="1"/></td>'+
                '<td><input type="text" class="form-control" name="purchase_request_list_delivery[]" readonly /></td>'+
                '<td><input type="text" class="form-control" name="purchase_request_list_remark[]" /></td>'+
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
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="purchase_request_list_delivery[]"]').datepicker({ dateFormat: 'dd-mm-yy' });
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
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=purchase_request&action=add" >
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ประเภทใบร้องขอสั่งซื้อสินค้า / PR Code <font color="#F00"><b>*</b></font></label>
                                <input id="purchase_request_code" name="purchase_request_code" class="form-control" value="<?php echo $last_code;?>" readonly>
                                <p class="help-block">Example : PR1801001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ประเภทใบร้องขอสั่งซื้อสินค้า / PR Type <font color="#F00"><b>*</b></font></label>
                                <select id="purchase_request_type" name="purchase_request_type" class="form-control">
                                        <option value="">Select</option>
                                        <option>Sale</option>
                                        <option>Use</option>
                                    </select>
                                <p class="help-block">Example : Low.</p>
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
                                    <option value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
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
                                <select id="customer_id" name="customer_id" class="form-control" >
                                    <option value="0">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($customers) ; $i++){
                                    ?>
                                    <option <?PHP if($user[0][0] == $users[$i]['user_id']){?> SELECTED <?PHP }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_th'] ?> (<?php echo $customers[$i]['customer_name_en'] ?>)</option>
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
                                    <option <?php if($suppliers[$i]['supplier_id'] == $supplier['supplier_id']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> (<?php echo $suppliers[$i]['supplier_name_th'] ?>)</option>
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
                                <input id="purchase_request_remark" name="purchase_request_remark" class="form-control" />
                                <p class="help-block">Example : -.</p>
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
                                    <select  class="form-control select" name="product_id[]" onchange="show_data(this);" data-live-search="true" >
                                        <option value="">Select</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($products) ; $ii++){
                                        ?>
                                        <option <?php if($products[$ii]['product_id'] == $purchase_request_lists[$i]['product_id']){?> selected <?php }?> value="<?php echo $products[$ii]['product_id'] ?>"><?php echo $products[$ii]['product_code'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $purchase_request_lists[$i]['product_name']; ?>" /></td>
                                <td><input type="text" class="form-control" style="text-align:right;" name="purchase_request_list_qty[]" value="<?php echo $purchase_request_lists[$i]['purchase_request_list_qty']; ?>" /></td>
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
                                <td>
                                    
                                </td>
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