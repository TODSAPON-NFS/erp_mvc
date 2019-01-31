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


        var stock_move_code = document.getElementById("stock_move_code").value;
        var employee_id = document.getElementById("employee_id").value;
        var stock_group_id_out = document.getElementById("stock_group_id_out").value;
        var stock_group_id_in = document.getElementById("stock_group_id_in").value;
        var stock_move_date = document.getElementById("stock_move_date").value;
        
        
        stock_move_code = $.trim(stock_move_code);
        stock_move_date = $.trim(stock_move_date);
        employee_id = $.trim(employee_id);
        stock_group_id_out = $.trim(stock_group_id_out);
        stock_group_id_in = $.trim(stock_group_id_in);
        

        if(stock_group_id_out.length == 0){
            alert("Please input stock group");
            document.getElementById("stock_group_id_out").focus();
            return false;
        }else if(stock_group_id_in.length == 0){
            alert("Please input stock group");
            document.getElementById("stock_group_id_in").focus();
            return false;
        }else if(stock_move_code.length == 0){
            alert("Please input delivery note stock move code");
            document.getElementById("stock_move_code").focus();
            return false;
        }else if(stock_move_date.length == 0){
            alert("Please input delivery note stock move date");
            document.getElementById("stock_move_date").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else{
            return true;
        }



    }

    function update_line(){
        var td_number = $('table[name="tb_list"]').children('tbody').children('tr').children('td:first-child');
        for(var i = 0; i < td_number.length ;i++){
            td_number[i].innerHTML = (i+1);
        }
    }
    
    function delete_row(id){
        $(id).closest('tr').remove();
        update_line();
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
                '<td class="sorter">'+
                '</td>'+
                '<td>'+  
                    '<input type="hidden" name="stock_move_list_id[]" value="0" />'+
                    '<input type="hidden" name="product_id[]" class="form-control" />'+
					'<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" />'+ 
                '</td>'+
                '<td><input type="text" class="form-control" name="product_name[]" readonly /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="stock_move_list_qty[]"  /></td>'+
                '<td><input type="text" class="form-control" name="stock_move_list_remark[]" /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        ); 
        $(".example-ajax-post").easyAutocomplete(options);
        update_line();
    }

    function product_detail_blank(id){
        var product_id = $(id).closest('tr').children('td').children('input[name="product_id[]"]').val();
        if(product_id == ''){
            alert('ไม่มีข้อมูลสินค้านี้');
            $(id).closest('tr').children('td').children('input[name="product_code[]"]').focus();
        }else{
            window.open("?app=product_detail&product_id="+product_id);
        }
    }

</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Stock Transfer Management</h1>
    </div> 
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            เพิ่มใบย้ายคลังสินค้า /  Add Stock Transfer   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=stock_move&action=add" enctype="multipart/form-data">
                <div class="row">
                        <div class="col-lg-5">
                            <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>จากคลังสินค้า / From stock <font color="#F00"><b>*</b></font></label>
                                        <select id="stock_group_id_out" name="stock_group_id_out" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($stock_groups) ; $i++){
                                            ?>
                                            <option <?php if($stock_groups[$i]['stock_group_id'] == $stock_move['stock_group_id_out']){?> selected <?php }?> value="<?php echo $stock_groups[$i]['stock_group_id'] ?>"><?php echo $stock_groups[$i]['stock_group_name'] ?> </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Main Stock.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ไปยังคลังสินค้า / To stock  <font color="#F00"><b>*</b></font> </label>
                                        <select id="stock_group_id_in" name="stock_group_id_in" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($stock_groups) ; $i++){
                                            ?>
                                            <option <?php if($stock_groups[$i]['stock_group_id'] == $stock_move['stock_group_id_in']){?> selected <?php }?> value="<?php echo $stock_groups[$i]['stock_group_id'] ?>"><?php echo $stock_groups[$i]['stock_group_name'] ?> </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Tool Management Stock.</p>
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
                                        <label>หมายเลขใบย้ายสินค้า / Stock Transfer Code <font color="#F00"><b>*</b></font></label>
                                        <input id="stock_move_code" name="stock_move_code" class="form-control" value="<?php echo $last_code;?>" >
                                        <p class="help-block">Example : SM1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบย้ายสินค้า / Stock Transfer Date</label>
                                        <input type="text" id="stock_move_date" name="stock_move_date"  class="form-control calendar" value="<?php echo $first_date;?>" readonly/>
                                        <p class="help-block">31-01-2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ย้ายคลังสินค้า / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
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
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark</label>
                                        <textarea id="stock_move_remark" name="stock_move_remark"  class="form-control"><?php echo $stock_move['stock_move_remark'];?></textarea>
                                        <p class="help-block">- </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <table name="tb_list" width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;" width="60">ลำดับ </th> 
                                <th style="text-align:center;">รหัสสินค้า<br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า<br>(Product Name)</th>
                                <th style="text-align:center;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;">หมายเหตุ<br>(Remark)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody  class="sorted_table">
                            <?php 
                            for($i=0; $i < count($stock_move_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td class="sorter">
                                    <?PHP echo ($i + 1); ?>.
                                </td>
                                <td>
                                    <input type="hidden" name="stock_move_list_id[]" value="0" />
                                    <input type="hidden" name="product_id[]" class="form-control" value="<?php echo $stock_move_lists[$i]['product_id']; ?>" />
                                    <input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="<?php echo $stock_move_lists[$i]['product_code']; ?>"  readonly/>
                                </td>
                                <td><input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $stock_move_lists[$i]['product_name']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  name="stock_move_list_qty[]" value="<?php echo $stock_move_lists[$i]['stock_move_list_qty']; ?>" /></td>
                                <td><input type="text" class="form-control" name="stock_move_list_remark[]" value="<?php echo $stock_move_lists[$i]['stock_move_list_remark']; ?>" /></td>
                                <td>
                                    <a href="javascript:;" onclick="product_detail_blank(this);">
                                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                    </a> 
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
                            <a href="index.php?app=stock_move" class="btn btn-default">Back</a>
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
// Sortable rows
$('.sorted_table').sortable({
        handle: ".sorter" , 
        update: function( event, ui ) {
            update_line(); 
        }
    });
</script>