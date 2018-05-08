            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?PHP echo $stock_group['stock_group_name'];?> </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        Search Product.
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Product Type</th>
                                        <th>Product Status</th>
                                        <th>From Stock</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($stock_list); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $stock_list[$i]['product_code']; ?></td>
                                        <td><?php echo $stock_list[$i]['product_name']; ?></td>
                                        <td><?php echo $stock_list[$i]['product_type']; ?></td>
                                        <td><?php echo $stock_list[$i]['product_status']; ?></td>
                                        <td><?php echo $stock_list[$i]['stock_group_name']; ?></td>
                                        <td><?php echo $stock_list[$i]['stock_report_qty']; ?></td>
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
            
            
