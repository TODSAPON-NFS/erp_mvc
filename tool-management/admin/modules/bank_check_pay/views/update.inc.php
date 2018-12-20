<script>

    
    function check(){


        var check_pay_code = document.getElementById("check_pay_code").value;
        var supplier_id = document.getElementById("supplier_id").value;
        
        check_pay_code = $.trim(check_pay_code);
        supplier_id = $.trim(supplier_id);
        

        /*if(supplier_id.length == 0){
            alert("Please input supplier");
            document.getElementById("supplier_id").focus();
            return false;
        } else*/ if(check_pay_code.length == 0){
            alert("Please input delivery note supplier code");
            document.getElementById("check_pay_code").focus();
            return false;
        }
        
        else{
            return true;
        }

    }

    function format_number(id){
        var val =  parseFloat($(id).val(  ).replace(',','')); 

        if(isNaN(val)){
            val = 0;
        }  
        $(id).val( val.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

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
            แก้ไขเช็คจ่าย /  Edit Check  Pay
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=bank_check_pay&action=edit&id=<?PHP echo  $check_pay_id; ?>" enctype="multipart/form-data">
                <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>วันที่เช็ค</label>
                                        <input id="check_pay_date_write" name="check_pay_date_write" class="form-control calendar" type="text" value="<?PHP echo $check['check_pay_date_write']; ?>" readonly />
                                        <p class="help-block">01-06-2018 </p>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>เลขที่เช็ค <font color="#F00"><b>*</b></font></label>
                                        <input id="check_pay_code" name="check_pay_code" class="form-control" type="text" value="<?PHP echo $check['check_pay_code']; ?>" readonly />
                                        <p class="help-block">Example : QR4411555.</p>
                                    </div>
                                </div>


                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>ผู้ขาย <font color="#F00"><b>*</b></font> </label>
                                        <select id="supplier_id" name="supplier_id" class="form-control select"  data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($suppliers) ; $i++){
                                            ?>
                                            <option <?PHP if($suppliers[$i]['supplier_id'] == $check['supplier_id']){?> SELECTED <?PHP } ?>  value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
    
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>จ่ายจากบัญชี <font color="#F00"><b>*</b></font> </label>
                                        <select id="bank_account_id" name="bank_account_id" class="form-control select" data-live-search="true">
                                            <option  value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($accounts) ; $i++){
                                            ?>
                                            <option <?PHP if($accounts[$i]['bank_account_id'] == $check['bank_account_id']){?> SELECTED <?PHP } ?> value="<?php echo $accounts[$i]['bank_account_id'] ?>"><?php echo $accounts[$i]['bank_account_name'] ?> </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : BKK.</p>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>วันจ่ายที่เช็ค</label>
                                        <input id="check_pay_date" name="check_pay_date" value="<?PHP echo $check['check_pay_date']; ?>" class="form-control calendar"  readonly>
                                        <p class="help-block">01-06-2018 </p>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>จำนวนเงิน</label>
                                        <input id="check_pay_total" name="check_pay_total" class="form-control " onchange="format_number(this);" style="text-align:right;" value="<?PHP echo number_format($check['check_pay_total'],2); ?>" >
                                        <p class="help-block">80000 </p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>หมายเหตุุ</label>
                                        <input id="check_pay_remark" name="check_pay_remark" class="form-control" type="text" value="<?PHP echo $check['check_pay_remark']; ?>" />
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
                            <a href="index.php?app=bank_check_pay" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button type="button" onclick="check_login('form_target');" class="btn btn-success">Save</button>
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