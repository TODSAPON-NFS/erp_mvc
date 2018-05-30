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
                    '<input type="hidden" name="stock_move_list_id" value="0" />'+
                    '<select class="form-control select" type="text" name="product_id[]" onchange="show_data(this);" data-live-search="true" ></select>'+
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
        <h1 class="page-header">Stock Transfer Management</h1>
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
            แก้ไขใบย้ายคลังสินค้า /  Edit Stock Transfer   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=stock_move&action=edit&id=<?PHP echo $stock_move['stock_move_id'];?>" enctype="multipart/form-data">
                <input type="hidden" name="stock_move_id" value="<?PHP echo $stock_move['stock_move_id'];?>"  />
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
                                        <input id="stock_move_code" name="stock_move_code" class="form-control" value="<?php echo $stock_move['stock_move_code'];?>" readonly>
                                        <p class="help-block">Example : SM1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบย้ายสินค้า / Stock Transfer Date</label>
                                        <input type="text" id="stock_move_date" name="stock_move_date"  class="form-control calendar" value="<?php echo $stock_move['stock_move_date'];?>" readonly/>
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
                                            <option value="<?php echo $users[$i]['user_id'] ?>" <?php if($users[$i]['user_id'] == $stock_move['employee_id']){?> selected <?php }?> ><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
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
                            for($i=0; $i < count($stock_move_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="stock_move_list_id" value="<?PHP echo $stock_move_lists[$i]['stock_move_list_id'];?>" />
                                    <select  class="form-control select" name="product_id[]" onchange="show_data(this);" data-live-search="true" >
                                        <option value="">Select</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($products) ; $ii++){
                                        ?>
                                        <option <?php if($products[$ii]['product_id'] == $stock_move_lists[$i]['product_id']){?> selected <?php }?> value="<?php echo $products[$ii]['product_id'] ?>"><?php echo $products[$ii]['product_code'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $stock_move_lists[$i]['product_name']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;"  name="stock_move_list_qty[]" value="<?php echo $stock_move_lists[$i]['stock_move_list_qty']; ?>" /></td>
                                <td><input type="text" class="form-control" name="stock_move_list_remark[]" value="<?php echo $stock_move_lists[$i]['stock_move_list_remark']; ?>" /></td>
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
                            <a href="index.php?app=stock_move" class="btn btn-default">Back</a>
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