<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Stock Issue Management</h1>
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
                        รายการใบตัดคลังสินค้า / Stock Issue List
                            <a class="btn btn-success " style="float:right;" href="?app=stock_issue&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;">ลำดับ <br>No.</th>
                                        <th style="text-align:center;">วันที่ตัดสินค้า <br>Issue Date</th>
                                        <th style="text-align:center;">หมายเลยใบตัด <br>Issue No.</th>
                                        <th style="text-align:center;">จากคลังสินค้า <br>From stock</th>
                                        <th style="text-align:center;">หมายเลขใบกำกับ <br>Invoice Customer Code</th>
                                        <th style="text-align:center;">ยอดการตัดสินค้า <br>Issue Price</th>
                                        <th style="text-align:center;">ยอดตามใบกำกับ <br>Invoice Price</th>
                                        <th style="text-align:center;">ผลกำไร <br>Profit</th>
                                        <th style="text-align:center;">ผู้ตัดสินค้า <br>Issue by</th>
                                        <th style="text-align:center;">หมายเหตุ <br>Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($stock_issues); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $stock_issues[$i]['stock_issue_date']; ?></td>
                                        <td><?php echo $stock_issues[$i]['stock_issue_code']; ?></td>
                                        <td><?php echo $stock_issues[$i]['stock_group_name']; ?></td>
                                        <td><?php echo $stock_issues[$i]['invoice_customer_code']; ?></td>
                                        <td><?php echo number_format($stock_issues[$i]['stock_issue_total'],2); ?></td>
                                        <td><?php echo number_format($stock_issues[$i]['invoice_customer_total_price'],2); ?></td>
                                        <td><?php echo number_format($stock_issues[$i]['invoice_customer_total_price'] - $stock_issues[$i]['stock_issue_total'],2); ?></td>
                                        <td><?php echo $stock_issues[$i]['employee_name']; ?></td>
                                        <td><?php echo $stock_issues[$i]['stock_issue_remark']; ?></td>

                                        <td>

                                            <a href="index.php?app=stock_issue&action=print&id=<?PHP echo $stock_issues[$i]['stock_issue_id'];?>" >
                                                <i class="fa fa-print" aria-hidden="true"></i>
                                            </a>
                                            

                                            <a href="?app=stock_issue&action=detail&id=<?php echo $stock_issues[$i]['stock_issue_id'];?>">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>

                                            <a href="?app=stock_issue&action=update&id=<?php echo $stock_issues[$i]['stock_issue_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=stock_issue&action=delete&id=<?php echo $stock_issues[$i]['stock_issue_id'];?>" onclick="return confirm('You want to delete Stock Issue : <?php echo $stock_issues[$i]['stock_issue_code']; ?>');" style="color:red;">
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
            
            
