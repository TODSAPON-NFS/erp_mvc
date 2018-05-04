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
                                            <?php if($quotations[$i]['quotation_rewrite_id'] != 0){ ?>
                                                <span><font color="#F00">(Rewrite)</font></span>
                                            <?PHP } ?>
                                            <?php if($quotations[$i]['quotation_cancelled'] != 0){ ?>
                                                <span><font color="#F00">(Cancelled)</font></span>
                                            <?PHP } ?>
                                        </td>
                                        <td><?php echo $quotations[$i]['customer_name']; ?></td>
                                        <td><?php echo $quotations[$i]['quotation_contact_name']; ?></td>
                                        <td><?php echo $quotations[$i]['employee_name']; ?></td>
                                        
                                        <td>
                                            <a href="?app=quotation&action=detail&id=<?php echo $quotations[$i]['quotation_id'];?>" titel="ดูรายละเอียดใบเสนอราคา">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>
                                            
                                        <?php if($quotations[$i]['quotation_cancelled'] == 0){ ?>
                                            <a href="?app=quotation&action=update&id=<?php echo $quotations[$i]['quotation_id'];?>" titel="แก้ไขใบเสนอราคา">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a>
                                            <a href="?app=quotation&action=cancelled&id=<?php echo $quotations[$i]['quotation_id'];?>" titel="ยกเลิกใบเสนอราคา" onclick="return confirm('You want to cancel quotation : <?php echo $quotations[$i]['quotation_code']; ?>');"  style="color:#F00;">
                                                <i class="fa fa-ban" aria-hidden="true"></i>
                                            </a>
                                        <?php } else { ?>
                                            <a href="?app=quotation&action=uncancelled&id=<?php echo $quotations[$i]['quotation_id'];?>" titel="เรียกคืนใบเสนอราคา" onclick="return confirm('You want to uncancelled quotation : <?php echo $quotations[$i]['quotation_code']; ?>');" >
                                                <i class="fa fa-undo" aria-hidden="true"></i>
                                            </a>
                                            <a href="?app=quotation&action=delete&id=<?php echo $quotations[$i]['quotation_id'];?>" titel="ลบใบเสนอราคา" onclick="return confirm('You want to delete quotation : <?php echo $quotations[$i]['quotation_code']; ?>');" style="color:red;">
                                                <font color="#F00" ><i class="fa fa-times" aria-hidden="true"></i></font>
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
            
            
