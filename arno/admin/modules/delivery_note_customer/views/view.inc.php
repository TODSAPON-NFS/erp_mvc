<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=delivery_note_customer&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword;
    }
</script>

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
                            <div class="row">
                                <div class="col-md-8">
                                    รายการใบยืมสินค้าสำหรับลูกค้า / Delivery Note Customer List
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-success " style="float:right;" href="?app=delivery_note_customer&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>วันที่ออกใบยืม</label>
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
                                        <label>ผู้ซื้อ </label>
                                        <select id="customer_id" name="customer_id" class="form-control select"  data-live-search="true">
                                            <option value="">ทั้งหมด</option>
                                            <?php 
                                            for($i =  0 ; $i < count($customers) ; $i++){
                                            ?>
                                            <option <?php if($customers[$i]['customer_id'] == $customer_id){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> (<?php echo $customers[$i]['customer_name_th'] ?>)</option>
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
                                    <a href="index.php?app=delivery_note_customer" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                                </div>
                            </div>
                            <br>

                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ <br>No.</th>
                                        <th>วันที่รับใบยืม <br>DNC Date</th>
                                        <th>หมายเลขใบยืม <br>DNC No.</th>
                                        <th>ลูกค้า <br>Customer</th>
                                        <th>ผู้ติดต่อ <br>Contact</th>
                                        <th>ผู้ส่ง <br>Send by</th>
                                        <th>หมายเหตุ <br>Remark</th>
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
                                        <td><?php echo $delivery_note_customers[$i]['delivery_note_customer_code']; ?>-<?PHP echo strtoupper(substr($delivery_note_customers[$i]['employee_name'],0,2));?></td>
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
            
            
