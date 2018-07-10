<script>
    function check(){


        var stock_type_name = document.getElementById("stock_type_name").value;
        var stock_type_code = document.getElementById("stock_type_code").value;
       
        
        stock_type_name = $.trim(stock_type_name);
        stock_type_code = $.trim(stock_type_code);
        
        

        if(stock_type_code.length == 0){
            alert("Please input stock type code");
            document.getElementById("stock_type_code").focus();
            return false;
        }else if(stock_type_name.length == 0){
            alert("Please input stock type name");
            document.getElementById("stock_type_name").focus();
            return false;
        }else{
            return true;
        }



    }

    function delete_row(id){
        $(id).closest('tr').remove();

        var tr =  $('#table').children('tbody').children('tr').children('td:first-child');
            
        for(i=0; i<tr.length; i++){
            tr[i].innerHTML = (i+1)+".";
        }
     }

    function addEmployee(id){
        var emp_id = document.getElementById("emp_id").value;
        var val = document.getElementsByName('employee_id[]');
        
        if (emp_id != ""){
            for(var i = 0 ; i < val.length ; i++){
                if(val[i].value == emp_id){
                    alert("Employee exits.");
                    return false;
                }
            }

            $('#table').children('tbody').append(
                '<tr class="odd gradeX">'+
                    '<td  align="center" ></td>'+
                    '<td>'+
                        '<input type="hidden" name="stock_type_user_id[]" value="0" />'+
                        '<input type="hidden" name="employee_id[]" value="'+emp_id+'" />'+
                        '<span>'+$("#emp_id option:selected").text()+'</span>'+
                    '</td>'+
                    '<td>'+
                        '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                            '<i class="fa fa-times" aria-hidden="true"></i>'+
                        '</a>'+
                    '</td>'+
                '</tr>'
            );

            var tr =  $('#table').children('tbody').children('tr').children('td:first-child');
            
            for(i=0; i<tr.length; i++){
                tr[i].innerHTML = (i+1)+".";
            }
            
        }else{
            alert("Please select employee.");
        }
        
    }



</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">ประเภทคลังสินค้า / Stock</h1>
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
               แก้ไขประเภทคลังสินค้า / Edit stock type 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=stock_type&action=edit&id=<?PHP echo $stock_type["stock_type_id"];?>" enctype="multipart/form-data">
                    <input type="hidden" name="stock_type_id" value="<?PHP echo $stock_type["stock_type_id"];?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>หมายเลขประเภทคลังสินค้า / Stock type code. <font color="#F00"><b>*</b></font></label>
                                <input id="stock_type_code" name="stock_type_code" class="form-control" value="<?PHP echo $stock_type['stock_type_code'];?>">
                                <p class="help-block">Example : Main Stock</p>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label>ชื่อประเภทคลังสินค้า / Stock type name. </label>
                                <input id="stock_type_name" name="stock_type_name" type="text" class="form-control" value="<?PHP echo $stock_type['stock_type_name'];?>">
                                <p class="help-block">Example : Description...</p>
                            </div>
                        </div>
                    </div>

                    <div>
                    Employee reference :
                    </div>

                    <table id="table" width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th width="120px" style="text-align:center;">ลำดับ<br>(No.)</th>
                                <th style="text-align:center;">พนักงานที่มีสิทธิ์เข้าถึง<br>(Employee)</th>
                                <th width="120px">
                                    
                                </th>
                            </tr>
                            <tr>
                                <th width="120px" style="text-align:center;"></th>
                                <th style="text-align:center;">
                                    <select id="emp_id" class="form-control select" data-live-search="true" >
                                        <option value="">Select</option>
                                        <?php 
                                        for($i =  0 ; $i < count($users) ; $i++){
                                        ?>
                                        <option value="<?php echo $users[$i]['user_id'] ?>" <?PHP if( $users[$i]['user_id'] == $user['user_id']){ ?> SELECTED <?PHP }?> ><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </th>
                                <th>
                                <a class="btn btn-success " href="javascript:;" onclick="addEmployee(this);" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($stock_type_users); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td align="center">
                                    <?PHP echo $i+1; ?>.
                                </td>
                                <td>
                                    <input type="hidden" class="form-control" name="employee_id[]" value="<?php echo $stock_type_users[$i]['employee_id']; ?>" />
                                    <input type="hidden" class="form-control" name="stock_type_user_id[]" value="<?php echo $stock_type_users[$i]['stock_type_user_id']; ?>" />
                                    <span><?PHP echo $stock_type_users[$i]['user_name']?> <?PHP echo $stock_type_users[$i]['user_lastname']?> (<?PHP echo $stock_type_users[$i]['user_position_name']?>)</span>
                                </td>
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
                        
                    </table>

                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=stock_type" class="btn btn-default">Back</a>
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