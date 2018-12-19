

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Request Test Management</h1>
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
                Request Test Detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=request_test&action=approve&id=<?php echo $request_test_id;?>" >
                    <input type="hidden"  id="request_test_id" name="request_test_id" value="<?php echo $request_test_id; ?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Request Test Type <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block">
                                        <?php echo $request_test['request_test_type']?> 
                                        <?php if($request_test['request_test_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $request_test['request_test_rewrite_no']; ?></font></b> <?PHP } ?> <?php if($request_test['request_test_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $request_test['supplier_code']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>Supplier  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $request_test['supplier_name_en'] ?> </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $request_test['supplier_address_1']?><br><?php echo $request_test['supplier_address_2']?><br><?php echo $request_test['supplier_address_3']?><br></p>
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
                                        <label>Request Test Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $request_test['request_test_code']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Request Test Date</label>
                                        <p class="help-block"><?php echo $request_test['request_test_date']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Credit term (Day)</label>
                                        <p class="help-block"><?php echo $request_test['request_test_credit_term']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Employee  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $request_test['user_name'] ?> <?php echo $request_test['user_lastname'] ?>(<?php echo $request_test['user_position_name'] ?>)</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Delivery by</label>
                                        <p class="help-block"><?php echo $request_test['request_test_delivery_by']?></p>
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
								<!--
                                <th style="text-align:center;">Delivery Min</th>
                                <th style="text-align:center;">Delivery Max</th>
                                <th style="text-align:center;">Remark</th>
								-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
							$sub_total = 0;
                            for($i=0; $i < count($request_test_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
								<td>
                                    <?php echo $i + 1; ?>.
                                </td>
                                <td>
                                    <?php echo $request_test_lists[$i]['product_code']?>
                                </td>
                                <td>
								Product name : <?php echo $request_test_lists[$i]['product_name']?> <br>
								Delivery : <?php echo $request_test_lists[$i]['request_test_list_delivery_min']?> - <?php echo $request_test_lists[$i]['request_test_list_delivery_max']?> <br>
								Remark : <?php echo $request_test_lists[$i]['request_test_list_remark']?>
								</td>
                                <td align="right"><?php echo number_format($request_test_lists[$i]['request_test_list_qty'],0)?></td>
                                <td align="right"><?php echo number_format($request_test_lists[$i]['request_test_list_price'],2)?></td>
                                <td align="right"><?php echo number_format($request_test_lists[$i]['request_test_list_price_sum'],2)?></td>
								<!--
                                <td align="center"><?php echo $request_test_lists[$i]['request_test_list_delivery_min']?></td>
                                <td align="center"><?php echo $request_test_lists[$i]['request_test_list_delivery_max']?></td>
                                <td><?php echo $request_test_lists[$i]['request_test_list_remark']?></td>
								-->
                            </tr>
                            <?
							$sub_total += $request_test_lists[$i]['request_test_list_price_sum'];
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
							<td>
                                    
                                </td>
                                <td>
                                    
                                </td>
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
								<!--
                                <td></td>
                                <td></td>
                                <td></td>
                                -->
                            </tr>
                        </tfoot>
                    </table>


                    <!-- /.row (nested) -->
                    <div class="row">
                    <?php if(($user[0][24] == "High" || $user[0][25] == "High" ) && $request_test['request_test_status'] == 'Request'){ ?>
                        <div class="col-lg-offset-9 col-lg-2" align="right">
                            <select id="request_test_accept_status" name="request_test_accept_status" class="form-control" data-live-search="true" >
                                <option <?php if($request_test['request_test_accept_status'] == "Waitting"){?> selected <?php }?> >Waitting</option>
                                <option <?php if($request_test['request_test_accept_status'] == "Approve"){?> selected <?php }?> >Approve</option>
                                <option <?php if($request_test['request_test_accept_status'] == "Not Approve"){?> selected <?php }?> >Not Approve</option>
                            </select>
                        </div>
                        <div class="col-lg-1" align="right">
                            <a href="index.php?app=request_test" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    <?php } else { ?>
                        <div class="col-lg-offset-9 col-lg-2" align="right">
                            
                        </div>
                        <div class="col-lg-1" align="right">
                            <a href="index.php?app=request_test" class="btn btn-default">Back</a>
                        </div>
                    <?PHP } ?>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>