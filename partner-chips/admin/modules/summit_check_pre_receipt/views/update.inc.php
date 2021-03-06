<script>

    
    function check(){


        var check_code = document.getElementById("check_code").value;
        var customer_id = document.getElementById("customer_id").value;
        
        check_code = $.trim(check_code);
        customer_id = $.trim(customer_id);
        

        if(customer_id.length == 0){
            alert("Please input Customer");
            document.getElementById("customer_id").focus();
            return false;
        }else if(check_code.length == 0){
            alert("Please input delivery note Customer code");
            document.getElementById("check_code").focus();
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
            แก้ไขเช็ครับล่วงหน้ายกยอดมา /  Edit Check   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=summit_check_pre_receipt&action=edit&id=<?PHP echo  $check_id; ?>" enctype="multipart/form-data">
                <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>วันที่เช็ค</label>
                                        <input id="check_date_write" name="check_date_write" class="form-control calendar" type="text" value="<?PHP echo $check['check_date_write']; ?>" readonly />
                                        <p class="help-block">01-06-2018 </p>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>เลขที่เช็ค <font color="#F00"><b>*</b></font></label>
                                        <input id="check_code" name="check_code" class="form-control" type="text" value="<?PHP echo $check['check_code']; ?>" readonly />
                                        <p class="help-block">Example : QR4411555.</p>
                                    </div>
                                </div>


                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>ผู้สั่งจ่าย <font color="#F00"><b>*</b></font> </label>
                                        <select id="customer_id" name="customer_id" class="form-control select"  data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($customers) ; $i++){
                                            ?>
                                            <option <?PHP if($customers[$i]['customer_id'] == $check['customer_id']){?> SELECTED <?PHP } ?>  value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?>  </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
    
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>ธนาคาร <font color="#F00"><b>*</b></font> </label>
                                        <select id="bank_id" name="bank_id" class="form-control select" data-live-search="true">
                                            <option  value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($banks) ; $i++){
                                            ?>
                                            <option <?PHP if($banks[$i]['bank_id'] == $check['bank_id']){?> SELECTED <?PHP } ?> value="<?php echo $banks[$i]['bank_id'] ?>"><?php echo $banks[$i]['bank_name'] ?> </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : BKK.</p>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>สาขา</label>
                                        <input type="text" id="bank_branch" name="bank_branch" value="<?PHP echo $check['bank_branch']; ?>"  class="form-control" />
                                        <p class="help-block">- </p>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>วันรับที่เช็ค</label>
                                        <input id="check_date_recieve" name="check_date_recieve" value="<?PHP echo $check['check_date_recieve']; ?>" class="form-control calendar"  readonly>
                                        <p class="help-block">01-06-2018 </p>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>จำนวนเงิน</label>
                                        <input id="check_total" name="check_total" class="form-control " value="<?PHP echo $check['check_total']; ?>" >
                                        <p class="help-block">80000 </p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>หมายเหตุุ</label>
                                        <input id="check_remark" name="check_remark" class="form-control" type="text" value="<?PHP echo $check['check_remark']; ?>" />
                                        <p class="help-block">- </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <hr>
                    
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=summit_check_pre_receipt" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button  type="button" onclick="check_login('form_target');" class="btn btn-success">Save</button>
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