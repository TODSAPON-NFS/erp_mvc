<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Delivery Note Customer Management</h1>
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
                        รายการใบยืมสินค้าสำหรับลูกค้า / Delivery Note Customer List
                            <a class="btn btn-success " style="float:right;" href="?app=delivery_note_customer&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>DNC Date</th>
                                        <th>DNC No.</th>
                                        <th>Customer</th>
                                        <th>Contact</th>
                                        <th>Sent by</th>
                                        <th>Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($delivery_note_customers); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $delivery_note_customers[$i]['delivery_note_customer_date']; ?></td>
                                        <td><?php echo $delivery_note_customers[$i]['delivery_note_customer_code']; ?></td>
                                        <td><?php echo $delivery_note_customers[$i]['customer_name']; ?></td>
                                        <td><?php echo $delivery_note_customers[$i]['contact_name']; ?></td>
                                        <td><?php echo $delivery_note_customers[$i]['employee_name']; ?></td>
                                        <td><?php echo $delivery_note_customers[$i]['delivery_note_customer_remark']; ?></td>

                                        <td>
                                            <?
                                                if($delivery_note_customers[$i]['delivery_note_customer_file'] != ""){
                                            ?>
                                                <a href="../upload/delivery_note_customer/<?php echo $delivery_note_customers[$i]['delivery_note_customer_file'];?>" target="_blank">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                </a> 
                                            <?
                                                }
                                            ?>

                                            <a href="index.php?app=delivery_note_customer&action=print&id=<?PHP echo $delivery_note_customers[$i]['delivery_note_customer_id'];?>" >
                                                <i class="fa fa-print" aria-hidden="true"></i>
                                            </a>
                                            

                                            <a href="?app=delivery_note_customer&action=detail&id=<?php echo $delivery_note_customers[$i]['delivery_note_customer_id'];?>">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>

                                            <a href="?app=delivery_note_customer&action=update&id=<?php echo $delivery_note_customers[$i]['delivery_note_customer_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=delivery_note_customer&action=delete&id=<?php echo $delivery_note_customers[$i]['delivery_note_customer_id'];?>" onclick="return confirm('You want to delete Delivery Note Customer : <?php echo $delivery_note_customers[$i]['delivery_note_customer_code']; ?>');" style="color:red;">
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
            
            
