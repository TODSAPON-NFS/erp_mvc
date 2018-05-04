<script>
    function check(){   
        var stock_date = document.getElementById("stock_date").value;
        var product_id = document.getElementById("product_id").value;
        var supplier_id = document.getElementById("supplier_id").value;
        var qty = document.getElementById("qty").value;

        stock_date = $.trim(stock_date);
        product_id = $.trim(product_id);
        supplier_id = $.trim(supplier_id);
        qty = $.trim(qty);

       if(stock_date.length == 0){
            alert("Please input date recieve.");
            document.getElementById("stock_date").focus();
            return false;
        }else  if(product_id.length == 0){
            alert("Please input product.");
            document.getElementById("product_id").focus();
            return false;
        }else  if(product_id.length == 0){
            alert("Please input product.");
            document.getElementById("product_id").focus();
            return false;
        }else  if(supplier_id.length == 0){
            alert("Please input supplier.");
            document.getElementById("supplier_id").focus();
            return false;
        }else  if(qty.length == 0){
            alert("Please input qty.");
            document.getElementById("qty").focus();
            return false;
        }else{
            return true;
        }
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Main Stock - In </h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Stock In. 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body"> 
                <form role="form" method="post" onsubmit="return check();" <?php if($stock_log_id == ''){ ?>action="index.php?app=stock_in&action=add"<?php }else{?> action="index.php?app=stock_in&action=edit&id=<? echo $stock_log_id; ?>" <?php }?> enctype="multipart/form-data">
                    <input type="hidden" id="stock_log_id" name="stock_log_id" value="<?php echo $stock_log_id?>"/>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>PO Code</label>
                                <input id="po_code" name="po_code"  class="form-control" value="<? echo $stock_in['po_code'];?>">
                                <p class="help-block">Example : PO1801001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Recieve Code</label>
                                <input id="recieve_code" name="recieve_code"  class="form-control" value="<? echo $stock_in['recieve_code'];?>">
                                <p class="help-block">Example : RE1801001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label>Date <font color="#F00"><b>*</b></font></label>
                            <input type="text" id="stock_date" name="stock_date"  class="form-control" value="<? echo $stock_in['stock_date'];?>" readonly/>
                            <p class="help-block"></p>
                        </div>
                        
                    </div>    
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Product <font color="#F00"><b>*</b></font></label>
                                <select id="product_id" name="product_id"  class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($products) ; $i++){
                                    ?>
                                    <option <?if($products['product_id'] == $stock_in[$i]['product_id'] ){?> selected <?php } ?> value="<?php echo $products[$i]['product_id'] ?>"><?php echo $products[$i]['product_name'] ?> (<?php echo $products[$i]['product_code'] ?>) </option>
                                    <?
                                    }
                                    ?>
                                </select>
                               <p class="help-block">Example : ARNO18001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Supplier <font color="#F00"><b>*</b></font></label>
                                <select id="supplier_id" name="supplier_id"  class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($suppliers) ; $i++){
                                    ?>
                                    <option <?if($suppliers['supplier_id'] == $stock_in[$i]['supplier_id'] ){?> selected <?php } ?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> (<?php echo $suppliers[$i]['supplier_name_th'] ?>) </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : Arno.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label>Qty <font color="#F00"><b>*</b></font></label>
                            <input type="text" id="qty" name="qty"  class="form-control" value="<? echo $stock_in['qty'];?>" />
                            <p class="help-block"></p>
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
                        Stock In List.
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form action="?app=stock_in" method="post">

                                <div class="row">
                                    <div class="col-lg-3">
                                            <label>Date Start </label>
                                            <input type="text" id="date_start" name="date_start"  class="form-control" value="<? echo $date_start;?>" readonly/>
                                            <p class="help-block"></p>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Date End </label>
                                            <input type="text" id="date_end" name="date_end"  class="form-control" value="<? echo $date_end;?>" readonly/>
                                            <p class="help-block"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" align="left" style="padding-top:24px;">
                                        <button type="submit" class="btn btn-success">Veiw</button>
                                    </div>
                                    
                                </div> 
                                <br>
                            </from>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Date In</th>
                                        <th>Supplier</th>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Product Type</th>
                                        <th>Product Status</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($stock_ins); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $stock_ins[$i]['stock_date']; ?></td>
                                        <td><?php echo $stock_ins[$i]['supplier_name_en'] ?> (<?php echo $suppliers[$i]['supplier_name_th'] ?>)</td>
                                        <td><?php echo $stock_ins[$i]['product_code']; ?></td>
                                        <td><?php echo $stock_ins[$i]['product_name']; ?></td>
                                        <td><?php echo $stock_ins[$i]['product_type']; ?></td>
                                        <td><?php echo $stock_ins[$i]['product_status']; ?></td>
                                        <td><?php echo $stock_ins[$i]['qty']; ?></td>
                                        <td>
                                            
                                            <a title="Update data" href="?app=stock_in&action=update&id=<?php echo $stock_ins[$i]['stock_log_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Delete data" href="?app=stock_in&action=delete&id=<?php echo $stock_ins[$i]['stock_log_id'];?>" onclick="return confirm('You want to delete stock in : <?php echo $stock_ins[$i]['product_name']; ?> (<?php echo $stock_ins[$i]['product_code']; ?>)');" style="color:red;">
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
            
            