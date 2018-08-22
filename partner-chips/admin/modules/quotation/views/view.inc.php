
<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=quotation&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword;
    }
</script>

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
                            <div class="row">
                                <div class="col-md-8">
                                    รายการใบเสนอราคาสินค้า / Quotation List
                                </div>
                                <?PHP if($license_sale_page == "Low" || $license_sale_page == "Medium" || $license_sale_page == "High"){ ?>
                                <div class="col-md-4">
                                    <a class="btn btn-success " style="float:right;" href="?app=quotation&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </div>
                                <?PHP } ?>
                            </div>
                        </div>
                        
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>วันที่ออกใบเสนอราคา</label>
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
                                        <label>ลูกค้า </label>
                                        <select id="customer_id" name="customer_id" class="form-control select" data-live-search="true">
                                            <option value="">ทั้งหมด</option>
                                            <?php 
                                            for($i =  0 ; $i < count($customers) ; $i++){
                                            ?>
                                            <option <?php if($customers[$i]['customer_id'] == $customer_id){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> (<?php echo $customers[$i]['customer_name_th'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
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
                                    <a href="index.php?app=quotation" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                                </div>
                            </div>
                            <br>

                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-view">
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


                                                <?PHP if(($license_sale_page == "Low" && $admin_id == $employee_id) || $license_sale_page == "Medium" || $license_sale_page == "High"){ ?>
                                                <a href="?app=quotation&action=cancelled&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="ยกเลิกใบร้องขอ" onclick="return confirm('You want to cancelled purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=quotation&action=rewrite&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=quotation&action=update&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="แก้ไขใบร้องขอ">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a> 
                                                <?PHP } ?>


                                            <?php } else if($quotations[$i]['count_rewrite'] == 0) { ?>
                                                
                                                
                                                <?PHP if(($license_sale_page == "Low" && $admin_id == $employee_id) || $license_sale_page == "Medium" || $license_sale_page == "High"){ ?>
                                                <a href="?app=quotation&action=uncancelled&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=quotation&action=rewrite&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>

                                                
                                                <?PHP if(($license_sale_page == "Low" && $admin_id == $employee_id) || $license_sale_page == "High"){ ?>
                                                <a href="?app=quotation&action=delete&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="ลบใบร้องขอ" onclick="return confirm('You want to delete purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" style="color:red;">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>


                                            <?PHP } else { ?> 

                                                <?PHP if(($license_sale_page == "Low" && $admin_id == $employee_id) || $license_sale_page == "Medium" || $license_sale_page == "High"){ ?>
                                                <a href="?app=quotation&action=uncancelled&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>

                                                <?PHP if(($license_sale_page == "Low" && $admin_id == $employee_id) || $license_sale_page == "High"){ ?>
                                                <a href="?app=quotation&action=delete&id=<?php echo $quotations[$i]['quotation_id'];?>"  title="ลบใบร้องขอ" onclick="return confirm('You want to delete purchase request : <?php echo $quotations[$i]['quotation_code']; ?>');" style="color:red;">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>

                                                
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
            
            
