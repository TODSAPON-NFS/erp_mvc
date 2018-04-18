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

    function check_customer(){

        var customer_id = document.getElementById("customer_id").value;
        var product_name = document.getElementById("minimum_stock").value;
        var product_group = document.getElementById("safety_stock").value;
        var product_barcode = document.getElementById("product_status").value;

        customer_id = $.trim(customer_id);
        minimum_stock = $.trim(minimum_stock);
        safety_stock = $.trim(safety_stock);
        product_status = $.trim(product_status);



        if(customer_id.length == 0){
            alert("Please input customer");
            document.getElementById("customer_id").focus();
            return false;
        }else if(minimum_stock.length == 0){
            alert("Please input minimum stock");
            document.getElementById("minimum_stock").focus();
            return false;
        }else if(safety_stock.length == 0){
            alert("Please input safety stock");
            document.getElementById("safety_stock").focus();
            return false;
        }else if(product_status.length == 0){
            alert("Please input product status");
            document.getElementById("product_status").focus();
            return false;
        }else{
            return true;
        }
    }


    function check_supplier(){

        var supplier_id = document.getElementById("supplier_id").value;
        var product_buyprice = document.getElementById("product_buyprice").value;
        var lead_time = document.getElementById("lead_time").value;
        var product_supplier_status = document.getElementById("product_supplier_status").value;

        supplier_id = $.trim(supplier_id);
        product_buyprice = $.trim(product_buyprice);
        lead_time = $.trim(lead_time);
        product_supplier_status = $.trim(product_supplier_status);



        if(supplier_id.length == 0){
            alert("Please input supplier");
            document.getElementById("supplier_id").focus();
            return false;
        }else if(product_buyprice.length == 0){
            alert("Please input price");
            document.getElementById("product_buyprice").focus();
            return false;
        }else if(lead_time.length == 0){
            alert("Please input lead time");
            document.getElementById("lead_time").focus();
            return false;
        }else if(product_supplier_status.length == 0){
            alert("Please input supplier status");
            document.getElementById("product_supplier_status").focus();
            return false;
        }else{
            return true;
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
            $('#img_logo').attr('src', '../upload/product/default.png');
        }
    }

/*
    function update_code(){
        var product_type = document.getElementById("product_type").value;
        product_type = $.trim(product_type);
        if(product_type.length > 0){
            $.post( "controllers/getFirstChar.php", { 'product_type_name': product_type }, function( data ) {
                document.getElementById("first_char").value =  data;
            });
            
        }else{
            document.getElementById("first_char").value =  "";
        }
    }
*/
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
                แก้ไขสินค้า / Edit Product 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=product&action=edit" enctype="multipart/form-data">
                <input type="hidden"  id="product_id" name="product_id" value="<?php echo $product_id ?>" />
                <input type="hidden"  id="product_drawing_o" name="product_drawing_o" value="<?php echo $product['product_drawing']; ?>" /> 
                <input type="hidden"  id="product_logo_o" name="product_logo_o" value="<?php echo $product['product_logo']; ?>" /> 
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>รหัสสินค้า / Product code <font color="#F00"><b>*</b></font></label>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <input id="product_code_first" id="product_code_first" value="" class="form-control" readonly />
                                        </div>
                                        <div class="col-lg-9">
                                            <input id="product_code" name="product_code" class="form-control"  value="<?php echo $product['product_code']?>" readonly>
                                        </div>
                                    </div>
                                    <p class="help-block">Example : VNMG060404EN.</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                
                                    <div class="form-group">
                                        <label>ชื่อสินค้า / Name. <font color="#F00"><b>*</b></font></label>
                                        <input id="product_name" name="product_name" class="form-control" value="<?php echo $product['product_name']?>">
                                        <p class="help-block">Example : VNMG060404EN...</p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>ลักษณะสินค้า / Product Category  <font color="#F00"><b>*</b></font> </label>
                                        <select id="product_category_id" name="product_category_id" onchange="change_dd()" class="form-control">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($product_category) ; $i++){
                                            ?>
                                            <option <?if($product['product_category_id'] == $product_category[$i]['product_category_id'] ){?> selected <?php } ?> value="<?php echo $product_category[$i]['product_category_id'] ?>"><?php echo $product_category[$i]['product_category_name'] ?></option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Consumable.</p>
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
                                            <option <?if($product['product_group'] == $product_group[$i]['product_group_name'] ){?> selected <?php } ?> value="<?php echo $product_group[$i]['product_group_name'] ?>"><?php echo $product_group[$i]['product_group_name'] ?></option>
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
                                    <select id="product_type" name="product_type" class="form-control" >
                                    <option value="">Select</option>
                                        <?php 
                                        for($i =  0 ; $i < count($product_type) ; $i++){
                                        ?>
                                        <option <?if($product['product_type'] == $product_type[$i]['product_type_name'] ){?> selected <?php } ?> value="<?php echo $product_type[$i]['product_type_name'] ?>"><?php echo $product_type[$i]['product_type_name'] ?></option>
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
                                    <input id="product_barcode" name="product_barcode" type="text" class="form-control" value="<?php echo $product['product_barcode']?>">
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
                                            <option <?if($product['product_unit'] == $product_unit[$i]['product_unit_name'] ){?> selected <?php } ?> value="<?php echo $product_unit[$i]['product_unit_name'] ?>"><?php echo $product_unit[$i]['product_unit_name'] ?></option>
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
                                        <option <?php if($product['product_status'] == 'Active'){?> selected <?php } ?> >Active</option>
                                        <option <?php if($product['product_status'] == 'Inactive'){?> selected <?php } ?> >Inactive</option>
                                    </select>
                                    <p class="help-block">Example : Use.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รายละเอียดสินค้า / Description </label>
                                    <input id="product_description" name="product_description" type="text" class="form-control" value="<?php echo $product['product_description']?>">
                                    <p class="help-block">Example : Description...</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>แบบสินค้า / Drawing </label>
                                    <input id="product_drawing_url" name="product_drawing_url" type="text" value="<?php echo $product['product_drawing'];?>" readonly class="form-control">
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
                                        <label>รูปสินค้า / Product Picture <font color="#F00"><b>*</b></font></label>
                                        <img class="img-responsive" id="img_logo" src="../upload/product/<?php echo $product['product_logo']; ?>" />
                                    
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




<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Supplier List
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check_supplier();" 
                <?php if($product_supplier_id == ""){ ?>
                    action="index.php?app=product&action=add_supplier&id=<?php echo $product_id?>" 
                <?php }else{ ?>
                    action="index.php?app=product&action=edit_supplier&id=<?php echo $product_id?>" 
                <?php }?>
                enctype="multipart/form-data">
                <input type="hidden"  id="product_supplier_id" name="product_supplier_id" value="<?php echo $product_supplier_id ?>" />
                   
                   <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Supplier <font color="#F00"><b>*</b></font></label>
                                <select id="supplier_id" name="supplier_id"  class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($supplier) ; $i++){
                                    ?>
                                    <option <?if($supplier['supplier_id'] == $product_supplier[$i]['supplier_id'] ){?> selected <?php } ?> value="<?php echo $supplier[$i]['supplier_id'] ?>"><?php echo $supplier[$i]['supplier_name_en'] ?> (<?php echo $supplier[$i]['supplier_name_th'] ?>) </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด.</p>
                            </div>
                        </div>
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>

                     <!-- /.row (nested) -->
                     <div class="row">
                       
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Price <font color="#F00"><b>*</b></font></label>
                                <input id="product_buyprice" name="product_buyprice" type="text" class="form-control" value="<?php echo $product_supplier['product_buyprice']?>">
                                <p class="help-block">Example : 120.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Lead Time <font color="#F00"><b>*</b></font></label>
                                <input id="lead_time" name="lead_time" type="text" class="form-control" value="<?php echo $product_supplier['lead_time']?>">
                                <p class="help-block">Example : 50.</p>
                            </div>
                        </div>
                       
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Supplier Status  <font color="#F00"><b>*</b></font></label>
                                <select id="product_supplier_status" name="product_supplier_status" class="form-control">
                                    <option value="">Select</option>
                                    <option <?php if($product_supplier['product_supplier_status'] == 'Active'){?> selected <?php } ?> >Active</option>
                                    <option <?php if($product_supplier['product_supplier_status'] == 'Inactive'){?> selected <?php } ?> >Inactive</option>
                                </select>
                                <p class="help-block">Example : Active.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=product&action=update&id=<? echo $product_id;?>" class="btn btn-primary" >Reset</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                    <br>
                    <table width="100%" class="table table-striped table-bordered table-hover" id="tb-product-customer">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Supplier</th>
                                <th>Price</th>
                                <th>Lead Time</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($product_suppliers); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td><?php echo $i+1; ?></td>
                                <td><?php echo $product_suppliers[$i]['supplier_name_en']; ?> (<?php echo $product_suppliers[$i]['supplier_name_th']; ?>) </td>
                                <td class="center"><?php echo $product_suppliers[$i]['product_buyprice']; ?></td>
                                <td class="center"><?php echo $product_suppliers[$i]['lead_time']; ?></td>
                                <td class="center"><?php echo $product_suppliers[$i]['product_supplier_status']; ?></td>
                                <td>
                                    <a href="?app=product&action=update&id=<?php echo $product_id;?>&product_supplier_id=<?php echo $product_suppliers[$i]['product_supplier_id'];?>">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </a> 
                                    <a href="?app=product&action=delete&id=<?php echo $product_id;?>&product_supplier_id=<?php echo $product_suppliers[$i]['product_supplier_id'];?>" onclick="return confirm('You want to delete supplier : <?php echo $product_suppliers[$i]['supplier_name_en']; ?> (<?php echo $product_suppliers[$i]['supplier_name_th']; ?>)');" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                    </table>
                    
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>



<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Customer Stock
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check_customer();" 
                <?php if($product_customer_id == ""){ ?>
                    action="index.php?app=product&action=add_customer&id=<?php echo $product_id?>" 
                <?php }else{ ?>
                    action="index.php?app=product&action=edit_customer&id=<?php echo $product_id?>" 
                <?php }?>
                enctype="multipart/form-data">
                <input type="hidden"  id="product_customer_id" name="product_customer_id" value="<?php echo $product_customer_id ?>" />
                   
                   <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Customer <font color="#F00"><b>*</b></font></label>
                                <select id="customer_id" name="customer_id"  class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($customer) ; $i++){
                                    ?>
                                    <option <?if($customer['customer_id'] == $product_customer[$i]['customer_id'] ){?> selected <?php } ?> value="<?php echo $customer[$i]['customer_id'] ?>"><?php echo $customer[$i]['customer_name_en'] ?> (<?php echo $customer[$i]['customer_name_th'] ?>) </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด.</p>
                            </div>
                        </div>
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>

                     <!-- /.row (nested) -->
                     <div class="row">
                       
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Minimum Stock <font color="#F00"><b>*</b></font></label>
                                <input id="minimum_stock" name="minimum_stock" type="text" class="form-control" value="<?php echo $product_customer['minimum_stock']?>">
                                <p class="help-block">Example : 120.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Safety Stock <font color="#F00"><b>*</b></font></label>
                                <input id="safety_stock" name="safety_stock" type="text" class="form-control" value="<?php echo $product_customer['safety_stock']?>">
                                <p class="help-block">Example : 50.</p>
                            </div>
                        </div>
                       
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Customer Status  <font color="#F00"><b>*</b></font></label>
                                <select id="product_status" name="product_status" class="form-control">
                                    <option value="">Select</option>
                                    <option <?php if($product_customer['product_status'] == 'Active'){?> selected <?php } ?> >Active</option>
                                    <option <?php if($product_customer['product_status'] == 'Inactive'){?> selected <?php } ?> >Inactive</option>
                                </select>
                                <p class="help-block">Example : Active.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=product&action=update&id=<? echo $product_id;?>" class="btn btn-primary" >Reset</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                    <br>
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example2">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Customer</th>
                                <th>Minmum Stock</th>
                                <th>Safety Stock</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($product_customers); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td><?php echo $i+1; ?></td>
                                <td><?php echo $product_customers[$i]['customer_name_en']; ?> (<?php echo $product_customers[$i]['customer_name_th']; ?>) </td>
                                <td class="center"><?php echo $product_customers[$i]['minimum_stock']; ?></td>
                                <td class="center"><?php echo $product_customers[$i]['safety_stock']; ?></td>
                                <td class="center"><?php echo $product_customers[$i]['product_status']; ?></td>
                                <td>
                                    <a href="?app=product&action=update&id=<?php echo $product_id;?>&product_customer_id=<?php echo $product_customers[$i]['product_customer_id'];?>">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </a> 
                                    <a href="?app=product&action=delete&id=<?php echo $product_id;?>&product_customer_id=<?php echo $product_customers[$i]['product_customer_id'];?>" onclick="return confirm('You want to delete supplier : <?php echo $product_customers[$i]['customer_name_en']; ?> (<?php echo $product_customers[$i]['customer_name_th']; ?>)');" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                    </table>
                    
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>