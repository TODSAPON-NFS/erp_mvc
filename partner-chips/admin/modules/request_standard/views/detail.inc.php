

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Standard Tool Request  Management</h1>
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
               รายละเอียดใบร้องขอสั่งซื้อสินค้าทดลอง / Standard Tool Request  Detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=request_standard&action=approve&id=<?php echo $request_standard_id;?>" >
                    <input type="hidden"  id="request_standard_id" name="request_standard_id" value="<?php echo $request_standard_id; ?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>หมายเลขสั่งซื้อสินค้า / STR Code <font color="#F00"><b>*</b></font> </label>
                                <p class="help-block">
                                    <? echo $request_standard['request_standard_code'];?>
                                    <?php if($request_standard['request_standard_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $request_standard['request_standard_rewrite_no']; ?></font></b> <?PHP } ?> <?php if($request_standard['request_standard_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?>
                                </p>
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ผู้ร้องขอ / Request by  <font color="#F00"><b>*</b></font> </label>
                                <p class="help-block"><? echo $request_standard['user_name'];?> <? echo $request_standard['user_lastname'];?> (<? echo $request_standard['user_position_name'];?>)</p>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>สำหรับลูกค้า / Customer </label>
                                <p class="help-block"><?php echo $request_standard['customer_name_en'];?></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ผู้ขาย / Supplier </label>
                                <p class="help-block"><?php echo $request_standard['supplier_name_th']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>หมายเหคุ / Remark</label>
                                <p class="help-block"><? echo $request_standard['request_standard_remark'];?></p>
                            </div>
                        </div>
                    </div>

                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">ลำดับ <br>(No.)</th>
                                <th style="text-align:center;">รหัสสินค้า <br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า<br>(Product Name)</th>
                                <th style="text-align:center;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;">วันที่ใช้สินค้า<br>(Delivery Min)</th>
                                <th style="text-align:center;">หมายเหตุ<br>(Remark)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($request_standard_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <?php echo $i+1; ?>.
                                </td>
                                <td>
                                    <?php echo $request_standard_lists[$i]['product_code']; ?>
                                </td>
                                <td><?php echo $request_standard_lists[$i]['product_name']; ?></td>
                                <td><?php echo $request_standard_lists[$i]['request_standard_list_qty']; ?></td>
                                <td><?php echo $request_standard_lists[$i]['request_standard_list_delivery']; ?></td>
                                <td><?php echo $request_standard_lists[$i]['request_standard_list_remark']; ?></td>
                               
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- /.row (nested) -->
                    <div class="row">
                    <div class="col-lg-offset-8 col-lg-2" align="right">
                        
                    <?php if(($user[0][24] == "High" || $user[0][25] == "High" ) && $request_standard['request_standard_cancelled'] == 0 ){ ?>
                        
                            <select id="request_standard_accept_status" name="request_standard_accept_status" class="form-control" data-live-search="true" >
                                <option <?php if($request_standard['request_standard_accept_status'] == "Waiting"){?> selected <?php }?> >Waiting</option>
                                <option <?php if($request_standard['request_standard_accept_status'] == "Approve"){?> selected <?php }?> >Approve</option>
                                <option <?php if($request_standard['request_standard_accept_status'] == "Not Approve"){?> selected <?php }?> >Not Approve</option>
                            </select>
                        
                    <?php } ?>
                        </div>
                        <div class="col-lg-2" align="right">
                            <a href="index.php?app=request_standard" class="btn btn-default">Back</a>

                            <?php if(($user[0][24] == "High" || $user[0][25] == "High" ) && $request_standard['request_standard_cancelled'] == 0 ){ ?>
                            <button type="submit" class="btn btn-success">Save</button>
                            <?php } ?>
                            
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>