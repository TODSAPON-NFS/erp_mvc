
<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=purchase_order&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
    }
</script>
<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Order Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        
    </div>
    <!-- /.col-lg-12 -->
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
            เปิดใบสั่งซื้ออ้างอิงตามบริษัท / Purchase order to do
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
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
                            <td><?php echo $supplier_orders[$i]['supplier_name_en']; ?> (<?php echo $supplier_orders[$i]['supplier_name_th']; ?>)</td>
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

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
            เปิดใบสั่งซื้อสินค้าทดลองอ้างอิงตามบริษัท / Test order to do
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
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
                            <td><?php echo $supplier_tests[$i]['supplier_name_en']; ?> (<?php echo $supplier_tests[$i]['supplier_name_th']; ?>)</td>
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


<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        รายใบสั่งซื้อ / Purchase Order List
                    </div>
                    <div class="col-md-4">
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
                                <option <?php if($suppliers[$i]['supplier_id'] == $supplier_id){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> (<?php echo $suppliers[$i]['supplier_name_th'] ?>)</option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>คำค้น <font color="#F00"><b>*</b></font></label>
                            <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                            <p class="help-block">Example : T001.</p>
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
                        <a href="index.php?app=purchase_order" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ลำดับ<br>No.</th>
                            <th>วันที่ออกใบสั่งซื้อ<br>PO Date</th>
                            <th>หมายเลขใบสั่งซื้อ<br>PO No.</th>
                            <th>ผู้ขาย<br>Supplier</th>
                            <th>ผู้ออกใบสั่งซื้อ<br>Request by</th>
							<th>สถานะสั่งซื้อ<br>PO Status</th>
                            <th>สถานะอนุมัติ<br>Accept Status</th>
                            <th>ผู้อนุมัติ<br>Accept by</th>
                            <th>หมายเหตุ<br>Remark</th>
							
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($purchase_orders); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $purchase_orders[$i]['purchase_order_date']; ?></td>
                            <td><?php echo $purchase_orders[$i]['purchase_order_code']; ?> <?php if($purchase_orders[$i]['purchase_order_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $purchase_orders[$i]['purchase_order_rewrite_no']; ?></font></b> <?PHP } ?> <?php if($purchase_orders[$i]['purchase_order_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?></td>
                            <td><?php echo $purchase_orders[$i]['supplier_name']; ?> </td>
                            <td><?php echo $purchase_orders[$i]['employee_name']; ?></td>
							<td><?php echo $purchase_orders[$i]['purchase_order_status']; ?></td>
                            <td><?php echo $purchase_orders[$i]['purchase_order_accept_status']; ?></td>
                            <td><?php echo $purchase_orders[$i]['accept_name']; ?></td>
                            <td><?php echo $purchase_orders[$i]['purchase_order_remark']; ?></td>

                            <td>
                                <a href="?app=purchase_order&action=detail&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>">
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                </a>


                                <?php if($purchase_orders[$i]['purchase_order_status'] == "New" || $purchase_orders[$i]['purchase_order_status'] == "Approved"){ ?>
                                    
                                    <?php if($purchase_orders[$i]['purchase_order_cancelled'] == 0){ ?>
                                        <a href="?app=purchase_order&action=cancelled&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>"  title="ยกเลิกใบร้องขอ" onclick="return confirm('You want to cancelled purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:#F00;">
                                            <i class="fa fa-ban" aria-hidden="true"></i>
                                        </a>
                                        <a href="?app=purchase_order&action=rewrite&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:#F00;">
                                            <i class="fa fa-registered" aria-hidden="true"></i>
                                        </a>
                                        <a href="?app=purchase_order&action=update&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>"  title="แก้ไขใบร้องขอ">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a> 
                                    <?php } else if($purchase_orders[$i]['count_rewrite'] == 0) { ?>

                                        <a href="?app=purchase_order&action=uncancelled&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" >
                                            <i class="fa fa-undo" aria-hidden="true"></i>
                                        </a>
                                        <a href="?app=purchase_order&action=rewrite&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:#F00;">
                                            <i class="fa fa-registered" aria-hidden="true"></i>
                                        </a>
                                        <a href="?app=purchase_order&action=delete&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>" onclick="return confirm('You want to delete Purchase Order : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:red;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    <?PHP }else{ ?>
                                        <a href="?app=purchase_order&action=uncancelled&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" >
                                            <i class="fa fa-undo" aria-hidden="true"></i>
                                        </a>
                                        <a href="?app=purchase_order&action=delete&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>" onclick="return confirm('You want to delete Purchase Order : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:red;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
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
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
