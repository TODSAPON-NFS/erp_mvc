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


        var customer_purchase_order_type = document.getElementById("customer_purchase_order_type").value;
        var customer_id = document.getElementById("customer_id").value;
        var customer_purchase_order_code = document.getElementById("customer_purchase_order_code").value;
        var customer_purchase_order_date = document.getElementById("customer_purchase_order_date").value;
        var customer_purchase_order_credit_term = document.getElementById("customer_purchase_order_credit_term").value;
        var employee_id = document.getElementById("employee_id").value;
        
        customer_purchase_order_type = $.trim(customer_purchase_order_type);
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

     function delete_row(id){
        $(id).closest('tr').remove();
     }

     function show_data(id){
        var product_name = "";
        var data = product_data.filter(val => val['product_id'] == $(id).val());
        if(data.length > 0){
            $(id).closest('tr').children('td').children('input[name="product_id"]').val( data[0]['product_id'] );
            $(id).closest('tr').children('td').children('input[name="product_name"]').val( data[0]['product_name'] );
        }
        
     }

     function update_sum(id){

          var qty =  $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_qty"]').val(  );
          var price =  $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price"]').val( );
          var sum =  $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price_sum"]').val( );

        if(isNaN(qty)){
            qty = 0;
        }

        if(isNaN(price)){
            price = 0;
        }

        if(isNaN(sum)){
            sum = 0;
        }

        sum = qty*price;

        $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_qty"]').val( qty );
        $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price"]').val( price );
        $(id).closest('tr').children('td').children('input[name="customer_purchase_order_list_price_sum"]').val( sum );

        
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
                    '<input type="hidden" name="product_id" />'+
                    '<select class="form-control select" type="text" name="product_code" onchange="show_data(this);" data-live-search="true" ></select>'+
                '</td>'+
                '<td><input type="text" class="form-control" name="product_name" readonly /></td>'+
                '<td><input type="text" class="form-control" name="customer_purchase_order_list_qty" onchange="update_sum(this);" /></td>'+
                '<td><input type="text" class="form-control" name="customer_purchase_order_list_price" onchange="update_sum(this);" /></td>'+
                '<td><input type="text" class="form-control" name="customer_purchase_order_list_price_sum" onchange="update_sum(this);" /></td>'+
                '<td><input type="text" class="form-control" name="customer_purchase_order_list_delivery_min" readonly /></td>'+
                '<td><input type="text" class="form-control" name="customer_purchase_order_list_delivery_max" readonly /></td>'+
                '<td><input type="text" class="form-control" name="customer_purchase_order_list_remark" /></td>'+
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
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="customer_purchase_order_list_delivery_min"]').datepicker({ dateFormat: 'dd-mm-yy' });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="customer_purchase_order_list_delivery_max"]').datepicker({ dateFormat: 'dd-mm-yy' });
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
                Edit Purchase Request 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=customer_purchase_order&action=edit&id=<?php echo $customer_purchase_order_id;?>" >
                    <input type="hidden"  id="customer_purchase_order_id" name="customer_purchase_order_id" value="<?php echo $customer_purchase_order_id; ?>" />
                    <input type="hidden"  id="customer_purchase_order_date" name="customer_purchase_order_date" value="<?php echo $customer_purchase_order['customer_purchase_order_date']; ?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Customer Code <font color="#F00"><b>*</b></font></label>
                                        <input id="customer_code" name="customer_code" class="form-control" value="<? echo $customer_purchase_order['customer_code'];?>" readonly>
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>Customer  <font color="#F00"><b>*</b></font> </label>
                                        <select id="customer_id" name="customer_id" class="form-control" >
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
                                        <label>Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="customer_address" name="customer_address" class="form-control" rows="7" readonly><? echo $customer_purchase_order['customer_address'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Remark <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="customer_purchase_order_remark" name="customer_purchase_order_remark" class="form-control" rows="7" readonly><? echo $customer_purchase_order['customer_address'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4">
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Code <font color="#F00"><b>*</b></font></label>
                                        <input id="customer_purchase_order_code" name="customer_purchase_order_code" class="form-control" value="<? echo $customer_purchase_order['customer_purchase_order_code'];?>" readonly>
                                        <p class="help-block">Example : PO1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Date</label>
                                        <input type="text" id="customer_purchase_order_date" name="customer_purchase_order_date" value="<? echo $customer_purchase_order['customer_purchase_order_date'];?>"  class="form-control calendar" readonly/>
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Credit term (Day)</label>
                                        <input type="text" id="customer_purchase_order_credit_term" name="customer_purchase_order_credit_term" value="<? echo $customer_purchase_order['customer_purchase_order_credit_term'];?>" class="form-control"/>
                                        <p class="help-block">10 </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control" >
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
                                        <label>Delivery by</label>
                                        <input type="text" id="customer_purchase_order_delivery_by" name="customer_purchase_order_delivery_by" value="<? echo $customer_purchase_order['customer_purchase_order_delivery_by'];?>"  class="form-control"/>
                                        <p class="help-block">DHL </p>
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
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>@</th>
                                <th>Amount</th>
                                <th>Delivery Min</th>
                                <th>Delivery Max</th>
                                <th>Remark</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($customer_purchase_order_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="product_id"  value="<?php echo $customer_purchase_order_lists[$i]['product_id']; ?>" />
                                    <select  class="form-control select" name="product_code" onchange="show_data(this);" data-live-search="true" >
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
                                <td><input type="text" class="form-control" name="product_name" readonly value="<?php echo $customer_purchase_order_lists[$i]['product_name']; ?>" /></td>
                                <td><input type="text" class="form-control" onchange="update_sum(this);" name="customer_purchase_order_list_qty" value="<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_qty']; ?>" /></td>
                                <td><input type="text" class="form-control" onchange="update_sum(this);" name="customer_purchase_order_list_price" value="<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_price']; ?>" /></td>
                                <td><input type="text" class="form-control" onchange="update_sum(this);" name="customer_purchase_order_list_price_sum" value="<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_price_sum']; ?>" /></td>
                                <td><input type="text" class="form-control calendar" name="customer_purchase_order_list_delivery_min" readonly value="<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_delivery_min']; ?>" /></td>
                                <td><input type="text" class="form-control calendar" name="customer_purchase_order_list_delivery_max" readonly value="<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_delivery_max']; ?>" /></td>
                                <td><input type="text" class="form-control" name="customer_purchase_order_list_remark" value="<?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_remark']; ?>" /></td>
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