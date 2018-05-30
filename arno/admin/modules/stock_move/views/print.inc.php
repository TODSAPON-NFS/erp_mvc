<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Stock Transfer Management</h1>
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
            รายละเอียดใบย้ายคลังสินค้า /  Stock Transfer Detail  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                        <div class="col-lg-5">
                            <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>จากคลังสินค้า / From stock <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $stock_move['move_group_name_out'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ไปยังคลังสินค้า / To stock  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $stock_move['move_group_name_in'];?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                        </div>
                        <div class="col-lg-5">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเลขใบย้ายสินค้า / Stock Transfer Code <font color="#F00"><b>*</b></font></label> 
                                        <p class="help-block"><?php echo $stock_move['stock_move_code'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบย้ายสินค้า / Stock Move Date</label> 
                                        <p class="help-block"><?php echo $stock_move['stock_move_date'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ย้ายคลังสินค้า / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $stock_move['employee_name'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark</label> 
                                        <p class="help-block"><?php echo $stock_move['stock_move_remark'];?> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">รหัสสินค้า<br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า<br>(Product Name)</th>
                                <th style="text-align:center;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;">หมายเหตุ<br>(Remark)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($stock_move_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                <?php echo $stock_move_lists[$i]['product_code']; ?>
                                </td>
                                <td><?php echo $stock_move_lists[$i]['product_name']; ?></td>
                                <td align="right"><?php echo $stock_move_lists[$i]['stock_move_list_qty']; ?></td>
                                <td><?php echo $stock_move_lists[$i]['stock_move_list_remark']; ?></td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                    </table> 

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=stock_move" class="btn btn-default">Back</a>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>