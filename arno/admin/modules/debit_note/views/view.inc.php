<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Debit Note Management</h1>
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
            รายการใบเพิ่มหนี้ / Debit Note List
                <a class="btn btn-success " style="float:right;" href="?app=debit_note&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th width="48"> ลำดับ <br>No.</th>
                            <th width="150">วันที่ออกใบเพิ่มหนี้ <br>Debit Note Date</th>
                            <th width="150">หมายเลขใบเพิ่มหนี้ <br>Debit Note Code.</th>
                            <th>ลูกค้า <br>Customer</th>
                            <th width="150" > ผู้ออก<br>Create by</th>
                            <th>หมายเหตุ <br>Remark</th>
							
                            <th width="64"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($debit_notes); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $debit_notes[$i]['debit_note_date']; ?></td>
                            <td><?php echo $debit_notes[$i]['debit_note_code']; ?></td>
                            <td><?php echo $debit_notes[$i]['customer_name']; ?> </td>
                            <td><?php echo $debit_notes[$i]['employee_name']; ?></td>
                            <td><?php echo $debit_notes[$i]['debit_note_remark']; ?></td>

                            <td>
                                <a href="?app=debit_note&action=detail&id=<?php echo $debit_notes[$i]['debit_note_id'];?>">
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                </a>

                                 <a href="index.php?app=debit_note&action=print&id=<?PHP echo $debit_notes[$i]['debit_note_id'];?>" >
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </a>

                                <a href="?app=debit_note&action=update&id=<?php echo $debit_notes[$i]['debit_note_id'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                                <a href="?app=debit_note&action=delete&id=<?php echo $debit_notes[$i]['debit_note_id'];?>" onclick="return confirm('You want to delete Debit Note : <?php echo $debit_notes[$i]['debit_note_code']; ?>');" style="color:red;">
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
            
            
