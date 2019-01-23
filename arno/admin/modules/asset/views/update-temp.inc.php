
<script>

    function check_code(id){
        var code = $(id).val();
        $.post( "controllers/getUserByCode.php", { 'asset_code': code }, function( data ) {  
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
        var asset_prefix = document.getElementById("asset_prefix").value;
        var asset_name = document.getElementById("asset_name").value;
        var asset_lastname = document.getElementById("asset_lastname").value;
        var asset_mobile = document.getElementById("asset_mobile").value;
        var asset_email = document.getElementById("asset_email").value;
        var asset_assetname = document.getElementById("asset_assetname").value;
        var asset_password = document.getElementById("asset_password").value;
        var asset_address = document.getElementById("asset_address").value;
        var asset_province = document.getElementById("asset_province").value;
        var asset_amphur = document.getElementById("asset_amphur").value;
        var asset_district = document.getElementById("asset_district").value;
        var asset_zipcode = document.getElementById("asset_zipcode").value;
        var asset_position_id = document.getElementById("asset_position_id").value;
        var license_id = document.getElementById("license_id").value;
        var asset_status_id = document.getElementById("asset_status_id").value;
        var code_check = document.getElementById("code_check").value;
        var asset_id = document.getElementById("asset_id").value;

        
        asset_code = $.trim(asset_code);
        asset_prefix = $.trim(asset_prefix);
        asset_name = $.trim(asset_name);
        asset_lastname = $.trim(asset_lastname);
        asset_mobile = $.trim(asset_mobile);
        asset_email = $.trim(asset_email);
        asset_assetname = $.trim(asset_assetname);
        asset_password = $.trim(asset_password);
        asset_address = $.trim(asset_address);
        asset_province = $.trim(asset_province);
        asset_amphur = $.trim(asset_amphur);
        asset_district = $.trim(asset_district);
        asset_zipcode = $.trim(asset_zipcode);
        asset_position_id = $.trim(asset_position_id);
        license_id = $.trim(license_id);
        asset_status_id = $.trim(asset_status_id);

        

        if(code_check != "" && code_check != asset_id){
            alert("This "+code_check+" is already in the system.");
            document.getElementById("asset_code").focus();
            return false;
        }else if(asset_code.length == 0){
            alert("Please input asset code");
            document.getElementById("asset_code").focus();
            return false;
        }else if(asset_prefix.length == 0){
            alert("Please input asset prefix");
            document.getElementById("asset_prefix").focus();
            return false;
        }else if(asset_name.length == 0){
            alert("Please input asset name");
            document.getElementById("asset_name").focus();
            return false;
        }else if(asset_lastname.length == 0){
            alert("Please input asset lastname");
            document.getElementById("asset_lastname").focus();
            return false;
        }else if(asset_assetname.length == 0){
            alert("Please input asset assetname");
            document.getElementById("asset_assetname").focus();
            return false;
        }else if(asset_password.length == 0){
            alert("Please input asset password");
            document.getElementById("asset_password").focus();
            return false;
        }else if(asset_address.length == 0){
            alert("Please input asset address");
            document.getElementById("asset_address").focus();
            return false;
        }else if(asset_province.length == 0){
            alert("Please input asset provice");
            document.getElementById("asset_province").focus();
            return false;
        }else if(asset_amphur.length == 0){
            alert("Please input asset amphur");
            document.getElementById("asset_amphur").focus();
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
            var canvas = document.getElementById("signature");
            var dataURL = canvas.toDataURL("image/png");
            document.getElementById('hidden_data').value = dataURL;
            return true;
        }



    }

    function getAmphur(){
        
        var asset_province = document.getElementById("asset_province").value;
        $.post( "controllers/getAmphur.php", { 'province': asset_province }, function( data ) {
            document.getElementById("asset_amphur").innerHTML = data;
            $("#asset_amphur").selectpicker('refresh');
        });

        
        
    }

    function getDistrict(){
        var asset_amphur = document.getElementById("asset_amphur").value;
        $.post( "controllers/getDistrict.php", { 'amphur': asset_amphur }, function( data ) {
            document.getElementById("asset_district").innerHTML = data;
            $("#asset_district").selectpicker('refresh');
        });

        $.post( "controllers/getZipcode.php", { 'amphur': asset_amphur }, function( data ) {
            document.getElementById("asset_zipcode").value = data;
        });
    }



    

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Employee Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=asset" class="btn btn-primary active btn-menu">พนักงาน / Employee</a>
        <a href="?app=asset_license" class="btn btn-primary  btn-menu">สิทธิ์การใช้งาน / License</a>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                แก้ไขพนักงาน / Edit asset 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=asset&action=edit" >
                    <input type="hidden"  id="asset_id" name="asset_id" value="<?php echo $asset_id ?>" />
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>รหัสพนักงาน / Employee Code <font color="#F00"><b>*</b></font></label>
                                <input id="asset_code" name="asset_code" class="form-control" value="<?php echo $asset['asset_code']?>" onchange="check_code(this)" />
                                <input id="code_check" type="hidden" value="" />
                                <p class="help-block">Example : 0000001.</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>คำนำหน้าชื่อ / Prename <font color="#F00"><b>*</b></font></label>
                                    <select id="asset_prefix" name="asset_prefix" class="form-control">
                                        <option value="">Select</option>
                                        <option <?php if($asset['asset_prefix'] == 'นาย'){?> selected <?php } ?> >นาย</option>
                                        <option <?php if($asset['asset_prefix'] == 'นาง'){?> selected <?php } ?> >นาง</option>
                                        <option <?php if($asset['asset_prefix'] == 'นางสาว'){?> selected <?php } ?> >นางสาว</option>
                                    </select>
                                    <p class="help-block">Example : นาย.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>ชื่อ / Name <font color="#F00"><b>*</b></font></label>
                                    <input id="asset_name" name="asset_name" class="form-control" value="<?php echo $asset['asset_name']?>">
                                    <p class="help-block">Example : วินัย.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>นามสกุล / Lastname <font color="#F00"><b>*</b></font></label>
                                    <input id="asset_lastname" name="asset_lastname" class="form-control" value="<?php echo $asset['asset_lastname']?>">
                                    <p class="help-block">Example : ชาญชัย.</p>
                                </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>อีเมล์ / Email </label>
                                <input id="asset_email" name="asset_email" type="email" class="form-control" value="<?php echo $asset['asset_email']?>">
                                <p class="help-block">Example : admin@arno.co.th.</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>โทรศัพท์ / Mobile </label>
                                    <input id="asset_mobile" name="asset_mobile" type="text" class="form-control" value="<?php echo $asset['asset_mobile']?>">
                                    <p class="help-block">Example : 0610243003.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>ยูสเซอร์ / Username <font color="#F00"><b>*</b></font></label>
                                    <input id="asset_assetname" name="asset_assetname" class="form-control" value="<?php echo $asset['asset_assetname']?>">
                                    <p class="help-block">Example : thana.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                                <div class="form-group">
                                    <label>รหัสผ่าน / Password <font color="#F00"><b>*</b></font></label>
                                    <input id="asset_password" name="asset_password" type="password" class="form-control" value="<?php echo $asset['asset_password']?>">
                                    <p class="help-block">Example : thanaadmin.</p>
                                </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font> </label>
                                <input type="text" id="asset_address" name="asset_address" class="form-control" value="<?php echo $asset['asset_address']?>">
                                <p class="help-block">Example : 271/55.</p>
                            </div>
                        </div>
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>จังหวัด / Province <font color="#F00"><b>*</b></font> </label>
                                <select id="asset_province" name="asset_province" class="form-control" onchange="getAmphur()">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($add_province) ; $i++){
                                    ?>
                                    <option <?php if($asset['asset_province'] == $add_province[$i]['PROVINCE_NAME'] ){?> selected <?php } ?> value="<?php echo $add_province[$i]['PROVINCE_NAME'] ?>"><?php echo $add_province[$i]['PROVINCE_NAME'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : นครราชสีมา.</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>อำเภอ / Amphur <font color="#F00"><b>*</b></font> </label>
                                <select id="asset_amphur" name="asset_amphur"  class="form-control" onchange="getDistrict()">
                                <option value="">Select</option>
                                <?php 
                                    for($i =  0 ; $i < count($add_amphur) ; $i++){
                                    ?>
                                    <option <?php if($asset['asset_amphur'] == $add_amphur[$i]['AMPHUR_NAME'] ){?> selected <?php } ?> value="<?php echo $add_amphur[$i]['AMPHUR_NAME'] ?>"><?php echo $add_amphur[$i]['AMPHUR_NAME'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : เมือง.</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ตำบล / Distict <font color="#F00"><b>*</b></font> </label>
                                
                                <select id="asset_district" name="asset_district" class="form-control">
                                <option value="">Select</option>
                                <?php 
                                    for($i =  0 ; $i < count($add_district) ; $i++){
                                    ?>
                                    <option <?php if($asset['asset_district'] == $add_district[$i]['DISTRICT_NAME'] ){?> selected <?php } ?> value="<?php echo $add_district[$i]['DISTRICT_NAME'] ?>"><?php echo $add_district[$i]['DISTRICT_NAME'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : ในเมือง.</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>เลขไปรษณีย์ / Zipcode <font color="#F00"><b>*</b></font> </label>
                                <input id="asset_zipcode" name="asset_zipcode" type="text" readonly class="form-control" value="<?php echo $asset['asset_zipcode']?>">
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
                                    <option <?php if($asset['asset_position_id'] == $asset_position[$i]['asset_position_id'] ){?> selected <?php } ?> value="<?php echo $asset_position[$i]['asset_position_id'] ?>"><?php echo $asset_position[$i]['asset_position_name'] ?></option>
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
                                    <option <?php if($asset['license_id'] == $asset_license[$i]['license_id'] ){?> selected <?php } ?> value="<?php echo $asset_license[$i]['license_id'] ?>"><?php echo $asset_license[$i]['license_name'] ?></option>
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
                                    <option <?php if($asset['asset_status_id'] == $asset_status[$i]['asset_status_id'] ){?> selected <?php } ?> value="<?php echo $asset_status[$i]['asset_status_id'] ?>"><?php echo $asset_status[$i]['asset_status_name'] ?></option>
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
                                    <canvas id="signature" width="280px" height="280px" style="border: 1px solid #ddd;"></canvas>
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