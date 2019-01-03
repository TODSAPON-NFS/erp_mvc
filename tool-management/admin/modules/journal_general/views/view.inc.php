<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=journal_general&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword;
    }
</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Journal General Management</h1>
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
                        รายการสมุดรายวันทั่วไป / Journal General List
                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-success " style="float:right;" href="?app=journal_general&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="get" action="index.php?app=journal_general">
                    <input type="hidden" name="app" value="journal_general" />
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>วันที่ออกสมุดรายวันทั่วไป</label>
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
                            <button class="btn btn-primary" style="float:right; margin:0px 4px;" type="submit">Search</button>
                            <a href="index.php?app=journal_general" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                        </div>
                    </div>
                </form>
                <br>
 
                <table width="100%" class="table table-striped table-bordered table-hover"  id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ลำดับ <br>No.</th>
                            <th>วันที่ <br>Journal Cash Payment Date</th>
                            <th>หมายเลขสมุดรายวันทั่วไป <br>Journal Cash Payment No.</th>
                            <th>ชื่อสมุดรายวันทั่วไป <br>Name.</th>
                            <th>เดบิต <br>Debit.</th>
                            <th>เครดิต <br>Credit.</th>
                            <th>สถานะ <br>Status.</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                        for($i = 0  ; $i < count($journal_generals) ; $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $journal_generals[$i]['journal_general_date']; ?></td>
                            <td><?php echo $journal_generals[$i]['journal_general_code']; ?></td>
                            <td><?php echo $journal_generals[$i]['journal_general_name']; ?></td> 
                            <td align="right"><?php echo number_format($journal_generals[$i]['journal_debit'],2); ?></td> 
                            <td align="right"><?php echo number_format($journal_generals[$i]['journal_credit'],2); ?></td>  
                            <td align="center">
                                <?PHP if(number_format($journal_generals[$i]['journal_debit'],2) == number_format($journal_generals[$i]['journal_credit'],2)){ ?>
                                    <font color="green"><b>ยอดตรง</b></font>
                                <?PHP } else { ?> 
                                    <font color="red"><b>ยอดไม่ตรง</b></font>
                                <?PHP } ?>
                            </td>  
                            <td>
                                <a href="print.php?app=report_journal_general&type=id&action=pdf&id=<?php echo $journal_generals[$i]['journal_general_id'];?>" target="_blank" >
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </a> 
                                <a href="?app=journal_general&action=update&id=<?php echo $journal_generals[$i]['journal_general_id'];?>" style="color:orange;">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                                <a href="?app=journal_general&action=delete&id=<?php echo $journal_generals[$i]['journal_general_id'];?>" onclick="return confirm('You want to delete Journal Cash Payment : <?php echo $journal_generals[$i]['journal_general_code']; ?>');" style="color:red;">
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

