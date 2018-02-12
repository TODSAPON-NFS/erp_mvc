<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Employee Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=employee" class="btn btn-primary  btn-menu">Employee</a>
        <a href="?app=employee_license" class="btn btn-primary active btn-menu">License</a>
    </div>
    <!-- /.col-lg-12 -->
</div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Employee License List
                            <a class="btn btn-success " style="float:right;" href="?app=employee_license&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>License Name</th>
                                        <th>Sale Page</th>
                                        <th>Purchase Page</th>
                                        <th>Manager Page</th>
                                        <th>Inventery Page</th>
                                        <th>Account Page</th>
                                        <th>Report Page</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($license); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $license[$i]['license_name']; ?></td>
                                        <td><?php echo $license[$i]['license_sale_page']; ?></td>
                                        <td><?php echo $license[$i]['license_purchase_page']; ?></td>
                                        <td><?php echo $license[$i]['license_manager_page']; ?></td>
                                        <td><?php echo $license[$i]['license_inventery_page']; ?></td>
                                        <td><?php echo $license[$i]['license_account_page']; ?></td>
                                        <td><?php echo $license[$i]['license_report_page']; ?></td>
                                        <td>
                                            <a href="?app=employee_license&action=update&id=<?php echo $license[$i]['license_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=employee_license&action=delete&id=<?php echo $license[$i]['license_id'];?>" onclick="return confirm('You want to delete Employee License : <?php echo $license[$i]['license_name']; ?>');" style="color:red;">
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
            
            
