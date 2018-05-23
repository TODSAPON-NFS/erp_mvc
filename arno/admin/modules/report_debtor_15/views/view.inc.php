
<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var user_id = $("#user_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=report_debtor_15&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&user_id="+user_id+"&keyword="+keyword;
    }

    function print(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var user_id = $("#user_id").val();
        var keyword = $("#keyword").val();

        window.location = "print/report_debtor_15.php?date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&user_id="+user_id+"&keyword="+keyword;
    }
</script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานใบเสนอราคา</h1>
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
                        รายงานใบเสนอราคาสินค้า
                    </div>
                    <div class="col-md-4">
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>วันที่ออกใบเสนอราคา</label>
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ลูกค้า </label>
                            <select id="customer_id" name="customer_id" class="form-control select" data-live-search="true">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ผู้ออกใบเสนอราคา </label>
                            <select id="user_id" name="user_id" class="form-control select" data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($users) ; $i++){
                                ?>
                                <option <?php if($users[$i]['user_id'] == $user_id){?> selected <?php }?> value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['user_name_en'] ?> (<?php echo $users[$i]['user_name_th'] ?>)</option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                        <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="print();">Print</button>
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                        <a href="index.php?app=report_debtor_15" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th>ลำดับ<br>No.</th>
                            <th>วันที่ออกใบเสนอราคา<br>Quotation Date</th>
                            <th>หมายเลขใบเสนอราคา<br>Quotation No.</th>
                            <th>ลูกค้า<br>Customer.</th>
                            <th>ยอดเงิน<br>Net Price.</th>
                            <th>ผู้ติดต่อ<br>Contact.</th>
                            <th>ผู้ออกใบเสนอราคา<br>Create by.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($quotations); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td align="center" ><?php echo $quotations[$i]['quotation_date']; ?></td>
                            <td  align="left" >
                                <?php echo $quotations[$i]['quotation_code']; ?>
                                <?php if($quotations[$i]['quotation_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $quotations[$i]['quotation_rewrite_no']; ?></font></b> <?PHP } ?> <?php if($quotations[$i]['quotation_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?>
                            </td>
                            <td><?php echo $quotations[$i]['customer_name']; ?></td>
                            <td  align="right" >
                                <?php echo number_format($quotations[$i]['quotation_total'],2); ?>
                            </td>
                            <td><?php echo $quotations[$i]['quotation_contact_name']; ?></td>
                            <td><?php echo $quotations[$i]['employee_name']; ?></td>
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
            
            
