<script>
    function check(){


        var user_code = document.getElementById("user_code").value;
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
        var user_position_id = document.getElementById("user_position_id").value;
        var license_id = document.getElementById("license_id").value;
        var user_status_id = document.getElementById("user_status_id").value;

        
        user_code = $.trim(user_code);
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
        user_position_id = $.trim(user_position_id);
        license_id = $.trim(license_id);
        user_status_id = $.trim(user_status_id);

        

        if(user_code.length == 0){
            alert("Please input employee code");
            document.getElementById("user_code").focus();
            return false;
        }else if(user_prefix.length == 0){
            alert("Please input employee prefix");
            document.getElementById("user_prefix").focus();
            return false;
        }else if(user_name.length == 0){
            alert("Please input employee name");
            document.getElementById("user_name").focus();
            return false;
        }else if(user_lastname.length == 0){
            alert("Please input employee lastname");
            document.getElementById("user_lastname").focus();
            return false;
        }else if(user_username.length == 0){
            alert("Please input employee username");
            document.getElementById("user_username").focus();
            return false;
        }else if(user_password.length == 0){
            alert("Please input employee password");
            document.getElementById("user_password").focus();
            return false;
        }else if(user_address.length == 0){
            alert("Please input employee address");
            document.getElementById("user_address").focus();
            return false;
        }else if(user_province.length == 0){
            alert("Please input employee provice");
            document.getElementById("user_province").focus();
            return false;
        }else if(user_amphur.length == 0){
            alert("Please input employee amphur");
            document.getElementById("user_amphur").focus();
            return false;
        }else if(user_district.length == 0){
            alert("Please input employee district");
            document.getElementById("user_district").focus();
            return false;
        }else if(user_position_id.length == 0){
            alert("Please input employee position");
            document.getElementById("user_position_id").focus();
            return false;
        }else if(license_id.length == 0){
            alert("Please input employee license");
            document.getElementById("license_id").focus();
            return false;
        }else if(user_status_id.length == 0){
            alert("Please input employee status");
            document.getElementById("user_status_id").focus();
            return false;
        }else{
            return true;
        }



    }

    function getAmphur(){
        
        var user_province = document.getElementById("user_province").value;
        $.post( "controllers/getAmphur.php", { 'province': user_province }, function( data ) {
            $("#user_amphur").html(data);
            $("#user_amphur").selectpicker();

        });

        
        
    }

    function getDistrict(){
        var user_amphur = document.getElementById("user_amphur").value;
        $.post( "controllers/getDistrict.php", { 'amphur': user_amphur }, function( data ) {
            $("#user_district").html(data);
            $("#user_district").selectpicker();
        });

        $.post( "controllers/getZipcode.php", { 'amphur': user_amphur }, function( data ) {
            $("#user_zipcode").val(data);
        });
    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Employee Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=employee" class="btn btn-primary active btn-menu">Employee</a>
        <a href="?app=employee_license" class="btn btn-primary  btn-menu">License</a>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Add employee 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=employee&action=add" >
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Employee Code <font color="#F00"><b>*</b></font></label>
                                <input id="user_code" name="user_code" class="form-control">
                                <p class="help-block">Example : 0000001.</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>Prename <font color="#F00"><b>*</b></font></label>
                                    <select id="user_prefix" name="user_prefix" class="form-control">
                                        <option value="">Select</option>
                                        <option>นาย</option>
                                        <option>นาง</option>
                                        <option>นางสาว</option>
                                    </select>
                                    <p class="help-block">Example : นาย.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>Name <font color="#F00"><b>*</b></font></label>
                                    <input id="user_name" name="user_name" class="form-control">
                                    <p class="help-block">Example : วินัย.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>Lastname <font color="#F00"><b>*</b></font></label>
                                    <input id="user_lastname" name="user_lastname" class="form-control">
                                    <p class="help-block">Example : ชาญชัย.</p>
                                </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Email </label>
                                <input id="user_email" name="user_email" type="email" class="form-control">
                                <p class="help-block">Example : admin@arno.co.th.</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>Mobile </label>
                                    <input id="user_mobile" name="user_mobile" type="text" class="form-control">
                                    <p class="help-block">Example : 0610243003.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>Username <font color="#F00"><b>*</b></font></label>
                                    <input id="user_username" name="user_username" class="form-control">
                                    <p class="help-block">Example : thana.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Password <font color="#F00"><b>*</b></font></label>
                                    <input id="user_password" name="user_password" type="password" class="form-control">
                                    <p class="help-block">Example : thanaadmin.</p>
                                </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Address <font color="#F00"><b>*</b></font> </label>
                                <input type="text" id="user_address" name="user_address" class="form-control">
                                <p class="help-block">Example : 271/55.</p>
                            </div>
                        </div>
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Province <font color="#F00"><b>*</b></font> </label>
                                <select id="user_province" name="user_province" data-live-search="true" class="form-control" onchange="getAmphur()">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($add_province) ; $i++){
                                    ?>
                                    <option value="<?php echo $add_province[$i]['PROVINCE_NAME'] ?>"><?php echo $add_province[$i]['PROVINCE_NAME'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : นครราชสีมา.</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Amphur <font color="#F00"><b>*</b></font> </label>
                                <select id="user_amphur" name="user_amphur" data-live-search="true"  class="form-control" onchange="getDistrict()">
                                <option value="">Select</option>
                                </select>
                                <p class="help-block">Example : เมือง.</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Distict <font color="#F00"><b>*</b></font> </label>
                                <select id="user_district" name="user_district" data-live-search="true" class="form-control">
                                <option value="">Select</option>
                                </select>
                                <p class="help-block">Example : ในเมือง.</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Zipcode <font color="#F00"><b>*</b></font> </label>
                                <input id="user_zipcode" name="user_zipcode" type="text" readonly class="form-control">
                                <p class="help-block">Example : 30000.</p>
                            </div>
                        </div>
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->


                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Position <font color="#F00"><b>*</b></font> </label>
                                <select class="form-control" id="user_position_id" name="user_position_id">
                                <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($user_position) ; $i++){
                                    ?>
                                    <option value="<?php echo $user_position[$i]['user_position_id'] ?>"><?php echo $user_position[$i]['user_position_name'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : ผู้ดูแลระบบ.</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>License <font color="#F00"><b>*</b></font> </label>
                                <select class="form-control" id="license_id" name="license_id">
                                <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($user_license) ; $i++){
                                    ?>
                                    <option value="<?php echo $user_license[$i]['license_id'] ?>"><?php echo $user_license[$i]['license_name'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : สิทธิ์การใช้งานที่ 1 .</p>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Status <font color="#F00"><b>*</b></font> </label>
                                <select class="form-control" id="user_status_id" name="user_status_id">
                                <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($user_status) ; $i++){
                                    ?>
                                    <option value="<?php echo $user_status[$i]['user_status_id'] ?>"><?php echo $user_status[$i]['user_status_name'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : ทำงาน.</p>
                            </div>
                        </div>
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button type="submit" class="btn btn-success">Save</button>
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