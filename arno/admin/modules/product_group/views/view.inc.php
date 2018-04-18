<script>
    function check(){   
        var product_group_name = document.getElementById("product_group_name").value;
        var product_group_detail = document.getElementById("product_group_detail").value;
       
        product_group_name = $.trim(product_group_name);
        product_group_detail = $.trim(product_group_detail);
        
       if(product_group_name.length == 0){
            alert("Please input logistic name");
            document.getElementById("product_group_name").focus();
            return false;
        }else  if(product_group_detail.length == 0){
            alert("Please input detail name english");
            document.getElementById("product_group_detail").focus();
            return false;
        }else{
            return true;
        }
    }
</script>


<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Product Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=product" class="btn btn-primary  btn-menu">สินค้า / Product</a>
        <a href="?app=product_category" class="btn btn-primary btn-menu">ลักษณะ / Category</a>
        <a href="?app=product_type" class="btn btn-primary btn-menu">ประเภท / Type</a>
        <a href="?app=product_group" class="btn btn-primary btn-menu active">กลุ่ม / Group</a>
        <a href="?app=product_unit" class="btn btn-primary btn-menu">หน่วย / Unit</a>
    </div>
    <!-- /.col-lg-12 -->
</div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            กลุ่มสินค้า / Product Group
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" method="post" onsubmit="return check();" <?php if($product_group_id == ''){ ?>action="index.php?app=product_group&action=add"<?php }else{?> action="index.php?app=product_group&action=edit" <?php }?> enctype="multipart/form-data">
                                <input type="hidden" id="product_group_id" name="product_group_id" value="<?php echo $product_group_id?>"/>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>ชื่อกลุ่มสินค้า / Group name<font color="#F00"><b>*</b></font></label>
                                            <input id="product_group_name" name="product_group_name"  class="form-control" value="<? echo $product_group['product_group_name'];?>">
                                            <p class="help-block">Example : Consumable.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label>รายละเอียดกลุ่มสินค้า / Group detail <font color="#F00"><b>*</b></font></label>
                                            <input id="product_group_detail" name="product_group_detail" class="form-control" value="<? echo $product_group['product_group_detail'];?>">
                                            <p class="help-block">Example : -.</p>
                                        </div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-lg-offset-9 col-lg-3" align="right">
                                        <a href="?app=product_group&action=view" class="btn btn-primary">Reset</a>
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                <br>
                            </form>



                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ <br>No.</th>
                                        <th>ชื่อกลุ่มสินค้า<br>Group name</th>
                                        <th>รายละเอียดกลุ่มสินค้า<br>Group detail</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($product_groups); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $product_groups[$i]['product_group_name']; ?></td>
                                        <td><?php echo $product_groups[$i]['product_group_detail']; ?></td>
                                        <td>
                                            
                                            <a title="Update data" href="?app=product_group&action=update&id=<?php echo $product_groups[$i]['product_group_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Delete data" href="?app=product_group&action=delete&id=<?php echo $product_groups[$i]['product_group_id'];?>" onclick="return confirm('You want to delete customer product unit : <?php echo $product_groups[$i]['product_group_name']; ?>');" style="color:red;">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                   <?
                                    }
                                   ?>
                                </tbody>
                            </table>
                           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            
