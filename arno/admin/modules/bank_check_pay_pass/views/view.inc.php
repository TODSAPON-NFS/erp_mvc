<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=bank_check_pay_pass&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
    }

    function check(id,check_date){
        var date = $(id).children('input[name="check_pay_date_pass"]').val();

        var str_pass = date.split("-");
        var str_pay = check_date.split("-");

        var check_pay_date_pass = new Date (str_pass[1]+'-'+str_pass[0]+'-'+str_pass[2]);
        var check_pay_date = new Date(str_pay[1]+'-'+str_pay[0]+'-'+str_pay[2]);
        if(date == ""){
            alert("กรุณากรอกวันที่ผ่านเช็ค");
            $(id).children('input[name="check_pay_date_pass"]').first().focus();
            return false;
        }else if (check_pay_date_pass <  check_pay_date){
            alert("วันที่ผ่านเช็คต้องมากกว่าหรือเท่ากับ วันที่จ่ายเช็ค");
            $(id).children('input[name="check_pay_date_pass"]').first().focus();
            return false;
        }else{
            return true;
        }
        
    }
</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Check Management</h1>
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
                        เช็คจ่ายที่ยังไม่ผ่านรายการ
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>วันที่จ่ายเช็ค</label>
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
                        <a href="index.php?app=check" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ลำดับ <br>No.</th>
                            <th>วันที่จ่ายเช็ค </th>
                            <th>หมายเลขเช็ค</th>
                            <th>จำนวนเงิน </th>
                            <th>ผู้ขาย</th>
                            <th>วันที่ออกเช็ค</th>
                            <th>หมายเหตุ <br>Remark</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($checks); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $checks[$i]['check_pay_date']; ?></td>
                            <td>
                                <?php echo $checks[$i]['check_pay_code']; ?>
                                <?PHP 
                                if($checks[$i]['check_pay_status'] == '0'){
                                ?>
                                    <font color="red">ยังไม่ผ่านเช็ค</font>
                                <?PHP 
                                }else{
                                ?>
                                    <font color="green">ผ่านเช็ค</font>
                                <?PHP 
                                }
                                ?>    
                            </td>
                            <td><?php echo $checks[$i]['check_pay_total']; ?></td>
                            <td><?php if($checks[$i]['supplier_name_th'] != ""){echo $checks[$i]['supplier_name_th'];}else{echo $checks[$i]['supplier_name_en'];} ?> </td>
                            <td><?php echo $checks[$i]['check_pay_date_write']; ?></td>
                            <td><?php echo $checks[$i]['check_pay_remark']; ?></td>

                            <td>
                                <form role="form" method="post" onsubmit="return check(this,'<?PHP echo $checks[$i]['check_pay_date']; ?>');" action="?app=bank_check_pay_pass&action=pass&id=<?php echo $checks[$i]['check_pay_id'];?>" enctype="multipart/form-data">
                                    <input type="text" name="check_pay_date_pass" class="form-control calendar"  readonly />
                                    <button type="summit" class="btn btn-success" ><i class="fa fa-check" aria-hidden="true"></i> ผ่านเช็ค</button>
                                </form>
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


