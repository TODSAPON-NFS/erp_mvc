<script>

    var option_stocks = {
        url: function(keyword) {
            // var stock_group_id = $(this).closest('tr').children('td').children('select[name="stock_group_id_old[]"]').val(); 
            // return "controllers/getProductINStockByKeyword.php?stock_group_id="+stock_group_id+"&keyword="+keyword;

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

    var stock_group_data = [
    <?php for($i = 0 ; $i < count($stock_groups) ; $i++ ){?>
        {
            stock_group_id:'<?php echo $stock_groups[$i]['stock_group_id'];?>',
            stock_group_name:'<?php echo $stock_groups[$i]['stock_group_name'];?>'
        },
    <?php }?>
    ];

    function check(){


        var stock_change_product_code = document.getElementById("stock_change_product_code").value;
        var employee_id = document.getElementById("employee_id").value;
        var stock_group_id = document.getElementById("stock_group_id").value; 
        var stock_change_product_date = document.getElementById("stock_change_product_date").value;
        
        
        stock_change_product_code = $.trim(stock_change_product_code);
        stock_change_product_date = $.trim(stock_change_product_date);
        employee_id = $.trim(employee_id);
        stock_group_id = $.trim(stock_group_id); 
        

        if(stock_group_id.length == 0){
            alert("Please input stock group");
            document.getElementById("stock_group_id").focus();
            return false;
        } else if(stock_change_product_code.length == 0){
            alert("Please input delivery note Stock Change Product code");
            document.getElementById("stock_change_product_code").focus();
            return false;
        }else if(stock_change_product_date.length == 0){
            alert("Please input delivery note Stock Change Product date");
            document.getElementById("stock_change_product_date").focus();
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
        update_line();
        calculateAll();
     }

    function update_line(){
        var td_number = $('table[name="tb_list"]').children('tbody').children('tr').children('td:first-child');
        for(var i = 0; i < td_number.length ;i++){
            td_number[i].innerHTML = (i+1);
        }
    }

     function update_sum(id){

        var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="stock_change_product_list_qty[]"]').val(  ).replace(',',''));
        var price =  parseFloat($(id).closest('tr').children('td').children('input[name="stock_change_product_list_price[]"]').val( ).replace(',',''));
        var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="stock_change_product_list_total[]"]').val( ).replace(',',''));

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

        $(id).closest('tr').children('td').children('input[name="stock_change_product_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="stock_change_product_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="stock_change_product_list_total[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll(); 
    }

    function calculateAll(){

        var val = document.getElementsByName('stock_change_product_list_total[]');
        var total = 0.0;

        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $('#stock_change_product_total_price').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") ); 

    }



     function show_data_old(id){
        var product_code = $(id).val();
        var stock_group_id_old = $(id).closest('tr').children('td').children('select[name="stock_group_id_old[]"]').val();

        $.post( "controllers/getProductCostByCode.php", { stock_group_id:stock_group_id_old, 'product_code': $.trim(product_code)}, function( data ) {
            console.log(data);
            if(data != null){
                $(id).closest('tr').children('td').children('input[name="product_name_old[]"]').val(data.product_name);
                $(id).closest('tr').children('td').children('input[name="product_id_old[]"]').val(data.product_id);
                $(id).closest('tr').children('td').children('input[name="stock_change_product_list_price[]"]').val(data.stock_report_cost_avg);
                $(id).closest('tr').children('td').children('input[name="stock_change_product_list_qty[]"]').val(data.stock_report_qty);
                update_sum(id)
            }
        });
     }

     function show_data_new(id){
        var product_code = $(id).val();
        $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
            if(data != null){
                $(id).closest('tr').children('td').children('input[name="product_name_new[]"]').val(data.product_name);
                $(id).closest('tr').children('td').children('input[name="product_id_new[]"]').val(data.product_id);
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
                '<td align="center" >'+  
                    
                '</td>'+
                '<td>'+
                    '<select  name="stock_group_id_old[]" class="form-control select" data-live-search="true">'+ 
                        '<option value="0">Select</option>'+ 
                    '</select>'+ 
                '</td>'+
                '<td>'+   
                    '<input type="hidden" name="stock_change_product_list_id" value="0" />'+
                    '<input type="hidden" name="product_id_old[]" class="form-control" />'+
					'<input class="example-ajax-post form-control" name="product_code_old[]" onchange="show_data_old(this);" placeholder="Product Code" />'+ 
                '</td>'+
                '<td><input type="text" class="form-control" name="product_name_old[]" readonly /></td>'+
                '<td>'+
                    '<select  name="stock_group_id_new[]" class="form-control select" data-live-search="true">'+ 
                        '<option value="0">Select</option>'+ 
                    '</select>'+ 
                '</td>'+
                '<td>'+   
                    '<input type="hidden" name="product_id_new[]" class="form-control" />'+
					'<input class="example-ajax-post form-control" name="product_code_new[]" onchange="show_data_new(this);" placeholder="Product Code" />'+ 
                '</td>'+
                '<td><input type="text" class="form-control" name="product_name_new[]" readonly /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="stock_change_product_list_qty[]" onchange="update_sum(this);"  /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="stock_change_product_list_price[]"  readonly /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="stock_change_product_list_total[]"  readonly /></td>'+
                '<td><input type="text" class="form-control" name="stock_change_product_list_remark[]" /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        ); 

        var str_stock = "<option value=''>Select Stock</option>";
        $.each(stock_group_data, function (index, value) { 
            str_stock += "<option value='" + value['stock_group_id'] + "'>" +  value['stock_group_name'] + "</option>"; 
        });

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id_old[]"]').html(str_stock);
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id_old[]"]').selectpicker();

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id_new[]"]').html(str_stock);
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_id_new[]"]').selectpicker();

        $('input[name="product_code_old[]"]').easyAutocomplete(option_stocks);
        $('input[name="product_code_new[]"]').easyAutocomplete(options);
        update_line();
    }

    

    $('input[name="product_code_old[]"]').easyAutocomplete(option_stocks);
    $('input[name="product_code_new[]"]').easyAutocomplete(options);

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Stock Change Product Management</h1>
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
            แก้ไขใบย้ายคลังสินค้า /  Edit Stock Change Product   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=stock_change_product&action=edit&id=<?PHP echo $stock_change_product['stock_change_product_id'];?>" enctype="multipart/form-data">
                <input type="hidden" name="stock_change_product_id" value="<?PHP echo $stock_change_product['stock_change_product_id'];?>"  />
                    <div class="row"> 
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบย้ายสินค้า / Stock Change Product Code <font color="#F00"><b>*</b></font></label>
                                        <input id="stock_change_product_code" name="stock_change_product_code" class="form-control" value="<?php echo $stock_change_product['stock_change_product_code'];?>" >
                                        <p class="help-block">Example : SM1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกใบย้ายสินค้า / Stock Change Product Date</label>
                                        <input type="text" id="stock_change_product_date" name="stock_change_product_date"  class="form-control calendar" value="<?php echo $stock_change_product['stock_change_product_date'];?>" readonly/>
                                        <p class="help-block">31-01-2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ผู้ย้ายคลังสินค้า / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option value="<?php echo $users[$i]['user_id'] ?>" <?php if($users[$i]['user_id'] == $stock_change_product['employee_id']){?> selected <?php }?> ><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark</label>
                                        <textarea id="stock_change_product_remark" name="stock_change_product_remark"  class="form-control"><?php echo $stock_change_product['stock_change_product_remark'];?></textarea>
                                        <p class="help-block">- </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <table width="100%"  name="tb_list" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">ลำดับ<br>(No.)</th>
                                <th style="text-align:center;" colspan="3">สินค้าเดิม<br>(Product Old)</th>
                                <th style="text-align:center;" colspan="3">สินค้าใหม่<br>(Product New)</th>
                                <th style="text-align:center;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;">ต้นทุน<br>(Price)</th>
                                <th style="text-align:center;">ต้นทุนรวม<br>(Total)</th>
                                <th style="text-align:center;">หมายเหตุ<br>(Remark)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($stock_change_product_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td  align="center"  >
                                    <?PHP echo $i+1; ?>
                                    
                                </td>
                                <td> 
                                    <select  name="stock_group_id_old[]" class="form-control select" data-live-search="true"> 
                                        <?php 
                                        for($ii =  0 ; $ii < count($stock_groups) ; $ii++){
                                        ?>
                                        <option <?php if($stock_groups[$ii]['stock_group_id'] == $stock_change_product_lists[$i]['stock_group_id_old']){?> selected <?php }?> value="<?php echo $stock_groups[$ii]['stock_group_id'] ?>"><?php echo $stock_groups[$ii]['stock_group_name'] ?> </option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td> 
                                    <input type="hidden" name="stock_change_product_list_id" value="<?PHP echo $stock_change_product_lists[$i]['stock_change_product_list_id'];?>" />
                                    <input type="hidden" name="product_id_old[]" class="form-control" value="<?php echo $stock_change_product_lists[$i]['product_id_old']; ?>" />
                                    <input class="example-ajax-post form-control" name="product_code_old[]" onchange="show_data_old(this);" placeholder="Product Code" value="<?php echo $stock_change_product_lists[$i]['product_code_old']; ?>"  readonly/>
                                </td>
                                <td><input type="text" class="form-control" name="product_name_old[]" readonly value="<?php echo $stock_change_product_lists[$i]['product_name_old']; ?>" /></td>
                                <td> 
                                    <select  name="stock_group_id_new[]" class="form-control select" data-live-search="true"> 
                                        <?php 
                                        for($ii =  0 ; $ii < count($stock_groups) ; $ii++){
                                        ?>
                                        <option <?php if($stock_groups[$ii]['stock_group_id'] == $stock_change_product_lists[$i]['stock_group_id_new']){?> selected <?php }?> value="<?php echo $stock_groups[$ii]['stock_group_id'] ?>"><?php echo $stock_groups[$ii]['stock_group_name'] ?> </option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td> 
                                    <input type="hidden" name="product_id_new[]" class="form-control" value="<?php echo $stock_change_product_lists[$i]['product_id_new']; ?>" />
                                    <input class="example-ajax-post form-control" name="product_code_new[]" onchange="show_data_new(this);" placeholder="Product Code" value="<?php echo $stock_change_product_lists[$i]['product_code_new']; ?>"  readonly/>
                                </td>
                                <td><input type="text" class="form-control" name="product_name_new[]" readonly value="<?php echo $stock_change_product_lists[$i]['product_name_new']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  name="stock_change_product_list_qty[]" value="<?php echo number_format($stock_change_product_lists[$i]['stock_change_product_list_qty'],0); ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  name="stock_change_product_list_price[]" value="<?php echo number_format($stock_change_product_lists[$i]['stock_change_product_list_price'],2); ?>" readonly /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  name="stock_change_product_list_total[]" value="<?php echo number_format($stock_change_product_lists[$i]['stock_change_product_list_total'],2); ?>" readonly /></td>
                                <td><input type="text" class="form-control" name="stock_change_product_list_remark[]" value="<?php echo $stock_change_product_lists[$i]['stock_change_product_list_remark']; ?>" /></td>
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
                                <td colspan="12" align="center">
                                    <a href="javascript:;" onclick="add_row(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการ
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table> 

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=stock_change_product" class="btn btn-default">Back</a>
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