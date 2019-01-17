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
                            <div class="row">
                                <div class="col-md-8">
                                    รายการใบตัดคลังสินค้า / Stock Issue List
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-success " style="float:right;" href="?app=stock_issue&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                       <th class="datatable-th text-center "data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="10"> No.</th>
                                       <th class="datatable-th text-center "data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="วันที่ตัดสินค้า" width="100">Issue Date</th>
                                       <th class="datatable-th text-center "data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลยใบตัด" width="100"> Issue No.</th>
                                       <th class="datatable-th text-center "data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="จากคลังสินค้า" width=""> From stock</th>
                                       <th class="datatable-th text-center "data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลขใบกำกับ" width=""> Invoice Customer Code</th>
                                       <th class="datatable-th text-center "data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ยอดการตัดสินค้า" width=""> Issue Price</th>
                                       <th class="datatable-th text-center "data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ยอดตามใบกำกับ" width=""> Invoice Price</th>
                                       <th class="datatable-th text-center "data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผลกำไร" width=""> Profit</th>
                                       <th class="datatable-th text-center "data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้ตัดสินค้า" width=""> Issue by</th>
                                       <th class="datatable-th text-center "data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเหตุ" width=""> Remark</th>
                                        <th class="datatable-th text-center "data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="" width="10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($stock_issues); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td class="text-center"><?php echo $i+1; ?></td>
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
            
            
