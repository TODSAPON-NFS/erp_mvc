<script>
    function check(){   
        var product_type_name = document.getElementById("product_type_name").value;
        var product_type_detail = document.getElementById("product_type_detail").value;
       
        product_type_name = $.trim(product_type_name);
        product_type_detail = $.trim(product_type_detail);
        
       if(product_type_name.length == 0){
            alert("Please input logistic name");
            document.getElementById("product_type_name").focus();
            return false;
        }else  if(product_type_detail.length == 0){
            alert("Please input detail name english");
            document.getElementById("product_type_detail").focus();
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
        <a href="?app=product_type" class="btn btn-primary btn-menu active">ประเภท / Type</a>
        <a href="?app=product_group" class="btn btn-primary btn-menu ">กลุ่ม / Group</a>
        <a href="?app=product_unit" class="btn btn-primary btn-menu ">หน่วย / Unit</a>
    </div>
    <!-- /.col-lg-12 -->
</div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            ประเภทสินค้า / Product Type
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                            <form role="form" method="post" onsubmit="return check();" <?php if($product_type_id == ''){ ?>action="index.php?app=product_type&action=add"<?php }else{?> action="index.php?app=product_type&action=edit" <?php }?> enctype="multipart/form-data">
                                <input type="hidden" id="product_type_id" name="product_type_id" value="<?php echo $product_type_id?>"/>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>ชื่อประเภทสินค้า / Type name<font color="#F00"><b>*</b></font></label>
                                            <input id="product_type_name" name="product_type_name"  class="form-control" value="<? echo $product_type['product_type_name'];?>">
                                            <p class="help-block">Example : Special Tool.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>ตัวอักษรขึ้นต้น / First char</label>
                                            <input id="product_type_first_char" name="product_type_first_char"  class="form-control" value="<? echo $product_type['product_type_first_char'];?>">
                                            <p class="help-block">Example : EN.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>สร้างรหัสสินค้าอัตโนมัติ / Generate Code Auto</label>
                                            <select id="product_type_auto" name="product_type_auto" class="form-control">
                                                <option value="">Select</option>
                                                <option <?php if($product_type['product_type_auto'] == '0'){?> selected <?php } ?> value="0" >None</option>
                                                <option <?php if($product_type['product_type_auto'] == '1'){?> selected <?php } ?> value="1" >Auto</option>
                
                                            </select>
                                            <p class="help-block">Example : None.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>จำนวนหลักของรหัสสินค้า / Code Digit</label>
                                            <input id="product_type_digit" name="product_type_digit"  class="form-control" value="<? echo $product_type['product_type_digit'];?>">
                                            <p class="help-block">Example : 8.</p>
                                        </div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>รายละเอียดประเภท / Type detail <font color="#F00"><b>*</b></font></label>
                                            <input id="product_type_detail" name="product_type_detail" class="form-control" value="<? echo $product_type['product_type_detail'];?>">
                                            <p class="help-block">Example : -.</p>
                                        </div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-lg-offset-9 col-lg-3" align="right">
                                        <a href="?app=product_type&action=view" class="btn btn-primary">Reset</a>
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                <br>
                            </form>
                        <?PHP } ?>


                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ<br>No.</th>
                                        <th>ชื่อประเภทสินค้า<br>Type name</th>
                                        <th>ตัวอักษรขึ้นต้น<br>First char</th>
                                        <th>รายละเอียดประเภทสินค้า<br>Type detail</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($product_types); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $product_types[$i]['product_type_name']; ?></td>
                                        <td><?php echo $product_types[$i]['product_type_first_char']; ?></td>
                                        <td><?php echo $product_types[$i]['product_type_detail']; ?></td>
                                        <td>
                                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                            <a title="Update data" href="?app=product_type&action=update&id=<?php echo $product_types[$i]['product_type_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP } ?>
                                        <?php if($license_admin_page == "High"){ ?> 
                                            <a title="Delete data" href="?app=product_type&action=delete&id=<?php echo $product_types[$i]['product_type_id'];?>" onclick="return confirm('You want to delete customer product unit : <?php echo $product_types[$i]['product_type_name']; ?>');" style="color:red;">
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
            
            
