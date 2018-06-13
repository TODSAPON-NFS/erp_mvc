
<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=credit_purchasing&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
    }
</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Credit Purchasing Management</h1>
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
                                    รายการซื้อเงินเชื่อสินค้า / Credit Purchasing List
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-success " style="float:right;" href="?app=credit_purchasing&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>วันที่ออกซื้อเงินเชื่อ</label>
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
                                        <select id="supplier_id" name="supplier_id" class="form-control select" data-live-search="true">
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
                                    <a href="index.php?app=credit_purchasing" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                                </div>
                            </div>
                            <br>

                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-view">
                                <thead>
                                    <tr>
                                        <th>ลำดับ<br>No.</th>
                                        <th>วันที่ออกซื้อเงินเชื่อ<br>Credit Purchasing Date</th>
                                        <th>หมายเลขซื้อเงินเชื่อ<br>Credit Purchasing No.</th>
                                        <th>หมายเลขใบสั่งซื้อ<br>Purchase Order No.</th>
                                        <th>ผู้ขาย<br>Supplier</th>
                                        <th>มูลค่า<br>Value</th> 
                                        <th>ผู้ออกซื้อเงินเชื่อ<br>Create by</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($credit_purchasings); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $credit_purchasings[$i]['credit_purchasing_date']; ?></td>
                                        <td>
                                            <?php echo $credit_purchasings[$i]['credit_purchasing_code']; ?>
                                        </td>
                                        <td>
                                            <?php echo $credit_purchasings[$i]['purchase_order_code']; ?>
                                        </td>
                                        <td><?php echo $credit_purchasings[$i]['supplier_name']; ?></td>
                                        <td><?php echo number_format($credit_purchasings[$i]['credit_purchasing_net'],2); ?></td>
                                        <td><?php echo $credit_purchasings[$i]['employee_name']; ?></td>
                                        
                                        <td>
                                            <a href="?app=credit_purchasing&action=detail&id=<?php echo $credit_purchasings[$i]['credit_purchasing_id'];?>" title="ดูรายละเอียดซื้อเงินเชื่อ">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>
                                            <a href="?app=credit_purchasing&action=update&id=<?php echo $credit_purchasings[$i]['credit_purchasing_id'];?>"  title="แก้ไขซื้อเงินเชื่อ">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a href="?app=credit_purchasing&action=delete&id=<?php echo $credit_purchasings[$i]['credit_purchasing_id'];?>"  title="ลบซื้อเงินเชื่อ" onclick="return confirm('You want to delete credit purchasings : <?php echo $credit_purchasings[$i]['credit_purchasing_code']; ?>');" style="color:red;">
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
            
            
