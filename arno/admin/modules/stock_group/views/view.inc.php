            <div class="row">
                <div class="col-lg-6">
                    <h1 class="page-header">คลังสินค้า / Stock</h1>
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
                        รายการคลังสินค้า / Stock List
                            <a class="btn btn-success " style="float:right;" href="?app=stock_group&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;" width="48" >ลำดับ <br>(No.)</th>
                                        <th style="text-align:center;">ชื่อคลังสินค้า <br>(Stock Name)</th>
                                        <th style="text-align:center;">รายละเอียด <br>(Description)</th>
                                        <th width="96"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($stock_groups); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $stock_groups[$i]['stock_group_name']; ?></td>
                                        <td><?php echo $stock_groups[$i]['stock_group_detail']; ?></td>
                                        <td>
                                            <a href="?app=stock_in&action=view&id=<?php echo $stock_groups[$i]['stock_group_id'];?>">
                                                <i class="fa fa-sign-in" aria-hidden="true"></i>
                                            </a>
                                            <a href="?app=stock_out&action=view&id=<?php echo $stock_groups[$i]['stock_group_id'];?>">
                                                <i class="fa fa-sign-out" aria-hidden="true"></i>
                                            </a>
                                            <a href="?app=stock_list&action=view&id=<?php echo $stock_groups[$i]['stock_group_id'];?>">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=stock_group&action=update&id=<?php echo $stock_groups[$i]['stock_group_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=stock_group&action=delete&id=<?php echo $stock_groups[$i]['stock_group_id'];?>" onclick="return confirm('You want to delete stock group : <?php echo $stock_group[$i]['stock_group_name']; ?>');" style="color:red;">
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
            
            
