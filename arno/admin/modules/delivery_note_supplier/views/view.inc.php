<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=delivery_note_supplier&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
    }
</script>
<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Delivery Note Supplier Management</h1>
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
                                    รายการใบยืมสินค้าจากผู้ขาย /  Delivery Note Supplier List
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-success " style="float:right;" href="?app=delivery_note_supplier&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>วันที่รับใบยืม</label>
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
                                        <label>ผู้ขาย </label>
                                        <select id="supplier_id" name="supplier_id" class="form-control select"  data-live-search="true">
                                            <option value="">ทั้งหมด</option>
                                            <?php 
                                            for($i =  0 ; $i < count($suppliers) ; $i++){
                                            ?>
                                            <option <?php if($suppliers[$i]['supplier_id'] == $supplier_id){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> (<?php echo $suppliers[$i]['supplier_name_th'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
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
                                    <a href="index.php?app=delivery_note_supplier" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                                </div>
                            </div>
                            <br>

                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ <br>No.</th>
                                        <th>วันที่รับใบยืม <br>DNS Date</th>
                                        <th>หมายเลขใบยืม <br>DNS No.</th>
                                        <th>ผู้ขาย <br>Supplier</th>
                                        <th>ผู้ติดต่อ <br>Contact</th>
                                        <th>ผู้รับ <br>Recieve by</th>
                                        <th>หมายเหตุ <br>Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($delivery_note_suppliers); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $delivery_note_suppliers[$i]['delivery_note_supplier_date']; ?></td>
                                        <td><?php echo $delivery_note_suppliers[$i]['delivery_note_supplier_code']; ?>-<?PHP echo strtoupper(substr($delivery_note_suppliers[$i]['employee_name'],0,2));?></td>
                                        <td><?php echo $delivery_note_suppliers[$i]['supplier_name']; ?></td>
                                        <td><?php echo $delivery_note_suppliers[$i]['contact_name']; ?></td>
                                        <td><?php echo $delivery_note_suppliers[$i]['employee_name']; ?></td>
                                        <td><?php echo $delivery_note_suppliers[$i]['delivery_note_supplier_remark']; ?></td>

                                        <td>
                                            <?
                                                if($delivery_note_suppliers[$i]['delivery_note_supplier_file'] != ""){
                                            ?>
                                                <a href="../upload/delivery_note_supplier/<?php echo $delivery_note_suppliers[$i]['delivery_note_supplier_file'];?>" target="_blank">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                </a> 
                                            <?
                                                }
                                            ?>

                                            <a href="index.php?app=delivery_note_supplier&action=print&id=<?PHP echo $delivery_note_suppliers[$i]['delivery_note_supplier_id'];?>" >
                                                <i class="fa fa-print" aria-hidden="true"></i>
                                            </a>
                                            

                                            <a href="?app=delivery_note_supplier&action=detail&id=<?php echo $delivery_note_suppliers[$i]['delivery_note_supplier_id'];?>">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>

                                            <a href="?app=delivery_note_supplier&action=update&id=<?php echo $delivery_note_suppliers[$i]['delivery_note_supplier_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=delivery_note_supplier&action=delete&id=<?php echo $delivery_note_suppliers[$i]['delivery_note_supplier_id'];?>" onclick="return confirm('You want to delete Delivery Note Supplier : <?php echo $delivery_note_suppliers[$i]['delivery_note_supplier_code']; ?>');" style="color:red;">
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
            
            
