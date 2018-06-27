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


function getStockDetail(){
    var stock_group_id = $('#stock_group_id').val();
    window.location = "?app=summit_product&action=view-stock&stock_group_id="+stock_group_id;

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
        <a href="?app=summit_product&action=view-product" class="btn btn-primary btn-menu ">แบ่งตามสินค้า</a>
        <a href="?app=summit_product&action=view-stock" class="btn btn-primary btn-menu active">แบ่งตามคลังสินค้า</a>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                รายละเอียดคลังสินค้า / Stock detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>คลังสินค้า / Stock <font color="#F00"><b>*</b></font></label>
                                    <select id="stock_group_id" name="stock_group_id" onchange="getStockDetail()"  class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($stock_groups) ; $i++){
                                    ?>
                                    <option <?if($stock_groups[$i]['stock_group_id'] == $stock_group_id ){?> selected <?php } ?> value="<?php echo $stock_groups[$i]['stock_group_id'] ?>"><?php echo $stock_groups[$i]['stock_group_name'] ?>  </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                
                                <div class="form-group">
                                    <label>รายละเอียด / Description  <font color="#F00"><b>*</b></font> </label>
                                    <p id="stock_group_detail" class="help-block"><?PHP echo $stock_group ['stock_group_detail'];?></p>
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
                <form role="form" method="post" onsubmit="return check();"   action="index.php?app=summit_product&action=add-stock&stock_group_id=<?php echo $stock_group_id?>"   enctype="multipart/form-data">
                <input type="hidden"  id="stock_group_id" name="stock_group_id" value="<?php echo $stock_group_id ?>" />
                   
                   <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>สินค้า / Product <font color="#F00"><b>*</b></font></label>
                                <select id="product_id" name="product_id"  class="form-control select" data-live-search="true">
                                        <option value="">Select</option>
                                        <?php 
                                        for($i =  0 ; $i < count($products) ; $i++){
                                        ?>
                                        <option <?if($product_id == $products[$i]['product_id'] ){?> selected <?php } ?> value="<?php echo $products[$i]['product_id'] ?>">[<?php echo $products[$i]['product_first_code'].$products[$i]['product_code'] ?>] <?php echo $products[$i]['product_name'] ?></option>
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
                    <br>
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>สินค้า</th>
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
                                <td><?php echo $summit_products[$i]['product_name']; ?></td>
                                <td align="right"><?php echo number_format($summit_products[$i]['summit_product_qty'],0); ?></td>
                                <td align="right"><?php echo number_format($summit_products[$i]['summit_product_cost'],2); ?></td>
                                <td align="right"><?php echo number_format($summit_products[$i]['summit_product_total'],2); ?></td>
                                <td>
                                    <a href="?app=summit_product&action=delete-stock&product_id=<?php echo $product_id;?>&summit_product_id=<?php echo $summit_products[$i]['summit_product_id'];?>" onclick="return confirm('You want to delete supplier : <?php echo $summit_products[$i]['supplier_name_en']; ?> (<?php echo $summit_products[$i]['supplier_name_th']; ?>)');" style="color:red;">
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
