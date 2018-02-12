            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Customer Management</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Customer List
                            <a class="btn btn-success " style="float:right;" href="?app=customer&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Code</th>
                                        <th>Name thai</th>
                                        <th>Name english</th>
                                        <th>TAX ID</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($customer); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $customer[$i]['customer_code']; ?></td>
                                        <td><?php echo $customer[$i]['customer_name_th']; ?></td>
                                        <td><?php echo $customer[$i]['customer_name_en']; ?></td>
                                        <td><?php echo $customer[$i]['customer_tax']; ?></td>
                                        <td class="center"><?php echo $customer[$i]['customer_tel']; ?></td>
                                        <td class="center"><?php echo $customer[$i]['customer_email']; ?></td>
                                        <td>
                                            <a title="View Detail" href="?app=customer&action=detail&id=<?php echo $customer[$i]['customer_id'];?>">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a> 
                                            <a title="calendar" href="?app=customer_holiday&action=view&id=<?php echo $customer[$i]['customer_id'];?>">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </a>
                                            <a title="Bank account" href="?app=customer_account&action=view&id=<?php echo $customer[$i]['customer_id'];?>">
                                                <i class="fa fa-university" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Person contact" href="?app=customer_contact&action=view&id=<?php echo $customer[$i]['customer_id'];?>">
                                                <i class="fa fa-users" aria-hidden="true"></i>
                                            </a>
                                            <a title="Logistic type" href="?app=customer_logistic&action=view&id=<?php echo $customer[$i]['customer_id'];?>">
                                                <i class="fa fa-truck" aria-hidden="true"></i>
                                            </a>
                                            <a title="Update data" href="?app=customer&action=update&id=<?php echo $customer[$i]['customer_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Delete data" href="?app=customer&action=delete&id=<?php echo $customer[$i]['customer_id'];?>" onclick="return confirm('You want to delete customer : <?php echo $customer[$i]['customer_name']; ?>');" style="color:red;">
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
            
            
