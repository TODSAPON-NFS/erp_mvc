<script>

function check(){


var product_id = document.getElementById("product_id").value;
var summit_product_qty = document.getElementById("summit_product_qty").value;
var summit_product_cost = document.getElementById("summit_product_cost").value;
var stock_group_id = document.getElementById("stock_group_id").value;

product_id = $.trim(product_id);
summit_product_qty = $.trim(summit_product_qty);
summit_product_cost = $.trim(summit_product_cost);
stock_group_id = $.trim(stock_group_id);


if(product_id.length == 0){
    alert("Please input product.");
    document.getElementById("product_id").focus();
    return false;
}else if(stock_group_id.length == 0){
    alert("Please input stock");
    document.getElementById("stock_group_id").focus();
    return false;
}else if(summit_product_qty.length == 0){
    alert("Please input product qty.");
    document.getElementById("summit_product_qty").focus();
    return false;
}else if(summit_product_cost.length == 0){
    alert("Please input product price.");
    document.getElementById("summit_product_cost").focus();
    return false;
}else{
    return true;
}



}


function getProductDetail(){
    var product_id = $('#product_id_select').val();
    window.location = "?app=summit_product&action=view-product&product_id="+product_id;
/*
    $.post( "controllers/getProductByID.php", { 'product_id': product_id }, function( data ) {
        console.log(data);
        document.getElementById('product_category_name').innerHTML  = data.product_category_name;
        document.getElementById('product_group_name').innerHTML  = data.product_group_name;
        document.getElementById('product_type_name').innerHTML  = data.product_type_name;
        document.getElementById('product_barcode').innerHTML  = data.product_barcode;
        document.getElementById('product_unit_name').innerHTML  = data.product_unit_name;
        document.getElementById('product_status').innerHTML  = data.product_status;
        document.getElementById('product_description').innerHTML  = data.product_description;
        document.getElementById('product_id').value = product_id;

    });
*/

}
 

function update_sum(){

    var qty =  parseFloat(document.getElementById('summit_product_qty').value.replace(',',''));
    var price =  parseFloat(document.getElementById('summit_product_cost').value .replace(',',''));
    var sum =  parseFloat(document.getElementById('summit_product_total').value .replace(',',''));


    if(isNaN(qty)){
        qty = 0;
    }

    if(isNaN(price)){
        price = 0.0;
    }

    if(isNaN(sum)){
        sum = 0.0;
    }


    sum = qty*price;




    document.getElementById('summit_product_qty').value = qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    document.getElementById('summit_product_cost').value = price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    document.getElementById('summit_product_total').value = sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");



}

</script>

<div class="row">
    <div class="col-lg-8">  
        <h1 class="page-header">Summit Product Management</h1>
    </div>
    <div class="col-lg-4" align="right">
        <a href="?app=summit_product&action=view-product" class="btn btn-primary btn-menu active">แบ่งตามสินค้า</a>
        <a href="?app=summit_product&action=view-stock" class="btn btn-primary btn-menu ">แบ่งตามคลังสินค้า</a>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                รายละเอียดสินค้า / Product detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รหัสสินค้า / Product code <font color="#F00"><b>*</b></font></label>
                                    <select id="product_id_select" name="product_id_select" onchange="getProductDetail()" class="form-control select" data-live-search="true">
                                        <option value="">Select</option>
                                        <?php 
                                        for($i =  0 ; $i < count($products) ; $i++){
                                        ?>
                                        <option <?if($product_id == $products[$i]['product_id'] ){?> selected <?php } ?> value="<?php echo $products[$i]['product_id'] ?>">[<?php echo $products[$i]['product_first_code'].$products[$i]['product_code'] ?>] <?php echo $products[$i]['product_name'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>ลักษณะสินค้า / Product Category  <font color="#F00"><b>*</b></font> </label>
                                        <p id="product_category_name" class="help-block"><?PHP echo $product ['product_category_name'];?></p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>กลุ่มสินค้า / Product Group <font color="#F00"><b>*</b></font></label>
                                        <p id="product_group_name" class="help-block"><?PHP echo $product ['product_group_name'];?></p>
                                    </div>
                                
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>ประเภทสินค้า / Product Type <font color="#F00"><b>*</b></font> </label>
                                    <p id="product_type_name" class="help-block"><?PHP echo $product ['product_type_name'];?></p>
                                </div>
                            </div>
                            
                            <!-- /.col-lg-6 (nested) -->
                        </div>

                        <!-- /.row (nested) -->
                        <div class="row">
                        
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>หมายเลขบาร์โค๊ต / Barcode </label>
                                    <p id="product_barcode" class="help-block"><?PHP echo $product ['product_barcode'];?></p>
                                </div>
                            </div>
                            
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>หน่วยสินค้า / Product Unit <font color="#F00"><b>*</b></font> </label>
                                    <p id="product_unit_name" class="help-block"><?PHP echo $product ['product_unit_name'];?></p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>สถานะสินค้า / Produc Status <font color="#F00"><b>*</b></font> </label>
                                    <p id="product_status" class="help-block"><?PHP echo $product ['product_status'];?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รายละเอียดสินค้า / Description </label>
                                    <p id="product_description" class="help-block"><?PHP echo $product ['product_description'];?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="col-lg-2">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>รูปสินค้า / Product Picture <font color="#F00"><b>*</b></font></label>
                                        <img id="product_logo" class="img-responsive" src="../upload/product/default.png" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                รายการสินค้ายกยอดมา
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();"   action="index.php?app=summit_product&action=add-product&product_id=<?php echo $product_id?>"   enctype="multipart/form-data">
                <input type="hidden"  id="product_id" name="product_id" value="<?php echo $product_id ?>" />
                   
                   <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>คลังสินค้า / Stock <font color="#F00"><b>*</b></font></label>
                                <select id="stock_group_id" name="stock_group_id"  class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($stock_groups) ; $i++){
                                    ?>
                                    <option <?if($stock_groups[$i]['stock_group_id'] == $stock_group_id ){?> selected <?php } ?> value="<?php echo $stock_groups[$i]['stock_group_id'] ?>"><?php echo $stock_groups[$i]['stock_group_name'] ?>  </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : คลังสินค้าหลัก.</p>
                            </div>
                        </div>
                       
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>จำนวน / Qty <font color="#F00"><b>*</b></font></label>
                                <input id="summit_product_qty" name="summit_product_qty" type="text" onchange="update_sum();" class="form-control" style="text-align:right;" value="<?php echo number_format($summit_products['summit_product_qty'],0);?>">
                                <p class="help-block">Example : 100 pc.</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ราคา / price <font color="#F00"><b>*</b></font></label>
                                <input id="summit_product_cost" name="summit_product_cost" type="text" onchange="update_sum();" class="form-control" style="text-align:right;" value="<?php echo number_format($summit_products['summit_product_cost'],2);?>">
                                <p class="help-block">Example : 50.</p>
                            </div>
                        </div>
                       
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ราคารวม / Total price <font color="#F00"><b>*</b></font></label>
                                <input id="summit_product_total" name="summit_product_total" type="text" class="form-control" style="text-align:right;" value="<?php echo number_format($summit_products['summit_product_total'],2);?>" readonly>
                                <p class="help-block">Example : 5000.</p>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=product&action=update&id=<? echo $product_id;?>" class="btn btn-primary" >Reset</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
                <br>

                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($summit_products),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=summit_product&action=view-product&product_id=<?php echo $product_id?>&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_product&action=view-product&product_id=<?php echo $product_id?>&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=summit_product&action=view-product&product_id=<?php echo $product_id?>&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_product&action=view-product&product_id=<?php echo $product_id?>&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_product&action=view-product&product_id=<?php echo $product_id?>&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=summit_product&action=view-product&product_id=<?php echo $product_id?>&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>


                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>คลังสินค้า</th>
                            <th>จำนวน</th>
                            <th>ราคาต่อชิ้น</th>
                            <th>ราคารวม</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($summit_products); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $summit_products[$i]['stock_group_name']; ?></td>
                            <td align="right"><?php echo number_format($summit_products[$i]['summit_product_qty'],0); ?></td>
                            <td align="right"><?php echo number_format($summit_products[$i]['summit_product_cost'],2); ?></td>
                            <td align="right"><?php echo number_format($summit_products[$i]['summit_product_total'],2); ?></td>
                            <td>
                                <a href="?app=summit_product&action=delete-product&product_id=<?php echo $product_id;?>&summit_product_id=<?php echo $summit_products[$i]['summit_product_id'];?>" onclick="return confirm('You want to delete supplier : <?php echo $summit_products[$i]['supplier_name_en']; ?> (<?php echo $summit_products[$i]['supplier_name_th']; ?>)');" style="color:red;">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                </table>

                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($summit_products),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=summit_product&action=view-product&product_id=<?php echo $product_id?>&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_product&action=view-product&product_id=<?php echo $product_id?>&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=summit_product&action=view-product&product_id=<?php echo $product_id?>&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_product&action=view-product&product_id=<?php echo $product_id?>&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_product&action=view-product&product_id=<?php echo $product_id?>&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=summit_product&action=view-product&product_id=<?php echo $product_id?>&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>


                    
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
