<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Stock Change Product Management</h1>
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
                                    รายการใบย้ายคลังสินค้า / Stock Change Product List
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-success " style="float:right;" href="?app=stock_change_product&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;">ลำดับ <br>No.</th>
                                        <th style="text-align:center;">วันที่ย้าย <br>Change Product Date</th>
                                        <th style="text-align:center;">หมายเลยใบย้าย <br>Change Product No.</th>
                                        <th style="text-align:center;">คลัง <br>Stock</th> 
                                        <th style="text-align:center;">ผู้ย้าย <br>Change Product by</th>
                                        <th style="text-align:center;">หมายเหตุ <br>Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($stock_change_products); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td align="center" ><?php echo $i+1; ?></td>
                                        <td><?php echo $stock_change_products[$i]['stock_change_product_date']; ?></td>
                                        <td><?php echo $stock_change_products[$i]['stock_change_product_code']; ?></td>
                                        <td><?php echo $stock_change_products[$i]['stock_group_name']; ?></td>
                                        <td><?php echo $stock_change_products[$i]['employee_name']; ?></td>
                                        <td><?php echo $stock_change_products[$i]['stock_change_product_remark']; ?></td> 
                                        <td> 
                                            <a href="print.php?app=stock_change_product&action=print&id=<?PHP echo $stock_change_products[$i]['stock_change_product_id'];?>" >
                                                <i class="fa fa-print" aria-hidden="true"></i>
                                            </a>  
                                            <a href="?app=stock_change_product&action=update&id=<?php echo $stock_change_products[$i]['stock_change_product_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=stock_change_product&action=delete&id=<?php echo $stock_change_products[$i]['stock_change_product_id'];?>" onclick="return confirm('You want to delete Stock Change Product : <?php echo $stock_change_products[$i]['stock_change_product_code']; ?>');" style="color:red;">
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
            
            
