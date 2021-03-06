<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=journal_special_06&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword;
    }
</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Journal Sale Return Management</h1>
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
                    <div class="col-md-12">
                        รายการสมุดรายวันรับคืนสินค้า / Journal Sale Return List
                    </div>
                    <!--
                    <div class="col-md-4">
                        <a class="btn btn-success " style="float:right;" href="?app=journal_special_06&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                    -->
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>วันที่ออกสมุดรายวันรับคืนสินค้า</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start;?>"  class="form-control calendar" readonly/>
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/>
                                </div>
                            </div>
                            <p class="help-block">01-01-2018 - 31-12-2018</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>คำค้น <font color="#F00"><b>*</b></font></label>
                            <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                            <p class="help-block">Example : T001.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                        <a href="index.php?app=journal_special_06" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ลำดับ <br>No.</th>
                            <th>วันที่ <br>Journal Sale Return Date</th>
                            <th>หมายเลขสมุดรายวันรับคืนสินค้า <br>Journal Sale Return No.</th>
                            <th>ชื่อสมุดรายวันรับคืนสินค้า <br>Name.</th>
                            <th>ผู้เพิ่มเอกสาร <br>Add by</th>
                            <th>วันที่เพิ่มเอกสาร <br>Add date</th>
                            <th>ผู้แก้ไขเอกสาร <br>Update by</th>
                            <th>วันที่แก้ไขเอกสาร <br>Last update</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($journal_credit_notes); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $journal_credit_notes[$i]['journal_credit_note_date']; ?></td>
                            <td><?php echo $journal_credit_notes[$i]['journal_credit_note_code']; ?></td>
                            <td><?php echo $journal_credit_notes[$i]['journal_credit_note_name']; ?></td>
                            <td><?php echo $journal_credit_notes[$i]['add_name']; ?></td>
                            <td><?php echo $journal_credit_notes[$i]['adddate']; ?></td>
                            <td><?php echo $journal_credit_notes[$i]['update_name']; ?></td>
                            <td><?php echo $journal_credit_notes[$i]['lastupdate']; ?></td>
                            <td>
 
                                <a href="?app=journal_special_06&action=update&id=<?php echo $journal_credit_notes[$i]['journal_credit_note_id'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 

                                <!-- 
                                <a href="?app=journal_special_06&action=delete&id=<?php echo $journal_credit_notes[$i]['journal_credit_note_id'];?>" onclick="return confirm('You want to delete Journal Sale Return : <?php echo $journal_credit_notes[$i]['journal_credit_note_code']; ?>');" style="color:red;">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                                -->
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


