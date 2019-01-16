
<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=invoice_customer&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword;
    }
</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Invoice Customer Management</h1>
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
            ออกใบกำกับภาษีตามลูกค้า /  Invoice Customer to do
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div style="font-size:18px;padding: 8px 0px;">แยกตามลูกค้า</div>
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th width="64px" >No.</th>
                                    <th>Customer</th>
                                    <th width="180px" >Open Invoice Customer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=0; $i < count($customer_orders); $i++){
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $customer_orders[$i]['customer_name_en']; ?></td>
                                    <td>
                                        <a href="?app=invoice_customer&action=insert&customer_id=<?php echo $customer_orders[$i]['customer_id'];?>">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                        </a>

                                    </td>

                                </tr>
                                <?
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div style="font-size:18px;padding: 8px 0px;">แยกตามใบสั่งซื้อ</div>
                        <table width="100%" class="table table-scroll table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th width="64px" >No.</th>
                                    <th>Customer Purchase Order</th>
                                    <th width="180px" >Open Invoice Customer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=0; $i < count($customer_purchase_orders); $i++){
                                    $invoice_customer_list_has = $invoice_customer_model->getCustomerPurchaseOrderStock($customer_purchase_orders[$i]['customer_id'],'','',$customer_purchase_orders[$i]['customer_purchase_order_id']);
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $customer_purchase_orders[$i]['customer_purchase_order_code']; ?> (<?php echo $customer_purchase_orders[$i]['customer_name_en'];  ?>) 
                                    </td>
                                    <td>
                                        <?PHP if(count($invoice_customer_list_has) > 0){ ?>
                                        <a href="?app=invoice_customer&action=insert&customer_id=<?php echo $customer_purchase_orders[$i]['customer_id'];?>&open-type=ready&customer_purchase_order_id=<?php echo $customer_purchase_orders[$i]['customer_purchase_order_id'];?>" style="color:green;">
                                            <i class="fa fa-check-square" aria-hidden="true"></i>
                                        </a>
                                        <?PHP } ?>
                                        <a href="?app=invoice_customer&action=insert&customer_id=<?php echo $customer_purchase_orders[$i]['customer_id'];?>&customer_purchase_order_id=<?php echo $customer_purchase_orders[$i]['customer_purchase_order_id'];?>">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                        </a>

                                    </td>

                                </tr>
                                <?
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
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
                        รายการใบกำกับภาษี / Invoice Customer List
                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-success " style="float:right;" href="?app=invoice_customer&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>วันที่ออกใบกำกับภาษี</label>
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
                            <label>ผู้ซื้อ </label>
                            <select id="customer_id" name="customer_id" class="form-control select"  data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($customers) ; $i++){
                                ?>
                                <option <?php if($customers[$i]['customer_id'] == $customer_id){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?></option>
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
                        <a href="index.php?app=invoice_customer" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th width="24"   class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" > No.</th>
                            <th width="100"class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="วันที่" >Invoice Date</th>
                            <th width="100"class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="เลขที่ใบกำกับภาษี" > Invoice Code.</th>
                            <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลูกค้า" >  Customer</th>
                            <th width="150" class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้ออกเอกสาร" > Create by</th>
                            <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="จำนวนเงิน" >amount</th>
                            <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ภาษีขาย" >Sales Tax</th>
                            <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="จำนวนเงินสุทธิ" >Net amount</th>
							
                            <th width="64"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $invoice_customer_total_price =0;
                        $invoice_customer_vat_price =0;
                        $invoice_customer_net_price =0;
                        for($i=0; $i < count($invoice_customers); $i++){
                            $invoice_customer_total_price +=$invoice_customers[$i]['invoice_customer_total_price'];
                            $invoice_customer_vat_price +=$invoice_customers[$i]['invoice_customer_vat_price'];
                            $invoice_customer_net_price +=$invoice_customers[$i]['invoice_customer_net_price'];
                        ?>
                        <tr class="odd gradeX">
                            <td class="text-center"><?php echo $i+1; ?></td>
                            <td class="text-center"><?php echo $invoice_customers[$i]['invoice_customer_date']; ?></td>
                            <td class="text-center"><?php echo $invoice_customers[$i]['invoice_customer_code']; ?></td>
                            <td><?php echo $invoice_customers[$i]['customer_name']; ?> </td>
                            <td><?php echo $invoice_customers[$i]['employee_name']; ?></td>
                            <td align="right"><?php echo number_format($invoice_customers[$i]['invoice_customer_total_price'],2); ?></td>
                            <td align="right"><?php echo number_format($invoice_customers[$i]['invoice_customer_vat_price'],2); ?></td>
                            <td align="right"><?php echo number_format($invoice_customers[$i]['invoice_customer_net_price'],2); ?></td>

                            <td>
                                <a href="?app=invoice_customer&action=detail&id=<?php echo $invoice_customers[$i]['invoice_customer_id'];?>">
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                </a>

                                 <a href="print.php?app=invoice_customer&action=pdf&id=<?PHP echo $invoice_customers[$i]['invoice_customer_id'];?>" target="_blank" >
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </a>


                                <?PHP if ( $license_sale_page == "Medium" || $license_sale_page == "High" ) { ?>
                                <a href="?app=invoice_customer&action=update&id=<?php echo $invoice_customers[$i]['invoice_customer_id'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                                <?PHP } ?>

                                <?PHP if ( $license_sale_page == "High" ) { ?>
                                <a href="?app=invoice_customer&action=delete&id=<?php echo $invoice_customers[$i]['invoice_customer_id'];?>" onclick="return confirm('You want to delete Invoice Customer : <?php echo $invoice_customers[$i]['invoice_customer_code']; ?>');" style="color:red;">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                                <?PHP } ?>
                            </td>

                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="odd gradeX">
                            <td colspan ="5"><b>จำนวนเงินรวม</b></td>
                            <td align="right"><?php echo number_format($invoice_customer_total_price,2); ?></td>
                            <td align="right"><?php echo number_format($invoice_customer_vat_price,2); ?></td>
                            <td align="right"><?php echo number_format($invoice_customer_net_price,2); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
