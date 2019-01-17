
<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();
        var view_type = $("#view_type").val();
        if( view_type == 'paper'){
            window.location = "index.php?app=purchase_order&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword+"&view_type=paper"; 
       
        }else{
            window.location = "index.php?app=purchase_order&action=view_list&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword+"&view_type=product";    
        
        }

    }

    function export_excel(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "print.php?app=purchase_order&action=excel&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
    }
</script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Purchase Order Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<ul class="nav nav-tabs">
    <li  class="active" ><a data-toggle="tab" class="tabs" href="#standard">Standard Order <?PHP if(count($supplier_orders) > 0){ ?>(<b style="color:red;"><?PHP echo count($supplier_orders); ?></b>)<?PHP } ?></a></li>
    <li><a class="tabs" data-toggle="tab" href="#blanked">Blanked Order <?PHP if(count($supplier_blankeds) > 0){ ?>(<b style="color:red;"><?PHP echo count($supplier_blankeds); ?></b>)<?PHP } ?> </a></li>
    <li><a class="tabs" data-toggle="tab" href="#test">Test Order <?PHP if(count($supplier_tests) > 0){ ?>(<b style="color:red;"><?PHP echo count($supplier_tests); ?></b>)<?PHP } ?> </a></li> 
    <li><a class="tabs" data-toggle="tab" href="#regrind">Regrind Order <?PHP if(count($supplier_regrinds) > 0){ ?>(<b style="color:red;"><?PHP echo count($supplier_regrinds); ?></b>)<?PHP } ?> </a></li> 
</ul>

  <div class="tab-content">

    <div id="standard" class="tab-pane fade in active">
        <h3>Standard Order</h3>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    เปิดใบสั่งซื้ออ้างอิงตามบริษัท / Purchase order to do
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">

                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th width="64px" >ลำดับ <br>No.</th>
                                    <th>ผู้ขาย <br>Supplier</th>
                                    <th width="180px" >เปิดใบสั่งซื้อ <br>Open purchase order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=0; $i < count($supplier_orders); $i++){
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php  echo $supplier_orders[$i]['supplier_name_en'];  ?></td>
                                    <td>
                                        <a href="?app=purchase_order&action=insert&supplier_id=<?php echo $supplier_orders[$i]['supplier_id'];?>">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
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


    </div>

    <div id="blanked" class="tab-pane fade">
        <h3>Blanked Order</h3>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    เปิดใบสั่งซื้อสินค้าอ้างอิงตามบริษัท / Blanked order to do
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">

                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th width="64px" >ลำดับ <br>No.</th>
                                    <th>ผู้ขาย <br>Supplier</th>
                                    <th width="180px" >เปิดใบสั่งซื้อ <br>Open Blanked order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=0; $i < count($supplier_blankeds); $i++){
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $supplier_blankeds[$i]['purchase_request_code']; ?> <?php  echo $supplier_blankeds[$i]['supplier_name_en'];   ?></td>
                                    <td>
                                        <a href="?app=purchase_order&action=insert&type=blanked&purchase_request_id=<?php echo $supplier_blankeds[$i]['purchase_request_id'];?>&supplier_id=<?php echo $supplier_blankeds[$i]['supplier_id'];?>">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
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
    </div>

    <div id="test" class="tab-pane fade">
        <h3>Test Order</h3>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    เปิดใบสั่งซื้อสินค้าทดลองอ้างอิงตามบริษัท / Test order to do
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">

                        <table width="100%" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="64px" >ลำดับ <br>No.</th>
                                    <th>ผู้ขาย <br>Supplier</th>
                                    <th width="180px" >เปิดใบสั่งซื้อ <br>Open Test order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=0; $i < count($supplier_tests); $i++){
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php  echo $supplier_tests[$i]['supplier_name_en'];   ?></td>
                                    <td>
                                        <a href="?app=purchase_order&action=insert&type=test&supplier_id=<?php echo $supplier_tests[$i]['supplier_id'];?>">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
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
    </div>

    <div id="regrind" class="tab-pane fade">
        <h3>Regrind Order</h3>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    เปิดใบสั่งซื้อสินค้าทดลองอ้างอิงตามบริษัท / Regrind order to do
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">

                        <table width="100%" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="64px" >ลำดับ <br>No.</th>
                                    <th>ผู้ขาย <br>Supplier</th>
                                    <th width="180px" >เปิดใบสั่งซื้อ <br>Open Regrind order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=0; $i < count($supplier_regrinds); $i++){
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $supplier_regrinds[$i]['supplier_name_en'];   ?></td>
                                    <td>
                                        <a href="?app=purchase_order&action=insert&type=regrind&supplier_id=<?php echo $supplier_regrinds[$i]['supplier_id'];?>">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
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

    </div>

</div>






<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        รายใบสั่งซื้อ / Purchase Order List
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-warning " style="float:right;margin-left:8px;" href="?app=purchase_order&action=insert&type=test" ><i class="fa fa-plus" aria-hidden="true"></i> Add Test Order</a>
                        <a class="btn btn-danger " style="float:right;margin-left:8px;" href="?app=purchase_order&action=insert&type=blanked" ><i class="fa fa-plus" aria-hidden="true"></i> Add Blanked Order</a>
                        <a class="btn btn-success " style="float:right;margin-left:8px;" href="?app=purchase_order&action=insert&type=standard" ><i class="fa fa-plus" aria-hidden="true"></i> Add Standard Order</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>วันที่ออกใบสั่งซื้อ</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start;?>"  class="form-control calendar" readonly/>
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/>
                                </div>
                            </div>
                            <p class="help-block">01-01-2018 - 31-12-2018</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ผู้ซื้อ </label>
                            <select id="supplier_id" name="supplier_id" class="form-control select"  data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($suppliers) ; $i++){
                                ?>
                                <option <?php if($suppliers[$i]['supplier_id'] == $supplier_id){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>คำค้น <font color="#F00"><b>*</b></font></label>
                            <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                            <p class="help-block">Example : T001.</p>
                        </div>
                    </div>
                    
                <div class="col-md-2">
                        <div class="form-group">
                            <label>แสดง </label>
                            <select id="view_type" name="view_type" class="form-control select" data-live-search="true"> 
                                <option <?PHP   if($view_type == 'paper'){?> selected <?PHP   }?> value="paper">ตามใบสั่งซื้อ</option> 
                                <option <?PHP   if($view_type == 'product'){?> selected <?PHP   }?> value="product">ตามรายการสั่งซื้อ</option> 
                            </select>
                            <p class="help-block">Example : ตามใบสั่งซื้อ.</p>
                        </div>
                    </div>

                </div>
                
                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                        <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="export_excel();"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button>
                        <a href="index.php?app=purchase_order" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br> 

                <div class="row">
                    <div class="col-sm-12">
                        <table width="100%" class="table table-striped table-bordered table-hover"  id="dataTables-example" >
                            <thead>
                                <tr>
                                    <th>ลำดับ<br>No.</th>
                                    <th>หมายเลขใบสั่งซื้อ<br>PO No.</th>
                                    <th>วันที่ออกใบสั่งซื้อ<br>PO Date</th>
                                    <th>ผู้ขาย<br>Supplier</th>
                                    <th>รหัสสินค้า<br> Product Code</th>
                                    <th>ชื่อสินค้า<br> Product Name</th>
                                    <th>รหัสเอกสาร<br>Invoice Code</th>
                                    <th>จำนวน <br> QTY</th> 
                                    <th>สถานะ<br>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=0; $i < count($purchase_orders); $i++){
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $purchase_orders[$i]['purchase_order_code']; ?> <b 
                                    <?PHP 
                                        if( $purchase_orders[$i]['purchase_order_type'] == "BLANKED") {echo ' style="color:#D9534F;"';}
                                        else if ($purchase_orders[$i]['purchase_order_type'] == "TEST"){echo ' style="color:#F0AD4E;"';}
                                        else{ echo ' style="color:#449D44;"';}?>
                                        
                                    >[<?PHP echo $purchase_orders[$i]['purchase_order_type']; ?>]</b> <?php if($purchase_orders[$i]['purchase_order_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $purchase_orders[$i]['purchase_order_rewrite_no']; ?></font></b> <?PHP } ?> <?php if($purchase_orders[$i]['purchase_order_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?></td>
                                   
                                   
                                    <td data-order="<?php echo  $timestamp = strtotime($purchase_orders[$i]['purchase_order_date']) ?>" >
                                        <?php echo ($purchase_orders[$i]['purchase_order_date']); ?>
                                    </td>
                                    
                                    <td>
                                        <?php echo $purchase_orders[$i]['supplier_name_en']; ?><br>
                                        (<?php echo $purchase_orders[$i]['supplier_name_th']; ?>)
                                     </td>
                                    <td>
                                    
                                    <?php echo $purchase_orders[$i]['product_code']; ?><br>
                                    
                                    </td>
                                    <td>
                                    <?php echo $purchase_orders[$i]['product_name']; ?><br>
                                    
                                    
                                    </td>
                                   

                                    <td><?PHP   
                                    // echo  $purchase_orders[$i]['purchase_order_list_id'];
                                    $invoice_product = $purchase_order_model -> getPurchaseOrderInvoiceProductBy($purchase_orders[$i]['purchase_order_list_id']);
                                   
                                        // echo "<pre>";
                                        // print_r($invoice_product);
                                        // echo"</pre>";

                                    for($j = 0; $j<count($invoice_product); $j++){ 
                                            # code...
                                        
                                        ?>
                                    <ul class="list-inline">
                                     <li class="list-inline-item">
                                            <a href="index.php?app=invoice_supplier&action=detail&id=<?PHP echo $invoice_product[$j]['invoice_supplier_id']; ?>" target="_blank">
                                                <?PHP
                                                echo $invoice_product[$j]['invoice_supplier_code_gen']; 
                                                ?>
                                            </a>
                                        </li>
                                    </ul>
                                        <?PHP 
                                    
                                }
                                    
                                    ?></td> 
                                     <td><?PHP   
                                    if($purchase_orders[$i]['invoice_supplier_list_qty'] == null){
                                    ?>
                                    0
                                    <?PHP
                                    }else{
                                    ?>
                                    <?PHP
                                    }
                                    echo $purchase_orders[$i]['invoice_supplier_list_qty']; 
                                    
                                    ?> / <?PHP   echo $purchase_orders[$i]['purchase_order_list_qty']; ?>
                                    </td>
                                    
                                    <td>
                                    <?PHP 
                                        if($purchase_orders[$i]['invoice_supplier_list_qty'] == $purchase_orders[$i]['purchase_order_list_qty']) {
                                    ?>
                                    <B>
                                        <p class="font-weight-bold text-success">ครบ</p>
                                    </b>
                                    <?PHP 
                                        } else if($purchase_orders[$i]['invoice_supplier_list_qty'] > $purchase_orders[$i]['purchase_order_list_qty']) {
                                    ?>
                                    <B>
                                        <p class="font-weight-bold text-warning">เกิน</p>
                                    </b>
                                    <?PHP 
                                        }else{
                                    ?>
                                    <B>
                                        <p class="font-weight-bold text-danger">ไม่ครบ</p>
                                    </b>
                                    <?PHP
                                        }
                                    ?>
                                    </td>

                                    <td>
                                        <a href="?app=purchase_order&action=detail&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>">
                                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                        </a>

                                        <a href="print.php?app=purchase_order&action=excel&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>" target="_blank"  title="นำออกข้อมูล" style="color:green;">
                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        </a> 
                                        <a href="print.php?app=purchase_order&action=pdf&lan=th&id=<?PHP echo $purchase_orders[$i]['purchase_order_id'];?>" target="_blank" title="พิมพ์เอกสารภาษาไทย"  style="color:orange;">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a>
                                        <a href="print.php?app=purchase_order&action=pdf&lan=en&id=<?PHP echo $purchase_orders[$i]['purchase_order_id'];?>" target="_blank" title="พิมพ์เอกสารภาษาอังกฤษ">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a>

                                        <?php if($purchase_orders[$i]['purchase_order_status'] == "New" || $purchase_orders[$i]['purchase_order_status'] == "Approved"){ ?>
                                            
                                            <?php if($purchase_orders[$i]['purchase_order_cancelled'] == 0){ ?>


                                                <?PHP if( $license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?> 
                                                <a href="?app=purchase_order&action=cancelled&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>"  title="ยกเลิกใบร้องขอ" onclick="return confirm('You want to cancelled purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=purchase_order&action=rewrite&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=purchase_order&action=update&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>"  title="แก้ไขใบร้องขอ">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a> 
                                                <?PHP } ?>


                                            <?php } else if($purchase_orders[$i]['count_rewrite'] == 0) { ?>


                                                <?PHP if( $license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?> 
                                                <a href="?app=purchase_order&action=uncancelled&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=purchase_order&action=rewrite&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>


                                                <?PHP if( $license_purchase_page == "High"){ ?> 
                                                <a href="?app=purchase_order&action=delete&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>" onclick="return confirm('You want to delete Purchase Order : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:red;">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>


                                            <?PHP }else{ ?>

                                                <?PHP if( $license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?> 
                                                <a href="?app=purchase_order&action=uncancelled&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>


                                                <?PHP if( $license_purchase_page == "High"){ ?> 
                                                <a href="?app=purchase_order&action=delete&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>" onclick="return confirm('You want to delete Purchase Order : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:red;">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>

                                                
                                            <?PHP } ?>
                                                
                                            
                                        <?php } ?>

                                    </td>

                                </tr>
                                <?
                                    
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                 
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
