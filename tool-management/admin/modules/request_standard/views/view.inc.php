<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=request_standard&date_start="+date_start+"&date_end="+date_end+"&keyword="+keyword;
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Standard Tool Request  Management</h1>
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
                                    รายการใบร้องขอสั่งซื้อสินค้าทดลอง / Standard Tool Request  List
                                </div>

                                <?php if($license_request_page == "Low" || $license_request_page == "Medium" || $license_request_page == "High"){ ?> 
                                <div class="col-md-4">
                                    <a class="btn btn-success " style="float:right;" href="?app=request_standard&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </div>
                                <?PHP } ?>

                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>วันที่ออกใบร้องขอสั่งซื้อสินค้าทดลอง</label>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start;?>"  class="form-control calendar" readonly/>
                                            </div>
                                            <div class="col-md-1" align="center">
                                                -
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/>
                                            </div>
                                        </div>
                                        <p class="help-block">01-01-2018 - 31-12-2018</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>คำค้น <font color="#F00"><b>*</b></font></label>
                                        <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                                        <p class="help-block">Example : T001.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                                    <a href="index.php?app=request_standard" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                                </div>
                            </div>
                            <br>

                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ<br>No.</th>
                                        <th>วันที่ออกใบร้องขอ<br>STR Date</th>
                                        <th>หมายเลขใบร้องขอ<br>STR No.</th>
                                        <th>ร้องขอโดย<br>Request by</th> 
                                        <th>สถานะอนุมัติ<br>Accept Status</th>
                                        <th>ผู้อนุมัติ<br>Accept by</th>
                                        <th>หมายเหตุ<br>Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($request_standards); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $request_standards[$i]['request_standard_date']; ?></td>
                                        <td><?php echo $request_standards[$i]['request_standard_code']; ?> <?php if($request_standards[$i]['request_standard_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $request_standards[$i]['request_standard_rewrite_no']; ?></font></b> <?PHP } ?> <?php if($request_standards[$i]['request_standard_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?></td>
                                        <td><?php echo $request_standards[$i]['request_name']; ?></td> 
                                        <td><?php echo $request_standards[$i]['request_standard_accept_status']; ?></td>
                                        <td><?php echo $request_standards[$i]['accept_name']; ?></td>
                                        <td><?php echo $request_standards[$i]['request_standard_remark']; ?></td>
                                        
                                        <td>
                                            <a href="?app=request_standard&action=detail&id=<?php echo $request_standards[$i]['request_standard_id'];?>" title="ดูรายละเอียดใบร้องขอ">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>

                                        <?php if($request_standards[$i]['request_standard_accept_status'] == "Waiting"){ ?>
                                            
                                            <?php if($request_standards[$i]['request_standard_cancelled'] == 0){ ?>

                                                <?php if($license_request_page == "Medium" || $license_request_page == "High" ||  ($request_standards[$i]['employee_id'] == $admin_id && $license_request_page == "Low")){ ?> 
                                                <a href="?app=request_standard&action=cancelled&id=<?php echo $request_standards[$i]['request_standard_id'];?>"  title="ยกเลิกใบร้องขอ" onclick="return confirm('You want to cancelled Standard Tool Request  : <?php echo $request_standards[$i]['request_standard_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=request_standard&action=rewrite&id=<?php echo $request_standards[$i]['request_standard_id'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite Standard Tool Request  : <?php echo $request_standards[$i]['request_standard_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=request_standard&action=update&id=<?php echo $request_standards[$i]['request_standard_id'];?>"  title="แก้ไขใบร้องขอ">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a> 
                                                <?PHP } ?>


                                            <?php } else if($request_standards[$i]['count_rewrite'] == 0) { ?>

                                                <?php if($license_request_page == "Medium" || $license_request_page == "High" ||  ($request_standards[$i]['employee_id'] == $admin_id && $license_request_page == "Low")){ ?> 
                                                <a href="?app=request_standard&action=uncancelled&id=<?php echo $request_standards[$i]['request_standard_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled Standard Tool Request  : <?php echo $request_standards[$i]['request_standard_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=request_standard&action=rewrite&id=<?php echo $request_standards[$i]['request_standard_id'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite Standard Tool Request  : <?php echo $request_standards[$i]['request_standard_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>

                                                <?php if($license_request_page == "High" ||  ($request_standards[$i]['employee_id'] == $admin_id && $license_request_page == "Low")){ ?> 
                                                <a href="?app=request_standard&action=delete&id=<?php echo $request_standards[$i]['request_standard_id'];?>"  title="ลบใบร้องขอ" onclick="return confirm('You want to delete Standard Tool Request  : <?php echo $request_standards[$i]['request_standard_code']; ?>');" style="color:red;">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>


                                            <?PHP } else { ?> 

                                                <?php if($license_request_page == "Medium" || $license_request_page == "High" ||  ($request_standards[$i]['employee_id'] == $admin_id && $license_request_page == "Low")){ ?> 
                                                <a href="?app=request_standard&action=uncancelled&id=<?php echo $request_standards[$i]['request_standard_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled Standard Tool Request  : <?php echo $request_standards[$i]['request_standard_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>

                                                <?php if($license_request_page == "Medium" || $license_request_page == "High" ||  ($request_standards[$i]['employee_id'] == $admin_id && $license_request_page == "Low")){ ?> 
                                                <a href="?app=request_standard&action=delete&id=<?php echo $request_standards[$i]['request_standard_id'];?>"  title="ลบใบร้องขอ" onclick="return confirm('You want to delete Standard Tool Request  : <?php echo $request_standards[$i]['request_standard_code']; ?>');" style="color:red;">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>

                                                
                                            <?PHP } ?>
                                                
                                            
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
            
            
