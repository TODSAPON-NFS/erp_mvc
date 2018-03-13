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
                                        <th>No.</th>
                                        <th>PR Date</th>
                                        <th>PR No.</th>
                                        <th>Request by</th>
                                        <th>Request Type</th>
                                        <th>Accept Status</th>
                                        <th>Accept by</th>
                                        <th>Remark</th>
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
                                        <td><?php echo $purchase_requests[$i]['purchase_request_code']; ?></td>
                                        <td><?php echo $purchase_requests[$i]['request_name']; ?></td>
                                        <td><?php echo $purchase_requests[$i]['purchase_request_type']; ?></td>
                                        <td><?php echo $purchase_requests[$i]['purchase_request_accept_status']; ?></td>
                                        <td><?php echo $purchase_requests[$i]['accept_name']; ?></td>
                                        <td><?php echo $purchase_requests[$i]['purchase_request_remark']; ?></td>

                                        <td>
                                            <a href="?app=purchase_request&action=detail&id=<?php echo $purchase_requests[$i]['purchase_request_id'];?>">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>

                                        <?php if($purchase_requests[$i]['purchase_request_accept_status'] == "Waiting"){ ?>
                                            <a href="?app=purchase_request&action=update&id=<?php echo $purchase_requests[$i]['purchase_request_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=purchase_request&action=delete&id=<?php echo $purchase_requests[$i]['purchase_request_id'];?>" onclick="return confirm('You want to delete purchase request : <?php echo $purchase_requests[$i]['purchase_request_code']; ?>');" style="color:red;">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
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
            
            
