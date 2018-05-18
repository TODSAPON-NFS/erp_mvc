<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Employee Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=employee" class="btn btn-primary  btn-menu">พนักงาน / Employee</a>
        <a href="?app=employee_license" class="btn btn-primary active btn-menu">สิทธิ์การใช้งาน / License</a>
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
                                    รายการสิทธิ์การใช้งาน / License List
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-success " style="float:right;" href="?app=employee_license&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;">ลำดับ <br>No.</th>
                                        <th style="text-align:center;">ชื่อสิทธิ์การใช้งาน <br>License Name</th>
                                        <th style="text-align:center;">ระบบผู้ขาย <br>Sale Page</th>
                                        <th style="text-align:center;">ระบบจัดซื้อ <br>Purchase Page</th>
                                        <th style="text-align:center;">ระบบผู้จัดการ <br>Manager Page</th>
                                        <th style="text-align:center;">ระบบคลังสินค้า <br>Inventery Page</th>
                                        <th style="text-align:center;">ระบบบัญชี <br>Account Page</th>
                                        <th style="text-align:center;">ระบบรายงาน <br>Report Page</th>
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
            
            
