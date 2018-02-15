

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Customer Purchase Order Management</h1>
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
            Customer Purchase Order Detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=customer_purchase_order&action=approve&id=<?php echo $customer_purchase_order_id;?>" >
                    <input type="hidden"  id="customer_purchase_order_id" name="customer_purchase_order_id" value="<?php echo $customer_purchase_order_id; ?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Customer Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $customer_purchase_order['customer_code']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>Customer  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $customer_purchase_order['customer_name_en'] ?> (<?php echo $customer_purchase_order['customer_name_th'] ?>)</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $customer_purchase_order['customer_address_1']?><br><?php echo $customer_purchase_order['customer_address_2']?><br><?php echo $customer_purchase_order['customer_address_3']?><br></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Tax <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $customer_purchase_order['customer_tax']?></p>
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
                                        <p class="help-block"><?php echo $customer_purchase_order['customer_purchase_order_code']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Date</label>
                                        <p class="help-block"><?php echo $customer_purchase_order['customer_purchase_order_date']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Credit term (Day)</label>
                                        <p class="help-block"><?php echo $customer_purchase_order['customer_purchase_order_credit_term']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Employee  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $customer_purchase_order['user_name'] ?> <?php echo $customer_purchase_order['user_lastname'] ?>(<?php echo $customer_purchase_order['user_position_name'] ?>)</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Delivery by</label>
                                        <p class="help-block"><?php echo $customer_purchase_order['customer_purchase_order_delivery_by']?></p>
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
                                <th style="text-align:center;width:32px;">Item</th>
                                <th style="text-align:center;">Product Code</th>
                                <th style="text-align:center;">Product Name / Description</th>
                                <th style="text-align:center;">Qty</th>
                                <th style="text-align:center;">@</th>
                                <th style="text-align:center;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $sub_total = 0;
                            for($i=0; $i < count($customer_purchase_order_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                <?php echo ($i+1) ?>.
                                </td>
                                <td>
                                <?php echo $customer_purchase_order_lists[$i]['product_code'] ?>

                                </td>
                                <td>
                                    <span>Stadard Name :</span> <?php echo $customer_purchase_order_lists[$i]['product_name'] ?><br>
                                    <span>Customer Name :</span> <?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_product_name'] ?><br>
                                    <span>Description :</span> <?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_product_detail'] ?><br>
                                    <span>Remark :</span> <?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_remark']; ?><br>
                                    <span>Hold Stock :</span> <font color="red"><b><?php echo $customer_purchase_order_lists[$i]['customer_purchase_order_list_hold']; ?></b></font><br>
                                </td>
                                <td align="right"><?php echo number_format($customer_purchase_order_lists[$i]['customer_purchase_order_list_qty'],0); ?></td>
                                <td align="right"><?php echo number_format($customer_purchase_order_lists[$i]['customer_purchase_order_list_price'],2); ?></td>
                                <td align="right"><?php echo number_format($customer_purchase_order_lists[$i]['customer_purchase_order_list_price_sum'],2); ?></td>


                                
                            </tr>
                            <?
                            $sub_total += $customer_purchase_order_lists[$i]['customer_purchase_order_list_price_sum'];
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
                                <td align="right">
                                    Sub Total <br>
                                    Vat <br>
                                    Net Total <br>
                                </td>
                                <td align="right">
                                    <?php echo number_format($sub_total,2);?> <br>
                                    <?php echo number_format($sub_total * 0.07,2);?> <br>
                                    <?php echo number_format($sub_total+($sub_total * 0.07),2);?> <br>
                                </td>
                               
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>