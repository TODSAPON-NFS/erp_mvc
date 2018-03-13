<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Invoice Supplier Management</h1>
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
            รายการใบกำกับภาษีรับเข้าตามผู้ขาย / Invoice Supplier to do
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th width="64px" >No.</th>
                            <th>Supplier</th>
                            <th width="180px" >Open Invoice Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($supplier_orders); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $supplier_orders[$i]['supplier_name_en']; ?> (<?php echo $supplier_orders[$i]['supplier_name_th']; ?>)</td>
                            <td>
                                <a href="?app=invoice_supplier&action=insert&supplier_id=<?php echo $supplier_orders[$i]['supplier_id'];?>">
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
            รายการใบกำกับภาษีรับเข้า / Invoice Supplier List
                <a class="btn btn-success " style="float:right;" href="?app=invoice_supplier&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th width="48">No.</th>
                            <th width="150">Recieve Date</th>
                            <th width="150">Invoice Date</th>
                            <th width="150">Code.</th>
                            <th>Supplier</th>
                            <th width="150" >Recieve by</th>
                            <th>Remark</th>
							
                            <th width="64"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($invoice_suppliers); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $invoice_suppliers[$i]['invoice_supplier_date_recieve']; ?></td>
                            <td><?php echo $invoice_suppliers[$i]['invoice_supplier_date']; ?></td>
                            <td><?php echo $invoice_suppliers[$i]['invoice_supplier_code']; ?></td>
                            <td><?php echo $invoice_suppliers[$i]['supplier_name']; ?> </td>
                            <td><?php echo $invoice_suppliers[$i]['employee_name']; ?></td>
                            <td><?php echo $invoice_suppliers[$i]['invoice_supplier_remark']; ?></td>

                            <td>
                                <a href="?app=invoice_supplier&action=detail&id=<?php echo $invoice_suppliers[$i]['invoice_supplier_id'];?>">
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                </a>


                                <a href="?app=invoice_supplier&action=update&id=<?php echo $invoice_suppliers[$i]['invoice_supplier_id'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                                <a href="?app=invoice_supplier&action=delete&id=<?php echo $invoice_suppliers[$i]['invoice_supplier_id'];?>" onclick="return confirm('You want to delete Invoice Supplier : <?php echo $invoice_suppliers[$i]['invoice_supplier_code']; ?>');" style="color:red;">
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
            
            
