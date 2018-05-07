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
                        Stock List.
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form action="?app=stock_list&action=view&id=<?PHP echo $stock_group_id?>" method="post">

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
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Product Type</th>
                                        <th>Product Status</th>
                                        <th>Old</th>
                                        <th>In</th>
                                        <th>Out</th>
                                        <th>Amount</th>
                                        <th>Minimum</th>
                                        <th>Safety</th>
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
                                        <td><?php echo $stock_list[$i]['stock_old']; ?></td>
                                        <td><?php echo $stock_list[$i]['stock_in']; ?> (<?php echo $stock_list[$i]['stock_borrow_in']; ?>)</td>
                                        <td><?php echo $stock_list[$i]['stock_out']; ?> (<?php echo $stock_list[$i]['stock_borrow_out']; ?>)</td>
                                        <td><?php echo ($stock_list[$i]['stock_in'] - $stock_list[$i]['stock_out'] ) + $stock_list[$i]['stock_old']; ?></td>
                                        <td><a href="?app=product&action=update&id=<?php echo $stock_list[$i]['product_id']; ?>#tb-product-customer"><?php echo $stock_list[$i]['stock_minimum']; ?></a></td>
                                        <td><a href="?app=product&action=update&id=<?php echo $stock_list[$i]['product_id']; ?>#tb-product-customer"><?php echo $stock_list[$i]['stock_safety']; ?></a></td>
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
            
            
