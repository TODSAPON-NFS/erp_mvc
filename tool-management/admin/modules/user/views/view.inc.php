<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Employee Management</h1>
    </div>
    <div class="col-lg-6" align="right">
    
        <a href="?app=employee" class="btn btn-primary active btn-menu">พนักงาน / Employee</a>
        <a href="?app=employee_license" class="btn btn-primary  btn-menu">สิทธิ์การใช้งาน / License</a>
    
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
                                    รายชื่อพนักงาน / Employee List
                                </div>
                                <div class="col-md-4">
                                    <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                        <a class="btn btn-success " style="float:right;" href="?app=employee&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                    <?PHP } ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;">ลำดับ <br>No.</th>
                                        <th style="text-align:center;">รหัส <br>ID</th>
                                        <th style="text-align:center;">ชื่อ <br>Name</th>
                                        <th style="text-align:center;">ตำแหน่ง <br>Position</th>
                                        <th style="text-align:center;">โทรศัพท์ <br>Mobile</th>
                                        <th style="text-align:center;">อีเมล์ <br>Email</th>
                                        <th style="text-align:center;">สถานะ <br>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($user); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $user[$i]['user_code']; ?></td>
                                        <td><?php echo $user[$i]['name']; ?></td>
                                        <td><?php echo $user[$i]['user_position_name']; ?></td>
                                        <td class="center"><?php echo $user[$i]['user_mobile']; ?></td>
                                        <td class="center"><?php echo $user[$i]['user_email']; ?></td>
                                        <td class="center"><?php echo $user[$i]['user_status_name']; ?></td>
                                        <td>
                                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                            <a href="?app=employee&action=update&id=<?php echo $user[$i]['user_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP }?>
                                        <?php if($license_admin_page == "High"){ ?> 
                                            <a href="?app=employee&action=delete&id=<?php echo $user[$i]['user_id'];?>" onclick="return confirm('You want to delete employee : <?php echo $user[$i]['name']; ?>');" style="color:red;">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        <?PHP }?>
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
            
            
