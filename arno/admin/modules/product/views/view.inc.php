            <div class="row">
                <div class="col-lg-6">
                    <h1 class="page-header">Product Management</h1>
                </div>
                <div class="col-lg-6" align="right">
                    <a href="?app=product" class="btn btn-primary active btn-menu">สินค้า / Product</a>
                    <a href="?app=product_category" class="btn btn-primary btn-menu">ลักษณะ / Category</a>
                    <a href="?app=product_type" class="btn btn-primary btn-menu">ประเภท / Type</a>
                    <a href="?app=product_group" class="btn btn-primary btn-menu">กลุ่ม / Group</a>
                    <a href="?app=product_unit" class="btn btn-primary btn-menu">หน่วย / Unit</a>
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
                                    รายการสินค้า / Product List
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-success " style="float:right;" href="?app=product&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>รหัสสินค้า <br>Product Code</th>
                                        <th>ชื่อสินค้า <br>Product Name</th>
                                        <th>รายละเอียด <br> Description</th>
                                        <th>สถานะ <br> Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($product); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $product[$i]['product_code']; ?></td>
                                        <td><?php echo $product[$i]['product_name']; ?></td>
                                        <td class="center"><?php echo $product[$i]['product_description']; ?></td>
                                        <td class="center"><?php echo $product[$i]['product_status']; ?></td>
                                        <td>
                                        <?
                                            if($product[$i]['product_drawing'] != ""){
                                        ?>
                                            <a href="../upload/product/<?php echo $product[$i]['product_drawing'];?>" target="_blank">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            </a> 
                                        <?
                                            }
                                        ?>
                                            <a href="?app=product&action=update&id=<?php echo $product[$i]['product_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=product&action=delete&id=<?php echo $product[$i]['product_id'];?>" onclick="return confirm('You want to delete product : <?php echo $product[$i]['product_name']; ?>');" style="color:red;">
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
            
            
