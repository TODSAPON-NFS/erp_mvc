            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Supplier Management</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Supplier List
                            <a class="btn btn-success " style="float:right;" href="?app=supplier&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
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
                                    for($i=0; $i < count($Supplier); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $Supplier[$i]['supplier_code']; ?></td>
                                        <td><?php echo $Supplier[$i]['supplier_name_th']; ?></td>
                                        <td><?php echo $Supplier[$i]['supplier_name_en']; ?></td>
                                        <td><?php echo $Supplier[$i]['supplier_tax']; ?></td>
                                        <td class="center"><?php echo $Supplier[$i]['supplier_tel']; ?></td>
                                        <td class="center"><?php echo $Supplier[$i]['supplier_email']; ?></td>
                                        <td>
                                            <a title="View Detail" href="?app=supplier&action=detail&id=<?php echo $Supplier[$i]['supplier_id'];?>">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Bill Calendar" href="?app=supplier_calendar&action=view&id=<?php echo $Supplier[$i]['supplier_id'];?>">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </a>
                                            <a title="Sale Products" href="?app=stock&action=product&supplier_id=<?php echo $Supplier[$i]['supplier_id'];?>">
                                                <i class="fa fa-archive" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Bank account" href="?app=supplier_account&action=view&id=<?php echo $Supplier[$i]['supplier_id'];?>">
                                                <i class="fa fa-university" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Person contact" href="?app=supplier_contact&action=view&id=<?php echo $Supplier[$i]['supplier_id'];?>">
                                                <i class="fa fa-address-card" aria-hidden="true"></i>
                                            </a>
                                            <a title="Logistic type" href="?app=supplier_logistic&action=view&id=<?php echo $Supplier[$i]['supplier_id'];?>">
                                                <i class="fa fa-truck" aria-hidden="true"></i>
                                            </a>
                                            <a title="Update data" href="?app=supplier&action=update&id=<?php echo $Supplier[$i]['supplier_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Delete data" href="?app=supplier&action=delete&id=<?php echo $Supplier[$i]['supplier_id'];?>" onclick="return confirm('You want to delete Supplier : <?php echo $Supplier[$i]['supplier_name']; ?>');" style="color:red;">
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
            
            
