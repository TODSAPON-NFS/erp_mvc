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
    <div class="col-lg-12">
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
</div>


<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
             รายใบสั่งซื้อ / Purchase Order List
                <a class="btn btn-success " style="float:right;" href="?app=purchase_order&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
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
                            <td><?php echo $purchase_orders[$i]['purchase_order_code']; ?></td>
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
                                <a href="?app=purchase_order&action=update&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                               
                            <?php } ?>
                                <a href="?app=purchase_order&action=delete&id=<?php echo $purchase_orders[$i]['purchase_order_id'];?>" onclick="return confirm('You want to delete Purchase Order : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:red;">
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
            
            
