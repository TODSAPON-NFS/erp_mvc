<script>

    function check(){
        
        var user_prefix = document.getElementById("user_prefix").value;
        var user_name = document.getElementById("user_name").value;
        var user_lastname = document.getElementById("user_lastname").value;
        var user_mobile = document.getElementById("user_mobile").value;
        var user_email = document.getElementById("user_email").value;
        var user_username = document.getElementById("user_username").value;
        var user_password = document.getElementById("user_password").value;
        var user_address = document.getElementById("user_address").value;
        var user_province = document.getElementById("user_province").value;
        var user_amphur = document.getElementById("user_amphur").value;
        var user_district = document.getElementById("user_district").value;
        var user_zipcode = document.getElementById("user_zipcode").value;
        var user_image = document.getElementById("user_image").value;
        
        user_prefix = $.trim(user_prefix);
        user_name = $.trim(user_name);
        user_lastname = $.trim(user_lastname);
        user_mobile = $.trim(user_mobile);
        user_email = $.trim(user_email);
        user_username = $.trim(user_username);
        user_password = $.trim(user_password);
        user_address = $.trim(user_address);
        user_province = $.trim(user_province);
        user_amphur = $.trim(user_amphur);
        user_district = $.trim(user_district);
        user_zipcode = $.trim(user_zipcode);
        user_image = $.trim(user_image);

        
         if(user_prefix.length == 0){
            alert("Please input  prefix");
            document.getElementById("user_prefix").focus();
            return false;
        }else if(user_name.length == 0){
            alert("Please input  name");
            document.getElementById("user_name").focus();
            return false;
        }else if(user_lastname.length == 0){
            alert("Please input  lastname");
            document.getElementById("user_lastname").focus();
            return false;
        }else if(user_username.length == 0){
            alert("Please input  username");
            document.getElementById("user_username").focus();
            return false;
        }else if(user_password.length == 0){
            alert("Please input  password");
            document.getElementById("user_password").focus();
            return false;
        }else if(user_address.length == 0){
            alert("Please input  address");
            document.getElementById("user_address").focus();
            return false;
        }else if(user_province.length == 0){
            alert("Please input  provice");
            document.getElementById("user_province").focus();
            return false;
        }else if(user_amphur.length == 0){
            alert("Please input  amphur");
            document.getElementById("user_amphur").focus();
            return false;
        }else if(user_district.length == 0){
            alert("Please input  district");
            document.getElementById("user_district").focus();
            return false;
      
        }else{
            var canvas = document.getElementById("signature");
            var dataURL = canvas.toDataURL("image/png");
            document.getElementById('hidden_data').value = dataURL;
            return true;
        }



    }

    function getAmphur(){
        
        var user_province = document.getElementById("user_province").value;
        $.post( "controllers/getAmphur.php", { 'province': user_province }, function( data ) {
            document.getElementById("user_amphur").innerHTML = data;
            $("#user_amphur").selectpicker('refresh');
        });

        
        
    }

    function getDistrict(){
        var user_amphur = document.getElementById("user_amphur").value;
        $.post( "controllers/getDistrict.php", { 'amphur': user_amphur }, function( data ) {
            document.getElementById("user_district").innerHTML = data;
            $("#user_district").selectpicker('refresh');
        });

        $.post( "controllers/getZipcode.php", { 'amphur': user_amphur }, function( data ) {
            document.getElementById("user_zipcode").value = data;
        });
    }


    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img_user').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_user').attr('src', '../upload/employee/default.png');
        }
    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">User Profile</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                แก้ไขข้อมูลส่วนตัว / Edit profile
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=user_profile&action=edit" enctype="multipart/form-data">
                    <input type="hidden"  id="user_id" name="user_id" value="<?php echo $user_id ?>" />
                    <input type="hidden"  id="user_image_o" name="user_image_o" value="<?php echo $user['user_image']; ?>" />    
                        
                    <div class="row center-block">
                        <div class="col-lg-12">
                            <div class="form-group" align="center">
                                <img id="img_user" height="200px" src="../upload/employee/<?php if($user['user_image'] != "" ){echo $user['user_image'];}else{ echo "default.png"; }?>" class="example-avater"> 
                                <input accept=".jpg , .png" type="file" id="user_image" name="user_image" class="form-control" style="margin: 14px 0 0 0 ;" onChange="readURL(this);" value="<?php echo $user['user_image']?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                    
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>รหัสพนักงาน / User Code <font color="#F00"><b>*</b></font></label>
                                <input id="user_code" readonly name="user_code" class="form-control" value="<?php echo $user['user_code']?>" onchange="check_code(this)" />
                                <input id="code_check" type="hidden" value="" />
                                <p class="help-block">Example : 0000001.</p>
                            </div>
                        </div>
                        
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>คำนำหน้าชื่อ / Prename <font color="#F00"><b>*</b></font></label>
                                    <select id="user_prefix" name="user_prefix" class="form-control">
                                        <option value="">Select</option>
                                        <option <?php if($user['user_prefix'] == 'นาย'){?> selected <?php } ?> >นาย</option>
                                        <option <?php if($user['user_prefix'] == 'นาง'){?> selected <?php } ?> >นาง</option>
                                        <option <?php if($user['user_prefix'] == 'นางสาว'){?> selected <?php } ?> >นางสาว</option>
                                    </select>
                                    <p class="help-block">Example : นาย.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>ชื่อ / Name <font color="#F00"><b>*</b></font></label>
                                    <input id="user_name" name="user_name" class="form-control" value="<?php echo $user['user_name']?>">
                                    <p class="help-block">Example : วินัย.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>นามสกุล / Lastname <font color="#F00"><b>*</b></font></label>
                                    <input id="user_lastname" name="user_lastname" class="form-control" value="<?php echo $user['user_lastname']?>">
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
                                <input id="user_email" name="user_email" type="email" class="form-control" value="<?php echo $user['user_email']?>">
                                <p class="help-block">Example : admin@arno.co.th.</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>โทรศัพท์ / Mobile </label>
                                    <input id="user_mobile" name="user_mobile" type="text" class="form-control" value="<?php echo $user['user_mobile']?>">
                                    <p class="help-block">Example : 0610243003.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>ชื่อผู้ใช้ / Username <font color="#F00"><b>*</b></font></label>
                                    <input id="user_username" name="user_username" class="form-control" value="<?php echo $user['user_username']?>">
                                    <p class="help-block">Example : thana.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                                <div class="form-group">
                                    <label>รหัสผ่าน / Password <font color="#F00"><b>*</b></font></label>
                                    <input id="user_password" name="user_password" type="password" class="form-control" value="<?php echo $user['user_password']?>">
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
                                <input type="text" id="user_address" name="user_address" class="form-control" value="<?php echo $user['user_address']?>">
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
                                <select id="user_province" name="user_province" class="form-control" onchange="getAmphur()">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($add_province) ; $i++){
                                    ?>
                                    <option <?php if($user['user_province'] == $add_province[$i]['PROVINCE_NAME'] ){?> selected <?php } ?> value="<?php echo $add_province[$i]['PROVINCE_NAME'] ?>"><?php echo $add_province[$i]['PROVINCE_NAME'] ?></option>
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
                                <select id="user_amphur" name="user_amphur"  class="form-control" onchange="getDistrict()">
                                <option value="">Select</option>
                                <?php 
                                    for($i =  0 ; $i < count($add_amphur) ; $i++){
                                    ?>
                                    <option <?php if($user['user_amphur'] == $add_amphur[$i]['AMPHUR_NAME'] ){?> selected <?php } ?> value="<?php echo $add_amphur[$i]['AMPHUR_NAME'] ?>"><?php echo $add_amphur[$i]['AMPHUR_NAME'] ?></option>
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
                                
                                <select id="user_district" name="user_district" class="form-control">
                                <option value="">Select</option>
                                <?php 
                                    for($i =  0 ; $i < count($add_district) ; $i++){
                                    ?>
                                    <option <?php if($user['user_district'] == $add_district[$i]['DISTRICT_NAME'] ){?> selected <?php } ?> value="<?php echo $add_district[$i]['DISTRICT_NAME'] ?>"><?php echo $add_district[$i]['DISTRICT_NAME'] ?></option>
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
                                <input id="user_zipcode" name="user_zipcode" type="text" readonly class="form-control" value="<?php echo $user['user_zipcode']?>">
                                <p class="help-block">Example : 30000.</p>
                            </div>
                        </div>
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->


                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button  type="submit" class="btn btn-success">Save</button>
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