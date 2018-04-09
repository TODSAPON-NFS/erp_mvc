<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Credit Note Management</h1>
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
            รายการใบลดหนี้ / Credit Note List
                <a class="btn btn-success " style="float:right;" href="?app=credit_note&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th width="48"> ลำดับ <br>No.</th>
                            <th width="150">วันที่ออกใบลดหนี้ <br>Credit Note Date</th>
                            <th width="150">หมายเลขใบลดหนี้ <br>Credit Note Code</th>
                            <th>ลูกค้า <br>Customer</th>
                            <th width="150" > ผู้ออก<br>Create by</th>
                            <th>ประเภทใบลดหนี้ <br>Credit Note Type</th>
							
                            <th width="64"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($credit_notes); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $credit_notes[$i]['credit_note_date']; ?></td>
                            <td><?php echo $credit_notes[$i]['credit_note_code']; ?></td>
                            <td><?php echo $credit_notes[$i]['customer_name']; ?> </td>
                            <td><?php echo $credit_notes[$i]['employee_name']; ?></td>
                            <td><?php echo $credit_notes[$i]['credit_note_type_name']; ?></td>

                            <td>
                                <a href="?app=credit_note&action=detail&id=<?php echo $credit_notes[$i]['credit_note_id'];?>">
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                </a>

                                 <a href="index.php?app=credit_note&action=print&id=<?PHP echo $credit_notes[$i]['credit_note_id'];?>" >
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </a>

                                <a href="?app=credit_note&action=update&id=<?php echo $credit_notes[$i]['credit_note_id'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                                <a href="?app=credit_note&action=delete&id=<?php echo $credit_notes[$i]['credit_note_id'];?>" onclick="return confirm('You want to delete Credit Note : <?php echo $credit_notes[$i]['credit_note_code']; ?>');" style="color:red;">
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
            
            
