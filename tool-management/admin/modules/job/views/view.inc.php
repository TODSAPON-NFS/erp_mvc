<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Job Management</h1>
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
                            <div class="row">
                                <div class="col-md-8">
                                    รายการงานจัดการต้นทุน / Job List
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-success " style="float:right;" href="?app=job&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ<br>No.</th>
                                        <th>หมายเลขงาน<br>Job Code</th>
                                        <th>ชื่องาน<br>Job No.</th>
                                        <th>ลูกค้า<br>Customer</th>
                                        <th>ยอดการผลิต<br>Production</th>
                                        <th>หมายเหตุ<br>Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($jobs); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $jobs[$i]['job_code']; ?></td>
                                        <td>
                                            <?php echo $jobs[$i]['job_name']; ?>
                                        </td>
                                        <td><?php echo $jobs[$i]['customer_name']; ?></td>
                                        <td><?php echo $jobs[$i]['job_production']; ?></td>
                                        <td><?php echo $jobs[$i]['job_remark']; ?></td>
                                        
                                        <td>
                                            <a href="?app=job&action=detail&id=<?php echo $jobs[$i]['job_id'];?>" title="ดูรายละเอียดใบร้องขอ">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>
                                            
                                            <?php if($jobs[$i]['job_active'] == 1){ ?>
                                                <a href="?app=job&action=active&id=<?php echo $jobs[$i]['job_id'];?>"  title="ยกเลิกใบร้องขอ" onclick="return confirm('You want to active purchase request : <?php echo $jobs[$i]['job_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=job&action=update&id=<?php echo $jobs[$i]['job_id'];?>"  title="แก้ไขใบร้องขอ">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a> 
                                            <?php } else { ?> 
                                                <a href="?app=job&action=inactive&id=<?php echo $jobs[$i]['job_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to inactive purchase request : <?php echo $jobs[$i]['job_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=job&action=delete&id=<?php echo $jobs[$i]['job_id'];?>"  title="ลบใบร้องขอ" onclick="return confirm('You want to delete purchase request : <?php echo $jobs[$i]['job_code']; ?>');" style="color:red;">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                            <?PHP } ?>

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
            
            
