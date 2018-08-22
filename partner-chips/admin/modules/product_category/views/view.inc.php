<script>
    function check(){   
        var product_category_name = document.getElementById("product_category_name").value;
        var stock_event = document.getElementById("stock_event").value;
       
        product_category_name = $.trim(product_category_name);
        stock_event = $.trim(stock_event);
        
       if(product_category_name.length == 0){
            alert("Please input category name");
            document.getElementById("product_category_name").focus();
            return false;
        }else  if(stock_event.length == 0){
            alert("Please input detail name english");
            document.getElementById("stock_event").focus();
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
        <a href="?app=product_category" class="btn btn-primary btn-menu active">ลักษณะ / Category</a>
        <a href="?app=product_type" class="btn btn-primary btn-menu">ประเภท / Type</a>
        <a href="?app=product_group" class="btn btn-primary btn-menu ">กลุ่ม / Group</a>
        <a href="?app=product_category" class="btn btn-primary btn-menu ">หน่วย / Category</a>
    </div>
    <!-- /.col-lg-12 -->
</div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            ลักษณะสินค้า / Product Category
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                            <form role="form" method="post" onsubmit="return check();" <?php if($product_category_id == ''){ ?>action="index.php?app=product_category&action=add"<?php }else{?> action="index.php?app=product_category&action=edit" <?php }?> enctype="multipart/form-data">
                                <input type="hidden" id="product_category_id" name="product_category_id" value="<?php echo $product_category_id?>"/>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>ชื่อลักษณะสินค้า / Category name<font color="#F00"><b>*</b></font></label>
                                            <input id="product_category_name" name="product_category_name"  class="form-control" value="<? echo $product_category['product_category_name'];?>">
                                            <p class="help-block">Example : ชิ้น.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label>มีผลต่อคลังสินค้า / Stock event <font color="#F00"><b>*</b></font></label>
                                            <select id="stock_event" name="stock_event" class="form-control">
                                                <option value="">Select</option>
                                                <option <?php if($product_category['stock_event'] == '0'){?> selected <?php } ?> value="0" >None</option>
                                                <option <?php if($product_category['stock_event'] == '1'){?> selected <?php } ?> value="1" >Auto</option>
                                            </select>
                                            <p class="help-block">Example : -.</p>
                                        </div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-lg-offset-9 col-lg-3" align="right">
                                        <a href="?app=product_category&action=view" class="btn btn-primary">Reset</a>
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                <br>
                            </form>
                        <?PHP } ?>


                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ <br>No.</th>
                                        <th>ชื่อลักษณะสินค้า<br>Category name</th>
                                        <th>มีผลต่อคลังสินค้า<br>Stock event</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($product_categorys); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $product_categorys[$i]['product_category_name']; ?></td>
                                        <td><?php if($product_categorys[$i]['stock_event']){ echo "Auto"; }else{ echo "None"; } ?></td>
                                        <td>
                                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                            <a title="Update data" href="?app=product_category&action=update&id=<?php echo $product_categorys[$i]['product_category_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP } ?>
                                        <?php if($license_admin_page == "High"){ ?> 
                                            <a title="Delete data" href="?app=product_category&action=delete&id=<?php echo $product_categorys[$i]['product_category_id'];?>" onclick="return confirm('You want to delete customer product Category : <?php echo $product_categorys[$i]['product_category_name']; ?>');" style="color:red;">
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
            
            
