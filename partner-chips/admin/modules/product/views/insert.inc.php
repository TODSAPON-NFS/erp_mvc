<script>
    function check(){


        var product_code = document.getElementById("product_code").value;
        var product_name = document.getElementById("product_name").value;
        var product_group = document.getElementById("product_group").value;
        var product_barcode = document.getElementById("product_barcode").value;
        var product_description = document.getElementById("product_description").value;
        var product_type = document.getElementById("product_type").value;
        var product_unit = document.getElementById("product_unit").value;
        var product_status = document.getElementById("product_status").value;
       
       
        
        product_code = $.trim(product_code);
        product_name = $.trim(product_name);
        product_group = $.trim(product_group);
        product_barcode = $.trim(product_barcode);
        product_description = $.trim(product_description);
        product_type = $.trim(product_type);
        product_unit = $.trim(product_unit);
        product_status = $.trim(product_status);
        
        

        if(product_code.length == 0){
            alert("Please input product code");
            document.getElementById("product_code").focus();
            return false;
        }else if(product_name.length == 0){
            alert("Please input product name");
            document.getElementById("product_name").focus();
            return false;
        }else if(product_group.length == 0){
            alert("Please input product group");
            document.getElementById("product_group").focus();
            return false;
        }else if(product_type.length == 0 ){
            alert("Please input product type");
            document.getElementById("product_type").focus();
            return false;
        }else if(product_status.length == 0){
            alert("Please input product status");
            document.getElementById("product_status").focus();
            return false;
        }else{
            return true;
        }



    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#product_drawing_url').attr('value', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#product_drawing_url').attr('src', '');
        }
    }

    function readURL_logo(input) {

        if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#img_logo').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_logo').attr('src', '../upload/customer/default.png');
        }
    }

    function update_code(){
        var product_type = document.getElementById("product_type").value;
        product_type = $.trim(product_type);
        if(product_type.length > 0){
            $.post( "controllers/getFirstChar.php", { 'product_type_name': product_type }, function( data ) {
                document.getElementById("product_code_first").value =  data;
            });
            
        }else{
            document.getElementById("product_code_first").value =  "";
        }
    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Product Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=product" class="btn btn-primary active btn-menu">สินค้า / Product</a>
        <a href="?app=product_category" class="btn btn-primary btn-menu">ลักษณะ / Category</a>
        <a href="?app=product_type" class="btn btn-primary btn-menu">ประเภท / Type</a>
        <a href="?app=product_group" class="btn btn-primary btn-menu">กลุ่ม / Group</a>
        <a href="?app=product_unit" class="btn btn-primary btn-menu">หน่วย / Unit</a>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                เพิ่มสินค้า / Add Product 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=product&action=add" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>รหัสสินค้า / Product code <font color="#F00"><b>*</b></font></label>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <input id="product_code_first" name="product_code_first" value="" class="form-control" readonly />
                                        </div>
                                        <div class="col-lg-9">
                                            <input id="product_code" name="product_code" class="form-control" onChange="update_code()">
                                        </div>
                                    </div>
                                    <p class="help-block">Example : VNMG060404EN.</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                
                                    <div class="form-group">
                                        <label>ชื่อสินค้า / Name. <font color="#F00"><b>*</b></font></label>
                                        <input id="product_name" name="product_name" class="form-control">
                                        <p class="help-block">Example : VNMG060404EN...</p>
                                    </div>
                                
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>ลักษณะสินค้า / Product Category  <font color="#F00"><b>*</b></font> </label>
                                    <select id="product_category_id" name="product_category_id" class="form-control" onChange="update_code()">
                                            <option value="">Select</option>
                                            <?php 
                                                for($i =  0 ; $i < count($product_category) ; $i++){
                                            ?>
                                            <option  value="<?php echo $product_category[$i]['product_category_id'] ?>"><?php echo $product_category[$i]['product_category_name'] ?></option>
                                            <?
                                                }
                                            ?>
                                        </select>
                                    <p class="help-block">Example : Special Tool.</p>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>กลุ่มสินค้า / Product Group <font color="#F00"><b>*</b></font></label>
                                        <select id="product_group" name="product_group" onchange="change_dd()" class="form-control">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($product_group) ; $i++){
                                            ?>
                                            <option value="<?php echo $product_group[$i]['product_group_id'] ?>"><?php echo $product_group[$i]['product_group_name'] ?></option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Consumable.</p>
                                    </div>
                                
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>ประเภทสินค้า / Product Type <font color="#F00"><b>*</b></font> </label>
                                    <select id="product_type" name="product_type" class="form-control" onChange="update_code()">
                                            <option value="">Select</option>
                                            <?php 
                                                for($i =  0 ; $i < count($product_type) ; $i++){
                                            ?>
                                            <option  value="<?php echo $product_type[$i]['product_type_id'] ?>"><?php echo $product_type[$i]['product_type_name'] ?></option>
                                            <?
                                                }
                                            ?>
                                        </select>
                                    <p class="help-block">Example : Special Tool.</p>
                                </div>
                            </div>
                            <!-- /.col-lg-6 (nested) -->
                        </div>

                        <!-- /.row (nested) -->
                        <div class="row">
                        
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>หมายเลขบาร์โค๊ต / Barcode </label>
                                    <input id="product_barcode" name="product_barcode" type="text" class="form-control">
                                    <p class="help-block">Example : 123456789.</p>
                                </div>
                            </div>
                            
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>หน่วยสินค้า / Product Unit <font color="#F00"><b>*</b></font> </label>
                                    <select id="product_unit" name="product_unit" class="form-control">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($product_unit) ; $i++){
                                            ?>
                                            <option value="<?php echo $product_unit[$i]['product_unit_id'] ?>"><?php echo $product_unit[$i]['product_unit_name'] ?></option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                    <p class="help-block">Example : ชิ้น.</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>สถานะสินค้า / Produc Status <font color="#F00"><b>*</b></font> </label>
                                    <select id="product_status" name="product_status" class="form-control">
                                            <option value="">Select</option>
                                            <option>Active</option>
                                            <option>Inactive</option>
            
                                        </select>
                                    <p class="help-block">Example : Active.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รายละเอียดสินค้า / Description </label>
                                    <input id="product_description" name="product_description" type="text" class="form-control">
                                    <p class="help-block">Example : Description...</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>แบบสินค้า / Drawing </label>
                                    <input id="product_drawing_url" name="product_drawing_url" type="text" readonly class="form-control">
                                    <input accept=".pdf"   type="file" id="product_drawing" name="product_drawing" onChange="readURL(this);">
                                    <p class="help-block">Example : .pdf</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รูปสินค้า / Product Picture </label>
                                    <img class="img-responsive" id="img_logo" src="../upload/product/default.png" />
                                    <input accept=".jpg , .png"   type="file" id="product_logo" name="product_logo" onChange="readURL_logo(this);">
                                    <p class="help-block">Example : .jpg or .png </p>
                                </div>
                            </div>
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