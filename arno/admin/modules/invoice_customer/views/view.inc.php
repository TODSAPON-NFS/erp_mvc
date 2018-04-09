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
                                    <td><?php echo $customer_orders[$i]['customer_name_en']; ?> (<?php echo $customer_orders[$i]['customer_name_th']; ?>)</td>
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
                        <table width="100%" class="table table-striped table-bordered table-hover" >
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
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $customer_purchase_orders[$i]['customer_purchase_order_code']; ?> (<?php echo $customer_purchase_orders[$i]['customer_name_th']; ?>)</td>
                                    <td>
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
            รายการใบกำกับภาษี / Invoice Customer List
                <a class="btn btn-success " style="float:right;" href="?app=invoice_customer&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th width="48">No.</th>
                            <th width="150">Invoice Date</th>
                            <th width="150">Code.</th>
                            <th>Customer</th>
                            <th width="150" >Recieve by</th>
                            <th>Remark</th>
							
                            <th width="64"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($invoice_customers); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $invoice_customers[$i]['invoice_customer_date']; ?></td>
                            <td><?php echo $invoice_customers[$i]['invoice_customer_code']; ?></td>
                            <td><?php echo $invoice_customers[$i]['customer_name']; ?> </td>
                            <td><?php echo $invoice_customers[$i]['employee_name']; ?></td>
                            <td><?php echo $invoice_customers[$i]['invoice_customer_remark']; ?></td>

                            <td>
                                <a href="?app=invoice_customer&action=detail&id=<?php echo $invoice_customers[$i]['invoice_customer_id'];?>">
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                </a>

                                 <a href="index.php?app=invoice_customer&action=print&id=<?PHP echo $invoice_customers[$i]['invoice_customer_id'];?>" >
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </a>

                                <a href="?app=invoice_customer&action=update&id=<?php echo $invoice_customers[$i]['invoice_customer_id'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                                <a href="?app=invoice_customer&action=delete&id=<?php echo $invoice_customers[$i]['invoice_customer_id'];?>" onclick="return confirm('You want to delete Invoice Customer : <?php echo $invoice_customers[$i]['invoice_customer_code']; ?>');" style="color:red;">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                           
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
            
            
