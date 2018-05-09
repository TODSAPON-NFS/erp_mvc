<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Quotation Management</h1>
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
                        รายการใบเสนอราคาสินค้า / Quotation List
                            <a class="btn btn-success " style="float:right;" href="?app=quotation&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ<br>No.</th>
                                        <th>วันที่ออกใบเสนอราคา<br>Quotation Date</th>
                                        <th>หมายเลขใบเสนอราคา<br>Quotation No.</th>
                                        <th>ลูกค้า<br>Customer</th>
                                        <th>ผู้ติดต่อ<br>Contact</th>
                                        <th>ผู้ออกใบเสนอราคา<br>Create by</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($quotations); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $quotations[$i]['quotation_date']; ?></td>
                                        <td>
                                            <?php echo $quotations[$i]['quotation_code']; ?>
                                            <?php if($quotations[$i]['quotation_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $quotations[$i]['quotation_rewrite_no']; ?></font></b> <?PHP } ?> <?php if($quotations[$i]['quotation_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?>
                                        </td>
                                        <td><?php echo $quotations[$i]['customer_name']; ?></td>
                                        <td><?php echo $quotations[$i]['quotation_contact_name']; ?></td>
                                        <td><?php echo $quotations[$i]['employee_name']; ?></td>
                                        
                                        <td>
                                            <a href="?app=quotation&action=detail&id=<?php echo $quotations[$i]['quotation_id'];?>" title="ดูรายละเอียดใบร้องขอ">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>
                                            
                                            <?php if($quotations[$i]['quotation_cancelled'] == 0){ ?>
                                                <a href="?app=quotation&action=cancelled&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="ยกเลิกใบร้องขอ" onclick="return confirm('You want to cancelled purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=quotation&action=rewrite&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=quotation&action=update&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="แก้ไขใบร้องขอ">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a> 
                                            <?php } else if($quotations[$i]['count_rewrite'] == 0) { ?>

                                                <a href="?app=quotation&action=uncancelled&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=quotation&action=rewrite&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=quotation&action=delete&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="ลบใบร้องขอ" onclick="return confirm('You want to delete purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" style="color:red;">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                            <?PHP } else { ?> 
                                                <a href="?app=quotation&action=uncancelled&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=quotation&action=delete&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="ลบใบร้องขอ" onclick="return confirm('You want to delete purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" style="color:red;">
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
            
            
