<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Billing Note Management</h1>
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
            ออกใบวางบิลตามลูกค้า /  Billing Note Customer to do
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div style="font-size:18px;padding: 8px 0px;">แยกตามลูกค้า</div>
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th width="64px" >No.</th>
                                    <th>Customer</th>
                                    <th width="180px" >Open Billing Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=0; $i < count($customer_orders); $i++){
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $customer_orders[$i]['customer_name_en']; ?> (<?php echo $customer_orders[$i]['customer_name_th']; ?>)</td>
                                    <td>
                                        <a href="?app=billing_note&action=insert&customer_id=<?php echo $customer_orders[$i]['customer_id'];?>">
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
            รายการใบวางบิล / Billing Note List
                <a class="btn btn-success " style="float:right;" href="?app=billing_note&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th width="48"> ลำดับ <br>No.</th>
                            <th width="150">วันที่ออกใบวางบิล <br>Billing Note Date</th>
                            <th width="150">หมายเลขใบวางบิล <br>Billing Note Code.</th>
                            <th>ลูกค้า <br>Customer</th>
                            <th width="150" > ผู้ออก<br>Create by</th>
                            <th>หมายเหตุ <br>Remark</th>
							
                            <th width="64"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($billing_notes); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $billing_notes[$i]['billing_note_date']; ?></td>
                            <td><?php echo $billing_notes[$i]['billing_note_code']; ?></td>
                            <td><?php echo $billing_notes[$i]['customer_name']; ?> </td>
                            <td><?php echo $billing_notes[$i]['employee_name']; ?></td>
                            <td><?php echo $billing_notes[$i]['billing_note_remark']; ?></td>

                            <td>
                                <a href="?app=billing_note&action=detail&id=<?php echo $billing_notes[$i]['billing_note_id'];?>">
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                </a>

                                 <a href="index.php?app=billing_note&action=print&id=<?PHP echo $billing_notes[$i]['billing_note_id'];?>" >
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </a>

                                <a href="?app=billing_note&action=update&id=<?php echo $billing_notes[$i]['billing_note_id'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                                <a href="?app=billing_note&action=delete&id=<?php echo $billing_notes[$i]['billing_note_id'];?>" onclick="return confirm('You want to delete Billing Note : <?php echo $billing_notes[$i]['billing_note_code']; ?>');" style="color:red;">
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
            
            
