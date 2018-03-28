<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Customer Order Management</h1>
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
                        รายการใบสั่งซื้อสินค้าของลูกค้า / Customer Order List
                            <a class="btn btn-success " style="float:right;" href="?app=customer_purchase_order&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>PO Date</th>
                                        <th>PO No.</th>
                                        <th>Customer</th>
                                        <th>Employee</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                        <th>Invoice</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($customer_purchase_orders); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $customer_purchase_orders[$i]['customer_purchase_order_date']; ?></td>
                                        <td><?php echo $customer_purchase_orders[$i]['customer_purchase_order_code']; ?></td>
                                        <td><?php echo $customer_purchase_orders[$i]['customer_name']; ?></td>
                                        <td><?php echo $customer_purchase_orders[$i]['employee_name']; ?></td>
                                        <td><?php echo $customer_purchase_orders[$i]['customer_purchase_order_status']; ?></td>
                                        <td><?php echo $customer_purchase_orders[$i]['customer_purchase_order_remark']; ?></td>
                                        <td>-</td>
                                        <td>
                                            <?
                                                if($customer_purchase_orders[$i]['customer_purchase_order_file'] != ""){
                                            ?>
                                                <a href="../upload/customer_purchase_order/<?php echo $customer_purchase_orders[$i]['customer_purchase_order_file'];?>" target="_blank">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                </a> 
                                            <?
                                                }
                                            ?>
                                            <a href="?app=customer_purchase_order&action=detail&id=<?php echo $customer_purchase_orders[$i]['customer_purchase_order_id'];?>">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>

                                        <?php if($customer_purchase_orders[$i]['customer_purchase_order_status'] == "Waiting"){ ?>
                                            <a href="?app=customer_purchase_order&action=update&id=<?php echo $customer_purchase_orders[$i]['customer_purchase_order_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=customer_purchase_order&action=delete&id=<?php echo $customer_purchase_orders[$i]['customer_purchase_order_id'];?>" onclick="return confirm('You want to delete Customer Purchase Order : <?php echo $customer_purchase_orders[$i]['customer_purchase_order_code']; ?>');" style="color:red;">
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
            
            
