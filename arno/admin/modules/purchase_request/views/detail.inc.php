

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Request Management</h1>
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
                Purchase Request Detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=purchase_request&action=approve&id=<?php echo $purchase_request_id;?>" >
                    <input type="hidden"  id="purchase_request_id" name="purchase_request_id" value="<?php echo $purchase_request_id; ?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Purchase Request Code <font color="#F00"><b>*</b></font></label>
                                <p class="help-block"><? echo $purchase_request['purchase_request_code'];?></p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Purchase Request Type <font color="#F00"><b>*</b></font></label>
                                <p class="help-block"><? echo $purchase_request['purchase_request_type'];?></p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Request by  <font color="#F00"><b>*</b></font> </label>
                                <p class="help-block"><? echo $purchase_request['user_name'];?> <? echo $purchase_request['user_lastname'];?> (<? echo $purchase_request['user_position_name'];?>)</p>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Urgent Time (Day)<font color="#F00"><b>*</b></font></label>
                                <p class="help-block"><? echo $purchase_request['urgent_time'];?></p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Purchase Request Urgent <font color="#F00"><b>*</b></font></label>
                                <p class="help-block"><? echo $purchase_request['urgent_status'];?></p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Customer </label>
                                <p class="help-block"><?php if($purchase_request['customer_name_en'] != ''){ echo $purchase_request['customer_name_en'];?> (<?php echo $purchase_request['customer_name_th'];?>)<?php } else {?> <?php }?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Remark</label>
                                <p class="help-block"><? echo $purchase_request['purchase_request_remark'];?></p>
                            </div>
                        </div>
                    </div>

                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Delivery Min</th>
                                <th>Delivery Max</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($purchase_request_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <?php echo $purchase_request_lists[$i]['product_code']; ?>
                                </td>
                                <td><?php echo $purchase_request_lists[$i]['product_name']; ?></td>
                                <td><?php echo $purchase_request_lists[$i]['purchase_request_list_qty']; ?></td>
                                <td><?php echo $purchase_request_lists[$i]['purchase_request_list_delivery_min']; ?></td>
                                <td><?php echo $purchase_request_lists[$i]['purchase_request_list_delivery_max']; ?></td>
                                <td><?php echo $purchase_request_lists[$i]['purchase_request_list_remark']; ?></td>
                               
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- /.row (nested) -->
                    <div class="row">
                    <?php if($user[0][24] == "High" || $user[0][25] == "High" ){ ?>
                        <div class="col-lg-offset-9 col-lg-2" align="right">
                            <select id="purchase_request_accept_status" name="purchase_request_accept_status" class="form-control" data-live-search="true" >
                                <option <?php if($purchase_request['purchase_request_accept_status'] == "Waiting"){?> selected <?php }?> >Waiting</option>
                                <option <?php if($purchase_request['purchase_request_accept_status'] == "Approve"){?> selected <?php }?> >Approve</option>
                                <option <?php if($purchase_request['purchase_request_accept_status'] == "Not Approve"){?> selected <?php }?> >Not Approve</option>
                            </select>
                        </div>
                        <div class="col-lg-1" align="right">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    <?php } ?>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>