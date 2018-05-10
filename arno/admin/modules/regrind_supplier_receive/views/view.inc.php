<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Regrind supplier receive Management</h1>
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
            เปิดใบรับรีกายร์อ้างอิงตามบริษัท / Purchase order to do
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="row">
                            <div class="col-md-6">
                                <table width="100%" class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th width="64px" >ลำดับ <br>No.</th>
                                            <th>ผู้ขาย <br>Supplier</th>
                                            <th width="180px" >เปิดใบรับรีกายร์ <br>Open recieve regrind</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        for($i=0; $i < count($suppliers); $i++){
                                        ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $i+1; ?></td>
                                            <td><?php echo $suppliers[$i]['supplier_name_en']; ?> (<?php echo $suppliers[$i]['supplier_name_th']; ?>)</td>
                                            <td>
                                                <a href="?app=regrind_supplier_receive&action=insert&supplier_id=<?php echo $suppliers[$i]['supplier_id'];?>">
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
                                <table width="100%" class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th width="64px" >ลำดับ <br>No.</th>
                                            <th>ผู้ขาย <br>ใบส่งรีกายร์</th>
                                            <th width="180px" >เปิดใบรับรีกายร์ <br>Open recieve regrind</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        for($i=0; $i < count($regrind_suppliers); $i++){
                                        ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $i+1; ?></td>
                                            <td><?php echo $regrind_suppliers[$i]['regrind_supplier_code']; ?> <?php echo $regrind_suppliers[$i]['supplier_name_en']; ?> (<?php echo $regrind_suppliers[$i]['supplier_name_th']; ?>)</td>
                                            <td>
                                                <a href="?app=regrind_supplier_receive&action=insert&supplier_id=<?php echo $regrind_suppliers[$i]['supplier_id'];?>">
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
                        รายการใบรีกายร์สินค้าจากผู้ขาย /  Regrind supplier receive List
                            <a class="btn btn-success " style="float:right;" href="?app=regrind_supplier_receive&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                        </div>
                        <!-- /.panel-heading -->

                        
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ <br>No.</th>
                                        <th>วันที่รับใบรีกายร์ <br>RG Date</th>
                                        <th>หมายเลขใบรีกายร์ <br>RG No.</th>
                                        <th>ผู้ขาย <br>Supplier</th>
                                        <th>ผู้ติดต่อ <br>Contact</th>
                                        <th>ผู้รับ <br>Recieve by</th>
                                        <th>หมายเหตุ <br>Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($regrind_supplier_receives); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $regrind_supplier_receives[$i]['regrind_supplier_receive_date']; ?></td>
                                        <td><?php echo $regrind_supplier_receives[$i]['regrind_supplier_receive_code']; ?></td>
                                        <td><?php echo $regrind_supplier_receives[$i]['supplier_name']; ?></td>
                                        <td><?php echo $regrind_supplier_receives[$i]['contact_name']; ?></td>
                                        <td><?php echo $regrind_supplier_receives[$i]['employee_name']; ?></td>
                                        <td><?php echo $regrind_supplier_receives[$i]['regrind_supplier_receive_remark']; ?></td>
                                        <td>
                                            <?
                                                if($regrind_supplier_receives[$i]['regrind_supplier_receive_file'] != ""){
                                            ?>
                                                <a href="../upload/regrind_supplier_receive/<?php echo $regrind_supplier_receives[$i]['regrind_supplier_receive_file'];?>" target="_blank">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                </a> 
                                            <?
                                                }
                                            ?>

                                            <a href="index.php?app=regrind_supplier_receive&action=print&id=<?PHP echo $regrind_supplier_receives[$i]['regrind_supplier_receive_id'];?>" >
                                                <i class="fa fa-print" aria-hidden="true"></i>
                                            </a>
                                            

                                            <a href="?app=regrind_supplier_receive&action=detail&id=<?php echo $regrind_supplier_receives[$i]['regrind_supplier_receive_id'];?>">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>

                                            <a href="?app=regrind_supplier_receive&action=update&id=<?php echo $regrind_supplier_receives[$i]['regrind_supplier_receive_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=regrind_supplier_receive&action=delete&id=<?php echo $regrind_supplier_receives[$i]['regrind_supplier_receive_id'];?>" onclick="return confirm('You want to delete Regrind supplier receive : <?php echo $regrind_supplier_receives[$i]['regrind_supplier_receive_code']; ?>');" style="color:red;">
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
            
            
