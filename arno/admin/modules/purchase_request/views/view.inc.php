<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Request Management</h1>
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
                        รายการใบร้องขอสั่งซื้อสินค้า / Purchase Request List
                            <a class="btn btn-success " style="float:right;" href="?app=purchase_request&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ<br>No.</th>
                                        <th>วันที่ออกใบร้องขอ<br>PR Date</th>
                                        <th>หมายเลขใบร้องขอ<br>PR No.</th>
                                        <th>ร้องขอโดย<br>Request by</th>
                                        <th>ประเภทการร้องขอ<br>Request Type</th>
                                        <th>สถานะอนุมัติ<br>Accept Status</th>
                                        <th>ผู้อนุมัติ<br>Accept by</th>
                                        <th>หมายเหตุ<br>Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($purchase_requests); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $purchase_requests[$i]['purchase_request_date']; ?></td>
                                        <td><?php echo $purchase_requests[$i]['purchase_request_code']; ?> <?php if($purchase_requests[$i]['purchase_request_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $purchase_requests[$i]['purchase_request_rewrite_no']; ?></font></b> <?PHP } ?> <?php if($purchase_requests[$i]['purchase_request_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?></td>
                                        <td><?php echo $purchase_requests[$i]['request_name']; ?></td>
                                        <td><?php echo $purchase_requests[$i]['purchase_request_type']; ?></td>
                                        <td><?php echo $purchase_requests[$i]['purchase_request_accept_status']; ?></td>
                                        <td><?php echo $purchase_requests[$i]['accept_name']; ?></td>
                                        <td><?php echo $purchase_requests[$i]['purchase_request_remark']; ?></td>
                                        
                                        <td>
                                            <a href="?app=purchase_request&action=detail&id=<?php echo $purchase_requests[$i]['purchase_request_id'];?>" title="ดูรายละเอียดใบร้องขอ">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>

                                        <?php if($purchase_requests[$i]['purchase_request_accept_status'] == "Waiting"){ ?>
                                            
                                            <?php if($purchase_requests[$i]['purchase_request_cancelled'] == 0){ ?>
                                                <a href="?app=purchase_request&action=cancelled&id=<?php echo $purchase_requests[$i]['purchase_request_id'];?>"  title="ยกเลิกใบร้องขอ" onclick="return confirm('You want to cancelled purchase request : <?php echo $purchase_requests[$i]['purchase_request_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=purchase_request&action=rewrite&id=<?php echo $purchase_requests[$i]['purchase_request_id'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite purchase request : <?php echo $purchase_requests[$i]['purchase_request_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=purchase_request&action=update&id=<?php echo $purchase_requests[$i]['purchase_request_id'];?>"  title="แก้ไขใบร้องขอ">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a> 
                                            <?php } else if($purchase_requests[$i]['count_rewrite'] == 0) { ?>

                                                <a href="?app=purchase_request&action=uncancelled&id=<?php echo $purchase_requests[$i]['purchase_request_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_requests[$i]['purchase_request_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=purchase_request&action=rewrite&id=<?php echo $purchase_requests[$i]['purchase_request_id'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite purchase request : <?php echo $purchase_requests[$i]['purchase_request_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=purchase_request&action=delete&id=<?php echo $purchase_requests[$i]['purchase_request_id'];?>"  title="ลบใบร้องขอ" onclick="return confirm('You want to delete purchase request : <?php echo $purchase_requests[$i]['purchase_request_code']; ?>');" style="color:red;">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                            <?PHP } else { ?> 
                                                <a href="?app=purchase_request&action=uncancelled&id=<?php echo $purchase_requests[$i]['purchase_request_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_requests[$i]['purchase_request_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=purchase_request&action=delete&id=<?php echo $purchase_requests[$i]['purchase_request_id'];?>"  title="ลบใบร้องขอ" onclick="return confirm('You want to delete purchase request : <?php echo $purchase_requests[$i]['purchase_request_code']; ?>');" style="color:red;">
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
            
            
