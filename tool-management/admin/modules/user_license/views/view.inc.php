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
                                    <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                        <a class="btn btn-success " style="float:right;" href="?app=employee_license&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
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
                                        <th style="text-align:center;">ชื่อสิทธิ์</th>
                                        <th style="text-align:center;">1</th>
                                        <th style="text-align:center;">2</th>
                                        <th style="text-align:center;">3</th>
                                        <th style="text-align:center;">4</th>
                                        <th style="text-align:center;">5</th>
                                        <th style="text-align:center;">6</th>
                                        <th style="text-align:center;">7</th>
                                        <th style="text-align:center;">8</th>
                                        <th style="text-align:center;">9</th>
                                        <th style="text-align:center;">10</th>
                                        <th style="text-align:center;">11</th>
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
                                        <td><?php echo $license[$i]['license_admin_page']; ?></td>
                                        <td><?php echo $license[$i]['license_sale_employee_page']; ?></td>
                                        <td><?php echo $license[$i]['license_request_page']; ?></td>
                                        <td><?php echo $license[$i]['license_delivery_note_page']; ?></td>
                                        <td><?php echo $license[$i]['license_regrind_page']; ?></td>
                                        <td><?php echo $license[$i]['license_purchase_page']; ?></td>
                                        <td><?php echo $license[$i]['license_sale_page']; ?></td>
                                        <td><?php echo $license[$i]['license_inventery_page']; ?></td>
                                        <td><?php echo $license[$i]['license_account_page']; ?></td>
                                        <td><?php echo $license[$i]['license_report_page']; ?></td>
                                        <td><?php echo $license[$i]['license_manager_page']; ?></td>
                                        <td>
                                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                            <a href="?app=employee_license&action=update&id=<?php echo $license[$i]['license_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP }?>
                                        <?php if($license_admin_page == "High"){ ?> 
                                            <a href="?app=employee_license&action=delete&id=<?php echo $license[$i]['license_id'];?>" onclick="return confirm('You want to delete Employee License : <?php echo $license[$i]['license_name']; ?>');" style="color:red;">
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
                            <div class="row">
                                <div class="col-md-12">
                                    <font color="red"><b>หมายเหตุ * </b></font><br> 
                                    1.ระบบพื้นฐาน,&nbsp;&nbsp;
                                    2.ระบบพนักงานขาย,&nbsp;&nbsp;
                                    3.ระบบสั่งสินค้าทดลอง,&nbsp;&nbsp;
                                    4.ระบบใบยืม,&nbsp;&nbsp;
                                    5.ระบบรีกายด์สินค้า,&nbsp;&nbsp;
                                    6.ระบบจัดซื้อ,&nbsp;&nbsp;
                                    7.ขายสินค้า,&nbsp;&nbsp;
                                    8.ระบบคลังสินค้า,&nbsp;&nbsp;
                                    9.ระบบบัญชี,&nbsp;&nbsp;
                                    10.ระบบรายงาน,&nbsp;&nbsp;
                                    11.ระบบผู้จัดการ,&nbsp;&nbsp;  

                                </div>
                            </div>
                           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            
