<script>
    function check(){


        var stock_group_detail = document.getElementById("stock_group_detail").value;
        var stock_group_name = document.getElementById("stock_group_name").value;
       
        
        stock_group_detail = $.trim(stock_group_detail);
        stock_group_name = $.trim(stock_group_name);
        
        

        if(stock_group_name.length == 0){
            alert("Please input stock group name");
            document.getElementById("stock_group_name").focus();
            return false;
        }else if(stock_group_detail.length == 0){
            alert("Please input stock group detail");
            document.getElementById("stock_group_detail").focus();
            return false;
        }else{
            return true;
        }



    }


</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">คลังสินค้า / Stock</h1>
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
               เพิ่มคลังสินค้า / Add Stock 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=stock_group&stock_type_id=<?php echo $stock_type_id;?>&action=add" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ชื่อคลังสินค้า / Stock Name. <font color="#F00"><b>*</b></font></label>
                                <input id="stock_group_name" name="stock_group_name" class="form-control">
                                <p class="help-block">Example : Main Stock.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ผู้ดูแล / Employee  <font color="#F00"><b>*</b></font> </label>
                                <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($users) ; $i++){
                                    ?>
                                    <option value="<?php echo $users[$i]['user_id'] ?>" <?PHP if( $users[$i]['user_id'] == $stock_group['employee_id']){ ?> SELECTED <?PHP }?> ><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ประเภทคลังสินค้า / Employee  <font color="#F00"><b>*</b></font> </label>
                                <select id="stock_type_id" name="stock_type_id" class="form-control select" data-live-search="true">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($stock_types) ; $i++){
                                    ?>
                                    <option value="<?php echo $stock_types[$i]['stock_type_id'] ?>" <?PHP if( $stock_types[$i]['stock_type_id'] == $stock_type_id){ ?> SELECTED <?PHP }?> ><?php echo $stock_types[$i]['stock_type_code'] ?> - <?php echo $stock_types[$i]['stock_type_name'] ?></option>
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
                                <label>แจ้งเตือนทุกวันที่ / Daily alerts </label>
                                <input id="stock_group_day" name="stock_group_day" type="text" class="form-control" />
                                <p class="help-block">Example : 25.</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>แจ้งเตือนเมื่อคลังสินค้าต่ำกว่าเกณฑ์ / Notification </label>
                                <input id="stock_group_notification" name="stock_group_notification" type="checkbox" class="form-control" value="1" />
                                <p class="help-block">Example : true.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>รายละเอียดคลังสินค้า / Description </label>
                                <input id="stock_group_detail" name="stock_group_detail" type="text" class="form-control" />
                                <p class="help-block">Example : Description.</p>
                            </div>
                        </div>
                    </div>

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