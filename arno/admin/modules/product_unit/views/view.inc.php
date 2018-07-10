<script>
    function check(){   
        var product_unit_name = document.getElementById("product_unit_name").value;
        var product_unit_detail = document.getElementById("product_unit_detail").value;
       
        product_unit_name = $.trim(product_unit_name);
        product_unit_detail = $.trim(product_unit_detail);
        
       if(product_unit_name.length == 0){
            alert("Please input logistic name");
            document.getElementById("product_unit_name").focus();
            return false;
        }else  if(product_unit_detail.length == 0){
            alert("Please input detail name english");
            document.getElementById("product_unit_detail").focus();
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
        <a href="?app=product" class="btn btn-primary btn-menu">สินค้า / Product</a>
        <a href="?app=product_category" class="btn btn-primary btn-menu">ลักษณะ / Category</a>
        <a href="?app=product_type" class="btn btn-primary btn-menu">ประเภท / Type</a>
        <a href="?app=product_group" class="btn btn-primary btn-menu ">กลุ่ม / Group</a>
        <a href="?app=product_unit" class="btn btn-primary btn-menu active">หน่วย / Unit</a>
    </div>
    <!-- /.col-lg-12 -->
</div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            หน่วยสินค้า / Product Unit
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                            <form role="form" method="post" onsubmit="return check();" <?php if($product_unit_id == ''){ ?>action="index.php?app=product_unit&action=add"<?php }else{?> action="index.php?app=product_unit&action=edit" <?php }?> enctype="multipart/form-data">
                                <input type="hidden" id="product_unit_id" name="product_unit_id" value="<?php echo $product_unit_id?>"/>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>ชื่อหน่วยสินค้า / Unit name<font color="#F00"><b>*</b></font></label>
                                            <input id="product_unit_name" name="product_unit_name"  class="form-control" value="<? echo $product_unit['product_unit_name'];?>">
                                            <p class="help-block">Example : ชิ้น.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label>รายละเอียดหน่วยสินค้า / Unit detail <font color="#F00"><b>*</b></font></label>
                                            <input id="product_unit_detail" name="product_unit_detail" class="form-control" value="<? echo $product_unit['product_unit_detail'];?>">
                                            <p class="help-block">Example : -.</p>
                                        </div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-lg-offset-9 col-lg-3" align="right">
                                        <a href="?app=product_unit&action=view" class="btn btn-primary">Reset</a>
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                <br>
                            </form>
                        <?PHP }?>


                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ<br>No.</th>
                                        <th>ชื่อหน่วยสินค้า<br>Unit name</th>
                                        <th>รายละเอียดหน่วยสินค้า<br>Unit detail</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($product_units); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $product_units[$i]['product_unit_name']; ?></td>
                                        <td><?php echo $product_units[$i]['product_unit_detail']; ?></td>
                                        <td>
                                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                            <a title="Update data" href="?app=product_unit&action=update&id=<?php echo $product_units[$i]['product_unit_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP } ?>
                                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                            <a title="Delete data" href="?app=product_unit&action=delete&id=<?php echo $product_units[$i]['product_unit_id'];?>" onclick="return confirm('You want to delete customer product unit : <?php echo $product_units[$i]['product_unit_name']; ?>');" style="color:red;">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        <?PHP } ?>
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
            
            
