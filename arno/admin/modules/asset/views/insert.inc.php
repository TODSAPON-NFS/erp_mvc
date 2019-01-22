<script>

    function check_code(id){
        var code = $(id).val();
        $.post( "controllers/getAssetByCode.php", { 'asset_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("asset_code").focus();
                $("#code_check").val(data.asset_code);
                
            } else{
                $("#code_check").val("");
            }
        });
    }

    function check(){


        var asset_code = document.getElementById("asset_code").value;
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
            alert("This "+code_check+" is already in the system.");
            document.getElementById("code_check").focus();
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
            alert("Please input asset lastname");
            document.getElementById("asset_buy_date").focus();
            return false;
        }else if(asset_use_date.length == 0){
            alert("Please input asset assetname");
            document.getElementById("asset_use_date").focus();
            return false;
        }else if(asset_scrap_price.length == 0){
            alert("Please input asset password");
            document.getElementById("asset_scrap_price").focus();
            return false;
        }else if(asset_expire.length == 0){
            alert("Please input asset address");
            document.getElementById("asset_expire").focus();
            return false;
        }else if(asset_rate.length == 0){
            alert("Please input asset provice");
            document.getElementById("asset_rate").focus();
            return false;
        }else if(asset_depreciate.length == 0){
            alert("Please input asset amphur");
            document.getElementById("asset_depreciate").focus();
            return false;
        }else if(asset_district.length == 0){
            alert("Please input asset district");
            document.getElementById("asset_district").focus();
            return false;
        }else if(asset_position_id.length == 0){
            alert("Please input asset position");
            document.getElementById("asset_position_id").focus();
            return false;
        }else if(license_id.length == 0){
            alert("Please input asset license");
            document.getElementById("license_id").focus();
            return false;
        }else if(asset_status_id.length == 0){
            alert("Please input asset status");
            document.getElementById("asset_status_id").focus();
            return false;
        }else{
            // var canvas = document.getElementById("signature");
            // var dataURL = canvas.toDataURL("image/png");
            // document.getElementById('hidden_data').value = dataURL;
            return true;
        }



    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Asset Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=asset" class="btn btn-primary active btn-menu">ทรัพย์สิน / Asset</a>
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
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>รหัสทรัพย์สิน / Asset Code <font color="#F00"><b>*</b></font></label>
                                <input id="asset_code" name="asset_code" class="form-control" onchange="check_code(this)" />
                                <input id="code_check" type="hidden" value="" />
                                <p class="help-block">Example : 0000001.</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>ชื่อทรัพย์สิน TH/ Name TH<font color="#F00"><b>*</b></font></label>
                                    <input id="asset_name_th" name="asset_name_th" class="form-control">
                                    <p class="help-block">Example : คอมพิวเตอร์.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>ชื่อทรัพย์สิน EN/  Name EN <font color="#F00"><b>*</b></font></label>
                                    <input id="asset_name_en" name="asset_name_en" class="form-control">
                                    <p class="help-block">Example : Computer.</p>
                                </div>
                        </div>
                        <div class="col-lg-3">
                            
                            <div class="form-group">
                                <label>หมวดหมู่ทรัพย์สิน <font color="#F00"><b>*</b></font></label>
                                <select id="asset_category_id" name="asset_category_id" class="form-control">
                                    <option value="">Select</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                </select>
                                <p class="help-block">Example : คอมพิวเตอร์และอุปกรณ์สำนักงาน.</p>
                            </div>
                        
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-3">
                            
                            <div class="form-group">
                                <label>กลุ่มบัญชีทรัพย์สิน <font color="#F00"><b>*</b></font></label>
                                <select id="asset_account_group_id" name="asset_account_group_id" class="form-control">
                                    <option value="">Select</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
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
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
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
                                <label>คิดค่าเสื่อม <font color="#F00"><b>*</b></font> </label>
                                <input type="checkbox" id="asset_depreciate" name="asset_depreciate" class="form-control">
                                <p class="help-block">Example : checked.</p>
                            </div>
                        </div>                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>วันที่ซื้อ / PO Date</label>
                                <input type="text" id="customer_purchase_order_date" name="customer_purchase_order_date" value="<? echo $customer_purchase_order['customer_purchase_order_date'];?>"  class="form-control calendar" readonly/>
                                <p class="help-block">Example : 31-01-2018</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>อำเภอ / Amphur <font color="#F00"><b>*</b></font> </label>
                                <select id="asset_amphur" name="asset_amphur" data-live-search="true"  class="form-control" onchange="getDistrict()">
                                <option value="">Select</option>
                                </select>
                                <p class="help-block">Example : เมือง.</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ตำบล / Distict <font color="#F00"><b>*</b></font> </label>
                                <select id="asset_district" name="asset_district" data-live-search="true" class="form-control">
                                <option value="">Select</option>
                                </select>
                                <p class="help-block">Example : ในเมือง.</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>เลขไปรษณีย์ / Zipcode <font color="#F00"><b>*</b></font> </label>
                                <input id="asset_zipcode" name="asset_zipcode" type="text" readonly class="form-control">
                                <p class="help-block">Example : 30000.</p>
                            </div>
                        </div>
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->


                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ตำแหน่ง / Position <font color="#F00"><b>*</b></font> </label>
                                <select class="form-control" id="asset_position_id" name="asset_position_id">
                                <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($asset_position) ; $i++){
                                    ?>
                                    <option value="<?php echo $asset_position[$i]['asset_position_id'] ?>"><?php echo $asset_position[$i]['asset_position_name'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : ผู้ดูแลระบบ.</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>สิทธิ์การใช้งาน / License <font color="#F00"><b>*</b></font> </label>
                                <select class="form-control" id="license_id" name="license_id">
                                <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($asset_license) ; $i++){
                                    ?>
                                    <option value="<?php echo $asset_license[$i]['license_id'] ?>"><?php echo $asset_license[$i]['license_name'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : สิทธิ์การใช้งานที่ 1 .</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>สถานะ / Status <font color="#F00"><b>*</b></font> </label>
                                <select class="form-control" id="asset_status_id" name="asset_status_id">
                                <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($asset_status) ; $i++){
                                    ?>
                                    <option value="<?php echo $asset_status[$i]['asset_status_id'] ?>"><?php echo $asset_status[$i]['asset_status_name'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : ทำงาน.</p>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>ลายเซ็น / Signature <font color="#F00"><b>*</b></font> </label>
                                <div align="center">
                                    <input name="hidden_data" id='hidden_data' type="hidden"/>
                                    <canvas id="signature" width="280px" height="280px"  style="border: 1px solid #ddd;"></canvas>
                                    <br>
                                    <a class="btn btn-default" id="clear-signature" >Clear</a>
                                </div>
                                <p class="help-block">Example : ทำงาน.</p>
                            </div>
                        </div>
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->
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