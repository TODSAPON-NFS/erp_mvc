<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=bank_check_pass&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword;
    }

    function update_deposit(id,check_id,check_date){
        var bank = $(id).closest('tr').children('td').children('div').children('select[name="bank_deposit_id"]').val();
        var check_fee = $(id).closest('tr').children('td').children('input[name="check_fee"]').val();
        var date = $(id).closest('tr').children('td').children('input[name="check_date_deposit"]').val();

        var str_pass = date.split("-");
        var str_pay = check_date.split("-");

        var check_date_deposit = new Date (str_pass[1]+'-'+str_pass[0]+'-'+str_pass[2]);
        var check_date = new Date(str_pay[1]+'-'+str_pay[0]+'-'+str_pay[2]);

        if(bank == ""){
            alert("กรุณากรอกธนาคารที่นำฝาก");
            $(id).closest('tr').children('td').children('div').children('input[name="bank_deposit_id"]').first().focus();
            return false;
        }else if(date == ""){
            alert("กรุณากรอกวันที่นำฝากเช็ค");
            $(id).closest('tr').children('td').children('input[name="check_date_deposit"]').first().focus();
            return false;
        }else if (check_date_deposit <  check_date){
            alert("วันที่นำฝากเช็คต้องมากกว่าหรือเท่ากับ วันที่ออกเช็ค");
            $(id).closest('tr').children('td').children('input[name="check_date_deposit"]').first().focus();
            return false;
        }else{
            window.location="?app=bank_check_in_deposit&action=deposit&id="+check_id+"&bank_deposit_id="+bank+"&check_fee="+check_fee+"&check_date_deposit="+date;
        }
        
    }

    function clear_deposit(id,check_id){
        window.location="?app=bank_check_in_deposit&action=undeposit&id="+check_id;
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
                        เช็ครับที่ยังไม่ผ่านรายการ
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>วันที่รับเช็ค</label>
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
                            <label>ผู้สั่งจ่าย </label>
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
                        <a href="index.php?app=check" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th>ลำดับ </th>
                            <th>วันที่รับเช็ค </th>
                            <th>หมายเลขเช็ค</th>
                            <th>ผู้สั่งจ่าย</th>
                            <th>วันที่ออกเช็ค</th>
                            <th>ธนาคารที่นำฝาก </th>
                            <th>วันที่ที่นำฝาก </th>
                            <th>จำนวนเงิน </th>
                            <th>ค่าธรรมเนียม </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($checks); $i++){
                        ?>
                               
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $checks[$i]['check_date_recieve']; ?></td>
                            <td>
                                <?php echo $checks[$i]['check_code']; ?>
                                <?PHP
                                if($checks[$i]['check_date_deposit'] == ""){
                                ?>
                                    <font color="red">ยังไม่ฝากเช็คเข้าบัญชี</font>
                                <?PHP 
                                } else if($checks[$i]['check_status'] == '0'){
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
                            <td><?php echo $checks[$i]['customer_name_en']; ?> </td>
                            <td><?php echo $checks[$i]['check_date_write']; ?></td>
                            <td>
                            <?PHP  if($checks[$i]['check_date_deposit'] == ""){  ?>
                                <select id="bank_deposit_id" name="bank_deposit_id" class="form-control select" data-live-search="true">
                                    <option value="">Select</option>
                                    <?php 
                                    for($ii =  0 ; $ii < count($accounts) ; $ii++){
                                    ?>
                                    <option value="<?php echo $accounts[$ii]['bank_account_id'] ?>" <?PHP if( $checks[$i]['bank_deposit_id'] == $accounts[$ii]['bank_account_id']){ ?> SELECTED <?PHP } ?>><?php echo $accounts[$ii]['bank_account_name'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                            <?PHP } else { 
                                for($ii =  0 ; $ii < count($accounts) ; $ii++){
                                    if( $checks[$i]['bank_deposit_id'] == $accounts[$ii]['bank_account_id']){
                                        echo $accounts[$ii]['bank_account_name'];
                                    }  
                                }
                                    ?>
                            <?PHP } ?> 
                            </td>
                            <td> 
                            <?PHP  if($checks[$i]['check_date_deposit'] == ""){  ?>
                                <input type="text" name="check_date_deposit" class="form-control calendar" style="width:120px;" value="<?PHP echo  $checks[$i]['check_date_deposit']; ?>" readonly />
                            <?PHP } else { ?>
                                <?PHP echo  $checks[$i]['check_date_deposit']; ?>
                            <?PHP } ?> 
                            </td> 
                            <td align="right"><?php echo number_format($checks[$i]['check_total'],2); ?></td>

                            <td>
                            <?PHP  if($checks[$i]['check_date_deposit'] == ""){  ?>
                                <input type="text" name="check_fee" class="form-control" style="width:80px;" value="<?PHP echo  $checks[$i]['check_fee']; ?>"   />
                            <?PHP } else { ?>
                                <?PHP echo  $checks[$i]['check_fee']; ?>
                            <?PHP } ?> 
                            </td> 
                            
                            <td>
                            <?PHP  if($checks[$i]['check_date_deposit'] == ""){  ?>
                                <button type="button" class="btn btn-success" onclick="update_deposit(this,'<?PHP echo $checks[$i]['check_id']; ?>','<?PHP echo $checks[$i]['check_date_write']; ?>');" ><i class="fa fa-check" aria-hidden="true"></i> นำฝากเช็ค</button> 
                            <?PHP } else { ?>
                                <button type="button" class="btn btn-danger" onclick="clear_deposit(this,'<?PHP echo $checks[$i]['check_id']; ?>');" ><i class="fa fa-times" aria-hidden="true"></i> ยกเลิก</button> 
                            <?PHP } ?> 
                            </td>

                        </tr>
                        </form>
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


