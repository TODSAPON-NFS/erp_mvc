

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Order Management</h1>
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
                Purchase Order Detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=purchase_order&action=approve&id=<?php echo $purchase_order_id;?>" >
                    <input type="hidden"  id="purchase_order_id" name="purchase_order_id" value="<?php echo $purchase_order_id; ?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Type <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_type']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $purchase_order['supplier_code']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>Supplier  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $purchase_order['supplier_name_en'] ?> (<?php echo $purchase_order['supplier_name_th'] ?>)</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $purchase_order['supplier_address_1']?><br><?php echo $purchase_order['supplier_address_2']?><br><?php echo $purchase_order['supplier_address_3']?><br></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_code']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Date</label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_date']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Credit term (Day)</label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_credit_term']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Employee  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $purchase_order['user_name'] ?> <?php echo $purchase_order['user_lastname'] ?>(<?php echo $purchase_order['user_position_name'] ?>)</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Delivery by</label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_delivery_by']?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    <div>
                    Our reference :
                    </div>
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>@</th>
                                <th>Amount</th>
                                <th>Delivery Min</th>
                                <th>Delivery Max</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($purchase_order_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <?php echo $purchase_order_lists[$i]['product_code']?>
                                </td>
                                <td><?php echo $purchase_order_lists[$i]['product_name']?></td>
                                <td><?php echo $purchase_order_lists[$i]['purchase_order_list_qty']?></td>
                                <td><?php echo $purchase_order_lists[$i]['purchase_order_list_price']?></td>
                                <td><?php echo $purchase_order_lists[$i]['purchase_order_list_price_sum']?></td>
                                <td><?php echo $purchase_order_lists[$i]['purchase_order_list_delivery_min']?></td>
                                <td><?php echo $purchase_order_lists[$i]['purchase_order_list_delivery_max']?></td>
                                <td><?php echo $purchase_order_lists[$i]['purchase_order_list_remark']?></td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td>
                                    
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                
                            </tr>
                        </tfoot>
                    </table>


                    <!-- /.row (nested) -->
                    <div class="row">
                    <?php if($user[0][24] == "High" || $user[0][25] == "High" ){ ?>
                        <div class="col-lg-offset-9 col-lg-2" align="right">
                            <select id="purchase_order_accept_status" name="purchase_order_accept_status" class="form-control" data-live-search="true" >
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Waiting"){?> selected <?php }?> >Waiting</option>
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Approve"){?> selected <?php }?> >Approve</option>
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Not Approve"){?> selected <?php }?> >Not Approve</option>
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