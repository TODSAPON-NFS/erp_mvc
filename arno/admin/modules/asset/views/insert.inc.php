<script>

    function check_code(id){
        var code = $(id).val();
        console.log('code:',code);
        $.post( "controllers/getAssetByCode.php", { 'asset_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("asset_code").focus();
                $("#code_check").val(data.asset_code);
                
            } else{
                // alert("This "+code+" is available.");
                console.log("This "+code+" is available");
                $("#code_check").val("");
            }
        });
    }

    function check(){


        var asset_code = document.getElementById("asset_code").value;
        var code_check = document.getElementById("code_check").value;
        var asset_name_th = document.getElementById("asset_name_th").value;
        var asset_name_en = document.getElementById("asset_name_en").value;
        var asset_category_id = document.getElementById("asset_category_id").value;
        var asset_registration_no = document.getElementById("asset_registration_no").value;
        var asset_department_id = document.getElementById("asset_department_id").value;
        var asset_depreciate = document.getElementById("asset_depreciate").value;
        var asset_buy_date = document.getElementById("asset_buy_date").value;
        var asset_use_date = document.getElementById("asset_use_date").value;
        var asset_cost_price = document.getElementById("asset_cost_price").value;
        var asset_scrap_price = document.getElementById("asset_scrap_price").value;
        var asset_expire = document.getElementById("asset_expire").value;
        var asset_rate = document.getElementById("asset_rate").value;
        var asset_depreciate_type = document.getElementById("asset_depreciate_type").value;
        var asset_depreciate_transfer = document.getElementById("asset_depreciate_transfer").value;
        var asset_depreciate_manual = document.getElementById("asset_depreciate_manual").value;
        var asset_depreciate_initial = document.getElementById("asset_depreciate_initial").value;
        var asset_manual_date = document.getElementById("asset_manual_date").value;
        var asset_sale_date = document.getElementById("asset_sale_date").value;
        var asset_price = document.getElementById("asset_price").value;
        var asset_income = document.getElementById("asset_income").value;
        
        asset_code = $.trim(asset_code);
        asset_name_th = $.trim(asset_name_th);
        asset_name_en = $.trim(asset_name_en);
        asset_category_id = $.trim(asset_category_id);
        asset_registration_no = $.trim(asset_registration_no);
        asset_department_id = $.trim(asset_department_id);
        asset_depreciate = $.trim(asset_depreciate);
        asset_buy_date = $.trim(asset_buy_date);
        asset_use_date = $.trim(asset_use_date);
        asset_cost_price = $.trim(asset_cost_price);
        asset_scrap_price = $.trim(asset_scrap_price);
        asset_expire = $.trim(asset_expire);
        asset_rate = $.trim(asset_rate);
        asset_depreciate_type = $.trim(asset_depreciate_type);
        asset_depreciate_transfer = $.trim(asset_depreciate_transfer);
        asset_depreciate_manual = $.trim(asset_depreciate_manual);
        asset_depreciate_initial = $.trim(asset_depreciate_initial);
        asset_manual_date = $.trim(asset_manual_date);
        asset_sale_date = $.trim(asset_sale_date);
        asset_price = $.trim(asset_price);
        asset_income = $.trim(asset_income);

        

        if(code_check != ""){
            alert("This "+asset_code+" is already in the system.");
            document.getElementById("asset_code").focus();
            return false;
        }else if(asset_code.length == 0){
            alert("Please input asset code");
            document.getElementById("asset_code").focus();
            return false;
        }else if(asset_name_th.length == 0){
            alert("Please input asset name th");
            document.getElementById("asset_name_th").focus();
            return false;
        }else if(asset_name_en.length == 0){
            alert("Please input asset name en ");
            document.getElementById("asset_name_en").focus();
            return false;
        }else if(asset_buy_date.length == 0){
            alert("Please input asset buy date");
            document.getElementById("asset_buy_date").focus();
            return false;
        }else if(asset_use_date.length == 0){
            alert("Please input asset use date");
            document.getElementById("asset_use_date").focus();
            return false;
        }else if(asset_scrap_price.length == 0){
            alert("Please input asset scrap price");
            document.getElementById("asset_scrap_price").focus();
            return false;
        }else if(asset_expire.length == 0){
            alert("Please input asset expire");
            document.getElementById("asset_expire").focus();
            return false;
        }else if(asset_rate.length == 0){
            alert("Please input asset rate");
            document.getElementById("asset_rate").focus();
            return false;
        }
        else{
            return true;
        }



    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Asset Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=asset" class="btn btn-primary active btn-menu">รายการทรัพย์สิน / Asset</a>
        <!-- <a href="?app=asset_license" class="btn btn-primary  btn-menu">สิทธิ์การใช้งาน / License</a> -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            เพิ่มทรัพย์สิน / Add asset 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=asset&action=add" >
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>รหัสทรัพย์สิน / Asset Code <font color="#F00"><b>*</b></font></label>
                                <input id="asset_code" name="asset_code" class="form-control" onchange="check_code(this)" />
                                <input id="code_check" type="hidden" value="" />
                                <p class="help-block">Example : 0000001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            
                                <div class="form-group">
                                    <label>ชื่อทรัพย์สิน TH/ Name TH<font color="#F00"><b>*</b></font></label>
                                    <input id="asset_name_th" name="asset_name_th" class="form-control">
                                    <p class="help-block">Example : คอมพิวเตอร์.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-4">
                            
                                <div class="form-group">
                                    <label>ชื่อทรัพย์สิน EN/  Name EN <font color="#F00"><b>*</b></font></label>
                                    <input id="asset_name_en" name="asset_name_en" class="form-control">
                                    <p class="help-block">Example : Computer.</p>
                                </div>
                        </div>
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-3">
                            
                            <div class="form-group">
                                <label>หมวดหมู่ทรัพย์สิน <font color="#F00"><b>*</b></font></label>
                                <select id="asset_category_id" name="asset_category_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php for($i=0;$i<count($category);$i++){?>
                                        <option value="<?php echo $category[$i]['asset_category_id'];?>"><?php echo $category[$i]['asset_category_name_th'];?></option>
                                    <?php }?>
                                </select>
                                <p class="help-block">Example : คอมพิวเตอร์และอุปกรณ์สำนักงาน.</p>
                            </div>
                        
                        </div>
                        <div class="col-lg-3">
                            
                            <div class="form-group">
                                <label>กลุ่มบัญชีทรัพย์สิน <font color="#F00"><b>*</b></font></label>
                                <select id="asset_account_group_id" name="asset_account_group_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php for($i=0;$i<count($account_group);$i++){?>
                                        <option value="<?php echo $account_group[$i]['asset_account_group_id'];?>"><?php echo $account_group[$i]['asset_account_group_name_th'];?></option>
                                    <?php }?>
                                </select>
                                <p class="help-block">Example : อุปกรณ์สำนักงาน.</p>
                            </div>
                        
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>เลขทะเบียน  </label>
                                    <input id="asset_registration_no" name="asset_registration_no" type="text" class="form-control">
                                    <p class="help-block">Example : 0610243003.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                            <div class="form-group">
                                <label>แผนก <font color="#F00"><b>*</b></font></label>
                                <select id="asset_department_id" name="asset_department_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php for($i=0;$i<count($department);$i++){?>
                                        <option value="<?php echo $department[$i]['asset_department_id'];?>"><?php echo $department[$i]['asset_department_name_th'];?></option>
                                    <?php }?>
                                </select>
                                <p class="help-block">Example : บัญชี.</p>
                            </div>
                        
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-3">
                            
                            <div class="form-group">
                                <label>ตัวเลือกการคิดค่าเสื่อมทรัพย์สิน <font color="#F00"><b>*</b></font></label>
                                <select id="asset_depreciate" name="asset_depreciate" class="form-control">
                                    <option value="">Select</option>
                                    <option value="0">ไม่คิดค่าเสื่อม</option>
                                    <option value="1">คิดค่าเสื่อม</option>
                                </select>
                                <p class="help-block">Example : คิดค่าเสื่อม.</p>
                            </div>
                        
                        </div>
                        <div class="col-lg-3">
                            
                            <div class="form-group">
                                <label>การคำนวณค่าเสื่อมทรัพย์สิน <font color="#F00"><b>*</b></font></label>
                                <select id="asset_depreciate_type" name="asset_depreciate_type" class="form-control">
                                    <option value="">Select</option>
                                    <option value="0">รายเดือน</option>
                                    <option value="1">รายปี</option>
                                </select>
                                <p class="help-block">Example :รายเดือน/รายปี.</p>
                            </div>
                        
                        </div> 
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>วันที่ซื้อ</label>
                                <input type="text" id="asset_buy_date" name="asset_buy_date"  class="form-control calendar" />
                                <p class="help-block">Example : 31-01-2019</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>วันที่ใช้</label>
                                <input type="text" id="asset_use_date" name="asset_use_date"  class="form-control calendar" />
                                <p class="help-block">Example : 31-01-2019</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>ราคาทุน<font color="#F00"><b>*</b></font></label>
                                    <input id="asset_cost_price" name="asset_cost_price" class="form-control">
                                    <p class="help-block">Example : 25000.</p>
                                </div>
                            
                        </div>

                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>ราคาซาก<font color="#F00"><b>*</b></font></label>
                                    <input id="asset_scrap_price" name="asset_scrap_price" class="form-control">
                                    <p class="help-block">Example : 25000.</p>
                                </div>
                            
                        </div>
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->


                    <div class="row">
                        <div class="col-lg-3">
                                
                                <div class="form-group">
                                    <label>อายุการใช้<font color="#F00"><b>*</b></font></label>
                                    <input id="asset_expire" name="asset_expire" class="form-control">
                                    <p class="help-block">Example : 25000.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>อัตรา%<font color="#F00"><b>*</b></font></label>
                                    <input id="asset_rate" name="asset_rate" class="form-control">
                                    <p class="help-block">Example : 20.</p>
                                </div>
                            
                        </div>                        
                    </div>
                    <!-- /.row (nested) -->
                    <div class="row">

                            
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>ค่าเสื่อมสะสมยกมา<font color="#F00"><b>*</b></font></label>
                                    <input id="asset_depreciate_transfer" name="asset_depreciate_transfer" class="form-control">
                                    <p class="help-block">Example : 0.</p>
                                </div>
                            
                        </div> 
                        <div class="col-lg-3">
                            
                            <div class="form-group">
                                <label>ค่าเสื่อมที่คำนวณเอง<font color="#F00"><b>*</b></font></label>
                                <input id="asset_depreciate_manual" name="asset_depreciate_manual" class="form-control">
                                <p class="help-block">Example : 0.</p>
                            </div>
                            
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>คำนวณเองถึงวันที่</label>
                                <input type="text" id="asset_manual_date" name="asset_manual_date"  class="form-control calendar" />
                                <p class="help-block">Example : 31-01-2019</p>
                            </div>
                        </div>                    
                    </div>
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>วันที่ขาย</label>
                                <input type="text" id="asset_sale_date" name="asset_sale_date"  class="form-control calendar" />
                                <p class="help-block">Example : 31-01-2019</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ราคาขาย<font color="#F00"><b>*</b></font></label>
                                <input id="asset_price" name="asset_price" class="form-control">
                                <p class="help-block">Example : 0.</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>กำไร/ขาดทุน<font color="#F00"><b>*</b></font></label>
                                <input id="asset_income" name="asset_income" class="form-control">
                                <p class="help-block">Example : 0.</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ค่าเสื่อมเบื้องต้น<font color="#F00"><b>*</b></font></label>
                                <input id="asset_depreciate_initial" name="asset_depreciate_initial" class="form-control">
                                <p class="help-block">Example : 0.</p>
                            </div>
                        </div>
                           
                    </div>

                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=asset" class="btn btn-default">Back</a>
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
<script>

$(document).ready(function($){
		$('#asset_expire').on('change keyup', function()
		{
			console.log(this.value);
            var val = parseFloat(this.value);
            if(!isNaN(val)){
                var rate = 100 / val;
                $("#asset_rate").val(rate); 
            }
            else{
                $("#asset_rate").val("");
            }	      
             
	    });
	});

</script>
