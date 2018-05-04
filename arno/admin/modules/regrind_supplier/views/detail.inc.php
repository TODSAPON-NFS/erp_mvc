
<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Regrind Supplier  Management</h1>
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
               รายละเอียดใบรีกายร์สินค้าจากผู้ขาย / Regrind Supplier detail   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><? echo $regrind_supplier['supplier_code'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $regrind_supplier['supplier_name_en'] ?> (<?php echo $regrind_supplier['supplier_name_th'] ?>)</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อผู้ติดต่อ / Contact name <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?PHP echo $regrind_supplier['contact_name']; ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><? echo $regrind_supplier['supplier_address_1'] ."\n". $regrind_supplier['supplier_address_2'] ."\n". $regrind_supplier['supplier_address_3'];?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเลขใบรีกายร์ / RG Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?PHP echo $regrind_supplier['regrind_supplier_code']; ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบรีกายร์ / RG Date</label>
                                        <p class="help-block"><?PHP echo $regrind_supplier['regrind_supplier_date']; ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้รับสินค้า / Employee  <font color="#F00"><b>*</b></font> </label>
                                       
                                        <p class="help-block"><?PHP echo $regrind_supplier['user_name']; ?> <?PHP echo $regrind_supplier['user_lastname']; ?> (<?PHP echo $regrind_supplier['user_position_name']; ?>)</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark</label>
                                        <p class="help-block"><?PHP echo $regrind_supplier['regrind_supplier_remark']; ?> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;" width="48">ลำดับ<br>(No.)</th>
                                <th style="text-align:center;">รหัสสินค้า<br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า<br>(Product Name)</th>
                                <th style="text-align:center;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;">หมายเหตุ<br>(Remark)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($regrind_supplier_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td style="text-align:center;" >
                                    <?php echo $i+1; ?>.
                                </td>
                                <td>
                                    <?php echo $regrind_supplier_lists[$i]['product_code']; ?>
                                </td>
                                <td><?php echo $regrind_supplier_lists[$i]['product_name']; ?></td>
                                <td align="right"><?php echo $regrind_supplier_lists[$i]['regrind_supplier_list_qty']; ?></td>
                                <td ><?php echo $regrind_supplier_lists[$i]['regrind_supplier_list_remark']; ?></td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                    </table> 

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=regrind_supplier" class="btn btn-default">Back</a>
                            <a href="index.php?app=regrind_supplier&action=print&id=<?PHP echo $regrind_supplier_id?>" class="btn btn-danger">Print</a>
                        </div>
                    </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>