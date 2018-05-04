            <div class="row">
                <div class="col-lg-6">
                    <h1 class="page-header">ประเภทคลังสินค้า / Stock type</h1>
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
                        รายการประเภทคลังสินค้า / Stock type list
                            <a class="btn btn-success " style="float:right;" href="?app=stock_type&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;" width="48" >ลำดับ <br>(No.)</th>
                                        <th style="text-align:center;" width="180">หมายเลขประเภทคลังสินค้า <br>(Stock type code)</th>
                                        <th style="text-align:center;">ชื่อประเภทคลังสินค้า <br>(Stock type name)</th>
                                        <th width="96"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($stock_types); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $stock_types[$i]['stock_type_code']; ?></td>
                                        <td><?php echo $stock_types[$i]['stock_type_name']; ?>
                                        <?php 
                                        $stock_groups = $model_stock_group->getStockGroupBy($stock_types[$i]['stock_type_id']);
                                        for($ii=0; $ii < count($stock_groups); $ii++){

                                        ?>
                                            <div style="padding-top:8px;padding-left:16px;"> - <?php echo $stock_groups[$ii]['stock_group_name'];?></div>
                                        <?
                                        }
                                        ?>

                                        </td>
                                        <td>
                                            <a href="?app=stock_group&action=view&stock_type_id=<?php echo $stock_types[$i]['stock_type_id'];?>">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=stock_type&action=update&id=<?php echo $stock_types[$i]['stock_type_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=stock_type&action=delete&id=<?php echo $stock_types[$i]['stock_type_id'];?>" onclick="return confirm('You want to delete stock group : <?php echo $stock_type[$i]['stock_type_name']; ?>');" style="color:red;">
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
            
            
