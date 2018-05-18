<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Stock Move Management</h1>
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
                                    รายการใบย้ายคลังสินค้า / Stock Move List
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-success " style="float:right;" href="?app=stock_move&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;">ลำดับ <br>No.</th>
                                        <th style="text-align:center;">วันที่ย้าย <br>Move Date</th>
                                        <th style="text-align:center;">หมายเลยใบย้าย <br>Move No.</th>
                                        <th style="text-align:center;">จากคลัง <br>From stock</th>
                                        <th style="text-align:center;">ไปยังคลัง <br>To stock</th>
                                        <th style="text-align:center;">ผู้ย้าย <br>Move by</th>
                                        <th style="text-align:center;">หมายเหตุ <br>Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($stock_moves); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $stock_moves[$i]['stock_move_date']; ?></td>
                                        <td><?php echo $stock_moves[$i]['stock_move_code']; ?></td>
                                        <td><?php echo $stock_moves[$i]['move_group_name_out']; ?></td>
                                        <td><?php echo $stock_moves[$i]['move_group_name_in']; ?></td>
                                        <td><?php echo $stock_moves[$i]['employee_name']; ?></td>
                                        <td><?php echo $stock_moves[$i]['stock_move_remark']; ?></td>

                                        <td>

                                            <a href="index.php?app=stock_move&action=print&id=<?PHP echo $stock_moves[$i]['stock_move_id'];?>" >
                                                <i class="fa fa-print" aria-hidden="true"></i>
                                            </a>
                                            

                                            <a href="?app=stock_move&action=detail&id=<?php echo $stock_moves[$i]['stock_move_id'];?>">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>

                                            <a href="?app=stock_move&action=update&id=<?php echo $stock_moves[$i]['stock_move_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=stock_move&action=delete&id=<?php echo $stock_moves[$i]['stock_move_id'];?>" onclick="return confirm('You want to delete Stock Move : <?php echo $stock_moves[$i]['stock_move_code']; ?>');" style="color:red;">
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
            
            
